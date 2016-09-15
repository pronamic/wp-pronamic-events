<?php
/*
 * PHP: 5 >= 5.3.0
 */

global $post;

$event = new Pronamic_WP_Event( $post );

$repeat_helper = new Pronamic_Events_RepeatEventHelper( $event );

$options = array(
	'0'        => __( '&mdash; Select Repeat &mdash;', 'pronamic-events' ),
	'daily'    => __( 'Daily', 'pronamic-events' ),
	'weekly'   => __( 'Weekly', 'pronamic-events' ),
	'monthly'  => __( 'Monthly', 'pronamic-events' ),
	'annually' => __( 'Annually', 'pronamic-events' ),
);

$options_interval_suffix = array(
	'daily'    => __( 'days', 'pronamic-events' ),
	'weekly'   => __( 'weeks', 'pronamic-events' ),
	'monthly'  => __( 'moths', 'pronamic-events' ),
	'annually' => __( 'year', 'pronamic-events' ),
);

$repeat        = get_post_meta( $post->ID, '_pronamic_event_repeat', true );
$frequency     = get_post_meta( $post->ID, '_pronamic_event_repeat_frequency', true );
$interval      = get_post_meta( $post->ID, '_pronamic_event_repeat_interval', true );
$ends_on       = get_post_meta( $post->ID, '_pronamic_event_ends_on', true );
$ends_on_count = get_post_meta( $post->ID, '_pronamic_event_ends_on_count', true );
$ends_on_until = get_post_meta( $post->ID, '_pronamic_event_ends_on_until', true );

?>

<?php if ( ! empty( $post->post_parent ) ) : ?>

	<p>
		<?php

		echo '<em>';

		echo wp_kses(
			sprintf(
				__( 'This event is part of a <a href="%s">repeated series</a>.', 'pronamic-events' ),
				esc_attr( get_edit_post_link( $post->post_parent ) )
			),
			array(
				'a' => array(
					'href' => true,
				),
			)
		);

		echo '</em>';

		?>
	</p>

<?php else : ?>

	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row">
					<label for="pronamic_event_repeat"><?php esc_html_e( 'Repeat', 'pronamic-events' ); ?></label>
				</th>
				<td>
					<label for="pronamic_event_repeat">
						<input type="checkbox" value="1" id="pronamic_event_repeat" name="_pronamic_event_repeat" <?php checked( $repeat ); ?> />
						<?php esc_html_e( 'Enable repeat', 'pronamic-events' ); ?>
					</label>

					<script type="text/javascript">
						jQuery( document ).ready( function( $ ) {
							var $repeat = $( '#pronamic_event_repeat' );
							var $meta_box = $( '#pronamic_events_repeats_meta_box' );

							var $hides = $( '.hide-if-no-repeat' ).add( $meta_box );;

							$repeat.change( function() {
								$hides.toggle( $repeat.prop( 'checked' ) );
							} );

							$hides.toggle( $repeat.prop( 'checked' ) );
						} );
					</script>
				</td>
			</tr>
			<tr class="hide-if-no-repeat">
				<th scope="row">
					<label for="pronamic_event_repeat_frequency"><?php esc_html_e( 'Frequency', 'pronamic-events' ); ?></label>
				</th>
				<td>
					<select id="pronamic_event_repeat_frequency" name="_pronamic_event_repeat_frequency">
						<?php

						foreach ( $options as $key => $label ) {
							$interval_suffix = '';
							if ( isset( $options_interval_suffix[ $key ] ) ) {
								$interval_suffix = $options_interval_suffix[ $key ];
							}

							printf(
								'<option value="%s" data-interval-suffix="%s" %s">%s</option>',
								esc_attr( $key ),
								esc_attr( $interval_suffix ),
								selected( $key, $frequency, false ),
								esc_html( $label )
							);
						}

						?>
					</select>
				</td>
			</tr>
			<tr class="hide-if-no-repeat">
				<th scope="row">
					<label for="pronamic_event_repeat_interval"><?php esc_html_e( 'Repeat every', 'pronamic-events' ); ?></label>
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

					<span id="pronamic_event_repeat_interval_suffix"><?php esc_html_e( 'days/weeks/months/year', 'pronamic-events' ); ?></span>
				</td>
			</tr>
			<tr class="hide-if-no-repeat">
				<th scope="row">
					<?php esc_html_e( 'Ends On', 'pronamic-events' ); ?>
				</th>
				<td>
					<div>
						<input type="radio" name="_pronamic_event_ends_on" value="count" <?php checked( 'count', $ends_on ); ?> />

						<?php

						$allowed_html = array(
							'input' => array(
								'id'     => true,
								'name'   => true,
								'type'   => true,
								'value'  => true,
								'size'   => true,
								'class'  => true,
							),
						);

						echo wp_kses(
							sprintf(
								__( 'After %s instances', 'pronamic-events' ),
								sprintf( '<input type="text" name="_pronamic_event_ends_on_count" value="%s" size="2"  />', esc_attr( $ends_on_count ) )
							),
							$allowed_html
						);

						?>
					</div>

					<div>
						<input type="radio" name="_pronamic_event_ends_on" value="until" <?php checked( 'until', $ends_on ); ?> />

						<?php

						echo wp_kses(
							sprintf(
								__( 'Until %s', 'pronamic-events' ),
								sprintf( '<input class="pronamic_date" type="text" id="pronamic_event_ends_on_until" name="_pronamic_event_ends_on_until" value="%s" size="14"  />', esc_attr( $ends_on_until ) )
							),
							$allowed_html
						);

						?>
					</div>
				</td>
			</tr>
			<tr class="hide-if-no-repeat">
				<th scope="row">
					<?php esc_html_e( 'Number Repeats', 'pronamic-events' ); ?>
				</th>
				<td>
					<?php echo esc_html( $repeat_helper->get_number_repeats() ); ?>

					<span class="description"><br /><?php printf( esc_html__( 'Note: Due to performance there is currently an maximum of %d repeats.', 'pronamic-events' ), esc_html( Pronamic_Events_RepeatModule::MAX_REPEATS ) ); ?></span>
				</td>
			</tr>
		</tbody>
	</table>

	<script type="text/javascript">
		jQuery( document ).ready( function( $ ) {
			// Interval label
			function set_interval_label() {
				var text = $( '#pronamic_event_repeat_frequency :selected' ).data( 'interval-suffix' );

				$( '#pronamic_event_repeat_interval_suffix' ).text( text );
			}

			$( '#pronamic_event_repeat_frequency' ).change( function() { set_interval_label(); } );

			set_interval_label();

			// Post submit
			$( '#post' ).submit( function() {
				var submit = true;

				if ( $( '#pronamic_event_repeat' ).prop( 'checked' ) ) {
					submit = confirm( '<?php echo esc_js( __( 'Note: All events in the series are changed.', 'pronamic-events' ) ); ?>' );
				}

				return submit;
			} );
		} );
	</script>

<?php endif; ?>
