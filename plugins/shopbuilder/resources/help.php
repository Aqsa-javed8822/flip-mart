<?php
/**
 * Get Help
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

$playlist     = 'https://youtube.com/playlist?list=PLSR3AlpVWfs6GVZtNTXZpAfjHimBGNvCG&si=_wEdUpNVjEBJCvwa';
$banner       = rtsb()->get_assets_uri( 'images/help-banner.jpg' );
$youtube      = rtsb()->get_assets_uri( 'images/youtube-icon.svg' );
$watch        = rtsb()->get_assets_uri( 'images/watch-on-youtube.svg' );
$pro          = 'https://www.radiustheme.com/downloads/woocommerce-bundle/';
$pro_banner   = rtsb()->get_assets_uri( 'images/admin-banner.jpg' );
$contact      = 'https://www.radiustheme.com/contact/';
$fb           = 'https://www.facebook.com/groups/234799147426640/';
$rt           = 'https://www.radiustheme.com/';
$has_pro      = rtsb()->has_pro();
$testimonials = [
	[
		'content' => 'I don’t know much about coding and I am just trying to set up a simple online shop without it looking like a 3 year old made it! This plugin did it and saved me hours and hours! I love their simple layout and easy to import templates.',
		'image'   => rtsb()->get_assets_uri( 'images/admin-testimonial/review-clients-2.png' ),
		'name'    => 'Once Upon a Tree',
		'stars'   => 5,
	],
	[
		'content' => 'I strongly recommend this solution for creating unique design for your next WooCommerce store. In comparison with alternatives – ShopBuilder is the best. Support is amazing and fast. Thanks for this plugin!',
		'image'   => rtsb()->get_assets_uri( 'images/admin-testimonial/review-clients-3.png' ),
		'name'    => 'codeorlov',
		'stars'   => 5,
	],
	[
		'content' => 'I love this plugin. It’s simple but exactly what I was looking for. Easy install, quick response from the developer. Finally, an easy way to monetize my Shop.',
		'image'   => rtsb()->get_assets_uri( 'images/admin-testimonial/review-clients-1.png' ),
		'name'    => 'Mahabub Hasan',
		'stars'   => 5,
	],
];
$actions      = [
	[
		'title'        => 'Documentation Guide',
		'description'  => 'Explore our easy-to-follow documentation, complete with step-by-step guides, screenshots, and videos to simplify your setup process.',
		'icon_class'   => 'rtsb-documentation-icon',
		'button_label' => 'View Documentation',
		'button_link'  => 'https://shopbuilderwp.com/docs/',
	],
	[
		'title'        => 'Need Any Help?',
		'description'  => 'Stuck with something? Please create a <a href="' . esc_url( $contact ) . '" target="_blank">ticket here</a> or post on <a href="' . esc_url( $fb ) . '" target="_blank">facebook group</a>. For emergency case join our <a href="' . esc_url( $rt ) . '" target="_blank">live chat</a>.',
		'icon_class'   => 'rtsb-support-icon',
		'button_label' => 'Get Support',
		'button_link'  => $contact,
	],
	[
		'title'        => 'Happy with Our Work?',
		'description'  => 'Thank you for choosing <strong>ShopBuilder</strong>. If you have found our plugin useful, please consider giving us a 5-star rating on WordPress.org. It will help us to grow.',
		'icon_class'   => 'rtsb-rating-icon',
		'button_label' => 'Yes, You Deserve It',
		'button_link'  => 'https://wordpress.org/support/plugin/shopbuilder/reviews/#new-post',
	],
];
?>

<div class="wrap rtsb-help-wrap">
	<div class="rtsb-settings-page-wrapper">
		<div class="rtsb-header-area">
			<div class="rtsb-header-logo-wrap">
				<img src="<?php echo esc_url( rtsb()->get_assets_uri( 'images/icon/ShopBuilder-Logo.svg' ) ); ?>" alt="ShopBuilder">
			</div>
			<div class="rtsb-header-title-wrap">
				<h1 class="rtsb-title"><?php echo esc_html__( 'ShopBuilder Help Center', 'shopbuilder' ); ?></h1>
			</div>
		</div>
		<div class="rtsb-settings-tabs-wrap">
			<div class="rtsb-settings-tab-content">
				<div class="rtsb-ss-wrap">
					<div class="rtsb-ss-header-area">
						<div class="rtsb-ss-header">
							<h2 class="rtsb-title"><?php echo esc_html__( 'ShopBuilder Essentials: Video Tutorials for Every Feature', 'shopbuilder' ); ?></h2>
							<p class="rtsb-description"><?php echo esc_html__( 'Discover how to make the most of ShopBuilder with our detailed tutorial playlist. From basic setup to advanced customization, these videos will guide you every step of the way.', 'shopbuilder' ); ?></p>
						</div>
					</div>

					<div class="rtsb-ss-item-list-wrap">
						<div class="rtsb-playlist-container">
							<a href="<?php echo esc_url( $playlist ); ?>" target="_blank">
								<img src="<?php echo esc_url( $banner ); ?>" class="rtsb-playlist-banner" alt="How to create WooCommerce pages with ShopBuilder">
								<img src="<?php echo esc_url( $youtube ); ?>" class="rtsb-playlist-icon" alt="Youtube">
								<img src="<?php echo esc_url( $watch ); ?>" class="rtsb-watch-icon" alt="Watch on Youtube">
							</a>
						</div>
					</div>
				</div>
			</div>

			<div class="rtsb-settings-tab-content">
				<div class="rtsb-ss-wrap">
					<div class="rtsb-ss-header-area">
						<div class="rtsb-ss-header">
							<h2 class="rtsb-title"><?php echo esc_html__( 'Hear from Our Happy Clients', 'shopbuilder' ); ?></h2>
							<p class="rtsb-description"><?php echo esc_html__( 'See how ShopBuilder is making an impact with real feedback from happy customers around the world.', 'shopbuilder' ); ?></p>
						</div>
					</div>

					<div class="rtsb-ss-item-list-wrap">
						<div class="rtsb-testimonial-container">
							<div class="rtsb-testimonials">
								<?php
								foreach ( $testimonials as $testimonial ) {
									?>
									<div class="rtsb-testimonial">
										<p><?php echo esc_html( $testimonial['content'] ); ?></p>
										<div class="client-info">
											<img src="<?php echo esc_url( $testimonial['image'] ); ?>" alt="Client Image">
											<div class="client-details">
												<div class="rtsb-star">
													<?php for ( $i = 0; $i < $testimonial['stars']; $i++ ) : ?>
														<i class="dashicons dashicons-star-filled"></i>
													<?php endfor; ?>
												</div>
												<span class="client-name"><?php echo esc_html( $testimonial['name'] ); ?></span>
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

			<?php
			if ( ! $has_pro ) {
				?>
				<div class="rtsb-settings-tab-content">
					<div class="rtsb-ss-wrap">
						<div class="rtsb-ss-header-area">
							<div class="rtsb-ss-header">
								<h2 class="rtsb-title"><?php echo esc_html__( 'Go Pro for Enhanced Functionality', 'shopbuilder' ); ?></h2>
								<p class="rtsb-description"><?php echo esc_html__( 'Elevate your projects with exclusive Pro features that enhance functionality, powerful modules for advanced customization, and more template design support.', 'shopbuilder' ); ?></p>
							</div>
						</div>

						<div class="rtsb-ss-item-list-wrap">
							<div class="rtsb-features-container">
								<ul>
									<li><i class="dashicons dashicons-saved"></i> AJAX Product Filter Widget</li>
									<li><i class="dashicons dashicons-saved"></i> Product Category Specific Archive Page</li>
									<li><i class="dashicons dashicons-saved"></i> Product Specific Details Page</li>
									<li><i class="dashicons dashicons-saved"></i> Product Category / Tag-Specific Details Page</li>
									<li><i class="dashicons dashicons-saved"></i> Thank You / Order Received Page</li>
									<li><i class="dashicons dashicons-saved"></i> My Account Pages</li>
									<li><i class="dashicons dashicons-saved"></i> Quick View Template</li>
									<li><i class="dashicons dashicons-saved"></i> Custom Endpoint Builder</li>
									<li><i class="dashicons dashicons-saved"></i> Advanced Product Tabs for Creating Custom Tab</li>
									<li><i class="dashicons dashicons-saved"></i> Special Product Query i.e., on Sale, Featured, etc.</li>
									<li><i class="dashicons dashicons-saved"></i> Ajax, Load More and Infinite Scroll Pagination</li>
									<li><i class="dashicons dashicons-saved"></i> Category Ajax Tab Filter</li>
									<li><i class="dashicons dashicons-saved"></i> Product Gallery Images Slider</li>
									<li><i class="dashicons dashicons-saved"></i> Product Add-Ons Module</li>
									<li><i class="dashicons dashicons-saved"></i> Sales Notification Module</li>
									<li><i class="dashicons dashicons-saved"></i> Flash Sale Countdown Module</li>
									<li><i class="dashicons dashicons-saved"></i> Quick Checkout Module</li>
									<li><i class="dashicons dashicons-saved"></i> Pre-Order Module</li>
									<li><i class="dashicons dashicons-saved"></i> Multi-Step Checkout Module</li>
									<li><i class="dashicons dashicons-saved"></i> Customize My Account Module</li>
									<li><i class="dashicons dashicons-saved"></i> Currency Switcher Module</li>
									<li><i class="dashicons dashicons-saved"></i> Back Order Module</li>
									<li><i class="dashicons dashicons-saved"></i> Checkout Fields Editor Module</li>
									<li><i class="dashicons dashicons-saved"></i> Many more features...</li>
								</ul>
							</div>
						</div>
					</div>
				</div>

				<div class="rtsb-settings-tab-content pro-banner">
					<div class="rtsb-ss-wrap">
						<div class="rtsb-ss-item-list-wrap">
							<div class="rtsb-pro-banner-container">
								<div class="rtsb-pro-banner">
									<a href="<?php echo esc_url( $pro ); ?>" target="_blank">
										<img class="rtsb-upgrade-pro" src="<?php echo esc_url( $pro_banner ); ?>" alt="Upgrade to Pro">
										<img class="rtsb-get-pro" src="<?php echo esc_url( rtsb()->get_assets_uri( 'images/get-pro.svg' ) ); ?>" alt="Get Pro">
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php
			}
			?>

			<div class="rtsb-settings-tab-content rtsb-action-content">
				<div class="rtsb-ss-item-list-wrap">
						<div class="rtsb-actions-container">
							<div class="rtsb-actions">
								<?php
								foreach ( $actions as $box ) {
									?>
									<div class="rtsb-action-box">
										<div class="rtsb-box-icon"><div class="rtsb-help-icon <?php echo esc_attr( $box['icon_class'] ); ?>"></div></div>
										<div class="rtsb-box-content">
											<h3 class="rtsb-box-title"><?php echo esc_html( $box['title'] ); ?></h3>
											<p><?php echo wp_kses_post( $box['description'] ); ?></p>
											<a href="<?php echo esc_url( $box['button_link'] ); ?>" class="rtsb-button" target="_blank">
												<?php echo esc_html( $box['button_label'] ); ?>
											</a>
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
</div>
