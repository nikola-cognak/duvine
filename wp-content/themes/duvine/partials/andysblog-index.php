<article class="Listing-Item Clear">

    <?php if ( has_post_thumbnail() ) {  ?>
        <a class="Thumbnail" href="<?php the_permalink(); ?>">
            <?php the_post_thumbnail( 'post-thumb' ); ?>
        </a>
    <?php } ?>

    <a class="Title" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
    <time class="Sub-Title" datetime="<?php the_date( 'c' ); ?>">Posted by Andy Levine on <?php echo get_the_date( 'l, F j, Y \a\t g:i a' ); ?></time>
    <p class="Abstract"><?php the_excerpt(); ?></p>

    <p class="Tag-Group">
        <?php echo get_the_term_list( get_the_ID(), 'andytag', '<b>Tags: </b>', ', ' ); ?>
    </p>

    <p class="Tag-Group">
        <?php echo get_the_term_list( get_the_ID(), 'andycategory', '<b>Categories: </b>', ', ' ); ?>
    </p>
</article>
