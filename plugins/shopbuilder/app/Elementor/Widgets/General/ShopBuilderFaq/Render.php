<?php
/**
 * Render class for Advanced Heading widget.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\General\ShopBuilderFaq;

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
		$data            = apply_filters( 'rtsb/general/shopbuilder_faq/args/' . $data['unique_name'], $data );
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
			'display_tab_icon'  => ! empty( $this->settings['display_tab_icon'] ),
			'title_html_tag'    => $this->settings['sb_faq_title_html_tag'] ?? 'h2',
			'tab_icon'          => $this->settings['sb_tab_icon'] ?? '',
			'expanded_icon'     => $this->settings['sb_expanded_icon'] ?? '',
			'tab_icon_position' => $this->settings['tab_icon_position'] ?? 'right',
			'accordion_type'    => $this->settings['sb_faq_type'] ?? 'sb-accordion',
			'display_count'     => ! empty( $this->settings['display_count'] ),
			'faq_items'         => $this->settings['sb_faq_items'] ?? [],
		];
	}
	/**
	 * Function to render icon.
	 *
	 * @return string
	 */
	public static function render_title_icon( $icon ) {
		$html  = '';
		$html .= '<span class="rtsb-icon">' . Fns::icons_manager( $icon ) . '</span>';
		return $html;
	}
}
