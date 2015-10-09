<?php

/**
 * Execute changes made in Pronamic Events 1.0.0
 *
 * @see https://github.com/WordPress/WordPress/blob/3.5.1/wp-admin/includes/upgrade.php#L413
 * @since 1.0.0
 */
function orbis_events_upgrade_100() {
	global $wpdb;

	// Dates
	$keys = array(
		'_pronamic_start_date' => '_pronamic_event_start_date',
		'_pronamic_end_date'   => '_pronamic_event_end_date',
	);

	foreach ( $keys as $old_key => $new_key ) {
		$query = "INSERT
			INTO
				$wpdb->postmeta ( post_id, meta_key, meta_value )
			SELECT
				post.ID AS post_id,
				%s AS meta_key,
				FROM_UNIXTIME( MAX( IF( meta.meta_key = %s, meta.meta_value, NULL ) ) ) AS meta_value
			FROM
				$wpdb->posts AS post
					LEFT JOIN
				$wpdb->postmeta AS meta
						ON post.ID = meta.post_id
			WHERE
				post_type = 'pronamic_event'
					AND
				ID NOT IN (
					SELECT post_id FROM $wpdb->postmeta WHERE meta_key = %s
				)
			GROUP BY
				post.ID
			;
		";

		$query = $wpdb->prepare( $query, $new_key, $old_key, $new_key ); // unprepared SQL

		$wpdb->query( $query ); // unprepared SQL
	}
}
