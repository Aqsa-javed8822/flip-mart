<?php

namespace RadiusTheme\SB\Modules\Compare;

use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Traits\SingletonTrait;
use WP_Error;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Class CompareFns
 */
class CompareFns {
	/**
	 * @var singleton
	 */
	use SingletonTrait;

	/**
	 * @return false|string
	 */
	public function get_page_url() {
		$page_id = Fns::get_option( 'modules', 'compare', 'page' );
		return $page_id && absint( $page_id ) ? get_permalink( $page_id ) : '#';
	}

	/**
	 * Get Fields List
	 *
	 * @return array
	 */
	public static function get_available_list_fields() {
		$attribute_list = [];

		if ( function_exists( 'wc_get_attribute_taxonomies' ) ) {
			$attribute_list = wc_get_attribute_taxonomies();
		}

		$fields = self::get_list_default_fields();

		if ( count( $attribute_list ) > 0 ) {
			foreach ( $attribute_list as $attribute ) {
				$fields[ 'pa_' . $attribute->attribute_name ] = $attribute->attribute_label;
			}
		}

		return $fields;
	}


	/**
	 * Get default fields List
	 * return array
	 */
	public static function get_list_default_fields() {
		$fields = [
			'title'        => esc_html__( 'Title', 'shopbuilder' ),
			'rating'       => esc_html__( 'Rating', 'shopbuilder' ),
			'price'        => esc_html__( 'Price', 'shopbuilder' ),
			'add_to_cart'  => esc_html__( 'Add To Cart', 'shopbuilder' ),
			'description'  => esc_html__( 'Description', 'shopbuilder' ),
			'availability' => esc_html__( 'Availability', 'shopbuilder' ),
			'sku'          => esc_html__( 'Sku', 'shopbuilder' ),
			'weight'       => esc_html__( 'Weight', 'shopbuilder' ),
			'dimensions'   => esc_html__( 'Dimensions', 'shopbuilder' ),
		];

		return apply_filters( 'rtsb/module/compare/table_default_fields', $fields );
	}


	/**
	 * Table field list
	 *
	 * @return array Table Field list
	 */
	public static function get_list_field_ids() {

		$default_show    = array_keys( self::get_list_default_fields() );
		$fields_settings = Fns::get_option( 'modules', 'compare', 'page_show_fields', [] );
		if ( isset( $fields_settings ) && ( is_array( $fields_settings ) ) && count( $fields_settings ) > 1 ) {
			$fields = $fields_settings;
		} else {
			$fields = $default_show;
		}

		return $fields;
	}

	/**
	 * @param string $field field.
	 *
	 * @return mixed|string|void
	 */
	public function field_name( $field ) {

		if ( empty( $field ) ) {
			return;
		}

		$default = self::get_list_default_fields();

		$str = substr( $field, 0, 3 );
		if ( 'pa_' === $str ) {
			$field_name = wc_attribute_label( $field );
		} else {
			$field_name = isset( $default[ $field ] ) ? $default[ $field ] : '';
		}

		return $field_name;
	}

	/**
	 * @param string $field_id firld id.
	 * @param object $product product object.
	 *
	 * @return void
	 */
	public function display_field( $field_id, $product ) {

		$type = $field_id;
		if ( 'pa_' === substr( $field_id, 0, 3 ) ) {
			$type = 'attribute';
		}
		switch ( $type ) {
			case 'primary':
				?>
				<div class="rtsb-compare-primary-content-area">
					<a href="#" class="rtsb-compare-remove" data-product_id="<?php echo absint( $product['id'] ); ?>">
						<i class="rtsb-icon rtsb-icon-trash-empty"></i>
					</a>
					<a href="<?php echo esc_url( get_permalink( $product['id'] ) ); ?>" class="rtsb-compare-product-image">
						<?php
						if ( ! empty( $product['primary']['image'] ) ) {
							echo wp_kses( $product['primary']['image'], Fns::allowedHtml( 'image' ) );
						}
						?>
					</a>
				</div>
				<?php
				break;

			case 'title':
				echo '<a href="' . esc_url( get_permalink( $product['id'] ) ) . '" class="rtsb-compare-product-title">' . esc_html( $product[ $field_id ] ) . '</a>';
				break;

			case 'rating':
				echo '<span class="rtsb-compare-product-rating">' . wp_kses_post( $product[ $field_id ] ) . '</span>';
				break;

			case 'price':
				echo '<div class="rtsb-compare-product-price">' . wp_kses_post( $product[ $field_id ] ) . '</div>';
				break;

			case 'add_to_cart':
				Fns::print_html( apply_filters( 'rtsb/module/compare/add_to_cart_btn', $product[ $field_id ] ), true );
				break;

			case 'weight':
				if ( $product[ $field_id ] ) {
					$unit = '-' !== $product[ $field_id ] ? get_option( 'woocommerce_weight_unit' ) : '';
					Fns::print_html( wc_format_localized_decimal( $product[ $field_id ] ) . ' ' . esc_attr( $unit ) );
				}
				break;

			case 'description':
				Fns::print_html( apply_filters( 'woocommerce_short_description', $product[ $field_id ] ) );
				break;
			case 'attribute':
				if ( isset( $product[ $field_id ] ) ) {
					Fns::print_html( $product[ $field_id ] );
				}
				break;
			default:
				echo isset( $product[ $field_id ] ) ? wp_kses_post( $product[ $field_id ] ) : '';
				break;
		}
	}

	/**
	 * @return array
	 */
	public function get_products_data() {
		$ids = $this->get_compared_product_ids();

		// For shareable link.
		if ( empty( $ids ) ) {
			return [];
		}

		$args = [
			'include' => $ids,
		];

		$products = wc_get_products( $args );

		$products_data = [];

		$field_ids     = $this->get_list_field_ids();
		$tax_field_ids = array_filter(
			$field_ids,
			function ( $field_id ) {
				return str_starts_with( $field_id, 'pa_' );
			}
		);

		$data_none = '-';

		foreach ( $products as $product ) {

			$rating_count = $product->get_rating_count();
			$average      = $product->get_average_rating();

			$products_data[ $product->get_id() ] = [
				'primary'      => [
					'image' => $product->get_image() ? $product->get_image( 'ever-compare-image' ) : $data_none,
				],
				'id'           => $product->get_id(),
				'title'        => $product->get_title() ? $product->get_title() : $data_none,
				'image_id'     => $product->get_image_id(),
				'permalink'    => $product->get_permalink(),
				'price'        => $product->get_price_html() ? $product->get_price_html() : $data_none,
				'rating'       => wc_get_rating_html( $average, $rating_count ),
				'add_to_cart'  => $this->add_to_cart_html( $product ) ? $this->add_to_cart_html( $product ) : $data_none,
				'dimensions'   => wc_format_dimensions( $product->get_dimensions( false ) ),
				'description'  => $product->get_short_description() ? $product->get_short_description() : $data_none,
				'weight'       => $product->get_weight() ? $product->get_weight() : $data_none,
				'sku'          => $product->get_sku() ? $product->get_sku() : $data_none,
				'availability' => $this->availability_html( $product ),
			];
			if ( empty( $tax_field_ids ) ) {
				continue;
			}
			foreach ( $tax_field_ids as $field_id ) {
				if ( ! taxonomy_exists( $field_id ) ) {
					continue;
				}
				$products_data[ $product->get_id() ][ $field_id ] = [];
				$terms = get_the_terms( $product->get_id(), $field_id );
				if ( ! empty( $terms ) ) {
					foreach ( $terms as $term ) {
						$term = sanitize_term( $term, $field_id );
						$products_data[ $product->get_id() ][ $field_id ][] = $term->name;
					}
				} else {
					$products_data[ $product->get_id() ][ $field_id ][] = '-';
				}
				$products_data[ $product->get_id() ][ $field_id ] = implode( ', ', $products_data[ $product->get_id() ][ $field_id ] );

			}
		}

		return $products_data;
	}

	/**
	 * @param object $product product object.
	 *
	 * @return mixed|void|null
	 */
	public function add_to_cart_html( $product ) {

		$defaults = [
			'quantity'   => 1,
			'class'      => implode(
				' ',
				array_filter(
					[
						'rtsb-compare-cart-button button',
						'product_type_' . $product->get_type(),
						$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
						$product->supports( 'ajax_add_to_cart' ) && $product->is_purchasable() && $product->is_in_stock() ? 'ajax_add_to_cart' : '',
					]
				)
			),
			'attributes' => [
				'data-product_id'  => $product->get_id(),
				'data-product_sku' => $product->get_sku(),
				'aria-label'       => $product->add_to_cart_description(),
			],
		];

		$args = apply_filters( 'woocommerce_loop_add_to_cart_args', $defaults, $product );

		if ( isset( $args['attributes']['aria-label'] ) ) {
			$args['attributes']['aria-label'] = wp_strip_all_tags( $args['attributes']['aria-label'] );
		}

		return apply_filters(
			'woocommerce_loop_add_to_cart_link',
			sprintf(
				'<a href="%s" data-quantity="%s" class="%s add-to-cart-loop" %s><span>%s</span></a>',
				esc_url( $product->add_to_cart_url() ),
				esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
				esc_attr( isset( $args['class'] ) ? $args['class'] : 'button' ),
				isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
				esc_html( $product->add_to_cart_text() )
			),
			$product,
			$args
		);
	}
	/**
	 * @param object $product product object.
	 *
	 * @return mixed|void|null
	 */
	public function availability_html( $product ) {
		$html         = '';
		$availability = $product->get_availability();

		if ( empty( $availability['availability'] ) ) {
			$availability['availability'] = esc_html__( 'In stock', 'shopbuilder' );
		}

		if ( ! empty( $availability['availability'] ) ) {
			ob_start();

			wc_get_template(
				'single-product/stock.php',
				[
					'product'      => $product,
					'class'        => $availability['class'],
					'availability' => $availability['availability'],
				]
			);

			$html = ob_get_clean();
		}

		return apply_filters( 'woocommerce_get_stock_html', $html, $product );
	}

	/**
	 * @param int $id product id.
	 * @return true|WP_Error
	 */
	public function add_product( $id ) {
		$cookie_name = $this->get_cookie_name();

		if ( $this->is_exists_in_list( $id ) ) {
			return new \WP_Error( 'rtsb-compare-product-exist', esc_html__( 'Product already in compare list!.', 'shopbuilder' ) );
		}

		$product_ids = $this->get_compared_product_ids();

		// Reached Maximum Limit.
		if ( $this->reached_max_limit() ) {
			return new \WP_Error( 'rtsb-compare-reached_max_limit', esc_html__( 'Reached maximum number of compare!.', 'shopbuilder' ) );
		}

		$product_ids[] = $id;

		setcookie( $cookie_name, wp_json_encode( $product_ids ), 0, COOKIEPATH, COOKIE_DOMAIN, false, false );

		$_COOKIE[ $cookie_name ] = wp_json_encode( $product_ids );

		return true;
	}

	/**
	 * Remove product from compare list
	 *
	 * @param integer $product_id product id.
	 *
	 * @return bool|WP_Error
	 */
	public function remove_product( $product_id ) {

		if ( ! $this->is_exists_in_list( $product_id ) ) {
			return new WP_Error( 'rtsb_compare_product_not_exist', esc_html__( 'Product not in compare list!.', 'shopbuilder' ) );
		}
		$delete_status = false;
		$product_ids   = $this->get_compared_product_ids();
		if ( is_array( $product_ids ) && ! empty( $product_ids ) ) {
			foreach ( $product_ids as $prod_key => $productId ) {
				if ( $productId == $product_id ) {
					unset( $product_ids[ $prod_key ] );
					$delete_status = true;
					break;
				}
			}
		}

		$cookie_name = $this->get_cookie_name();

		setcookie( $cookie_name, wp_json_encode( $product_ids ), 0, COOKIEPATH, COOKIE_DOMAIN, false, false );
		$_COOKIE[ $cookie_name ] = wp_json_encode( $product_ids );

		return $delete_status;
	}

	/**
	 * @return string
	 */
	public function get_cookie_name() {
		$name = 'rtsb_compare_list';
		if ( is_multisite() ) {
			$name .= '_' . get_current_blog_id();
		}

		return $name;
	}

	/**
	 * @param int $id product id.
	 *
	 * @return bool
	 */
	public function is_exists_in_list( $id ) {
		$list = $this->get_compared_product_ids();
		return in_array( absint( $id ), $list, true );
	}

	/**
	 * @return array
	 */
	public function get_compared_product_ids() {
		$cookie_name = $this->get_cookie_name();
		$ids         = [];

		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		if ( isset( $_COOKIE[ $cookie_name ] ) && is_array( json_decode( wp_unslash( $_COOKIE[ $cookie_name ] ), true ) ) ) {
			// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			$ids = array_map( 'absint', json_decode( wp_unslash( $_COOKIE[ $cookie_name ] ), true ) );
		}

		return $ids;
	}

	/**
	 * @return bool
	 */
	public function reached_max_limit() {

		$product_ids = $this->get_compared_product_ids();
		$max_limit   = absint( Fns::get_option( 'modules', 'compare', 'max_limit', 10, 'number' ) );

		// Remove if product is not exist.
		foreach ( $product_ids as $id ) {
			if ( 'publish' !== get_post_status( $id ) ) {
				$this->remove_product( $id );
				unset( $product_ids[ $id ] );
			}
		}

		if ( $max_limit && count( $product_ids ) >= $max_limit ) {
			return true;
		}

		return false;
	}
}
