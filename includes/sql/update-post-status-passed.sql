--
-- Select
--
SELECT
	post.ID,
	post.post_title,
	post.post_type,
	post.post_status,
	meta_end_date.meta_value AS event_end_date
FROM
	mv_posts AS post
		INNER JOIN
	mv_postmeta AS meta_end_date
			ON post.id = meta_end_date.post_id AND meta_end_date.meta_key = '_pronamic_event_end_date'
WHERE
	post_type = 'market'
		AND
	post_status = 'publish'
		AND
	CAST( meta_end_date.meta_value AS DATETIME ) < NOW()
;

--
-- Update
--
UPDATE 
	mv_posts AS post
		INNER JOIN
	mv_postmeta AS meta_end_date
			ON post.id = meta_end_date.post_id AND meta_end_date.meta_key = '_pronamic_event_end_date'
SET
	post_status = 'passed' 
WHERE
	post_type = 'market'
		AND
	post_status = 'publish'
		AND
	CAST( meta_end_date.meta_value AS DATETIME ) < NOW()
;
