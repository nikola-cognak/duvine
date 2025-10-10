<?php

/* ========================================================
 *
 * API function calls for theming
 *
 * ======================================================== */


/* -------------------------------------------------
 * --getting fields
 * ------------------------------------------------- */


if( !function_exists('sk_the_field') ) :
/**
 * theme implementation of ACF's get_field - checks to ensure the
 *  value is there, and then wraps it in html
 *
 * @param string $field : The name of the field
 * @param array  $args  : Arguments to display this field
 *    - id (number)         : the post id to check this field for.
 *    - before (string)     : the html to appear before the field.
 *    - after  (string)     : the html to appear after the field.
 *    - filter (string)     : any filters to apply to the field.
 *    - filter_args (array) : an array of arguments to pass to the filter.
 *    - sub_field (boolean) : whether or not this field is a sub-field of a repeater.
 *    - default (string)    : if the field is undefined, render the default. Default is an empty string.
 *    - return (boolean)    : whether to return the value, or simply echo it. Default is false.
 *    - debug (boolean)     : enables debug mode. Default is false.
 */
function sk_the_field($field, $args = array() ){

    // lets check the existance of ACF
    if ( function_exists( 'get_field' ) === false ){
        return false;
    }

    // ok we're good - let's go
    global $post;

    $defaults = array(
        'id'          => 0,
        'before'      => '',
        'after'       => '',
        'filter'      => '',
        'filter_args' => array(),
        'sub_field'   => false,
        'default'     => '',
        'return'      => false,
        'debug'       => false,
    );

    $options = array_merge($defaults, $args);

    //
    // check to see if we have an ID set. if not, grab it from
    //  the global post variable
    //
    if($options['id']){
        $id = $options['id'];
    } else {
        if( !isset($post->ID)){
            return;
        }
        $id = $post->ID;
    }

    $val = $options['sub_field'] ? get_sub_field($field, $id) : get_field($field, $id) ;

    if( $val ){
        if($options['filter']){
            $val = apply_filters( $options['filter'], $val, $options['filter_args'] );
        }

        $markup = $options['before'] . $val . $options['after'];

        if($options['return']){
            return $markup;
        }

        echo $markup;

    } else if($options['default']){
        $markup = $options['before'] . $options['default'] . $options['after'];

        if($options['return']){
            return $markup;
        }

        echo $markup;
    }
}
endif; // sk_the_field





if( !function_exists('sk_get_field') ) :
/**
 * Wrapper to call the sk_the_field function to return
 *
 * @param string $field : Field we want
 * @param array  $args  : see above
 */
function sk_get_field( $field, $args = array() ){
    $options = array_merge($args, array('return' => true));
    return sk_the_field($field, $options);
}
endif; // sk_get_field





if( !function_exists('sk_the_subfield') ) :
/**
 * Wrapper to call the sk_the_field with a subfield
 *
 * @param string $field : Field we want
 * @param array  $args  : see above
 */
function sk_the_subfield( $field, $args = array() ){
    $options = array_merge( $args, array('sub_field' => true) );
    sk_the_field($field, $options);
}
endif; // sk_the_subfield




if( !function_exists('sk_get_subfield') ) :
/**
 * Wrapper to call the sk_the_field to return and show a subfield
 *
 * @param string $field : Field we want
 * @param array  $args  : see above
 */
function sk_get_subfield( $field, $args = array() ){
    $options = array_merge($args, array('return' => true, 'sub_field' => true));
    return sk_the_field($field, $options);
}
endif; // sk_get_subfield




if( !function_exists('sk_block_field') ) :
/**
 * Wrapper to display a field within a block. Since the page blocks
 *  utilizes an ACF repeater field, this is an alias of sk_the_subfield()
 */
function sk_block_field( $field, $args = array() ){
    sk_the_subfield( $field, $args );
}
endif; // sk_block_field






/* -------------------------------------------------
 *
 * --theme
 *
 * ------------------------------------------------- */


if( !function_exists('sk_the_page_blocks') ) :
/**
 * hooks into the ACF repeater field to render all the additional
 *  page blocks for a page. Calls tempaltes in the blocks/ directory
 */
function sk_the_page_blocks(){

    // check for the existance of ACF
    if ( function_exists( 'have_rows' ) === false ){
        return false;
    }

    // the page blocks repeater field
    $newBlocks = 'sk_page_blocks';

    if( !have_rows( $newBlocks ) ){
        return false;
    }
?>
    <?php // loop through the rows of data ?>
    <?php while ( have_rows($newBlocks) ) : the_row(); ?>
        <?php $block = get_row_layout(); ?>
        <?php
        $padding_classes;
        if( get_sub_field('block_padding_top') ) $padding_classes = ' block-padding-top';
        if( get_sub_field('block_padding_bottom') ) $padding_classes .= ' block-padding-bottom';

        $is_private_tours_page = strpos($block,'private_planning_form');

        if( $is_private_tours_page !== false ) :
            echo '<a class="page-anchor" id='.$block.'></a>';
        endif;
        ?>

        <section class="sk-block<?php echo $block ? " block--$block" : "";echo $padding_classes; ?>">
            <?php
                //
                // - example implementation of getting the header field
                //    for each of the blocks
                //
                // sk_block_field( 'sk_page_block_title' , array(
                //     'before'    => '<header class="page-module-title"><h2>',
                //     'after'     => '</h2></header>'
                // ));

                get_template_part('blocks/block', $block);
            ?>

        </section><!-- .sk-module -->
    <?php endwhile; ?>
<?php
}
endif; // sk_the_page_blocks






/* -------------------------------------------------
 *
 * --renderers
 *     - functions that echo things
 *
 * ------------------------------------------------- */




if( !function_exists( 'duvine_render_tour_loop' ) ) :
/**
 * Wrapper for directly rendering the tourloop
 *
 * @param array $args The WP_Query arguments
 */
function duvine_render_tour_loop( $args = array() ){
    echo duvine_get_tourloop_markup( $args );
}
endif; // duvine_render_tour_loop



if( !function_exists( 'duvine_get_tourloop_markup' ) ) :
/**
 * Gets the list of all tour placards. Returns the markup
 *
 * @param array $args The WP_Query arguments
 *
 * @return string
 */
function duvine_get_tourloop_markup( $args = array() ){

    global $wp;

    $options = array_merge( array(
        'duvine_show_count'             => false,
        'duvine_show_you_may_also_like' => false,
        'duvine_show_loadmore'          => true
    ), $args);

    $queryArgs = duvine_build_tour_queryargs( $options );

    // get the number of tours
    if( false === ( $tourCount = get_transient( 'duvine_tour_count' ) ) ){
        $tourCountObj = wp_count_posts( 'tour' );
        if( $tourCountObj && isset( $tourCountObj->publish ) ){
            $tourCount = $tourCountObj->publish;
            set_transient( 'duvine_tour_count', $tourCount, 60 * 60 * 2 );
        }
    }

    // The Query
    $tourQuery = new WP_Query( $queryArgs );

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

    ob_start();

    // The Loop
?>
    <?php if ( $tourQuery->have_posts() ) : ?>
        
        <?php if( $options['duvine_show_count'] ) : ?>
            <div class="tourlist__count">
                <?php if( $tourCount && $tourCount === $tourQuery->found_posts ) : ?>
                    Showing all tours ( <?php echo $tourCount; ?> )
                <?php else : ?>
                    Showing results ( <?php echo $tourQuery->found_posts; ?> )
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <ul class="tourlist baselist">
            <?php $tour_count = 1; ?>
            <?php while ( $tourQuery->have_posts() ) : $tourQuery->the_post(); ?>
                <?php
                $centaur_id = trim(get_field('d_tour_centaur_id'));
                $privateOnly = get_field('d_private_only');
                $tour_name = get_the_title();
                ?>
                <?php duvine_render_tour_placard(); ?>
                <?php
                // if this is the tour finder page, then insert the private tour banner after the third tour
                if( $tour_count == 3 && $wp->request == 'tour-finder' ) : ?>
                    <?php duvine_text_private_promo(); ?>
                <?php endif; ?>
                <?php ++$tour_count; ?>
            <?php endwhile; ?>
        </ul>

        <?php if( $options['duvine_show_loadmore'] && $tourQuery->found_posts > $tourQuery->post_count ) : ?>
            <div class="t-center tourloop__footersection"><a href="#" class="cta js-trigger-tourloop-lazyload cta--hasloader" data-tourcount="<?php echo $tourQuery->found_posts; ?>" data-touroffset="<?php echo $tourQuery->post_count; ?>">Load More</a></div>
        <?php endif; ?>
        
    <?php else : ?>
        <div class="notours">
            <p>No results found that meet your criteria. Please adjust your search and try again. If preferred travel dates aren’t available, note that all tours can be run on a private basis.</p>
        </div>
    <?php endif; ?>

    <?php
        /* Restore original Post Data */
        wp_reset_postdata();
    ?>


    <?php if( $options['duvine_show_you_may_also_like'] && $tourQuery->found_posts < 3 ) : ?>
        <?php
            $displayed_tours = wp_list_pluck( $tourQuery->posts, 'ID' );

            $youMayAlsoLikeArgs = array(
                'posts_per_page' => 2,
                'post_type'      => 'tour',
                'orderby'        => 'RAND'
            );

            if( $displayed_tours ){
                $youMayAlsoLikeArgs['post__not_in'] = $displayed_tours;
            }

            if( $q_level = get_query_var('level')){
                $has_level = false;
                $levels_meta_query = array('relation' => 'OR');
                foreach( $q_level as $level ){
                    if( $level > 0 ){
                        $has_level = true;
                        array_push( $levels_meta_query, array(
                            'key'     => 'd_cycling_level',
                            'value'   => $level,
                            'compare' => 'LIKE'
                        ));
                    } else {
                        array_push( $levels_meta_query, array(
                            'key'     => 'd_non_rider',
                            'value'   => '1',
                            'compare' => '=='
                        ));
                    }
                    
                }

                if( $has_level ) {
                    // get all centaur posts that DON'T have any one of these levels
                    $post_not_in = duvine_get_tours_not_in_centaur_level($q_level);
                    if( count($post_not_in) > 0 ) {
                        $not_in_query = array('relation' => 'AND');
                        foreach ($post_not_in as $value) {
                            $not_in_query[] = array(
                                'key'       => 'd_tour_centaur_id',
                                'value'     => $value,
                                'compare'   => '!=',
                            );
                        }
                        if (array_key_exists('meta_query', $youMayAlsoLikeArgs)) {
                            array_push($youMayAlsoLikeArgs['meta_query'], $not_in_query);
                        } else {
                            $youMayAlsoLikeArgs['meta_query'] = $not_in_query;
                        }
                    }
                }

                $youMayAlsoLikeArgs['meta_query'] = $levels_meta_query;
            }

            $youMayAlsoLike = get_posts( $youMayAlsoLikeArgs );
        ?>

        <?php if( $youMayAlsoLike ) : ?>
            <div class="tourloop__footersection">
                <h3 class="blockheader also-like">You may also like</h3>
                <ul class="tourlist baselist">
                    <?php foreach( $youMayAlsoLike as $secondaryTour ) : ?>
                        <?php duvine_render_tour_placard( $secondaryTour ); ?>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    <?php endif; ?>

<?php

    return ob_get_clean();
}
endif; // duvine_get_tourloop_markup


if (!function_exists('duvine_render_grouped_tour_item')) {
    function duvine_render_grouped_tour_item(&$_post, $is_tour_finder, $loc) {
        global $wp;
        $current_url = $_SERVER['REQUEST_URI'];
        global $post;

        if( $_post !== null ){
            $post = $_post;

            setup_postdata( $post );
        }

        $taxfilters = duvine_get_active_filters( $post );
        $permalink  = get_the_permalink();
        $centaur_id = trim(get_field('d_tour_centaur_id'));
        $privateOnly = get_field('d_private_only');
        ?>
        <li class="tourlist__listing tourlist__listing--<?php echo get_field('d_private_only') ? 'private' : 'scheduled' ;?> tourlist__country" data-country="<?php echo $loc; ?>">

            <?php if( $is_tour_finder ) : ?>
            <div class="tourlisting__inner">
                <?php endif; ?>
                <div class="tourlisting__image"><a href="<?php echo $permalink; ?>">
                        <?php
                        sk_the_field('d_thumbnail_image', array(
                            'filter'      => 'sk_img_markup',
                            'filter_args' => array(
                                'img_size' => 'tour_thumbnail'
                            ),
                            'default'     => '<div class="tourlisting__placeholder"></div>'
                        ));
                        ?>
                    </a>
                    <?php if( $is_tour_finder === true ) : ?>
                        <?php duvine_tour_flags(); ?>
                    <?php endif; ?>
                </div><!-- .tourlisting__image -->

                <div class="tourlisting__data">
                    <?php if( $is_tour_finder === false ) : ?>
                        <?php duvine_tour_flags(); ?>
                    <?php endif; ?>

                    <header>
                        <div class="tourlisting__location"><?php duvine_tour_breadcrumbs( $post->ID, true ); ?></div>
                        <h3 class="blockheader"><a href="<?php echo $permalink; ?>"><?php the_title(); ?></a></h3>
                    </header>

                    <?php
                    sk_the_field('d_tour_subtitle', array(
                        'before' => '<div class="tourlisting__description">',
                        'after'  => '</div>'
                    ));
                    ?>

                    <div class="tourlisting__meta">
                        <?php
                        sk_the_field('d_tour_duration', array(
                            'before' => '<span class="tourmeta tourmeta--separator">',
                            'after'  => '</span>',
                            'filter' => 'duvine_tourlength_strip_nights'
                        ));
                        $tourDates = duvine_get_tour_dates();
                        if($privateOnly || !is_array($tourDates)) {
                            $tour_level = get_field('d_cycling_level');
                            $tour_level = $tour_level['value'];
                        } else {
                            $tour_level = duvine_get_tour_level($centaur_id);
                        }
                        echo '<span class="tourmeta tourmeta--separator"><span class="tourmeta tourmeta--label">Level:</span>&nbsp;' . $tour_level . '</span>';
                        /*sk_the_field('d_cycling_level', array(
                            'before' => '<span class="tourmeta tourmeta--separator">',
                            'after'  => '</span>',
                            'filter' => 'duvine_cycling_level'
                        ));*/

                        if( $minPrice = duvine_get_minimum_price() ){
                            $price = '<span class="tourmeta tourmeta--separator">';
                            if( $is_tour_finder === true ) {
                                $price .= '<span class="tourmeta tourmeta--label">Price From:</span>&nbsp;' . $minPrice . '</span>';
                            } else {
                                $price .= '<strong>Price From: </strong>' . $minPrice . '</span>';
                            }
                            echo $price;
                        }

                        if( $is_tour_finder === true) {
                            if (get_field('d_private_only') != 'private' ) {
                                echo '<span class="tourmeta tourmeta--separator years-list"><span class="tourmeta tourmeta--label">Dates:</span>&nbsp;</span>';
                                if (!is_array($tourDates)) {
                                    echo $tourDates;
                                } else {
                                    duvine_render_tour_date_years($is_tour_finder);
                                }
                            }
                        }
                        ?>
                    </div>

                    <?php if( $taxfilters ) : ?>
                        <p class="tourlisting__filters"><?php echo $taxfilters; ?></p>
                    <?php endif; ?>


                    <div class="tourlisting__footer l-cf">
                        <?php if( $is_tour_finder === false ) : ?>
                            <a class="tourlisting__datetrigger js-open-datedrawer" href="#">View dates</a>
                        <?php endif; ?>
                        <a class="<?php if( !$is_tour_finder ) echo 'cta '; ?>tourlisting__detaillink" href="<?php echo $permalink; ?>">View Tour</a>
                    </div>

                </div><!-- .tourlisting__data -->

                <div class="tourlisting__datedrawer">
                    <?php duvine_render_tour_date_drawer(); ?>
                </div>
                <?php if( $is_tour_finder ) : ?>
            </div>
        <?php endif; ?>
        </li>
        <?php
        if( $_post !== null ){
            wp_reset_postdata();
        }
    }
}

if (!function_exists('duvine_render_name_grouped_tourloop')) {
    /**
     * Lists tours based on the tour arguments and grouped by tour name
     *
     * @param array $args
     * @param int $depth
     * @return void
     */
    function duvine_render_name_grouped_tourloop(array $args, int $depth = 1) {
        $defaults = [
            'post_type' => 'tour',
            'orderby' => ['title' => 'ASC'],
        ];
        $queryArgs = array_merge($defaults, $args);
        $tourList = get_posts($queryArgs);
        $filterData = [];
        $isMultipleLocation = false;
        $tourCount = count($tourList);
        foreach ($tourList as $key => $tour) {
            $locations = get_field('d_tour_location', $tour->ID);
            $locationId = duvine_get_location_at_depth($locations, $depth);
            if (!array_key_exists($locationId, $filterData)) {
                $location = get_the_title($locationId);
                $filterData[$locationId] = $location;
            }
            $tourList[$key]->locID = $locationId;
        }
        if (count($filterData) > 1) {
            $isMultipleLocation = true;
            natcasesort($filterData);
        }
        if ($tourList) {
            if ($isMultipleLocation) {
                ?>
                <div class="tourloop-filter">
                    <div class="tourloop__quickfilter">
                        <span class="filtercell__label">Destinations</span>
                        <select class="js-duvine-chosen quickfilter__filters" data-placeholder="Filter by region">
                            <option value="all">All destinations</option>
                            <?php foreach($filterData as $key => $title): ?>
                                <option value="<?php echo $key; ?>"><?php echo $title; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <header class="headerpromo__wrapper">
                        <?php duvine_text_private_promo(); ?>
                    </header>
                </div>
                <?php
            }
            ?>
            <div class="tourloop__holder tour-finder <?php echo ($tourCount < 2) ? 'alone-tour' : ''?>">
                <ul class="tourlist baselist">
                    <?php foreach($tourList as $tour): ?>
                        <?php duvine_render_grouped_tour_item($tour, true, $tour->locID); ?>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php
        } else {
            ?><p>No tours found.</p><?php
        }
    }
}

if(!function_exists('duvine_render_collection_grouped_tourloop')){
    /**
     * Lists tours based on the tour arguments, with filtering and grouped by tour location
     *
     * @param array $args     The WP_Query arguments
     * @param int   $depth    Depth of the location
     */
    function duvine_render_collection_grouped_tourloop(array $args, int $depth = 1){
        $defaults = [
            'post_type' => 'tour',
            'orderby' => ['title' => 'ASC'],
        ];
        $queryArgs = array_merge($defaults, $args);
        $tourList = get_posts($queryArgs);
        $tourQuery = duvine_organize_tours_by_location($tourList, $depth);
        uksort($tourQuery, 'duvine_sort_tours_by_location');
        $filterData = [];
        $tourCount = count($tourList);
        foreach ($tourQuery as $key => $tour) {
            $location = get_the_title($key);
            $filterData[$key] = $location;
        }
        $isMultipleLocation = count($filterData) > 1;
    ?>
        <?php if ($tourQuery) : ?>
            <?php if ($isMultipleLocation) :?>
                <div class="tourloop-filter">
                    <div class="tourloop__quickfilter">
                        <span class="filtercell__label">Destinations</span>
                        <select class="js-duvine-chosen quickfilter__filters" data-placeholder="Filter by region">
                            <option value="all">All destinations</option>
                            <?php foreach($filterData as $key => $title) : ?>
                                <option value="<?php echo $key; ?>"><?php echo $title; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                        <header class="headerpromo__wrapper">
                            <?php duvine_text_private_promo(); ?>
                        </header>
                </div>
            <?php endif; ?>
            <div class="tourloop__holder tour-finder <?php echo ($tourCount < 2) ? 'alone-tour' : ''?>">
                <ul class="tourlist baselist">
                <?php foreach($tourQuery as $loc => $tours) : ?>
                        <?php if($tours) : ?>
                                <?php foreach ($tours as $tour) : ?>
                                    <?php duvine_render_grouped_tour_item($tour, true, $loc); ?>
                                <?php endforeach; ?>
                        <?php endif; ?>
                <?php endforeach; ?>
                </ul>
            </div>
        <?php else : ?>
            <p>No tours found.</p>
        <?php endif; ?>
    <?php
    }
}

if( !function_exists( 'duvine_sort_tours_by_location' ) ) :
/**
 * Sorts the given tour by location
 */
function duvine_sort_tours_by_location( $a, $b ){
    $a_title = get_the_title( $a );
    $b_title = get_the_title( $b );

    //error_log('the title for a: '.$a_title);
    //error_log('the title for b: '.$b_title);
    
    
    return strcmp( $a_title, $b_title );
}
endif; // duvine_sort_tours_by_location









if( !function_exists('duvine_render_tour_placard') ) :
/**
 * Renders the location placard
 */
function duvine_render_tour_placard( &$_post = null ){

    global $wp;
    $current_url = $_SERVER['REQUEST_URI'];
    $is_tour_finder = ( strpos($current_url, 'tour-finder') || strpos($current_url, 'action=filter_tourloop') || strpos($current_url, 'action=lazyload_tours') ) ? true : false;
    
    global $post;

    if( $_post !== null ){
        $post = $_post;

        setup_postdata( $post );
    }

    $taxfilters = duvine_get_active_filters( $post );
    $permalink  = get_the_permalink();
    $centaur_id = trim(get_field('d_tour_centaur_id'));
    $privateOnly = get_field('d_private_only');
?>
    <li class="tourlist__listing tourlist__listing--<?php echo get_field('d_private_only') ? 'private' : 'scheduled' ;?>">

        <?php if( $is_tour_finder ) : ?>
        <div class="tourlisting__inner">
        <?php endif; ?>
            <div class="tourlisting__image"><a href="<?php echo $permalink; ?>">
                <?php
                    sk_the_field('d_thumbnail_image', array(
                        'filter'      => 'sk_img_markup',
                        'filter_args' => array(
                            'img_size' => 'tour_thumbnail'
                        ),
                        'default'     => '<div class="tourlisting__placeholder"></div>'
                    ));
                ?>
            </a>
            <?php if( $is_tour_finder === true ) : ?>
                <?php duvine_tour_flags(); ?>
                <?php endif; ?>
            </div><!-- .tourlisting__image -->

            <div class="tourlisting__data">
                <?php if( $is_tour_finder === false ) : ?>
                <?php duvine_tour_flags(); ?>
                <?php endif; ?>

                <header>
                    <div class="tourlisting__location"><?php duvine_tour_breadcrumbs( $post->ID, true ); ?></div>
                    <h3 class="blockheader"><a href="<?php echo $permalink; ?>"><?php the_title(); ?></a></h3>
                </header>

                <?php
                    sk_the_field('d_tour_subtitle', array(
                        'before' => '<div class="tourlisting__description">',
                        'after'  => '</div>'
                    ));
                ?>

                <div class="tourlisting__meta">
                    <?php
                        sk_the_field('d_tour_duration', array(
                            'before' => '<span class="tourmeta tourmeta--separator">',
                            'after'  => '</span>',
                            'filter' => 'duvine_tourlength_strip_nights'
                        ));
                        $tourDates = duvine_get_tour_dates();
                        if($privateOnly || !is_array($tourDates)) {
                            $tour_level = get_field('d_cycling_level');
                            $tour_level = $tour_level['value'];
                        } else {
                            $tour_level = duvine_get_tour_level($centaur_id);
                        }
                        echo '<span class="tourmeta tourmeta--separator"><span class="tourmeta tourmeta--label">Level:</span>&nbsp;' . $tour_level . '</span>';
                        /*sk_the_field('d_cycling_level', array(
                            'before' => '<span class="tourmeta tourmeta--separator">',
                            'after'  => '</span>',
                            'filter' => 'duvine_cycling_level'
                        ));*/

                        if( $minPrice = duvine_get_minimum_price() ){
                            $price = '<span class="tourmeta tourmeta--separator">';
                            if( $is_tour_finder === true ) {
                                $price .= '<span class="tourmeta tourmeta--label">Price From:</span>&nbsp;' . $minPrice . '</span>';
                            } else {
                                $price .= '<strong>Price From: </strong>' . $minPrice . '</span>';
                            }
                            echo $price;
                        }

                        if( $is_tour_finder === true) {
                            if (get_field('d_private_only') != 'private' ) {
                                echo '<span class="tourmeta tourmeta--separator years-list"><span class="tourmeta tourmeta--label">Dates:</span>&nbsp;</span>';
                                if (!is_array($tourDates)) {
                                    echo $tourDates;
                                } else {
                                    duvine_render_tour_date_years();
                                }
                            }
                        }
                    ?>
                </div>
                
                <?php if( $taxfilters ) : ?>
                    <p class="tourlisting__filters"><?php echo $taxfilters; ?></p>
                <?php endif; ?>


                <div class="tourlisting__footer l-cf">
                    <?php if( $is_tour_finder === false ) : ?>
                    <a class="tourlisting__datetrigger js-open-datedrawer" href="#">View dates</a>
                    <?php endif; ?>
                    <a class="<?php if( !$is_tour_finder ) echo 'cta '; ?>tourlisting__detaillink" href="<?php echo $permalink; ?>">View Tour</a>
                </div>

            </div><!-- .tourlisting__data -->

            <div class="tourlisting__datedrawer">
                <?php duvine_render_tour_date_drawer(); ?>
            </div>
        <?php if( $is_tour_finder ) : ?>
        </div>
        <?php endif; ?>
    </li>
<?php
    if( $_post !== null ){
        wp_reset_postdata();
    }
}
endif; // duvine_render_tour_placard





if( !function_exists( 'duvine_render_tour_date_drawer' ) ) :
/**
 * Renders the date drawer
 */
function duvine_render_tour_date_drawer(){
    global $post;

    $tourDates = duvine_get_tour_dates();

    $privateOnly = get_field('d_private_only');
    //$dates = get_field('d_tour_schedules');
?>

    <div class="datedrawer">
        <?php if( $tourDates ) : ?>
            <div class="tourdates dateslider" data-yearsperslide="2">
                <div class="dateslider__sliderwrap">
                    <div class="dateslider__slider">
                        <?php if( is_array($tourDates) ) : ?>
                            <?php foreach( $tourDates as $year => $dates ) : ?>
                                <div class="dateslider__el">
                                    <div class="dateslider__elheader">
                                        <span class="dateslider__eltitle"><?php echo $year; ?></span>
                                    </div>
                                    <ul class="dateslider__datelist">
                                        <?php foreach( $dates as $date ) : ?>
                                        <?php 
                                        // Centaur need to check this on the feed!!!!
                                        $flag = $date['flags'] ? $date['flags']['value'] : ''; ?>
                                            <li class="dateslider__date <?php if( $flag === 'sold_out' ) echo ' dateslider__date--soldout'; ?>"><?php echo $date['label']; ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endforeach; ?>
                        <?php elseif( is_string($tourDates) && !$privateOnly ) : ?>
                            <div class="dateslider__el">
                                <div class="dateslider__elheader">
                                    <span class="dateslider__eltitle"><?php echo $tourDates; ?></span>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php elseif( !$privateOnly ) : ?>
            <?php duvine_text_dates_coming_soon(); ?>
        <?php endif; ?>
        
        <?php
            if(duvine_is_family_tour()){
                duvine_text_family_tour_date_dropdown();
            } elseif($privateOnly){
                duvine_text_private_tour_date_dropdown();
            } else {
                duvine_text_scheduled_tour_date_dropdown();
            }
        ?>
    </div>

<?php
}
endif; // duvine_render_tour_date_drawer



if( !function_exists( 'duvine_render_tour_date_years' ) ) :
/**
 * Renders the date drawer
 */
function duvine_render_tour_date_years($is_tour_finder = false){

    global $post;

    $tourDates = duvine_get_tour_dates();

    $privateOnly = get_field('d_private_only');
    $dates = get_field('d_tour_schedules');
    $years = array();
    $centaur_id = get_field('d_tour_centaur_id');
    $date_content = '';
?>

    <?php if( $tourDates && is_array($tourDates) ) : ?>
        <span class="years-list">
            <?php foreach( $tourDates as $year => $dates ) :
                $date_content = '<h4>'.$year.' Tour Dates</h4><ul>';
                foreach( $dates as $date ) :
                    //$flag = $date['flags']['OnlineTourStatus'] ? $date['flags']['OnlineTourStatus'] : '';
                    $date_flags['flags']['OnlineTourStatus'] = $date['flags']['OnlineTourStatus'];

                    $date_flags['flags']['HoldInventory'] = $date['flags']['HoldInventory'];
                    $date_flags['flags']['DepartureDateOnlineFlag'] = $date['flags']['DepartureDateOnlineFlag'];
                    $date_flags['flags']['AvailableInventory'] = $date['flags']['AvailableInventory'];
                    $date_flags['flags']['ThresholdInventory'] = $date['flags']['TotalRoomsInventory']['ThresholdInventory'];
                    $date_flags['flags']['NumberOfPax'] = $date['flags']['NumberOfPax'];
                    
                    $tour_status = duvine_get_tour_date_status($date_flags['flags'], $date['label'], $year);
                    
                    $date_content .= '<li class="dateslider__date ';
                    
                    if( $tour_status === 'sold_out' || $tour_status === 'sold_out_w_private' ) $date_content .= ' dateslider__date--soldout';
                    $date_content = $date_content.'"><a href="'.get_permalink($post).'#tour-dates">'.$date['label'].'</a></li>';
                endforeach;
                $date_content .= '</ul>';

                $years[] = duvine_get_dates_tooltip($centaur_id.'_'.$year, $year, $date_content, $is_tour_finder);
            endforeach; ?>
            <?php echo implode (", ", $years); ?>
        </span>
    <?php endif; ?>

<?php
}
endif; // duvine_render_tour_date_years






if( !function_exists( 'duvine_render_tourgallery' ) ) :
/**
 * Renders the tourgallery
 *
 * @param array $gallery  The gallery array from WP
 */
function duvine_render_tourgallery( $gallery ){

    if( ! $gallery ){
        return false;
    }

    echo '<div class="tourgallery">';

    foreach( $gallery as $img ) {
        $width  = $img['width'];
        $height = $img['height'];
        $ratio  = $height / $width;
        $renderArgs = array( 'img_size' => 'full', 'container_class' => 'tourgallery__image' );

        if( $width < 960 && $ratio < .7 ){
            $renderArgs['img_size'] = 'tourgallery_rectangle';
            $renderArgs['container_class'] = 'tourgallery__image tourgallery__image--rectangle';
        } else if( $width < 960 ){
            $renderArgs['img_size'] = 'tourgallery_square';
            $renderArgs['container_class'] = 'tourgallery__image tourgallery__image--square';
        }

        duvine_render_img( $img, $renderArgs );

    }

    echo '</div>';
}
endif; // duvine_render_tourgallery






if( !function_exists( 'duvine_render_featuredin' ) ) :
/**
 * Renders the publications that are included in the given list of press posts
 *
 * @param array $press, The list of press posts
 */
function duvine_render_featuredin( $press ){

    if( !$press ){
        return false;
    }

    // hold the pubs
    $publications = array();
    $awards = array();

    if($press) {

    echo '<div class="featuredin">';
    echo    '<h6 class="capsheader">As featured in:</h6>';
    echo    '<ul class="menu">';

        // foreach( $press as $pressPost ){

        //     // get awards
        //     if( $award = get_field('d_press_award_logo', $pressPost->ID) ){
        //         $awardLogo = duvine_get_url_from_object( $award );
        //         $awardURL = get_field('d_press_url',$pressPost->ID);

        //         echo '<li class="featuredin__logo featuredin__logo--awards">';
        //             echo '<a target="_blank" href="' . $awardURL . '">';
        //                 echo '<img src=' . $awardLogo . '>';
        //             echo '</a>';
        //         echo '</li>';
        //     }
        // }

        foreach( $press as $press_item ){
            if( $pub = get_field('d_press_publication', $press_item->ID) ){
                $pub_name = $pub->post_name;
                if(get_field('d_press_award_logo', $press_item->ID)) {
                    $logo = get_field('d_press_award_logo', $press_item->ID)['url'];
                } else {
                    $logo = duvine_get_url_from_object( get_post_thumbnail_id( $pub ), 'full' );
                }

                $url = duvine_get_press_url($press_item);

                echo "<li class=\"featuredin__logo featuredin__logo--$pub_name\">";

                if( $url ){
                    echo "<a href=\"$url\" target=\"_blank\">";
                }

                echo "<img src=\"$logo\" alt=\"$pub_name\">";

                if( $url ){
                    echo "</a>";
                }

                echo "</li>";
            }
        }

    echo    '</ul>';
    echo '</div>';

    }

    if( !$press && !$awards){
        return false;
    }



    // foreach( $awards as $awardLogo ){
    //     echo '<li class=\"featuredin__logo featuredin__logo--awards\">';
    //     echo '<img src=\"$awardLogo\">';
    //     echo '</li>';
    // }


}
endif; // duvine_render_featuredin

if(!function_exists( 'duvine_get_location_list')) {
    /**
     * Get the list of locations for desktop
     *
     * @return array
     */
    function duvine_get_location_list(): array
    {
        $locationList = [];
        $locations = getLocationsList();
        foreach ($locations as $location) {
            $locationList[] = [
                'id' => $location['data']['id'],
                'key' => $location['data']['title'],
                'label' => $location['data']['title']
            ];
            $childLocation = getChildFromList($location['child'], true, $location['data']['title']);
            foreach ($childLocation as $item) {
                $locationList[] = $item;
            }
        }
        
        return $locationList;
    }

    /**
     * @param array $locations
     * @param bool $isFirstLevel
     * @param string $parentLabel
     * @return array
     */
    function getChildFromList(array $locations, bool $isFirstLevel, string $parentLabel): array
    {
        $locationList = [];
        foreach ($locations as $location) {
            $label = $location['data']['title'];
            if ($isFirstLevel) {
                $label .= htmlentities(' <span class="destination__continent">in ' . $parentLabel . '</span>');
            } else {
                $label .= ', ' . $parentLabel;
            }
            $locationList[] = [
                'id' => $location['data']['id'],
                'key' => $location['data']['title'],
                'label' => $label
            ];
            if (array_key_exists('child', $location) && $location['child']) {
                $childLocation = getChildFromList($location['child'], false, $label);
                foreach ($childLocation as $item) {
                    $locationList[] = $item;
                }
            }
        }

        return $locationList;
    }
}

if (!function_exists('duvine_get_location_list_mobile')) {
    /**
     * Get the list of locations for mobile
     *
     * @return string
     */
    function duvine_get_location_list_mobile(): string {
        return displayOptions(getLocationsList());
    }

    /**
     * Get the list of locations (the content type) from the WordPres cms
     *
     * @return array
     */
    function getLocationsList(): array
    {
        if(false === ($locationList = get_transient( 'duvine_locations_list'))) {
            $parentLocations = get_posts([
                'post_type' => 'location',
                'posts_per_page' => -1,
                'orderby' => 'menu_order',
                'order' => 'ASC',
                'post_parent' => 0
            ]);
            $locationList = [];
            if ($parentLocations) {
                foreach ($parentLocations as $parentLocation) {
                    $parentId = $parentLocation->ID;
                    $locationList[$parentLocation->ID]['data'] = [
                        'title' => $parentLocation->post_title,
                        'id' => $parentId
                    ];
                    $locationList[$parentLocation->ID]['child'] = getChildListLocations($parentId);
                }
            }
            set_transient( 'duvine_locations_list', $locationList, 60 * 60 );
        }

        return $locationList;
    }

    /**
     * @param int $parentId
     * @return array
     */
    function getChildListLocations(int $parentId): array
    {
        $childList = [];
        $childLocations = get_posts([
            'post_type' => 'location',
            'posts_per_page'=> -1,
            'orderby' => 'menu_order',
            'order' => 'ASC',
            'post_parent'=> $parentId
        ]);
        if ($childLocations) {
            foreach ($childLocations as $childLocation) {
                $childId = $childLocation->ID;
                $childList[$childLocation->ID]['data'] = [
                    'title' => $childLocation->post_title,
                    'id'  => $childId
                ];
                $childList[$childLocation->ID]['child'] = getChildListLocations($childId);
            }
        }

        return $childList;
    }

    /**
     * @param array $locations
     * @return string
     */
    function displayOptions(array $locations): string
    {
        return displayFakeSelect($locations);
    }

    /**
     * @param array $locations
     * @return string
     */
    function displayFakeSelect(array $locations): string
    {
        $selectedOptions = get_query_var('destination');
        $select = '<div class="duvine-chosen destination-mobile">';
        foreach ($locations as $location) {
            $select .= getParentBlockHtml($location, $selectedOptions, true);
        }
        $select .= '</div>';

        return $select;
    }

  /**
   * @param array $location
   * @param $selectedOptions
   * @param bool $isParent
   * @param int $level
   * @return string
   */
    function getParentBlockHtml(array $location, $selectedOptions, bool $isParent = false, int $level = 1): string
    {
        $isSelected = isOptionSelected($location, $selectedOptions);
        $optionsHtml = '<ul class="parent-option"><div data-is-parent="'
          . (int)$isParent . '" data-has-child="'
          . (int)!empty($location['child']) . '" class="parent-option-header'
          . $isSelected . '"><span  class="parent-location-item" data-option-value="'
          . $location['data']['id'] . '">'
          . $location['data']['title']
          . '</span><span class="option-expand"></span></div><div class="parent-option-content collapsed" style="height: 0;">';
        foreach ($location['child'] as $childLocation) {
            $optionsHtml .= getChildBlockHtml($childLocation, $selectedOptions, $level + 1);
        }
        $optionsHtml .= '</div></ul>';

        return $optionsHtml;
    }

    /**
     * @param array $location
     * @param $selectedOptions
     * @return string
     */
    function isOptionSelected(array $location, $selectedOptions): string
    {
        $isSelected = '';
        if ($selectedOptions) {
            $isSelected = in_array($location['data']['id'], $selectedOptions)  ? ' active' : '';
        }

        return $isSelected;
    }

  /**
   * @param array $location
   * @param $selectedOptions
   * @param int $level
   * @return string
   */
    function getChildBlockHtml(array $location, $selectedOptions, int $level): string
    {
        $isSelected = isOptionSelected($location, $selectedOptions);
        $optionsHtml = '';
        if ($location['child'] || $level < 3) {
            $optionsHtml .= getParentBlockHtml($location, $selectedOptions, false, $level);
        } else {
            $optionsHtml .= getFakeOptionHtml($location['data'], $isSelected);
        }

        return $optionsHtml;
    }

    /**
     * @param array $optionData
     * @param string $isSelected
     * @return string
     */
    function getFakeOptionHtml(array $optionData, string $isSelected): string
    {
        return '<li data-option-value="' . $optionData['id'] . '" class="location-item'
          . $isSelected . '">' . $optionData['title'] . '</li>';
    }
}

if (!function_exists('duvine_get_tour_tags')) {
  /**
   * Lists out all terms in the given taxonomy
   *
   * @param string $taxonomy
   * @param string $label
   * @return array
   */
  function duvine_get_tour_tags(string $taxonomy, string $label): array {
    $tags = [];
    $queryFilters = get_query_var('morefilters');
    $queryTerms = [];
    foreach ($queryFilters as $queryFilter) {
      $searchTerm = strtolower($label) . '__';
      if (strpos($queryFilter, $searchTerm) !== false) {
        $queryTerms[] = (int)str_replace($searchTerm, '', $queryFilter);
      }
    }
    $terms = get_terms([
      'taxonomy'   => $taxonomy,
      'hide_empty' => false
    ]);
    if (!$terms || isset($terms->errors)) {
      return [];
    }
    $label = $label === '' ? ucfirst($taxonomy) : $label;
    foreach ($terms as $term) {
      $isChecked = $queryTerms && in_array($term->term_id, $queryTerms, true);
      $tags[] = [
        'label' => $term->name,
        'value' => strtolower($label) . '__' . $term->term_id,
        'isChecked' => $isChecked
      ];
    }

    return $tags;
  }
}

if( !function_exists( 'duvine_list_tour_tags' ) ) :
/**
 * Lists out all of the terms in the given taxonomy
 *
 * @param string $tax   The taxonomy to get all of the tags from
 * @param string $label Label for the filter list
 */
function duvine_list_tour_tags( $tax, $label = '' ){

    $q_morefilters = get_query_var('morefilters');
    $q_terms = [];
    foreach ($q_morefilters as $var) {
        $searchTerm = strtolower($label) . '__';
        if (strpos($var, $searchTerm) !== false) {
            $q_terms[] = str_replace($searchTerm, '', $var);
        }
    }

    $terms = get_terms( array(
        'taxonomy'   => $tax,
        'hide_empty' => false
    ));

    if( ! $terms || isset( $terms->errors ) ){
        return false;
    }

    $label = $label === '' ? ucfirst($tax) : $label;
?>

        <optgroup label="<?php echo $label; ?>">
        <?php foreach( $terms as $term ) : ?>
            <?php $checked = $q_terms && in_array( $term->term_id, $q_terms ) ? ' selected' : ''; ?>
            
            <option value="<?php echo strtolower($label).'__'.$term->term_id; ?>" <?php echo $checked;?> class="psuedo-checkbox"><?php echo $term->name; ?></option>

        <?php endforeach; ?>
        </optgroup>
<?php
}
endif; // duvine_list_tour_tags



if (!function_exists('duvine_date_range_input')) {
    /**
     * Renders the date range
     */
    function duvine_date_range_input()
    {
        $q_date = get_query_var('date');
        ?>
            <div class="chosen-container chosen-container-multi js-duvine-daterange" style="width: 100%">
                <ul class="chosen-choices chosen-date datepicker__trigger">
                    <li class="search-field"<?php if( $q_date ) echo ' style="width: 0; overflow:hidden;"'; ?>>
                        <span class="chosen-date-placeholder">Select departure date</span>
                    </li>
                    <li class="search-choice" data-label="<?php echo $q_date; ?>">
                        <span><?php echo $q_date; ?></span><a class="search-choice-close"></a>
                    </li>
                </ul>
                <input name="date" id="date-range" type="hidden" autocomplete="off" value="<?php echo $q_date?>"/>
            </div>
        <?php
    }
}

if( !function_exists( 'duvine_date_picker_input' ) ) :
/**
 * Renders the date picker
 */
function duvine_date_picker_input(){
    $q_dates = get_query_var('date');
?>
    <div class="chosen-container chosen-container-multi js-duvine-datepicker" style="width: 100%;">
        <ul class="chosen-choices chosen-date datepicker__trigger">
            <li class="search-field"<?php if( $q_dates ) echo ' style="width: 0; overflow:hidden;"'; ?>>
                <span class="chosen-date-placeholder">Select months and years</span>
            </li>
            <?php if( $q_dates ) : ?>
                <?php foreach( $q_dates as $d ) : ?>
                    <?php $year = substr($d, 0, 4); ?>
                    <?php $month = apply_filters('map_month', substr($d, 4), true ); ?>
                    <?php $label = $month . ' ' . $year; ?>
                    <li class="search-choice" data-label="<?php echo $label; ?>"><span><?php echo $label; ?></span><a class="search-choice-close"></a></li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>

        <div class="chosen-drop">
            <?php duvine_render_dateslider(); ?>
        </div>
    </div>
<?php
}
endif; // duvine_date_picker_input





if( !function_exists( 'duvine_render_dateslider' ) ) :
/**
 * Renders the dateslider
 *
 * @param int $yearsPerSlide The number of years that appear in the carousel
 */
function duvine_render_dateslider( $yearsPerSlide = 1 ){

    $currentYear = date('Y');

    // get the number of years in the future we should show
    $currentMonth = date('n');
    $yearsForward = $currentMonth > 9 ? 2 : 1;

    $years = array( $currentYear );
    $months = array( 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec' );

    for( $i = 1; $i <= $yearsForward; $i++ ){
        array_push($years, $currentYear + $i );
    }

    $q_dates = get_query_var('date');
?>
    <div class="dateslider" data-yearsperslide="<?php echo $yearsPerSlide; ?>">
        <div class="dateslider__sliderwrap">
            <div class="dateslider__slider">
                <?php foreach( $years as $year ) : ?>
                    <div class="dateslider__el">
                        <div class="dateslider__elcontainer">
                            <header class="dateslider__elheader">
                                <span class="dateslider__eltitle"><?php echo $year; ?></span>

                                <?php if( $yearsPerSlide > 1 ) : ?>
                                    <span class="checkmark--white checkmark--small dateslider__option--selectall"><input type="checkbox" id="dateslider--<?php echo $year; ?>--selectall"><label class="tourfinderbanner__label" for="dateslider--<?php echo $year; ?>--selectall">Select all months</label></span>
                                <?php endif; ?>
                            </header>
                            <ul class="dateslider__dateblocks">
                                <?php foreach( $months as $mIndex => $month ) : ?>
                                    <?php $dateId = "datepicker--$month-$year"; ?>
                                    <?php $queryString = $year . sprintf("%02d", $mIndex + 1); ?>
                                    <?php $displayDate = "$month $year"; ?>
                                    <?php $checked = $q_dates && in_array($queryString, $q_dates) ? ' checked' : ''; ?>
                                    <?php 
                                    $monthClass = '';

                                    if($queryString < date('Ym')) {
                                        $monthClass = 'passed';
                                    }
                                    ?>

                                    <?php

                                    if(get_posts())

                                    ?>

                                    <li class="dateslider__monthblock"><input data-display="<?php echo $displayDate; ?>" id="<?php echo $dateId; ?>" type="checkbox" name="date[]" value="<?php echo $queryString; ?>"<?php echo $checked; ?>><label for="<?php echo $dateId; ?>" class="<?php echo $monthClass; ?>"><span><?php echo $month; ?></span></label></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php
}
endif; // duvine_render_dateslider






if( !function_exists( 'duvine_tour_flags' ) ) :
/**
 * Output the tour flags
 */
function duvine_tour_flags(){
    global $post;
    
    $guaranteed  = get_field('d_guaranteeed_to_run');
    $newtour     = get_field('d_new_tour');
    $privatetour = get_field('d_private_only');
    $comingsoon  = get_field('d_tour_coming_soon');

    if( $newtour || $privatetour || $comingsoon ) {
        echo '<div class="infobox__flags">';
            if( $newtour ) echo '<span class="infobox__flag">New Tour</span>';
            if( $privatetour ) echo '<span class="infobox__flag">Private only</span>';
            if( $comingsoon) echo '<span class="infobox__flag">Coming soon</span>';
        echo '</div>';
    }
}
endif; // duvine_tour_flags


if( !function_exists( 'duvine_tour_breadcrumbs' ) ) :
/**
 * Render breadcrumbs for a single tour
 *
 * @param int     $postId ID of the post in wordpress
 * @param boolean $link   Whether or not to link the breadcrumbs
 */
function duvine_tour_breadcrumbs( $postId = null, $link = false ){

    if( $postId === null ){
        global $post;
        $postId = $post->ID;
    }


    $locations = get_field('d_tour_location', $postId);

    if( ! $locations ){
        return false;
    }

    $breadcrumbs = '<ul class="breadcrumbs baselist">';

    foreach( $locations as $locID ){
        $locTitle = get_the_title( $locID );
        $link = get_the_permalink( $locID );
        $breadcrumbs .= '<li class="breadcrumb__item">';
        $breadcrumbs .= $link ? '<a class="breadcrumb__link basiclink" href="' . get_the_permalink( $locID ) . '">' . $locTitle . '</a>' : $locTitle;
        $breadcrumbs .= '</li>';
    }

    $breadcrumbs .= '</ul>';

    echo $breadcrumbs;
}
endif; // duvine_tour_breadcrumbs



if( !function_exists( 'duvine_region_breadcrumbs' ) ) :
/**
 * Renders breadcrumbs for the current region
 */
function duvine_region_breadcrumbs(){
    global $post;

    $ancestors = get_ancestors( $post->ID, 'location', 'post_type' );

    if( ! $ancestors ){
        return false;
    }

    $breadcrumbs = '<ul class="breadcrumbs baselist">';

    $currTitle = get_the_title();
    $reversedAncestors = array_reverse( $ancestors );

    foreach( $reversedAncestors as $ancestor ){
        $ancestorTitle = get_the_title( $ancestor );
        $ancestorLink = get_the_permalink( $ancestor );
        $breadcrumbs .= "<li class=\"breadcrumb__item\"><a class=\"breadcrumb__link\" href=\"$ancestorLink\">$ancestorTitle</a></li>";
    }

    $breadcrumbs .= "<li class=\"breadcrumb__item\">$currTitle</li>";
    $breadcrumbs .= '</ul>';

    echo $breadcrumbs;
}
endif; // duvine_region_breadcrumbs







if( !function_exists( 'duvine_accordion_element' ) ) :
/**
 * Builds an individual accordion element
 */
function duvine_accordion_element( $title, &$content = '' ){
?>
    <li>
        <header class="accordion__header accordion__header--showlesslabel js-duvine-accordion-trigger">
            <?php echo $title; ?>
        </header>
        <div class="accordion__content">
            <div class="accordion__contentwrapper d-content">
                <?php echo $content; ?>
            </div>
        </div>
    </li>
<?php
}
endif; // duvine_accordion_element

if (!function_exists('duvine_render_overlaid_image')) {
    /**
     * Renders the overlay badge only if the conditions for showing the field inside ACF are met.
     * @return void
     */
    function duvine_render_overlaid_image(): void
    {
        $overlaidImageObject = get_field_object('d_hero_overlaid_image');
        $isExist = is_show_acf_field($overlaidImageObject);
        if ($isExist) {
            $heroType = get_field('d_hero_type');
            duvine_render_img($overlaidImageObject['value'], [
                'container_class' => "bannerhero__overlayimage hero_type_$heroType"
            ]);
        }
    }
}

if( !function_exists( 'duvine_render_homepage_hero' ) ) :
/**
 * Renders the homepage hero
 */
function duvine_render_homepage_hero(){

    $heroType = get_field('d_hero_type');
    $autoscroll = get_field('d_hero_autoscroll_speed');
?>
    <div class="duvine-homepage-hero <?php echo $heroType; ?>" data-autoscroll="<?php echo $autoscroll; ?>">
        <?php duvine_render_overlaid_image(); ?>
        <?php
            $heroCopyClass = "hero__title pageheader hero_type_$heroType";
            $heroCopy = sk_get_field('d_hero_copy', array(
                'before' => '<h2 class="' . $heroCopyClass . '">',
                'after'  => '</h2>'
            ));
        ?>

        <?php if( $heroType === 'dayslider' ) : ?>
            <?php
                $morning   = get_field('d_hero_morning');
                $afternoon = get_field('d_hero_afternoon');
                $evening   = get_field('d_hero_evening');
                $night     = get_field('d_hero_night');

                $morning_label   = get_field('d_hero_morning_label');
                $afternoon_label = get_field('d_hero_afternoon_label');
                $evening_label   = get_field('d_hero_evening_label');
                $night_label     = get_field('d_hero_night_label');

                $imgArgs = array(
                    'img_size'        => 'banner_hero',
                    'container_class' => 'heroslide__slide bannerhero__image'
                );

                $activeImgArgs = array_merge( $imgArgs, array('container_class' => 'heroslide__slide heroslide__slide--active bannerhero__image') );
            ?>
            <div class="dayslider bannerhero">
                <div class="overlay"></div>
                <div class="heroslides">
                    <?php duvine_render_img( $morning, $activeImgArgs ); ?>
                    <?php duvine_render_img( $afternoon, $imgArgs ); ?>
                    <?php duvine_render_img( $evening, $imgArgs ); ?>
                    <?php duvine_render_img( $night, $imgArgs ); ?>
                </div>
            </div>

            <div class="hero__center <?= "hero_type_$heroType" ?>">

            <?php if(get_field('d_hero_cta_link')) { ?>
                    <a style="display: block;" href="<?php the_field('d_hero_cta_link'); ?>"><?php echo $heroCopy; ?> </a>
                <?php } ?>
                
                    <ul class="dayslider__nav menu" <?php if(!get_field('show_navigation')) { echo 'style="visibility:hidden;height:0;margin:0;"'; } ?>>
                        <?php if( $morning ) : ?><li class="dayslider__time dayslider__time--active"><?php echo $morning_label; ?></li><?php endif; ?>
                        <?php if( $afternoon ) : ?><li class="dayslider__time"><?php echo $afternoon_label; ?></li><?php endif; ?>
                        <?php if( $evening ) : ?><li class="dayslider__time"><?php echo $evening_label; ?></li><?php endif; ?>
                        <?php if( $night ) : ?><li class="dayslider__time"><?php echo $night_label; ?></li><?php endif; ?>
                    </ul>
               
                    <?php if(get_field('d_hero_cta_text')) { ?>
                        <a class="hero-cta" href="<?php the_field('d_hero_cta_link'); ?>"><?php the_field('d_hero_cta_text'); ?></a>
                    <?php } ?>
               
            </div>
        <?php else : ?>
            <div class="bannerhero">
                <?php 
                if( get_field('desktop_header_before_video_load') && get_field('mobile_header_replacement') ) : ?>
                    <style>
                   .background-image {
                        background-image:url('<?php the_field('desktop_header_before_video_load') ?>');
                    }
                    @media only screen and ( max-width: 580px ) {
                        .background-image {
                            background-image:url('<?php the_field('mobile_header_replacement') ?>');
                        }
                    }
                    </style>
                <?php endif; ?>
                <div class="overlay"></div>
                <div class="background-image"></div>
                <?php if((get_field('d_hero_type') == 'video') && get_field('d_hero_video_homepage')) { ?>
                    <video data="<?php the_field('d_hero_video_homepage'); ?>" loop="true" autoplay="autoplay" id="vid" muted onended="this.play();" playsinline>
                      
                    </video>
                <?php } else { ?>

                    <?php
                        duvine_render_img( get_field('d_hero_image_homepage'), array(
                            'img_size'        => 'banner_hero',
                            'container_class' => 'bannerhero__image'
                        ));
                    ?>

                <?php } ?>
            </div>
            <div class="hero__center <?= "hero_type_$heroType" ?>">
                <?php if(get_field('d_hero_cta_text')) { ?>
                    <a style="display: block;" href="<?php the_field('d_hero_cta_link'); ?>">
                <?php } ?>

                <?php echo $heroCopy; ?>
                
                <?php if(get_field('show_hero_copy_link') && get_field('hero_copy_link')) { ?>
                    </a>
                <?php } ?>
                
                
                    <?php if(get_field('d_hero_cta_text')) { ?>
                        <a class="hero-cta" href="<?php the_field('d_hero_cta_link'); ?>"><?php the_field('d_hero_cta_text'); ?></a>
                    <?php } ?>
               
            </div>
        <?php endif; ?>
    </div>
<?php
}
endif; // duvine_render_homepage_hero





if( !function_exists( 'duvine_render_page_hero' ) ) :
/**
 * description
 */
function duvine_render_page_hero(){
    global $post;

    duvine_render_img( get_field('d_page_hero'), array(
        'img_size'        => 'banner_hero_page',
        'container_class' => 'pagehero'
    ));
}
endif; // duvine_render_page_hero





if( !function_exists( 'duvine_get_img' ) ) :
/**
 * Gets an image tag, and returns it
 *
 * @param array $imgField The image from the CMS database
 * @param array $args     Arguments
 */
function duvine_get_img( $imgField, $args = array() ){
    $defaults = array(
        'img_size'        => 'full',
        'container_class' => '',
        'container'       => true
    );

    $options = array_merge( $defaults, $args );

    $imgSrc = duvine_get_url_from_object( $imgField, $options['img_size'] );

    if( !$imgSrc ){
        return false;
    }
    
    $markup = '';

    $alt = ( is_array($imgField) && isset($imgField['alt']) ) ? $imgField['alt'] : '';

    if( $options['container'] === true ){
        $containerClass = $options['container_class'] ? ' class="' . $options['container_class'] . '"' : '';
        $markup .= "<div$containerClass>";
    }

    $markup .= "<img src=\"$imgSrc\" alt=\"$alt\">";

    if($imgField['caption'] && (get_post_type() != 'tour')) {
        $markup .= '<p class="image__caption">' . $imgField['caption'] . '</p>';
    }

    if( $options['container'] === true ){
        $markup .= '</div>';
    }

    return $markup;
}
endif; // duvine_get_img


if( !function_exists( 'duvine_render_img' ) ) :
/**
 * Renders an image, given an image array. If the image array is null, then returns nothing
 *
 * @param array $imgField The image from the CMS database
 * @param array $args     Arguments
 */
function duvine_render_img( $imgField, $args = array() ){

    $img = duvine_get_img( $imgField, $args );
    echo $img;
}
endif; // duvine_render_img






if( !function_exists( 'duvine_render_cta' ) ) :
/**
 * Renders a CTA
 */
function duvine_render_cta(){
    global $post;

    $cta = get_sub_field('d_cta');

    if( ! $cta ){
        return false;
    }

    $ctaText = $cta['title'] ?: 'Learn more';
    $ctaUrl = $cta['url'];

    echo "<a href=\"$ctaUrl\" class=\"cta\">$ctaText</a>";
    
}
endif; // duvine_render_cta






if( !function_exists( 'duvine_render_social_icons' ) ) :
/**
 * Renders the social icons
 *
 * @param (string) $additionalClasses, Any classes to add to the container
 */
function duvine_render_social_icons( $additionalClasses = '' ){
    global $post;

    $postlink = urlencode(get_the_permalink());
?>
    <div class="social-icons__wrapper <?php echo $additionalClasses; ?>">
        <ul class="menu">
            <li><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $postlink; ?>"><?php include_svg('social--facebook'); ?></a></li>
            <li><a target="_blank" href="https://twitter.com/intent/tweet?url=<?php echo $postlink; ?>&text="><?php include_svg('social--twitter'); ?></a></li>
            <!--<li><a target="_blank" href="https://plus.google.com/share?url=<?php echo $postlink; ?>"><?php //include_svg('social--googleplus'); ?></a></li>-->
            <li><a target="_blank" href="http://pinterest.com/pin/create/button/?url=<?php echo $postlink; ?>&media=&description="><?php include_svg('social--pinterest'); ?></a></li>
            <li><a href="mailto:?body=<?php echo $postlink; ?>&subject=Check out this tour from DuVine" class="social--email"><?php include_svg('social--email'); ?></a></li>
            <li><a href="javascript:void(0);" class="copy-link-to-clipboard" data-clipboard-text="<?php echo urldecode($postlink); ?>" data-copied="Link copied!" data-copyerror="Error copying link"><?php include_svg('social--copylink'); ?></a></li>
            <?php if ( is_singular( 'tour' ) ) : ?>
            <li><a href="/print-itinerary/?tour_id=<?php the_ID(); ?>" class="social--print" target="_blank"><?php include_svg('social--print'); ?></a></li>
            <?php else : ?>
            <li><a href="#" onclick="window.print(); return false;" class="social--print"><?php include_svg('social--print'); ?></a></li>
            <?php endif; ?>
        </ul>
    </div>
<?php
}
endif; // duvine_render_social_icons






if( !function_exists( 'duvine_render_tourmap' ) ) :
/**
 * Renders the tour map and the markers
 */
function duvine_render_tourmap(){
    $tourstops = get_field('d_tour_stops');

    if( ! $tourstops ){
        return false;
    }

    $data_center = '';
    if( $center = get_field('d_tour_weather_address') ) {
        $centerLat = $center['lat'];
        $centerLng = $center['lng'];
        $data_center = "[$centerLat,$centerLng]";
    }
?>
    <div class="tourmap">
        <div id="d-tourmap" class="tourmap__map" data-center='<?php echo $data_center; ?>'></div>
        
        <div class="mappoints">
            <?php foreach( $tourstops as $stop ) : ?>
                <?php $lat = $stop['tour_stop']['lat']; ?>
                <?php $lng = $stop['tour_stop']['lng']; ?>
                <?php $point = "[$lat,$lng]"; ?>
                <span class="mappoint" data-point='<?php echo $point; ?>'></span>
            <?php endforeach; ?>
        </div>
    </div>
    
<?php
}
endif; // duvine_render_tourmap





if( !function_exists( 'duvine_render_publication_logo' ) ) :
/**
 * Renders the publication logo
 *
 * @param bool $showGrayscaleLogo
 * @param object | int $publication, The publication we want the logo for
 */
function duvine_render_publication_logo($publication, bool $showGrayscaleLogo = false){
    if( ! $publication ){
        return false;
    }
    $mobileLogo = null;

    if ($showGrayscaleLogo) {
        $postThumbnail = get_field('d_pub_grayscale_logo', $publication->ID );
        $mobileLogo = duvine_get_url_from_object( get_post_thumbnail_id( $publication ), 'full' );
    } else {
        $postThumbnail = get_post_thumbnail_id($publication);
    }
    $logo = duvine_get_url_from_object($postThumbnail, 'full');

    if( ! $logo ){
        return false;
    }

    $pubTitle = get_the_title($publication);
    $pubSlug = $publication->post_name;

    if ($mobileLogo) {
        echo "<div class=\"pressblock__logo-mobile pressblock__logo--$pubSlug\">";
        echo    "<img src=\"$mobileLogo\" alt=\"$pubTitle\">";
        echo "</div>";
    }

    echo "<div class=\"pressblock__logo pressblock__logo--$pubSlug\">";
    echo    "<img src=\"$logo\" alt=\"$pubTitle\">";
    echo "</div>";
}
endif; // duvine_render_publication_logo




if( !function_exists( 'duvine_render_mobilenav' ) ) :
/**
 * Renders the mobile navigation
 */
function duvine_render_mobilenav(){
    $locationlist = duvine_get_hierarchical_location_list();

    $collections = get_posts( array(
        'post_type'      => 'tour_collection',
        'posts_per_page' => -1,
        'orderby'        => 'menu_order',
        'post_parent'    => 0
    ));

?>

    <div id="duvine-mobile-nav" class="mobilenav">
        <div class="mobilenav__container">
            <div class="mobilenav__mainpane">

                <div class="mobilenav__section">
                    <ul class="blockmenu">
                        <li class="menu-item menu-item-has-children"><a href="#">Search</a>
                            <ul class="sub-menu">
                                <li class="menu-item">
                                    <form method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                                        <label for="s" class="assistive-text"><?php _e( 'Search', 'twentyeleven' ); ?></label>
                                        <input type="search" name="s" placeholder="<?php esc_attr_e( 'Search', 'twentyeleven' ); ?>" value="<?php echo get_search_query(); ?>" class="mobilesearch" />
                                        <button type="submit" class="searchform__submit searchform__submit--mobile" id="searchsubmit">Search</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>


                <?php if( $locationlist ) : ?>
                    <div class="mobilenav__section">
                        <h4 class="mobilenav__header">Destinations</h4>
                        <ul class="blockmenu">
                            <?php foreach( $locationlist as $index => $continent ) : ?>
                                <li class="menu-item">
                                    <span class="subdrawer__trigger"><?php echo $continent['continent']; ?></span>
                                    
                                    <?php if( $continent['countries'] ) : ?>
                                        <div class="subdrawer sub-menu">
                                            <ul class="blockmenu">
                                                <?php foreach( $continent['countries'] as $country ) : ?>
                                                    <li class="menu-item"><a href="<?php echo get_the_permalink( $country->ID ); ?>"><?php echo $country->post_title; ?></a></li>
                                                <?php endforeach; ?>
                                                <li class="menu-item"><a href="<?php echo get_the_permalink($continent['id']); ?>">View all of <?php echo $continent['continent']; ?></a></li>
                                            </ul>
                                            
                                        </div>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if( $collections ) : ?>
                    <div class="mobilenav__section">
                        <h4 class="mobilenav__header">Tour Collections</h4>
                        <ul class="blockmenu">
                            <?php foreach( $collections as $coll ) : ?>
                                <li class="menu-item">
                                    <a href="<?php echo get_the_permalink( $coll->ID ); ?>"><?php echo $coll->post_title; ?></a>
                                </li>
								
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>


				<?php
                    wp_nav_menu( array(
                        'theme_location'  => 'primary',
                        'container_class' => 'mobilenav__section',
                        'menu_class'      => 'blockmenu',
                        'duvine_location' => 'mobilenav'
                    ) );
                ?>

            </div><!-- mobilenav__mainpane -->

            <a href="#" class="subdrawer__back js-mobilenav-back">Back</a>
            <a href="#" class="mobilenav__close js-close-mobilenav">Close</a>
        </div><!-- mobilenav__container -->


    </div>
<?php 
}
endif; // duvine_render_mobilenav






if( !function_exists( 'duvine_render_search_modal' ) ) :
/**
 * Renders the search modal
 */
function duvine_render_search_modal(){
?>
    <div class="searchmodal" id="duvine-searchmodal">
        <div class="l-container l-container--small"><?php get_search_form() ?></div>
        <a href="#" class="closesearch"></a>
    </div>
<?php
}
endif; // duvine_render_search_modal

if( !function_exists( 'duvine_render_newsletter_modal' ) ) :
/**
 * Renders the search modal
 */
function duvine_render_newsletter_modal(){
?>
    <div class="newslettermodal" id="duvine-newslettermodal">
        <div class="l-container l-container--small d-column-container newsletter-modal-container">
            <div class="d-col d-col--1-3 newsletter-img" style="background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/img/newsletter-signup.jpg);"></div>
            <div class="d-col d-col--2-3 newsletter-content">
              <div class="close"></div>
              <h4>Want some DuVine inspiration?</h4>
              <h3 class="blockheader">Sign up for our newsletter:</h3>
              <div class="footer-newsletter__form">
                <!--[if lte IE 8]>
                <script charset="utf-8" type="text/javascript" src="https://js.hsforms.net/forms/v2-legacy.js"></script>
                <![endif]-->
                <script charset="utf-8" type="text/javascript" src="https://js.hsforms.net/forms/v2.js"></script>
                <script>
                  hbspt.forms.create({
                    css: '',
                    portalId: '408217',
                    formId: '8f9177f0-0d14-4b9a-8ddf-7b2731274bb4',
                    onFormSubmit: function($form) {
                        Cookies.set('duvine-newsletter', '1', { expires: 365 });
                    }
                  });
                </script>
              </div>
            </div>
        </div>
        <a href="#" class="closesearch"></a>
    </div>
<?php
}
endif; // duvine_render_newsletter_modal




if( !function_exists( 'duvine_render_optional_itinerary' ) ) :
/**
 * Renders the pre and post tour optional days/itineraries
 *
 * @param boolean $pretour // whether this is the posttour itinerary or not
 */
function duvine_render_optional_itinerary( $preOrPost = null ){
    if( $preOrPost === null || ($preOrPost !== 'pre' && $preOrPost !== 'post' )  ){
        return false;
    }

    global $post;

    //$phoneNumber = str_replace(' ', '-', duvine_get_phone_number());
    $calltobook = '<p><strong>To reserve call ' . duvine_get_phone_number() . '</strong></p>';

    $titleField = $preOrPost === 'pre' ? 'd_itinerary_pre_tour_title' : 'd_itinerary_post_tour_title';
    $imageField = $preOrPost === 'pre' ? 'd_itinerary_pre_tour_image' : 'd_itinerary_post_tour_image';
    $descriptionField = $preOrPost === 'pre' ? 'd_itinerary_pre_tour_description' : 'd_itinerary_post_tour_description';
    $priceField = $preOrPost === 'pre' ? 'd_itinerary_pre_tour_price' : 'd_itinerary_post_tour_price';
    $priceField = $preOrPost === 'pre' ? 'd_itinerary_pre_tour_price' : 'd_itinerary_post_tour_price';
    $durationField = $preOrPost === 'pre' ? 'd_itinerary_pre_tour_duration' : 'd_itinerary_post_tour_duration';

    $graybox_title = $preOrPost === 'pre' ? 'Pre-tour' : 'Post-tour' ;


    $title = sk_get_field($titleField, array(
        'before'  => '<h4 class="accordion__title">',
        'after'   => '</h4>',
        'default' => "Pre tour option"
    ));
    $price = sk_get_field($priceField, array(
        'before' => '<p><strong>Price Per Person: </strong>',
        'after'  => '</p>',
        'filter' => 'format_price'
    ));
    $image = sk_get_field($imageField, array(
        'before' => '<div class="itinerary__dayimage">',
        'after'  => '</div>',
        'filter' => 'sk_img_markup'
    ));
    $description = sk_get_field($descriptionField, array(
        'before' => '<div class="itinerary__daydescription">',
        'after'  => $price . $calltobook . '</div>'
    ));


    // title
    $header = '<div class="itinerary__title">';
    $header .=  '<div class="itinerary__daymarker itinerary__daymarker--gray">' . $graybox_title . '</div>';
    $header .=  $title;
    $header .= '</div>';

    // body
    $content = '<div class="itinerary__daywrapper">' . $description . $image . '</div>';

    
    duvine_accordion_element( $header, $content );

}
endif; // duvine_render_optional_itinerary




if( !function_exists( 'duvine_render_itinerary_day' ) ) :
/**
 * Renders the itinerary day
 */
function duvine_render_itinerary_day( $daynumber = 0 ){

    $dayDescription = sk_get_subfield('description', array(
        'before' => '<div class="itinerary__daydescription">',
        'after'  => '</div>'
    ) );
    $dayImage = sk_get_subfield('image', array(
        'before' => '<div class="itinerary__dayimage">',
        'after'  => '</div>',
        'filter' => 'sk_img_markup'
    ) );


    // the header
    $dayBlock = "<div class=\"itinerary__daymarker\">Day <span>$daynumber</span></div>";
    $dayTitle = sk_get_subfield('title', array(
        'before'  => "<div class=\"itinerary__title\">$dayBlock<h4 class=\"accordion__title\">",
        'after'   => '</h4></div>',
        'default' => "Day $daynumber"
    ) );

    // build the day content
    $dayContent = '<div class="itinerary__daywrapper">' . $dayDescription . $dayImage . '</div>';

    duvine_accordion_element( $dayTitle, $dayContent );
    
}
endif; // duvine_render_itinerary_day




/* -------------------------------------------------
 *
 * --getters
 *     - functions that return things
 *
 * ------------------------------------------------- */




if( !function_exists( 'duvine_get_tour_dates' ) ) :
/**
 * Gets and returns the tour dates
 *
 * @return array
 */
function duvine_get_tour_dates( $tour_post = null ){
    global $post;

    $post_id = ( isset($tour_post->ID) ) ? $tour_post->ID : $post->ID;
    
    // get tour date from local XML
    $centaur_id = trim(get_field('d_tour_centaur_id',$post_id));
    $dates = get_tour_dates_by_centaur_id($centaur_id);

    //$dates = get_field('d_tour_schedules');

    /*echo '<br><br>------------->>>>>>>>>>>>>>><br><br>';
    var_dump($dates);
    echo '<br><br><<<<<<<<<<<<<<<<-------------<br><br>';*/

    $today = date('Ymd');

    $displayDates = array();

    if( $dates ){

        // if only one date, we have to change the looping array
        $iterate_dates = array_key_exists(0, $dates['dates']['date']) ? $dates['dates']['date'] : $dates['dates'];
        foreach( $iterate_dates as $date ){

            /*error_log('------------->>>>>>>>>>>>>>>');
            error_log( print_r($date,TRUE));
            error_log('------------->>>>>>>>>>>>>>>');
            error_log('');*/


            $start = explode('/',$date['TourDate']);
            $start = $start[2].$start[0].$start[1];

            if( $start > $today ){
                $start = strtotime( $start );
                $end = explode('/',$date['ReturnDate']);
                $end = $end[2].$end[0].$end[1];
                $end = strtotime( $end );

                $startYear = date('Y', $start);
                $endYear = date('Y', $start);
                
                $startMonth = date('M', $start);
                $endMonth = date('M', $end);
               
                $startDay = date('j', $start);
                $endDay = date('j', $end);
                
                // construct the string
                $dateString = "$startMonth $startDay &ndash; ";

                if( $startMonth !== $endMonth ){
                    $dateString .= "$endMonth ";
                }

                $dateString .= $endDay;

                if( $startYear !== $endYear ){
                    $dateString .= " $endYear";
                }

                //$the_price = $date['Currency'].$date['PriceDetailsTwinList'];
                $the_price = $date['PriceDetailsDoubleList'];  // should this be twin list?

                $special_events = $date['GuaranteeDepTextShowOnline'];

                //if( count($special_events) > 0 ) :

                    //error_log('>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>');
                    //error_log(print_r($special_events,true));

                    //error_log('>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>');

                //endif;

                // Now set up the Flags
                $date_flags = array();
                $date_flags['flags']['OnlineTourStatus'] = $date['OnlineTourStatus'];
                $date_flags['flags']['GuaranteedDeparture'] = $date['GuaranteedDeparture'];
                $date_flags['flags']['DepartureDateOnlineFlag'] = $date['DepartureDateOnlineFlag'];
                $date_flags['flags']['AvailableInventory'] = $date['TotalRoomsInventory']['AvailableInventory'];
                $date_flags['flags']['ThresholdInventory'] = $date['TotalRoomsInventory']['ThresholdInventory'];
                $date_flags['flags']['NumberOfPax'] = $date['NumberOfPax'];
                $date_flags['flags']['HoldInventory'] = $date['HoldInventory'];
                

                $dateArray = array(
                    'start_date' => date( 'm/d/Y', $start ),
                    'label'      => $dateString,
                    'price'      => $the_price,
                    'events'     => $special_events,
                    'flags'      => $date_flags['flags']
                );

                if( isset( $displayDates[$startYear] ) ){
                    array_push($displayDates[$startYear], $dateArray);
                } else {
                    $displayDates[ $startYear ] = array( $dateArray );
                }
            }
        }
    } else {
        return 'Travel dates not yet available. Contact us for details.';
    }
    if (empty($displayDates)) {
        return 'Travel dates not yet available. Contact us for details.';
    }

    ksort( $displayDates );

    return $displayDates;
}
endif; // duvine_get_tour_dates

if( !function_exists( 'duvine_get_tour_date_status' ) ) :
/**
 * Gets the tour date status depending on the flags
 * @ flags - built from Centaur data
 */
function duvine_get_tour_date_status( $flags, $tour_date = null, $year = null ){

    if( !$flags || !is_array($flags) ) return false;

    $tour_status = 'call_to_book';

    //echo 'OnlineTourStatus'.$flags['OnlineTourStatus'].'<br>';

    if( ( strtoupper($flags['DepartureDateOnlineFlag']) == 'YES' && strtoupper($flags['OnlineTourStatus'] == 'BOOK NOW') && $flags['NumberOfPax'] == 14 ) || ( strtoupper($flags['OnlineTourStatus']) == 'SOLD OUT' ) ) :
        $tour_status = 'sold_out';
        //echo 'just set to SOLD OUT!!!!!!!';

    elseif( strtoupper($flags['DepartureDateOnlineFlag']) == 'YES' && strtoupper($flags['OnlineTourStatus']) == 'BOOK NOW' && strtoupper($flags['HoldInventory']) == 'YES' ) :
        $tour_status = 'sold_out_w_private';

    elseif( strtoupper($flags['DepartureDateOnlineFlag']) == 'YES' && strtoupper($flags['OnlineTourStatus']) == 'BOOK NOW' && $flags['AvailableInventory'] > 1) :
        $tour_status = 'book_now';

    elseif(strtoupper($flags['DepartureDateOnlineFlag']) == 'YES' && strtoupper($flags['OnlineTourStatus'] == 'BOOK NOW') && $flags['AvailableInventory'] == 1 && $flags['NumberOfPax'] < 13) :
        $tour_status = 'book_now_limited_space';

    elseif(strtoupper($flags['DepartureDateOnlineFlag']) == 'YES' && strtoupper($flags['OnlineTourStatus'] == 'BOOK NOW') && $flags['AvailableInventory'] == 1 && $flags['NumberOfPax'] = 13) :
      $tour_status = 'call_to_book_limited_space';

    elseif( (strtoupper($flags['DepartureDateOnlineFlag']) == 'YES' && strtoupper($flags['OnlineTourStatus']) == 'BOOK NOW' && $flags['AvailableInventory'] == 0 && $flags['NumberOfPax'] < 14 ) || ( strtoupper($flags['OnlineTourStatus']) == 'CALL TO BOOK' ) ) :
        $tour_status = 'call_to_book';
    endif;

    if( $tour_status == 'book_now' || $tour_status == 'book_now_limited_space' || $tour_status == 'call_to_book') :

        // see if tour departure is within 30 days
        // expected format for tour_date: May 24 &ndash; 29
        if( $tour_date !== null && $year !== null) :
            $month_day = explode('&ndash;', $tour_date);
            if( isset($month_day[0]) ) :

                $date = trim($month_day[0]).' '.$year;
                $date = date('Y-m-d', strtotime($date));

                $now = time();
                $datediff = strtotime($date) - $now;

                $datediff = round($datediff / (60 * 60 * 24));

                if( is_numeric($datediff) && $datediff > 0 && $datediff <= 30) :
                    $tour_status = 'call_to_book_30_days';
                endif;
            endif;
        endif;
    endif;

    return $tour_status;

}
endif; // duvine_get_tour_date_status

if( !function_exists( 'duvine_get_hierarchical_location_list' ) ) :
/**
 * Gets the locations that all tours go, grouped hierarchically
 */
function duvine_get_hierarchical_location_list(){

    //delete_transient( 'duvine_hierarchical_location_list' );

    if( false === ( $locationlist = get_transient( 'duvine_hierarchical_location_list' ) ) ){
        $locationlist = array();

        $continents = get_posts( array(
            'post_type'      => 'location',
            'posts_per_page' => -1,
            'orderby'        => 'menu_order',
            'order' => 'ASC',
            'post_parent'    => 0
        ));

        if( ! $continents ){
            return $locationlist;
        }

        foreach( $continents as $continent ){
            $continentData = array(
                'continent' => $continent->post_title,
                'slug'      => $continent->post_name,
                'id'        => $continent->ID
            );

            $countries = get_posts( array(
                'post_type'      => 'location',
                'posts_per_page' => -1,
                'orderby'        => 'menu_order',
                'order' => 'ASC',
                'post_parent'    => $continent->ID
            ));

            if( $countries ){
                $continentData['countries'] = $countries;
            }

            array_push($locationlist, $continentData);
        }

        set_transient( 'duvine_hierarchical_location_list', $locationlist, 60 * 60 );
    }

    return $locationlist;
}
endif; // duvine_get_hierarchical_location_list







if( !function_exists('duvine_get_minimum_price') ) :
/**
 * Gets the miniumum price of the given tour
 *
 * @return string
 */
function duvine_get_minimum_price($centaur_id=null){

    if( $centaur_id === null ) :
        global $post;
        $centaur_id = trim(get_field('d_tour_centaur_id',$post->ID));
    endif;

    // get tour data from local XML
    $dates = get_tour_dates_by_centaur_id($centaur_id);

    if( !$dates ) :
        if( $startingPrice = get_field('d_private_starting_price') ){
            return apply_filters('format_price', $startingPrice);
        } else {
            return '';
        }
    endif;

    if( isset($dates['dates']['date'][0]) ) :
        $min = $dates['dates']['date'][0]['PriceDetailsDoubleList'];
        $date_loop = $dates['dates']['date'];
    else :
        // only one date for this tour
        $min = $dates['dates']['date']['PriceDetailsDoubleList'];
        $date_loop = $dates['dates'];
    endif;

    if(!$min){
        return '';
    }

    // we want to get lowest price from dates that have not passed...
    // but we can settle for the $min we already have otherwise
    $date_min = 100000;
    $today = strtotime(date('m/d/Y'));

    foreach ($date_loop as $date) {

        $tour_start = strtotime($date['TourDate']);
        
        if( $date['PriceDetailsDoubleList'] && intval($date['PriceDetailsDoubleList']) < intval($date_min) ) {
            if( $tour_start >= $today ) {
                $date_min = $date['PriceDetailsDoubleList'];
            }
        }
    }

    $min = ( $date_min < 100000 ) ? $date_min : $min;

    return apply_filters('format_price', floatval($min));
}
endif; // duvine_get_minimum_price

if( !function_exists( 'duvine_get_url_from_object' ) ) :
/**
 * Gets the URL for an image from an image object.
 *
 * @param object | string $img      The image data from the database, or ID
 * @param string          $imgSize  The renderable size of the image
 *
 * @return string | false if no image
 */
function duvine_get_url_from_object( $img, $imgSize = 'full' ){
    if( ! $img ){
        return false;
    }

    $id = is_array($img) ? $img['ID'] : $img;

    $imgSrc = wp_get_attachment_image_src( $id, $imgSize );
    if( $imgSrc ){
        return $imgSrc[0];
    }

    return false;

}
endif; // duvine_get_url_from_object





if( !function_exists( 'duvine_get_phone_number' ) ) :
/**
 * Gets the phone number
 */
function duvine_get_phone_number(){

    $contact = get_field('sk_contact_phone', 'option');

    if( $contact ){
        return $contact;
    }

    return false;
}
endif; // duvine_get_phone_number






if( !function_exists( 'duvine_get_office_hours' ) ) :
/**
 * Gets the phone number
 */
function duvine_get_office_hours(){
    $hours = get_field('sk_contact_office_hours', 'option');

    if( $hours ){
        return '<div class="footercontact">' . $hours . '</div>';
    }

    return false;
}
endif; // duvine_get_office_hours






if( !function_exists( 'duvine_organize_tours_by_location' ) ) :
/**
 * Takes a list of posts and organizes an array of posts organized by tour location
 *
 * @param array $posts List of WP posts
 * @param int   $depth Location depth
 *
 * @return array
 */
function duvine_organize_tours_by_location( $posts, $depth ){
    $organized = array();

    if( !$posts ){
        return false;
    }

    foreach( $posts as $tour ){
        $locations = get_field('d_tour_location', $tour->ID);

        //$loc = duvine_get_first_level_location( $locations );
        $loc = duvine_get_location_at_depth( $locations, $depth );

        if( isset( $organized[ $loc ] ) && $organized[ $loc ] ){
            array_push( $organized[ $loc ], $tour );
        } else {
            $organized[ $loc ] = array( $tour );
        }
    }

    return $organized;
}
endif; // duvine_organize_tours_by_location

if( !function_exists( 'duvine_organize_tours_by_location_and_sublocation' ) ) :
/**
 * Takes a list of posts and organizes an array of posts organized by tour location
 *
 * @param array $posts List of WP posts
 * @param int   $depth Location depth
 *
 * @return array
 */
function duvine_organize_tours_by_location_and_sublocation( $posts, $depth ){
    $organized = array();

    if( !$posts ){
        return false;
    }

    foreach( $posts as $tour ){
        $locations = get_field('d_tour_location', $tour->ID);

        //$loc = duvine_get_first_level_location( $locations );
        $loc = duvine_get_location_at_depth( $locations, $depth );
        
        if( isset( $organized[ $loc ] ) && $organized[ $loc ] ){
            if( !empty($locations[2]) ) {
                $p = get_post($locations[2]);
                $slug = $p->post_name;
                if( isset( $organized[ $loc ][ $slug ] ) && $organized[ $loc ][ $slug ] ){
                    array_push( $organized[ $loc ][ $slug ], $tour );
                } else {
                    $organized[ $loc ][ $slug ] = array( $tour );
                }
            } else {
                array_push( $organized[ $loc ], $tour );
            }
        } else {
            if( !empty($locations[2]) ) {
                $p = get_post($locations[2]);
                $slug = $p->post_name;
                $organized[ $loc ][ $slug ] = array( $tour );
            } else {
                $organized[ $loc ] = array( $tour );
            }
        }
    }

    return $organized;
}
endif; // duvine_organize_tours_by_location_and_sublocation








if( !function_exists( 'duvine_get_location_at_depth' ) ) :
/**
 * Given a list of locations, return the location whose parent is a continent
 *
 * @param array $locations List of Locations within WordPress
 * @param int   $depth     Location depth
 *
 * @return array
 */
function duvine_get_location_at_depth( $locations, $depth = 1 ){
    if( !$locations ){
        return 0;
    }

    foreach( $locations as $loc ){
        $locObj = get_post($loc);
        $ancestors = get_ancestors($locObj->ID, 'location', 'post_type');

        if( count( $ancestors ) + 1 === $depth ){
            return $loc;
        }
    }
    return $locations[0];
}
endif; // duvine_get_location_at_depth


if( !function_exists( 'duvine_has_US_tour' ) ) :
/**
 * Given a list of tours, check if they are in US
 *
 * @param array $tours List of Tours
 *
 * @return array
 */
function duvine_has_US_tour( $tours ){
    if ( $post = get_page_by_path( 'united-states', OBJECT, 'location' ) )
        $id = $post->ID;
    else
        $id = 0;

    if( $id === 0 ) return false;

    foreach ( $tours as $index => $tour ): 
        $tour_regions = get_field("d_tour_location", $tour->ID);
        if( !empty($tour_regions[0]) && $tour_regions[0] === $id ) return true;
    endforeach;

    return false;
}
endif; // duvine_has_US_tour



if( !function_exists( 'duvine_get_press_url' ) ) :
/**
 * Gets the appropriate press url (downloadable asset vs. website)
 *
 * @param int | object $press, The press content from WordPress
 *
 * @return string, the url to the press content
 */
function duvine_get_press_url( $press = null ){
    if( $press === null ){
        global $post;
        $press = $post;
    }

    if( is_object( $press ) && isset( $press->ID ) ){
        $id = $press->ID;
    } else {
        $id = $press;
    }

    $pressUrl = get_field('d_press_downloadable_asset', $id);
    if( $pressUrl ){
        return $pressUrl['url'];
    }

    $pressUrl = get_field('d_press_url', $id);
    if( $pressUrl ){
        return $pressUrl;
    }

    return '#';

}
endif; // duvine_get_press_url





if( !function_exists( 'duvine_get_active_filters' ) ) :
/**
 * Gets the relevatnt filters that are being filtered by for the given post
 *
 * @param object $post Tour post
 * @return string
 */
function duvine_get_active_filters( $post ){

    $taxfilter_culinary  = get_query_var('taxfilter_culinary');
    $taxfilter_culture   = get_query_var('taxfilter_culture');
    $taxfilter_landscape = get_query_var('taxfilter_landscape');
    $taxfilter_activity  = get_query_var('taxfilter_activity');


    $termCount = 0;
    $terms = 'Includes:';

    if( $taxfilter_culinary ){
        if( $post_culinary = wp_get_post_terms( $post->ID, 'culinary' ) ){
            foreach( $post_culinary as $term ){
                if( in_array($term->term_id, $taxfilter_culinary) ){
                    if( $termCount > 0 ){
                        $terms .= ',';
                    }
                    $termCount++;
                    $terms .= ' ' . $term->name;
                }
            }
        }
    }

    if( $taxfilter_culture ){
        if( $post_culture = wp_get_post_terms( $post->ID, 'culture' ) ){
            foreach( $post_culture as $term ){
                if( in_array($term->term_id, $taxfilter_culture) ){
                    if( $termCount > 0 ){
                        $terms .= ',';
                    }
                    $termCount++;
                    $terms .= ' ' . $term->name;
                }
            }
        }
    }

    if( $taxfilter_landscape ){
        if( $post_landscape = wp_get_post_terms( $post->ID, 'landscape' ) ){
            foreach( $post_landscape as $term ){
                if( in_array($term->term_id, $taxfilter_landscape) ){
                    if( $termCount > 0 ){
                        $terms .= ',';
                    }
                    $termCount++;
                    $terms .= ' ' . $term->name;
                }
            }
        }
    }

    if( $taxfilter_activity ){
        if( $post_activity = wp_get_post_terms( $post->ID, 'activity' ) ){
            foreach( $post_activity as $term ){
                if( in_array($term->term_id, $taxfilter_activity) ){
                    if( $termCount > 0 ){
                        $terms .= ',';
                    }
                    $termCount++;
                    $terms .= ' ' . $term->name;
                }
            }
        }
    }

    if( $termCount > 0 ){
        return $terms;
    }

    return '';
}
endif; // duvine_get_active_filters





if( !function_exists( 'duvine_get_tooltip' ) ) :
/**
 * Gets and returns a tooltip
 *
 * @param (string) $field
 *
 * @return string
 */
function duvine_get_tooltip( $field, $color ){
    
    $content = get_field($field, 'option');
    $width = '23px';

    if( ! $content ){
        return '';
    }

    if( $color == 'dark' ) :
        $tooltip_icon = '/wp-content/themes/sherman/svg/question-mark.svg';

    elseif( $color == 'light' ) :
        $tooltip_icon = '/wp-content/themes/sherman/svg/question-mark-light.svg';
    elseif( $color == 'small' ) :
        $tooltip_icon = '/wp-content/themes/sherman/svg/info.svg';
        $width = '18px';
    endif;

    $tooltip_markup = "<img src=\"$tooltip_icon\" class=\"tooltip $color\" data-tooltip-content=\"#$field\" width=\"$width\" />";
    $tooltip_markup .= "<div class=\"tooltip_templates\" style=\"display:none\"><div id=\"$field\">$content</div></div>";

    return $tooltip_markup;
}
endif; // duvine_get_tooltip



if( !function_exists( 'duvine_get_dates_tooltip' ) ) :
/**
 * Gets and returns a date tooltip
 *
 * @param (string) $field
 *
 * @return string
 */
function duvine_get_dates_tooltip( $tooltip_id, $tooltip_year, $tooltip_content, $is_tour_finder = false){
    
    $content = $tooltip_content;

    if( ! $content ){
        return '';
    }

    $class = $is_tour_finder ? " tour-finder" : '';

    $tooltip_markup = '<a href="#" class="tooltip-tour-calendar'. $class .'" data-tooltip-content="#'.$tooltip_id.'">'.$tooltip_year.'</a>';
    $tooltip_markup .= '<div class="tooltip_templates" style="display:none"><div id="'.$tooltip_id.'">'.$content.'</div></div>';

    //$tooltip_markup = "<img src=\"$tooltip_icon\" class=\"tooltip $color\" data-tooltip-content=\"#$field\" width=\"$width\" />";
    //$tooltip_markup .= "<div class=\"tooltip_templates\" style=\"display:none\"><div id=\"$field\">$content</div></div>";

    return $tooltip_markup;
}
endif; // duvine_get_tooltip




/* -------------------------------------------------
 *
 * --util
 *
 * ------------------------------------------------- */


if(!function_exists('duvine_is_family_tour')) :
/**
 * checks whether or not the current tour is a family tour or not
 * 
 * @param {array} $collections // (optional) A list of tour collections
 * @return boolean
 */
function duvine_is_family_tour($collections = null){
    global $post;

    if($collections === null){
        $collections = get_field('d_tour_collection');
    }

    if (is_array($collections)) {
        return in_array(180, $collections);
    } else {
        return false;
    }
}
endif; // duvine_is_family_tour




if( !function_exists( 'duvine_build_tour_queryargs' ) ) :
/**
 * Builds the WP_Query args for a tourloop
 *
 * @param array $args    The WP_Query arguments
 * @param array $filters List of filters to build a query
 */
function duvine_build_tour_queryargs( $args = array() ){
    
    $defaults = array(
        'posts_per_page' => 12,
        'post_type'      => 'tour',
        'meta_query'     => array(),
        'tax_query'      => array(),
        'post_status'    => 'publish',

        // 'meta_key'       => 'd_tour_location',
        // 'orderby'        => array( 'meta_value' => 'ASC', 'title' => 'ASC'),
        'orderby'        => array( 'title' => 'ASC'),
    );
    $queryArgs = array_merge( $defaults, $args );


    $q_destination         = get_query_var( 'destination' );
    $q_level               = get_query_var( 'level' );
    $q_collection          = get_query_var( 'collection' );

    $q_morefilters         = get_query_var( 'morefilters' );
    /*$q_taxfilter_landscape = get_query_var( 'taxfilter_landscape' );
    $q_taxfilter_activity  = get_query_var( 'taxfilter_activity' );
    $q_taxfilter_culinary  = get_query_var( 'taxfilter_culinary' );
    $q_taxfilter_culture   = get_query_var( 'taxfilter_culture' );*/

    $q_date                = get_query_var( 'date' );
    $q_tourtype            = get_query_var( 'tourtype' );
    $q_duration            = get_query_var( 'duration' );

    if( $q_destination && ! is_array( $q_destination )){
        $q_destination = array( $q_destination );
    }


    $meta_query = array_merge( array( 'relation' => 'AND' ), $queryArgs['meta_query'] );
    $tax_query = array_merge( array( 'relation' => 'OR' ), $queryArgs['tax_query'] );

    if( $q_destination ){
        $destination_meta_query = array( 'relation' => 'OR' );
        foreach( $q_destination as $dest ){
            array_push( $destination_meta_query, array(
                'key'     => 'd_tour_location',
                'value'   => '"' . $dest . '"',
                'compare' => 'LIKE'
            ));
        }
        array_push( $meta_query, $destination_meta_query );
    }

    if($q_duration) {

        $duration_meta_query = array( 'relation' => 'OR' );
        foreach( $q_duration as $duration ){
            
            if($duration == '4') {
                array_push( $duration_meta_query, array(
                    'key'     => 'd_tour_duration',
                    'value'   => '^[4][ ]',
                    'compare' => 'REGEXP'
                ));
            } else {
                array_push( $duration_meta_query, array(
                    'key'     => 'd_tour_duration',
                    'value'   => array( 5, 50 ),
                    'type'    => 'numeric',
                    'compare' => 'BETWEEN',
                ));
            }
        }
        array_push( $meta_query, $duration_meta_query );
    }

    if( $q_level ){
        $levels_meta_query = array('relation' => 'OR');
        $has_level = false;
        foreach( $q_level as $level ){
            if( $level > 0 ){
                $has_level = true;
                array_push( $levels_meta_query, array(
                    'key'     => 'd_cycling_level',
                    'value'   => $level,
                    'compare' => 'LIKE'
                ));

            } else {
                array_push( $levels_meta_query, array(
                    'key'     => 'd_non_rider',
                    'value'   => '1',
                    'compare' => '=='
                ));
            }
        }
        if( $has_level ) {
            // get all centaur posts that DON'T have any one of these levels
            $post_not_in = duvine_get_tours_not_in_centaur_level($q_level);
            if( count($post_not_in) > 0 ) {
                $not_in_query = array('relation' => 'AND',
                    'key'       => 'd_tour_centaur_id',
                    'value'     => $post_not_in,
                    'compare'   => 'NOT IN'
                );
                array_push($meta_query, $not_in_query);
            }
        }
        array_push( $meta_query, $levels_meta_query );
    }


    if( $q_collection ){
        $collections_meta_query = array('relation' => 'OR');
        foreach( $q_collection as $collection ){
            array_push( $collections_meta_query, array(
                'key'     => 'd_tour_collection',
                'value'   => '"' . $collection . '"',
                'compare' => 'LIKE'
            ));
        }
        array_push( $meta_query, $collections_meta_query );
    }


    if($q_date){
        $dates_meta_query = ['relation' => 'OR',
            [
                'key'     => 'd_private_only',
                'value'   => 1,
                'compare' => '='
            ]
        ];
        $post_in = null;
        if (is_string($q_date) && strpos($q_date, '-')) {
            $dates = explode('-', $q_date);
            $startDate = $dates[0];
            $endDate = $dates[1];
            if ($startDate && $endDate) {
                $post_in = duvine_get_tours_with_daterange(trim($startDate), trim($endDate));
            }
        } elseif (is_array($q_date)) {
            $dates = [];
            foreach($q_date as $date){
                $dates[] = $date;
            }
            if(count($dates) > 0) {
                $post_in = duvine_get_tours_with_date($dates);
            }
        }
        if ($post_in && count($post_in) > 0) {
            array_push($dates_meta_query, [
                'key'       => 'd_tour_centaur_id',
                'value'     => $post_in,
                'compare'   => 'IN',
            ]);
        }

        array_push($meta_query, $dates_meta_query);
    }

    if( $q_morefilters ) {
        // split the chosen filters up into taxonomies
        $q_taxfilter_activity = array();
        $q_taxfilter_culinary = array();
        $q_taxfilter_culture = array();
        $q_taxfilter_landscape = array();

        foreach( $q_morefilters as $filter ) {
            if( strpos($filter,'activities__') !== false ) {
                $q_taxfilter_activity[] = str_replace('activities__','',$filter);
            }
            if( strpos($filter,'culinary__') !== false ) {
                $q_taxfilter_culinary[] = str_replace('culinary__','',$filter);
            }
            if( strpos($filter,'cultural__') !== false ) {
                $q_taxfilter_culture[] = str_replace('cultural__','',$filter);
            }
            if( strpos($filter,'landscape__') !== false ) {
                $q_taxfilter_landscape[] = str_replace('landscape__','',$filter);
            }
        }

        if( count($q_taxfilter_activity) > 0 ){
            array_push( $tax_query, array(
                'taxonomy' => 'activity',
                'field'    => 'term_id',
                'terms'    => $q_taxfilter_activity
            ));
        }
        if( count($q_taxfilter_culinary) > 0 ){
            array_push( $tax_query, array(
                'taxonomy' => 'culinary',
                'field'    => 'term_id',
                'terms'    => $q_taxfilter_culinary
            ));
        }
        if( count($q_taxfilter_culture) > 0 ){
            array_push( $tax_query, array(
                'taxonomy' => 'culture',
                'field'    => 'term_id',
                'terms'    => $q_taxfilter_culture
            ));
        }
        if( count($q_taxfilter_landscape) > 0 ){
            array_push( $tax_query, array(
                'taxonomy' => 'landscape',
                'field'    => 'term_id',
                'terms'    => $q_taxfilter_landscape
            ));
        }
    }

    if( $q_tourtype ){
        if (!is_array($q_tourtype)) {
            $q_tourtypes[] = $q_tourtype;
        } else {
            $q_tourtypes = $q_tourtype;
        }
        foreach ($q_tourtypes as $q_tourtype) {
            if ($q_tourtype === 'scheduled') {
                array_push($meta_query, array(
                    'key' => 'd_private_only',
                    'value' => '1',
                    'compare' => '!='
                ));
            } elseif ($q_tourtype === 'private') {
                array_push($meta_query, array(
                    'key' => 'd_private_only',
                    'value' => '1',
                    'compare' => '=='
                ));
            } elseif ($q_tourtype === 'new') {
                array_push($meta_query, array(
                    'key' => 'd_new_tour',
                    'value' => '1',
                    'compare' => '=='
                ));
            }
        }
    }
    
    // debug
    // array_push( $meta_query, array(
    //     'key'     => 'd_new_tour',
    //     'value'   => '1',
    //     'compare' => '=='
    // ));



    // merge all the meta and taxonomy queries
    $queryArgs['meta_query'] = $meta_query;
    $queryArgs['tax_query'] = $tax_query;

    return $queryArgs;

}
endif; // duvine_build_tour_queryargs





if( !function_exists( 'duvine_get_post_by_slug' ) ) :
/**
 * Gets post by slug
 *
 * @param string $post_name Slug to search
 * @param string $post_type Type of post
 */
function duvine_get_post_by_slug( $post_name, $post_type = 'post' ){
    
    $post_ids = get_posts(array (
        'name'        => $post_name,
        'post_type'   => $post_type,
        'numberposts' => -1
    ));

    if( $post_ids ){
        return $post_ids[0]->ID;
    }

    return false;
}
endif; // duvine_get_post_by_slug
