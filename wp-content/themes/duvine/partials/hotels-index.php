<?php
// Set connected to get region
p2p_type( 'destinations_to_regions' )->each_connected( $post->destination, array(), 'region' );

// Bit of hack to Region
foreach ( $post->destination as $post_destination ) {
    foreach ( $post_destination->region as $post_region ) {
        $permalink = get_permalink( $post_region->ID );
        $hotel_region = "<a href=\"$permalink\">{$post_region->post_title}</a>";
    }
}

$hotel_destination = do_shortcode( '[p2p_connected type=hotels_to_destinations mode=inline]' );
?>

<div class="Listing-Item Center Hotel">
    <a class="Grid-Link" href="<?php the_permalink(); ?>">

        <?php if( has_post_thumbnail() ) { ?>
            <?php the_post_thumbnail( 'guide-thumb' ); ?>
        <?php } ?>

        <span class="Listing-Title"><?php the_title(); ?></span>
    </a>

    <span class="Listing-Sub-Line">
        <?php echo $hotel_region; ?> | <?php echo $hotel_destination; ?>
    </span>

    <hr class="Slim">
    <p class="Abstract"></p>
</div>
