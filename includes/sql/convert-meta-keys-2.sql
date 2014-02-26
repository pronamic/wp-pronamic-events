--
-- Location
--

UPDATE 
	gbh_postmeta
SET
	meta_key = '_pronamic_location' 
WHERE
	meta_key = '_event_location'
;

--
-- Site / URL
--

UPDATE 
	gbh_postmeta
SET
	meta_key = '_pronamic_event_url' 
WHERE
	meta_key = '_event_url'
;