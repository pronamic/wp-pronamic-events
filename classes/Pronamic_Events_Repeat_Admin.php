<?php

/**
 * Pronamic Events repeat admin
 */
class Pronamic_Events_Repeat_Admin {
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
	}

	//////////////////////////////////////////////////

	/**
	 * Add meta boxes
	 */
	public function add_meta_boxes( $post_type, $post ) {
		if ( 0 == $post->post_parent ) {
			add_meta_box(
				'pronamic_events_repeat_meta_box',
				__( 'Event Repeat', 'pronamic_events' ),
				array( $this, 'meta_box_event_repeat' ),
				'pronamic_event',
				'normal',
				'high'
			);

			add_meta_box(
				'pronamic_events_repeats_meta_box',
				__( 'Event Repeats', 'pronamic_events' ),
				array( $this, 'meta_box_event_repeats' ),
				'pronamic_event',
				'normal',
				'high'
			);
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

		if ( ! isset( $_POST['pronamic_events_nonce_repeat'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['pronamic_events_nonce_repeat'], 'pronamic_events_edit_repeat' ) ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Definition
		$definition = array(
			'_pronamic_event_repeat'           => FILTER_SANITIZE_STRING,
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

		$repeat_events = $event->get_repeat_events();

		$data = $event->get_period_data();

		if ( $data ) {
			remove_filter( 'save_post', array( $this->plugin->admin, 'save_post' ) );
			remove_filter( 'save_post', array( $this, 'save_post' ) );
			remove_filter( 'save_post', array( $this, 'save_repeats' ) );

			foreach ( $data as $e ) {
				$hash_code = $e->get_event_hash_code();

				if ( isset( $repeat_events[ $hash_code ] ) ) {
					// Post already exists
				} else {
					$post_data = array(
						'post_type'    => $post->post_type,
						'post_content' => $post->post_content,
						'post_title'   => $post->post_title,
						'post_author'  => $post->post_author,
						'post_parent'  => $post->ID,
						'post_status'  => $post->post_status,
					);

					$repeat_post_id = wp_insert_post( $post_data );

					$start_timestamp = $e->get_start()->format( 'U' );
					$end_timestamp   = $e->get_end()->format( 'U' );

					$meta = array(

					);

					$meta = pronamic_events_get_start_date_meta( $start_timestamp, $meta );
					$meta = pronamic_events_get_end_date_meta( $end_timestamp, $meta );

					// Save meta data
					foreach ( $meta as $key => $value ) {
						update_post_meta( $repeat_post_id, $key, $value );
					}
				}
			}

			add_filter( 'save_post', array( $this->plugin->admin, 'save_post' ) );
			add_filter( 'save_post', array( $this, 'save_post' ) );
			add_filter( 'save_post', array( $this, 'save_repeats' ) );
		}
	}
}
