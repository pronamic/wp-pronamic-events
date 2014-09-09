--
-- Update event start dates
--
INSERT 
	INTO 
		gbh_postmeta ( post_id, meta_key, meta_value )
	SELECT
		post.ID AS post_id,
		'_pronamic_event_start_date' AS meta_key,
		-- FROM_UNIXTIME( MAX( IF( meta.meta_key = '_event_startdate', meta.meta_value, NULL ) ) ) AS meta_start_data,
		-- MAX( IF( meta.meta_key = '_event_starttime', meta.meta_value, NULL ) ) AS meta_start_time,
		ADDTIME( FROM_UNIXTIME( MAX( IF( meta.meta_key = '_event_startdate', meta.meta_value, NULL ) ) ), MAX( IF( meta.meta_key = '_event_starttime', meta.meta_value, '0' ) ) ) AS meta_value
	FROM
		gbh_posts AS post
			LEFT JOIN
		gbh_postmeta AS meta
				ON post.ID = meta.post_id
	WHERE
		post_type = 'pronamic_event'
			AND
		ID NOT IN (
			SELECT post_id FROM gbh_postmeta WHERE meta_key = '_pronamic_event_start_date'
		)
	GROUP BY
		post.ID
	;
	
INSERT 
	INTO 
		gbh_postmeta ( post_id, meta_key, meta_value )
	SELECT
		post.ID AS post_id,
		'_pronamic_start_date' AS meta_key,
		UNIX_TIMESTAMP( MAX( IF( meta.meta_key = '_pronamic_event_start_date', meta.meta_value, NULL ) ) ) AS meta_value
	FROM
		gbh_posts AS post
			LEFT JOIN
		gbh_postmeta AS meta
				ON post.ID = meta.post_id
	WHERE
		post_type = 'pronamic_event'
			AND
		ID NOT IN (
			SELECT post_id FROM gbh_postmeta WHERE meta_key = '_pronamic_start_date'
		)
	GROUP BY
		post.ID
	;
--
-- Update event end dates
--
INSERT
	INTO 
		gbh_postmeta ( post_id, meta_key, meta_value )
	SELECT
		post.ID AS post_id,
		'_pronamic_event_end_date' AS meta_key,
		-- FROM_UNIXTIME( MAX( IF( meta.meta_key = '_event_enddate', meta.meta_value, NULL ) ) ) AS meta_start_data,
		-- MAX( IF( meta.meta_key = '_event_endtime', meta.meta_value, NULL ) ) AS meta_start_time,
		ADDTIME( FROM_UNIXTIME( MAX( IF( meta.meta_key = '_event_enddate', meta.meta_value, NULL ) ) ), MAX( IF( meta.meta_key = '_event_endtime', meta.meta_value, '0' ) ) ) AS meta_value
	FROM
		gbh_posts AS post
			LEFT JOIN
		gbh_postmeta AS meta
				ON post.ID = meta.post_id
	WHERE
		post_type = 'pronamic_event'
			AND
		ID NOT IN (
			SELECT post_id FROM gbh_postmeta WHERE meta_key = '_pronamic_event_end_date'
		)
	GROUP BY
		post.ID
	;
	
INSERT 
	INTO 
		gbh_postmeta ( post_id, meta_key, meta_value )
	SELECT
		post.ID AS post_id,
		'_pronamic_end_date' AS meta_key,
		UNIX_TIMESTAMP( MAX( IF( meta.meta_key = '_pronamic_event_end_date', meta.meta_value, NULL ) ) ) AS meta_value
	FROM
		gbh_posts AS post
			LEFT JOIN
		gbh_postmeta AS meta
				ON post.ID = meta.post_id
	WHERE
		post_type = 'pronamic_event'
			AND
		ID NOT IN (
			SELECT post_id FROM gbh_postmeta WHERE meta_key = '_pronamic_end_date'
		)
	GROUP BY
		post.ID
	;

-- 
-- http://stackoverflow.com/questions/8058670/mysql-rows-to-columns-join-statement-problems
-- http://www.artfulsoftware.com/infotree/queries.php#77
-- 