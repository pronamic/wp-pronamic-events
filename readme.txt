=== Pronamic Events ===
Contributors: pronamic, remcotolsma, kjtolsma 
Tags: pronamic, events, agenda
Donate link: http://pronamic.eu/donate/?for=wp-plugin-pronamic-events&source=wp-plugin-readme-txt
Requires at least: 3.0
Tested up to: 3.5.1
Stable tag: 0.1.3

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
and location. You can set this up by adding Custom Fields from the Post Fields section and give them the correct custom field
names:

*	Start Date - Field Type = Date, Name = _pronamic_start_date_date
*	Start Time - Field Type = Time, Name = _pronamic_start_date_time
*	End Date - Field Type = Date, Name = _pronamic_end_date_date
*	End Time - Field Type = Time, Name = _pronamic_end_date_time
*	Location - Field Type = Text, Name = _pronamic_location


== Installation ==

Upload the Pronamic Events folder to your wp-content/plugins folder.


== Screenshots ==

1. Add new event


== Changelog ==

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

