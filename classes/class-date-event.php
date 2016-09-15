<?php

/**
 * Pronamic Events widget
*
* @since 1.0.0
* @see https://github.com/WordPress/WordPress/blob/3.5.1/wp-includes/default-widgets.php#L527
*/
class Pronamic_DateEvent implements Pronamic_DateEventInterface {
	/**
	 * Constructs and intializes an date event
	 *
	 * @param DateTime $start
	 * @param DateTime $end
	 */
	public function __construct( DateTime $start = null, DateTime $end = null ) {
		$this->start = ( null === $start ) ? new DateTime() : $start;
		$this->end   = ( null === $end ) ? $this->start : $end;
	}

	//////////////////////////////////////////////////

	/**
	 * Get start date
	 *
	 * @return DateTime
	 */
	public function get_start() {
		return $this->start;
	}

	/**
	 * Set start date
	 *
	 * @param DateTime $date
	 */
	public function set_start( DateTime $date ) {
		$this->start = $date;
	}

	//////////////////////////////////////////////////

	/**
	 * Get end date
	 *
	 * @return DateTime
	 */
	public function get_end() {
		return $this->end;
	}

	/**
	 * Set end date
	 *
	 * @param DateTime $date
	 */
	public function set_end( DateTime $date ) {
		$this->end = $date;
	}

	//////////////////////////////////////////////////

	/**
	 * Get event hash code
	 *
	 * @see Pronamic_DateEventInterface::get_event_hash_code()
	 */
	public function get_event_hash_code() {
		$hash_code = '' . $this->start->format( 'U' ) . '-' . $this->end->format( 'U' );

		return $hash_code;
	}
}
