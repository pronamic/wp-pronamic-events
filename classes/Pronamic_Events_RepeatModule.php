<?php

/**
 * Pronamic Events plugin
 */
class Pronamic_Events_RepeatModule {
	/**
	 * Maximum repeat
	 *
	 * @var int
	 */
	const MAX_REPEATS = 50;

	//////////////////////////////////////////////////

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
			$this->admin = new Pronamic_Events_RepeatModule_Admin( $plugin );
		}
	}
}
