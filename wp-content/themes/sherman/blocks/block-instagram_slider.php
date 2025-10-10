<?php 
/**
 * MODULE - Instagram slider
 *
 * Shows the latest instagram posts
 *
 * @subfield title (text)
 * @subfield instagram_link_title (text)
 * @subfield instagram_link_url (url)
 *
 */

delete_transient('duvine_instagram_markup');

// do we want to filter by a tag?
$tag = '';
if( strtolower(basename(get_sub_field('instagram_link_url'))) != 'duvine' && strtolower(basename(get_sub_field('instagram_link_url'))) != '' ) {
    $tag = basename(get_sub_field('instagram_link_url'));
    // need to clear the transient since we don't want the general feed to appear
    delete_transient('duvine_instagram_markup');
}
$instaMarkup = get_transient( 'duvine_instagram_markup' );
?>

<header class="l-container instaslider__header">
    <?php
        sk_block_field('title', array(
            'before' => '<h3 class="blockheader instaslider__title">',
            'after'  => '</h3>'
        ));

        $instaLinkText = get_sub_field('instagram_link_title');
        $instaLinkUrl = get_sub_field('instagram_link_url');
        if( $instaLinkText && $instaLinkUrl ) {
            echo "<a class=\"insta__cta\" href=\"$instaLinkUrl\">$instaLinkText</a>";
        }
    ?>


</header>

<div class="instaslider" data-id="duvine-instagram"<?php if( $tag != '' ) echo ' data-tag="'.$tag.'"'; ?>>
    <ul id="duvine-instagram" class="insta__list baselist<?php if( $instaMarkup !== false ) echo ' insta__list--fromcache'; ?>">
        <?php if( $instaMarkup !== false ) : ?>
            <?php echo urldecode($instaMarkup); ?>
        <?php endif; ?>
    </ul>
</div>


