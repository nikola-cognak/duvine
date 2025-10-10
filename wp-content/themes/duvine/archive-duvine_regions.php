<?php
/**
 * Template Name: Tours By Region Page
 */
get_header(); ?>

    <?php
    if( isset( $_GET['tourType'] ) ) {
        get_template_part( 'partials/regions', 'type' );
    }
    else {
        get_template_part( 'partials/regions', 'all' );
    }
    ?>

<?php get_footer(); ?>
