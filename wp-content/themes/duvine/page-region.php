<?php
/**
 * Template Name: Tours By Region Page
 */
get_header(); ?>

    <h1 class="Page-Title Tour Center">Tours by Region</h1>

    <div class="Page Section Regions">

        <?php while ( have_posts() ) : the_post(); ?>

            <div class="Listing-Section Clear">
                <?php
                //***************************************************************************
                // Output Regions
                //***************************************************************************
                $regions_query = new WP_Query(array(
                    'post_type'     => 'duvine_regions',
                    'posts_per_page'=> '-1',
                    'orderby'       => 'menu_order',
                    'order'         => 'ASC'
                ));

                if ( $regions_query->have_posts() ) { ?>



                    <?php
                    $counter = 1;
                    $div_is_open = false;
                    while ( $regions_query->have_posts() ) {
                        $regions_query->the_post();
                        $div_is_open = false;
                    ?>

                        <?php if( 1 == $counter ) { ?>
                            <div class="Row Clear">
                        <?php } ?>

                        <div class="Region <?php echo ($counter % 3 == 1) ? 'First' : null ?>">
                            <?php get_template_part( 'partials/regions', 'index' ); ?>
                        </div>

                        <?php if( $counter % 3 == 0 ) { $div_is_open = true; ?>

                            </div><!--/.Row.Clear-->
                            <div class="Row Clear">

                        <?php }  ?>


                    <?php
                        $counter++;
                    } // while $regions_query ?>

                    <?php if( $div_is_open ) { ?>
                        </div><!--/.Row.Clear-->
                    <?php } ?>

                <?php } // if $regions_query->have_posts

                wp_reset_postdata();
                ?>

            </div><!--/.Row.Clear-->



        <?php endwhile; // end of the loop. ?>

    </div><!--/.Page.Double-->

<?php get_footer(); ?>
