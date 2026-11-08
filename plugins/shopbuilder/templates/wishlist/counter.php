<?php
/**
 * Go to wishlist Page
 *
 * @var $page_url                    string Wishlist page url
 * @var $icon_html                 string
 * @var $item_count                  int Total number of product of wishlist
 */

use RadiusTheme\SB\Helpers\Fns;

defined( 'ABSPATH' ) || die( 'Keep Silent' );
?>
<a href="<?php echo esc_url( $page_url ); ?>" class="rtsb-wishlist-counter-wrap">
	<?php Fns::print_html( $icon_html, true ); ?>
	<span class="rtsb-wishlist-counter"><?php echo esc_html( $item_count ); ?></span>
</a>