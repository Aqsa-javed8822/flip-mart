<?php
namespace Aepro\Themes\Generic;

use Aepro\Themes\Ae_Theme_Base;
class Ae_Theme extends Ae_Theme_Base
{

    public function manage_actions()
    {
        parent::manage_actions();

        do_action('aep_theme_manage_actions');

        return true;
    }

    public function theme_hooks( $hook_positions )
    {

        return $hook_positions;
    }

    public function set_fullwidth()
    {

        do_action('aep_theme_fullwidth');
    }
}
