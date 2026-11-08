<?php

get_header(); ?>

    <div class="wrap">

        <div id="primary" class="content-area">
            <main id="main" class="site-main" role="main">
				<?php //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                <?php echo do_action('ae_pro_search'); ?>s

            </main><!-- #main -->
        </div><!-- #primary -->
    </div><!-- .wrap -->

<?php
get_footer();
