# [Pronamic Events](http://www.happywp.com/plugins/pronamic-events/)

Pronamic Events is a basic plugin to add some Events functionality.

## WordPress Query

```php
$query = new WP_Query( array(
	'post_type'                 => 'pronamic_event',
	'pronamic_event_date_after' => strtotime( 'today' ), // default
	'orderby'                   => 'pronamic_event_start_date', // default
) );
```

```php
$query = new WP_Query( array(
	'post_type'                 => 'pronamic_event',
	'pronamic_event_date_after' => strtotime( '-1 month' ),
	'orderby'                   => 'date',
) );
```

```php
$query = new WP_Query( array(
	'post_type'                 => 'pronamic_event',
	'pronamic_event_date_after' => false,
	'orderby'                   => 'pronamic_event_start_date',
) );
```

### Parameters

#### pronamic_event_date_after

Type: `int`  
Default: `strtotime( 'today' )`

#### orderby

Type: `string`  
Default: `pronamic_event_start_date`


## WordPress Filters

### pronamic_event_date_after

```php
function prefix_pronamic_events_date_offset( $offset ) {
	$offset = '-2 days';
	
	return $offset;
}

add_filter( 'pronamic_events_date_offset', 'prefix_pronamic_events_date_offset' );
```


## Post Class

The Pronamic Events will automatic add the `event-ended` post class if an 
event is ended.


## Resources

*	http://wp-events-plugin.com/
*	http://forums.devshed.com/php-development-5/saving-recurrence-schedule-database-666571.html
*	http://www.yiiframework.com/extension/recur/
*	http://stackoverflow.com/questions/3566762/php-date-recurrence-library
*	http://stackoverflow.com/questions/579892/php-calendar-recurrence-logic
*	https://github.com/briannesbitt/Carbon
*	http://www.php.net/manual/en/book.datetime.php
*	http://stephenharris.info/date-intervals-in-php-5-2/
