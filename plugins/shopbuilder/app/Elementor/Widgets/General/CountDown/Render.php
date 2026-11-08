<?php
/**
 * Render class for Advanced Heading widget.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\General\CountDown;

use DateTime;
use DateTimeZone;
use RadiusTheme\SB\Elementor\Helper\RenderHelpers;
use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Elementor\Render\GeneralAddons;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Render class.
 *
 * @package RadiusTheme\SB
 */
class Render extends GeneralAddons {
	/**
	 * Main render function for displaying content.
	 *
	 * @param array $data     Data to be passed to the template.
	 * @param array $settings Widget settings.
	 *
	 * @return string
	 */
	public function display_content( $data, $settings ) {
		$this->settings  = $settings;
		$data            = wp_parse_args( $this->get_template_args( $data['id'] ), $data );
		$data            = apply_filters( 'rtsb/general/countdown/args/' . $data['unique_name'], $data );
		$data['content'] = Fns::load_template( $data['template'], $data, true );

		return $this->addon_view( $data, $settings );
	}
	/**
	 * Retrieves template arguments based on widget settings.
	 *
	 * @return array
	 */
	private function get_template_args( $id ) {
		return [
			'countdown_expire_time'       => $this->format_date_time( $this->settings['countdown_date_time'] ),
			'has_countdown_days'          => ! empty( $this->settings['display_days'] ),
			'has_countdown_hours'         => ! empty( $this->settings['display_hours'] ),
			'has_countdown_minutes'       => ! empty( $this->settings['display_minutes'] ),
			'has_countdown_seconds'       => ! empty( $this->settings['display_seconds'] ),
			'countdown_days_label'        => $this->settings['countdown_days_label'] ?? '',
			'countdown_hours_label'       => $this->settings['countdown_hours_label'] ?? '',
			'countdown_minutes_label'     => $this->settings['countdown_minutes_label'] ?? '',
			'countdown_seconds_label'     => $this->settings['countdown_seconds_label'] ?? '',
			'countdown_layout'            => $this->settings['layout_style'] ?? 'rtsb-countdown-layout1',
			'has_countdown_circle'        => ! empty( $this->settings['layout_style'] ) && 'rtsb-countdown-layout5' === $this->settings['layout_style'] ? 'rtsb-countdown-circle' : '',
			'circle_stroke_width'         => $this->settings['countdown_circle_stroke']['size'] ?? 10,
			'rtsb_countdown_layout6_html' => $this->render_countdown_layout6(),
		];
	}
	public function format_date_time( $date_time ) {
		$timezone_string = get_option( 'timezone_string' );
		$gmt_offset      = get_option( 'gmt_offset' );
		if ( $timezone_string ) {
			$timezone = new DateTimeZone( $timezone_string );
		} else {
			$timezone = new DateTimeZone( sprintf( 'Etc/GMT%s', $gmt_offset >= 0 ? "-$gmt_offset" : '+' . abs( $gmt_offset ) ) );
		}
		$date = new DateTime( $date_time, $timezone );
		return $date->getTimestamp() * 1000;
	}
	/**
	 * Function to render countdown.
	 *
	 * @param string $class countdown class name.
	 * @param string $countdown_label countdown label text.
	 *
	 * @return string
	 */
	public static function render_countdown( $class, $countdown_label, $layout, $circle_stroke_width ) {
		ob_start();
		?>
		<div class="rtsb-countdown-item <?php echo esc_attr( $class ); ?>">
			<span class="rtsb-countdown-count">00</span>
			<span class="rtsb-countdown-count-text"><?php echo esc_html( $countdown_label ); ?></span>
			<?php if ( 'rtsb-countdown-layout4' === $layout ) { ?>
				<span class="rtsb-ex-shape rtsb-ex-shape-one">
					<span></span>
				</span>
				<span class="rtsb-ex-shape rtsb-ex-shape-two">
					<span></span>
				</span>
			 <?php } ?>
			<?php if ( 'rtsb-countdown-layout5' === $layout ) { ?>
				<div class="rtsb-circle-canvas">
					<svg width="200" height="200">
						<circle r="<?php echo esc_attr( ( ( 200 / 2 ) - ( ( absint( $circle_stroke_width ) ) / 2 ) ) ); ?>" cx="<?php echo esc_attr( ( 200 / 2 ) ); ?>" cy="<?php echo ( ( ( 200 ) / 2 ) ); ?>" class="rtsbCircleTrack rtsbCircleTrackDown <?php echo esc_attr( $class ); ?>">
						</circle>
						<circle r="<?php echo esc_attr( ( ( 200 / 2 ) - ( ( absint( $circle_stroke_width ) ) / 2 ) ) ); ?>" cx="<?php echo esc_attr( ( 200 / 2 ) ); ?>" cy="<?php echo esc_attr( ( 200 / 2 ) ); ?>" class="rtsbCircleTrack rtsbCircleTrackUp <?php echo esc_attr( $class ); ?>">
						</circle>
					</svg>
				</div>
			<?php } ?>
		</div>
		<?php
		return ob_get_clean();
	}
	/**
	 * Function to render countdown layout 6.
	 *
	 * @return string
	 */
	public function render_countdown_layout6() {
		ob_start();

		$number_of_days = $this->calculate_days_from_timestamp( $this->format_date_time( $this->settings['countdown_date_time'] ?? '' ) );
		$digit_count    = strlen( (string) $number_of_days );
		?>
		<?php if ( ! empty( $this->settings['display_days'] ) ) { ?>
			<div class="rtsb-countdown-item rtsb-day">

				<?php if ( ! empty( $this->settings['countdown_days_label'] ) ) { ?>
					<span class="rtsb-countdown-count-text"><?php echo esc_html( $this->settings['countdown_days_label'] ); ?></span>
				<?php } ?>

				<span class="rtsb-countdown-count">
					<?php
					for ( $i = 1; $i <= $digit_count; $i++ ) {
						echo '<span class="rtsb-countdown-single-digit rtsb-d' . absint( $i ) . '">0</span>';
					}
					?>
				</span>

			</div>
		<?php } ?>

		<?php if ( ! empty( $this->settings['display_hours'] ) ) { ?>
			<div class="rtsb-countdown-item rtsb-hr">
				<?php if ( ! empty( $this->settings['countdown_hours_label'] ) ) : ?>
					<span class="rtsb-countdown-count-text"><?php echo esc_html( $this->settings['countdown_hours_label'] ); ?></span>
				<?php endif; ?>

				<span class="rtsb-countdown-count">
					<span class="rtsb-countdown-single-digit rtsb-h1">0</span>
					<span class="rtsb-countdown-single-digit rtsb-h2">0</span>
					</span>
			</div>
		<?php } ?>

		<?php if ( ! empty( $this->settings['display_minutes'] ) ) { ?>
			<div class="rtsb-countdown-item rtsb-min">
				<?php if ( ! empty( $this->settings['countdown_minutes_label'] ) ) { ?>
					<span class="rtsb-countdown-count-text"><?php echo esc_html( $this->settings['countdown_minutes_label'] ); ?></span>
				<?php } ?>

				<span class="rtsb-countdown-count">
					<span class="rtsb-countdown-single-digit rtsb-m1">0</span>
					<span class="rtsb-countdown-single-digit rtsb-m2">0</span>
				</span>
			</div>
		<?php } ?>


		<?php if ( ! empty( $this->settings['display_seconds'] ) ) { ?>
			<div class="rtsb-countdown-item rtsb-sec">

				<?php if ( ! empty( $this->settings['countdown_seconds_label'] ) ) { ?>
					<span class="rtsb-countdown-count-text"><?php echo esc_html( $this->settings['countdown_seconds_label'] ); ?></span>
				<?php } ?>

				<span class="rtsb-countdown-count">
					<span class="rtsb-countdown-single-digit rtsb-s1">0</span>
					<span class="rtsb-countdown-single-digit rtsb-s2">0</span>
				</span>
			</div>
		<?php } ?>
		<?php
		return ob_get_clean();
	}

	/**
	 * Calculate days from timestamp.
	 *
	 * @param int $millis_timestamp Timestamp in milliseconds.
	 *
	 * @return float|int
	 */
	public function calculate_days_from_timestamp( $millis_timestamp ) {
		if ( empty( $millis_timestamp ) ) {
			return 0;
		}

		$millis_timestamp      = (float) $millis_timestamp;
		$timestamp_seconds     = $millis_timestamp / 1000;
		$current_time          = time();
		$difference_in_seconds = $timestamp_seconds - $current_time;

		return abs( floor( $difference_in_seconds / 86400 ) );
	}
}
