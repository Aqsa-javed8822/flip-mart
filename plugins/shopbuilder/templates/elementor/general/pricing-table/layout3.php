<?php
/**
 * Template: Info Box
 *
 * @package RadiusTheme\SB
 */

/**
 * Template variables:
 *
 * @var $sb_pricing_table_title             string
 * @var $sb_pricing_table_title_html_tag    string
 * @var $has_button                         bool
 * @var $description                        string
 * @var $price                              string
 * @var $sale_price                         string
 * @var $price_currency                     string
 * @var $currency_position                  string
 * @var $price_unit                         string
 * @var $price_unit_separator               string
 * @var $badge_style                        string
 * @var $badge_text                         string
 * @var $offer_text                         string
 * @var $feature_items                      array
 * @var $badge_position                     string
 * @var $icon_bg_type                       string
 * @var $icon_hover_bg_type                 string
 * @var $has_description                    bool
 * @var $has_table_icon                     bool
 * @var $has_button                         bool
 * @var $has_sale_price                     bool
 * @var $has_badge                          bool
 * @var $sb_button_content                  string
 * @var $sb_button_icon                     array
 * @var $sb_button_icon_position            string
 * @var $button_attributes                  string
 * @var $icon_html                          string
 * @var $feature_item_html                  string
 * @var $icon_type                          string
 * @var $badge_bg_type                      string
 */

use RadiusTheme\SB\Elementor\Render\GeneralAddons;
use RadiusTheme\SB\Elementor\Widgets\General\PricingTable\Render;
use RadiusTheme\SB\Helpers\Fns;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

?>
<div class="rtsb-pricing-table-wrapper">
	<div class="rtsb-pricing-table-box">
		<?php Fns::print_html( Render::render_badge( $has_badge, $badge_style, $badge_text, $badge_position, $badge_bg_type ) ); ?>
		<div class="pricing-table-header">
			<?php
			/**
			 * Title.
			 */
			?>
			<<?php Fns::print_validated_html_tag( $sb_pricing_table_title_html_tag ); ?> class="rtsb-pricing-title">
			<?php Fns::print_html( $sb_pricing_table_title ); ?>
			</<?php Fns::print_validated_html_tag( $sb_pricing_table_title_html_tag ); ?>>
			<?php
			/**
			 * Description.
			 */
			if ( $has_description ) {
				?>
				<div class="rtsb-pricing-table-description">
					<?php Fns::print_html( $description ); ?>
				</div>
				<?php
			}
			?>
			<?php if ( ! empty( $icon_html ) ) { ?>
				<div class="pricing-table-icon-holder <?php echo esc_attr( $icon_type ); ?>">
					<div class="icon-wrap <?php echo esc_attr( 'has-' . $icon_bg_type ); ?>">
						<?php Fns::print_html( $icon_html ); ?>
					</div>
				</div>
			<?php } ?>
			<?php
			/**
			 * Price.
			 */
			Fns::print_html( Render::render_price( $has_sale_price, $currency_position, $price_currency, $price, $sale_price, $price_unit, $price_unit_separator ) );
			?>
			<?php if ( $offer_text ) { ?>
				<div class="rtsb-offer-text">
					<?php Fns::print_html( $offer_text ); ?>
				</div>
			<?php } ?>
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
			<?php } ?>

		</div>
		<div class="rtsb-pricing-button-separator"></div>
		<div class="pricing-table-footer">

			<?php
			/**
			 * Feature Item.
			 */
			?>
			<?php Fns::print_html( $feature_item_html ); ?>
		</div>

	</div>
</div>
