<?php
/**
 * The template for displaying the shopify header.
 *
 * @package RadiusTheme\SB
 */

use RadiusTheme\SB\Modules\ShopifyCheckout\ShopifyCheckoutFns;
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php
$hasTop = ! empty( ShopifyCheckoutFns::get_shopify_page_logo( true ) ) || ! empty( ShopifyCheckoutFns::get_cart_icon_html( true ) );
?>
<main id="content" class="<?php echo esc_attr( $hasTop ? 'rtsb-shopify-with-header' : 'rtsb-shopify-without-header' ); ?>" >
	<?php
	$site_name = get_bloginfo( 'name' );
	$tagline   = get_bloginfo( 'description', 'display' );

	if ( $hasTop ) {
		?>
		<div class="rtsb-checkout-page-container rtsb-shopify-header-container">
			<div class="rtsb-checkout-page-header">
				<a href="<?php echo esc_url( home_url() ); ?>">
					<?php ShopifyCheckoutFns::get_shopify_page_logo(); ?>
				</a>
				<div class="header-right-side">
					<a href="<?php echo esc_url( wc_get_cart_url() ); ?>">
						<?php
						if ( ! empty( ShopifyCheckoutFns::get_cart_icon_html( true ) ) ) {
							ShopifyCheckoutFns::get_cart_icon_html();
						}
						?>
					</a>
				</div>
			</div>
		</div>
		<?php
	}
	?>
