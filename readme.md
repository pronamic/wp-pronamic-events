# [Pronamic Events](http://www.happywp.com/plugins/pronamic-events/)

Pronamic Events is a basic plugin to add some Events functionality.

## WordPress Query

```php
$query = new WP_Query(
    array(
        'post_type'                 => 'pronamic_event',
        'pronamic_event_date_after' => strtotime( 'today' ), // default
        'orderby'                   => 'pronamic_event_start_date', // default
    )
);
```

```php
$query = new WP_Query(
    array(
        'post_type'                 => 'pronamic_event',
        'pronamic_event_date_after' => strtotime( '-1 month' ),
        'orderby'                   => 'date',
    )
);
```

```php
$query = new WP_Query(
    array(
        'post_type'                 => 'pronamic_event',
        'pronamic_event_date_after' => false,
        'orderby'                   => 'pronamic_event_start_date',
    )
);
```

```php
$query = new WP_Query(
    array(
        'post_type'                 => 'pronamic_event',
        'pronamic_event_date_after' => false,
        'orderby'                   => 'pronamic_event_start_date',
        'order'                     => 'ASC',
        'meta_query'                => array(
            array(
                'key'     => '_pronamic_end_date',
                'value'   => array(
                    strtotime( '01-01-2016' ),
                    strtotime( '01-05-2016' ),
                ),
                'compare' => 'BETWEEN',
                'type'    => 'NUMERIC',
            ),
        ),
    )
);
```

### Parameters

#### pronamic_event_date_after

Type: `int`  
Default: `strtotime( 'today' )`

#### orderby

Type: `string`  
Default: `pronamic_event_start_date`


## WordPress Filters

### pronamic_events_date_offset

```php
function prefix_pronamic_events_date_offset( $offset = 'today' ) {
	$offset = '-2 days';
	
	return $offset;
}

add_filter( 'pronamic_events_date_offset', 'prefix_pronamic_events_date_offset' );
```


## Post Class

The Pronamic Events will automatic add the `event-ended` post class if an 
event is ended.

## Multi-block plugin

- https://wordpress.stackexchange.com/questions/390282/possible-to-use-wordpress-create-block-with-multiple-blocks
- https://www.designbombs.com/reusing-functionality-for-wordpress-plugins-with-blocks/
- https://wordpress.stackexchange.com/questions/346562/file-structure-and-react-setup-when-creating-multiple-gutenberg-blocks
- https://github.com/pronamic/wp-pronamic-pay-fundraising

## Resources

*	http://wp-events-plugin.com/
*	http://forums.devshed.com/php-development-5/saving-recurrence-schedule-database-666571.html
*	http://www.yiiframework.com/extension/recur/
*	http://stackoverflow.com/questions/3566762/php-date-recurrence-library
*	http://stackoverflow.com/questions/579892/php-calendar-recurrence-logic
*	https://github.com/briannesbitt/Carbon
*	http://www.php.net/manual/en/book.datetime.php
*	http://stephenharris.info/date-intervals-in-php-5-2/
