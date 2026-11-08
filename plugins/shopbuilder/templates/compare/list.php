<?php
/**
 * Wishlist page template
 *
 * @author  RadiusTheme
 * @package RTSB_ABSPATH\Templates\Wishlist\list
 * @version 1.0.0
 */

/**
 * Template variables:
 *
 * @var $field_ids              string[] List of field ids
 * @var $empty_text             string
 * @var $return_to_shop_text    string
 */

use RadiusTheme\SB\Modules\Compare\CompareFns;

defined( 'ABSPATH' ) || die( 'Keep Silent' );
?>

<div class="rtsb-compare-wrap">
	<?php do_action( 'rtsb/module/compare/before_list' ); ?>
	<div class="rtsb-compare-list">
		<?php

		if ( ! empty( $products ) ) {
			foreach ( $field_ids as $field_id ) {
				// Generate Filed name.
				$name = CompareFns::instance()->field_name( $field_id );
				?>
				<div class="rtsb-compare-list-row">
					<div class="rtsb-compare-list-col rtsb-compare-field-label"><?php echo esc_html( $name ); ?></div>
					<?php foreach ( $products as $product ) : ?>
						<?php if ( ! empty( $product ) ) : ?>
							<div class="rtsb-compare-list-col rtsb-compare-list-value"
								 data-product_id="<?php echo esc_attr( $product['id'] ); ?>"><?php CompareFns::instance()->display_field( $field_id, $product ); ?></div>
						<?php else : ?>
							<div class="rtsb-compare-list-col empty">
								<?php echo esc_html( $name ?: '-' ); ?>
							</div>
						<?php endif; ?>
					<?php endforeach ?>
				</div>
				<?php
			}
		} else {
			if ( $empty_text ) {
				echo '<div class="rtsb-compare-empty">' . wp_kses_post( $empty_text ) . '</div>';
			}

			if ( $return_to_shop_text ) {
				echo '<div class="rtsb-compare-return-to-shop"><a href="' . esc_url( wc_get_page_permalink( 'shop' ) ) . '" class="button">' . esc_html( $return_to_shop_text ) . '</a></div>';
			}
		}
		?>
		<?php do_action( 'rtsb/module/compare/after_list' ); ?>
	</div>
</div>
