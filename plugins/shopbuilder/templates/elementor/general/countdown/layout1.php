<?php
/**
 * Template: CountDown General Widget Layout
 *
 * @package RadiusTheme\SB
 */

/**
 * Template variables:
 *
 * @var $countdown_expire_time                string
 * @var $has_countdown_days                   boolean
 * @var $has_countdown_hours                  boolean
 * @var $has_countdown_minutes                boolean
 * @var $has_countdown_seconds                boolean
 * @var $countdown_days_label                 string
 * @var $countdown_hours_label                string
 * @var $countdown_minutes_label              string
 * @var $countdown_seconds_label              string
 * @var $countdown_layout                     string
 * @var $has_countdown_circle                 string
 * @var $circle_stroke_width                  string
 */


// Do not allow directly accessing this file.
use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Elementor\Widgets\General\CountDown\Render;

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}
?>
<div class="rtsb-countdown-wrapper">
	<div class="rtsb-countdown rtsbcd1 <?php echo esc_attr( $has_countdown_circle ); ?>" data-deadline-date-time="<?php echo esc_attr( $countdown_expire_time ); ?>">

		<?php
		if ( $has_countdown_days ) {
			Fns::print_html( Render::render_countdown( 'rtsb-day', $countdown_days_label, $countdown_layout, $circle_stroke_width ) );
		}
		?>
		<?php
		if ( $has_countdown_hours ) {
			Fns::print_html( Render::render_countdown( 'rtsb-hr', $countdown_hours_label, $countdown_layout, $circle_stroke_width ) );
		}
		?>
		<?php
		if ( $has_countdown_minutes ) {
			Fns::print_html( Render::render_countdown( 'rtsb-min', $countdown_minutes_label, $countdown_layout, $circle_stroke_width ) );
		}
		?>
		<?php
		if ( $has_countdown_seconds ) {
			Fns::print_html( Render::render_countdown( 'rtsb-sec', $countdown_seconds_label, $countdown_layout, $circle_stroke_width ) );
		}
		?>

	</div>
</div>
