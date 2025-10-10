<?php
/**
* Template Name: Guides Page
**/

get_header(); ?>

    <div class="Page">
        <div class="Page Double Listing-Section">

            <?php
            //***************************************************************************
            // Output Videos
            //***************************************************************************
            $paged = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;
            $posts_per_page = 16;
            $guide_query = new WP_Query(array(
                'post_type'     => 'duvine_staff',
                'orderby'       => 'title',
                'order'         => 'ASC',
                'posts_per_page'=> $posts_per_page,
                'paged'         => $paged,
                'meta_query'    => array(
                    array(
                        'key'   => 'duvine_staff_group',
                        'value' => 'guides'
                    )
                )
            ));

            if ( $guide_query->have_posts() ) { ?>


                <div class="Listing-Total Section">
                    <?php duvine_pagination_links( $guide_query, $label = 'Staff' ); ?>
                </div><!--/.Listing-Total.Section-->


                <div class="Listing Grid Grid-Guides Center Clear">
                    <?php
                    $counter = 1;
                    $div_is_open = false;
                    while ( $guide_query->have_posts() ) {
                        $guide_query->the_post();
                        $div_is_open = false;
                    ?>

                        <?php if( 1 == $counter ) { ?>
                            <div class="Listing-Row Clear">
                        <?php } ?>

                        <?php get_template_part( 'partials/guides', 'index' ); ?>


                        <?php if( $counter % 4 == 0 ) { $div_is_open = true; ?>

                            </div><!--/.Listing-Row.Clear-->
                            <div class="Listing-Row Clear">

                        <?php } // if $video_count % 4 ?>

                    <?php
                        $counter++;
                    } // while $videos_query ?>


                    <?php if( $div_is_open || $counter <= $posts_per_page ) { ?>
                        </div><!--/.Listing-Row.Clear-->
                    <?php } ?>


                </div><!--/.Listing.Grid.Center.Clear-->


                <div class="Listing-Total Section">
                    <?php duvine_pagination_links( $guide_query, $label = 'Staff' ); ?>
                </div><!--/.Listing-Total.Section-->


            <?php } // if $guide_query
            wp_reset_postdata();
            ?>

        </div><!--/.Page.Double.Listing-Section-->
    </div><!--/.Page-->

<?php get_footer(); ?>
