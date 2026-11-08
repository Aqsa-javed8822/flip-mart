<?php

namespace Aepro;

use Elementor\Plugin;
use Aepro\Frontend;

class Shortcode {

	private static $_instance = null;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	private function __construct() {

		add_shortcode( 'INSERT_ELEMENTOR', [ $this, 'render_shortcode' ] );
		add_shortcode( 'dynific_template', [ $this, 'render_shortcode' ] );

		add_filter( 'widget_text', 'do_shortcode' );
	}

	public function render_shortcode( $atts ) {
		if ( ! isset( $atts['id'] ) || empty( $atts['id'] ) ) {
			return '';
		}

		$template_id = (int) $atts['id'];
		if ( is_null( get_post( $template_id ) ) ) {
			return '';
		}

		$context_post_id = $this->get_context_post_id( $atts );
		if ( $context_post_id <= 0 ) {
			return $this->render_template( $template_id );
		}

		return $this->render_template_with_post_context( $template_id, $context_post_id );
	}

	private function render_template( $template_id ) {
		return Frontend::instance()->render_insert_elementor( $template_id );
	}

	private function get_context_post_id( $atts ) {
		$post_id = 0;
		if ( isset( $atts['post-id'] ) ) {
			$post_id = absint( $atts['post-id'] );
		} elseif ( isset( $atts['post_id'] ) ) {
			$post_id = absint( $atts['post_id'] );
		}

		if ( $post_id <= 0 || is_null( get_post( $post_id ) ) ) {
			return 0;
		}

		return $post_id;
	}

	private function render_template_with_post_context( $template_id, $context_post_id ) {
		global $post;

		$previous_post = isset( $post ) ? $post : null;
		$previous_loop_post_id = Frontend::$_ae_post_block;
		$context_post = get_post( $context_post_id );

		if ( ! $context_post ) {
			return $this->render_template( $template_id );
		}

		Frontend::$_ae_post_block = $context_post_id;
		$post = $context_post;
		setup_postdata( $context_post );

		$output = $this->render_template( $template_id );

		Frontend::$_ae_post_block = $previous_loop_post_id;
		if ( $previous_post instanceof \WP_Post ) {
			$post = $previous_post;
			setup_postdata( $previous_post );
		} else {
			wp_reset_postdata();
		}

		return $output;
	}
}
