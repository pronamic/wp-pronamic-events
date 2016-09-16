<?php

/**
 * Pronamic Events repeat admin
 */
class Pronamic_Events_RepeatModule_Admin {
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
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ), 10, 2 );

		add_action( 'save_post', array( $this, 'save_post' ) );
		add_action( 'save_post', array( $this, 'save_repeats' ) );

		add_filter( 'manage_pronamic_events_columns', array( $this, 'manage_pronamic_events_columns' ) );
	}

	//////////////////////////////////////////////////

	/**
	 * Add meta boxes
	 */
	public function add_meta_boxes( $post_type, $post ) {
		if ( post_type_supports( $post_type, 'pronamic_event_repeat' ) ) {
			add_meta_box(
				'pronamic_events_repeat_meta_box',
				__( 'Event Repeat', 'pronamic-events' ),
				array( $this, 'meta_box_event_repeat' ),
				$post_type,
				'normal',
				'high'
			);

			if ( empty( $post->post_parent ) ) {
				add_meta_box(
					'pronamic_events_repeats_meta_box',
					__( 'Event Repeats', 'pronamic-events' ),
					array( $this, 'meta_box_event_repeats' ),
					$post_type,
					'normal',
					'high'
				);
			}
		}
	}

	/**
	 * Meta box for event repeat
	 */
	public function meta_box_event_repeat() {
		wp_nonce_field( 'pronamic_events_edit_repeat', 'pronamic_events_nonce_repeat' );

		include $this->plugin->dirname . '/admin/meta-box-event-repeat.php';
	}

	/**
	 * Meta box for event repeat
	 */
	public function meta_box_event_repeats() {
		include $this->plugin->dirname . '/admin/meta-box-event-repeats.php';
	}

	//////////////////////////////////////////////////

	/**
	 * Save post
	 *
	 * @param int $post_id
	 */
	public function save_post( $post_id ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! filter_has_var( INPUT_POST, 'pronamic_events_nonce_repeat' ) ) {
			return;
		}

		$nonce = filter_input( INPUT_POST, 'pronamic_events_nonce_repeat', FILTER_SANITIZE_STRING );

		if ( ! wp_verify_nonce( $nonce, 'pronamic_events_edit_repeat' ) ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Definition
		$definition = array(
			'_pronamic_event_repeat'           => FILTER_VALIDATE_BOOLEAN,
			'_pronamic_event_repeat_frequency' => FILTER_SANITIZE_STRING,
			'_pronamic_event_repeat_interval'  => FILTER_SANITIZE_STRING,
			'_pronamic_event_ends_on'          => FILTER_SANITIZE_STRING,
			'_pronamic_event_ends_on_count'    => FILTER_SANITIZE_STRING,
			'_pronamic_event_ends_on_until'    => FILTER_SANITIZE_STRING,
		);

		$meta = filter_input_array( INPUT_POST, $definition );

		// Save meta data
		foreach ( $meta as $key => $value ) {
			update_post_meta( $post_id, $key, $value );
		}
	}

	/**
	 * Save repeats
	 *
	 * @param string $post_id
	 */
	public function save_repeats( $post_id ) {
		// Create repeated posts
		$post  = get_post( $post_id );

		$event = new Pronamic_WP_Event( $post );

		$repeat_helper = new Pronamic_Events_RepeatEventHelper( $event );

		$repeat_events = $repeat_helper->get_repeat_events();

		$data = new ArrayIterator( $repeat_helper->get_period_data() );
		$data = new LimitIterator( $data, 0, Pronamic_Events_RepeatModule::MAX_REPEATS );

		if ( $repeat_helper->is_repeat_enabled() && $data ) {
			// Remove filters
			remove_filter( 'save_post', array( $this->plugin->admin, 'save_post' ) );
			remove_filter( 'save_post', array( $this, 'save_post' ) );
			remove_filter( 'save_post', array( $this, 'save_repeats' ) );

			foreach ( $data as $e ) {
				$hash_code = $e->get_event_hash_code();

				$post_data = array(
					'post_title'   => $post->post_title,
					'post_content' => $post->post_content,
					'post_author'  => $post->post_author,
					'post_parent'  => $post->ID,
					'post_status'  => $post->post_status,
					'post_type'    => $post->post_type,
				);

				if ( ! isset( $repeat_events[ $hash_code ] ) ) {
					$repeat_post_id = wp_insert_post( $post_data );

					$start_timestamp = $e->get_start()->format( 'U' );
					$end_timestamp   = $e->get_end()->format( 'U' );

					$meta = array();

					$meta = pronamic_events_get_start_date_meta( $start_timestamp, $meta );
					$meta = pronamic_events_get_end_date_meta( $end_timestamp, $meta );

					// Save meta data
					foreach ( $meta as $key => $value ) {
						update_post_meta( $repeat_post_id, $key, $value );
					}
				}
			}

			/*
			 * Sync posts
			 */

			// Post
			$post_data = array(
				'post_title'   => $post->post_title,
				'post_content' => $post->post_content,
				'post_author'  => $post->post_author,
				'post_parent'  => $post->ID,
				'post_status'  => $post->post_status,
				'post_type'    => $post->post_type,
			);

			// Meta
			$ignore = array_flip( array(
				'_edit_last',
				'_pronamic_start_date',
				'_pronamic_event_start_date',
				'_pronamic_event_start_date_gmt',
				'_pronamic_end_date',
				'_pronamic_event_end_date',
				'_pronamic_event_end_date_gmt',
				'_pronamic_event_repeat',
				'_pronamic_event_repeat_frequency',
				'_pronamic_event_repeat_interval',
				'_pronamic_event_ends_on',
				'_pronamic_event_ends_on_count',
				'_pronamic_event_ends_on_until',
				'_edit_lock',
			) );

			$post_custom = get_post_custom( $post->ID );
			$post_custom = array_diff_key( $post_custom, $ignore );

			// Taxonomies
			$taxonomies = array();

			$taxonomy_names = get_object_taxonomies( $post );
			foreach ( $taxonomy_names as $taxonomy ) {
				$terms = get_the_terms( $post_id, $taxonomy );

				if ( is_array( $terms ) ) {
					$term_ids = wp_list_pluck( $terms, 'term_id' );

					$taxonomies[ $taxonomy ] = $term_ids;
				}
			}

			// Posts
			$args = $repeat_helper->get_repeat_posts_query_args( array( 'fields' => 'ids' ) );

			$post_ids = get_posts( $args );

			foreach ( $post_ids as $post_id ) {
				// Post
				$post_data['ID'] = $post_id;

				wp_update_post( $post_data );

				// Meta
				foreach ( $post_custom as $meta_key => $meta_values ) {
					delete_post_meta( $post_id, $meta_key );

					foreach ( $meta_values as $meta_value ) {
						add_post_meta( $post_id, $meta_key, $meta_value );
					}
				}

				// Taxonomies
				foreach ( $taxonomies as $taxonomy => $terms ) {
					wp_set_object_terms( $post_id, $terms, $taxonomy );
				}
			}

			// Add filters
			add_filter( 'save_post', array( $this->plugin->admin, 'save_post' ) );
			add_filter( 'save_post', array( $this, 'save_post' ) );
			add_filter( 'save_post', array( $this, 'save_repeats' ) );
		}
	}

	//////////////////////////////////////////////////

	/**
	 * Manage edit columns
	 *
	 * @param array $columns
	 */
	public function manage_pronamic_events_columns( $columns ) {
		$columns['pronamic_event_repeat'] = sprintf( '<span class="dashicons dashicons-backup" title="%s" />', __( 'Repeat', 'pronamic-events' ) );

		return $columns;
	}
}
