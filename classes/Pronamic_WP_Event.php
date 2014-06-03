<?php

class Pronamic_WP_Event {
	private $post;

	//////////////////////////////////////////////////

	public function __construct( $post ) {
		$this->post = get_post( $post );
	}

	//////////////////////////////////////////////////

	public function get_repeats() {
		$repeats = get_posts( array(
			'post_type'   => 'pronamic_event',
			'post_parent' => $this->post->ID,
		) );

		return $repeats;
	}

	//////////////////////////////////////////////////

	public function get_period() {
		$period = null;

		// Details
		$start_date    = get_post_meta( $this->post->ID, '_pronamic_event_start_date', true );

		// Repeat
		$frequency     = get_post_meta( $this->post->ID, '_pronamic_event_repeat_frequency', true );
		$interval      = get_post_meta( $this->post->ID, '_pronamic_event_repeat_interval', true );
		$ends_on       = get_post_meta( $this->post->ID, '_pronamic_event_ends_on', true );
		$ends_on_count = get_post_meta( $this->post->ID, '_pronamic_event_ends_on_count', true );
		$ends_on_until = get_post_meta( $this->post->ID, '_pronamic_event_ends_on_until', true );

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

	//////////////////////////////////////////////////

	public function get_repeat_posts_query_args() {
		$args = array(
			'post_type'   => 'pronamic_event',
			'post_parent' => $this->post->ID,
		);

		return $args;
	}

	public function get_repeat_posts() {
		$posts = get_posts( $this->get_repeat_posts_query_args() );

		return $posts;
	}
}