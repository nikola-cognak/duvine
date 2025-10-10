<?php
//***************************************************************************
// Ajax request
//***************************************************************************
if( duvine_is_ajax_request() ) {
    do_action('wp_head');

    while ( have_posts() ) {
        the_post();
        get_template_part( 'partials/videos', 'ajax' );
    }

    do_action('wp_footer');
}
//***************************************************************************
// Normal HTTP request
//***************************************************************************
else {

    get_header();

    while ( have_posts() ) {
        the_post();
        get_template_part( 'partials/videos', 'http' );
    }

    get_footer();

} // if duvine_is_ajax_request
