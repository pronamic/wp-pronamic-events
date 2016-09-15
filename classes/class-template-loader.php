<?php

class Pronamic_Events_TemplateLoader {
	public $plugin;

	//////////////////////////////////////////////////

	/**
	 * Bootstrap
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;

		// Actions
		add_filter( 'template_include', array( $this, 'template_include' ) );
	}

	//////////////////////////////////////////////////

	/**
	 * Template include
	 *
	 * @see https://github.com/woothemes/woocommerce/blob/2.4.13/includes/class-wc-template-loader.php#L26-L84
	 */
	public function template_include( $template ) {
		$template_path = '';

		$find = array();
		$file = '';

		if ( is_singular( 'pronamic_event' ) ) {
			$file 	= 'single-pronamic_event.php';

			$find[] = $template_path . $file;
		} elseif ( is_post_type_archive( 'pronamic_event' ) ) {
			$file 	= 'archive-pronamic_event.php';

			$find[] = $template_path . $file;
		}

		if ( $file ) {
			$template = locate_template( $find );

			if ( ! $template ) {
				$template = plugin_dir_path( $this->plugin->file ) . '/templates/' . $file;
			}
		}

		return $template;
	}
}
