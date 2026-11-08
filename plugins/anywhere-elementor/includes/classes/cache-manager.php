<?php

namespace Aepro\Classes;

use Aepro\Helper;

class CacheManager {

	private static $_instance = null;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	private function __construct() {
		add_action( 'save_post', [ $this, 'clear_transients' ], 10, 2 );
	}

	public function initiate_template_cache() {

		$template_list     = [];
		$single_global     = [];
		$post_type_archive = [];
		$taxonomy_global   = [];

		// get templates
		$args = [
			'post_type'      => 'ae_global_templates',
			'posts_per_page' => -1,
			'lang' => ''
		];

		$query = new \WP_Query( $args );

		if ( $query->found_posts ) {
			while ( $query->have_posts() ) {
				$query->the_post();

				$id    = get_the_ID();
				$title = get_the_title();

				$render_mode = get_post_meta( $id, 'ae_render_mode', true );

				$template_list[ $render_mode ][ $id ] = $title;

				switch ( $render_mode ) {

					case 'post_template':
						$post_type = get_post_meta( $id, 'ae_rule_post_type', true );
						$global    = get_post_meta( $id, 'ae_apply_global', true );

						if ( $global ) {
							$single_global[ $post_type ][ $id ] = $title;
						}

						break;

					case 'post_type_archive_template':
						$post_type                              = get_post_meta( $id, 'ae_rule_post_type_archive', true );
						$post_type_archive[ $post_type ][ $id ] = $title;

						break;

					case 'archive_template':
						$global = get_post_meta( $id, 'ae_apply_global', true );

						if ( $global ) {
							$taxonomy                            = get_post_meta( $id, 'ae_rule_taxonomy', true );
							$taxonomy_global[ $taxonomy ][ $id ] = $title;
						}

						break;
				}
			}

			wp_reset_postdata();
		}

		// set in transient
		$expiry = apply_filters( 'aepro/transient/expiry', 86400 );

		set_transient( 'ae_template_list', $template_list, $expiry );
		set_transient( 'ae_template_post_global', $single_global, $expiry );
		set_transient( 'ae_template_taxonomy_global', $taxonomy_global );
		set_transient( 'ae_template_post_type_archive', $post_type_archive, $expiry );
	}

	public function get_block_layouts( $exclude_current = true ) {

		$template_list = get_transient( 'ae_template_list' );
		if ( false === $template_list ) {
			// It wasn't there, so regenerate the data and save the transient
			$this->initiate_template_cache();
		}

		$template_list = get_transient( 'ae_template_list' );

		if ( is_array( $template_list ) && isset( $template_list['block_layout'] ) ) {

			if ( $exclude_current ) {
				$post_id = 0;
				// get current post
                // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				if ( isset( $_GET['post'] ) ) {
                    // phpcs:ignore WordPress.Security.NonceVerification.Recommended
					$post_id = $_GET['post'];
				}
				// phpcs:ignore WordPress.Security.NonceVerification.Missing
				if ( wp_doing_ajax() && isset( $_POST['editor_post_id'] ) ) {
					// TODO:: sanitize
					// phpcs:ignore WordPress.Security.NonceVerification.Missing
					$post_id = $_POST['editor_post_id'];
				}
				
				if(is_numeric($post_id)){
					if ( isset( $template_list['block_layout'][ $post_id ] ) ) {
						unset( $template_list['block_layout'][ $post_id ] );
					}
				}
				
			}

			return $template_list['block_layout'];
		}

		return [];
	}

	public function get_post_global_templates( $post_type ) {
		$template_list = get_transient( 'ae_template_post_global' );
		if ( false === $template_list ) {
			// It wasn't there, so regenerate the data and save the transient
			$this->initiate_template_cache();
		}

		$template_list = get_transient( 'ae_template_post_global' );

		if ( is_array( $template_list ) && isset( $template_list[ $post_type ] ) ) {
			return $template_list[ $post_type ];
		}

		return false;
	}

	public function get_templates_by_render_mode( $render_mode ) {
		$template_list = get_transient( 'ae_template_list' );
		if ( false === $template_list ) {
			// It wasn't there, so regenerate the data and save the transient
			$this->initiate_template_cache();
		}

		$template_list = get_transient( 'ae_template_list' );

		if ( is_array( $template_list ) && isset( $template_list[ $render_mode ] ) ) {
			return $template_list[ $render_mode ];
		}

		return false;
	}

	public function get_finder_items() {
		$items         = [];
		$template_list = get_transient( 'ae_finder_items' );
		if ( false === $template_list ) {
			// It wasn't there, so regenerate the data and save the transient

			$posts = get_posts(
				[
					'post_type'   => 'ae_global_templates',
					'post_status' => [ 'publish', 'draft' ],
					'numberposts' => -1,
				]
			);

			$items = [];
			if ( $posts ) {

				$helper       = new Helper();
				$render_modes = $helper->get_ae_render_mode_hook();

				foreach ( $posts as $post ) :
					$mode = get_post_meta( $post->ID, 'ae_render_mode', true );
					if ( $mode === '' ) {
						continue;
					}
					$is_acf_pro = \Aepro\Plugin::show_acf( true );
					if ( ! $is_acf_pro && $mode === 'acf_repeater_layout' ) {
						continue;
					}
					$draft_post = '';
					if ( $post->post_status === 'draft' ) {
						$draft_post = ' &#8210; Draft';
					}
					$title              = $post->post_title . $draft_post;
					$ae_template_render = 'DA Template / ' . $render_modes[ $mode ];
					$items[]            = [
						//phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralText
						'title'       => __( $title, 'ae-pro' ),
						//phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralText
						'description' => __( $ae_template_render, 'ae-pro' ),
						'url'         => admin_url( 'post.php?post=' . $post->ID . '&action=elementor' ),
						'icon'        => 'wordpress',
						'keywords'    => [ 'ae template', 'template' ],
						'actions'     => [
							[
								'name' => 'view',
								'url'  => get_permalink( $post->ID ),
								'icon' => 'eye',
							],
							[
								'name' => 'edit',
								'url'  => get_edit_post_link( $post->ID, 'context' ),
								'icon' => 'edit',
							],
						],
					];
				endforeach;
			}

			set_transient( 'ae_finder_items', $items, 86400 );
		}

		return $items;
	}

	public function clear_transients( $post_id, $post ) {

		if ( $post->post_type !== 'ae_global_templates' ) {
			return;
		}

		$transients = [
			'ae_template_list',
			'ae_template_post_global',
			'ae_finder_items',
			'ae_template_post_type_archive',
			'ae_template_taxonomy_global',
		];

		foreach ( $transients as $transient ) {
			delete_transient( $transient );
		}
	}

	public function get_post_type_archive_template( $post_type ) {
		$template_list = get_transient( 'ae_template_post_type_archive' );
		if ( false === $template_list ) {
			// It wasn't there, so regenerate the data and save the transient
			$this->initiate_template_cache();
		}

		$templates = get_transient( 'ae_template_post_type_archive' );

		if ( is_array( $templates ) && isset( $templates[ $post_type ] ) ) {
			$template = array_key_first( $templates[ $post_type ] );
			return $template;
		} else {
			return false;
		}
	}

	public function get_taxonomy_global_template( $taxonomy ) {
		$template_list = get_transient( 'ae_template_taxonomy_global' );
		if ( false === $template_list ) {
			// It wasn't there, so regenerate the data and save the transient
			$this->initiate_template_cache();
		}

		$template_list = get_transient( 'ae_template_taxonomy_global' );

		if ( is_array( $template_list ) && isset( $template_list[ $taxonomy ] ) ) {
			return $template_list[ $taxonomy ];
		}

		return false;
	}
}
