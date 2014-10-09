<?php

/**
 * Check if the query is requesting event post type
 *
 * @param WP_Query $query
 * @return boolean true if events are queried, false otherwises
 */
function is_pronamic_events_query( WP_Query $query ) {
	$is_pronamic_events = false;

	if ( $query->is_archive() && ! $query->is_tax( 'pronamic_event_status' ) ) {
		// Check 'post_type' var
		// Note: post_type could also be an array
		$post_type = $query->get( 'post_type' );

		if ( ! empty( $post_type ) && ! is_array( $post_type ) ) {
			$is_pronamic_events = post_type_supports( $post_type, 'pronamic_event' );
		}

		if ( ! $is_pronamic_events ) {
			// Check queried object
			$object = $query->get_queried_object();

			$is_pronamic_events = isset( $object, $object->name ) && post_type_supports( $object->name, 'pronamic_event' );
		}
	}

	return $is_pronamic_events;
}

function pronamic_events_get_start_date_meta( $timestamp, array &$meta = array() ) {
	$date     = date( 'Y-m-d H:i:s', $timestamp );
	$date_gmt = get_gmt_from_date( $date );

	$meta['_pronamic_start_date']           = $timestamp;
	$meta['_pronamic_event_start_date']     = $date;
	$meta['_pronamic_event_start_date_gmt'] = $date_gmt;

	return $meta;
}

function pronamic_events_get_end_date_meta( $timestamp, array &$meta = array() ) {
	$date     = date( 'Y-m-d H:i:s', $timestamp );
	$date_gmt = get_gmt_from_date( $date );

	$meta['_pronamic_end_date']           = $timestamp;
	$meta['_pronamic_event_end_date']     = $date;
	$meta['_pronamic_event_end_date_gmt'] = $date_gmt;

	return $meta;
}
