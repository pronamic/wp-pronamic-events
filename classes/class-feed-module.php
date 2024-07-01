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
	 * Constructs and initializes an Pronamic Events feed module object.
	 */
	public function __construct( Pronamic_Events_Plugin $plugin ) {
		$this->plugin = $plugin;

		// Actions.
		add_action( 'rss2_ns', array( $this, 'rss2_ns' ) );
		add_action( 'rss2_item', array( $this, 'rss2_item' ) );
	}

    /**
     * Determine if the post type supports the Pronamic Events feed module.
     *
     * @return boolean
     */
    protected function is_supported_post_type($post_type = null) {
        if ( ! $post_type ) {
            return false;
        }

        $post_types = (array) $post_type;

        foreach ( $post_types as $post_type ) {
            if ( post_type_supports( $post_type, 'pronamic_event' ) ) {
                return true;
            }
        }

        return false;
    }

	/**
	 * RSS2 namespaces.
	 *
	 * @see https://theeventscalendar.com/knowledgebase/customize-rss-feed/
	 * @see http://web.resource.org/rss/1.0/modules/event/
	 * @see https://github.com/WordPress/WordPress/blob/4.9/wp-includes/feed-rss2.php#L30-L37
	 */
	public function rss2_ns() {
		if ( ! $this->is_supported_post_type( get_query_var( 'post_type' ) ) ) {
			return;
		}

		echo 'xmlns:ev="http://purl.org/rss/2.0/modules/event/"', "\n";
	}

	/**
	 * RSS2 item.
	 *
	 * @see https://theeventscalendar.com/knowledgebase/customize-rss-feed/
	 * @see http://web.resource.org/rss/1.0/modules/event/
	 * @see https://github.com/WordPress/WordPress/blob/4.9/wp-includes/feed-rss2.php#L113-L120
	 */
	public function rss2_item() {
		if ( ! $this->is_supported_post_type( get_post_type() ) ) {
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
