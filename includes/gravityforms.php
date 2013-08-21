<?php

/**
 * Gravity Forms - Field advanced settings
 * 
 * @param int $position
 * @param int $form_id
 */
function pronamic_events_gform_field_advanced_settings( $position, $form_id ) {
	if ( $position == 100 ) : ?>

		<li class="date_format_setting field_setting" style="display: list-item;">
			<input type="checkbox" id="pronamic_events_is_start_date" onclick="SetFieldProperty('isEventStartDate', this.checked); ToggleInputName();" />

			<label for="pronamic_events_is_start_date" class="inline">
				<?php _e( 'Is Event Start Date', 'pronamic_events' ); ?>
			</label>
		</li>
		<li class="time_format_setting field_setting" style="display: list-item;">
			<input type="checkbox" id="pronamic_events_is_start_time" onclick="SetFieldProperty('isEventStartTime', this.checked); ToggleInputName();" />

			<label for="pronamic_events_is_start_time" class="inline">
				<?php _e( 'Is Event Start Time', 'pronamic_events' ); ?>
			</label>
		</li>

		<li class="date_format_setting field_setting" style="display: list-item;">
			<input type="checkbox" id="pronamic_events_is_end_date" onclick="SetFieldProperty('isEventEndDate', this.checked); ToggleInputName();" />

			<label for="pronamic_events_is_end_date" class="inline">
				<?php _e( 'Is Event End Date', 'pronamic_events' ); ?>
			</label>
		</li>
		<li class="time_format_setting field_setting" style="display: list-item;">
			<input type="checkbox" id="pronamic_events_is_end_time" onclick="SetFieldProperty('isEventEndTime', this.checked); ToggleInputName();" />

			<label for="pronamic_events_is_end_time" class="inline">
				<?php _e( 'Is Event Start Time', 'pronamic_events' ); ?>
			</label>
		</li>

	<?php endif;
}

add_action( 'gform_field_advanced_settings', 'pronamic_events_gform_field_advanced_settings', 10, 2 );

/**
 * Gravity Forms - Editor JavaScript
 */
function pronamic_events_gform_editor_js() {
	?>
	<script type="text/javascript">
		jQuery(document).bind("gform_load_field_settings", function(event, field, form) {
			if(field.type == "date") {
				var isEventStartDate = typeof field.isEventStartDate == "boolean" ? field.isEventStartDate : false;
				jQuery("#pronamic_events_is_start_date").prop( "checked", isEventStartDate );

				var isEventEndDate = typeof field.isEventEndDate == "boolean" ? field.isEventEndDate : false; 			
				jQuery("#pronamic_events_is_end_date").prop( "checked", isEventEndDate );
			}

			if(field.type == "time") {
				var isEventStartTime = typeof field.isEventStartTime == "boolean" ? field.isEventStartTime : false;
				jQuery("#pronamic_events_is_start_time").prop( "checked", isEventStartTime );

				var isEventEndTime = typeof field.isEventEndTime == "boolean" ? field.isEventEndTime : false; 			
				jQuery("#pronamic_events_is_end_time").prop( "checked", isEventEndTime );
			}
		});
	</script>
	<?php
}

add_action( 'gform_editor_js', 'pronamic_events_gform_editor_js' );

function pronamic_events_gform_parse_date( $value, $format ) {
	$timestamp = false;

	$date_info = GFCommon::parse_date( $value, $format );

	if ( isset( $date_info['month'], $date_info['day'], $date_info['year'] ) ) {
		$timestamp = mktime( 0, 0, 0, $date_info['month'], $date_info['day'], $date_info['year'] );
	}

	return $timestamp;
}

/**
 * Gravity Forms post data
 *
 * @param array $post_data
 * @param array $form
 * @param array $lead
 */
function pronamic_events_gform_post_data( $post_data, $form, $lead ) {
	$start_date = $end_date = null;
	
	// Form fields
	foreach ( $form['fields'] as $field ) {
		if ( isset( $field['isEventStartDate'] ) ) {
			$is_active = filter_var( $field['isEventStartDate'], FILTER_VALIDATE_BOOLEAN );
	
			if ( $is_active ) {
				$value = pronamic_events_gform_parse_date( $lead[$field['id']], $field['dateFormat'] );
				
				echo date( 'd-m-Y', $value );
				echo '<pre>';
				var_dump($value);
				echo '</pre>';
				echo '<pre>';
				var_dump($field);
				echo '</pre>';
				echo '<pre>';
				var_dump($lead);
				echo '</pre>';
			}
		}

		if ( isset( $field['isEventStartTime'] ) ) {
			$is_active = filter_var( $field['isEventStartTime'], FILTER_VALIDATE_BOOLEAN );
		
			if ( $is_active ) {
				$value = $lead[$field['id']];
				$value = strtotime( $value );
				echo date( 'd-m-Y H:i:s', $value );
				echo '<pre>';
				var_dump($value);
				echo '</pre>';

				exit;
			}
		}

		if ( isset( $field['isEventEndDate'] ) ) {
			$is_active = filter_var( $field['isEventEndDate'], FILTER_VALIDATE_BOOLEAN );
		
			if ( $is_active ) {
		
			}
		}

		if ( isset( $field['isEventEndTime'] ) ) {
			$is_active = filter_var( $field['isEventEndTime'], FILTER_VALIDATE_BOOLEAN );
		
			if ( $is_active ) {
		
			}
		}
	}

	if ( isset( $post_data['post_custom_fields'] ) ) {
		$fields =& $post_data['post_custom_fields'];

		// Start date
		if ( isset( $fields['_pronamic_start_date_date'] ) ) {
			$start_date = $fields['_pronamic_start_date_date'];

			if ( isset( $fields['_pronamic_start_date_time'] ) ) {
				$start_date .= ' ' . $fields['_pronamic_start_date_time'];
			}
				
			$start_timestamp = strtotime( $start_date );

			if ( $start_timestamp !== false ) {
				$fields['_pronamic_start_date'] = $start_timestamp;
			}
		}

		// End date
		if ( isset( $fields['_pronamic_end_date_date'] ) ) {
			$end_date = $fields['_pronamic_end_date_date'];

			if ( isset( $fields['_pronamic_end_date_time'] ) ) {
				$end_date .= ' ' . $fields['_pronamic_end_date_time'];
			}
				
			$end_timestamp = strtotime( $end_date );

			if ( $end_timestamp !== false ) {
				$fields['_pronamic_end_date'] = $end_timestamp;
			}
		}
	}

	return $post_data;
}

add_filter( 'gform_post_data', 'pronamic_events_gform_post_data', 10, 3 );
