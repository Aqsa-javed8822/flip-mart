<?php
/**
 * Template: Post List
 *
 * @package RadiusTheme\SB
 */

/**
 * Template variables:
 *
 * @var $grid_classes           string
 * @var $title                  string
 * @var $title_link             string
 * @var $excerpt                string
 * @var $title_tag              string
 * @var $title_limit            string
 * @var $excerpt_limit          string
 * @var $button_text            string
 * @var $comments               string
 * @var $show_title             boolean
 * @var $show_categories        boolean
 * @var $show_tags              boolean
 * @var $show_dates             boolean
 * @var $show_author            boolean
 * @var $show_comment           boolean
 * @var $show_reading_time      boolean
 * @var $show_post_views        boolean
 * @var $show_post_thumbnail    boolean
 * @var $show_author_icon       boolean
 * @var $show_comment_icon      boolean
 * @var $show_date_icon         boolean
 * @var $show_reading_time_icon boolean
 * @var $show_post_views_icon   boolean
 * @var $show_short_desc        boolean
 * @var $image_link             boolean
 * @var $show_read_more_btn     boolean
 * @var $img_html               string
 * @var $button_icon            array
 * @var $button_icon_position   string
 * @var $item_class             string
 * @var $author_icon_html       string
 * @var $date_icon_html         string
 * @var $comment_icon_html      string
 * @var $reading_time_icon_html string
 * @var $post_views_icon_html   string
 * @var $meta_separator         string
 * array
 */

// Do not allow directly accessing this file.


use RadiusTheme\SB\Elementor\Helper\PostHelpers;
use RadiusTheme\SB\Elementor\Widgets\General\PostList\Render;
use RadiusTheme\SB\Helpers\Fns;

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

?>
<div class="<?php echo esc_attr( $grid_classes ); ?>">
	<div class="<?php echo esc_attr( $item_class ); ?>">
		<article>
			<?php if ( $show_post_thumbnail ) { ?>
				<div class="rtsb-post-img rtsb-img-wrap">
					<?php
					if ( $image_link ) {
						$aria_label = esc_attr(
						/* translators: Post Name */
							sprintf( __( 'Image link for Post: %s', 'shopbuilder' ), $title )
						);
						?>
						<figure>
							<a class="rtsb-img-link" href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr( $aria_label ); ?>">
								<?php
								Fns::print_html( $img_html );
								?>
							</a>
						</figure>
						<?php
					} else {
						echo '<figure class="rtsb-img-link">';
						Fns::print_html( $img_html );
						echo '</figure>';
					}
					?>
					<?php
					if ( $show_categories || $show_tags ) {
						?>
						<ul class="rtsb-post-taxonomy-list">
							<?php
							if ( $show_categories ) {
								Fns::print_html( '<li class="rtsb-tax-item category">' );
								Fns::print_html( PostHelpers::rtsb_posted_taxonomy() );
								Fns::print_html( '</li>' );
							}
							if ( $show_tags ) {
								Fns::print_html( '<li class="rtsb-tax-item tag">' );
								Fns::print_html( PostHelpers::rtsb_posted_taxonomy( 'tag' ) );
								Fns::print_html( '</li>' );
							}
							?>

						</ul>
						<?php
					}
					?>
				</div>
			<?php } ?>
			<div class="rtsb-post-content">

				<?php
				if ( $show_title ) {
					?>
					<<?php Fns::print_validated_html_tag( $title_tag ); ?> class="rtsb-post-title limit-<?php echo esc_attr( $title_limit ); ?>">
						<?php
						if ( $title_link ) {
							?>
							<a class="rtsb-title-link" href="<?php the_permalink(); ?>"><?php Fns::print_html( $title ); ?></a>
							<?php
						} else {
							Fns::print_html( $title );
						}
						?>
					</<?php Fns::print_validated_html_tag( $title_tag ); ?>>
					<?php
				}
				if ( $show_dates || $show_author || $show_comment || $show_reading_time || $show_post_views ) {
					?>
					<ul class="rtsb-post-meta">
						<?php
						if ( $show_author ) {
							$prefix = esc_html__( 'By ', 'shopbuilder' );
							$prefix = apply_filters( 'rtsb/general_widgets/posts_author_prefix', $prefix );
							Fns::print_html( '<li class="rtsb-meta-item author">' );
							if ( $show_author_icon ) {
								Fns::print_html( $author_icon_html );
							}
							Fns::print_html( PostHelpers::rtsb_posted_by( $prefix ) );
							Fns::print_html( '</li>' );
						}
						if ( $show_dates ) {
							if ( $meta_separator ) {
								Fns::print_html( '<li class="rtsb-meta-separator">' );
								Fns::print_html( $meta_separator );
								Fns::print_html( '</li>' );
							}
							Fns::print_html( '<li class="rtsb-meta-item date">' );
							if ( $show_date_icon ) {
								Fns::print_html( $date_icon_html );
							}
							Fns::print_html( PostHelpers::rtsb_posted_date() );
							Fns::print_html( '</li>' );
						}
						if ( $show_comment ) {
							if ( $meta_separator ) {
								Fns::print_html( '<li class="rtsb-meta-separator">' );
								Fns::print_html( $meta_separator );
								Fns::print_html( '</li>' );
							}
							Fns::print_html( '<li class="rtsb-meta-item comments">' );
							if ( $show_comment_icon ) {
								Fns::print_html( $comment_icon_html );
							}
							Fns::print_html( PostHelpers::get_post_comments_number() );
							Fns::print_html( '</li>' );
						}
						if ( $show_reading_time ) {
							if ( $meta_separator ) {
								Fns::print_html( '<li class="rtsb-meta-separator">' );
								Fns::print_html( $meta_separator );
								Fns::print_html( '</li>' );
							}
							Fns::print_html( '<li class="rtsb-meta-item reading-time">' );
							if ( $show_reading_time_icon ) {
								Fns::print_html( $reading_time_icon_html );
							}
							Fns::print_html( PostHelpers::rtsb_reading_time() );
							Fns::print_html( '</li>' );
						}
						if ( $show_post_views ) {
							if ( $meta_separator ) {
								Fns::print_html( '<li class="rtsb-meta-separator">' );
								Fns::print_html( $meta_separator );
								Fns::print_html( '</li>' );
							}
							Fns::print_html( '<li class="rtsb-meta-item post-views">' );
							if ( $show_post_views_icon ) {
								Fns::print_html( $post_views_icon_html );
							}
							Fns::print_html( PostHelpers::rtsb_post_views() );
							Fns::print_html( '</li>' );
						}
						?>
					</ul>
					<?php
				}
				?>
				<?php
				if ( $show_short_desc ) {
					?>
					<div class="rtsb-post-excerpt limit-<?php Fns::print_html( $excerpt_limit ); ?>">
						<?php Fns::print_html( $excerpt ); ?>
					</div>
					<?php
				}
				?>
				<?php
				/**
				 * Button.
				 */
				if ( $show_read_more_btn ) {
					?>
					<div class="rtsb-button-wrapper">
						<a class="rtsb-readmore-btn" href="<?php the_permalink(); ?>">
							<span class="text-wrap">
								<?php
								Fns::print_html( Render::render_icon( 'left', $button_icon, $button_icon_position ) );
								Fns::print_html( $button_text );
								Fns::print_html( Render::render_icon( 'right', $button_icon, $button_icon_position ) );
								?>
							</span>
						</a>
					</div>
				<?php } ?>
			</div>
		</article>
	</div>
</div>
