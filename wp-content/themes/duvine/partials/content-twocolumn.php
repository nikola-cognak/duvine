<?php duvine_featured_image(); ?>

        <div class="Page">
            <div class="Page Top">

                <?php
                $banner_heading =  get_post_meta( $post->ID, 'duvine_banner_heading', true );
                if( !empty( $banner_heading ) ) { ?>
                    <h1 class="Page-Title Center Highlight HideInOverlay"><?php echo $banner_heading; ?></h1>
                <?php } ?>

                <?php if( 'on' != get_post_meta( $post->ID, 'duvine__title', true ) ) { ?>
                    <h1 class="Page-Title"><?php the_title(); ?></h1>
                <?php } ?>

                <div class="TwoColumn Body-Form Clear">
                    <div class="Column One">
                        <div class="Info Clear Body">
                            <?php the_content(); ?>
                        </div>
                    </div><!--/.Column.One-->
                    <div class="Column Two">
                        <?php the_field( 'right_column_content' ); ?>
                    </div><!--/.Column.Two-->
                </div><!--/.TwoColumn-->
            </div><!--/.Page.Top-->
        </div><!--/.Page-->
