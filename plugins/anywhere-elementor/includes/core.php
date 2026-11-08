<?php

if ( ! function_exists( 'aepro_is_pro_active' ) ) {
	function aepro_is_pro_active() {
		return (bool) apply_filters( 'aepro/is_pro_active', false );
	}
}

if ( ! function_exists( 'aepro_get_locked_render_modes' ) ) {
	function aepro_get_locked_render_modes() {
		if ( aepro_is_pro_active() ) {
			return [];
		}

		return apply_filters(
			'aepro/locked_render_modes',
			[
				'archive_template',
				'post_type_archive_template',
				'block_layout',
				'404',
				'search',
				'author_template',
				'date_template',
				'acf_repeater_layout',
			]
		);
	}
}

if ( ! function_exists( 'aepro_get_upgrade_url' ) ) {
	function aepro_get_upgrade_url() {
		if ( function_exists( 'wpv_ae' ) ) {
			return wpv_ae()->get_upgrade_url();
		}

		return apply_filters( 'aepro/upgrade_url', 'https://www.wpbuilderaddons.com/' );
	}
}

if ( ! function_exists( 'aepro_enqueue_post_css' ) ) {
	/**
	 * Enqueue the Elementor-generated CSS file for a template / inner layout.
	 *
	 * Widgets that render a nested Elementor template (Post Blocks, Post Blocks
	 * Adv skins, etc.) call this during widget render — which also runs on
	 * `wp_ajax_*` requests for pagination / load-more / filter bars. In an AJAX
	 * (or REST) request `wp_enqueue_scripts` never fires, so Elementor never
	 * registers the `elementor-frontend` style handle and WP emits a
	 * "dependencies that are not registered: elementor-frontend" notice from
	 * Elementor's own Post CSS enqueue. Register Elementor's frontend styles
	 * first so the dependency resolves. On a normal frontend page the handle is
	 * already registered, so this is a no-op there.
	 *
	 * @param int|string $post_id Template / layout post ID whose CSS to enqueue.
	 */
	function aepro_enqueue_post_css( $post_id ) {
		if ( empty( $post_id ) || ! class_exists( '\Elementor\Core\Files\CSS\Post' ) ) {
			return;
		}

		static $frontend_styles_registered = false;

		if (
			! $frontend_styles_registered
			&& ! wp_style_is( 'elementor-frontend', 'registered' )
			&& isset( \Elementor\Plugin::$instance->frontend )
			&& method_exists( \Elementor\Plugin::$instance->frontend, 'register_styles' )
		) {
			\Elementor\Plugin::$instance->frontend->register_styles();
			$frontend_styles_registered = true;
		}

		\Elementor\Core\Files\CSS\Post::create( $post_id )->enqueue();
	}
}

// Composer autoload is used for modules and classes. See composer.json.
