<?php
/**
 * Template: Social Share.
 *
 * @package RadiusTheme\SB
 */

/**
 * Template variables:
 *
 * @var $p_id                    int
 * @var $share_items             array
 * @var $preset                  string
 * @var $direction               string
 * @var $show_icon               boolean
 * @var $show_text               boolean
 * @var $show_pre_text           boolean
 * @var $share_pre_text          string
 * @var $raw_settings            array
 */

use RadiusTheme\SB\Helpers\Fns;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

$toggle_class = ! empty( $raw_settings['share_toggle'] ) ? ' has-toggle is-closed loaded' : '';
?>

<div class="rtsb-social-share-container <?php echo esc_attr( $direction . ( ! empty( $raw_settings['share_toggle'] ) ? ' has-toggle' : '' ) ); ?>">
	<?php
	if ( $show_pre_text && ! empty( $share_pre_text ) ) {
		?>
		<div class="rtsb-social-header"><p><?php echo esc_html( $share_pre_text ); ?></p></div>
			<?php
	}

	/**
	 * Before social share items hook.
	 */
	do_action( 'rtsb/before/social/share/item', $raw_settings );
	?>

	<div class="share-wrapper">
		<ul class="rtsb-social-share <?php echo esc_attr( $preset . ' ' . $toggle_class ); ?>">
			<?php
			Fns::print_html( Fns::get_social_share_html( $p_id, $share_items, $preset, $show_icon, $show_text ) );
			?>
		</ul>
	</div>
</div>
