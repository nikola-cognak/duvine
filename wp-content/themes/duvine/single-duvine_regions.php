<?php get_header(); ?>


    <div class="Page Section RegionDetail">

        <?php while ( have_posts() ) : the_post(); ?>

          <?php
          if( isset( $_GET['tourType'] ) ) {
              get_template_part( 'partials/region', 'single-type' );
          }
          else {
              get_template_part( 'partials/region', 'single' );
          }
          ?>

        <?php endwhile; // end of the loop. ?>


    </div><!--/.Page.Section.RegionDetail-->

<?php get_footer(); ?>
