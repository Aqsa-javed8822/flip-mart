<?php
/**
 * Template: Category Layout 1.
 *
 * @package RadiusTheme\SB
 */

/**
 * Template variables:
 *
 * @var $grid                    string
 * @var $class                   string
 * @var $p_id                    int
 * @var $image_link              bool
 * @var $cat_link                string
 * @var $img_html                string
 * @var $items                   array
 * @var $title_tag               string
 * @var $excerpt_limit           string
 * @var $excerpt                 string
 * @var $title_class             string
 * @var $title_link              bool
 * @var $target                  string
 * @var $title                   string
 * @var $count                   string
 * @var $count_position          string
 * @var $p_sale                  string
 * @var $badge_class             string
 * @var $excerpt_position        string
 * @var $excerpt_class           string
 * @var $raw_settings            array
 */

use RadiusTheme\SB\Helpers\Fns;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}
?>

<div class="<?php echo esc_attr( $grid ) . ' ' . esc_attr( $class ); ?>">
	<div class="category-wrapper">
		<?php
		/**
		 * Category Image.
		 */
		if ( $img_html ) {
			?>
			<div class="rtsb-product-img">
				<?php
				if ( $image_link ) {
					$aria_label = esc_attr(
						/* translators: Product Category Name */
						sprintf( __( 'Image link for Category: %s', 'shopbuilder' ), $title )
					);
					?>
					<figure>
						<a href="<?php echo esc_url( $cat_link ); ?>" target="<?php echo esc_attr( $target ); ?>" class="rtsb-img-link" aria-label="<?php echo esc_attr( $aria_label ); ?>">
							<?php
							Fns::get_product_image( $img_html );
							?>
						</a>
					</figure>
					<?php
				} else {
					echo '<figure class="rtsb-img-link">';
					Fns::get_product_image( $img_html );
					echo '</figure>';
				}
				?>
			</div><!-- .rtsb-product-img -->
			<?php
		}
		?>

		<div class="rtsb-category-content">
			<div class="category-inner <?php echo esc_attr( in_array( 'excerpt', $items, true ) ? 'excerpt-enabled' : 'no-excerpt' ) . ( ! empty( $count ) ? esc_attr( 'block' === $count_position ? ' block-count' : ' inline-count' ) : ' no-count' ); ?>">
				<?php
				/**
				 * Category Title.
				 */
				if ( in_array( 'title', $items, true ) ) {
					?>
					<div class="category-title-with-count <?php echo esc_attr( $count_position ); ?>">
						<?php
						/**
						 * Category Short Description.
						 */
						if ( in_array( 'excerpt', $items, true ) && 'above' === $excerpt_position ) {
							?>
							<div class="category-description rtsb-text-limit limit-<?php echo esc_attr( $excerpt_limit ); ?>">
								<?php
								Fns::print_html( $excerpt );
								?>
							</div>
							<?php
						}
						?>

						<<?php Fns::print_validated_html_tag( $title_tag ); ?> class="<?php echo esc_attr( $title_class ); ?>">
						<?php
						if ( $title_link ) {
							?>
								<a href="<?php echo esc_url( $cat_link ); ?>" target="<?php echo esc_attr( $target ); ?>"><?php Fns::print_html( $title ); ?></a>
								<?php
						} else {
							Fns::print_html( $title );
						}
						?>
						</<?php Fns::print_validated_html_tag( $title_tag ); ?>>

						<?php
						/**
						 * Category Product Count.
						 */
						if ( in_array( 'count', $items, true ) && ( 'flex' === $count_position ) ) {
							?>
							<div class="product-count">
								<?php
								Fns::print_html( $count );
								?>
							</div>
							<?php
						}
						?>
					</div>
					<?php
				}
				?>

				<div class="<?php echo esc_attr( $excerpt_class ); ?>">
					<?php
					/**
					 * Category Product Count.
					 */
					if ( in_array( 'count', $items, true ) && ( 'block' === $count_position ) ) {
						?>
						<div class="product-count">
							<?php
							Fns::print_html( $count );
							?>
						</div>
						<?php
					}

					/**
					 * Category Short Description.
					 */
					if ( in_array( 'excerpt', $items, true ) && 'below' === $excerpt_position ) {
						?>
						<div class="category-description rtsb-text-limit limit-<?php echo esc_attr( $excerpt_limit ); ?>">
							<?php
							Fns::print_html( $excerpt );
							?>
						</div>
						<?php
					}
					?>
				</div>
			</div>
		</div><!-- .rtsb-category-content -->
	</div><!-- .category-wrapper -->
</div><!-- .rtsb-category-grid -->
