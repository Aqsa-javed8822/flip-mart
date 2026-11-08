<?php
if ( ! class_exists( 'cdx_wpgs_suggest_plugin' ) ) {
	class cdx_wpgs_suggest_plugin {

		static function init() {
			if ( is_admin() ) {
				if ( ! is_plugin_active( 'deposits-for-woocommerce/deposits-for-woocommerce.php' ) ) {
					add_filter( 'install_plugins_table_api_args_featured', array( __CLASS__, 'featured_plugins_tab' ) );
				}
			}
		} // init

		// add our plugins to recommended list
		static function plugins_api_result( $res, $action, $args ) {
			remove_filter( 'plugins_api_result', array( __CLASS__, 'plugins_api_result' ), 10, 1 );
			$res = self::add_plugin_favs( array( 'deposits-for-woocommerce', 'woo-product-gallery-slider','precart' ), $res );

			return $res;
		} // plugins_api_result

		// helper function for adding plugins to fav list
		static function featured_plugins_tab( $args ) {
			add_filter( 'plugins_api_result', array( __CLASS__, 'plugins_api_result' ), 10, 3 );

			return $args;
		} // featured_plugins_tab

		/**
		 * Add one or more plugins to the favorites list (new plugins appear on top).
		 *
		 * @param string|array $plugin_slugs One slug or an array of slugs.
		 * @param object       $res          Response object with plugins.
		 * @return object Updated response object.
		 */
		static function add_plugin_favs( $plugin_slugs, $res ) {

			// Always make $plugin_slugs an array
			$plugin_slugs = (array) $plugin_slugs;

			// Ensure $res->plugins is an array
			if ( ! isset( $res->plugins ) || ! is_array( $res->plugins ) ) {
				$res->plugins = array();
			}

			foreach ( $plugin_slugs as $plugin_slug ) {

				// Skip invalid slugs
				if ( empty( $plugin_slug ) || ! is_string( $plugin_slug ) ) {
					continue;
				}

				// Skip if already in the list
				$already_added = false;
				foreach ( $res->plugins as $plugin ) {
					if ( is_object( $plugin ) && ! empty( $plugin->slug ) && $plugin->slug === $plugin_slug ) {
						$already_added = true;
						break;
					}
				}
				if ( $already_added ) {
					continue;
				}

				// Try transient first
				$plugin_info = get_transient( 'cdx-wpgs-plugin-info-' . $plugin_slug );

				if ( ! $plugin_info ) {
					// Fetch plugin info from WP.org API
					$plugin_info = plugins_api(
						'plugin_information',
						array(
							'slug'   => $plugin_slug,
							'is_ssl' => is_ssl(),
							'fields' => array(
								'banners'           => true,
								'reviews'           => true,
								'downloaded'        => true,
								'active_installs'   => true,
								'icons'             => true,
								'short_description' => true,
							),
						)
					);

					if ( ! is_wp_error( $plugin_info ) ) {
						set_transient( 'cdx-wpgs-plugin-info-' . $plugin_slug, $plugin_info, DAY_IN_SECONDS * 7 );
					}
				}

				if ( $plugin_info && ! is_wp_error( $plugin_info ) ) {
					// 🔹 Add new plugin to the *top* of the list
					array_unshift( $res->plugins, $plugin_info );
				}
			}

			return $res;
		}
	}

	add_action( 'init', array( 'cdx_wpgs_suggest_plugin', 'init' ) );
}
