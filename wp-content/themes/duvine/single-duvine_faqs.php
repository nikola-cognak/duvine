<?php get_header(); ?>

    <div class="Page Double">

        <div class="Page Experience">
            <h2 class="Section-Title Headline">FAQS</h2>
            <div class="Info Clear"></div>
        </div>

        <hr class="Large Slim">

        <div class="Clear Section Page Top">

            <?php while ( have_posts() ) : the_post(); ?>

                <?php get_template_part( 'partials/faqs', 'single' ); ?>

            <?php endwhile; // end of the loop. ?>

        </div><!--/.Clear.Section.Page-->

        <?php duvine_sharethis(); ?>

    </div><!--/.Page.Double-->

<?php get_footer(); ?>
