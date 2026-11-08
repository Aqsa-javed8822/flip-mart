<?php

namespace RadiusTheme\SB\Modules\WishList;

use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Traits\SingletonTrait;
use WC_Product;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Class WishlistFns
 */
class WishlistFns {
	/**
	 * Singleton
	 */
	use SingletonTrait;

	/**
	 * Wishlist menu slug
	 */
	const WC_MENU_SLUG = 'wishlist';

	/**
	 * Remove product id
	 *
	 * @param int $product_id product id.
	 *
	 * @return bool|int
	 */
	public function remove_product( $product_id ) {

		if ( ! $this->is_exists_in_wishlist( $product_id ) ) {
			return 0;
		}
		$pIds = $this->get_wishlist_ids();
		$pIds = array_diff( $pIds, [ $product_id ] );
		if ( is_user_logged_in() ) {
			$this->update_user_wishlist_ids( $pIds );
		} else {
			$cookie_name = $this->get_cookie_name();
			if ( empty( $pIds ) ) {
				setcookie( $cookie_name, false, 0, COOKIEPATH, COOKIE_DOMAIN, false, false );
				$_COOKIE[ $cookie_name ] = false;
			} else {
				setcookie( $cookie_name, wp_json_encode( $pIds ), 0, COOKIEPATH, COOKIE_DOMAIN, false, false );
				$_COOKIE[ $cookie_name ] = wp_json_encode( $pIds );
			}
		}

		return true;
	}

	/**
	 * @return false|string
	 */
	public static function get_page_url() {
		$page_id = Fns::get_option( 'modules', 'wishlist', 'page' );
		return $page_id && absint( $page_id ) ? get_permalink( $page_id ) : '#';
	}

	/**
	 * Product Add
	 *
	 * @param integer $product_id product id.
	 */
	public function add_product( $product_id ) {

		if ( $this->is_exists_in_wishlist( $product_id ) ) {
			return 0;
		}

		$pIds   = $this->get_wishlist_ids();
		$pIds[] = $product_id;
		if ( is_user_logged_in() ) {
			$this->update_user_wishlist_ids( $pIds );
		} else {
			$cookie_name = $this->get_cookie_name();

			setcookie( $cookie_name, wp_json_encode( $pIds ), 0, COOKIEPATH, COOKIE_DOMAIN, false, false );
			$_COOKIE[ $cookie_name ] = wp_json_encode( $pIds );
		}

		return 1;
	}

	/**
	 * [get_products_data] generate wishlist products data
	 *
	 * @return array product list
	 */
	public function get_products_data() {

		$ids = $this->get_wishlist_ids();

		if ( empty( $ids ) ) {
			return [];
		}

		$args = [
			'include' => $ids,
		];

		$products = wc_get_products( $args );

		$products_data = [];

		$fields = $this->get_field_ids();

		$fields = array_filter(
			$fields,
			function ( $field ) {
				return 'pa_' === substr( $field, 0, 3 );
			},
			ARRAY_FILTER_USE_KEY
		);

		$data_none = '-';

		foreach ( $products as $product ) {

			$rating_count  = $product->get_rating_count();
			$average       = $product->get_average_rating();
			$quantity_args = [
				'input_value' => 1,
				'min_value'   => $product->get_min_purchase_quantity(),
				'max_value'   => $product->get_max_purchase_quantity(),
			];

			$products_data[ $product->get_id() ] = [
				'id'           => $product->get_id(),
				'remove'       => $product->get_id(),
				'image'        => $product->get_image() ? $product->get_image() : $data_none,
				'title'        => $product->get_title() ? $product->get_title() : $data_none,
				'image_id'     => $product->get_image_id(),
				'permalink'    => $product->get_permalink(),
				'price'        => $product->get_price_html() ? $product->get_price_html() : $data_none,
				'rating'       => wc_get_rating_html( $average, $rating_count ),
				'add_to_cart'  => $this->add_to_cart_html( $product ) ? $this->add_to_cart_html( $product ) : $data_none,
				'quantity'     => woocommerce_quantity_input( $quantity_args, $product, false ),
				'dimensions'   => wc_format_dimensions( $product->get_dimensions( false ) ),
				'description'  => $product->get_short_description() ? $product->get_short_description() : $data_none,
				'weight'       => $product->get_weight() ? $product->get_weight() : $data_none,
				'sku'          => $product->get_sku() ? $product->get_sku() : $data_none,
				'availability' => $this->availability_html( $product ),
			];

			foreach ( $fields as $field_id => $field_name ) {
				if ( taxonomy_exists( $field_id ) ) {
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
		}

		return $products_data;
	}

	/**
	 * Table field list
	 *
	 * @return array Table Field list
	 */
	public function get_field_ids() {

		$default_show = [
			'info',
			'price',
			'availability',
			'add_to_cart',
			'remove',
		];

		$fields_settings = Fns::get_option( 'modules', 'wishlist', 'page_show_fields', [] );

		if ( isset( $fields_settings ) && ( is_array( $fields_settings ) ) && count( $fields_settings ) > 1 ) {
			$fields = $fields_settings;
		} else {
			$fields = $default_show;
		}

		return $fields;
	}

	/**
	 * Get default fields List
	 * return array
	 */
	public function get_default_fields() {
		$fields = [
			'info'         => esc_html__( 'Products', 'shopbuilder' ),
			'image'        => esc_html__( 'Image', 'shopbuilder' ),
			'title'        => esc_html__( 'Title', 'shopbuilder' ),
			'price'        => esc_html__( 'Price', 'shopbuilder' ),
			'quantity'     => esc_html__( 'Quantity', 'shopbuilder' ),
			'add_to_cart'  => esc_html__( 'Add To Cart', 'shopbuilder' ),
			'remove'       => esc_html__( 'Remove', 'shopbuilder' ),
			'description'  => esc_html__( 'Description', 'shopbuilder' ),
			'availability' => esc_html__( 'Availability', 'shopbuilder' ),
			'sku'          => esc_html__( 'Sku', 'shopbuilder' ),
			'weight'       => esc_html__( 'Weight', 'shopbuilder' ),
			'dimensions'   => esc_html__( 'Dimensions', 'shopbuilder' ),
		];
		return apply_filters( 'rtsb/module/wishlist/list_default_fields', $fields );
	}

	/**
	 * [get_cookie_name] Get cookie name
	 *
	 * @return string [string]
	 */
	public function get_cookie_name() {
		$name = Wishlist::KEY;
		if ( is_multisite() ) {
			$name .= '_' . get_current_blog_id();
		}

		return $name;
	}

	/**
	 * Display_field
	 *
	 * @param string $field_id attribute slug.
	 * @param array  $product product data.
	 *
	 * @return void [html]
	 */
	public function display_field( $field_id, $product ) {

		$type = $field_id;

		if ( 'pa_' === substr( $field_id, 0, 3 ) ) {
			$type = 'attribute';
		}

		switch ( $type ) {
			case 'info':
				?>
				<a href="<?php echo esc_url( get_permalink( $product['id'] ) ); ?>"> <?php echo wp_kses( $product['image'], Fns::allowedHtml( 'image' ) ); ?> </a>
				<a href="<?php the_permalink( $product['id'] ); ?>"><?php echo esc_html( $product['title'] ); ?></a>
				<?php
				break;

			case 'remove':
				?>
				<a href="#" class="rtsb-wishlist-remove"
				   data-product_id="<?php echo esc_attr( $product['id'] ); ?>"><i class="rtsb-icon rtsb-icon-trash-empty"></i></a>
				<?php
				break;

			case 'image':
				?>
				<a href="<?php echo esc_url( get_permalink( $product['id'] ) ); ?>"> <?php echo wp_kses( $product['image'], Fns::allowedHtml( 'image' ) ); ?> </a>
				<?php
				break;

			case 'title':
				?>
				<a href="<?php the_permalink( $product['id'] ); ?>"><?php echo esc_html( $product[ $field_id ] ); ?></a>
				<?php
				break;
			case 'quantity':
				Fns::print_html( $product[ $field_id ] );
				break;

			case 'ratting':
				echo '<span class="rtsb-wl-product-ratting">' . wp_kses_post( $product[ $field_id ] ) . '</span>';
				break;

			case 'add_to_cart':
				Fns::print_html( apply_filters( 'rtsb/modules/wishlist/add_to_cart_btn', $product[ $field_id ] ) );
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
			case 'price':
			default:
				echo wp_kses_post( $product[ $field_id ] );
				break;
		}
	}

	/**
	 * Field name
	 *
	 * @param string $field field.
	 * @param bool   $custom custom or not.
	 *
	 * @return string|void
	 */
	public function field_name( $field, $custom = false ) {

		if ( empty( $field ) ) {
			return;
		}

		if ( true === $custom ) {
			return $field;
		}

		$default = $this->get_default_fields();

		$str = substr( $field, 0, 3 );
		if ( 'pa_' === $str ) {
			$field_name = wc_attribute_label( $field );
		} else {
			$field_name = $default[ $field ];
		}

		return $field_name;
	}

	/**
	 * Get wishlist product ids
	 *
	 * @return array
	 */
	public function get_wishlist_ids() {
		$ids = [];
		if ( is_user_logged_in() ) {
			$listIds = get_user_meta( get_current_user_id(), Wishlist::KEY, true );
			if ( is_array( $listIds ) && ! empty( $listIds ) ) {
				$ids = array_map( 'absint', $listIds );
			}
		} else {
			$cookie_name = $this->get_cookie_name();

			// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			if ( isset( $_COOKIE[ $cookie_name ] ) && is_array( json_decode( wp_unslash( $_COOKIE[ $cookie_name ] ), true ) ) ) {
				// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
				$ids = array_map( 'absint', json_decode( wp_unslash( $_COOKIE[ $cookie_name ] ), true ) );
			}
		}
		return Fns::get_available_products_by_ids( $ids );
	}

	/**
	 * @param array $product_ids ids.
	 * @return void
	 */
	public function update_user_wishlist_ids( $product_ids ) {
		$uid         = get_current_user_id();
		$product_ids = Fns::get_available_products_by_ids( $product_ids );
		update_user_meta( $uid, Wishlist::KEY, $product_ids );
	}

	/**
	 * @param int $product_id product id.
	 *
	 * @return bool
	 */
	public function is_exists_in_wishlist( $product_id ) {
		$id   = $product_id;
		$list = $this->get_wishlist_ids();
		if ( is_array( $list ) ) {
			return in_array( absint( $id ), $list, true );
		} else {
			return false;
		}
	}

	/**
	 * @param object $product product.
	 *
	 * @return string
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
	 * Generate Cart button
	 *
	 * @param object $product product.
	 */
	public function add_to_cart_html( $product ) {
		if ( ! $product ) {
			return;
		}

		$defaults = [
			'quantity'   => 1,
			'class'      => implode(
				' ',
				array_filter(
					[
						'htcompare-cart-button button',
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
}
