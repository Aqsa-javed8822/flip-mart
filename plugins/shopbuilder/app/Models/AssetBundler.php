<?php
/**
 * AssetBundler class.
 *
 * Dynamically merges and minifies CSS/JS files into a single versioned file stored in uploads.
 *
 * @package  RadiusTheme\SB
 * @since    1.0.0
 */

namespace RadiusTheme\SB\Models;

use RadiusTheme\SB\Dependencies\MatthiasMullie\Minify\JS;
use RadiusTheme\SB\Dependencies\MatthiasMullie\Minify\CSS;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * AssetBundler class.
 */
class AssetBundler {

	/**
	 * How long a build lock stays valid, in seconds.
	 *
	 * Generous compared with the measured build cost (tens of milliseconds) so a
	 * slow host is never treated as a crashed one, while still bounding how long
	 * a lock left by a fatal error can hold up the next attempt.
	 *
	 * @var int
	 */
	const BUILD_LOCK_TTL = 60;

	/**
	 * Handle for this bundle (used in filename/versioning).
	 *
	 * @var string
	 */
	protected $handle;

	/**
	 * List of source file paths to bundle.
	 *
	 * @var array
	 */
	protected $source_files = [];

	/**
	 * Directory where a minified bundle will be saved.
	 *
	 * @var string
	 */
	protected $upload_dir;

	/**
	 * Generated filename of the minified JS file.
	 *
	 * @var string
	 */
	protected $filename;

	/**
	 * File type (JS or CSS).
	 *
	 * @var string
	 */
	protected $type;

	/**
	 * AssetBundler constructor.
	 *
	 * @param string $handle Unique identifier for the bundle.
	 * @param array  $files  List of absolute file paths to include in the bundle.
	 * @param string $type   File type (JS or CSS).
	 *
	 * @return void
	 */
	public function __construct( string $handle, array $files, string $type = 'js' ) {
		$this->handle       = sanitize_key( $handle );
		$this->source_files = $files;
		$this->type         = in_array( $type, [ 'js', 'css' ], true ) ? $type : 'js';

		$upload           = wp_upload_dir();
		$this->upload_dir = trailingslashit( $upload['basedir'] ) . "shopbuilder_uploads/cache/$this->type/";

		$this->maybe_create_cache_dir();
	}

	/**
	 * Creates the cache directory if it doesn't exist.
	 *
	 * @return void
	 */
	protected function maybe_create_cache_dir() {
		if ( ! file_exists( $this->upload_dir ) ) {
			wp_mkdir_p( $this->upload_dir );
		}

		if ( ! wp_is_writable( $this->upload_dir ) ) {
			global $wp_filesystem;

			if ( ! function_exists( 'WP_Filesystem' ) ) {
				require_once ABSPATH . 'wp-admin/includes/file.php';
			}

			if ( ! $wp_filesystem ) {
				WP_Filesystem();
			}

			if ( $wp_filesystem ) {
				$wp_filesystem->chmod( $this->upload_dir, FS_CHMOD_DIR );
			}
		}
	}

	/**
	 * Generates the full path to the cached JS file based on handle and file hash.
	 *
	 * @return string
	 */
	protected function get_cache_file_path() {
		$this->filename = $this->generate_filename();

		return $this->upload_dir . $this->filename;
	}

	/**
	 * Checks if the cached version of the JS file already exists.
	 *
	 * @return bool
	 */
	protected function is_cached() {
		return file_exists( $this->get_cache_file_path() );
	}

	/**
	 * Builds the minified JS bundle if not cached and returns the URL.
	 *
	 * @return string
	 */
	public function build() {
		$cache_path = $this->get_cache_file_path();

		if ( ! $this->is_cached() ) {
			if ( ! wp_is_writable( $this->upload_dir ) ) {
				return '';
			}

			// Guard against the scoped Minify library not being loadable yet — e.g. the first
			// request right after a plugin upgrade, when opcache/the autoload map still points
			// at the old (now deleted) files. Instantiating the class then would fatal; instead
			// skip bundling for this request and fall back to the unbundled assets. It self-heals
			// on the next request once the autoloader is refreshed.
			$minify_class = ( 'css' === $this->type ) ? CSS::class : JS::class;

			if ( ! class_exists( $minify_class ) ) {
				return '';
			}

			/*
			 * One builder per bundle.
			 *
			 * Without this every concurrent request that finds a cold bundle
			 * minifies the whole set independently: measured 8 of 8 and 13 of 16
			 * processes doing identical work. The loser does not wait - waiting
			 * would just move the cost rather than remove it - it returns an empty
			 * source so the caller falls back to the unbundled assets for this one
			 * request, and picks up the finished bundle on the next.
			 *
			 * This is an optimisation, not a correctness mechanism: the atomic
			 * rename below is what guarantees nobody ever reads a partial file, so
			 * a lock that occasionally fails to exclude is harmless.
			 */
			if ( ! $this->acquire_build_lock() ) {
				return '';
			}

			try {
				$minifier = ( 'css' === $this->type ) ? new CSS() : new JS();

				foreach ( $this->source_files as $file ) {
					$this->safely_add_file_to_minifier( $file, $minifier );
				}

				/*
				* Minify to a temporary file and move it into place only once it is
				* complete.
				*
				* The minifier opens its target with fopen( $path, 'w' ), which
				* truncates immediately, and is_cached() only tests file_exists().
				* Writing straight to the final path therefore exposes a window in
				* which the bundle exists at zero bytes and is reported as cached, so
				* a concurrent request would serve an empty stylesheet or script.
				* rename() is atomic on POSIX, so readers see either the previous
				* bundle or the finished one, never a partial write.
				*/
				$temp_path = $this->get_temp_file_path();

				try {
					$minifier->minify( $temp_path );
				} catch ( \Exception $e ) {
					$this->delete_temp_file( $temp_path );
					error_log( '[ShopBuilder Minify] ' . $e->getMessage() ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log

					return '';
				}

				/*
				 * rename() rather than WP_Filesystem::move(): atomicity is the whole
				 * point of this fix, and WP_Filesystem only delivers it on the direct
				 * transport. Over FTP or SSH it copies, which reintroduces the partial
				 * read this is meant to remove, and initialising WP_Filesystem on a
				 * frontend request is both expensive and liable to fail without
				 * credentials. The @ suppresses the warning on a losing race, which is
				 * handled below rather than surfaced.
				 */
				// phpcs:ignore WordPress.WP.AlternativeFunctions.rename_rename, WordPress.PHP.NoSilencedErrors.Discouraged
				if ( ! @rename( $temp_path, $cache_path ) ) {
					$this->delete_temp_file( $temp_path );
					error_log( '[ShopBuilder Minify] Could not move the bundle into place: ' . $cache_path ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log

					return '';
				}

				// Only once the new bundle is in place is it safe to drop the ones it
				// replaced. Doing this earlier would leave the site with nothing to
				// serve if the build failed.
				$this->prune_obsolete_bundles();
			} finally {
				// Every exit above returns from inside the try, so the release has
				// to live here: success, minify exception and rename failure all
				// have to hand the lock back.
				$this->release_build_lock();
			}
		}

		return $this->get_url();
	}

	/**
	 * Path of the lock guarding this bundle's build.
	 *
	 * Sits beside the bundle it guards, so the lock is per bundle rather than
	 * global and two different bundles never contend. The `.lock` suffix keeps it
	 * outside the `handle-*.min.type` glob used by prune_obsolete_bundles(), so
	 * pruning can never delete a live lock.
	 *
	 * @return string
	 */
	protected function get_lock_file_path() {
		return $this->get_cache_file_path() . '.lock';
	}

	/**
	 * Try to become the process that builds this bundle.
	 *
	 * Opening with the 'x' mode creates the file only if it does not already
	 * exist, and the check and create are a single atomic operation, so exactly
	 * one caller can win. A lock older than the TTL is assumed to belong to a
	 * request that died mid build and is reclaimed.
	 *
	 * @return bool Whether this process holds the lock.
	 */
	protected function acquire_build_lock() {
		$lock_path = $this->get_lock_file_path();

		clearstatcache( true, $lock_path );

		if ( file_exists( $lock_path ) ) {
			$age = time() - (int) filemtime( $lock_path );

			if ( $age < self::BUILD_LOCK_TTL ) {
				return false;
			}

			// Stale: the builder that created this is gone.
			wp_delete_file( $lock_path );
		}

		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fopen, WordPress.PHP.NoSilencedErrors.Discouraged
		$handle = @fopen( $lock_path, 'x' );

		if ( false === $handle ) {
			// Another process created it between the check above and here.
			return false;
		}

		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fwrite, WordPress.PHP.NoSilencedErrors.Discouraged
		@fwrite( $handle, (string) getmypid() );
		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fclose
		fclose( $handle );

		return true;
	}

	/**
	 * Hand the build lock back.
	 *
	 * @return void
	 */
	protected function release_build_lock() {
		$lock_path = $this->get_lock_file_path();

		clearstatcache( true, $lock_path );

		if ( file_exists( $lock_path ) ) {
			wp_delete_file( $lock_path );
		}
	}

	/**
	 * Path for the in-progress bundle.
	 *
	 * Lives in the same directory as the final file so rename() stays on one
	 * filesystem, and carries a per-process suffix so two concurrent builds never
	 * share a temporary file. The suffix also keeps it outside the
	 * `handle-*.min.type` glob used for pruning, so one build cannot delete
	 * another's work in progress.
	 *
	 * @return string
	 */
	protected function get_temp_file_path() {
		return $this->get_cache_file_path() . '.tmp-' . getmypid() . '-' . uniqid( '', true );
	}

	/**
	 * Remove a temporary file, ignoring the case where it was never created.
	 *
	 * @param string $path Temporary file path.
	 *
	 * @return void
	 */
	protected function delete_temp_file( $path ) {
		if ( $path && file_exists( $path ) ) {
			wp_delete_file( $path );
		}
	}

	/**
	 * Delete previous bundles for this handle, keeping the current one.
	 *
	 * Bundle filenames embed a hash of the plugin versions and the source file
	 * list, so a new file appears on every update and on every settings change
	 * that alters which assets are included. Without this the directory grows
	 * without bound: nothing else prunes it, because Cache::clear_asset_cache()
	 * returns early when optimization is disabled or the directory is not
	 * writable.
	 *
	 * @return void
	 */
	protected function prune_obsolete_bundles() {
		$current = $this->get_cache_file_path();
		$pattern = $this->upload_dir . "$this->handle-*.min.$this->type";
		$files   = glob( $pattern );

		if ( ! is_array( $files ) ) {
			return;
		}

		foreach ( $files as $file ) {
			if ( $file === $current ) {
				continue;
			}

			if ( file_exists( $file ) ) {
				wp_delete_file( $file );
			}
		}
	}

	/**
	 * Returns the URL to the cached JS bundle.
	 *
	 * @return string
	 */
	public function get_url() {
		$upload = wp_upload_dir();

		return trailingslashit( $upload['baseurl'] ) . "shopbuilder_uploads/cache/$this->type/" . $this->filename;
	}

	/**
	 * Clears all cached files related to this bundle handle.
	 *
	 * @return void
	 */
	public function clear_cache() {
		foreach ( glob( $this->upload_dir . "$this->handle-*.min.$this->type" ) as $file ) {
			if ( file_exists( $file ) ) {
				wp_delete_file( $file );
			}
		}
	}

	/**
	 * Generates the filename for the minified JS file.
	 *
	 * @return string
	 */
	protected function generate_filename() {
		$version = defined( 'RTSB_VERSION' ) ? RTSB_VERSION : '';

		if ( defined( 'RTSBPRO_VERSION' ) ) {
			$version .= '-' . RTSBPRO_VERSION;
		}

		$hash = md5( $this->handle . $version . wp_json_encode( $this->source_files ) );

		return "$this->handle-$hash.min.$this->type";
	}

	/**
	 * Rewrites relative font URLs in CSS to absolute plugin URLs.
	 *
	 * @param string $css       The CSS content.
	 * @param string $base_path The base URL for resolving relative paths.
	 * @return string Modified CSS content with absolute font URLs.
	 */
	protected function fix_font_urls( string $css, string $base_path ) {
		$base_path = trailingslashit( $base_path );

		return preg_replace_callback(
			'/url\((["\']?)(?!https?:\/\/|\/)([^)]+?\.(?:eot|woff2?|ttf|svg|otf))(\?[^)]+)?\1\)/i',
			function ( $matches ) use ( $base_path ) {
				$relative_path = $matches[2];
				$query         = isset( $matches[3] ) ? $matches[3] : '';
				$absolute_url  = $base_path . ltrim( $relative_path, './\\' );
				return 'url("' . esc_url( $absolute_url . $query ) . '")';
			},
			$css
		);
	}

	/**
	 * Safely add file contents or path to the minifier.
	 *
	 * @param string $file     File path.
	 * @param object $minifier Minifier instance.
	 *
	 * @return void
	 */
	private function safely_add_file_to_minifier( $file, $minifier ) {
		if ( ! is_string( $file ) ) {
			return;
		}

		if ( ! is_readable( $file ) ) {
			error_log( "[ShopBuilder Minify] Skipping unreadable file: $file" ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log

			return;
		}

		if ( 'css' === $this->type ) {
			$content = @file_get_contents( $file ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged, WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents

			if ( false === $content ) {
				error_log( "[ShopBuilder Minify] Failed to read file: $file" ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log

				return;
			}

			$base_url  = dirname( plugins_url( '', $file ), 2 );
			$processed = $this->fix_font_urls( $content, $base_url );

			$minifier->add( $processed );
		} else {
			$minifier->add( $file );
		}
	}
}
