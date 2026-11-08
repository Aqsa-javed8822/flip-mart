<?php
/**
 * Main FilterHooks class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Modules\Badges;

use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SBPRO\Modules\PreOrder\PreOrderFns;
use WC_Product;

defined( 'ABSPATH' ) || exit();

/**
 * Main FilterHooks class.
 */
class BadgesFns {
	/**
	 * @var array
	 */
	private static $cache = [];

	/**
	 * Get product min/max price.
	 *
	 * @return array|false
	 */
	public static function get_product_badges_list() {
		$cache_key = 'get_product_badges_list';
		// Reduce Calculation.
		if ( isset( self::$cache[ $cache_key ] ) ) {
			return self::$cache[ $cache_key ];
		}
		// If the cached result doesn't exist, fetch it from the database.
		$options                   = Fns::get_options( 'modules', 'product_badges' );
		$badges_field              = json_decode( $options['badges_field'] ?? '', true );
		self::$cache[ $cache_key ] = $badges_field;

		return $badges_field;
	}

	/**
	 * Get Badges.
	 *
	 * @param object $product Product Object.
	 *
	 * @return array|mixed
	 */
	public static function get_product_badges_for_current_product( $product = null ) {
		$available_badges = [];

		if ( is_null( $product ) ) {
			global $product;
		}

		if ( ! $product instanceof WC_Product ) {
			return [];
		}

		$product_id = $product->get_id();
		$cache_key  = 'badges_cache_for_' . $product_id;

		// Reduce Calculation.
		if ( isset( self::$cache[ $cache_key ] ) ) {
			return self::$cache[ $cache_key ];
		}

		$active_product_badges = self::get_product_badges_list();

		if ( ! is_array( $active_product_badges ) || ! count( $active_product_badges ) ) {
			self::$cache[ $cache_key ] = [];

			return [];
		}

		foreach ( $active_product_badges as $index => $badge ) {
			$product_badges_exclude_for = $badge['exclude_product'] ?? '';
			$exclude_for                = Fns::multiselect_settings_field_value( $product_badges_exclude_for );
			$exclude_for                = array_map( 'absint', $exclude_for );

			if ( count( $exclude_for ) && in_array( $product_id, $exclude_for, true ) ) {
				continue;
			}
			$badge_condition = $badge['badge_condition'] ?? '';
			if ( 'dynamic' == $badge_condition ) {
				$badge_for = $badge['badge_for'] ?? '';
				switch ( $badge_for ) {
					case 'on_sale':
						if ( ! $product->is_on_sale() ) {
							continue 2;
						}
						break;
					case 'featured':
						if ( ! $product->is_featured() ) {
							continue 2;
						}
						break;
					case 'out_of_stock':
						if ( $product->is_in_stock() ) {
							continue 2;
						}
						break;
					case 'best_selling':
						$minimum_sale = $badge['minimum_sale_count'] ?? 1;
						$best_selling = Fns::best_selling_products_ids( $minimum_sale );
						// phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
						if ( ! is_array( $best_selling ) || ! in_array( $product_id, $best_selling ) ) {
							continue 2;
						}
						break;
					case 'new_arrival':
						$new_days = $badge['new_arrival_days'] ?? 30;
						if ( ! Fns::is_new_product( $product, $new_days ) ) {
							continue 2;
						}
						break;
					case 'pre_order':
						$is_on_pre_order = rtsb()->has_pro() && ( PreOrderFns::is_on_pre_order( $product ) || PreOrderFns::variation_is_on_pre_order( $product ) );
						if ( ! ( Fns::is_module_active( 'pre_order' ) && $is_on_pre_order ) ) {
							continue 2;
						}
						break;
					default:
						break;
				}
			}

			$product_badges_apply_for = $badge['apply_for'] ?? '';

			if ( 'product_cat' === $product_badges_apply_for ) {
				$term_ids = [];
				$terms    = get_the_terms( $product_id, 'product_cat' );

				if ( ! is_wp_error( $terms ) ) {
					foreach ( $terms as $term ) {
						$term_ids[] = $term->term_id;
					}
				}

				$applicable_cat = $badge['applicable_categories'] ?? '';
				$applicable_cat = Fns::multiselect_settings_field_value( $applicable_cat );
				$applicable_cat = array_map( 'absint', $applicable_cat );
				$has_cat        = array_intersect( $term_ids, $applicable_cat );

				if ( is_array( $applicable_cat ) && count( $applicable_cat ) && ! count( $has_cat ) ) {
					continue;
				}
			} else {
				$applicable_products = $badge['applicable_products'] ?? '';
				$applicable_products = Fns::multiselect_settings_field_value( $applicable_products );
				$applicable_products = array_map( 'absint', $applicable_products );

				if ( is_array( $applicable_products ) && count( $applicable_products ) && ! in_array( $product_id, $applicable_products, true ) ) {
					continue;
				}
			}

			$available_badges[] = $badge;
		}

		self::$cache[ $cache_key ] = $available_badges;

		return $available_badges;
	}

	/**
	 * Is allowed badge.
	 *
	 * @return bool
	 */
	public static function is_allowed_badge_showing() {
		$badge_applied = self::get_product_badges_for_current_product();

		return is_array( $badge_applied ) && count( $badge_applied );
	}
}
