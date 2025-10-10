<div class="Listing-Item" id="faq-<?php echo get_the_ID(); ?>">
    <a class="Title" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
    <p class="Abstract">
        <?php echo get_post_meta( get_the_ID(), 'duvine_abstract', true ); ?>
    </p>
</div>
