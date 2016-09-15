<?php

if ( ! empty( $title ) ) {
	echo $args['before_title'] . $title . $args['after_title']; // WPCS: XSS ok.
}

?>

<?php if ( have_posts() ) : ?>

	<ul>

		<?php while ( have_posts() ) : the_post(); ?>

			<li>
				<a href="<?php the_permalink() ?>"><?php the_title(); ?></a><br />

				<?php pronamic_the_start_date(); ?> / <?php pronamic_the_end_date(); ?>
			</li>

		<?php endwhile; ?>

	</ul>

<?php else : ?>

	<p>
		<em><?php esc_html_e( 'No upcoming events.', 'pronamic-events' ); ?></em>
	</p>

<?php endif; ?>
