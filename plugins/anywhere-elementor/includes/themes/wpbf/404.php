<?php
/**
 * 404 Page
 *
 * Displayed if a page couldn't be found.
 *
 * @package Page Builder Framework
 */

// exit if accessed directly
if (! defined('ABSPATH') ) {
    exit;
}

get_header(); ?>

    <div id="content">

        <div id="inner-content" class="wpbf-container wpbf-container-center">

            <main id="main" class="wpbf-main" itemscope itemprop="mainContentOfPage" itemtype="http://schema.org/Blog">

                <article id="post-not-found" class="wpbf-post wpbf-404 wpbf-text-center">
					<?php //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    <?php echo do_action('aepro_404'); ?>
                </article>

            </main>
        </div>

    </div>

<?php get_footer(); ?>
