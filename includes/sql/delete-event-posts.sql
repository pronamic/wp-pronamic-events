DELETE FROM wp_posts WHERE post_type = 'pronamic_event';

DELETE FROM wp_postmeta WHERE post_id NOT IN ( SELECT ID FROM wp_posts );
