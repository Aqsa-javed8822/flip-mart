<?php
namespace Aepro\Themes\Twentyseventeen;

use Aepro\Themes\Ae_Theme_Base;

class Ae_Theme extends Ae_Theme_Base
{


    public function manage_actions()
    {
        parent::manage_actions();

        add_filter(
            'body_class',
            function ( $classes ) {
                $classes[] = 'no-sidebar';
                return $classes;
            }
        );
        add_action('wp_enqueue_scripts', [ $this, 'css_rules' ]);

        remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);
        remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);

        if (is_singular() ) {
            add_filter(
                'post_thumbnail_html',
                function () {
                    $html = '';
                    return $html;
                }
            );
        }
    }

    public function css_rules()
    {
        $css  = 'body #primary { float: none !important; width: 100% !important; }';
        $css .= '#content{ padding:0 !important; }';
        $css .= '.search-form button.search-submit { position: unset; padding: 10px;}';
        wp_add_inline_style('ae-pro-css', $css);
    }

    public function theme_hooks( $hook_positions )
    {

        return $hook_positions;
    }

    public function set_fullwidth()
    {
    }
}
