<?php
/**
 * Sticky Abandoned Cart Recovery Module Class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Modules\AbandonedCartRecovery;

use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Traits\SingletonTrait;


defined( 'ABSPATH' ) || exit();

/**
 * Sticky add-to-cart Module Class.
 */
class CartRecoveryCron {
	/**
	 * Singleton Trait.
	 */
	use SingletonTrait;

	/**
	 * Create custom schedule.
	 *
	 * @param array $schedules schedules.
	 * @return mixed
	 */
	public function abandoned_cart_cron( $schedules ) {
		$time         = CartRecoveryFns::get_options( 'abandoned_time', 20 );
		$cron_time    = apply_filters( 'rtsb/ca/abandoned/time/interval', $time );
		$schedule_key = 'abandoned_time_in_minutes_' . $cron_time;
		if ( ! isset( $schedules[ $schedule_key ] ) ) {
			$schedules[ $schedule_key ] = [
				'interval' => absint( $cron_time ) * MINUTE_IN_SECONDS,
				'display'  => __( 'Abandoned Time In Minutes', 'shopbuilder' ),
			];
		}
		// Register the existing cron event's schedule to prevent reschedule errors.
		$existing_event = wp_get_scheduled_event( 'rtsb_abandoned_cart_recovery_action' );
		if ( $existing_event && ! empty( $existing_event->schedule ) && ! isset( $schedules[ $existing_event->schedule ] ) ) {
			$schedules[ $existing_event->schedule ] = [
				'interval' => ! empty( $existing_event->interval ) ? $existing_event->interval : absint( $cron_time ) * MINUTE_IN_SECONDS,
				'display'  => __( 'Abandoned Time In Minutes', 'shopbuilder' ),
			];
		}
		if ( ! isset( $schedules['every_minute'] ) ) {
			$schedules['every_minute'] = [
				'interval' => 60,
				'display'  => __( 'Every Minute', 'shopbuilder' ),
			];
		}
		return $schedules;
	}

	/**
	 * Run Cron Function
	 *
	 * @return void
	 */
	public function run_cron() {
		$recovery_action = 'rtsb_abandoned_cart_recovery_action';
		$time            = CartRecoveryFns::get_options( 'abandoned_time', 20 );
		$cron_time       = apply_filters( 'rtsb/ca/abandoned/time/interval', $time );
		$schedule_key    = 'abandoned_time_in_minutes_' . $cron_time;
		$existing_event  = wp_get_scheduled_event( $recovery_action );

		// Clear and reschedule if the schedule key has changed.
		if ( $existing_event && $existing_event->schedule !== $schedule_key ) {
			wp_clear_scheduled_hook( $recovery_action );
			$existing_event = false;
		}

		if ( ! $existing_event ) {
			wp_schedule_event( time(), $schedule_key, $recovery_action );
			Fns::add_to_scheduled_hook_list( $recovery_action );
		}

		/**
		 * Ensure the cron event is scheduled.
		 */
		$abandoned_cart_emails = 'rtsb_send_abandoned_cart_emails';
		if ( ! wp_next_scheduled( $abandoned_cart_emails ) ) {
			wp_schedule_event( time(), 'every_minute', $abandoned_cart_emails );
		}
	}
	/**
	 * Detect Abandoned Cart Recovery
	 */
	public function detect_abandoned_cart_recovery() {
		CartRecoveryDB::update_to_abandoned();
		CartRecoveryDB::lost_abandonment_detection();
		self::delete_expired_coupon();
	}
	/**
	 * Detect Abandoned Cart Recovery
	 */
	public function delete_expired_coupon() {
		$delete_coupons = CartRecoveryFns::get_options( 'delete_coupons_automatically', '' );
		if ( 'on' !== $delete_coupons ) {
			return;
		}
		$args            = [
			'post_type'      => 'shop_coupon',
			'posts_per_page' => -1,
			'post_status'    => 'publish',
			'fields'         => 'ids',
			'meta_query'     => [ // phpcs:ignore WordPress.DB.SlowDBQuery
				'relation' => 'AND',
				[
					'key'   => 'coupon_created_by_ca_abandonment',
					'value' => 'yes',
				],
				[
					'key'     => 'date_expires',
					'value'   => Fns::currentTimestampUTC(),
					'compare' => '<',
					'type'    => 'NUMERIC',
				],
			],
		];
		$expired_coupons = get_posts( $args );
		if ( ! empty( $expired_coupons ) ) {
			foreach ( $expired_coupons as $coupon_id ) {
				wp_delete_post( $coupon_id, true ); // true = force delete.
			}
		}
	}
	/**
	 * Detect Abandoned Cart Recovery
	 */
	public function process_abandoned_cart_emails() {
		$emails_history = CartRecoveryDB::get_scheduled_email_history();
		if ( empty( $emails_history ) ) {
			return;
		}
		$recovery_email = RecoveryEmail::instance();
		foreach ( $emails_history as $history ) {
			$recovery = $recovery_email->send_recovery_email( $history );
			if ( $recovery ) {
				CartRecoveryDB::update_scheduled_email_history( $history['id'], [ 'email_sent' => 1 ] );
			} else {
				CartRecoveryDB::update_scheduled_email_history( $history['id'], [ 'email_sent' => -1 ] );
			}
		}
	}
}
