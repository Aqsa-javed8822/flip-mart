<?php
/**
 * Render class for Advanced Heading widget.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\General\ShopBuilderButton;

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
	 * @param array $data Data to be passed to the template.
	 * @param array $settings Widget settings.
	 *
	 * @return string
	 */
	public function display_content( $data, $settings ) {
		$this->settings  = $settings;
		$data            = wp_parse_args( $this->get_template_args( $data['id'] ), $data );
		$data            = apply_filters( 'rtsb/general/shopbuilder_button/args/' . $data['unique_name'], $data );
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
			'sb_button__content'       => $this->settings['sb_button__content'],
			'sb_button2__content'      => $this->settings['sb_button2__content'],
			'sb_button_link'           => ! empty( $this->settings['sb_button_link']['url'] ) ? $this->settings['sb_button_link']['url'] : '#',
			'sb_button2_link'          => ! empty( $this->settings['sb_button2_link']['url'] ) ? $this->settings['sb_button2_link']['url'] : '#',
			'sb_button_target'         => ! empty( $this->settings['sb_button_link']['is_external'] ) ? '_blank' : '_self',
			'sb_button2_target'        => ! empty( $this->settings['sb_button2_link']['is_external'] ) ? '_blank' : '_self',
			'sb_button1_icon'          => $this->settings['sb_button1_icon'] ?? '',
			'sb_button1_icon_position' => $this->settings['sb_button1_icon_position'] ?? 'right',
			'sb_button2_icon'          => $this->settings['sb_button2_icon'] ?? '',
			'sb_button2_icon_position' => $this->settings['sb_button2_icon_position'] ?? 'right',
			'has_connector'            => ! empty( $this->settings['display_connector'] ),
			'connector_type'           => $this->settings['connector_type'] ?? 'text',
			'sb_button_connect_text'   => $this->settings['sb_button_connect_text'] ?? '',
			'sb_button_connect_icon'   => $this->settings['sb_button_connect_icon'] ?? '',
			'button1_attributes'       => $this->render_button_attributes( 'rtsb_button_' . $id, 'sb_button_link', 'rtsb-btn rtsb-primary-btn' ) ?? '',
			'button2_attributes'       => $this->render_button_attributes( 'rtsb_button2_' . $id, 'sb_button2_link', 'rtsb-btn rtsb-secondary-btn' ) ?? '',
		];
	}

	/**
	 * Function to render icon.
	 *
	 * @param string $position icon position ('left' or 'right').
	 * @param string $icon Position of the separator in settings.
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
	 * Function to render button attributes.
	 *
	 * @param string $id Element ID.
	 *
	 * @return string
	 */
	public function render_button_attributes( $id, $control_name, $class = 'rtsb-btn' ) {
		if ( ! empty( $this->settings['hover_animation'] ) ) {
			$class .= ' ' . 'hover-' . $this->settings['hover_animation'];
		}
		$this->add_attribute( $id, 'class', $class );

		if ( ! empty( $this->settings[ $control_name ]['url'] ) ) {
			$this->add_link_attributes( $id, $this->settings[ $control_name ] );
		} else {
			$this->add_attribute( $id, 'role', 'button' );
		}

		return $this->get_attribute_string( $id );
	}
	/**
	 * Function to render connector.
	 *
	 * @param string $connector_type  ('text' or 'icon').
	 * @param string $sb_button_connect_text .
	 * @param string $sb_button_connect_icon .
	 *
	 * @return string
	 */
	public static function render_connector( $connector_type, $sb_button_connect_text, $sb_button_connect_icon ) {
		ob_start();
		?>
		<div class="rtsb-dual-btn-connector">
			<div class="connector-inner">
				<?php
				if ( 'text' === $connector_type ) {
					Fns::print_html( $sb_button_connect_text );
				}
				if ( 'icon' === $connector_type ) {
					Fns::print_html( Fns::icons_manager( $sb_button_connect_icon ) );
				}
				?>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}
}
