<div class="Listing-Item Related-Item Large">
    <a href="<?php the_permalink(); ?>">

        <?php if( has_post_thumbnail()) { ?>
            <?php the_post_thumbnail( 'guide-thumb' ); ?>
        <?php } ?>

        <span class="Title"><?php the_title(); ?></span>
    </a>

    <span class="Sub-Title"><?php echo get_post_meta( get_the_ID(), 'duvine_staff_job_title', true ); ?></span>
    <blockquote class="Abstract"></blockquote>
</div><!--/.Listing-Item.Related-Item.Large-->
