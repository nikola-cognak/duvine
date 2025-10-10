<?php
/***************************************
 *
 * Sherman functions and definitions
 *
 * @package sherman
 *
 ***************************************/

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if (!isset($content_width)) {
  $content_width = 640; /* pixels */
}

// sup


/**
 * hide the admin bar, if you want
 */
// add_filter('show_admin_bar', '__return_false');


if (!function_exists('sk_setup')) :
  /**
   * Sets up theme defaults and registers support for various WordPress features.
   *
   * Note that this function is hooked into the after_setup_theme hook, which
   * runs before the init hook. The init hook is too late for some features, such
   * as indicating support for post thumbnails.
   */
  function sk_setup()
  {


    /*
     * Make theme available for translation.
     * Translations can be filed in the /languages/ directory.
     */
    load_theme_textdomain('sherman', get_template_directory() . '/languages');


    /**
     * Add default posts and comments RSS feed links to head.
     */
    add_theme_support('automatic-feed-links');


    /**
     * Enable support for Post Thumbnails on posts and pages.
     */
    add_theme_support('post-thumbnails');


    /**
     * Add image sizes
     */

    //
    // visible (full cropped image sizes, users should use these exact dimensions)
    //

    // banners for pages, tours, collections
    add_image_size('banner_hero', 1440, 810, true);
    add_image_size('banner_hero_page', 1440, 588, true);

    // blog features
    add_image_size('tour_thumbnail', 720, 405, true);

    // grid cell for people
    add_image_size('gridcell_image', 520, 388, true);


    //
    // silent (backend use only)
    //

    // bike images
    add_image_size('bike_thumbnail', 370, 224);

    // tour gallery image sizes
    add_image_size('tourgallery_rectangle', 746, 490, true);
    add_image_size('tourgallery_square', 490, 490, true);


    /**
     * Register nav menu locations
     */
    register_nav_menus(array(
      'primary' => __('Primary menu', 'sherman'),
      'utility' => __('Utility menu', 'sherman'),
      'search' => __('Search menu', 'sherman'),
      'company' => __('Company menu', 'sherman'),
      'connect' => __('Connect menu', 'sherman'),
      'contact' => __('Contact menu', 'sherman'),
      'footer_utility' => __('Footer utility menu', 'sherman')
    ));

    /**
     * Switch default core markup for search form, comment form, and comments
     * to output valid HTML5.
     */
    add_theme_support('html5', array(
      'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
    ));

    /**
     * Enable support for Post Formats.
     * See http://codex.wordpress.org/Post_Formats
     */
    add_theme_support('post-formats', array(
      // 'aside', 'image', 'video', 'quote', 'link'
      'gallery'
    ));


    /**
     * Setup the WordPress core custom background feature. Uncomment
     *  this if you'd like the user to be able to control this stuff.
     */
    // add_theme_support( 'custom-background', apply_filters( 'sk_custom_background_args', array(
    //     'default-color' => 'ffffff',
    //     'default-image' => '',
    // ) ) );
  }
endif; // sk_setup
add_action('after_setup_theme', 'sk_setup');


/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function sk_widgets_init()
{
  register_sidebar(array(
    'name' => __('Sidebar', 'sherman'),
    'id' => 'sidebar-1',
    'description' => '',
    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
    'after_widget' => '</aside>',
    'before_title' => '<h1 class="widget-title">',
    'after_title' => '</h1>',
  ));
}

add_action('widgets_init', 'sk_widgets_init');


/* --------------------------------------------
 * --scripts
 * -------------------------------------------- */

/**
 * Enqueue scripts and styles.
 */
function sk_scripts()
{

  $env = defined('WP_ENV') ? WP_ENV : 'staging';

  //
  // if we're in the production environment, enqueue the minified,
  //  concatenated scripts. Otherwise, load them all individually
  //  for easier debugging.
  //
  if ($env === 'production') {

    // load up the one production, minified file
    wp_enqueue_script('sk_script_main_min', get_template_directory_uri() . '/js/build/production.min.js', array('jquery'), false, true);
    wp_localize_script('sk_script_main_min', 'duvine_ajax', array('ajaxurl' => admin_url('admin-ajax.php')));
  } else {

    // globals
    wp_enqueue_script('sk_script_globals', get_template_directory_uri() . '/js/src/globals.js', null, false, true);

    // plugins
    wp_enqueue_script('sk_plugin_chosen', get_template_directory_uri() . '/js/plugins/chosen.jquery.js', array('jquery'), false, true);
    wp_enqueue_script('sk_plugin_moment', get_template_directory_uri() . '/js/plugins/moment.min.js', array('jquery'), false, true);
    wp_enqueue_script('sk_plugin_clipboard', get_template_directory_uri() . '/js/plugins/clipboard.min.js', array('jquery'), false, true);
    wp_enqueue_script('sk_plugin_instafeed', get_template_directory_uri() . '/js/plugins/instafeed.min.js', null, false, true);
    wp_enqueue_script('sk_plugin_slickslider', get_template_directory_uri() . '/js/plugins/slick.slider.js', array('jquery'), false, true);
    wp_enqueue_script('sk_plugin_tooltipster', get_template_directory_uri() . '/js/plugins/tooltipster.bundle.min.js', array('jquery'), false, true);
    wp_enqueue_script('sk_plugin_jquery_serialize_object', get_template_directory_uri() . '/js/plugins/jquery.serialize-object.js', array('jquery'), false, true);
    wp_enqueue_script('sk_plugin_cookie', get_template_directory_uri() . '/js/plugins/js.cookie.js', array('jquery'), false, true);

    // modules
    wp_enqueue_script('sk_module_sliders', get_template_directory_uri() . '/js/modules/sliders.js', array('sk_plugin_slickslider'), false, true);
    wp_enqueue_script('sk_module_datepicker', get_template_directory_uri() . '/js/modules/datepicker.js', array('jquery'), false, true);
    wp_enqueue_script('sk_module_tourloop', get_template_directory_uri() . '/js/modules/tourloop.js', array('jquery', 'sk_plugin_jquery_serialize_object'), false, true);
    wp_enqueue_script('sk_module_tourfinderbanner', get_template_directory_uri() . '/js/modules/tourfinderbanner.js', array('jquery'), false, true);
    wp_enqueue_script('sk_module_accordion', get_template_directory_uri() . '/js/modules/accordion.js', array('jquery'), false, true);
    wp_enqueue_script('sk_module_tournav', get_template_directory_uri() . '/js/modules/tournav.js', array('jquery'), false, true);
    wp_enqueue_script('sk_module_homepagehero', get_template_directory_uri() . '/js/modules/homepagehero.js', array('jquery'), false, true);
    wp_enqueue_script('sk_module_instaslider', get_template_directory_uri() . '/js/modules/instaslider.js', array('jquery'), false, true);
    wp_enqueue_script('sk_module_tourmap', get_template_directory_uri() . '/js/modules/tourmap.js', array('jquery'), false, true);
    wp_enqueue_script('sk_module_locmap', get_template_directory_uri() . '/js/modules/location-map.js', array('jquery'), false, true);
    wp_enqueue_script('sk_module_searchresults', get_template_directory_uri() . '/js/modules/searchresults.js', array('jquery'), false, true);
    wp_enqueue_script('sk_module_blogfilter', get_template_directory_uri() . '/js/modules/blogfilter.js', array('jquery'), false, true);
    wp_enqueue_script('sk_module_mobilenav', get_template_directory_uri() . '/js/modules/mobilenav.js', array('jquery'), false, true);


    // main
    wp_enqueue_script('sk_script_main', get_template_directory_uri() . '/js/src/main.js', array('jquery'), false, true);

    wp_localize_script('sk_module_instaslider', 'duvine_ajax', array('ajaxurl' => admin_url('admin-ajax.php')));
    wp_localize_script('sk_module_tourloop', 'duvine_ajax', array('ajaxurl' => admin_url('admin-ajax.php')));
  }

  //
  // since we're compiling sass anyway, the style.css file is
  //  already minified and optimized
  //
  wp_enqueue_style('sk_fonts', 'https://cloud.typography.com/7389396/6378572/css/fonts.css');
  wp_enqueue_style('sk_style', get_template_directory_uri() . '/styles/build/style.css', array('sk_fonts'));


}

add_action('wp_enqueue_scripts', 'sk_scripts');


if (!function_exists('duvine_query_vars')) :
  /**
   * Custom query vars
   */
  function duvine_query_vars($vars)
  {

    // tours
    $vars[] = 'destination';
    $vars[] = 'level';
    $vars[] = 'collection';

    $vars[] = 'date';
    $vars[] = 'tourtype';
    $vars[] = 'duration';

    $vars[] = 'taxfilter_landscape';
    $vars[] = 'taxfilter_activity';
    $vars[] = 'taxfilter_culinary';
    $vars[] = 'taxfilter_culture';
    $vars[] = 'morefilters';

    // press
    $vars[] = 'publication';


    // blog search
    $vars[] = 'blogsearch';


    return $vars;
  }

  add_filter('query_vars', 'duvine_query_vars');
endif; // duvine_query_vars


/* --------------------------------------------
 * --includes
 * -------------------------------------------- */

/**
 * Implement the Custom Header feature. Comment this out if you don't
 *  need a banner image on the homepage (or other pages I guess).
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Custom post types and taxonomies
 */
require get_template_directory() . '/inc/post-types.php';

/**
 * Custom post types and taxonomies
 */
require get_template_directory() . '/inc/taxonomies.php';

/**
 * custom filters
 */
require get_template_directory() . '/inc/filters.php';

/**
 * theme api - generally front end functions
 */
require get_template_directory() . '/inc/api.php';

/**
 * centaur api - get tour data from centaur feed
 */
require get_template_directory() . '/inc/centaur.php';

/**
 * Renderers for miscellaneous text that is controlled via the CMS
 */
require get_template_directory() . '/inc/cms-text.php';

/**
 * theme shortcodes
 */
require get_template_directory() . '/inc/shortcodes.php';

/**
 * custom walkers
 */
require get_template_directory() . '/inc/mainnav-walker.php';

/**
 * addons
 */
require get_template_directory() . '/addons/acf-reciprocal-relationship.php';


/**
 * ajax
 */
require get_template_directory() . '/ajax/loadmore-posts.php';
require get_template_directory() . '/ajax/cache-transients.php';
require get_template_directory() . '/ajax/ajax-tourloop.php';


/**
 * admin
 */
require get_template_directory() . '/admin/admin.php';
require get_template_directory() . '/admin/settings-pages.php';


/* --------------------------------------------
 * --util
 * -------------------------------------------- */

/**
 * include svgs inline
 *
 * @param $svg (string)
 *   - the svg to include
 * @param $return (boolean)
 *   - whether to return the svg as a string or simply include the svg
 */
function include_svg($svg, $return = false)
{
  $svg_path = get_template_directory() . '/svg/' . $svg . '.svg';

  if (!file_exists($svg_path)) {
    return false;
  }

  if ($return) {
    return file_get_contents($svg_path);
  }

  include($svg_path);
}

if (!function_exists('remove_from_indexing')) {

  /**
   * Remove website from indexing by robots
   *
   * @return void
   */
  function remove_from_indexing(): void
  {
    add_filter('wp_robots', function ($robots) {
      $robots['noindex'] = true;
      $robots['nofollow'] = true;
      unset($robots['index'], $robots['follow']);

      return $robots;
    });
  }
}

if (!function_exists('get_oneTrust_data_domain_attr')) {

  /**
   * Get OneTrust data-domain-script attribute
   *
   * @return string
   */
  function get_oneTrust_data_domain_attr(): string
  {
    return wp_get_environment_type() === 'production' ? '075c1463-4528-41fe-aca4-73f71b22fb53' : '075c1463-4528-41fe-aca4-73f71b22fb53-test';
  }
}

if (!function_exists('is_load_cookie_disclaimer')) {
  function is_load_cookie_disclaimer(): bool
  {
    $isLoad = false;
    if (defined('IS_LOAD_COOKIE_DISCLAIMER') && constant('IS_LOAD_COOKIE_DISCLAIMER')) {
      $isLoad = IS_LOAD_COOKIE_DISCLAIMER;
    }

    return $isLoad;
  }
}

if (!function_exists('is_show_acf_field')) {
  /**
   * Check is show acf field for post
   * Note: if post id is null check will work for current
   *
   * @param array $displayFieldObject
   * @param int|null $postId
   * @return bool
   */
  function is_show_acf_field(array $displayFieldObject, ?int $postId = null): bool
  {
    $isDisplay = true;
    if ($displayFieldConditions = $displayFieldObject['conditional_logic']) {
      $successCount = 0;
      foreach ($displayFieldConditions as $displayFieldCondition) {
        $trueOperationCount = 0;
        foreach ($displayFieldCondition as $item) {
          $conditionFieldValue = get_field($item['field'], $postId);
          $operator = $item['operator'];
          $value = $item['value'];
          if ($operator === '!=empty') {
            $trueOperationCount += (int)($value != null);
          } elseif ($operator === '==empty') {
            $trueOperationCount += (int)($value == null);
          } elseif ($operator === '!=') {
            $trueOperationCount += (int)($conditionFieldValue != $value);
          } elseif ($operator === '==') {
            $trueOperationCount += (int)($conditionFieldValue == $value);
          }
        }
        if (count($displayFieldCondition) > 0 && count($displayFieldCondition) === $trueOperationCount) {
          $successCount++;
        }
      }
      $isDisplay = $isDisplay && (bool)$successCount;
    }

    return $isDisplay;
  }
}

if (!function_exists('save_post_in_transaction')) {
  /**
   * Triggers during the 'save_post' action to save the $_POST data. Make save in mysql transaction
   *
   * @param int $post_id
   * @param WP_POST $post
   * @return void
   */
  function save_post_in_transaction($post_id, $post)
  {
    global $wpdb;
    // start transaction to avoid data inconsistency
    $wpdb->query("START TRANSACTION");
    $result = acf_save_post($post_id);

    if (post_type_supports($post->post_type, 'revisions')) {
      acf_save_post_revision($post_id);
    }
    if ($result) {
      $wpdb->query("COMMIT");
    } else {
      $wpdb->query("ROLLBACK");
    }
  }
}

if (!function_exists('onetrust_ingore_scripts')) {
  /**
   * @param string $tag
   * @param string $handle
   * @return string
   */
  function onetrust_ingore_scripts($tag, $handle)
  {
    $addAttrArray = ['jquery-core', 'sagittarius', 'jquery-migrate', 'sk_script_main_min', 'load-more-posts-js'];
    if (!in_array($handle, $addAttrArray, true)) {
      return $tag;
    }

    return str_replace(' src', ' data-ot-ignore src', $tag);
  }
}
add_filter( 'script_loader_tag', 'onetrust_ingore_scripts', 10, 2 );
