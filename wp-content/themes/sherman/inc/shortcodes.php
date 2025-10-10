<?php
/* ========================================================
 *
 * Custom shortcodes
 *
 * ======================================================== */


if( !function_exists( 'duvine_shortcode_tour_meals' ) ) :
/**
 * Hooks into the included dinners field
 */
function duvine_shortcode_tour_meals(){
    global $post;
    $dinners = get_field('d_dinners_included');
    return $dinners;
}
add_shortcode('duvine_tour_meals', 'duvine_shortcode_tour_meals');
endif; // duvine_shortcode_tour_meals






if( !function_exists( 'duvine_shortcode_year' ) ) :
/**
 * The year
 */
function duvine_shortcode_year(){
    return date('Y');
}
add_shortcode('duvine_year', 'duvine_shortcode_year');
endif; // duvine_shortcode_year








if( !function_exists( 'duvine_shortcode_featured_stats' ) ) :
/**
 * A featured stats block to be inserted in a WYSIWYG editor. The shortcode
 *  takes some attributes:
 *
 * @attr avg_distance
 * @attr avg_elevation
 * @attr level
 *
 * @use [duvine_featured_stats avg_distance="" avg_elevation="" level=""]
 */
function duvine_shortcode_featured_stats( $atts = array() ){
    $a = shortcode_atts( array(
        'avg_distance'  => '',
        'avg_elevation' => '',
        'level'         => ''
    ), $atts );


    $markup = '<div class="featuredstats">';
    if( $a['avg_distance'] ){
        $markup .= '<div class="featuredstats__stat"><p class="stat__label">Average daily distance</p><p class="stat__value">' . $a['avg_distance'] . '</p></div>';
    }
    if( $a['avg_elevation'] ){
        $markup .= '<div class="featuredstats__stat"><p class="stat__label">Average daily elevation</p><p class="stat__value">' . $a['avg_elevation'] . '</p></div>';
    }
    if( $a['level'] ){
        $markup .= '<div class="featuredstats__stat"><p class="stat__label">Level</p><p class="stat__value">' . $a['level'] . '</p></div>';
    }
    $markup .= '</div>';
    return $markup;
}
add_shortcode('duvine_featured_stats', 'duvine_shortcode_featured_stats');
endif; // duvine_shortcode_featured_stats