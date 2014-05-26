<?php

/**
 * Pronamic Events plugin
 */
class Pronamic_Events_Repeat {
	/**
	 * Plugin
	 *
	 * @var Pronamic_Events_Plugin
	 */
	private $plugin;

	//////////////////////////////////////////////////

	/**
	 * Constructs and initializes an Pronamic Events plugin admin object
	 */
	public function __construct( Pronamic_Events_Plugin $plugin ) {
		$this->plugin = $plugin;

		// Admin
		if ( is_admin() ) {
			new Pronamic_Events_Repeat_Admin( $plugin );
		}
	}
}
