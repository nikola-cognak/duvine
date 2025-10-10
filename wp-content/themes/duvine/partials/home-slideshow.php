<div class="Slideshow Home">
    <div class="slick-slider slick-home">
        <?php
        if( function_exists( 'have_rows' ) && have_rows('hp_slides') ) {
            while ( have_rows('hp_slides') ) { the_row();

                $slide_image    = get_sub_field('hp_slide_image');
                $slide_url      = get_sub_field('hp_slide_url');
                ?>
                   <a href="<?php echo esc_url( $slide_url ); ?>">
                       <img data-lazy="<?php echo esc_url( $slide_image['url'] ); ?>" aria-label="<?php echo esc_attr( $slide_image['alt'] ); ?>" />
                   </a>

                <?php
            } // while have_rows
        } // if have_rows
        ?>
    </div>
</div><!--/.Slideshow.Home-->
