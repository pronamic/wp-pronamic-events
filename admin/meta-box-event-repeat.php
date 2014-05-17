<?php

$options = array(
	'0'        => __( '&mdash; Select Repeat &mdash;', 'pronamic_events' ),
	'daily'	   => __( 'Daily', 'pronamic_events' ),
	'weekly'   => __( 'Weekly', 'pronamic_events' ),
	'monthly'  => __( 'Monthly', 'pronamic_events' ),
	'annually' => __( 'Annually', 'pronamic_events' ),
);

?>
<table class="form-table">
	<tbody>
		<tr>
			<th scope="row">
				<label for="pronamic_event_repeat_frequency"><?php _e( 'Frequency', 'pronamic_events' ); ?></label>
			</th>
			<td>
				<select id="pronamic_event_repeat_frequency">
					<?php

					foreach ( $options as $key => $label ) {
						printf(
							'<option value="%s" %s">%s</option>',
							esc_attr( $key ),
							selected( $key, '', false ),
							esc_html( $label )
						);
					}

					?>
				</select>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<label for="pronamic_event_repeat_interval"><?php _e( 'Interval', 'pronamic_events' ); ?></label>
			</th>
			<td>
				<select id="pronamic_event_repeat_interval">
					<?php

					foreach ( range( 1, 30 ) as $value ) {
						printf(
							'<option value="%s" %s">%s</option>',
							esc_attr( $value ),
							selected( $value, '', false ),
							esc_html( $value )
						);
					}

					?>
				</select>

				maanden
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php _e( 'Ends On', 'pronamic_events' ); ?>
			</th>
			<td>
				<div>
					<input type="radio" name="pronamic_event_ends_on" value="count" />

					<?php

					printf(
						__( 'After %s instances', 'pronamic_events' ),
						sprintf( '<input type="text" name="pronamic_event_ends_on_count" value="%s" size="2"  />', 2 )
					);

					?>
				</div>

				<div>
					<input type="radio" name="pronamic_event_ends_on" value="until" />

					<?php

					printf(
						__( 'Until %s', 'pronamic_events' ),
						sprintf( '<input class="pronamic_date" type="text" id="pronamic_event_ends_on_until" name="pronamic_event_ends_on_until" value="%s" size="14"  />', '12-02-2014' )
					);

					?>
				</div>
			</td>
		</tr>
	</tbody>
</table>
