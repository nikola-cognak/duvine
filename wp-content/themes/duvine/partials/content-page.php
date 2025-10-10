<?php
    $banner_heading =  get_post_meta( $post->ID, 'duvine_banner_heading', true );
    if( !empty( $banner_heading ) ) { ?>
        <h1 class="Page-Title Center Highlight HideInOverlay"><?php echo $banner_heading; ?></h1>
    <?php } ?>


    <?php if( 'on' != get_post_meta( $post->ID, 'duvine__title', true ) ) { ?>
        <h2 class="Section-Title Headline"><?php the_title(); ?></h2>
    <?php } ?>

    <div class="Info Body Clear">
        <?php the_content(); ?>
    </div><!--/.Info.Body-->
