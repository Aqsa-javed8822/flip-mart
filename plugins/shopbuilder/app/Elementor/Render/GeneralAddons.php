<?php
/**
 * Elementor GeneralAddons Class.
 *
 * This class contains render logics & output for general addons.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Render;

use RadiusTheme\SB\Elementor\Helper\RenderHelpers;
use RadiusTheme\SB\Helpers\Fns;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Elementor Render Class.
 */
class GeneralAddons extends Render {
	/**
	 * HTML.
	 *
	 * @var string
	 */
	private $addon_html = null;

	/**
	 * Element ID.
	 *
	 * @var string
	 */
	private $id = null;

	/**
	 * Unique name.
	 *
	 * @var string
	 */
	public $unique_name = '';

	/**
	 * Template name.
	 *
	 * @var string
	 */
	public $template_name = '';

	/**
	 * Settings.
	 *
	 * @var array
	 */
	public $settings = [];

	/**
	 * Carousel check.
	 *
	 * @var bool
	 */
	public $is_carousel = false;

	/**
	 * Blog Post check.
	 *
	 * @var bool
	 */
	public $is_post_query = false;

	/**
	 * Blog Post Pagination.
	 *
	 * @var bool
	 */
	public $is_post_pagination = false;

	/**
	 * Grid check.
	 *
	 * @var bool
	 */
	public $is_grid = false;

	/**
	 * Content classes.
	 *
	 * @var bool
	 */
	public $content_classes = [
		'rtsb-col-grid',
	];

	/**
	 * Renders the addon view.
	 *
	 * @param array $data The addon data.
	 * @param array $settings The addon settings.
	 *
	 * @return string
	 */
	public function addon_view( $data, $settings = [] ) {
		$this->id                 = $data['id'];
		$this->unique_name        = $data['unique_name'];
		$this->template_name      = $data['template'];
		$this->settings           = $settings;
		$this->is_carousel        = $data['is_carousel'] ?? false;
		$this->is_post_query      = $data['is_post_query'] ?? false;
		$this->is_grid            = $data['is_grid'] ?? false;
		$this->is_post_pagination = $data['is_post_pagination'] ?? false;
		$content                  = $data['content'];

		if ( ! ( $this->is_grid || empty( $data['content_class'] ) ) ) {
			$this->content_classes[] = $data['content_class'];
			$this->content_classes   = implode( ' ', $this->content_classes );
		}

		// Container.
		$this->addon_container( $content, $data );

		return $this->addon_html;
	}

	/**
	 * Generates the container HTML.
	 *
	 * @param string $content The content inside the container.
	 * @param array  $args Additional arguments for the container.
	 *
	 * @return string
	 */
	public function addon_container( $content, $args = [] ) {
		$container_id         = 'rtsb-container-' . $this->id;
		$container_classes    = $this->get_container_classes( $args['container_class'] ?? '' );
		$raw_layout           = $args['layout'] ?? '';
		$template             = $args['template'];
		$layout               = RenderHelpers::filter_layout( $raw_layout, $template );
		$style_attr           = '--rtsb-default-columns: ' . esc_attr( RenderHelpers::default_columns( $layout ) );
		$container_attributes = [
			'id'          => $container_id,
			'class'       => $container_classes,
			'data-layout' => $layout,
		];
		if ( $this->is_grid ) {
			$container_attributes['style'] = apply_filters( 'rtsb/general_widget/container/attr', $style_attr, $this->settings );
		}

		if ( ! empty( $args['container_attr'] ) ) {
			$container_attributes = array_merge( $container_attributes, $args['container_attr'] );
		}

		$row_args = wp_parse_args(
			$this->get_row_args(),
			[
				'layout'        => $raw_layout,
				'template'      => $template,
				'content_class' => $args['content_class'] ?? '',
				'post_query'    => $args['post_query'] ?? '',
			]
		);

		// Adding render attributes.
		$this->add_attribute( 'rtsb_addon_container_attr_' . $this->id, $container_attributes );

		// Generate HTML for the container.
		$this->addon_html = '<div ' . $this->get_attribute_string( 'rtsb_addon_container_attr_' . $this->id ) . '>';
		$this->addon_row( $content, $row_args );
		$this->addon_html .= '</div><!-- .rtsb-container -->';

		return $this->addon_html;
	}

	/**
	 * Generates the row HTML.
	 *
	 * @param string $content The content inside the row.
	 * @param array  $args Additional arguments for the row.
	 *
	 * @return string
	 */
	public function addon_row( $content, $args = [] ) {
		$layout      = RenderHelpers::filter_layout( $args['layout'] ?? '' );
		$row_classes = $this->get_row_classes( $layout );

		// Adding render attributes.
		$this->add_attribute( 'rtsb_addon_row_attr_' . $this->id, 'class', $row_classes );

		// Start rendering the row.
		$this->addon_html .= '<div ' . $this->get_attribute_string( 'rtsb_addon_row_attr_' . $this->id ) . '>';
		if ( $this->is_carousel ) {
			$this->slider_wrapper_start();
		}

		// Rendering the content.
		$this->get_content( $content, $args );

		if ( $this->is_carousel ) {
			$this->slider_wrapper_end();
		}
		$this->addon_html .= '</div><!-- .rtsb-row -->';

		if ( $this->is_post_query && $this->is_post_pagination && ! empty( $args['post_query'] ) ) {
			$this->addon_html .= $this->render_posts_pagination( $args );
		}
		// Preloader.

		$this->addon_html .= $this->pre_loader();

		return $this->addon_html;
	}

	/**
	 * Renders the content inside the row.
	 *
	 * @param string $content The content.
	 * @param array  $args Additional arguments for the content.
	 *
	 * @return void
	 */
	protected function get_content( $content, $args = [] ) {
		// Adding render attributes.
		$this->add_attribute( 'rtsb_addon_content_attr_' . $this->id, 'class', $this->content_classes );

		// Render content HTML.
		$this->addon_html .= ! $this->is_grid ? '<div ' . $this->get_attribute_string( 'rtsb_addon_content_attr_' . $this->id ) . '>' : '';
		$this->addon_html .= $content;
		$this->addon_html .= ! $this->is_grid ? '</div><!-- .rtsb-col-grid -->' : '';
	}

	/**
	 * Gets container classes.
	 *
	 * @param string $custom Optional custom classes.
	 *
	 * @return string
	 */
	protected function get_container_classes( $custom = '' ) {
		$classes = [
			'rtsb-elementor-container',
			'rtsb-pos-r',
			$this->unique_name,
		];

		if ( ! empty( $custom ) ) {
			$classes[] = $custom;
		}
		if ( ! empty( $this->settings['slider_slide_animation'] ) ) {
			$classes[] = 'has-slide-animation';
		}
		$classes = implode( ' ', $classes );

		return apply_filters( 'rtsb/general/addon_container/class', $classes, $this->settings );
	}

	/**
	 * Gets row classes.
	 *
	 * @param string $layout Optional layout name.
	 *
	 * @return string
	 */
	protected function get_row_classes( $layout = '' ) {
		$classes = [
			'rtsb-row',
			'rtsb-content-loader',
		];

		if ( ! empty( $layout ) ) {
			$classes[] = $layout;
		}

		if ( $this->is_carousel ) {
			$classes[] = 'rtsb-slider-layout';
		}
		if ( $this->is_carousel && ! empty( $this->settings['always_show_nav'] ) ) {
			$classes[] = 'always-show-nav';
		}
		if ( ! empty( $this->settings['cols_mobile'] ) && '1' == $this->settings['cols_mobile'] ) {
			$classes[] = ' rtsb-mobile-flex-row';
		}
		$classes = implode( ' ', $classes );

		return apply_filters( 'rtsb/general/addon_row/class', $classes, $this->settings );
	}

	/**
	 * Gets row arguments.
	 *
	 * @return array Row arguments.
	 */
	protected function get_row_args() {
		return [];
	}

	/**
	 * Starts the carousel slider wrapper.
	 *
	 * @return void
	 */
	private function slider_wrapper_start() {
		if ( 'rtsb-hero-slider' === $this->unique_name ) {
			$slider_data = self::get_hero_slider_data( $this->settings );
		} else {
			$slider_data    = $this->get_slider_data();
			$slider_options = $slider_data['data'] ?? '';
			$slider_classes = $slider_data['class'] ?? '';
			$slider_data    = [
				'class' => 'rtsb-carousel-slider swiper ' . $slider_classes,
				'data'  => $slider_options,
			];
		}

		// Adding slider attributes.
		$this->add_attribute(
			'rtsb_slider_attr_' . $this->id,
			[
				'class'        => 'rtsb-col-grid ' . $slider_data['class'],
				'data-options' => $slider_data['data'],
			]
		);

		// Render slider wrapper.
		$this->addon_html .= '<div ' . $this->get_attribute_string( 'rtsb_slider_attr_' . $this->id ) . '>';
		$this->addon_html .= '<div class="swiper-wrapper">';
	}

	/**
	 * Ends the carousel slider wrapper.
	 *
	 * @return void
	 */
	private function slider_wrapper_end() {
		$this->addon_html .= '</div><!-- .swiper-wrapper -->';
		$this->addon_html .= $this->slider_buttons( $this->settings );
		$this->addon_html .= '</div><!-- .rtsb-carousel-slider -->';
	}

	/**
	 * Gets slider data for the carousel.
	 *
	 * @return array
	 */
	protected function get_slider_data() {
		$metas = $this->get_slider_meta_dataset( $this->settings, $this->template_name, $this->settings );

		return RenderHelpers::slider_data( (array) $metas, $this->settings );
	}

	/**
	 * Prepares and returns slider configuration dataset for rendering in Elementor or frontend use.
	 *
	 * This method collects various slider-related metadata settings, normalizes them using helper
	 * functions, and returns a structured dataset used to initialize and render a responsive slider.
	 *
	 * @since 1.0.0
	 *
	 * @param array  $meta         Metadata array containing configuration values for the slider.
	 *                             Example keys: 'cols', 'cols_tablet', 'slider_loop', etc.
	 * @param string $template     The template identifier or slug used for rendering slider content.
	 * @param array  $raw_settings Raw settings passed directly from Elementor widget settings or similar source.
	 *
	 * @return array|null          Returns an array of slider settings suitable for frontend use,
	 *                             optionally modified by the 'rtsb/elementor/render/slider_dataset_final' filter.
	 */
	protected function get_slider_meta_dataset( array $meta, $template = '', $raw_settings = [] ) {
		$data = [
			'widget'             => 'custom',
			'widget_name'        => $this->unique_name,
			'template'           => RenderHelpers::get_data( $template, '', '' ),
			'raw_settings'       => $raw_settings,
			'd_cols'             => RenderHelpers::get_data( $meta, 'cols', 0 ),
			't_cols'             => RenderHelpers::get_data( $meta, 'cols_tablet', 2 ),
			'm_cols'             => RenderHelpers::get_data( $meta, 'cols_mobile', 1 ),
			'd_group'            => RenderHelpers::get_data( $meta, 'cols_group', 1 ),
			't_group'            => RenderHelpers::get_data( $meta, 'cols_group_tablet', 1 ),
			'm_group'            => RenderHelpers::get_data( $meta, 'cols_group_mobile', 1 ),
			'layout'             => RenderHelpers::get_data( $meta, 'layout_style', 'layout1' ),
			'auto_play'          => ! empty( $meta['slide_autoplay'] ),
			'stop_on_hover'      => ! empty( $meta['pause_hover'] ),
			'slider_nav'         => ! empty( $meta['slider_nav'] ),
			'slider_dot'         => ! empty( $meta['slider_pagi'] ),
			'slider_dynamic_dot' => ! empty( $meta['slider_dynamic_pagi'] ),
			'loop'               => ! empty( $meta['slider_loop'] ),
			'lazy_load'          => ! empty( $meta['slider_lazy_load'] ),
			'auto_height'        => ! empty( $meta['slider_auto_height'] ),
			'speed'              => RenderHelpers::get_data( $meta, 'slide_speed', 2000 ),
			'space_between'      => isset( $meta['grid_gap']['size'] ) && strlen( $meta['grid_gap']['size'] ) ? $meta['grid_gap']['size'] : 30,
			'auto_play_timeout'  => RenderHelpers::get_data( $meta, 'autoplay_timeout', 5000 ),
			'nav_position'       => RenderHelpers::get_data( $meta, 'slider_nav_position', 'standard' ),
			'left_arrow_icon'    => RenderHelpers::get_data( $meta, 'slider_left_arrow_icon', [] ),
			'right_arrow_icon'   => RenderHelpers::get_data( $meta, 'slider_right_arrow_icon', [] ),
		];
		if ( ! empty( $meta['cols_widescreen'] ) ) {
			$data['w_cols'] = $meta['cols_widescreen'];
		}

		if ( ! empty( $meta['cols_laptop'] ) ) {
			$data['l_cols'] = $meta['cols_laptop'];
		}

		if ( ! empty( $meta['cols_tablet_extra'] ) ) {
			$data['te_cols'] = $meta['cols_tablet_extra'];
		}

		if ( ! empty( $meta['cols_mobile_extra'] ) ) {
			$data['me_cols'] = $meta['cols_mobile_extra'];
		}

		return apply_filters( 'rtsb/elementor/render/slider_dataset_final', $data, $meta, $raw_settings );
	}

	/**
	 * Generates the configuration and CSS class for the hero slider carousel.
	 *
	 * Prepares Swiper-compatible slider options and associated class names based on
	 * the provided metadata. This includes autoplay settings, pagination type,
	 * lazy loading, effects (e.g., parallax, fade), and responsive breakpoints.
	 *
	 * @since 1.0.0
	 *
	 * @param array $meta Metadata array for the slider settings, including keys like
	 *                    'slide_speed', 'slider_loop', 'slider_pagi_type', etc.
	 *
	 * @return array Returns an associative array containing:
	 *               - 'data' (string): JSON-encoded slider options for Swiper.
	 *               - 'class' (string): Additional CSS class names for the carousel container.
	 */
	protected function get_hero_slider_data( array $meta ) {
		$has_dots         = $meta['slider_pagi'] ? ' has-dot' : ' no-dot';
		$has_dots        .= $meta['slider_nav'] ? ' has-nav' : ' no-nav';
		$has_dynamic_dots = $meta['slider_dynamic_pagi'] ? true : false;
		$breakpoints      = [
			0    => [
				'slidesPerView'  => 1,
				'slidesPerGroup' => 1,
				'pagination'     => [
					'dynamicBullets' => $has_dynamic_dots,
					'type'           => RenderHelpers::get_data( $meta, 'slider_pagi_type', 'bullet' ),
				],
			],
			768  => [
				'slidesPerView'  => 1,
				'slidesPerGroup' => 1,
				'pagination'     => [
					'dynamicBullets' => $has_dynamic_dots,
					'type'           => RenderHelpers::get_data( $meta, 'slider_pagi_type', 'bullet' ),
				],
			],
			1025 => [
				'slidesPerView'  => 1,
				'slidesPerGroup' => 1,
				'pagination'     => [
					'dynamicBullets' => $has_dynamic_dots,
					'type'           => RenderHelpers::get_data( $meta, 'slider_pagi_type', 'bullet' ),
				],
			],
		];
		$slider_options   = [
			'slidesPerView'  => 1,
			'slidesPerGroup' => 1,
			'speed'          => absint( RenderHelpers::get_data( $meta, 'slide_speed', 2000 ) ),
			'loop'           => ! empty( $meta['slider_loop'] ),
			'autoHeight'     => ! empty( $meta['slider_auto_height'] ),
			'preloadImages'  => ! empty( $meta['slider_lazy_load'] ),
			'lazy'           => ! empty( $meta['slider_lazy_load'] ),
			'breakpoints'    => $breakpoints,
		];
		if ( $meta['slide_autoplay'] ) {
			$slider_options['autoplay'] = [
				'delay'                => absint( RenderHelpers::get_data( $meta, 'autoplay_timeout', 5000 ) ),
				'pauseOnMouseEnter'    => ! empty( $meta['pause_hover'] ),
				'disableOnInteraction' => false,
			];
		}
		if ( 'fraction' === $meta['slider_pagi_type'] ) {
			unset( $slider_options['pagination']['dynamicBullets'] );
		}
		if ( 'parallax' === $meta['slider_effect'] ) {
			$slider_options['parallax'] = true;
		} else {
			$slider_options['effect'] = $meta['slider_effect'];
		}
		if ( 'fade' === $meta['slider_effect'] ) {
			$slider_options['crossFade'] = true;
		}

		$slider_options = apply_filters( 'rtsb/elementor/render/hero_slider_dataset', $slider_options, $this->settings );
		$carouselClass  = 'rtsb-hero-carousel-slider slider-loading rtsb-pos-s ' . $has_dots;
		return [
			'data'  => esc_js( wp_json_encode( $slider_options ) ),
			'class' => $carouselClass,
		];
	}


	/**
	 * Renders the carousel slider navigation arrows and pagination markup.
	 *
	 * This method returns HTML for Swiper navigation buttons (left/right arrows)
	 * and optional pagination dots, depending on the provided slider settings.
	 *
	 * @since 1.0.0
	 *
	 * @param array $settings Slider configuration options. Expected keys:
	 *                         - 'slider_left_arrow_icon' (string)  Icon for the left arrow.
	 *                         - 'slider_right_arrow_icon' (string) Icon for the right arrow.
	 *                         - 'slider_nav' (bool)                Whether to show navigation arrows.
	 *                         - 'slider_pagi' (bool)               Whether to show pagination.
	 *                         - 'slider_pagi_type' (string)        (Optional) Pagination style (e.g., 'bullets', 'fraction').
	 *
	 * @return string HTML markup for the slider navigation and pagination.
	 */
	protected function slider_buttons( $settings ) {
		$html             = '';
		$pagination_type  = '';
		$left_icon        = $settings['slider_left_arrow_icon'] ?? '';
		$right_icon       = $settings['slider_right_arrow_icon'] ?? '';
		$is_hero_carousel = 'rtsb-hero-slider' === $this->unique_name;
		if ( $is_hero_carousel && ! empty( $settings['slider_pagi_type'] ) ) {
			$pagination_type = $settings['slider_pagi_type'];
		}
		if ( ! empty( $settings['slider_nav'] ) ) {
			$html .=
				'<div class="swiper-nav">
					<div class="swiper-arrow swiper-button-next">
						' . Fns::icons_manager( $right_icon ) . '
					</div>
					<div class="swiper-arrow swiper-button-prev">
						' . Fns::icons_manager( $left_icon ) . '
					</div>
				</div>';
		}

		$html .= ! empty( $settings['slider_pagi'] ) ? '<div class="swiper-pagination rtsb-pos-s ' . esc_attr( $pagination_type ) . '"></div>' : '';

		/**
		 * Filters the slider navigation + pagination markup.
		 *
		 * @param string        $html     Slider buttons (arrows + pagination) HTML.
		 * @param array         $settings Widget settings.
		 * @param GeneralAddons $instance Current render instance (exposes unique_name, id, etc.).
		 */
		return apply_filters( 'rtsb/general/slider_buttons/html', $html, $settings, $this );
	}
	/**
	 * Preloader.
	 *
	 * @return string
	 */
	public function pre_loader() {
		if ( ! Fns::enable_loader() ) {
			return '';
		}
		if ( $this->is_carousel ) {
			return '<div class="rtsb-elements-loading rtsb-ball-clip-rotate"><div></div></div>';
		}
		return '';
	}
	/**
	 * No posts message.
	 *
	 * @return string
	 */
	public function no_posts_msg() {
		return '<div class="rtsb-notice">
            <div class="woocommerce-info">' . esc_html__( 'No posts were found matching your selection.', 'shopbuilder' ) . '</div>
        </div>';
	}
	/**
	 * Renders posts pagination
	 *
	 * @param array $args Pagination args.
	 *
	 * @return string
	 */
	public function render_posts_pagination( $args ) {
		list(
			'post_query'    => $query,
			'posts_limit'    => $limit,
			'posts_per_page' => $per_page
			)           = $args;
		$html_utility   = null;
		$html           = null;
		$posts_per_page = $query->query_vars['posts_per_page'];
		$found_posts    = $query->found_posts;
		$total_page     = $query->max_num_pages;
		if ( $limit ) {
			if ( ( empty( $wp_query->query['tax_query'] ) ) ) {
				$found_posts = $query->found_posts;
			}

			if ( $per_page && $found_posts > $per_page ) {
				$found_posts = $limit;
				$total_page  = ceil( $found_posts / $per_page );
			}
		}

		$total_page = absint( $total_page );

		$html_utility .= Fns::custom_pagination(
			$total_page,
			$posts_per_page
		);

		if ( $html_utility ) {
			$rand = wp_rand();

			// Adding pagination render attributes.
			$this->add_attribute(
				'rtsb_pagination_attr_' . $rand,
				[
					'class'               => 'rtsb-pagination-wrap element-loading',
					'data-total-pages'    => $total_page,
					'data-posts-per-page' => $posts_per_page,
					'data-type'           => 'pagination',
				]
			);

			// Start rendering.
			$html  = '<div ' . $this->get_attribute_string( 'rtsb_pagination_attr_' . $rand ) . '>';
			$html .= $html_utility;
			$html .= '</div><!-- .rtsb-pagination-wrap -->';
		}

		return $html;
	}

	/**
	 * Function to render buttom html.
	 *
	 * @param array $args Button Arguments.
	 *
	 * @return void
	 */
	public static function render_buttom_html( $args ) {
		$default_args = [
			'button_attributes'       => '',
			'sb_button_icon'          => '',
			'sb_button_icon_position' => 'right',
			'sb_button_content'       => esc_html__( 'ShopBuilder Button', 'shopbuilder' ),
		];
		$args         = wp_parse_args( $args, $default_args );
		?>
		<a <?php Fns::print_html( $args['button_attributes'] ); ?>>
			<span class="text-wrap <?php echo esc_attr( 'icon-' . $args['sb_button_icon_position'] ); ?>">
				<?php
				Fns::print_html( self::render_button_icon( 'left', $args['sb_button_icon'], $args['sb_button_icon_position'] ) );
				Fns::print_html( '<span class="text">' );
				Fns::print_html( $args['sb_button_content'] );
				Fns::print_html( '</span>' );
				Fns::print_html( '<span class="hover-text">' );
				Fns::print_html( $args['sb_button_content'] );
				Fns::print_html( '</span>' );
				Fns::print_html( self::render_button_icon( 'right', $args['sb_button_icon'], $args['sb_button_icon_position'] ) );
				?>
			</span>
		</a>
		<?php
	}
	/**
	 * Renders an icon HTML markup based on position match.
	 *
	 * Compares the desired icon position with the configured position
	 * and renders the icon HTML if they match.
	 *
	 * @since 1.0.0
	 *
	 * @param string $position       Desired position to render the icon ('left' or 'right').
	 * @param string $icon           Icon identifier or SVG markup.
	 * @param string $icon_position  Actual position set in the settings ('left' or 'right').
	 *
	 * @return string Icon HTML if position matches; otherwise, an empty string.
	 */
	public static function render_button_icon( $position, $icon, $icon_position ) {
		$html = '';

		// Render the left icon.
		if ( $position === $icon_position ) {
			$html .= '<span class="icon">' . Fns::icons_manager( $icon ) . '</span>';
		}

		return $html;
	}
}
