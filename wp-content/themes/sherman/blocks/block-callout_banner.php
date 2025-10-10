<?php 
/**
 * MODULE - Callout banner
 *
 * Gray background callout block that spans the site width
 *
 * @subfield title (text)
 * @subfield content (wysiwyg)
 *
 */


// sk_the_subfield('copy');

?>

<div class="calloutbanner__banner" data-is-gutenberg="<?= use_block_editor_for_post($post) ?>">
    <?php
        sk_block_field( 'title', array(
            'before' => '<h3 class="tertiaryheader">',
            'after'  => '</h3>',
            'filter' => 'smart_type'
        ));
        sk_block_field( 'content', array(
            'before' => '<div class="calloutbanner__content d-content">',
            'after'  => '</div>'
        ));
    ?>
</div>
