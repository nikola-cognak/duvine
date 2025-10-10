<?php if( 'on' != get_post_meta( $post->ID, 'duvine__title', true ) ) { ?>
        <h1 class="Page-Title"><?php the_title(); ?></h1>
    <?php } ?>

    <div class="Info Body Clear">
        <?php the_content(); ?>
    </div><!--/.Info.Body-->
