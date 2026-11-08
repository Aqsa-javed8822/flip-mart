<?php
/**
 * Main ProductDescription class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\Single;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Abstracts\ElementorWidgetBase;
use RadiusTheme\SB\Elementor\Helper\RenderHelpers;
use RadiusTheme\SB\Elementor\Widgets\Controls\StyleFields;
use RadiusTheme\SB\Elementor\Widgets\Controls\SettingsFields;
use RadiusTheme\SB\Elementor\Widgets\Controls\ProductFlashSale;

/**
 * Product Description class
 */
class ProductBadges extends ElementorWidgetBase {
	/**
	 * Construct function
	 *
	 * @param array $data default array.
	 * @param mixed $args default arg.
	 */
	public function __construct( $data = [], $args = null ) {
		$this->rtsb_name = esc_html__( 'Product Badges', 'shopbuilder' );
		$this->rtsb_base = 'rtsb-product-onsale';
		parent::__construct( $data, $args );
	}
	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public function widget_fields() {

		$fields = array_merge(
			$this->badge(),
			$this->flash_sale_style_settings(),
			$this->badge_style()
		);

		return $fields;
	}

	/**
	 * Flash sale style settings.
	 *
	 * @return array
	 */
	public function flash_sale_style_settings(): array {
		$style_settings = ProductFlashSale::flash_sale( $this );

		// Drop the section-level gate so the badge size controls (width/height/border
		// radius) stay visible even in "Show Only Module Badges" mode. Only the
		// colour/typography controls, which restyle the non-module badge markup, stay
		// gated to that mode.
		unset( $style_settings['flash_sale_section_start']['condition'] );

		$style_settings['flash_sale_section_start']['label'] = esc_html__( 'General', 'shopbuilder' );

		// Keep the "General" section open so the badge style controls (from badge_style())
		// merge into it instead of forming a separate "Badges" section. badge_style()
		// supplies the closing section end.
		unset( $style_settings['flash_sale_section_end'] );

		// The Badges module forces `width: auto !important` on its text badge spans
		// (`.rtsb-badge.type-text > span`), which the width control's `width` cannot beat
		// in "Show Only Module Badges" mode. Drive the badge width via `min-width`
		// instead — the module leaves `min-width` untouched, so this applies for both
		// module and custom-preset badges without an `!important` war and still lets the
		// badge grow for longer text.
		$style_settings['flash_sale_badge_width']['selectors'] = [
			$this->selectors['flash_sale_badge_width'] => 'min-width: {{SIZE}}{{UNIT}};',
		];

		// Flash Sale style settings apply to every badge preset (the selectors already
		// target `.onsale`, `.rtsb-tag-fill` and `.rtsb-tag-outline`), so keep the
		// controls available for all presets instead of gating them per preset.
		$new = [
			'flash_sale_border_color' => [
				'label'     => esc_html__( 'Border Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					$this->selectors['flash_sale_border_color'] => 'border-color: {{VALUE}};',
				],
			],
		];

		$fields = Fns::insert_controls( 'flash_sale_bg_color', $style_settings, $new, true );
		return $fields;
	}
	/**
	 * Badge layout settings.
	 *
	 * @return array
	 */
	public function badge(): array {
		$badge_settings = SettingsFields::badges( $this );

		unset( $badge_settings['badges_section']['condition'] );
		unset( $badge_settings['badges_section']['conditions'] );
		unset( $badge_settings['enable_badges_module']['condition'] );

		$badge_settings['enable_badges_module']['label'] = esc_html__( 'Show Only Module Badges', 'shopbuilder' );

		// The "Enable Badges Module?" toggle only makes sense when the Product Badges
		// module is active. When it isn't, convert the toggle to a hidden control (with
		// an empty value) instead of removing it — other controls' visibility conditions
		// reference this key, so unsetting it would incorrectly hide them too. Hidden +
		// empty keeps those conditions resolving to the custom-badge-preset path.
		if ( ! Fns::is_module_active( 'product_badges' ) ) {
			$badge_settings['enable_badges_module']['type']    = 'hidden';
			$badge_settings['enable_badges_module']['default'] = '';
		}

		unset( $badge_settings['sale_badges_text']['condition']['layout!'] );
		unset( $badge_settings['stock_badges_text']['condition']['layout!'] );
		unset( $badge_settings['custom_badge_text']['condition']['layout!'] );

		$badge_settings['sale_badges_type']['condition'] = [
			'enable_badges_module!' => 'yes',
			'custom_badge_preset!'  => 'preset-default',
		];

		$badge_settings['sale_badges_text']['condition']['custom_badge_preset!']  = 'preset-default';
		$badge_settings['stock_badges_text']['condition']['custom_badge_preset!'] = 'preset-default';
		$badge_settings['custom_badge_text']['condition']['custom_badge_preset!'] = 'preset-default';

		$badge_settings['custom_badge_preset']['options'] = array_merge(
			[
				'preset-default' => [
					'title' => esc_html__( 'Preset Default', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/badge-preset-default.png' ) ),
				],
			],
			$badge_settings['custom_badge_preset']['options']
		);

		$badge_settings['badges_section']['tab'] = 'layout';

		$badge_settings['custom_badge_preset']['default'] = 'preset-default';
		if ( ! empty( $badge_settings['badges_module_alignment'] ) ) {
			// "Group Badge Alignment" applies to both the module group badges and the
			// custom badge presets, so expose it regardless of the module toggle and
			// point its selector at both markups.
			unset( $badge_settings['badges_module_alignment']['condition'] );
			$badge_settings['badges_module_alignment']['default']   = 'start';
			$badge_settings['badges_module_alignment']['selectors'] = [
				$this->selectors['badges_module']['alignment'] => 'align-items: {{VALUE}} !important; justify-content: {{VALUE}} !important;',
				'{{WRAPPER}} .rtsb-product-onsale' => 'display: flex !important; justify-content: {{VALUE}} !important;',
			];
		}
		return $badge_settings;
	}

	/**
	 * Badge style settings.
	 *
	 * @return array
	 */
	public function badge_style(): array {
		$style_settings = StyleFields::product_badges( $this );

		unset( $style_settings['badges_position'] );
		unset( $style_settings['badge_x_position'] );
		unset( $style_settings['badge_y_position'] );
		unset( $style_settings['badges_alignment'] );

		// Merge these controls into the "General" (Flash Sale) section rather than a
		// separate "Badges" section: drop this section's opener (the General opener stays
		// open) and keep its closer to end the merged section.
		unset( $style_settings['badges_style_section'] );

		// Typography/Colors/Border are already provided (and unconditional) by the General
		// section (Border Color via `flash_sale_border_color`, radius via the "Badge Border
		// Radius" slider), so drop the duplicates here and keep only the unique Padding/Margin
		// controls. Everything now shows regardless of the "Show Only Module Badges" toggle,
		// since the selectors target both the default and custom badge markup.
		unset(
			$style_settings['badges_typo_note'],
			$style_settings['rtsb_el_badges_typography'],
			$style_settings['badges_color_note'],
			$style_settings['badges_color'],
			$style_settings['badges_bg_color'],
			$style_settings['badges_border_color'],
			$style_settings['badges_border_radius']
		);

		return $style_settings;
	}
	/**
	 * Set Widget Keyword.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return [ 'On Sale', 'sale flash' ] + parent::get_keywords();
	}
	/**
	 * Set icons function
	 *
	 * @param [type] $availability status.
	 * @param [type] $obj  WC_Product class object.
	 * @return string
	 */

	/**
	 * Render Function
	 *
	 * @return void
	 */
	protected function render() {
		global $product, $post;
		$_product    = $product;
		$_post       = $post;
		$product     = Fns::get_product();
		$controllers = $this->get_settings_for_display();
		$this->theme_support();

		$data = [
			'template'    => 'elementor/single-product/product-badges',
			'controllers' => $controllers,
		];

		$badges_visibility                 = rtsb()->has_pro() && Fns::is_guest_feature_disabled( 'hide_badges', '' );
		$data['controllers']['visibility'] = $badges_visibility ? ' rtsb-not-visible' : ' rtsb-is-visible';

		Fns::load_template( $data['template'], $data );

		$this->theme_support( 'render_reset' );
		$product = $_product; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		$post    = $_post; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
	}
}
