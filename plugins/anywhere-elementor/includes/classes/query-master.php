<?php
namespace Aepro\Classes;

use Aepro\Aepro;
use Elementor\Plugin;
use Elementor\Controls_Manager;
use Aepro\Post_Helper;

class QueryMaster {

	public $settings = [];

	public $filter_slug = 'generic';

	public function __construct( $settings ) {

		$this->settings = $settings;
	}

	public function get_posts() {

		$query_args = $this->build_query();

		$settings = $this->settings;
		// Run Generic Filter
		$query_args = apply_filters( 'aepro/' . $this->filter_slug . '/custom-source-query', $query_args, $settings );

		// Run Query Filter
		/**
		 * Filter - Add Custom Source Query
		 */
		$post_type = $settings['source'];
		if ( $post_type === 'current_loop' && ! Plugin::instance()->editor->is_edit_mode() ) {
			$query_args = null;
			global $wp_query;
			$main_query = clone $wp_query;
			$posts      = $main_query;
		} else {
			if ( isset( $query_args ) ) {
				if ( $post_type !== 'ae_preset' && ! empty( $settings['query_filter'] ) ) {
					$query_args = apply_filters( $settings['query_filter'], $query_args );
				}
			}

			$posts = new \WP_Query( $query_args );
		}

		// Return
		return $posts;
	}

	public function build_query() {
		$settings   = $this->settings;
		$source     = $this->settings['source'];
		$query_args = [];

		switch ( $source ) {
			case 'current_loop':
				if ( ! Plugin::instance()->editor->is_edit_mode() ) {
											global $wp_query;
											$main_query = clone $wp_query;
											$post_items = $main_query;

				} else {
					$query_args = $this->get_preview_query();
				}
				break;

			case 'manual_selection':
				$query_args = $this->manual_selection_query();
				break;

			case 'related':
				$query_args = $this->related_query();
				break;

			case 'relation':
				$query_args = $this->relation_query();
				break;

			case 'post_object':
				$query_args = $this->post_object_query();
				break;

			case 'ae_preset':
				$preset_id = absint( $settings['ae_preset_query_id'] ?? 0 );
				if ( $preset_id ) {
					$query_args = \Aepro\Admin\PresetQuery\Registry::instance()->get_query_args( $preset_id );
				}
				break;

			default:
				$query_args = $this->post_query();
				break;
		}
		if ( $source !== 'current_loop' && $source !== 'manual_selection' && $source !== 'ae_preset' ) {
			if ( is_array( $settings['include_author_ids'] ) && count( $settings['include_author_ids'] ) ) {
				$authors = $this->include_authors();
				if ( isset( $authors ) && ! empty( $authors ) ) {
					$query_args['author__in'] = $authors;
				} else {
					$query_args['author__in'] = [ 0 ];
				}
			}
			if ( is_array( $settings['exclude_author_ids'] ) && count( $settings['exclude_author_ids'] ) ) {
				$authors = $this->exclude_authors();
				if ( isset( $authors ) && ! empty( $authors ) ) {
					$query_args['author__not_in'] = $authors;
				}
			}
		}

		return $query_args;
	}

	public function include_authors() {
		$settings = $this->settings;
			// Include Author
			$author_ids = $settings['include_author_ids'];
			//phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
		if ( in_array( 'current_author', $author_ids ) ) {
			//phpcs:ignore WordPress.Security.NonceVerification.Missing
			if ( isset( $_POST['fetch_mode'] ) ) {
				//phpcs:ignore WordPress.Security.NonceVerification.Missing
				$cpost_id = $_POST['pid'];
				$cpost    = get_post( $cpost_id );
			} else {
				$cpost    = Aepro::$_helper->get_demo_post_data();
				$cpost_id = $cpost->ID;
			}

			$current_author_id = $cpost->post_author;
			$index             = 0;
			foreach ( $author_ids as $id ) {
				if ( $id === 'current_author' ) {
					break;
				}
				$index++;
			}
			$author_ids[ $index ] = $current_author_id;
		}
		//phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
		if ( in_array( 'logged_in_author', $author_ids ) ) {
			if ( get_current_user_id() ) {
				$author_ids[] = get_current_user_id();
			}
			//phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
			$key = array_search( 'logged_in_author', $author_ids );
			unset( $author_ids[ $key ] );
		}

			$author_ids = implode( ',', $author_ids );
			return $author_ids;
	}
	public function exclude_authors() {
		$settings = $this->settings;
		//Exclude By Author
		$author_ids = $settings['exclude_author_ids'];
		//phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
		if ( in_array( 'current_author', $author_ids ) ) {
			//phpcs:ignore WordPress.Security.NonceVerification.Missing
			if ( isset( $_POST['fetch_mode'] ) ) {
				//phpcs:ignore WordPress.Security.NonceVerification.Missing
				$cpost_id = $_POST['pid'];
				$cpost    = get_post( $cpost_id );
			} else {
				$cpost    = Aepro::$_helper->get_demo_post_data();
				$cpost_id = $cpost->ID;
			}
			$current_author_id = $cpost->post_author;
			$index             = 0;
			foreach ( $author_ids as $id ) {
				if ( $id === 'current_author' ) {
					break;
				}
				$index++;
			}
			$author_ids[ $index ] = $current_author_id;
		}
		//phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
		if ( in_array( 'logged_in_author', $author_ids ) ) {
			if ( get_current_user_id() ) {
				$author_ids[] = get_current_user_id();
			}
			//phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
			$key = array_search( 'logged_in_author', $author_ids );
			unset( $author_ids[ $key ] );
		}
		$author_ids = implode( ',', $author_ids );
		return $author_ids;
	}

	public function post_query() {
		$settings = $this->settings;
		$paged    = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : '';

		$paged                     = $this->get_current_page_num();
		$query_args['post_status'] = 'publish'; // Hide drafts/private posts for admins
		$query_args['post_type']   = $settings['source'];

		// Taxonomy Parameters (Taxonomy Query )
		$selected_terms = [];
		$selected_tax   = [];
		$post_type      = $settings['source'];
		if ( isset( $settings[ $post_type . '_tax_ids' ] ) ) {
			$selected_tax = $settings[ $post_type . '_tax_ids' ];
		}

		/* Filter Bar */
		// When there is Term Id in $_POST
		if ( isset( $settings['filter_taxonomy'] ) ) {
			//phpcs:ignore WordPress.Security.NonceVerification.Missing
			if ( isset( $settings['filter_taxonomy'] ) && ( isset( $_POST['term_id'] ) && $_POST['term_id'] >= 0 ) ) {
				//phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.PHP.StrictComparisons.LooseComparison
				if ( $_POST['term_id'] != 0 ) {
					$selected_terms['relation'] = 'AND';
					$selected_terms[]           = [
						'taxonomy' => $settings['filter_taxonomy'],
						'field'    => 'term_id',
						//phpcs:ignore WordPress.Security.NonceVerification.Missing
						'terms'    => $_POST['term_id'],
					];
				}
				// When there is default term selected
				//phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison
			} elseif ( isset( $settings[ $settings['filter_taxonomy'] . '_filter_default_term' ] ) && $settings[ $settings['filter_taxonomy'] . '_filter_default_term' ] !== '' && $settings[ $settings['filter_taxonomy'] . '_filter_default_term' ] != 0 ) {
				$selected_terms['relation'] = 'AND';
				$selected_terms[]           = [
					'taxonomy' => $settings['filter_taxonomy'],
					'field'    => 'term_id',
					'terms'    => $settings[ $settings['filter_taxonomy'] . '_filter_default_term' ],
				];
				// When there is 'All' tab is disabled
			} elseif ( isset( $settings['show_all'] ) && $settings['show_all'] !== 'yes' ) {
				$filter_terms               = Aepro::$_helper->get_filter_bar_filters( $settings );
				$selected_terms['relation'] = 'AND';
				$selected_terms[]           = [
					'taxonomy' => $settings['filter_taxonomy'],
					'field'    => 'term_id',
					'terms'    => $filter_terms[array_key_first($filter_terms)]->term_id,
				];
				// When there is 'All' tab is disabled
			}
		}
		/* Filter Bar End */

		if ( is_array( $selected_tax ) && count( $selected_tax ) ) {
			$selected_tax_terms = [];
			$terms = [];
			$tax_relation = '';
			foreach ( $selected_tax as $tax ) {
				//Include by Terms
				if(isset($settings[ $tax . '_' . $post_type . '_include_term_ids' ])){
					$terms         = $settings[ $tax . '_' . $post_type . '_include_term_ids' ];
				}
				if(isset($settings[ $tax . '_' . $post_type . '_term_operator' ])){
					$term_operator  = $settings[ $tax . '_' . $post_type . '_term_operator' ];
				}

				$tax_relation  = $settings[ $post_type . '_tax_relation' ];

				if ( is_array( $terms ) && count( $terms ) ) {
					$selected_tax_terms['relation'] = $tax_relation;
					$selected_tax_terms[]           = [
						'taxonomy' => $tax,
						'field'    => 'term_id',
						'terms'    => $terms,
						'operator' => $term_operator,
					];
				}

				//Exclude by Terms
				if(isset($settings[ $tax . '_' . $post_type . '_exclude_term_ids' ])){
					$terms = $settings[ $tax . '_' . $post_type . '_exclude_term_ids' ];
				}
				if ( is_array( $terms ) && count( $terms ) ) {
					$selected_tax_terms[] = [
						'taxonomy' => $tax,
						'field'    => 'term_id',
						'terms'    => $terms,
						'operator' => 'NOT IN',
					];
				}
			}
			if ( is_array( $selected_tax_terms ) && count( $selected_tax_terms ) ) {
				$selected_terms[] = $selected_tax_terms;
			}
		}

		if ( is_array( $selected_terms ) && count( $selected_terms ) ) {
			$query_args['tax_query'] = $selected_terms;
		}

		//Exclude by current post.
		if ( $settings['current_post'] === 'yes' && is_single() ) {
			$post_data                  = Aepro::$_helper->get_demo_post_data();
			$query_args['post__not_in'] = [ $post_data->ID ];
		}

		//Exclude by offset
		$query_args['offset'] = $settings['offset'];

		// Meta Query

		// Date Query

		$select_date = $settings['select_date'];
		if ( $select_date !== 'anytime' ) {
			$date_query = [];
			switch ( $select_date ) {
				case 'today':
					$date_query['after'] = '-1 day';
					break;
				case 'week':
					$date_query['after'] = '-1 week';
					break;
				case 'month':
					$date_query['after'] = '-1 month';
					break;
				case 'quarter':
					$date_query['after'] = '-3 month';
					break;
				case 'year':
					$date_query['after'] = '-1 year';
					break;
				case 'exact':
					if ( ! empty( $settings['post_status'] ) ) {
						$query_args['post_status'] = $settings['post_status'];
					}
					$after_date = $settings['date_after'];
					if ( ! empty( $after_date ) ) {
						$date_query['after'] = $after_date;
					}
					$before_date = $settings['date_before'];
					if ( ! empty( $before_date ) ) {
						$date_query['before'] = $before_date;
					}
					$date_query['inclusive'] = true;
					break;
			}
			$query_args['date_query'] = $date_query;
		}

		// Ordering Parameters
		$query_args['orderby'] = $settings['orderby'];
		$query_args['order']   = $settings['order'];

		if ( $settings['orderby'] === 'meta_value' || $settings['orderby'] === 'meta_value_num' ) {
			$query_args['meta_key'] = $settings['orderby_metakey'];
		}

		// Post Count
		if ( $settings['posts_per_page'] ) {
			$query_args['posts_per_page'] = $settings['posts_per_page'];
		}

		// Pagination Parameters
		//phpcs:ignore WordPress.Security.NonceVerification.Missing
		if ( isset( $_POST['page_num'] ) || $paged > 1 ) {
			$query_args['offset'] = $this->calculate_offset( $settings, $query_args, $paged );
		}

		// Ingnore Sticky Posts
		if ( isset( $settings['ignore_sticky_posts'] ) && $settings['ignore_sticky_posts'] === 'yes' ) {
			$query_args['ignore_sticky_posts'] = true;
		}

		/** WooCommerce */
		if ( class_exists( 'WooCommerce' ) ) {
			// Out of Stock Product
			if ( isset( $settings['hide_out_of_stock'] ) && $settings['hide_out_of_stock'] === 'yes' ) {
				$query_args['meta_query'] = [
					[
						'key'     => '_stock_status',
						'value'   => 'outofstock',
						'compare' => 'NOT IN',
					],
				];
			}

			// Featured Products
			if ( isset( $settings['show_featured'] ) && $settings['show_featured'] === 'yes' ) {
				$featured_product_tax_query[] = [
					'taxonomy' => 'product_visibility',
					'field'    => 'name',
					'terms'    => 'featured',
					'operator' => 'IN', // or 'NOT IN' to exclude feature products
				];

				if ( is_array( $featured_product_tax_query ) && count( $featured_product_tax_query ) ) {
					$selected_terms[] = $featured_product_tax_query;
				}

				if ( is_array( $selected_terms ) && count( $selected_terms ) ) {
					$query_args['tax_query'] = $selected_terms;
				}
			}
		}

		return $query_args;
	}

	public function manual_selection_query() {
		$settings                          = $this->settings;
		$query_args['post_type']           = 'any';
		$ae_post_ids                       = $settings['select_post_ids'];
		$query_args['post__in']            = $ae_post_ids;
		$query_args['orderby']             = $settings['orderby'];
		$query_args['order']               = $settings['order'];
		$query_args['ignore_sticky_posts'] = 1;
		$query_args['posts_per_page']      = $settings['posts_per_page'];
		if ( empty( $query_args['post__in'] ) ) {
			// If no selection - return an empty query
			$query_args['post__in'] = [ -1 ];
		}
		if ( $settings['orderby'] === 'meta_value' || $settings['orderby'] === 'meta_value_num' ) {
			$query_args['meta_key'] = $settings['orderby_metakey'];
		}
		//phpcs:ignore WordPress.Security.NonceVerification.Missing
		if ( isset( $_POST['page_num'] ) ) {
			//phpcs:ignore WordPress.Security.NonceVerification.Missing
			$query_args['offset'] = ( $query_args['posts_per_page'] * ( $_POST['page_num'] - 1 ) ) + $query_args['offset'];
		}

		return $query_args;
	}

	public function related_query() {
		$settings = $this->settings;
		//phpcs:ignore WordPress.Security.NonceVerification.Missing
		if ( isset( $_POST['fetch_mode'] ) && $_POST['wid'] == $settings['ae_widget_id_hidden'] ) {
			//phpcs:ignore WordPress.Security.NonceVerification.Missing
			$cpost_id = $_POST['cpid'];
			$cpost    = get_post( $cpost_id );
		} else {
			$cpost    = Aepro::$_helper->get_demo_post_data();
			$cpost_id = $cpost->ID;
		}

		$query_args = [
			'orderby'             => $settings['orderby'],
			'order'               => $settings['order'],
			'ignore_sticky_posts' => 1,
			'post_status'         => 'publish', // Hide drafts/private posts for admins
			'offset'              => $settings['offset'],
			'posts_per_page'      => $settings['posts_per_page'],
			'post__not_in'        => [ $cpost_id ],
			'post_type'           => 'any',
		];

		if ( $settings['orderby'] === 'meta_value' || $settings['orderby'] === 'meta_value_num' ) {
				$query_args['meta_key'] = $settings['orderby_metakey'];
		}
		//phpcs:ignore WordPress.Security.NonceVerification.Missing
		if ( isset( $_POST['page_num'] ) ) {
			//phpcs:ignore WordPress.Security.NonceVerification.Missing
			$query_args['offset'] = ( $query_args['posts_per_page'] * ( $_POST['page_num'] - 1 ) ) + $query_args['offset'];
		}

		$taxonomies = $settings['related_by'];

		if ( $taxonomies ) {
			foreach ( $taxonomies as $taxonomy ) {

				$terms = get_the_terms( $cpost_id, $taxonomy );
				if ( $terms ) {
					$term_ids = [];
					foreach ( $terms as $term ) {
						$term_ids[] = $term->term_id;
					}

					if ( $settings['related_match_with'] === 'OR' ) {
						$operator = 'IN';
					} else {
						$operator = 'AND';
					}

					$query_args['tax_query'][] = [
						'taxonomy' => $taxonomy,
						'field'    => 'term_id',
						'terms'    => $term_ids,
						'operator' => $operator,
					];
				}
			}
		}

		return $query_args;
	}

	public function relation_query() {
		$settings       = $this->settings;
		$query_args     = [];
		$selected_terms = [];
		$field          = $settings['acf_relation_field'];
		$paged          = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : '';
		//phpcs:ignore WordPress.Security.NonceVerification.Missing
		if ( isset( $_POST['fetch_mode'] ) && $_POST['wid'] == $settings['ae_widget_id_hidden'] ) {
			//phpcs:ignore WordPress.Security.NonceVerification.Missing
			$cpost_id = $_POST['cpid'];
			$cpost    = get_post( $cpost_id );
		} else {
			$cpost    = Aepro::$_helper->get_demo_post_data();
			$cpost_id = $cpost->ID;
		}

		if($settings['option_page_relationship_field'] === 'yes'){
			$post_items = get_field( $field, 'option' );
		}else{
			if ( \Aepro\Plugin::show_acf() && is_plugin_active( 'pods/init.php' ) ) {
				
				if ( $settings['relationship_type'] === 'pods' ) {
					$pods = get_post_meta( $cpost_id, $field );
					if ( count( $pods ) ) {
						//$pods = [ $pods ];
						foreach ( $pods as $pod ) {

							if(isset($pod['ID'])){
								$post_items[] = $pod['ID'];
							}else{
								$post_items[] = $pod;
							}
						}
					}
				} else {
					$post_items = get_field( $field, $cpost_id );
				}
			} elseif ( is_plugin_active( 'pods/init.php' ) ) {
				
				$pods = get_post_meta( $cpost_id, $field );
				if ( count( $pods ) ) {
					//$pods = [ $pods ];

					foreach ( $pods as $pod ) {
						if(isset($pod['ID'])){
							$post_items[] = $pod['ID'];
						}else{
							$post_items[] = $pod;
						}
					}
				}
			} else {
				$post_items = get_field( $field, $cpost_id );
			}
		}

		$repeater = Aepro::$_helper->is_repeater_block_layout();
		if ( $repeater['is_repeater'] ) {
			if ( isset( $repeater['field'] ) ) {
				$repeater_field = get_field( $repeater['field'], $cpost_id );
				$post_items     = $repeater_field[0][ $settings['acf_relation_field'] ];
			} else {
				$post_items = get_sub_field( $settings['acf_relation_field'] );
			}
		}

		$flexible = Aepro::$_helper->is_flexible_block_layout();

		if ( $flexible['is_flexible'] ) {
			if ( isset( $flexible['field'] ) ) {
				$flexible_field = get_field( $flexible['field'], $cpost_id );
				foreach ( $flexible_field as $flex_layout ) {
					if ( array_key_exists( $settings['acf_relation_field'], $flex_layout ) ) {
						$post_items = $flex_layout[ $settings['acf_relation_field'] ];
					}
				}
			} else {
				$post_items = get_sub_field( $settings['acf_relation_field'] );
			}
		}

			$query_args = [
				'orderby'             => $settings['orderby'],
				'order'               => $settings['order'],
				'ignore_sticky_posts' => 1,
				'post_status'         => 'publish', // Hide drafts/private posts for admins
				'offset'              => $settings['offset'],
				'posts_per_page'      => $settings['posts_per_page'],
				'post_type'           => get_post_types( [ 'public' => true ], 'names' ),
				'post__not_in'        => [ $cpost_id ],
			];

			if ( $settings['reverse_relation'] === 'yes' ) {
				$query_args['meta_query'] = [
					[
						'key'     => $settings['acf_relation_field'],
						'value'   => $cpost_id,
						'compare' => 'LIKE',
					],
				];
			} else {
				if ( empty( $post_items ) || ! $post_items ) {
					$post_items = [ 0 ];
				}
				$query_args['post__in'] = $post_items;
			}

			if ( $settings['orderby'] === 'meta_value' || $settings['orderby'] === 'meta_value_num' ) {
				$query_args['meta_key'] = $settings['orderby_metakey'];
			}
			//phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison, WordPress.Security.NonceVerification.Missing
			if ( isset( $_POST['page_num'] ) || $paged > 1 ) {
				$query_args['offset'] = $this->calculate_offset( $settings, $query_args, $paged );
			}

			/* Filter Bar */
			// When there is Term Id in $_POST
			//phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison, WordPress.Security.NonceVerification.Missing
			if ( isset( $settings['filter_taxonomy'] ) && ( isset( $_POST['term_id'] ) && $_POST['term_id'] >= 0 ) ) {
				//phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison, WordPress.Security.NonceVerification.Missing
				if ( $_POST['term_id'] != 0 ) {
					$selected_terms['relation'] = 'AND';
					$selected_terms[]           = [
						'taxonomy' => $settings['filter_taxonomy'],
						'field'    => 'term_id',
						//phpcs:ignore WordPress.Security.NonceVerification.Missing
						'terms'    => $_POST['term_id'],
					];
				}
				// When there is default term selected
				//phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison
			} elseif ( isset( $settings[ $settings['filter_taxonomy'] . '_filter_default_term' ] ) && $settings[ $settings['filter_taxonomy'] . '_filter_default_term' ] != 0 ) {
				$selected_terms['relation'] = 'AND';
				$selected_terms[]           = [
					'taxonomy' => $settings['filter_taxonomy'],
					'field'    => 'term_id',
					'terms'    => $settings[ $settings['filter_taxonomy'] . '_filter_default_term' ],
				];
				// When there is 'All' tab is disabled
				//phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison
			} elseif ( isset( $settings['show_all'] ) && $settings['show_all'] != 'yes' ) {
				$filter_terms               = Aepro::$_helper->get_filter_bar_filters( $settings );
				$selected_terms['relation'] = 'AND';
				$selected_terms[]           = [
					'taxonomy' => $settings['filter_taxonomy'],
					'field'    => 'term_id',
					'terms'    => $filter_terms[0]->term_id,
				];
				// When there is 'All' tab is disabled
			}
			/* Filter Bar End */
			if ( is_array( $selected_terms ) && count( $selected_terms ) ) {
				$query_args['tax_query'] = $selected_terms;
			}

			return $query_args;
	}

	public function post_object_query() {
		$settings   = $this->settings;
		$query_args = [];
		$paged      = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : '';
		$field      = $settings['acf_post_field'];
		//phpcs:ignore WordPress.Security.NonceVerification.Missing
		if ( isset( $_POST['fetch_mode'] ) && $_POST['wid'] == $settings['ae_widget_id_hidden'] ) {
			//phpcs:ignore WordPress.Security.NonceVerification.Missing
			$cpost_id = $_POST['cpid'];
			$cpost    = get_post( $cpost_id );
		} else {
			$cpost    = Aepro::$_helper->get_demo_post_data();
			$cpost_id = $cpost->ID;
		}

		$post_items = get_field( $field, $cpost_id );

		$repeater = Aepro::$_helper->is_repeater_block_layout();
		if ( $repeater['is_repeater'] ) {
			if ( isset( $repeater['field'] ) ) {
				$repeater_field = get_field( $repeater['field'], $cpost_id );
				$post_items     = $repeater_field[0][ $settings['acf_post_field'] ];
			} else {
				$post_items = get_sub_field( $settings['acf_post_field'] );
			}
		}

		$flexible = Aepro::$_helper->is_flexible_block_layout();
		if ( $flexible['is_flexible'] ) {
			if ( isset( $flexible['field'] ) ) {
				$flexible_field = get_field( $flexible['field'], $cpost_id );
				foreach ( $flexible_field as $flex_layout ) {
					if ( array_key_exists( $settings['acf_post_field'], $flex_layout ) ) {
						$post_items = $flex_layout[ $settings['acf_post_field'] ];
					}
				}
			} else {
				$post_items = get_sub_field( $settings['acf_post_field'] );
			}
		}
		if ( ! is_array( $post_items ) ) {
			$post_items = [ $post_items ];
		}

		if ( $post_items ) {
			$query_args = [
				'orderby'             => $settings['orderby'],
				'order'               => $settings['order'],
				'ignore_sticky_posts' => 1,
				'post_status'         => 'publish', // Hide drafts/private posts for admins
				'offset'              => $settings['offset'],
				'posts_per_page'      => $settings['posts_per_page'],
				'post_type'           => get_post_types( [ 'public' => true ], 'names' ),
				'post__in'            => $post_items,
				'post__not_in'        => [ $cpost_id ],
			];

			if ( $settings['orderby'] === 'meta_value' || $settings['orderby'] === 'meta_value_num' ) {
				$query_args['meta_key'] = $settings['orderby_metakey'];
			}
			//phpcs:ignore WordPress.Security.NonceVerification.Missing
			if ( isset( $_POST['page_num'] ) || $paged > 1 ) {
				$query_args['offset'] = $this->calculate_offset( $settings, $query_args, $paged );
			}
		}

		return $query_args;
	}

	public function calculate_offset( $settings, $query_args, $paged ) {
		if ( ! isset( $query_args['posts_per_page'] ) ) {
			return 0;
		}

		$ias_pagination = '';
		if ( $settings[ $settings['_skin'] . '_show_infinite_scroll' ] === 'yes' ) {
			$ias_pagination = 'yes';
		}

		if ( $settings[ $settings['_skin'] . '_show_pagination' ] !== 'yes' && $ias_pagination === '' ) {
			return 0;
		}

		if ( $settings[ $settings['_skin'] . '_disable_ajax' ] === 'yes' && $paged > 1 ) {
			$offset = ( $query_args['posts_per_page'] * ( $paged - 1 ) );

		} else {
			$offset = $query_args['posts_per_page'] * ( $this->get_current_page_num() - 1 );
		}

		if ( is_numeric( $query_args['offset'] ) ) {
			$offset += $query_args['offset'];
		}

		return $offset;
	}

	public function get_current_page_num() {
		$current = 1;
		//phpcs:ignore WordPress.Security.NonceVerification.Missing
		if($this->settings['source'] === 'relation' && $this->settings['source'] === 'post_object'){
			if(isset( $_POST['page_num'] ) && $this->settings['ae_widget_id_hidden'] === $_POST['wid']){
				$current = $_POST['page_num'];
				return $current;
			}else{
				return $current;
			}
		}

		//phpcs:ignore WordPress.Security.NonceVerification.Missing
		if ( isset( $_POST['page_num'] ) ) {
			//phpcs:ignore WordPress.Security.NonceVerification.Missing
			$current = $_POST['page_num'];
			return $current;
		}

		if ( is_front_page() && ! is_home() ) {
			$current = ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1;
		} else {
			$current = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
		}

		return $current;
	}

	public function get_preview_query() {
		$current_post_id = get_the_ID();
		$render_mode     = get_post_meta( $current_post_id, 'ae_render_mode', true );

		$post_type = 'post';

		switch ( $render_mode ) {

			case 'author_template':
				$author_data                                     = Aepro::$_helper->get_preview_author_data();
										$query_args['author']    = $author_data['prev_author_id'];
										$query_args['post_type'] = 'any';
				break;

			case 'post_type_archive_template':
												$post_type               = get_post_meta( $current_post_id, 'ae_rule_post_type_archive', true );
												$query_args['post_type'] = $post_type;
				break;

			case 'archive_template':
				$term_data                                       = Aepro::$_helper->get_preview_term_data();
										$query_args['tax_query'] = [
											[
												'taxonomy' => $term_data['taxonomy'],
												'field'    => 'term_id',
												'terms'    => $term_data['prev_term_id'],
											],
										];
										$query_args['post_type'] = 'any';
				break;

			case 'date_template':
				$query_args['post_type'] = $post_type;
				break;

			default:
				$query_args['post_type'] = $post_type;
		}

		return $query_args;
	}

	public function get_query_section( $widget, $condition = [] ) {

		$widget->start_controls_section(
			'section_post_query',
			[
				'label' => __( 'Query', 'ae-pro' ),
			]
		);

		$source                                = Aepro::$_helper->get_rule_post_types();
		$ae_source_options                     = $source;
		$ae_source_options['current_loop']     = __( 'Current Archive', 'ae-pro' );
		$ae_source_options['manual_selection'] = __( 'Manual Selection', 'ae-pro' );
		$ae_source_options['related']          = __( 'Related Posts', 'ae-pro' );

		if ( \Aepro\Plugin::show_acf() || is_plugin_active( 'pods/init.php' ) ) {
			$ae_source_options['relation']    = __( 'Relationship', 'ae-pro' );
			$ae_source_options['post_object'] = __( 'Post (ACF)', 'ae-pro' );
		}

		$widget->add_control(
			'source',
			[
				'label'   => __( 'Source', 'ae-pro' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $ae_source_options,
				'default' => key( $source ),
			]
		);

		$widget->add_control(
			'select_post_ids',
			[
				'label'       => __( 'Posts', 'ae-pro' ),
				'type'        => 'aep-query',
				'label_block' => true,
				'query_type'  => 'post',
				'multiple'    => true,
				'condition'   => [
					'source' => 'manual_selection',
				],
			]
		);

		$widget->add_control(
			'related_by',
			[
				'label'       => __( 'Related By', 'ae-pro' ),
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'label_block' => true,
				'placeholder' => __( 'Select Taxonomies', 'ae-pro' ),
				'default'     => '',
				'options'     => Aepro::$_helper->get_rules_taxonomies(),
				'condition'   => [
					'source' => 'related',
				],
			]
		);
		$widget->add_control(
			'related_match_with',
			[
				'label'     => __( 'Match With', 'ae-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'OR',
				'options'   => [
					'OR'  => __( 'Anyone Term', 'ae-pro' ),
					'AND' => __( 'All Terms', 'ae-pro' ),
				],
				'condition' => [
					'source' => 'related',
				],
			]
		);

		if ( \Aepro\Plugin::show_acf() && is_plugin_active( 'pods/init.php' ) ) {
			$widget->add_control(
				'relationship_type',
				[
					'label'     => __( 'Relationship Type', 'ae-pro' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'acf',
					'options'   => [
						'acf'  => __( 'ACF', 'ae-pro' ),
						'pods' => __( 'Pods', 'ae-pro' ),
					],
					'condition' => [
						'source' => 'relation',
					],
				]
			);
		}

		if ( \Aepro\Plugin::show_acf() || is_plugin_active( 'pods/init.php' ) ) {
			$widget->add_control(
				'acf_relation_field',
				[
					'label'       => __( 'Relationship Field', 'ae-pro' ),
					'tyoe'        => Controls_Manager::TEXT,
					'description' => __( 'Key of ACF / Pods Relationship Field', 'ae-pro' ),
					'condition'   => [
						'source' => 'relation',
					],
				]
			);

			$widget->add_control(
				'reverse_relation',
				[
					'label'        => __( 'Reverse Relation', 'ae-pro' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'ae-pro' ),
					'label_off'    => __( 'No', 'ae-pro' ),
					'return_value' => 'yes',
					'condition'    => [
						'source' => 'relation',
					],
				]
			);
		}

		if ( \Aepro\Plugin::show_acf() || is_plugin_active( 'pods/init.php' ) ) {
			$widget->add_control(
				'acf_post_field',
				[
					'label'       => __( 'Post Field', 'ae-pro' ),
					'tyoe'        => Controls_Manager::TEXT,
					'description' => __( 'Key of ACF Post Field', 'ae-pro' ),
					'condition'   => [
						'source' => 'post_object',
					],
				]
			);
		}

		$widget->add_control(
			'taxonomy_divider',
			[
				'type'      => Controls_Manager::DIVIDER,
				'style'     => 'thick',
				'condition' => [
					'source!' => [ 'manual_selection', 'current_loop', 'related', 'relation', 'post_object' ],
				],
			]
		);

		$widget->add_control(
			'taxonomy_heading',
			[
				'label'     => __( 'Taxonomy Query', 'ae-pro' ),
				'type'      => Controls_Manager::HEADING,
				'condition' => [
					'source!' => [ 'current_loop', 'related', 'relation', 'post_object', 'manual_selection' ],
				],
			]
		);

		$ae_taxonomies = Post_Helper::instance()->get_all_taxonomies();

		$post_types = Aepro::$_helper->get_rule_post_types();

		foreach ( $post_types as $key => $post_type ) {
			$widget->add_control(
				$key . '_tax_ids',
				[
					'label'       => 'Taxonomies',
					'type'        => Controls_Manager::SELECT2,
					'multiple'    => true,
					'label_block' => true,
					'placeholder' => __( 'Enter Taxnomies ID Separated by Comma', 'ae-pro' ),
					'options'     => Post_Helper::instance()->get_taxonomies_by_post_type( $key ),
					'condition'   => [
						'source' => $key,
					],
				]
			);

			$widget->add_control(
				$key . '_tax_relation',
				[
					'label'     => __( 'Relation', 'ae-pro' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'OR',
					'options'   => [
						'OR'  => __( 'Anyone Term', 'ae-pro' ),
						'AND' => __( 'All Terms', 'ae-pro' ),
					],
					'condition' => [
						'source' => $key,
					],
				]
			);
		}

		$widget->start_controls_tabs( 'tabs_include_exclude' );

		$widget->start_controls_tab(
			'tab_query_include',
			[
				'label'     => __( 'Include', 'ae-pro' ),
				'condition' => [
					'source!' => [ 'manual_selection', 'current_loop', 'related', 'relation', 'post_object' ],
				],
			]
		);

		foreach ( $ae_taxonomies as $ae_taxonomy => $object ) {
			foreach ( $object->object_type as $object_type ) {
				$widget->add_control(
					$ae_taxonomy . '_' . $object_type . '_include_term_ids',
					[
						'label'       => $object->label,
						'type'        => Controls_Manager::SELECT2,
						'multiple'    => true,
						'label_block' => true,
						//phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralText
						'placeholder' => __( 'Enter ' . $object->label . ' ID Separated by Comma', 'ae-pro' ),
						'object_type' => $ae_taxonomy,
						'options'     => Post_Helper::instance()->get_taxonomy_terms( $ae_taxonomy ),
						'condition'   => [
							'source'                  => $object_type,
							$object_type . '_tax_ids' => $ae_taxonomy,
						],
					]
				);

				$widget->add_control(
					$ae_taxonomy . '_' . $object_type . '_term_operator',
					[
						'label'     => __( 'Operator', 'ae-pro' ),
						'type'      => Controls_Manager::SELECT,
						'default'   => 'IN',
						'options'   => [
							'IN'         => __( 'IN', 'ae-pro' ),
							'NOT IN'     => __( 'NOT IN', 'ae-pro' ),
							'AND'        => __( 'AND', 'ae-pro' ),
							'EXISTS'     => __( 'EXISTS', 'ae-pro' ),
							'NOT EXISTS' => __( 'NOT EXISTS', 'ae-pro' ),
						],
						'condition' => [
							'source'                  => $object_type,
							$object_type . '_tax_ids' => $ae_taxonomy,
						],
					]
				);
			}
		}

		$widget->end_controls_tab();

		$widget->start_controls_tab(
			'tab_query_exclude',
			[
				'label'     => __( 'Exclude', 'ae-pro' ),
				'condition' => [
					'source!' => [ 'manual_selection', 'current_loop', 'related', 'relation', 'post_object' ],
				],
			]
		);

		foreach ( $ae_taxonomies as $ae_taxonomy => $object ) {
			foreach ( $object->object_type as $object_type ) {
				$widget->add_control(
					$ae_taxonomy . '_' . $object_type . '_exclude_term_ids',
					[
						'label'       => $object->label,
						'type'        => Controls_Manager::SELECT2,
						'multiple'    => true,
						'label_block' => true,
						//phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralText
						'placeholder' => __( 'Enter ' . $object->label . ' ID Separated by Comma', 'ae-pro' ),
						'object_type' => $ae_taxonomy,
						'options'     => Post_Helper::instance()->get_taxonomy_terms( $ae_taxonomy ),
						'condition'   => [
							'source'                  => $object_type,
							$object_type . '_tax_ids' => $ae_taxonomy,
						],
					]
				);
			}
		}

		$widget->end_controls_tab();

		$widget->end_controls_tabs();

		$widget->add_control(
			'author_divider',
			[
				'type'      => Controls_Manager::DIVIDER,
				'style'     => 'thick',
				'condition' => [
					'source!' => [ 'current_loop', 'manual_selection' ],
				],
			]
		);

		$widget->add_control(
			'author_query_heading',
			[
				'label'     => __( 'Author', 'ae-pro' ),
				'type'      => Controls_Manager::HEADING,
				'condition' => [
					'source!' => [ 'current_loop', 'manual_selection' ],
				],
			]
		);

		$widget->start_controls_tabs( 'author_query_tabs' );

		$widget->start_controls_tab(
			'tab_author_include',
			[
				'label'     => __( 'Include', 'ae-pro' ),
				'condition' => [
					'source!' => [ 'manual_selection', 'current_loop' ],
				],
			]
		);

		$widget->add_control(
			'include_author_ids',
			[
				'label'       => 'Authors',
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'label_block' => true,
				'show_label'  => false,
				'placeholder' => __( 'Enter Author ID Separated by Comma', 'ae-pro' ),
				'options'     => Post_Helper::instance()->get_authors(),
				'condition'   => [
					'source!' => [ 'manual_selection', 'current_loop' ],
				],
			]
		);

		$widget->end_controls_tab();

		$widget->start_controls_tab(
			'tab_author_exclude',
			[
				'label'     => __( 'Exclude', 'ae-pro' ),
				'condition' => [
					'source!' => [ 'manual_selection', 'current_loop' ],
				],
			]
		);

		$widget->add_control(
			'exclude_author_ids',
			[
				'label'       => 'Authors',
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'label_block' => true,
				'show_label'  => false,
				'placeholder' => __( 'Enter Author ID Separated by Comma', 'ae-pro' ),
				'options'     => Post_Helper::instance()->get_authors(),
			]
		);

		$widget->end_controls_tab();

		$widget->end_controls_tabs();

		$widget->add_control(
			'date_divider',
			[
				'type'      => Controls_Manager::DIVIDER,
				'style'     => 'thick',
				'condition' => [
					'source!' => 'current_loop',
				],
			]
		);

		$widget->add_control(
			'date_query_heading',
			[
				'label'     => __( 'Date Query', 'ae-pro' ),
				'type'      => Controls_Manager::HEADING,
				'condition' => [
					'source!' => 'current_loop',
				],
			]
		);

		$widget->add_control(
			'select_date',
			[
				'label'     => __( 'Date', 'ae-pro' ),
				'type'      => Controls_Manager::SELECT,
				'post_type' => '',
				'options'   => [
					'anytime' => __( 'All', 'ae-pro' ),
					'today'   => __( 'Past Day', 'ae-pro' ),
					'week'    => __( 'Past Week', 'ae-pro' ),
					'month'   => __( 'Past Month', 'ae-pro' ),
					'quarter' => __( 'Past Quarter', 'ae-pro' ),
					'year'    => __( 'Past Year', 'ae-pro' ),
					'exact'   => __( 'Custom', 'ae-pro' ),
				],
				'default'   => 'anytime',
				'multiple'  => false,
				'condition' => [
					'source!' => [
						'manual_selection',
						'current_loop',
					],
				],
			]
		);

		$widget->add_control(
			'post_status',
			[
				'label'       => 'Post Status',
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'label_block' => true,
				'options'     => [
					'publish' => __( 'Publish', 'ae-pro' ),
					'future'  => __( 'Schedule', 'ae-pro' ),
				],
				'condition'   => [
					'select_date' => 'exact',
					'source!'     => [
						'manual_selection',
						'current_loop',
					],
				],
			]
		);

		$widget->add_control(
			'date_before',
			[
				'label'       => __( 'Before', 'ae-pro' ),
				'type'        => Controls_Manager::DATE_TIME,
				'post_type'   => '',
				'label_block' => false,
				'multiple'    => false,
				'placeholder' => __( 'Choose', 'ae-pro' ),
				'condition'   => [
					'select_date' => 'exact',
					'source!'     => [
						'manual_selection',
						'current_loop',
					],
				],
				'description' => __( 'Setting a ‘Before’ date will show all the posts published until the chosen date (inclusive).', 'ae-pro' ),
			]
		);

		$widget->add_control(
			'date_after',
			[
				'label'       => __( 'After', 'ae-pro' ),
				'type'        => Controls_Manager::DATE_TIME,
				'post_type'   => '',
				'label_block' => false,
				'multiple'    => false,
				'placeholder' => __( 'Choose', 'ae-pro' ),
				'condition'   => [
					'select_date' => 'exact',
					'source!'     => [
						'manual_selection',
						'current_loop',
					],
				],
				'description' => __( 'Setting an ‘After’ date will show all the posts published since the chosen date (inclusive).', 'ae-pro' ),
			]
		);

		$widget->add_control(
			'orderby',
			[
				'label'           => __( 'Order By', 'ae-pro' ),
				'type'            => Controls_Manager::SELECT,
				'content_classes' => 'ae_conditional_fields',
				'default'         => 'post_date',
				'options'         => [
					'post_date'      => __( 'Date', 'ae-pro' ),
					'post_title'     => __( 'Title', 'ae-pro' ),
					'menu_order'     => __( 'Menu Order', 'ae-pro' ),
					'rand'           => __( 'Random', 'ae-pro' ),
					'post__in'       => __( 'Manual', 'ae-pro' ),
					'meta_value'     => __( 'Custom Field', 'ae-pro' ),
					'meta_value_num' => __( 'Custom Field (Numeric)', 'ae-pro' ),
				],
				'condition'       => [
					'source!' => 'current_loop',
				],
			]
		);

		$widget->add_control(
			'orderby_alert',
			[
				'type'            => Controls_Manager::RAW_HTML,
				'content_classes' => 'ae_order_by_alert',
				'raw'             => __( "<div class='elementor-control-field-description'>Note: Order By 'Manual' is only applicable when Source is 'Manual Selection' and 'Relationship' </div>", 'ae-pro' ),
				'separator'       => 'none',
				'condition'       => [
					'orderby' => 'post__in',
				],
			]
		);

		$widget->add_control(
			'orderby_metakey',
			[
				'label'       => __( 'Meta Key Name', 'ae-pro' ),
				'tyoe'        => Controls_Manager::TEXT,
				'description' => __( 'Custom Field Key', 'ae-pro' ),
				'condition'   => [
					'source!' => 'current_loop',
					'orderby' => [ 'meta_value', 'meta_value_num' ],
				],
			]
		);

		$widget->add_control(
			'order',
			[
				'label'     => __( 'Order', 'ae-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'desc',
				'options'   => [
					'asc'  => __( 'ASC', 'ae-pro' ),
					'desc' => __( 'DESC', 'ae-pro' ),
				],
				'condition' => [
					'source!'  => 'current_loop',
					'orderby!' => 'post__in',
				],
			]
		);

		$widget->add_control(
			'current_post',
			[
				'label'        => __( 'Exclude Current Post', 'ae-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => __( 'Show', 'ae-pro' ),
				'label_off'    => __( 'Hide', 'ae-pro' ),
				'return_value' => 'yes',
				'condition'    => [
					'source!' => 'current_loop',
				],
			]
		);

		$widget->add_control(
			'offset',
			[
				'label'       => __( 'Offset', 'ae-pro' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 0,
				'condition'   => [
					'source!' => [ 'current_loop', 'manual_selection' ],
				],
				'description' => __( 'Use this setting to skip over posts (e.g. \'2\' to skip over 2 posts).', 'ae-pro' ),
			]
		);

		$widget->add_control(
			'posts_per_page',
			[
				'label'     => __( 'Posts Count', 'ae-pro' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 6,
				'condition' => [
					'source!' => 'current_loop',
				],
			]
		);

		$widget->add_control(
			'query_filter',
			[
				'label'       => __( 'Query Filter', 'ae-pro' ),
				'type'        => Controls_Manager::TEXT,
				//phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralText
				'description' => __( Aepro::$_helper->get_widget_admin_note_html( '<span style="color:red">Danger Ahead!!</span> It is a developer oriented feature. Only use if you know how exaclty WordPress queries and filters works.', 'https://wpvibes.link/go/feature-post-blocks-query-filter', 'Read Instructions' ), 'ae-pro' ),
			]
		);

		$widget->add_control(
			'no_posts_message',
			[
				'label'     => __( 'No Posts Message', 'ae-pro' ),
				'type'      => Controls_Manager::TEXTAREA,
				'separator' => 'before',
				'default'   => 'No results were found.',
			]
		);

		$widget->end_controls_section();
	}
}
