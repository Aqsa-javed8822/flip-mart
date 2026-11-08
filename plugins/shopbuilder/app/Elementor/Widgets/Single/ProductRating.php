<?php
/**
 * Main ProductDescription class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\Single;

use Elementor\Controls_Manager;
use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Abstracts\ElementorWidgetBase;
use RadiusTheme\SB\Elementor\Helper\ControlHelper;
use RadiusTheme\SB\Elementor\Widgets\Controls\ReviewsStarSettings;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Product Description class
 */
class ProductRating extends ElementorWidgetBase {
	/**
	 * Construct function
	 *
	 * @param array $data default array.
	 * @param mixed $args default arg.
	 */
	public function __construct( $data = [], $args = null ) {
		$this->rtsb_name = esc_html__( 'Product Rating', 'shopbuilder' );
		$this->rtsb_base = 'rtsb-product-rating';
		$this->pro_tab   = 'style';
		parent::__construct( $data, $args );
	}
	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public function widget_fields() {
		$alignment = ControlHelper::alignment();
		unset( $alignment['justify'] );
		$fields = [
			'sec_general'                  => [
				'mode'  => 'section_start',
				'label' => esc_html__( 'General', 'shopbuilder' ),
				'tab'   => 'style',
			],
			'rating_align'                 => [
				'mode'      => 'responsive',
				'label'     => esc_html__( 'Alignment', 'shopbuilder' ),
				'type'      => 'choose',
				'options'   => $alignment,
				'separator' => 'default',
				'selectors' => [
					$this->selectors['rating_align'] => 'text-align: {{VALUE}};',
				],
			],
			'rating_text_link_color'       => [
				'label'     => esc_html__( 'Link Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					$this->selectors['rating_text_link_color'] => 'color: {{VALUE}} !important;',
				],
			],
			'rating_text_link_hover_color' => [
				'label'     => esc_html__( 'Link Hover Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					$this->selectors['rating_text_link_hover_color'] => 'color: {{VALUE}} !important;',
				],
			],
			'link_typography'              => [
				'mode'     => 'group',
				'type'     => 'typography',
				'label'    => esc_html__( 'Link Typography', 'shopbuilder' ),
				'selector' => $this->selectors['link_typography'],
			],
			'rating_space_between'         => [
				'label'      => esc_html__( 'Right Spacing (px)', 'shopbuilder' ),
				'type'       => 'slider',
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 5,
				],
				'selectors'  => [
					$this->selectors['rating_space_between']['margin-right'] => 'margin-right: {{SIZE}}{{UNIT}} !important;',
					$this->selectors['rating_space_between']['margin-left'] => 'margin-left: {{SIZE}}{{UNIT}} !important;',
				],
			],
			'sec_general_end'              => [
				'mode' => 'section_end',
			],
		];
		return array_merge( $fields, ReviewsStarSettings::widget_fields( $this ) );
	}

	/**
	 * Render Function
	 *
	 * @return void
	 */
	protected function render() {
		global $post, $product;
		$_product    = $product;
		$_post       = $post;
		$controllers = $this->get_settings_for_display();
		$this->theme_support();
		$product = Fns::get_product();

		if ( $this->is_builder_mode() ) {
			// Overriding Global.
			$post = get_post( Fns::get_prepared_product_id() ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		}

		ob_start();
			woocommerce_template_single_rating();
		$rating_html = ob_get_clean();

		if ( empty( $rating_html ) && $this->is_builder_mode() ) {
			$rating_html = $this->rtsb_name;
		}

		$data = [
			'template'    => 'elementor/single-product/rating',
			'controllers' => $controllers,
			'content'     => $rating_html,
		];

		Fns::load_template( $data['template'], $data );
		$this->theme_support( 'render_reset' );
		$product = $_product; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		$post    = $_post;
	}
}
