<?php
/**
 * Render class for Advanced Heading widget.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\General\ProgressBar;

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
		$data            = apply_filters( 'rtsb/general/progress_bar/args/' . $data['unique_name'], $data );
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
			'has_label'              => ! empty( $this->settings['display_progress_label'] ) && ! empty( $this->settings['progress_label'] ),
			'wrapper_class'          => $this->generate_wrapper_class(),
			'rtsb_progress_bar_html' => $this->render_progress_bar(),
		];
	}
	/**
	 * Function to render progressbar.
	 *
	 * @return string
	 */
	public function render_progress_bar() {
		$layout             = $this->settings['layout_style'] ?? 'rtsb-countdown-layout1';
		$progress_count     = $this->settings['progress_count'] ?? 80;
		$animation_duration = $this->settings['progress_bar_transition'] ?? 1500;
		$has_label          = ! empty( $this->settings['display_progress_label'] ) && ! empty( $this->settings['progress_label'] );
		$has_count          = ! empty( $this->settings['display_progress_count'] ) && ! empty( $this->settings['progress_count'] );
		ob_start();
		?>
		<div class="rtsb-progressbar-<?php echo esc_attr( $layout ); ?>-container">

			<?php if ( ( 'rtsb-progress-bar-layout1' === $layout || 'rtsb-progress-bar-layout4' === $layout ) && $has_label ) { ?>
				<div class="rtsb-progressbar-label-wrap">
					<h4 class="rtsb-pb-label"><?php echo esc_html( $this->settings['progress_label'] ); ?></h4>
				</div>
			<?php } ?>

			<div class="<?php echo esc_attr( $this->layout_class() ); ?>" data-layout="<?php echo esc_attr( $this->layout_class( true ) ); ?>" data-count="<?php echo esc_attr( $progress_count ); ?>" data-duration="<?php echo esc_attr( $animation_duration ); ?>">

				<?php if ( 'rtsb-progress-bar-layout1' === $layout ) { ?>
					<?php if ( $has_count ) { ?>
						<span class="rtsb-pb-count-wrap">
							<span class="rtsb-pb-count">0</span>
							<span class="postfix">%</span>
						</span>
					<?php } ?>
					<span class="rtsb-pb-line-fill"></span>
				<?php } ?>
				<?php if ( 'rtsb-progress-bar-layout4' === $layout ) { ?>
					<span class="rtsb-pb-line-fill">
						<?php if ( $has_count ) { ?>
							<span class="rtsb-pb-count-wrap">
								<span class="rtsb-pb-count"><?php echo esc_html( $this->settings['progress_count'] ); ?></span>
								<span class="postfix">%</span>
							</span>
						<?php } ?>
					</span>
				<?php } ?>
				<?php if ( 'rtsb-progress-bar-layout5' === $layout ) { ?>
					<?php
					if ( $has_label ) {
						?>
						<div class="rtsb-progressbar-label-wrap">
							<h4 class="rtsb-pb-label"><?php echo esc_html( $this->settings['progress_label'] ); ?></h4>
						</div>
					<?php } ?>
					<div class="rtsb-pb-line-fill">
						<?php if ( $has_count ) { ?>
							<span class="rtsb-pb-count-wrap">
								<span class="rtsb-pb-count"><?php echo esc_html( $this->settings['progress_count'] ); ?></span>
								<span class="postfix">%</span>
							</span>
						<?php } ?>
					</div>
				<?php } ?>
				<?php if ( 'rtsb-progress-bar-layout2' === $layout ) { ?>
					<div class="rtsb-pb-circle-pie">
						<div class="rtsb-pb-circle-left rtsb-pb-circle-half"></div>
						<div class="rtsb-pb-circle-right rtsb-pb-circle-half"></div>
					</div>

					<div class="rtsb-pb-circle-inner"></div>

					<div class="rtsb-pb-circle-inner-content">
						<?php if ( $has_count ) { ?>
							<span class="rtsb-pb-count-wrap">
								<span class="rtsb-pb-count"><?php echo esc_html( $this->settings['progress_count'] ); ?></span>
								<span class="postfix">%</span>
							</span>
						<?php } ?>
					</div>
				<?php } ?>


				<?php if ( 'rtsb-progress-bar-layout3' === $layout ) { ?>
					<div class="rtsb-pb-circle">
						<div class="rtsb-pb-circle-pie">
							<div class="rtsb-pb-circle-half" ref={circle_half}></div>
						</div>
						<div class="rtsb-pb-circle-inner"></div>
					</div>

					<div class="rtsb-pb-circle-inner-content">
						<?php if ( $has_count ) : ?>
							<span class="rtsb-pb-count-wrap">
								<span class="rtsb-pb-count"><?php echo esc_html( $this->settings['progress_count'] ); ?></span>
								<span class="postfix">%</span>
							</span>
						<?php endif; ?>
					</div>
				<?php } ?>

			</div>

			<?php if ( ( 'rtsb-progress-bar-layout2' === $layout ) && $has_label ) { ?>
				<div class="rtsb-progressbar-label-wrap">
					<h4 class="rtsb-pb-label"><?php echo esc_html( $this->settings['progress_label'] ); ?></h4>
				</div>
			<?php } ?>

			<?php if ( ( 'rtsb-progress-bar-layout3' === $layout ) && $has_label ) { ?>
				<div class="rtsb-progressbar-label-wrap">
					<h4 class="rtsb-pb-label"><?php echo esc_html( $this->settings['progress_label'] ); ?></h4>
				</div>
			<?php } ?>
		</div>
		<?php
		return ob_get_clean();
	}
	/**
	 * Function to render layout class.
	 *
	 * @return string
	 */
	public function layout_class( $data_attribute = false ) {
		$data_layout  = 'line';
		$layout_class = 'rtsb-progressbar';
		if ( ! empty( $this->settings['layout_style'] ) ) {
			if ( self::progress_bar_style_line_layout( $this->settings['layout_style'] ) ) {
				$layout_class .= ' rtsb-pb-line';
			} elseif ( 'rtsb-progress-bar-layout2' === $this->settings['layout_style'] ) {
				$layout_class .= ' rtsb-pb-circle';
				$data_layout   = 'circle';
			} elseif ( 'rtsb-progress-bar-layout3' === $this->settings['layout_style'] ) {
				$layout_class .= ' rtsb-pb-half_circle';
				$data_layout   = 'half_circle';
			}
			if ( $this->settings['display_progress_stripe'] ) {
				$layout_class .= ' rtsb-pb-line-stripe';
			}
			if ( $data_attribute ) {
				return $data_layout;
			}
		}
		return $layout_class;
	}
	/**
	 * Function to generate wrapper class.
	 *
	 * @return string
	 */
	public function generate_wrapper_class() {
		$wrapper_class = '';
		if ( ! empty( $this->settings['layout_style'] ) ) {
			if ( self::progress_bar_style_line_layout( $this->settings['layout_style'] ) ) {
				$wrapper_class = 'rtsb-prgressbar-style-line';
			} elseif ( 'rtsb-progress-bar-layout2' === $this->settings['layout_style'] ) {
				$wrapper_class = 'rtsb-prgressbar-style-circle';
			} elseif ( 'rtsb-progress-bar-layout3' === $this->settings['layout_style'] ) {
				$wrapper_class = 'rtsb-prgressbar-style-half_circle';
			}
		}
		return $wrapper_class;
	}

	public static function progress_bar_style_line_layout( $layout ) {
		$line_layouts = [ 'rtsb-progress-bar-layout1','rtsb-progress-bar-layout4','rtsb-progress-bar-layout5' ];
		if ( in_array( $layout, $line_layouts, true ) ) {
			return true;
		}
		return false;
	}
}
