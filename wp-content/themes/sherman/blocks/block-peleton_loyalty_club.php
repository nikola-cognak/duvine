<?php
//
// MODULE - Peleton Loyalty Club
//
// Renders loyalty in a table, mobile in accordion
//
?>
    <div class="loyalty-club l-container l-container--small">

        <div class="d-content">
        <?php if(get_sub_field('peloton_club_section_title')) : ?>
            <h3><?php echo get_sub_field('peloton_club_section_title'); ?></h3>
        <?php endif; ?>
        </div>

        <div class="loyalty-club__grid d-column-container duvine-accordion">

            <?php
            if( have_rows('table_columns') ):
                $cnt = 1;
                $col_count = 0;
                $tableColumns = get_field('table_columns');
                if (is_array($tableColumns) || is_object($tableColumns)) {
                    $col_count = count(get_field('table_columns'));
                }
                while ( have_rows('table_columns') ) : the_row(); ?>

                    <?php if( $cnt == 2 ) : ?>
                        <div class="loyalty-club__column d-col d-col--2-3">
                    <?php endif; ?>

                    <div class="loyalty-club__column d-col d-col--1-3">
                        <header class="accordion__header js-duvine-accordion-trigger">
                            <div class="column-accordion-header">
                                <div class="accordion-title">
                                    <?php the_sub_field('column_header'); ?>
                                </div>
                            </div>
                        </header>
                        <div class="accordion__content">
                            <div class="temp-header"><?php the_sub_field('column_header'); ?></div>
                            <?php
                            if( have_rows('column_cell') ): $cell = 1; ?>
                                <div class="bottom-text">
                                    <ul>
                                    <?php while( have_rows('column_cell') ): the_row(); ?>
                                        <?php if( $cell == 1 ) $row_copy = get_sub_field('cell_data'); ?>
                                        <li class="cell<?php echo $cell; if( !get_sub_field('cell_data_mobile') ) echo ' empty-cell'; ?>">
                                            <div class="bottom-text__copy<?php if( get_sub_field('cell_data') == 'YES' ) echo ' checkmark'; ?>">
                                                <?php if( get_sub_field('cell_data') == 'YES' ) : ?>
                                                    <img data-ot-ignore src="<?php echo get_template_directory_uri(); ?>/svg/checkmark.svg" width="24" heigh="19" />
                                                <?php else: ?>
                                                    <?php if( get_sub_field('cell_data') !== 'NO' ) : ?>
                                                    <span class="desktop-copy"><?php the_sub_field('cell_data'); ?></span>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                                <?php if( get_sub_field('cell_data_mobile') ) : ?>
                                                <span class="mobile-copy"><?php the_sub_field('cell_data_mobile') ?></span>
                                                <?php endif; ?>
                                            </div>
                                        </li>
                                    <?php ++$cell; endwhile; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                        </div>

                    </div>

                    <?php if( $cnt == $col_count ) : ?>
                        </div>
                    <?php endif; ?>

                <?php ++$cnt; endwhile;

            endif;
            ?>

        </div>

        <?php 
        if( get_sub_field('footnotes') ) : ?>
            <div class="footnotes">
                <?php the_sub_field('footnotes'); ?>
            </div>
        <?php endif; ?>

    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.matchHeight/0.7.2/jquery.matchHeight-min.js"></script>

    <script>
        jQuery(document).ready(function($){
            for( i = 1; i < <?php echo $cell; ?>; i++ ) {
                $('.cell'+i).matchHeight({
                    byRow: false
                });
            }
            for( i = 1; i < <?php echo $cell; ?>; i++ ) {
                height = $('.cell'+i).height()+'px';
                $('.cell'+i+' .checkmark').css('line-height',height);
                $('.cell'+i+' .checkmark img').css('vertical-align','middle');
            }
        });
    </script>


