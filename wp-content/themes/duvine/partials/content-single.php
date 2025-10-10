<article class="Blog Detail">
    <h1 class="Page-Title"><?php the_title(); ?></h1>

    <time class="Sub-Title Slim" datetime="<?php the_date( 'c' ); ?>"><?php echo get_the_date( 'l, F j, Y \a\t g:i a' ); ?></time>
    <span class="Author"><?php echo get_post_meta( get_the_ID(), 'duvine_blog_author', true ); ?></span>

    <div class="Section Clear Body">
        <?php the_content(); ?>
    </div>

    <?php
    // Output Tags
    the_tags( '<p class="Tag-Group"><strong>TAGS: </strong>', ', ', '</p>');

    // Build category array to exclude Uncategorized tag
    $cats = array();
    $categories = get_the_category( get_the_ID() );
    if( $categories ) {
        foreach( $categories as $category ) {
            if( 'Uncategorized' != $category->cat_name  ) {
                $cats[] = '<a href="' . get_category_link( $category->term_id ) . '">' . $category->cat_name . '</a>';
            }
        }
    }

    // Output categories
    if( $cats ) { ?>
        <p class="Tag-Group">
            <strong>CATEGORIES: </strong>

            <?php
            if( 1 == count( $cats ) ) {
                echo $cats[0];
            }
            else {
                echo implode(', ', $cats);
            }
            ?>
        </p>

    <?php
    } // if cats

    // Output share this icons
    duvine_sharethis();
    ?>
</article>
