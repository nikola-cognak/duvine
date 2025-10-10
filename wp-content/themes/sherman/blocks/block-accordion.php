<?php 
/**
 * MODULE - Accordion
 *
 * Block of accordion items
 *
 * @subfield title (text)
 * @subfield text (textarea)
 *
 */


?>


<div class="duvine-accordion accordion l-container l-container--small">
    <?php if(get_sub_field('accordion_title')) { ?>
        <span style="font-size:14px;text-transform:uppercase;"><strong><?php the_sub_field('accordion_title'); ?></strong></span>
    <?php } ?>
    <ul>
    <?php
        if( have_rows('items') ):
            while ( have_rows('items') ) : the_row();

                $title = '<h4>' . get_sub_field('title') . '</h4>';

                duvine_accordion_element($title, get_sub_field('text'));

            endwhile;

        endif;
    ?>
    </ul>
</div>