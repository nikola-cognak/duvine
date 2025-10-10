<?php

/* --------------------------------------------
 *
 * Register taxonomies
 *
 * -------------------------------------------- */

    //
    // @link http://codex.wordpress.org/Function_Reference/register_taxonomy
    //
    //
    // register_taxonomy('tax_name', array('related_post_type'), array(
    //     'labels'                => array(
    //         'name'                       => __( 'tax_name', 'sherman' ),
    //         'separate_items_with_commas' => __( 'Separate tax_name with commas', 'sherman' ),
    //         'choose_from_most_used'      => __( 'Choose from the most used tax_name', 'sherman' ),
    //     ),
    //     'hierarchical'          => false,
    //     'show_admin_column'     => true,
    //     'update_count_callback' => '_update_post_term_count'
    // ));
    // register_taxonomy_for_object_type( 'tax_name', 'related_post_type' );
    //



if( !function_exists('sk_taxonomies') ) :
/**
 * Register taxonomies. Insert taxonomy registrations in here
 */
function sk_taxonomies(){

    //
    // TOUR TAXONOMIES
    //

    register_taxonomy('landscape', array('tour'), array(
        'labels'                => array(
            'name'                       => __( 'Tour Landscape', 'sherman' ),
            'separate_items_with_commas' => __( 'Separate Landscapes with commas', 'sherman' ),
            'choose_from_most_used'      => __( 'Choose from the most used Landscapes', 'sherman' ),
        ),
        'hierarchical'          => false,
        'show_ui'               => true,
        'show_in_quick_edit'    => false,
        'meta_box_cb'           => false,
        'update_count_callback' => '_update_post_term_count'
    ));
    register_taxonomy_for_object_type( 'landscape', 'tour' );



    register_taxonomy('activity', array('tour'), array(
        'labels'                => array(
            'name'                       => __( 'Activities', 'sherman' ),
            'separate_items_with_commas' => __( 'Separate Activities with commas', 'sherman' ),
            'choose_from_most_used'      => __( 'Choose from the most used Activities', 'sherman' ),
        ),
        'hierarchical'          => false,
        'show_ui'               => true,
        'show_in_quick_edit'    => false,
        'meta_box_cb'           => false,
        'update_count_callback' => '_update_post_term_count'
    ));
    register_taxonomy_for_object_type( 'activity', 'tour' );



    register_taxonomy('culinary', array('tour'), array(
        'labels'                => array(
            'name'                       => __( 'Culinary Experiences', 'sherman' ),
            'separate_items_with_commas' => __( 'Separate Culinary Experiences with commas', 'sherman' ),
            'choose_from_most_used'      => __( 'Choose from the most used Culinary Experiences', 'sherman' ),
        ),
        'hierarchical'          => false,
        'show_ui'               => true,
        'show_in_quick_edit'    => false,
        'meta_box_cb'           => false,
        'update_count_callback' => '_update_post_term_count'
    ));
    register_taxonomy_for_object_type( 'culinary', 'tour' );



    register_taxonomy('culture', array('tour'), array(
        'labels'                => array(
            'name'                       => __( 'Cultural Experiences', 'sherman' ),
            'separate_items_with_commas' => __( 'Separate Cultural Experiences with commas', 'sherman' ),
            'choose_from_most_used'      => __( 'Choose from the most used Cultural Experiences', 'sherman' ),
        ),
        'hierarchical'          => false,
        'show_ui'               => true,
        'show_in_quick_edit'    => false,
        'meta_box_cb'           => false,
        'update_count_callback' => '_update_post_term_count'
    ));
    register_taxonomy_for_object_type( 'culture', 'tour' );






    //
    // BIKE TAXONOMIES
    //

    register_taxonomy('manufacturer', array('bike'), array(
        'labels'                => array(
            'name'                       => __( 'Bike Manufacturer', 'sherman' ),
            'separate_items_with_commas' => __( 'Separate Manufacturers with commas', 'sherman' ),
            'choose_from_most_used'      => __( 'Choose from the most used Manufacturers', 'sherman' ),
        ),
        'hierarchical'          => false,
        'show_ui'               => true,
        'show_in_quick_edit'    => false,
        'meta_box_cb'           => false,
        'update_count_callback' => '_update_post_term_count'
    ));
    register_taxonomy_for_object_type( 'manufacturer', 'bike' );


    register_taxonomy('bike_type', array('bike'), array(
        'labels'                => array(
            'name'                       => __( 'Bike Type', 'sherman' ),
            'separate_items_with_commas' => __( 'Separate Bike Types with commas', 'sherman' ),
            'choose_from_most_used'      => __( 'Choose from the most used Bike Types', 'sherman' ),
        ),
        'hierarchical'          => false,
        'show_ui'               => true,
        'show_in_quick_edit'    => false,
        'meta_box_cb'           => false,
        'update_count_callback' => '_update_post_term_count'
    ));
    register_taxonomy_for_object_type( 'bike_type', 'bike' );



    //
    // PERSON TAXONOMIES
    //

    register_taxonomy('role', array('person'), array(
        'labels'                => array(
            'name'                       => __( 'Role', 'sherman' ),
            'separate_items_with_commas' => __( 'Separate Roles with commas', 'sherman' ),
            'choose_from_most_used'      => __( 'Choose from the most used Roles', 'sherman' ),
        ),
        'hierarchical'          => true,
        'show_ui'               => true,
        'update_count_callback' => '_update_post_term_count'
    ));
    register_taxonomy_for_object_type( 'role', 'person' );


}
add_action('init', 'sk_taxonomies');
endif; // sk_taxonomies
