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
				<th scope="col"><?php _e( 'Title', 'pronamic_events' ); ?></th>
				<th scope="col"><?php _e( 'Start Date', 'pronamic_events' ); ?></th>
				<th scope="col"><?php _e( 'End Date', 'pronamic_events' ); ?></th>
				<th scope="col"><?php _e( 'In Series', 'pronamic_events' ); ?></th>
				<th scope="col"><?php _e( 'Actions', 'pronamic_events' ); ?></th>
			</tr>
		</thead>

		<tbody>

			<?php foreach ( $repeat_events as $repeat_event ) : ?>

				<tr>
					<td>
						<?php edit_post_link( get_the_title( $repeat_event->post ), null, null, $repeat_event->post->ID ); ?>
					</td>
					<td>
						<?php

						$t_time = pronamic_get_the_start_date( __( 'Y/m/d g:i:s A', 'pronamic_events' ), $repeat_event->post->ID );
						$h_time = pronamic_get_the_start_date( __( 'Y/m/d', 'pronamic_events' ), $repeat_event->post->ID );
						$hours  = pronamic_get_the_start_date( __( 'g:i:s', 'pronamic_events' ), $repeat_event->post->ID );

						printf( '<abbr title="%s">%s</abbr><br />%s', $t_time, $h_time, $hours );

						?>
					</td>
					<td>
						<?php

						$t_time = pronamic_get_the_end_date( __( 'Y/m/d g:i:s A', 'pronamic_events' ), $repeat_event->post->ID );
						$h_time = pronamic_get_the_end_date( __( 'Y/m/d', 'pronamic_events' ), $repeat_event->post->ID );
						$hours  = pronamic_get_the_end_date( __( 'g:i:s', 'pronamic_events' ), $repeat_event->post->ID );

						printf( '<abbr title="%s">%s</abbr><br />%s', $t_time, $h_time, $hours );

						?>
					</td>
					<td>
						<?php

						$hash_key = $repeat_event->get_event_hash_code();

						echo isset( $data[ $hash_key ] ) ? __( 'Yes', 'pronamic_events' ) : __( 'No', 'pronamic_events' );

						?>
					</td>
					<td class="pronamic-event-repeats-actions">
						<?php edit_post_link( __( 'Edit', 'pronamic_events' ), null, null, $repeat_event->post->ID ); ?> |
						<a class="submitdelete" href="<?php echo get_delete_post_link( $repeat_event->post->ID ); ?>"><?php _e( 'Trash', 'pronamic_events' ); ?></a>
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
