<?php
/**
 * Render class for Advanced Heading widget.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\General\AdvancedHeading;

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
		$data            = wp_parse_args( $this->get_template_args(), $data );
		$data            = apply_filters( 'rtsb/general/advanced_heading/args/' . $data['unique_name'], $data );
		$data['content'] = Fns::load_template( $data['template'], $data, true );

		return $this->addon_view( $data, $settings );
	}

	/**
	 * Retrieves template arguments based on widget settings.
	 *
	 * @return array
	 */
	private function get_template_args() {
		return [
			'layout'               => $this->settings['layout_style'],
			'title_tag'            => $this->settings['heading_title_html_tag'] ?? 'h2',
			'has_title'            => ! empty( $this->settings['heading_title_text'] ),
			'title'                => $this->settings['heading_title_text'] ?? '',
			'has_sub_heading'      => ! empty( $this->settings['display_subheading'] ) && ! empty( $this->settings['subheading_text'] ),
			'sub_heading'          => $this->settings['subheading_text'] ?? '',
			'sub_heading_tag'      => $this->settings['subheading_html_tag'] ?? 'span',
			'sub_heading_position' => $this->settings['rtsb_subheading_position'] ?? 'top',
			'has_separator'        => ! empty( $this->settings['display_separator'] ),
			'has_description'      => ! empty( $this->settings['display_description'] ) && ! empty( $this->settings['description_text'] ),
			'description'          => $this->settings['description_text'] ?? '',
			'separator_position'   => $this->settings['rtsb_separator_position'] ?? 'bottom',
			'display_left_bar'     => ! empty( $this->settings['display_subheading_left_bar'] ),
			'display_right_bar'    => ! empty( $this->settings['display_subheading_right_bar'] ),
		];
	}

	/**
	 * Function to render the subheading.
	 *
	 * @param string $sub_heading The subheading text.
	 * @param string $sub_heading_tag The HTML tag for the subheading.
	 * @param string $left_bar The HTML for the left bar.
	 * @param string $right_bar The HTML for the right bar.
	 *
	 * @return string
	 */
	public static function render_subheading( $sub_heading, $sub_heading_tag, $left_bar, $right_bar ) {
		ob_start();
		?>
		<div class="rtsb-advanced-heading-sub-title-wrap">
			<div class="rtsb-advanced-heading-sub-title">
				<?php
				Fns::print_html( $left_bar );
				?>
				<<?php Fns::print_validated_html_tag( $sub_heading_tag ); ?> class="sub-heading-text">
					<?php Fns::print_html( $sub_heading ); ?>
				</<?php Fns::print_validated_html_tag( $sub_heading_tag ); ?>>
				<?php
				Fns::print_html( $right_bar );
				?>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Function to render the separator line.
	 *
	 * @param string $position Separator position ('top' or 'bottom').
	 * @param bool   $has_separator Whether the separator is enabled.
	 * @param string $separator_position Position of the separator in settings.
	 *
	 * @return string
	 */
	public static function render_separator( $position, $has_separator, $separator_position ) {
		$html = '';

		if ( $has_separator && $position === $separator_position ) {
			$html .= '<div class="rtsb-separator-line"></div>';
		}

		return $html;
	}

	/**
	 * Function to render the left or right subheading bars.
	 *
	 * @param string $position          Bar position ('left' or 'right').
	 * @param bool   $display_left_bar  Whether to display the left bar.
	 * @param bool   $display_right_bar Whether to display the right bar.
	 *
	 * @return string
	 */
	public static function render_title_bar( $position, $display_left_bar, $display_right_bar ) {
		$html = '';

		// Render the left bar.
		if ( $display_left_bar && 'left' === $position ) {
			$html .= '<span class="rtsb-sub-title-bar rtsb-sub-title-bar-left"></span>';
		}

		// Render the right bar.
		if ( $display_right_bar && 'right' === $position ) {
			$html .= '<span class="rtsb-sub-title-bar rtsb-sub-title-bar-right"></span>';
		}

		return $html;
	}
}
