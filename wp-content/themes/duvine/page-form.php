<?php
/**
 * Template Name: Form Page
 */

if( !duvine_is_ajax_request() ) {
    get_header();
}
?>


    <?php while ( have_posts() ) : the_post(); ?>

        <div class="Page Double Top Section">
            <?php get_template_part( 'partials/content', 'form' ); ?>
        </div><!--/.Page.Top.Section-->

    <?php endwhile; // end of the loop. ?>

<?php
if( !duvine_is_ajax_request() ) {
    get_footer();
}
?>
