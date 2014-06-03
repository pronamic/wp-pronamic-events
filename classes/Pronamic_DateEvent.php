<?php

/**
 * Pronamic Events widget
*
* @since 1.0.0
* @see https://github.com/WordPress/WordPress/blob/3.5.1/wp-includes/default-widgets.php#L527
*/
class Pronamic_DateEvent implements Pronamic_DateEventInterface {
	public function __construct( DateTime $start = null, DateTime $end = null ) {
		$this->start = ( null === $start ) ? new DateTime() : $start;
		$this->end   = ( null === $end ) ? $this->start : $end;
	}

	public function get_start() {
		return $this->start;
	}

	public function set_start( DateTime $date ) {
		$this->start = $date;
	}

	public function get_end() {
		return $this->end;
	}

	public function set_end( DateTime $date ) {
		$this->end = $date;
	}

	public function get_event_hash_code() {
		$hash_code = '' . $this->start->format( 'U' ) . '-' .  $this->end->format( 'U' );

		return $hash_code;
	}
}
