<?php
/**
 * Render class for Advanced Heading widget.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\General\PostList;

use DateTime;
use DateTimeZone;
use RadiusTheme\SB\Elementor\Helper\ControlHelper;
use RadiusTheme\SB\Elementor\Helper\PostHelpers;
use RadiusTheme\SB\Elementor\Helper\RenderHelpers;
use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Elementor\Render\GeneralAddons;
use RadiusTheme\SB\Models\PostQueryArgs;
use WP_Query;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Render class.
 *
 * @package RadiusTheme\SB
 */
class Render extends GeneralAddons {
	/**
	 * Main render function for displaying content.
	 *
	 * @param array $data     Data to be passed to the template.
	 * @param array $settings Widget settings.
	 *
	 * @return string
	 */
	public function display_content( $data, $settings ) {
		$data['content'] = '';
		$this->settings  = $settings;
		$data            = wp_parse_args( $this->get_template_args( $data ), $data );
		$data            = apply_filters( 'rtsb/general/post_list/args/' . $data['unique_name'], $data );
		$metas           = PostHelpers::post_meta_dataset( $this->settings, $data['template'], $this->settings );

		$args = ( new PostQueryArgs() )->buildArgs( $metas );
		/**
		 * Before Post query hook.
		 */
		do_action( 'rtsb/elements/render/before_post_query', $this->settings, $args );

		$query = new WP_Query( $args );

		if ( $query->have_posts() ) {
			$data['post_query'] = $query;
			while ( $query->have_posts() ) {
				$query->the_post();
				$data['grid_classes'] = $this->content_classes;

				$data             = array_merge(
					$data,
					PostHelpers::get_post_arg_dataset( $this->settings )
				);
				$data['content'] .= Fns::load_template( $data['template'], $data, true );
			}
		} else {
			$data['content'] = $this->no_posts_msg();
		}

		wp_reset_postdata();

		unset( $args['tax_query'] );

		/**
		 * After Product query hook.
		 */
		do_action( 'rtsb/elements/render/after_post_query', $settings, $args );
		return $this->addon_view( $data, $settings );
	}
	/**
	 * Retrieves template arguments based on widget settings.
	 *
	 * @param array $data Data to be passed to the template.
	 *
	 * @return array
	 */
	private function get_template_args( $data ) {
		if ( ! empty( $data['content_class'] ) ) {
			$this->content_classes[] = $data['content_class'];
		}
		$this->content_classes = is_array( $this->content_classes ) ? implode( ' ', $this->content_classes ) : $this->content_classes;

		return [
			'title_limit'            => $this->settings['title_limit'] ?? 'default',
			'excerpt_limit'          => $this->settings['excerpt_limit'] ?? 'default',
			'title_tag'              => $this->settings['title_tag'] ?? 'h2',
			'title_limit_custom'     => $this->settings['title_limit_custom'] ?? '',
			'excerpt_limit_custom'   => $this->settings['excerpt_limit_custom'] ?? '200',
			'show_title'             => ! empty( $this->settings['show_title'] ),
			'show_short_desc'        => ! empty( $this->settings['show_short_desc'] ),
			'show_post_thumbnail'    => ! empty( $this->settings['show_post_thumbnail'] ),
			'show_categories'        => ! empty( $this->settings['show_categories'] ),
			'show_tags'              => ! empty( $this->settings['show_tags'] ),
			'show_dates'             => ! empty( $this->settings['show_dates'] ),
			'show_author'            => ! empty( $this->settings['show_author'] ),
			'show_comment'           => ! empty( $this->settings['show_comment'] ),
			'show_reading_time'      => ! empty( $this->settings['show_reading_time'] ),
			'show_post_views'        => ! empty( $this->settings['show_post_views'] ),
			'show_read_more_btn'     => ! empty( $this->settings['show_read_more_btn'] ),
			'show_author_icon'       => ! empty( $this->settings['show_author_icon'] ),
			'show_date_icon'         => ! empty( $this->settings['show_date_icon'] ),
			'show_comment_icon'      => ! empty( $this->settings['show_comment_icon'] ),
			'show_reading_time_icon' => ! empty( $this->settings['show_reading_time_icon'] ),
			'show_post_views_icon'   => ! empty( $this->settings['show_post_views_icon'] ),
			'author_icon_html'       => $this->render_meta_icon( $this->settings['author_icon'] ?? '', 'author-icon' ),
			'date_icon_html'         => $this->render_meta_icon( $this->settings['date_icon'] ?? '', 'date-icon' ),
			'comment_icon_html'      => $this->render_meta_icon( $this->settings['comment_icon'] ?? '', 'comment-icon' ),
			'reading_time_icon_html' => $this->render_meta_icon( $this->settings['reading_time_icon'] ?? '', 'reading-time-icon' ),
			'post_views_icon_html'   => $this->render_meta_icon( $this->settings['post_views_icon'] ?? '', 'post-views-icon' ),
			'title_link'             => ! empty( $this->settings['title_link'] ),
			'image_link'             => ! empty( $this->settings['image_link'] ),
			'button_text'            => $this->settings['button_text'] ?? '',
			'button_icon'            => $this->settings['button_icon'] ?? '',
			'button_icon_position'   => $this->settings['button_icon_position'] ?? 'right',
			'meta_separator'         => $this->settings['meta_separator'] ?? '',
			'item_class'             => $this->generate_post_item_class(),
		];
	}
	/**
	 * Gets row arguments.
	 *
	 * @return array Row arguments.
	 */
	protected function get_row_args() {
		$limit          = ( empty( $this->settings['posts_limit'] ) || '-1' === $this->settings['posts_limit'] ) ? 10000000 : $this->settings['posts_limit'];
		$posts_per_page = $this->settings['pagination_per_page'] ?? '';

		return [
			'posts_limit'    => $limit,
			'posts_per_page' => $posts_per_page,
		];
	}
	/**
	 * Function to render icon.
	 *
	 * @param string $position icon position ('left' or 'right').
	 * @param array  $icon Position of the separator in settings.
	 * @param string $icon_position icon position.
	 *
	 * @return string
	 */
	public static function render_icon( $position, $icon, $icon_position ) {
		$html = '';

		// Render the left icon.
		if ( $position === $icon_position ) {
			$html .= '<span class="icon">' . Fns::icons_manager( $icon ) . '</span>';
		}

		return $html;
	}
	/**
	 * Function to render meta icon.
	 *
	 * @param array  $icon Position of the separator in settings.
	 * @param string $class icon class.
	 *
	 * @return string
	 */
	public static function render_meta_icon( $icon, $class ) {
		$html = '';
		// Render the left icon.

		$html .= '<span class="icon ' . esc_attr( $class ) . '">' . Fns::icons_manager( $icon ) . '</span>';

		return $html;
	}
	/**
	 * Function to generate item class.
	 *
	 * @return string
	 */
	public function generate_post_item_class() {
		$classes = 'rtsb-post-list-item ';
		if ( empty( $this->settings['image_link'] ) ) {
			$classes .= 'no-img-linkable ';
		}
		if ( ! empty( $this->settings['image_hover_effect'] ) ) {
			$classes .= $this->settings['image_hover_effect'];
		}
		return $classes;
	}
}
