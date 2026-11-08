<?php
namespace Aepro;

use Aepro\Classes\CacheManager;
use Elementor\Core\Common\Modules\Finder\Base_Category;


class Aep_Finder extends Base_Category {

	public function get_title() {
		return __( 'Dynific Addons for Elementor Pro', 'ae-pro' );
	}

	public function get_id(){
		return 'aep-finder';
	}

	public function get_category_items( array $options = [] ) {

		$items = CacheManager::instance()->get_finder_items();

		return $items;
	}
}
