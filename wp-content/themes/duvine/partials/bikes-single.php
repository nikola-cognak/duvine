<article class="Page">

    <div class="TwoColumn Detail Clear Section Page Top">
        <div class="Column One">
            <div class="Breadcrumb Section">
                <a href="/bikes">Your Experience</a> &rsaquo;
                <a href="/bikes">Bike</a> &rsaquo;
                <a><?php the_title(); ?></a>
            </div>
            <?php if ( has_post_thumbnail() ) {  ?>
                <?php the_post_thumbnail( 'bike-detail' ); ?>
            <?php } ?>
        </div><!--/.Column.One-->

        <div class="Column Two">

            <h1 class="Detail-Title"><?php the_title(); ?></h1>

            <p>
                <?php echo get_post_meta( get_the_ID(), 'duvine_bike_manufacturer', true ); ?>
                /
                <?php echo get_post_meta( get_the_ID(), 'duvine_bike_style', true ); ?>
            </p>

            <?php the_content(); ?>


            <div class="Section">
                <?php
                //***************************************************************************
                // Related Tours
                //***************************************************************************
                $connected_tours = new WP_Query( array(
                  'connected_type'  => 'tours_to_bikes',
                  'connected_items' => get_queried_object(),
                  'nopaging'        => true,
                  'orderby'         => 'title',
                  'order'           => 'ASC'
                ) );

                if( $connected_tours->have_posts() ) { ?>

                    <span class="Title">Ride this bike on these tours:</span><br />

                    <?php while( $connected_tours->have_posts() ) { $connected_tours->the_post(); ?>

                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a><br />

                        <?php
                    } // while $connected_tours->have_posts
                    wp_reset_postdata();
                } // if $connected_tours->have_posts
                ?>


            </div><!--/.Section-->
        </div>  <!--/.Column.Two-->
    </div><!--/.TwoColumn-->

</article><!--/.Page-->

<?php duvine_sharethis(); ?>

<div class="Action-Bar Post-Navigation Clear">
    <?php
    global $post;
    duvine_get_previous_post_link( 'duvine_bikes', $post->menu_order, 'Previous Bike'  );
    duvine_get_next_post_link( 'duvine_bikes', $post->menu_order, 'Next Bike'  );
    ?>
</div>
