UPDATE
	wp_postmeta
SET
	meta_value = meta_value + 7200
WHERE
	meta_key = '_pronamic_start_date'
;

UPDATE
	wp_postmeta
SET
	meta_value = meta_value + INTERVAL 2 HOUR
WHERE
	meta_key = '_pronamic_event_start_date'
;

UPDATE
	wp_postmeta
SET
	meta_value = meta_value + 7200
WHERE
	meta_key = '_pronamic_end_date'
;

UPDATE
	wp_postmeta
SET
	meta_value = meta_value + INTERVAL 2 HOUR
WHERE
	meta_key = '_pronamic_event_end_date'
;

--
-- Condition
--
UPDATE
	wp_postmeta
SET
	meta_value = meta_value + 7200
WHERE
	meta_key = '_pronamic_start_date'
		AND
	post_id IN (
		SELECT
			ID
		FROM
			wp_posts
		WHERE
			post_date < '2012-12-07'
	)
;

UPDATE
	wp_postmeta
SET
	meta_value = meta_value + INTERVAL 2 HOUR
WHERE
	meta_key = '_pronamic_event_start_date'
		AND
	post_id IN (
		SELECT
			ID
		FROM
			wp_posts
		WHERE
			post_date < '2012-12-07'
	)
;

--
-- Winter summer time
-- @see http://www.staff.science.uu.nl/~gent0113/wettijd/wettijd.htm
--
UPDATE
	wp_postmeta
SET
	meta_value = meta_value + INTERVAL 1 HOUR
WHERE
	meta_key = '_pronamic_event_end_date'
		AND
	CAST( meta_value AS DATETIME ) BETWEEN '2012-10-28' AND '2013-03-31'
		AND
	post_id IN (
		SELECT
			ID
		FROM
			wp_posts
		WHERE
			post_date < '2013-03-02 14:10'
	)
;

UPDATE
	wp_postmeta
SET
	meta_value = meta_value + INTERVAL 2 HOUR
WHERE
	meta_key = '_pronamic_event_end_date'
		AND
	CAST( meta_value AS DATETIME ) BETWEEN '2013-03-31' AND '2013-10-27'
		AND
	post_id IN (
		SELECT
			ID
		FROM
			wp_posts
		WHERE
			post_date < '2013-03-02 14:10'
	)
;

UPDATE
	wp_postmeta
SET
	meta_value = meta_value + INTERVAL 1 HOUR
WHERE
	meta_key = '_pronamic_event_end_date'
		AND
	CAST( meta_value AS DATETIME ) BETWEEN '2013-10-27' AND '2014-03-30'
		AND
	post_id IN (
		SELECT
			ID
		FROM
			wp_posts
		WHERE
			post_date < '2013-03-02 14:10'
	)
;

UPDATE
	wp_postmeta
SET
	meta_value = meta_value + INTERVAL 2 HOUR
WHERE
	meta_key = '_pronamic_event_end_date'
		AND
	CAST( meta_value AS DATETIME ) BETWEEN '2014-03-30' AND '2014-10-26'
		AND
	post_id IN (
		SELECT
			ID
		FROM
			wp_posts
		WHERE
			post_date < '2013-03-02 14:10'
	)
;

UPDATE
	wp_postmeta
SET
	meta_value = meta_value + INTERVAL 1 HOUR
WHERE
	meta_key = '_pronamic_event_end_date'
		AND
	CAST( meta_value AS DATETIME ) BETWEEN '2014-10-26' AND '2015-03-29'
		AND
	post_id IN (
		SELECT
			ID
		FROM
			wp_posts
		WHERE
			post_date < '2013-03-02 14:10'
	)
;

SELECT
	*
FROM
	wp_postmeta
WHERE
	meta_key = '_pronamic_event_end_date'
		AND
	CAST( meta_value AS DATETIME ) BETWEEN '2013-03-31' AND '2013-10-27'
		AND
	post_id IN (
		SELECT
			ID
		FROM
			wp_posts
		WHERE
			post_date < '2013-03-02 14:10'
	)
;