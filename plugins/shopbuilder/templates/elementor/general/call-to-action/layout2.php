<?php
/**
 * Template: Call To Action Layout 1
 *
 * @package RadiusTheme\SB
 */

/**
 * Template variables:
 *
 * @var $cta_title                   string
 * @var $cta_title_html_tag          string
 * @var $has_button2                 bool
 * @var $has_button                  bool
 * @var $description                 string
 * @var $has_description             bool
 * @var $has_cta_img_linkable        bool
 * @var $has_cta_img_link            bool
 * @var $sb_button_content           string
 * @var $sb_button2_content          string
 * @var $sb_button_icon              array
 * @var $sb_button_icon_position     string
 * @var $button1_attributes          string
 * @var $button2_attributes          string
 * @var $cta_img_link_attributes     string
 * @var $section_parallax            string
 * @var $img_html                    string
 * @var $parallax_speed              integer
 */

use RadiusTheme\SB\Elementor\Render\GeneralAddons;
use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Elementor\Widgets\General\CallToAction\Render;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

?>
<div class="rtsb-cta-wrapper <?php echo esc_attr( $section_parallax ); ?>" data-speed="<?php echo esc_attr( $parallax_speed ); ?>">
	<div class="cta-container">
		<div class="cta-content-wrap">
			<div class="cta-content-left">
				<div class="cta-img-wrapper">
					<?php
					if ( $has_cta_img_linkable ) {
						?>
						<a <?php Fns::print_html( $cta_img_link_attributes ); ?> >
							<?php Fns::print_html( $img_html ); ?>
						</a>
						<?php
					} else {
						Fns::print_html( $img_html );
					}
					?>
				</div>
			</div>
			<div class="cta-content-right">
				<?php
				/**
				 * Title.
				 */
				?>
				<<?php Fns::print_validated_html_tag( $cta_title_html_tag ); ?> class="rtsb-cta-title">
				<?php Fns::print_html( $cta_title ); ?>
			</<?php Fns::print_validated_html_tag( $cta_title_html_tag ); ?>>
			<?php
			/**
			 * Description.
			 */
			if ( $has_description ) {
				?>
				<div class="rtsb-cta-description">
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
								'button_attributes'       => $button1_attributes,
								'sb_button_icon'          => $sb_button_icon,
								'sb_button_icon_position' => $sb_button_icon_position,
								'sb_button_content'       => $sb_button_content,
							]
						);
					?>
					<?php
					if ( $has_button2 ) {
						?>
						<?php
						GeneralAddons::render_buttom_html(
							[
								'button_attributes'       => $button2_attributes,
								'sb_button_icon'          => $sb_button_icon,
								'sb_button_icon_position' => $sb_button_icon_position,
								'sb_button_content'       => $sb_button2_content,
							]
						);
						?>
					<?php } ?>
				</div>
			<?php } ?>
		</div>
		</div>
	</div>

</div>
