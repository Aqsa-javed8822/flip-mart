<?php
/**
 * Shortcodes Class.
 *
 * This class contains all the Shortcodes.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Helpers;

use W3TC\Dispatcher;
use RadiusTheme\SB\Traits\SingletonTrait;
use RadiusTheme\SB\Controllers\AssetRegistry;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * CacheController Class.
 */
class Cache {

	/**
	 * Option key holding the list of transients owned by the plugin.
	 *
	 * @var string
	 */
	const TRANSIENT_INDEX_KEY = 'shopbuilder_transient_cached_data';

	/**
	 * Lifetime of the transient index.
	 *
	 * Must outlive the individual transients it tracks (12 hours) so a purge can
	 * still find them, while keeping the option out of the autoloaded set.
	 *
	 * @var int
	 */
	const TRANSIENT_INDEX_TTL = WEEK_IN_SECONDS;

	/**
	 * Transient key prefixes owned by ShopBuilder.
	 *
	 * A purge resolves these against wp_options directly instead of consulting a
	 * stored list of keys. The stored list had to be read, appended to, deduped
	 * and rewritten on every frontend cache miss, which is O(n) per write and so
	 * O(n^2) to rebuild after an update wipes it. A prefix lookup moves that cost
	 * off the frontend entirely and onto the purge, which runs a few times a day.
	 *
	 * The template_for_* families are listed individually rather than collapsed
	 * into a single 'template_for_' prefix: that stem carries no plugin
	 * namespace, so a broad match could delete another plugin's transients.
	 *
	 * @var string[]
	 */
	const TRANSIENT_PREFIXES = [
		'rtsb_',
		'template_for_product_page_',
		'template_for_archive_page_',
		'template_for_tag_archive_page_',
		'template_for_brand_archive_page_',
	];

	/**
	 * Option holding the cache group rotation state.
	 *
	 * A single combined option rather than separate keys, so the signature, the
	 * group it produced and the generation counter can never drift apart: they
	 * are always written together in one atomic update_option() call.
	 *
	 * Shape: [ 'sig' => '<free>|<pro>', 'group' => '<group name>', 'gen' => int ]
	 *
	 * Autoloaded on purpose - it is a three key array, and once group() becomes
	 * version derived it is read on every request.
	 *
	 * @var string
	 */
	const STATE_OPTION = 'rtsb_cache_state';

	/**
	 * Group name used before the group became version derived.
	 *
	 * Also the prefix every derived name is built from, so a derived name can
	 * never collide with it: derived names always carry a `_<hash>` suffix.
	 *
	 * @var string
	 */
	const LEGACY_GROUP = 'shopbuilder';

	/**
	 * Resolved group for this request.
	 *
	 * A property rather than a function local static so maybe_rotate_group() can
	 * clear it after rotating, and the remainder of the request then uses the
	 * group we moved to instead of the one primed before rotation ran.
	 *
	 * @var string|null
	 */
	private static $group_memo = null;

	/**
	 * Whether a full purge already ran during this request.
	 *
	 * Both the free and the pro plugin hook `upgrader_process_complete`, and a
	 * bulk update can match both at once. Without this guard a single update
	 * would run the whole purge (including every third-party cache plugin) more
	 * than once in the same request.
	 *
	 * @var bool
	 */
	private static $all_cache_cleared = false;

	/**
	 * Object cache group owned by the plugin.
	 *
	 * Single source of truth for every wp_cache_* call in both the free and the
	 * pro plugin, so the group can be changed in one place instead of at ninety
	 * separate call sites.
	 *
	 * @return string
	 */
	public static function group() {
		if ( null !== self::$group_memo ) {
			return self::$group_memo;
		}

		if ( self::is_legacy_group_pinned() ) {
			self::$group_memo = self::LEGACY_GROUP;

			return self::$group_memo;
		}

		$state = self::get_state();

		// The stored group is canonical. It is only derived here when no state
		// exists yet, which means a fresh install or a wiped option.
		self::$group_memo = '' !== $state['group']
			? $state['group']
			: self::compute_group( self::state_signature(), $state['gen'] );

		return self::$group_memo;
	}

	/**
	 * Whether the site has pinned the group back to the pre-derivation name.
	 *
	 * Escape hatch for sites that need the old behaviour restored without a
	 * downgrade. It suspends the derived naming only. Signature tracking and the
	 * generation counter keep running underneath, so removing the hatch moves the
	 * site onto a group name that has never been used before.
	 *
	 * @return bool
	 */
	private static function is_legacy_group_pinned() {
		return defined( 'RTSB_LEGACY_CACHE_GROUP' ) && RTSB_LEGACY_CACHE_GROUP;
	}

	/**
	 * Derive a group name from a signature and generation.
	 *
	 * Deterministic on purpose: two requests racing the same rotation compute the
	 * same name and converge, rather than each inventing a group of its own.
	 *
	 * @param string $signature  Version signature.
	 * @param int    $generation Monotonic counter.
	 *
	 * @return string
	 */
	private static function compute_group( $signature, $generation ) {
		return self::LEGACY_GROUP . '_' . substr( md5( $signature . '|' . absint( $generation ) ), 0, 8 );
	}

	/**
	 * Signature of the currently installed plugin versions.
	 *
	 * Reads the constants directly rather than rtsb()->has_pro(), which depends
	 * on function_exists( 'rtsbpro' ) and is therefore load order sensitive. Both
	 * constants are defined while the plugin files are being included, so this is
	 * stable from the first hook onwards.
	 *
	 * @return string
	 */
	public static function state_signature() {
		return RTSB_VERSION . '|' . ( defined( 'RTSBPRO_VERSION' ) ? RTSBPRO_VERSION : '' );
	}

	/**
	 * Read the rotation state, normalised against missing or malformed data.
	 *
	 * Never throws and never returns a partial shape, so a hand edited or
	 * truncated option cannot break a request.
	 *
	 * @return array
	 */
	public static function get_state() {
		$state = get_option( self::STATE_OPTION, [] );

		if ( ! is_array( $state ) ) {
			$state = [];
		}

		return [
			'sig'   => ( isset( $state['sig'] ) && is_string( $state['sig'] ) ) ? $state['sig'] : '',
			'group' => ( isset( $state['group'] ) && is_string( $state['group'] ) && '' !== $state['group'] ) ? $state['group'] : '',
			'gen'   => isset( $state['gen'] ) ? absint( $state['gen'] ) : 0,
		];
	}

	/**
	 * Rotate the cache group when the installed version pair changes.
	 *
	 * Rotation is keyed on the version signature only, never on the group name.
	 * Once group() becomes version derived, the generation counter feeds into the
	 * group name, so comparing against the group name instead would change the
	 * name on every rotation and loop forever.
	 *
	 * The generation counter increments on ANY signature change, in either
	 * direction. That is what makes a downgrade safe: reverting to an older
	 * version produces a third, previously unused group rather than reusing the
	 * one that version had before, which could still hold stale entries.
	 *
	 * @return void
	 */
	public static function maybe_rotate_group() {
		$state     = self::get_state();
		$signature = self::state_signature();

		// First run, or the option was wiped or malformed. Record the current
		// state without touching the cache: there is no known previous group to
		// reclaim, and incrementing here would burn a generation for nothing.
		if ( '' === $state['sig'] || '' === $state['group'] ) {
			self::store_state( $signature, self::group(), $state['gen'] );

			return;
		}

		$pinned            = self::is_legacy_group_pinned();
		$signature_changed = ( $signature !== $state['sig'] );

		/*
		 * Invariant: the stored group must equal what the stored signature and
		 * generation produce. A violation means the naming scheme changed under
		 * us, which is exactly how the legacy group migrates to a derived one
		 * without requiring a version bump. It also self heals a hand edited or
		 * corrupted generation, in a single rotation.
		 *
		 * Suspended while pinned: a pinned group deliberately does not follow the
		 * derivation, so the check would report a violation on every request and
		 * rotate endlessly.
		 */
		$invariant_broken = ( ! $pinned && self::compute_group( $state['sig'], $state['gen'] ) !== $state['group'] );

		if ( ! $signature_changed && ! $invariant_broken ) {
			return;
		}

		$previous   = $state['group'];
		$generation = $state['gen'] + 1;
		$next       = $pinned ? self::LEGACY_GROUP : self::compute_group( $signature, $generation );

		self::store_state( $signature, $next, $generation );

		// Drop any value primed before this ran so the rest of the request uses
		// the group we just moved to.
		self::$group_memo = null;

		// Reclaim only the group we left. Never the one now in use, which is the
		// case whenever the name has not actually changed.
		if ( $next !== $previous ) {
			self::flush_group_if_supported( $previous );
		}
	}

	/**
	 * Persist the rotation state.
	 *
	 * @param string $signature Version signature.
	 * @param string $group     Group name in use for that signature.
	 * @param int    $generation Monotonic counter, never reused.
	 *
	 * @return void
	 */
	private static function store_state( $signature, $group, $generation ) {
		update_option(
			self::STATE_OPTION,
			[
				'sig'   => (string) $signature,
				'group' => (string) $group,
				'gen'   => absint( $generation ),
			],
			true
		);
	}

	/**
	 * Flush a single object cache group, only where the drop-in supports it.
	 *
	 * Deliberately has no fallback. wp_cache_flush() would empty every group on
	 * the site, including WooCommerce sessions and every other plugin's data,
	 * which is the exact behaviour this work exists to remove. When group
	 * flushing is unavailable the abandoned entries are simply left to expire.
	 *
	 * @param string $group Group to flush.
	 *
	 * @return bool Whether the flush was performed.
	 */
	public static function flush_group_if_supported( $group ) {
		if ( ! is_string( $group ) || '' === $group ) {
			return false;
		}

		if ( ! function_exists( 'wp_cache_supports' ) || ! function_exists( 'wp_cache_flush_group' ) ) {
			return false;
		}

		if ( ! wp_cache_supports( 'flush_group' ) ) {
			return false;
		}

		wp_cache_flush_group( $group );

		return true;
	}

	/**
	 * Clear the template cache.
	 *
	 * @since 4.3.0
	 */
	public static function clear_all_cache() {
		if ( self::$all_cache_cleared ) {
			return;
		}

		self::$all_cache_cleared = true;

		self::clear_data_cache();
		self::clear_template_cache();
		self::clear_transient_cache();
		self::clear_asset_cache();

		// Purged last on purpose. This is what empties the external page caches,
		// so it must not run until ShopBuilder's own transients and asset bundles
		// have been rebuilt - otherwise live traffic arrives while the plugin is
		// still mid-teardown and has to render against half-cleared state.
		self::clear_plugins_cache();
	}
	/**
	 * Clear the template cache.
	 *
	 * @since 4.3.0
	 */
	public static function clear_plugins_cache() {
		// Clear W3 Total Cache.
		if ( function_exists( 'w3tc_flush_all' ) ) {
			w3tc_flush_all();
		}
		// Clear WP Super Cache.
		if ( function_exists( 'wp_cache_clear_cache' ) ) {
			wp_cache_clear_cache();
		}
		// Clear WP Rocket cache.
		if ( function_exists( 'rocket_clean_domain' ) ) {
			rocket_clean_domain();
		}
		if ( method_exists( 'LiteSpeed_Cache_API', 'purge_all' ) ) {
			\LiteSpeed_Cache_API::purge_all();
		}
		if ( class_exists( '\LiteSpeed\Purge' ) ) {
			\LiteSpeed\Purge::purge_all();
		}
		if ( class_exists( 'Endurance_Page_Cache' ) ) {
			$epc = new \Endurance_Page_Cache();
			$epc->purge_all();
		}
		if ( class_exists( 'SG_CachePress_Supercacher' ) && method_exists( 'SG_CachePress_Supercacher', 'purge_cache' ) ) {
			\SG_CachePress_Supercacher::purge_cache( true );
		}
		if ( class_exists( 'SiteGround_Optimizer\Supercacher\Supercacher' ) ) {
			\SiteGround_Optimizer\Supercacher\Supercacher::purge_cache();
		}
		if ( isset( $GLOBALS['wp_fastest_cache'] ) && method_exists( $GLOBALS['wp_fastest_cache'], 'deleteCache' ) ) {
			$GLOBALS['wp_fastest_cache']->deleteCache( true );
		}
		if ( is_callable( [ 'Swift_Performance_Cache', 'clear_all_cache' ] ) ) {
			\Swift_Performance_Cache::clear_all_cache();
		}
		if ( is_callable( [ 'Hummingbird\WP_Hummingbird', 'flush_cache' ] ) ) {
			\Hummingbird\WP_Hummingbird::flush_cache( true, false );
		}
		if ( class_exists( 'WP_Optimize' ) ) {
			\WP_Optimize()->get_page_cache()->purge();
		}

		// Purge WP Engine.
		if ( class_exists( 'WpeCommon' ) ) {
			if ( method_exists( 'WpeCommon', 'purge_memcached' ) ) {
				\WpeCommon::purge_memcached();
			}
			if ( method_exists( 'WpeCommon', 'clear_maxcdn_cache' ) ) {
				\WpeCommon::clear_maxcdn_cache();
			}
			if ( method_exists( 'WpeCommon', 'purge_varnish_cache' ) ) {
				\WpeCommon::purge_varnish_cache();
			}
		}
		// Purge Kinsta.
		global $kinsta_cache;
		if ( isset( $kinsta_cache ) && class_exists( '\\Kinsta\\CDN_Enabler' ) ) {
			if ( ! empty( $kinsta_cache->kinsta_cache_purge ) && is_callable( [ $kinsta_cache->kinsta_cache_purge, 'purge_complete_caches' ] ) ) {
				$kinsta_cache->kinsta_cache_purge->purge_complete_caches();
			}
		}
		// Purge Pagely.
		if ( class_exists( 'PagelyCachePurge' ) ) {
			$purge_pagely = new \PagelyCachePurge();
			if ( is_callable( [ $purge_pagely, 'purgeAll' ] ) ) {
				$purge_pagely->purgeAll();
			}
		}
		// Purge Pressidum.
		if ( defined( 'WP_NINUKIS_WP_NAME' ) && class_exists( 'Ninukis_Plugin' ) && is_callable( [ 'Ninukis_Plugin', 'get_instance' ] ) ) {
			$purge_pressidum = \Ninukis_Plugin::get_instance();
			if ( is_callable( [ $purge_pressidum, 'purgeAllCaches' ] ) ) {
				$purge_pressidum->purgeAllCaches();
			}
		}
		// Purge Savvii.
		if ( defined( '\Savvii\CacheFlusherPlugin::NAME_DOMAINFLUSH_NOW' ) ) {
			$purge_savvii = new \Savvii\CacheFlusherPlugin();
			if ( is_callable( [ $purge_savvii, 'domainflush' ] ) ) {
				$purge_savvii->domainflush();
			}
		}
		// Purge Hyper Cache.
		if ( class_exists( 'HyperCache' ) ) {
			do_action( 'autoptimize_action_cachepurged' );
		}
		// purge cache enabler.
		if ( has_action( 'ce_clear_cache' ) ) {
			do_action( 'ce_clear_cache' );
		}
		// When plugins have a simple method, add them to the array ('Plugin Name' => 'method_name').
		$others = [
			'WP Fastest Cache' => 'wpfc_clear_all_cache',
			'Cachify'          => 'cachify_flush_cache',
			'Comet Cache'      => [ 'comet_cache', 'clear' ],
			'SG Optimizer'     => 'sg_cachepress_purge_cache',
			'Pantheon'         => 'pantheon_wp_clear_edge_all',
			'Zen Cache'        => [ 'zencache', 'clear' ],
			'Breeze'           => [ 'Breeze_PurgeCache', 'breeze_cache_flush' ],
		];
		foreach ( $others as $plugin => $method ) {
			if ( is_callable( $method ) ) {
				call_user_func( $method );
			}
		}
		// Purge Godaddy Managed WordPress Hosting (Varnish + APC).
		if ( class_exists( 'WPaaS\Plugin' ) ) {
			self::godaddy_request( 'BAN' );
		}

		wp_cache_flush();
	}


	/**
	 * Purge GoDaddy Managed WordPress Hosting (Varnish)
	 *
	 * Source: https://github.com/wp-media/wp-rocket/blob/master/inc/3rd-party/hosting/godaddy.php
	 *
	 * @param string $method The request method.
	 * @param string $url    The request URL.
	 *
	 * @return void
	 */
	public static function godaddy_request( $method, $url = null ) {
		$url  = empty( $url ) ? home_url() : $url;
		$host = wp_parse_url( $url, PHP_URL_HOST );
		$url  = set_url_scheme( str_replace( $host, \WPaas\Plugin::vip(), $url ), 'http' );
		update_option( 'gd_system_last_cache_flush', time() ); // purge apc.
		wp_remote_request(
			esc_url_raw( $url ),
			[
				'method'   => $method,
				'blocking' => false,
				'headers'  => [ 'Host' => $host ],
			]
		);
	}

	/**
	 * Add a template to the template cache.
	 *
	 * @since 4.3.0
	 * @param string $cache_key Object cache key.
	 * @param string $template Located template.
	 */
	public static function set_template_cache( $cache_key, $template ) {
		wp_cache_set( $cache_key, $template, self::group(), 12 * HOUR_IN_SECONDS );
		$cached_templates = wp_cache_get( 'shopbuilder_cached_templates', self::group() );
		if ( is_array( $cached_templates ) ) {
			$cached_templates[] = $cache_key;
		} else {
			$cached_templates = [ $cache_key ];
		}
		// The index must outlive the entries it tracks so a purge can still find
		// them, hence the longer lifetime than the templates themselves.
		wp_cache_set( 'shopbuilder_cached_templates', $cached_templates, self::group(), WEEK_IN_SECONDS );
	}
	/**
	 * Clear the template cache.
	 *
	 * @since 4.3.0
	 */
	public static function clear_template_cache() {
		$cached_templates = wp_cache_get( 'shopbuilder_cached_templates', self::group() );
		if ( is_array( $cached_templates ) ) {
			foreach ( $cached_templates as $cache_key ) {
				wp_cache_delete( $cache_key, self::group() );
			}
			wp_cache_delete( 'shopbuilder_cached_templates', self::group() );
		}
	}
	/**
	 * Added data cache.
	 *
	 * @since 4.3.0
	 * @param string $cache_key Object cache key.
	 *
	 * @return void
	 */
	public static function set_data_cache_key( $cache_key ) {
		$cached_data = wp_cache_get( 'shopbuilder_cached_data', self::group() );
		if ( is_array( $cached_data ) ) {
			$cached_data[] = $cache_key;
		} else {
			$cached_data = [ $cache_key ];
		}
		// The index must outlive the entries it tracks so a purge can still find
		// them, hence the longer lifetime than the cached data itself.
		wp_cache_set( 'shopbuilder_cached_data', $cached_data, self::group(), WEEK_IN_SECONDS );
	}
	/**
	 * Clear data cache.
	 *
	 * @since 4.3.0
	 */
	public static function clear_data_cache() {
		$cached_templates = wp_cache_get( 'shopbuilder_cached_data', self::group() );
		if ( is_array( $cached_templates ) ) {
			foreach ( $cached_templates as $cache_key ) {
				wp_cache_delete( $cache_key, self::group() );
			}
			wp_cache_delete( 'shopbuilder_cached_data', self::group() );
		}
	}

	/**
	 * Clear asset cache.
	 *
	 * @return void
	 */
	public static function clear_asset_cache() {
		if ( ! Fns::is_optimization_enabled() ) {
			return;
		}

		$upload_dir = wp_upload_dir();
		$asset_dir  = trailingslashit( $upload_dir['basedir'] ) . 'shopbuilder_uploads/cache/';

		// Delete old assets.
		if ( is_dir( $asset_dir ) ) {
			self::delete_dir_contents( $asset_dir );
		}

		// Re-generate assets.
		AssetRegistry::instance()->regenerate_bundles();
	}

	/**
	 * Recursively delete directory contents.
	 *
	 * @param string $dir Directory path.
	 * @return void
	 */
	public static function delete_dir_contents( $dir ) {
		global $wp_filesystem;

		if ( ! function_exists( 'WP_Filesystem' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}

		if ( ! $wp_filesystem ) {
			WP_Filesystem();
		}

		if ( ! $wp_filesystem->is_dir( $dir ) ) {
			return;
		}

		$contents = $wp_filesystem->dirlist( $dir );

		if ( ! is_array( $contents ) ) {
			return;
		}

		foreach ( $contents as $item ) {
			$path = trailingslashit( $dir ) . $item['name'];

			if ( 'f' === $item['type'] ) {
				$wp_filesystem->delete( $path, false );
			} elseif ( 'd' === $item['type'] ) {
				self::delete_dir_contents( $path );

				$wp_filesystem->delete( $path, true );
			}
		}
	}


	/**
	 * Register a transient key with the purge index.
	 *
	 * @deprecated No longer required. Purges now resolve the plugin's transients
	 *             from wp_options by key prefix, see TRANSIENT_PREFIXES.
	 *
	 * Maintaining the index meant reading, appending to, deduplicating and
	 * rewriting the whole list of keys on every frontend cache miss. That is
	 * O(n) per write, so rebuilding it after an update wiped it cost O(n^2):
	 * measured at 13.8 seconds of CPU for a 10,000 product catalogue. It was
	 * also lossy under concurrency, because the read-modify-write was unguarded.
	 *
	 * Kept as a no-op so third-party callers do not fatal.
	 *
	 * @since 4.3.0
	 * @param string $cache_key Transient cache key.
	 */
	public static function set_transient_cache_key( $cache_key ) {
		// Intentionally empty. Retained so third-party code calling this does not
		// fatal; ShopBuilder itself no longer calls it.
		unset( $cache_key );
	}

	/**
	 * Clear every transient owned by the plugin.
	 *
	 * @since 4.3.0
	 *
	 * @return int Number of transients deleted.
	 */
	public static function clear_transient_cache() {
		$deleted = self::delete_transients_by_prefix( self::TRANSIENT_PREFIXES );

		/*
		 * Nothing writes the index any more, but installations upgrading from an
		 * earlier version still hold one, and it can be megabytes on a large
		 * catalogue. Its key predates the rtsb_ namespace so no prefix matches
		 * it; remove it explicitly. Harmless when already absent.
		 */
		delete_transient( self::TRANSIENT_INDEX_KEY );

		return $deleted;
	}

	/**
	 * Delete every transient whose key starts with one of the given prefixes.
	 *
	 * Runs one indexed lookup against wp_options per prefix and deletes through
	 * delete_transient(), so the timeout row is removed with its value and any
	 * persistent object cache stays consistent.
	 *
	 * Only ever called from a purge. It must not be reachable from a normal
	 * frontend request.
	 *
	 * @param string[] $prefixes Transient key prefixes, without the _transient_ part.
	 *
	 * @return int Number of transients deleted.
	 */
	public static function delete_transients_by_prefix( array $prefixes ) {
		global $wpdb;

		$deleted = 0;

		foreach ( $prefixes as $prefix ) {
			if ( '' === $prefix ) {
				continue;
			}

			/*
			 * esc_like() is applied to the whole literal, including the
			 * _transient_ part: the underscore is a single character wildcard in
			 * LIKE, so an unescaped '_transient_rtsb_' would also match keys such
			 * as 'Xtransient_rtsbY'.
			 */
			$like = $wpdb->esc_like( '_transient_' . $prefix ) . '%';

			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
			$option_names = $wpdb->get_col(
				$wpdb->prepare(
					"SELECT option_name FROM {$wpdb->options} WHERE option_name LIKE %s",
					$like
				)
			);

			foreach ( $option_names as $option_name ) {
				// Trim only the leading marker; str_replace() would also corrupt
				// a key that happens to contain '_transient_' further along.
				$key = substr( $option_name, strlen( '_transient_' ) );

				if ( '' === $key ) {
					continue;
				}

				delete_transient( $key );
				++$deleted;
			}
		}

		return $deleted;
	}

	/**
	 * Clear all theme css handle transients.
	 *
	 * @return void
	 */
	public static function delete_all_theme_css_handle_transients() {
		self::delete_transients_by_prefix( [ 'rtsb_theme_css_handle_' ] );
	}
}
