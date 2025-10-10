<?php
/* ========================================================
 *
 * Custom theme actions and filters for the front end
 *
 * ======================================================== */


/* -------------------------------------------------
 *
 * --querying posts
 *
 * ------------------------------------------------- */

if( !function_exists( 'duvine_posts_where' ) ) :
/**
 * Update the WHERE clause in the query to include some crazy ACF queries
 */
function duvine_posts_where( $where ) {
    global $wpdb;
    $where = str_replace("meta_key = 'd_tour_schedules_%", "meta_key LIKE 'd_tour_schedules_%", $wpdb->remove_placeholder_escape($where));
    return $where;
}

add_filter('posts_where', 'duvine_posts_where');
endif; // duvine_posts_where










/* -------------------------------------------------
 *
 * --config core functions
 *
 * ------------------------------------------------- */

if( !function_exists( 'duvine_add_menu_anochor_classes' ) ) :
/**
 * Adds classes to the <a> tag in wo menus
 */
function duvine_add_menu_anochor_classes( $atts, $item, $args ){
    
    if( $args->theme_location === 'footer_utility' ){
        $atts['class'] = 'underlinedlink';
    }

    return $atts;
}
add_filter( 'nav_menu_link_attributes', 'duvine_add_menu_anochor_classes', 10, 3 );
endif; // duvine_add_menu_anochor_classes






/* --------------------------------------------
 *
 * --rendering fields
 *
 * -------------------------------------------- */


if( !function_exists('sk_create_img_markup') ) :
/**
 * filter to render an image from a custom field
 *
 * @param $img_obj (array)
 *   - the image object from wordpress
 */
function sk_create_img_markup( $img_obj, $args = array() ){

    if(!$img_obj){
        return '';
    }

    $defaults = array(
        'img_size' => '',
    );
    $options = array_merge($defaults, $args);

    $size = $options['img_size'];
    $img_src = $size !== '' && isset( $img_obj['sizes'][ $size ] ) ? $img_obj['sizes'][ $size ] : $img_obj['url'];
    $alt = strlen($img_obj['alt']) > 0 ? $img_obj['alt'] : '';
    return '<img src="' . $img_src . '" alt="'.$alt.'">';

}
add_filter('sk_img_markup', 'sk_create_img_markup', 10, 3);
endif; // sk_create_img_markup






if( !function_exists( 'sk_create_background_image_style') ) :
/**
 * Return a background image for the given image object
 *
 * @param object $img_obj All the image data
 * @param array  $args    Arguments for this filter
 */
function sk_create_background_image_style( $img_obj, $args = array() ){

    if( !$img_obj){
        return '';
    }

    $defaults = array(
        'img_size' => '',
    );
    $options = array_merge($defaults, $args);
    $size = $options['img_size'];
    $imgSrc = $size !== '' && isset( $img_obj['sizes'][ $size ] ) ? $img_obj['sizes'][ $size ] : $img_obj['url'];
    return 'background-image: url(' . $imgSrc . ');';

}
add_filter('sk_background_image', 'sk_create_background_image_style', 10, 2);
endif; // sk_create_background_image_style





if( !function_exists('sk_link_email') ) :
/**
 * render an email address as a linked link
 *
 * @param $email (string)
 *   - the email address
 */
function sk_link_email( $email ){
    $email = '<a href="mailto:' . $email . '">' . $email . '</a>';

    return $email;
}
add_filter('sk_link_email', 'sk_link_email');
endif; // sk_link_email




if( !function_exists('sk_sanitize_svg') ) :
/**
 * sanitize any values coming through the CMS that should be output
 *  as HTML and make sure that it's svg code
 */
function sk_sanitize_svg( $markup ){
    $markup = trim( $markup );

    $first = substr( $markup, 0, 5);
    $last = substr( $markup, -6);

    // check to make sure the string starts with a valid svg tag, and
    //  ends with the closing svg tag
    if( $first !== '<svg ' || $last !== '</svg>'){
        return '';
    }

    // check to ensure there are no script tags
    if( strpos( $markup, 'script' ) !== false ){
        return '';
    }

    return $markup;
}
add_filter('sk_sanitize_svg', 'sk_sanitize_svg');
endif; // sk_sanitize_svg




if( !function_exists('sk_youtube_video_embed')) :
/**
 * creates markup for a youtube video embed
 *
 * @param $markup (string)
 *   - the youtube ID, from the CMS
 */
function sk_youtube_video_embed($id){

    return '<div class="iframe-container"><iframe width="560" height="315" src="https://www.youtube.com/embed/' . $id . '" frameborder="0" allowfullscreen></iframe></div>';
}
add_filter('sk_youtube', 'sk_youtube_video_embed');
endif; // sk_youtube_video_embed





if( !function_exists('sk_relevanssi_index_custom_fields' )) :
function sk_relevanssi_index_custom_fields($custom_fields) {
    // d_blog_lead,duvine_person_title,d_itinerary_departure_airport,d_itinerary_arrival_airport,d_tour_always_unique,d_tour_always_unique,d_tour_drink,d_tour_eat,d_tour_unique,d_intinerary_0_description,d_intinerary_1_description,d_intinerary_2_description,d_intinerary_3_description,d_intinerary_4_description,d_intinerary_5_description,d_intinerary_6_description,d_intinerary_7_description,d_intinerary_0_title,d_intinerary_1_title,d_intinerary_2_title,d_intinerary_3_title,d_intinerary_4_title,d_intinerary_5_title,d_intinerary_6_title,d_intinerary_7_title,d_tour_subtitle,d_location_locals_love,bike_components,d_location_drink,d_location_eat
    $acf_fields = array(

        // blog fields
        'd_blog_lead',

        // tour
        'd_tour_always_unique',
        'd_tour_always_unique',
        'd_tour_drink',
        'd_tour_eat',
        'd_tour_unique',
        'd_tour_subtitle',
        'd_itinerary_departure_airport',
        'd_itinerary_arrival_airport',

        // location fields
        'd_location_locals_love',
        'd_location_drink',
        'd_location_eat',

        // bike fields
        'bike_components',

        // person fields
        'duvine_person_title',
    );

    for ($i=0; $i < 9; $i++) {
        array_push($acf_fields,
            'd_itinerary_' . $i . '_description',
            'd_itinerary_' . $i . '_title',
            'd_blog_content_sections_' . $i . '_copy'
        );
    }

    return $acf_fields;
}
// add_filter('relevanssi_custom_fields_to_index', 'sk_relevanssi_index_custom_fields');
add_filter('relevanssi_index_custom_fields', 'sk_relevanssi_index_custom_fields');
endif;


// group the order of the global search results...we want tours first
function tours_first($hits) {
    $types = array();
 
    $types['tour'] = array();
    $types['location'] = array();
    $types['page'] = array();
    $types['press'] = array();
    $types['post'] = array();
    $types['person'] = array();
 
    // Split the post types in array $types
    if (!empty($hits) && is_array($hits) && isset($hits[0]) && is_array($hits[0])) {
        foreach ($hits[0] as $hit) {
            if (array_key_exists($hit->post_type, $types)) {
                array_push($types[$hit->post_type], $hit);
            } else {
                // Initialize the array for unknown post types
                $types[$hit->post_type] = array($hit);
            }
        }
    }
 
    // Merge back to $hits in the desired order
    $hits[0] = array_merge($types['tour'], $types['location'], $types['page'], $types['post'], $types['press'], $types['person']);
    return $hits;
}
add_filter('relevanssi_hits_filter', 'tours_first');






/* -------------------------------------------------
 *
 * --theme
 *
 * ------------------------------------------------- */


if( !function_exists('duvine_render_cycling_level')) :
/**
 * Renders the cycling level, given an object containing the level
 */
function duvine_render_cycling_level( $level ){

    global $wp;
    $current_url = $_SERVER['REQUEST_URI'];
    $is_tour_finder = ( strpos($current_url, 'tour-finder') || strpos($current_url, 'action=filter_tourloop') ) ? true : false;

    $tourfinder_page = get_field('d_tourfinder_page', 'option');

    if( $is_tour_finder === true ) {
        $meta = '<span class="tourmeta tourmeta--label">Level:&nbsp;</span>';
    } else {
        $meta = '<strong>Level: </strong>';
    }

    

    if( $tourfinder_page ){
        $meta .= '<a class="metalink" href="' . $tourfinder_page['url'] . '?level%5B%5D=' . $level['value'] . '">';
    }

    $meta .= $level['value'];

    if( $tourfinder_page ){
        $meta .= '</a>';
    }

    if( $is_tour_finder === true ) {
        $tooltip_activity_level = duvine_get_tooltip( 'd_tooltip_activity_level', 'small' );
        //$meta .= '&nbsp;<img src="'.get_template_directory_uri() . '/svg/info.svg' .'" width="18" alt="info" class="tooltip" />';
        $meta .= $tooltip_activity_level;
    }
    
    return $meta;
}
add_filter('duvine_cycling_level', 'duvine_render_cycling_level');
endif; // duvine_render_cycling_level




if( !function_exists( 'duvine_strip_nights' ) ) :
/**
 * Strips the number of nights from the duration field in a tour
 */
function duvine_strip_nights($string){
    global $wp;
    //$current_url = $wp->request;

    $current_url = $_SERVER['REQUEST_URI'];
    $is_tour_finder = (strpos($current_url, 'tour-finder') ||
        strpos($current_url, 'region') ||
        strpos($current_url, 'collection') ||
        strpos($current_url, 'action=filter_tourloop') ||
        strpos($current_url, 'action=lazyload_tours')) ? true : false;

    $string = preg_replace('/\s\/\s\d+\s[nN]ights/', '', $string);

    if( $is_tour_finder ) {
        return '<span class="tourmeta tourmeta--label">Duration:&nbsp;</span>'.$string;
    } else {
        return $string;
    }
}
add_filter('duvine_tourlength_strip_nights', 'duvine_strip_nights');
endif; // duvine_strip_nights




if( !function_exists( 'duvine_render_tour_collection' ) ) :
/**
 * Filters the tour collection into an html list of collections
 *
 * @param array $collection The list of tour collections
 */
function duvine_render_tour_collection( $collection ){

    if( ! $collection ){
        return false;
    }

    $meta = '<strong>Collection: </strong>';

    $index = 0;
    foreach( $collection as $coll ){
        if( wp_get_post_parent_id($coll) !== 0 ){
            continue;
        }

        $coll_page = get_the_permalink( $coll );

        if( $index > 0 ){
            $meta .= ', ';
        }

        if( $coll_page ){
            $meta .= '<a class="metalink" href="' . $coll_page . '">';
        }
        $meta .= get_the_title( $coll );
        if( $coll_page ){
            $meta .= '</a>';
        }
        $index++;
    }

    return $meta;
}
add_filter('duvine_tour_collection', 'duvine_render_tour_collection');
endif; // duvine_render_tour_collection




if( !function_exists( 'duvine_render_price' ) ) :
/**
 * Renders a number as a price
 *
 * @param string $price The number that's being filtered
 */
function duvine_render_price( $price ){
    return '$' . number_format($price);
}
add_filter('format_price', 'duvine_render_price');
endif; // duvine_render_price




if( !function_exists( 'duvine_array_to_list' ) ) :
/**
 * Takes an array and outputs an html list
 *
 * @param array $array To be converted into an html list
 */
function duvine_array_to_list( $array, $args = array() ){

    if( ! $array || gettype($array) !== 'array' ){
        return '';
    }

    $classes = isset( $args['list_classes'] ) ? ' class="' . $args['list_classes'] . '"' : '';

    $list = "<ul$classes>";

    foreach( $array as $item ){
    

        if( $item === 'Travel Protection' && ($travel_protection_page = get_field('d_travel_protection_page', 'option')) ){
            $item = '<a href="' . $travel_protection_page['url'] . '" class="basiclink">' . $item . '</a>';
        }

        if( $item === 'Gratuities for DuVine guides' && ($faq_page = get_field('d_faq_page', 'option')) ){
//             $item = '<a href="' . $faq_page['url'] . '#Gratuities" class="basiclink">' . $item . '</a>';
			   $item = $item;
        }

        $list .= '<li>' . $item . '</li>';
    }

    $list .= '</ul>';

    return $list;
}
add_filter('list_items', 'duvine_array_to_list', 10, 2);
endif; // duvine_array_to_list






if( !function_exists( 'duvine_typography' ) ) :
/**
 * Typographic enhancements
 */
function duvine_typography( $str ){

    // Replace the smart quotes that cause question marks to appear
    $str = str_replace(
        array("'"),
        array("&rsquo;"), $str);
    
    
    // Return the fixed string
    return $str;
}
// Add filters to modify the content before saving to the database
add_filter( 'smart_type', 'duvine_typography' );
endif; // duvine_typography









/* -------------------------------------------------
 *
 * --util
 *
 * ------------------------------------------------- */

if( !function_exists( 'duvine_map_month_index' ) ) :
/**
 * Maps a month index to an actual montha name
 *
 * @param boolean $short Use short names for months
 */
function duvine_map_month_index( $monthIndex, $short = false ){
    $longMonths = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
    $shortMonths = array( 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec' );
    $months = $short ? $shortMonths : $longMonths;
    $realIndex = $monthIndex - 1;

    if( !isset( $months[$realIndex] ) ){
        return $monthIndex;
    }

    return $months[$realIndex];
}
add_filter('map_month', 'duvine_map_month_index', 10, 2);
endif; // duvine_map_month_index
