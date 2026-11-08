<?php
namespace Aepro\Themes\PageBuilderFramework;

use Aepro\Themes\Ae_Theme_Base;

class Ae_Theme extends Ae_Theme_Base
{

    public function remove_ocean_page_header()
    {
        return false;
    }

    public function css_rules()
    {
        $css = '#inner-content{ padding:0 !important; }';
        wp_add_inline_style('ae-pro-css', $css);
    }

    public function theme_hooks( $hook_positions )
    {

        return $hook_positions;
    }

    public function set_fullwidth()
    {
        add_action('wp_enqueue_scripts', [ $this, 'css_rules' ]);
        add_action('body_class', [ $this, 'ae_wpbf_body' ]);
        remove_action('wpbf_sidebar_right', 'wpbf_do_sidebar_right');
    }
    public function ae_wpbf_body( $classes )
    {
     //phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
        $key = array_search('wpbf-sidebar-right', $classes);
        if ($key !== false ) {
            unset($classes[ $key ]);
        }
     //phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
        $key = array_search('wpbf-sidebar-right', $classes);
        if ($key !== false ) {
            unset($classes[ $key ]);
        }

        $classes[] = 'wpbf-no-sidebar';
        return $classes;
    }
}
