<div class="Section">
  <span class="Page-Title">Content</span>
  <div class="Listing Search">
    <?php
    $posts_per_page = 8;
    $paged = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;
    $content_search = new WP_Query(array(
      'post_type'     => 'page',
      'post_status'   => 'publish',
      's'             => get_search_query(),
      'posts_per_page'=> $posts_per_page,
      'paged'         => $paged,
    ));

    if( $content_search->have_posts() ) { ?>

      <?php duvine_pagination_links( $content_search, $label = 'Content' ); ?>

      <?php while( $content_search->have_posts() ) { $content_search->the_post(); ?>

        <article class="Listing-Item Clear">
          <a class="Title" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </article>

      <?php } wp_reset_postdata(); // while have_posts ?>

      <?php duvine_pagination_links( $content_search, $label = 'Content' ); ?>

    <?php } else { ?>

      <article class="Listing-Item Clear">
        <p>No content results found</p>
      </article>

    <?php } // if have_posts ?>
  </div><!--/.Listing.Search-->

</div><!--/.Section-->
