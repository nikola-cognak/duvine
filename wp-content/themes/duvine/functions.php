<?php
if ( ! function_exists( 'duvine_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function duvine_setup() {

    // Add default posts and comments RSS feed links to head.
    add_theme_support( 'automatic-feed-links' );

    /*
     * Let WordPress manage the document title.
     * By adding theme support, we declare that this theme does not use a
     * hard-coded <title> tag in the document head, and expect WordPress to
     * provide it for us.
     */
    add_theme_support( 'title-tag' );

    /*
     * Enable support for Post Thumbnails on posts and pages.
     *
     * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
     */
    add_theme_support( 'post-thumbnails' );

    // Bikes
    add_image_size( 'bike-detail', 525, 350 );
    add_image_size( 'bike-thumb', 222, 148 );

    // Posts
    add_image_size( 'post-thumb', 222, 166 );

    // Videos
    add_image_size( 'video-thumb', 214, 120 );

    // News
    add_image_size( 'news-thumb', 170, 60 );
    add_image_size( 'news-detail', 300, 220 );

    // Guides
    add_image_size( 'guide-thumb', 214, 143 );
    add_image_size( 'guide-detail', 525, 350 );

    // Photography
    add_image_size( 'gallery-thumb', 130, 87 );
    add_image_size( 'gallery-medium', 560, 336, array('center', 'center') );
    add_image_size( 'gallery-detail', 766, 477 );


    // This theme uses wp_nav_menu() in one location.
    register_nav_menus( array(
        'header' => 'Header Navigation',
    ) );

    /*
     * Switch default core markup for search form, comment form, and comments
     * to output valid HTML5.
     */
    add_theme_support( 'html5', array(
        'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
    ) );


}
endif; // duvine_setup
add_action( 'after_setup_theme', 'duvine_setup' );

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function duvine_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Blog Sidebar', '_s' ),
		'id'            => 'sidebar-blog',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h1>',
	) );
}
add_action( 'widgets_init', 'duvine_widgets_init' );


/**
 * Enqueue scripts and styles.
 */
function duvine_scripts() {
    // Main stylesheet
    wp_enqueue_style( 'duvine-style', get_stylesheet_uri() );
    wp_enqueue_script( 'duvine-header-js', get_template_directory_uri() . '/assets/js/duvine-header.js', array(), false, false );

    if( is_front_page() || is_singular( 'duvine_gallery' ) ) {
        // Load Slick Carousel
        wp_enqueue_style( 'duvine-slick-style', get_template_directory_uri() . '/assets/css/slick.css' );
        wp_enqueue_script( 'duvine-slick-js', get_template_directory_uri() . '/assets/js/vendor/slick.min.js', array('jquery'), false, true );

        if( is_front_page() ) {
            wp_enqueue_script( 'duvine-slick-init-js', get_template_directory_uri() . '/assets/js/duvine-slick.js', array('jquery', 'duvine-slick-js'), false, true );
        }

        if( is_singular( 'duvine_gallery' ) ) {
            wp_enqueue_script( 'duvine-slick-gallery-js', get_template_directory_uri() . '/assets/js/duvine-gallery.js', array('jquery', 'duvine-slick-js'), false, true );
        }
    }

    wp_enqueue_script( 'duvine-js', get_template_directory_uri() . '/assets/js/duvine.js', array('jquery'), false, true );
    wp_enqueue_script( 'duvine-navigation', get_template_directory_uri() . '/assets/js/nav.js', array('jquery'), false, true );


    if( is_singular( 'duvine_tours' ) || is_singular( 'duvine_hotels' ) ) {
        wp_enqueue_style( 'duvine-slick-style', get_template_directory_uri() . '/assets/css/slick.css' );
        wp_enqueue_script( 'duvine-slick-js', get_template_directory_uri() . '/assets/js/vendor/slick.min.js', array('jquery'), false, true );
        wp_enqueue_script( 'duvine-slick-slideshow-js', get_template_directory_uri() . '/assets/js/duvine-slick-slideshow.js', array('jquery', 'duvine-slick-js'), false, true );
    }

    // Load swf video player for video pages only
    if( is_singular( 'duvine_videos' ) ) {
        wp_enqueue_script( 'duvine-swfobject', get_template_directory_uri() . '/assets/js/vendor/swfobject.js');
        wp_enqueue_script( 'duvine-youtube', get_template_directory_uri() . '/assets/js/duvine-youtube.js', array(), false, true);
        wp_enqueue_script( 'duvine-fitvids', get_template_directory_uri() . '/assets/js/vendor/jquery.fitvids.js', array('jquery'), false, true);
    }

    // Load Tours & Meet Andy JS
    if( is_singular( 'duvine_tours' ) || is_page( ) ) {
        wp_enqueue_script( 'jquery-ui-core' );
        wp_enqueue_script( 'jquery-ui-accordion');
    }

    if( is_singular( 'duvine_tours' ) ){
        wp_enqueue_style( 'duvine-print-style', get_template_directory_uri() . '/assets/css/print.css', null, null, 'print' );
        wp_enqueue_script( 'duvine-appendaround-js', get_template_directory_uri() . '/assets/js/vendor/appendAround.js',  array('jquery'), false, true );
    }

    // Load Tours index JS
    if( is_page( 'tours' ) ) {
      wp_enqueue_script( 'jquery-ui-core' );
      wp_enqueue_script( 'jquery-ui-datepicker' );
      wp_enqueue_script( 'gfPlatform', get_template_directory_uri() . '/assets/js/vendor/gfPlatform.combined.js',  array('jquery'), false, true );
      wp_enqueue_script( 'duvine-selectbox', get_template_directory_uri() . '/assets/js/vendor/jquery.selectBox.min.js');
      wp_enqueue_script( 'duvine-forms', get_template_directory_uri() . '/assets/js/duvine-forms.js', array('jquery', 'duvine-selectbox', 'jquery-ui-datepicker', 'gfPlatform'), false, true );
      wp_enqueue_script( 'duvine-tours', get_template_directory_uri() . '/assets/js/duvine-tours.js', array('jquery', 'duvine-selectbox'), false, true );
    }

    // Load Region JS
    if( is_singular( 'duvine_regions' ) ) {
        wp_enqueue_script( 'duvine-jquery-scrollable', get_template_directory_uri() . '/assets/js/vendor/jquery.tools.scrollable.min.js', array('jquery'), false, true );
        wp_enqueue_script( 'duvine-scrollable', get_template_directory_uri() . '/assets/js/duvine-scrollable.js', array('jquery', 'duvine-jquery-scrollable'), false, true );
    }

    // Load Testimonials index JS
    if( is_page( 'testimonials' ) ) {
      wp_enqueue_script( 'jquery-ui-core' );
      wp_enqueue_script( 'gfPlatform', get_template_directory_uri() . '/assets/js/vendor/gfPlatform.combined.js',  array('jquery'), false, true );
      wp_enqueue_script( 'duvine-selectbox', get_template_directory_uri() . '/assets/js/vendor/jquery.selectBox.min.js');
      wp_enqueue_script( 'duvine-forms', get_template_directory_uri() . '/assets/js/duvine-forms.js', array('jquery', 'duvine-selectbox', 'jquery-ui-datepicker', 'gfPlatform'), false, true );
    }


    wp_enqueue_script( 'duvine-navigation', get_template_directory_uri() . '/assets/js/nav.js', array('jquery'), false, true );

    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}
add_action( 'wp_enqueue_scripts', 'duvine_scripts' );


/**
 * Display separate submenu
 */
add_filter( 'wp_nav_menu_objects', 'duvine_submenu_limit', 10, 2 );
function duvine_submenu_limit( $items, $args ) {

    if ( empty( $args->submenu ) ) {
        return $items;
    }

    if( is_numeric( $args->submenu ) ) {
        $menu_parent = (int) $args->submenu;
    }
    else {
        $menu_parent = '';

        foreach ( $items as $menu_item ) {

            if( $menu_item->current_item_parent ) {
                $menu_parent = $menu_item->ID;
                break;
            }
        }
    }

    if( $menu_parent ) {
        $submenu_items = array();

        foreach ( $items as $menu_item ) {
            if( $menu_item->menu_item_parent == $menu_parent ) {
                array_push($submenu_items, $menu_item);
            }
        }
        return $submenu_items;
    }
}

/**
 * Set active states in main and chlid nav for custom post types single pages
 */
function duvine_nav_menu_css_class( $classes, $item ) {

    //***************************************************************************
    // Tours
    //***************************************************************************
    if( is_post_type_archive( 'duvine_regions' ) ) {
        if( 'Tours' == $item->title )  {
            $classes[] = 'current-menu-ancestor';
        }
    }
      if( is_singular( 'duvine_regions' ) || is_post_type_archive( 'duvine_regions' ) ) {
          if( 'Tours By Region' == $item->title ) { $classes[] = 'current-menu-item'; }
      }

    //***************************************************************************
    // Your Experience Top Level Nav
    //***************************************************************************
    if( is_singular( 'duvine_bikes' ) || is_singular( 'duvine_faqs' ) || is_singular( 'duvine_hotels' )  ) {
        if( 'Your Experience' == $item->title )  {
            $classes[] = 'current-menu-ancestor';
        }
    }

        if( is_singular( 'duvine_bikes' ) ) {
            if( 'Bike' == $item->title ) { $classes[] = 'current-menu-item'; }
        }

        if( is_singular( 'duvine_hotels' ) ) {
            if( 'Sleep' == $item->title ) { $classes[] = 'current-menu-item'; }
        }

        if( is_singular( 'duvine_faqs' ) ) {
            if( 'FAQS' == $item->title ) { $classes[] = 'current-menu-item'; }
        }


    //***************************************************************************
    // Meet Our Team Top Level Nav
    //***************************************************************************
    if( is_singular( 'duvine_staff' ) ) {
        global $post;
        $staff_group = get_post_meta( $post->ID, 'duvine_staff_group', true );

        if( 'Meet Our Team' == $item->title )  {
            $classes[] = 'current-menu-ancestor';
        }

        if( 'guides' == $staff_group && 'Guides' == $item->title ) {
            $classes[] = 'current-menu-item';
        }

        if( 'behind-the-scenes' == $staff_group && 'Behind the Scenes' == $item->title ) {
            $classes[] = 'current-menu-item';
        }

    }


    //***************************************************************************
    // Blog + Media Top Level Nav
    //***************************************************************************
    if( is_singular( 'post' ) || is_singular( 'duvine_videos' ) || is_singular( 'duvine_gallery' ) || is_singular( 'duvine_news' ) || is_singular( 'duvine_andyblog' ) ) {
        if( 'Blog + Media' == $item->title )  { $classes[] = 'current-menu-ancestor'; }
    }

        if( is_singular( 'post' ) ) {
            if( 'Blog' == $item->title ) { $classes[] = 'current-menu-item'; }
        }

        if( is_singular( 'duvine_videos' ) ) {
            if( 'Videos' == $item->title ) { $classes[] = 'current-menu-item'; }
        }

        if( is_singular( 'duvine_gallery' ) ) {
            if( 'Photography' == $item->title ) { $classes[] = 'current-menu-item'; }
        }

        if( is_singular( 'duvine_news' ) ) {
            if( 'In the News' == $item->title ) { $classes[] = 'current-menu-item'; }
        }

        if( is_singular( 'duvine_andyblog' ) ) {
            if( 'Where In The World Is Andy' == $item->title ) { $classes[] = 'current-menu-item'; }
        }

    return $classes;
}
add_filter( 'nav_menu_css_class', 'duvine_nav_menu_css_class', 10, 2 );

/*
 * Function to get post_IDs of duvine_region post types
 */
function duvine_get_destination_ids( $region_items ) {
  $destination_ids = get_posts( array (
    'fields'                => 'ids',
    'post_type'             => 'duvine_regions',
    'connected_type'        => 'destinations_to_regions',
    'connected_items'       => $region_items,
    'connected_direction'   => 'to',
    'nopaging'              => true,
    'suppress_filters'      => false,
  ) );

  return $destination_ids;
}

/*
 * Function to get post_IDs filtered by destination_ids
 */
function duvine_get_tour_ids_filtered_by_destinations( $destination_items, $destination_ids ) {
  $tour_region_ids = get_posts( array (
    'fields'                => 'ids',
    'post_type'             => 'duvine_destinations',
    'connected_type'        => 'tours_to_destination',
    'connected_items'       => $destination_items,
    'connected_direction'   => 'to',
    'connected_query'       => array(
      'post__in' => $destination_ids
    ),
    'nopaging'              => true,
    'suppress_filters'      => false,
  ) );

  return $tour_region_ids;
}

/*
 * Function to get post_IDs filtered by tour types
 */
function duvine_get_tour_ids_filtered_by_type( $type_items ) {
  $tour_type_ids = get_posts( array(
    'fields'                => 'ids',
    'post_type'             => 'duvine_tour_types',
    'connected_type'        => 'tours_to_types',
    'connected_direction'   => 'to',
    'connected_items'       => $type_items,
    'nopaging'              => true,
    'suppress_filters'      => false,
  ) );

  return $tour_type_ids;
}

/*
 * Filter function to group by Post_id
 */
function  duvine_query_group_by_id( $groupby ) {
   global $wpdb;
   return $wpdb->posts . '.ID';
}

/*
 * Filter function to group by Post_type
 */
function  duvine_query_group_by_post_type( $groupby ) {
   global $wpdb;
   return $wpdb->posts . '.post_type';
}

/*
 * Function delete element from array
 */
function array_delete($array, $element) {
    return array_diff($array, array($element));
}

function duvine_to_p2p_pre_get_posts( $query ){

    if( isset($query->_p2p_capture) && $query->_p2p_capture ) {
        add_filter( 'posts_fields', 'duvine_to_p2p_setupFields', 20);
    }
    else
    if( $query->is_home() && $query->is_main_query() ) {
        // Add search string to blog template results
        if( isset( $_GET['searchString'] ) && !empty( $_GET['searchString'] ) ) {
          $query->set('s', strip_tags( trim( $_GET['searchString'] ) ));
        }
    }
    else
    if( $query->is_main_query() && !is_admin() && is_post_type_archive( 'duvine_regions' )  ) {
        $query->set( 'orderby', 'menu_order' );
        $query->set( 'posts_per_page', '20' );
        $query->set( 'order', 'ASC' );
    }
    else {
        remove_filter( 'posts_fields', 'duvine_to_p2p_setupFields', 20);
    }
    return $query;
}

function duvine_to_p2p_setupFields( $fields ){
    global $wpdb;
    $fields = "{$wpdb->posts}.ID";

    return $fields;
}
add_action( 'pre_get_posts', 'duvine_to_p2p_pre_get_posts');

/**
 * Hide ACF admin menu for all users except fts
 */
function duvine_show_acf_admin( $show ) {

    $current_user = wp_get_current_user();

    return ( isset($current_user) && isset($current_user->user_login) && 'freshtilledsoil' == $current_user->user_login || 'nbraica' == $current_user->user_login );
}
add_filter('acf/settings/show_admin', 'duvine_show_acf_admin');

/**
 * Check if the current request is an ajax request
 */
function duvine_is_ajax_request() {
    return ( isset( $_GET['ajax_test'] ) || isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' );
}

/**
 * Set Yoast SEO metabox priority to low
 */
add_filter( 'wpseo_metabox_prio', function() { return 'low'; } );


/**
 * Display tour date title as formated start date - formatted end date
 */
function duvine_the_title_filter( $title, $id = null ) {

    if ( 'duvine_tour_dates' == get_post_type( $id ) ) {
        $start_timestamp    = strtotime( get_post_meta( $id, 'duvine_tour_date_start', true ) );
        $end_timestamp      = strtotime( get_post_meta( $id, 'duvine_tour_date_end', true ) );
        return date('m/d/Y', $start_timestamp) . ' - ' . date('m/d/Y', $end_timestamp);
    }

    return $title;
}
add_filter( 'the_title', 'duvine_the_title_filter', 10, 2 );

/**
 * Add options page for analytics
 */
if( function_exists('acf_add_options_page') ) {
  acf_add_options_page(array(
    'page_title'   => 'Tracking Codes',
    'menu_title'  => 'Tracking Codes',
    'menu_slug'   => 'theme-analytics-settings',
    'capability'  => 'edit_posts',
    'redirect'    => false
  ));
}

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';





/* -------------------------------------------------
 *
 * --custom search
 *
 * ------------------------------------------------- */



// /**
//  * Join posts and postmeta tables
//  *
//  * http://codex.wordpress.org/Plugin_API/Filter_Reference/posts_join
//  */
// function cf_search_join( $join ) {
//     global $wpdb;

//     if ( is_search() ) {    
//         $join .=' LEFT JOIN '.$wpdb->postmeta. ' ON '. $wpdb->posts . '.ID = ' . $wpdb->postmeta . '.post_id ';
//     }
    
//     return $join;
// }
// add_filter('posts_join', 'cf_search_join' );

// /**
//  * Modify the search query with posts_where
//  *
//  * http://codex.wordpress.org/Plugin_API/Filter_Reference/posts_where
//  */
// function cf_search_where( $where ) {
//     global $pagenow, $wpdb;
   
//     if ( is_search() ) {
//         $where = preg_replace(
//             "/\(\s*".$wpdb->posts.".post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",
//             "(".$wpdb->posts.".post_title LIKE $1) OR (".$wpdb->postmeta.".meta_value LIKE $1)", $where );
//     }

//     return $where;
// }
// add_filter( 'posts_where', 'cf_search_where' );

// /**
//  * Prevent duplicates
//  *
//  * http://codex.wordpress.org/Plugin_API/Filter_Reference/posts_distinct
//  */
// function cf_search_distinct( $where ) {
//     global $wpdb;

//     if ( is_search() ) {
//         return "DISTINCT";
//     }

//     return $where;
// }
// add_filter( 'posts_distinct', 'cf_search_distinct' );


/**
 * [list_searcheable_acf list all the custom fields we want to include in our search query]
 * @return [array] [list of custom fields]
 */
function list_searcheable_acf(){
  $list_searcheable_acf = array(
    "title",
    "duvine_abstract",
    "duvine_tour_itinenary",
    "duvine_tour_region",
    "duvine_tour_highlights",
    "duvine_tour_whats_included"
  );

  return $list_searcheable_acf;
}
/**
 * [advanced_custom_search search that encompasses ACF/advanced custom fields and taxonomies and split expression before request]
 * @param  [query-part/string]      $where    [the initial "where" part of the search query]
 * @param  [object]                 $wp_query []
 * @return [query-part/string]      $where    [the "where" part of the search query as we customized]
 * see https://vzurczak.wordpress.com/2013/06/15/extend-the-default-wordpress-search/
 * credits to Vincent Zurczak for the base query structure/spliting tags section
 */
function advanced_custom_search( $where, &$wp_query ) {
    global $wpdb;
 
    if ( empty( $where ))
        return $where;
 
    // get search expression
    $terms = $wp_query->query_vars[ 's' ];
    
    // explode search expression to get search terms
    $exploded = explode( ' ', $terms );
    if( $exploded === FALSE || count( $exploded ) == 0 )
        $exploded = array( 0 => $terms );
         
    // reset search in order to rebuilt it as we whish
    $where = '';
    
    // get searcheable_acf, a list of advanced custom fields you want to search content in
    $list_searcheable_acf = list_searcheable_acf();
    foreach( $exploded as $tag ) :
        $where .= " 
          AND (
            (wp_posts.post_title LIKE '%$tag%')
            OR (wp_posts.post_content LIKE '%$tag%')
            OR EXISTS (
              SELECT * FROM wp_postmeta
                  WHERE post_id = wp_posts.ID
                    AND (";
        foreach ($list_searcheable_acf as $searcheable_acf) :
          if ($searcheable_acf == $list_searcheable_acf[0]):
            $where .= " (meta_key LIKE '%" . $searcheable_acf . "%' AND meta_value LIKE '%$tag%') ";
          else :
            $where .= " OR (meta_key LIKE '%" . $searcheable_acf . "%' AND meta_value LIKE '%$tag%') ";
          endif;
        endforeach;
            $where .= ")
            )
            OR EXISTS (
              SELECT * FROM wp_comments
              WHERE comment_post_ID = wp_posts.ID
                AND comment_content LIKE '%$tag%'
            )
            OR EXISTS (
              SELECT * FROM wp_terms
              INNER JOIN wp_term_taxonomy
                ON wp_term_taxonomy.term_id = wp_terms.term_id
              INNER JOIN wp_term_relationships
                ON wp_term_relationships.term_taxonomy_id = wp_term_taxonomy.term_taxonomy_id
              WHERE (
                taxonomy = 'post_tag'
                    OR taxonomy = 'category'                
                    OR taxonomy = 'myCustomTax'
                )
                AND object_id = wp_posts.ID
                AND wp_terms.name LIKE '%$tag%'
            )
        )";
    endforeach;
    return $where;
}
 
add_filter( 'posts_search', 'advanced_custom_search', 500, 2 );