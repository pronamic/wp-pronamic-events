<?php
/*
 * PHP: 5 >= 5.3.0
 */

global $post;

$event  = new Pronamic_WP_Event( $post );

$repeat_helper = new Pronamic_Events_RepeatEventHelper( $event );

$repeat_events = $repeat_helper->get_repeat_events();

$data = $repeat_helper->get_period_data();

if ( $repeat_events ) : ?>

	<table class="pronamic-event-repeats-table">
		<thead>
			<tr>
				<th scope="col"><?php esc_html_e( 'Title', 'pronamic-events' ); ?></th>
				<th scope="col"><?php esc_html_e( 'Start Date', 'pronamic-events' ); ?></th>
				<th scope="col"><?php esc_html_e( 'End Date', 'pronamic-events' ); ?></th>
				<th scope="col"><?php esc_html_e( 'In Series', 'pronamic-events' ); ?></th>
				<th scope="col"><?php esc_html_e( 'Actions', 'pronamic-events' ); ?></th>
			</tr>
		</thead>

		<tbody>

			<?php foreach ( $repeat_events as $repeat_event ) : ?>

				<tr>
					<td>
						<a href="<?php echo esc_url( get_permalink( $repeat_event->post ) ); ?>">
							<?php echo esc_html( get_the_title( $repeat_event->post ) ); ?>
						</a>
					</td>
					<td>
						<?php

						$t_time = pronamic_get_the_start_date( __( 'Y/m/d g:i:s A', 'pronamic-events' ), $repeat_event->post->ID );
						$h_time = pronamic_get_the_start_date( __( 'Y/m/d', 'pronamic-events' ), $repeat_event->post->ID );
						$hours  = pronamic_get_the_start_date( __( 'g:i:s', 'pronamic-events' ), $repeat_event->post->ID );

						printf(
							'<abbr title="%s">%s</abbr><br />%s',
							esc_attr( $t_time ),
							esc_html( $h_time ),
							esc_html( $hours )
						);

						?>
					</td>
					<td>
						<?php

						$t_time = pronamic_get_the_end_date( __( 'Y/m/d g:i:s A', 'pronamic-events' ), $repeat_event->post->ID );
						$h_time = pronamic_get_the_end_date( __( 'Y/m/d', 'pronamic-events' ), $repeat_event->post->ID );
						$hours  = pronamic_get_the_end_date( __( 'g:i:s', 'pronamic-events' ), $repeat_event->post->ID );

						printf(
							'<abbr title="%s">%s</abbr><br />%s',
							esc_attr( $t_time ),
							esc_html( $h_time ),
							esc_html( $hours )
						);

						?>
					</td>
					<td>
						<?php

						$hash_key = $repeat_event->get_event_hash_code();

						echo esc_html( isset( $data[ $hash_key ] ) ? __( 'Yes', 'pronamic-events' ) : __( 'No', 'pronamic-events' ) );

						?>
					</td>
					<td class="pronamic-event-repeats-actions">
						<?php edit_post_link( __( 'Edit', 'pronamic-events' ), null, null, $repeat_event->post->ID ); ?> |
						<a class="submitdelete" href="<?php echo esc_attr( get_delete_post_link( $repeat_event->post->ID ) ); ?>"><?php esc_html_e( 'Trash', 'pronamic-events' ); ?></a>
					</td>
				</tr>

			<?php endforeach; ?>

		</tbody>
	</table>

<?php else : ?>

	<p>
		<?php esc_html_e( 'No repeats available for this event.', 'pronamic-events' ); ?>
	</p>

<?php endif; ?>
