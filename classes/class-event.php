<?php

class Pronamic_WP_Event implements Pronamic_DateEventInterface {
	public $post;

	public function __construct( $post ) {
		$this->post = get_post( $post );
	}

	public function get_start_date() {
		$date = get_post_meta( $this->post->ID, '_pronamic_event_start_date', true );

		return $date;
	}

	public function get_end_date() {
		$date = get_post_meta( $this->post->ID, '_pronamic_event_end_date', true );

		return $date;
	}

	public function get_event_hash_code() {
		$format = apply_filters( 'pronamic_events_hash_code_format', 'U' );

		$start_date = pronamic_get_the_start_date( $format, $this->post->ID );
		$end_date   = pronamic_get_the_end_date( $format, $this->post->ID );

		$hash_code = '' . $start_date . '-' . $end_date;

		return $hash_code;
	}
}
