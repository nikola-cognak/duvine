<div class="Listing-Item Video">
    <?php
    $color_image_id = get_post_meta( $post->ID, 'duvine_video_image_color_id', true );
    $image_url = wp_get_attachment_image_src( $color_image_id, 'medium' );
    ?>

    <a href="<?php the_permalink(); ?>" class="Grayscale-Image" style="background-image: url(<?php echo $image_url[0]; ?>);background-size:214px 120px;">

        <?php if ( has_post_thumbnail() ) {  ?>
            <?php the_post_thumbnail( 'video-thumb' ); ?>
        <?php } ?>

        <span class="Title"><?php the_title(); ?></span>
    </a>
</div>
