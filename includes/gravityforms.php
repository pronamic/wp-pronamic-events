<?php

/**
 * Gravity Forms - Field advanced settings
 *
 * @param int $position
 * @param int $form_id
 */
function pronamic_events_gform_field_advanced_settings( $position ) {
	if ( 100 === $position ) : ?>

		<li class="date_format_setting field_setting" style="display: list-item;">
			<input type="checkbox" id="pronamic_events_is_start_date" onclick="SetFieldProperty('isEventStartDate', this.checked); ToggleInputName();" />

			<label for="pronamic_events_is_start_date" class="inline">
				<?php esc_html_e( 'Is Event Start Date', 'pronamic-events' ); ?>
			</label>
		</li>
		<li class="time_format_setting field_setting" style="display: list-item;">
			<input type="checkbox" id="pronamic_events_is_start_time" onclick="SetFieldProperty('isEventStartTime', this.checked); ToggleInputName();" />

			<label for="pronamic_events_is_start_time" class="inline">
				<?php esc_html_e( 'Is Event Start Time', 'pronamic-events' ); ?>
			</label>
		</li>

		<li class="date_format_setting field_setting" style="display: list-item;">
			<input type="checkbox" id="pronamic_events_is_end_date" onclick="SetFieldProperty('isEventEndDate', this.checked); ToggleInputName();" />

			<label for="pronamic_events_is_end_date" class="inline">
				<?php esc_html_e( 'Is Event End Date', 'pronamic-events' ); ?>
			</label>
		</li>
		<li class="time_format_setting field_setting" style="display: list-item;">
			<input type="checkbox" id="pronamic_events_is_end_time" onclick="SetFieldProperty('isEventEndTime', this.checked); ToggleInputName();" />

			<label for="pronamic_events_is_end_time" class="inline">
				<?php esc_html_e( 'Is Event End Time', 'pronamic-events' ); ?>
			</label>
		</li>

	<?php endif;
}

add_action( 'gform_field_advanced_settings', 'pronamic_events_gform_field_advanced_settings' );

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
	$date_info = GFCommon::parse_date( $value, $format );

	return $date_info;
}

function pronamic_events_gform_parse_time( $value ) {
	$date_info = null;

	$result = date_parse( $value );

	if ( $result ) {
		$date_info = array_intersect_key( $result, array(
			'hour'   => 0,
			'minute' => 0,
			'second' => 0,
		) );
	}

	return $date_info;
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

	// Init
	$start_date = array();
	$start_time = array();
	$end_date   = array();
	$end_time   = array();

	// Form fields
	foreach ( $form['fields'] as $field ) {
		if ( isset( $field['isEventStartDate'] ) ) {
			$has_start_date = filter_var( $field['isEventStartDate'], FILTER_VALIDATE_BOOLEAN );

			if ( $has_start_date ) {
				$start_date = pronamic_events_gform_parse_date( $lead[ $field['id'] ], $field['dateFormat'] );
			}
		}

		if ( isset( $field['isEventStartTime'] ) ) {
			$has_start_time = filter_var( $field['isEventStartTime'], FILTER_VALIDATE_BOOLEAN );

			if ( $has_start_time ) {
				$start_time = pronamic_events_gform_parse_time( $lead[ $field['id'] ] );
			}
		}

		if ( isset( $field['isEventEndDate'] ) ) {
			$has_end_date = filter_var( $field['isEventEndDate'], FILTER_VALIDATE_BOOLEAN );

			if ( $has_end_date ) {
				$end_date = pronamic_events_gform_parse_date( $lead[ $field['id'] ], $field['dateFormat'] );
			}
		}

		if ( isset( $field['isEventEndTime'] ) ) {
			$has_end_time = filter_var( $field['isEventEndTime'], FILTER_VALIDATE_BOOLEAN );

			if ( $has_end_time ) {
				$end_time = pronamic_events_gform_parse_time( $lead[ $field['id'] ] );
			}
		}
	}

	// Mapping
	if ( ! isset( $post_data['post_custom_fields'] ) ) {
		$post_data['post_custom_fields'] = array();
	}

	$fields =& $post_data['post_custom_fields'];

	// Backwards compatibility
	$backwards_compatibility = true;

	if ( $backwards_compatibility ) {
		// Start date
		if ( isset( $fields['_pronamic_start_date_date'] ) ) {
			$start_date = $fields['_pronamic_start_date_date'];

			if ( isset( $fields['_pronamic_start_date_time'] ) ) {
				$start_date .= ' ' . $fields['_pronamic_start_date_time'];
			}

			$start_timestamp = strtotime( $start_date );

			if ( false !== $start_timestamp ) {
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

			if ( false !== $end_timestamp ) {
				$fields['_pronamic_end_date'] = $end_timestamp;
			}
		}
	}

	// Merge start and end dates togehter, magic!
	$start = array_merge( $end_date, $end_time, $start_date, $start_time );
	$end   = array_merge( $start_date, $start_time, $end_date, $end_time );

	if ( ! empty( $start ) && ! empty( $end ) ) {
		// Merge missing date info with today's date info
		$today = date_parse( 'today' );

		$start = array_merge( $today, $start );
		$end   = array_merge( $today, $end );

		$timestamp = mktime( $start['hour'], $start['minute'], $start['second'], $start['month'], $start['day'], $start['year'] );
		$fields = pronamic_events_get_start_date_meta( $timestamp, $fields );

		$timestamp = mktime( $end['hour'], $end['minute'], $end['second'], $end['month'], $end['day'], $end['year'] );
		$fields = pronamic_events_get_end_date_meta( $timestamp, $fields );
	}

	return $post_data;
}

add_filter( 'gform_post_data', 'pronamic_events_gform_post_data', 10, 3 );
