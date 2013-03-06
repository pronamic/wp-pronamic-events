<?php

/**
 * Gravity Forms post data
 *
 * @param array $post_data
 * @param array $form
 * @param array $lead
 */
function pronamic_events_gform_post_data( $post_data, $form, $lead ) {
	$start_date = $end_date = null;

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
