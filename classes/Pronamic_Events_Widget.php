<?php

/**
 * Pronamic Events widget
 * 
 * @since 1.0.0
 * @see https://github.com/WordPress/WordPress/blob/3.5.1/wp-includes/default-widgets.php#L527
 */
class Pronamic_Events_Widget extends WP_Widget {
	/**
	 * Constructs and initialize an Pronamic Events widget
	 */
	public function __construct() {
		parent::__construct(
	 		'pronamic_events_widget', // Base ID
			__( 'Pronamic Events', 'pronamic_events' ), // Name
			array( // Arguments
				'description' => __( 'The most recent events on your site', 'pronamic_events' )
			)
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		global $pronamic_events_plugin, $wp_query;

		extract( $args );

		$title = apply_filters( 'widget_title', $instance['title'] );

		if ( empty( $instance['number'] ) || ! $number = absint( $instance['number'] ) ) {
			$number = 10;
		}

		// Query start
		$original_query = $wp_query;

		$wp_query = null;
		$wp_query = new WP_Query( array(
			'post_type'           => 'pronamic_event',
			'posts_per_page'      => $number,
			'no_found_rows'       => true,
			'post_status'         => 'publish',
			'ignore_sticky_posts' => true,
		) );
		
		// Start output
		$templates = array();
		$templates[] = 'widget-pronamic-events-' . $id . '.php';
		$templates[] = 'widget-pronamic-events-' . $widget_id . '.php';
		$templates[] = 'widget-pronamic-events.php';		

		$template = locate_template( $templates );

		if ( ! $template ) {
			$template = $pronamic_events_plugin->dirname . '/templates/widget-pronamic-events.php';
		}

		echo $before_widget;

		include $template;

		echo $after_widget;
		
		// Query reset
		$wp_query = null;
		$wp_query = $original_query;

		wp_reset_postdata();
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();

		$instance['title']  = strip_tags( $new_instance['title'] );
		$instance['number'] = (int) $new_instance['number'];

		return $instance;
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$title = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$number = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;

		?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'pronamic_events' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts to show:', 'pronamic_events' ); ?></label>
		<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>	
		<?php 
	}
}
