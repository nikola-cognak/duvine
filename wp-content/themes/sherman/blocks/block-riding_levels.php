<?php
//
// MODULE - Rider Levels
//
// Renders rider levels in a grid
//
?>


    <div class="tourlevels l-container l-container--small">

        <div class="d-content">
        <?php if(get_sub_field('levels_section_title')) : ?>
            <h3><?php echo get_sub_field('levels_section_title'); ?></h3>
        <?php endif; ?>
        </div>

        <div class="tourlevels__grid d-column-container duvine-accordion">

            <?php
            if( have_rows('levels') ):
                while ( have_rows('levels') ) : the_row(); ?>

                    <div class="tourlevels__level d-col d-col--1-4">
                        <header class="accordion__header js-duvine-accordion-trigger">
                            <div class="level-accordion-header">
                                <div class="accordion-title">
                                    Riding Level <?php echo get_sub_field('level'); ?>
                                </div>
                            </div>
                        </header>
                        <div class="accordion__content">
                            <div class="header-image">
                                <span class="header-level"><?php //echo get_sub_field('level'); ?></span>
                                <?php
                                $header_img = get_sub_field('header_image');
                                //var_dump($header_img);
                                if( !empty($header_img) ): ?>
                                    <div class="image-container">
                                        <img src="<?php echo $header_img['url']; ?>" alt="<?php echo $header_img['alt']; ?>" />
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="top-text">
                                <?php echo get_sub_field('per_day_text'); ?>
                            </div>
                            <?php
                            if( have_rows('bottom_text') ): ?>
                                <div class="bottom-text">
                                    <ul>
                                    <?php while( have_rows('bottom_text') ): the_row(); ?>
                                        <li>
                                            <div class="bottom-text__header"><?php echo get_sub_field('title'); ?></div>
                                            <div class="bottom-text__copy"><?php echo get_sub_field('copy'); ?></div>
                                        </li>
                                    <?php endwhile; ?>
                                    </ul>
                                    <p><a class="cta" href="/tour-finder/?level%5B%5D=<?php echo get_sub_field('level'); ?>">View Level <?php echo get_sub_field('level'); ?> Tours</a></p>
                                </div>
                            <?php endif; ?>
                        </div>

                    </div>

                <?php endwhile;

            endif;
            ?>

        </div>

        <?php 
        $link = get_sub_field('download_guides');

        if( $link ): 
            $link_url = $link['url'];
            $link_title = $link['title'];
            $link_target = $link['target'] ? $link['target'] : '_self';
            ?>
            <div class="download-guides">
                <a href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>"><?php echo esc_html($link_title); ?>
                    <img src="/wp-content/themes/sherman/svg/down-arrow.svg" alt="down arrow" width="17" />
                </a>
            </div>
        <?php endif; ?>

    </div>
