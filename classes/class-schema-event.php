<?php

/**
 * Schema.org Event generator.
 *
 * @author  Reüel van der Steege
 * @since   1.3.0
 * @version 1.3.0
 */
class Pronamic_Events_Schema_Event extends Yoast\WP\SEO\Generators\Schema\Abstract_Schema_Piece {
	/**
	 * Event attendance mode.
	 */
	const SCHEMA_MIXED_ATTENDANCE_MODE   = 'https://schema.org/MixedEventAttendanceMode';
	const SCHEMA_OFFLINE_ATTENDANCE_MODE = 'https://schema.org/OfflineEventAttendanceMode';
	const SCHEMA_ONLINE_ATTENDANCE_MODE  = 'https://schema.org/OnlineEventAttendanceMode';

	/**
	 * Event status type.
	 */
	const SCHEMA_EVENT_STATUS_CANCELLED    = 'https://schema.org/EventCancelled';
	const SCHEMA_EVENT_STATUS_MOVED_ONLINE = 'https://schema.org/EventMovedOnline';
	const SCHEMA_EVENT_STATUS_POSTPONED    = 'https://schema.org/EventPostponed';
	const SCHEMA_EVENT_STATUS_RESCHEDULED  = 'https://schema.org/EventRescheduled';
	const SCHEMA_EVENT_STATUS_SCHEDULED    = 'https://schema.org/EventScheduled';

	/**
	 * WordPress SEO context object.
	 *
	 * @var \WPSEO_Schema_Context
	 */
	public $context;

	/**
	 * Event generator constructor.
	 *
	 * @param \WPSEO_Schema_Context $context WordPress SEO context object.
	 */
	public function __construct( \WPSEO_Schema_Context $context ) {
		$this->context = $context;
	}

	/**
	 * Determines whether or not a piece should be added to the graph.
	 *
	 * @return bool
	 */
	public function is_needed() {
		if ( null === $this->context->post ) {
			return false;
		}

		$post_id = $this->context->post->ID;

		$post_type = \get_post_type( $post_id );

		return \post_type_supports( $post_type, 'pronamic_event' );
	}

	/**
	 * Render a list of questions, referencing them by ID.
	 *
	 * @link https://developer.yoast.com/features/schema/integration-guidelines/
	 * @link https://developers.google.com/search/docs/data-types/event#standard-event
	 * @link https://search.google.com/test/rich-results
	 * @return array $data Our Schema graph.
	 */
	public function generate() {
		$post_id = $this->context->post->ID;

		$data = array(
			'@type'               => 'Event',
			'name'                => get_the_title( $post_id ),
			'startDate'           => pronamic_get_the_start_date( \DATE_ATOM, $post_id ),
			'endDate'             => pronamic_get_the_end_date( \DATE_ATOM, $post_id ),
			'eventAttendanceMode' => $this->get_event_attendance_mode( $post_id ),
			'eventStatus'         => $this->get_event_status( $post_id ),
			'description'         => $this->get_event_description( $post_id ),
			'location'            => pronamic_get_the_location( $post_id ),
			'image'               => $this->get_event_images( $post_id ),
		);

		$data = array_filter( $data );

		return $data;
	}

	/**
	 * Get event attendance mode.
	 *
	 * @param int $post_id Event post ID.
	 * @return string
	 * @link https://schema.org/EventAttendanceModeEnumeration
	 */
	private function get_event_attendance_mode( $post_id ) {
		$location = pronamic_get_the_location( $post_id );

		if ( ! empty( $location ) ) {
			return self::SCHEMA_OFFLINE_ATTENDANCE_MODE;
		}

		return self::SCHEMA_ONLINE_ATTENDANCE_MODE;
	}

	/**
	 * Get event status type.
	 *
	 * @param int $post_id Event post ID.
	 * @return string
	 * @link https://schema.org/EventStatusType
	 */
	private function get_event_status( $post_id ) {
		return self::SCHEMA_EVENT_STATUS_SCHEDULED;
	}

	/**
	 * Get event description.
	 *
	 * @param int $post_id Event post ID.
	 *
	 * @return string|null
	 */
	private function get_event_description( $post_id ) {
		$post = get_post( $post_id );

		if ( null === $post ) {
			return null;
		}

		// Use post excerpt content if set.
		$post_excerpt = $post->post_excerpt;

		if ( ! empty( $post_excerpt ) ) {
			return $post_excerpt;
		}

		// Get the content part before the read more link.
		$content = get_extended( $post->post_content );

		return $content['main'];
	}

	/**
	 * Get event images.
	 *
	 * @param int $post_id Event post ID.
	 * @return array|null
	 */
	private function get_event_images( $post_id ) {
		$images = array();

		// Post thumbnail.
		$thumbnail_url = get_the_post_thumbnail_url( $post_id );

		if ( false !== $thumbnail_url ) {
			$images[] = $thumbnail_url;
		}

		// Return.
		if ( empty( $images ) ) {
			return null;
		}

		return $images;
	}
}
