<?php
/**
* Template Name: News Page
**/

get_header(); ?>

    <?php while ( have_posts() ) : the_post(); ?>

        <div class="Page">
            <div class="Page Double Listing-Section Listing-News">

                <?php
                //***************************************************************************
                // Output News
                //***************************************************************************
                $posts_per_page = 5;
                $paged = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;
                $news_query = new WP_Query(array(
                    'post_type'     => 'duvine_news',
                    'orderby'       => 'post_date',
                    'order'         => 'DESC',
                    'posts_per_page'=> $posts_per_page,
                    'paged'         => $paged,
                ));

                if ( $news_query->have_posts() ) { ?>


                    <div class="Listing-Total Section">
                        <?php duvine_pagination_links( $news_query, $label = 'News' ); ?>
                    </div><!--/.Listing-Total.Section-->

                    <div class="Listing Article">

                        <?php
                        $counter = 1;
                        while ( $news_query->have_posts() ) { $news_query->the_post(); ?>

                            <?php get_template_part( 'partials/news', 'index' ); ?>

                            <?php if( $counter % $posts_per_page != 0  ) { ?>
                                <hr>
                            <?php } ?>

                        <?php
                            $counter++;
                        } // while $news_query ?>

                    </div><!--/.Listing.Article-->

                    <div class="Listing-Total Listing-Last Section">
                        <?php duvine_pagination_links( $news_query, $label = 'News' ); ?>
                    </div><!--/.Listing-Total.Section-->

                <?php } // if $news_query->have_posts
                wp_reset_postdata();
                ?>

            </div><!--/.Page.Double.Listing-Section-->
        </div><!--/.Page-->

    <?php endwhile; // end of the loop. ?>

<?php get_footer(); ?>
