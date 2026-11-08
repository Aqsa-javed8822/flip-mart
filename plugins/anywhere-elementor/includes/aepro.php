<?php

namespace Aepro;

use Aepro\Classes\ModuleManager;
use Aepro\Controls\Query;
use Aepro\Upgrade\Manager as UpgradeManager;

use function class_exists;
use Elementor;
use Elementor\Plugin;


class Aepro
{


    private static $_instance = null;

    public $_hook_positions = [];

	public $upgrade = null;

    public static $_helper = null;

    public static $module_manager = null;

    public static $_theme = null;

    public static $_widget_debug = false;

    /**
     * @var array Themes that are fully supported in core 
     */
    protected $supported_themes = [ 'generatepress', 'oceanwp', 'astra', 'hestia', 'twentyseventeen', 'wpbf', 'page-builder-framework' ];

    public static function instance()
    {
        if (is_null(self::$_instance) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function init()
    {
		load_plugin_textdomain('ae-pro', false, AE_PRO_FILE . 'includes/languages/ae-pro');
        add_filter('widget_text', 'do_shortcode');

        /**
         *   Widget Debug Mode
         **/
        self::$_widget_debug = apply_filters('aepro/widget_debug', self::$_widget_debug);
		$this->load_hook_positions();
		$this->ae_includes();

        self::$_helper = new Helper();
    }

    /**
     * Plugin constructor.
     */
    private function __construct()
    {
        add_action('init', [ $this, 'init' ]);
        add_action('plugins_loaded', [ $this, 'ae_plugins_loaded' ], 11);
        add_action('after_setup_theme', [ $this, 'ae_after_setup_theme' ]);
        add_action('elementor/init', [ $this, 'elementor_loaded' ]);

        // for frontend scripts & styles
        add_action('wp_enqueue_scripts', [ $this, 'ae_enqueue_scripts' ]);

        // elementor editor scripts & styles
        add_action('elementor/editor/wp_head', [ $this, 'ae_editor_enqueue_scripts' ]);

        // for admin scripts & styles
        add_action('admin_enqueue_scripts', [ $this, 'ae_admin_enqueue_scripts' ]);

        add_action('elementor/widgets/register', [ $this, 'elementor_widget_registered' ]);
        add_action('elementor/controls/controls_registered', [ $this, 'register_controls' ]);

        add_filter('manage_ae_global_templates_posts_columns', [ $this, 'set_custom_edit_ae_global_templates_posts_columns' ]);
        add_action('manage_ae_global_templates_posts_custom_column', [ $this, 'add_ae_global_templates_columns' ], 10, 2);
        add_filter('ae_pro_filter_hook_positions', [ $this, 'theme_hooks' ]);

        // woo template hook
        add_filter('wc_get_template_part', [ $this, 'load_wc_layout' ], 10, 3);

        // woo scripts setup
        add_action('template_redirect', [ $this, 'ae_woo_setup' ]);

        add_action('after_setup_theme', [ $this, 'editor_woo_scripts' ]);

        // TODO:: Do this only if product page is using AE Template
        add_filter('woocommerce_enqueue_styles', [ $this, 'load_wc_styles' ], 99, 1);

        add_action('woocommerce_init', [ $this, 'woo_init' ]);
        add_filter('acf/format_value', [ $this, 'normalize_acf_null_text_values' ], 20, 3);

        $map_key = get_option('ae_pro_gmap_api');
        if ($map_key ) {
            add_filter('acf/fields/google_map/api', [ $this, 'register_acf_map_key' ]);

            add_action('acf/init', [ $this, 'register_acf_pro_map_key' ]);
        }
        add_filter('template_redirect', [ $this, 'block_template_frontend' ]);

        add_filter('template_include', [ $this, 'ae_template_canvas' ]);

        add_filter('plugin_row_meta', [ $this, 'plugin_row_meta' ], 10, 2);

        add_action('elementor/editor/after_enqueue_styles', [ $this, 'editor_after_enqueue_styles' ]);
    }

    public function editor_after_enqueue_styles()
    {
        wp_add_inline_style('elementor-editor-dark-mode', '.elementor-panel .elementor-tab-control-tab_ae_pro a:before{ background-color:#E0E1E3 !important; color:#E0E1E3 !important; }');
    }

    public function ae_template_canvas( $template )
    {
        if (is_singular('ae_global_templates') ) {
            $helper = new Helper();

            if ($helper->is_canvas_enabled(get_the_ID()) ) {
                $template = ELEMENTOR_PATH . '/modules/page-templates/templates/canvas.php';
                return $template;
            }

            if ($helper->is_heder_footer_enabled(get_the_ID()) ) {
                $template = ELEMENTOR_PATH . '/modules/page-templates/templates/header-footer.php';
                return $template;
            }
        }

        return $template;
    }

    public function register_acf_pro_map_key()
    {
        $map_key = get_option('ae_pro_gmap_api');
        acf_update_setting('google_api_key', $map_key);
    }

    public function register_acf_map_key( $api )
    {
        $map_key = get_option('ae_pro_gmap_api');

        $api['key'] = $map_key;

        return $api;
    }

    public function normalize_acf_null_text_values( $value, $post_id, $field )
    {
        if ( null !== $value || ! is_array( $field ) || ! isset( $field['type'] ) ) {
            return $value;
        }

        $supported_types = [
            'text',
            'textarea',
            'number',
            'email',
            'password',
            'wysiwyg',
            'select',
            'checkbox',
            'radio',
            'true_false',
            'oembed',
            'google_map',
            'date_picker',
            'time_picker',
            'date_time_picker',
            'color_picker',
        ];

        if ( in_array( $field['type'], $supported_types, true ) ) {
            return '';
        }

        return $value;
    }

    public function get_script_suffix()
    {
        $suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';

        return $suffix;
    }

    public function woo_init()
    {
     // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        if (is_product() || isset($_REQUEST['ae_global_templates']) ) {
            \WC_Frontend_Scripts::load_scripts();
            wp_enqueue_script('wc-single-product');
            wp_enqueue_script('wc-product-gallery-zoom');
            wp_enqueue_script('flexslider');
            wp_enqueue_script('photoswipe-ui-default');
            wp_enqueue_style('photoswipe-default-skin');
            add_action('wp_footer', 'woocommerce_photoswipe');
        }

     // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        if (isset($_REQUEST['ae_global_templates']) ) {
            add_theme_support('wc-product-gallery-zoom');
            add_theme_support('wc-product-gallery-lightbox');
            add_theme_support('wc-product-gallery-slider');
        }
    }

    public function load_wc_styles( $styles )
    {
        return $styles;
    }

    public function ae_editor_enqueue_scripts()
    {

        wp_enqueue_script('aepro-editor-js', AE_PRO_URL . 'includes/assets/js/editor' . AE_PRO_SCRIPT_SUFFIX . '.js', [ 'jquery' ], AE_PRO_VERSION, true);

        wp_localize_script(
            'aepro-editor-js',
            'aepro',
            [
            'ajaxurl'    => admin_url('admin-ajax.php'),
            'plugin_url' => plugins_url('anywhere-elementor-pro'),
            'aep_nonce'  => wp_create_nonce('aep_ajax_nonce'),
            ]
        );

        // Vegas Slider Scripts for Background Slider
        wp_enqueue_style('vegas-css', AE_PRO_URL . 'includes/assets/lib/vegas/vegas' . AE_PRO_SCRIPT_SUFFIX . '.css', [], '2.4.0');
        wp_enqueue_script('vegas', AE_PRO_URL . 'includes/assets/lib/vegas/vegas' . AE_PRO_SCRIPT_SUFFIX . '.js', [ 'jquery' ], '2.4.0', true);

        wp_register_script('ae-elementor-editor-js-build', AE_PRO_URL . 'build/index' . AE_PRO_SCRIPT_SUFFIX . '.js', [ 'jquery', 'ae-gmap' ], AE_PRO_VERSION, false);

        $localize_data = [
        'plugin_url' => plugins_url('anywhere-elementor-pro'),
        ];
        wp_localize_script('ae-elementor-editor-js', 'aepro_editor', $localize_data);
        wp_localize_script('ae-elementor-editor-js-build', 'aepro_editor', $localize_data);

        wp_enqueue_script('ae-elementor-editor-js-build');

        // Swiper Slider Script - Enqueued from Elementor
        wp_enqueue_script('swiper');

        $map_key = get_option('ae_pro_gmap_api');
        if ($map_key ) {
            wp_enqueue_script('ae-gmap', 'https://maps.googleapis.com/maps/api/js?loading=async&libraries=marker&key=' . $map_key, [], '1', false);
            wp_enqueue_script('ae-gmap-cluster', 'https://unpkg.com/@googlemaps/markerclusterer/dist/index.min.js', [ 'ae-gmap' ], '1', false);
        }

        wp_enqueue_script('ae-masonry', AE_PRO_URL . 'includes/assets/lib/masonry/js/masonry.pkgd' . AE_PRO_SCRIPT_SUFFIX . '.js', [ 'jquery', 'jquery-masonry' ], '2.0.1', true);

        wp_enqueue_style('aep-editor', AE_PRO_URL . 'includes/assets/css/aep-editor.css', [], AE_PRO_VERSION);

        wp_enqueue_style('wpv-fonts', AE_PRO_URL . 'includes/assets/lib/wpv-icons/style.css', [], AE_PRO_VERSION);

		wp_enqueue_style('wpv-widget-fonts', AE_PRO_URL . 'includes/assets/lib/wpv-icons/widget/style.css', [], AE_PRO_VERSION);
    }


    public function theme_hooks( $hook_positions )
    {
        global $ae_template;
        $theme_class = '\Aepro\Themes\\' . $ae_template . '\\Ae_Theme';

        if (class_exists($theme_class) ) {
            $theme_obj      = new $theme_class();
            $hook_positions = $theme_obj->theme_hooks($hook_positions);
        }
        return $hook_positions;
    }




    public function set_custom_edit_ae_global_templates_posts_columns( $columns )
    {
        $columns['ae_global_template_column'] = __('Is Global', 'ae-pro');
        $columns['ae_render_mode_column']     = __('Render Mode', 'ae-pro');
        return $columns;
    }
    public function add_ae_global_templates_columns( $column, $post_id )
    {

        switch ( $column ) {

        case 'ae_global_template_column':
            $is_global = get_post_meta($post_id, 'ae_apply_global', true);
            if (! empty($is_global) ) {
                 echo '<span class="dashicons dashicons-star-filled" style="color:#ffd71c;"></span>';
            }
            break;

        case 'ae_render_mode_column':
            $helper      = new Helper();
            $render_mode = get_post_meta($post_id, 'ae_render_mode', true);
            $field_type  = get_post_meta($post_id, 'ae_acf_field_type', true);
            if (! empty($render_mode) ) {
                $render_modes = $helper->get_ae_render_mode_hook();

                if (isset($render_modes[ $render_mode ]) ) {
                    if ($render_mode === 'acf_repeater_layout' ) {
                        if ($field_type === 'repeater' || $field_type === '' ) {
                            echo 'ACF Blocks (Repeater)';
                        } else {
                            echo 'ACF Blocks (Flexible Content)';
                        }
                    } else {
                                echo esc_html($render_modes[ $render_mode ]);
                    }
                } else {
                    echo '<span style="color:#ff6033">' . $render_mode . '</span>';
                }
            }
            break;
        }
    }

    public function ae_plugins_loaded()
    {

        //load_plugin_textdomain('ae-pro', false, AE_PRO_FILE . 'includes/languages/ae-pro');

        if (! did_action('elementor/loaded') ) {
            /* TO DO */
            add_action('admin_notices', [ $this, 'ae_pro_fail_load' ]);
            return;
        }

        $elementor_version_required = '3.0';
        // Check for required Elementor version
        if (! version_compare(ELEMENTOR_VERSION, $elementor_version_required, '>=') ) {
            add_action('admin_notices', [ $this, 'ae_elementor_requried_version_fail' ]);
            return;
        }

        self::$module_manager = new ModuleManager();
    }

    public function ae_after_setup_theme()
    {

        // WPML Compatibility
        if (is_plugin_active('sitepress-multilingual-cms/sitepress.php') && is_plugin_active('wpml-string-translation/plugin.php') ) {
            include_once AE_PRO_PATH . 'includes/wpml/wpml-compatibility.php';
        }

		
    }

    private function ae_includes()
    {
        global $ae_template;

        $enable_generic = get_option('ae_pro_generic_theme');

        $enable_generic = 1;
     //phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison
        if (! in_array($ae_template, $this->supported_themes, true) && $enable_generic == 1 ) {
            $ae_template = 'generic';
        }

        if (file_exists(AE_PRO_PATH . 'includes/themes/' . $ae_template . '/Ae_Theme.php') ) {
            include_once AE_PRO_PATH . 'includes/themes/' . $ae_template . '/Ae_Theme.php';

            $theme_class  = '\Aepro\Themes\\' . $this->dashes_to_camel_case($ae_template) . '\Ae_Theme';
            self::$_theme = new $theme_class();
        } else {
            add_action(
                'after_setup_theme',
                function () {
                    do_action('ae_external_theme_support');
                }
            );
        }

        // Todo :: load only one frontend
        include_once AE_PRO_PATH . 'includes/frontend.php';
        include_once AE_PRO_PATH . 'includes/template.php';

        include_once AE_PRO_PATH . 'includes/post_helper.php';
        include_once AE_PRO_PATH . 'includes/rules.php';

        include_once AE_PRO_PATH . 'includes/helper.php';
        //require_once AE_PRO_PATH . 'includes/post-type.php';

        if (is_admin() ) {
            include_once AE_PRO_PATH . 'includes/admin/template-settings-schema.php';
            include_once AE_PRO_PATH . 'includes/admin/admin.php';
            include_once AE_PRO_PATH . 'includes/admin/admin-helper.php';
            include_once AE_PRO_PATH . 'includes/admin/template-config.php';
			include_once AE_PRO_PATH . 'includes/admin/template-debug.php';
        }

        include_once AE_PRO_PATH . 'includes/license-manager.php';
    }

    public function licence_activated()
    {
        return true;
    }

    public function ae_enqueue_scripts()
    {
        global $wp;

        wp_enqueue_style('ae-pro-css', AE_PRO_URL . 'includes/assets/css/ae-pro' . AE_PRO_SCRIPT_SUFFIX . '.css', [], AE_PRO_VERSION);
        wp_enqueue_script('ae-pro-js', AE_PRO_URL . 'includes/assets/js/ae-pro' . AE_PRO_SCRIPT_SUFFIX . '.js', [ 'jquery' ], AE_PRO_VERSION, true);

        wp_enqueue_script('aep-build', AE_PRO_URL . 'build/index' . AE_PRO_SCRIPT_SUFFIX . '.js', [ 'jquery' ], AE_PRO_VERSION, true);

        wp_register_style('vegas-css', AE_PRO_URL . 'includes/assets/lib/vegas/vegas' . AE_PRO_SCRIPT_SUFFIX . '.css', [], '2.4.0');
        wp_register_script('vegas', AE_PRO_URL . 'includes/assets/lib/vegas/vegas' . AE_PRO_SCRIPT_SUFFIX . '.js', [ 'jquery' ], '2.4.0', true);

        wp_register_style('justifiedGallery-css', AE_PRO_URL . 'includes/assets/lib/justifiedGallery/css/justifiedGallery' . AE_PRO_SCRIPT_SUFFIX . '.css', [], '3.8.1');
        wp_register_script('justifiedGallery', AE_PRO_URL . 'includes/assets/lib/justifiedGallery/js/justifiedGallery' . AE_PRO_SCRIPT_SUFFIX . '.js', [ 'jquery' ], '3.8.1', true);

        $helper = new Helper();

        if (Plugin::instance()->preview->is_preview_mode() ) {

            $post_css = $helper->ae_get_post_css();
            wp_add_inline_style('ae-pro-css', $post_css);

        }

        wp_enqueue_script('wc-single-product');
        wp_enqueue_style('woocommerce-general');

        global $wp_query;
        $queried_object = $wp_query->get_queried_object();
        $queried_page   = [];
        if (is_category() || is_tag() || is_tax() ) {
            $queried_page = [
            'term_id'   => $queried_object->term_id,
            'page_type' => 'taxonomy',
            ];
        } elseif (is_singular() ) {
            $queried_page = [
            'ID'        => $queried_object->ID,
            'page_type' => 'single',
            ];
        }

        wp_localize_script(
            'ae-pro-js',
            'aepro',
            [
            'ajaxurl'      => admin_url('admin-ajax.php'),
            'current_url'  => rawurlencode($helper->get_current_url_non_paged()),
            'plugin_url'   => plugins_url('anywhere-elementor-pro'),
            'queried_page' => $queried_page,
            'access_level' => \Aepro\Plugin::$_level,
            'aep_nonce'    => wp_create_nonce('aep_ajax_nonce'),
            ]
        );
        wp_localize_script(
            'aep-build',
            'aepro',
            [
            'ajaxurl'      => admin_url('admin-ajax.php'),
            'current_url'  => rawurlencode($helper->get_current_url_non_paged()),
            'plugin_url'   => plugins_url('anywhere-elementor-pro'),
            'queried_page' => $queried_page,
            'access_level' => \Aepro\Plugin::$_level,
            'aep_nonce'    => wp_create_nonce('aep_ajax_nonce'),
            ]
        );
        $localize_data = [
        'plugin_url' => plugins_url('anywhere-elementor-pro'),
        ];
        wp_localize_script('aepro-editor-js', 'aepro_editor', $localize_data);

        $map_key = get_option('ae_pro_gmap_api');
        if ($map_key ) {
            wp_register_script('ae-gmap', 'https://maps.googleapis.com/maps/api/js?loading=async&libraries=marker&key=' . $map_key, [], '1', true);
            wp_enqueue_script('ae-gmap-cluster', 'https://unpkg.com/@googlemaps/markerclusterer/dist/index.min.js', [ 'ae-gmap' ], '1', false);
        }

        wp_register_script('ae-masonry', AE_PRO_URL . 'includes/assets/lib/masonry/js/masonry.pkgd' . AE_PRO_SCRIPT_SUFFIX . '.js', [ 'jquery', 'jquery-masonry' ], '2.0.1', true);
        wp_register_script('ae-infinite-scroll', AE_PRO_URL . 'includes/assets/lib/infinite-scroll/infinite-scroll.pkgd' . AE_PRO_SCRIPT_SUFFIX . '.js', [ 'jquery' ], '3.0.5', true);
        //phpcs:ignore Squiz.PHP.CommentedOutCode.Found
        //add_filter('wp_grid_builder/frontend/register_scripts', [ $this, 'prefix_register_script' ]);

        // Move from after_setup_theme to wp_enqueue_scripts to make sure it runs after the base css is loaded
        if (\Aepro\Plugin::show_acf() || \Aepro\Plugin::show_acf(true) ) {
			$helper = new Helper();
			$post_cf_css = $helper->ae_get_cf_image_css();
			wp_add_inline_style('ae-pro-css', $post_cf_css);

			$post_term_cf_css = $helper->ae_get_term_cf_image_css();
			wp_add_inline_style('ae-pro-css', $post_term_cf_css);
		}
    }
    //phpcs:ignore Squiz.PHP.CommentedOutCode.Found
    /* public function prefix_register_script( $scripts )
    {

        $scripts[] = [
        'handle'  => 'wpgridbuilder',
        'source'  => plugins_url('anywhere-elementor-pro') . '/includes/assets/js/wpgridbuilder.js',
        'version' => '1.0.0',
        ];

        return $scripts;
    } */

    public function ae_admin_enqueue_scripts()
    {
        $screen = get_current_screen();
        //phpcd:ignore WordPress.PHP.StrictComparisons.LooseComparison
	        if ($screen->post_type === 'ae_global_templates' || $screen->id == 'plugins' ) {
	            $localize_data              = [];
	            $localize_data['aep_nonce'] = wp_create_nonce('aep_ajax_nonce');
	            $localize_data['locked_render_modes'] = aepro_get_locked_render_modes();
	            $localize_data['is_pro_active']       = aepro_is_pro_active();
	            $localize_data['upgrade_url']         = aepro_get_upgrade_url();
	            if ( class_exists( '\Aepro\Admin\TemplateSettingsSchema' ) ) {
	                $localize_data['render_mode_fields'] = \Aepro\Admin\TemplateSettingsSchema::get_edit_render_mode_field_visibility();
	            }
	            $admin_js_path = AE_PRO_PATH . 'includes/admin/admin-scripts.js';
	            $admin_js_ver  = file_exists( $admin_js_path ) ? (string) filemtime( $admin_js_path ) : AE_PRO_VERSION;
	            wp_enqueue_script('ae-admin-js', AE_PRO_URL . 'includes/admin/admin-scripts.js', [], $admin_js_ver, true);
            wp_localize_script('ae-admin-js', 'aepro', $localize_data);
            wp_enqueue_style('aep-select2', AE_PRO_URL . 'includes/assets/lib/select2/css/select2' . AE_PRO_SCRIPT_SUFFIX . '.css', [], '4.0.3');
            wp_enqueue_script('aep-select2', AE_PRO_URL . 'includes/assets/lib/select2/js/select2' . AE_PRO_SCRIPT_SUFFIX . '.js', [ 'jquery' ], '4.0.3', true);
        }
    }

    public function load_hook_positions()
    {
        $hook_positions        = [
        ''       => esc_html__('None', 'ae-pro'),
        'custom' => esc_html__('Custom', 'ae-pro'),
        ];
        $this->_hook_positions = $hook_positions;
    }

    public function get_hook_positions()
    {
        return $this->_hook_positions;
    }

    public function elementor_loaded()
    {

        include_once AE_PRO_PATH . 'includes/aep-finder.php';

        add_action(
            'elementor/finder/register',
            function ( $categories_manager ) {
                // Add the category
                $categories_manager->register(new Aep_Finder());
            }
        );

        /**
    * Initiate Upgrade Manager 
*/
        if (class_exists('\Aepro\Upgrade\Manager') ) {
            $this->upgrade = UpgradeManager::instance();
        }
    }


    public function load_wc_layout( $template, $slug, $name )
    {

        global $product, $ae_template;
        $helper         = new Helper();
        $ae_wc_template = '';

        if ($slug === 'content' && $name === 'single-product' ) {
            $ae_wc_template = $helper->get_ae_active_post_template($product->get_id(), 'product');
            if ($ae_wc_template !== '' && is_numeric($ae_wc_template) ) {
                $ae_wc_path = AE_PRO_PATH . 'includes/wc/ae-wc-single.php';
                return $ae_wc_path;
            }
        }

        /**
        // Code temporarily removed - #AEP-102
        if ($slug == 'content' && $name == 'product') {
         $ae_wc_template = $helper->get_woo_archive_template();
            if ($ae_wc_template != '' && is_numeric($ae_wc_template)) {
          if ($helper->is_full_override($ae_wc_template)) {
           $ae_theme = new Ae_Theme();
           $ae_theme->set_override('full');
              $ae_theme->set_use_canvas($helper->is_canvas_enabled($ae_wc_template));
           $ae_wc_path = $ae_theme->load_archive_template($template);
          } else {
           $ae_wc_path =  AE_PRO_PATH . 'includes/wc/ae-wc-archive.php';
          }
             return $ae_wc_path;
         }
        }         
**/

        return $template;
    }

    public function elementor_widget_registered()
    {

        // FacetWP Integration
        if ( file_exists( AE_PRO_PATH . 'pro/aepro.php' ) && class_exists('FacetWP') ) {
            include_once AE_PRO_PATH . 'pro/classes/facetwp-master.php';
        }
    }

    public function register_controls()
    {
        \Elementor\Plugin::instance()->controls_manager->register(new Query());
    }

    public function ae_woo_setup()
    {
        global $post;
        global $product;

        if (! class_exists('woocommerce') ) {
            return false;
        }

        if (is_product() ) {
            $helper              = new Helper();
            $ae_product_template = $helper->get_ae_active_post_template($post->ID, 'product');

            if ($ae_product_template ) {
                add_theme_support('wc-product-gallery-zoom');
                add_theme_support('wc-product-gallery-lightbox');
                add_theme_support('wc-product-gallery-slider');
            }
        }
    }

    public function editor_woo_scripts()
    {

        if (is_singular('ae_global_templates') ) {
            add_theme_support('wc-product-gallery-zoom');
            add_theme_support('wc-product-gallery-lightbox');
            add_theme_support('wc-product-gallery-slider');
        }
    }

    public function block_template_frontend()
    {

        if (is_singular('ae_global_templates') && ! current_user_can('edit_posts') ) {
            wp_safe_redirect(site_url(), 301);
            die;
        }
    }

    public function ae_pro_fail_load()
    {

        $plugin = 'elementor/elementor.php';

        if (_is_elementor_installed() ) {
            if (! current_user_can('activate_plugins') ) {
                return;
            }

            $message      = sprintf(__('<b>Dynific Addons for Elementor Pro</b> is not working because you need to activate the <b>Elementor</b> plugin.', 'ae-pro'), '<strong>', '</strong>');
            $action_url   = wp_nonce_url('plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin);
            $button_label = __('Activate Elementor', 'ae-pro');
        } else {
            if (! current_user_can('install_plugins') ) {
                return;
            }
            $message      = sprintf(__('<b>Dynific Addons for Elementor Pro</b> is not working because you need to install the <b>Elementor</b> plugin.', 'ae-pro'), '<strong>', '</strong>');
            $action_url   = wp_nonce_url(self_admin_url('update.php?action=install-plugin&plugin=elementor'), 'install-plugin_elementor');
            $button_label = __('Install Elementor', 'ae-pro');
        }

        $button = '<p><a href="' . $action_url . '" class="button-primary">' . $button_label . '</a></p><p></p>';

        printf(esc_html('<div class="%1$s"><p>%2$s</p>%3$s</div>'), 'notice notice-error', $message, $button);
    }

    public function plugin_row_meta( $plugin_meta, $plugin_file )
    {

        if (AE_PRO_BASE === $plugin_file ) {
            $row_meta = [
            'docs' => '<a href="https://wpbuilderaddons.com/docs/dynific-addons-elementor/" aria-label="' . esc_attr(__('View Documentation', 'ae-pro')) . '" target="_blank">' . __('Docs', 'ae-pro') . '</a>',
            ];

            $plugin_meta = array_merge($plugin_meta, $row_meta);
        }

        return $plugin_meta;
    }

    private function dashes_to_camel_case( $string, $capitalizeFirstCharacter = true )
    {

        $str = str_replace('-', '', ucwords($string, '-'));

        if (! $capitalizeFirstCharacter ) {
            $str = lcfirst($str);
        }

        return $str;
    }

    public function ae_elementor_requried_version_fail()
    {
        if (! current_user_can('update_plugins') ) {
            return;
        }
        $elementor_version_required = '3.0.0';
        $file_path                  = 'elementor/elementor.php';
        $upgrade_link               = wp_nonce_url(self_admin_url('update.php?action=upgrade-plugin&plugin=') . $file_path, 'upgrade-plugin_' . $file_path);
        $message                    = '<p>';
        $elementor_required_message = 'Dynific Addons for Elementor Pro requires Elementor ' . $elementor_version_required . '. Please update Elementor to continue.';
        $message                   .= esc_html__($elementor_required_message, 'ae-pro');
        $message                   .= '</p>';
        $message                   .= '<p>' . sprintf('<a href="%s" class="button-primary">%s</a>', $upgrade_link, __('Update Elementor Now', 'ae-pro')) . '</p>';
        echo esc_html('<div class="error">' . $message . '</div>');
    }
}

Aepro::instance();
