<article class="Listing-Item Clear">

        <?php if ( has_post_thumbnail() ) { ?>
            <a class="Thumbnail" href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail( 'post-thumb' ); ?>
            </a>
        <?php } ?>

        <a class="Title" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>

        <time class="Sub-Title" datetime="<?php the_date( 'c' ); ?>">Posted by <?php echo get_post_meta( get_the_ID(), 'duvine_blog_author', true ); ?> on <?php echo get_the_date( 'l, F j, Y \a\t g:i a' ); ?></time>

        <p class="Abstract"><?php the_excerpt(); ?></p>
    </article>
