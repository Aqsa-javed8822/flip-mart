<?php
/**
 * Sticky Abandoned Cart Recovery Module Class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Modules\AbandonedCartRecovery;

use DateTime;
use Exception;
use RadiusTheme\SB\Helpers\Fns;

defined( 'ABSPATH' ) || exit();

/**
 * Sticky add-to-cart Module Class.
 */
class CartRecoveryDB {
	/**
	 * Parse GROUP_CONCAT meta_data into associative array.
	 *
	 * @param array $abandonments Single or multiple abandonment records.
	 * @return array Abandonments with 'meta' array.
	 * @since 1.0.0
	 */
	private static function parseMetaData( array $abandonments ): array {
		return array_map(
			function ( $abandonment ) {
				if ( ! empty( $abandonment['meta_data'] ) ) {
					$abandonment['meta'] = [];
					foreach ( explode( ',', $abandonment['meta_data'] ) as $pair ) {
						[ $key, $value ]             = explode( ':', $pair, 2 );
						$abandonment['meta'][ $key ] = $value;
					}
				}
				unset( $abandonment['meta_data'] );
				if ( isset( $abandonment['other_fields'] ) ) {
					$abandonment['other_fields'] = maybe_unserialize( $abandonment['other_fields'] );
				}
				return $abandonment;
			},
			$abandonments
		);
	}
	/**
	 * Insert meta for a given table and ID.
	 *
	 * @param string $table       Table name.
	 * @param string $id_field    ID field name (e.g., 'abandonment_id' or 'email_template_id').
	 * @param int    $id          The ID value.
	 * @param string $key         Meta key.
	 * @param mixed  $value       Meta value.
	 * @return void
	 */
	public static function insert_meta( $table, $id_field, $id, $key, $value ) {
		$data = [
			$id_field    => absint( $id ),
			'meta_key'   => $key, // phpcs:ignore WordPress.DB.SlowDBQuery
			'meta_value' => maybe_serialize( $value ), // phpcs:ignore WordPress.DB.SlowDBQuery
		];
		Fns::DB()::insert( $table, [ $data ] )->execute();
	}

	/**
	 * Update meta for a given table and ID.
	 *
	 * @param string $table       Table name.
	 * @param string $id_field    ID field name (e.g., 'abandonment_id' or 'email_template_id').
	 * @param int    $id          The ID value.
	 * @param string $key         Meta key.
	 * @param mixed  $value       Meta value.
	 * @return void
	 */
	public static function update_meta( $table, $id_field, $id, $key, $value ) {
		$data = [
			'meta_value' => maybe_serialize( $value ), // phpcs:ignore WordPress.DB.SlowDBQuery
		];
		Fns::DB()::update( $table, $data )
			->where( $id_field, '=', absint( $id ) )
			->andWhere( 'meta_key', '=', $key )
			->execute();
	}
	/**
	 * Insert or update meta for a given table and ID.
	 *
	 * @param string $table       Table name.
	 * @param string $id_field    ID field name (e.g., 'abandonment_id' or 'email_template_id').
	 * @param int    $id          The ID value.
	 * @param string $key         Meta key.
	 * @param mixed  $value       Meta value.
	 * @return void
	 */
	public static function upsert_meta( $table, $id_field, $id, $key, $value ) {
		$exists = Fns::DB()::select( 'id' )
			->from( $table )
			->where( $id_field, '=', absint( $id ) )
			->andWhere( 'meta_key', '=', $key )
			->get();
		if ( ! empty( $exists[0] ) ) {
			// Update existing.
			self::update_meta( $table, $id_field, $id, $key, $value );
		} else {
			// Insert new.
			self::insert_meta( $table, $id_field, $id, $key, $value );
		}
	}
	/**
	 * @param array $templates Array of templates.
	 * @return void
	 */
	public static function createTemplates( $templates = [] ) {
		if ( empty( $templates ) ) {
			return;
		}
		foreach ( $templates as $template ) {
			$id = $template['id'] ?? 0;
			unset( $template['id'] );
			// Check if the ID already exists.
			$exists = Fns::DB()::select( 'id' )
				->from( CartRecoveryFns::$ca_email )
				->where( 'id', '=', $id )
				->get();
			if ( $exists ) {
				// Update existing row.
				Fns::DB()::update( CartRecoveryFns::$ca_email, $template )->where( 'id', '=', $id )->execute();
			} else {
				Fns::DB()::insert( CartRecoveryFns::$ca_email, [ $template ] )->execute();
			}
		}
	}

	/**
	 * Apply coupon meta fields to a template
	 *
	 * @param array $template Template data with parsed meta.
	 * @return array Template with coupon-related fields.
	 */
	private static function templatesOtherFields( $template ) {
		if ( ! empty( $template['other_fields'] ) ) {
			$template = array_merge( $template, $template['other_fields'] );
			unset( $template['other_fields'] );
		}
		return $template;
	}
	/**
	 * Get all email templates from database
	 *
	 * @return array Array of template objects
	 */
	public static function getAllTemplates() {
		$order_by = 'menu_order';
		$order    = 'ASC';
		// Validate order direction to prevent SQL injection.
		$order = strtoupper( $order ) === 'DESC' ? 'DESC' : 'ASC';
		// Validate order_by column to prevent SQL injection.
		$allowed_columns = [ 'id', 'title', 'email_subject', 'is_activated', 'frequency', 'menu_order', 'created_at' ];
		$order_by        = in_array( $order_by, $allowed_columns, true ) ? $order_by : 'menu_order';
		try {
			$templates = Fns::DB()::select()
				->raw( 't.*, GROUP_CONCAT(CONCAT(m.meta_key, ":", m.meta_value) SEPARATOR ",") AS meta_data' )
				->from( CartRecoveryFns::$ca_email . ' t' )
				->leftJoin( CartRecoveryFns::$ca_email_meta . ' m', 't.id', 'm.email_template_id' )
				->groupBy( 't.id' )
				->orderBy( $order_by, $order )
				->get();
			$templates = ! empty( $templates ) ? self::parseMetaData( $templates ) : [];
			$data      = [];
			if ( ! empty( $templates ) ) {
				foreach ( $templates as $template ) {
					$data[] = self::templatesOtherFields( $template );
				}
			}
			return $data;
		} catch ( Exception $e ) {
			return [];
		}
	}

	/**
	 * Get template by ID
	 *
	 * @param int $template_id Template ID.
	 * @return object|null Template object or null if not found
	 */
	/**
	 * Get template by ID with meta data
	 *
	 * @param int $template_id Template ID.
	 * @return array|null Template data with meta or null if not found
	 */
	public static function getTemplateById( $template_id ) {
		try {
			$template = Fns::DB()::select()
				->raw( 't.*, GROUP_CONCAT(CONCAT(m.meta_key, ":", m.meta_value) SEPARATOR ",") AS meta_data' )
				->from( CartRecoveryFns::$ca_email . ' t' )
				->leftJoin( CartRecoveryFns::$ca_email_meta . ' m', 't.id', 'm.email_template_id' )
				->where( 't.id', '=', absint( $template_id ) )
				->groupBy( 't.id' )
				->get();

			if ( empty( $template ) ) {
				return null;
			}
			// Parse meta data.
			$templates = self::parseMetaData( $template );
			$template  = $templates[0] ?? null;
			return $template ? self::templatesOtherFields( $template ) : null;
		} catch ( Exception $e ) {
			return null;
		}
	}

	/**
	 * Delete template by ID
	 *
	 * @param int $template_id Template ID.
	 * @return bool True if deleted successfully, false otherwise
	 */
	public static function deleteTemplate( $template_id ) {
		try {
			// Delete email history records.
			Fns::DB()::delete( CartRecoveryFns::$ca_email_history )
				->where( 'template_id', '=', $template_id )
				->execute();
			Fns::DB()::delete( CartRecoveryFns::$ca_email_meta )
				->where( 'email_template_id', '=', $template_id )
				->execute();
			return Fns::DB()::delete( CartRecoveryFns::$ca_email )
				->where( 'id', '=', $template_id )
				->execute();
		} catch ( Exception $e ) {
			return false;
		}
	}

	/**
	 * Get total number of abandoned carts.
	 *
	 * @since 1.0.0
	 */
	public static function getCartAbandonmentCount() {
		try {
			$count = Fns::DB()::select()
				->raw( 'COUNT(*) as total' )
				->from( CartRecoveryFns::$ca_abandonment )
				->get();

			return ! empty( $count[0]['total'] ) ? intval( $count[0]['total'] ) : 0;
		} catch ( Exception $e ) {
			return 0;
		}
	}

	/**
	 * Get all cart abandonment records with meta.
	 *
	 * @param int $limit Number of records per page.
	 * @param int $page Page number.
	 * @return array List of abandonment records with meta.
	 * @since 1.0.0
	 */
	public static function getAllCartAbandonment( int $limit = 50, int $page = 1 ): array {
		try {
			$offset       = ( $page - 1 ) * $limit;
			$abandonments = Fns::DB()::select()
				->raw( 't.*, GROUP_CONCAT(CONCAT(m.meta_key, ":", m.meta_value) SEPARATOR ",") AS meta_data' )
				->from( CartRecoveryFns::$ca_abandonment . ' t' )
				->leftJoin( CartRecoveryFns::$ca_abandonment_meta . ' m', 't.id', 'm.abandonment_id' )
				->groupBy( 't.id' )
				->orderBy( 't.id', 'DESC' )
				->limit( $limit )
				->offset( $offset )
				->get();

			if ( empty( $abandonments ) ) {
				return [];
			}

			$abandonments = self::parseMetaData( $abandonments );

			// Convert stored UTC `time` to the site timezone for display.
			foreach ( $abandonments as &$abandonment ) {
				if ( ! empty( $abandonment['time'] ) ) {
					$abandonment['time'] = get_date_from_gmt( $abandonment['time'], 'Y-m-d H:i:s' );
				}
			}
			unset( $abandonment );

			return $abandonments;
		} catch ( Exception $e ) {
			return [];
		}
	}

	/**
	 * Get all cart abandonment records with meta.
	 *
	 * @param string $startDate Number of records per page.
	 * @param string $endDate Page number.
	 * @return array number.
	 */
	public static function getRecoverableOrderData( $startDate, $endDate ) {
		$default = [
			'revenue' => '',
			'items'   => [],
			'count'   => 0,
		];
		if ( empty( $startDate ) || empty( $endDate ) ) {
			return $default;
		}
		try {
			$results = Fns::DB()::select()
				->raw( "t.*, SUM(CAST(REGEXP_REPLACE(t.cart_total, '[^0-9.]', '') AS DECIMAL(10,2))) AS total, REGEXP_REPLACE(t.cart_total, '[0-9.]+', '') AS currency, JSON_ARRAYAGG(JSON_OBJECT('scheduled_time', m.scheduled_time, 'template_id', m.template_id)) AS scheduled_times" )
				->from( CartRecoveryFns::$ca_abandonment . ' t' )
				->innerJoin( CartRecoveryFns::$ca_email_history . ' m', 't.id', 'm.abandonment_id' )
				->where( 'm.email_sent', '=', 1 )
				->andWhere( 'm.scheduled_time', '>=', $startDate )
				->andWhere( 'm.scheduled_time', '<=', $endDate )
				->groupBy( 't.id' )
				->get();

			$totals = [];
			$items  = [];
			foreach ( $results as $row ) {
				$currency = trim( $row['currency'] ?: '$' );
				if ( isset( $totals[ $currency ] ) ) {
					$totals[ $currency ] += floatval( $row['total'] );
				} else {
					$totals[ $currency ] = floatval( $row['total'] );
				}
				$scheduled_times = json_decode( $row['scheduled_times'], true );
				$times           = [];
				foreach ( $scheduled_times as &$st ) {
					$time           = ( new DateTime( $st['scheduled_time'] ) )->format( 'Y-m-d H:i:s' );
					$times[ $time ] = strtotime( $time );
				}
				$row['scheduled_times'] = $times;
				$items[]                = $row;
			}
			// Prepare final string with currency symbols.
			$revenue_parts = [];
			foreach ( $totals as $currency => $amount ) {
				$revenue_parts[] = $amount . $currency;
			}
			$revenue = implode( ' / ', $revenue_parts );
			return [
				'items'   => $items,
				'count'   => count( $items ),
				'revenue' => $revenue,
			];
		} catch ( Exception $e ) {
			return $default;
		}
	}
	/**
	 * Get recovered revenue grouped by currency and return as a single concatenated string.
	 *
	 * @param array $recoveredOrderMeta  Abandonment Meta.
	 * @return string e.g. "36.50-$, 30.50-€"
	 */
	public static function getRecoveredRevenueByRecoveredOrderMeta( $recoveredOrderMeta = [] ) {
		if ( empty( $recoveredOrderMeta ) ) {
			return '';
		}
		$ids = array_column( $recoveredOrderMeta, 'abandonment_id' );
		$ids = implode( ',', $ids );
		try {
			$query   = Fns::DB()::select()
				->raw( "SUM( CAST( REGEXP_REPLACE(cart_total, '[^0-9.]', '') AS DECIMAL(10,2)) ) as total, REGEXP_REPLACE(cart_total, '[0-9.]+', '') as currency" )
			->from( CartRecoveryFns::$ca_abandonment )
			->whereIn( 'id', $ids )
			->groupBy( 'currency' );
			$results = $query->get();
			$totals  = [];
			foreach ( $results as $row ) {
				$currency = trim( $row['currency'] ?: '$' );
				$totals[] = floatval( $row['total'] ) . $currency;
			}
			return implode( ' / ', $totals );
		} catch ( Exception $e ) {
			return '';
		}
	}

	/**
	 * Get a single cart abandonment record with meta by a specific field.
	 *
	 * @param string $field Database column name (e.g., 'id', 'email', 'ca_session_id').
	 * @param mixed  $value Value of the field to match.
	 * @return array Single abandonment record with meta, or empty array if not found.
	 * @since 1.0.0
	 */
	public static function getSingleAbandonment( string $field, $value ): array {
		try {
			$abandonments = Fns::DB()::select()
				->raw( 't.*, GROUP_CONCAT(CONCAT(m.meta_key, ":", m.meta_value) SEPARATOR ",") AS meta_data' )
				->from( CartRecoveryFns::$ca_abandonment . ' t' )
				->leftJoin( CartRecoveryFns::$ca_abandonment_meta . ' m', 't.id', 'm.abandonment_id' )
				->where( 't.' . $field, '=', $value )
				->groupBy( 't.id' )
				->orderBy( 't.id', 'DESC' )
				->get();
			if ( empty( $abandonments ) ) {
				return [];
			}
			$abandonments = self::parseMetaData( $abandonments );
			return $abandonments[0] ?? [];
		} catch ( Exception $e ) {
			return [];
		}
	}

	/**
	 * Get a cart abandonment record by ID.
	 *
	 * Retrieves a single abandonment record by its unique ID and includes meta data.
	 *
	 * @param int $id Abandonment ID.
	 * @return array Abandonment record with meta data. Empty array if not found.
	 * @since 1.0.0
	 */
	public static function getCaAbandonmentByID( int $id ): array {
		return self::getSingleAbandonment( 'id', absint( $id ) );
	}

	/**
	 * Get a cart abandonment record by user email.
	 *
	 * Retrieves a single abandonment record based on the email and includes meta data.
	 *
	 * @param string $email User email address.
	 * @return array Abandonment record with meta data. Empty array if not found or invalid email.
	 * @since 1.0.0
	 */
	public static function getCaAbandonmentByEmail( string $email ): array {
		$email = sanitize_email( $email );
		return ! empty( $email ) ? self::getSingleAbandonment( 'email', $email ) : [];
	}

	/**
	 * Get a cart abandonment record by session ID.
	 *
	 * Retrieves a single abandonment record based on the session ID and includes meta data.
	 *
	 * @param string $session_id User session ID.
	 * @return array Abandonment record with meta data. Empty array if not found or invalid session ID.
	 * @since 1.0.0
	 */
	public static function getCaAbandonmentBySessionId( string $session_id ): array {
		$session_id = sanitize_text_field( $session_id );
		return ! empty( $session_id ) ? self::getSingleAbandonment( 'ca_session_id', $session_id ) : [];
	}

	/**
	 * Delete checkout details and meta for a user session.
	 *
	 * @param string $session_id User session ID.
	 * @since 1.0.0
	 */
	public static function deleteCaAbandonmentBySessionId( $session_id ) {
		try {
			$session_id = sanitize_text_field( $session_id );
			// Get abandonment ID first.
			$abandonment    = self::getCaAbandonmentBySessionId( $session_id );
			$abandonment_id = $abandonment['id'] ?? 0;
			// Delete meta records.
			Fns::DB()::delete( CartRecoveryFns::$ca_abandonment_meta )
				->where( 'abandonment_id', '=', absint( $abandonment_id ) )
				->execute();
			// Delete email history records.
			Fns::DB()::delete( CartRecoveryFns::$ca_email_history )
				->where( 'abandonment_id', '=', $abandonment_id )
				->execute();
			if ( ! $abandonment_id ) {
				return false; // Nothing to delete.
			}
			// Delete main record.
			return Fns::DB()::delete( CartRecoveryFns::$ca_abandonment )
				->where( 'ca_session_id', '=', $session_id )
				->execute();
		} catch ( Exception $e ) {
			return false;
		}
	}
	/**
	 * Delete abandoned cart records for a given email with "normal" order status.
	 *
	 * This function looks up the abandoned cart sessions for the provided email
	 * where the order status is "normal" and deletes the corresponding record.
	 *
	 * @param string $email The customer's email address.
	 *
	 * @return bool|mixed Returns the result of the deletion if a session is found, or false if no session exists.
	 */
	public static function deleteNormalAbandonedByEmail( $email ) {
		$normal     = Fns::DB()::select( 'ca_session_id' )
			->from( CartRecoveryFns::$ca_abandonment )
			->where( 'order_status', '=', 'normal' )
			->andWhere( 'email', '=', $email )
			->get();
		$session_id = ! empty( $normal[0] ) ? $normal[0]['ca_session_id'] : '';
		if ( ! empty( $session_id ) ) {
			return self::deleteCaAbandonmentBySessionId( $session_id );
		}
		return false;
	}
	/**
	 * Get a cart abandonment record by ID.
	 *
	 * Retrieves a single abandonment record by its unique ID and includes meta data.
	 *
	 * @param int $id Abandonment ID.
	 * @since 1.0.0
	 */
	public static function deleteAbandonmentByID( $id ) {
		$abandonment = self::getSingleAbandonment( 'id', absint( $id ) );
		if ( ! empty( $abandonment['ca_session_id'] ) ) {
			self::deleteCaAbandonmentBySessionId( $abandonment['ca_session_id'] );
		}
		$abandonment = self::getSingleAbandonment( 'id', absint( $id ) );
		return empty( $abandonment );
	}
	/**
	 * Update an abandonment record in the database.
	 *
	 * Updates the specified record by applying the given key/value condition.
	 * Typically used with session ID or abandonment ID, but can work with
	 * any column name/value pair.
	 *
	 * @param string $whereKey       The database column name for the condition.
	 * @param mixed  $whereValue     The value to match against the column.
	 * @param array  $data           Key-value pairs of data to update.
	 *
	 * @return array Number of rows updated on success, or false on failure.
	 *
	 * @since 1.0.0
	 */
	public static function updateCaAbandonment( $whereKey, $whereValue, $data = [] ) {
		try {
			if ( empty( $whereKey ) || empty( $data ) ) {
				return [];
			}
			$sanitized_data = CartRecoveryFns::sanitize_abandonment_data( $data );
			unset( $sanitized_data['time'] );
			$query = Fns::DB()::update( CartRecoveryFns::$ca_abandonment, $sanitized_data );
			$query->where( $whereKey, '=', $whereValue )->execute();
			return self::getSingleAbandonment( $whereKey, $whereValue );
		} catch ( Exception $e ) {
			return [];
		}
	}
	/**
	 * Insert or update checkout details for a user session.
	 *
	 * @param array $data Data to insert/update.
	 * @since 1.0.0
	 */
	public static function insertCaAbandonment( $data ) {
		try {
			if ( empty( $data ) || ! is_array( $data ) ) {
				return false;
			}
			$meta_data      = $data['meta_data'] ?? [];
			$sanitized_data = CartRecoveryFns::sanitize_abandonment_data( $data );
			$result         = Fns::DB()::insert( CartRecoveryFns::$ca_abandonment, [ $sanitized_data ] )->execute();
			// Get abandonment ID for meta update.
			$abandonment_id = 0;
			$session_lookup = $sanitized_data['ca_session_id'] ?? '';
			if ( $session_lookup ) {
				$abandonment_id = self::getCaAbandonmentBySessionId( $session_lookup )['id'] ?? 0;
			}
			if ( $abandonment_id && ! empty( $meta_data ) ) {
				foreach ( $meta_data as $k => $val ) {
					self::update_ca_abandonment_meta( $abandonment_id, $k, $val );
				}
			}
			return $result;
		} catch ( Exception $e ) {
			return false;
		}
	}
	/**
	 * Update checkout details for a user session.
	 *
	 * @param string $session_id User session ID.
	 * @param array  $sanitized_data Data to update.
	 */
	public static function updateCaAbandonmentBySessionId( $session_id, $sanitized_data ) {
		return self::updateCaAbandonment( 'ca_session_id', sanitize_text_field( $session_id ), $sanitized_data );
	}

	/**
	 * Update checkout details for a user session.
	 *
	 * @param int   $id ID.
	 * @param array $sanitized_data Data to update.
	 */
	public static function updateCaAbandonmentById( $id, $sanitized_data ) {
		return self::updateCaAbandonment( 'id', absint( $id ), $sanitized_data );
	}

	/**
	 * Update checkout details for a user session.
	 *
	 * @param int $id ID.
	 */
	public static function get_abandonment_details( $id ) {
		$abandonment = self::getSingleAbandonment( 'id', absint( $id ) );
		if ( empty( $abandonment ) ) {
			return [
				'abandonment' => [],
				'email'       => [],
			];
		}
		$email                        = Fns::DB()::select()
			->raw( 't.*, m.*' )
			->from( CartRecoveryFns::$ca_email . ' t' )
			->leftJoin( CartRecoveryFns::$ca_email_history . ' m', 't.id', 'm.template_id' )
			->where( 'm.abandonment_id', '=', $id )
			->orderBy( 'm.scheduled_time', 'ASC' );
			$result                   = $email->get();
		$abandonment['cart_contents'] = maybe_unserialize( $abandonment['cart_contents'] );
		return [
			'abandonment' => $abandonment,
			'email'       => $result,
		];
	}

	/**
	 * Insert or update email abandonment meta.
	 *
	 * @param int    $abandonment_id abandonment ID.
	 * @param string $key         Meta key.
	 * @param mixed  $value       Meta value.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public static function update_ca_abandonment_meta( $abandonment_id, $key, $value ) {
		self::upsert_meta( CartRecoveryFns::$ca_abandonment_meta, 'abandonment_id', $abandonment_id, $key, $value );
	}
	/**
	 * Insert or update email abandonment meta.
	 *
	 * @param int    $abandonment_id abandonment ID.
	 * @param string $key         Meta key.
	 * @param mixed  $value       Meta value.
	 *
	 * @since 1.0.0
	 */
	public static function add_ca_abandonment_meta( $abandonment_id, $key, $value ) {
		self::insert_meta( CartRecoveryFns::$ca_abandonment_meta, 'abandonment_id', $abandonment_id, $key, $value );
	}

	/**
	 * Delete abandonment meta by key (and optional value).
	 *
	 * @param int         $abandonment_id Abandonment ID.
	 * @param string      $key            Meta key.
	 * @param string|null $value          Optional meta value to match.
	 * @return void
	 */
	public static function delete_abandonment_meta( $abandonment_id, $key, $value = null ) {
		$query = Fns::DB()::delete( CartRecoveryFns::$ca_abandonment_meta )
			->where( 'abandonment_id', '=', absint( $abandonment_id ) )
			->andWhere( 'meta_key', '=', $key );
		if ( $value ) {
			$query->andWhere( 'meta_value', '=', $value );
		}
		$query->execute();
	}
	/**
	 * Delete unnecessery email history
	 *
	 * @param int $abandonment_id Abandonment ID.
	 *
	 * @return bool
	 */
	public static function delete_unnecessery_email_history_for_abandonment( $abandonment_id ) {
		try {
			return Fns::DB()::delete( CartRecoveryFns::$ca_email_history )
				->where( 'abandonment_id', '=', $abandonment_id )
				->andWhere( 'email_sent', '=', 0 )
				->execute();
		} catch ( Exception $e ) {
			return false;
		}
	}
	/**
	 * Get abandoned cart data.
	 */
	public static function get_normal_abandoned() {
		$wp_current_datetime = gmdate( 'Y-m-d H:i:s', Fns::currentTimestampUTC() );
		$minutes             = absint( CartRecoveryFns::get_options( 'abandoned_time', 20 ) );
		$normal              = Fns::DB()::select( '*' )
			->from( CartRecoveryFns::$ca_abandonment )
			->where( 'order_status', '=', 'normal' )
			->raw( "AND ADDDATE(time, INTERVAL '{$minutes}' MINUTE) <= '{$wp_current_datetime}' " )
			->orderBy( 'id', 'DESC' )
			->get();
		return $normal;
	}

	/**
	 * Get active templates only
	 *
	 * @return array Array of active template objects.
	 */
	public static function getActiveTemplatesSecuence() {
		try {
			$templates = Fns::DB()::select( '*' )
				->from( CartRecoveryFns::$ca_email )
				->where( 'is_activated', '=', 'on' )
				->raw(
					"ORDER BY CASE UPPER(frequency_unit)
                            WHEN 'MINUTE' THEN 1
                            WHEN 'HOUR'   THEN 2
                            WHEN 'DAY'    THEN 3
                            ELSE 4
                        END ASC"
				)
				->get();
			return $templates ?: [];

		} catch ( Exception $e ) {
			return [];
		}
	}
	/**
	 * Scheduled Email
	 *
	 * @return mixed
	 */
	public static function get_scheduled_email_history() {
		$wp_current_datetime = gmdate( 'Y-m-d H:i:s', Fns::currentTimestampUTC() );
		return Fns::DB()::select( '*' )
			->from( CartRecoveryFns::$ca_email_history )
			->where( 'email_sent', '=', 0 )
			->andWhere( 'scheduled_time', '<=', $wp_current_datetime )
			->groupBy( 'abandonment_id' )
			->orderBy( 'scheduled_time', 'ASC' )
			->get();
	}

	/**
	 * Get an email history record by ID.
	 *
	 * @param int $id Record ID.
	 * @return array Record object or null.
	 */
	public static function get_history_by_id( $id ) {
		$result = Fns::DB()::select( '*' )
			->from( CartRecoveryFns::$ca_email_history )
			->where( 'id', '=', $id )
			->get();
		return $result[0] ?? [];
	}
	/**
	 * @param int   $history_id Email History ID.
	 * @param array $data update column.
	 * @return void
	 */
	public static function update_scheduled_email_history( $history_id, $data ) {
		Fns::DB()::update( CartRecoveryFns::$ca_email_history, $data )->where( 'id', '=', absint( $history_id ) )->execute();
	}

	/**
	 * @param int    $template_id Email History ID.
	 * @param string $meta_key Meta Key.
	 * @param mixed  $default default value.
	 * @return mixed
	 */
	public static function get_email_template_meta( $template_id, $meta_key, $default = null ) {
		$getMetaData = Fns::DB()::select( 'meta_value' )
			->from( CartRecoveryFns::$ca_email_meta )
			->where( 'email_template_id', '=', $template_id )
			->andWhere( 'meta_key', '=', $meta_key )
			->get();
		return $getMetaData[0]['meta_value'] ?? $default;
	}

	/**
	 * @param string $startDate Number of records per page.
	 * @param string $endDate Page number.
	 * @return mixed
	 */
	public static function getRecoveredOrderMeta( $startDate, $endDate ) {
		$getMetaData = Fns::DB()::select( 'abandonment_id', 'meta_value' )
			->from( CartRecoveryFns::$ca_abandonment_meta )
			->where( 'meta_key', '=', 'completed_time' )
			->andWhere( 'meta_value', '>=', $startDate )
			->andWhere( 'meta_value', '<=', $endDate )
			->groupBy( 'abandonment_id' )
			->get();
		if ( is_array( $getMetaData ) && count( $getMetaData ) ) {
			return $getMetaData;
		}
		return [];
	}
	/**
	 * @param string $startDate Number of records per page.
	 * @param string $endDate Page number.
	 * @return mixed
	 */
	public static function getLostOrderMeta( $startDate, $endDate ) {
		$getMetaData = Fns::DB()::select( 'abandonment_id', 'meta_value' )
			->from( CartRecoveryFns::$ca_abandonment_meta )
			->where( 'meta_key', '=', 'lost_time' )
			->andWhere( 'meta_value', '>=', $startDate )
			->andWhere( 'meta_value', '<=', $endDate )
			->groupBy( 'abandonment_id' )
			->get();
		if ( is_array( $getMetaData ) && count( $getMetaData ) ) {
			return $getMetaData;
		}
		return [];
	}
	/**
	 * @param int    $template_id Email History ID.
	 * @param string $meta_key Meta Key.
	 * @param mixed  $andValue Meta Value.
	 * @return mixed
	 */
	public static function count_email_template_by_meta_value( $template_id, $meta_key, $andValue = '' ) {
		$query       = Fns::DB()::select( 'id' )
			->from( CartRecoveryFns::$ca_email_meta )
			->where( 'email_template_id', '=', $template_id )
			->andWhere( 'meta_key', '=', $meta_key )
			->andWhere( 'meta_value', '=', $andValue );
		$getMetaData = $query->get();
		return is_array( $getMetaData ) ? count( $getMetaData ) : 0;
	}
	/**
	 * Insert or update email template meta.
	 *
	 * @param int    $template_id Template ID.
	 * @param string $key         Meta key.
	 * @param mixed  $value       Meta value.
	 *
	 * @since 1.0.0
	 */
	public static function add_email_template_meta( $template_id, $key, $value ) {
		self::insert_meta( CartRecoveryFns::$ca_email_meta, 'email_template_id', $template_id, $key, $value );
	}

	/**
	 * Insert or update email template meta.
	 *
	 * @param int    $template_id Template ID.
	 * @param string $key         Meta key.
	 * @param mixed  $value       Meta value.
	 *
	 * @since 1.0.0
	 */
	public static function update_email_template_meta( $template_id, $key, $value ) {
		self::upsert_meta( CartRecoveryFns::$ca_email_meta, 'email_template_id', $template_id, $key, $value );
	}
	/**
	 * Get the most recent scheduled email for an abandonment ID.
	 */
	public static function lost_abandonment_detection() {
		$wp_current_datetime = gmdate( 'Y-m-d H:i:s', Fns::currentTimestampUTC() );
		$getLostItems        = Fns::DB()->select( 't.id' )
			->from( CartRecoveryFns::$ca_abandonment . ' t' )
			->leftJoin( CartRecoveryFns::$ca_abandonment_meta . ' m', 't.id', 'm.abandonment_id' )
			->where( 't.order_status', '!=', 'lost' )
			->andWhere( 'm.meta_key', '=', 'lost_time' )
			->andWhere( 'm.meta_value', '<=', $wp_current_datetime )
			->groupBy( 'abandonment_id' )
			->get();
		if ( empty( $getLostItems ) ) {
			return;
		}
		foreach ( $getLostItems as $item ) {
			if ( empty( $item['id'] ) ) {
				continue;
			}
			self::updateCaAbandonmentById( $item['id'], [ 'order_status' => 'lost' ] );
			self::delete_unnecessery_email_history_for_abandonment( $item['id'] );
		}
	}
	/**
	 * Retrieve abandonment meta records by meta key and meta value.
	 *
	 * This function fetches records from the abandonment meta table
	 * where the meta key and meta value match the given parameters.
	 *
	 * @param int    $abandonment_id abandonment id to match.
	 * @param string $meta_key meta key to match.
	 * @param mixed  $default default value.
	 *
	 * @return false|array List of results with abandonment_id and meta_value.
	 */
	public static function get_abandonment_meta( $abandonment_id, $meta_key, $default = null ) {
		if ( empty( $abandonment_id ) || empty( $meta_key ) ) {
			return false;
		}
		$query  = Fns::DB()
			->select( 'meta_value' )
			->from( CartRecoveryFns::$ca_abandonment_meta )
			->where( 'abandonment_id', '=', $abandonment_id )
			->andWhere( 'meta_key', '=', $meta_key );
		$result = $query->get();
		return $result[0]['meta_value'] ?? $default;
	}
	/**
	 * @param int    $abandonment_id abandonment ID.
	 * @param string $meta_key Meta Key.
	 * @param mixed  $andValue Meta Value.
	 * @return mixed
	 */
	public static function count_abandonment_by_meta_value( $abandonment_id, $meta_key, $andValue = '' ) {
		$query       = Fns::DB()::select( 'id' )
			->from( CartRecoveryFns::$ca_abandonment_meta )
			->where( 'abandonment_id', '=', $abandonment_id )
			->andWhere( 'meta_key', '=', $meta_key )
			->andWhere( 'meta_value', '=', $andValue );
		$getMetaData = $query->get();
		return is_array( $getMetaData ) ? count( $getMetaData ) : 0;
	}

	/**
	 * Reschedule emails for cart abandonment.
	 *
	 * @param int $abandonment_id Abandonment ID.
	 *
	 * @return void
	 */
	public static function reshedule_email( $abandonment_id ) {
		$templates = self::getActiveTemplatesSecuence();
		if ( empty( $templates ) ) {
			return;
		}
		$result = CartRecoveryFns::prepare_email_history_data( $templates, $abandonment_id );
		if ( ! empty( $result['history'] ) ) {
			Fns::DB()::insert( CartRecoveryFns::$ca_email_history, $result['history'] )->execute();
			$expireTimestamp = $result['max_scheduled'] + ( 7 * DAY_IN_SECONDS );
			$lost_time       = gmdate( 'Y-m-d H:i:s', $expireTimestamp );
			self::update_ca_abandonment_meta( $abandonment_id, 'lost_time', $lost_time );
		}
	}

	/**
	 * Update normal carts to abandoned status and schedule emails.
	 *
	 * @return array Updated abandonment IDs.
	 */
	public static function update_to_abandoned() {
		$abandoned = self::get_normal_abandoned();
		if ( empty( $abandoned ) ) {
			return [];
		}
		$ids         = [];
		$allHistory  = [];
		$templates   = self::getActiveTemplatesSecuence();
		$currentTime = Fns::currentTimestampUTC();
		foreach ( $abandoned as $item ) {
			$ids[] = $item['id'];
			Fns::DB()::update(
				CartRecoveryFns::$ca_abandonment,
				[ 'order_status' => 'abandoned' ]
			)
				->where( 'order_status', '=', 'normal' )
				->andWhere( 'id', '=', absint( $item['id'] ) )
				->execute();
			if ( ! empty( $templates ) ) {
				$result = CartRecoveryFns::prepare_email_history_data( $templates, $item['id'], $currentTime );
				if ( ! empty( $result['history'] ) ) {
					$allHistory = array_merge( $allHistory, $result['history'] );
				}
				if ( $result['max_scheduled'] ) {
					$expireTimestamp = $result['max_scheduled'] + ( 7 * DAY_IN_SECONDS );
					$lost_time       = gmdate( 'Y-m-d H:i:s', $expireTimestamp );
					self::update_ca_abandonment_meta( $item['id'], 'lost_time', $lost_time );
				}
			}
		}
		if ( ! empty( $allHistory ) ) {
			Fns::DB()::insert( CartRecoveryFns::$ca_email_history, $allHistory )->execute();
		}
		return $ids;
	}
	/**
	 * Delete a scheduled email record by its ID.
	 *
	 * This method removes the specified scheduled email entry from
	 * the email history table in the database.
	 *
	 * @param int $sheduled_id The ID of the scheduled email record to delete.
	 *
	 * @return void
	 */
	public static function delete_sheduled( $sheduled_id ) {
		// Delete email history records.
		Fns::DB()::delete( CartRecoveryFns::$ca_email_history )
			->where( 'id', '=', $sheduled_id )
			->execute();
	}
}
