# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]


## 1.3.0

- Feature - Added schema.org data via Yoast SEO plugin integration.
- Feature - Added setting for date format of repeatable events slugs.
- Feature - Added support for updating time of existing repeatable events.

## 1.2.5

- Feature - Custom date slug support for repeatable events.
- Fixed - Fix non-static method message.

## 1.2.3

- Fixed - Make sure to check if $status is a array `get_the_terms ` can also return `false` or a `WP_Error`.
- Tweak - Improved support for non public post types and make sure to add start and end date columns after title column.

## 1.2.2

- Fixed - Flush rewrite rules on plugin activation.
- Tweak - Use Composer for autoload.
- Feature - Added a template loader for default templates for single event and archive event.
- Feature - Added default template for archive and singular.
- Tweak - Removed jQuery date picket language files https://make.wordpress.org/core/2016/07/06/jquery-ui-datepicker-localization-in-4-6/.
- Tweak - Switched to Bower and use WordPress date picker style from https://github.com/xwp/wp-jquery-ui-datepicker-skins.

## 1.2.1

- Tweak - WordPress Coding Standards optimizations.

## 1.2.0

- Feature - Added support for 'All day' flag.
- Tweak - Changed text domain from 'pronamic_events' to 'pronamic-events'.

## 1.1.1

- Tweak - Added 'pronamic_events_date_offset' filter.

## 1.1.0

- Tweak - Moved event details meta box from the side to the normal part of the edit post screen.
- Tweak - WordPress Coding Standards optimizations.
- Feature - Added support for recurring event, create daily, weekly, monthly and yearly event patterns.
- Tweak - Use post type supports to enable event meta boxes.
- Tweak - Replaced custom menu icon with an WordPress dash icon.
- Tweak - Removed the deprecated WordPress screen icon.
- Feature - Added event status and automatisch upcoming and passed event status updater.

## 1.0.0

- Added French translations thanks to Gwendal Leriche.
- Added a new filter to the Pronamic Events Archive timestamp. 'pronamic_event_parse_query_timestamp'. It expects a timestamp returned.
- Display events sorted by start date with a fallback to publication date.

## 0.2.2

- Added Brazilian Portuguese translation thanks to Gustavo Magalhães

## 0.2.1

- Improved support for Gravity Forms + Custom Post Types plugin

## 0.2.0

- Improved saving of start and end dates
- Added an "Pronamic Events" widget
- jQuery UI datepicker i18n
- Events query filter end date greater then today (midnight) instead of -1 day 

## 0.1.3

- Improved start and end date columns
- Moved functions into classes and seperated files

## 0.1.2

- Added template functions for the location
- Improved the documentation in the readme.txt file
- Added admin sortable columns for start and end date
- Added settings page for events base slug

## 0.1.1

- Added datepicker

## 0.1

- Initial release
