<?php get_header(); ?>

    <div class="Page Clear">
        <h1 class="Page-Title Search-Title"><?php printf( __( 'Search Results for: %s', '_s' ), '<span>' . get_search_query() . '</span>' ); ?></h1>

        <div class="Page Double Listing-Section Search-Results">


          <?php if( isset( $_GET['type'] ) && 'content' == $_GET['type'] ) { ?>

            <?php get_template_part( 'partials/search', 'content' ); ?>

          <?php } elseif( isset( $_GET['type'] ) && 'article' == $_GET['type'] ) { ?>

            <?php get_template_part( 'partials/search', 'article' ); ?>

          <?php } else { ?>

            <?php get_template_part( 'partials/search', 'results-all' ); ?>

          <?php } ?>

        </div><!--/.Page.Double-->
    </div><!--/.Page.Clear-->

<?php get_footer(); ?>
