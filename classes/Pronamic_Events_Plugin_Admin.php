<?php

/**
 * Pronamic Events plugin admin
 */
class Pronamic_Events_Plugin_Admin {
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

		// Actions
		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );

		add_action( 'save_post', array( $this, 'save_post' ), 10, 2 );
	}

	//////////////////////////////////////////////////

	/**
	 * Admin intialize
	 */
	function admin_init() {
		// Permalinks
		// Un we can't add the permalink options to permalink settings page
		// @see http://core.trac.wordpress.org/ticket/9296
		add_settings_section(
			'pronamic_events_permalinks', // id
			__( 'Permalinks', 'pronamic_events' ), // title
			'__return_false', // callback
			'pronamic_events' // page
		);

		add_settings_field(
			'pronamic_event_base', // id
			__( 'Event base', 'pronamic_events' ), // title
			array( $this, 'input_text' ), // callback
			'pronamic_events', // page
			'pronamic_events_permalinks', // section
			array( 'label_for' => 'pronamic_event_base' ) // args
		);

		// Register settings
		register_setting( 'pronamic_events', 'pronamic_event_base' );
	}

	/**
	 * Admin menu
	 */
	function admin_menu() {
		add_submenu_page(
			'edit.php?post_type=pronamic_event', // parent_slug
			__( 'Pronamic Events Settings', 'pronamic_events' ), // page_title
			__( 'Settings', 'pronamic_events' ), // menu_title
			'manage_options', // capability
			'pronamic_events_settings', // menu_slug
			array( $this, 'page_settings' ) // function
		);
	}

	//////////////////////////////////////////////////

	/**
	 * Page settings
	 */
	function page_settings() {
		include $this->plugin->dirname . '/admin/settings.php';
	}

	/**
	 * Pronamic events input text
	 *
	 * @param array $args
	 */
	function input_text( $args ) {
		printf(
			'<input name="%s" id="%s" type="text" value="%s" class="%s" />',
			esc_attr( $args['label_for'] ),
			esc_attr( $args['label_for'] ),
			esc_attr( get_option( $args['label_for'] ) ),
			'regular-text code'
		);
	}

	//////////////////////////////////////////////////

	/**
	 * Admin enqueue scripts
	 *
	 * @param string $hook
	 */
	function admin_enqueue_scripts( $hook ) {
		wp_enqueue_style( 'pronamic-events', plugins_url( '/admin/css/pronamic-events.css', $this->plugin->file ) );
	}

	//////////////////////////////////////////////////

	/**
	 * Add meta boxes
	 */
	function add_meta_boxes() {
		add_meta_box(
			'pronamic_event_meta_box',
			__( 'Event Details', 'pronamic_events' ),
			array( $this, 'event_details_meta_box' ),
			'pronamic_event' ,
			'side' ,
			'high'
		);
	}

	/**
	 * Event details meta box
	 */
	function event_details_meta_box() {
		include $this->plugin->dirname . '/admin/meta-box.php';
	}

	/**
	 * Save metaboxes
	 */
	function save_post( $post_id ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return;

		if ( ! isset( $_POST['pronamic_events_nonce'] ) )
			return;

		if ( ! wp_verify_nonce( $_POST['pronamic_events_nonce'], 'pronamic_events_edit_details' ) )
			return;

		if ( ! current_user_can( 'edit_post', $post_id ) )
			return;

		// Define timestamps
		$start_date = filter_input( INPUT_POST, 'pronamic_start_date', FILTER_SANITIZE_STRING );
		$start_time = filter_input( INPUT_POST, 'pronamic_start_time', FILTER_SANITIZE_STRING );

		$end_date =  filter_input( INPUT_POST, 'pronamic_end_date', FILTER_SANITIZE_STRING );
		$end_time =  filter_input( INPUT_POST, 'pronamic_end_time', FILTER_SANITIZE_STRING );

		$location = filter_input( INPUT_POST, 'pronamic_location', FILTER_SANITIZE_STRING );
		$url      = filter_input( INPUT_POST, 'pronamic_event_url', FILTER_SANITIZE_STRING );

		$end_date = empty( $end_date ) ? $start_date : $end_date;
		$end_time = empty( $end_time ) ? $start_time : $end_time;

		$start_timestamp = strtotime( $start_date . ' ' . $start_time );
		$end_timestamp   = strtotime( $end_date . ' ' . $end_time );

		// Save data
		update_post_meta( $post_id, '_pronamic_start_date', $start_timestamp );
		update_post_meta( $post_id, '_pronamic_end_date', $end_timestamp );
		update_post_meta( $post_id, '_pronamic_location', $location );
		update_post_meta( $post_id, '_pronamic_event_url', $url );
	}
}
