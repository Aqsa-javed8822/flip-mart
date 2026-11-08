<?php
/**
 * Template variables:
 *
 * @var $controllers  array settings as array
 * @var $content  string
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

use RadiusTheme\SB\Helpers\Fns;

global $product;
if ( empty( $product ) ) {
	return;
}

if ( ! wc_review_ratings_enabled() ) {
	return;
}

$rating_count = $product->get_rating_count();
$review_count = $product->get_review_count();
$average      = $product->get_average_rating();
?> 
<div class="rtsb-product-rating">
	<div class="woocommerce-product-rating">
		<?php Fns::print_html( wc_get_rating_html( $average, $rating_count ) ); ?>
		<?php if ( comments_open() ) : ?>
            <?php //phpcs:disable ?>
            <a href="#comments" class="woocommerce-review-link" rel="nofollow">(<?php printf( _n( '%s customer review', '%s customer reviews', $review_count, 'shopbuilder' ), '<span class="count">' . esc_html( $review_count ) . '</span>' ); ?>)</a>
            <?php // phpcs:enable ?>
		<?php endif ?>
	</div>
</div>
