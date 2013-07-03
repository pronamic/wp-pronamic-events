<?php
/*
Plugin Name: Pronamic Events
Plugin URI: http://pronamic.eu/wordpress/events/
Description: This plugin add some basic Event functionality to WordPress

Version: 0.2.0
Requires at least: 3.0

Author: Pronamic
Author URI: http://pronamic.eu/

Text Domain: pronamic_events
Domain Path: /languages/

License: GPL

GitHub URI: https://github.com/pronamic/wp-pronamic-events
*/

require_once dirname( __FILE__ ) . '/classes/Pronamic_Events_Plugin.php';
require_once dirname( __FILE__ ) . '/classes/Pronamic_Events_Widget.php';
require_once dirname( __FILE__ ) . '/classes/Pronamic_Events_Plugin_Admin.php';

global $pronamic_events_plugin;

$pronamic_events_plugin = new Pronamic_Events_Plugin( __FILE__ );
