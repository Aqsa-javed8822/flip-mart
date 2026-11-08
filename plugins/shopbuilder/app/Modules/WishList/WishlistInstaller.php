<?php

namespace RadiusTheme\SB\Modules\WishList;

use RadiusTheme\SB\Helpers\Fns;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

class WishlistInstaller {

	/**
	 * Run the installer
	 *
	 * @return void
	 */
	public function run() {
		$this->create_page();
	}

	/**
	 * [create_page] Create page
	 *
	 * @return void [void]
	 */
	private function create_page() {

		$page_id = Fns::get_option( 'modules', 'wishlist', 'page', 0, 'number' );
		if ( $page_id > 0 ) {
			$page_object = get_post( $page_id );

			if ( $page_object && 'page' === $page_object->post_type && ! in_array(
				$page_object->post_status,
				[
					'pending',
					'trash',
					'future',
					'auto-draft',
				],
				true
			) ) {
				// Valid page is already in place.
				return;
			}
		}
		if ( function_exists( 'WC' ) ) {
			if ( ! function_exists( 'wc_create_page' ) ) {
				require_once WC_ABSPATH . '/includes/admin/wc-admin-functions.php';
			}
			$create_page_id = wc_create_page(
				sanitize_title_with_dashes( _x( 'wishlist', 'page_slug', 'shopbuilder' ) ),
				'',
				esc_html__( 'Wishlist', 'shopbuilder' ),
				'<!-- wp:shortcode -->[rtsb_wishlist]<!-- /wp:shortcode -->'
			);
			if ( $create_page_id ) {
				$section_items         = [];
				$section_items['page'] = strval( $create_page_id );
				Fns::set_options( 'modules', 'wishlist', $section_items );
			}
		}
	}
}
