<?php

namespace RadiusTheme\SB\Controllers\Admin\Ajax;

use Elementor\Plugin;
use RadiusTheme\SB\Helpers\BuilderFns;
use RadiusTheme\SB\Helpers\Cache;
use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Models\TemplateSettings;
use RadiusTheme\SB\Traits\SingletonTrait;
use Elementor\Core\Files\Uploads_Manager;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Template Create Modal.
 */
class ModalTemplate {
	/**
	 * Singleton Trait.
	 */
	use SingletonTrait;

	/**
	 * Construct function
	 */
	private function __construct() {
		add_action( 'wp_ajax_rtsb_builder_modal_template', [ $this, 'response' ] );
		add_action( 'wp_ajax_rtsb_modal_product_search', [ $this, 'product_search' ] );
		add_action( 'wp_ajax_rtsb_modal_term_search', [ $this, 'term_search' ] );
	}

	/**
	 * Popups For Create Template
	 *
	 * @return void
	 */
	public function response() { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.MaxExceeded
		$title = '<h2>' . esc_html__( 'Template Settings', 'shopbuilder' ) . '</h2>';
		if ( ! wp_verify_nonce( Fns::get_nonce(), rtsb()->nonceText ) ) {
			$return = [
				'success' => false,
				'title'   => $title,
				'content' => esc_html__( 'Session Expired...', 'shopbuilder' ),
			];
			wp_send_json( $return );
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			$return = [
				'success' => false,
				'title'   => $title,
				'content' => esc_html__( 'Permission Denied...', 'shopbuilder' ),
			];
			wp_send_json( $return );
		}
		if ( ! defined( 'ELEMENTOR_VERSION' ) ) {
			$return = [
				'success' => true,
				'title'   => $title,
				'content' => '<h2 style="text-align: center;">' . esc_html__( 'Permission Denied. Please Install elementor', 'shopbuilder' ) . '</h2>',
			];

			wp_send_json( $return );
		}

		add_filter( 'option_' . Uploads_Manager::UNFILTERED_FILE_UPLOADS_KEY, '__return_true' );
		Plugin::$instance->files_manager->clear_cache();

		// user capability check.
		$user          = wp_get_current_user();
		$allowed_roles = [ 'editor', 'administrator', 'author' ];

		if ( ! array_intersect( $allowed_roles, Fns::get_current_user_roles() ) ) {
			wp_die( esc_html__( 'You don\'t have permission to perform this action', 'shopbuilder' ) );
		}

		// Check if the user is not logged in and the provided 'user_id' in the request does not match the currently logged-in user's ID.
		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated, WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.PHP.YodaConditions.NotYoda
		if ( ! is_user_logged_in() && $user->ID !== sanitize_text_field( $_REQUEST['user_id'] ) ) {
			wp_die( esc_html__( 'You don\'t have proper authorization to perform this action', 'shopbuilder' ) );
		}

		$post_id   = isset( $_REQUEST['post_id'] ) ? absint( $_REQUEST['post_id'] ) : null;
		$layout_id = isset( $_REQUEST['layout_id'] ) ? absint( $_REQUEST['layout_id'] ) : null;

		$template_type    = null;
		$product_page_for = 'all_products';
		$template_default = null;
		$url              = '';
		$tmp_title        = '';
		$_rtsb_import_id  = '';
		$edit_by          = did_action( 'elementor/loaded' ) ? 'elementor' : 'elementor';
		if ( $post_id ) {
			$tmp_title        = get_the_title( $post_id );
			$template_type    = get_post_meta( $post_id, BuilderFns::template_type_meta_key(), true );
			$options          = BuilderFns::option_name( $template_type );
			$template_default = TemplateSettings::instance()->get_option( $options );
			$edit_by          = Fns::page_edit_with( $post_id );
			$action           = 'edit';
			if ( rtsb()->has_pro() ) {
				$product_page_for = get_post_meta( $post_id, '_is_product_page_template_for', true );
			}

			/* This Condition Will remove after release Gutenberg Addons. */
			if ( 'gutenberg' == $edit_by ) {
				$action  = 'elementor';
				$edit_by = 'elementor';
			}

			if ( 'elementor' == $edit_by ) {
				$action = 'elementor';
			}

			$url = add_query_arg(
				[
					'post'   => $post_id,
					'action' => $action,
				],
				admin_url( 'post.php' )
			);

			$_rtsb_import_id = get_post_meta( $post_id, '_rtsb_import_id', true );
		}
		ob_start();

		?>
		<form action="<?php echo esc_url( admin_url( 'edit.php?post_type=rtsb_builder' ) ); ?>" autocomplete="off" data-imported-id="<?php echo esc_attr( $_rtsb_import_id ); ?>">
			<div class="rtsb-tb-modal-wrapper">
				<div class="rtsb-template-name rtsb-tb-field-wraper">
					<label for="rtsb_tb_template_name"> <?php esc_html_e( 'Template Name', 'shopbuilder' ); ?></label>
					<input
							required
							class="rtsb-field"
							type="text"
							id="rtsb_tb_template_name"
							name="rtsb_tb_template_name"
							placeholder="<?php esc_attr_e( 'Enter Template Name', 'shopbuilder' ); ?>"
							value="<?php echo esc_attr( $tmp_title ); ?>"
							autocomplete="off"
					>
					<span class="message" style="display: none; color:red"><?php esc_html_e( 'This field is required', 'shopbuilder' ); ?></span>
				</div>
				<div class="rtsb-template-type rtsb-tb-field-wraper">
					<label for="rtsb_tb_template_type"><?php esc_html_e( 'Select Template Type', 'shopbuilder' ); ?></label>
					<select class="rtsb-field" id="rtsb_tb_template_type" name="rtsb_tb_template_type">
						<?php
						$builder_page_types = BuilderFns::builder_page_types();
						foreach ( $builder_page_types as $key => $value ) {
							?>
							<option <?php echo esc_attr( $key === $template_type ? 'selected="selected"' : '' ); ?> value="<?php echo esc_attr( $key ); ?>"> <?php echo esc_html( $value ); ?> </option>
						<?php } ?>
					</select>
				</div>

				<div class="rtsb-product-page-for rtsb-tb-field-wraper  <?php echo esc_attr( ! rtsb()->has_pro() ? 'pro-disable' : '' ); ?>" style="display:none;" >
					<label for="product_page_for">
						<?php esc_html_e( 'Product Template For', 'shopbuilder' ); ?>
						<?php if ( ! rtsb()->has_pro() ) { ?>
							<div class="card-label">
								<span class="rtsb-btn-import pro-btn">
									<?php echo esc_html( 'PRO' ); ?>
								</span>
							</div>
						<?php } ?>
					</label>

					<select class="rtsb-field" id="product_page_for" name="product_page_for">
						<?php
						$builder_page_types = [
							'all_products'      => esc_html__( 'All Products', 'shopbuilder' ),
							'product_cats'      => esc_html__( 'Product Categories', 'shopbuilder' ),
							'product_brands'    => esc_html__( 'Product Brands', 'shopbuilder' ),
							'product_tags'      => esc_html__( 'Product Tags', 'shopbuilder' ),
							'specific_products' => esc_html__( 'Selected Products', 'shopbuilder' ),
						];
						// Only expose the brand option when the `product_brand` taxonomy is
						// registered (WC 9.4+ native, or a brand plugin). Hides gracefully
						// on sites without brand support.
						if ( ! taxonomy_exists( 'product_brand' ) ) {
							unset( $builder_page_types['product_brands'] );
						}
						foreach ( $builder_page_types as $key => $value ) {
							?>
							<option <?php echo esc_attr( $key === $product_page_for ? 'selected="selected"' : '' ); ?> value="<?php echo esc_attr( $key ); ?>"> <?php echo esc_html( $value ); ?> </option>
						<?php } ?>
					</select>
				</div>

				<?php if ( ! rtsb()->has_pro() ) { ?>
					<div class="rtsb-product-page-for rtsb-tb-field-wraper" style="display:none;" >
						<label for="product_page_for">  </label>
						<span class="elementor-pro-notice"><a target="_blank" href="<?php echo esc_url( rtsb()->pro_version_link() ); ?>">Upgrade to PRO</a> to unlock Product Categories, Product Tags, Selected Products</span>
					</div>
				<?php } ?>

				<!-- Product Page For Categories -->
				<div class="rtsb-categories-page-field rtsb-product-page-for-the-categories rtsb-tb-field-wraper <?php echo esc_attr( ! rtsb()->has_pro() ? 'pro-disable' : '' ); ?>" style="display:none;">
					<?php
					$default_items_link = '';
					$default_items      = '';
					$categories_name    = apply_filters( 'rtsb/template/builder/selected/categories', [], $post_id, $template_type );
					if ( ! empty( $categories_name ) && is_array( $categories_name ) ) {
						foreach ( $categories_name as $c_name ) {
							$cat       = get_term_by( 'slug', $c_name, 'product_cat' );
							$title_cat = $cat->name;
							$link      = get_term_link( $cat );
							if ( ! is_wp_error( $link ) ) {
								$default_items_link .= '<a target="_blank" href="' . get_term_link( $cat ) . '">' . $title_cat . '</a>';
							}
							$default_items .= '<option selected="selected" value="' . $c_name . '">' . $title_cat . '</option>';
						}
					}
					?>
					<label for="rtsb_page_for_the_products">
						<?php esc_html_e( 'Select Product Categories', 'shopbuilder' ); ?>
						<?php if ( ! rtsb()->has_pro() ) { ?>
							<div class="card-label">
								<span class="rtsb-btn-import pro-btn">
									<?php echo esc_html( 'PRO' ); ?>
								</span>
							</div>
						<?php } ?>
					</label>
					<select class="rtsb-field" id="rtsb_page_for_the_categories" name="rtsb_page_for_the_categories" multiple="multiple">
						<?php Fns::print_html( $default_items, true ); ?>
					</select>
					<p style="margin:0;">Select a Product Category to apply this template. You can select multiple Categories. Keep it blank to apply to all Categories.
						<?php
						if ( ! empty( $default_items_link ) ) {
							?>
							<span style="display: flex; gap: 3px 7px; flex-wrap: wrap; margin-top: 10px;"> <?php Fns::print_html( 'View Category Archive Pages: ' . $default_items_link ); ?> </span>
							<?php
						}
						?>
					</p>
				</div>

				<?php if ( ! rtsb()->has_pro() ) { ?>
					<div class="rtsb-categories-page-field rtsb-product-page-for-the-categories rtsb-tb-field-wraper" style="display:none;">
						<label for="product_page_for">  </label>
						<span class="elementor-pro-notice"><a target="_blank" href="<?php echo esc_url( rtsb()->pro_version_link() ); ?>">Upgrade to PRO</a> to unlock Categories Base Prodcut Page.</span>
					</div>
				<?php } ?>

				<!-- Product Page for Selected Product Tags-->
				<div class="rtsb-product-tags-page-field rtsb-product-page-for-the-tags rtsb-tb-field-wraper <?php echo esc_attr( ! rtsb()->has_pro() ? 'pro-disable' : '' ); ?>" style="display:none;">
					<?php
					$default_items_link = '';
					$default_items      = '';
					$tags_name          = apply_filters( 'rtsb/template/builder/selected/tags', [], $post_id, $template_type );
					if ( ! empty( $tags_name ) && is_array( $tags_name ) ) {
						foreach ( $tags_name as $tg_name ) {
							$tag       = get_term_by( 'slug', $tg_name, 'product_tag' );
							$title_tag = $tag->name;
							$link      = get_term_link( $tag );
							if ( ! is_wp_error( $link ) ) {
								$default_items_link .= '<a target="_blank" href="' . get_term_link( $tag ) . '">' . $title_tag . '</a>';
							}
							$default_items .= '<option selected="selected" value="' . $tg_name . '">' . $title_tag . '</option>';
						}
					}
					?>
					<label for="rtsb_page_for_the_products_tags">
						<?php esc_html_e( 'Select Product Tags', 'shopbuilder' ); ?>
						<?php if ( ! rtsb()->has_pro() ) { ?>
							<div class="card-label">
								<span class="rtsb-btn-import pro-btn">
									<?php echo esc_html( 'PRO' ); ?>
								</span>
							</div>
						<?php } ?>
					</label>
					<select class="rtsb-field" id="rtsb_page_for_the_products_tags" name="rtsb_page_for_the_products_tags" multiple="multiple">
						<?php Fns::print_html( $default_items, true ); ?>
					</select>
					<p style="margin:0;">Select a Product Tags to apply this template. You can select multiple Tags. Keep it blank to apply to All.
						<?php
						if ( ! empty( $default_items_link ) ) {
							?>
							<span style="display: flex; gap: 3px 7px; flex-wrap: wrap; margin-top: 10px;"> <?php Fns::print_html( 'View Tags Archive Pages: ' . $default_items_link ); ?> </span>
							<?php
						}
						?>
					</p>
				</div>


				<!-- Product Page for Selected Product Brands -->
				<?php if ( taxonomy_exists( 'product_brand' ) ) : ?>
				<div class="rtsb-product-brands-page-field rtsb-tb-field-wraper <?php echo esc_attr( ! rtsb()->has_pro() ? 'pro-disable' : '' ); ?>" style="display:none;">
					<?php
					$default_items_link = '';
					$default_items      = '';
					$brands_name        = apply_filters( 'rtsb/template/builder/selected/brands', [], $post_id, $template_type );
					if ( ! empty( $brands_name ) && is_array( $brands_name ) ) {
						foreach ( $brands_name as $b_name ) {
							$brand = get_term_by( 'slug', $b_name, 'product_brand' );
							if ( empty( $brand ) || is_wp_error( $brand ) ) {
								continue;
							}
							$title_brand = $brand->name;
							$link        = get_term_link( $brand );
							if ( ! is_wp_error( $link ) ) {
								$default_items_link .= '<a target="_blank" href="' . esc_url( $link ) . '">' . esc_html( $title_brand ) . '</a>';
							}
							$default_items .= '<option selected="selected" value="' . esc_attr( $b_name ) . '">' . esc_html( $title_brand ) . '</option>';
						}
					}
					?>
					<label for="rtsb_page_for_the_product_brands">
						<?php esc_html_e( 'Select Product Brands', 'shopbuilder' ); ?>
						<?php if ( ! rtsb()->has_pro() ) { ?>
							<div class="card-label">
								<span class="rtsb-btn-import pro-btn">
									<?php echo esc_html( 'PRO' ); ?>
								</span>
							</div>
						<?php } ?>
					</label>
					<select class="rtsb-field" id="rtsb_page_for_the_product_brands" name="rtsb_page_for_the_product_brands" multiple="multiple">
						<?php Fns::print_html( $default_items, true ); ?>
					</select>
					<p style="margin:0;">Select a Product Brand to apply this template. You can select multiple Brands. Keep it blank to apply to all.
						<?php
						if ( ! empty( $default_items_link ) ) {
							?>
							<span style="display: flex; gap: 3px 7px; flex-wrap: wrap; margin-top: 10px;"> <?php Fns::print_html( 'View Brand Archive Pages: ' . $default_items_link ); ?> </span>
							<?php
						}
						?>
					</p>
				</div>

					<?php if ( ! rtsb()->has_pro() ) { ?>
						<div class="rtsb-product-brands-page-field rtsb-tb-field-wraper" style="display:none;">
							<label for="product_page_for">  </label>
							<span class="elementor-pro-notice"><a target="_blank" href="<?php echo esc_url( rtsb()->pro_version_link() ); ?>">Upgrade to PRO</a> to unlock Brand Base Product Page.</span>
						</div>
					<?php } ?>
				<?php endif; ?>

				<?php do_action( 'rtsb/builder/modal/extra_fields', $post_id, $template_type, $product_page_for ); ?>

				<!-- Product Page for Selected Product -->
				<div class="rtsb-page-for-the-products rtsb-tb-field-wraper <?php echo esc_attr( ! rtsb()->has_pro() ? 'pro-disable' : '' ); ?>" style="display:none;">
					<?php
					$default_items_link = '';
					$default_items      = '';
					$product_ids        = apply_filters( 'rtsb/product/page/builder/selected/products', [], $post_id );
					if ( ! empty( $product_ids ) && is_array( $product_ids ) ) {
						foreach ( $product_ids as $p_id ) {
							$p_title             = get_the_title( $p_id );
							$default_items_link .= '<a target="_blank" href="' . get_the_permalink( $p_id ) . '">' . $p_title . '</a>';
							$default_items      .= '<option selected="selected" value="' . $p_id . '">' . $p_title . ' - ( ID ' . $p_id . ' )</option>';
						}
					}
					?>
					<label for="rtsb_page_for_the_products "><?php esc_html_e( 'Select Products ', 'shopbuilder' ); ?>
						<?php if ( ! rtsb()->has_pro() ) { ?>
							<div class="card-label">
								<span class="rtsb-btn-import pro-btn">
									<?php echo esc_html( 'PRO' ); ?>
								</span>
							</div>
						<?php } ?>
					</label>
					<select class="rtsb-field" id="rtsb_page_for_the_products" name="rtsb_page_for_the_products" multiple="multiple">
						<?php Fns::print_html( $default_items, true ); ?>
					</select>
					<p style="margin:0;">Select a Product to apply this template. You can select multiple Products. Keep it blank to apply to all Products.
						<?php
						if ( ! empty( $default_items_link ) ) {
							?>
							<span style="display: flex; gap: 3px 7px; flex-wrap: wrap; margin-top: 10px;"> <?php Fns::print_html( 'View Product Pages: ' . $default_items_link ); ?> </span>
							<?php
						}
						?>
					</p>
				</div>

				<div class="rtsb-product-page-preview-field rtsb-tb-field-wraper" style="display:none;">
					<?php
					$default_items = '';
					$product_id    = get_post_meta( $post_id, BuilderFns::$product_template_meta, true );
					if ( $product_id && get_post_status( $product_id ) ) {
						$default_items = '<option selected="selected" value="' . $product_id . '">' . get_the_title( $product_id ) . ' - ( ID ' . $product_id . ' )</option>';
					}
					if ( $product_id && ! get_post_status( $product_id ) ) {
						delete_post_meta( $post_id, BuilderFns::$product_template_meta );
					}
					?>
					<label for="rtsb_product_page_preview"><?php esc_html_e( 'Select Preview Product', 'shopbuilder' ); ?></label>
					<select class="rtsb-field" id="rtsb_product_page_preview" name="rtsb_product_page_preview">
						<?php Fns::print_html( $default_items, true ); ?>
					</select>
					<p style="margin:0;">Select a Product for preview and Elementor editing purposes only.</p>
				</div>


				<div class="rtsb-template-edit-with rtsb-tb-field-wraper">
					<label for="rtsb_tb_template_edit_with"><?php esc_html_e( 'Select Editor Type', 'shopbuilder' ); ?></label>
					<select class="rtsb-field" id="rtsb_tb_template_edit_with" name="rtsb_tb_template_edit_with"
							required>
						<option value="elementor" <?php echo 'elementor' === $edit_by ? 'selected="selected"' : ''; ?> ><?php esc_html_e( 'Elementor', 'shopbuilder' ); ?></option>
						<!-- disabled-->
						<option disabled value="gutenberg" <?php echo 'gutenberg' === $edit_by ? 'selected="selected"' : ''; ?> ><?php esc_html_e( 'Gutenberg (Coming Soon)', 'shopbuilder' ); ?></option>
					</select>
				</div>
				<div class="rtsb-template-setdefaults rtsb-tb-field-wraper">
					<label for="default_template"> <?php esc_html_e( 'Set as Active Template', 'shopbuilder' ); ?></label>
					<?php
					$singleton_types = apply_filters(
						'rtsb/builder/singleton_types',
						array_keys( BuilderFns::builder_page_types() )
					);
					$is_pool_type    = $template_type && ! in_array( $template_type, $singleton_types, true );
					if ( $post_id && $is_pool_type ) {
						$template_default = 'on' === get_post_meta( $post_id, '_rtsb_tb_enabled_in_pool', true ) ? $post_id : 0;
					}
					if ( $post_id && 'product' === $template_type ) {
						if ( 'specific_products' === $product_page_for ) {
							$set_default = BuilderFns::get_specific_product_as_default( $post_id );
							if ( $set_default ) {
								$template_default = $post_id;
							}
							if ( ! $set_default && $template_default === $post_id ) {
								$template_default = '';
							}
						} else {
							$set_default_option_name = '';
							if ( 'product_cats' === $product_page_for ) {
								$set_default_option_name = BuilderFns::option_name_product_page_specific_cat_set_default( $post_id );
							} elseif ( 'product_tags' === $product_page_for ) {
								$set_default_option_name = BuilderFns::option_name_product_page_specific_tag_set_default( $post_id );
							} elseif ( 'product_brands' === $product_page_for ) {
								$set_default_option_name = BuilderFns::option_name_product_page_specific_brand_set_default( $post_id );
							}
							$default_id = ! empty( $set_default_option_name ) && TemplateSettings::instance()->get_option( $set_default_option_name );
							if ( $default_id ) {
								$template_default = $post_id;
							}
						}
					} elseif ( $post_id && 'archive' === $template_type ) {
						$categories_name = apply_filters( 'rtsb/template/builder/selected/categories', [], $post_id, $template_type );
						$set_default     = BuilderFns::get_specific_category_as_default( $post_id );
						if ( ! empty( $categories_name ) && $set_default ) {
							$template_default = $post_id;
						} else {
							$option_name    = BuilderFns::option_name( $template_type );
							$is_set_default = TemplateSettings::instance()->get_option( $option_name );
							$set_default    = $is_set_default;
						}
						if ( ! $set_default && $template_default === $post_id ) {
							$template_default = '';
						}
					}

					$template_default = apply_filters( 'rtsb/builder/modal/template_default', $template_default, $post_id, $template_type );
					?>
					<span class="rtsb-switch-wrapper"
						  title="<?php esc_html_e( 'Switch on to set as active template', 'shopbuilder' ); ?>">
						<label class="rtsb-switch">
							<input class="rtsb_set_default rtsb-field"
								   type="checkbox"
								   id="default_template"
								   name="default_template"
								   value="default_template"
									<?php echo esc_attr( ( $post_id && ( absint( $post_id ) === absint( $template_default ) ) ) ? 'checked' : '' ); ?>
						>
							<span class="rtsb-slider rtsb-round ">
								<span class="rtsb-loader"></span>
							</span>
						</label>
					</span>
				</div>
				<!--                TODO: Moday Layout -->
				<?php
				if ( ! $post_id ) {
					if ( defined( 'ELEMENTOR_PATH' ) && class_exists( '\Elementor\Utils' ) ) {
						$placeholderImageSrc = \Elementor\Utils::get_placeholder_image_src();
					} else {
						$placeholderImageSrc = '#';
					}
					?>
					<div class="set-default-layout-wrapper rtsb-tb-field-wraper" style="opacity: 0" >
						<div style="display: flex; justify-content: space-between;">
							<label>
								<?php esc_html_e( 'Pre-built Templates ', 'shopbuilder' ); ?>
								<span id="modallabelPrefix"></span>
							</label>
							<div class="rtsb-tb-button-wrapper">
								<a class="btn" href="https://shopbuilderwp.com/themes/" target="_blank" style="opacity: 1; visibility: visible;">View Out Themes</a>
							</div>
						</div>
						<div class="set-default-layout" data-has-pro="<?php echo esc_attr( rtsb()->has_pro() ); ?>"  data-rest-url="<?php echo esc_url( rtsb()->BASE_API ); ?>" data-placeholder-image-src="<?php echo esc_url( $placeholderImageSrc ); ?>">
							<!-- Layout Will Add Via Ajax-->
						</div>
					</div>
				<?php } ?>
				<input type="hidden" id="page_id" name="page_id" value="<?php echo esc_attr( $post_id ); ?>">
			</div>
		</form>
		<?php
		$content = ob_get_clean();
		ob_start();
		?>
		<div class="rtsb-template-footer">
			<div class="rtsb-tb-button-wrapper save-button">
				<button class="rtsb-import-layout" <?php echo esc_attr( $post_id ? 'disabled' : '' ); ?> type="submit" id="rtsb_tb_button">
					<?php esc_html_e( 'Save', 'shopbuilder' ); ?>
				</button>
			</div>
			<div class="rtsb-tb-button-wrapper rtsb-tb-edit-button-wrapper">
				<a href="<?php echo esc_url( $url ); ?>" class="btn">
					<?php
					printf(
						/* translators: %s: Edit */
						esc_html__( 'Edit with %s', 'shopbuilder' ),
						esc_html( $edit_by )
					);
					?>
				</a>
			</div>
		</div>
		<?php
		$footer = ob_get_clean();
		$return = [
			'success' => true,
			'title'   => $title,
			'content' => $content,
			'footer'  => $footer,
		];
		wp_send_json( $return );
		wp_die();
	}

	/**
	 * Get Modal Card Markup.
	 *
	 * @param array $item Item.
	 *
	 * @return void
	 */
	public function get_modal_card_html( $item ) {
		?>
		<div class="layout-container rtsb-template-item type-<?php echo esc_attr( $item['template_type'] ); ?>"
			 data-layout-type='<?php echo esc_attr( $item['template_type'] ); ?>'>
			<input class="rtsb-field" type="hidden" value="<?php echo esc_attr( $item['id'] ?? '' ); ?>" name="import_default_layout"/>
			<div class="image-wrapper">
				<?php if ( $item['image_url'] ) : ?>
					<img src="<?php echo esc_url( $item['image_url'] ); ?>" alt="<?php echo esc_attr( $item['title'] ); ?>">
				<?php endif; ?>

				<?php if ( 'default' != $item['template_type'] && ( ! rtsb()->has_pro() ) ) : ?>
					<div class="card-label lllllll">
						label
					<span class="rtsb-btn-import <?php echo esc_attr( $item['status'] ?? 'free' ); ?>-btn">
						<?php echo esc_html( $item['status'] ?? 'Free' ); ?>
					</span>
					</div>
				<?php endif; ?>

				<div class="import-btn-wrap">
					<?php if ( $item['preview_link'] ) : ?>
						<a class="rtsb-btn-import preview-btn" target="_blank" href="<?php echo esc_url( $item['preview_link'] ); ?>">
							<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M19 0H13C12.7348 0 12.4804 0.105357 12.2929 0.292893C12.1054 0.48043 12 0.734783 12 1C12 1.26522 12.1054 1.51957 12.2929 1.70711C12.4804 1.89464 12.7348 2 13 2H16.586L8.293 10.293C8.19749 10.3852 8.12131 10.4956 8.0689 10.6176C8.01649 10.7396 7.9889 10.8708 7.98775 11.0036C7.9866 11.1364 8.0119 11.2681 8.06218 11.391C8.11246 11.5138 8.18671 11.6255 8.28061 11.7194C8.3745 11.8133 8.48615 11.8875 8.60905 11.9378C8.73194 11.9881 8.86362 12.0134 8.9964 12.0122C9.12918 12.0111 9.2604 11.9835 9.38241 11.9311C9.50441 11.8787 9.61475 11.8025 9.707 11.707L18 3.414V7C18 7.26522 18.1054 7.51957 18.2929 7.70711C18.4804 7.89464 18.7348 8 19 8C19.2652 8 19.5196 7.89464 19.7071 7.70711C19.8946 7.51957 20 7.26522 20 7V1C20 0.734783 19.8946 0.48043 19.7071 0.292893C19.5196 0.105357 19.2652 0 19 0Z"
									  fill="black"></path>
								<path d="M17 8.588C16.7348 8.588 16.4804 8.69336 16.2929 8.88089C16.1054 9.06843 16 9.32278 16 9.588V16.751C15.9997 17.0822 15.8681 17.3997 15.6339 17.6339C15.3997 17.8681 15.0822 17.9997 14.751 18H3.249C2.91783 17.9997 2.60029 17.8681 2.36612 17.6339C2.13194 17.3997 2.00026 17.0822 2 16.751V5.249C2.00026 4.91783 2.13194 4.60029 2.36612 4.36612C2.60029 4.13194 2.91783 4.00026 3.249 4H9.471C9.73621 4 9.99057 3.89464 10.1781 3.70711C10.3656 3.51957 10.471 3.26522 10.471 3C10.471 2.73478 10.3656 2.48043 10.1781 2.29289C9.99057 2.10536 9.73621 2 9.471 2H3.249C2.38764 2.00106 1.56186 2.3437 0.952779 2.95278C0.343703 3.56186 0.00105851 4.38764 0 5.249V16.749C0.000529143 17.6107 0.34294 18.437 0.952073 19.0465C1.56121 19.656 2.38729 19.9989 3.249 20H14.749C15.6107 19.9995 16.437 19.6571 17.0465 19.0479C17.656 18.4388 17.9989 17.6127 18 16.751V9.588C18 9.32278 17.8946 9.06843 17.7071 8.88089C17.5196 8.69336 17.2652 8.588 17 8.588Z"
									  fill="black"></path>
							</svg>
							<?php echo esc_html__( 'Preview', 'shopbuilder' ); ?>
						</a>


						<?php
						// phpcs:ignore WordPress.PHP.YodaConditions.NotYoda
						if ( rtsb()->has_pro() || $item['status'] == 'free' ) :
							?>
							<a class="rtsb-btn-import import-btn rtsb-import-layout">
								<svg width="22" height="20" viewBox="0 0 22 20" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M11 12.2295C10.9048 12.2296 10.8106 12.2113 10.7226 12.1757C10.6347 12.14 10.5547 12.0878 10.4874 12.0219C10.4201 11.956 10.3668 11.8778 10.3304 11.7917C10.294 11.7056 10.2753 11.6134 10.2754 11.5202L10.2754 0.912773C10.2754 0.72466 10.3517 0.544251 10.4876 0.411235C10.6235 0.278219 10.8078 0.203491 11 0.203491C11.1922 0.203491 11.3765 0.278219 11.5124 0.411235C11.6483 0.544251 11.7246 0.72466 11.7246 0.912773L11.7246 11.5202C11.7246 11.7083 11.6483 11.8887 11.5124 12.0218C11.3765 12.1548 11.1922 12.2295 11 12.2295Z"
										  fill="white"></path>
									<path fill-rule="evenodd" clip-rule="evenodd"
										  d="M11.0002 12.4295C10.8793 12.4296 10.7595 12.4064 10.6475 12.361C10.5356 12.3157 10.4336 12.2491 10.3475 12.1648C10.2614 12.0806 10.1929 11.9803 10.1461 11.8696C10.0994 11.7589 10.0753 11.6402 10.0754 11.5201C10.0754 11.5201 10.0754 11.5201 10.0754 11.52L10.2748 11.5202H10.0754L10.0754 11.5201L10.0754 0.91276C10.0754 0.670205 10.1738 0.438473 10.3477 0.268295C10.5214 0.0982601 10.7561 0.003479 11 0.003479C11.2439 0.003479 11.4786 0.0982601 11.6523 0.268295C11.8262 0.438473 11.9246 0.670205 11.9246 0.91276L11.9246 11.5202C11.9246 11.7628 11.8262 11.9945 11.6523 12.1647C11.4786 12.3347 11.244 12.4294 11.0002 12.4295ZM10.7226 12.1756C10.8106 12.2113 10.9048 12.2296 11 12.2295C11.1922 12.2295 11.3765 12.1548 11.5124 12.0217C11.6483 11.8887 11.7246 11.7083 11.7246 11.5202L11.7246 0.91276C11.7246 0.724647 11.6483 0.544239 11.5124 0.411223C11.3765 0.278207 11.1922 0.203479 11 0.203479C10.8078 0.203479 10.6235 0.278207 10.4876 0.411223C10.3517 0.544239 10.2754 0.724647 10.2754 0.91276L10.2754 11.5202C10.2753 11.6134 10.294 11.7056 10.3304 11.7917C10.3668 11.8778 10.4201 11.956 10.4874 12.0219C10.5547 12.0878 10.6347 12.14 10.7226 12.1756Z"
										  fill="white"></path>
									<path d="M11 12.5484C10.7565 12.5488 10.5152 12.5021 10.2902 12.4109C10.0652 12.3198 9.86076 12.186 9.6887 12.0173L5.77508 8.18661C5.63924 8.05365 5.56293 7.87332 5.56293 7.68529C5.56293 7.49726 5.63924 7.31693 5.77508 7.18397C5.91091 7.05101 6.09515 6.97632 6.28725 6.97632C6.47935 6.97632 6.66359 7.05101 6.79942 7.18397L10.713 11.0147C10.7507 11.0515 10.7955 11.0808 10.8447 11.1008C10.8939 11.1207 10.9467 11.131 11 11.131C11.0533 11.131 11.1061 11.1207 11.1553 11.1008C11.2045 11.0808 11.2493 11.0515 11.287 11.0147L15.2006 7.18397C15.3372 7.05473 15.5202 6.98319 15.7102 6.98475C15.9002 6.98632 16.082 7.06086 16.2164 7.19233C16.3508 7.3238 16.427 7.50168 16.4288 7.68765C16.4305 7.87362 16.3575 8.0528 16.2255 8.18661L12.3113 12.0173C12.1392 12.186 11.9348 12.3197 11.7098 12.4108C11.4847 12.502 11.2435 12.5487 11 12.5484Z"
										  fill="white"></path>
									<path fill-rule="evenodd" clip-rule="evenodd"
										  d="M11.287 11.0146L15.2006 7.18396C15.3372 7.05472 15.5202 6.98318 15.7102 6.98474C15.9002 6.9863 16.082 7.06085 16.2164 7.19232C16.3508 7.32379 16.4271 7.50166 16.4288 7.68763C16.4305 7.87361 16.3575 8.05279 16.2255 8.1866L12.3113 12.0173C12.1392 12.1859 11.9348 12.3197 11.7098 12.4108C11.4847 12.502 11.2435 12.5487 11 12.5484C10.7565 12.5488 10.5153 12.5021 10.2902 12.4109C10.0652 12.3198 9.86076 12.186 9.6887 12.0173L5.77508 8.1866C5.63924 8.05364 5.56293 7.87331 5.56293 7.68528C5.56293 7.49725 5.63924 7.31692 5.77508 7.18396C5.91092 7.051 6.09515 6.97631 6.28725 6.97631C6.47936 6.97631 6.66359 7.051 6.79943 7.18396L10.713 11.0146C10.7507 11.0515 10.7955 11.0808 10.8447 11.1008C10.8939 11.1207 10.9467 11.131 11 11.131C11.0533 11.131 11.1061 11.1207 11.1553 11.1008C11.2045 11.0808 11.2493 11.0515 11.287 11.0146ZM12.4513 12.1601L16.3654 8.32954L16.3679 8.32703C16.5368 8.1558 16.6309 7.92559 16.6287 7.6858C16.6266 7.44602 16.5282 7.21755 16.3563 7.04935C16.1845 6.88129 15.953 6.78673 15.7119 6.78475C15.4708 6.78276 15.2378 6.8735 15.0631 7.03866L11.1471 10.8717C11.1281 10.8902 11.1054 10.9052 11.0802 10.9154C11.0549 10.9257 11.0276 10.931 11 10.931C10.9724 10.931 10.9451 10.9257 10.9199 10.9154C10.8946 10.9052 10.8719 10.8902 10.853 10.8717L6.93933 7.04103C6.76567 6.87106 6.53103 6.77631 6.28725 6.77631C6.04347 6.77631 5.80884 6.87106 5.63518 7.04103C5.46138 7.21115 5.36293 7.44281 5.36293 7.68528C5.36293 7.92775 5.46138 8.15941 5.63518 8.32953L9.54868 12.1601C9.73954 12.3472 9.96607 12.4954 10.2151 12.5963C10.4641 12.6971 10.7308 12.7488 11 12.7484"
										  fill="white"></path>
									<path d="M19.0788 19H2.92116C2.41182 18.9994 1.92353 18.8011 1.56337 18.4486C1.20322 18.0961 1.00061 17.6181 1 17.1196V12.1614C1 11.9733 1.07635 11.7929 1.21224 11.6599C1.34814 11.5269 1.53245 11.4521 1.72464 11.4521C1.91682 11.4521 2.10114 11.5269 2.23703 11.6599C2.37293 11.7929 2.44928 11.9733 2.44928 12.1614V17.1196C2.44943 17.242 2.49919 17.3594 2.58766 17.446C2.67612 17.5326 2.79605 17.5813 2.92116 17.5815H19.0788C19.2039 17.5813 19.3239 17.5326 19.4123 17.446C19.5008 17.3594 19.5506 17.242 19.5507 17.1196V12.1614C19.5507 11.9733 19.6271 11.7929 19.763 11.6599C19.8989 11.5269 20.0832 11.4521 20.2754 11.4521C20.4675 11.4521 20.6519 11.5269 20.7878 11.6599C20.9237 11.7929 21 11.9733 21 12.1614V17.1196C20.9994 17.6181 20.7968 18.0961 20.4366 18.4486C20.0765 18.8011 19.5882 18.9994 19.0788 19Z"
										  fill="white"></path>
									<path fill-rule="evenodd" clip-rule="evenodd"
										  d="M19.0788 19.2H2.92116C2.36023 19.1994 1.82139 18.981 1.42348 18.5915C1.02542 18.2019 0.800684 17.6727 0.800003 17.1198L0.800003 12.1614C0.800003 11.9189 0.898484 11.6871 1.07235 11.517C1.24606 11.3469 1.48078 11.2521 1.72464 11.2521C1.9685 11.2521 2.20322 11.3469 2.37694 11.517C2.5508 11.6871 2.64928 11.9189 2.64928 12.1614L2.64928 17.1193C2.64928 17.1194 2.64928 17.1193 2.64928 17.1193C2.64939 17.1874 2.67702 17.2536 2.72756 17.3031C2.77826 17.3527 2.84789 17.3814 2.9214 17.3815H19.0786C19.1521 17.3814 19.2217 17.3527 19.2724 17.3031C19.323 17.2536 19.3506 17.1874 19.3507 17.1193V12.1614C19.3507 11.9189 19.4492 11.6871 19.6231 11.517C19.7968 11.3469 20.0315 11.2521 20.2754 11.2521C20.5192 11.2521 20.7539 11.3469 20.9277 11.517C21.1015 11.6871 21.2 11.9189 21.2 12.1614V17.1196C21.1993 17.6725 20.9746 18.2019 20.5765 18.5915C20.1786 18.981 19.6398 19.1994 19.0788 19.2ZM19.0788 17.5815H2.92116C2.79606 17.5813 2.67612 17.5326 2.58766 17.446C2.4992 17.3594 2.44943 17.242 2.44928 17.1196V12.1614C2.44928 11.9733 2.37293 11.7929 2.23704 11.6599C2.10114 11.5269 1.91683 11.4521 1.72464 11.4521C1.53245 11.4521 1.34814 11.5269 1.21224 11.6599C1.07635 11.7929 1 11.9733 1 12.1614V17.1196C1.00062 17.6181 1.20322 18.0961 1.56338 18.4486C1.92353 18.8011 2.41183 18.9994 2.92116 19H19.0788C19.5882 18.9994 20.0765 18.8011 20.4366 18.4486C20.7968 18.0961 20.9994 17.6181 21 17.1196V12.1614C21 11.9733 20.9237 11.7929 20.7878 11.6599C20.6519 11.5269 20.4676 11.4521 20.2754 11.4521C20.0832 11.4521 19.8989 11.5269 19.763 11.6599C19.6271 11.7929 19.5507 11.9733 19.5507 12.1614V17.1196C19.5506 17.242 19.5008 17.3594 19.4123 17.446C19.3239 17.5326 19.2039 17.5813 19.0788 17.5815Z"
										  fill="white"></path>
								</svg>
								<span class="import-label" data-label="<?php echo esc_html__( 'Importing...', 'shopbuilder' ); ?>"><?php echo esc_html__( 'Import', 'shopbuilder' ); ?></span>
							</a>

						<?php else : ?>
							<a class="rtsb-btn-import import-btn" target="_blank" href="https://shopbuilderwp.com/">
								<i class="dashicons dashicons-migrate"></i>
								<span class="import-label"><?php echo esc_html__( 'Upgrade', 'shopbuilder' ); ?></span>
							</a>
						<?php endif; ?>

					<?php else : ?>
						<a class="rtsb-btn-import import-btn rtsb-import-layout">
							<i class="dashicons dashicons-plus"></i>
							<span class="import-label" data-label="<?php echo esc_html__( 'Creating Template...', 'shopbuilder' ); ?>"><?php echo esc_html__( 'Create Blank Template', 'shopbuilder' ); ?></span>
						</a>
					<?php endif; ?>
				</div>

			</div>

			<?php if ( $item['title'] ) : ?>
				<div class="image-label">
					<span class="label-text"><?php echo esc_html( $item['title'] ); ?></span>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Popups For Create Template
	 *
	 * @return void
	 */
	public function product_search() {
		if ( ! wp_verify_nonce( Fns::get_nonce(), rtsb()->nonceText ) ) {
			$return = [
				'success' => false,
				'content' => esc_html__( 'Session Expired...', 'shopbuilder' ),
			];
			wp_send_json( $return );
		}
		if ( ! current_user_can( 'manage_options' ) ) {
			$return = [
				'success' => false,
				'content' => esc_html__( 'Permission Denied...', 'shopbuilder' ),
			];
			wp_send_json( $return );
		}
		global $wpdb;
		$search = isset( $_REQUEST['search'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['search'] ) ) : null; // phpcs:ignore WordPress.Security.NonceVerification.Missing
		if ( empty( $search ) ) {
			$return = [
				'success' => false,
				'content' => esc_html__( 'Empty string Not allowed...', 'shopbuilder' ),
			];
			wp_send_json( $return );
		}
		/** @noinspection SqlNoDataSourceInspection */
		/** @noinspection SqlResolve */
		// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		$query     = $wpdb->prepare(
			"SELECT ID, post_title
                    FROM $wpdb->posts
                    WHERE post_type = 'product'
                    AND post_status = 'publish'
                    AND post_title LIKE %s
                    LIMIT %d",
			'%' . $search . '%',
			10
		);
		$cache_key = md5( $query );
		$_product  = wp_cache_get( $cache_key, Cache::group() );
		if ( false === $_product ) {
			$_product = $wpdb->get_results( $query ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.PreparedSQL.NotPrepared
			wp_cache_set( $cache_key, $_product, Cache::group(), 12 * HOUR_IN_SECONDS );
			Cache::set_data_cache_key( $cache_key );
		}
		$product_list = [];
		foreach ( $_product as $product ) {
			$product_list['items'][] = [
				'id'   => $product->ID,
				'text' => html_entity_decode( $product->post_title, ENT_QUOTES | ENT_HTML5 ) . ' - (ID#' . $product->ID . ')',
			];
		}

		wp_send_json_success( $product_list );
		wp_die();
	}

	/**
	 * Popups For Create Template
	 *
	 * @return void
	 */
	public function term_search() {

		if ( ! wp_verify_nonce( Fns::get_nonce(), rtsb()->nonceText ) ) {
			$return = [
				'success' => false,
				'content' => esc_html__( 'Session Expired...', 'shopbuilder' ),
			];
			wp_send_json( $return );
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			$return = [
				'success' => false,
				'content' => esc_html__( 'Permission Denied...', 'shopbuilder' ),
			];
			wp_send_json( $return );
		}

		$search   = isset( $_REQUEST['search'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['search'] ) ) : null; // phpcs:ignore WordPress.Security.NonceVerification.Missing
		$taxonomy = isset( $_REQUEST['taxonomy'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['taxonomy'] ) ) : null; // phpcs:ignore WordPress.Security.NonceVerification.Missing
		if ( ! $taxonomy ) {
			$return = [
				'success' => false,
				'content' => esc_html__( 'Invalid taxonomy', 'shopbuilder' ),
			];
			wp_send_json( $return );
		}
		$args      = [
			'taxonomy'   => [ $taxonomy ], // taxonomy name.
			'orderby'    => 'name',
			'order'      => 'ASC',
			'hide_empty' => false,
			'name__like' => $search,
		];
		$terms     = get_terms( $args );
		$count     = count( $terms );
		$term_list = [];
		if ( $count > 0 ) {
			foreach ( $terms as $term ) {
				$term_list['items'][] = [
					'id'   => $term->slug,
					'text' => html_entity_decode( $term->name, ENT_QUOTES | ENT_HTML5 ),
				];
			}
		}
		wp_send_json_success( $term_list );

		wp_die();
	}
}
