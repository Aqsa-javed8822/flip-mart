<?php
/**
 * Selectors class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\Archive\ProductFilters;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Selectors class.
 *
 * @package RadiusTheme\SB
 */
class Selectors {
	/**
	 * Product Filters CSS Selectors.
	 *
	 * @return array
	 */
	public static function get_selectors() {
		return [
			'filter_types'      => [
				'column_gap' => '{{WRAPPER}} .rtsb-archive-catalog-ordering',
			],
			'filter_header'     => [
				'typography' => '{{WRAPPER}} .rtsb-default-archive-filters .rtsb-default-filter-header h3',
				'color'      => '{{WRAPPER}} .rtsb-default-archive-filters .rtsb-default-filter-header h3',
				'bg_color'   => '{{WRAPPER}} .rtsb-default-archive-filters .rtsb-default-filter-header h3',
				'padding'    => '{{WRAPPER}} .rtsb-default-archive-filters .rtsb-default-filter-header h3',
				'border'     => '{{WRAPPER}} .rtsb-default-archive-filters .rtsb-default-filter-header h3',
				'margin'     => '{{WRAPPER}} .rtsb-default-archive-filters .rtsb-default-filter-header h3',
			],
			'filter_title'      => [
				'typography' => '{{WRAPPER}} .rtsb-default-archive-filters .default-filter-title-wrapper h3',
				'color'      => '{{WRAPPER}} .rtsb-default-archive-filters .default-filter-title-wrapper h3',
				'bg_color'   => '{{WRAPPER}} .rtsb-default-archive-filters .default-filter-title-wrapper h3',
				'padding'    => '{{WRAPPER}} .rtsb-default-archive-filters .default-filter-title-wrapper h3',
				'border'     => '{{WRAPPER}} .rtsb-default-archive-filters .default-filter-title-wrapper h3',
				'margin'     => '{{WRAPPER}} .rtsb-default-archive-filters .default-filter-title-wrapper h3',
			],
			'search_styles'     => [
				'typography'                => '{{WRAPPER}} .rtsb-default-archive-filters .rtsb-default-product-search .rtsb-default-search-field',
				'button_typography'         => '{{WRAPPER}} .rtsb-default-archive-filters .rtsb-default-product-search .rtsb-search-submit',
				'icon_typography'           => '{{WRAPPER}} .rtsb-default-archive-filters .rtsb-default-product-search .rtsb-search-submit',
				'search_input_height'       => '{{WRAPPER}} .rtsb-default-archive-filters .rtsb-default-product-search .rtsb-default-search-field',
				'search_button_width'       => '{{WRAPPER}} .rtsb-default-archive-filters .rtsb-default-product-search .rtsb-search-submit',
				'search_icon_width'         => '{{WRAPPER}} .rtsb-default-archive-filters .rtsb-default-product-search .rtsb-search-submit .search-icon, {{WRAPPER}} .rtsb-default-archive-filters .rtsb-default-product-search .rtsb-search-submit svg',
				'search_icon_height'        => '{{WRAPPER}} .rtsb-default-archive-filters .rtsb-default-product-search .rtsb-search-submit .search-icon, {{WRAPPER}} .rtsb-default-archive-filters .rtsb-default-product-search .rtsb-search-submit svg',
				'color'                     => '{{WRAPPER}} .rtsb-default-archive-filters .rtsb-default-product-search .rtsb-default-search-field',
				'bg_color'                  => '{{WRAPPER}} .rtsb-default-archive-filters .rtsb-default-product-search .rtsb-default-search-field',
				'button_color'              => '{{WRAPPER}} .rtsb-default-archive-filters .rtsb-default-product-search .rtsb-search-submit',
				'button_bg_color'           => '{{WRAPPER}} .rtsb-default-archive-filters .rtsb-default-product-search .rtsb-search-submit',
				'placeholder_color'         => '{{WRAPPER}} .rtsb-default-archive-filters .rtsb-default-product-search ::-webkit-input-placeholder, {{WRAPPER}} .rtsb-default-archive-filters .rtsb-default-product-search ::placeholder',
				'hover_color'               => '{{WRAPPER}} .rtsb-default-archive-filters .rtsb-default-product-search .rtsb-default-search-field:hover',
				'hover_bg_color'            => '{{WRAPPER}} .rtsb-default-archive-filters .rtsb-default-product-search .rtsb-default-search-field:hover',
				'hover_button_color'        => '{{WRAPPER}} .rtsb-default-archive-filters .rtsb-default-product-search .rtsb-search-submit:hover',
				'hover_button_bg_color'     => '{{WRAPPER}} .rtsb-default-archive-filters .rtsb-default-product-search .rtsb-search-submit:hover',
				'border'                    => '{{WRAPPER}} .rtsb-default-archive-filters .rtsb-default-product-search .rtsb-default-search-field',
				'button_border'             => '{{WRAPPER}} .rtsb-default-archive-filters .rtsb-default-product-search .rtsb-search-submit',
				'border_hover_color'        => '{{WRAPPER}} .rtsb-default-archive-filters .rtsb-default-product-search .rtsb-default-search-field:hover',
				'button_border_hover_color' => '{{WRAPPER}} .rtsb-default-archive-filters .rtsb-default-product-search .rtsb-search-submit:hover',
				'input_border_radius'       => '{{WRAPPER}} .rtsb-default-archive-filters .rtsb-default-product-search .rtsb-default-search-field',
				'button_border_radius'      => '{{WRAPPER}} .rtsb-default-archive-filters .rtsb-default-product-search .rtsb-search-submit',
				'padding'                   => '{{WRAPPER}} .rtsb-default-archive-filters .rtsb-default-product-search .rtsb-default-search-field',
				'margin'                    => '{{WRAPPER}} .rtsb-default-archive-filters .rtsb-default-product-search .rtsb-default-search-field',
				'button_padding'            => '{{WRAPPER}} .rtsb-default-archive-filters .rtsb-default-product-search .rtsb-search-submit',
				'button_margin'             => '{{WRAPPER}} .rtsb-default-archive-filters .rtsb-default-product-search .rtsb-search-submit',
				'wrapper_margin'            => '{{WRAPPER}} .rtsb-default-archive-filters .rtsb-default-product-search',
			],
			'rating'            => [
				'typography'            => '{{WRAPPER}} .rtsb-product-default-filters.rtsb-ratings .rtsb-default-rating-star-wrapper .rtsb-default-rating-star',
				'rating_color'          => '{{WRAPPER}} .rtsb-product-default-filters.rtsb-ratings .rtsb-default-rating-star-wrapper .rtsb-default-rating-star.inactive',
				'count_position'        => '{{WRAPPER}} .rtsb-product-default-filters.rtsb-ratings .rtsb-default-rating-star-wrapper + .rtsb-count',
				'rating_active_color'   => '{{WRAPPER}} .rtsb-product-default-filters.rtsb-ratings .rtsb-default-rating-star-wrapper .rtsb-default-rating-star.active',
				'rating_selected_color' => '{{WRAPPER}} .rtsb-product-default-filters.rtsb-ratings .rtsb-default-filter-trigger.checked + .rtsb-checkbox-filter-label .rtsb-default-rating-star.active',
				'rating_padding'        => '{{WRAPPER}} .rtsb-product-default-filters.rtsb-ratings .product-default-filters .rtsb-default-filter-group',
				'rating_margin'         => '{{WRAPPER}} .rtsb-product-default-filters.rtsb-ratings .product-default-filters .rtsb-default-filter-group',
			],
			'apply_btn'         => [
				'typography'         => '{{WRAPPER}} .rtsb-default-archive-filters .rtsb-apply-filters-btn .rtsb-apply-filters',
				'apply_btn_width'    => '{{WRAPPER}} .rtsb-default-archive-filters .rtsb-apply-filters-btn .rtsb-apply-filters',
				'icon_display'       => '{{WRAPPER}} .rtsb-default-archive-filters .rtsb-apply-filters-btn .rtsb-apply-filters .icon',
				'apply_icon_size'    => '{{WRAPPER}} .rtsb-default-archive-filters .rtsb-apply-filters-btn .rtsb-apply-filters .icon',
				'color'              => '{{WRAPPER}} .rtsb-default-archive-filters .rtsb-apply-filters-btn .rtsb-apply-filters',
				'icon_color'         => '{{WRAPPER}} .rtsb-default-archive-filters .rtsb-apply-filters-btn .rtsb-apply-filters .icon',
				'bg_color'           => '{{WRAPPER}} .rtsb-default-archive-filters .rtsb-apply-filters-btn .rtsb-apply-filters',
				'hover_color'        => '{{WRAPPER}} .rtsb-default-archive-filters .rtsb-apply-filters-btn .rtsb-apply-filters:hover',
				'hover_icon_color'   => '{{WRAPPER}} .rtsb-default-archive-filters .rtsb-apply-filters-btn .rtsb-apply-filters:hover .icon',
				'hover_bg_color'     => '{{WRAPPER}} .rtsb-default-archive-filters .rtsb-apply-filters-btn .rtsb-apply-filters:hover',
				'border'             => '{{WRAPPER}} .rtsb-default-archive-filters .rtsb-apply-filters-btn .rtsb-apply-filters',
				'border_hover_color' => '{{WRAPPER}} .rtsb-default-archive-filters .rtsb-apply-filters-btn .rtsb-apply-filters:hover',
				'border_radius'      => '{{WRAPPER}} .rtsb-default-archive-filters .rtsb-apply-filters-btn .rtsb-apply-filters',
				'padding'            => '{{WRAPPER}} .rtsb-default-archive-filters .rtsb-apply-filters-btn .rtsb-apply-filters',
				'margin'             => '{{WRAPPER}} .rtsb-default-archive-filters .rtsb-apply-filters-btn .rtsb-apply-filters',
			],
			'reset_btn'         => [
				'typography'         => '{{WRAPPER}} .rtsb-product-default-filters.rtsb-reset .product-default-filter-reset',
				'btn_width'          => '{{WRAPPER}} .rtsb-product-default-filters.rtsb-reset .product-default-filter-reset',
				'color'              => '{{WRAPPER}} .rtsb-product-default-filters.rtsb-reset .product-default-filter-reset',
				'bg_color'           => '{{WRAPPER}} .rtsb-product-default-filters.rtsb-reset .product-default-filter-reset',
				'hover_color'        => '{{WRAPPER}} .rtsb-product-default-filters.rtsb-reset .product-default-filter-reset:hover',
				'hover_bg_color'     => '{{WRAPPER}} .rtsb-product-default-filters.rtsb-reset .product-default-filter-reset:hover',
				'border'             => '{{WRAPPER}} .rtsb-product-default-filters.rtsb-reset .product-default-filter-reset',
				'border_hover_color' => '{{WRAPPER}} .rtsb-product-default-filters.rtsb-reset .product-default-filter-reset:hover',
				'border_radius'      => '{{WRAPPER}} .rtsb-product-default-filters.rtsb-reset .product-default-filter-reset',
				'padding'            => '{{WRAPPER}} .rtsb-product-default-filters.rtsb-reset .product-default-filter-reset',
				'margin'             => '{{WRAPPER}} .rtsb-product-default-filters.rtsb-reset .product-default-filter-reset',
			],
			'mobile_toggle_btn' => [
				'typography'         => '{{WRAPPER}} .rtsb-default-archive-filters  .product-filter-toggle',
				'btn_width'          => '{{WRAPPER}} .rtsb-default-archive-filters  .product-filter-toggle',
				'color'              => '{{WRAPPER}} .rtsb-default-archive-filters  .product-filter-toggle',
				'bg_color'           => '{{WRAPPER}} .rtsb-default-archive-filters  .product-filter-toggle',
				// Touch devices have no :hover, so the toggle's Hover controls also target
				// :active (press) and .active (open state, set by product-default-filter.js).
				'hover_color'        => '{{WRAPPER}} .rtsb-default-archive-filters .product-filter-toggle:hover, {{WRAPPER}} .rtsb-default-archive-filters .product-filter-toggle:active, {{WRAPPER}} .rtsb-default-archive-filters .product-filter-toggle.active',
				'hover_bg_color'     => '{{WRAPPER}} .rtsb-default-archive-filters .product-filter-toggle:hover, {{WRAPPER}} .rtsb-default-archive-filters .product-filter-toggle:active, {{WRAPPER}} .rtsb-default-archive-filters .product-filter-toggle.active',
				'border'             => '{{WRAPPER}} .rtsb-default-archive-filters  .product-filter-toggle',
				'border_hover_color' => '{{WRAPPER}} .rtsb-default-archive-filters .product-filter-toggle:hover, {{WRAPPER}} .rtsb-default-archive-filters .product-filter-toggle:active, {{WRAPPER}} .rtsb-default-archive-filters .product-filter-toggle.active',
				'border_radius'      => '{{WRAPPER}} .rtsb-default-archive-filters  .product-filter-toggle',
				'padding'            => '{{WRAPPER}} .rtsb-default-archive-filters  .product-filter-toggle',
				'margin'             => '{{WRAPPER}} .rtsb-default-archive-filters  .product-filter-toggle',
			],
			'filter_items'      => [
				'typography'                => '{{WRAPPER}} .rtsb-product-default-filters input.rtsb-checkbox-filter + label, {{WRAPPER}} .rtsb-product-default-filters input.rtsb-radio-filter + label, {{WRAPPER}} .rtsb-product-default-filters .input-type-checkbox .rtsb-default-filter-group .rtsb-count, {{WRAPPER}} .rtsb-product-default-filters .input-type-radio .rtsb-default-filter-group .rtsb-count, {{WRAPPER}} .rtsb-product-default-filters.rtsb-color .rtsb-color-filter .default-filter-attr-name, {{WRAPPER}} .rtsb-product-default-filters.rtsb-color .rtsb-default-filter-group .rtsb-count, {{WRAPPER}} .rtsb-product-default-filters.rtsb-button .rtsb-button-filter .default-filter-attr-name, {{WRAPPER}} .rtsb-product-default-filters.rtsb-button .rtsb-default-filter-group .rtsb-count, {{WRAPPER}} .rtsb-product-default-filters.rtsb-ratings .rtsb-default-rating-star-wrapper + .rtsb-count,{{WRAPPER}} .rtsb-product-default-filters  .rtsb-product-count,{{WRAPPER}} .rtsb-archive-default-filters-wrapper .price-inputs label',
				'count_display'             => '{{WRAPPER}} .rtsb-product-default-filters .input-type-checkbox .rtsb-default-filter-group .rtsb-count, {{WRAPPER}} .rtsb-product-default-filters .input-type-radio .rtsb-default-filter-group .rtsb-count, {{WRAPPER}} .rtsb-product-default-filters.rtsb-color .rtsb-default-filter-group .rtsb-count,{{WRAPPER}} .rtsb-product-default-filters.rtsb-button .rtsb-default-filter-group .rtsb-count,{{WRAPPER}} .rtsb-product-default-filters .rtsb-default-filter-group .rtsb-product-count',
				'color'                     => '{{WRAPPER}} .rtsb-product-default-filters .input-type-checkbox .rtsb-default-filter-group label, {{WRAPPER}} .rtsb-product-default-filters .input-type-radio .rtsb-default-filter-group label, {{WRAPPER}} .rtsb-product-default-filters.rtsb-color .rtsb-color-filter label, {{WRAPPER}} .rtsb-product-default-filters.rtsb-button .rtsb-button-filter label, {{WRAPPER}} .rtsb-product-default-filters.rtsb-price-filter .price-inputs label',
				'bg_color'                  => '{{WRAPPER}} .rtsb-product-default-filters .input-type-checkbox .rtsb-default-filter-group, {{WRAPPER}} .rtsb-product-default-filters .input-type-radio .rtsb-default-filter-group, {{WRAPPER}} .rtsb-product-default-filters.rtsb-color .rtsb-default-filter-group, {{WRAPPER}} .rtsb-product-default-filters.rtsb-button .rtsb-default-filter-group .rtsb-button-filter, {{WRAPPER}} .rtsb-product-default-filters.rtsb-price-filter .price-inputs label',
				'count_color'               => '{{WRAPPER}} .rtsb-product-default-filters .input-type-checkbox .rtsb-default-filter-group .rtsb-count, {{WRAPPER}} .rtsb-product-default-filters .input-type-radio .rtsb-default-filter-group .rtsb-count, {{WRAPPER}} .rtsb-product-default-filters.rtsb-color .rtsb-default-filter-group .rtsb-count, {{WRAPPER}} .rtsb-product-default-filters.rtsb-button .rtsb-default-filter-group .rtsb-count, {{WRAPPER}} .rtsb-product-default-filters.rtsb-ratings .rtsb-default-rating-star-wrapper + .rtsb-count,{{WRAPPER}} .rtsb-product-default-filters  .rtsb-product-count',
				'border'                    => '{{WRAPPER}} .rtsb-product-default-filters .input-type-checkbox .rtsb-default-filter-group, {{WRAPPER}} .rtsb-product-default-filters .input-type-radio .rtsb-default-filter-group, {{WRAPPER}} .rtsb-product-default-filters.rtsb-color .rtsb-default-filter-group, {{WRAPPER}} .rtsb-product-default-filters.rtsb-button .rtsb-default-filter-group .rtsb-button-filter',
				'padding'                   => '{{WRAPPER}} .rtsb-product-default-filters .input-type-checkbox .rtsb-default-filter-group, {{WRAPPER}} .rtsb-product-default-filters .input-type-radio .rtsb-default-filter-group, {{WRAPPER}} .rtsb-product-default-filters.rtsb-color .rtsb-default-filter-group, {{WRAPPER}} .rtsb-product-default-filters.rtsb-button .rtsb-default-filter-group .rtsb-button-filter, {{WRAPPER}} .rtsb-product-default-filters.rtsb-price-filter .price-inputs label',
				'margin'                    => '{{WRAPPER}} .rtsb-product-default-filters .input-type-checkbox .rtsb-default-filter-group, {{WRAPPER}} .rtsb-product-default-filters .input-type-radio .rtsb-default-filter-group, {{WRAPPER}} .rtsb-product-default-filters.rtsb-color .rtsb-default-filter-group, {{WRAPPER}} .rtsb-product-default-filters.rtsb-button .rtsb-default-filter-group .rtsb-button-filter',
				'wrapper_padding'           => '{{WRAPPER}} .rtsb-product-default-filters .default-filter-content > ul',
				'wrapper_margin'            => '{{WRAPPER}}  .rtsb-product-default-filters > .default-filter-content > ul',
				'subcat_margin'             => '{{WRAPPER}} .rtsb-categories .default-filter-content ul ul',
				'input_fields_color'        => '{{WRAPPER}} .rtsb-archive-default-filters-wrapper input.rtsb-radio-filter + label:before,{{WRAPPER}} .rtsb-archive-default-filters-wrapper .input-type-checkbox input.rtsb-checkbox-filter + label:before,{{WRAPPER}} .rtsb-archive-default-filters-wrapper .price-inputs .filter-price-field',
				'active_input_fields_color' => '{{WRAPPER}} .rtsb-archive-default-filters-wrapper .input-type-radio input.rtsb-checkbox-filter:checked + label:after,{{WRAPPER}} .rtsb-archive-default-filters-wrapper .input-type-checkbox input.rtsb-checkbox-filter:checked + label:after',
			],
		];
	}
}
