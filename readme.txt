=== Pronamic Events ===
Contributors: pronamic, remcotolsma, kjtolsma 
Tags: pronamic, events, agenda
Donate link: http://pronamic.eu/donate/?for=wp-plugin-pronamic-events&source=wp-plugin-readme-txt
Requires at least: 3.0
Tested up to: 4.0
Stable tag: 1.1.0

Pronamic Events is a basic plugin to add some Events functionality.


== Description ==

This plugin uses custom post types to add some Events functionality. Pronamic Events allows you to add, edit and remove events.

= Template Functions =

*	Start Date
	*	pronamic_get_the_start_date( $format = null )
	*	pronamic_the_start_date( $format = null )
	*	pronamic_has_start_date()
*	End Date
	*	pronamic_get_the_end_date( $format = null )
	*	pronamic_the_end_date( $format = null )
	*	pronamic_has_end_date()
*	Location
	*	pronamic_get_the_location()
	*	pronamic_the_location()
	*	pronamic_has_location()

= Meta Keys =

*	_pronamic_start_date
*	_pronamic_end_date
*	_pronamic_location

= Gravity Forms =

With [Gravity Forms](http://www.gravityforms.com/) and the [Gravity Forms + Custom Post Types](http://wordpress.org/extend/plugins/gravity-forms-custom-post-types/)
you can create an form to create event posts. In most cases you also want to automatic fill in the event start date, end date 
and location. You can set this up by checking one of the options on the date/time fields.

*	Is Event Start Date
*	Is Event Start Time
*	Is Event End Date
*	Is Event End Time

For the other fields you can add Custom Fields from the Post Fields 
section and give them the correct custom field names:

*	Location - Field Type = Text, Name = _pronamic_location
*	Website - Field Type = Website, Name = _pronamic_event_url


== Installation ==

Upload the Pronamic Events folder to your wp-content/plugins folder.


== Developers ==

*	php ~/wp/svn/i18n-tools/makepot.php wp-plugin ~/wp/git/pronamic-events ~/wp/git/pronamic-events/languages/pronamic_events.pot


== Screenshots ==

1.	Add new event


== Changelog ==

= 1.1.0 =
*	Tweak - Moved event details meta box from the side to the normal part of the edit post screen.
*	Tweak - WordPress Coding Standards optimizations.
*	Feature - Added support for recurring event, create daily, weekly, monthly and yearly event patterns.
*	Tweak - Use post type supports to enable event meta boxes.
*	Tweak - Replaced custom menu icon with an WordPress dash icon.
*	Tweak - Removed the deprecated WordPress screen icon.
*	Feature - Added event status and automatisch upcoming and passed event status updater.

= 1.0.0 =
*	Added French translations thanks to Gwendal Leriche.
*	Added a new filter to the Pronamic Events Archive timestamp. 'pronamic_event_parse_query_timestamp'. It expects a timestamp returned.
*	Display events sorted by start date with a fallback to publication date.

= 0.2.2 =
*	Added Brazilian Portuguese translation thanks to Gustavo Magalhães

= 0.2.1 =
*	Improved support for Gravity Forms + Custom Post Types plugin

= 0.2.0 =
*	Improved saving of start and end dates
*	Added an "Pronamic Events" widget
*	jQuery UI datepicker i18n
*	Events query filter end date greater then today (midnight) instead of -1 day 

= 0.1.3 =
*	Improved start and end date columns
*	Moved functions into classes and seperated files

= 0.1.2 =
*	Added template functions for the location
*	Improved the documentation in the readme.txt file
*	Added admin sortable columns for start and end date
*	Added settings page for events base slug

= 0.1.1 =
*	Added datepicker

= 0.1 =
*	Initial release


== Links ==

*	[Pronamic](http://pronamic.eu/)
*	[Remco Tolsma](http://remcotolsma.nl/)
*	[Karel-Jan Tolsma](http://kareljantolsma.nl/)
*	[Markdown's Syntax Documentation][markdown syntax]

[markdown syntax]: http://daringfireball.net/projects/markdown/syntax
		"Markdown is what the parser uses to process much of the readme file"


== Pronamic plugins ==

*	[Pronamic Google Maps](http://wordpress.org/extend/plugins/pronamic-google-maps/)
*	[Gravity Forms (nl)](http://wordpress.org/extend/plugins/gravityforms-nl/)
*	[Pronamic Page Widget](http://wordpress.org/extend/plugins/pronamic-page-widget/)
*	[Pronamic Page Teasers](http://wordpress.org/extend/plugins/pronamic-page-teasers/)
*	[Maildit](http://wordpress.org/extend/plugins/maildit/)
*	[Pronamic Framework](http://wordpress.org/extend/plugins/pronamic-framework/)
*	[Pronamic iDEAL](http://wordpress.org/extend/plugins/pronamic-ideal/)

