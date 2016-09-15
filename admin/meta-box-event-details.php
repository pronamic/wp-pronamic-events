<?php

global $pronamic_events_plugin, $post;

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

$all_day = get_post_meta( $post->ID, '_pronamic_event_all_day', true );

$time_style = '';
if ( $all_day ) {
	$time_style = 'display: none;';
}

?>

<table class="form-table">
	<tbody>
		<tr>
			<th scope="row">
				<label for="pronamic_start_date"><?php esc_html_e( 'Date', 'pronamic-events' ); ?></label>
			</th>
			<td>
				<div>
					<input class="pronamic_date" type="text" id="pronamic_start_date" name="pronamic_start_date" value="<?php echo esc_attr( $start_date ); ?>" size="14" />
					<input class="pronamic_time" style="<?php echo esc_attr( $time_style ); ?>" type="text" id="pronamic_start_time" name="pronamic_start_time" value="<?php echo esc_attr( $start_time ); ?>" size="6" placeholder="00:00" />
				
					<?php esc_html_e( 'to', 'pronamic-events' ); ?>

					<input class="pronamic_date" type="text" id="pronamic_end_date" name="pronamic_end_date" value="<?php echo esc_attr( $end_date ); ?>" size="14"  />
					<input class="pronamic_time" style="<?php echo esc_attr( $time_style ); ?>" type="text" id="pronamic_end_time" name="pronamic_end_time" value="<?php echo esc_attr( $end_time ); ?>" size="6" placeholder="00:00" />
				</div>

				<div style="margin-top: 1em">
					<label for="pronamic_event_all_day">
						<input type="checkbox" id="pronamic_event_all_day" name="pronamic_event_all_day" value="true" <?php checked( $all_day ); ?> />

						<?php esc_html_e( 'All day', 'pronamic-events' ); ?>
					</label>
				</div>
			</td>
		</tr>

		<?php

		$fields = array(
			'location' => array(
				'id'       => 'pronamic_location',
				'label'    => __( 'Location', 'pronamic-events' ),
				'meta_key' => '_pronamic_location',
			),
			'website' => array(
				'id'       => 'pronamic_event_url',
				'label'    => __( 'Website', 'pronamic-events' ),
				'meta_key' => '_pronamic_event_url',
			),
		);

		$fields = apply_filters( 'pronamic_event_fields', $fields, $post );

		foreach ( $fields as $field ) : ?>

			<tr>
				<th scope="row">
					<label for="<?php echo esc_attr( $field['id'] ); ?>"><?php echo esc_html( $field['label'] ); ?></label>
				</th>
				<td>
					<input type="text" id="<?php echo esc_attr( $field['id'] ); ?>" name="<?php echo esc_attr( $field['id'] ); ?>" value="<?php echo esc_attr( get_post_meta( $post->ID, $field['meta_key'], true ) ); ?>" size="25" />
				</td>
			</tr>

		<?php endforeach; ?>

	</tbody>
</table>

<script type="text/javascript">
	jQuery( document ).ready( function( $ ) {
		// Date feields
		var dateFields = $( '.pronamic_date' );

		dateFields.datepicker( {
			dateFormat: 'dd-mm-yy'
		} );

		// Time fields
		var timeFields = $( '.pronamic_time' );

		// All day
		var allDayField = $( '#pronamic_event_all_day' );

		allDayField.change( function() {
			timeFields.toggle( ! allDayField.prop( 'checked' ) );
		} );
	} );
</script>
