<?php get_header(); ?>

<div id="primary">
	<div id="content" role="main">

		<?php while ( have_posts() ) : the_post(); ?>

			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="entry-header">
					<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
				</header>

				<div class="entry-content">
					<dl>
						<dt><?php esc_html_e( 'Start Date', 'pronamic-events' ); ?></dt>
						<dd><?php pronamic_the_start_date(); ?></dd>

						<dt><?php esc_html_e( 'End Date', 'pronamic-events' ); ?></dt>
						<dd><?php pronamic_the_end_date(); ?></dd>

						<?php if ( pronamic_has_location() ) : ?>

							<dt><?php esc_html_e( 'Location', 'pronamic-events' ); ?></dt>
							<dd><?php pronamic_the_location(); ?></dd>

						<?php endif; ?>

						<?php if ( pronamic_event_has_url() ) : ?>

							<dt><?php esc_html_e( 'Website', 'pronamic-events' ); ?></dt>
							<dd><?php pronamic_event_the_url(); ?></dd>

						<?php endif; ?>

					</dl>

					<?php the_content(); ?>
				</div>

				<footer class="entry-footer">
					<?php edit_post_link( __( 'Edit', 'pronamic-events' ), '<span class="edit-link">', '</span>' ); ?>
				</footer>
			</article>

		<?php endwhile; ?>

	</div>
</div>

<?php get_footer(); ?>
