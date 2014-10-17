<?php

/**
 * Pronamic Events plugin
 */
class Pronamic_Events_Plugin {
	/**
	 * Plugin file
	 *
	 * @var string
	 */
	public $file;

	/**
	 * Plugin directory name
	 *
	 * @var string
	 */
	public $dirname;

	//////////////////////////////////////////////////

	/**
	 * Constructs and initializes an Pronamic Events plugin
	 *
	 * @param string $file the plugin file
	 */
	public function __construct( $file ) {
		$this->file    = $file;
		$this->dirname = plugin_dir_path( $file );

		register_activation_hook( $this->file, array( $this, 'flush_rewrite_rules' ) );

		// Includes
		require_once $this->dirname . '/includes/version.php';
		require_once $this->dirname . '/includes/functions.php';
		require_once $this->dirname . '/includes/gravityforms.php';
		require_once $this->dirname . '/includes/template.php';

		// Actions
		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );

		add_action( 'init', array( $this, 'init' ) );

		add_action( 'widgets_init', array( $this, 'widgets_init' ) );

		add_action( 'pre_get_posts', array( $this, 'pre_get_posts' ), 100 );

		add_action( 'pronamic_event_status_update', array( $this, 'event_status_update' ) );

		// Filters
		add_filter( 'request', array( $this, 'request' ) );

		add_action( 'the_post', array( $this, 'the_post' ) );

		add_filter( 'post_class', array( $this, 'post_class' ), 10, 3 );

		// Admin
		if ( is_admin() ) {
			$this->admin = new Pronamic_Events_Plugin_Admin( $this );
		}

		// Events repeat
		if ( version_compare( PHP_VERSION, '5.3', '>=' ) ) {
			$this->repeat_module = new Pronamic_Events_RepeatModule( $this );
		}
	}

	//////////////////////////////////////////////////

	/**
	 * Flush rewrite rules
	 */
	public function flush_rewrite_rules() {
		$this->init();

		flush_rewrite_rules();
	}

	//////////////////////////////////////////////////

	/**
	 * Plugins loaded
	 */
	public function plugins_loaded() {
		// Text domain
		$rel_path = dirname( plugin_basename( $this->file ) ) . '/languages/';

		load_plugin_textdomain( 'pronamic_events', false, $rel_path );
	}

	//////////////////////////////////////////////////

	/**
	 * Initialize
	 */
	public function init() {
		// Post type
		$slug = get_option( 'pronamic_event_base' );
		$slug = empty( $slug ) ? _x( 'events', 'slug', 'pronamic_events' ) : $slug;

		register_post_type( 'pronamic_event', array(
			'labels'             => array(
				'name'               => _x( 'Events', 'post type general name', 'pronamic_events' ),
				'singular_name'      => _x( 'Event', 'post type singular name', 'pronamic_events' ),
				'add_new'            => _x( 'Add New', 'event', 'pronamic_events' ),
				'add_new_item'       => __( 'Add New Event', 'pronamic_events' ),
				'edit_item'          => __( 'Edit Event', 'pronamic_events' ),
				'new_item'           => __( 'New Event', 'pronamic_events' ),
				'view_item'          => __( 'View Event', 'pronamic_events' ),
				'search_items'       => __( 'Search Events', 'pronamic_events' ),
				'not_found'          => __( 'No events found', 'pronamic_events' ),
				'not_found_in_trash' => __( 'No events found in Trash', 'pronamic_events' ),
				'parent_item_colon'  => __( 'Parent Event:', 'pronamic_events' ),
				'menu_name'          => _x( 'Events', 'menu_name', 'pronamic_events' ),
			),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => true,
			'capability_type'    => 'post',
			'has_archive'        => true,
			'rewrite'            => array(
				'slug'       => $slug,
				'with_front' => false,
			),
			'menu_icon'          => 'dashicons-calendar',
			'hierarchical'       => false,
			'supports'           => array(
				'title',
				'editor',
				'author',
				'thumbnail',
				'excerpt',
				'custom-fields',
				'comments',
				'revisions',
				'pronamic_event',
				'pronamic_event_repeat',
			),
		) );

		// Category
		$slug = get_option( 'pronamic_event_category_base' );
		$slug = empty( $slug ) ? _x( 'event-category', 'slug', 'pronamic_events' ) : $slug;

		register_taxonomy( 'pronamic_event_category', 'pronamic_event', array(
			'hierarchical' => true,
			'labels'       => array(
				'name'              => _x( 'Event categories', 'class general name', 'pronamic_events' ),
				'singular_name'     => _x( 'Event category', 'class singular name', 'pronamic_events' ),
				'search_items'      => __( 'Search Event categories', 'pronamic_events' ),
				'all_items'         => __( 'All Event categories', 'pronamic_events' ),
				'parent_item'       => __( 'Parent Event category', 'pronamic_events' ),
				'parent_item_colon' => __( 'Parent Event category:', 'pronamic_events' ),
				'edit_item'         => __( 'Edit Event category', 'pronamic_events' ),
				'update_item'       => __( 'Update Event category', 'pronamic_events' ),
				'add_new_item'      => __( 'Add New Event category', 'pronamic_events' ),
				'new_item_name'     => __( 'New Event category Name', 'pronamic_events' ),
				'menu_name'         => __( 'Categories', 'pronamic_events' ),
			),
			'show_ui'      => true,
			'query_var'    => true,
			'rewrite'      => array( 'slug' => $slug ),
		) );

		// Status
		$slug = get_option( 'pronamic_event_status_base' );
		$slug = empty( $slug ) ? _x( 'event-status', 'slug', 'pronamic_events' ) : $slug;

		register_taxonomy( 'pronamic_event_status', 'pronamic_event', array(
			'hierarchical' => true,
			'labels'       => array(
				'name'              => _x( 'Event statuses', 'class general name', 'pronamic_events' ),
				'singular_name'     => _x( 'Event status', 'class singular name', 'pronamic_events' ),
				'search_items'      => __( 'Search Event statuses', 'pronamic_events' ),
				'all_items'         => __( 'All Event statuses', 'pronamic_events' ),
				'parent_item'       => __( 'Parent Event status', 'pronamic_events' ),
				'parent_item_colon' => __( 'Parent Event status:', 'pronamic_events' ),
				'edit_item'         => __( 'Edit Event status', 'pronamic_events' ),
				'update_item'       => __( 'Update Event status', 'pronamic_events' ),
				'add_new_item'      => __( 'Add New Event status', 'pronamic_events' ),
				'new_item_name'     => __( 'New Event status Name', 'pronamic_events' ),
				'menu_name'         => __( 'Statuses', 'pronamic_events' ),
			),
			'show_ui'      => true,
			'query_var'    => true,
			'rewrite'      => array( 'slug' => $slug ),
		) );
	}

	//////////////////////////////////////////////////

	/**
	 * Widgets initialize
	 */
	public function widgets_init() {
		register_widget( 'Pronamic_Events_Widget' );
	}

	//////////////////////////////////////////////////

	/**
	 * Request
	 *
	 * @see http://codex.wordpress.org/Plugin_API/Filter_Reference/request
	 *
	 * @param array $request
	 * @return array
	 */
	public function request( $request ) {
		if ( isset( $request['orderby'] ) && 'pronamic_start_date' == $request['orderby'] ) {
			$request = array_merge( $request, array(
				'meta_key' => '_pronamic_start_date',
				'orderby'  => 'meta_value_num',
			) );
		}

		if ( isset( $request['orderby'] ) && 'pronamic_end_date' == $request['orderby'] ) {
			$request = array_merge( $request, array(
				'meta_key' => '_pronamic_end_date',
				'orderby'  => 'meta_value_num',
			) );
		}

		return $request;
	}

	//////////////////////////////////////////////////

	/**
	 * Pre get posts
	 *
	 * @note In PHP 5.1.4, "today" means midnight today, and "now" means the current timestamp.
	 * http://php.net/manual/en/function.strtotime.php#77541
	 *
	 * @param WP_Query $query
	 */
	public function pre_get_posts( $query ) {
		// Order
		$orderby = $query->get( 'orderby' );
		$order   = $query->get( 'order' );

		// Date
		$date_after = $query->get( 'pronamic_event_date_after' );

		// Defaults
		if ( ! is_admin() && is_pronamic_events_query( $query ) ) {
			// Default - Date after
			if ( '' === $date_after ) {
				$offset = apply_filters( 'pronamic_events_date_offset', 'today' );

				$date_after = strtotime( $offset );

				$query->set( 'pronamic_event_date_after', $date_after );
			}

			// Default - Order by
			if ( empty( $orderby ) ) {
				// Default = Start date
				$orderby = 'pronamic_event_start_date';

				$query->set( 'orderby', $orderby );
			}

			if ( 'pronamic_event_start_date' == $orderby && empty( $order ) ) {
				// Default = Ascending
				$order = 'ASC';

				$query->set( 'order', $order );
			}
		}

		// Order by
		if ( 'pronamic_event_start_date' == $orderby ) {
			$query->set( 'orderby', 'meta_value_num date' );
			$query->set( 'meta_key', '_pronamic_start_date' );
		}

		// Date after
		if ( ! empty( $date_after ) ) {
			$meta_query_extra = array(
				array(
					'key'     => '_pronamic_end_date',
					'value'   => apply_filters( 'pronamic_event_parse_query_timestamp', $date_after ),
					'compare' => '>',
					'type'    => 'NUMERIC',
				),
			);

			$meta_query = $query->get( 'meta_query' );
			$meta_query = wp_parse_args( $meta_query_extra , $meta_query );

			$query->set( 'meta_query', $meta_query );
		}
	}

	/**
	 * When the_post is called, put product data into a global.
	 *
	 * @param mixed $post
	 * @return Pronamic_WP_Event
	 */
	function the_post( $post ) {
		global $pronamic_event;

		unset( $pronamic_event );

		if ( is_int( $post ) ) {
			$post = get_post( $post );
		}

		if ( 'pronamic_event' != $post->post_type ) {
			return;
		}

		$pronamic_event = new Pronamic_WP_Event( $post );

		return $pronamic_event;
	}

	/**
	 * Post class
	 *
	 * @see https://core.trac.wordpress.org/browser/tags/3.9.1/src/wp-includes/post-template.php#L457
	 *
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public function post_class( $classes, $class, $post_id ) {
		$post = get_post( $post_id );

		if ( 'pronamic_event' == $post->post_type ) {
			$end = get_post_meta( $post_id, '_pronamic_end_date', true );

			if ( $end < time() ) {
				$classes[] = 'event-ended';
			}
		}

		return $classes;
	}

	//////////////////////////////////////////////////

	/**
	 * Event status update
	 *
	 * @param int $post_id
	 */
	public function event_status_update( $post_id ) {
		$end = intval( get_post_meta( $post_id, '_pronamic_end_date', true ) );

		// Warning: some functions may return term_ids as strings which will be interpreted as slugs consisting of numeric characters!
		// @see http://codex.wordpress.org/Function_Reference/wp_set_object_terms
		$status_upcoming = intval( get_option( 'pronamic_event_status_upcoming' ) );
		$status_passed   = intval( get_option( 'pronamic_event_status_passed' ) );

		$statuses = wp_get_object_terms( $post_id, 'pronamic_event_status', array( 'fields' => 'ids' ) );

		// @see http://stackoverflow.com/a/9268826
		$statuses = array_diff( $statuses, array( $status_upcoming, $status_passed ) );

		if ( $end > time() ) {
			$statuses[] = $status_upcoming;
		} else {
			$statuses[] = $status_passed;
		}

		wp_set_object_terms( $post_id, $statuses, 'pronamic_event_status' );
	}
}
