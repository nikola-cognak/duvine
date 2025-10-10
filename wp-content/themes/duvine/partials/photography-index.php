<div class="Listing-Item">

    <a href="<?php the_permalink(); ?>">
        <span class="Title"><?php the_title(); ?></span>

        <?php
        if( function_exists('get_field') && $slide_images = get_field('photography_gallery') ) {
            $slide_images = array_slice($slide_images, 0, 5);
            if( $slide_images ) {
                foreach( $slide_images as $slide_image ) { ?>

                    <img src="<?php echo esc_url( $slide_image['sizes']['gallery-thumb'] ) ; ?>" alt="<?php echo esc_attr( $slide_image['alt'] ) ; ?>">

                <?php }
            }
        }
        ?>
    </a>

</div>
