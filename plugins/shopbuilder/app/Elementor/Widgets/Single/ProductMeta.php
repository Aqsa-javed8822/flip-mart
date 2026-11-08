<?php
/**
 * Main ProductMeta class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\Single;

use Elementor\Controls_Manager;
use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Abstracts\ElementorWidgetBase;
use RadiusTheme\SB\Elementor\Widgets\Controls\MetaSettings;
use RadiusTheme\SB\Elementor\Widgets\Controls\ProductTaxSettings;
use WC_Product;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Product Meta class
 */
class ProductMeta extends ElementorWidgetBase {
	/**
	 * Construct function
	 *
	 * @param array $data default array.
	 * @param mixed $args default arg.
	 */
	public function __construct( $data = [], $args = null ) {
		$this->rtsb_name = esc_html__( 'Product Meta', 'shopbuilder' );
		$this->rtsb_base = 'rtsb-product-meta';
		parent::__construct( $data, $args );
	}
	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public function widget_fields() {
		$fields                              = MetaSettings::widget_fields( $this );
		$tax_settings                        = ProductTaxSettings::widget_fields( $this );
		$tax_settings['meta_content']['tab'] = 'style';
		$fields                              = $fields + $tax_settings;
		unset( $fields['show_label'] );
		unset( $fields['meta_label_heading']['condition'] );
		unset( $fields['label_typo']['condition'] );
		unset( $fields['meta_label_color']['condition'] );
		return $fields;
	}
	/**
	 * Set Widget Keyword.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return [ 'SKU', 'Category', 'Tag' ] + parent::get_keywords();
	}
	/**
	 * Registers the brand row on the product meta for the editor preview.
	 *
	 * @return void
	 */
	public function apply_hooks() {
		add_action( 'woocommerce_product_meta_end', [ $this, 'show_brand' ], 99 );
	}
	/**
	 * Displays the brand row in the editor preview (WooCommerce core's brand hook bails
	 * there because the request is not a singular product). The label is wrapped in a
	 * `.rtsb-meta-label` element so it can be styled independently of the value.
	 */
	public function show_brand() {
		$product = Fns::get_product();
		if ( ! $product instanceof WC_Product ) {
			$product = wc_get_product();
		}
		if ( ! $product instanceof WC_Product || ! function_exists( 'wc_get_brands' ) ) {
			return;
		}
		$terms = get_the_terms( $product->get_id(), 'product_brand' );
		if ( empty( $terms ) || is_wp_error( $terms ) ) {
			return;
		}
		echo wc_get_brands( $product->get_id(), ', ', $this->brand_meta_before( count( $terms ) ), '</span>' ); // phpcs:ignore WordPress.Security.EscapeOutput
	}

	/**
	 * Wraps WooCommerce core's brand meta output so its label sits in a `.rtsb-meta-label`
	 * element, matching the SKU/Category/Tag rows. Hooked onto `woocommerce_product_brands_output`
	 * (WC 9.8+) only while this widget renders, so it never affects brand output elsewhere.
	 *
	 * @param string $brand_output Default brand HTML from WooCommerce.
	 * @param mixed  $terms        Brand term objects, or empty when none.
	 * @param int    $post_id      Product ID.
	 *
	 * @return string
	 */
	public function wrap_brand_label( $brand_output, $terms, $post_id ) {
		if ( empty( $terms ) || is_wp_error( $terms ) || ! function_exists( 'wc_get_brands' ) ) {
			return $brand_output;
		}
		return wc_get_brands( $post_id, ', ', $this->brand_meta_before( count( $terms ) ), '</span>' );
	}

	/**
	 * Builds the opening markup of the brand row with its label wrapped in `.rtsb-meta-label`.
	 *
	 * @param int $count Number of brand terms (for the singular/plural label).
	 *
	 * @return string
	 */
	private function brand_meta_before( $count ) {
		$taxonomy = get_taxonomy( 'product_brand' );
		if ( ! $taxonomy ) {
			return '<span class="posted_in"><span class="rtsb-meta-label">' . esc_html__( 'Brand:', 'shopbuilder' ) . '</span> ';
		}
		$labels = $taxonomy->labels;
		/* translators: %s - Brand label name */
		$label = sprintf( _n( '%s:', '%s:', $count, 'shopbuilder' ), $labels->singular_name, $labels->name );
		return '<span class="posted_in"><span class="rtsb-meta-label">' . esc_html( $label ) . '</span> ';
	}
	/**
	 * Render Function
	 *
	 * @return void
	 */
	protected function render() {
		global $product;
		$_product    = $product;
		$product     = Fns::get_product();
		$controllers = $this->get_settings_for_display();
		if ( $this->is_builder_mode() ) {
			$this->apply_hooks();
		}
		$this->theme_support();
		// show_sku.
		$classes = '';
		if ( ! empty( $controllers['show_sku'] ) ) {
			$classes .= 'rtsb-show-sku ';
		}
		if ( ! empty( $controllers['show_cat'] ) ) {
			$classes .= 'rtsb-show-cat ';
		}
		if ( ! empty( $controllers['show_tag'] ) ) {
			$classes .= 'rtsb-show-tag ';
		}
		if ( ! empty( $controllers['show_brand'] ) ) {
			$classes .= 'rtsb-show-brand ';
		}
		$data = [
			'template'    => 'elementor/single-product/meta',
			'controllers' => $controllers,
			'classes'     => $classes,
		];
		add_filter( 'woocommerce_product_brands_output', [ $this, 'wrap_brand_label' ], 10, 3 );
		Fns::load_template( $data['template'], $data );
		remove_filter( 'woocommerce_product_brands_output', [ $this, 'wrap_brand_label' ], 10 );
		$this->theme_support( 'render_reset' );
		$product = $_product; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
	}
}
