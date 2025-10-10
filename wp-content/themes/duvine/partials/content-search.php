<?php if( $post->post_type == 'duvine_tours' ): ?>

    <?php
    $destination_connected = p2p_type( 'tours_to_destination' )->get_connected( $post->ID );

    // Bit of a hack to get the region
    while($destination_connected->have_posts()) {
        $destination_connected->the_post();
        $tour_region = do_shortcode( '[p2p_connected type=destinations_to_regions mode=inline]' );
    }
    wp_reset_postdata();
    ?>

    <div class="Listing-Item Related-Item">
        <a href="<?php the_permalink(); ?>">
            <?php the_post_thumbnail( 'medium', array( 'style' => 'width:160px;height:auto' ) ); ?>
            <span class="Title"><?php the_title(); ?></span>
        </a>

        <span class="Sub-Title"><?php echo $tour_region; ?></span>

        <p class="Abstract"><?php echo get_post_meta( $post->ID, 'duvine_abstract', true ); ?></p>
    </div>
<?php elseif( $post->post_type == 'post' ): ?>

    <article class="Listing-Item Clear">
        <a class="Title" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        <time class="Sub-Title" datetime=""><?php the_time('l, F j, Y g:i A') ?></time>
        <p class="Abstract"><?php echo get_post_meta( $post->ID, 'duvine_abstract', true ); ?></p>
    </article>

<?php elseif( $post->post_type == 'page' ): ?>

    <article class="Listing-Item Clear">
        <a class="Title" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
    </article>

<?php endif; ?>
