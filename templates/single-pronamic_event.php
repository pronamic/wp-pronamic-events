<?php get_header(); ?>

<div id="primary">
	<div id="content" role="main">

		<?php while ( have_posts() ) : the_post(); ?>

			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="entry-header">
					<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
				</header>

				<div class="entry-content">
					<?php the_content(); ?>
				</div>

				<footer class="entry-footer">
					<?php edit_post_link( __( 'Edit', 'ntta-courses' ), '<span class="edit-link">', '</span>' ); ?>
				</footer>
			</article>

		<?php endwhile; ?>

	</div>
</div>

<?php get_footer(); ?>
