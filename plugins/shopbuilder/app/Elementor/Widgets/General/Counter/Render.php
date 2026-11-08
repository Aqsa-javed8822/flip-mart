<?php
/**
 * Render class for Advanced Heading widget.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\General\Counter;

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
		$data            = apply_filters( 'rtsb/general/counter/args/' . $data['unique_name'], $data );
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
			'counter_title_html_tag' => $this->settings['counter_title_html_tag'] ?? 'h2',
			'counter_title'          => $this->settings['counter_title'],
			'counter_count'          => $this->settings['counter_count'] ?? '1000',
			'counter_transition'     => $this->settings['counter_transition'] ?? '2000',
			'icon_position'          => $this->settings['counter_icon_position'] ?? 'center',
			'has_prefix'             => ! empty( $this->settings['display_prefix'] ) && ! empty( $this->settings['count_prefix'] ),
			'has_suffix'             => ! empty( $this->settings['display_suffix'] ) && ! empty( $this->settings['count_suffix'] ),
			'prefix'                 => $this->settings['count_prefix'] ?? '+',
			'suffix'                 => $this->settings['count_suffix'] ?? '+',
			'icon_html'              => $this->render_icon_image(),
			'icon_type'              => $this->settings['counter_icon_type'] ?? 'icon',
			'icon_bg_type'           => ! empty( $this->settings['counter_icon_style_gradient_bg_background'] ) ? $this->settings['counter_icon_style_gradient_bg_background'] : 'classic',
			'icon_hover_bg_type'     => ! empty( $this->settings['counter_icon_style_hover_gradient_bg_background'] ) ? $this->settings['counter_icon_style_hover_gradient_bg_background'] : 'classic',
		];
	}

	/**
	 * Function to render number.
	 *
	 * @param integer $number Position of the separator in settings.
	 *
	 * @return string
	 */
	public static function render_count_number( $number, $has_prefix, $prefix, $has_suffix, $suffix, $transition ) {
		ob_start();
		?>
		<div class="rtsb-counter-number-wrap" style="--odo-duration: <?php echo esc_attr( $transition ); ?>ms">
			<?php if ( $has_prefix ) { ?>
				<span class="rtsb-counter-prefix"><?php Fns::print_html( $prefix ); ?></span>
			<?php } ?>
			<div class="rtsb-count-number rtsb-counter-number" data-count="<?php echo esc_attr( $number ); ?>" data-format="<?php echo esc_attr( '(,ddd)' ); ?>" data-duration="<?php echo esc_attr( $transition ); ?>">
				<?php
				$digitCount = strlen( (string) abs( $number ) );
				$zeroes     = str_repeat( 0, $digitCount );

				echo esc_html( preg_replace( '/\B(?=(\d{3})+(?!\d))/', ',', $zeroes ) );
				?>
			</div>
			<?php if ( $has_suffix ) : ?>
				<span class="rtsb-counter-suffix"><?php Fns::print_html( $suffix ); ?></span>
			<?php endif ?>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Function to render icon.
	 *
	 * @param string $position icon position ('left' or 'right').
	 * @param array  $icon Position of the separator in settings.
	 *
	 * @return string
	 */
	public static function render_icon( $position, $icon, $icon_position ) {
		$html = '';

		// Render the left icon.
		if ( $position === $icon_position ) {
			$html .= '<span class="icon">' . Fns::icons_manager( $icon ) . '</span>';
		}

		return $html;
	}

	/**
	 * Function to render icon image.
	 *
	 * @return string
	 */
	public function render_icon_image() {
		$icon_img_html = '';

		$icon = $this->settings['counter_icon'] ?? '';

		if ( ! empty( $icon['value'] ) && 'icon' === $this->settings['counter_icon_type'] ) {
			$icon_img_html .= '<span class="icon">' . Fns::icons_manager( $icon ) . '</span>';
		} else {
			if ( ! empty( $this->settings['counter_image'] ) ) {
				$c_image_size         = RenderHelpers::get_data( $this->settings, 'counter_img_dimension', [] );
				$c_image_size['crop'] = RenderHelpers::get_data( $this->settings, 'counter_img_crop', [] );
				$c_image_size         = ! empty( $c_image_size ) && is_array( $c_image_size ) ? $c_image_size : [];
				$image_id             = ! empty( $this->settings['counter_image']['id'] ) ? $this->settings['counter_image']['id'] : $this->settings['counter_image'];
				$icon_img_html       .= Fns::get_product_image_html(
					'',
					null,
					RenderHelpers::get_data( $this->settings, 'counter_image_size', 'full' ),
					$image_id,
					$c_image_size
				);
			}
		}

		return $icon_img_html;
	}
}
