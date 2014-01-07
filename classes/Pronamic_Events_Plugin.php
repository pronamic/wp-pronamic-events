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

		// Global
		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );

		add_action( 'init',           array( $this, 'init' ) );

		add_action( 'widgets_init',   array( $this, 'widgets_init' ) );

		add_action( 'parse_query',    array( $this, 'parse_query' ) );

		add_filter( 'request',        array( $this, 'request' ) );

		// Admin
		if ( is_admin() ) {
			new Pronamic_Events_Plugin_Admin( $this );
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
			'menu_icon'          =>  plugins_url( '/admin/icons/event.png', $this->file ),
			'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt' ),
		) );

		// Taxonomy
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
	 * Parse query
	 * 
	 * @note In PHP 5.1.4, "today" means midnight today, and "now" means the current timestamp.
	 * http://php.net/manual/en/function.strtotime.php#77541
	 *
	 * @param WP_Query $query
	 */
	public function parse_query( $query ) {
		if ( ! is_admin() && is_pronamic_events_query( $query ) ) {
			$meta_query_extra = array(
				array(
					'key'     => '_pronamic_end_date',
					'value'   => apply_filters( 'pronamic_event_parse_query_timestamp', strtotime( 'today' ) ),
					'compare' => '>',
					'type'    => 'NUMERIC',
				)
			);

			$meta_query = $query->get( 'meta_query' );
			$meta_query = wp_parse_args( $meta_query_extra , $meta_query );

			$query->set( 'meta_query', $meta_query );

			$query->set( 'orderby', 'meta_value_num date' );
			$query->set( 'meta_key', '_pronamic_start_date' );
			$query->set( 'order', 'ASC' );
		}
	}
}
