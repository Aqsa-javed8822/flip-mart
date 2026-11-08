<?php

namespace Aepro\Classes;

use Elementor\Controls_Manager;
use Aepro\Modules\AeDynamic;
use Aepro\Modules\AcfDynamic;
use Aepro\Modules\PostDynamic;
use Aepro\Modules\WooDynamic;
use Aepro\Modules\AcfGroupDynamic;
use Aepro\Modules\AcfFlexibleDynamic;
use Aepro\Modules\AcfGroupDynamic\AcfGroupDynamicHelper;

class ModuleManager {


	const TAB_AE_PRO = 'tab_ae_pro';

	private $modules = [];

	public function __construct() {

		add_filter( 'elementor/init', [ $this, 'add_ae_tab' ], 10, 1 );

		add_action( 'wp_ajax_aep_module', [ $this, 'save_modules' ] );

		add_action( 'wp_ajax_aep_save_config', [ $this, 'save_config' ] );

		add_action( 'elementor/elements/categories_registered', [ $this, 'register_category' ], -999 );

		// Register Dynamic Tags
		/* add_action( 'elementor/dynamic_tags/register', [ $this, 'register_dynamic_tags' ] ); */
	}


	public function register_category() {
		\Elementor\Plugin::instance()->elements_manager->add_category(
			'ae-template-elements',
			[
				'title' => 'DA Template Elements',
				'icon'  => 'fa fa-plug',
			],
			1
		);
	}

	public function init_modules() {
		// Test Work
		$this->modules = [];

		$this->modules['core'] = [
			'label'   => __( 'General', 'ae-pro' ),
			'modules' => [

					'author' => [
						'label'   => __( 'Post Author', 'ae-pro' ),
						'type'    => 'widget',
						'enabled' => true,
					],

					'post-comments' => [
						'label'   => __( 'Post Comments', 'ae-pro' ),
						'type'    => 'widget',
						'enabled' => true,
					],

					'post-content' => [
						'label'   => __( 'Post Content', 'ae-pro' ),
						'type'    => 'widget',
						'enabled' => true,
					],

					'post-title' => [
						'label'   => __( 'Post Title', 'ae-pro' ),
						'type'    => 'widget',
						'enabled' => true,
					],

					'post-image' => [
						'label'   => __( 'Post Image', 'ae-pro' ),
						'type'    => 'widget',
						'enabled' => true,
					],

					'post-meta' => [
						'label'   => __( 'Post Meta', 'ae-pro' ),
						'type'    => 'widget',
						'enabled' => true,
					],

					'post-navigation' => [
						'label'   => __( 'Post Navigation', 'ae-pro' ),
						'type'    => 'widget',
						'enabled' => true,
					],


				],
			];

		//echo '<pre>'; print_r( $this->modules ); echo '</per>'; die;

		$this->modules = apply_filters( 'aepro/modules', $this->modules );

		$saved_modules = get_option( 'aep_modules' );

		if ( $saved_modules !== false ) {
			foreach ( $this->modules as $group => $modules ) {

				foreach ( $modules['modules'] as $modulekey => $moduleName ) {

					if ( isset( $saved_modules[ $modulekey ] ) ) {
						$this->modules[ $group ]['modules'][ $modulekey ]['enabled'] = $saved_modules[ $modulekey ];
					} else {
						$this->modules[ $group ]['modules'][ $modulekey ]['enabled'] = true;
					}
				}
			}
		}

		$this->modules = apply_filters( 'wts_aep_active_modules', $this->modules );
	}

	public function get_modules() {
		return $this->modules;
	}

	public function elementor_widget_registered() {
		$modules = $this->modules;
		if ( isset( $modules['core']['modules']['query-control'] ) ) {
			$modules['core']['modules']['query-control']['enabled'] = 1;
		}

		$enabled_keys = [];
		foreach ( $modules as $group ) {

			if ( is_array( $group['modules'] ) && count( $group['modules'] ) ) {

				foreach ( $group['modules'] as $key => $value ) {

					if ( $value['enabled'] ) {
						$enabled_keys[] = $key;
					}
				}
			}
		}

		foreach ( $enabled_keys as $key ) {
			$class_name = str_replace( '-', ' ', $key );
			$class_name = str_replace( ' ', '', ucwords( $class_name ) );
			$class_name = 'Aepro\Modules\\' . $class_name . '\Module';
			if ( class_exists( $class_name ) ) {
				$class_name::instance();
			}
		}
	}

	public function add_ae_tab() {
		$this->init_modules();
		$this->elementor_widget_registered();
		Controls_Manager::add_tab( self::TAB_AE_PRO, __( 'DA PRO', 'ae-pro' ) );
	}

	public function save_modules() {
		//phpcs:ignore WordPress.Security.NonceVerification.Missing
		$module_data = $_POST['moduleData'];

		// get saved modules
		$saved_modules = get_option( 'aep_modules' );

		foreach ( $module_data as $key => $action ) {

			if ( $action === 'deactivate' ) {
				$saved_modules[ $key ] = false;
			} else {
				$saved_modules[ $key ] = true;
			}
		}

		update_option( 'aep_modules', $saved_modules );

		wp_send_json(
			[
				'modules' => $saved_modules,
			]
		);
	}

	public function save_config() {
		check_ajax_referer( 'aep_ajax_nonce', 'nonce' );

		$gmap_api = sanitize_text_field( $_POST['config']['ae_pro_gmap_api'] );
		$template_debug = '';
		if ( isset( $_POST['config']['ae_pro_template_debug'] ) ) {
			$template_debug = sanitize_text_field( $_POST['config']['ae_pro_template_debug'] ) === '1' ? '1' : '';
		}

		update_option( 'ae_pro_gmap_api', trim( $gmap_api ) );
		update_option( 'ae_pro_template_debug', $template_debug );

		wp_send_json(
			[
				'success' => 1,
			]
		);
	}
}
