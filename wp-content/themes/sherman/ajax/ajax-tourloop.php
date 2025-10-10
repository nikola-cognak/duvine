<?php
/**
 * Ajax functions to filter and lazlyload the tourloop
 */


if( !function_exists( 'duvine_filter_tourloop' ) ) :
/**
 * Filters the tourloop
 */
function duvine_filter_tourloop(){

    if(!isset($_REQUEST)){
        wp_send_json_error('There was no request data.');
    }

    $orderbyArgs = duvine_get_orderby_args();
    $tourArgs = array_merge(array('duvine_show_count' => true, 'duvine_show_you_may_also_like' => true), $orderbyArgs);

    // set up the query vars
    duvine_ajax_set_query_vars();

    $tourloop = duvine_get_tourloop_markup($tourArgs);


    wp_send_json_success(array(
        'markup' => $tourloop
    ));
}
add_action('wp_ajax_filter_tourloop', 'duvine_filter_tourloop');
add_action('wp_ajax_nopriv_filter_tourloop', 'duvine_filter_tourloop');
endif; // duvine_filter_tourloop





if( !function_exists( 'duvine_lazyload_tours' ) ) :
/**
 * Lazyload the tours
 */
function duvine_lazyload_tours(){

    if(!isset( $_REQUEST)){
        wp_send_json_error('There was no request data.');
    }

    if(!isset($_REQUEST['offset'])){
        wp_send_json_error('There was no offset specified.');
    }

    $offset = $_REQUEST['offset'];
    $orderbyArgs = duvine_get_orderby_args();
    $tourArgs = array_merge(array('offset' => $offset, 'duvine_show_count' => true), $orderbyArgs);


    // set up the query vars
    duvine_ajax_set_query_vars();

    $queryArgs = duvine_build_tour_queryargs($tourArgs);
    $tourQuery = new WP_Query($queryArgs);

    // we need to handle price sorting here since pricing is in Centaur, not in the CMS
    if(isset($_REQUEST['sortby']) && $_REQUEST['sortby'] === 'price' && $tourQuery->have_posts()) {
        $postsPrice = [];
        $posts = [];
        foreach ($tourQuery->posts as $post) {
            $posts[$post->ID] = $post;
            if (get_field('d_private_only', $post->ID)) {
                $price = get_field('d_private_starting_price', $post->ID);
                if (!is_numeric($price)) {
                    $price = (int)preg_replace('/[^0-9]/', '', $price);
                }
            } else {
                $centaurId = get_field('d_tour_centaur_id', $post->ID);
                $price = duvine_get_minimum_price($centaurId);
                if (!$price) {
                    $price = get_field('d_private_starting_price', $post->ID);
                }
                $price = (int)preg_replace('/[^0-9]/', '', $price);
            }
            $postsPrice[$post->ID] = $price;
        }
        if ($queryArgs['order'] === 'ASC') {
            asort($postsPrice);
        } else {
            arsort($postsPrice);
        }
        $orderedPosts = [];
        foreach ($postsPrice as $key => $item) {
            $orderedPosts[] = $posts[$key];
        }
        $tourQuery->posts = $orderedPosts;
        unset($orderedPosts, $postsPrice, $posts);
    }

    if(!$tourQuery->have_posts()){
        wp_send_json_error('There were no additional posts.');
    }

    // start output
    ob_start();

    while ( $tourQuery->have_posts() ) : $tourQuery->the_post();
        duvine_render_tour_placard();
    endwhile;

    wp_reset_postdata(); /* Restore original Post Data */

    $returnMarkup = ob_get_clean();
    $returnJson = array(
        'markup'     => $returnMarkup,
        'post_count' => $tourQuery->post_count
    );

    if( $offset + $tourQuery->post_count >= $tourQuery->found_posts ){
        $returnJson['hide_loadmore'] = true;
    }

    wp_send_json_success( $returnJson );

}
add_action( 'wp_ajax_lazyload_tours', 'duvine_lazyload_tours' );
add_action( 'wp_ajax_nopriv_lazyload_tours', 'duvine_lazyload_tours' );
endif; // duvine_lazyload_tours






/* -------------------------------------------------
 *
 * --util
 *
 * ------------------------------------------------- */

if( !function_exists( 'duvine_ajax_set_query_vars' ) ) :
/**
 * Sets query vars for the ajax calls
 */
function duvine_ajax_set_query_vars(){

    if( !isset( $_REQUEST['filters'] ) ){
        return false;
    }

    if( isset( $_REQUEST['filters']['destination'] ) ){
        set_query_var( 'destination', $_REQUEST['filters']['destination'] );
    }
    if( isset( $_REQUEST['filters']['level'] ) ){
        set_query_var( 'level', $_REQUEST['filters']['level'] );
    }
    if( isset( $_REQUEST['filters']['collection'] ) ){
        set_query_var( 'collection', $_REQUEST['filters']['collection'] );
    }
    if( isset( $_REQUEST['filters']['taxfilter_landscape'] ) ){
        set_query_var( 'taxfilter_landscape', $_REQUEST['filters']['taxfilter_landscape'] );
    }
    if( isset( $_REQUEST['filters']['taxfilter_activity'] ) ){
        set_query_var( 'taxfilter_activity', $_REQUEST['filters']['taxfilter_activity'] );
    }
    if( isset( $_REQUEST['filters']['taxfilter_culinary'] ) ){
        set_query_var( 'taxfilter_culinary', $_REQUEST['filters']['taxfilter_culinary'] );
    }
    if( isset( $_REQUEST['filters']['taxfilter_culture'] ) ){
        set_query_var( 'taxfilter_culture', $_REQUEST['filters']['taxfilter_culture'] );
    }
    if( isset( $_REQUEST['filters']['date'] ) ){
        set_query_var( 'date', $_REQUEST['filters']['date'] );
    }
    if( isset( $_REQUEST['filters']['tourtype'] ) ){
        set_query_var( 'tourtype', $_REQUEST['filters']['tourtype'] );
    }
    if( isset( $_REQUEST['filters']['duration'] ) ){
        set_query_var( 'duration', $_REQUEST['filters']['duration'] );
    }
    if( isset( $_REQUEST['filters']['morefilters'] ) ){
        set_query_var( 'morefilters', $_REQUEST['filters']['morefilters'] );
    }
}
endif; // duvine_ajax_set_query_vars




if( !function_exists( 'duvine_get_orderby_args' ) ) :
/**
 * Gets the orderby query based on the request
 */
function duvine_get_orderby_args(){
    $orderbyArgs = array();

    $orderby = isset( $_REQUEST['sortby'] ) ? $_REQUEST['sortby'] : false;

    if( ! $orderby ){
        return $orderbyArgs;
    }

    // pricing is now in Centaur, so we can't order by price via WP
    if( $orderby === 'price' ){
        $orderbyArgs['meta_key'] = 'd_private_starting_price';
        $orderbyArgs['orderby'] = 'meta_value_num';
        $orderbyArgs['order'] = 'ASC';
        $orderbyArgs['meta_type'] = 'NUMERIC';
    } elseif ( $orderby === 'title' ){
        $orderbyArgs['orderby'] = 'post_title';
        $orderbyArgs['order']   = 'ASC';
    }

    return $orderbyArgs;
}
endif; // duvine_get_orderby_args

