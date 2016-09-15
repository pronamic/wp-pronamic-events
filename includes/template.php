<?php

/**
 * Return formatted start date
 *
 * @see https://github.com/WordPress/WordPress/blob/3.5.1/wp-includes/general-template.php#L1364
 * @return the start date
 */
function pronamic_get_the_start_date( $format = null, $post_id = null ) {
	$format  = ( null === $format ) ? get_option( 'date_format' ) : $format;
	$post_id = ( null === $post_id ) ? get_the_ID() : $post_id;

	$date = get_post_meta( $post_id, '_pronamic_event_start_date', true );

	if ( ! empty( $date ) ) {
		return mysql2date( $format, $date );
	}
}

/**
 * Echo formatted start date
 *
 * @return echo the start date
 */
function pronamic_the_start_date( $format = null, $post_id = null ) {
	echo esc_html( pronamic_get_the_start_date( $format, $post_id ) );
}

/**
 * Conditional tag for start date
 *
 * @return boolean true if has start date, false otherwise
 */
function pronamic_has_start_date( $post_id = null ) {
	$post_id = ( null === $post_id ) ? get_the_ID() : $post_id;

	$date = get_post_meta( $post_id, '_pronamic_event_start_date', true );

	return ! empty( $date );
}

////////////////////////////////////////////////////////////

/**
 * Return formatted end date
 *
 * @see https://github.com/WordPress/WordPress/blob/3.5.1/wp-includes/general-template.php#L1364
 * @return the end date
 */
function pronamic_get_the_end_date( $format = null, $post_id = null ) {
	$format  = ( null === $format ) ? get_option( 'date_format' ) : $format;
	$post_id = ( null === $post_id ) ? get_the_ID() : $post_id;

	$date = get_post_meta( $post_id, '_pronamic_event_end_date', true );

	if ( ! empty( $date ) ) {
		return mysql2date( $format, $date );
	}
}

/**
 * Echo formatted end date
 *
 * @return echo the end date
 */
function pronamic_the_end_date( $format = null, $post_id = null ) {
	echo esc_html( pronamic_get_the_end_date( $format, $post_id ) );
}

/**
 * Conditional tag for end date
 *
 * @return boolean true if has end date, false otherwise
 */
function pronamic_has_end_date( $post_id = null ) {
	$post_id = ( null === $post_id ) ? get_the_ID() : $post_id;

	$date = get_post_meta( $post_id, '_pronamic_event_end_date', true );

	return ! empty( $date );
}

////////////////////////////////////////////////////////////

/**
 * Get the location of the post
 *
 * @return string
 */
function pronamic_get_the_location( $post_id = null ) {
	$post_id = ( null === $post_id ) ? get_the_ID() : $post_id;

	return get_post_meta( $post_id, '_pronamic_location', true );
}

/**
 * Echo the location of the post
 */
function pronamic_the_location( $post_id = null ) {
	echo esc_html( pronamic_get_the_location( $post_id ) );
}

/**
 * Conditional tag for location
 *
 * @return boolean true if post has location, false otherwise
 */
function pronamic_has_location( $post_id = null ) {
	$location = pronamic_get_the_location( $post_id );

	return ! empty( $location );
}

////////////////////////////////////////////////////////////

/**
 * Get the URL of the event
 *
 * @return string
 */
function pronamic_event_get_the_url( $post_id = null ) {
	$post_id = ( null === $post_id ) ? get_the_ID() : $post_id;

	return get_post_meta( $post_id, '_pronamic_event_url', true );
}

/**
 * Echo the URL of the event
 */
function pronamic_event_the_url( $post_id = null ) {
	echo esc_html( pronamic_event_get_the_url( $post_id ) );
}

/**
 * Conditional tag for Pronamic event
 *
 * @return boolean true if post has URL, false otherwise
 */
function pronamic_event_has_url( $post_id = null ) {
	$url = pronamic_event_get_the_url( $post_id );

	return ! empty( $url );
}
