<?php
/**
 * Class to build up query args.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Models;

// Do not allow directly accessing this file.
use RadiusTheme\SB\Helpers\Fns;

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Query Args Class
 */
class QueryArgs {

	/**
	 * Query Args.
	 *
	 * @var array
	 */
	private $args = [];

	/**
	 * Meta values.
	 *
	 * @var array
	 */
	private $meta = [];

	/**
	 * Method to build args
	 *
	 * @param array  $meta Meta values.
	 * @param string $type Query type.
	 * @param bool   $isCarousel Layout type.
	 *
	 * @return array
	 */
	public function buildArgs( array $meta, string $type = 'product', bool $isCarousel = false ) {
		$this->meta = $meta;

		if ( 'product' === $type ) {
			$this
				->post_params()
				->order_params()
				->pagination_params( $isCarousel )
				->tax_params();

		} else {
			$this
				->get_tax_type()
				->cat_params()
				->cat_order_params();
		}

		return apply_filters( 'rtsb/elements/' . $type . '_query_args', $this->args, $this->meta );
	}

	/**
	 * Post type.
	 *
	 * @return QueryArgs
	 */
	private function get_tax_type() {
		$this->args['taxonomy']     = [ 'product_cat' ];
		$this->args['post_status']  = 'publish';
		$this->args['hierarchical'] = 1;

		return $this;
	}

	/**
	 * WC Post parameters.
	 *
	 * @return QueryArgs
	 */
	private function post_params() {
		$post_in     = isset( $this->meta['post_in'] ) ? sanitize_text_field( implode( ',', $this->meta['post_in'] ) ) : null;
		$post_not_in = isset( $this->meta['post_not_in'] ) ? sanitize_text_field( implode( ', ', $this->meta['post_not_in'] ) ) : null;
		$author_in   = isset( $this->meta['author_in'] ) ? sanitize_text_field( implode( ',', $this->meta['author_in'] ) ) : null;
		$limit       = ( ( empty( $this->meta['limit'] ) || '-1' === $this->meta['limit'] ) ? 10000000 : absint( $this->meta['limit'] ) );
		$offset      = ! empty( $this->meta['offset'] ) ? absint( $this->meta['offset'] ) : 0;

		if ( $post_in ) {
			$post_in               = explode( ',', $post_in );
			$this->args['include'] = $post_in;
		}

		if ( $post_not_in ) {
			$post_not_in           = explode( ',', $post_not_in );
			$this->args['exclude'] = $post_not_in; // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
		}

		if ( $author_in ) {
			$this->args['author'] = $author_in;
		}

		$this->args['limit'] = $limit;

		if ( $offset ) {
			$this->args['offset'] = $offset;
		}

		return $this;
	}

	/**
	 * Category parameters.
	 *
	 * @return QueryArgs
	 */
	private function cat_params() {
		$cats_in     = isset( $this->meta['include_cats'] ) ? sanitize_text_field( implode( ', ', $this->meta['include_cats'] ) ) : null;
		$cat_ids_in  = isset( $this->meta['select_cat_ids'] ) ? esc_html( $this->meta['select_cat_ids'] ) : null;
		$cats_not_in = isset( $this->meta['exclude_cats'] ) ? sanitize_text_field( implode( ', ', $this->meta['exclude_cats'] ) ) : null;
		$limit       = ( ( empty( $this->meta['cats_limit'] ) || '-1' === $this->meta['cats_limit'] ) ? 10000000 : absint( $this->meta['cats_limit'] ) );

		if ( $cats_in ) {
			$cats_in = explode( ',', $cats_in );
		}

		if ( $cat_ids_in ) {
			$cats_in = explode( ',', $cat_ids_in );
		}

		if ( ! $this->meta['show_uncategorized'] ) {
			$cats_not_in .= ',' . get_option( 'default_product_cat', 0 );
		}

		if ( $cats_not_in ) {
			$cats_not_in           = array_filter( explode( ',', $cats_not_in ) );
			$this->args['exclude'] = $cats_not_in; // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
		}

		$this->args['number']     = $limit;
		$this->args['hide_empty'] = $this->meta['show_empty'] ? 0 : 1;

		if ( ! $this->meta['show_subcats'] ) {
			$this->args['parent'] = 0;
		}

		switch ( $this->meta['display_cat_by'] ) {
			case 'specific_parent':
				unset( $this->args['exclude'] );
				unset( $this->args['parent'] );

				if ( $this->meta['show_top_level_cats'] ) {
					$this->args['parent'] = absint( $this->meta['select_parent_cat'] );
				} else {
					$this->args['child_of'] = absint( $this->meta['select_parent_cat'] );
				}
				break;
			case 'cat_ids':
			case 'selection':
				unset( $this->args['exclude'] );
				unset( $this->args['parent'] );

				$this->args['include'] = $cats_in;
				break;
			default:
				break;
		}

		return $this;
	}

	/**
	 * WC Order & Orderby parameters.
	 *
	 * @return QueryArgs
	 */
	private function order_params() {
		$order_by = ( isset( $this->meta['order_by'] ) ? esc_html( $this->meta['order_by'] ) : null );
		$order    = ( isset( $this->meta['order'] ) ? esc_html( $this->meta['order'] ) : null );

		if ( $order ) {
			$this->args['order'] = $order;
		}

		if ( $order_by ) {
			$this->args['orderby'] = $order_by;

			if ( 'menu_order' === $order_by ) {
				$this->args['orderby'] = 'menu_order title';
			}

			if ( 'price' === $order_by ) {
				$this->args['orderby']  = 'meta_value_num';
				$this->args['meta_key'] = '_price'; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			}
		}

		return $this;
	}

	/**
	 * Order & Orderby parameters.
	 *
	 * @return QueryArgs
	 */
	private function cat_order_params() {
		$order_by = ( isset( $this->meta['cats_order_by'] ) ? esc_html( $this->meta['cats_order_by'] ) : 'name' );
		$order    = ( isset( $this->meta['cats_order'] ) ? esc_html( $this->meta['cats_order'] ) : 'DESC' );

		if ( $order ) {
			$this->args['order'] = $order;
		}

		if ( $order_by ) {
			$this->args['orderby'] = $order_by;
		}

		if ( 'menu_order' === $order_by ) {
			$this->args['menu_order'] = $order;
		}

		return $this;
	}

	/**
	 * Pagination parameters.
	 *
	 * @param bool $isCarousel Layout type.
	 *
	 * @return QueryArgs
	 */
	private function pagination_params( $isCarousel ) {
		$pagination = ! empty( $this->meta['pagination'] );
		$limit      = ( ( empty( $this->meta['limit'] ) || '-1' === $this->meta['limit'] ) ? 10000000 : absint( $this->meta['limit'] ) );

		if ( $pagination ) {
			unset( $this->args['offset'] );

			$posts_per_page = ( ! empty( $this->meta['posts_per_page'] ) ? absint( $this->meta['posts_per_page'] ) : $limit );

			if ( $posts_per_page > $limit ) {
				$posts_per_page = $limit;
			}

			$this->args['paginate'] = true;
			$this->args['limit']    = $posts_per_page;

			// Prefer `paged` (archive pagination), fall back to `page` (paginated singular posts).
			// Handles the case where the shop/archive is set as the front page.
			$paged = get_query_var( 'paged' );
			if ( ! $paged ) {
				$paged = get_query_var( 'page' );
			}
			if ( ! $paged ) {
				$paged = 1;
			}

			$offset             = $posts_per_page * ( (int) $paged - 1 );
			$this->args['page'] = $paged;

			if ( absint( $this->args['limit'] ) > $limit - $offset ) {
				$this->args['limit']  = $limit - $offset;
				$this->args['offset'] = $offset;
			}
		}

		if ( $isCarousel ) {
			$this->args['limit'] = $limit;
		}

		return $this;
	}

	/**
	 * Taxonomy parameters.
	 *
	 * @return void
	 */
	private function tax_params(): void {
		$categories = isset( $this->meta['categories'] ) ? array_filter( $this->meta['categories'] ) : [];
		$brands     = isset( $this->meta['brands'] ) ? array_filter( $this->meta['brands'] ) : [];
		$tags       = isset( $this->meta['tags'] ) ? array_filter( $this->meta['tags'] ) : [];
		$attributes = isset( $this->meta['attributes'] ) ? array_filter( $this->meta['attributes'] ) : [];

		if ( ! empty( $categories ) ) {
			$this->args['product_category_id'] = $categories;
		}

		if ( ! empty( $brands ) ) {
			$this->args['product_brand_id'] = $brands;
		}

		if ( ! empty( $tags ) ) {
			$this->args['product_tag_id'] = $tags;
		}

		if ( ! empty( $attributes ) ) {
			$this->args['product_attribute_id'] = $attributes;
		}
	}
}
