<?php
/**
 * Ajax functions to filter and lazlyload the pressloop
 *
 * Thisscript would ajax load the press posts based on a filter parameter on
 *  the page. Instead, we've opted to go with the browser's reload
 *  implementation rather than ajax this content.
 */


if( !function_exists( 'duvine_filter_pressloop' ) ) :
/**
 * Filters the tourloop
 */
function duvine_filter_pressloop(){

    if( ! isset( $_REQUEST ) ){
        wp_send_json_error('There was no request data.');
    }

    $pressArgs = array (
        'posts_per_page' => 10,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'post_type'      => 'press',
        'meta_query'     => array(
            'relation' => 'AND',
            array(
                'relation' => 'OR',
                array(
                    'key' => 'd_press_featured',
                    'compare' => 'NOT EXISTS' 
                ),
                array(
                    'key' => 'd_press_featured',
                    'value' => 0
                )
            )
        )
    );

    if( isset( $_REQUEST['publication'] ) ) {
        $publicationId = duvine_get_post_by_slug( $_REQUEST['publication'], 'publication' );
        array_push( $pressArgs['meta_query'], array(
            'key'     => 'd_press_publication',
            'value'   => $publicationId,
            'compare' => 'LIKE'
        ));
    }

    $pressQuery = new WP_Query( $pressArgs );

    ob_start();
?>
    <?php if( $pressQuery->have_posts() ) : ?>
        <div class="press__articleholder loadmore__mainposts">
            <?php while( $pressQuery->have_posts() ) : $pressQuery->the_post(); ?>
                <?php get_template_part( 'content', 'press-article' ); ?>
            <?php endwhile; ?>
        </div>

        <div class="t-center">
            <?php sk_load_more_button( $pressQuery, 'press-article' ); ?>
        </div>
    <?php else : ?>
        <p>No posts found.</p>
    <?php endif; ?>
    
<?php

    wp_reset_postdata();

    $pressloop = ob_get_clean();

    error_log( print_r( $pressloop, true ) );

    wp_send_json_success( array(
        'markup' => $pressloop
    ));

}
add_action( 'wp_ajax_filter_press_posts', 'duvine_filter_pressloop' );
add_action( 'wp_ajax_nopriv_filter_press_posts', 'duvine_filter_pressloop' );
endif; // duvine_filter_pressloop

