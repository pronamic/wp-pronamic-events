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

		// Post type
		$post_type = 'pronamic_event';
		
		add_filter( "manage_edit-{$post_type}_columns",          array( $this, 'manage_edit_columns' ) );
		add_filter( "manage_edit-{$post_type}_sortable_columns", array( $this, 'manage_edit_sortable_columns' ) );
		add_filter( "manage_{$post_type}_posts_custom_column",   array( $this, 'manage_posts_custom_column' ), 10, 2 );
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

		// Timezone
		$timezone = new DateTimeZone( get_option( 'timezone_string' ) );

		$start = new DateTime( $start_date . ' ' . $start_time, $timezone );
		$end   = new DateTime( $end_date . ' ' . $end_time, $timezone );

		$start_timestamp = $start->format( 'U' );
		$end_timestamp   = $end->format( 'U' );

		// Save data
		update_post_meta( $post_id, '_pronamic_start_date', $start_timestamp );
		update_post_meta( $post_id, '_pronamic_end_date', $end_timestamp );
		update_post_meta( $post_id, '_pronamic_location', $location );
		update_post_meta( $post_id, '_pronamic_event_url', $url );
	}

	//////////////////////////////////////////////////

	/**
	 * Manage edit columns
	 *
	 * @param array $columns
	 */
	public function manage_edit_columns( $columns ) {
		$new_columns = array();

		if( isset( $columns['cb'] ) ) {
			$new_columns['cb'] = $columns['cb'];
		}

		// $new_columns['thumbnail'] = __('Thumbnail', 'pronamic_companies');

		if( isset( $columns['title'] ) ) {
			$new_columns['title'] = $columns['title'];
		}

		if( isset( $columns['author'] ) ) {
			$new_columns['author'] = $columns['author'];
		}

		if( isset( $columns['comments'] ) ) {
			$new_columns['comments'] = $columns['comments'];
		}

		if( isset( $columns['date'] ) ) {
			$new_columns['date'] = $columns['date'];
		}

		$new_columns['pronamic_start_date'] = __( 'Start Date', 'pronamic_events' );
		$new_columns['pronamic_end_date']   = __( 'End Date', 'pronamic_events' );

		return array_merge( $new_columns, $columns );
	}

	//////////////////////////////////////////////////

	/**
	 * Manage edit sortable columns
	 *
	 * @param array $columns
	 */
	public function manage_edit_sortable_columns( $columns ) {
		$columns['pronamic_start_date'] = 'pronamic_start_date';
		$columns['pronamic_end_date']   = 'pronamic_end_date';

		return $columns;
	}

	//////////////////////////////////////////////////

	/**
	 * Manage posts custom column
	 *
	 * @param string $column_name
	 * @param string $post_id
	 */
	function manage_posts_custom_column( $column_name, $post_id ) {
		switch ( $column_name ) {
			case 'pronamic_start_date' :
				// @see http://translate.wordpress.org/projects/wp/3.5.x/admin/nl/default?filters[term]=Y%2Fm%2Fd&filters[user_login]=&filters[status]=current_or_waiting_or_fuzzy_or_untranslated&filter=Filter&sort[by]=priority&sort[how]=desc
				// @see https://github.com/WordPress/WordPress/blob/3.5.1/wp-admin/includes/class-wp-posts-list-table.php#L572

				$t_time = pronamic_get_the_start_date( __( 'Y/m/d g:i:s A', 'pronamic_events' ), $post_id );
				$h_time = pronamic_get_the_start_date( __( 'Y/m/d', 'pronamic_events' ), $post_id );
					
				printf( '<abbr title="%s">%s</abbr>', $t_time, $h_time );

				break;

			case 'pronamic_end_date' :
				// @see http://translate.wordpress.org/projects/wp/3.5.x/admin/nl/default?filters[term]=Y%2Fm%2Fd&filters[user_login]=&filters[status]=current_or_waiting_or_fuzzy_or_untranslated&filter=Filter&sort[by]=priority&sort[how]=desc
				// @see https://github.com/WordPress/WordPress/blob/3.5.1/wp-admin/includes/class-wp-posts-list-table.php#L572

				$t_time = pronamic_get_the_end_date( __( 'Y/m/d g:i:s A', 'pronamic_events' ), $post_id );
				$h_time = pronamic_get_the_end_date( __( 'Y/m/d', 'pronamic_events' ), $post_id );
					
				printf( '<abbr title="%s">%s</abbr>', $t_time, $h_time );

				break;

			default:
		}
	}
}
