<article class="Listing-Item Clear NoImage">
        <span class="Title"><?php the_title(); ?></span>

        <span class="Sub-Title">
            <?php echo get_post_meta( get_the_ID(), 'duvine_story_subtitle', true ); ?>
        </span>
        <div class="Abstract">
            <?php echo get_post_meta( get_the_ID(), 'duvine_abstract', true ); ?>
        </div>

        <?php
        $tags = get_the_terms( get_the_ID(), 'storytag' );
        if( $tags ) { ?>
            <p class="Tag-Group Testimonials-Group">

            <b>Tags:</b>

            <?php foreach( $tags as $tag ) { ?>
                <a href="?tag=<?php echo esc_attr( $tag->slug ); ?>#listingContent" class="Tag Styled">
                    <span><?php echo $tag->name; ?></span>
                </a>
            <?php } ?>
            </p>
        <?php } ?>

        <!-- <a href="/testimonials?searchString=guides&amp;regionId=0&amp;destinationId=0&amp;level=anyLevel&amp;type=0#listingContent" class="Tag Styled">
            <span>guides</span>
        </a>

        <a href="/testimonials?searchString=service&amp;regionId=0&amp;destinationId=0&amp;level=anyLevel&amp;type=0#listingContent" class="Tag Styled">
            <span>service</span>
        </a> -->

    </article><!--/.Listing-Item-->
