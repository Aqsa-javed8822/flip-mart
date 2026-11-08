<?php

namespace Aepro\Base;

use Aepro\Aepro;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Icons_Manager;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Utils;

abstract class Widget_Base extends \Elementor\Widget_Base {

	public function is_enabled() {

		return true;
	}

	public function get_custom_help_url() {
		return Aepro::$_helper->get_help_url_prefix() . $this->get_name();
	}

	public function is_debug_on() {

		if ( \Aepro\Aepro::$_widget_debug ) {
			echo '<div class="ae-widget-debug ' . esc_html( $this->get_name() ) . '">' . esc_html( $this->get_title() ) . '</div>';
		}

		return \Aepro\Aepro::$_widget_debug;
	}

	protected function get_access_level() {
		return $this->_access_level;
	}

	public function is_editable() {
		return ( \AePro\Plugin::$_level >= $this->get_access_level() );
	}

	public function get_widget_title_controls() {

		$this->start_controls_section(
			'section_widget_title',
			[
				'label' => __( 'Widget Title', 'ae-pro' ),
			]
		);

		$this->add_control(
			'widget_title',
			[
				'label'       => esc_html__( 'Title', 'ae-pro' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'dynamic'     => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'Enter your title', 'ae-pro' ),
			]
		);

		$this->add_control(
			'widget_title_size',
			[
				'label'   => esc_html__( 'HTML Tag', 'ae-pro' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'div',
					'span' => 'span',
					'p'    => 'p',
				],
				'default' => 'h2',
			]
		);

		$this->add_responsive_control(
			'widget_title_align',
			[
				'label'     => esc_html__( 'Alignment', 'ae-pro' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left' => [
						'title' => esc_html__( 'Left', 'ae-pro' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'ae-pro' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'ae-pro' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default'   => 'center',
				'selectors' => [
					'{{WRAPPER}} .ae-widget-title-wrap' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	public function get_widget_title_style_controls() {
		$this->start_controls_section(
			'section_widget_title_style',
			[
				'label' => __( 'Widget Title', 'ae-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'widget_title_width',
			[
				'label'          => __( 'Width', 'ae-pro' ),
				'type'           => Controls_Manager::SLIDER,
				'range'          => [
					'%' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'default'        => [
					'unit' => '%',
				],
				'tablet_default' => [
					'unit' => '%',
				],
				'mobile_default' => [
					'unit' => '%',
				],
				'size_units'     => [ '%', 'px', 'vw' ],
				'selectors'      => [
					'{{WRAPPER}} .ae-widget-title-inner' => 'width : {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'widget_title_color',
			[
				'label'     => esc_html__( 'Text Color', 'ae-pro' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .ae-widget-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'           => 'widget_title_typography',
				'global'         => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'fields_options' => [
					'font_size' => [
						'default' => [
							'unit' => 'px',
							'size' => '25',
						],
					],
				],
				'selector'       => '{{WRAPPER}} .ae-widget-title',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name'     => 'widget_title_text_shadow',
				'selector' => '{{WRAPPER}} .ae-widget-title',
			]
		);

		$this->add_control(
			'widget_title_blend_mode',
			[
				'label'     => esc_html__( 'Blend Mode', 'ae-pro' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					''            => esc_html__( 'Normal', 'ae-pro' ),
					'multiply'    => 'Multiply',
					'screen'      => 'Screen',
					'overlay'     => 'Overlay',
					'darken'      => 'Darken',
					'lighten'     => 'Lighten',
					'color-dodge' => 'Color Dodge',
					'saturation'  => 'Saturation',
					'color'       => 'Color',
					'difference'  => 'Difference',
					'exclusion'   => 'Exclusion',
					'hue'         => 'Hue',
					'luminosity'  => 'Luminosity',
				],
				'selectors' => [
					'{{WRAPPER}} .ae-widget-title' => 'mix-blend-mode: {{VALUE}}',
				],
				'separator' => 'none',
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'widget_title_background',
				'label'    => __( 'Item Background', 'ae-pro' ),
				'types'    => [ 'none', 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .ae-widget-title-inner',
				'default'  => '#fff',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'           => 'widget_title_border',
				'label'          => __( 'Border', 'ae-pro' ),
				'fields_options' => [
					'border' => [
						'default' => 'solid',
					],
					'width'  => [
						'default' => [
							'top'    => 0,
							'right'  => 0,
							'bottom' => 1,
							'left'   => 0,
							'unit'   => 'px',
						],
					],
					'color'  => [
						'global' => [
							'default' => Global_Colors::COLOR_SECONDARY,
						],
					],
				],
				'selector'       => '{{WRAPPER}} .ae-widget-title-inner',
			]
		);

		$this->add_control(
			'widget_title_border_radius',
			[
				'label'      => __( 'Border Radius', 'ae-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .ae-widget-title-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow:hidden;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'widget_title_box_shadow',
				'label'    => __( 'Box Shadow', 'ae-pro' ),
				'selector' => '{{WRAPPER}} .ae-widget-title-inner',
			]
		);

		$this->add_responsive_control(
			'fwidget_title_padding',
			[
				'label'      => __( 'Padding', 'ae-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'default'    => [
					'top'    => '0',
					'right'  => '10',
					'bottom' => '5',
					'left'   => '10',
					'unit'   => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} .ae-widget-title-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'widget_title_margin',
			[
				'label'      => __( 'Margin', 'ae-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'default'    => [
					'top'    => '0',
					'right'  => '0',
					'bottom' => '10',
					'left'   => '0',
					'unit'   => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} .ae-widget-title-inner' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	public function get_widget_title_html() {
		$widget = $this;

		$settings = $this->get_settings_for_display();

		if ( '' === $settings['widget_title'] ) {
			return;
		}

		$widget->add_render_attribute( 'widget_title', 'class', 'ae-widget-title' );

		$title = $settings['widget_title'];

		$title_html = sprintf( '<%1$s %2$s>%3$s</%1$s>', Utils::validate_html_tag( $settings['widget_title_size'] ), $this->get_render_attribute_string( 'widget_title' ), $title );

		echo '<div class="ae-widget-title-wrap"><div class="ae-widget-title-inner">' . $title_html . '</div></div>';
	}
}
