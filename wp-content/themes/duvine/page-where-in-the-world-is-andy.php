<?php
/**
* Template Name: Where in the World is Andy Page
**/

get_header(); ?>

    <?php while ( have_posts() ) : the_post(); ?>

        <?php if ( has_post_thumbnail() ) {  ?>
            <div class="Content-Header-Image">
                <?php the_post_thumbnail(); ?>
                <span class="Image-Caption"><?php the_title(); ?></span>
            </div>
        <?php } ?>

        <div class="Page">
            <div class="Page Double Listing-Section">

                <div class="Clear">
                    <form class="Listing-Search" action="<?php the_permalink(); ?>" method="get">
                        <input type="text" class="Input" id="searchString" name="searchString" placeholder="Search Blog" value="">
                        <button class="Button Action" id="searchVideoButton" type="submit"><span>Search</span></button>
                    </form>
                </div>


                <?php
                //***************************************************************************
                // Output Andy's Blog
                //***************************************************************************
                $posts_per_page = 5;
                $paged = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;

                $andy_args = array(
                    'post_type'     => 'duvine_andyblog',
                    'orderby'       => 'post_date',
                    'order'         => 'DESC',
                    'posts_per_page'=> $posts_per_page,
                    'paged'         => $paged,
                );

                if( isset( $_GET['searchString'] ) && !empty( $_GET['searchString'] ) ) {
                    $andy_args['s'] = trim( strip_tags( $_GET['searchString'] ) );
                }

                $andy_query = new WP_Query( $andy_args );

                if ( $andy_query->have_posts() ) { ?>


                    <div class="Listing-Total Section">
                        <?php duvine_pagination_links( $andy_query, $label = 'Blog' ); ?>
                    </div><!--/.Listing-Total.Section-->

                    <div class="Listing Blog">


                        <?php
                        $counter = 1;
                        while ( $andy_query->have_posts() ) { $andy_query->the_post(); ?>

                            <?php get_template_part( 'partials/andysblog', 'index' ); ?>

                            <?php if( $counter % $posts_per_page != 0  ) { ?>
                                <hr>
                            <?php } ?>

                        <?php
                            $counter++;
                        } // while $andy_query ?>

                    </div><!--/.Listing.Blog-->


                    <div class="Listing-Total Listing-Last Section">
                        <?php duvine_pagination_links( $andy_query, $label = 'Blog' ); ?>
                    </div><!--/.Listing-Total.Section-->

                <?php } // if $andy_query->have_posts
                wp_reset_postdata();
                ?>


            </div><!--/.Page.Double.Listing-Section-->
        </div><!--/.Page-->

    <?php endwhile; // end of the loop. ?>

<?php get_footer(); ?>
