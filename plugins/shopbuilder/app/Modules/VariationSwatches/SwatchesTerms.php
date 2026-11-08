<?php
/**
 * Main FilterHooks class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Modules\VariationSwatches;

use RadiusTheme\SB\Helpers\Cache;
use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Traits\SingletonTrait;

defined( 'ABSPATH' ) || exit();

/**
 * Main FilterHooks class.
 */
class SwatchesTerms {
	/**
	 * Singleton Trait.
	 */
	use SingletonTrait;

	/**
	 * Module Class Constructor.
	 */
	private function __construct() {
		add_filter( 'product_attributes_type_selector', [ $this, 'product_attributes_types' ] );
		add_action( 'admin_init', [ $this, 'add_product_taxonomy_meta' ] );
	}

	/**
	 * Product attributes types.
	 *
	 * @param array $selector Selector.
	 *
	 * @return array
	 */
	public function product_attributes_types( $selector ) {
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_REQUEST['post'] ) && 'edit' === sanitize_text_field( wp_unslash( $_REQUEST['action'] ?? '' ) ) ) {
			return $selector;
		}
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_REQUEST['product_type'] ) ) {
			return $selector;
		}
		$types = SwatchesFns::get_available_attributes_types();
		if ( ! empty( $types ) && is_array( $types ) ) {
			$selector = array_merge( $selector, $types );
		}
		return $selector;
	}
	/**
	 * Add product taxonomy meta.
	 */
	public function add_product_taxonomy_meta() {

		$fields         = SwatchesFns::get_taxonomy_meta_fields();
		$meta_added_for = apply_filters( 'rtsb/product/taxonomy/meta/for', array_keys( $fields ) );
		if ( function_exists( 'wc_get_attribute_taxonomies' ) ) :
			$attribute_taxonomies = wc_get_attribute_taxonomies();
			if ( $attribute_taxonomies ) :
				foreach ( $attribute_taxonomies as $tax ) :
					$product_attr      = wc_attribute_taxonomy_name( $tax->attribute_name );
					$product_attr_type = $tax->attribute_type;
					if ( in_array( $product_attr_type, $meta_added_for, true ) ) :
						new TermMeta( $product_attr, $fields[ $product_attr_type ] );
						do_action( 'rtsb/wc/attribute/taxonomy/meta/added', $product_attr, $product_attr_type );
					endif;
				endforeach;
			endif;
		endif;
	}
}
