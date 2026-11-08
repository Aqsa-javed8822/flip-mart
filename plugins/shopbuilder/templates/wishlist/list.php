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
 * @var $field_ids       array Wishlist page field_ids
 * @var $headings        array Wishlist page table headings
 */

use RadiusTheme\SB\Modules\WishList\WishlistFns;

defined( 'ABSPATH' ) || die( 'Keep Silent' );
?>

<div class="rtsb-wishlist-content">
    <table class="rtsb-wishlist-table">
        <thead>
		<?php
		$cell_count = 1;
		if ( ! empty( $field_ids ) ) {
			$cell_count = count( $field_ids );
			echo '<tr>';
			foreach ( $field_ids as $field_id ) {
				$name = ! empty( $headings[ $field_id ] ) ? $headings[ $field_id ] : WishlistFns::instance()->field_name( $field_id );
				echo '<th class="rtsb-wl-product-' . esc_attr( $field_id ) . '">' . ( 'Remove' === $name ? '<i class="rtsb-icon rtsb-icon-trash-empty"></i>' : esc_html( $name )  ). '</th>';
			}
			echo '</tr>';
		}
		?>
        </thead>
        <tbody>
		<?php
		if ( ! empty( $products ) ):
			foreach ( $products as $product_id => $product ):
				?>
                <tr class="rtsb-wl-product" data-product_id="<?php echo absint( $product_id ) ?>">
					<?php foreach ( $field_ids as $field_id ) :
						$data_label = ! empty( $headings[ $field_id ] ) ? $headings[ $field_id ] : WishlistFns::instance()->field_name( $field_id );
						?>
                        <td class="rtsb-wl-product-<?php echo esc_attr( $field_id ); ?>"
                            data-label="<?php echo esc_attr( $data_label ); ?>">
                            <div class="rtsb-wl-td-wrapper">
							    <?php WishlistFns::instance()->display_field( $field_id, $product ); ?>
                            </div>
                        </td>
					<?php endforeach; ?>
                </tr>

			<?php endforeach; ?>
            <tr class="rtsb-wl-empty-tr">
                <td class="rtsb-wl-empty-text" colspan="<?php echo esc_attr( $cell_count ); ?>">
					<?php if ( ! empty( $empty_text ) ) {
						echo wp_kses_post( $empty_text );
					} ?>
                </td>
            </tr>
		<?php else: ?>
            <tr>
                <td class="rtsb-wl-empty-text" colspan="<?php echo esc_attr( $cell_count ); ?>">
					<?php if ( ! empty( $empty_text ) ) {
						echo wp_kses_post( $empty_text );
					} ?>
                </td>
            </tr>
		<?php endif; ?>
        </tbody>
    </table>
</div>
