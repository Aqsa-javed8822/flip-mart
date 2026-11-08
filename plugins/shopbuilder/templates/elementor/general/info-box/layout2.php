<?php
/**
 * Template: Info Box
 *
 * @package RadiusTheme\SB
 */

/**
 * Template variables:
 *
 * @var $info_box_title              string
 * @var $info_box_title_html_tag     string
 * @var $has_button                  bool
 * @var $description                 string
 * @var $has_description             bool
 * @var $has_count                   bool
 * @var $sb_button_content           string
 * @var $count                       string
 * @var $sb_button_icon              array
 * @var $sb_button_icon_position     string
 * @var $button_attributes           string
 * @var $icon_html                   string
 * @var $icon_type                   string
 * @var $icon_bg_type                string
 * @var $icon_hover_bg_type          string
 */

use RadiusTheme\SB\Elementor\Render\GeneralAddons;
use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Elementor\Widgets\General\CallToAction\Render;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}
?>
<div class="rtsb-info-box-wrap">
	<div class="rtsb-info-box-inner">
		<div class="info-box-top-content">
			<div class="icon-title-wrap">
				<?php
				if ( ! empty( $icon_html ) ) {
					?>
					<div class="info-box-icon-holder <?php echo esc_attr( $icon_type ); ?>">
						<div class="icon-wrap <?php echo esc_attr( 'has-' . $icon_bg_type . '-bg has-' . $icon_hover_bg_type . '-hover-bg' ); ?>">
							<?php Fns::print_html( $icon_html ); ?>
						</div>
					</div>
					<?php
				}

				/**
				 * Title.
				 */
				?>
				<<?php Fns::print_validated_html_tag( $info_box_title_html_tag ); ?> class="rtsb-info-box-title">
				<?php Fns::print_html( $info_box_title ); ?>
				</<?php Fns::print_validated_html_tag( $info_box_title_html_tag ); ?>>
			</div>
			<?php
			if ( $has_count && ! empty( $count ) ) {
				?>
				<div class="info-box-counter">
					<?php Fns::print_html( $count ); ?>
				</div>
			<?php } ?>
		</div>

		<div class="info-box-bottom-content">

			<?php
			/**
			 * Description.
			 */
			if ( $has_description ) {
				?>
				<div class="rtsb-info-box-description">
					<?php Fns::print_html( $description ); ?>
				</div>
				<?php
			}
			?>
			<?php
			/**
			 * Button.
			 */
			if ( $has_button ) {
				?>
				<div class="rtsb-shopbuilder-button">
					<?php
					GeneralAddons::render_buttom_html(
						[
							'button_attributes'       => $button_attributes,
							'sb_button_icon'          => $sb_button_icon,
							'sb_button_icon_position' => $sb_button_icon_position,
							'sb_button_content'       => $sb_button_content,
						]
					);
					?>
				</div>

				<?php
			}
			?>
		</div>
	</div>
</div>
