<?php

class Pronamic_Events_RepeatEventHelper {
	/**
	 * The event
	 *
	 * @var Pronamic_WP_Event
	 */
	private $event;

	//////////////////////////////////////////////////

	/**
	 * Construct and intialize an repeat event helper
	 *
	 * @param Pronamic_WP_Event $event
	 */
	public function __construct( Pronamic_WP_Event $event ) {
		$this->event = $event;
	}

	//////////////////////////////////////////////////

	public function is_repeat_enabled() {
		$repeat_enabled = get_post_meta( $this->event->post->ID, '_pronamic_event_repeat', true );

		return $repeat_enabled;
	}

	//////////////////////////////////////////////////

	public function get_number_repeats() {
		$number = 0;

		$period = $this->get_period();

		if ( $period ) {
			$number = iterator_count( $period );
		}

		return $number;
	}

	//////////////////////////////////////////////////

	public function get_period( $start_date = null ) {
		$period = null;

		// Details
		if ( null === $start_date ) {
			$start_date = $this->event->get_start_date();
		}

		// Repeat
		$post_id = $this->event->post->ID;

		$frequency     = get_post_meta( $post_id, '_pronamic_event_repeat_frequency', true );
		$interval      = get_post_meta( $post_id, '_pronamic_event_repeat_interval', true );
		$ends_on       = get_post_meta( $post_id, '_pronamic_event_ends_on', true );
		$ends_on_count = get_post_meta( $post_id, '_pronamic_event_ends_on_count', true );
		$ends_on_until = get_post_meta( $post_id, '_pronamic_event_ends_on_until', true );

		// Periods
		$periods = array(
			'daily'    => 'D',
			'weekly'   => 'W',
			'monthly'  => 'M',
			'annually' => 'Y',
		);

		// Interval specification
		$interval_spec = null;

		if ( isset( $periods[ $frequency ] ) ) {
			$interval_spec = 'P' . $interval . $periods[ $frequency ];
		}

		// End
		$end = null;

		switch ( $ends_on ) {
			case 'count':
				$end = (int) $ends_on_count;

				break;
			case 'until':
				$end = new DateTime( $ends_on_until );

				break;
		}

		// Period
		if ( isset( $end, $interval_spec ) ) {
			$start = new DateTime( $start_date );

			$interval = new DateInterval( $interval_spec );

			$period = new DatePeriod( $start, $interval, $end, DatePeriod::EXCLUDE_START_DATE );
		}

		return $period;
	}

	public function get_period_data() {
		$data = array();

		// Dates
		$start_date = $this->event->get_start_date();
		$end_date   = $this->event->get_end_date();

		// Periods
		$start_period = $this->get_period( $start_date );
		$end_period   = $this->get_period( $end_date );

		// Loops
		$events = array();

		if ( isset( $start_period, $end_period ) ) {
			foreach ( $start_period as $i => $date ) {
				if ( ! isset( $events[ $i ] ) ) {
					$events[ $i ] = new Pronamic_DateEvent( $date, null );
				}

				$events[ $i ]->set_start( $date );
			}

			foreach ( $end_period as $i => $date ) {
				if ( ! isset( $events[ $i ] ) ) {
					$events[ $i ] = new Pronamic_DateEvent( null, $date );
				}

				$events[ $i ]->set_end( $date );
			}
		}

		// Data
		foreach ( $events as $e ) {
			$data[ $e->get_event_hash_code() ] = $e;
		}

		return $data;
	}

	//////////////////////////////////////////////////

	public function get_repeat_posts_query_args( $args = array() ) {
		$post = $this->event->post;

		$defaults = array(
				'post_type'      => $post->post_type,
				'post_parent'    => $post->ID,
				'post_status'    => 'any',
				'posts_per_page' => Pronamic_Events_RepeatModule::MAX_REPEATS,
				'orderby'        => 'meta_value_num date',
				'meta_key'       => '_pronamic_start_date',
		);

		$args = wp_parse_args( $args, $defaults );

		return $args;
	}

	public function get_repeat_posts() {
		$posts = get_posts( $this->get_repeat_posts_query_args() );

		return $posts;
	}

	//////////////////////////////////////////////////

	public function get_repeat_events() {
		$events = array();

		$posts = $this->get_repeat_posts();

		foreach ( $posts as $post ) {
			$event = new Pronamic_WP_Event( $post );

			$hash_code = $event->get_event_hash_code();

			$events[ $hash_code ] = $event;
		}

		return $events;
	}
}
