<?php
/**
 * Render class for Pricing Table widget.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\General\PricingTable;

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
		$data            = apply_filters( 'rtsb/general/pricing_table/args/' . $data['unique_name'], $data );
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
			'sb_pricing_table_title_html_tag' => $this->settings['sb_pricing_table_title_html_tag'] ?? 'h2',
			'sb_pricing_table_title'          => $this->settings['sb_pricing_table_title'],
			'preset_layout'                   => $this->settings['layout_style'] ?? 'rtsb-pricing-table-layout1',
			'has_description'                 => ! empty( $this->settings['display_content'] ) && ! empty( $this->settings['sb_pricing_table_content'] ),
			'has_table_icon'                  => ! empty( $this->settings['sb_pricing_table_display_icon'] ),
			'description'                     => $this->settings['sb_pricing_table_content'] ?? '',
			'has_button'                      => ! empty( $this->settings['display_button'] ),
			'price'                           => $this->settings['sb_pricing_table_price'],
			'has_sale_price'                  => ! empty( $this->settings['sale_price'] ) && ! empty( $this->settings['sb_pricing_table_sale_price'] ),
			'sale_price'                      => $this->settings['sb_pricing_table_sale_price'],
			'price_currency'                  => $this->settings['sb_pricing_table_currency'] ?? '$',
			'currency_position'               => $this->settings['sb_pricing_table_currency_position'] ?? 'left',
			'price_unit'                      => $this->settings['sb_pricing_table_unit'] ?? 'mo',
			'price_unit_separator'            => $this->settings['sb_pricing_table_unit_separator'] ?? '/',
			'has_badge'                       => ! empty( $this->settings['sb_pricing_table_badge'] ),
			'badge_style'                     => $this->settings['sb_pricing_table_badge_style'],
			'badge_text'                      => $this->settings['sb_pricing_table_badge_text'] ?? '',
			'offer_text'                      => $this->settings['sb_pricing_table_offer_text'] ?? '',
			'badge_position'                  => $this->settings['sb_pricing_table_badge_position'] ?? 'right',
			'sb_button_content'               => $this->settings['sb_button_content'],
			'sb_button_icon'                  => $this->settings['sb_button_icon'] ?? '',
			'sb_button_icon_position'         => $this->settings['sb_button_icon_position'] ?? 'right',
			'button_attributes'               => $this->render_button_attributes( 'rtsb_button_' . $id, 'sb_button_link', 'primary-btn' ) ?? '',
			'icon_html'                       => $this->render_icon_image(),
			'feature_item_html'               => $this->render_pricing_table_feature(),
			'icon_type'                       => $this->settings['sb_pricing_table_icon_type'],
			'icon_bg_type'                    => ! empty( $this->settings['pricing_table_icon_style_gradient_bg_background'] ) ? $this->settings['pricing_table_icon_style_gradient_bg_background'] : 'classic',
			'badge_bg_type'                   => ! empty( $this->settings['pricing_table_badge_style_gradient_bg_background'] ) ? $this->settings['pricing_table_badge_style_gradient_bg_background'] : 'classic',
		];
	}
	/**
	 * Function to render button attributes.
	 *
	 * @param string $id Element ID.
	 *
	 * @return string
	 */
	public function render_button_attributes( $id, $control_name, $class, $type = 'rtsb-btn' ) {
		if ( ! empty( $this->settings['hover_animation'] ) ) {
			$class .= ' ' . 'hover-' . $this->settings['hover_animation'];
		}
		$this->add_attribute( $id, 'class', $type . ' ' . $class );

		if ( ! empty( $this->settings[ $control_name ]['url'] ) ) {
			$this->add_link_attributes( $id, $this->settings[ $control_name ] );
		} else {
			$this->add_attribute( $id, 'role', 'button' );
		}

		return $this->get_attribute_string( $id );
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

		$icon = $this->settings['sb_pricing_table_icon'] ?? '';

		if ( 'icon' === $this->settings['sb_pricing_table_icon_type'] ) {
			$icon_img_html .= '<span class="icon">' . Fns::icons_manager( $icon ) . '</span>';
		} else {
			if ( ! empty( $this->settings['sb_pricing_table_image'] ) ) {
				$c_image_size         = RenderHelpers::get_data( $this->settings, 'sb_pricing_table_img_dimension', [] );
				$c_image_size['crop'] = RenderHelpers::get_data( $this->settings, 'sb_pricing_table_img_crop', [] );
				$c_image_size         = ! empty( $c_image_size ) && is_array( $c_image_size ) ? $c_image_size : [];
				$image_id             = ! empty( $this->settings['sb_pricing_table_image']['id'] ) ? $this->settings['sb_pricing_table_image']['id'] : $this->settings['sb_pricing_table_image'];
				$icon_img_html       .= Fns::get_product_image_html(
					'',
					null,
					RenderHelpers::get_data( $this->settings, 'sb_pricing_table_image_size', 'full' ),
					$image_id,
					$c_image_size
				);
			}
		}

		return $icon_img_html;
	}
	/**
	 * Function to render feature.
	 *
	 * @return string
	 */
	public function render_pricing_table_feature() {
		$feature_html = '';
		if ( ! empty( $this->settings['sb_pricing_table_feature_items'] ) ) {

			$feature_html .= '<ul class="rtsb-feature-list">';
			foreach ( $this->settings['sb_pricing_table_feature_items'] as $key => $item ) {
				$cross_text    = ! empty( $item['sb_pricing_table_feature_cross_text'] ) ? 'has-cross-text' : '';
				$feature_html .= '<li class="list-type-' . esc_attr( $item['sb_pricing_table_list_style'] . ' ' . $cross_text ) . '">';
				if ( 'icon' === $item['sb_pricing_table_list_style'] ) {
					$feature_html .= '<span class="list-icon">' . Fns::icons_manager( $item['sb_pricing_table_feature_icon'] ) . '</span>';
				}
				$feature_html .= '<span class="list-text">' . $item['sb_pricing_table_feature_text'] . '</span>';
				$feature_html .= '</li>';
			}
			$feature_html .= '</ul>';

		}
		return $feature_html;
	}
	/**
	 * Function to render price.
	 *
	 * @return string
	 */
	public static function render_price( $has_sale_price, $currency_position, $price_currency, $price, $sale_price, $price_unit, $price_unit_separator ) {
		ob_start();
		?>
		<div class="rtsb-pricing-wrap">
			<div class="rtsb-price-wrap-inner">
				<span class="rtsb-price-wrap">
					<span class="rtsb-orginal-price <?php echo esc_attr( $has_sale_price ? 'line-through' : '' ); ?>">
					<?php Fns::print_html( self::render_price_currency( 'left', $currency_position, $price_currency ) ); ?>
					<span class="rtsb-price"><?php Fns::print_html( $price ); ?></span>
					<?php Fns::print_html( self::render_price_currency( 'right', $currency_position, $price_currency ) ); ?>
					</span>
					<?php if ( $has_sale_price ) { ?>
						<span class="rtsb-sale-price">
							<?php Fns::print_html( self::render_price_currency( 'left', $currency_position, $price_currency ) ); ?>
							<span class="rtsb-price"><?php Fns::print_html( $sale_price ); ?></span>
							<?php Fns::print_html( self::render_price_currency( 'right', $currency_position, $price_currency ) ); ?>
						</span>
					<?php } ?>
				</span>
				<?php if ( $price_unit ) { ?>
					<span class="rtsb-price-period">
							<?php
							if ( $price_unit_separator ) :
								?>
								<span class="period-seperator"><?php Fns::print_html( $price_unit_separator ); ?></span><?php endif; ?>
							<span class="period-text"><?php Fns::print_html( $price_unit ); ?></span>
						</span>
				<?php } ?>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Function to render the badge.
	 *
	 * @param bool   $has_badge Whether the badge is enabled.
	 * @param string $badge_style Badge Presets.
	 * @param string $badge_text Badge Text.
	 * @param string $badge_position Separator position ('left' or 'right').
	 * @return string
	 */
	public static function render_badge( $has_badge, $badge_style, $badge_text, $badge_position, $badge_bg_type ) {
		ob_start();
		$feature_icon_svg = '<svg width="12" height="22" viewBox="0 0 12 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M5 14H0L7 0V8H12L5 22V14Z" fill="white"/>
                            </svg>';
		?>
		<?php
		if ( $has_badge ) {
			if ( 'rtsb-pricing-table-badge-preset4' === $badge_style ) {
				?>
				<span class="rtsb-ribbon-span <?php echo esc_attr( $badge_style . ' ' . $badge_position . ' ' . 'has-' . $badge_bg_type ); ?>">
					<span class="badge-icon">
						 <?php Fns::print_html( $feature_icon_svg ); ?>
					</span>
				</span>
				<?php
			} else {
				?>
				<span class="rtsb-ribbon-span <?php echo esc_attr( $badge_style . ' ' . $badge_position . ' ' . 'has-' . $badge_bg_type ); ?>">
					<?php Fns::print_html( $badge_text ); ?>
					<span class="triangle-bar bar-one"> </span>
					<span class="triangle-bar bar-two"></span>
				</span>
				<?php
			}
		}
		?>
		<?php
		return ob_get_clean();
	}
	/**
	 * Function to render the left or right price currency.
	 *
	 * @param string $position  currency position ('left' or 'right').
	 *
	 * @return string
	 */
	public static function render_price_currency( $position, $currency_position, $currency ) {
		$html = '';
		// Render the left currency.
		if ( $position === $currency_position ) {
			$html .= '<span class="rtsb-currency">' . $currency . '</span>';
		}
		return $html;
	}
}
