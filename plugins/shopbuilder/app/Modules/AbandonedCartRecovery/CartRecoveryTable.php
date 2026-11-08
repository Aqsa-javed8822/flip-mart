<?php
/**
 * Sticky Abandoned Cart Recovery DB Module Class.
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
class CartRecoveryTable {
	/**
	 * Singleton Trait.
	 */
	use SingletonTrait;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->create_cart_table_if_need();
	}
	/**
	 * @return void
	 */
	public function create_cart_table_if_need() {
		global $wpdb;
		$isMissing       = false;
		$required_tables = [
			CartRecoveryFns::$ca_abandonment,
			CartRecoveryFns::$ca_abandonment_meta,
			CartRecoveryFns::$ca_email,
			CartRecoveryFns::$ca_email_meta,
			CartRecoveryFns::$ca_email_history,
		];
		foreach ( $required_tables as $table ) {
			$is_table_exist = $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->prefix . $table ) ); //phpcs:ignore
			if ( empty( $is_table_exist ) ) {
				$isMissing = true;
				break;
			}
		}
		if ( $isMissing ) {
			$this->create_cart_abandonment_table();
			$this->create_cart_abandonment_email_template_table();
			$this->create_email_templates_meta_table();
			$this->create_email_history_table();
			$this->create_abandonment_meta_table();
		}
	}

	/**
	 * @return void
	 */
	private function create_cart_abandonment_table() {
		Fns::DB()::create( CartRecoveryFns::$ca_abandonment )
			->column( 'id' )->bigInt( 20 )->unsigned()->autoIncrement()->primary()->required()
			->column( 'checkout_id' )->int( 11 )->required()
			->column( 'email' )->string( 100 )->nullable()
			->column( 'cart_contents' )->longText()
			->column( 'cart_total' )->string( 10 )->nullable()
			->column( 'ca_session_id' )->string( 60 )->required()
			->column( 'other_fields' )->longText()
			->column( 'order_status' )->enum( [ 'normal', 'abandoned', 'completed', 'lost' ] )->default( 'normal' )
			->column( 'unsubscribed' )->boolean()->default( 0 )
			->column( 'coupon_code' )->string( 50 )->nullable()
			->column( 'time' )->dateTime()
			->execute();
	}
	/**
	 * @return void
	 */
	private function create_abandonment_meta_table() {
		Fns::DB()::create( CartRecoveryFns::$ca_abandonment_meta )
			->column( 'id' )->bigInt( 20 )->unsigned()->autoIncrement()->primary()->required()
			->column( 'abandonment_id' )->bigInt( 20 )->unsigned()->required()
			->column( 'meta_key' )->string( 255 )->required()
			->column( 'meta_value' )->longText()->required()
			->execute();
	}
	/**
	 * @return void
	 */
	private function create_cart_abandonment_email_template_table() {
		Fns::DB()::create( CartRecoveryFns::$ca_email )
			->column( 'id' )->bigInt( 20 )->unsigned()->autoIncrement()->primary()->required()
			->column( 'title' )->text()->required()
			->column( 'email_subject' )->text()->required()
			->column( 'email_body' )->longText()->required()
			->column( 'other_fields' )->longText()
			->column( 'is_activated' )->text()
			->column( 'frequency' )->int( 11 )->required()
			->column( 'frequency_unit' )->enum( [ 'MINUTE','HOUR','DAY' ] )->default( 'MINUTE' )->required()
			->column( 'menu_order' )->int( 11 )->default( 0 )
			->execute();
	}
	/**
	 * @return void
	 */
	private function create_email_history_table() {
		Fns::DB()::create( CartRecoveryFns::$ca_email_history )
			->column( 'id' )->bigInt( 20 )->unsigned()->autoIncrement()->primary()->required()
			->column( 'template_id' )->bigInt( 20 )->unsigned()->required()
			->column( 'abandonment_id' )->bigInt( 20 )->required()
			->column( 'coupon_code' )->string( 50 )->nullable()
			->column( 'scheduled_time' )->dateTime()
			->column( 'email_sent' )->boolean()->default( 0 )
			->column( 'open_time' )->dateTime()
			->column( 'action' )->string( 50 )->nullable()
			->execute();
	}
	/**
	 * @return void
	 */
	private function create_email_templates_meta_table() {
		Fns::DB()::create( CartRecoveryFns::$ca_email_meta )
			->column( 'id' )->bigInt( 20 )->unsigned()->autoIncrement()->primary()->required()
			->column( 'email_template_id' )->bigInt( 20 )->unsigned()->required()
			->column( 'meta_key' )->string( 255 )->required()
			->column( 'meta_value' )->longText()->required()
			->execute();
	}
}
