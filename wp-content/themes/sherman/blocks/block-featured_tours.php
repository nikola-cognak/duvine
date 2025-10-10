<?php 
/**
 * MODULE - Featured Tours
 *
 * Features up to three tours
 *
 * @subfield title (text area)
 * @subfield featured_tours (relationship)
 *
 */

?>


<div class="l-container">
    <header class="t-center">
        <?php
            sk_block_field('title', array(
                'before' => '<h2 class="pageheader pageheader--padbottom">',
                'after'  => '</h2>',
                'filter' => 'smart_type'
            ));

            if( $page_tourfinder = get_field('d_tourfinder_page', 'option') ){
                echo '<a href="' . $page_tourfinder['url'] . '" class="basiclink">View All Tours</a>';
            }
        ?>

        
    </header>

    <?php $featuredTours = get_sub_field('featured_tours'); ?>
    <?php if( $featuredTours ) : ?>
        <div class="d-column-container">
            <?php foreach( $featuredTours as $tour ) : ?>
                <div class="d-col d-col--1-3 featuredtour">
                    <a href="<?php echo get_the_permalink( $tour->ID ); ?>" class="featuredtour__image">
                        <?php
                            sk_the_field('d_thumbnail_image', array(
                                'filter'      => 'sk_img_markup',
                                'filter_args' => array(
                                    'img_size' => 'tour_thumbnail'
                                ),
                                'default'     => '<div class="tourlisting__placeholder"></div>',
                                'id'          => $tour->ID
                            ));
                        ?>
                    </a><!-- .tourlisting__image -->

                    <div class="featuredtour__content">
                        <header>
                            <div class="tourlisting__location tourlisting__location--smallmargin"><?php duvine_tour_breadcrumbs( $tour->ID, true ); ?></div>
                            <h3 class="blockheader"><a href="<?php echo get_the_permalink( $tour->ID ); ?>"><?php echo $tour->post_title; ?></a></h3>
                        </header>

                        <?php
                            sk_the_field('d_tour_subtitle', array(
                                'before' => '<div class="tourlisting__description">',
                                'after'  => '</div>',
                                'id'     => $tour->ID
                            ));
                        ?>

                        <div class="featuredtour__footer">
                            <a class="cta" href="<?php echo get_the_permalink( $tour->ID ); ?>">View Tour</a>
                        </div>

                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>