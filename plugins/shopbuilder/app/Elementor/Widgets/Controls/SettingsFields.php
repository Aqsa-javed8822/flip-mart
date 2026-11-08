<?php
/**
 * Elementor Settings Fields Class.
 *
 * This class contains all the common fields for Settings tab.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\Controls;

use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Elementor\Helper\ControlHelper;
use RadiusTheme\SB\Modules\VariationSwatches\SwatchesFns;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Elementor Settings Fields Class.
 */
class SettingsFields {
	/**
	 * Tab name.
	 *
	 * @access private
	 * @static
	 *
	 * @var array
	 */
	private static $tab = 'settings';

	/**
	 * Visibility section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function content_visibility( $obj ) {
		$fields['visibility_section'] = $obj->start_section(
			esc_html__( 'Content Visibility', 'shopbuilder' ),
			self::$tab
		);

		$fields['general_visibility'] = $obj->el_heading( esc_html__( 'General Visibility', 'shopbuilder' ) );

		$fields['show_title'] = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Show Product Title?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to show product title.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'default'     => 'yes',
		];

		$fields['show_short_desc'] = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Show Short Description?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to show product short description.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'condition'   => [ 'layout!' => [ 'grid-layout2', 'slider-layout2' ] ],
		];

		$fields['show_price'] = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Show Product Price?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to show product price.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'default'     => 'yes',
		];

		$fields['show_rating'] = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Show Product Rating?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to show product rating.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'default'     => 'yes',
		];

		$fields['show_badges'] = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Show Product Badges?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to show product badges.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'default'     => 'yes',
		];

		$fields['show_categories'] = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Show Product Categories?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to show product categories.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'condition'   => [ 'layout!' => [ 'grid-layout2', 'slider-layout2' ] ],
		];
		$fields['show_brands']     = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Show Product Brand?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to show product brand.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'condition'   => [ 'layout!' => [ 'grid-layout2', 'slider-layout2' ] ],
		];
		$fields['single_brand']    = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Show Only First Brand?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to show only the first brand.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'default'     => 'yes',
			'condition'   => [
				'show_brands' => [ 'yes' ],
				'layout!'     => [ 'grid-layout2', 'slider-layout2' ],
			],
		];
		$fields['single_category'] = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Show Only First Category?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to show only the first category.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'default'     => 'yes',
			'condition'   => [
				'show_categories' => [ 'yes' ],
				'layout!'         => [ 'grid-layout2', 'slider-layout2' ],
			],
		];
		if ( function_exists( 'rtwpvsp' ) || ( Fns::is_module_active( 'variation_swatches' ) && 'on' === SwatchesFns::get_options( 'enable_showcase_swatches' ) ) ) {
			$fields['show_swatches'] = [
				'type'        => 'switch',
				'label'       => esc_html__( 'Show Variation Swatches?', 'shopbuilder' ),
				'description' => esc_html__( 'Switch on to show variation swatches.', 'shopbuilder' ),
				'label_on'    => esc_html__( 'On', 'shopbuilder' ),
				'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
				'default'     => 'yes',
				'condition'   => [ 'layout!' => [ 'grid-layout2', 'slider-layout2' ] ],
			];

			$fields['show_vs_clear_btn'] = [
				'type'        => 'switch',
				'label'       => esc_html__( 'Show Swatches Reset?', 'shopbuilder' ),
				'description' => esc_html__( 'Switch on to show swatches clear button.', 'shopbuilder' ),
				'label_on'    => esc_html__( 'On', 'shopbuilder' ),
				'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
				'separator'   => 'default',
				'condition'   => [
					'show_swatches' => [ 'yes' ],
					'layout!'       => [ 'grid-layout2', 'slider-layout2' ],
				],
			];
		}

		$fields['action_btn_visibility'] = $obj->el_heading( esc_html__( 'Action Buttons Visibility', 'shopbuilder' ), 'before' );

		$fields['show_add_to_cart'] = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Show Add to Cart Button?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to show product add to cart.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'default'     => 'yes',
		];

		$wishlist   = Fns::get_options( 'modules', 'wishlist' );
		$compare    = Fns::get_options( 'modules', 'compare' );
		$quick_view = Fns::get_options( 'modules', 'quick_view' );

		if ( 'on' === ( $wishlist['active'] ?? '' ) ) {
			$fields['show_wishlist'] = [
				'type'        => 'switch',
				'label'       => esc_html__( 'Show Wishlist Button?', 'shopbuilder' ),
				'description' => esc_html__( 'Switch on to show product wishlist.', 'shopbuilder' ),
				'label_on'    => esc_html__( 'On', 'shopbuilder' ),
				'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
				'default'     => 'yes',
			];
		}

		if ( 'on' === ( $quick_view['active'] ?? '' ) ) {
			$fields['show_quick_view'] = [
				'type'        => 'switch',
				'label'       => esc_html__( 'Show Quick View Button?', 'shopbuilder' ),
				'description' => esc_html__( 'Switch on to show product quick view.', 'shopbuilder' ),
				'label_on'    => esc_html__( 'On', 'shopbuilder' ),
				'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
				'default'     => 'yes',
			];
		}

		if ( 'on' === ( $compare['active'] ?? '' ) ) {
			$fields['show_compare'] = [
				'type'        => 'switch',
				'label'       => esc_html__( 'Show Compare Button?', 'shopbuilder' ),
				'description' => esc_html__( 'Switch on to show product compare.', 'shopbuilder' ),
				'label_on'    => esc_html__( 'On', 'shopbuilder' ),
				'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			];
		}

		$fields['visibility_section_end'] = $obj->end_section();
		return apply_filters( 'rtsb/elements/elementor/visibility_control', $fields, $obj );
	}

	/**
	 * Content ordering section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function content_ordering( $obj ) {
		$fields['ordering_section'] = $obj->start_section(
			esc_html__( 'Content Ordering', 'shopbuilder' ),
			self::$tab
		);

		$fields['ordering_note'] = [
			'type'            => 'html',
			'raw'             => '',
			'content_classes' => 'elementor-panel-heading-title',
		];

		$fields['custom_ordering'] = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Enable Custom Ordering?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to enable elements custom ordering.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'separator'   => $obj->pro_class(),
			'classes'     => $obj->pro_class(),
			'condition'   => [
				'layout!' => [ 'grid-layout4', 'list-layout5', 'list-layout6', 'slider-layout4', 'slider-layout8' ],
			],
		];

		$fields['ordering_section_end'] = $obj->end_section();

		return apply_filters( 'rtsb/elementor/ordering_control', $fields, $obj );
	}

	/**
	 * Action buttons section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function action_buttons( $obj ) {
		$fields['action_visibility_section'] = $obj->start_section(
			esc_html__( 'Action Buttons', 'shopbuilder' ),
			self::$tab
		);

		$fields['add_to_cart_note'] = $obj->el_heading(
			esc_html__( 'Add to Cart Button', 'shopbuilder' ),
			'default',
			[],
			[ 'show_add_to_cart' => [ 'yes' ] ]
		);

		$fields['show_cart_text'] = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Show Add to Cart Text?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to show add to cart text.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'default'     => 'yes',
			'condition'   => [ 'show_add_to_cart' => [ 'yes' ] ],
		];

		$fields['add_to_cart_text'] = [
			'type'        => 'text',
			'label'       => esc_html__( 'Add to Cart Text', 'shopbuilder' ),
			'default'     => esc_html__( 'Add to Cart', 'shopbuilder' ),
			'description' => esc_html__( 'Please enter the add to cart text.', 'shopbuilder' ),
			'condition'   => [
				'show_cart_text'   => [ 'yes' ],
				'show_add_to_cart' => [ 'yes' ],
			],
			'label_block' => true,
		];

		$fields['add_to_cart_success_text'] = [
			'type'        => 'text',
			'label'       => esc_html__( 'Add to Cart Success Text', 'shopbuilder' ),
			'default'     => esc_html__( 'Added!', 'shopbuilder' ),
			'description' => esc_html__( 'Please enter the add to cart success text.', 'shopbuilder' ),
			'condition'   => [
				'show_cart_text'   => [ 'yes' ],
				'show_add_to_cart' => [ 'yes' ],
			],
			'label_block' => true,
		];

		$fields['show_cart_icon'] = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Show Add to Cart Icon?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to show add to cart icon.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'default'     => 'yes',
			'condition'   => [ 'show_add_to_cart' => [ 'yes' ] ],
		];

		$fields['add_to_cart_icon'] = [
			'type'      => 'icons',
			'label'     => esc_html__( 'Choose Add to Cart Icon', 'shopbuilder' ),
			'default'   => [
				'value'   => 'rtsb-icon rtsb-icon-cart',
				'library' => 'rtsb-fonts',
			],
			'condition' => [
				'show_cart_icon'   => [ 'yes' ],
				'show_add_to_cart' => [ 'yes' ],
			],
		];

		$fields['add_to_cart_success_icon'] = [
			'type'      => 'icons',
			'label'     => esc_html__( 'Choose Add to Cart Success Icon', 'shopbuilder' ),
			'default'   => [
				'value'   => 'rtsb-icon rtsb-icon-check',
				'library' => 'rtsb-fonts',
			],
			'condition' => [
				'show_cart_icon'   => [ 'yes' ],
				'show_add_to_cart' => [ 'yes' ],
			],
		];

		$fields['wishlist_note'] = $obj->el_heading(
			esc_html__( 'Wishlist Button', 'shopbuilder' ),
			'before',
			[],
			[ 'show_wishlist' => [ 'yes' ] ]
		);

		$fields['show_wishlist_text'] = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Show Wishlist Text?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to show wishlist text.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'default'     => false,
			'condition'   => [ 'show_wishlist' => [ 'yes' ] ],
		];
		$fields['show_wishlist_icon'] = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Show Wishlist Icon?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to show wishlist icon.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'default'     => 'yes',
			'condition'   => [ 'show_wishlist' => [ 'yes' ] ],
		];
		$fields['wishlist_icon']      = [
			'type'      => 'icons',
			'label'     => esc_html__( 'Choose Wishlist Icon', 'shopbuilder' ),
			'default'   => [
				'value'   => 'rtsb-icon rtsb-icon-heart-empty',
				'library' => 'rtsb-fonts',
			],
			'condition' => [
				'show_wishlist'      => [ 'yes' ],
				'show_wishlist_icon' => [ 'yes' ],
			],
		];

		$fields['wishlist_icon_added'] = [
			'type'      => 'icons',
			'label'     => esc_html__( 'Choose Wishlist Success Icon', 'shopbuilder' ),
			'default'   => [
				'value'   => 'rtsb-icon rtsb-icon-heart',
				'library' => 'rtsb-fonts',
			],
			'condition' => [ 'show_wishlist' => [ 'yes' ] ],
		];

		$fields['quick_view_note']      = $obj->el_heading(
			esc_html__( 'Quick View Button', 'shopbuilder' ),
			'before',
			[],
			[ 'show_quick_view' => [ 'yes' ] ]
		);
		$fields['show_quick_view_text'] = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Show Quick View Text?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to show quick view text.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'default'     => false,
			'condition'   => [ 'show_quick_view' => [ 'yes' ] ],
		];
		$fields['show_quick_view_icon'] = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Show Quick View Icon?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to show quick view icon.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'default'     => 'yes',
			'condition'   => [ 'show_quick_view' => [ 'yes' ] ],
		];
		$fields['quick_view_icon']      = [
			'type'      => 'icons',
			'label'     => esc_html__( 'Choose Quick View Icon', 'shopbuilder' ),
			'default'   => [
				'value'   => 'rtsb-icon rtsb-icon-eye',
				'library' => 'rtsb-fonts',
			],
			'condition' => [
				'show_quick_view'      => [ 'yes' ],
				'show_quick_view_icon' => [ 'yes' ],
			],
		];

		$fields['compare_note']      = $obj->el_heading(
			esc_html__( 'Compare Button', 'shopbuilder' ),
			'before',
			[],
			[ 'show_compare' => [ 'yes' ] ]
		);
		$fields['show_compare_text'] = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Show Compare Text?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to show compare text.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'default'     => false,
			'condition'   => [ 'show_compare' => [ 'yes' ] ],
		];
		$fields['show_compare_icon'] = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Show Compare Icon?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to show compare icon.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'default'     => 'yes',
			'condition'   => [ 'show_compare' => [ 'yes' ] ],
		];
		$fields['comparison_icon']   = [
			'type'      => 'icons',
			'label'     => esc_html__( 'Choose Compare Icon', 'shopbuilder' ),
			'default'   => [
				'value'   => 'rtsb-icon rtsb-icon-exchange',
				'library' => 'rtsb-fonts',
			],
			'condition' => [
				'show_compare'      => [ 'yes' ],
				'show_compare_icon' => [ 'yes' ],
			],
		];

		$fields['comparison_icon_added'] = [
			'type'      => 'icons',
			'label'     => esc_html__( 'Choose Compare Success Icon', 'shopbuilder' ),
			'default'   => [
				'value'   => 'rtsb-icon rtsb-icon-check',
				'library' => 'rtsb-fonts',
			],
			'condition' => [ 'show_compare' => [ 'yes' ] ],
		];

		$fields['action_visibility_section_end'] = $obj->end_section();

		return apply_filters( 'rtsb/elements/elementor/action_buttons', $fields, $obj );
	}

	/**
	 * Category Visibility section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function cat_content_visibility( $obj ) {
		$status                           = ! rtsb()->has_pro();
		$fields['cat_visibility_section'] = $obj->start_section(
			esc_html__( 'Content Visibility', 'shopbuilder' ),
			self::$tab
		);

		$fields['show_title'] = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Show Category Title?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to show category title.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'default'     => 'yes',
		];

		$fields['show_short_desc'] = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Show Category Description?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to show category description.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
		];

		$fields['show_count'] = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Show Product Count?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to show product count.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'default'     => 'yes',
		];

		$fields['show_badges'] = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Show Custom Badge?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to show custom category badge.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'default'     => 'yes',
			'condition'   => [ 'layout!' => [ 'category-layout1', 'category-layout2' ] ],
		];

		if ( 'rtsb-product-categories-general' === $obj->rtsb_base ) {
			$fields['show_count']['separator'] = rtsb()->has_pro() ? 'default' : 'after';

			$fields['active_cat_slider'] = [
				'type'        => 'switch',
				'label'       => esc_html__( 'Active Category Slider?', 'shopbuilder' ),
				'description' => esc_html__( 'Switch on to active category slider.', 'shopbuilder' ),
				'label_on'    => esc_html__( 'On', 'shopbuilder' ),
				'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
				'classes'     => $obj->pro_class(),
				'is_pro'      => $status,
			];
		}

		$fields['cat_visibility_section_end'] = $obj->end_section();

		return $fields;
	}

	/**
	 * Image section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function image( $obj ) {
		$fields['image_section'] = $obj->start_section(
			esc_html__( 'Product Images', 'shopbuilder' ),
			'settings'
		);

		$fields['show_featured_image'] = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Display Featured Image?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to display featured image.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'default'     => 'yes',
			'separator'   => rtsb()->has_pro() ? 'default' : 'after',
		];

		$fields['show_product_gallery'] = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Activate Product Image Gallery Slider', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to display product image gallery slider.', 'shopbuilder' ),
			'classes'     => $obj->pro_class(),
			'condition'   => [
				'show_featured_image' => [ 'yes' ],
				'grid_style!'         => [ 'masonry' ],
			],
		];

		$fields['show_product_gallery_thumb'] = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Activate Image Gallery Thumbnail', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to display product image gallery thumbnail slider.', 'shopbuilder' ),
			'classes'     => $obj->pro_class(),
			'condition'   => [
				'show_featured_image'  => [ 'yes' ],
				'show_product_gallery' => [ 'yes' ],
				'grid_style!'          => [ 'masonry' ],
			],
		];

		$fields['show_hover_image'] = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Display Gallery Image on Hover?', 'shopbuilder' ),
			'description' => __( 'Switch on to display gallery image on hover. <br /><b>Note: </b>It will display the first gallery image on hover.', 'shopbuilder' ) . ( ( function_exists( 'rtwpvsp' ) || rtsb()->has_pro() ) ? esc_html__( ' It will not work if variation swatches are enabled.', 'shopbuilder' ) : '' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'separator'   => rtsb()->has_pro() ? 'default' : 'before-short',
			'default'     => 'yes',
			'condition'   => rtsb()->has_pro() ? [
				'show_featured_image'   => [ 'yes' ],
				'show_product_gallery!' => [ 'yes' ],
			] : [ 'show_featured_image' => [ 'yes' ] ],
		];

		$fields['image_hover_animation'] = [
			'type'        => 'select2',
			'label'       => esc_html__( 'Image Hover Animation', 'shopbuilder' ),
			'description' => esc_html__( 'Please choose image hover animation.', 'shopbuilder' ),
			'options'     => [
				'zoom_in'  => esc_html__( 'Zoom In', 'shopbuilder' ),
				'zoom_out' => esc_html__( 'Zoom Out', 'shopbuilder' ),
				'none'     => esc_html__( 'None', 'shopbuilder' ),
			],
			'label_block' => true,
			'condition'   => [ 'show_featured_image' => [ 'yes' ] ],
			'default'     => 'zoom_in',
			'separator'   => rtsb()->has_pro() ? 'default' : 'after',
		];

		$fields['gallery_hover_animation'] = [
			'type'        => 'select2',
			'label'       => esc_html__( 'Gallery Image Hover Animation', 'shopbuilder' ),
			'description' => esc_html__( 'Please choose gallery image hover animation.', 'shopbuilder' ),
			'options'     => [
				'fade'        => esc_html__( 'Default (Fade)', 'shopbuilder' ),
				'slide'       => esc_html__( 'Slide Up', 'shopbuilder' ),
				'slide-down'  => esc_html__( 'Slide Down', 'shopbuilder' ),
				'slide-left'  => esc_html__( 'Slide Left', 'shopbuilder' ),
				'slide-right' => esc_html__( 'Slide Right', 'shopbuilder' ),
			],
			'label_block' => true,
			'condition'   => rtsb()->has_pro() ? [
				'show_featured_image'   => [ 'yes' ],
				'show_hover_image'      => [ 'yes' ],
				'show_product_gallery!' => [ 'yes' ],
			] : [
				'show_featured_image' => [ 'yes' ],
				'show_hover_image'    => [ 'yes' ],
			],
			'default'     => 'fade',
			'classes'     => $obj->pro_class(),
		];

		$fields['image_size_note'] = $obj->el_heading(
			esc_html__( 'Image Size', 'shopbuilder' ),
			'before',
			[],
			[ 'show_featured_image' => [ 'yes' ] ]
		);

		$fields = array_merge( $fields, self::image_size_controls( 'grid' ) );

		$fields['thumb_image_note']            = $obj->el_heading(
			esc_html__( 'Thumbnail Slider', 'shopbuilder' ),
			'before',
			[],
			[
				'show_featured_image'        => [ 'yes' ],
				'show_product_gallery'       => [ 'yes' ],
				'show_product_gallery_thumb' => [ 'yes' ],
				'grid_style!'                => [ 'masonry' ],
			],
		);
		$fields['gallery_thumb_column_number'] = [
			'label'      => esc_html__( 'Gallery Thumb Image Column', 'shopbuilder' ),
			'classes'    => $obj->pro_class(),
			'type'       => 'slider',
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min'  => 2,
					'max'  => 10,
					'step' => 1,
				],
			],
			'default'    => [
				'size' => 3,
			],
			'condition'  => [
				'show_featured_image'        => [ 'yes' ],
				'show_product_gallery'       => [ 'yes' ],
				'show_product_gallery_thumb' => [ 'yes' ],
				'grid_style!'                => [ 'masonry' ],
			],
		];
		$fields['gallery_thumb_column_gap']    = [
			'label'      => esc_html__( 'Gallery Thumb Column Gap', 'shopbuilder' ),
			'classes'    => $obj->pro_class(),
			'type'       => 'slider',
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min'  => 2,
					'max'  => 30,
					'step' => 1,
				],
			],
			'default'    => [
				'size' => 10,
			],
			'condition'  => [
				'show_featured_image'        => [ 'yes' ],
				'show_product_gallery'       => [ 'yes' ],
				'show_product_gallery_thumb' => [ 'yes' ],
				'grid_style!'                => [ 'masonry' ],
			],
		];

		$fields['thumb_image_section_end'] = $obj->end_section();
		return $fields;
	}

	/**
	 * Category Image section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function cat_image( $obj ) {
		$fields['cat_image_section'] = $obj->start_section(
			esc_html__( 'Image', 'shopbuilder' ),
			self::$tab
		);

		$fields['show_cat_image'] = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Display Category Image?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to display category image.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'default'     => 'yes',
		];

		$fields['show_custom_image'] = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Add Custom Image?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to select a custom category image. Setting a custom image will override the category image.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'classes'     => $obj->pro_class(),
		];

		$fields['custom_image'] = [
			'type'        => 'media',
			'label'       => esc_html__( 'Upload Custom Image', 'shopbuilder' ),
			'description' => esc_html__( 'Please choose the custom category image. It will override the default image.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'condition'   => [
				'show_custom_image' => [ 'yes' ],
				'layout'            => [ 'category-single-layout1', 'category-single-layout2' ],
			],
			'default'     => [
				'url' => \Elementor\Utils::get_placeholder_image_src(),
			],
		];

		$fields['show_overlay'] = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Enable Image Overlay?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to enable image overlay.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'separator'   => 'before',
			'condition'   => [
				'layout' => [ 'category-single-layout1' ],
			],
		];

		$fields['image_hover_animation'] = [
			'type'        => 'select2',
			'label'       => esc_html__( 'Image Hover Animation', 'shopbuilder' ),
			'description' => esc_html__( 'Please choose image hover animation.', 'shopbuilder' ),
			'options'     => [
				'zoom_in'  => esc_html__( 'Zoom In', 'shopbuilder' ),
				'zoom_out' => esc_html__( 'Zoom Out', 'shopbuilder' ),
				'none'     => esc_html__( 'None', 'shopbuilder' ),
			],
			'label_block' => true,
			'condition'   => [
				'show_cat_image' => [ 'yes' ],
				'layout!'        => [ 'category-layout4' ],
			],
			'default'     => 'zoom_in',
			'separator'   => rtsb()->has_pro() ? 'default' : 'before-short',
		];

		$fields['cat_image_size_note'] = $obj->el_heading(
			esc_html__( 'Image Size', 'shopbuilder' ),
			'before',
			[],
			[ 'show_cat_image' => [ 'yes' ] ]
		);

		$fields = array_merge( $fields, self::image_size_controls( 'catgory' ) );

		$fields['cat_image_section_end'] = $obj->end_section();

		return apply_filters( 'rtsb/elements/elementor/cat_image_control', $fields, $obj );
	}

	/**
	 * Image Size Controls.
	 *
	 * @param string $type Type.
	 *
	 * @return array
	 */
	public static function image_size_controls( $type ) {
		$conditions = [
			'relation' => 'and',
			'terms'    => [],
		];

		if ( 'grid' === $type ) {
			$conditions['terms'][] = [
				'relation' => 'or',
				'terms'    => [
					[
						'name'     => 'show_featured_image',
						'operator' => '==',
						'value'    => 'yes',
					],
				],
			];
		} else {
			$conditions['terms'][] = [
				'relation' => 'or',
				'terms'    => [
					[
						'name'     => 'show_cat_image',
						'operator' => '==',
						'value'    => 'yes',
					],
					[
						'name'     => 'show_custom_image',
						'operator' => '==',
						'value'    => 'yes',
					],
				],
			];
		}

		$fields['image'] = [
			'type'            => 'select2',
			'label'           => esc_html__( 'Select Image Size', 'shopbuilder' ),
			'description'     => esc_html__( 'Please select the image size.', 'shopbuilder' ),
			'options'         => Fns::get_image_sizes(),
			'default'         => 'woocommerce_thumbnail',
			'label_block'     => true,
			'content_classes' => 'elementor-descriptor',
			'conditions'      => $conditions,
		];

		$conditions['terms'][] = [
			'relation' => 'or',
			'terms'    => [
				[
					'name'     => 'image',
					'operator' => '==',
					'value'    => 'rtsb_custom',
				],
			],
		];

		$fields['image_custom_dimension'] = [
			'type'        => 'image-dimensions',
			'label'       => esc_html__( 'Enter Custom Image Size', 'shopbuilder' ),
			'label_block' => true,
			'show_label'  => true,
			'default'     => [
				'width'  => 400,
				'height' => 400,
			],
			'conditions'  => $conditions,
		];

		$fields['image_crop'] = [
			'type'        => 'select2',
			'label'       => esc_html__( 'Image Crop', 'shopbuilder' ),
			'description' => esc_html__( 'Please click on "Apply" to update the image.', 'shopbuilder' ),
			'options'     => [
				'soft' => esc_html__( 'Soft Crop', 'shopbuilder' ),
				'hard' => esc_html__( 'Hard Crop', 'shopbuilder' ),
			],
			'default'     => 'hard',
			'conditions'  => $conditions,
		];

		$fields['image_custom_dimension_note'] = [
			'type'       => 'html',
			'raw'        => sprintf(
				'<span style="display: block; background: #fffbf1; padding: 10px; font-weight: 500; line-height: 1.4; color: #bd3a3a;border: 1px solid #bd3a3a;">%s</span>',
				esc_html__( 'Please note that, if you enter image size larger than the actual image itself, the image sizes will fallback to the full image dimension.', 'shopbuilder' )
			),
			'conditions' => $conditions,
		];

		// Variation swatch (showcase) images render at their own size, so the custom
		// image size above does not apply to them. Self-gate on the `show_swatches`
		// control rather than a hardcoded widget/type allow-list: that control is the
		// codebase's universal marker for swatch-capable widgets — defined in the
		// product-loop visibility section (SettingsFields::content_visibility) and also
		// injected by RtwpvsSupport when the RTWPVS plugin is active. An Elementor
		// condition that references an absent control stays unmet, so the note never
		// surfaces on the category image size or any non-swatch widget.
		$swatch_note_conditions = $conditions;

		$swatch_note_conditions['terms'][] = [
			'relation' => 'or',
			'terms'    => [
				[
					'name'     => 'show_swatches',
					'operator' => '==',
					'value'    => 'yes',
				],
			],
		];

		$fields['image_custom_dimension_swatch_note'] = [
			'type'       => 'html',
			'raw'        => sprintf(
				'<span style="display: block; background: #fffbf1; padding: 10px; font-weight: 500; line-height: 1.4; color: #bd3a3a;border: 1px solid #bd3a3a;">%s</span>',
				esc_html__( 'Note: this does not apply to variation swatch images.', 'shopbuilder' )
			),
			'conditions' => $swatch_note_conditions,
		];

		return $fields;
	}

	/**
	 * Slider section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function slider_settings( $obj ) {
		$status = ! rtsb()->has_pro();

		$fields['slider_control_section'] = $obj->start_section(
			esc_html__( 'Slider Settings', 'shopbuilder' ),
			self::$tab
		);

		$fields['slider_control_note'] = $obj->el_heading(
			esc_html__( 'Controls', 'shopbuilder' ),
			'default'
		);

		$fields['slider_nav'] = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Navigation Arrows', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to enable slider navigation arrows.', 'shopbuilder' ),
			'default'     => 'yes',
		];

		$fields['slider_nav_position'] = [
			'type'        => 'select2',
			'label'       => esc_html__( 'Navigation Arrows Position', 'shopbuilder' ),
			'options'     => [
				'top'      => esc_html__( 'Top', 'shopbuilder' ),
				'standard' => esc_html__( 'Middle', 'shopbuilder' ),
				'bottom'   => esc_html__( 'Bottom', 'shopbuilder' ),
			],
			'description' => esc_html__( 'Please select the slider arrows position.', 'shopbuilder' ),
			'default'     => 'standard',
			'label_block' => true,
			'separator'   => rtsb()->has_pro() ? 'default' : 'after',
			'condition'   => [ 'slider_nav' => [ 'yes' ] ],
		];

		$fields['always_show_nav'] = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Always Show Arrows', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to enable always showing navigation arrows.', 'shopbuilder' ),
			'classes'     => $obj->pro_class(),
			'is_pro'      => $status,
			'condition'   => [ 'slider_nav' => [ 'yes' ] ],
		];

		if ( rtsb()->has_pro() ) {
			$fields['always_show_nav']['condition']['slider_nav_position'] = 'standard';
		}

		$fields['slider_left_arrow_icon'] = [
			'label'       => esc_html__( 'Left Arrow Icon', 'shopbuilder' ),
			'description' => esc_html__( 'Please choose the slider left arrow icon.', 'shopbuilder' ),
			'type'        => 'icons',
			'default'     => [
				'value'   => 'fas fa-chevron-left',
				'library' => 'fa-solid',
			],
			'separator'   => rtsb()->has_pro() ? 'default' : 'before-short',
			'condition'   => [ 'slider_nav' => 'yes' ],
		];

		$fields['slider_right_arrow_icon'] = [
			'label'       => esc_html__( 'Right Arrow Icon', 'shopbuilder' ),
			'description' => esc_html__( 'Please choose the slider right arrow icon.', 'shopbuilder' ),
			'type'        => 'icons',
			'default'     => [
				'value'   => 'fas fa-chevron-right',
				'library' => 'fa-solid',
			],
			'condition'   => [ 'slider_nav' => 'yes' ],
		];

		$fields['slider_pagi']         = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Dot Pagination', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to enable slider dot pagination.', 'shopbuilder' ),
			'default'     => 'yes',
		];
		$fields['slider_dynamic_pagi'] = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Dynamic Pagination', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'description' => esc_html__( 'It will keep only few bullets visible at the same time.', 'shopbuilder' ),
			'condition'   => [ 'slider_pagi' => 'yes' ],
		];
		$fields['slider_loop']         = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Infinite Loop', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to enable slider infinite loop.', 'shopbuilder' ),
			'condition'   => [ 'tax_filter!' => [ 'yes' ] ],
		];

		$fields['slider_auto_height'] = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Auto Height', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to enable slider dynamic height.', 'shopbuilder' ),
		];

		$fields['slider_lazy_load'] = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Image Lazy Load', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to enable slider image lazy load.', 'shopbuilder' ),
			'condition'   => [ 'tax_filter!' => [ 'yes' ] ],
		];

		$fields['slide_speed'] = [
			'type'        => 'number',
			'label'       => esc_html__( 'Slide Speed (in ms)', 'shopbuilder' ),
			'description' => esc_html__( 'Please enter the duration of transition between slides (in ms).', 'shopbuilder' ),
			'default'     => 2000,
			'separator'   => $status ? 'default elementor-control-separator-after' : 'default',
		];

		$fields['slider_slide_animation'] = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Enable Slide Effect', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to enable zoom-in effect when sliding.', 'shopbuilder' ),
			'default'     => '',
			'classes'     => $obj->pro_class(),
			'is_pro'      => $status,
		];

		$fields['slider_autoplay_note'] = $obj->el_heading(
			esc_html__( 'Autoplay', 'shopbuilder' ),
			'before'
		);

		$fields['slide_autoplay'] = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Enable Autoplay?', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to enable slider autoplay.', 'shopbuilder' ),
			'default'     => 'yes',
		];

		$fields['pause_hover'] = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Pause on Mouse Hover?', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to enable slider autoplay pause on mouse hover.', 'shopbuilder' ),
			'default'     => 'yes',
			'condition'   => [ 'slide_autoplay' => 'yes' ],
		];

		$fields['autoplay_timeout'] = [
			'type'        => 'number',
			'label'       => esc_html__( 'Autoplay Delay (in ms)', 'shopbuilder' ),
			'default'     => 5000,
			'description' => esc_html__( 'Please select autoplay interval delay (in ms).', 'shopbuilder' ),
			'condition'   => [ 'slide_autoplay' => 'yes' ],
		];

		$fields['slider_control_section_end'] = $obj->end_section();

		return $fields;
	}

	/**
	 * Title section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function product_title( $obj ) {
		$condition = [
			'show_title' => [ 'yes' ],
		];

		$fields['product_title_section'] = $obj->start_section(
			esc_html__( 'Product Title', 'shopbuilder' ),
			self::$tab,
			[],
			$condition
		);

		$fields['title_tag']   = [
			'type'        => 'select2',
			'label'       => esc_html__( 'Product Title Tag', 'shopbuilder' ),
			'options'     => ControlHelper::heading_tags(),
			'label_block' => true,
			'default'     => 'h3',
			'description' => esc_html__( 'Please select the product title tag.', 'shopbuilder' ),
		];
		$fields['title_limit'] = [
			'type'        => 'select2',
			'label'       => esc_html__( 'Product Title Limit', 'shopbuilder' ),
			'options'     => [
				'default' => esc_html__( 'Default', 'shopbuilder' ),
				'1-line'  => esc_html__( 'Show in 1 line', 'shopbuilder' ),
				'2-lines' => esc_html__( 'Show in 2 lines', 'shopbuilder' ),
				'3-lines' => esc_html__( 'Show in 3 lines', 'shopbuilder' ),
				'custom'  => esc_html__( 'Custom', 'shopbuilder' ),
			],
			'label_block' => true,
			'default'     => 'default',
			'description' => esc_html__( 'Please select the product title limit.', 'shopbuilder' ),
		];

		$fields['title_limit_custom'] = [
			'type'        => 'number',
			'label'       => esc_html__( 'Custom Character Limit', 'shopbuilder' ),
			'default'     => 200,
			'description' => esc_html__( 'Please enter the product title character limit.', 'shopbuilder' ),
			'condition'   => [ 'title_limit' => [ 'custom' ] ],
		];

		$fields['product_title_section_end'] = $obj->end_section();

		return $fields;
	}

	/**
	 * Title section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function section_title( $obj ) {
		$condition = [
			'show_section_title' => [ 'yes' ],
		];

		$fields['section_title_section'] = $obj->start_section(
			esc_html__( 'Section Title', 'shopbuilder' ),
			self::$tab,
			[],
			$condition
		);

		$fields['section_title_tag'] = [
			'type'        => 'select2',
			'label'       => esc_html__( 'Section Title Tag', 'shopbuilder' ),
			'options'     => ControlHelper::heading_tags(),
			'label_block' => true,
			'default'     => 'h2',
			'description' => esc_html__( 'Please select the section title tag.', 'shopbuilder' ),
		];

		$fields['section_title_text'] = [
			'type'        => 'text',
			'label'       => esc_html__( 'Section Title Text', 'shopbuilder' ),
			'default'     => esc_html__( 'Section Title', 'shopbuilder' ),
			'description' => esc_html__( 'Please enter the section title text.', 'shopbuilder' ),
			'label_block' => true,
		];

		$fields['section_title_section_end'] = $obj->end_section();

		return $fields;
	}

	/**
	 * Category Title section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function cat_title( $obj ) {
		$condition = [
			'show_title' => [ 'yes' ],
		];

		$fields['category_title_section'] = $obj->start_section(
			esc_html__( 'Category Title', 'shopbuilder' ),
			self::$tab,
			[],
			$condition
		);

		$fields['title_tag'] = [
			'type'        => 'select2',
			'label'       => esc_html__( 'Category Title Tag', 'shopbuilder' ),
			'options'     => ControlHelper::heading_tags(),
			'label_block' => true,
			'default'     => 'h3',
			'description' => esc_html__( 'Please select the category title tag.', 'shopbuilder' ),
			'separator'   => rtsb()->has_pro() ? 'before-short' : 'default',
		];

		$fields['show_custom_title'] = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Enable Custom Title?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to display alternate custom title.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'classes'     => $obj->pro_class(),
			'separator'   => rtsb()->has_pro() ? 'before-short' : 'default',
			'condition'   => [ 'layout' => [ 'category-single-layout1' ] ],
		];

		$fields['custom_title'] = [
			'type'        => 'textarea',
			'label'       => esc_html__( 'Alternate Custom Title', 'shopbuilder' ),
			'description' => esc_html__( 'Please enter an alternate custom title.', 'shopbuilder' ),
			'condition'   => [
				'show_custom_title' => [ 'yes' ],
				'layout'            => [ 'category-single-layout1' ],
			],
			'classes'     => $obj->pro_class(),
			'separator'   => rtsb()->has_pro() ? 'before-short' : 'default',
		];

		$fields['title_limit'] = [
			'type'        => 'select2',
			'label'       => esc_html__( 'Category Title Limit', 'shopbuilder' ),
			'options'     => [
				'default' => esc_html__( 'Default', 'shopbuilder' ),
				'1-line'  => esc_html__( 'Show in 1 line', 'shopbuilder' ),
				'2-lines' => esc_html__( 'Show in 2 lines', 'shopbuilder' ),
				'3-lines' => esc_html__( 'Show in 3 lines', 'shopbuilder' ),
				'custom'  => esc_html__( 'Custom', 'shopbuilder' ),
			],
			'label_block' => true,
			'default'     => 'default',
			'separator'   => rtsb()->has_pro() ? 'default' : 'before',
			'description' => esc_html__( 'Please select the category title limit.', 'shopbuilder' ),
		];

		$fields['title_limit_custom'] = [
			'type'        => 'number',
			'label'       => esc_html__( 'Custom Character Limit', 'shopbuilder' ),
			'default'     => 200,
			'description' => esc_html__( 'Please select the category title custom limit.', 'shopbuilder' ),
			'condition'   => [ 'title_limit' => [ 'custom' ] ],
		];

		$fields['category_title_section_end'] = $obj->end_section();

		return $fields;
	}

	/**
	 * Excerpt section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function product_excerpt( $obj ) {
		$condition = [
			'show_short_desc' => [ 'yes' ],
		];

		$fields['product_excerpt_section'] = $obj->start_section(
			esc_html__( 'Short Description', 'shopbuilder' ),
			self::$tab,
			[],
			$condition
		);

		$fields['excerpt_limit'] = [
			'type'        => 'select2',
			'label'       => esc_html__( 'Short Description Limit', 'shopbuilder' ),
			'description' => esc_html__( 'Please select the product short description limit.', 'shopbuilder' ),
			'options'     => [
				'default' => esc_html__( 'Default', 'shopbuilder' ),
				'1-line'  => esc_html__( 'Show in 1 line', 'shopbuilder' ),
				'2-lines' => esc_html__( 'Show in 2 lines', 'shopbuilder' ),
				'3-lines' => esc_html__( 'Show in 3 lines', 'shopbuilder' ),
				'custom'  => esc_html__( 'Custom', 'shopbuilder' ),
			],
			'label_block' => true,
			'default'     => 'default',
		];

		$fields['excerpt_limit_custom'] = [
			'type'        => 'number',
			'label'       => esc_html__( 'Custom Character Limit', 'shopbuilder' ),
			'default'     => 200,
			'description' => esc_html__( 'Please enter the product short description character limit.', 'shopbuilder' ),
			'condition'   => [ 'excerpt_limit' => [ 'custom' ] ],
		];

		$fields['product_excerpt_section_end'] = $obj->end_section();

		return $fields;
	}

	/**
	 * Category Excerpt section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function cat_excerpt( $obj ) {
		$condition = [
			'show_short_desc' => [ 'yes' ],
		];

		$fields['cat_excerpt_section'] = $obj->start_section(
			esc_html__( 'Category Description', 'shopbuilder' ),
			self::$tab,
			[],
			$condition
		);

		$fields['desciption_note'] = [
			'type'            => 'html',
			'raw'             => '',
			'content_classes' => 'elementor-panel-heading-title',
			'condition'       => [ 'layout' => [ 'category-single-layout1' ] ],
		];

		$fields['show_custom_description'] = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Enable Custom Description?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to display alternate custom description.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'classes'     => $obj->pro_class(),
			'separator'   => rtsb()->has_pro() ? 'before-short' : $obj->pro_class(),
			'condition'   => [ 'layout' => [ 'category-single-layout1' ] ],
		];

		$fields['custom_description'] = [
			'type'        => 'textarea',
			'label'       => esc_html__( 'Alternate Custom Description', 'shopbuilder' ),
			'description' => esc_html__( 'Please enter an alternate custom description.', 'shopbuilder' ),
			'condition'   => [
				'show_custom_description' => [ 'yes' ],
				'layout'                  => [ 'category-single-layout1' ],
			],
			'classes'     => $obj->pro_class(),
			'separator'   => rtsb()->has_pro() ? 'before-short' : 'default',
		];

		$fields['excerpt_position'] = [
			'type'        => 'select2',
			'label'       => esc_html__( 'Category Description Position', 'shopbuilder' ),
			'description' => esc_html__( 'Please select the category short description position.', 'shopbuilder' ),
			'options'     => [
				'above' => esc_html__( 'Top', 'shopbuilder' ),
				'below' => esc_html__( 'Bottom', 'shopbuilder' ),
			],
			'label_block' => true,
			'condition'   => [ 'layout!' => [ 'category-layout2' ] ],
			'default'     => 'below',
		];

		$fields['excerpt_limit'] = [
			'type'        => 'select2',
			'label'       => esc_html__( 'Category Description Limit', 'shopbuilder' ),
			'description' => esc_html__( 'Please select the category short description limit.', 'shopbuilder' ),
			'options'     => [
				'default' => esc_html__( 'Default', 'shopbuilder' ),
				'1-line'  => esc_html__( 'Show in 1 line', 'shopbuilder' ),
				'2-lines' => esc_html__( 'Show in 2 lines', 'shopbuilder' ),
				'3-lines' => esc_html__( 'Show in 3 lines', 'shopbuilder' ),
				'custom'  => esc_html__( 'Custom', 'shopbuilder' ),
			],
			'label_block' => true,
			'default'     => 'default',
		];

		$fields['excerpt_limit_custom'] = [
			'type'        => 'number',
			'label'       => esc_html__( 'Custom Character Limit', 'shopbuilder' ),
			'default'     => 200,
			'description' => esc_html__( 'Please select the category short description custom limit.', 'shopbuilder' ),
			'condition'   => [ 'excerpt_limit' => [ 'custom' ] ],
		];

		$fields['cat_excerpt_section_end'] = $obj->end_section();

		return $fields;
	}

	/**
	 * Variation swatch section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function variation_swatch( $obj ) {
		if ( ! function_exists( 'rtwpvsp' ) && ! rtsb()->has_pro() ) {
			return [];
		}
		$condition = [
			'show_swatches' => [ 'yes' ],
		];

		$fields['variation_swatch_section'] = $obj->start_section(
			esc_html__( 'Variation Swatches', 'shopbuilder' ),
			self::$tab,
			[],
			$condition
		);

		$fields['swatch_position'] = [
			'type'        => 'select2',
			'label'       => esc_html__( 'Swatches Display Position', 'shopbuilder' ),
			'description' => esc_html__( 'Please select the variation swatches display position.', 'shopbuilder' ),
			'options'     => [
				'top'   => esc_html__( 'Top of Content', 'shopbuilder' ),
				'title' => esc_html__( 'With Product Title', 'shopbuilder' ),
				'price' => esc_html__( 'WIth Product Price', 'shopbuilder' ),
			],
			'label_block' => true,
			'default'     => 'top',
		];
		if ( function_exists( 'rtwpvsp' ) ) {
			$fields['swatch_type'] = [
				'type'        => 'select2',
				'label'       => esc_html__( 'Color Swatches Type', 'shopbuilder' ),
				'description' => esc_html__( 'Please select the variation swatches display type (only work with color swatch).', 'shopbuilder' ),
				'options'     => [
					'square' => esc_html__( 'Square', 'shopbuilder' ),
					'circle' => esc_html__( 'Circle', 'shopbuilder' ),
				],
				'label_block' => true,
				'default'     => 'square',
			];
		}
		$fields['swatch_margin'] = [
			'mode'       => 'responsive',
			'type'       => 'dimensions',
			'label'      => esc_html__( 'Margin', 'shopbuilder' ),
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [ '{{WRAPPER}} .rtsb-swatches' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ],
		];

		$fields['variation_swatch_section_end'] = $obj->end_section();

		return $fields;
	}

	/**
	 * Badge section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function badges( $obj ) {
		$condition = [
			'show_badges' => [ 'yes' ],
			'layout!'     => [ 'category-layout1', 'category-layout2' ],
		];

		$fields['badges_section']       = $obj->start_section(
			esc_html__( 'Badges', 'shopbuilder' ),
			self::$tab,
			[],
			$condition
		);
		$fields['enable_badges_module'] = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Enable Badges Module?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to integrate Badge module.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'condition'   => [ 'layout!' => [ 'category-single-layout1', 'category-single-layout2', 'category-layout1', 'category-layout2', 'category-layout3' ] ],
		];
		$fields['badges_module_notice'] = [
			'type'      => 'html',
			'raw'       => sprintf(
				'<span style="display: block; background: #fffbf1; padding: 10px; line-height: 1.4; color: #bd3a3a; border: 1px solid #bd3a3a30;">%s</span>',
				sprintf(
					/* translators: 1: link to the Modules page */
					__(
						'<b>Note:</b> Badges Module needs to be enabled in the <a href="%1$s" target="_blank">ShopBuilder Modules Settings</a>. From there you can create and customize different badges.',
						'shopbuilder'
					),
					esc_url( admin_url( 'admin.php?page=rtsb-settings' ) )
				)
			),
			'separator' => rtsb()->has_pro() ? 'default' : 'after',
			'condition' => [ 'enable_badges_module' => [ 'yes' ] ],
		];

		$fields['badges_module_direction'] = [
			'type'        => 'select',
			'options'     => [
				'row'    => esc_html__( 'Horizontal', 'shopbuilder' ),
				'column' => esc_html__( 'Vertical', 'shopbuilder' ),
			],
			'label'       => esc_html__( 'Group Badge Direction', 'shopbuilder' ),
			'description' => esc_html__( 'Please choose the group badge direction.', 'shopbuilder' ),
			'condition'   => [ 'enable_badges_module' => [ 'yes' ] ],
			'default'     => 'row',
			'label_block' => true,
			'classes'     => $obj->pro_class(),
			'separator'   => rtsb()->has_pro() ? 'before-short' : 'default',
			'selectors'   => [
				$obj->selectors['badges_module']['direction'] => 'flex-direction: {{VALUE}} !important;',
			],
		];

		$fields['badges_module_alignment'] = [
			'type'        => 'choose',
			'options'     => [
				'start'  => [
					'title' => esc_html__( 'Left', 'shopbuilder' ),
					'icon'  => 'eicon-text-align-left',
				],
				'center' => [
					'title' => esc_html__( 'Center', 'shopbuilder' ),
					'icon'  => 'eicon-text-align-center',
				],
				'end'    => [
					'title' => esc_html__( 'Right', 'shopbuilder' ),
					'icon'  => 'eicon-text-align-right',
				],
			],
			'label'       => esc_html__( 'Group Badge Alignment', 'shopbuilder' ),
			'description' => esc_html__( 'Please choose the group badge alignment.', 'shopbuilder' ),
			'condition'   => [ 'enable_badges_module' => [ 'yes' ] ],
			'default'     => '',
			'separator'   => rtsb()->has_pro() ? 'default' : 'before',
			'label_block' => true,
			'selectors'   => [
				$obj->selectors['badges_module']['alignment']      => 'display: flex !important; width: 100% !important; justify-content: {{VALUE}} !important; align-items: {{VALUE}} !important;',
				$obj->selectors['badges_module']['alignment_wrap'] => 'width: 100% !important; left: 0 !important; right: 0 !important;',
			],
		];

		$fields['custom_badge_preset'] = [
			'type'      => 'rtsb-image-selector',
			'label'     => esc_html__( 'Custom Badge Appearance', 'shopbuilder' ),
			'options'   => ControlHelper::badge_presets(),
			'default'   => 'preset1',
			'condition' => [ 'enable_badges_module!' => [ 'yes' ] ],
		];

		$fields['sale_badges_type'] = [
			'type'        => 'select2',
			'label'       => esc_html__( 'Sale Badge Type', 'shopbuilder' ),
			'description' => esc_html__( 'Please select the badge type.', 'shopbuilder' ),
			'options'     => [
				'percentage' => esc_html__( 'Show Sale Percentage', 'shopbuilder' ),
				'text'       => esc_html__( 'Show Sale Text', 'shopbuilder' ),
			],
			'default'     => 'percentage',
			'label_block' => true,
			'condition'   => [
				'layout!'               => [ 'category-single-layout1', 'category-single-layout2', 'category-layout1', 'category-layout2', 'category-layout3' ],
				'enable_badges_module!' => [ 'yes' ],
			],
		];

		$fields['sale_badges_text'] = [
			'type'        => 'text',
			'label'       => esc_html__( 'Sale Badge Text', 'shopbuilder' ),
			'default'     => esc_html__( 'Sale', 'shopbuilder' ),
			'description' => esc_html__( 'Please enter the sale badge text.', 'shopbuilder' ),
			'label_block' => true,
			'condition'   => [
				'sale_badges_type'      => [ 'text' ],
				'layout!'               => [ 'category-single-layout1', 'category-single-layout2', 'category-layout1', 'category-layout2', 'category-layout3' ],
				'enable_badges_module!' => [ 'yes' ],
			],
		];

		$fields['stock_badges_text'] = [
			'type'        => 'text',
			'label'       => esc_html__( 'Out of Stock Badge Text', 'shopbuilder' ),
			'default'     => esc_html__( 'Out of Stock', 'shopbuilder' ),
			'description' => esc_html__( 'Please enter the out of stock badge text.', 'shopbuilder' ),
			'label_block' => true,
			'condition'   => [
				'layout!'               => [ 'category-single-layout1', 'category-single-layout2', 'category-layout1', 'category-layout2', 'category-layout3' ],
				'enable_badges_module!' => [ 'yes' ],
			],
		];

		$fields['custom_badge_text'] = [
			'type'        => 'text',
			'label'       => esc_html__( 'Custom Badge Text', 'shopbuilder' ),
			'description' => esc_html__( 'Please enter the custom badge text.', 'shopbuilder' ),
			'label_block' => true,
			'default'     => esc_html__( 'Popular', 'shopbuilder' ),
			'condition'   => [
				'layout'                => [ 'category-single-layout1', 'category-single-layout2', 'category-layout1', 'category-layout2' ],
				'enable_badges_module!' => [ 'yes' ],
			],
		];

		$fields['badges_section_end'] = $obj->end_section();

		return apply_filters( 'rtsb/elementor/badges', $fields, $obj );
	}

	/**
	 * Count section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function cat_count_settings( $obj ) {
		$condition = [
			'show_count' => [ 'yes' ],
		];

		$fields['count_section'] = $obj->start_section(
			esc_html__( 'Product Count', 'shopbuilder' ),
			self::$tab,
			[],
			$condition
		);

		$fields['count_display_type'] = [
			'type'        => 'select2',
			'label'       => esc_html__( 'Display Position', 'shopbuilder' ),
			'description' => esc_html__( 'Please choose the count display position.', 'shopbuilder' ),
			'options'     => [
				'flex'  => esc_html__( 'Same Line with Title', 'shopbuilder' ),
				'block' => esc_html__( 'After Title', 'shopbuilder' ),
			],
			'label_block' => true,
			'default'     => 'block',
			'render_type' => 'template',
			'condition'   => [ 'layout!' => [ 'category-layout2' ] ],
			'selectors'   => [
				$obj->selectors['cat_count_settings']['count_display_type'] => 'display: {{VALUE}};',
			],
		];

		$fields['before_count'] = [
			'type'        => 'text',
			'label'       => esc_html__( 'Text Before Count', 'shopbuilder' ),
			'description' => esc_html__( 'Please enter the text to show before count.', 'shopbuilder' ),
		];

		$fields['after_count'] = [
			'type'        => 'text',
			'label'       => esc_html__( 'Text After Count', 'shopbuilder' ),
			'description' => esc_html__( 'Please enter the text to show after count.', 'shopbuilder' ),
			'default'     => esc_html__( ' Products', 'shopbuilder' ),
		];

		$fields['count_section_end'] = $obj->end_section();

		return $fields;
	}

	/**
	 * Pagination section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function links( $obj ) {
		$fields['links_section'] = $obj->start_section(
			esc_html__( 'Links', 'shopbuilder' ),
			self::$tab
		);
		$obj->start_section( 'links_section', esc_html__( 'Detail Page', 'shopbuilder' ), self::$tab );

		$fields['title_link'] = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Title Clickable?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to enable title linking.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'default'     => 'yes',
		];

		$fields['image_link'] = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Image Clickable?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to enable image linking.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'default'     => 'yes',
		];

		$fields['hover_btn_note'] = $obj->el_heading(
			esc_html__( 'Hover Button', 'shopbuilder' ),
			'before',
			[],
			[ 'layout' => [ 'grid-layout2', 'slider-layout2' ] ]
		);

		$fields['hover_btn_text'] = [
			'type'        => 'text',
			'label'       => esc_html__( 'Hover Button Text', 'shopbuilder' ),
			'description' => esc_html__( 'Please enter the button text.', 'shopbuilder' ),
			'default'     => esc_html__( 'See Details', 'shopbuilder' ),
			'condition'   => [ 'layout' => [ 'grid-layout2', 'slider-layout2' ] ],
		];

		$fields['show_hover_btn_icon'] = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Enable Hover Button Icon?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to enable hover button icon.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'default'     => 'yes',
			'condition'   => [ 'layout' => [ 'grid-layout2', 'slider-layout2' ] ],
		];

		$fields['hover_btn_icon'] = [
			'type'        => 'icons',
			'label'       => esc_html__( 'Choose Icon', 'shopbuilder' ),
			'description' => esc_html__( 'Please choose the hover button icon.', 'shopbuilder' ),
			'default'     => [
				'value'   => 'fas fa-angle-right',
				'library' => 'fa-solid',
			],
			'condition'   => [
				'show_hover_btn_icon' => [ 'yes' ],
				'layout'              => [ 'grid-layout2', 'slider-layout2' ],
			],
		];

		$fields['custom_link'] = [
			'type'        => 'link',
			'label'       => esc_html__( 'Alternate Custom Link', 'shopbuilder' ),
			'description' => esc_html__( 'Please enter an alternate custom link.', 'shopbuilder' ),
			'placeholder' => esc_html__( 'https://custom-link.com', 'shopbuilder' ),
			'options'     => [ 'url', 'is_external', 'nofollow' ],
			'condition'   => [ 'layout' => [ 'category-single-layout1' ] ],
		];

		$fields['links_section_end'] = $obj->end_section();

		return $fields;
	}
}
