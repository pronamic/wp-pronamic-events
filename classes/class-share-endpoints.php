<?php

/**
 * Title: Pronamic Events share endpoints
 * Description:
 * Copyright: Copyright (c) 2005 - 2020
 * Company: Pronamic
 *
 * @author Remco Tolsma
 * @version 1.3.0
 * @since 1.2.5
 */
class Pronamic_Events_ShareEndpoints {
	/**
	 * Constructs and initialize a Orbis vCard.
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;

		$this->endpoints = array(
			'icalendar',
			'google-calendar',
			'yahoo-calendar',
		);

		add_action( 'init', array( $this, 'init' ) );

		add_action( 'template_redirect', array( $this, 'template_redirect' ) );
	}

	/**
	 * Initialize.
	 *
	 * @see https://make.wordpress.org/plugins/2012/06/07/rewrite-endpoints-api/
	 */
	public function init() {
		foreach ( $this->endpoints as $endpoint ) {
			add_rewrite_endpoint( $endpoint, EP_PERMALINK );
		}
	}

	/**
	 * Get share endpoint.
	 */
	private function get_share() {
		if ( ! is_singular() ) {
			return false;
		}

		foreach ( $this->endpoints as $endpoint ) {
			if ( null !== get_query_var( $endpoint, null ) ) {
				return $endpoint;
			}
		}

		return false;
	}

	private function get_start_date() {
		$start = get_post_meta( get_the_ID(), '_pronamic_event_start_date_gmt', true );

		if ( empty( $start ) ) {
			return false;
		}

		return date_create( $start );
	}

	private function get_end_date() {
		$end = get_post_meta( get_the_ID(), '_pronamic_event_end_date_gmt', true );

		if ( empty( $end ) ) {
			return false;
		}

		return date_create( $end );
	}

	/**
	 * Share Google Calendar.
	 *
	 * @see http://stackoverflow.com/questions/22757908/google-calendar-render-action-template-parameter-documentation
	 */
	private function share_google_calendar() {
		$start = $this->get_start_date();
		$end   = $this->get_end_date();

		if ( false === $start || false === $end ) {
			return;
		}

		$url = add_query_arg(
			array(
				'action'   => 'TEMPLATE',
				'text'     => get_the_title(),
				'dates'    => '' . $start->format( 'Ymd\THis\Z' ) . '/' . $end->format( 'Ymd\THis\Z' ),
				'details'  => get_the_content(),
				'location' => pronamic_get_the_location(),
			),
			'https://www.google.com/calendar/render'
		);

		wp_redirect( $url );

		exit;
	}

	/**
	 * Share Yahoo Calendar.
	 *
	 * @see http://taskboy.com/blog/Creating_events_for_Yahoo_and_Google_calendars.html
	 */
	private function share_yahoo_calendar() {
		$start = $this->get_start_date();
		$end   = $this->get_end_date();

		if ( false === $start || false === $end ) {
			return;
		}

		// @see http://stackoverflow.com/a/3856312
		$seconds = $end->getTimestamp() - $end->getTimestamp();
		$hours   = floor( $seconds / 3600 );
		$minutes = floor( ( $seconds - ( $hours * 3600 ) ) / 60 );

		$url = add_query_arg(
			array(
				'v'      => '60',
				'view'   => 'd',
				'type'   => '20',
				'title'  => get_the_title(),
				'st'     => $start->format( 'Ymd\THis\Z' ),
				'dur'    => '' . sprintf( '%02d', $hours ) . sprintf( '%02d', $minutes ),
				'desc'   => get_the_content(),
				'in_loc' => pronamic_get_the_location(),
			),
			'http://calendar.yahoo.com/'
		);

		wp_redirect( $url );

		exit;
	}

	/**
	 * Share iCalendar.
	 */
	private function share_icalendar() {
		$start = $this->get_start_date();
		$end   = $this->get_end_date();

		if ( false === $start || false === $end ) {
			return;
		}

		$post = get_post();

		$vcalendar = new \Sabre\VObject\Component\VCalendar(
			array(
				'VEVENT' => array(
					'SUMMARY'     => get_the_title(),
					'DTSTART'     => $start,
					'DTEND'       => $end,
					'DESCRIPTION' => get_the_content(),
					'LOCATION'    => pronamic_get_the_location(),
				),
			)
		);

		header( 'Content-Type: text/calendar; charset=' . get_option( 'blog_charset' ) );
		header( 'Content-Disposition: inline; filename=' . $post->post_name . '.ics' );

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $vcalendar->serialize();

		exit;
	}

	/**
	 * Template redirect.
	 *
	 * @see https://make.wordpress.org/plugins/2012/06/07/rewrite-endpoints-api/
	 */
	public function template_redirect() {
		$share = $this->get_share();

		if ( false === $share ) {
			return;
		}

		switch ( $share ) {
			case 'icalendar':
				return $this->share_icalendar();
			case 'google-calendar':
				return $this->share_google_calendar();
			case 'yahoo-calendar':
				return $this->share_yahoo_calendar();
		}
	}
}
