<?php

	if ( ! empty( $title ) ) {
		echo isset($before_title) ? $before_title : null;
		echo '<h1 class="widget-title">' . $title . '</h1>';
		echo isset($after_title) ? $after_title : null;
	}

if ( have_posts() ) { ?>

	<ul>

		<?php while ( have_posts() ) : the_post(); ?>

			<li>
				<a href="<?php the_permalink() ?>" title="<?php echo esc_attr( get_the_title() ? get_the_title() : get_the_ID() ); ?>"><?php if ( get_the_title() ) { the_title(); } else { the_ID(); } ?></a><br />

				<?php pronamic_the_start_date(); ?> / <?php pronamic_the_end_date(); ?><br />
			</li>

		<?php endwhile; ?>

	</ul>

<?php 

} else {
	echo __( 'No upcoming events', 'pronamic_events' );
}