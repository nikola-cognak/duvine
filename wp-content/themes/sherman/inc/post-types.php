<?php

/* --------------------------------------------
 *
 * Register post types
 *
 * -------------------------------------------- */

    //
    // @link http://codex.wordpress.org/Function_Reference/register_post_type
    //
    
    //
    // /**
    //  * register post type that has page for archive and single
    //  */
    // register_post_type('post_type', array(
    //     'labels'       => array(
    //         'name'               => __( 'post_type', 'sherman' ),
    //         'singular_name'      => __( 'post_type', 'sherman' ),
    //         'add_new_item'       => __( 'Add new post_type', 'sherman' ),
    //         'edit_item'          => __( 'Edit post_type', 'sherman' ),
    //         'new_item'           => __( 'New post_type', 'sherman' ),
    //         'view_item'          => __( 'View post_type', 'sherman' ),
    //         'search_items'       => __( 'Search post_types', 'sherman' ),
    //         'not_found'          => __( 'No post_types found', 'sherman' ),
    //         'not_found_in_trash' => __( 'No post_types found in Trash', 'sherman' ),
    //     ),
    //     'supports' => array('title', 'editor', 'thumbnail'),
    //     'public'   => true,
    //     'rewrite'  => array(
    //         'slug' => 'post_type'
    //     )
    // ));
    //

    
    //
    // /**
    //  * register post type with NO single page
    //  */
    // register_post_type('post_type', array(
    //     'labels'       => array(
    //         'name'               => __( 'post_type', 'sherman' ),
    //         'singular_name'      => __( 'post_type', 'sherman' ),
    //         'add_new_item'       => __( 'Add new post_type', 'sherman' ),
    //         'edit_item'          => __( 'Edit post_type', 'sherman' ),
    //         'new_item'           => __( 'New post_type', 'sherman' ),
    //         'view_item'          => __( 'View post_type', 'sherman' ),
    //         'search_items'       => __( 'Search post_types', 'sherman' ),
    //         'not_found'          => __( 'No post_types found', 'sherman' ),
    //         'not_found_in_trash' => __( 'No post_types found in Trash', 'sherman' ),
    //     ),
    //     'show_ui' => true,
    // ));
    //



if( !function_exists('sk_post_types') ) :
/**
 * register content types
 */
function sk_post_types(){

    
    
    
    /**
     * register post type that has page for archive and single
     */
    register_post_type('tour', array(
        'labels'        => array(
            'name'               => __( 'Tours', 'sherman' ),
            'singular_name'      => __( 'Tour', 'sherman' ),
            'add_new_item'       => __( 'Add new Tour', 'sherman' ),
            'edit_item'          => __( 'Edit Tour', 'sherman' ),
            'new_item'           => __( 'New Tour', 'sherman' ),
            'view_item'          => __( 'View Tour', 'sherman' ),
            'search_items'       => __( 'Search Tours', 'sherman' ),
            'not_found'          => __( 'No Tours found', 'sherman' ),
            'not_found_in_trash' => __( 'No Tours found in Trash', 'sherman' ),
        ),
        'supports'      => array('title', 'revisions'),
        'public'        => true,
        'menu_position' => 26,
        'rewrite'       => array(
            'slug'       => 'tour',
            'with_front' => false
        )
    ));

    
    
    
    /**
     * register post type that has page for archive and single
     */
    register_post_type('location', array(
        'labels'        => array(
            'name'               => __( 'Locations', 'sherman' ),
            'singular_name'      => __( 'Location', 'sherman' ),
            'add_new_item'       => __( 'Add new Location', 'sherman' ),
            'edit_item'          => __( 'Edit Location', 'sherman' ),
            'new_item'           => __( 'New Location', 'sherman' ),
            'view_item'          => __( 'View Location', 'sherman' ),
            'search_items'       => __( 'Search Locations', 'sherman' ),
            'not_found'          => __( 'No Locations found', 'sherman' ),
            'not_found_in_trash' => __( 'No Locations found in Trash', 'sherman' ),
        ),
        'supports'      => array('title', 'editor', 'page-attributes'),
        'hierarchical'  => true,
        'public'        => true,
        'menu_position' => 28,
        'rewrite'       => array(
            'slug'       => 'region',
            'with_front' => false
        )
    ));





    /**
     * register post type that has page for archive and single
     */
    register_post_type('tour_collection', array(
        'labels'        => array(
            'name'               => __( 'Tour Collections', 'sherman' ),
            'singular_name'      => __( 'Tour Collection', 'sherman' ),
            'add_new_item'       => __( 'Add new Tour Collection', 'sherman' ),
            'edit_item'          => __( 'Edit Tour Collection', 'sherman' ),
            'new_item'           => __( 'New Tour Collection', 'sherman' ),
            'view_item'          => __( 'View Tour Collection', 'sherman' ),
            'search_items'       => __( 'Search Tour Collections', 'sherman' ),
            'not_found'          => __( 'No Tour Collections found', 'sherman' ),
            'not_found_in_trash' => __( 'No Tour Collections found in Trash', 'sherman' ),
        ),
        'supports'      => array('title', 'editor', 'page-attributes'),
        'hierarchical'  => true,
        'public'        => true,
        'menu_position' => 27,
        'rewrite'       => array(
            'slug'       => 'collection',
            'with_front' => false
        )
    ));





    /**
     * register post type that has page for archive and single
     */
    register_post_type('bike', array(
        'labels'        => array(
            'name'               => __( 'Bikes', 'sherman' ),
            'singular_name'      => __( 'Bike', 'sherman' ),
            'add_new_item'       => __( 'Add new Bike', 'sherman' ),
            'edit_item'          => __( 'Edit Bike', 'sherman' ),
            'new_item'           => __( 'New Bike', 'sherman' ),
            'view_item'          => __( 'View Bike', 'sherman' ),
            'search_items'       => __( 'Search Bikes', 'sherman' ),
            'not_found'          => __( 'No Bikes found', 'sherman' ),
            'not_found_in_trash' => __( 'No Bikes found in Trash', 'sherman' ),
        ),
        'supports'      => array('title', 'editor'),
        'hierarchical'  => true,
        'public'        => true,
        'menu_position' => 29,
        'rewrite'       => array(
            'slug'       => 'bike',
            'with_front' => false
        )
    ));





//     /**
//      * register post type that has page for archive and single
//      */
//     register_post_type('person', array(
//         'labels'        => array(
//             'name'               => __( 'People', 'sherman' ),
//             'singular_name'      => __( 'Person', 'sherman' ),
//             'add_new_item'       => __( 'Add new Person', 'sherman' ),
//             'edit_item'          => __( 'Edit Person', 'sherman' ),
//             'new_item'           => __( 'New Person', 'sherman' ),
//             'view_item'          => __( 'View Person', 'sherman' ),
//             'search_items'       => __( 'Search People', 'sherman' ),
//             'not_found'          => __( 'No People found', 'sherman' ),
//             'not_found_in_trash' => __( 'No People found in Trash', 'sherman' ),
//         ),
//         'supports'      => array('title', 'editor'),
//         'hierarchical'  => true,
//         'public'        => true,
//         'menu_position' => 28,
//         'rewrite'       => array(
//             'slug'       => 'team',
//             'with_front' => false
//         )
//     ));

  register_post_type('person', array(
        'labels'        => array(
            'name'               => __( 'People', 'sherman' ),
            'singular_name'      => __( 'Person', 'sherman' ),
            'add_new_item'       => __( 'Add new Person', 'sherman' ),
            'edit_item'          => __( 'Edit Person', 'sherman' ),
            'new_item'           => __( 'New Person', 'sherman' ),
            'view_item'          => __( 'View Person', 'sherman' ),
            'search_items'       => __( 'Search People', 'sherman' ),
            'not_found'          => __( 'No People found', 'sherman' ),
            'not_found_in_trash' => __( 'No People found in Trash', 'sherman' ),
        ),
        'supports'      => array('title', 'editor'),
        'hierarchical'  => true,
        'public'        => true,
        'menu_position' => 28,
        'rewrite'       => array(
            'slug'       => 'staff',  // Changed from 'internal-guides' to support both staff and internal-guides URLs
            'with_front' => false
        ),
        'has_archive'        => false,  // Added to prevent archive page
        'exclude_from_search'=> true    // Added to hide from search results
    ));




    /**
     * register post type with NO single page
     */
    register_post_type('blog_author', array(
        'labels'        => array(
            'name'               => __( 'Post Author', 'sherman' ),
            'singular_name'      => __( 'Post Author', 'sherman' ),
            'add_new_item'       => __( 'Add new Post Author', 'sherman' ),
            'edit_item'          => __( 'Edit Author', 'sherman' ),
            'new_item'           => __( 'New Author', 'sherman' ),
            'view_item'          => __( 'View Author', 'sherman' ),
            'search_items'       => __( 'Search Authors', 'sherman' ),
            'not_found'          => __( 'No Authors found', 'sherman' ),
            'not_found_in_trash' => __( 'No Authors found in Trash', 'sherman' ),
        ),
        'show_ui'       => true,
        'supports'      => array('title', 'editor'),
        'menu_position' => 5
    ));






    /**
     * register post type with NO single page
     */
    register_post_type('press', array(
        'labels'              => array(
            'name'               => __( 'Press', 'sherman' ),
            'singular_name'      => __( 'Press', 'sherman' ),
            'add_new_item'       => __( 'Add new Press', 'sherman' ),
            'edit_item'          => __( 'Edit Press', 'sherman' ),
            'new_item'           => __( 'New Press', 'sherman' ),
            'view_item'          => __( 'View Press', 'sherman' ),
            'search_items'       => __( 'Search Press', 'sherman' ),
            'not_found'          => __( 'No Press found', 'sherman' ),
            'not_found_in_trash' => __( 'No Press found in Trash', 'sherman' ),
        ),
        'menu_position'       => 30,
        'supports'            => array('title', 'editor'),
        'show_ui'             => true,
        'exclude_from_search' => false,
    ));




    /**
     * register post type with NO single page
     */
    register_post_type('publication', array(
        'labels'        => array(
            'name'               => __( 'Publication', 'sherman' ),
            'singular_name'      => __( 'Publication', 'sherman' ),
            'add_new_item'       => __( 'Add new Publication', 'sherman' ),
            'edit_item'          => __( 'Edit Publication', 'sherman' ),
            'new_item'           => __( 'New Publication', 'sherman' ),
            'view_item'          => __( 'View Publication', 'sherman' ),
            'search_items'       => __( 'Search Publications', 'sherman' ),
            'not_found'          => __( 'No Publications found', 'sherman' ),
            'not_found_in_trash' => __( 'No Publications found in Trash', 'sherman' ),
        ),
        'show_ui'       => true,
        'supports'      => array( 'title', 'thumbnail' ),
        'menu_position' => 31
    ));


    

    

}

add_action('init', 'sk_post_types');
endif; // sk_post_types