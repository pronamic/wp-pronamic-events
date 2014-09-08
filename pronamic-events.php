<?php
/*
Plugin Name: Pronamic Events
Plugin URI: http://www.happywp.com/plugins/pronamic-events/
Description: This plugin add some basic Event functionality to WordPress.

Version: 1.1.0
Requires at least: 3.0

Author: Pronamic
Author URI: http://www.pronamic.eu/

Text Domain: pronamic_events
Domain Path: /languages/

License: GPL

GitHub URI: https://github.com/pronamic/wp-pronamic-events
*/

require_once dirname( __FILE__ ) . '/classes/Pronamic_Events_Plugin.php';
require_once dirname( __FILE__ ) . '/classes/Pronamic_Events_Widget.php';
require_once dirname( __FILE__ ) . '/classes/Pronamic_Events_Plugin_Admin.php';
require_once dirname( __FILE__ ) . '/classes/Pronamic_Events_RepeatModule.php';
require_once dirname( __FILE__ ) . '/classes/Pronamic_Events_RepeatModule_Admin.php';
require_once dirname( __FILE__ ) . '/classes/Pronamic_Events_RepeatEventHelper.php';
require_once dirname( __FILE__ ) . '/classes/Pronamic_DateEventInterface.php';
require_once dirname( __FILE__ ) . '/classes/Pronamic_DateEvent.php';
require_once dirname( __FILE__ ) . '/classes/Pronamic_WP_Event.php';

global $pronamic_events_plugin;

$pronamic_events_plugin = new Pronamic_Events_Plugin( __FILE__ );
