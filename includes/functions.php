<?php

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
