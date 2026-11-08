<?php
/**
 * Quick view button
 *
 * @author  RadiusTheme
 * @package ShopBuilder QuickView
 * @version 1.0.0
 */


/**
 * Template variables:
 *
 * @var $product_id                int
 * @var $button_text               string
 * @var $icon_html                 string
 * @var $classes                   string
 * @var $left_text                 string
 * @var $right_text                string
 * @var $has_button_text           bool
 */

defined( 'ABSPATH' ) || die( 'Keep Silent' );

use RadiusTheme\SB\Helpers\Fns;
?>

<a class="rtsb-quick-view-btn tipsy <?php echo esc_attr( implode( ' ', $classes ) ); ?>" rel="nofollow"
	data-product_id="<?php echo esc_attr( $product_id ); ?>"
	title="<?php echo esc_attr( $button_text ); ?>"
	data-title="<?php echo esc_attr( $button_text ); ?>" href="#" aria-label="<?php echo esc_attr__( 'Quick view', 'shopbuilder' ); ?>" >
	<span class="icon">
		<?php echo wp_kses( $left_text, Fns::get_kses_array() ); ?>
		<?php Fns::print_html( $icon_html ); ?>
		<?php echo wp_kses( $right_text, Fns::get_kses_array() ); ?>
	</span>
	<?php
	if ( $has_button_text ) {
		?>
		<span class="button-text">
			<?php echo esc_attr( $button_text ); ?>
		</span>
		<?php
	}
	?>
</a>
