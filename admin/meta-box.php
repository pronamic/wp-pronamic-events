<?php

global $pronamic_events_plugin, $post;

wp_nonce_field( 'pronamic_events_edit_details', 'pronamic_events_nonce' );

if ( pronamic_has_start_date() ) {
	$start_date = pronamic_get_the_start_date( 'd-m-Y' );
	$start_time = pronamic_get_the_start_date( 'H:i' );
} else {
	$start_date = '';
	$start_time = '';
}

$end_timestamp = get_post_meta( $post->ID, '_pronamic_end_date', true );

if ( pronamic_has_end_date( ) ) {
	$end_date = pronamic_get_the_end_date( 'd-m-Y' );
	$end_time = pronamic_get_the_end_date( 'H:i' );
} else {
	$end_date = $start_date;
	$end_time = $start_time;
}

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