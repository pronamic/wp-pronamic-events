<?php

/**
 * Return formatted start date
 */
function pronamic_get_the_start_date( $format = null ) {
	global $post;

	if( $format == null ) {
		$format = get_option( 'date_format' );
	}

	$start_date = get_post_meta( $post->ID, '_pronamic_start_date', true );

	return date_i18n( $format, $start_date );
}

/**
 * Echo formatted start date
 */
function pronamic_the_start_date( $format = null ) {
	echo pronamic_get_the_start_date( $format );
}

/**
 * Conditional tag for start date
 */
function pronamic_has_start_date() {
	global $post;

	$start_date = get_post_meta( $post->ID, '_pronamic_start_date', true );

	return ! empty( $start_date );
}

////////////////////////////////////////////////////////////

/**
 * Return formatted end date
 */
function pronamic_get_the_end_date( $format = null ) {
	global $post;

	if( $format == null ) {
		$format = get_option( 'date_format' );
	}

	$end_date = get_post_meta( $post->ID, '_pronamic_end_date', true );

	return date_i18n( $format, $end_date );
}

/**
 * Echo formatted end date
 */
function pronamic_the_end_date( $format = null ) {
	echo pronamic_get_the_end_date( $format );
}

/**
 * Conditional tag for end date
 */
function pronamic_has_end_date() {
	global $post;

	$end_date = get_post_meta( $post->ID, '_pronamic_end_date', true );

	return ! empty( $end_date );
}
