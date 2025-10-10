<?php
/**
 * Creates a settings page for global information about tours
 */


if( function_exists('acf_add_options_page') ) :



// add options page for page links
acf_add_options_page(array(
    'page_title' => 'Contact Info',
    'capability' => 'edit_posts',
    'position'   => '1002.1'
));

// add options page for page links
acf_add_options_page(array(
    'page_title' => 'Tooltips',
    'capability' => 'edit_posts',
    'position'   => '1002.2'
));

// add options page for page links
acf_add_options_page(array(
    'page_title' => 'Miscellaneous copy',
    'capability' => 'edit_posts',
    'position'   => '1002.3'
));


// add options page
acf_add_options_page(array(
    'page_title' => 'Press Recognition',
    'capability' => 'edit_posts',
    'position'   => '1002.4'
));

// add options page for page links
acf_add_options_page(array(
    'page_title' => 'Important pages',
    'capability' => 'edit_posts',
    'position'   => '1002.5'
));

// add options page for 404 page content
acf_add_options_page(array(
    'page_title' => '404 Page',
    'capability' => 'edit_posts',
    'position'   => '1002.6'
));






endif; // acf_add_options_page




if( function_exists('acf_add_options_sub_page') ) :

// add sub page
acf_add_options_sub_page(array(
    'page_title'  => 'Tour Inclusions',
    'menu_title'  => 'Tour Inclusions',
    'parent_slug' => 'edit.php?post_type=tour',
    'capability'  => 'edit_posts',
    'position'    => 2
));



    
endif; // acf_add_options_sub_page
