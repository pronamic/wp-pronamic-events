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
