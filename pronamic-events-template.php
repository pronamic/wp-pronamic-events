<?php

/**
 * Return formatted start date
 */
function pronamic_get_the_start_date( $format = null, $post_id = null ) {
	$format = ( null === $format ) ? get_option( 'date_format' ) : $format;
	$post_id = ( null === $post_id ) ? get_the_ID() : $post_id;

	$start_date = get_post_meta( $post_id, '_pronamic_start_date', true );

	if( is_numeric( $start_date ) ) {
		return date_i18n( $format, $start_date );
	}
}

/**
 * Echo formatted start date
 */
function pronamic_the_start_date( $format = null, $post_id = null ) {
	echo pronamic_get_the_start_date( $format, $post_id );
}

/**
 * Conditional tag for start date
 */
function pronamic_has_start_date( $post_id = null ) {
	$post_id = ( null === $post_id ) ? get_the_ID() : $post_id;

	$start_date = get_post_meta( $post_id, '_pronamic_start_date', true );

	return ! empty( $start_date );
}

////////////////////////////////////////////////////////////

/**
 * Return formatted end date
 */
function pronamic_get_the_end_date( $format = null, $post_id = null ) {
	$format = ( null === $format ) ? get_option( 'date_format' ) : $format;
	$post_id = ( null === $post_id ) ? get_the_ID() : $post_id;

	$end_date = get_post_meta( $post_id, '_pronamic_end_date', true );

	if( is_numeric( $end_date ) ) {
		return date_i18n( $format, $end_date );
	}
}

/**
 * Echo formatted end date
 */
function pronamic_the_end_date( $format = null, $post_id = null ) {
	echo pronamic_get_the_end_date( $format, $post_id );
}

/**
 * Conditional tag for end date
 */
function pronamic_has_end_date( $post_id = null ) {
	$post_id = ( null === $post_id ) ? get_the_ID() : $post_id;

	$end_date = get_post_meta( $post->ID, '_pronamic_end_date', true );

	return ! empty( $end_date );
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
	echo pronamic_get_the_location( $post_id );
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
