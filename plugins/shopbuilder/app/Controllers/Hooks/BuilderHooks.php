<?php
/**
 * Main initialization class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Controllers\Hooks;

defined( 'ABSPATH' ) || exit();

use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Helpers\BuilderFns;
use RadiusTheme\SB\Helpers\ElementorDataMap;
use RadiusTheme\SB\Models\TemplateSettings;
use RadiusTheme\SB\Traits\SingletonTrait;

/**
 * Builder Frontend.
 */
class BuilderHooks {
	/**
	 * Builder page id.
	 *
	 * @var integer
	 */
	private $builder_page_id = 0;

	/**
	 * Page Edit By.
	 *
	 * @var string
	 */
	private $page_edit_with = '';

	/**
	 * Internal cache.
	 *
	 * @var array
	 */
	private static $cache = [];

	/**
	 * Singleton.
	 */
	use SingletonTrait;

	/**
	 * Final constructor.
	 */
	private function __construct() {
		// Template Redirect hook run after the global query.
		add_action( 'template_redirect', [ $this, 'frontend_init' ] );
		add_filter( 'rtsb/builder/set/current/page/type', [ $this, 'builder_page_type' ] );
		add_filter( 'body_class', [ $this, 'product_page_body_class' ] );
		add_action( 'pre_get_posts', [ $this, 'override_archive_posts_per_page' ] );
	}

	/**
	 * Override posts_per_page for shop/archive pages based on widget setting.
	 *
	 * @param \WP_Query $query The WP_Query instance.
	 *
	 * @return void
	 */
	public function override_archive_posts_per_page( $query ) {
		if ( is_admin() || ! $query->is_main_query() ) {
			return;
		}

		if ( ! ( is_shop() || is_product_taxonomy() ) ) {
			return;
		}

		$type            = is_shop() ? 'shop' : 'archive';
		$option_name     = BuilderFns::option_name( $type );
		$builder_page_id = TemplateSettings::instance()->get_option( $option_name );

		if ( empty( $builder_page_id ) ) {
			$option_name     = BuilderFns::option_name( $type, true );
			$builder_page_id = TemplateSettings::instance()->get_option( $option_name );
		}

		// Fallback: use shop template for archive pages when no archive template exists.
		if ( empty( $builder_page_id ) && 'archive' === $type ) {
			$option_name     = BuilderFns::option_name( 'shop' );
			$builder_page_id = TemplateSettings::instance()->get_option( $option_name );

			if ( empty( $builder_page_id ) ) {
				$option_name     = BuilderFns::option_name( 'shop', true );
				$builder_page_id = TemplateSettings::instance()->get_option( $option_name );
			}
		}

		if ( empty( $builder_page_id ) ) {
			return;
		}

		$per_page = $this->get_archive_widget_per_page( $builder_page_id );

		if ( $per_page ) {
			$query->set( 'posts_per_page', $per_page );
		}
	}

	/**
	 * Get posts_per_page from the ProductsArchive widget in Elementor data.
	 *
	 * @param int $post_id Builder page ID.
	 *
	 * @return int|false The per page value or false if not set.
	 */
	private function get_archive_widget_per_page( $post_id ) {
		$cache_key = 'rtsb_archive_per_page_' . $post_id;

		if ( isset( self::$cache[ $cache_key ] ) ) {
			return self::$cache[ $cache_key ];
		}

		// Check default layout widget first.
		$widgets = ElementorDataMap::instance()->get_widget_data( 'rtsb-products-archive', null, $post_id );

		if ( ! empty( $widgets ) ) {
			$widget   = reset( $widgets );
			$per_page = isset( $widget['settings']['posts_per_page'] ) && '' !== $widget['settings']['posts_per_page']
				? absint( $widget['settings']['posts_per_page'] )
				: false;

			self::$cache[ $cache_key ] = $per_page;

			return $per_page;
		}

		// Check custom layout widget.
		$widgets = ElementorDataMap::instance()->get_widget_data( 'rtsb-products-archive-custom', null, $post_id );

		if ( ! empty( $widgets ) ) {
			$widget   = reset( $widgets );
			$per_page = isset( $widget['settings']['posts_per_page'] ) && '' !== $widget['settings']['posts_per_page']
				? absint( $widget['settings']['posts_per_page'] )
				: false;

			self::$cache[ $cache_key ] = $per_page;

			return $per_page;
		}

		self::$cache[ $cache_key ] = false;

		return false;
	}

	/**
	 * Builder page Body class.
	 *
	 * @param array $classes class list.
	 * @return array.
	 */
	public function product_page_body_class( $classes ) {
		$classes[] = 'rtsb-shopbuilder-plugin rtsb-shopbuilder-v-' . RTSB_VERSION . ' rtsb_theme_' . rtsb()->current_theme;

		if ( defined( 'RTSBPRO_VERSION' ) ) {
			$classes[] = 'rtsb-shopbuilder-pro-v-' . RTSBPRO_VERSION;
		}

		if ( defined( 'ELEMENTOR_PRO_VERSION' ) ) {
			$classes[] = 'rtsb-elementor-pro-v-' . ELEMENTOR_PRO_VERSION;
		}
		if ( Fns::is_woocommerce() || BuilderFns::is_builder_preview() ) {
			$classes[] = 'woocommerce-page';
		}

		if ( BuilderFns::is_product() ) {
			$classes[] = 'single-product';
		}

		if ( BuilderFns::is_shop() ) {
			$classes[] = 'woocommerce-shop';
		}

		if ( BuilderFns::is_cart() ) {
			$classes[] = 'woocommerce-cart';
		} else {
			$classes[] = 'woocommerce';
		}

		if ( BuilderFns::is_checkout() ) {
			$classes[] = 'woocommerce-checkout ';
		}

		if ( rtsb()->has_pro() ) {
			$disable_cart     = Fns::is_guest_feature_disabled( 'hide_cart_page', '' );
			$disable_checkout = Fns::is_guest_feature_disabled( 'hide_checkout_page', '' );

			if ( $disable_cart || $disable_checkout ) {
				$classes[] = 'rtsb-not-visible';
			}
		}

		$hide_notification = Fns::get_option( 'general', 'notification', 'hide_notification', '' );

		if ( 'on' === $hide_notification ) {
			$classes[] = 'rtsb-notifications-hidden';
		}

		return $classes;
	}
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function frontend_init() {
		$this->builder_page_id = BuilderFns::builder_page_id_by_page();
		$this->page_edit_with  = Fns::page_edit_with( $this->builder_page_id );
		if ( $this->builder_page_id ) {
			if ( did_action( 'elementor/loaded' ) && 'elementor' === $this->page_edit_with ) {
				$this->elementor_frontend();
			} elseif ( 'gutenberg' === $this->page_edit_with ) {
				$this->gutenberg_frontend();
			}
		}
	}
	/**
	 * Appl for gutenberg.
	 *
	 * @return void
	 */
	public function elementor_frontend() {
		// Priority 15: after Elementor registers style handles (priority 5) and before
		// its enqueue_styles / after_enqueue_post_styles pass (priority 20) that renders
		// the atomic-widget (V4) styles collected via the elementor/post/render action.
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_elementor_template_css' ], 15 );
		add_action( 'rtsb/builder/before/header', [ $this, 'el_body_class' ] );
		add_action( 'rtsb/builder/template/main/content', [ $this, 'elementor_template_main_content' ] );
	}

	/**
	 * Enqueue the Elementor builder template CSS in the document head.
	 *
	 * ShopBuilder renders the Elementor template on a WooCommerce object (e.g. a product),
	 * so Elementor's own wp_enqueue_scripts pass only fires elementor/post/render for the
	 * queried object — never for the builder template. As a result the template's
	 * document CSS (post-{id}.css) and, on Elementor V4, its atomic-widget styles
	 * (local-{id}-*.css rendered on elementor/frontend/after_enqueue_post_styles) are
	 * never loaded on builder pages although they load on Elementor's own preview.
	 *
	 * Firing elementor/post/render for the template id here — before Elementor's
	 * priority-20 style pass — registers the template with the atomic styles manager so
	 * its styles are generated and enqueued. enqueue_styles() loads the classic post CSS.
	 *
	 * @return void
	 */
	public function enqueue_elementor_template_css() {
		if ( ! $this->builder_page_id || ! class_exists( '\Elementor\Plugin' ) ) {
			return;
		}

		$documents = \Elementor\Plugin::$instance->documents;

		if ( ! $documents ) {
			return;
		}

		$document = $documents->get( $this->builder_page_id );

		if ( ! $document ) {
			return;
		}

		// Register the template with Elementor's render pipeline (atomic V4 styles manager
		// collects these post ids and renders their styles on after_enqueue_post_styles).
		do_action( 'elementor/post/render', $this->builder_page_id );

		// Classic per-post document CSS (post-{id}.css) and font dependencies.
		if ( method_exists( $document, 'enqueue_styles' ) ) {
			$document->enqueue_styles();
		}

		// Elementor hooks its own frontend enqueue_styles() pass only when the
		// *queried* object is a singular Elementor document (Frontend::init).
		// On ShopBuilder frontend pages the queried object (shop/archive/cart/
		// checkout/product) is never the builder template, so that pass never
		// runs — leaving the elementor-frontend base stylesheet and, critically,
		// the V4 atomic styles manager (which renders the template's flexbox/
		// container CSS on elementor/frontend/after_enqueue_post_styles)
		// un-triggered, so containers collapse to full width. We drive the pass
		// manually below, but ONLY on the real frontend: in the Elementor editor
		// preview and the ShopBuilder single-template preview the queried object
		// *is* the template (singular + built with Elementor), so Elementor runs
		// its own pass and firing ours early would interfere with the editor
		// preview load (the panel waits on that iframe). Skip both cases.
		$preview             = \Elementor\Plugin::$instance->preview ?? null;
		$is_editor_view      = $preview && method_exists( $preview, 'is_editor_or_preview' ) && $preview->is_editor_or_preview();
		$queried_document    = $documents->get( get_the_ID() );
		$elementor_will_load = is_singular() && $queried_document && $queried_document->is_built_with_elementor();
		$frontend            = \Elementor\Plugin::$instance->frontend ?? null;

		if ( $is_editor_view || $elementor_will_load || ! $frontend || ! method_exists( $frontend, 'enqueue_styles' ) ) {
			return;
		}

		// Enqueues the elementor-frontend base stylesheet; one-shot (internal
		// static guard) so it is a no-op if something already ran it.
		$frontend->enqueue_styles();

		// The V4 Atomic_Styles_Manager only renders CSS for the post ids collected
		// via elementor/post/render *before* the style pass runs. When another
		// plugin triggers that pass first — e.g. Elementor Pro's Theme Builder
		// rendering its own archive location — our builder template id is
		// registered too late, so its per-template file (local-{id}-frontend-*.css)
		// is written to disk but never enqueued, and containers stay full width.
		// Re-firing the atomic trigger after registering the template above makes
		// the manager enqueue that file. This action's only listener is the atomic
		// styles manager, and wp_enqueue_style() de-duplicates handles, so the
		// extra pass is safe.
		do_action( 'elementor/frontend/after_enqueue_post_styles' );
	}

	/**
	 * Appl for Elementor.
	 *
	 * @return void
	 */
	public function gutenberg_frontend() {
		add_action( 'rtsb/builder/template/main/content', [ $this, 'gutenberg_template_main_content' ] );
	}
	/**
	 * Apply For the supported builder page.
	 *
	 * @param string $type string.
	 * @return string
	 */
	public function builder_page_type( $type ) {

		if ( BuilderFns::is_shop() ) {
			$type = 'shop';
		} elseif ( BuilderFns::is_archive() ) {
			$type = 'archive';
		} elseif ( BuilderFns::is_product() ) {
			$type = 'product';
		} elseif ( BuilderFns::is_checkout() ) {
			$type = 'checkout';
			wp_enqueue_script( 'wc-checkout' );
		} elseif ( BuilderFns::is_cart() ) {
			$type = 'cart';
		}
		wp_enqueue_style( Fns::optimized_handle( 'rtsb-frontend' ) );
		wp_enqueue_script( Fns::optimized_handle( 'rtsb-public' ) );

		return apply_filters( 'rtsb/builder/set/current/page/type/external', $type );
	}

	/**
	 * Appl for Elementor.
	 *
	 * @return void
	 */
	public function el_body_class() {
		if ( ! class_exists( '\Elementor\Plugin' ) || empty( \Elementor\Plugin::$instance->frontend ) ) {
			return;
		}

		$page_template = get_post_meta( $this->builder_page_id, '_wp_page_template', true );

		if ( 'elementor_canvas' === $page_template ) {
			\Elementor\Plugin::$instance->frontend->add_body_class( 'elementor-template-canvas' );
		} elseif ( 'elementor_header_footer' === $page_template ) {
			\Elementor\Plugin::$instance->frontend->add_body_class( 'elementor-template-full-width' );
		}
	}

	/**
	 * Apply for Elementor.
	 *
	 * @return void
	 */
	public function elementor_template_main_content() {
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo BuilderFns::get_builder_content( $this->builder_page_id );
	}
	/**
	 * Apply for Gutenberg.
	 *
	 * @return void
	 */
	public function gutenberg_template_main_content() {
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		$output  = '';
		$content = get_the_content( null, false, $this->builder_page_id );
		if ( has_blocks( $content ) ) {
			$blocks = parse_blocks( $content );
			foreach ( $blocks as $block ) {
				$output .= render_block( $block );
			}
		} else {
			$content = apply_filters( 'the_content', $content );
			$output  = str_replace( ']]>', ']]&gt;', $content );
		}
		echo wp_kses_post( $output );
	}
}
