<?php
/**
 * Quick View Module Class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Modules\QuickView;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Traits\SingletonTrait;

/**
 * Quick View Module Class.
 */
final class QuickView {
	/**
	 * @var array
	 */
	private $cache = [];

	/**
	 * @var array
	 */
	public $settings = [];
	/**
	 * @var array|mixed
	 */
	private array $options;
	/**
	* Options
	 */
	use SingletonTrait;

	/**
	 * Class constructor.
	 *
	 * @return void
	 */
	private function __construct() {
		QuickViewFrontEnd::instance();
		$this->options = Fns::get_options( 'modules', 'quick_view' );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_public_scripts' ], 99 );
		do_action( 'rtsb/module/quick_view/loaded' );
	}

	/**
	 * @return void
	 */
	public function enqueue_public_scripts() {
		$cache_key = 'quick_view_style_cache';
		$width     = ! empty( $this->options['modal_width'] ) ? $this->options['modal_width'] : '950';
		$handle    = Fns::optimized_handle( 'rtsb-public' );

		wp_localize_script(
			$handle,
			'QvModalParams',
			[
				'modal_width' => absint( $width ),
			]
		);

		if ( isset( $this->cache[ $cache_key ] ) && ! empty( $this->cache[ $cache_key ] ) ) {
			wp_add_inline_style( Fns::optimized_handle( 'rtsb-frontend' ), $this->cache[ $cache_key ] );
			return;
		}

		$height          = ! empty( $this->options['modal_height'] ) ? absint( $this->options['modal_height'] ) : false;
		$wrapper_padding = $this->options['modal_wrapper_padding'] ?? '0';
		$bg_color        = $this->options['modal_bg_color'] ?? false;
		$box_shadow      = $this->options['modal_box_shadow_color'] ?? false;
		$overly_color    = $this->options['modal_overly_color'] ?? false;

		ob_start();

		if ( $height ) { ?>
			.rtsb-ui-modal .rtsb-modal-content{
				height: <?php echo absint( $height ); ?>px;
			}
			<?php
		}

		if ( '' !== $wrapper_padding ) {
			?>
			.rtsb-ui-modal .rtsb-modal-wrapper.quick-view-modal .rtsb-modal-content .rtsb-modal-body{
				padding: <?php echo esc_attr( $wrapper_padding ); ?>;
			}
			<?php
		}
		if ( $bg_color ) {
			?>
			.rtsb-ui-modal .rtsb-modal-wrapper .rtsb-modal-content{
				background-color: <?php echo esc_attr( $bg_color ); ?>;
			}
			<?php
		}
		if ( $box_shadow ) {
			?>
			.rtsb-ui-modal .rtsb-modal-content{
				box-shadow: 0 0 10px <?php echo esc_attr( $box_shadow ); ?>;
			}
			<?php
		}

		if ( $overly_color ) {
			?>
			.rtsb-ui-modal .rtsb-mask-wrapper{
				background-color: <?php echo esc_attr( $overly_color ); ?>;
			}
			<?php
		}

		$dynamic_css               = ob_get_clean();
		$this->cache[ $cache_key ] = $dynamic_css;
		if ( ! empty( $dynamic_css ) ) {
			wp_add_inline_style( Fns::optimized_handle( 'rtsb-frontend' ), $dynamic_css );
		}
	}
}
