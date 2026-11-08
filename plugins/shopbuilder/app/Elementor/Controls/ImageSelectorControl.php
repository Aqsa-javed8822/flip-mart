<?php
/**
 * Main Elementor ImageSelectorControl Class
 *
 * The main class that initiates and runs the plugin.
 *
 * @package  RadiusTheme\SB
 * @since    1.0.0
 */

namespace RadiusTheme\SB\Elementor\Controls;

use Elementor\Base_Data_Control;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Main Elementor ImageSelectorControl Class
 */
class ImageSelectorControl extends Base_Data_Control {

	/**
	 * Set control name.
	 *
	 * @var string
	 */
	public static $controlName = 'rtsb-image-selector';

	/**
	 * Set control type.
	 */
	public function get_type() {
		return self::$controlName;
	}

	/**
	 * Enqueue control scripts and styles.
	 */
	public function enqueue() {
		wp_enqueue_style( 'rtsb-image-selector', rtsb()->get_assets_uri( 'css/backend/elementor-image-selector.css' ), [], '1.0' );
	}

	/**
	 * Set default settings
	 */
	protected function get_default_settings() {
		return [
			'label_block' => true,
			'toggle'      => true,
			'options'     => [],
		];
	}

	/**
	 * Control field markup
	 */
	public function content_template() {
		$control_uid = $this->get_control_uid( '{{ value }}' );
		?>
		<div class="elementor-control-field">
			<label class="elementor-control-title">{{{ data.label }}}</label>
			<div class="elementor-control-image-selector-wrapper">
				<# _.each( data.options, function( options, value ) { #>
				<div class="image-selector-inner{{ options.is_pro ? ' rtsb-pro' : '' }}" title="{{ ! options.is_pro ? '' : 'Upgrade to PRO!' }}" data-tooltip="{{ ! options.is_pro ? options.title : 'Upgrade to PRO!' }}">
					<input id="<?php echo esc_attr( $control_uid ); ?>" type="radio" name="elementor-image-selector-{{ data.name }}-{{ data._cid }}" value="{{ value }}" data-setting="{{ data.name }}">
					<label class="rtsb-elementor-image-selector-label tooltip-target{{ options.is_pro ? ' is-pro' : '' }}" for="<?php echo esc_attr( $control_uid ); ?>" data-tooltip="{{ options.title }}" title="{{ options.title }}">
						<img src="{{ options.url }}" alt="{{ options.title }}">
						<span class="elementor-screen-only">{{{ options.title }}}</span>
					</label>
				</div>
				<# } ); #>
			</div>
			<# if ( data.description ) { #>
			<div class="elementor-control-field-description rtsb-description">{{{ data.description }}}</div>
			<# } #>
		</div>
		<?php
	}
}
