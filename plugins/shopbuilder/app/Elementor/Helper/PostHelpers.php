<?php

namespace RadiusTheme\SB\Elementor\Helper;

// Do not allow directly accessing this file.
use RadiusTheme\SB\Elementor\Helper\ControlHelper;
use RadiusTheme\SB\Elementor\Helper\RenderHelpers;
use RadiusTheme\SB\Elementor\Widgets\Controls\StyleFields as StyleFieldsFree;
use RadiusTheme\SB\Helpers\Fns;

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}
/**
 * Post Helpers class.
 *
 * @package RadiusTheme\SB
 */
class PostHelpers {
	/**
	 * Query section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function query( $obj ) {
		$fields['query_section'] = $obj->start_section(
			esc_html__( 'Query', 'shopbuilder' ),
			'content'
		);

		$fields['query_note'] = $obj->el_heading( esc_html__( 'Common Filters', 'shopbuilder' ) );

		$fields['include_posts'] = [
			'type'        => 'rt-select2',
			'label'       => esc_html__( 'Include Post', 'shopbuilder' ),
			'description' => esc_html__( 'Please select the products to show. Leave it blank to include all products.', 'shopbuilder' ),
			'source_name' => 'post_type',
			'source_type' => 'post',
			'multiple'    => true,
			'label_block' => true,
		];

		$fields['exclude_posts'] = [
			'type'        => 'rt-select2',
			'label'       => esc_html__( 'Exclude Post', 'shopbuilder' ),
			'description' => esc_html__( 'Please select the products to show. Leave it blank to exclude none.', 'shopbuilder' ),
			'source_name' => 'post_type',
			'source_type' => 'post',
			'multiple'    => true,
			'label_block' => true,
		];

		$fields['posts_limit'] = [
			'type'        => 'number',
			'label'       => esc_html__( 'Posts Limit', 'shopbuilder' ),
			'description' => esc_html__( 'The number of products to show. Set empty to show all products.', 'shopbuilder' ),
			'default'     => 12,
		];

		$fields['posts_offset'] = [
			'type'        => 'number',
			'label'       => esc_html__( 'Posts Offset', 'shopbuilder' ),
			'description' => esc_html__( 'Number of products to skip.', 'shopbuilder' ),
		];

		$fields['category_note'] = $obj->el_heading( esc_html__( 'Taxonomy Filters', 'shopbuilder' ), 'before' );

		$fields['filter_categories'] = [
			'type'                 => 'rt-select2',
			'label'                => esc_html__( 'Filter By Categories', 'shopbuilder' ),
			'description'          => esc_html__( 'Select the categories you want to filter, Leave it blank for all categories.', 'shopbuilder' ),
			'source_name'          => 'taxonomy',
			'source_type'          => 'category',
			'multiple'             => true,
			'label_block'          => true,
			'minimum_input_length' => 1,
		];

		$fields['filter_tags'] = [
			'type'                 => 'rt-select2',
			'label'                => esc_html__( 'Filter By Tags', 'shopbuilder' ),
			'description'          => esc_html__( 'Select the tags you want to filter, Leave it blank for all tags.', 'shopbuilder' ),
			'source_name'          => 'taxonomy',
			'source_type'          => 'post_tag',
			'multiple'             => true,
			'label_block'          => true,
			'minimum_input_length' => 1,
		];

		$fields['tax_relation'] = [
			'type'        => 'select2',
			'label'       => esc_html__( 'Taxonomy relation', 'shopbuilder' ),
			'options'     => [
				'OR'  => 'Show All Products (OR)',
				'AND' => 'Show Common Products (AND)',
			],
			'default'     => 'AND',
			'description' => esc_html__( 'Select the taxonomies relationship. It is applicable if you select more than one taxonomy.', 'shopbuilder' ),
			'label_block' => true,
		];

		$fields['sorting_note'] = $obj->el_heading( esc_html__( 'Sorting', 'shopbuilder' ), 'before' );

		$fields['posts_order_by'] = [
			'type'    => 'select2',
			'label'   => esc_html__( 'Order By', 'shopbuilder' ),
			'options' => ControlHelper::posts_order_by(),
			'default' => 'date',
		];

		$fields['posts_order'] = [
			'type'    => 'select2',
			'label'   => esc_html__( 'Order', 'shopbuilder' ),
			'options' => ControlHelper::posts_order(),
			'default' => 'DESC',
		];

		$fields['query_section_end'] = $obj->end_section();

		return apply_filters( 'rtsb/elements/elementor/query_control', $fields, $obj );
	}
	/**
	 * Pagination section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function pagination( $obj ) {
		$fields['pagination_section'] = $obj->start_section(
			esc_html__( 'Pagination', 'shopbuilder' ),
			'content',
		);

		$fields['show_pagination'] = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Enable Pagination?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to enable pagination.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
		];

		$fields['pagination_per_page'] = [
			'type'        => 'number',
			'label'       => esc_html__( 'Number of Posts Per Page', 'shopbuilder' ),
			'default'     => 8,
			'description' => esc_html__( 'Please enter the number of products per page to show.', 'shopbuilder' ),
			'condition'   => [ 'show_pagination' => [ 'yes' ] ],
		];

		$fields['pagination_section_end'] = $obj->end_section();

		return apply_filters( 'rtsb/elementor/post_pagination_control', $fields, $obj );
	}
	/**
	 * Visibility section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function content_visibility( $obj ) {
		$fields['visibility_section']  = $obj->start_section(
			esc_html__( 'Content Visibility', 'shopbuilder' ),
			'settings'
		);
		$fields['show_title']          = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Show Post Title?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to show post title.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'default'     => 'yes',
		];
		$fields['show_post_thumbnail'] = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Show Post Thumbnail?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to show post thumbnail.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'default'     => 'yes',
		];
		$fields['show_short_desc']     = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Show Excerpt?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to show post excerpt.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'default'     => 'yes',
		];

		$fields['show_categories']    = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Show Post Categories?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to show product categories.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'default'     => 'yes',
		];
		$fields['show_tags']          = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Show Post Tags?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to show product categories.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
		];
		$fields['show_dates']         = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Show Post Dates', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to show post dates.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'default'     => 'yes',
		];
		$fields['show_author']        = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Show Post Author', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to show post author.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'default'     => 'yes',
		];
		$fields['show_comment']       = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Show Post Comment', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to show post comment.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
		];
		$fields['show_reading_time']  = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Show Reading Time', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to show post reading time.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
		];
		$fields['show_post_views']    = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Show Post Views', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to show post post views.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
		];
		$fields['show_read_more_btn'] = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Show Read More Button', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to show post read more button.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'default'     => 'yes',
		];

		$fields['visibility_section_end'] = $obj->end_section();

		return apply_filters( 'rtsb/elements/elementor/posts_visibility_control', $fields, $obj );
	}
	/**
	 * Image section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function image( $obj ) {
		$condition                           = [
			'show_post_thumbnail' => 'yes',
		];
		$fields['image_section']             = $obj->start_section(
			esc_html__( 'Images', 'shopbuilder' ),
			'settings',
			[],
			$condition
		);
		$fields['image_hover_effect']        = [
			'type'        => 'select',
			'label'       => esc_html__( 'Image Hover Effect', 'shopbuilder' ),
			'options'     => self::rtsb_post_image_hover_effect(),
			'default'     => 'rtsb-gw-img-zoom-out',
			'label_block' => true,
		];
		$fields['image_size_note']           = $obj->el_heading(
			esc_html__( 'Image Size', 'shopbuilder' ),
			'before',
			[],
			[ 'show_post_thumbnail' => [ 'yes' ] ]
		);
		$fields['image_size']                = [
			'type'            => 'select2',
			'label'           => esc_html__( 'Select Image Size', 'shopbuilder' ),
			'description'     => esc_html__( 'Please select the image size.', 'shopbuilder' ),
			'options'         => Fns::get_image_sizes(),
			'default'         => 'full',
			'label_block'     => true,
			'content_classes' => 'elementor-descriptor',
		];
		$fields['img_dimension']             = [
			'type'        => 'image-dimensions',
			'label'       => esc_html__( 'Enter Custom Image Size', 'shopbuilder' ),
			'label_block' => true,
			'default'     => [
				'width'  => 400,
				'height' => 400,
			],
			'condition'   => [
				'image_size' => 'rtsb_custom',
			],
		];
		$fields['img_crop']                  = [
			'type'        => 'select2',
			'label'       => esc_html__( 'Image Crop', 'shopbuilder' ),
			'description' => esc_html__( 'Please click on "Apply" to update the image.', 'shopbuilder' ),
			'options'     => [
				'soft' => esc_html__( 'Soft Crop', 'shopbuilder' ),
				'hard' => esc_html__( 'Hard Crop', 'shopbuilder' ),
			],
			'default'     => 'hard',
			'condition'   => [
				'image_size' => 'rtsb_custom',
			],
		];
		$fields['img_custom_dimension_note'] = [
			'type'      => 'html',
			'raw'       => sprintf(
				'<span style="display: block; background: #fffbf1; padding: 10px; font-weight: 500; line-height: 1.4; color: #bd3a3a;border: 1px solid #bd3a3a;">%s</span>',
				esc_html__( 'Please note that, if you enter image size larger than the actual image itself, the image sizes will fallback to the full image dimension.', 'shopbuilder' )
			),
			'condition' => [
				'image_size' => 'rtsb_custom',
			],

		];
		$fields['image_section_end'] = $obj->end_section();

		return $fields;
	}
	/**
	 * Button section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function readmore_button( $obj ) {
		$condition                      = [
			'show_read_more_btn' => 'yes',
		];
		$fields['button_section']       = $obj->start_section(
			esc_html__( 'Read More Button', 'shopbuilder' ),
			'settings',
			[],
			$condition
		);
		$fields['button_icon']          = [
			'label'   => esc_html__( 'Button Icon', 'shopbuilder' ),
			'type'    => 'icons',
			'default' => [
				'value'   => 'fas fa-arrow-right',
				'library' => 'fa-solid',
			],
		];
		$fields['button_icon_position'] = [
			'mode'    => 'responsive',
			'type'    => 'choose',
			'label'   => esc_html__( 'Position', 'shopbuilder' ),
			'options' => [
				'left'  => [
					'title' => esc_html__( 'Left', 'shopbuilder' ),
					'icon'  => 'eicon-h-align-left',
				],
				'right' => [
					'title' => esc_html__( 'Right', 'shopbuilder' ),
					'icon'  => 'eicon-h-align-right',
				],

			],
			'toggle'  => true,
			'default' => 'right',
		];
		$fields['button_text']        = [
			'label'       => esc_html__( 'Button Text', 'shopbuilder' ),
			'description' => esc_html__( 'Please enter the button text.', 'shopbuilder' ),
			'type'        => 'text',
			'label_block' => true,
			'default'     => __( 'Read More', 'shopbuilder' ),
		];
		$fields['button_section_end'] = $obj->end_section();

		return $fields;
	}
	/**
	 * Title section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function post_title( $obj ) {
		$condition = [
			'show_title' => [ 'yes' ],
		];

		$fields['post_title_section'] = $obj->start_section(
			esc_html__( 'Title', 'shopbuilder' ),
			'settings',
			[],
			$condition
		);

		$fields['title_tag']   = [
			'type'        => 'select2',
			'label'       => esc_html__( 'Post Title Tag', 'shopbuilder' ),
			'options'     => ControlHelper::heading_tags(),
			'label_block' => true,
			'default'     => 'h3',
			'description' => esc_html__( 'Please select the post title tag.', 'shopbuilder' ),
		];
		$fields['title_limit'] = [
			'type'        => 'select2',
			'label'       => esc_html__( 'Post Title Limit', 'shopbuilder' ),
			'options'     => [
				'default' => esc_html__( 'Default', 'shopbuilder' ),
				'1-line'  => esc_html__( 'Show in 1 line', 'shopbuilder' ),
				'2-lines' => esc_html__( 'Show in 2 lines', 'shopbuilder' ),
				'3-lines' => esc_html__( 'Show in 3 lines', 'shopbuilder' ),
				'custom'  => esc_html__( 'Custom', 'shopbuilder' ),
			],
			'label_block' => true,
			'default'     => 'default',
			'description' => esc_html__( 'Please select the post title limit.', 'shopbuilder' ),
		];

		$fields['title_limit_custom'] = [
			'type'        => 'number',
			'label'       => esc_html__( 'Custom Character Limit', 'shopbuilder' ),
			'default'     => 200,
			'description' => esc_html__( 'Please enter the post title character limit.', 'shopbuilder' ),
			'condition'   => [ 'title_limit' => [ 'custom' ] ],
		];

		$fields['post_title_section_end'] = $obj->end_section();

		return $fields;
	}
	/**
	 * Excerpt section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function post_excerpt( $obj ) {
		$condition = [
			'show_short_desc' => [ 'yes' ],
		];

		$fields['post_excerpt_section'] = $obj->start_section(
			esc_html__( 'Excerpt', 'shopbuilder' ),
			'settings',
			[],
			$condition
		);

		$fields['excerpt_limit'] = [
			'type'        => 'select2',
			'label'       => esc_html__( 'Excerpt Limit', 'shopbuilder' ),
			'description' => esc_html__( 'Please select the post excerpt limit.', 'shopbuilder' ),
			'options'     => [
				'default' => esc_html__( 'Default', 'shopbuilder' ),
				'1-line'  => esc_html__( 'Show in 1 line', 'shopbuilder' ),
				'2-lines' => esc_html__( 'Show in 2 lines', 'shopbuilder' ),
				'3-lines' => esc_html__( 'Show in 3 lines', 'shopbuilder' ),
				'custom'  => esc_html__( 'Custom', 'shopbuilder' ),
			],
			'label_block' => true,
			'default'     => 'default',
		];

		$fields['excerpt_limit_custom'] = [
			'type'        => 'number',
			'label'       => esc_html__( 'Custom Character Limit', 'shopbuilder' ),
			'default'     => 200,
			'description' => esc_html__( 'Please enter the post excerpt character limit.', 'shopbuilder' ),
			'condition'   => [ 'excerpt_limit' => [ 'custom' ] ],
		];

		$fields['post_excerpt_section_end'] = $obj->end_section();

		return $fields;
	}
	/**
	 * Pagination section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function links( $obj ) {
		$fields['links_section'] = $obj->start_section(
			esc_html__( 'Links', 'shopbuilder' ),
			'settings'
		);
		$obj->start_section( 'links_section', esc_html__( 'Detail Page', 'shopbuilder' ), 'settings' );

		$fields['title_link'] = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Title Clickable?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to enable title linking.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'default'     => 'yes',
		];

		$fields['image_link'] = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Image Clickable?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to enable image linking.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'default'     => 'yes',
		];

		$fields['links_section_end'] = $obj->end_section();

		return $fields;
	}
	/**
	 * Pagination Style section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function post_pagination_style_settings( $obj ) {
		$fields = StyleFieldsFree::pagination( $obj );
		unset( $fields['pagination_buttons_active_color']['condition'] );
		unset( $fields['pagination_buttons_active_bg_color']['condition'] );
		unset( $fields['pagination_buttons_spacing']['condition'] );
		return $fields;
	}
	/**
	 * Builds an array with field values.
	 *
	 * @param array  $meta Field values.
	 * @param string $template Template name.
	 * @param array  $raw_settings Raw settings.
	 *
	 * @return array
	 */
	public static function post_meta_dataset( array $meta, $template = '', $raw_settings = [] ) {
		$data = apply_filters(
			'rtsb/elementor/render/post_meta_dataset',
			[
				// Layout.
				'widget'         => 'custom',
				'template'       => RenderHelpers::get_data( $template, '', '' ),
				'raw_settings'   => $raw_settings,
				'layout'         => RenderHelpers::get_data( $meta, 'layout', 'layout1' ),
				'd_cols'         => RenderHelpers::get_data( $meta, 'cols', 0 ),
				't_cols'         => RenderHelpers::get_data( $meta, 'cols_tablet', 2 ),
				'm_cols'         => RenderHelpers::get_data( $meta, 'cols_mobile', 1 ),

				// Query.
				'post_in'        => RenderHelpers::get_data( $meta, 'include_posts', [] ),
				'post_not_in'    => RenderHelpers::get_data( $meta, 'exclude_posts', [] ),
				'limit'          => ( empty( $meta['posts_limit'] ) || '-1' === $meta['posts_limit'] ) ? 10000000 : $meta['posts_limit'],
				'offset'         => RenderHelpers::get_data( $meta, 'posts_offset', 0 ),
				'order_by'       => RenderHelpers::get_data( $meta, 'posts_order_by', 'date' ),
				'order'          => RenderHelpers::get_data( $meta, 'posts_order', 'DESC' ),
				'categories'     => RenderHelpers::get_data( $meta, 'filter_categories', [] ),
				'tags'           => RenderHelpers::get_data( $meta, 'filter_tags', [] ),
				'relation'       => RenderHelpers::get_data( $meta, 'tax_relation', 'OR' ),
				// Pagination.
				'pagination'     => ! empty( $meta['show_pagination'] ),
				'posts_per_page' => RenderHelpers::get_data( $meta, 'pagination_per_page', '' ),

			]
		);
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
		return apply_filters( 'rtsb/elementor/render/post_meta_dataset_final', $data, $meta, $raw_settings );
	}
	/**
	 * Function to get post arg dataset.
	 *
	 * @param array $settings Settings array.
	 *
	 * @return array
	 */
	public static function get_post_arg_dataset( $settings ) {
		global $post;

		$title_limit_custom   = $settings['title_limit_custom'] ?? '';
		$excerpt_limit_custom = $settings['excerpt_limit_custom'] ?? '200';
		return [
			'title'    => Fns::text_truncation( get_the_title( $post->ID ), $title_limit_custom ),
			'excerpt'  => Fns::text_truncation( do_shortcode( get_post_field( 'post_excerpt', $post->ID ) ), $excerpt_limit_custom ),
			'img_html' => self::render_posts_thumbnail( $post->ID, $settings ),
		];
	}
	/**
	 * Get post thumbnail HTML
	 *
	 * @param int   $post_id Post ID.
	 * @param array $settings Settings array.
	 *
	 * @return string
	 */
	public static function render_posts_thumbnail( $post_id, $settings ) {
		$c_image_size         = RenderHelpers::get_data( $settings, 'img_dimension', [] );
		$c_image_size['crop'] = RenderHelpers::get_data( $settings, 'img_dimension', [] );
		$f_img_size           = RenderHelpers::get_data( $settings, 'image_size', 'medium' );
		$thumbnail_id         = get_post_thumbnail_id( $post_id );
		return $settings['show_post_thumbnail'] ? Fns::get_product_image_html(
			'',
			null,
			$f_img_size,
			$thumbnail_id,
			! empty( $c_image_size ) && is_array( $c_image_size ) ? $c_image_size : [],
			false
		) : null;
	}
	/**
	 * Generate the formatted HTML list of categories or tags for the current post.
	 *
	 * Retrieves either post categories or tags based on the provided type and
	 * returns them wrapped in a span element with a corresponding CSS class.
	 *
	 * @param string $type The taxonomy type to display. Accepts 'category' or 'tag'. Default 'category'.
	 *
	 * @return string HTML markup with the taxonomy list, or an empty string if none found.
	 */
	public static function rtsb_posted_taxonomy( $type = 'category' ) {
		$categories_list = get_the_category_list( self::rtsb_list_item_separator() );
		if ( 'tag' === $type ) {
			$categories_list = get_the_tag_list( '', self::rtsb_list_item_separator() );
		}
		if ( $categories_list ) {
			/* translators: %s: CSS class, %s: List of category or tag links. */
			return sprintf(
				'<span class="%s-links">%s</span>',
				$type,
				$categories_list
			);
		}

		return '';
	}
	/**
	 * Prints HTML with meta information for the current post-date/time.
	 *
	 * @return string
	 */
	public static function rtsb_posted_date() {
		$time_string = sprintf(
			'<time class="entry-date published updated" datetime="%1$s">%2$s</time>',
			esc_attr( get_the_date( DATE_W3C ) ),
			esc_html( get_the_date() )
		);
		return sprintf( '<span class="posted-on">%s</span>', $time_string );
	}
	/**
	 * Generate formatted HTML for the post author section.
	 *
	 * Outputs the author name (linked to their posts archive) optionally
	 * prefixed by a custom label (e.g., "Posted by"). Both the prefix and
	 * author name are wrapped in appropriate markup for styling.
	 *
	 * @param string $prefix Optional text to display before the author name. Default empty.
	 *
	 * @return string HTML markup containing the author information.
	 */
	public static function rtsb_posted_by( $prefix = '' ) {
		return sprintf(
			/* translators: %1$s: Optional prefix (e.g., "Posted by"), %2$s: Author name with a link. */
			esc_html__( '%1$s %2$s', 'shopbuilder' ),
			$prefix ? '<span class="prefix">' . $prefix . '</span>' : '',
			'<span class="byline"><a href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '" rel="author">' . esc_html( get_the_author() ) . '</a></span>'
		);
	}
	/**
	 * Retrieve the formatted comments count text for the current post.
	 *
	 * Returns a translated string showing the number of comments,
	 * automatically handling singular/plural forms.
	 *
	 * Example: "Comment: 1" or "Comments: 5".
	 *
	 * @return string Formatted comments count text.
	 */
	public static function get_post_comments_number() {
		$comments_number = get_comments_number();
		// translators: %s is for comments number.
		$comments = sprintf( _n( 'Comment: %s', 'Comments: %s', $comments_number, 'shopbuilder' ), number_format_i18n( $comments_number ) );
		return $comments;
	}
	/**
	 * Get the separator used between post taxonomy list items.
	 *
	 * Returns an HTML span element used as a separator between categories or tags.
	 * The output can be customized using the `rtsb_post_list_item_separator` filter.
	 *
	 * @return string HTML separator markup.
	 */
	public static function rtsb_list_item_separator() {
		$separator = sprintf(
			/* translators: Used between list items, there is a space after the comma. */
			"<span class='%s'>%s</span>",
			'sp',
			' '
		);

		return apply_filters( 'rtsb_post_list_item_separator', $separator );
	}
	/**
	 * Calculate and display the estimated reading time for the current post.
	 *
	 * Counts the words in the post content (after removing shortcodes and HTML tags)
	 * and estimates reading time based on 200 words per minute. Outputs a formatted
	 * HTML span with the reading duration (e.g., "1 min read", "5 mins read",
	 * "Less than a minute", or "2 hours read").
	 *
	 * @return string HTML-wrapped reading time text.
	 */
	public static function rtsb_reading_time() {
		$post_content = get_post()->post_content;
		$post_content = strip_shortcodes( $post_content );
		$post_content = wp_strip_all_tags( $post_content );
		$word_count   = str_word_count( $post_content );
		$reading_time = floor( $word_count / 200 );

		if ( $reading_time < 1 ) {
			$result = esc_html__( 'Less than a minute', 'shopbuilder' );
		} elseif ( $reading_time > 60 ) {
			/* translators: %s is reading time. */
			$result = sprintf( esc_html__( '%s hours read', 'shopbuilder' ), floor( $reading_time / 60 ) );
		} elseif ( 1 == $reading_time ) {
			$result = esc_html__( '1 min read', 'shopbuilder' );
		} else {
			$result = sprintf(
				/* translators: %s is reading time. */
				esc_html__( '%s mins read', 'shopbuilder' ),
				$reading_time
			);
		}

		return '<span class="meta-reading-time meta-item">' . $result . '</span> ';
	}
	/**
	 * Display formatted post view count with CSS status classes.
	 *
	 * Retrieves the post view count stored in the `rt_post_views` meta key,
	 * formats the number for display, and assigns a visual status class based
	 * on popularity (e.g., "rising", "high", "very-high").
	 *
	 * Outputs an HTML span containing the view number and label
	 * ("View" or "Views") along with an optional CSS class.
	 *
	 * - very-high: more than 1000 views
	 * - high: more than 100 views
	 * - rising: more than 5 views
	 *
	 * @param string $text     Optional custom text (currently unused).
	 * @param int    $post_id  Optional post ID. Defaults to current post.
	 *
	 * @return string HTML markup for the post view count.
	 */
	public static function rtsb_post_views( $text = '', $post_id = 0 ) {

		if ( empty( $post_id ) ) {
			$post_id = get_the_ID();
		}

		$views_class = '';
		$formated    = 0;
		$count_key   = 'rt_post_views';
		$view_count  = get_post_meta( $post_id, $count_key, true );
		if ( ! empty( $view_count ) ) {
			$formated = number_format_i18n( $view_count );

			if ( $view_count > 1000 ) {
				$views_class = 'very-high';
			} elseif ( $view_count > 100 ) {
				$views_class = 'high';
			} elseif ( $view_count > 5 ) {
				$views_class = 'rising';
			}
		} elseif ( '' == $view_count ) {
			$view_count = 0;
		} else {
			$view_count = 0;
		}

		if ( 1 == $view_count ) {
			$shopbuilder_view_html = esc_html__( 'View', 'shopbuilder' );
		} else {
			$shopbuilder_view_html = esc_html__( 'Views', 'shopbuilder' );
		}

		$shopbuilder_views_html = '<span class="view-number" >' . $view_count . '</span> ' . $shopbuilder_view_html;

		return '<span class="meta-views meta-item ' . $views_class . '">' . $shopbuilder_views_html . '</span> ';
	}
	/**
	 * Content Box style section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function post_list_item_style( $obj ) {

		$css_selectors = $obj->selectors['post_list_item_style'];
		$title         = esc_html__( 'Post Item', 'shopbuilder' );
		$selectors     = [
			'box_shadow'           => $css_selectors['box_shadow'],
			'hover_box_shadow'     => $css_selectors['hover_box_shadow'],
			'border'               => $css_selectors['border'],
			'bg_color'             => [ $css_selectors['bg_color'] => 'background-color: {{VALUE}};' ],
			'content_alignment'    => [ $css_selectors['content_alignment'] => 'align-items: {{VALUE}};' ],
			'content_hr_alignment' => [ $css_selectors['content_hr_alignment'] => 'text-align:{{VALUE}};justify-content: {{VALUE}};' ],
			'padding'              => [ $css_selectors['padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ],
			'border_radius'        => [ $css_selectors['border_radius'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ],
		];

		$fields = ControlHelper::general_elementor_style( 'post_list_item_style', $title, $obj, [], $selectors );

		unset(
			$fields['post_list_item_style_typo_note'],
			$fields['rtsb_el_post_list_item_style_typography'],
			$fields['post_list_item_style_color'],
			$fields['post_list_item_style_alignment'],
			$fields['post_list_item_style_color_tabs'],
			$fields['post_list_item_style_color_tab'],
			$fields['post_list_item_style_color_tab_end'],
			$fields['post_list_item_style_hover_color_tab'],
			$fields['post_list_item_style_hover_color'],
			$fields['post_list_item_style_hover_bg_color'],
			$fields['post_list_item_style_hover_color_tab_end'],
			$fields['post_list_item_style_color_tabs_end'],
			$fields['post_list_item_style_border_hover_color'],
			$fields['post_list_item_style_margin']
		);
		$fields['post_list_item_style_border_note']['separator'] = 'default';
		$fields['post_list_item_style_color_note']['separator']  = 'default';
		$extra_controls['post_list_item_style_border_radius']    = [
			'label'      => esc_html__( 'Border Radius', 'shopbuilder' ),
			'type'       => 'dimensions',
			'mode'       => 'responsive',
			'size_units' => [ 'px' ],
			'selectors'  => $selectors['border_radius'],
		];
		$fields = Fns::insert_controls( 'post_list_item_style_spacing_note', $fields, $extra_controls );

		$extra_controls2['rtsb_post_list_item_style_box_shadow']       = [
			'type'     => 'box-shadow',
			'mode'     => 'group',
			'label'    => esc_html__( 'Box Shadow', 'shopbuilder' ),
			'selector' => $selectors['box_shadow'],
		];
		$extra_controls2['rtsb_post_list_item_style_hover_box_shadow'] = [
			'type'      => 'box-shadow',
			'mode'      => 'group',
			'label'     => esc_html__( 'Hover Box Shadow', 'shopbuilder' ),
			'selector'  => $selectors['hover_box_shadow'],
			'condition' => [
				'layout_style' => [ 'rtsb-post-grid-layout1','rtsb-post-grid-layout2','rtsb-post-grid-layout3' ],
			],
		];
		$fields                                = Fns::insert_controls( 'post_list_item_style_border_note', $fields, $extra_controls2 );
		$extra_controls3['content_align_note'] = $obj->el_heading( esc_html__( 'Alignment', 'shopbuilder' ), 'default' );
		$extra_controls3['content_align_note']['condition'] = [
			'layout_style!' => [ 'rtsb-post-grid-layout1','rtsb-post-grid-layout2','rtsb-post-grid-layout3' ],
		];
		$extra_controls3['content_alignment_style']         = [
			'mode'      => 'responsive',
			'type'      => 'choose',
			'label'     => esc_html__( 'Vertical Content', 'shopbuilder' ),
			'options'   => [
				'start'  => [
					'title' => esc_html__( 'Start', 'shopbuilder' ),
					'icon'  => 'eicon-justify-start-v',
				],
				'center' => [
					'title' => esc_html__( 'Center', 'shopbuilder' ),
					'icon'  => 'eicon-justify-center-v',
				],
				'end'    => [
					'title' => esc_html__( 'End', 'shopbuilder' ),
					'icon'  => 'eicon-justify-end-v',
				],

			],
			'toggle'    => true,
			'selectors' => $selectors['content_alignment'] ,
			'default'   => 'start',
			'condition' => [
				'layout_style!' => [ 'rtsb-post-grid-layout1','rtsb-post-grid-layout2','rtsb-post-grid-layout3' ],
			],
		];
		$extra_controls3['content_hr_alignment_style'] = [
			'mode'      => 'responsive',
			'type'      => 'choose',
			'label'     => esc_html__( 'Horizontal Content', 'shopbuilder' ),
			'options'   => [
				'start'  => [
					'title' => esc_html__( 'Start', 'shopbuilder' ),
					'icon'  => 'eicon-justify-start-h',
				],
				'center' => [
					'title' => esc_html__( 'Center', 'shopbuilder' ),
					'icon'  => 'eicon-justify-center-h',
				],
				'end'    => [
					'title' => esc_html__( 'End', 'shopbuilder' ),
					'icon'  => 'eicon-justify-end-h',
				],

			],
			'toggle'    => true,
			'selectors' => $selectors['content_hr_alignment'] ,
			'default'   => 'start',
		];
		$fields = Fns::insert_controls( 'post_list_item_style_color_note', $fields, $extra_controls3 );
		return $fields;
	}
	/**
	 * Title style section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function title_style( $obj ) {
		$condition     = [
			'show_title' => 'yes',
		];
		$css_selectors = $obj->selectors['title_style'];
		$title         = esc_html__( 'Title', 'shopbuilder' );
		$selectors     = [
			'typography'  => $css_selectors['typography'],
			'color'       => [ $css_selectors['color'] => 'color: {{VALUE}};' ],
			'hover_color' => [ $css_selectors['hover_color'] => 'color: {{VALUE}};' ],
			'margin'      => [ $css_selectors['margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;' ],
		];

		$fields = ControlHelper::general_elementor_style( 'title_style', $title, $obj, $condition, $selectors );
		unset(
			$fields['title_style_alignment'],
			$fields['rtsb_el_title_style_border'],
			$fields['title_style_border_note'],
			$fields['title_style_bg_color'],
			$fields['title_style_hover_bg_color'],
			$fields['title_style_border_hover_color'],
			$fields['title_style_padding'],
		);
		return $fields;
	}
	/**
	 * Content style section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function content_style( $obj ) {
		$condition     = [
			'show_short_desc' => 'yes',
		];
		$css_selectors = $obj->selectors['content_style'];
		$title         = esc_html__( 'Content', 'shopbuilder' );
		$selectors     = [
			'typography' => $css_selectors['typography'],
			'color'      => [ $css_selectors['color'] => 'color: {{VALUE}};' ],
			'margin'     => [ $css_selectors['margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;' ],
			'padding'    => [ $css_selectors['padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;' ],
		];

		$fields                                   = ControlHelper::general_elementor_style( 'content_style', $title, $obj, $condition, $selectors );
		$fields['content_style_padding']['label'] = esc_html__( 'Wrapper Padding', 'shopbuilder' );
		unset(
			$fields['content_style_alignment'],
			$fields['rtsb_el_content_style_border'],
			$fields['content_style_border_note'],
			$fields['content_style_color_tabs'],
			$fields['content_style_color_tab'],
			$fields['content_style_color_tab_end'],
			$fields['content_style_hover_color_tab'],
			$fields['content_style_bg_color'],
			$fields['content_style_hover_color'],
			$fields['content_style_hover_bg_color'],
			$fields['content_style_border_hover_color'],
			$fields['content_style_hover_color_tab_end'],
			$fields['content_style_color_tabs_end'],
		);
		return $fields;
	}

	/**
	 * Image style section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function image_styles( $obj ) {
		$condition         = [
			'show_post_thumbnail' => 'yes',
		];
		$alignment_options = ControlHelper::alignment();
		unset( $alignment_options['justify'] );
		$fields['image_style_sec_start']       = $obj->start_section(
			esc_html__( 'Image', 'shopbuilder' ),
			'style',
			[],
			$condition
		);
		$fields['image_styles_dimension_note'] = $obj->el_heading( esc_html__( 'Dimension', 'shopbuilder' ) );
		$fields['image_styles_width']          = [
			'type'       => 'slider',
			'mode'       => 'responsive',
			'label'      => esc_html__( 'Image Width', 'shopbuilder' ),
			'size_units' => [ '%', 'px' ],
			'range'      => [
				'%'  => [
					'min'  => 0,
					'max'  => 100,
					'step' => 1,
				],
				'px' => [
					'min'  => 0,
					'max'  => 600,
					'step' => 1,
				],
			],
			'selectors'  => [
				$obj->selectors['image_style']['width'] => 'width: {{SIZE}}{{UNIT}};flex: 0 0 {{SIZE}}{{UNIT}};',
			],
		];
		$fields['image_styles_max_width']      = [
			'type'       => 'slider',
			'mode'       => 'responsive',
			'label'      => esc_html__( 'Image Max-Width', 'shopbuilder' ),
			'size_units' => [ '%', 'px' ],
			'range'      => [
				'%'  => [
					'min'  => 0,
					'max'  => 100,
					'step' => 1,
				],
				'px' => [
					'min'  => 0,
					'max'  => 600,
					'step' => 1,
				],

			],
			'selectors'  => [
				$obj->selectors['image_style']['max_width'] => 'max-width: {{SIZE}}{{UNIT}};',
			],
		];
		$fields['image_border_note']         = $obj->el_heading( esc_html__( 'Border', 'shopbuilder' ), 'before' );
		$fields['image_border']              = [
			'mode'           => 'group',
			'type'           => 'border',
			'label'          => esc_html__( 'Border', 'shopbuilder' ),
			'selector'       => $obj->selectors['image_style']['border'],
			'fields_options' => [
				'color' => [
					'label' => esc_html__( 'Border Color', 'shopbuilder' ),
				],
			],
			'separator'      => 'default',
		];
		$fields['image_styles_radius']       = [
			'mode'       => 'responsive',
			'type'       => 'dimensions',
			'label'      => esc_html__( 'Border Radius', 'shopbuilder' ),
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				$obj->selectors['image_style']['border_radius'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		];
		$fields['image_styles_spacing_note'] = $obj->el_heading( esc_html__( 'Spacing', 'shopbuilder' ) );
		$fields['image_styles_margin']       = [
			'mode'       => 'responsive',
			'type'       => 'dimensions',
			'label'      => esc_html__( 'Margin', 'shopbuilder' ),
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				$obj->selectors['image_style']['margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		];
		$fields['image_sec_end']             = $obj->end_section();

		return $fields;
	}
	/**
	 * Button style section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function button_style( $obj ) {
		$condition     = [
			'show_read_more_btn' => 'yes',
		];
		$css_selectors = $obj->selectors['readmore_button_style'];
		$title         = esc_html__( 'Read More Button', 'shopbuilder' );
		$selectors     = [
			'typography'         => $css_selectors['typography'],
			'icon_size'          => [
				$css_selectors['icon_size']['font_size'] => 'font-size: {{SIZE}}{{UNIT}};',
				$css_selectors['icon_size']['svg']       => 'height: {{SIZE}}{{UNIT}};width: {{SIZE}}{{UNIT}};',
			],
			'icon_gap'           => [ $css_selectors['icon_gap'] => 'gap: {{SIZE}}{{UNIT}};' ],
			'btn_width'          => [ $css_selectors['btn_width'] => 'min-width: {{SIZE}}{{UNIT}};' ],
			'btn_height'         => [ $css_selectors['btn_height'] => 'height: {{SIZE}}{{UNIT}};' ],
			'color'              => [ $css_selectors['color'] => 'color: {{VALUE}};' ],
			'bg_color'           => [ $css_selectors['bg_color'] => 'background-color: {{VALUE}};' ],
			'hover_color'        => [ $css_selectors['hover_color'] => 'color: {{VALUE}};' ],
			'hover_bg_color'     => [ $css_selectors['hover_bg_color'] => 'background-color: {{VALUE}};' ],
			'border'             => $css_selectors['border'],
			'box_shadow'         => $css_selectors['box_shadow'],
			'border_hover_color' => [ $css_selectors['border_hover_color'] => 'border-color: {{VALUE}};' ],
			'padding'            => [ $css_selectors['padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ],
			'margin'             => [ $css_selectors['margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ],
			'border_radius'      => [ $css_selectors['border_radius'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ],
		];

		$fields = ControlHelper::general_elementor_style( 'readmore_button_style', $title, $obj, $condition, $selectors );

		// Redirect the text-decoration sub-field to `.text-wrap` (an unshielded span that wraps the label); the shield-blocked `<a>` can't reach text inside its inline-flex child.
		$fields['rtsb_el_readmore_button_style_typography']['fields_options'] = [
			'text_decoration' => [
				'selectors' => [
					$css_selectors['text_decoration'] => 'text-decoration: {{VALUE}};',
				],
			],
		];

		unset(
			$fields['readmore_button_style_alignment'],
		);
		$extra_controls['readmore_button_style_border_radius'] = [
			'label'      => esc_html__( 'Border Radius', 'shopbuilder' ),
			'type'       => 'dimensions',
			'mode'       => 'responsive',
			'size_units' => [ 'px' ],
			'selectors'  => $selectors['border_radius'],
		];
		$fields = Fns::insert_controls( 'readmore_button_style_spacing_note', $fields, $extra_controls );
		$extra_controls2['readmore_button_style_btn_width']  = [
			'type'       => 'slider',
			'mode'       => 'responsive',
			'label'      => esc_html__( 'Button Width', 'shopbuilder' ),
			'size_units' => [ 'px','%' ],
			'range'      => [
				'px' => [
					'min' => 10,
					'max' => 1000,
				],
				'%'  => [
					'min' => 0,
					'max' => 100,
				],
			],
			'selectors'  => $selectors['btn_width'],
		];
		$extra_controls2['readmore_button_style_btn_height'] = [
			'type'       => 'slider',
			'mode'       => 'responsive',
			'label'      => esc_html__( 'Button Height', 'shopbuilder' ),
			'size_units' => [ 'px','%' ],
			'range'      => [
				'px' => [
					'min' => 10,
					'max' => 1000,
				],
				'%'  => [
					'min' => 0,
					'max' => 100,
				],
			],
			'selectors'  => $selectors['btn_height'],
		];

		$extra_controls2['readmore_button_style_icon_size']  = [
			'type'       => 'slider',
			'mode'       => 'responsive',
			'label'      => esc_html__( 'Icon Size', 'shopbuilder' ),
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min' => 0,
					'max' => 100,
				],
			],
			'selectors'  => $selectors['icon_size'],
		];
		$extra_controls2['readmore_button_style_icon_gap']   = [
			'type'       => 'slider',
			'mode'       => 'responsive',
			'label'      => esc_html__( 'Icon Gap', 'shopbuilder' ),
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min' => 0,
					'max' => 100,
				],
			],
			'selectors'  => $selectors['icon_gap'],
		];
		$extra_controls2['readmore_button_style_box_shadow'] = [
			'type'     => 'box-shadow',
			'mode'     => 'group',
			'label'    => esc_html__( 'Box Shadow', 'shopbuilder' ),
			'selector' => $selectors['box_shadow'],
		];
		$fields = Fns::insert_controls( 'readmore_button_style_spacing_note', $fields, $extra_controls2, true );

		return $fields;
	}
	/**
	 * Meta style section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function meta_style( $obj ) {
		$conditions = [
			'relation' => 'or',
			'terms'    => [
				[
					'name'     => 'show_dates',
					'operator' => '==',
					'value'    => 'yes',
				],
				[
					'name'     => 'show_author',
					'operator' => '==',
					'value'    => 'yes',
				],
				[
					'name'     => 'show_comment',
					'operator' => '==',
					'value'    => 'yes',
				],
				[
					'name'     => 'show_reading_time',
					'operator' => '==',
					'value'    => 'yes',
				],
				[
					'name'     => 'show_post_views',
					'operator' => '==',
					'value'    => 'yes',
				],

			],
		];
		$css_selectors = $obj->selectors['post_meta_style'];
		$title         = esc_html__( 'Meta', 'shopbuilder' );
		$selectors     = [
			'typography'           => $css_selectors['typography'],
			'color'                => [ $css_selectors['color'] => 'color: {{VALUE}};' ],
			'meta_link_color'      => [ $css_selectors['meta_link_color'] => 'color: {{VALUE}};' ],
			'meta_separator_color' => [ $css_selectors['meta_separator_color'] => 'color: {{VALUE}};' ],
			'meta_icon_color'      => [ $css_selectors['meta_icon_color'] => 'color: {{VALUE}};' ],
			'hover_color'          => [ $css_selectors['hover_color'] => 'color: {{VALUE}};' ],
			'meta_gap'             => [ $css_selectors['meta_gap'] => 'gap: {{SIZE}}{{UNIT}};' ],
			'margin'               => [ $css_selectors['margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;' ],
		];

		$fields = ControlHelper::general_elementor_style( 'post_meta_style', $title, $obj, [], $selectors, $conditions );
		$fields['post_meta_style_hover_color']['label'] = esc_html__( 'Link Hover Color', 'shopbuilder' );
		unset(
			$fields['post_meta_style_alignment'],
			$fields['rtsb_el_post_meta_style_border'],
			$fields['post_meta_style_border_note'],
			$fields['post_meta_style_bg_color'],
			$fields['post_meta_style_hover_bg_color'],
			$fields['post_meta_style_border_hover_color'],
			$fields['post_meta_style_padding'],
		);
		$extra_controls['post_meta_link_color']      = [
			'type'      => 'color',
			'label'     => esc_html__( 'Link Color', 'shopbuilder' ),
			'selectors' => $selectors['meta_link_color'],
		];
		$extra_controls['post_meta_icon_color']      = [
			'type'      => 'color',
			'label'     => esc_html__( 'Icon Color', 'shopbuilder' ),
			'selectors' => $selectors['meta_icon_color'],
		];
		$extra_controls['post_meta_separator_color'] = [
			'type'      => 'color',
			'label'     => esc_html__( 'Separator Color', 'shopbuilder' ),
			'selectors' => $selectors['meta_separator_color'],
		];
		$fields                                      = Fns::insert_controls( 'post_meta_style_color', $fields, $extra_controls, true );
		$extra_controls2['post_meta_gap']            = [
			'type'       => 'slider',
			'mode'       => 'responsive',
			'label'      => esc_html__( 'Meta Gap', 'shopbuilder' ),
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min' => 0,
					'max' => 100,
				],
			],
			'selectors'  => $selectors['meta_gap'],
		];
		$fields                                      = Fns::insert_controls( 'post_meta_style_spacing_note', $fields, $extra_controls2, true );
		return $fields;
	}
	/**
	 * Meta style section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function meta_icon_settings( $obj ) {
		$conditions = [
			'relation' => 'or',
			'terms'    => [
				[
					'name'     => 'show_dates',
					'operator' => '==',
					'value'    => 'yes',
				],
				[
					'name'     => 'show_author',
					'operator' => '==',
					'value'    => 'yes',
				],
				[
					'name'     => 'show_comment',
					'operator' => '==',
					'value'    => 'yes',
				],
				[
					'name'     => 'show_reading_time',
					'operator' => '==',
					'value'    => 'yes',
				],
				[
					'name'     => 'show_post_views',
					'operator' => '==',
					'value'    => 'yes',
				],

			],
		];
		$fields['meta_icon_settings']          = $obj->start_section(
			esc_html__( 'Meta', 'shopbuilder' ),
			'settings',
			$conditions,
		);
		$fields['meta_separator']              = [
			'type'  => 'text',
			'label' => esc_html__( 'Meta Separator', 'shopbuilder' ),
		];
		$fields['author_icon_note']            = $obj->el_heading(
			esc_html__( 'Author Icon', 'shopbuilder' ),
			'default',
			[],
			[ 'show_author' => [ 'yes' ] ]
		);
		$fields['show_author_icon']            = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Enable Icon?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to enable pagination.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'condition'   => [
				'show_author' => [ 'yes' ],
			],
		];
		$fields['author_icon']                 = [
			'label'     => esc_html__( 'Icon', 'shopbuilder' ),
			'type'      => 'icons',
			'default'   => [
				'value'   => 'far fa-user',
				'library' => 'fa-solid',
			],
			'condition' => [
				'show_author'      => [ 'yes' ],
				'show_author_icon' => [ 'yes' ],
			],
		];
		$fields['post_date_icon_note']         = $obj->el_heading(
			esc_html__( 'Date Icon', 'shopbuilder' ),
			'default',
			[],
			[ 'show_dates' => [ 'yes' ] ]
		);
		$fields['show_date_icon']              = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Enable Icon?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to enable pagination.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'default'     => 'yes',
			'condition'   => [
				'show_dates' => [ 'yes' ] ,
			],
		];
		$fields['date_icon']                   = [
			'label'     => esc_html__( 'Icon', 'shopbuilder' ),
			'type'      => 'icons',
			'default'   => [
				'value'   => 'fas fa-calendar-alt',
				'library' => 'fa-solid',
			],
			'condition' => [
				'show_dates'     => [ 'yes' ],
				'show_date_icon' => [ 'yes' ],
			],
		];
		$fields['post_comment_icon_note']      = $obj->el_heading(
			esc_html__( 'Comment Icon', 'shopbuilder' ),
			'default',
			[],
			[ 'show_comment' => [ 'yes' ] ]
		);
		$fields['show_comment_icon']           = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Enable Icon?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to enable pagination.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'condition'   => [
				'show_comment' => [ 'yes' ] ,
			],
		];
		$fields['comment_icon']                = [
			'label'     => esc_html__( 'Icon', 'shopbuilder' ),
			'type'      => 'icons',
			'default'   => [
				'value'   => 'far fa-comment',
				'library' => 'fa-solid',
			],
			'condition' => [
				'show_comment'      => [ 'yes' ],
				'show_comment_icon' => [ 'yes' ],
			],
		];
		$fields['post_reading_time_icon_note'] = $obj->el_heading(
			esc_html__( 'Reading Time Icon', 'shopbuilder' ),
			'default',
			[],
			[ 'show_reading_time' => [ 'yes' ] ]
		);
		$fields['show_reading_time_icon']      = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Enable Icon?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to enable pagination.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'condition'   => [
				'show_reading_time' => [ 'yes' ] ,
			],
		];
		$fields['reading_time_icon']           = [
			'label'     => esc_html__( 'Icon', 'shopbuilder' ),
			'type'      => 'icons',
			'default'   => [
				'value'   => 'far fa-clock',
				'library' => 'fa-solid',
			],
			'condition' => [
				'show_reading_time'      => [ 'yes' ],
				'show_reading_time_icon' => [ 'yes' ],
			],
		];
		$fields['post_views_icon_note']        = $obj->el_heading(
			esc_html__( 'Post Views Icon', 'shopbuilder' ),
			'default',
			[],
			[ 'show_post_views' => [ 'yes' ] ]
		);
		$fields['show_post_views_icon']        = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Enable Icon?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to enable pagination.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'condition'   => [
				'show_post_views' => [ 'yes' ] ,
			],
		];
		$fields['post_views_icon']             = [
			'label'     => esc_html__( 'Icon', 'shopbuilder' ),
			'type'      => 'icons',
			'default'   => [
				'value'   => 'far fa-eye',
				'library' => 'fa-solid',
			],
			'condition' => [
				'show_post_views'      => [ 'yes' ],
				'show_post_views_icon' => [ 'yes' ],
			],
		];

		$fields['meta_icon_settings_end'] = $obj->end_section();
		return $fields;
	}
	/**
	 * Taxonomy style section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function taxonomy_style( $obj ) {
		$conditions    = [
			'relation' => 'or',
			'terms'    => [
				[
					'name'     => 'show_categories',
					'operator' => '==',
					'value'    => 'yes',
				],
				[
					'name'     => 'show_tags',
					'operator' => '==',
					'value'    => 'yes',
				],
			],
		];
		$css_selectors = $obj->selectors['post_taxonomy_style'];
		$title         = esc_html__( 'Category/Tag', 'shopbuilder' );
		$selectors     = [
			'typography'         => $css_selectors['typography'],
			'border'             => $css_selectors['border'],
			'border_hover_color' => [ $css_selectors['border_hover_color'] => 'border-color: {{VALUE}};' ],
			'color'              => [ $css_selectors['color'] => 'color: {{VALUE}};' ],
			'bg_color'           => [ $css_selectors['bg_color'] => 'background-color: {{VALUE}};' ],
			'hover_color'        => [ $css_selectors['hover_color'] => 'color: {{VALUE}};' ],
			'hover_bg_color'     => [ $css_selectors['hover_bg_color'] => 'background-color: {{VALUE}};' ],
			'padding'            => [ $css_selectors['padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ],
			'margin'             => [ $css_selectors['margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;' ],
			'border_radius'      => [ $css_selectors['border_radius'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ],
			'taxonomy_gap'       => [ $css_selectors['taxonomy_gap'] => 'gap: {{SIZE}}{{UNIT}};' ],

		];

		$fields = ControlHelper::general_elementor_style( 'post_taxonomy_style', $title, $obj, [], $selectors, $conditions );

		// Force text-decoration with !important to beat the `.rtsb-elementor-container a { text-decoration: none !important; }` framework shield (see _el-global.scss).
		$fields['rtsb_el_post_taxonomy_style_typography']['fields_options'] = [
			'text_decoration' => [
				'selectors' => [
					$css_selectors['typography'] => 'text-decoration: {{VALUE}} !important;',
				],
			],
		];

		unset(
			$fields['post_taxonomy_style_alignment'],
		);
		$extra_controls['taxonomy_border_radius'] = [
			'label'      => esc_html__( 'Border Radius', 'shopbuilder' ),
			'type'       => 'dimensions',
			'mode'       => 'responsive',
			'size_units' => [ 'px' ],
			'selectors'  => $selectors['border_radius'],
		];
		$fields                                   = Fns::insert_controls( 'post_taxonomy_style', $fields, $extra_controls );
		$extra_controls2['post_taxonomy_gap']     = [
			'type'       => 'slider',
			'mode'       => 'responsive',
			'label'      => esc_html__( 'Meta Gap', 'shopbuilder' ),
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min' => 0,
					'max' => 100,
				],
			],
			'selectors'  => $selectors['taxonomy_gap'],
		];
		$fields                                   = Fns::insert_controls( 'post_taxonomy_style_spacing_note', $fields, $extra_controls2, true );
		return $fields;
	}
	/**
	 * Get the list of available image hover effects for post widgets.
	 *
	 * Returns an associative array of hover effect CSS class names mapped
	 * to their corresponding human-readable labels. The list can be modified
	 * by third-party developers through the
	 * `rtsb/general/widget/post_widget_image_hover_effect` filter.
	 *
	 * Available effects:
	 * - None
	 * - Scale In
	 * - Scale Out
	 * - Slide Up
	 * - Slide Down
	 * - Slide Right
	 * - Slide Left
	 *
	 * @return array List of image hover effect options (class => label).
	 */
	public static function rtsb_post_image_hover_effect() {
		return apply_filters(
			'rtsb/general/widget/post_widget_image_hover_effect',
			[
				'rtsb-gw-img-effect-none' => __( 'None', 'shopbuilder' ),
				'rtsb-gw-img-zoom-in'     => __( 'Scale In', 'shopbuilder' ),
				'rtsb-gw-img-zoom-out'    => __( 'Scale Out', 'shopbuilder' ),
				'rtsb-gw-img-slide-up'    => __( 'Slide Up', 'shopbuilder' ),
				'rtsb-gw-img-slide-down'  => __( 'Slide Down', 'shopbuilder' ),
				'rtsb-gw-img-slide-right' => __( 'Slide Right', 'shopbuilder' ),
				'rtsb-gw-img-slide-left'  => __( 'Slide Left', 'shopbuilder' ),
			]
		);
	}
	/**
	 * Get CSS selectors used for styling pagination elements in Elementor widgets.
	 *
	 * Returns an associative array mapping style control keys (e.g., typography,
	 * alignment, colors, borders) to the corresponding CSS selectors used within
	 * the widget. These selectors are primarily applied when rendering pagination
	 * for posts, archives, and "load more" buttons inside the ShopBuilder Elementor
	 * container.
	 *
	 * This helps ensure all pagination-related style controls are centralized and
	 * consistent across widgets.
	 *
	 * @return array List of pagination style selectors keyed by control name.
	 */
	public static function post_pagination_selectors() {
		return [
			'typography'          => '{{WRAPPER}} .rtsb-elementor-container .rtsb-pagination ul.pagination-list li a, {{WRAPPER}} .rtsb-elementor-container .rtsb-pagination ul.pagination-list li span, {{WRAPPER}} .rtsb-elementor-container .rtsb-pagination-wrap .rtsb-load-more button,  {{WRAPPER}} .rtsb-elementor-container .rtsb-archive-pagination-wrap .rtsb-load-more button',
			'alignment'           => '{{WRAPPER}} .rtsb-elementor-container .rtsb-pagination ul.pagination-list, {{WRAPPER}} .rtsb-elementor-container .rtsb-pagination-wrap, {{WRAPPER}} .rtsb-elementor-container .rtsb-archive-pagination-wrap',
			'width'               => '{{WRAPPER}} .rtsb-elementor-container .rtsb-pagination ul.pagination-list li a, {{WRAPPER}} .rtsb-elementor-container .rtsb-pagination ul.pagination-list li span, {{WRAPPER}} .rtsb-elementor-container .rtsb-pagination-wrap .rtsb-load-more button, {{WRAPPER}} .rtsb-elementor-container .rtsb-archive-pagination-wrap .rtsb-load-more button',
			'height'              => '{{WRAPPER}} .rtsb-elementor-container .rtsb-pagination ul.pagination-list li a, {{WRAPPER}} .rtsb-elementor-container .rtsb-pagination ul.pagination-list li span, {{WRAPPER}} .rtsb-elementor-container .rtsb-pagination-wrap .rtsb-load-more button, {{WRAPPER}} .rtsb-elementor-container .rtsb-archive-pagination-wrap .rtsb-load-more button',
			'color'               => '{{WRAPPER}} .rtsb-elementor-container .rtsb-pagination ul.pagination-list li a, {{WRAPPER}} .rtsb-elementor-container .rtsb-pagination-wrap .rtsb-load-more button, {{WRAPPER}} .rtsb-elementor-container .rtsb-archive-pagination-wrap .rtsb-load-more button',
			'bg_color'            => '{{WRAPPER}} .rtsb-elementor-container .rtsb-pagination ul.pagination-list li a, {{WRAPPER}} .rtsb-elementor-container .rtsb-pagination-wrap .rtsb-load-more button, {{WRAPPER}} .rtsb-elementor-container .rtsb-archive-pagination-wrap .rtsb-load-more button',
			'active_color'        => '{{WRAPPER}} .rtsb-elementor-container .rtsb-pagination ul.pagination-list li span',
			'active_bg_color'     => '{{WRAPPER}} .rtsb-elementor-container .rtsb-pagination ul.pagination-list li span',
			'hover_color'         => '{{WRAPPER}} .rtsb-elementor-container .rtsb-pagination ul.pagination-list li a:hover, {{WRAPPER}} .rtsb-elementor-container .rtsb-pagination-wrap .rtsb-load-more button:hover, {{WRAPPER}} .rtsb-elementor-container .rtsb-archive-pagination-wrap .rtsb-load-more button:hover',
			'hover_bg_color'      => '{{WRAPPER}} .rtsb-elementor-container .rtsb-pagination ul.pagination-list li a:hover, {{WRAPPER}} .rtsb-elementor-container .rtsb-pagination-wrap .rtsb-load-more button:hover, {{WRAPPER}} .rtsb-elementor-container .rtsb-archive-pagination-wrap .rtsb-load-more button:hover',
			'border'              => '{{WRAPPER}} .rtsb-elementor-container .rtsb-pagination ul.pagination-list li a, {{WRAPPER}} .rtsb-elementor-container .rtsb-pagination ul.pagination-list li span, {{WRAPPER}} .rtsb-elementor-container .rtsb-pagination-wrap .rtsb-load-more button, {{WRAPPER}} .rtsb-elementor-container .rtsb-archive-pagination-wrap .rtsb-load-more button',
			'border_hover_color'  => '{{WRAPPER}} .rtsb-elementor-container .rtsb-pagination ul.pagination-list li a:hover, {{WRAPPER}} .rtsb-elementor-container .rtsb-pagination-wrap .rtsb-load-more button:hover, {{WRAPPER}} .rtsb-elementor-container .rtsb-archive-pagination-wrap .rtsb-load-more button:hover',
			'border_active_color' => '{{WRAPPER}} .rtsb-elementor-container .rtsb-pagination ul.pagination-list li span',
			'border_radius'       => '{{WRAPPER}} .rtsb-elementor-container .rtsb-pagination ul.pagination-list li a, {{WRAPPER}} .rtsb-elementor-container .rtsb-pagination ul.pagination-list li span, {{WRAPPER}} .rtsb-elementor-container .rtsb-pagination-wrap .rtsb-load-more button, {{WRAPPER}} .rtsb-elementor-container .rtsb-archive-pagination-wrap .rtsb-load-more button',
			'spacing'             => '{{WRAPPER}} .rtsb-elementor-container .rtsb-pagination ul.pagination-list, {{WRAPPER}} .rtsb-elementor-container .rtsb-pagination-wrap, {{WRAPPER}} .rtsb-elementor-container .rtsb-archive-pagination-wrap',
			'padding'             => '{{WRAPPER}} .rtsb-elementor-container .rtsb-pagination-wrap .rtsb-load-more button, {{WRAPPER}} .rtsb-elementor-container .rtsb-archive-pagination-wrap .rtsb-load-more button',
			'margin'              => '{{WRAPPER}} .rtsb-elementor-container .rtsb-pagination ul.pagination-list, {{WRAPPER}} .rtsb-elementor-container .rtsb-pagination-wrap, {{WRAPPER}} .rtsb-elementor-container .rtsb-archive-pagination-wrap',
		];
	}
}
