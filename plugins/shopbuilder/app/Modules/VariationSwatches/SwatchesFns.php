<?php
/**
 * Sticky add-to-cart Functions Class.
 *
 * @package Rse\SB
 */

namespace RadiusTheme\SB\Modules\VariationSwatches;

use RadiusTheme\SB\Helpers\Fns;

defined( 'ABSPATH' ) || exit();

/**
 * Sticky add-to-cart Functions Class.
 */
class SwatchesFns {
	/**
	 * @param array $args args.
	 * @return string
	 */
	public static function get_current_url( $args = [] ) {
		global $wp;
		return esc_url( trailingslashit( home_url( add_query_arg( $args, $wp->request ) ) ) );
	}
	/**
	 * @param string       $key Default Attribute.
	 * @param array|string $default Default.
	 * @return array|string
	 */
	public static function get_options( $key = null, $default = '' ) {
		$options = Fns::get_options( 'modules', 'variation_swatches' );
		if ( $key ) {
			if ( isset( $options[ $key ] ) ) {
				return $options[ $key ];
			} else {
				return $default;
			}
		}
		return $options;
	}
	/**
	 * @param string $empty Default Attribute.
	 *
	 * @return array
	 */
	public static function get_wc_attributes( $empty = '' ) {
		$list = [];
		// Guard WooCommerce functions: activation/cache rebuild can run while WooCommerce is inactive.
		if ( function_exists( 'wc_get_attribute_taxonomies' ) && function_exists( 'wc_attribute_taxonomy_name' ) ) {
			$lists = (array) wp_list_pluck( wc_get_attribute_taxonomies(), 'attribute_label', 'attribute_name' );
			foreach ( $lists as $name => $label ) {
				$list[ wc_attribute_taxonomy_name( $name ) ] = $label . " ( {$name} )";
			}
		}
		if ( $empty ) {
			$list = [ '' => $empty ] + $list;
		}
		return $list;
	}
	/**
	 * @param string $type Default Attribute.
	 *
	 * @return array
	 */
	public static function get_available_attributes_types( $type = false ) {
		$types = [
			'select' => esc_html__( 'Select', 'shopbuilder' ),
			'color'  => esc_html__( 'Color', 'shopbuilder' ),
			'image'  => esc_html__( 'Image', 'shopbuilder' ),
			'button' => esc_html__( 'Button', 'shopbuilder' ),
			'radio'  => esc_html__( 'Radio', 'shopbuilder' ),
		];

		$types = apply_filters( 'rtsb/available/attributes/types', $types );

		if ( $type ) {
			return $types[ $type ] ?? null;
		}

		return $types;
	}

	/**
	 * @param string $type Default Attribute.
	 *
	 * @return string
	 */
	public static function get_valid_product_attribute_type_from_available_types( $type ) {
		if ( ! $type ) {
			return null;
		}
		$available_types = array_keys( self::get_available_attributes_types() );
		if ( in_array( $type, $available_types, true ) || 'custom' === $type ) {
			return $type;
		}
		return null;
	}

	/**
	 * @param string $taxonomy_name Default Attribute.
	 *
	 * @return null|string
	 */
	public static function get_global_attribute_type( $taxonomy_name ) {
		$available_types = array_keys( self::get_available_attributes_types() );

		foreach ( $available_types as $type ) {
			if ( self::wc_product_has_attribute_type( $type, $taxonomy_name ) ) {
				return $type;
			}
		}
		return null;
	}
	/**
	 * @param string $taxonomy_name Default Attribute.
	 *
	 * @return array
	 */
	/**
	 * Get attribute taxonomy row from the database for a given taxonomy name.
	 *
	 * @param string $taxonomy_name Full taxonomy name (e.g., 'pa_color').
	 * @return object|null Attribute taxonomy row or null if not found.
	 */
	public static function get_wc_attribute_taxonomy( $taxonomy_name ) {
		global $wpdb;

		$attribute_name = str_replace( 'pa_', '', wc_sanitize_taxonomy_name( $taxonomy_name ) );

		$cache_key = 'wc_attr_tax_' . $attribute_name;
		$cached    = wp_cache_get( $cache_key, 'woocommerce' );

		if ( false !== $cached ) {
			return $cached;
		}

		$query = $wpdb->prepare(
			"SELECT * FROM {$wpdb->prefix}woocommerce_attribute_taxonomies WHERE attribute_name = %s",
			$attribute_name
		);
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.NotPrepared
		$attribute_taxonomy = $wpdb->get_row( $query );

		if ( $attribute_taxonomy ) {
			wp_cache_set( $cache_key, $attribute_taxonomy, 'woocommerce' );
		}

		return $attribute_taxonomy;
	}
	/**
	 * @param string $type Default Attribute.
	 * @param string $taxonomy_name Default Attribute.
	 * @return array
	 */
	public static function wc_product_has_attribute_type( $type, $taxonomy_name ) {
		$attribute = self::get_wc_attribute_taxonomy( $taxonomy_name );
		return apply_filters( 'rtsb/wc/product/has/attribute/type', ( isset( $attribute->attribute_type ) && ( $attribute->attribute_type == $type ) ), $type, $taxonomy_name, $attribute );
	}
	/**
	 * @param object $term Default Attribute.
	 * @param array  $args Args.
	 * @param array  $term_data Term Data.
	 *
	 * @return []
	 */
	private static function get_tooltip( $term, $args, $term_data ) {
		$text_tooltip  = esc_attr( $term->name );
		$image_tooltip = '';
		if ( 'on' != self::get_options( 'show_tooltip' ) ) {
			return [ $image_tooltip, $text_tooltip ];
		}
		$tooltip_type = null;
		$tooltip_data = null;
		if ( ! empty( $term_data['data'][ $term->slug ]['tooltip'] ) ) {
			$tooltip_type = $term_data['data'][ $term->slug ]['tooltip'];
			$tooltip_data = isset( $term_data['data'][ $term->slug ][ 'tooltip_' . $tooltip_type ] ) ? trim( $term_data['data'][ $term->slug ][ 'tooltip_' . $tooltip_type ] ) : null;
		} else {
			$attribute_tooltip_type = get_term_meta( $term->term_id, 'rtsb_vs_attribute_tooltip', true );
			if ( $attribute_tooltip_type ) {
				$tooltip_type = $attribute_tooltip_type;
				$tooltip_data = get_term_meta( $term->term_id, 'rtsb_vs_attribute_tooltip_' . $tooltip_type, true );
			}
		}
		if ( 'no' === $tooltip_type ) {
			return [ $image_tooltip, $text_tooltip ];
		}
		if ( rtsb()->has_pro() && 'image' === $tooltip_type && absint( $tooltip_data ) ) {
			$attachment_id = absint( $tooltip_data );
			$image_size    = sanitize_text_field( self::get_options( 'tooltip_image_size' ) );
			$image_url     = wp_get_attachment_image_url( $attachment_id, $image_size );
			$image_tooltip = apply_filters(
				'rtbs/tooltip/image',
				sprintf(
					// translators: %s: Tooltip Image source.
					'<span class="%s"><img alt="%s" src="%s"/></span>',
					'image-tooltip-wrapper',
					esc_attr( $term->name ),
					esc_url( $image_url )
				),
				$attachment_id,
				$image_url,
				$term,
				$args
			);
		}
		if ( ! $tooltip_type || 'text' === $tooltip_type ) {
			if ( $tooltip_data && rtsb()->has_pro() ) {
				$text_tooltip = trim( apply_filters( 'rtsb/variable/item/text/tooltip/text', $tooltip_data, $term, $args ) );
			} else {
				$text_tooltip = trim( apply_filters( 'rtsb/variable/item/text/tooltip', $term->name, $term, $args ) );
			}
			$text_tooltip = apply_filters(
				'rtsb/tooltip/text',
				sprintf(
					'<span class="%s">%s</span>',
					'text-tooltip-wrapper',
					$text_tooltip
				),
				$text_tooltip,
				$term,
				$args
			);
		}

		return [ $image_tooltip, $text_tooltip ];
	}
	/**
	 * @param string $type Default Attribute.
	 * @param array  $options Options.
	 * @param array  $args Args.
	 * @param array  $term_data Term Data.
	 *
	 * @return string
	 */
	public static function get_variable_items_contents( $type, $options, $args, $term_data = [] ) {
		$product          = $args['product'];
		$attribute        = $args['attribute'];
		$attr_first_image = self::swatches_attribute_first_image( $product );
		$is_showcase      = $args['is_showcase'] ?? false;
		$selected         = sanitize_title( $args['selected'] ?? '' );
		if ( empty( $options ) || ! $product ) {
			return '';
		}
		$limit = 0;
		if ( rtsb()->has_pro() ) {
			$limit = (int) ( $is_showcase ? self::get_options( 'showcase_swatches_display_limit' ) : self::get_options( 'attr_display_limit' ) );
		}
		$data          = '';
		$display_count = 0;
		$name          = uniqid( wc_variation_attribute_name( $attribute ) );
		if ( taxonomy_exists( $attribute ) ) {
			$terms = wc_get_product_terms( $product->get_id(), $attribute, [ 'fields' => 'all' ] );
			foreach ( $terms as $term ) {
				if ( ! in_array( $term->slug, $options, true ) ) {
					continue;
				}
				$args_set = compact( 'term', 'type', 'options', 'args', 'term_data', 'display_count', 'limit', 'name', 'attr_first_image', 'selected' );
				$data    .= self::render_term_attribute_item( $args_set );
				$display_count++;
			}
		} else {
			foreach ( $options as $option ) {
				$args_set = compact( 'option', 'type', 'options', 'args', 'term_data', 'display_count', 'limit', 'name', 'attr_first_image', 'selected' );
				$data    .= self::render_custom_attribute_item( $args_set );
				$display_count++;
			}
		}
		$more = ( $limit && $limit < count( $options ) ) ? count( $options ) - $limit : 0;
		if ( $more ) {
			$data .= '<div class="rtsb-term-more-btn"> <i style="font-size: 10px" class="rtsb-icon rtsb-icon-plus"></i>';
			// translators: %d: number of hidden variation items.
			$data .= '<a href="#">' . sprintf( _n( '%d more', '%d more', $more, 'shopbuilder' ), $more ) . '</a>';
			$data .= '</div>';
		}
		$contents    = apply_filters( 'rtsb/variable/term', $data, $type, $options, $args, $term_data );
		$css_classes = apply_filters( 'rtsb/variable/terms/wrapper/class', [ "$type-variable-wrapper" ], $type, $args, $term_data );
		if ( $more ) {
			$css_classes[] = 'has-more-variation';
		}

		$wrapper = sprintf(
			'<div class="rtsb-terms-wrapper %s" data-attribute_name="%s">%s</div>',
			trim( implode( ' ', array_unique( $css_classes ) ) ),
			esc_attr( wc_variation_attribute_name( $attribute ) ),
			$contents
		);

		return apply_filters( 'rtsb/variable/items/wrapper', $wrapper, $contents, $type, $args, $term_data );
	}
	/**
	 * Renders a single swatch for a taxonomy-based attribute term.
	 *
	 * Builds the swatch HTML based on the term type (e.g., color, image, etc.)
	 * and the provided metadata.
	 *
	 * @param array $args {.
	 *     @type WP_Term $term             The term object.
	 *     @type string  $type             The swatch type.
	 *     @type array   $options          All valid option slugs for the attribute.
	 *     @type array   $args             Original arguments array passed from the main function.
	 *     @type array   $term_data        Additional metadata for the term.
	 *     @type int     $display_count    Current loop iteration index.
	 *     @type int     $limit            Display limit for swatches.
	 *     @type string  $name             Unique input name for radio swatches.
	 *     @type array   $attr_first_image Optional fallback images by slug.
	 *     @type string  $selected         Selected attribute slug.
	 * }.
	 * @return string Rendered swatch HTML for the term.
	 */
	private static function render_term_attribute_item( $args ) {
		list( $term, $type, $options, $base_args, $term_data, $count, $limit, $name, $attr_first_image, $selected ) = [
			$args['term'],
			$args['type'],
			$args['options'],
			$args['args'],
			$args['term_data'],
			$args['display_count'],
			$args['limit'],
			$args['name'],
			$args['attr_first_image'],
			$args['selected'],
		];

		$slug      = $term->slug;
		$term_type = ( rtsb()->has_pro() && 'custom' === $type && ! empty( $term_data['data'][ $slug ]['type'] ) ) ? $term_data['data'][ $slug ]['type'] : $type;
		$classes   = $slug === $selected ? 'selected' : '';
		if ( 'on' === self::get_options( 'show_tooltip' ) ) {
			$classes .= ' rtsb-tipsy';
		}
		$classes .= ' rtsb-term-count-' . ( $count + 1 );
		if ( $limit && $count >= $limit ) {
			$classes .= ' rtsb-term-more';
		}
		list($image_tooltip, $text_tooltip) = self::get_tooltip( $term, $base_args, $term_data );
		$tooltip_type                       = rtsb()->has_pro() ? get_term_meta( $term->term_id, 'rtsb_vs_attribute_tooltip', true ) : 'text';
		if ( ! empty( $term_data['data'][ $term->slug ]['tooltip'] ) ) {
			$tooltip_type = $term_data['data'][ $term->slug ]['tooltip'];
		}
		$tooltip_type = $tooltip_type ?: 'text';
		$tooltip      = $text_tooltip;
		if ( rtsb()->has_pro() && ! empty( $image_tooltip ) ) {
			$tooltip = $image_tooltip;
		} else {
			$tooltip_type = 'text';
		}

		$html = sprintf( '<div class="rtsb-term rtsb-%1$s-term %1$s-variable-term-%2$s %3$s" title="%4$s" data-term="%2$s" data-theme="rtsb-vs-tooltip-%5$s">', esc_attr( $term_type ), esc_attr( $slug ), esc_attr( $classes ), esc_attr( $tooltip ), esc_attr( $tooltip_type ) );
		if ( 'radio' !== $term_type && 'on' === self::get_options( 'shape_style_checkmark' ) ) {
			$html .= '<span class="rtsb-term-checkmark"></span>';
		}
		$args  = compact( 'term_type', 'slug', 'term_data', 'base_args', 'attr_first_image', 'term' ) + [
			'label'   => $term->name,
			'term_id' => $term->term_id,
			'name'    => $name,
		];
		$html .= self::get_attribute_item_html( $args );
		$html .= '</div>';
		return $html;
	}
	/**
	 * Renders a single swatch for a custom (non-taxonomy) attribute option.
	 *
	 * Includes tooltip support, dual colors, and optional image fallback.
	 *
	 * @param array $args {.
	 *     @type string $option            The custom attribute option value.
	 *     @type string $type              The swatch type.
	 *     @type array  $options           All valid option values.
	 *     @type array  $args              Original arguments array passed from the main function.
	 *     @type array  $term_data         Additional data for custom attribute.
	 *     @type int    $display_count     Current loop iteration index.
	 *     @type int    $limit             Display limit for swatches.
	 *     @type string $name              Unique input name for radio swatches.
	 *     @type array  $attr_first_image  Optional fallback images by slug.
	 *     @type string $selected          Selected attribute option.
	 * }.
	 * @return string Rendered swatch HTML for the custom attribute.
	 */
	private static function render_custom_attribute_item( $args ) {
		list( $option, $type, $options, $base_args, $term_data, $count, $limit, $name, $attr_first_image, $selected ) = [
			$args['option'],
			$args['type'],
			$args['options'],
			$args['args'],
			$args['term_data'],
			$args['display_count'],
			$args['limit'],
			$args['name'],
			$args['attr_first_image'],
			$args['selected'],
		];

		$term_name = rawurldecode( $option );
		$term_type = ( 'custom' === $type && ! empty( $term_data['data'][ $option ]['type'] ) ) ? $term_data['data'][ $option ]['type'] : $type;
		$classes   = $option === $selected ? 'selected' : '';
		if ( 'on' === self::get_options( 'show_tooltip' ) ) {
			$classes .= ' rtsb-tipsy';
		}
		$classes .= ' rtsb-term-count-' . ( $count + 1 );
		if ( $limit && $count >= $limit ) {
			$classes .= ' rtsb-term-more';
		}
		$tooltip_html_attr = '';
		if ( ! empty( $term_data['data'][ $option ]['tooltip'] ) ) {
			$type = $term_data['data'][ $option ]['tooltip'];
			$data = $term_data['data'][ $option ][ 'tooltip_' . $type ] ?? '';
			if ( 'image' === $type && $data ) {
				$image_url     = wp_get_attachment_image_url( absint( $data ), self::get_options( 'tooltip_image_size' ) );
				$image_tooltip = apply_filters(
					'rtbs/custom/attribute/tooltip/image',
					sprintf(
					// translators: %s: Tooltip Image source.
						'<span class="%s"><img alt="%s" src="%s"/></span>',
						'image-tooltip-wrapper',
						esc_attr( $term_name ),
						esc_url( $image_url )
					),
					$data,
					$image_url,
					$option,
					$args
				);
				$tooltip_html_attr = sprintf( 'title="%s"', esc_attr( $image_tooltip ) );
			} elseif ( 'text' === $type ) {
				$tooltip_html_attr = sprintf( 'title="%s"', esc_attr( $data ) );
			}
		}

		$html = sprintf( '<div %1$s class="rtsb-term rtsb-%2$s-term %2$s-variable-term-%3$s %4$s" data-term="%3$s" data-theme="rtsb-vs-tooltip-%5$s">', $tooltip_html_attr, esc_attr( $term_type ), esc_attr( $term_name ), esc_attr( $classes ), esc_attr( $type ) );
		if ( 'radio' !== $term_type && 'on' === self::get_options( 'shape_style_checkmark' ) ) {
			$html .= '<span class="rtsb-term-checkmark"></span>';
		}
		$args  = compact( 'term_type', 'term_data', 'base_args', 'attr_first_image' ) + [
			'slug'    => $option,
			'label'   => $term_name,
			'term_id' => 0,
			'name'    => $name,
		];
		$html .= self::get_attribute_item_html( $args );
		$html .= '</div>';
		return $html;
	}
	/**
	 * Renders the HTML for a single attribute item (color, image, button, or radio).
	 *
	 * @param array $args {.
	 *     Required. An array of arguments to render the attribute item.
	 *
	 *     @type string $term_type         The type of the term (e.g., 'color', 'image', 'button', 'radio').
	 *     @type string $slug              The term slug or custom option key.
	 *     @type string $label             The label/name of the term or option.
	 *     @type array  $term_data         Term data array, usually includes color, image, etc.
	 *     @type array  $base_args         Original arguments passed to the wrapper function.
	 *     @type array  $attr_first_image  Array of first images for fallback (term_slug => image_url).
	 *     @type int    $term_id           Term ID. 0 for custom attributes.
	 *     @type string $name              Unique input name for radio buttons.
	 * }.
	 *
	 * @return string Rendered HTML output for the attribute item.
	 */
	private static function get_attribute_item_html( $args ) {
		list( $term_type, $slug, $label, $term_data, $base_args, $attr_first_image, $term_id, $name ) = [
			$args['term_type'] ?? '',
			$args['slug'] ?? '',
			$args['label'] ?? '',
			$args['term_data'] ?? [],
			$args['base_args'] ?? [],
			$args['attr_first_image'] ?? [],
			$args['term_id'] ?? 0,
			$args['name'] ?? '',
		];

		$html = '';

		switch ( $term_type ) {
			case 'color':
				if ( $term_id ) {
					// For taxonomy attribute (term ID is present).
					$global_color           = sanitize_hex_color( get_term_meta( $term_id, 'rtsb_vs_product_attribute_color', true ) );
					$global_secondary_color = sanitize_hex_color( get_term_meta( $term_id, 'rtsb_vs_secondary_color', true ) );
					$global_is_dual         = rtsb()->has_pro() && get_term_meta( $term_id, 'rtsb_vs_is_dual_color', true ) === 'yes';
					$color                  = $global_color;
					if ( ! empty( $term_data['data'][ $slug ]['color'] ) ) {
						$color = sanitize_hex_color( $term_data['data'][ $slug ]['color'] );
					}
					$secondary_color = $global_secondary_color;
					if ( ! empty( $term_data['data'][ $slug ]['secondary_color'] ) ) {
						$secondary_color = sanitize_hex_color( $term_data['data'][ $slug ]['secondary_color'] );
					}
					$is_dual = $global_is_dual;
					if ( ! empty( $term_data['data'][ $slug ]['is_dual_color'] ) ) {
						$is_dual = 'yes' === $term_data['data'][ $slug ]['is_dual_color'];
					}
				} else {
					// For custom attribute (term ID = 0).
					$color           = ! empty( $term_data['data'][ $slug ]['color'] ) ? sanitize_hex_color( $term_data['data'][ $slug ]['color'] ) : '';
					$secondary_color = ! empty( $term_data['data'][ $slug ]['secondary_color'] ) ? sanitize_hex_color( $term_data['data'][ $slug ]['secondary_color'] ) : '';
					$is_dual         = ! empty( $term_data['data'][ $slug ]['is_dual_color'] ) && 'yes' === $term_data['data'][ $slug ]['is_dual_color'];
				}
				// Render color HTML.
				if ( $is_dual && $secondary_color ) {
					$html .= sprintf(
						'<span class="rtsb-term-span rtsb-term-span-%1$s rtsb-term-span-dual-color" style="background: linear-gradient(-45deg, %2$s 0%%, %2$s 50%%, %3$s 50%%, %3$s 100%%);"></span>',
						esc_attr( $term_type ),
						esc_attr( $secondary_color ),
						esc_attr( $color )
					);
				} else {
					$html .= sprintf(
						'<span class="rtsb-term-span rtsb-term-span-%1$s" style="background-color:%2$s;"></span>',
						esc_attr( $term_type ),
						esc_attr( $color )
					);
				}
				$html = '<span class="rtsb-term-span">' . $html . '</span>';
				break;
			case 'image':
				// Per-product override (PRO) always wins.
				if ( ! empty( $term_data['data'][ $slug ]['image'] ) ) {
					$attachment_id = absint( $term_data['data'][ $slug ]['image'] );
				} else {
					// Legacy per-term meta (rtsb_vs_product_attribute_image)
					// only applies when the attribute's CURRENT global swatch
					// type is still "image". If the admin changed the
					// attribute type to "select" and is relying on the
					// "Default Dropdown Convert" setting to render images,
					// the legacy meta is stale; ignore it so the
					// variation-image fallback below takes over.
					$taxonomy             = ( isset( $args['term'] ) && $args['term'] instanceof \WP_Term )
						? $args['term']->taxonomy
						: '';
					$is_legacy_image_attr = $term_id && $taxonomy
						&& self::wc_product_has_attribute_type( 'image', $taxonomy );
					$attachment_id        = $is_legacy_image_attr
						? absint( get_term_meta( $term_id, 'rtsb_vs_product_attribute_image', true ) )
						: 0;
				}

				$image_size = self::get_options( 'attribute_image_size' );
				$image_url  = wp_get_attachment_image_url( $attachment_id, apply_filters( 'rtsb/product/attribute/image/size', $image_size ) );

				if ( ! $image_url ) {
					$image_url = $attr_first_image[ $slug ] ?? '';
				}

				if ( $image_url ) {
					$html .= sprintf(
						'<span class="rtsb-term-span rtsb-term-span-%1$s"><img alt="%2$s" src="%3$s" /></span>',
						esc_attr( $term_type ),
						esc_attr( $label ),
						esc_url( $image_url )
					);
				}
				break;

			case 'button':
				$html .= sprintf(
					'<span class="rtsb-term-span rtsb-term-span-%1$s">%2$s</span>',
					esc_attr( $term_type ),
					esc_html( $label )
				);
				break;

			case 'radio':
				$id    = uniqid( $slug );
				$html .= sprintf(
					'<input name="%1$s" id="%2$s" class="rtsb-radio-button-term" %3$s type="radio" value="%4$s" data-term="%4$s" /><label for="%2$s">%5$s</label>',
					esc_attr( $name ),
					esc_attr( $id ),
					checked( sanitize_title( $base_args['selected'] ?? '' ), $slug, false ),
					esc_attr( $slug ),
					esc_html( $label )
				);
				break;

			default:
				$html .= apply_filters(
					'rtsb/variable/default/item/content',
					'',
					(object) [
						'slug'    => $slug,
						'name'    => $label,
						'term_id' => $term_id,
					],
					$base_args,
					$term_data
				);
				break;
		}

		return $html;
	}
	/**
	 * Attribute with image.
	 *
	 * @param [type] $product  the parent product.
	 * @return array
	 */
	public static function swatches_attribute_first_image( $product ) {
		$attributes_image = [];
		$variation_ids    = $product->get_children();
		$image_size       = self::get_options( 'attribute_image_size' );
		$image_size       = apply_filters( 'rtsb/product/attribute/image/size', $image_size );
		foreach ( $variation_ids as $variation_id ) {
			$variation            = wc_get_product( $variation_id );
			$variation_attributes = array_values( $variation->get_variation_attributes() );
			$image_url            = wp_get_attachment_image_url( $variation->get_image_id(), $image_size );
			$fill_keys            = array_fill_keys( $variation_attributes, $image_url );
			$diff                 = array_diff_key( $fill_keys, $attributes_image );
			$attributes_image     = array_merge( $attributes_image, $diff );
		}
		return $attributes_image;
	}

	/**
	 * @param bool|string $field_id String.
	 *
	 * @return array|mixed|null
	 */
	public static function get_taxonomy_meta_fields( $field_id = false ) {
		$fields          = [];
		$common_fields   = apply_filters( 'rtsb/taxonomy/common/fields', [] );
		$fields['color'] = array_merge(
			apply_filters(
				'rtsb/get/taxonomy/meta/color',
				[
					[
						'label' => esc_html__( 'Color', 'shopbuilder' ),
						'desc'  => esc_html__( 'Choose a color', 'shopbuilder' ),
						'id'    => 'rtsb_vs_product_attribute_color',
						'type'  => 'color',
					],
				]
			),
			$common_fields
		);

		$fields['image']  = array_merge(
			[
				[
					'label' => esc_html__( 'Image', 'shopbuilder' ),
					'desc'  => esc_html__( 'Choose an Image', 'shopbuilder' ),
					'id'    => 'rtsb_vs_product_attribute_image',
					'type'  => 'image',
				],
			],
			$common_fields
		);
		$fields['button'] = $common_fields;
		$fields['radio']  = $common_fields;
		$fields           = apply_filters( 'rtsb/get/product/taxonomy/meta/fields', $fields );
		if ( $field_id ) {
			return $fields[ $field_id ] ?? [];
		}

		return $fields;
	}

	/**
	 * Generate variation attribute option html.
	 *
	 * @param array  $args options.
	 * @param string $html html.
	 * @return mixed|null
	 */
	public static function generate_variation_attribute_option_html( $args, $html = '' ) {

		$args = wp_parse_args(
			$args,
			[
				'options'          => false,
				'attribute'        => false,
				'product'          => false,
				'selected'         => '',
				'is_showcase'      => false,
				'name'             => '',
				'id'               => '',
				'class'            => '',
				'meta_data'        => [],
				'show_option_none' => esc_html__( 'Choose an option', 'shopbuilder' ),
			]
		);

		$args['selected'] = $args['selected'] ?? '';
		$attribute        = $args['attribute'];
		$attribute_id     = wc_variation_attribute_name( $attribute );
		$product          = $args['product'];
		$product_id       = $product->get_id();
		$meta_data        = rtsb()->has_pro() && empty( $args['meta_data'] ) ? get_post_meta( $product_id, '_rtsb_vs', true ) : $args['meta_data'];
		$attribute_type   = ! empty( $meta_data[ $attribute ]['type'] ) ? $meta_data[ $attribute ]['type'] : null;
		$attribute_type   = $attribute_type ? self::get_valid_product_attribute_type_from_available_types( $attribute_type ) : null;

		$global_attribute_type = self::get_global_attribute_type( $attribute );
		$type                  = $attribute_type ?: $global_attribute_type;
		// Show by default button.
		if ( empty( $meta_data[ $attribute ]['type'] ) && ( ! $type || 'select' === $type ) ) {
			$convert_type = self::get_options( 'default_dropdown_convert' );
			$type         = $convert_type ?: $type;
		}
		$args['attribute_type'] = $type;
		$transient_html         = '';

		if ( $type ) {
			$options               = $args['options'];
			$term_data             = $meta_data[ $attribute ] ?? [];
			$name                  = $args['name'] ?: wc_variation_attribute_name( $attribute );
			$id                    = $args['id'] ?: sanitize_title( $attribute );
			$class                 = $args['class'];
			$show_option_none      = (bool) $args['show_option_none'];
			$show_option_none_text = $args['show_option_none'] ?: esc_html__( 'Choose an option', 'shopbuilder' );

			if ( empty( $options ) && ! empty( $product ) && ! empty( $attribute ) ) {
				$attributes = $product->get_variation_attributes();
				$options    = $attributes[ $attribute ];
			}
			if ( 'select' === $type ) {
				$transient_html .= '<div class="rtsb-terms-wrapper select-variable-wrapper" data-attribute_name="' . esc_attr( wc_variation_attribute_name( $attribute ) ) . '">';
			}
			// I issue Found For The id Then need to change data-id to id.
			$transient_html .= '<select data-id="' . esc_attr( $id ) . '" class="' . esc_attr( $class ) . '  rtsb-wc-select rtsb-wc-type-' . esc_attr( $type ) . '" style="display:' . ( 'select' === $type ? 'block;' : 'none;' ) . '" name="' . esc_attr( $name ) . '" data-attribute_name="' . esc_attr( wc_variation_attribute_name( $attribute ) ) . '" data-show_option_none="' . ( $show_option_none ? 'yes' : 'no' ) . '">';

			if ( $args['show_option_none'] ) {
				$transient_html .= '<option value="">' . esc_html( $show_option_none_text ) . '</option>';
			}
			if ( ! empty( $options ) ) {
				if ( $product && taxonomy_exists( $attribute ) ) {
					$terms = wc_get_product_terms( $product->get_id(), $attribute, [ 'fields' => 'all' ] );

					foreach ( $terms as $term ) {
						if ( in_array( $term->slug, $options, true ) ) {
							$transient_html .= '<option value="' . esc_attr( $term->slug ) . '" ' . selected( sanitize_title( $args['selected'] ), $term->slug, false ) . '>' . esc_html( apply_filters( 'woocommerce_variation_option_name', $term->name ) ) . '</option>';
						}
					}
				} else {
					foreach ( $options as $option ) {
						// This handles < 2.4.0 bw compatibility where text attributes were not sanitized.
						$selected        = sanitize_title( $args['selected'] ) === $args['selected'] ? selected( $args['selected'], sanitize_title( $option ), false ) : selected( $args['selected'], $option, false );
						$transient_html .= '<option value="' . esc_attr( $option ) . '" ' . $selected . '>' . esc_html( apply_filters( 'woocommerce_variation_option_name', $option ) ) . '</option>';
					}
				}
			}

			$transient_html .= '</select>';
			if ( 'select' === $type ) {
				$transient_html .= '</div>';
			} else {
				$transient_html .= self::get_variable_items_contents( $type, $options, $args, $term_data );
			}
		} else {
			$transient_html = $html;
		}
		return apply_filters( 'rtsb/variation/attribute/options/html', $transient_html, $args );
	}
}
