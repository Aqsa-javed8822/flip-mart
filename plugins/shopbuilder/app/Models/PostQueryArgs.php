<?php
/**
 * Class to build up post query args.
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
 * Class PostQueryArgs
 *
 * Builds post query arguments for product queries.
 *
 * @package RadiusTheme\SB
 * Builds query arguments for fetching posts in ShopBuilder widgets.
 */
class PostQueryArgs {
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
	 * @param array $meta Meta values.
	 * @return array
	 */
	public function buildArgs( array $meta ) {
		$this->meta = $meta;
		$this->getPostType();
		$this->post_params()
			->include_exclude()
			->order()
			->pagination()
			->tax_params();

		return apply_filters( 'rtsb/elements/post_query_args', $this->args, $this->meta );
	}
	/**
	 * Post type.
	 *
	 * @return void
	 */
	private function getPostType() {
		$this->args['post_type']   = [ 'post' ];
		$this->args['post_status'] = 'publish';
	}
	/**
	 * Set the post type to "post" for blog queries.
	 *
	 * @return $this
	 */
	private function post_params() {
		$limit                        = ( ( empty( $this->meta['limit'] ) || '-1' === $this->meta['limit'] ) ? 10000000 : absint( $this->meta['limit'] ) );
		$offset                       = ! empty( $this->meta['offset'] ) ? absint( $this->meta['offset'] ) : 0;
		$this->args['posts_per_page'] = $limit;

		if ( $offset ) {
			$this->args['offset'] = $offset;
		}

		return $this;
	}
	/**
	 * Add include and exclude post IDs to the query.
	 *
	 * @return $this
	 */
	private function include_exclude() {
		if ( ! empty( $this->meta['post_in'] ) ) {
			$this->args['post__in'] = array_map( 'intval', (array) $this->meta['post_in'] );
		}

		if ( ! empty( $this->meta['post_not_in'] ) ) {
            // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_post__not_in
			$this->args['post__not_in'] = array_map( 'intval', (array) $this->meta['post_not_in'] );
		}

		return $this;
	}
	/**
	 * Set ordering parameters.
	 *
	 * @return $this
	 */
	private function order() {
		$order_by = ( isset( $this->meta['order_by'] ) ? esc_html( $this->meta['order_by'] ) : null );
		$order    = ( isset( $this->meta['order'] ) ? esc_html( $this->meta['order'] ) : null );
		if ( $order ) {
			$this->args['order'] = $order;
		}

		if ( $order_by ) {
			$this->args['orderby'] = $order_by;

		}

		return $this;
	}

	/**
	 * Set pagination parameters.
	 *
	 * @return $this
	 */
	private function pagination() {
		$pagination = ! empty( $this->meta['pagination'] );
		$limit      = ( ( empty( $this->meta['limit'] ) || '-1' === $this->meta['limit'] ) ? 10000000 : absint( $this->meta['limit'] ) );

		if ( $pagination ) {
			unset( $this->args['offset'] );

			$posts_per_page = ( ! empty( $this->meta['posts_per_page'] ) ? absint( $this->meta['posts_per_page'] ) : $limit );

			if ( $posts_per_page > $limit ) {
				$posts_per_page = $limit;
			}
			$this->args['posts_per_page'] = $posts_per_page;

			// Prefer `paged` (archive pagination), fall back to `page` (paginated singular posts).
			// Handles the case where the shop/archive is set as the front page.
			$paged = get_query_var( 'paged' );
			if ( ! $paged ) {
				$paged = get_query_var( 'page' );
			}
			if ( ! $paged ) {
				$paged = 1;
			}

			$offset              = $posts_per_page * ( (int) $paged - 1 );
			$this->args['paged'] = $paged;

			if ( absint( $this->args['posts_per_page'] ) > $limit - $offset ) {
				$this->args['posts_per_page'] = $limit - $offset;
				$this->args['offset']         = $offset;
			}
		}

		return $this;
	}

	/**
	 * Add taxonomy-related parameters (categories, tags).
	 *
	 * @return $this
	 */
	private function tax_params() {
		$categories = isset( $this->meta['categories'] ) ? array_filter( $this->meta['categories'] ) : [];
		$tags       = isset( $this->meta['tags'] ) ? array_filter( $this->meta['tags'] ) : [];
		$relation   = $this->meta['relation'] ?? 'AND';

		if ( ! empty( $categories ) || ! empty( $tags ) ) {
			$tax_query = [ 'relation' => $relation ];

			if ( ! empty( $categories ) ) {
				$tax_query[] = [
					'taxonomy' => 'category',
					'field'    => 'term_id',
					'terms'    => $categories,
					'operator' => 'IN',
				];
			}

			if ( ! empty( $tags ) ) {
				$tax_query[] = [
					'taxonomy' => 'post_tag',
					'field'    => 'term_id',
					'terms'    => $tags,
					'operator' => 'IN',
				];
			}
			$this->args['tax_query'] = $tax_query; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
		}

		return $this;
	}
}
