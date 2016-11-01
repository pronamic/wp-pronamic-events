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

		add_action( 'save_post', array( $this, 'save_post' ) );
	}

	//////////////////////////////////////////////////

	/**
	 * Admin intialize
	 */
	function admin_init() {
		foreach ( get_post_types() as $post_type ) {
			if ( post_type_supports( $post_type, 'pronamic_event' ) ) {
				$screen_id = 'edit-' . $post_type;

				add_filter( 'manage_' . $post_type . '_posts_columns', array( $this, 'manage_posts_columns' ), 10, 1 );
				add_action( 'manage_' . $post_type . '_posts_custom_column', array( $this, 'manage_posts_custom_column' ), 10, 2 );
				add_action( 'manage_' . $screen_id . '_sortable_columns', array( $this, 'post_sortable_columns' ), 10 );
			}
		}

		// General
		add_settings_section(
			'pronamic_events_general', // id
			__( 'General', 'pronamic-events' ), // title
			'__return_false', // callback
			'pronamic_events' // page
		);

		add_settings_field(
			'pronamic_event_status_upcoming', // id
			__( 'Upcoming Event Status', 'pronamic-events' ), // title
			array( $this, 'dropdown_statuses' ), // callback
			'pronamic_events', // page
			'pronamic_events_general', // section
			array( 'label_for' => 'pronamic_event_status_upcoming' ) // args
		);

		add_settings_field(
			'pronamic_event_status_passed', // id
			__( 'Passed Event Status ', 'pronamic-events' ), // title
			array( $this, 'dropdown_statuses' ), // callback
			'pronamic_events', // page
			'pronamic_events_general', // section
			array( 'label_for' => 'pronamic_event_status_passed' ) // args
		);

		// Permalinks
		// Un we can't add the permalink options to permalink settings page
		// @see http://core.trac.wordpress.org/ticket/9296
		add_settings_section(
			'pronamic_events_permalinks', // id
			__( 'Permalinks', 'pronamic-events' ), // title
			'__return_false', // callback
			'pronamic_events' // page
		);

		add_settings_field(
			'pronamic_event_base', // id
			__( 'Event base', 'pronamic-events' ), // title
			array( $this, 'input_text' ), // callback
			'pronamic_events', // page
			'pronamic_events_permalinks', // section
			array( 'label_for' => 'pronamic_event_base' ) // args
		);

		add_settings_field(
			'pronamic_event_category_base', // id
			__( 'Category base', 'pronamic-events' ), // title
			array( __CLASS__, 'input_text' ), // callback
			'pronamic_events', // page
			'pronamic_events_permalinks', // section
			array( 'label_for' => 'pronamic_event_category_base' ) // args
		);

		add_settings_field(
			'pronamic_event_status_base', // id
			__( 'Status base', 'pronamic-events' ), // title
			array( __CLASS__, 'input_text' ), // callback
			'pronamic_events', // page
			'pronamic_events_permalinks', // section
			array( 'label_for' => 'pronamic_event_status_base' ) // args
		);

		// Register settings
		register_setting( 'pronamic_events', 'pronamic_event_status_upcoming' );
		register_setting( 'pronamic_events', 'pronamic_event_status_passed' );

		register_setting( 'pronamic_events', 'pronamic_event_base' );
		register_setting( 'pronamic_events', 'pronamic_event_category_base' );
		register_setting( 'pronamic_events', 'pronamic_event_status_base' );

		// Maybe update
		global $pronamic_events_db_version;

		if ( get_option( 'pronamic_events_db_version' ) !== $pronamic_events_db_version ) {
			$this->upgrade();

			update_option( 'pronamic_events_db_version', $pronamic_events_db_version );
		}
	}

	/**
	 * Admin menu
	 */
	public function admin_menu() {
		add_submenu_page(
			'edit.php?post_type=pronamic_event', // parent_slug
			__( 'Pronamic Events Settings', 'pronamic-events' ), // page_title
			__( 'Settings', 'pronamic-events' ), // menu_title
			'manage_options', // capability
			'pronamic_events_settings', // menu_slug
			array( $this, 'page_settings' ) // function
		);
	}

	//////////////////////////////////////////////////

	/**
	 * Upgrade
	 */
	public function upgrade() {
		require_once $this->plugin->dirname . '/admin/includes/upgrade.php';

		$db_version = get_option( 'pronamic_events_db_version' );

		if ( $db_version < 100 ) {
			orbis_events_upgrade_100();
		}
	}

	//////////////////////////////////////////////////

	/**
	 * Page settings
	 */
	public function page_settings() {
		include $this->plugin->dirname . '/admin/settings.php';
	}

	/**
	 * Pronamic events input text
	 *
	 * @param array $args
	 */
	public function input_text( $args ) {
		printf(
			'<input name="%s" id="%s" type="text" value="%s" class="%s" />',
			esc_attr( $args['label_for'] ),
			esc_attr( $args['label_for'] ),
			esc_attr( get_option( $args['label_for'] ) ),
			'regular-text code'
		);
	}

	/**
	 * Pronamic events input text
	 *
	 * @param array $args
	 */
	public function dropdown_statuses( $args ) {
		wp_dropdown_categories( array(
			'show_option_none' => __( '&mdash; Select Status &mdash;', 'pronamic-events' ),
			'hide_empty'       => false,
			'selected'         => get_option( $args['label_for'] ),
			'name'             => $args['label_for'],
			'id'               => $args['label_for'],
			'taxonomy'         => 'pronamic_event_status',
		) );
	}

	//////////////////////////////////////////////////

	/**
	 * Admin enqueue scripts
	 *
	 * @param string $hook
	 */
	public function admin_enqueue_scripts() {
		wp_enqueue_style( 'pronamic-events', plugins_url( '/admin/css/pronamic-events.css', $this->plugin->file ) );

		// Screen
		$screen = get_current_screen();

		if ( isset( $screen, $screen->post_type ) && post_type_supports( $screen->post_type, 'pronamic_event' ) ) {
			wp_enqueue_script( 'jquery-ui-datepicker' );

			wp_enqueue_style(
				'jquery-ui-theme-base',
				plugins_url( '/assets/jquery-ui/themes/base/all.css', $this->plugin->file ),
				array(),
				'1.12.1'
			);

			wp_enqueue_style(
				'wp-jquery-ui-datepicker-skins',
				plugins_url( '/assets/wp-jquery-ui-datepicker-skins/datepicker.css', $this->plugin->file )
			);

		}
	}

	//////////////////////////////////////////////////

	/**
	 * Add meta boxes
	 */
	public function add_meta_boxes( $post_type ) {
		if ( post_type_supports( $post_type, 'pronamic_event' ) || post_type_supports( $post_type, 'pronamic_event_repeat' ) ) {
			add_meta_box(
				'pronamic_events_details_meta_box',
				__( 'Event Details', 'pronamic-events' ),
				array( $this, 'meta_box_event_details' ),
				$post_type,
				'normal',
				'high'
			);
		}
	}

	/**
	 * Meta box for event details
	 */
	public function meta_box_event_details() {
		wp_nonce_field( 'pronamic_events_edit_details', 'pronamic_events_nonce_details' );

		include $this->plugin->dirname . '/admin/meta-box-event-details.php';
	}

	/**
	 * Save metaboxes
	 */
	public function save_post( $post_id ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! filter_has_var( INPUT_POST, 'pronamic_events_nonce_details' ) ) {
			return;
		}

		$nonce = filter_input( INPUT_POST, 'pronamic_events_nonce_details', FILTER_SANITIZE_STRING );

		if ( ! wp_verify_nonce( $nonce, 'pronamic_events_edit_details' ) ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Define timestamps
		$start_date = filter_input( INPUT_POST, 'pronamic_start_date', FILTER_SANITIZE_STRING );
		$start_time = filter_input( INPUT_POST, 'pronamic_start_time', FILTER_SANITIZE_STRING );

		$end_date = filter_input( INPUT_POST, 'pronamic_end_date', FILTER_SANITIZE_STRING );
		$end_time = filter_input( INPUT_POST, 'pronamic_end_time', FILTER_SANITIZE_STRING );

		$all_day = filter_input( INPUT_POST, 'pronamic_event_all_day', FILTER_VALIDATE_BOOLEAN );

		$location = filter_input( INPUT_POST, 'pronamic_location', FILTER_SANITIZE_STRING );
		$url      = filter_input( INPUT_POST, 'pronamic_event_url', FILTER_SANITIZE_STRING );

		$end_date = empty( $end_date ) ? $start_date : $end_date;
		$end_time = empty( $end_time ) ? $start_time : $end_time;

		if ( $all_day ) {
			$start_time = '00:00';
			$end_time   = '23:59';
		}

		$start_timestamp = strtotime( $start_date . ' ' . $start_time );
		$end_timestamp   = strtotime( $end_date . ' ' . $end_time );

		$meta = array(
			'_pronamic_event_all_day' => $all_day,
			'_pronamic_location'      => $location,
			'_pronamic_event_url'     => $url,
		);

		$meta = pronamic_events_get_start_date_meta( $start_timestamp, $meta );
		$meta = pronamic_events_get_end_date_meta( $end_timestamp, $meta );

		// Save meta data
		foreach ( $meta as $key => $value ) {
			update_post_meta( $post_id, $key, $value );
		}

		// Status update
		$this->plugin->event_status_update( $post_id );

		wp_clear_scheduled_hook( 'pronamic_event_status_update', array( $post_id ) );

		wp_schedule_single_event( $end_timestamp, 'pronamic_event_status_update', array( $post_id ) );
	}

	//////////////////////////////////////////////////

	/**
	 * Manage edit columns
	 *
	 * @param array $columns
	 */
	public function manage_posts_columns( $columns ) {
		$columns['pronamic_start_date'] = __( 'Start Date', 'pronamic-events' );
		$columns['pronamic_end_date']   = __( 'End Date', 'pronamic-events' );

		$columns = apply_filters( 'manage_pronamic_events_columns', $columns );

		$new_columns = array();

		foreach ( $columns as $name => $label ) {
			$new_columns[ $name ] = $label;

			if ( 'title' === $name ) {
				$new_columns['pronamic_start_date'] = $columns['pronamic_start_date'];
				$new_columns['pronamic_end_date']   = $columns['pronamic_end_date'];

				if ( isset( $columns['pronamic_event_repeat'] ) ) {
					$new_columns['pronamic_event_repeat'] = $columns['pronamic_event_repeat'];
				}
			}
		}

		$columns = $new_columns;

		return $columns;
	}

	//////////////////////////////////////////////////

	/**
	 * Manage edit sortable columns
	 *
	 * @param array $columns
	 */
	public function post_sortable_columns( $columns ) {
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
	public function manage_posts_custom_column( $column_name, $post_id ) {
		$all_day = get_post_meta( $post_id, '_pronamic_event_all_day', true );

		switch ( $column_name ) {
			case 'pronamic_start_date' :
				// @see http://translate.wordpress.org/projects/wp/3.5.x/admin/nl/default?filters[term]=Y%2Fm%2Fd&filters[user_login]=&filters[status]=current_or_waiting_or_fuzzy_or_untranslated&filter=Filter&sort[by]=priority&sort[how]=desc
				// @see https://github.com/WordPress/WordPress/blob/3.5.1/wp-admin/includes/class-wp-posts-list-table.php#L572

				$t_time = pronamic_get_the_start_date( __( 'Y/m/d g:i:s A', 'pronamic-events' ), $post_id );
				$h_time = pronamic_get_the_start_date( __( 'Y/m/d', 'pronamic-events' ), $post_id );
				$hours  = pronamic_get_the_start_date( __( 'g:i:s', 'pronamic-events' ), $post_id );

				if ( $all_day ) {
					printf( '<abbr title="%s">%s</abbr>', esc_attr( $t_time ), esc_html( $h_time ) );
				} else {
					printf( '<abbr title="%s">%s</abbr><br />%s', esc_attr( $t_time ), esc_html( $h_time ), esc_html( $hours ) );
				}

				break;

			case 'pronamic_end_date' :
				// @see http://translate.wordpress.org/projects/wp/3.5.x/admin/nl/default?filters[term]=Y%2Fm%2Fd&filters[user_login]=&filters[status]=current_or_waiting_or_fuzzy_or_untranslated&filter=Filter&sort[by]=priority&sort[how]=desc
				// @see https://github.com/WordPress/WordPress/blob/3.5.1/wp-admin/includes/class-wp-posts-list-table.php#L572

				$t_time = pronamic_get_the_end_date( __( 'Y/m/d g:i:s A', 'pronamic-events' ), $post_id );
				$h_time = pronamic_get_the_end_date( __( 'Y/m/d', 'pronamic-events' ), $post_id );
				$hours  = pronamic_get_the_end_date( __( 'g:i:s', 'pronamic-events' ), $post_id );

				if ( $all_day ) {
					printf( '<abbr title="%s">%s</abbr>', esc_attr( $t_time ), esc_html( $h_time ) );
				} else {
					printf( '<abbr title="%s">%s</abbr><br />%s', esc_attr( $t_time ), esc_html( $h_time ), esc_html( $hours ) );
				}

				break;

			case 'pronamic_event_repeat' :

				$repeat = get_post_meta( $post_id, '_pronamic_event_repeat', true );

				if ( $repeat || wp_get_post_parent_id( $post_id ) ) {
					echo '<span class="dashicons dashicons-backup" />';
				}

				break;

			default:
		}
	}
}
