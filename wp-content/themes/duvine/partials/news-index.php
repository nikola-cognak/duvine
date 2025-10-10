<article class="Listing-Item Clear">

    <?php if ( has_post_thumbnail() ) {  ?>
        <a class="Thumbnail" href="<?php the_permalink(); ?>">
            <?php the_post_thumbnail( 'news-thumb' ); ?>
        </a>
    <?php } ?>

    <a class="Title" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
    <time class="Sub-Title" datetime="<?php the_date( 'c' ); ?>"><?php echo get_the_date( 'M j, Y' ); ?></time>
    <p class="Abstract"><?php the_excerpt(); ?></p>
</article>
