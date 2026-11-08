<?php
/**
 * Themes Page
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

$themes = [
	[
		'theme_name'    => 'Zilly - Grocery Store WooCommerce WordPress Theme',
		'thumbnail'     => esc_url( rtsb()->get_assets_uri( 'images/admin-themes/Zilly.png' ) ),
		'purchase_link' => 'https://www.radiustheme.com/downloads/zilly-grocery-store-woocommerce-wordpress-theme#pricing',
		'demo_link'     => 'https://www.radiustheme.com/demo/wordpress/themes/zilly/',
	],
	[
		'theme_name'    => 'Toyup - Kids Toys Store WooCommerce WordPress Theme',
		'thumbnail'     => esc_url( rtsb()->get_assets_uri( 'images/admin-themes/Toyup.png' ) ),
		'purchase_link' => 'https://www.radiustheme.com/downloads/toyup-kids-toys-store-woocommerce-wordpress-theme/?utm_source=themeforest#pricing',
		'demo_link'     => 'https://www.radiustheme.com/demo/wordpress/themes/toyup/',
	],
	[
		'theme_name'    => 'Blusho - Beauty & Cosmetics Store WooCommerce WordPress Theme',
		'thumbnail'     => esc_url( rtsb()->get_assets_uri( 'images/admin-themes/blusho.png' ) ),
		'purchase_link' => 'https://www.radiustheme.com/downloads/blusho-beauty-cosmetics-store-woocommerce-wordpress-theme/?utm_source=themeforest#pricing',
		'demo_link'     => 'https://www.radiustheme.com/demo/wordpress/themes/blusho/',
	],
	[
		'theme_name'    => 'Sportfy - Sports Store WooCommerce WordPress Theme',
		'thumbnail'     => esc_url( rtsb()->get_assets_uri( 'images/admin-themes/Sportfy.png' ) ),
		'purchase_link' => 'https://www.radiustheme.com/downloads/sportfy-sports-store-woocommerce-wordpress-theme#pricing',
		'demo_link'     => 'https://www.radiustheme.com/demo/wordpress/themes/sportfy/',
	],
	[
		'theme_name'    => 'Fasheno - Fashion Store WooCommerce WordPress Theme',
		'thumbnail'     => esc_url( rtsb()->get_assets_uri( 'images/admin-themes/Fasheno.png' ) ),
		'purchase_link' => 'https://www.radiustheme.com/downloads/fasheno-fashion-store-woocommerce-wordpress-theme#pricing',
		'demo_link'     => 'https://www.radiustheme.com/demo/wordpress/themes/fasheno/',
	],
		/**
		[
			'theme_name' => 'More Themes are Coming Soon',
			'thumbnail'  => esc_url( rtsb()->get_assets_uri( 'images/admin-themes/coming-soon.jpg' ) ),
		],
		*/
];

?>
<div class="wrap rtsb-themes-wrap">
	<div class="rtsb-settings-page-wrapper">
		<div class="rtsb-header-area">
			<div class="rtsb-header-logo-wrap">
				<img src="<?php echo esc_url( rtsb()->get_assets_uri( 'images/icon/ShopBuilder-Logo.svg' ) ); ?>" alt="ShopBuilder" loading="lazy">
			</div>
			<div class="rtsb-header-title-wrap">
				<h1 class="rtsb-title"><?php echo esc_html__( 'ShopBuilder Themes', 'shopbuilder' ); ?></h1>
			</div>
		</div>
		<div class="rtsb-settings-tabs-wrap">
			<div class="rtsb-settings-tab-content">
				<div class="rtsb-ss-wrap">
					<div class="rtsb-ss-header-area">
						<div class="rtsb-ss-header">
							<h2 class="rtsb-title"><?php echo esc_html__( 'WooCommerce Themes with ShopBuilder', 'shopbuilder' ); ?></h2>
							<p class="rtsb-description"><?php echo esc_html__( 'Discover our beautiful WooCommerce themes, expertly crafted for easy setup and customization with the Shopbuilder plugin.', 'shopbuilder' ); ?></p>
						</div>
					</div>

					<div class="rtsb-ss-item-list-wrap">
						<div class="rtsb-themes-container">
							<?php
							foreach ( $themes as $theme ) {
								?>
								<div class="rtsb-ss-item">
									<div class="rtsb-ss-item-box">
										<div class="rtsb-ss-item-thumbnail">
											<img src="<?php echo esc_url( $theme['thumbnail'] ); ?>" alt="ShopBuilder">
										</div>
										<div class="rtsb-ss-item-content">
											<h3 class="rtsb-ss-item-title"><?php echo esc_html( $theme['theme_name'] ); ?></h3>
											<div class="rtsb-ss-item-buttons">
												<?php
												if ( ! empty( $theme['demo_link'] ) ) {
													?>
													<a href="<?php echo esc_url( $theme['demo_link'] ); ?>" class="rtsb-button rtsb-button-demo" target="_blank"><?php echo esc_html__( 'View Demo', 'shopbuilder' ); ?></a>
													<?php
												}

												if ( ! empty( $theme['purchase_link'] ) ) {
													?>
													<a href="<?php echo esc_url( $theme['purchase_link'] ); ?>" class="rtsb-button rtsb-button-purchase" target="_blank"><?php echo esc_html__( 'Buy Now', 'shopbuilder' ); ?></a>
													<?php
												}

												if ( ! empty( $theme['download_link'] ) ) {
													?>
													<a href="<?php echo esc_url( $theme['download_link'] ); ?>" class="rtsb-button rtsb-button-purchase" target="_blank"><?php echo esc_html__( 'Download', 'shopbuilder' ); ?></a>
													<?php
												}
												?>
											</div>
										</div>
									</div>
								</div>
								<?php
							}
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
