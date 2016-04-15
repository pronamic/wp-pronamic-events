<?php get_header(); ?>

<div id="primary" class="site-content">
	<div id="content" role="main">

		<header class="page-header">
			<h1 class="page-title"><?php post_type_archive_title(); ?></h1>

			<?php the_archive_description( '<div class="taxonomy-description">', '</div>' ); ?>
		</header>

		<?php if ( have_posts() ) : ?>

			<table>
				<thead>
					<tr>
						<th scope="col"><?php esc_html_e( 'Title', 'ntta-courses' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Date', 'ntta-courses' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Location', 'ntta-courses' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Website', 'ntta-courses' ); ?></th>
					</tr>
				</thead>

				<tbody>

					<?php while ( have_posts() ) : the_post(); ?>

						<tr id="post-<?php the_ID(); ?>">
							<td>
								<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
							</td>
							<td>

							</td>
							<td>

							</td>
							<td>

							</td>
						</tr>

					<?php endwhile; ?>

				</tbody>
			</table>

		<?php else : ?>

			<p>
				<p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'ntta-courses' ); ?></p>
			</p>

		<?php endif; ?>

	</div>
</div>

<?php get_footer(); ?>
