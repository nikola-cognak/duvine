<a class="Grid-Link" href="<?php the_permalink(); ?>">

<!-- duvine_region_index_image
      //$image = wp_get_attachment_image( get_post_meta( get_the_ID(), 'wiki_test_image_id', 1 ), 'medium' ); -->

        <?php if( $small_image = get_post_meta( $post->ID, 'duvine_region_index_image_id', true ) ) { ?>
            <?php echo wp_get_attachment_image( $small_image, 'large') ?>
        <?php } ?>

        <span class="Title"><?php the_title(); ?></span>
    </a>

    <a class="LabelButton" href="<?php the_permalink(); ?>">
        <img class="Icon" src="<?php echo get_template_directory_uri(); ?>/assets/images/icons/destination.png" alt="Destination Icon">
        <span class="Label"><strong>Destinations</strong> in <?php the_title(); ?></span><img class="RightArrowIcon" src="<?php echo get_template_directory_uri(); ?>/assets/images/icons/right-arrow.png" alt="Right arrow">
    </a>

    <a class="LabelButton" href="/tours?regionId=<?php the_ID(); ?>">
        <img class="Icon" src="<?php echo get_template_directory_uri(); ?>/assets/images/icons/date.png" alt="Dates Icon">
        <span class="Label"><strong>Dates</strong> in <?php the_title(); ?></span><img class="RightArrowIcon" src="<?php echo get_template_directory_uri(); ?>/assets/images/icons/right-arrow.png" alt="Right arrow">
    </a>
