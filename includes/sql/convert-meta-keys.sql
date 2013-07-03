--
-- Location
--

UPDATE 
	wp_postmeta
SET
	meta_key = '_pronamic_location' 
WHERE
	meta_key = '_eisma_event_location'
;

--
-- Site / URL
--

UPDATE 
	wp_postmeta
SET
	meta_key = '_pronamic_event_url' 
WHERE
	meta_key = '_eisma_event_site'
;

--
-- Start date
--

UPDATE 
	wp_postmeta
SET
	meta_key = '_pronamic_start_date' 
WHERE
	meta_key = '_eisma_event_start_date'
;

--
-- End date
--

UPDATE 
	wp_postmeta
SET
	meta_key = '_pronamic_end_date' 
WHERE
	meta_key = '_eisma_event_end_date'
;
