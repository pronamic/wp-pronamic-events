<?php

/**
 * Pronamic Events plugin
 */
class Pronamic_Events_FeedModule {
	/**
	 * Plugin.
	 *
	 * @var Pronamic_Events_Plugin
	 */
	private $plugin;

	/**
	 * Construct feed module.
	 * 
	 * @param Pronamic_Events_Plugin $plugin Plugin.
	 */
	public function __construct( Pronamic_Events_Plugin $plugin ) {
		$this->plugin = $plugin;

		add_action( 'rss2_ns', array( $this, 'rss2_ns' ) );
		add_action( 'rss2_item', array( $this, 'rss2_item' ) );
	}

	/**
	 * RSS2 namespaces.
	 *
	 * @link https://theeventscalendar.com/knowledgebase/customize-rss-feed/
	 * @link http://web.resource.org/rss/1.0/modules/event/
	 * @link https://github.com/WordPress/WordPress/blob/4.9/wp-includes/feed-rss2.php#L30-L37
	 * @return void
	 */
	public function rss2_ns() {
		$post_types = \wp_parse_list( \get_query_var( 'post_type' ) );

		if ( ! \is_array( $post_types ) ) {
			return;
		}

		$event_post_types = \array_filter( $post_types, function( $post_type ) {
			return \post_type_supports( $post_type, 'pronamic_event' );
		} );

		if ( 0 === \count( $event_post_types ) ) {
			return;
		}

		echo 'xmlns:ev="http://purl.org/rss/2.0/modules/event/"', "\n";
	}

	/**
	 * RSS2 item.
	 *
	 * @link https://theeventscalendar.com/knowledgebase/customize-rss-feed/
	 * @link http://web.resource.org/rss/1.0/modules/event/
	 * @link https://github.com/WordPress/WordPress/blob/4.9/wp-includes/feed-rss2.php#L113-L120
	 * @return void
	 */
	public function rss2_item() {
		$post_type = \get_post_type();

		if ( false === $post_type ) {
			return;
		}

		if ( ! \post_type_supports( $post_type, 'pronamic_event' ) ) {
			return;
		}

		echo "\n";

		if ( pronamic_has_start_date() ) {
			echo "\t\t";

			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			printf(
				'<ev:startdate>%s</ev:startdate>',
				esc_html( pronamic_get_the_start_date( DATE_ATOM ) )
			);

			echo "\n";
		}

		if ( pronamic_has_end_date() ) {
			echo "\t\t";

			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			printf(
				'<ev:enddate>%s</ev:enddate>',
				esc_html( pronamic_get_the_end_date( DATE_ATOM ) )
			);

			echo "\n";
		}

		if ( pronamic_has_location() ) {
			echo "\t\t";

			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			printf(
				'<ev:location>%s</ev:location>',
				esc_html( pronamic_get_the_location() )
			);

			echo "\n";
		}
	}
}
