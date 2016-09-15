<?php

// Flush rewrite rules, same setup as WordPress permalinks options page:
// https://github.com/WordPress/WordPress/blob/3.4.2/wp-admin/options-permalink.php#L143
flush_rewrite_rules();

?>
<div class="wrap">
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

	<form name="form" action="options.php" method="post">
		<?php settings_fields( 'pronamic_events' ); ?>

		<?php do_settings_sections( 'pronamic_events' ); ?>

		<?php submit_button(); ?>
	</form>
</div>
