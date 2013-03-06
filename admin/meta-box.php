<?php

global $pronamic_events_plugin, $post;

wp_nonce_field( 'pronamic_events_edit_details', 'pronamic_events_nonce' );

$start_timestamp = get_post_meta( $post->ID, '_pronamic_start_date', true );

if ( is_numeric( $start_timestamp ) ) {
	$start_date = date( 'd-m-Y', $start_timestamp );
	$start_time = date( 'H:i', $start_timestamp );
} else {
	$start_date = '';
	$start_time = '';
}

$end_timestamp = get_post_meta( $post->ID, '_pronamic_end_date', true );

if ( is_numeric( $end_timestamp ) ) {
	$end_date = date( 'd-m-Y', $end_timestamp );
	$end_time = date( 'H:i', $end_timestamp );
} else {
	$end_date = $start_date;
	$end_time = $start_time;
}

wp_enqueue_script( 'jquery-ui-datepicker' );

wp_enqueue_script( 'jquery-ui-datepicker-nl', plugins_url( '/jquery-ui/languages/jquery.ui.datepicker-nl.js', $pronamic_events_plugin->file ) );
wp_enqueue_style( 'jquery-ui-datepicker', plugins_url( '/jquery-ui/themes/base/jquery.ui.all.css', $pronamic_events_plugin->file ) );

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