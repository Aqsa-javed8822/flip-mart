<?php
/**
 * Main BadgesFrontEnd class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Modules\Badges;

use RadiusTheme\SB\Elementor\Helper\RenderHelpers;
use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Traits\SingletonTrait;
use WC_Product;

defined( 'ABSPATH' ) || exit();

/**
 * Main BadgesFrontEnd class.
 */
class BadgesFrontEnd {
	/**
	 * Singleton Trait.
	 */
	use SingletonTrait;

	/**
	 * @var array|mixed
	 */
	private array $options;
	/**
	 * @var array
	 */
	private $cache = [];

	/**
	 * @var array
	 */
	private array $all_badges = [];

	/**
	 * @var array
	 */
	private array $group_badges_loop = [];
	/**
	 * @var array
	 */
	private array $group_badges_product = [];
	/**
	 * @var array
	 */
	private array $above_image_loop = [];
	/**
	 * @var array
	 */
	private array $above_image_product = [];

	/**
	 * Class Constructor.
	 */
	private function __construct() {
		$this->options = Fns::get_options( 'modules', 'product_badges' );
		add_filter( 'woocommerce_sale_flash', [ $this, 'woocommerce_sale_flash' ], 99 );
		add_action( 'the_post', [ $this, 'wc_setup_product_badge_data' ], 20 );
		add_action( 'rtsb_before_product_template_render', [ $this, 'wc_setup_product_badge_data' ], 12 );

		// show_group_custom_position.
		$loop_above_image = 'above_image' === ( $this->options['loop_group_position'] ?? '' );
		if ( ( wp_doing_ajax() || ! is_admin() ) && $loop_above_image ) {
			// Loop action will go here.
			add_filter( 'woocommerce_product_get_image', [ $this, 'show_badge_on_loop_product_thumbnail' ], 99, 1 );
		}

		$product_page_above_image = 'above_image' === ( $this->options['product_page_group_position'] ?? '' );

		if ( $product_page_above_image ) {
			add_filter( 'woocommerce_single_product_image_thumbnail_html', [ $this, 'show_badge_on_product_page_thumbnail' ], 99, 1 );
			add_action( 'rtwpvg_product_badge', [ $this, 'product_gallery_images_badge' ], 15, 5 );
			add_action( 'rtsb_product_badge', [ $this, 'product_gallery_images_badge' ], 15, 5 );
			// Product details action will go here.
		}

		$this->badges_position();

		// The Hook only work if global product found.
		add_action( 'rtsb/modules/product_badges/frontend/display', [ $this, 'all_badges_for_product' ] );

		// ShortCode.
		add_shortcode( 'rtsb_badges', [ $this, 'badges_shortcode' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_public_scripts' ], 99 );
	}


	/**
	 * @return void
	 */
	public function enqueue_public_scripts() {
		$cache_key = 'badge_style_cache';

		if ( isset( $this->cache[ $cache_key ] ) && ! empty( $this->cache[ $cache_key ] ) ) {
			wp_add_inline_style( Fns::optimized_handle( 'rtsb-frontend' ), $this->cache[ $cache_key ] );

			return;
		}

		$product_badges = BadgesFns::get_product_badges_list();

		if ( empty( $product_badges ) ) {
			return;
		}

		$dynamic_css = '';

		foreach ( $product_badges as $badge ) {
			$parent_selectors = [];

			if ( 'product' === ( $badge['apply_for'] ?? '' ) ) {
				if ( ! empty( $badge['applicable_products'] ) ) {
					foreach ( $badge['applicable_products'] as $product ) {
						$parent_selectors[] = '.product.post-' . absint( $product['value'] );
					}
				}

				if ( empty( $badge['applicable_products'] ) ) {
					$parent_selectors[] = '.product';
				}
			}

			if ( 'product_cat' === ( $badge['apply_for'] ?? '' ) ) {
				if ( ! empty( $badge['applicable_categories'] ) ) {
					foreach ( $badge['applicable_categories'] as $cat ) {
						$cat_id = absint( $cat['value'] );
						$term   = get_term( $cat_id, 'product_cat' );

						if ( is_wp_error( $term ) || empty( $term ) ) {
							continue;
						}

						$parent_selectors[] = '.product.product_cat-' . $term->slug;
					}
				}

				if ( empty( $badge['applicable_categories'] ) ) {
					$parent_selectors[] = '.product';
				}
			}

			if ( ! empty( $parent_selectors ) && 'text' == $badge['badges_type'] ) {
				$dynamic_css .= $this->print_text_style( $parent_selectors, $badge );
			}

			if ( ! empty( $parent_selectors ) && 'image' == $badge['badges_type'] ) {
				$dynamic_css .= $this->print_images_style( $parent_selectors, $badge );
			}
		}//end foreach

		$dynamic_css .= $this->global_badge_css();

		$this->cache[ $cache_key ] = $dynamic_css;

		if ( ! empty( $dynamic_css ) ) {
			wp_add_inline_style( Fns::optimized_handle( 'rtsb-frontend' ), $dynamic_css );
		}
	}

	/**
	 * Setup Product Badges
	 *
	 * @return array|void
	 */
	public function wc_setup_product_badge_data() {
		global $product;

		if ( ! $product instanceof WC_Product ) {
			return;
		}

		$this->all_badges           = [];
		$this->above_image_loop     = [];
		$this->above_image_product  = [];
		$this->group_badges_loop    = [];
		$this->group_badges_product = [];
		$cache_key                  = 'wc_setup_product_badge_data_' . $product->get_id();
		if ( isset( $this->cache[ $cache_key ] ) && is_array( $this->cache[ $cache_key ] ) ) {
			$this->all_badges           = $this->cache[ $cache_key ]['all_badges'] ?? [];
			$this->above_image_loop     = $this->cache[ $cache_key ]['above_image_loop'] ?? [];
			$this->above_image_product  = $this->cache[ $cache_key ]['above_image_product'] ?? [];
			$this->group_badges_loop    = $this->cache[ $cache_key ]['group_badges_loop'] ?? [];
			$this->group_badges_product = $this->cache[ $cache_key ]['group_badges_product'] ?? [];

			return $this->cache[ $cache_key ];
		}

		$badges = BadgesFns::get_product_badges_for_current_product();

		if ( empty( $badges ) ) {
			return;
		}

		$loop_group_above_image = 'above_image' == ( $this->options['loop_group_position'] ?? '' );
		$above_image_product    = 'above_image' == ( $this->options['product_page_group_position'] ?? '' );

		foreach ( $badges as $badge_applied ) {
			$this->all_badges[] = $badge_applied;
			// loop_group_position.
			if ( $loop_group_above_image ) {
				$this->above_image_loop[] = $badge_applied;
			}

			// loop_group_position.
			if ( ! $loop_group_above_image ) {
				$this->group_badges_loop[] = $badge_applied;
			}

			// Product Page Group Position.
			if ( $above_image_product ) {
				$this->above_image_product[] = $badge_applied;
			}

			// Product Page Group Position.
			if ( ! $above_image_product ) {
				$this->group_badges_product[] = $badge_applied;
			}
		}//end foreach
		$this->cache[ $cache_key ] = [
			'all_badges'           => $this->all_badges,
			'above_image_loop'     => $this->above_image_loop,
			'above_image_product'  => $this->above_image_product,
			'group_badges_loop'    => $this->group_badges_loop,
			'group_badges_product' => $this->group_badges_product,
		];

		return $this->cache[ $cache_key ];
	}

	/**
	 * Get Badge Html
	 *
	 * @param array $badge_applied badge array.
	 *
	 * @return void
	 */
	private function badge_html_for_text( $badge_applied ) {
		$text       = '';
		$is_dynamic = 'dynamic' == ( $badge_applied['badge_condition'] ?? '' );
		$is_on_sale = 'on_sale' == ( $badge_applied['badge_for'] ?? '' );
		if ( $is_dynamic && $is_on_sale && 'on' === ( $badge_applied['show_badges_percent_text'] ?? 'off' ) ) {
			$percent = Fns::calculate_sale_percentage();
			if ( $percent ) {
				$text = '-' . $percent . '%';
			}
		} else {
			$text = ( $badge_applied['badges_text'] ?? '' );
		}

		$badge_preset = ( $badge_applied['badge_preset'] ?? 'preset1' );
		$badge_class  = RenderHelpers::badge_class( $badge_preset );
		if ( empty( $text ) ) {
			return;
		}
		?>
		<span class="rtsb-tag-<?php echo esc_attr( $badge_class ); ?>"><?php echo esc_html( $text ); ?></span>
		<?php
	}

	/**
	 * Get Badge Html
	 *
	 * @param array $badge_applied badge array.
	 *
	 * @return void
	 */
	private function badge_html_for_image( $badge_applied ) {
		if ( empty( $badge_applied['upload_image']['id'] ) ) {
			return;
		}
		$img_alt = trim( wp_strip_all_tags( get_post_meta( $badge_applied['upload_image']['id'], '_wp_attachment_image_alt', true ) ) );
		$alt_tag = ! empty( $img_alt ) ? $img_alt : __( 'Badge Image', 'shopbuilder' );
		?>
		<img class="badge-image" src="<?php echo esc_url( $badge_applied['upload_image']['source'] ); ?>" alt="<?php echo esc_attr( $alt_tag ); ?>"/>
		<?php
	}

	/**
	 * Get Badge Html
	 *
	 * @param array  $badge_applied badge array.
	 * @param string $badges_type badges type.
	 * @param array  $badge_classes badges classes.
	 *
	 * @return void
	 */
	private function generate_badges( $badge_applied, $badges_type = '', $badge_classes = [] ) {
		?>
		<div class="rtsb-badge type-<?php echo esc_attr( $badges_type ) . ' ' . esc_attr( implode( ' ', $badge_classes ) ); ?>">
			<?php
			switch ( $badges_type ) {
				case 'text':
					$this->badge_html_for_text( $badge_applied );
					break;
				case 'image':
					$this->badge_html_for_image( $badge_applied );
					break;
			}
			?>
		</div>
		<?php
	}


	/**
	 * @return void
	 */
	public function product_gallery_images_badge() {
		if ( ! apply_filters( 'rtsb/module/badges/show', true ) ) {
			return;
		}

		global $product;

		if ( ! ( $product && $product instanceof WC_Product ) ) {
			return;
		}

		$group_classes = [];
		if ( ! empty( $this->options['group_badge_display_as'] ) ) {
			$group_classes[] = 'rtsb-group-display-as-' . $this->options['group_badge_display_as'];
		}

		$group_position = ( $this->options['product_page_group_badge_position'] ?? 'top-left' );
		// group_badge_position.
		$group_classes[] = 'rtsb-group-position-' . $group_position;
		Fns::print_html( $this->group_badges_html( $this->above_image_product, $group_classes ) );
	}

	/**
	 * @return void
	 */
	public function add_group_badges_for_product() {
		if ( ! apply_filters( 'rtsb/module/badges/show', true ) ) {
			return;
		}

		$group_classes = [ 'rtsb-group-custom-position' ];
		Fns::print_html( $this->group_badges_html( $this->group_badges_product, $group_classes ) );
	}

	/**
	 * @return void
	 */
	public function add_group_badges_for_loop() {
		if ( ! apply_filters( 'rtsb/module/badges/show', true ) ) {
			return;
		}

		$group_classes = [ 'rtsb-group-custom-position' ];
		if ( ! empty( $this->options['group_badge_display_as'] ) ) {
			$group_classes[] = 'rtsb-group-display-as-' . $this->options['group_badge_display_as'];
		}

		Fns::print_html( $this->group_badges_html( $this->group_badges_loop, $group_classes ) );
	}

	/**
	 * @return void
	 */
	public function all_badges_for_product() {
		if ( ! apply_filters( 'rtsb/module/badges/show', true ) ) {
			return;
		}

		// Ensure badge data is prepared for the current product. On the normal front end
		// `the_post` / `rtsb_before_product_template_render` populate it, but those don't
		// fire for the Product Badges Elementor widget (or Elementor editor), leaving
		// `all_badges` empty. Cached per product id, so this is idempotent and cheap.
		if ( empty( $this->all_badges ) ) {
			$this->wc_setup_product_badge_data();
		}

		$group_classes = [ 'rtsb-group-custom-position' ];

		$group_position = ( $this->options['group_badge_position'] ?? 'top-left' );
		// group_badge_position.
		if ( ! empty( $this->options['group_badge_display_as'] ) ) {
			$group_classes[] = 'rtsb-group-display-as-' . $this->options['group_badge_display_as'];
		}

		$group_classes[] = 'rtsb-group-position-' . $group_position;
		$group_classes   = apply_filters( 'rtsb/all/badges/classes/for/product', $group_classes );
		Fns::print_html( $this->group_badges_html( $this->all_badges, $group_classes ) );
	}

	/**
	 * Badges Position.
	 *
	 * @return void
	 */
	public function badges_position() {
		// Single Page Badges Position.
		$product_hook_priority = ( $this->options['product_page_group_hook_priority'] ?? null );

		if ( '' === $product_hook_priority ) {
			$product_hook_priority = null;
		}

		$positions = apply_filters(
			'rtsb/module/product_page_badges/positions',
			[
				'before_add_to_cart' => [
					'hook'     => 'woocommerce_single_product_summary',
					'priority' => ( $product_hook_priority ?? 25 ),
				],
				'after_add_to_cart'  => [
					'hook'     => 'woocommerce_single_product_summary',
					'priority' => ( $product_hook_priority ?? 31 ),
				],
				'after_thumbnail'    => [
					'hook'     => 'woocommerce_before_single_product_summary',
					'priority' => ( $product_hook_priority ?? 25 ),
				],
				'after_summary'      => [
					'hook'     => 'woocommerce_after_single_product_summary',
					'priority' => ( $product_hook_priority ?? 11 ),
				],
				'after_short_desc'   => [
					'hook'     => 'woocommerce_single_product_summary',
					'priority' => ( $product_hook_priority ?? 21 ),
				],
				'custom'             => [
					'hook'     => ( $this->options['product_page_custom_hook_name'] ?? '' ),
					'priority' => $product_hook_priority ?? 10,
				],
			]
		);

		$product_page_position = ( $this->options['product_page_group_position'] ?? 'after_add_to_cart' );

		if ( 'shortcode' !== $product_page_position && isset( $positions[ $product_page_position ]['hook'] ) ) {
			add_action( $positions[ $product_page_position ]['hook'], [ $this, 'add_group_badges_for_product' ], isset( $positions[ $product_page_position ]['priority'] ) ? absint( $positions[ $product_page_position ]['priority'] ) : '' );
		}

		$loop_hook_priority = ( $this->options['group_position_hook_priority'] ?? null );

		if ( '' === $loop_hook_priority ) {
			$loop_hook_priority = null;
		}

		// Shop Page/ Any Product Query loop.
		$positions = apply_filters(
			'rtsb/module/shop_page_badges/positions',
			[
				'before_product_title' => [
					'hook'     => 'woocommerce_shop_loop_item_title',
					'priority' => ( $loop_hook_priority ?? 5 ),
				],
				'after_product_title'  => [
					'hook'     => 'woocommerce_shop_loop_item_title',
					'priority' => ( $loop_hook_priority ?? 11 ),
				],
				'before_add_to_cart'   => [
					'hook'     => 'woocommerce_after_shop_loop_item',
					'priority' => ( $loop_hook_priority ?? 7 ),
				],
				'after_add_to_cart'    => [
					'hook'     => 'woocommerce_after_shop_loop_item',
					'priority' => ( $loop_hook_priority ?? 15 ),
				],
				'custom'               => [
					'hook'     => ( $this->options['loop_custom_hook_name'] ?? '' ),
					'priority' => ( $loop_hook_priority ?? 10 ),
				],
			]
		);

		$loop_group_position = ( $this->options['loop_group_position'] ?? 'after_add_to_cart' );

		if ( 'shortcode' !== $loop_group_position && ! empty( $positions[ $loop_group_position ]['hook'] ) ) {
			add_action(
				$positions[ $loop_group_position ]['hook'],
				[
					$this,
					'add_group_badges_for_loop',
				],
				( $positions[ $loop_group_position ]['priority'] ?? '' )
			);
		}
	}

	/**
	 * Badges Shortcode callable function
	 *
	 * @return string [HTML]
	 */
	public function badges_shortcode() {
		global $product;

		if ( ! $product instanceof WC_Product ) {
			return '';
		}

		ob_start();
		$this->all_badges_for_product();

		return ob_get_clean();
	}

	/**
	 * Image badge styles.
	 *
	 * @param array $parent_selector Selectors.
	 * @param array $badge Badges array.
	 *
	 * @return string
	 */
	private function print_images_style( $parent_selector, $badge ) {
		$style = '';

		if ( empty( $parent_selector ) ) {
			return '';
		}

		$selectors = [
			'width'  => '.index-' . sanitize_title( $badge['title'] ) . ':not(.rtsb-badge-manual-style)',
			'height' => '.index-' . sanitize_title( $badge['title'] ) . ':not(.rtsb-badge-manual-style)',
		];

		foreach ( $selectors as $key => $selector ) {
			$selectors[ $key ] = $this->generate_selector( $parent_selector, $selector );
		}

		if ( ! empty( $badge['badge_width'] ) ) {
			$width  = 'width:' . $badge['badge_width'] . ';';
			$style .= $selectors['width'] . '{' . $width . 'max-width: initial;}';
		}

		if ( ! empty( $badge['badge_height'] ) ) {
			$height = 'height:' . $badge['badge_height'] . ';';
			$style .= $selectors['height'] . '{' . $height . '}';
		}

		return $style;
	}

	/**
	 * Text badge styles.
	 *
	 * @param array $parent_selector Selectors.
	 * @param array $badge Badges array.
	 *
	 * @return string
	 */
	private function print_text_style( $parent_selector, $badge ) {
		$style = '';
		if ( empty( $parent_selector ) ) {
			return '';
		}

		$selectors = [
			'color'            => '.index-' . sanitize_title( $badge['title'] ) . ':not(.rtsb-badge-manual-style) span',
			'bg_color'         => '.index-' . sanitize_title( $badge['title'] ) . ':not(.rtsb-badge-manual-style) span',
			'border_color'     => '.index-' . sanitize_title( $badge['title'] ) . ':not(.rtsb-badge-manual-style) span, .index-' . sanitize_title( $badge['title'] ) . ':not(.rtsb-badge-manual-style) .rtsb-tag-outline.angle-right::after',
			'width'            => '.index-' . sanitize_title( $badge['title'] ) . ':not(.rtsb-badge-manual-style) span',
			'height'           => '.index-' . sanitize_title( $badge['title'] ) . ':not(.rtsb-badge-manual-style) span',
			'padding'          => '.index-' . sanitize_title( $badge['title'] ) . ':not(.rtsb-badge-manual-style) span',
			'border_radius'    => '.index-' . sanitize_title( $badge['title'] ) . ':not(.rtsb-badge-manual-style) span',
			'badge_font_size'  => '.index-' . sanitize_title( $badge['title'] ) . ':not(.rtsb-badge-manual-style) span',
			'badge_font_width' => '.index-' . sanitize_title( $badge['title'] ) . ':not(.rtsb-badge-manual-style) span',
		];

		foreach ( $selectors as $key => $selector ) {
			$selectors[ $key ] = $this->generate_selector( $parent_selector, $selector );
		}

		if ( ! empty( $badge['badge_text_color'] ) ) {
			$color  = 'color:' . $badge['badge_text_color'] . ';';
			$style .= $selectors['color'] . '{' . $color . '}';
		}

		if ( ! empty( $badge['badge_bg_color'] ) && in_array( ( $badge['badge_preset'] ?? '' ), [ 'preset1', 'preset2' ], true ) ) {
			$color  = 'background-color:' . $badge['badge_bg_color'] . ';';
			$color .= 'border-color:' . $badge['badge_bg_color'] . ';';
			$style .= $selectors['bg_color'] . '{' . $color . '}';
		}
		if ( ! empty( $badge['badge_border_color'] ) && in_array( ( $badge['badge_preset'] ?? '' ), [ 'preset3', 'preset4' ], true ) ) {
			$color  = 'border-color:' . $badge['badge_border_color'] . ';';
			$style .= $selectors['border_color'] . '{' . $color . '}';
		}
		// badge_border_color.
		if ( ! empty( $badge['badge_width'] ) ) {
			$width  = 'width:' . $badge['badge_width'] . ';';
			$style .= $selectors['width'] . '{' . $width . 'max-width: initial;}';
		}

		if ( ! empty( $badge['badge_height'] ) ) {
			$height = 'height:' . $badge['badge_height'] . ';';
			$style .= $selectors['height'] . '{' . $height . '}';
		}

		if ( ! empty( $badge['badge_padding'] ) ) {
			$padding = 'padding:' . $badge['badge_padding'] . ';';
			$style  .= $selectors['padding'] . '{' . $padding . '}';
		}

		if ( ! empty( $badge['border_radius'] ) ) {
			$border_radius = 'border-radius:' . $badge['border_radius'] . ';';
			$style        .= $selectors['border_radius'] . '{' . $border_radius . '}';
		}

		if ( ! empty( $badge['badge_font_size'] ) ) {
			$font_size = 'font-size:' . $badge['badge_font_size'] . ';';
			$style    .= $selectors['badge_font_size'] . '{' . $font_size . '}';
		}

		if ( ! empty( $badge['badge_font_width'] ) ) {
			$font_width = 'font-weight:' . $badge['badge_font_width'] . ';';
			$style     .= $selectors['badge_font_width'] . '{' . $font_width . '}';
		}

		return $style;
	}

	/**
	 * Selector generator.
	 *
	 * @param array  $parent_selector Selectors.
	 * @param string $suffix Suffix.
	 *
	 * @return string
	 */
	private function generate_selector( $parent_selector, $suffix ) {
		$selectors = '';

		foreach ( $parent_selector as $selector ) {
			if ( ! empty( $selectors ) ) {
				$selectors .= ',' . $selector . ' ' . $suffix;
			} else {
				$selectors = $selector . ' ' . $suffix;
			}
		}

		return $selectors;
	}

	/**
	 * Badge global CSS.
	 *
	 * @return string
	 */
	private function global_badge_css() {
		$global_css = '';
		$styles     = [
			'gap' => [
				'selector' => '.rtsb-badge-container .rtsb-badge-group-style',
				'style'    => 'gap',
				'value'    => $this->options['group_badge_gap'] ?? '',
			],
		];

		foreach ( $styles as $style ) {
			if ( ! empty( $style['value'] ) ) {
				$global_css .= $style['selector'] . '{';
				$global_css .= $style['style'] . ': ' . $style['value'] . ';';
				$global_css .= '}';
			}
		}

		return $global_css;
	}

	/**
	 * Hide Woocommerce on sale badge.
	 *
	 * @param string $onsale Text.
	 *
	 * @return string
	 */
	public function woocommerce_sale_flash( $onsale ) {
		$hide_default_badge = $this->options['hide_woocommerce_badge'] ?? false;

		if ( ! $hide_default_badge ) {
			return $onsale;
		}

		$hide_on_sale = ( $this->options['hide_on_sale'] ?? 'all_products' );

		switch ( $hide_on_sale ) {
			case 'where_custom_badge_applied':
				if ( BadgesFns::is_allowed_badge_showing() ) {
					$onsale = '';
				}
				break;
			case 'all_products':
				$onsale = '';
				break;
			default:
		}

		return $onsale;
	}

	/**
	 * Is allowed badge.
	 *
	 * @return bool
	 */
	private function is_allowed_badge_showing() {
		$badge_applied = BadgesFns::get_product_badges_for_current_product();

		return is_array( $badge_applied ) && count( $badge_applied );
	}

	/**
	 * Get Badge Html
	 *
	 * @param array $group_badges badge array.
	 * @param array $group_classes badges classes.
	 *
	 * @return string
	 */
	private function group_badges_html( $group_badges, $group_classes = [] ) {
		if ( empty( $group_badges ) ) {
			return '';
		}

		ob_start();
		?>
		<div class="rtsb-badge-group-style rtsb-promotion <?php echo esc_attr( implode( ' ', $group_classes ) ); ?>">
			<?php
			foreach ( $group_badges as $badge_applied ) {
				$badges_type   = ( $badge_applied['badges_type'] ?? 'text' );
				$badge_classes = [];
				if ( ! empty( $badge_applied['title'] ) ) {
					$badge_classes[] = 'index-' . sanitize_title( $badge_applied['title'] );
				}

				$this->generate_badges( $badge_applied, $badges_type, $badge_classes );
			}
			?>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Show badge on product thumbnail
	 *
	 * @param string $image_html The image HTML.
	 *
	 * @return string
	 */
	public function show_badge_on_loop_product_thumbnail( $image_html ) {
		if ( ! apply_filters( 'rtsb/module/badges/show', true ) ) {
			return $image_html;
		}

		global $product;

		if ( ! ( $product && $product instanceof WC_Product ) ) {
			return $image_html;
		}

		if ( ! $this->is_allowed_badge_showing() ) {
			return $image_html;
		}

		if ( empty( $this->above_image_loop ) ) {
			return $image_html;
		}

		$group_classes  = [];
		$group_position = ( $this->options['group_badge_position'] ?? 'top-left' );
		// group_badge_position.
		if ( ! empty( $this->options['group_badge_display_as'] ) ) {
			$group_classes[] = 'rtsb-group-display-as-' . $this->options['group_badge_display_as'];
		}

		$group_classes[] = 'rtsb-group-position-' . $group_position;

		return $this->image_badges_html( $image_html, $this->group_badges_html( $this->above_image_loop, $group_classes ) );
	}


	/**
	 * Show badge on product thumbnail
	 *
	 * @param string $image_html The image HTML.
	 *
	 * @return string
	 */
	public function show_badge_on_product_page_thumbnail( $image_html ) {
		if ( ! apply_filters( 'rtsb/module/badges/show', true ) ) {
			return $image_html;
		}

		global $product;

		if ( ! ( $product && $product instanceof WC_Product && ! did_action( 'woocommerce_product_thumbnails' ) ) ) {
			return $image_html;
		}

		if ( ! $this->is_allowed_badge_showing() ) {
			return $image_html;
		}

		if ( empty( $this->above_image_product ) ) {
			return $image_html;
		}

		$group_classes = [];
		if ( ! empty( $this->options['group_badge_display_as'] ) ) {
			$group_classes[] = 'rtsb-group-display-as-' . $this->options['group_badge_display_as'];
		}

		$group_position = ( $this->options['product_page_group_badge_position'] ?? 'top-left' );
		// group_badge_position.
		$group_classes[] = 'rtsb-group-position-' . $group_position;

		return $this->image_badges_html( $image_html, $this->group_badges_html( $this->above_image_product, $group_classes ) );
	}

	/**
	 * Badge HTML.
	 *
	 * @param string $image_html HTML.
	 * @param string $badge_html HTML.
	 *
	 * @return string
	 */
	private function image_badges_html( $image_html, $badge_html ) {
		if ( empty( $badge_html ) ) {
			return $image_html;
		}

		$print_badges_directly = false;
		$div_close             = '</div>';

		if ( get_theme_support( 'wc-product-gallery-slider' ) ) {
			$content = rtrim( $image_html );

			if ( strrpos( $content, $div_close ) === ( strlen( $content ) - strlen( $div_close ) ) ) {
				$print_badges_directly = true;
				$image_html            = $content;
			}
		}

		if ( $print_badges_directly ) {
			$image_html  = substr( $image_html, 0, - strlen( $div_close ) );
			$image_html .= $badge_html;
			$image_html .= $div_close;
		} else {
			$image_html = "<div class='rtsb-badge-container'>" . $image_html . $badge_html . '</div>';
		}

		return $image_html;
	}
}
