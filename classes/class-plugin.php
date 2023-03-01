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

	/**
	 * Schema.org module.
	 *
	 * @var Pronamic_Events_Schema_Module
	 */
	protected $schema_module;

	/**
	 * Constructs and initializes an Pronamic Events plugin
	 *
	 * @param string $file the plugin file
	 */
	public function __construct( $file ) {
		$this->file    = $file;
		$this->dirname = plugin_dir_path( $file );

		// Activation and deactivation hooks
		register_activation_hook( $file, array( $this, 'activation_hook' ) );
		register_deactivation_hook( $file, 'flush_rewrite_rules' );

		// Includes
		require_once $this->dirname . '/includes/version.php';
		require_once $this->dirname . '/includes/functions.php';
		require_once $this->dirname . '/includes/gravityforms.php';
		require_once $this->dirname . '/includes/template.php';

		// Actions
		add_action( 'plugins_loaded', array( $this, 'load_text_domain' ) );

		add_action( 'init', array( $this, 'register_content_types' ) );
		add_action( 'init', array( $this, 'register_block_types' ) );

		add_action( 'widgets_init', array( $this, 'widgets_init' ) );

		add_action( 'pre_get_posts', array( $this, 'parse_search_qualifiers' ) );
		add_action( 'pre_get_posts', array( $this, 'pre_get_posts' ), 100 );

		add_action( 'pronamic_event_status_update', array( $this, 'event_status_update' ) );

		// Filters
		add_filter( 'request', array( $this, 'request' ) );

		add_action( 'the_post', array( $this, 'the_post' ) );

		add_filter( 'post_class', array( $this, 'post_class' ), 10, 3 );

		add_filter( 'oembed_request_post_id', array( $this, 'oembed_request_passed_event' ), 10, 2 );

		// Admin
		if ( is_admin() ) {
			$this->admin = new Pronamic_Events_Plugin_Admin( $this );
		}

		// Feed module.
		$this->feed_module = new Pronamic_Events_FeedModule( $this );

		if ( version_compare( PHP_VERSION, '5.3', '>=' ) ) {
			// Events repeat
			$this->repeat_module = new Pronamic_Events_RepeatModule( $this );

			// Share endpoint
			$this->share_endpoints = new Pronamic_Events_ShareEndpoints( $this );
		}

		// Schema.org data module integration through WordPress SEO plugin.
		$this->schema_module = new Pronamic_Events_Schema_Module();
	}

	/**
	 * Activation hook.
	 *
	 * @see https://codex.wordpress.org/Function_Reference/register_activation_hook
	 * @see https://codex.wordpress.org/Function_Reference/flush_rewrite_rules
	 */
	public function activation_hook() {
		// Make sure to load text domain.
		$this->load_text_domain();

		// Make sure to register content types.
		$this->register_content_types();

		// Flush rewrite rueles.
		flush_rewrite_rules();
	}

	/**
	 * Load text domain.
	 *
	 * @see https://codex.wordpress.org/Function_Reference/load_plugin_textdomain
	 */
	public function load_text_domain() {
		load_plugin_textdomain( 'pronamic-events', false, plugin_basename( dirname( $this->file ) ) . '/languages' );
	}

	/**
	 * Register content types.
	 */
	public function register_content_types() {
		// Post type
		$slug = get_option( 'pronamic_event_base' );
		$slug = empty( $slug ) ? _x( 'events', 'slug', 'pronamic-events' ) : $slug;

		register_post_type(
			'pronamic_event',
			array(
				'labels'             => array(
					'name'                  => _x( 'Events', 'post type general name', 'pronamic-events' ),
					'singular_name'         => _x( 'Event', 'post type singular name', 'pronamic-events' ),
					'add_new'               => _x( 'Add New', 'event', 'pronamic-events' ),
					'add_new_item'          => __( 'Add New Event', 'pronamic-events' ),
					'edit_item'             => __( 'Edit Event', 'pronamic-events' ),
					'new_item'              => __( 'New Event', 'pronamic-events' ),
					'view_item'             => __( 'View Event', 'pronamic-events' ),
					'search_items'          => __( 'Search Events', 'pronamic-events' ),
					'not_found'             => __( 'No events found.', 'pronamic-events' ),
					'not_found_in_trash'    => __( 'No events found in Trash.', 'pronamic-events' ),
					'parent_item_colon'     => __( 'Parent Event:', 'pronamic-events' ),
					'all_items'             => __( 'All Events', 'pronamic-events' ),
					'archives'              => __( 'Event Archives', 'pronamic-events' ),
					'insert_into_item'      => __( 'Insert into event', 'pronamic-events' ),
					'uploaded_to_this_item' => __( 'Uploaded to this event', 'pronamic-events' ),
					'filter_items_list'     => __( 'Filter events list', 'pronamic-events' ),
					'items_list_navigation' => __( 'Events list navigation', 'pronamic-events' ),
					'items_list'            => __( 'Events list', 'pronamic-events' ),
					'menu_name'             => _x( 'Events', 'menu_name', 'pronamic-events' ),
				),
				'public'             => true,
				'publicly_queryable' => true,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'show_in_rest'       => true,
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
			)
		);

		// Category
		$slug = get_option( 'pronamic_event_category_base' );
		$slug = empty( $slug ) ? _x( 'event-category', 'slug', 'pronamic-events' ) : $slug;

		register_taxonomy(
			'pronamic_event_category',
			'pronamic_event',
			array(
				'hierarchical' => true,
				'labels'       => array(
					'name'              => _x( 'Event categories', 'class general name', 'pronamic-events' ),
					'singular_name'     => _x( 'Event category', 'class singular name', 'pronamic-events' ),
					'search_items'      => __( 'Search Event categories', 'pronamic-events' ),
					'all_items'         => __( 'All Event categories', 'pronamic-events' ),
					'parent_item'       => __( 'Parent Event category', 'pronamic-events' ),
					'parent_item_colon' => __( 'Parent Event category:', 'pronamic-events' ),
					'edit_item'         => __( 'Edit Event category', 'pronamic-events' ),
					'update_item'       => __( 'Update Event category', 'pronamic-events' ),
					'add_new_item'      => __( 'Add New Event category', 'pronamic-events' ),
					'new_item_name'     => __( 'New Event category Name', 'pronamic-events' ),
					'menu_name'         => __( 'Categories', 'pronamic-events' ),
				),
				'show_ui'      => true,
				'query_var'    => true,
				'rewrite'      => array( 'slug' => $slug ),
			)
		);

		// Status
		$slug = get_option( 'pronamic_event_status_base' );
		$slug = empty( $slug ) ? _x( 'event-status', 'slug', 'pronamic-events' ) : $slug;

		register_taxonomy(
			'pronamic_event_status',
			'pronamic_event',
			array(
				'hierarchical' => true,
				'labels'       => array(
					'name'              => _x( 'Event statuses', 'class general name', 'pronamic-events' ),
					'singular_name'     => _x( 'Event status', 'class singular name', 'pronamic-events' ),
					'search_items'      => __( 'Search Event statuses', 'pronamic-events' ),
					'all_items'         => __( 'All Event statuses', 'pronamic-events' ),
					'parent_item'       => __( 'Parent Event status', 'pronamic-events' ),
					'parent_item_colon' => __( 'Parent Event status:', 'pronamic-events' ),
					'edit_item'         => __( 'Edit Event status', 'pronamic-events' ),
					'update_item'       => __( 'Update Event status', 'pronamic-events' ),
					'add_new_item'      => __( 'Add New Event status', 'pronamic-events' ),
					'new_item_name'     => __( 'New Event status Name', 'pronamic-events' ),
					'menu_name'         => __( 'Statuses', 'pronamic-events' ),
				),
				'show_ui'      => true,
				'query_var'    => true,
				'rewrite'      => array( 'slug' => $slug ),
			)
		);

		// Post status
		register_post_status(
			'passed',
			array(
				'label'                     => __( 'Passed', 'pronamic-events' ),
				'public'                    => true,
				'exclude_from_search'       => false,
				'show_in_admin_all_list'    => true,
				'show_in_admin_status_list' => true,
				/* translators: %s: count value */
				'label_count'               => _n_noop( 'Passed <span class="count">(%s)</span>', 'Passed <span class="count">(%s)</span>', 'pronamic-events' ),
			)
		);
	}

	/**
	 * Widgets initialize
	 */
	public function widgets_init() {
		register_widget( 'Pronamic_Events_Widget' );
	}

	/**
	 * Request
	 *
	 * @see http://codex.wordpress.org/Plugin_API/Filter_Reference/request
	 *
	 * @param array $request
	 * @return array
	 */
	public function request( $request ) {
		if ( isset( $request['orderby'] ) && 'pronamic_start_date' === $request['orderby'] ) {
			$request = array_merge(
				$request,
				array(
					'meta_key' => '_pronamic_start_date',
					'orderby'  => 'meta_value_num',
				)
			);
		}

		if ( isset( $request['orderby'] ) && 'pronamic_end_date' === $request['orderby'] ) {
			$request = array_merge(
				$request,
				array(
					'meta_key' => '_pronamic_end_date',
					'orderby'  => 'meta_value_num',
				)
			);
		}

		return $request;
	}

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

			if ( 'pronamic_event_start_date' === $orderby && empty( $order ) ) {
				// Default = Ascending
				$order = 'ASC';

				$query->set( 'order', $order );
			}
		}

		// Order by
		if ( 'pronamic_event_start_date' === $orderby ) {
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
			$meta_query = wp_parse_args( $meta_query_extra, $meta_query );

			$query->set( 'meta_query', $meta_query );
		}
	}

	/**
	 * When the_post is called, put product data into a global.
	 *
	 * @param mixed $post
	 * @return Pronamic_WP_Event
	 */
	public function the_post( $post ) {
		global $pronamic_event;

		unset( $pronamic_event );

		if ( is_int( $post ) ) {
			$post = get_post( $post );
		}

		if ( 'pronamic_event' !== get_post_type( $post ) ) {
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
		if ( 'pronamic_event' === get_post_type( $post_id ) ) {
			$end = get_post_meta( $post_id, '_pronamic_end_date', true );

			if ( $end < time() ) {
				$classes[] = 'event-ended';
			}
		}

		return $classes;
	}

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

		if ( $status_upcoming || $status_passed ) {
			// https://developer.wordpress.org/reference/functions/get_the_terms/
			$statuses = get_the_terms( $post_id, 'pronamic_event_status' );

			$status_ids = array();

			if ( is_array( $statuses ) ) {
				$status_ids = wp_list_pluck( $statuses, 'term_id' );
			}

			// @see http://stackoverflow.com/a/9268826
			$status_ids = array_diff( $status_ids, array( $status_upcoming, $status_passed ) );

			if ( $end > time() ) {
				$status_ids[] = $status_upcoming;
			} else {
				$status_ids[] = $status_passed;
			}

			wp_set_object_terms( $post_id, $status_ids, 'pronamic_event_status' );
		}

		if ( $end <= time() ) {
			global $wpdb;

			$wpdb->update(
				$wpdb->posts,
				array( 'post_status' => 'passed' ),
				array(
					'ID'          => $post_id,
					'post_status' => 'publish',
				),
				array( '%s' ),
				array( '%d', '%s' )
			);
		}
	}

	/**
	 * The WordPress `get_oembed_response_data` function will return `false` when the
	 * post status is not equal to 'publish'. This is not desired for event posts with
	 * the post status 'passed'. Therefor we will simulate the 'publish' post status.
	 *
	 * @see https://github.com/WordPress/WordPress/blob/4.9/wp-includes/class-wp-oembed-controller.php#L110-L118
	 * @see https://github.com/WordPress/WordPress/blob/4.9/wp-includes/embed.php#L487-L507
	 * @param $post_id
	 * @param $request_url
	 */
	public function oembed_request_passed_event( $post_id, $request_url ) {
		if ( 'passed' !== get_post_status( $post_id ) ) {
			return $post_id;
		}

		if ( ! post_type_supports( get_post_type( $post_id ), 'pronamic_event' ) ) {
			return $post_id;
		}

		// Simulate post status 'publish'.
		$post = get_post( $post_id );

		$post->post_status = 'publish';

		return $post;
	}

	/**
	 * Register block types.
	 * 
	 * @link https://github.com/WordPress/gutenberg/blob/trunk/packages/block-library/src/post-date/index.php
	 * @link https://developer.wordpress.org/reference/functions/get_block_wrapper_attributes/
	 * @return void
	 */
	public function register_block_types() {
		if ( ! \function_exists( '\register_block_type' ) ) {
			return;
		}

		\register_block_type( __DIR__ . '/../blocks/event-start-date', array(
			'render_callback' => function( $attributes, $content, $block ) {
				if ( ! \array_key_exists( 'postId', $block->context ) ) {
					return '';
				}

				$format = '';

				if ( \array_key_exists( 'format', $attributes ) ) {
					$format = $attributes['format'];
				}

				$format = ( '' === $format ) ? 'd-m-Y H:i:s' : $format;

				$post_id = $block->context['postId'];

				return \sprintf(
					'<div %s><time datetime="%s">%s</time></div>',
					\get_block_wrapper_attributes(),
					\esc_attr( \pronamic_get_the_start_date( 'c', $post_id ) ),
					\esc_html( \pronamic_get_the_start_date( $format, $post_id ) )
				);
			},
		) );

		\register_block_type( __DIR__ . '/../blocks/event-end-date', array(
			'render_callback' => function( $attributes, $content, $block ) {
				if ( ! \array_key_exists( 'postId', $block->context ) ) {
					return '';
				}

				$format = '';

				if ( \array_key_exists( 'format', $attributes ) ) {
					$format = $attributes['format'];
				}

				$format = ( '' === $format ) ? 'd-m-Y H:i:s' : $format;

				$post_id = $block->context['postId'];

				return \sprintf(
					'<div %s><time datetime="%s">%s</time></div>',
					\get_block_wrapper_attributes(),
					\esc_attr( \pronamic_get_the_end_date( 'c', $post_id ) ),
					\esc_html( \pronamic_get_the_end_date( $format, $post_id ) )
				);
			},
		) );

		\register_block_type( __DIR__ . '/../blocks/event-location', array(
			'render_callback' => function( $attributes, $content, $block ) {
				if ( ! \array_key_exists( 'postId', $block->context ) ) {
					return '';
				}

				$post_id = $block->context['postId'];

				return \sprintf(
					'<div %s>%s</div>',
					\get_block_wrapper_attributes(),
					\esc_html( \pronamic_get_the_location( $post_id ) )
				);
			},
		) );
	}

	/**
	 * Parse search qualifiers.
	 * 
	 * @link https://docs.github.com/en/search-github/searching-on-github
	 * @link https://docs.github.com/en/search-github/getting-started-with-searching-on-github/understanding-the-search-syntax
	 * @param WP_Query $query WordPress query.
	 * @return void
	 */
	public function parse_search_qualifiers( $query ) {
		if ( ! is_pronamic_events_query( $query ) ) {
			return;
		}

		$s = $query->get( 's' );

		if ( empty( $s ) ) {
			return;
		}

		$keywords = \explode( ' ', $s );

		$keywords_new = [];

		foreach ( $keywords as $keyword ) {
			if ( 'sort:event-start-date-asc' === $keyword ) {
				$query->set( 'orderby', 'pronamic_event_start_date' );
				$query->set( 'order', 'asc' );

				continue;
			}

			$keywords_new[] = $keyword;
		}

		$s = \implode( ' ', $keywords_new );

		$query->set( 's', $s );
	}
}
