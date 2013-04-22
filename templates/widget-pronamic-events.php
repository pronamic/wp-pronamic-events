<?php if ( have_posts() ) : ?>

	<?php

	if ( ! empty( $title ) ) {
		echo $before_title . $title . $after_title;
	}

	?>

	<ul>

		<?php while ( have_posts() ) : the_post(); ?>
		
			<li>
				<a href="<?php the_permalink() ?>" title="<?php echo esc_attr( get_the_title() ? get_the_title() : get_the_ID() ); ?>"><?php if ( get_the_title() ) the_title(); else the_ID(); ?></a><br />
				
				<?php pronamic_the_start_date(); ?> / <?php pronamic_the_end_date(); ?><br />
			</li>

		<?php endwhile; ?>

	</ul>

<?php endif; ?>