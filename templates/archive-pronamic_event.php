<?php get_header(); ?>

<div id="primary" class="site-content">
	<div id="content" role="main">

		<header class="page-header">
			<h1 class="page-title"><?php post_type_archive_title(); ?></h1>

			<?php the_archive_description( '<div class="taxonomy-description">', '</div>' ); ?>
		</header>

		<div class="entry-content">

			<?php if ( have_posts() ) : ?>

				<table>
					<thead>
						<tr>
							<?php

							$columns = array(
								'title' => __( 'Title', 'pronamic-events' ),
								'date'  => __( 'Date', 'pronamic-events' ),
							);

							foreach ( $columns as $key => $text ) : ?>

								<th scope="col">
									<?php

									$link = add_query_arg( array(
										'order'   => get_query_var( 'order' ) === 'ASC' ? 'DESC' : 'ASC',
										'orderby' => $key,
									) );

									if ( get_query_var( 'orderby' ) === $key ) {
										$text = sprintf(
											__( '%1$s %2$s', 'pronamic-events' ),
											esc_html( $text ),
											get_query_var( 'order' ) === 'ASC' ? '▲' : '▼'
										);
									}

									printf(
										'<a href="%s">%s</a>',
										esc_attr( $link ),
										esc_html( $text )
									);

									?>
								</th>

							<?php endforeach; ?>

							<th scope="col"><?php esc_html_e( 'Location', 'pronamic-events' ); ?></th>
							<th scope="col"><?php esc_html_e( 'Website', 'pronamic-events' ); ?></th>
						</tr>
					</thead>

					<tbody>

						<?php while ( have_posts() ) : the_post(); ?>

							<tr id="post-<?php the_ID(); ?>">
								<td>
									<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
								</td>
								<td>
									<?php pronamic_the_start_date(); ?>
								</td>
								<td>
									<?php pronamic_the_location(); ?>
								</td>
								<td>
									<?php pronamic_event_the_url(); ?>
								</td>
							</tr>

						<?php endwhile; ?>

					</tbody>
				</table>

			<?php else : ?>

				<p>
					<p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'pronamic-events' ); ?></p>
				</p>

			<?php endif; ?>

		</div>
	</div>
</div>

<?php get_footer(); ?>
