<?php
/* ========================================================
 *
 * Admin filters and functions
 *
 * ======================================================== */


/**
 * @var $roleObject WP_Role
 */
$roleObject = get_role( 'editor' );
if (!$roleObject->has_cap( 'edit_theme_options' ) ) {
    $roleObject->add_cap( 'edit_theme_options' );
}


if( !function_exists( 'duvine_update_redirection_plugin_permissions' ) ) :
/**
 * Redirection Plugin Editor access
 */
function duvine_update_redirection_plugin_permissions(){
    return 'edit_pages';
}
add_filter( 'redirection_role', 'duvine_update_redirection_plugin_permissions' );
endif; // duvine_update_redirection_plugin_permissions



/* -------------------------------------------------
 * --general styling
 * ------------------------------------------------- */


if( !function_exists('sk_admin_styles') ) :
/**
 * Add custom css to the admin
 */
function sk_admin_styles() {
?>
    <style>
        .acf-repeater.-block table tr + tr td{
            border-top-color: #ccc;
            border-top-width: 2px;
        }
        .acf-repeater.-block table tr:nth-child(even) .acf-fields{
            background-color: #f9f9f9;
        }
        
        @media screen and ( min-width: 1316px){
            #duvine-admin-tour-dates .acf-fields .acf-field{
                width: 20% !important;
            }

            #duvine-admin-tour-dates .acf-fields .acf-field.acf-c0{
                clear: none !important;
            }
        }
        
    </style>
<?php
}
add_action('admin_head', 'sk_admin_styles');
endif; // sk_admin_styles



/* --------------------------------------------
 * --WYSIWYG
 * -------------------------------------------- */


/**
 * add block formats to the wysiwyg editor
 *
 * @param $init : the object that drives the wysiwyg
 * @return the modified object that represents the wysiwyg
 */
function sk_modify_wysiwyg( $init ) {
    $init['block_formats'] = 'Paragraph=p;Subhead 1=h3; Subhead 2=h4';
    return $init;
}
add_filter('tiny_mce_before_init', 'sk_modify_wysiwyg');




/**
 * filter to add a style select dropdown to the WYSIWYG editor
 */
function sk_add_style_select( $buttons ) {
    array_unshift( $buttons, 'styleselect' );
    return $buttons;
}
add_filter('mce_buttons_2', 'sk_add_style_select');




/**
 * filter to add a the actual styles to the wysiwyg
 */
function sk_custom_wysiwyg_classes( $init_array ) {  
    // Define the style_formats array
    $style_formats = array( 
        /**
         * Each array child is a format with it's own settings
         *
         * style format arguments
         * https://codex.wordpress.org/TinyMCE_Custom_Styles
         *
         * title      - the value that will appear in the "Formats" dropdown in the WYSIWYG
         * inline     – Name of the inline element to produce for example "span". The current text selection will be wrapped in this inline element.
         * block      – Name of the block element to produce for example "h1". Existing block elements within the selection gets replaced with the new block element.
         * selector   – CSS 3 selector pattern to find elements within the selection by. This can be used to apply classes to specific elements or complex things like odd rows in a table. Note that if you combine both selector and block then you can get more nuanced behavior where the button changes the class of the selected tag by default, but adds the block tag around the cursor if the selected tag isn't found.
         * classes    – Space separated list of classes to apply to the selected elements or the new inline/block element.
         * styles     – Name/value object with CSS style items to apply such as color etc.
         * attributes – Name/value object with attributes to apply to the selected elements or the new inline/block element.
         * exact      – Disables the merge similar styles feature when used. This is needed for some CSS inheritance issues such as text-decoration for underline/strikethrough.
         * wrapper    – State that tells that the current format is a container format for block elements. For example a div wrapper or blockquote.
         */
        array(  
            'title'   => 'Intro',
            'inline'  => 'span',
            'classes' => 'd-content__leade',
            'wrapper' => true,
        ),

        array(
            'title'   => 'No link hover',
            'inline'  => 'span',
            'classes' => 'nolink-hover',
            'wrapper' => true,
        )
    );
    // Insert the array, JSON ENCODED, into 'style_formats'
    $init_array['style_formats'] = json_encode( $style_formats );
    
    return $init_array;  
  
} 
add_filter( 'tiny_mce_before_init', 'sk_custom_wysiwyg_classes' );


if( !function_exists( 'sk_custom_wysiwyg_styles' ) ) :
/**
 * description
 */
function sk_custom_wysiwyg_styles(){
    add_editor_style( get_template_directory_uri() . '/admin/css/wysiwyg-style.css' );
}
add_action('admin_enqueue_scripts', 'sk_custom_wysiwyg_styles');
endif; // sk_custom_wysiwyg_styles





/* --------------------------------------------
 * --thumbnails and scaling
 * -------------------------------------------- */



if( !function_exists('sk_image_crop_dimensions') ) :
/**
 * filter to allow the upscaling of images to fit their assigned dimensions
 *
 * @link http://wordpress.stackexchange.com/questions/50649/how-to-scale-up-featured-post-thumbnail
 */
function sk_image_crop_dimensions($default, $orig_w, $orig_h, $new_w, $new_h, $crop) {
    if(!$crop)
        return null; // let the wordpress default function handle this

    $aspect_ratio = $orig_w / $orig_h;
    $size_ratio = max($new_w / $orig_w, $new_h / $orig_h);

    $crop_w = round($new_w / $size_ratio);
    $crop_h = round($new_h / $size_ratio);

    $s_x = floor( ($orig_w - $crop_w) / 2 );
    $s_y = floor( ($orig_h - $crop_h) / 2 );

    return array( 0, 0, (int) $s_x, (int) $s_y, (int) $new_w, (int) $new_h, (int) $crop_w, (int) $crop_h );
}
// add_filter('image_resize_dimensions', 'sk_image_crop_dimensions', 10, 6);
endif; // sk_image_crop_dimensions



/* --------------------------------------------
 * --administrative filters
 * -------------------------------------------- */


if( !function_exists('sk_add_featured_image_instruction') ) :
/**
 * adds instructions for the featured image field
 *
 * @param $content (string)
 *   - the markup for the block
 */
function sk_add_featured_image_instruction( $content ) {
    global $post;

    if( !$post || ! isset($post->post_type) ){
        return $content;
    }

    $post_type = $post->post_type;

    $helper_text = '';

    switch ($post_type) {
        case 'publication':
            $helper_text = "Include the publication logo here.";
            break;
    }


    return $helper_text . $content;
}
add_filter( 'admin_post_thumbnail_html', 'sk_add_featured_image_instruction');
endif; // sk_add_featured_image_instruction




if( !function_exists('sk_admin_tour_columns') ) :
/**
 * set up the columns for the tour admin page.
 *
 * @param array $columns The list of columns
 */
function sk_admin_tour_columns( $columns ) {
    $columns['tour_location'] = 'Tour Location';
    $columns['tour_collections'] = 'Tour Location';
    return $columns;
}
add_action( 'manage_tour_posts_columns' , 'sk_admin_tour_columns' );
endif; // sk_admin_tour_columns




if( !function_exists('sk_admin_person_columns') ) :
/**
 * set up the columns for the person admin page.
 *
 * @param array $columns The list of columns
 */
function sk_admin_person_columns( $columns ) {
    $columns['person_role'] = 'Role';
    return $columns;
}
add_action( 'manage_person_posts_columns' , 'sk_admin_person_columns' );
endif; // sk_admin_person_columns



//
// custom, sortable column for press
//

if( !function_exists( 'sk_admin_press_columns' ) ) :
/**
 * Admin Press columns
 */
function sk_admin_press_columns( $columns ){
    $columns['press_publication'] = 'Publication';
    return $columns;
}
add_action( 'manage_press_posts_columns' , 'sk_admin_press_columns' );
endif; // sk_admin_press_columns


if( !function_exists( 'sk_admin_sortable_press_columns' ) ) :
/**
 * Make the press columns sortable
 */
function sk_admin_sortable_press_columns( $columns ){
    $columns['press_publication'] = 'press_publication';
    return $columns;
}
add_filter( 'manage_edit-press_sortable_columns' , 'sk_admin_sortable_press_columns' );
endif; // sk_admin_sortable_press_columns



//
// custom, sortable column for posts
//


if( !function_exists( 'sk_admin_posts_columns' ) ) :
/**
 * Admin Press columns
 */
function sk_admin_posts_columns( $columns ){
    $columns['post_featured'] = 'Featured';
    return $columns;
}
add_action( 'manage_post_posts_columns' , 'sk_admin_posts_columns' );
endif; // sk_admin_posts_columns



if( !function_exists( 'sk_admin_sortable_post_columns' ) ) :
/**
 * Make the press columns sortable
 */
function sk_admin_sortable_post_columns( $columns ){
    $columns['post_featured'] = 'post_featured';
    return $columns;
}
add_filter( 'manage_edit-post_sortable_columns' , 'sk_admin_sortable_post_columns' );
endif; // sk_admin_sortable_post_columns



//
// handle custom columns
//



if( !function_exists('sk_admin_custom_columns') ) :
/**
 * in the admin section, display custom columns
 *
 * @param string $name The name of the column, as set in cc_admin_custom_post_columns
 */
function sk_admin_custom_columns($name) {
    global $post;

    switch ($name) {
        case 'tour_location':
            if( $locObj = get_field('d_tour_location') ){
                foreach( $locObj as $index => $locID ){
                    if( $index > 0 ){
                        echo ', ';
                    }
                    echo get_the_title( $locID );
                }

            } else {
                echo '-';
            }
            break;
        case 'tour_collections' :
        
            if( $collections = get_field('d_tour_collection') ){
                foreach( $collections as $index => $collID ){
                    if( $index > 0 ){
                        echo ', ';
                    }
                    echo get_the_title( $collID );
                }

            } else {
                echo '-';
            }
            break;
        case 'press_publication' :
            $publication = get_field('d_press_publication');
            echo get_the_title( $publication );
            break;
        case 'post_featured' :
            echo get_field('d_featured_post') ? 'Featured' : '';
            break;
    }
}
add_action('manage_posts_custom_column',  'sk_admin_custom_columns');
endif; // sk_admin_custom_columns



if( !function_exists( 'sk_admin_custom_person_columns' ) ) :
/**
 * description
 */
function sk_admin_custom_person_columns( $name ){
    global $post;

    switch ($name) {
        case 'person_role' :

            if( $roles = get_the_terms( $post, 'role') ){
                foreach( $roles as $index => $role ){
                    if( $index > 0 ){
                        echo ', ';
                    }
                    echo $role->name;
                }

            } else {
                echo '-';
            }
            break;
    }
}
add_action('manage_person_posts_custom_column', 'sk_admin_custom_person_columns');
endif; // sk_admin_custom_person_columns




if( !function_exists( 'duvine_admin_column_sorting_query' ) ) :
/**
 * Query adjustment for sorting by custom post columns
 */
function duvine_admin_column_sorting_query( $query ){
    if( is_admin() ){
        $orderby = $query->get( 'orderby');

        if( $query->get('orderby') === 'press_publication' ){
            $query->set('meta_key', 'd_press_publication');
            $query->set('orderby', 'meta_value');
        } elseif ( $query->get('orderby') === 'post_featured' ){
            $query->set('meta_key', 'd_featured_post');
            $query->set('orderby', 'meta_value');
        }
    }
}
add_filter( 'pre_get_posts', 'duvine_admin_column_sorting_query');
endif; // duvine_admin_column_sorting_query









if( !function_exists('sk_menu_page_removing') ) :
/**
 * hides admin menu items
 * @link - https://codex.wordpress.org/Function_Reference/remove_menu_page
 */
function sk_menu_page_removing() {
    remove_menu_page( 'edit-comments.php' );

}
add_action( 'admin_menu', 'sk_menu_page_removing' );
endif; // sk_menu_page_removing




if( !function_exists('sk_google_maps_api_key') ) :
function sk_google_maps_api_key() {
    
    acf_update_setting('google_api_key', 'AIzaSyCkWXdm7lkeOIl233uZHBVtRWEU297FUNI');
}

add_action('acf/init', 'sk_google_maps_api_key');
endif; // sk_google_maps_api_key




if( !function_exists('duvine_remove_thumbnail_box') ) :
function duvine_remove_thumbnail_box() {
    remove_meta_box( 'postimagediv','post','side' );
}
add_action('do_meta_boxes', 'duvine_remove_thumbnail_box');
endif; // duvine_remove_thumbnail_box