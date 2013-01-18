<?php
/*
Plugin Name: Pronamic Events
Plugin URI: http://pronamic.eu/wordpress/events/
Description: This plugin add some basic Event functionality to WordPress

Version: 0.1.2
Requires at least: 3.0

Author: Pronamic
Author URI: http://pronamic.eu/

Text Domain: pronamic_events
Domain Path: /languages/

License: GPL

GitHub URI: https://github.com/pronamic/wp-pronamic-events
*/

/**
 * Flush data
 */
function pronamic_events_rewrite_flush() {
    pronamic_events_init();

    flush_rewrite_rules();
}

register_activation_hook( __FILE__, 'pronamic_events_rewrite_flush' );

////////////////////////////////////////////////////////////

/**
 * Add admin columns
 */
function pronamic_events_add_columns( $column ) {
    $column['pronamic_start_date'] = __( 'Start Date', 'pronamic_events' );
    $column['pronamic_end_date']   = __( 'End Date', 'pronamic_events' );
 
    return $column;
}

add_filter( 'manage_edit-pronamic_event_columns', 'pronamic_events_add_columns' );

/**
 * Add admin rows
 */
function pronamic_events_add_rows( $column_name, $post_id ) {
    switch ( $column_name ) {
        case 'pronamic_start_date' :
        	pronamic_the_start_date( 'd-m-Y H:i' );

            break;
 
        case 'pronamic_end_date' :
            pronamic_the_end_date( 'd-m-Y H:i' );

            break;
 
        default:
    }
}

add_filter( 'manage_pronamic_event_posts_custom_column', 'pronamic_events_add_rows', 10, 2 );

function pronamic_events_add_columns_sortable( $columns ) {
	$columns['pronamic_start_date'] = '_pronamic_start_date';
	$columns['pronamic_end_date']   = '_pronamic_end_date';
	
	return $columns;
}

add_filter( 'manage_edit-pronamic_event_sortable_columns', 'pronamic_events_add_columns_sortable' );

function pronamic_events_column_orderby( $vars ) {
	if ( isset( $vars['orderby'] ) && '_pronamic_start_date' == $vars['orderby'] ) {
		$vars = array_merge( $vars, array(
			'meta_key' => '_pronamic_start_date',
			'orderby'  => 'meta_value_num'
		) );
	}

	if ( isset( $vars['orderby'] ) && '_pronamic_end_date' == $vars['orderby'] ) {
		$vars = array_merge( $vars, array(
			'meta_key' => '_pronamic_end_date',
			'orderby'  => 'meta_value_num'
		) );
	}

	return $vars;
}

add_filter( 'request', 'pronamic_events_column_orderby' );

////////////////////////////////////////////////////////////

/**
 * Pronamic events initialize
 */
function pronamic_events_init() {
	// Text domain
	$rel_path = dirname( plugin_basename( __FILE__ ) ) . '/languages/';

	load_plugin_textdomain( 'pronamic_events', false, $rel_path );

	// Includes
	require_once 'pronamic-events-template.php';

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
		) , 
		'public'             => true , 
		'publicly_queryable' => true, 
		'show_ui'            => true, 
		'show_in_menu'       => true,  
		'query_var'          => true, 
		'rewrite'            => true, 
		'capability_type'    => 'post', 
		'has_archive'        => true, 
		'rewrite'            => array(
			'slug'       => $slug,
			'with_front' => false 
		),
		'menu_icon'          =>  plugins_url( '/admin/icons/event.png', __FILE__ ),
		'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt' )
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
			'menu_name'         => __( 'Event categories', 'pronamic_events' ) 
		) , 
		'show_ui'      => true,
		'query_var'    => true
	) );

	// Actions
	add_action( 'admin_enqueue_scripts', 'pronamic_events_admin_enqueue_scripts' );
}

add_action( 'init', 'pronamic_events_init' );

////////////////////////////////////////////////////////////

function pronamic_events_admin_enqueue_scripts( $hook ) {
	wp_enqueue_style( 'pronamic-events', plugins_url( '/admin/css/pronamic-events.css', __FILE__ ) );
}

////////////////////////////////////////////////////////////

/**
 * Meta boxes
 */
function pronamic_events_add_dates_box() {
    add_meta_box( 
        'pronamic_event_meta_box',
        __( 'Event Details', 'pronamic_events' ),
        'pronamic_event_details_meta_box',
        'pronamic_event' ,
        'side' ,
        'high'
    );
}

add_action( 'add_meta_boxes', 'pronamic_events_add_dates_box' );

/**
 * Print metaboxes
 */
function pronamic_event_details_meta_box( $post ) {
	wp_nonce_field( plugin_basename( __FILE__ ), 'pronamic_events_nonce' );
	
	$start_timestamp = get_post_meta( $post->ID, '_pronamic_start_date', true );

	if( is_numeric( $start_timestamp ) ) {
		$start_date = date( 'd-m-Y', $start_timestamp );
		$start_time = date( 'H:i', $start_timestamp );
	} else {
		$start_date = '';
		$start_time = '';
	}

	$end_timestamp = get_post_meta( $post->ID, '_pronamic_end_date', true );

	if( is_numeric( $end_timestamp ) ) {
		$end_date = date( 'd-m-Y', $end_timestamp );
		$end_time = date( 'H:i', $end_timestamp );
	} else {
		$end_date = $start_date;
		$end_time = $start_time;
	}

	wp_enqueue_script( 'jquery-ui-datepicker' );
	wp_enqueue_script( 'jquery-ui-datepicker-nl', plugins_url( '/jquery-ui/languages/jquery.ui.datepicker-nl.js', __FILE__ ) );
	wp_enqueue_style( 'jquery-ui-datepicker', plugins_url( '/jquery-ui/themes/base/jquery.ui.all.css', __FILE__ ) );

	?>

	<div class="pronamic-section pronamic-section-first">
		<div>
			<label for="pronamic_start_date"><?php _e( 'Start Date:', 'pronamic_events' ); ?></label> <br />
			<input class="pronamic_date" type="text" id="pronamic_start_date" name="pronamic_start_date" value="<?php echo $start_date; ?>" size="14" />
			<input type="text" id="pronamic_start_time" name="pronamic_start_time" value="<?php echo $start_time; ?>" size="6" placeholder="00:00" />
		</div>

		<div>
			<label for="pronamic_end_date"><?php _e( 'End Date:', 'pronamic_events' ); ?></label> <br />
			<input class="pronamic_date" type="text" id="pronamic_end_date" name="pronamic_end_date" value="<?php echo $end_date; ?>" size="14"  />
			<input type="text" id="pronamic_end_time" name="pronamic_end_time" value="<?php echo $end_time; ?>" size="6" placeholder="00:00" />
		</div>
	
		<script type="text/javascript">
			jQuery(document).ready(function($) {
				var field = $('.pronamic_date');
		
				field.datepicker({
					dateFormat: 'dd-mm-yy'
				});
			});
		</script>
	</div>

	<div class="pronamic-section">
		<div>
			<label for="pronamic_location"><?php _e( 'Location:', 'pronamic_events' ); ?></label> <br />
			<input type="text" id="pronamic_location" name="pronamic_location" value="<?php echo get_post_meta( $post->ID, '_pronamic_location', true ); ?>" size="25" />
		</div>
	</div>

	<div class="pronamic-section pronamic-section-last">
		<div>
			<label for="pronamic_event_url"><?php _e( 'Website:', 'pronamic_events' ); ?></label> <br />
			<input type="url" id="pronamic_event_url" name="pronamic_event_url" value="<?php echo get_post_meta( $post->ID, '_pronamic_event_url', true ); ?>" size="25" />
		</div>
	</div>

	<?php
}

/**
 * Save metaboxes
 */
function pronamic_events_save_post( $post_id ) {
	global $post;

	if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		return;

	if( ! isset( $_POST['pronamic_events_nonce'] ) )
		return;

	if( ! wp_verify_nonce( $_POST['pronamic_events_nonce'], plugin_basename( __FILE__ ) ) )
		return;

	if( ! current_user_can( 'edit_post', $post_id ) )
		return;
		
	// Define timestamps
	$start_date = filter_input( INPUT_POST, 'pronamic_start_date', FILTER_SANITIZE_STRING );
	$start_time = filter_input( INPUT_POST, 'pronamic_start_time', FILTER_SANITIZE_STRING );

	$end_date =  filter_input( INPUT_POST, 'pronamic_end_date', FILTER_SANITIZE_STRING );
	$end_time =  filter_input( INPUT_POST, 'pronamic_end_time', FILTER_SANITIZE_STRING );
	
	$location = filter_input( INPUT_POST, 'pronamic_location', FILTER_SANITIZE_STRING );
	$url = filter_input( INPUT_POST, 'pronamic_event_url', FILTER_SANITIZE_STRING );

	$end_date = empty( $end_date ) ? $start_date : $end_date;
	$end_time = empty( $end_time ) ? $start_time : $end_time;

	$start_timestamp = strtotime( $start_date . ' ' . $start_time );
	$end_timestamp = strtotime( $end_date . ' ' . $end_time );
	
	// Save data
	update_post_meta( $post_id, '_pronamic_start_date', $start_timestamp );
	update_post_meta( $post_id, '_pronamic_end_date', $end_timestamp );
	update_post_meta( $post_id, '_pronamic_location', $location );
	update_post_meta( $post_id, '_pronamic_event_url', $url );
}

add_action( 'save_post', 'pronamic_events_save_post' );

////////////////////////////////////////////////////////////

/**
 * Check if the query is requesting event post type
 * 
 * @param WP_Query $query
 * @return boolean true if events are queried, false otherwises
 */
function is_pronamic_events_query( WP_Query $query ) {
	$is_pronamic_events = false;

	if ( $query->is_archive() ) {
		// Check 'post_type' var
		$is_pronamic_events = $query->get( 'post_type' ) == 'pronamic_event';

		if( ! $is_pronamic_events ) {
			// Check queried object
			$object = $query->get_queried_object();

			$is_pronamic_events = isset( $object, $object->name ) && $object->name == 'pronamic_event';
		}
	}

	return $is_pronamic_events;
}

/**
 * Customize query for the archive page
 */
function pronamic_events_query( $query ) {
	if ( ! is_admin() && is_pronamic_events_query( $query ) ) {
		$meta_query_extra = array(
			array(
				'key'     => '_pronamic_end_date' ,
				'value'   => strtotime( '-1 day' ) ,
				'compare' => '>', 
				'type'    => 'NUMERIC'
			)
		);

		$meta_query = $query->get( 'meta_query' );
		$meta_query = wp_parse_args( $meta_query_extra , $meta_query );

		$query->set( 'meta_query', $meta_query );
		$query->set( 'orderby', 'meta_value_num' );
		$query->set( 'meta_key', '_pronamic_start_date' );
		$query->set( 'order', 'ASC' );
	}
}

add_action( 'parse_query', 'pronamic_events_query' );

////////////////////////////////////////////////////////////

/**
 * Admin intialize
 */
function pronamic_events_admin_init() {
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
		'pronamic_events_input_text', // callback
		'pronamic_events', // page
		'pronamic_events_permalinks', // section
		array( 'label_for' => 'pronamic_event_base' ) // args
	);

	// Register settings
	register_setting( 'pronamic_events', 'pronamic_event_base' );
}

add_action( 'admin_init', 'pronamic_events_admin_init' );

/**
 * Admin menu
 */
function pronamic_events_admin_menu() {
	add_submenu_page(
		'edit.php?post_type=pronamic_event', // parent_slug
		__( 'Events Settings', 'pronamic_events' ), // page_title
		__( 'Settings', 'pronamic_events' ), // menu_title
		'manage_options', // capability
		'pronamic_events_settings', // menu_slug
		'pronamic_events_page_settings' // function
	);
}

add_action( 'admin_menu', 'pronamic_events_admin_menu' );

function pronamic_events_page_settings() {
	include 'admin/settings.php';
}

////////////////////////////////////////////////////////////

/**
 * Pronamic events input text
 * 
 * @param array $args
 */
function pronamic_events_input_text( $args ) {
	printf(
		'<input name="%s" id="%s" type="text" value="%s" class="%s" />',
		esc_attr( $args['label_for'] ),
		esc_attr( $args['label_for'] ),
		esc_attr( get_option( $args['label_for'] ) ),
		'regular-text code'
	);
}

////////////////////////////////////////////////////////////

/**
 * Gravity Forms post data
 * 
 * @param array $post_data
 * @param array $form
 * @param array $lead
 */
function pronamic_events_gform_post_data( $post_data, $form, $lead ) {
	$start_date = $end_date = null;

	if( isset( $post_data['post_custom_fields'] ) ) {
		$fields =& $post_data['post_custom_fields'];

		// Start date
		if( isset( $fields['_pronamic_start_date_date'] ) ) {
			$start_date = $fields['_pronamic_start_date_date'];

			if( isset( $fields['_pronamic_start_date_time'] ) ) {
				$start_date .= ' ' . $fields['_pronamic_start_date_time'];
			}
			
			$start_timestamp = strtotime( $start_date );

			if( $start_timestamp !== false ) {
				$fields['_pronamic_start_date'] = $start_timestamp;
			}
		}

		// End date
		if( isset( $fields['_pronamic_end_date_date'] ) ) {
			$end_date = $fields['_pronamic_end_date_date'];
	
			if( isset( $fields['_pronamic_end_date_time'] ) ) {
				$end_date .= ' ' . $fields['_pronamic_end_date_time'];
			}
			
			$end_timestamp = strtotime( $end_date );

			if( $end_timestamp !== false ) {
				$fields['_pronamic_end_date'] = $end_timestamp;
			}
		}
	}

	return $post_data;
}

add_filter( 'gform_post_data', 'pronamic_events_gform_post_data', 10, 3 );
