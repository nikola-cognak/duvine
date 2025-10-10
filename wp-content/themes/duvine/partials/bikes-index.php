<div class="Listing-Item Center">
    <a class="Grid-Link" href="<?php the_permalink(); ?>">

        <?php if ( has_post_thumbnail() ) {  ?>
            <?php the_post_thumbnail( 'bike-thumb' ); ?>
        <?php } ?>

        <span class="Listing-Title"><?php the_title(); ?></span>
    </a>


    <?php if( $bike_manufacturer = get_post_meta( get_the_ID(), 'duvine_bike_manufacturer', true ) ) { ?>
        <span class="Listing-Sub-Line"><?php echo $bike_manufacturer; ?></span>
    <?php } ?>

    <?php if( $bike_style = get_post_meta( get_the_ID(), 'duvine_bike_style', true ) ) { ?>
        <span class="Listing-Sub-Line"><?php echo $bike_style; ?></span>
    <?php } ?>

</div><!--/.Listing-Item-->
