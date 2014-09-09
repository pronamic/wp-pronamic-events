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

?>

<table class="form-table">
	<tbody>
		<tr>
			<th scope="row">
				<label for="pronamic_start_date"><?php _e( 'Start Date', 'pronamic_events' ); ?></label>
			</th>
			<td>
				<input class="pronamic_date" type="text" id="pronamic_start_date" name="pronamic_start_date" value="<?php echo $start_date; ?>" size="14" />
				<input type="text" id="pronamic_start_time" name="pronamic_start_time" value="<?php echo $start_time; ?>" size="6" placeholder="00:00" />
			</td>
		</tr>
		<tr>
			<th scope="row">
				<label for="pronamic_end_date"><?php _e( 'End Date', 'pronamic_events' ); ?></label>
			</th>
			<td>
				<input class="pronamic_date" type="text" id="pronamic_end_date" name="pronamic_end_date" value="<?php echo $end_date; ?>" size="14"  />
				<input type="text" id="pronamic_end_time" name="pronamic_end_time" value="<?php echo $end_time; ?>" size="6" placeholder="00:00" />
			</td>
		</tr>

		<?php

		$fields = array(
			'location' => array(
				'id'       => 'pronamic_location',
				'label'    => __( 'Location', 'pronamic_events' ),
				'meta_key' => '_pronamic_location',
			),
			'website' => array(
				'id'       => 'pronamic_event_url',
				'label'    => __( 'Website', 'pronamic_events' ),
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
					<input type="text" id="<?php echo esc_attr( $field['id'] ); ?>" name="<?php echo esc_attr( $field['id'] ); ?>" value="<?php echo get_post_meta( $post->ID, $field['meta_key'], true ); ?>" size="25" />
				</td>
			</tr>

		<?php endforeach; ?>

	</tbody>
</table>

<script type="text/javascript">
	jQuery( document ).ready( function( $ ) {
		var field = $( '.pronamic_date' );

		field.datepicker( {
			dateFormat: 'dd-mm-yy'
		} );
	} );
</script>
