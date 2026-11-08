<?php
/**
 * Builder Custom post type
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Controllers\Builder;

defined( 'ABSPATH' ) || exit();

use RadiusTheme\SB\Models\TemplateSettings;
use WP_Query;
use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Helpers\BuilderFns;
use RadiusTheme\SB\Traits\SingletonTrait;

/**
 * Builder Custom post type
 */
class BuilderCpt {
	/**
	 * Singleton
	 */
	use SingletonTrait;

	/**
	 * Final constructor.
	 */
	private function __construct() {
		add_action( 'init', [ $this, 'template_builder_post_type' ] );
		add_filter( 'post_row_actions', [ $this, 'filter_post_row_actions' ], 11, 1 );
		// Admin column.
		add_filter( 'manage_edit-' . BuilderFns::$post_type_tb . '_columns', [ $this, 'add_new_columns' ] );
		add_action( 'manage_' . BuilderFns::$post_type_tb . '_posts_custom_column', [ $this, 'custom_columns' ], 10, 2 );
		add_filter( 'manage_edit-' . BuilderFns::$post_type_tb . '_sortable_columns', [ $this, 'register_sortable_columns' ] );
		add_action( 'pre_get_posts', [ $this, 'sortable_columns_query' ] );
		// Priority 99999 ensures ShopBuilder wins over Elementor Pro Theme Builder,
		// which also filters `template_include` at 99.
		add_filter( 'template_include', [ $this, 'builder_template' ], 99999 );
		add_filter( 'template_redirect', [ $this, 'restrict_preview_access' ], 99 );

		add_filter( 'parse_query', [ $this, 'query_filter' ] );
		// Add filter for search.
		add_action( 'restrict_manage_posts', [ $this, 'add_filter' ] );
		// Post Content Remove For Elementor Builder.
		add_action( 'save_post_' . BuilderFns::$post_type_tb, [ $this, 'on_save_post' ], 10, 2 );
	}

	/**
	 * Hook into post save to clear post content for specific post type and editor.
	 *
	 * This method checks if the post is being updated (not newly created),
	 * and if the post type matches the target type defined in BuilderFns::$post_type_tb.
	 * If the post is edited with Elementor, it clears the post_content directly
	 * from the database and clears the post cache.
	 *
	 * @param int     $post_ID ID of the post being saved.
	 * @param WP_Post $post    Post object.
	 *
	 * @return void
	 */
	public function on_save_post( $post_ID, $post ) {
		// Only proceed on update and specific post type.
		if ( BuilderFns::$post_type_tb !== $post->post_type ) {
			return;
		}
		if ( 'elementor' !== Fns::page_edit_with( $post_ID ) ) {
			return;
		}
		global $wpdb;
		// Directly update the post_content to empty.
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
		$wpdb->update(
			$wpdb->posts,
			[ 'post_content' => '' ],
			[ 'ID' => $post_ID ]
		);
		// Clear cache so changes reflect properly.
		 clean_post_cache( $post_ID );
	}

	/**
	 * Public function add_filter.
	 * Added search filter for type of template
	 *
	 * @since 1.0.0
	 */
	public function add_filter() {

		global $typenow;

		if ( BuilderFns::$post_type_tb != $typenow ) {
			return;
		}
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$selected            = isset( $_GET['type'] ) ? sanitize_key( $_GET['type'] ) : '';
		 $builder_page_types = BuilderFns::builder_page_types();
		?>
		<select name="template_type" id="type">
			<option value="all" <?php selected( 'all', $selected ); ?>><?php esc_html_e( 'Template Type ', 'shopbuilder' ); ?></option>
			<?php foreach ( $builder_page_types as $key => $value ) { ?>
				<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, $selected ); ?>><?php echo esc_html( $value ); ?></option>
			<?php } ?>
		</select>
		<?php
	}

	/**
	 * Post type function
	 *
	 * @return void
	 */
	public function template_builder_post_type() {
		/**
		 * Template Builder Post type
		 */
		$tb_labels = [
			'name'                  => esc_html_x( 'WooCommerce Page Builder', 'Post Type General Name', 'shopbuilder' ),
			'singular_name'         => esc_html_x( 'Templates Builder', 'Post Type Singular Name', 'shopbuilder' ),
			'menu_name'             => esc_html__( 'Templates Builder', 'shopbuilder' ),
			'name_admin_bar'        => esc_html__( 'Templates Builder', 'shopbuilder' ),
			'archives'              => esc_html__( 'WC Page Archives', 'shopbuilder' ),
			'attributes'            => esc_html__( 'Page Attributes', 'shopbuilder' ),
			'parent_item_colon'     => esc_html__( 'Parent Item:', 'shopbuilder' ),
			'all_items'             => esc_html__( 'Templates Builder', 'shopbuilder' ),
			'add_new_item'          => esc_html__( 'Add New Page', 'shopbuilder' ),
			'add_new'               => esc_html__( 'Add New', 'shopbuilder' ),
			'new_item'              => esc_html__( 'New Page', 'shopbuilder' ),
			'edit_item'             => esc_html__( 'Edit Page', 'shopbuilder' ),
			'update_item'           => esc_html__( 'Update Page', 'shopbuilder' ),
			'view_item'             => esc_html__( 'View Page', 'shopbuilder' ),
			'view_items'            => esc_html__( 'View Pages', 'shopbuilder' ),
			'search_items'          => esc_html__( 'Search Pages', 'shopbuilder' ),
			'not_found'             => esc_html__( 'Not found', 'shopbuilder' ),
			'not_found_in_trash'    => esc_html__( 'Not found in Trash', 'shopbuilder' ),
			'featured_image'        => esc_html__( 'Featured Image', 'shopbuilder' ),
			'set_featured_image'    => esc_html__( 'Set featured image', 'shopbuilder' ),
			'remove_featured_image' => esc_html__( 'Remove featured image', 'shopbuilder' ),
			'use_featured_image'    => esc_html__( 'Use as featured image', 'shopbuilder' ),
			'insert_into_item'      => esc_html__( 'Insert into page', 'shopbuilder' ),
			'uploaded_to_this_item' => esc_html__( 'Uploaded to this page', 'shopbuilder' ),
			'items_list'            => esc_html__( 'Pages list', 'shopbuilder' ),
			'items_list_navigation' => esc_html__( 'Pages list navigation', 'shopbuilder' ),
			'filter_items_list'     => esc_html__( 'Filter from list', 'shopbuilder' ),
		];

		$tb_args = [
			'label'              => esc_html__( 'Templates Builder', 'shopbuilder' ),
			'description'        => esc_html__( 'Shopbuilder Template', 'shopbuilder' ),
			'labels'             => $tb_labels,
			'supports'           => [ 'title', 'editor', 'elementor', 'author', 'permalink', 'comments' ],
			'hierarchical'       => false,
			'public'             => true,
			'show_ui'            => true,
			'show_in_menu'       => 'rtsb',
			'show_in_admin_bar'  => false,
			'show_in_nav_menus'  => true,
			'can_export'         => true,
			'has_archive'        => false,
			'rewrite'            => [
				'slug'       => 'rtsb-template',
				'pages'      => false,
				'with_front' => true,
				'feeds'      => false,
			],
			'query_var'          => true,
			'publicly_queryable' => true,
			// 'capability_type'    => 'product', Will Remove this line in the future.
			'show_in_rest'       => true,
			'rest_base'          => BuilderFns::$post_type_tb,
		];

		$tb_args = apply_filters( 'rtsb/builder/template_args', $tb_args );

		register_post_type( BuilderFns::$post_type_tb, $tb_args );

		// Flash rewrite rules.
		$this->flush_rewrite_rules();
	}

	/**
	 * Flush rewrite
	 *
	 * @return void
	 */
	public function flush_rewrite_rules() {
		if ( get_option( 'shopbuilder_permalinks_flushed', 'no' ) !== 'yes' ) {
			flush_rewrite_rules();
			update_option( 'shopbuilder_permalinks_flushed', 'yes' );
		}
	}

	/**
	 * Add new columns to the post table
	 *
	 * @param array $columns - Current columns on the list post.
	 */
	public function add_new_columns( $columns ) {
		$column_meta = [
			'type'        => esc_html__( 'Template Type', 'shopbuilder' ),
			'edit_with'   => esc_html__( 'Editor Type', 'shopbuilder' ),
			'set_default' => esc_html__( 'Set as Active', 'shopbuilder' ),
		];

		unset( $columns['comments'] );

		$columns = array_merge(
			array_slice( $columns, 0, 2 ),
			$column_meta,
			array_slice( $columns, 2 )
		);

		return $columns;
	}

	/**
	 * Display data in new columns
	 *
	 * @param string $column table column.
	 * @param string $post_id Post id.
	 *
	 * @return void
	 */
	public function custom_columns( $column, $post_id ) { // phpcs:ignore Generic.Metrics.NestingLevel.TooHigh
		$post_id       = absint( $post_id );
		$types         = BuilderFns::builder_page_types();
		$template_type = BuilderFns::builder_type( $post_id ) ? BuilderFns::builder_type( $post_id ) : array_key_first( $types );

		switch ( $column ) {
			case 'type':
				$the_type = ! empty( $types[ $template_type ] ) ? '<b>' . esc_html( ucwords( $types[ $template_type ] ) ) . '</b>' : '';
				$the_type = $this->template_type_suffix( $the_type, $template_type, $post_id );
				Fns::print_html( apply_filters( 'rtsb/columns/template/types/text', $the_type, $template_type, $post_id ) );
				break;
			case 'edit_with':
				$edit_by = Fns::page_edit_with( $post_id );
				echo esc_html( ucfirst( $edit_by ) );
				break;
			case 'set_default':
				$singleton_types = apply_filters(
					'rtsb/builder/singleton_types',
					array_keys( BuilderFns::builder_page_types() )
				);
				if ( in_array( $template_type, $singleton_types, true ) ) {
					$is_set_default = absint( BuilderFns::builder_page_id_by_type( $template_type ) );
				} else {
					// Pool type: read per-post meta flag — multiple templates of the same type can be enabled simultaneously.
					$is_set_default = 'on' === get_post_meta( $post_id, '_rtsb_tb_enabled_in_pool', true ) ? $post_id : 0;
				}
				$is_published  = 'publish' === get_post_status( $post_id );
				$page_type_for = $template_type;
				if ( 'product' === $template_type ) {
					$product_page_for = get_post_meta( $post_id, '_is_product_page_template_for', true );
					switch ( $product_page_for ) {
						case 'specific_products':
							$page_type_for = 'template-' . $post_id . '-specific-products';
							$set_default   = BuilderFns::get_specific_product_as_default( $post_id );
							if ( $set_default ) {
								$is_set_default = $post_id;
							}
							if ( ! $set_default && $is_set_default === $post_id ) {
								$is_set_default = '';
							}
							break;
						case 'product_cats':
							$page_type_for           = 'product-page-template-' . $post_id . '-specific-category';
							$set_default_option_name = BuilderFns::option_name_product_page_specific_cat_set_default( $post_id );
							$pp_cat_set_default      = TemplateSettings::instance()->get_option( $set_default_option_name );
							if ( $pp_cat_set_default ) {
								$is_set_default = $post_id;
							}
							break;
						case 'product_tags':
							$page_type_for           = 'product-page-template-' . $post_id . '-specific-tag';
							$set_default_option_name = BuilderFns::option_name_product_page_specific_tag_set_default( $post_id );
							$pp_tag_set_default      = TemplateSettings::instance()->get_option( $set_default_option_name );
							if ( $pp_tag_set_default ) {
								$is_set_default = $post_id;
							}
							break;
						case 'product_brands':
							$page_type_for           = 'product-page-template-' . $post_id . '-specific-brand';
							$set_default_option_name = BuilderFns::option_name_product_page_specific_brand_set_default( $post_id );
							$pp_brand_set_default    = TemplateSettings::instance()->get_option( $set_default_option_name );
							if ( $pp_brand_set_default ) {
								$is_set_default = $post_id;
							}
							break;
						default:
							break;
					}
				} elseif ( 'archive' === $template_type ) {
					$categories_name = apply_filters( 'rtsb/template/builder/selected/categories', [], $post_id, $template_type );
					$set_default     = BuilderFns::get_specific_category_as_default( $post_id );
					if ( ! empty( $categories_name ) && $set_default ) {
						$is_set_default = $post_id;
						$page_type_for  = 'template-' . $post_id . '-specific-category';
					} else {
						$option_name    = BuilderFns::option_name( $template_type );
						$is_set_default = TemplateSettings::instance()->get_option( $option_name );
						$set_default    = $is_set_default;
					}
					if ( ! $set_default && $is_set_default === $post_id ) {
						$is_set_default = '';
					}
				}

				$is_set_default = apply_filters( 'rtsb/builder/columns/set_default', $is_set_default, $post_id, $template_type );
				$page_type_for  = apply_filters( 'rtsb/builder/columns/page_type_for', $page_type_for, $post_id, $template_type );
				?>
				<span class="rtsb-switch-wrapper page-type-<?php echo esc_attr( $page_type_for ); ?>" <?php echo ! $is_published ? 'style="pointer-events: none;"' : null; ?> title="<?php esc_html_e( 'Only publish post can set default ', 'shopbuilder' ); ?>">
					<label class="rtsb-switch">
						<?php do_action( 'rtsb/set/default/column/extra/field', $post_id, $template_type, $is_set_default, $is_published ); ?>
						<div class="rtsb_template_type" data-template_type="<?php echo esc_attr( $template_type ); ?>" style='display:none;'></div>
						<input disabled="disabled" value="<?php echo absint( $post_id ); ?>" class="rtsb_set_default" name="set_default" type="checkbox" <?php echo esc_attr( $post_id === $is_set_default ? 'checked' : '' ); ?> >
						<span class="rtsb-slider rtsb-round ">
							<span class="rtsb-loader"></span>
						</span>
					</label>
				</span>
				<?php
				break;
		}
	}

	/**
	 * Appends template sub-type suffix and selected item details to the type column text.
	 *
	 * For product templates: shows "Template For Selected Products/Categories/Tags"
	 * with linked item names below.
	 * For archive templates: shows "Template For Selected Category" with linked names.
	 *
	 * @param string $the_type      The current type column HTML.
	 * @param string $template_type The template type slug.
	 * @param int    $post_id       The builder post ID.
	 *
	 * @return string
	 */
	private function template_type_suffix( $the_type, $template_type, $post_id ) {
		if ( 'product' === $template_type ) {
			$the_type = $this->product_template_type_suffix( $the_type, $post_id );
		} elseif ( 'archive' === $template_type ) {
			$the_type = $this->archive_template_type_suffix( $the_type, $post_id );
		}

		return $the_type;
	}

	/**
	 * Appends product template sub-type suffix with linked product/category/tag names.
	 *
	 * @param string $the_type The current type column HTML.
	 * @param int    $post_id  The builder post ID.
	 *
	 * @return string
	 */
	private function product_template_type_suffix( $the_type, $post_id ) {
		$product_page_for = get_post_meta( $post_id, '_is_product_page_template_for', true );
		$has_pro          = rtsb()->has_pro();
		$pro_label        = ! $has_pro ? ' <span class="rtsb-pro-label" style="color:#fff;background:#ff5722;padding:1px 6px;border-radius:3px;font-size:11px;font-weight:600;">' . esc_html__( 'Pro', 'shopbuilder' ) . '</span>' : '';

		switch ( $product_page_for ) {
			case 'specific_products':
				$the_type .= ' - ' . esc_html__( 'Template For Selected Products', 'shopbuilder' ) . $pro_label;
				if ( $has_pro ) {
					$the_type .= $this->get_selected_products_html( $post_id );
				}
				break;
			case 'product_cats':
				$the_type .= ' - ' . esc_html__( 'Template For Selected Categories', 'shopbuilder' ) . $pro_label;
				if ( $has_pro ) {
					$the_type .= $this->get_selected_terms_html( $post_id, 'product_cat' );
				}
				break;
			case 'product_tags':
				$the_type .= ' - ' . esc_html__( 'Template For Selected Tags', 'shopbuilder' ) . $pro_label;
				if ( $has_pro ) {
					$the_type .= $this->get_selected_terms_html( $post_id, 'product_tag' );
				}
				break;
		}

		return $the_type;
	}

	/**
	 * Appends archive template sub-type suffix with linked category names.
	 *
	 * @param string $the_type The current type column HTML.
	 * @param int    $post_id  The builder post ID.
	 *
	 * @return string
	 */
	private function archive_template_type_suffix( $the_type, $post_id ) {
		$option_name = BuilderFns::archive_option_name_by_template_id( $post_id );
		$categories  = TemplateSettings::instance()->get_option( $option_name );

		if ( ! empty( $categories ) && is_array( $categories ) ) {
			$has_pro   = rtsb()->has_pro();
			$pro_label = ! $has_pro ? ' <span class="rtsb-pro-label" style="color:#fff;background:#ff5722;padding:1px 6px;border-radius:3px;font-size:11px;font-weight:600;">' . esc_html__( 'Pro', 'shopbuilder' ) . '</span>' : '';
			$the_type .= ' - ' . esc_html__( 'Template For Selected Category', 'shopbuilder' ) . $pro_label;
			$the_type .= $this->get_selected_archive_categories_html( $categories );
		}

		return $the_type;
	}

	/**
	 * Gets HTML list of selected archive category names with links.
	 *
	 * @param array $category_slugs Array of term slugs.
	 *
	 * @return string
	 */
	private function get_selected_archive_categories_html( $category_slugs ) {
		if ( empty( $category_slugs ) || ! is_array( $category_slugs ) ) {
			return '';
		}

		$links = [];

		foreach ( $category_slugs as $cat_slug ) {
			$term = get_term_by( 'slug', $cat_slug, 'product_cat' );

			if ( is_wp_error( $term ) || ! $term ) {
				continue;
			}

			$term_link = get_term_link( $term );

			if ( is_wp_error( $term_link ) ) {
				continue;
			}

			$links[] = '<a href="' . esc_url( $term_link ) . '" target="_blank">'
				. esc_html( $term->name ) . '</a>';
		}

		if ( empty( $links ) ) {
			return '';
		}

		return '<br><small>' . implode( ', ', $links ) . '</small>';
	}

	/**
	 * Gets HTML list of selected product names with links to their detail pages.
	 *
	 * @param int $post_id The builder post ID.
	 *
	 * @return string
	 */
	private function get_selected_products_html( $post_id ) {
		$option_name = BuilderFns::option_name_by_template_id( $post_id );
		$product_ids = TemplateSettings::instance()->get_option( $option_name );

		if ( empty( $product_ids ) || ! is_array( $product_ids ) ) {
			return '';
		}

		$links = [];

		foreach ( $product_ids as $product_id ) {
			$product_id = absint( $product_id );
			$product    = wc_get_product( $product_id );

			if ( ! $product ) {
				continue;
			}

			$links[] = '<a href="' . esc_url( get_permalink( $product_id ) ) . '" target="_blank">'
				. esc_html( $product->get_name() ) . '</a>';
		}

		if ( empty( $links ) ) {
			return '';
		}

		return '<br><small>' . implode( ', ', $links ) . '</small>';
	}

	/**
	 * Gets HTML list of selected term names with links.
	 *
	 * @param int    $post_id  The builder post ID.
	 * @param string $taxonomy The taxonomy slug.
	 *
	 * @return string
	 */
	private function get_selected_terms_html( $post_id, $taxonomy ) {
		if ( 'product_cat' === $taxonomy ) {
			$option_name = BuilderFns::option_name_product_page_selected_cat( $post_id );
		} else {
			$option_name = BuilderFns::option_name_product_page_selected_tag( $post_id );
		}

		$term_ids = TemplateSettings::instance()->get_option( $option_name );

		if ( empty( $term_ids ) || ! is_array( $term_ids ) ) {
			return '';
		}

		$links = [];

		foreach ( $term_ids as $term_id ) {
			$term = get_term( absint( $term_id ), $taxonomy );

			if ( is_wp_error( $term ) || ! $term ) {
				continue;
			}

			$term_link = get_term_link( $term );

			if ( is_wp_error( $term_link ) ) {
				continue;
			}

			$links[] = '<a href="' . esc_url( $term_link ) . '" target="_blank">'
				. esc_html( $term->name ) . '</a>';
		}

		if ( empty( $links ) ) {
			return '';
		}

		return '<br><small>' . implode( ', ', $links ) . '</small>';
	}

	/**
	 * Register sortable columns
	 *
	 * @param [type] $columns column list.
	 *
	 * @return array
	 */
	public function register_sortable_columns( $columns ) {
		$columns['type'] = 'type';

		return $columns;
	}

	/**
	 * Meta sortable function.
	 *
	 * @param object $query query object.
	 *
	 * @return void
	 */
	public function sortable_columns_query( $query ) {
		if ( ! is_admin() || ! $this->is_current_screen() ) {
			return;
		}
		$orderby = $query->get( 'orderby' );
		if ( 'type' === $orderby ) {
			$query->set( 'meta_key', BuilderFns::template_type_meta_key() );
			$query->set( 'orderby', 'meta_value' );
		}
	}

	/**
	 * Check template screen
	 *
	 * @return boolean
	 */
	public function is_current_screen() {
		global $pagenow, $typenow;

		return 'edit.php' === $pagenow && BuilderFns::$post_type_tb === $typenow;
	}

	/**
	 * Manage Template filter by template type
	 *
	 * @param WP_Query $query WordPress main query.
	 *
	 * @return void
	 */
	public function query_filter( WP_Query $query ) {

		if ( ! is_admin() || ! $this->is_current_screen() || ! empty( $query->query_vars['meta_key'] ) ) {
			return;
		}
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$type = isset( $_GET['template_type'] ) ? sanitize_key( $_GET['template_type'] ) : '';

		if ( '' != $type && 'all' != $type ) {  // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$query->query_vars['meta_key']     = BuilderFns::template_type_meta_key(); // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			$query->query_vars['meta_value']   = $type; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
			$query->query_vars['meta_compare'] = '=';
		}
	}

	/**
	 * Load Template.
	 *
	 * @param string $template Template File.
	 *
	 * @return string
	 */
	public function builder_template( $template ) {
		$builder_page_id = BuilderFns::is_builder_preview() ? get_the_ID() : BuilderFns::builder_page_id_by_page();
		if ( defined( 'ELEMENTOR_VERSION' ) && $builder_page_id ) { // Elementor Check.
			$page_template = get_post_meta( $builder_page_id, '_wp_page_template', true );
			$new_template  = RTSB_ABSPATH . 'page-templates/builder-template.php';
			if ( 'elementor_canvas' === $page_template ) {
				$new_template = RTSB_ABSPATH . 'page-templates/elementor-canvas.php';
			} elseif ( 'elementor_header_footer' === $page_template ) {
				$new_template = RTSB_ABSPATH . 'page-templates/builder-template.php';
			}
			if ( file_exists( $new_template ) ) {
				$template = $new_template;
			}
		}

		return $template;
	}

	/**
	 * Restrict the preview access.
	 *
	 * @return void
	 */
	public function restrict_preview_access() {
		if ( ! apply_filters( 'rtsb/builder/preview/access', true ) ) {
			return;
		}

		if ( is_singular( 'rtsb_builder' ) ) {
			if ( ! is_user_logged_in() ) {
				wp_safe_redirect( home_url() );
			}
		}
	}

	/**
	 * Add/Remove edit link in dashboard.
	 *
	 * Add or remove an edit link to the post/page action links on the post/pages list table.
	 *
	 * Fired by `post_row_actions` and `page_row_actions` filters.
	 *
	 * @access public
	 *
	 * @param array $actions An array of row action links.
	 *
	 * @return array An updated array of row action links.
	 */
	public function filter_post_row_actions( $actions ) {
		if ( $this->is_current_screen() ) {
			unset( $actions['inline hide-if-no-js'] );

			if ( isset( $actions['view'] ) ) {
				$actions['view'] = str_replace( __( 'View', 'shopbuilder' ), __( 'Preview', 'shopbuilder' ), $actions['view'] );
			}
		}

		return $actions;
	}
}
