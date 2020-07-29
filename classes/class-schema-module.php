<?php

/**
 * Pronamic Events schema.org integration through WordPress SEO plugin.
*
* @since 1.3.0
*/
class Pronamic_Events_Schema_Module {
	/**
	 * Constructs schema.org module.
	 *
	 * @return void
	 */
	public function __construct() {
		// Make sure to load after WordPress SEO (loads in `plugins_loaded` with priority `14`).
		\add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ), 20 );
	}

	/**
	 * Plugins loaded.
	 *
	 * @return void
	 */
	public function plugins_loaded() {
		// Check if required WordPress SEO plugin is available.
		if ( ! \is_callable( 'YoastSEO' ) ) {
			return;
		}

		// Maybe add schema.org piece.
		\add_filter( 'wpseo_schema_graph_pieces', array( $this, 'add_graph_piece_event' ), 10, 2 );
	}

	/**
	 * Add Schema pieces.
	 *
	 * @param array                 $pieces  Graph pieces.
	 * @param \WPSEO_Schema_Context $context Object with context.
	 *
	 * @return array
	 */
	public function add_graph_piece_event( $pieces, $context ) {
		if ( ! class_exists( '\Yoast\WP\SEO\Generators\Schema\Abstract_Schema_Piece' ) ) {
			return $pieces;
		}

		$pieces[] = new Pronamic_Events_Schema_Event( $context );

		return $pieces;
	}
}
