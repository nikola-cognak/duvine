<?php
$start_timestamp    = strtotime( get_post_meta( $post->ID, 'duvine_tour_date_start', true ) );
$start_month        = date( 'M', $start_timestamp );
$end_timestamp      = strtotime( get_post_meta( $post->ID, 'duvine_tour_date_end', true ) );
$end_month          = date( 'M', $end_timestamp );
$tour_availability  = get_post_meta( $post->ID, 'duvine_tour_date_availability', true );
$tour_date_price    = get_post_meta( $post->ID, 'duvine_tour_date_price', true );
$tour_special       = get_post_meta( $post->ID, 'duvine_tour_date_special_text', true );
$available_text     = get_post_meta( $post->ID, 'duvine_tour_date_available_text', true );
$call_or_book       = get_post_meta( $post->ID, 'duvine_tour_date_booking', true );

$tours_object = $post->tours;

foreach ( $tours_object as $post ) {
    setup_postdata( $post );
    p2p_type( 'destinations_to_regions' )->each_connected( $post->destination, array(), 'regions' );

    foreach ( $post->destination as $post ) { setup_postdata( $post );
        foreach ( $post->regions as $post ) { setup_postdata( $post );
            $tour_region = get_the_title();
        }
  }
}
wp_reset_postdata();
?>

<div class="Listing-Item Clear<?php global $is_first_tour; if( $is_first_tour ){echo ' First';} ?>">
    <div class="Date">
        <?php if( $start_month == $end_month ) { ?>

          <span class="Month"><?php echo date( 'M', $start_timestamp ) ?></span>
          <span class="Day"><?php echo date( 'j', $start_timestamp ) ?> - <?php echo date( 'j', $end_timestamp ) ?></span>

        <?php } else { ?>

          <span class="Month"><?php echo date( 'M j', $start_timestamp ) ?></span>
          <span class="Day">-</span>
          <span class="Month"><?php echo date( 'M j', $end_timestamp ) ?></span>

        <?php } ?>
    </div>

    <?php foreach ( $tours_object as $post ) : setup_postdata( $post ); ?>

    <?php
        // Set the tour data
        $tour_price             = get_post_meta( $post->ID, 'duvine_tour_price', true);
        $tour_price_supplement  = get_post_meta( $post->ID, 'duvine_tour_single_supplement', true);
        $tour_duration          = get_post_meta( $post->ID, 'duvine_tour_duration', true );
        $tour_level_value       = get_post_meta( $post->ID, 'duvine_tour_level', true );
        $tour_level             = duvine_tour_level( $tour_level_value );
        $tour_abstract          = get_post_meta( $post->ID, 'duvine_abstract', true );
        $tour_centaur_id        = get_post_meta( $post->ID, 'duvine_tour_centaur_id', true );
        $tour_company           = get_post_meta( $post->ID, 'duvine_tour_company', true );

        if( $tour_availability != 'limited' ) {
            $tour_booking_url = duvine_booking_link( $tour_centaur_id, $tour_company, $start_timestamp );
        }
    ?>

    <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'medium', array( 'class' => 'Thumbnail', 'style' => 'width:174px;height:104px' ) ); ?></a>

    <div class="Info ">
        <div class="Clear">
            <div class="Main">
                <a class="Title" href="<?php the_permalink(); ?>"><?php the_title(); ?> | <span class="SubTitle"><?php echo $tour_region ?></span></a>
                <p class="Abstract">
                    <?php echo $tour_abstract; ?>
                    <a href="<?php the_permalink(); ?>" class="Next">learn more</a>
                </p>
            </div>

            <div class="Extended">
                <span class="Inline-Title">Duration:</span> <?php echo $tour_duration; ?><br>
                <span class="Inline-Title">Level:</span> <?php echo $tour_level; ?><br>
                <span class="Inline-Title">Price:</span> $<?php echo duvine_format_price( $tour_date_price ); ?> <br>

                <?php if( 'available' == $tour_availability && !empty($available_text) ) { ?>
                  <span class="Limited"><?php echo $available_text; ?></span><br>
                <?php } ?>

                <?php if( 'limited' == $tour_availability ): ?>
                  <span class="Limited">Limited Space</span>
                <?php elseif( 'special' == $tour_availability ): ?>
                  <span class="Limited"><?php echo $tour_special; ?></span>
                <?php endif; ?>
            </div>
        </div>

        <p class="Action-Bar Black">
            <a class="Left" href="/tours?tourId=<?php echo $post->ID; ?>">View All Dates</a>

            <?php if( 'Book Now' == $call_or_book ) { ?>
              <a class="Right" target="_blank" href="<?php echo $tour_booking_url; ?>">Book Now</a>
            <?php } else if( 'Call to Book' == $call_or_book ) { ?>
              <span class="Right"><a href="/reserve">Call to book</a></span>
            <?php } else { ?>

              <?php if( $tour_availability == 'limited' ): ?>
                <span class="Right"><a href="/reserve">Call to book</a></span>
              <?php else : ?>
                <a class="Right" target="_blank" href="<?php echo $tour_booking_url; ?>">Book Now</a>
              <?php endif; ?>

            <?php } // if $call_or_book ?>

        </p>
    </div>

    <?php endforeach; wp_reset_postdata(); ?>
</div>
