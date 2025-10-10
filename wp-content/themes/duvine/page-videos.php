<?php
/**
* Template Name: Videos Page
**/

get_header(); ?>

    <div class="Page">
        <div class="Page Double Listing-Section">

            <div class="Clear">
                <form class="Listing-Search" action="/videos" method="get">
                    <input type="text" class="Input" id="searchString" name="searchString" placeholder="Search Videos" value="">
                    <button class="Button Action" id="searchVideoButton" type="submit"><span>Search</span></button>
                </form>
                <a class="YouTube-Icon" href="http://www.youtube.com/bicycletours" target="_blank">Watch DuVine TV on YouTube</a>
            </div>

            <?php
            //***************************************************************************
            // Output Videos
            //***************************************************************************
            $paged = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;

            $post_per_page = 25;

            $video_args = array(
                'post_type'     => 'duvine_videos',
                'orderby'       => 'menu_order',
                'order'         => 'ASC',
                'posts_per_page'=> $post_per_page,
                'paged'         => $paged,
                'meta_key'         => 'duvine_video_image_color_id',
                'meta_value'    => '',
                'meta_compare'  => '!='
            );

            if( isset( $_GET['searchString'] ) && !empty( $_GET['searchString'] ) ) {
                $video_args['s'] = strip_tags( trim( $_GET['searchString'] ) );
            }


            $videos_query = new WP_Query( $video_args );

            if ( $videos_query->have_posts() ) { ?>

                <div class="Listing-Total Section">
                    <?php duvine_pagination_links( $videos_query, $label = 'Videos' ); ?>
                </div>

                <div class="Listing Grid Clear">
                    <?php
                    $counter = 1;
                    $div_is_open = false;
                    while ( $videos_query->have_posts() ) {
                        $videos_query->the_post();
                        $div_is_open = false;
                    ?>

                        <?php if( 1 == $counter ) { ?>
                            <div class="Listing-Row Clear">
                        <?php } ?>

                        <?php get_template_part( 'partials/videos', 'index' ); ?>

                        <?php if( $counter % 4 == 0 ) { $div_is_open = true; ?>

                            </div><!--/.Listing-Row.Clear-->
                            <div class="Listing-Row Clear">

                        <?php } // if $counter
                        $counter++;
                    } // while $videos_query ?>

                    <?php if( $div_is_open || $counter <= $posts_per_page ) { ?>
                        </div><!--/.Listing-Row.Clear-->
                    <?php } ?>

                </div><!--/.Listing.Grid-->

                <div class="Listing-Total Section">
                    <?php duvine_pagination_links( $videos_query, $label = 'Videos' ); ?>
                </div>

                <?php } // if $videos_query
                wp_reset_postdata();
                ?>
        </div><!--/.Page.Double-->
    </div><!--/.Page-->

    <script>
        jQuery(function($) {
            var screenSize = window.getComputedStyle(document.body,':before').getPropertyValue('content') || '';
            if( 'mediumscreen' !== screenSize ) {
                $(".Grayscale-Image").on({
                    mouseenter: function(event) {
                        $(this).children('img').stop(true,true).animate({opacity: 0}, 400);
                    },
                    mouseleave: function(event) {
                        $(this).children('img').stop(true,true).animate({opacity: 1}, 400);
                    }
                });
            }
        });
    </script>

<?php get_footer(); ?>
