<?php

namespace Aepro;

use Aepro\Shortcode;
use Aepro\Admin\MetaBoxes;
use Aepro\Admin\PostType;
use Aepro\Admin\Promotion_Widgets;
use Aepro\Classes\CacheManager;

class Plugin {


	private static $_instance = null;

	public static $_level = 0;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	private function __construct() {

		// iniate cache
		CacheManager::instance();

		if ( self::$_level < 1 ) {
			self::$_level = 1;
		}

		$this->includes();
		add_action( 'elementor/init', [ $this, 'add_elementor_support' ] );
		add_action( 'after_setup_theme', [ $this, 'get_ae_contants' ] );

		require_once AE_PRO_PATH . 'includes/aepro.php';
		
		Promotion_Widgets::instance();

		//add_action( 'wp_enqueue_scripts', [ $this, 'ae_editor_enqueue_scripts' ] );
	}

	private function includes() {
		PostType::instance();
		MetaBoxes::instance();
		Shortcode::instance();
	}

	public function add_elementor_support() {

		add_post_type_support( 'ae_global_templates', 'elementor' );

		\Elementor\Plugin::instance()->elements_manager->add_category(
			'ae-template-elements',
			[
				'title' => 'DA Template Elements',
				'icon'  => 'fa fa-plug',
			],
			1
		);
	}

	public function ae_editor_enqueue_scripts() {
		wp_enqueue_script( 'ae_editor_js', AE_PRO_URL . 'includes/assets/js/ae-editor' . AE_PRO_SCRIPT_SUFFIX . '.js', [], AE_PRO_VERSION, true );
	}

	public function get_ae_contants() {
		/**
		 * Define ACF Constants
		 *
		 */
		if ( defined( '\ACF_PRO' ) && \ACF_PRO ) {
			define( 'AE_ACF', true );
			define( 'AE_ACF_PRO', true );
		} elseif ( defined( '\ACF' ) && \ACF ) {
			define( 'AE_ACF', true );
			define( 'AE_ACF_PRO', false );
		} else {
			define( 'AE_ACF', false );
			define( 'AE_ACF_PRO', false );
		}

		/**
		 * Define Pods Constants
		 *
		 */

		if ( is_plugin_active( 'pods/init.php' ) ) {
			define( 'AE_PODS', true );
		} else {
			define( 'AE_PODS', false );
		}

		/**
		 * Define Polylang Constants
		 *
		 */

		if ( class_exists( 'Polylang' ) ) {
			define( 'AE_POLYLANG', true );
		} else {
			define( 'AE_POLYLANG', false );
		}

		/** Define WooCommerce Constants
		 *
		 *
		 */

		if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			define( 'AE_WOO', true );
		} else {
			define( 'AE_WOO', false );
		}

		/**
		 * Define SEO Plugin Constants
		 *
		 */

		if ( is_plugin_active( 'wordpress-seo/wp-seo.php' ) ) {
			define( 'AE_YOAST_SEO', true );
		} else {
			define( 'AE_YOAST_SEO', false );
		}
		if ( function_exists( 'rank_math_the_breadcrumbs' ) ) {
			define( 'AE_RANK_MATH', true );
		} else {
			define( 'AE_RANK_MATH', false );
		}
	}

	public static function show_acf( $pro = false ) {
		$show_acf = false;
		if ( \Aepro\Plugin::$_level >= 2 ) {
			if ( $pro ) {
				$show_acf = AE_ACF_PRO;
			} else {
				$show_acf = AE_ACF;
			}
		}
		return $show_acf;
	}
}

Plugin::instance();
