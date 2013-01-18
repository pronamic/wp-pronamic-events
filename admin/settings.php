<?php 

// Flush rewrite rules, same setup as WordPress permalinks options page:
// https://github.com/WordPress/WordPress/blob/3.4.2/wp-admin/options-permalink.php#L143
flush_rewrite_rules();

?>
<div class="wrap">
	<?php screen_icon(); ?>

	<h2>
		<?php _e( 'Events Settings', 'pronamic_events' ); ?>
	</h2>

	<form name="form" action="options.php" method="post">
		<?php settings_fields( 'pronamic_events' ); ?>

		<?php do_settings_sections( 'pronamic_events' ); ?>

		<?php submit_button(); ?>
	</form>
</div>