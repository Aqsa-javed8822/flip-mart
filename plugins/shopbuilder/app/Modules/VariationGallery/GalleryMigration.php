<?php
/**
 * Batched migration of the legacy variation gallery meta into WooCommerce's
 * native per-variation gallery.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Modules\VariationGallery;

use RadiusTheme\SB\Traits\SingletonTrait;

defined( 'ABSPATH' ) || exit();

/**
 * Copies `rtsb_vg_images` into WooCommerce's `_product_image_gallery` variation meta
 * so the native gallery becomes the single source of truth once WooCommerce provides
 * it.
 *
 * The legacy meta is deliberately left in place: it keeps CSV export/import and any
 * third-party reader working, and lets a store roll back to an older WooCommerce
 * without losing data. A sentinel meta marks each processed variation instead, so the
 * legacy value is never used to resurrect images the merchant has since removed.
 *
 * The work runs through Action Scheduler (shipped with WooCommerce, so no new
 * dependency) in small batches: it survives the admin closing the page or logging out,
 * never blocks a request, and resumes safely after an interrupted batch.
 */
class GalleryMigration {

	/**
	 * Singleton Trait.
	 */
	use SingletonTrait;

	/**
	 * Option recording when the migration finished.
	 *
	 * @var string
	 */
	const COMPLETED_OPTION = 'rtsb_vg_native_gallery_migration_completed_at';

	/**
	 * Action Scheduler hook running a single batch.
	 *
	 * @var string
	 */
	const BATCH_HOOK = 'rtsb_vg_migrate_variation_gallery_batch';

	/**
	 * Action Scheduler group.
	 *
	 * @var string
	 */
	const BATCH_GROUP = 'rtsb-variation-gallery';

	/**
	 * Number of variations processed per batch.
	 *
	 * @var int
	 */
	const BATCH_SIZE = 250;

	/**
	 * Transient guarding against two runners entering a batch at once.
	 *
	 * @var string
	 */
	const LOCK_TRANSIENT = 'rtsb_vg_migration_running';

	/**
	 * Register hooks.
	 */
	private function __construct() {
		add_action( self::BATCH_HOOK, [ __CLASS__, 'run_batch' ] );
		// Action Scheduler only becomes available on `init`.
		add_action( 'init', [ $this, 'maybe_schedule' ], 21 );
	}

	/**
	 * Queue the next batch unless the migration is done or one is already queued.
	 *
	 * @return void
	 */
	public function maybe_schedule() {
		if ( ! GalleryFns::use_native_gallery() || get_option( self::COMPLETED_OPTION ) ) {
			return;
		}

		$queue = self::get_queue();

		if ( ! $queue || null !== $queue->get_next( self::BATCH_HOOK, [], self::BATCH_GROUP ) ) {
			return;
		}

		$queue->add( self::BATCH_HOOK, [], self::BATCH_GROUP );
	}

	/**
	 * Migrate one batch of variations and queue the next one when work remains.
	 *
	 * Idempotent by construction: the batch query only selects variations that still
	 * lack the sentinel, so a batch that dies part-way simply leaves the remainder to
	 * be picked up on the next run — nothing is processed twice and nothing is skipped.
	 *
	 * @return void
	 */
	public static function run_batch() {
		if ( ! GalleryFns::use_native_gallery() || get_option( self::COMPLETED_OPTION ) ) {
			return;
		}

		// Two runners racing on the same batch would each write the same values, but
		// the lock keeps them from duplicating the queued follow-up batch as well.
		if ( get_transient( self::LOCK_TRANSIENT ) ) {
			return;
		}

		set_transient( self::LOCK_TRANSIENT, 'yes', 5 * MINUTE_IN_SECONDS );

		try {
			$variation_ids = self::get_pending_variation_ids( self::BATCH_SIZE );

			foreach ( $variation_ids as $variation_id ) {
				self::migrate_variation( $variation_id );
			}

			$has_more = (bool) self::get_pending_variation_ids( 1 );
		} finally {
			delete_transient( self::LOCK_TRANSIENT );
		}

		if ( $has_more ) {
			$queue = self::get_queue();

			if ( $queue ) {
				$queue->add( self::BATCH_HOOK, [], self::BATCH_GROUP );
			}

			return;
		}

		if ( ! get_option( self::COMPLETED_OPTION ) ) {
			update_option( self::COMPLETED_OPTION, time(), false );
		}
	}

	/**
	 * Copy one variation's legacy gallery into the native gallery.
	 *
	 * The native value wins whenever it already holds images, so a merchant who has
	 * authored through WooCommerce before the migration ran is never overwritten.
	 *
	 * @param int $variation_id Variation ID.
	 *
	 * @return void
	 */
	protected static function migrate_variation( $variation_id ) {
		$variation_id = absint( $variation_id );
		$variation    = $variation_id ? wc_get_product( $variation_id ) : false;

		if ( ! $variation || ! $variation->is_type( 'variation' ) ) {
			return;
		}

		/*
		 * Re-check ownership at write time, not just in the batch query.
		 *
		 * A batch selects up to BATCH_SIZE IDs in one statement and then processes them
		 * one by one, so a merchant can save this variation in between. Were they to
		 * empty its gallery in that window, the native store would be legitimately empty
		 * while the legacy meta still held the old list — and the write below would put
		 * the removed images straight back.
		 */
		if ( GalleryFns::is_native_gallery_owned( $variation_id ) ) {
			return;
		}

		$legacy_ids = GalleryFns::get_legacy_variation_gallery_ids( $variation_id );
		$native_ids = GalleryFns::get_native_variation_gallery_ids( $variation );

		if ( empty( $native_ids ) && ! empty( $legacy_ids ) ) {
			$variation->set_gallery_image_ids( $legacy_ids );
			$variation->save();
		}

		GalleryFns::mark_native_gallery_owned( $variation_id );

		// The cached props were built from the legacy list; drop them so the next read
		// rebuilds from the native gallery.
		GalleryFns::delete_transients( $variation_id, 'variation-images' );
	}

	/**
	 * Fetch variation IDs that still carry an unmigrated legacy gallery.
	 *
	 * @param int $limit Maximum number of IDs to return.
	 *
	 * @return array
	 */
	protected static function get_pending_variation_ids( $limit ) {
		global $wpdb;

		$query = $wpdb->prepare(
			"SELECT legacy.post_id
			FROM {$wpdb->postmeta} AS legacy
			INNER JOIN {$wpdb->posts} AS posts
				ON posts.ID = legacy.post_id
				AND posts.post_type = 'product_variation'
			LEFT JOIN {$wpdb->postmeta} AS migrated
				ON migrated.post_id = legacy.post_id
				AND migrated.meta_key = %s
			WHERE legacy.meta_key = %s
				AND legacy.meta_value <> ''
				AND legacy.meta_value <> 'a:0:{}'
				AND migrated.post_id IS NULL
			GROUP BY legacy.post_id
			ORDER BY legacy.post_id ASC
			LIMIT %d",
			GalleryFns::NATIVE_GALLERY_SENTINEL_META_KEY,
			GalleryFns::LEGACY_GALLERY_META_KEY,
			absint( $limit )
		);

		// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Prepared immediately above; a migration scan must not be cached.
		return array_map( 'absint', $wpdb->get_col( $query ) );
	}

	/**
	 * Resolve WooCommerce's Action Scheduler queue when it is available.
	 *
	 * @return \WC_Queue_Interface|null
	 */
	protected static function get_queue() {
		if ( ! function_exists( 'WC' ) || ! method_exists( WC(), 'queue' ) ) {
			return null;
		}

		return WC()->queue();
	}
}
