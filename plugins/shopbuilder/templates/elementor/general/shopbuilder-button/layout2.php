<?php
/**
 * Template: ShopBuilder Button
 *
 * @package RadiusTheme\SB
 */

/**
 * Template variables:
 *
 * @var $sb_button__content                 string
 * @var $sb_button2__content                string
 * @var $sb_button_link                     string
 * @var $sb_button2_link                    string
 * @var $sb_button2_icon                    string
 * @var $sb_button1_icon                    string
 * @var $sb_button1_icon_position           string
 * @var $sb_button2_icon_position           string
 * @var $has_connector                      bool
 * @var $connector_type                     string
 * @var $sb_button_connect_text             string
 * @var $sb_button_connect_icon             string
 * @var $button1_attributes                 string
 * @var $button2_attributes                 string
 */

use RadiusTheme\SB\Elementor\Render\GeneralAddons;
use RadiusTheme\SB\Elementor\Widgets\General\ShopBuilderButton\Render;
use RadiusTheme\SB\Helpers\Fns;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

$connector = Render::render_connector( $connector_type, $sb_button_connect_text, $sb_button_connect_icon );
?>
<div class="rtsb-shopbuilder-button-wrap">
	<div class="rtsb-shopbuilder-button">
		<?php
		GeneralAddons::render_buttom_html(
			[
				'button_attributes'       => $button1_attributes,
				'sb_button_icon'          => $sb_button1_icon,
				'sb_button_icon_position' => $sb_button1_icon_position,
				'sb_button_content'       => $sb_button__content,
			]
		);
		?>
		<?php
		if ( $has_connector ) {
			Fns::print_html( $connector );
		}
		?>
		<?php
		GeneralAddons::render_buttom_html(
			[
				'button_attributes'       => $button2_attributes,
				'sb_button_icon'          => $sb_button2_icon,
				'sb_button_icon_position' => $sb_button2_icon_position,
				'sb_button_content'       => $sb_button2__content,
			]
		);
		?>
	</div>
</div>
