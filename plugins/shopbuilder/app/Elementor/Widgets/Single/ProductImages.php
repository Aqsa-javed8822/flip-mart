<?php
/**
 * Main ProductImages class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\Single;

use RadiusTheme\SB\Helpers\BuilderFns;
use RadiusTheme\SB\Helpers\ElementorDataMap;
use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Abstracts\ElementorWidgetBase;
use RadiusTheme\SB\Elementor\Widgets\Controls\ProductImagesSettings;
use RadiusTheme\SBPRO\Helpers\BuilderFunPro;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Product Images class
 */
class ProductImages extends ElementorWidgetBase {
	/**
	 * Construct function
	 *
	 * @param array $data default array.
	 * @param mixed $args default arg.
	 */
	public function __construct( $data = [], $args = null ) {
		$this->rtsb_name = esc_html__( 'Product Images', 'shopbuilder' );
		$this->rtsb_base = 'rtsb-product-image';
		parent::__construct( $data, $args );
		$this->the_hooks();
	}
	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public function widget_fields() {
		return $this->modify_widget_fields();
	}
	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public function modify_widget_fields() {
		$fields                         = ProductImagesSettings::widget_fields( $this );
		$extra_controls['image_border'] = [
			'mode'           => 'group',
			'type'           => 'border',
			'selector'       => $this->selectors['image_border'],
			'size_units'     => [ 'px' ],
			'fields_options' => [
				'border' => [
					'label'       => esc_html__( 'Border', 'shopbuilder' ),
					'label_block' => true,
				],
				'color'  => [
					'label' => esc_html__( 'Border Color', 'shopbuilder' ),
				],
			],
		];
		$fields                         = Fns::insert_controls( 'image_width', $fields, $extra_controls, true );
		return $fields;
	}

	/**
	 * Widget Field.
	 *
	 * @return void
	 */
	public function init_scripts() {
		?>
		<script type="text/javascript">
			// Initialize the gallery's Swiper sliders. In the Elementor editor the
			// widget renders/re-renders after the slider script's one-time init has
			// already run, so the main + thumbnail Swipers need to be (re)initialized
			// here — otherwise the thumbnails stack and the gallery looks broken.
			window.rtsbInitGallerySliders = window.rtsbInitGallerySliders || function ($scope) {
				var $ctx = ($scope && $scope.length) ? $scope : jQuery(document);
				// Elementor's AJAX re-render can run outside edit-mode detection, which
				// leaves the gallery wrapper in the hidden `rtsb-content-loading` (opacity:0)
				// state. Reveal it so the editor never shows a blank/hidden gallery.
				$ctx.find('.rtsb-content-loading').removeClass('rtsb-content-loading');
				// Hide the thumbnail column when the current view has no thumbnails (the
				// runtime variation JS that normally does this doesn't run in the editor).
				$ctx.find('.rtsb-vg-main-slider-wrapper').each(function () {
					var $wrap = jQuery(this);
					if ($wrap.find('.rtsb-vg-thumb-slider .swiper-slide').length < 1) {
						$wrap.addClass('rtsb-vg-thumbs-hidden');
					}
				});
				$ctx.find('.rtsb-carousel-slider').each(function () {
					var $slider = jQuery(this);
					if (typeof $slider.rtsb_slider !== 'function') {
						return;
					}
					// The runtime height-sync (syncThumbHeight) only runs on WooCommerce's
					// wc_variation_form event, which doesn't fire in the editor — so the
					// vertical thumbnail Swiper has no definite height and grows unbounded.
					// Replicate the sync here once the slider has loaded.
					$slider.off('rtsb_slider_loaded.rtsbEditor').on('rtsb_slider_loaded.rtsbEditor', function () {
						setTimeout(function () {
							window.rtsbEditorSyncThumbHeight($slider);
						}, 400);
					});
					$slider.rtsb_slider();
				});
				// The sync writes a fixed px height onto the thumbnail column and its
				// slots. Without re-running it on viewport change that value goes stale
				// the moment the layout reflows — switching to a responsive/device width
				// leaves a tall thumbnail column beside a much smaller main image. The
				// frontend module rebinds on resize for exactly this reason; the editor
				// preview needs the same.
				window.rtsbEditorBindResize($ctx);
			};
			// Re-run the height sync (debounced) whenever the viewport changes, so the
			// side-thumbnail layouts stay correct in Elementor's responsive modes and in
			// the browser's device toolbar.
			window.rtsbEditorBindResize = window.rtsbEditorBindResize || function ($ctx) {
				var resizeTimer;
				jQuery(window).off('resize.rtsbEditorVg').on('resize.rtsbEditorVg', function () {
					clearTimeout(resizeTimer);
					resizeTimer = setTimeout(function () {
						var $scope = ($ctx && $ctx.length) ? $ctx : jQuery(document);
						$scope.find('.rtsb-carousel-slider').each(function () {
							window.rtsbEditorSyncThumbHeight(jQuery(this));
						});
					}, 150);
				});
			};
			// Size the thumbnail column (and its slots) to the main image height, the
			// same way the frontend module does, so the editor preview isn't unbounded.
			window.rtsbEditorSyncThumbHeight = window.rtsbEditorSyncThumbHeight || function ($slider) {
				var $wrap = $slider.closest('.rtsb-vg-main-slider-wrapper');
				if (!$wrap.length || !$wrap.is('.rtsb-thumbnails-position-left, .rtsb-thumbnails-position-right')) {
					return;
				}
				var $thumb = $wrap.find('.rtsb-vg-thumb-slider').first();
				if (!$thumb.length) {
					return;
				}
				var mainH = Math.round($slider.outerHeight() || 0);
				if (mainH <= 0) {
					return;
				}
				var opts = $thumb.data('options') || {};
				var perView = parseInt(opts.slidesPerView, 10) || 4;
				var gap = parseInt(opts.spaceBetween, 10) || 0;
				var slotH = Math.floor((mainH - gap * (perView - 1)) / perView);
				$thumb.css('height', mainH + 'px');
				if (slotH > 0) {
					$thumb.find('.swiper-slide').css('height', slotH + 'px');
				}
				var thumbEl = $thumb.get(0);
				if (thumbEl && thumbEl.swiper) {
					thumbEl.swiper.update();
				}
			};
			// Re-scan for gallery wrappers, whichever way the module happens to be
			// available. The module registers itself with the RTSB registry only when
			// RTSB was already initialised at bundle-eval time; otherwise it just
			// exposes window.rtsbVariationGallery. Asking the registry alone therefore
			// silently no-ops in the Elementor template-builder preview, so the module
			// never runs and `rtsb-content-loading` (opacity:0) is never removed.
			window.rtsbRefreshVariationGallery = window.rtsbRefreshVariationGallery || function () {
				var modules = window.RTSB && window.RTSB.modules;
				var mod = (modules && typeof modules.get === 'function') ? modules.get('variationGallery') : null;
				if (mod && typeof mod.refresh === 'function') {
					mod.refresh();
					return;
				}
				if (typeof window.rtsbVariationGallery === 'function') {
					window.rtsbVariationGallery();
					return;
				}
				if (typeof window.waitForRTSB === 'function') {
					window.waitForRTSB(function (RTSB) {
						RTSB.modules.get('variationGallery')?.refresh();
					});
				}
			};
			// Last-resort reveal: the wrapper ships with `rtsb-content-loading`
			// (opacity:0) and every init path above is responsible for dropping it. If
			// they all fail the gallery is invisible with no way back, so uncover it
			// rather than leave a blank box.
			window.rtsbRevealGalleryFallback = window.rtsbRevealGalleryFallback || function () {
				setTimeout(function () {
					jQuery('.rtsb-vg-main-slider-wrapper.rtsb-content-loading').removeClass('rtsb-content-loading');
				}, 3000);
			};
			if (!'<?php echo esc_attr( Fns::is_optimization_enabled() ); ?>') {
				setTimeout(function() {
					window.rtsbInitGallerySliders();
					window.rtsbRefreshVariationGallery();
				}, 600);
			} else {
				if (typeof elementorFrontend !== 'undefined') {
					elementorFrontend.hooks.addAction(
						'frontend/element_ready/<?php echo esc_attr( $this->rtsb_base ); ?>.default',
						( $scope ) => {
							// Init the gallery slider immediately — it only needs jQuery and
							// the rtsb_slider plugin, not the RTSB module system, so it must
							// not be gated behind waitForRTSB (which may not resolve here).
							window.rtsbInitGallerySliders($scope);
							window.rtsbRefreshVariationGallery();
						}
					);
				} else {
					// No Elementor frontend on this request (some preview/standalone
					// renders): nothing would ever initialise the module otherwise.
					jQuery(function () {
						window.rtsbInitGallerySliders();
						window.rtsbRefreshVariationGallery();
					});
				}
			}
			window.rtsbRevealGalleryFallback();
			/*
			* Initialize all galleries on page.
			*/
			jQuery('.woocommerce-product-gallery').each(function () {
				const that = this;

				setTimeout(function () {
					// `wc_single_product_params` is localised on WooCommerce's
					// `wc-single-product` script, which is absent in some contexts —
					// Elementor template previews most commonly. Referencing it there
					// throws a ReferenceError that aborts gallery initialisation, so
					// bail out quietly instead.
					if (typeof wc_single_product_params === 'undefined' || typeof jQuery(that).wc_product_gallery !== 'function') {
						return;
					}
					jQuery(that).trigger('wc-product-gallery-before-init', [that, wc_single_product_params]);
					jQuery(that).wc_product_gallery(wc_single_product_params);
					jQuery(that).trigger('wc-product-gallery-after-init', [that, wc_single_product_params]);
				}, 600);

			});
			<?php if ( function_exists( 'rtwpvg' ) ) { ?>
			setTimeout(function () {
				jQuery('.rtwpvg-wrapper, .rtwpvg-grid-wrapper').rtWpVGallery();
			}, 600);
			<?php } ?>


			// Inject the selected lightbox icon into the gallery trigger inside the builder.
			// The front-end bundle (RTSB modules / product-image.js) is dequeued in the
			// Elementor editor preview, so the normal runtime injection never runs here.
			// Read the server-rendered `data-zoom-icon` and drop it into the trigger. Since
			// WooCommerce paints its own magnifier via the trigger's ::before/::after and
			// hides real content with text-indent — neither of which JS can clear on a
			// pseudo-element — also register a one-time <style> that neutralises them.
			(function () {
				var styleId = 'rtsb-editor-zoom-icon-style';

				function ensureTriggerStyle() {
					if (document.getElementById(styleId)) {
						return;
					}
					var style = document.createElement('style');
					style.id = styleId;
					style.textContent =
						'.rtsb-product-images .woocommerce-product-gallery__trigger{text-indent:0 !important;display:inline-flex;align-items:center;justify-content:center;}' +
						'.rtsb-product-images .woocommerce-product-gallery__trigger::before,' +
						'.rtsb-product-images .woocommerce-product-gallery__trigger::after{content:none !important;}';
					document.head.appendChild(style);
				}

				function injectZoomIcon($scope) {
					var $root = ($scope && $scope.length) ? $scope : jQuery(document);
					$root.find('.rtsb-product-images').each(function () {
						var $gallery  = jQuery(this);
						var zoomIcon  = $gallery.attr('data-zoom-icon');
						var $triggers = $gallery.find('.woocommerce-product-gallery__trigger, .rtwpvg-trigger');
						if (!zoomIcon) {
							$triggers.remove();
							return;
						}
						$triggers.html(zoomIcon);
					});
				}

				// WooCommerce re-inits the gallery ~600ms after render (see above) and can
				// reset the trigger, so inject after that window to win the race.
				window.rtsbEditorInjectZoomIcon = function ($scope) {
					ensureTriggerStyle();
					setTimeout(function () {
						injectZoomIcon($scope);
					}, 700);
				};

				if (typeof elementorFrontend !== 'undefined' && elementorFrontend.hooks) {
					elementorFrontend.hooks.addAction(
						'frontend/element_ready/<?php echo esc_attr( $this->rtsb_base ); ?>.default',
						function ($scope) {
							window.rtsbEditorInjectZoomIcon($scope);
						}
					);
				}

				jQuery(function () {
					window.rtsbEditorInjectZoomIcon();
				});
			})();

		</script>
		<?php
	}

	/**
	 * Widget Field.
	 *
	 * @param array $control Control.
	 *
	 * @return void
	 */
	public function the_hooks( $control = [] ) {
		if ( empty( $control ) ) {
			return;
		}
		add_filter(
			'woocommerce_product_thumbnails_columns',
			function ( $col ) {
				$col = 0;

				return $col;
			}
		);
		if ( empty( $control['show_zoom'] ) ) {
			add_filter( 'rtwpvg_trigger_icon', '__return_false' );
		}
		if ( empty( $control['show_thumbnails'] ) ) {
			add_filter( 'rtwpvg_show_product_thumbnail_slider', '__return_false' );
			remove_action( 'woocommerce_product_thumbnails', 'woocommerce_show_product_thumbnails', 20 );
		}
		add_filter( 'rtwpvg_thumbnail_slider_js_options', [ $this, 'thumbnail_slider_js_options' ], 50 );
		add_filter( 'rtwpvg_thumbnail_position', [ $this, 'thumbnail_position' ], 50 );
		add_filter( 'rtwpvg_thumbnails_columns', [ $this, 'thumbnails_columns' ] );
		add_filter( 'rtwpvg_sm_thumbnails_columns', [ $this, 'thumbnails_columns_sm' ] );
		add_filter( 'rtwpvg_xs_thumbnails_columns', [ $this, 'thumbnails_columns_xs' ] );
		// RTSB.
		add_filter( 'rtsb/vg/thumbnails/position', [ $this, 'vg_thumbnail_position' ], 50 );
		add_filter( 'rtsb/vg/lightbox', [ $this, 'vg_show_lightbox' ], 50 );
		add_filter( 'rtsb/vg/lightbox/trigger/icon', [ $this, 'vg_lightbox' ], 50 );
		add_filter( 'rtsb/vg/thumbnails/slider/options', [ $this, 'thumbnail_slider_js_options' ], 50, 2 );
		add_filter( 'rtsb/vg/lightbox/position', [ $this, 'vg_lightbox_position' ], 50, 2 );
		add_filter( 'rtsb/vg/gallery/columns', [ $this, 'vg_gallery_columns' ], 50, 2 );
	}
	/**
	 * Thumbnail Columns.
	 *
	 * @param int $col number.
	 *
	 * @return int
	 */
	public function vg_gallery_columns( $col ) {
		$controllers = $this->get_settings_for_display();
		if ( ! empty( $controllers['gallery_thumbs_column']['size'] ) ) {
			$col = absint( $controllers['gallery_thumbs_column']['size'] );
		}
		return $col;
	}
	/**
	 * Thumbnail slider js options.
	 *
	 * @param array $options Options.
	 *
	 * @return array
	 */
	public function thumbnail_slider_js_options( $options ) {
		$controllers = $this->get_settings_for_display();
		if ( ! empty( $controllers['gallery_thumbs_column']['size'] ) ) {
			$options['slidesPerView'] = absint( $controllers['gallery_thumbs_column']['size'] );
		}
		if ( ! empty( $controllers['gallery_thumbs_column_gap']['size'] ) ) {
			$options['spaceBetween'] = $controllers['gallery_thumbs_column_gap']['size'];
		}
		return $options;
	}

	/**
	 * Thumbnails columns.
	 *
	 * @param int $column Column.
	 *
	 * @return mixed
	 */
	public function thumbnails_columns( $column ) {
		$controllers = $this->get_settings_for_display();
		$column      = ! empty( $controllers['gallery_thumbs_column']['size'] ) ? $controllers['gallery_thumbs_column']['size'] : $column;

		return $column;
	}

	/**
	 * Thumbnails columns.
	 *
	 * @param int $column Column.
	 *
	 * @return mixed
	 */
	public function thumbnails_columns_sm( $column ) {
		$controllers = $this->get_settings_for_display();
		$column      = ! empty( $controllers['gallery_thumbs_column_tablet']['size'] ) ? $controllers['gallery_thumbs_column_tablet']['size'] : $column;

		return $column;
	}

	/**
	 * Thumbnails columns.
	 *
	 * @param int $column Column.
	 *
	 * @return mixed
	 */
	public function thumbnails_columns_xs( $column ) {
		$controllers = $this->get_settings_for_display();
		$column      = ! empty( $controllers['gallery_thumbs_column_mobile']['size'] ) ? $controllers['gallery_thumbs_column_mobile']['size'] : $column;

		return $column;
	}
	/**
	 * Thumbnail position.
	 *
	 * @param string $position Position.
	 *
	 * @return string
	 */
	public function thumbnail_position( $position ) {
		if ( ! function_exists( 'rtwpvg' ) || ! rtwpvg()->active_pro() || ! ( BuilderFns::is_product() || BuilderFns::is_quick_views_page() ) ) {
			return $position;
		}

		$controllers     = $this->get_settings_for_display();
		$show_thumbnails = ! empty( $controllers['show_thumbnails'] ) ? $controllers['show_thumbnails'] : false;
		if ( ! $show_thumbnails && 'grid' !== ( $controllers['image_layout'] ?? '' ) ) {
			add_filter( 'rtwpvg_show_product_thumbnail_slider', '__return_false', 20 );
			return 'bottom';
		}
		return ! empty( $controllers['image_layout'] ) ? $controllers['image_layout'] : 'bottom';
	}
	/**
	 * Thumbnail position.
	 *
	 * @param string $position Position.
	 *
	 * @return string
	 */
	public function vg_thumbnail_position( $position ) {
		if ( ! rtsb()->has_pro() ) {
			return $position;
		}
		$controllers = $this->get_settings_for_display();
		return ! empty( $controllers['image_layout'] ) ? $controllers['image_layout'] : 'bottom';
	}

	/**
	 * Whether the Variation Gallery lightbox trigger should render.
	 *
	 * The `rtsb/vg/lightbox` filter is a boolean gate (see the VG product-image
	 * template). It must reflect the widget's "Show Lightbox Icon?" (show_zoom)
	 * setting so disabling it actually hides the trigger — not the icon HTML, which
	 * is always truthy and would keep the trigger visible.
	 *
	 * @param bool $show Whether to show the lightbox trigger.
	 * @return bool
	 */
	public function vg_show_lightbox( $show ) {
		$controllers = $this->get_settings_for_display();
		return ! empty( $controllers['show_zoom'] );
	}
	/**
	 * @param bool $lightbox Lightbox.
	 * @return mixed
	 */
	public function vg_lightbox( $lightbox ) {
		$controllers = $this->get_settings_for_display();
		// The icons control always returns a populated array (`value`/`library`), so
		// check the actual `value`; when it is cleared, fall back to the default SVG
		// instead of rendering an empty icon.
		return ! empty( $controllers['lightbox_icon']['value'] ) ? Fns::icons_manager( $controllers['lightbox_icon'] ) : $lightbox;
	}
	/**
	 * @param string $position position text.
	 * @return mixed
	 */
	public function vg_lightbox_position( $position ) {
		$controllers = $this->get_settings_for_display();
		return ! empty( $controllers['vg_lightbox_position'] ) ? $controllers['vg_lightbox_position'] : $position;
	}

	/**
	 * Enqueue the Font Awesome library required by the selected lightbox icon.
	 *
	 * Elementor only auto-loads icon fonts for icons rendered through its own widget
	 * flow. The lightbox icon is injected into the Variation Gallery / WooCommerce
	 * gallery markup via a filter, so on the front end its font is never enqueued and
	 * the glyph renders empty — it appears in the editor only because Elementor always
	 * loads Font Awesome there. Enqueue the matching library so the icon shows on the
	 * front end. SVG icons carry no library and need no enqueue.
	 *
	 * @param array $icon Elementor icons control value.
	 * @return void
	 */
	private function enqueue_lightbox_icon_font( $icon ) {
		if ( empty( $icon['library'] ) ) {
			return;
		}
		$handles = [
			'fa-solid'   => 'elementor-icons-fa-solid',
			'fa-regular' => 'elementor-icons-fa-regular',
			'fa-brands'  => 'elementor-icons-fa-brands',
		];
		if ( isset( $handles[ $icon['library'] ] ) ) {
			wp_enqueue_style( $handles[ $icon['library'] ] );
		}
	}

	/**
	 * Render Function
	 *
	 * @return void
	 */
	protected function render() {
		add_action( 'wp_footer', 'woocommerce_photoswipe' );
		global $product, $post;
		$_product    = $product;
		$product     = Fns::get_product();
		$controllers = $this->get_settings_for_display();
		if ( empty( $controllers['show_module_badges'] ) ) {
			add_filter( 'rtsb/module/badges/show', '__return_false', 99 );
		}
		// When "Show Thumbnails?" is off, hide the Variation Gallery thumbnail slider.
		$hide_vg_thumbnails = empty( $controllers['show_thumbnails'] );

		if ( ! empty( $controllers['show_zoom'] ) && ! empty( $controllers['lightbox_icon'] ) ) {
			$this->enqueue_lightbox_icon_font( $controllers['lightbox_icon'] );
		}

		$this->theme_support();
		$controllers['lightbox_icon'] = Fns::icons_manager( $controllers['lightbox_icon'] );
		$data                         = [
			'template'    => 'elementor/single-product/product-images',
			'controllers' => $controllers,
		];
		$this->the_hooks( $controllers );

		if ( $hide_vg_thumbnails ) {
			add_filter( 'rtsb/vg/show/thumbnails', '__return_false' );
		}

		Fns::load_template( $data['template'], $data );

		if ( $hide_vg_thumbnails ) {
			remove_filter( 'rtsb/vg/show/thumbnails', '__return_false' );
		}

		if ( $this->is_builder_mode() ) {
			$this->init_scripts();
		}

		if ( empty( $controllers['show_module_badges'] ) ) {
			remove_filter( 'rtsb/module/badges/show', '__return_false', 99 );
		}

		$this->theme_support( 'render_reset' );
		$product = $_product; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
	}
}
