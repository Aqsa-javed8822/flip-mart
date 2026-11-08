<?php

get_header();

do_action('genesis_before_content_sidebar_wrap');

genesis_markup(
    [
        'open'    => '<div %s>',
        'context' => 'content-sidebar-wrap',
    ]
);


do_action('genesis_before_content');
genesis_markup(
    [
        'open'    => '<main %s>',
        'context' => 'content',
    ]
);
do_action('genesis_before_loop');

if (have_posts() ) :
	//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    echo do_action('aepro_archive_data', '');
                    else :
                        get_template_part('template-parts/content', 'none');
                    endif;

                    do_action('genesis_after_loop');
                    genesis_markup(
                        [
                            'close'   => '</main>', // End .content.
                            'context' => 'content',
                        ]
                    );
                    do_action('genesis_after_content');

                    genesis_markup(
                        [
                            'close'   => '</div>',
                            'context' => 'content-sidebar-wrap',
                        ]
                    );

                    do_action('genesis_after_content_sidebar_wrap');

                    get_footer();
