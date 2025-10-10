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
  wp_enqueue_style('sk_style', get_template_directory_uri() . '/styles/build/style.css', array('sk_fonts'), '1.2');

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

require get_template_directory() . '/inc/centaur-cron.php';

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
// add_filter( 'jpeg_quality', 'disable_img_compression' );

// function disable_img_compression() {

// return 100;

// }

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

add_filter('wpseo_json_ld_output', function ($data) {
    // Check if the schema data contains 'offers' and 'shippingDetails'
    if (isset($data['@graph'])) {
        foreach ($data['@graph'] as &$graph) {
            // If the 'offers' section exists and contains 'shippingDetails', remove it
            if (isset($graph['offers']['shippingDetails'])) {
                unset($graph['offers']['shippingDetails']);
            }
        }
    }
    return $data;
});


# ********
add_action('wp_footer', function(){
	if(is_front_page()){
		?>
		<script>
			jQuery(function($){
				
				// extension method:
				$.fn.onClassChange = function(cb) {
				  return $(this).each((_, el) => {
					new MutationObserver(mutations => {
					  mutations.forEach(mutation => cb && cb(mutation.target, mutation.target.className));
					}).observe(el, {
					  attributes: true,
					  attributeFilter: ['class'] // only listen for class attribute changes 
					});
				  });
				}
				
				$(".dayslider__time").onClassChange((el, newClass) => {
					if($(el).hasClass('dayslider__time--active')){
						const link = $(el).attr('data-link')
						const text = $(el).attr('data-text')
						// Update button link here
						if (link != ''){
							$('.hero-cta').attr('href', link)
						}
						if (text != ''){
							$('.hero-cta').text(text)
						}
					}
				})
				
			})
		</script>
		<?php
	}
}, 99);

# Tour page sticky ctas
add_action('wp_footer', function(){
	if(is_singular() && get_post_type() == 'tour'){
		?>
		<style>
		.cta-wrapper-container{ background: #000; padding-top: 16px; padding-bottom: 32px; position: sticky; top: 64px; z-index: 99; display: none; }
		.cta-wrapper-container .cta{ margin-top: 0; }

		@media (max-width: 576px){

			.cta-wrapper-container{ display: block; }
			.infobox__container .infobox__cta{ display: none; }
			.site-content{ overflow: visible; }
			.infobox__container .infobox{ padding-bottom: 1px; }
			.infobox__container .infobox__meta{ margin-bottom: 0; }

		}
		</style>
<script type="text/javascript">
	jQuery(document).on("scroll", function () {
      setMobileHeader();
    });
	function setMobileHeader() {
    if (jQuery(window).width() <= 580) {
      if (jQuery(window).scrollTop() > 100) {
        jQuery("body").addClass("scrolling");
        jQuery(".utilitynav").prependTo(".mobilenav__mainpane");
      } else {
        jQuery("body").removeClass("scrolling");
        jQuery(".utilitynav").fadeIn(400);
        jQuery(".utilitynav").insertAfter(".searchnav");
      }
    } else {
      jQuery("body").removeClass("scrolling");
      jQuery(".utilitynav").insertAfter(".searchnav").fadeIn(400);
    }
	}
</script>
		<?php
	}
});

function exclude_multiple_post_types_from_search($query) {
    // Only modify the main search query on the front end
    if ($query->is_main_query() && $query->is_search() && !is_admin()) {
        // Get the current post types in the query
        $post_types = $query->get('post_type');
        
        // If no post types are set, default to all public post types
        if (empty($post_types)) {
            $post_types = get_post_types(array('public' => true, 'exclude_from_search' => false));
        }
        
        // Ensure specific post types are excluded
        if (is_array($post_types)) {
            $post_types = array_diff($post_types, array('person', 'guide')); // Add more post types as needed
        }
        
        // Update the query
        $query->set('post_type', $post_types);
    }
    return $query;
}
add_filter('pre_get_posts', 'exclude_multiple_post_types_from_search');


function modify_json_ld_output($buffer) {
    // Check if the URL contains '/tour/'.
    if ( strpos( $_SERVER['REQUEST_URI'], '/tour/' ) !== false ) {
        // Use preg_replace_callback to target JSON-LD script blocks.
        $buffer = preg_replace_callback(
            '#<script type="application/ld\+json">(.*?)</script>#s',
            function ($matches) {
                $json = $matches[1];
                // Try to decode the JSON.
                $data = json_decode($json, true);
                if ( json_last_error() !== JSON_ERROR_NONE ) {
                    // If JSON is invalid, return the original block.
                    return $matches[0];
                }
                
                // Check if this JSON block is a Product schema.
                if ( isset($data['@type']) && $data['@type'] === 'Product' ) {
                    // Check if the offers block and shippingDetails exist.
                    if ( isset($data['offers']) && is_array($data['offers']) ) {
                        if ( isset($data['offers']['shippingDetails']) && is_array($data['offers']['shippingDetails']) ) {
                            // Add shippingRate if missing.
                            if ( !isset($data['offers']['shippingDetails']['shippingRate']) ) {
                                $data['offers']['shippingDetails']['shippingRate'] = array(
                                    '@type'    => 'MonetaryAmount',
                                    'value'    => '0.00',
                                    'currency' => 'USD'
                                );
                            }
                            // Add shippingDestination if missing.
                            if ( !isset($data['offers']['shippingDetails']['shippingDestination']) ) {
                                $data['offers']['shippingDetails']['shippingDestination'] = array(
                                    '@type'         => 'DefinedRegion',
                                    'addressCountry'=> 'ZZ'  // Use 'ZZ' as a placeholder for "no country"
                                );
                            }
                        } else {
                            // If shippingDetails is not set, create it.
                            $data['offers']['shippingDetails'] = array(
                                '@type' => 'OfferShippingDetails',
                                'shippingRate' => array(
                                    '@type'    => 'MonetaryAmount',
                                    'value'    => '0.00',
                                    'currency' => 'USD'
                                ),
                                'shippingDestination' => array(
                                    '@type'         => 'DefinedRegion',
                                    'addressCountry'=> 'ZZ'
                                )
                            );
                        }
                    }
                    // Re-encode the JSON with pretty print and without escaping slashes.
                    $new_json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                    // Return the modified script block.
                    return '<script type="application/ld+json">' . $new_json . '</script>';
                }
                // If not a Product schema, return the original block.
                return $matches[0];
            },
            $buffer
        );
    }
    return $buffer;
}

function start_buffer_for_schema_modification() {
    ob_start("modify_json_ld_output");
}
add_action('template_redirect', 'start_buffer_for_schema_modification');

/**
 * Sync "Pre-Tour Package" Activities term based on the ACF checkbox "d_itinerary_pre_tour".
 */
add_action( 'acf/save_post', 'duvine_sync_pre_tour_tag', 20 );
function duvine_sync_pre_tour_tag( $post_id ) {

    // If it's not the right post type, return
    if ( 'tour' !== get_post_type( $post_id ) ) {
        return;
    }

    // Get the value of the ACF checkbox (True/False)
    $is_pre_tour = get_field( 'd_itinerary_pre_tour', $post_id );

    // ID of the "Pre-Tour Package" term in the Activities taxonomy
    $pre_tour_term_id = 251;

    // Get the current Activities terms for this post
    $current_activities = wp_get_object_terms( $post_id, 'activity', array( 'fields' => 'ids' ) );

    // If the checkbox is checked, ensure "Pre-Tour Package" term is selected
    if ( $is_pre_tour && ! in_array( $pre_tour_term_id, $current_activities ) ) {
        // Add the "Pre-Tour Package" term to the activities
        $current_activities[] = $pre_tour_term_id;
        wp_set_object_terms( $post_id, $current_activities, 'activity' );
    }
    // If the checkbox is unchecked, remove "Pre-Tour Package" term if selected
    elseif ( ! $is_pre_tour && in_array( $pre_tour_term_id, $current_activities ) ) {
        // Remove the "Pre-Tour Package" term from activities
        $current_activities = array_diff( $current_activities, [ $pre_tour_term_id ] );
        wp_set_object_terms( $post_id, $current_activities, 'activity' );
    }
}

function ig_protect_internal_guides() {
    if ( is_page_template( 'page-guides-internal.php' ) ) {  // Removed is_singular('person') check
        if ( empty( $_COOKIE['ig_guides_unlocked'] ) ) {
            $error = false;

            if ( 'POST' === $_SERVER['REQUEST_METHOD'] && isset( $_POST['ig_password'] ) ) {
                $correct = 'Duvine@2025';
                if ( hash_equals( $correct, $_POST['ig_password'] ) ) {
                    setcookie( 'ig_guides_unlocked', '1', time() + 3600, COOKIEPATH, COOKIE_DOMAIN );
                    $_COOKIE['ig_guides_unlocked'] = '1';
                    wp_safe_redirect( $_SERVER['REQUEST_URI'] );
                    exit;
                } else {
                    $error = true;
                }
            }

            // Start output with header
            get_header();

            // Show breadcrumbs like default template
            ?>
            <div class="l-container l-container--small breadcrumbs__pad">
                <ul class="breadcrumbs baselist">
                    <li class="breadcrumb__item">
                        <a class="breadcrumb__link" href="/">Home</a>
                    </li>
                    <li class="breadcrumb__item"><?php echo get_the_title(); ?></li>
                </ul>
            </div>

            <div id="primary" class="content-area">
                <header class="entry-header l-container l-container--small">
                    <?php the_title( '<h1 class="superheader">', '</h1>' ); ?>
                </header>

                <main id="main" class="site-main l-container l-container--small d-content" role="main">
                    <div class="password-protection-form" style="text-align: center; padding: 2em 0;">
                        <?php if ( $error ) : ?>
                            <p class="error-message" style="color: #c00; margin-bottom: 1em;">
                                Incorrect password, please try again.
                            </p>
                        <?php endif; ?>

                        <form method="post" style="max-width: 300px; margin: 0 auto;">
                            <div class="form-group" style="margin-bottom: 1em;">
                                <label style="display: block; margin-bottom: 0.5em;">
                                    Please enter the password to view this content:
                                </label>
                                <input type="password" 
                                       name="ig_password" 
                                       class="Input" 
                                       style="width: 100%; padding: 0.5em;"
                                       autocomplete="off">
                            </div>
                            <div class="form-group">
                                <button type="submit" 
                                        class="Button Action">
                                    <span>Submit</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </main>
            </div>
            <?php

            get_footer();
            exit;
        }
    }
}
add_action( 'template_redirect', 'ig_protect_internal_guides' );

/**
 * Custom permalink structure for person post type based on role
 */
function custom_person_permalink($permalink, $post, $leavename) {
    if ($post->post_type == 'person') {
        // Get all role terms for this person
        $terms = wp_get_post_terms($post->ID, 'role');
        
        $is_tour_guide = false;
        foreach ($terms as $term) {
            // Check if role slug contains 'tour-guide'
            if (strpos($term->slug, 'tour-guide') !== false) {
                $is_tour_guide = true;
                break;
            }
        }
        
        // Generate the appropriate URL
        if ($is_tour_guide) {
            $permalink = home_url('/internal-guides/' . $post->post_name . '/');
        } else {
            $permalink = home_url('/staff/' . $post->post_name . '/');
        }
    }
    
    return $permalink;
}
add_filter('post_link', 'custom_person_permalink', 10, 3);
add_filter('post_type_link', 'custom_person_permalink', 10, 3);

/**
 * Handle custom rewrite rules for person post type
 */
function custom_person_rewrite_rules() {
    // Add rewrite rule for internal guides (tour guides) - higher priority
    add_rewrite_rule(
        '^internal-guides/([^/]+)/?$',
        'index.php?post_type=person&name=$matches[1]',
        'top'
    );
    
    // Add rewrite rule for staff (non-tour guides)  
    add_rewrite_rule(
        '^staff/([^/]+)/?$',
        'index.php?post_type=person&name=$matches[1]',
        'top'
    );
}
add_action('init', 'custom_person_rewrite_rules', 10, 0);

/**
 * Force rewrite rules flush on theme activation/update
 */
function flush_rewrite_rules_on_theme_switch() {
    custom_person_rewrite_rules();
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'flush_rewrite_rules_on_theme_switch');

/**
 * Template redirect to ensure correct URLs and handle old URLs
 */
function redirect_person_urls() {
    if (is_singular('person')) {
        global $post;
        $current_url = $_SERVER['REQUEST_URI'];
        $correct_permalink = get_permalink($post->ID);
        $correct_path = str_replace(home_url(), '', $correct_permalink);
        
        // If current URL doesn't match the correct one, redirect
        if ($current_url !== $correct_path) {
            wp_redirect($correct_permalink, 301);
            exit;
        }
    }
}
add_action('template_redirect', 'redirect_person_urls');

/**
 * Redirect old /team/ URLs to appropriate new URLs
 */
function redirect_old_team_urls() {
    if (!is_404()) {
        return;
    }
    
    $request_uri = $_SERVER['REQUEST_URI'];
    if (strpos($request_uri, '/team/') === 0) {
        $slug = str_replace('/team/', '', $request_uri);
        $person = get_page_by_path($slug, OBJECT, 'person');
        
        if ($person) {
            $new_url = get_permalink($person->ID); // This will use our custom permalink logic
            wp_redirect($new_url, 301);
            exit;
        }
    }
}
add_action('template_redirect', 'redirect_old_team_urls');

// PRESS DRAFT SYNC ON TOURS
function remove_press_from_tours_on_status_change($post_id) {
    if (get_post_type($post_id) !== 'press') {
        return;
    }

    $status = get_post_status($post_id);
    if (!in_array($status, ['draft', 'trash'])) {
        return;
    }

    $tours = get_posts([
        'post_type' => 'tour',
        'posts_per_page' => -1,
        'meta_query' => [
            [
                'key' => 'd_as_featured_in',
                'value' => $post_id,
                'compare' => 'LIKE',
            ]
        ]
    ]);

    $removed_count = 0;
	$removed_ids = [];

    foreach ($tours as $tour) {
        $current = get_field('d_as_featured_in', $tour->ID, false);

        if (is_array($current) && in_array($post_id, $current)) {
            $new = array_diff($current, [$post_id]);
            update_field('d_as_featured_in', $new, $tour->ID);
            $removed_count++;
			$removed_ids[] = $tour->ID;
        }
    }

    if ($removed_count > 0) {
        // Store a transient to show admin notice on next page load
        $ids_list = implode(', ', $removed_ids);
        set_transient('press_removal_notice', "Removed press post ID {$post_id} from {$removed_count} tour(s): {$ids_list}.", 30);
    }
}
add_action('save_post', 'remove_press_from_tours_on_status_change');

function show_press_removal_admin_notice() {
    if ($message = get_transient('press_removal_notice')) {
        echo '<div class="notice notice-success is-dismissible">';
        echo '<p>' . esc_html($message) . '</p>';
        echo '</div>';
        // Delete transient so it shows only once
        delete_transient('press_removal_notice');
    }
}
add_action('admin_notices', 'show_press_removal_admin_notice');

// Breadcrumb fix
add_action('wp_head', function() {
    if (is_page('internal-guides') || is_page('ernesto-araneda')) { // adjust slugs accordingly
        ?>
        <script type="application/ld+json">
        {
          "@context": "https://schema.org",
          "@type": "BreadcrumbList",
          "@id": "<?php echo esc_url(get_permalink()); ?>#breadcrumb",
          "itemListElement": [
            {
              "@type": "ListItem",
              "position": 1,
              "name": "Home",
              "item": "<?php echo esc_url(home_url('/')); ?>"
            },
            {
              "@type": "ListItem",
              "position": 2,
              "name": "Internal Guides",
              "item": "<?php echo esc_url(home_url('/internal-guides/')); ?>"
            },
            {
              "@type": "ListItem",
              "position": 3,
              "name": "<?php echo esc_html(get_the_title()); ?>",
              "item": "<?php echo esc_url(get_permalink()); ?>"
            }
          ]
        }
        </script>
        <?php
    }
});

// Centaur Sync fix
add_action('init', function() {
    // Only set up the cron if it's not already scheduled
    if (!wp_next_scheduled('centaur_sync')) {
        wp_schedule_event(time(), 'twohours', 'centaur_sync');
    }
});

add_filter('cron_schedules', function($schedules) {
    $schedules['twohours'] = array(
        'interval' => 7200,
        'display'  => 'Every 2 Hours'
    );
    return $schedules;
});
