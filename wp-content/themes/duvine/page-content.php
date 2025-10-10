<?php
/**
 * Template Name: Content Page
 */
if( duvine_is_ajax_request() ) {
    do_action('wp_head');
}
else {
    get_header();
}
?>

    <?php while ( have_posts() ) : the_post(); ?>

        <?php duvine_featured_image(); ?>

        <div class="Page">
            <div class="Page Content">
                <?php get_template_part( 'partials/content', 'pagecontent' ); ?>
            </div><!--/.Page.Content-->
        </div><!--/.Page-->

    <?php endwhile; // end of the loop. ?>

<?php
if( duvine_is_ajax_request() ) {
    do_action('wp_footer');
}
else {
    get_footer();
}
?>
