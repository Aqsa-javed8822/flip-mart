<?php
namespace Aepro\Themes\Storefront;

class Ae_Theme extends Ae_Theme_Base
{


    public function manage_actions()
    {
        parent::manage_actions();
        add_filter('woocommerce_get_breadcrumb', '__return_false');
    }

    public function remove_ocean_page_header()
    {
        return false;
    }

    public function css_rules()
    {
        $css = 'body #primary { float: none !important; width: 100% !important; }';
        wp_add_inline_style('ae-pro-css', $css);
    }

    public function theme_hooks( $hook_positions )
    {

        return $hook_positions;
    }

    public function set_fullwidth()
    {
        remove_action('storefront_sidebar', 'storefront_get_sidebar', 10);
        add_action('wp_enqueue_scripts', [ $this, 'css_rules' ]);
    }
}
