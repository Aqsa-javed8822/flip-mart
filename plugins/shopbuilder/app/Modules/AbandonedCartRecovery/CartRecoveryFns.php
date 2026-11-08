<?php
/**
 * Sticky add-to-cart Functions Class.
 *
 * @package Rse\SB
 */

namespace RadiusTheme\SB\Modules\AbandonedCartRecovery;

use DateInterval;
use DatePeriod;
use DateTime;
use RadiusTheme\SB\Helpers\Fns;

defined( 'ABSPATH' ) || exit();

/**
 * Sticky add-to-cart Functions Class.
 */
class CartRecoveryFns {
	/**
	 * Database table name for storing abandoned cart data.
	 *
	 * @var string
	 */
	public static $ca_abandonment = 'rtsb_ca_cart_abandonment';
	/**
	 * Database table name for storing meta information for each email template.
	 *
	 * @var string
	 */
	public static $ca_abandonment_meta = 'rtsb_ca_cart_abandonment_meta';

	/**
	 * Database table name for storing email templates related to cart recovery.
	 *
	 * @var string
	 */
	public static $ca_email = 'rtsb_ca_email_templates';

	/**
	 * Database table name for storing meta information for each email template.
	 *
	 * @var string
	 */
	public static $ca_email_meta = 'rtsb_ca_email_templates_meta';

	/**
	 * Database table name for storing email history, including sent status and schedule.
	 *
	 * @var string
	 */
	public static $ca_email_history = 'rtsb_ca_email_history';
	/**
	 * @param string       $key Default Attribute.
	 * @param array|string $default Default.
	 * @return array|string
	 */
	public static function get_options( $key = null, $default = '' ) {
		$options = Fns::get_options( 'modules', 'abandoned_cart_recovery' );
		if ( $key ) {
			if ( isset( $options[ $key ] ) ) {
				return $options[ $key ];
			} else {
				return $default;
			}
		}
		return $options;
	}
	/**
	 * Check whether the current user's role is excluded from abandonment tracking.
	 *
	 * @return bool True when the current user has a role listed in the
	 *              "Exclude User Roles from Tracking" setting.
	 */
	public static function is_tracking_excluded_for_current_user() {
		$disabled_roles = self::get_options( 'disable_tracking_roles', [] );
		if ( empty( $disabled_roles ) ) {
			return false;
		}
		$roles = (array) wp_get_current_user()->roles;
		return ! empty( array_intersect( $roles, (array) $disabled_roles ) );
	}
	/**
	 * @param int $abandonment_id abandonment id.
	 * @return void
	 */
	public static function unsebscribe( $abandonment_id ) {
		CartRecoveryDB::updateCaAbandonmentById( $abandonment_id, [ 'unsubscribed' => 1 ] );
		CartRecoveryDB::delete_unnecessery_email_history_for_abandonment( $abandonment_id );
	}
	/**
	 * Sanitize abandonment data before DB insert/update.
	 *
	 * @param array $data Raw data.
	 * @return array Sanitized data.
	 * @since 1.0.0
	 */
	public static function sanitize_abandonment_data( $data = [] ) {
		if ( ! is_array( $data ) ) {
			return [];
		}
		$sanitized = [];
		unset( $data['meta_data'], $data['abandonment_id'] );
		foreach ( $data as $key => $value ) {
			switch ( $key ) {
				case 'email':
					$sanitized[ $key ] = sanitize_email( $value );
					break;
				case 'cart_total':
					$sanitized[ $key ] = html_entity_decode( wp_strip_all_tags( wc_price( $value ) ) );
					break;
				case 'checkout_id':
					$sanitized[ $key ] = absint( $value );
					break;
				case 'cart_contents':
				case 'other_fields':
					$sanitized[ $key ] = maybe_serialize( $value );
					break;
				case 'unsubscribed':
					$sanitized[ $key ] = intval( $value );
					break;
				case 'time':
					$sanitized['time'] = gmdate( 'Y-m-d H:i:s', Fns::currentTimestampUTC() );
					break;
				default:
					$sanitized[ $key ] = sanitize_text_field( $value );
					break;
			}
		}
		return $sanitized;
	}
	/**
	 * Create WooCommerce coupon from template data
	 *
	 * @param array $template Template data with coupon meta fields.
	 * @param array $abandonment abandonment.
	 * @return string CouponCode.
	 */
	public static function create_wc_coupon_from_template( $template, $abandonment ) {
		if ( empty( $template['coupon_enabled'] ) || 'on' !== $template['coupon_enabled'] ) {
			return '';
		}

		// Generate coupon code.
		$prefix      = ! empty( $template['coupon_prefix'] ) ? $template['coupon_prefix'] : '';
		$random_code = wp_generate_password( 6, false );
		$coupon_code = strtoupper( sanitize_title( $prefix . $random_code ) );
		// Create coupon post.
		$coupon = [
			'post_title'   => $coupon_code,
			'post_content' => '',
			'post_status'  => 'publish',
			'post_author'  => get_current_user_id(),
			'post_type'    => 'shop_coupon',
		];

		$coupon_id = wp_insert_post( $coupon );

		if ( is_wp_error( $coupon_id ) ) {
			return $coupon_code;
		}
		// Set coupon meta.
		update_post_meta( $coupon_id, 'discount_type', $template['coupon_discount_type'] ?? 'fixed' ); // or 'percentage''.
		update_post_meta( $coupon_id, 'coupon_amount', $template['coupon_amount'] ?? 0 );
		update_post_meta( $coupon_id, 'individual_use', 'on' === $template['coupon_individual_use'] ? 'yes' : 'no' );
		update_post_meta( $coupon_id, 'free_shipping', 'on' === $template['coupon_free_shipping'] ? 'yes' : 'no' );

		// ✅ Add usage limits
		update_post_meta( $coupon_id, 'usage_limit', 1 );       // Limit per coupon.
		update_post_meta( $coupon_id, 'usage_limit_per_user', 1 ); // Limit per user.
		// ✅ Allowed emails (comma-separated or single).
		if ( ! empty( $abandonment['email'] ) ) {
			$emails = array_map( 'trim', explode( ',', $abandonment['email'] ) );
			update_post_meta( $coupon_id, 'customer_email', $emails );
		}

		$abandoned_cart = maybe_unserialize( $abandonment['cart_contents'] ?? [] );
		// ✅ Restrict coupon to selected products.
		if ( ! empty( $abandoned_cart ) ) {
			$products = [];
			foreach ( $abandoned_cart as $cart_item ) {
				$products[] = absint( $cart_item['product_id'] );
			}
			update_post_meta( $coupon_id, 'product_ids', $products );
		}
		// email.
		update_post_meta( $coupon_id, 'coupon_created_by_ca_abandonment', 'yes' );
		update_post_meta( $coupon_id, 'coupon_created_abandonment_id', $abandonment['id'] );

		// Expiry date: current time + days.
		if ( ! empty( $template['coupon_expiration'] ) ) {
			$expiry = strtotime( '+' . absint( $template['coupon_expiration'] ) . ' days' );
			update_post_meta( $coupon_id, 'date_expires', $expiry );
		}
		// Auto apply flag (custom, if your system supports it).
		if ( ! empty( $template['coupon_auto_apply'] ) && 'on' === $template['coupon_auto_apply'] ) {
			update_post_meta( $coupon_id, '_auto_apply', 'yes' );
		}
		return $coupon_code;
	}
	/**
	 * Check if a given time is between start and end dates.
	 *
	 * @param string $time  The time to check (e.g., '2025-10-13 09:55:56').
	 * @param string $start Start date (e.g., '2025-10-01').
	 * @param string $end   End date (e.g., '2025-10-14').
	 *
	 * @return bool True if $time is within the range, false otherwise.
	 */
	public static function is_time_between( $time, $start, $end ) {
		$t = strtotime( $time );
		return ( $t >= strtotime( $start ) && $t <= strtotime( $end ) );
	}
	/**
	 * Count total items within a date range using cached meta lookups.
	 *
	 * @param array  $items List of abandonment items (each with 'id' and 'scheduled_time').
	 * @param string $start Start date (Y-m-d H:i:s).
	 * @param string $end   End date (Y-m-d H:i:s).
	 *
	 * @return int Total count of items within the given range.
	 */
	public static function count_recoverable_items( $items, $start, $end ) {
		static $meta_cache = [];
		$startTime         = strtotime( $start );
		$endTime           = strtotime( $end );
		$count             = 0;
		foreach ( $items as $row ) {
			if ( empty( $row['scheduled_times'] ) ) {
				continue;
			}
			// Use static cache for meta lookups.
			if ( ! isset( $meta_cache[ $row['id'] ]['completed_time'] ) ) {
				$meta_cache[ $row['id'] ]['completed_time'] = CartRecoveryDB::get_abandonment_meta( $row['id'], 'completed_time' );
			}
			if ( ! isset( $meta_cache[ $row['id'] ]['lost_time'] ) ) {
				$meta_cache[ $row['id'] ]['lost_time'] = CartRecoveryDB::get_abandonment_meta( $row['id'], 'lost_time' );
			}
			$completed_time = $meta_cache[ $row['id'] ]['completed_time'] ? strtotime( $meta_cache[ $row['id'] ]['completed_time'] ) : false;
			$lost_time      = $meta_cache[ $row['id'] ]['lost_time'] ? strtotime( $meta_cache[ $row['id'] ]['lost_time'] ) : false;
			// Skip if completed before end time.
			if ( $completed_time && $completed_time < $endTime ) {
				continue;
			}
			// Skip if lost before end time.
			if ( $lost_time && $lost_time < $endTime ) {
				continue;
			}
			$times = array_values( $row['scheduled_times'] );
			foreach ( $times as $time ) {
				if ( $time >= $startTime && $time <= $endTime ) {
					$count++;
					break;
				}
			}
		}
		return $count;
	}
	/**
	 * Build chart data for recoverable and recovered orders within a date range.
	 *
	 * @param array  $recoverableOrderMeta Recoverable order meta data (with 'time' key).
	 * @param array  $recoveredOrderMeta   Recovered order meta data (with 'meta_value' key).
	 * @param string $start                Start date (Y-m-d H:i:s).
	 * @param string $end                  End date (Y-m-d H:i:s).
	 *
	 * @return array Formatted chart data with date, recoverable, and recovered counts.
	 */
	public static function get_chart_data( $recoverableOrderMeta, $recoveredOrderMeta, $start, $end ) {
		$chartData = [];
		// Generate all dates between start and end.
		$period         = new DatePeriod(
			new DateTime( $start ),
			new DateInterval( 'P1D' ),
			( new DateTime( $end ) )
		);
		$startFormatted = gmdate( 'M d Y H:i:s', strtotime( $start ) );
		foreach ( $period as $dateObj ) {
			$date               = $dateObj->format( 'M d Y' );
			$endDateFormatted   = $dateObj->format( 'M d Y 23:59:59' );
			$count              = self::count_recoverable_items( $recoverableOrderMeta, $startFormatted, $endDateFormatted );
			$chartData[ $date ] = [
				'date'        => $date,
				'recoverable' => $count,
				'recovered'   => 0,
			];
		}
		// Count recovered per date.
		foreach ( $recoveredOrderMeta as $row ) {
			if ( empty( $row['meta_value'] ) ) {
				continue;
			}
			$date = gmdate( 'M d Y', strtotime( $row['meta_value'] ) );
			if ( isset( $chartData[ $date ] ) ) {
				$chartData[ $date ]['recovered']++;
			}
		}
		// Sort and return.
		uksort( $chartData, fn( $a, $b ) => strtotime( $a ) <=> strtotime( $b ) );
		return array_values( $chartData );
	}

	/**
	 * Calculate scheduled timestamp based on frequency.
	 *
	 * @param int    $currentTime    Current timestamp.
	 * @param int    $frequency      Frequency value.
	 * @param string $frequency_unit Unit (MINUTE, HOUR, DAY).
	 *
	 * @return int Scheduled timestamp or 0.
	 */
	public static function calculate_schedule_timestamp( $currentTime, $frequency, $frequency_unit ) {
		$frequency      = absint( $frequency );
		$frequency_unit = strtoupper( (string) $frequency_unit );
		switch ( $frequency_unit ) {
			case 'MINUTE':
				return $currentTime + ( $frequency * MINUTE_IN_SECONDS );
			case 'HOUR':
				return $currentTime + ( $frequency * HOUR_IN_SECONDS );
			case 'DAY':
				return $currentTime + ( $frequency * DAY_IN_SECONDS );
			default:
				return 0;
		}
	}
	/**
	 * Prepare email history data for templates.
	 *
	 * @param array    $templates      Email templates.
	 * @param int      $abandonment_id Abandonment ID.
	 * @param int|null $currentTime    Current timestamp.
	 *
	 * @return array History data and max scheduled time.
	 */
	public static function prepare_email_history_data( $templates, $abandonment_id, $currentTime = null ) {
		if ( empty( $templates ) ) {
			return [
				'history'       => [],
				'max_scheduled' => null,
			];
		}
		$currentTime  = $currentTime ?? Fns::currentTimestampUTC();
		$historyData  = [];
		$maxScheduled = null;
		foreach ( $templates as $template ) {
			$scheduleTimestamp = self::calculate_schedule_timestamp(
				$currentTime,
				$template['frequency'],
				$template['frequency_unit']
			);
			if ( ! $scheduleTimestamp ) {
				continue;
			}
			$scheduleFormatted = gmdate( 'Y-m-d H:i:s', $scheduleTimestamp );
			$historyData[]     = [
				'template_id'    => $template['id'],
				'abandonment_id' => $abandonment_id,
				'coupon_code'    => '',
				'scheduled_time' => $scheduleFormatted,
				'email_sent'     => 0,
			];
			if ( ! $maxScheduled || $scheduleTimestamp > $maxScheduled ) {
				$maxScheduled = $scheduleTimestamp;
			}
		}
		return [
			'history'       => $historyData,
			'max_scheduled' => $maxScheduled,
		];
	}
}
