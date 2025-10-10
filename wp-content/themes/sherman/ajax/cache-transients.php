<?php
/**
 * provides functions for caching markup to the database 
 */


if( !function_exists('duvine_ajax_save_instagram_markup')) :
/**
 * Sends markup to be saved as a transient, so that we don't have to hit the
 *  Instagram API every time a user hits the site
 */
function duvine_ajax_save_instagram_markup(){

    if( isset( $_POST['markup'] ) ){
        set_transient( 'duvine_instagram_markup', $_POST['markup'], 60*60*2 );
    }

    wp_die();

}
add_action( 'wp_ajax_save_instagram_markup', 'duvine_ajax_save_instagram_markup' );
add_action( 'wp_ajax_nopriv_save_instagram_markup', 'duvine_ajax_save_instagram_markup' );
endif; // duvine_ajax_save_instagram_markup

