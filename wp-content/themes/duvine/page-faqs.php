<?php
/**
* Template Name: FAQS Page
**/

get_header(); ?>

    <?php while ( have_posts() ) : the_post(); ?>

        <div class="Page Double">
			<div class="Page">
            <?php get_template_part( 'partials/content', 'page' ); ?>

            <hr class="Large" />

            <div class="Listing-Section Clear">
                <div class="Page Double Listing-Section">

                    <?php
                    //***************************************************************************
                    // Output FAQs
                    //***************************************************************************
                    $faqs_query = new WP_Query(array(
                        'post_type'     => 'duvine_faqs',
                        'orderby'       => 'menu_order',
                        'order'         => 'ASC',
                        'posts_per_page'=> -1,
                    ));


                    if ( $faqs_query->have_posts() ) { ?>

                        <div class="Listing-Total Section">
                        </div>

                        <div class="Listing">

                            <?php while ( $faqs_query->have_posts() ) { $faqs_query->the_post(); ?>

                                <?php get_template_part( 'partials/faqs', 'index' ); ?>
                                <hr>
                            <?php } // while $faqs_query ?>

                        </div><!--/.Listing-->

                        <div class="Listing-Total Section">
                        </div>

                    <?php } // if $faqs_query->have_posts

                    wp_reset_postdata();
                    ?>
                </div><!--/.Page.Double-->
            </div><!--/.Listing-Section-->

			</div><!-- /.Page -->
        </div><!--/.Page.Double-->

    <?php endwhile; // end of the loop. ?>

<?php get_footer(); ?>
