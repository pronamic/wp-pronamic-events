<?php
/*
 * PHP: 5 >= 5.3.0
 */

global $post;

$event  = new Pronamic_WP_Event( $post );
$period = $event->get_period();

if ( $period ) : ?>

	<table>
		<thead>
			<tr>
				<th scope="col"><?php _e( 'Date', 'pronamic_events' ); ?></th>
			</tr>
		</thead>

		<tbody>

			<?php foreach ( $period as $date ) : ?>

				<tr>
					<td>
						<?php echo date_i18n( get_option( 'date_format' ), $date->getTimestamp() ); ?>
					</td>
				</tr>

			<?php endforeach; ?>

		</tbody>
	</table>

<?php else : ?>

	<p>
		<?php _e( 'No repeats available for this event.', 'pronamic_events' ); ?>
	</p>

<?php endif; ?>

<?php

$posts = $event->get_repeat_posts();

$query = new WP_Query( $event->get_repeat_posts_query_args() );

if ( $query->have_posts() ) : ?>

	<table>
		<thead>
			<tr>
				<th scope="col"><?php _e( 'ID', 'pronamic_events' ); ?></th>
				<th scope="col"><?php _e( 'Title', 'pronamic_events' ); ?></th>
			</tr>
		</thead>

		<tbody>

			<?php while ( $query->have_posts() ) : $query->the_post(); ?>

				<tr>
					<td>
						<?php echo get_the_ID(); ?>
					</td>
					<td>
						<?php the_title(); ?>
					</td>
				</tr>

			<?php endwhile; ?>

		</tbody>
	</table>

<?php endif; ?>
