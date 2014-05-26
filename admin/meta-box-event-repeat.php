<?php
/*
 * PHP: 5 >= 5.3.0
 */

global $post;

$options = array(
	'0'        => __( '&mdash; Select Repeat &mdash;', 'pronamic_events' ),
	'daily'    => __( 'Daily', 'pronamic_events' ),
	'weekly'   => __( 'Weekly', 'pronamic_events' ),
	'monthly'  => __( 'Monthly', 'pronamic_events' ),
	'annually' => __( 'Annually', 'pronamic_events' ),
);

$frequency     = get_post_meta( $post->ID, '_pronamic_event_repeat_frequency', true );
$interval      = get_post_meta( $post->ID, '_pronamic_event_repeat_interval', true );
$ends_on       = get_post_meta( $post->ID, '_pronamic_event_ends_on', true );
$ends_on_count = get_post_meta( $post->ID, '_pronamic_event_ends_on_count', true );
$ends_on_until = get_post_meta( $post->ID, '_pronamic_event_ends_on_until', true );

?>
<table class="form-table">
	<tbody>
		<tr>
			<th scope="row">
				<label for="pronamic_event_repeat_frequency"><?php _e( 'Frequency', 'pronamic_events' ); ?></label>
			</th>
			<td>
				<select id="pronamic_event_repeat_frequency" name="_pronamic_event_repeat_frequency">
					<?php

					foreach ( $options as $key => $label ) {
						printf(
							'<option value="%s" %s">%s</option>',
							esc_attr( $key ),
							selected( $key, $frequency, false ),
							esc_html( $label )
						);
					}

					?>
				</select>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<label for="pronamic_event_repeat_interval"><?php _e( 'Repeat every', 'pronamic_events' ); ?></label>
			</th>
			<td>
				<select id="pronamic_event_repeat_interval" name="_pronamic_event_repeat_interval">
					<?php

					foreach ( range( 1, 30 ) as $value ) {
						printf(
							'<option value="%s" %s">%s</option>',
							esc_attr( $value ),
							selected( $value, $interval, false ),
							esc_html( $value )
						);
					}

					?>
				</select>

				<?php _e( 'parts', 'pronamic_events' ); ?>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php _e( 'Ends On', 'pronamic_events' ); ?>
			</th>
			<td>
				<div>
					<input type="radio" name="_pronamic_event_ends_on" value="count" <?php checked( 'count', $ends_on ); ?> />

					<?php

					printf(
						__( 'After %s instances', 'pronamic_events' ),
						sprintf( '<input type="text" name="_pronamic_event_ends_on_count" value="%s" size="2"  />', $ends_on_count )
					);

					?>
				</div>

				<div>
					<input type="radio" name="_pronamic_event_ends_on" value="until" <?php checked( 'until', $ends_on ); ?> />

					<?php

					printf(
						__( 'Until %s', 'pronamic_events' ),
						sprintf( '<input class="pronamic_date" type="text" id="pronamic_event_ends_on_until" name="_pronamic_event_ends_on_until" value="%s" size="14"  />', $ends_on_until )
					);

					?>
				</div>
			</td>
		</tr>
	</tbody>
</table>
