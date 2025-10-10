<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package sherman
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('entry-content'); ?>>
	<?php the_content(); ?>
</article><!-- #post-## -->
