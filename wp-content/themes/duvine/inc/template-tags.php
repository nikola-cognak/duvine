<?php

if ( ! function_exists( 'the_posts_navigation' ) ) :
/**
 * Display navigation to next/previous set of posts when applicable.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 */
function the_posts_navigation() {
    // Don't print empty markup if there's only one page.
    if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
        return;
    }
    ?>
    <nav class="navigation posts-navigation" role="navigation">
        <h2 class="screen-reader-text"><?php _e( 'Posts navigation', '_s' ); ?></h2>
        <div class="nav-links">

            <?php if ( get_next_posts_link() ) : ?>
            <div class="nav-previous"><?php next_posts_link( __( 'Older posts', '_s' ) ); ?></div>
            <?php endif; ?>

            <?php if ( get_previous_posts_link() ) : ?>
            <div class="nav-next"><?php previous_posts_link( __( 'Newer posts', '_s' ) ); ?></div>
            <?php endif; ?>

        </div><!-- .nav-links -->
    </nav><!-- .navigation -->
    <?php
}
endif;

if ( ! function_exists( 'the_post_navigation' ) ) :
/**
 * Display navigation to next/previous post when applicable.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 */
function the_post_navigation() {
    // Don't print empty markup if there's nowhere to navigate.
    $previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
    $next     = get_adjacent_post( false, '', false );

    if ( ! $next && ! $previous ) {
        return;
    }
    ?>
    <nav class="navigation post-navigation" role="navigation">
        <h2 class="screen-reader-text"><?php _e( 'Post navigation', '_s' ); ?></h2>
        <div class="nav-links">
            <?php
                previous_post_link( '<div class="nav-previous">%link</div>', '%title' );
                next_post_link( '<div class="nav-next">%link</div>', '%title' );
            ?>
        </div><!-- .nav-links -->
    </nav><!-- .navigation -->
    <?php
}
endif;

if ( ! function_exists( 'duvine_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function duvine_posted_on() {
    $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
    if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
        $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
    }

    $time_string = sprintf( $time_string,
        esc_attr( get_the_date( 'c' ) ),
        esc_html( get_the_date() ),
        esc_attr( get_the_modified_date( 'c' ) ),
        esc_html( get_the_modified_date() )
    );

    $posted_on = sprintf(
        _x( 'Posted on %s', 'post date', '_s' ),
        '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
    );

    $byline = sprintf(
        _x( 'by %s', 'post author', '_s' ),
        '<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
    );

    echo '<span class="posted-on">' . $posted_on . '</span><span class="byline"> ' . $byline . '</span>';

}
endif;

if ( ! function_exists( 'duvine_entry_footer' ) ) :
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function duvine_entry_footer() {
    // Hide category and tag text for pages.
    if ( 'post' == get_post_type() ) {
        /* translators: used between list items, there is a space after the comma */
        $categories_list = get_the_category_list( __( ', ', '_s' ) );
        if ( $categories_list && duvine_categorized_blog() ) {
            printf( '<span class="cat-links">' . __( 'Posted in %1$s', '_s' ) . '</span>', $categories_list );
        }

        /* translators: used between list items, there is a space after the comma */
        $tags_list = get_the_tag_list( '', __( ', ', '_s' ) );
        if ( $tags_list ) {
            printf( '<span class="tags-links">' . __( 'Tagged %1$s', '_s' ) . '</span>', $tags_list );
        }
    }

    if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
        echo '<span class="comments-link">';
        comments_popup_link( __( 'Leave a comment', '_s' ), __( '1 Comment', '_s' ), __( '% Comments', '_s' ) );
        echo '</span>';
    }

    edit_post_link( __( 'Edit', '_s' ), '<span class="edit-link">', '</span>' );
}
endif;

if ( ! function_exists( 'the_archive_title' ) ) :
/**
 * Shim for `the_archive_title()`.
 *
 * Display the archive title based on the queried object.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 *
 * @param string $before Optional. Content to prepend to the title. Default empty.
 * @param string $after  Optional. Content to append to the title. Default empty.
 */
function the_archive_title( $before = '', $after = '' ) {
    if ( is_category() ) {
        $title = sprintf( __( 'Category: %s', '_s' ), single_cat_title( '', false ) );
    } elseif ( is_tag() ) {
        $title = sprintf( __( 'Tag: %s', '_s' ), single_tag_title( '', false ) );
    } elseif ( is_author() ) {
        $title = sprintf( __( 'Author: %s', '_s' ), '<span class="vcard">' . get_the_author() . '</span>' );
    } elseif ( is_year() ) {
        $title = sprintf( __( 'Year: %s', '_s' ), get_the_date( _x( 'Y', 'yearly archives date format', '_s' ) ) );
    } elseif ( is_month() ) {
        $title = sprintf( __( 'Month: %s', '_s' ), get_the_date( _x( 'F Y', 'monthly archives date format', '_s' ) ) );
    } elseif ( is_day() ) {
        $title = sprintf( __( 'Day: %s', '_s' ), get_the_date( _x( 'F j, Y', 'daily archives date format', '_s' ) ) );
    } elseif ( is_tax( 'post_format' ) ) {
        if ( is_tax( 'post_format', 'post-format-aside' ) ) {
            $title = _x( 'Asides', 'post format archive title', '_s' );
        } elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) {
            $title = _x( 'Galleries', 'post format archive title', '_s' );
        } elseif ( is_tax( 'post_format', 'post-format-image' ) ) {
            $title = _x( 'Images', 'post format archive title', '_s' );
        } elseif ( is_tax( 'post_format', 'post-format-video' ) ) {
            $title = _x( 'Videos', 'post format archive title', '_s' );
        } elseif ( is_tax( 'post_format', 'post-format-quote' ) ) {
            $title = _x( 'Quotes', 'post format archive title', '_s' );
        } elseif ( is_tax( 'post_format', 'post-format-link' ) ) {
            $title = _x( 'Links', 'post format archive title', '_s' );
        } elseif ( is_tax( 'post_format', 'post-format-status' ) ) {
            $title = _x( 'Statuses', 'post format archive title', '_s' );
        } elseif ( is_tax( 'post_format', 'post-format-audio' ) ) {
            $title = _x( 'Audio', 'post format archive title', '_s' );
        } elseif ( is_tax( 'post_format', 'post-format-chat' ) ) {
            $title = _x( 'Chats', 'post format archive title', '_s' );
        }
    } elseif ( is_post_type_archive() ) {
        $title = sprintf( __( 'Archives: %s', '_s' ), post_type_archive_title( '', false ) );
    } elseif ( is_tax() ) {
        $tax = get_taxonomy( get_queried_object()->taxonomy );
        /* translators: 1: Taxonomy singular name, 2: Current taxonomy term */
        $title = sprintf( __( '%1$s: %2$s', '_s' ), $tax->labels->singular_name, single_term_title( '', false ) );
    } else {
        $title = __( 'Archives', '_s' );
    }

    /**
     * Filter the archive title.
     *
     * @param string $title Archive title to be displayed.
     */
    $title = apply_filters( 'get_the_archive_title', $title );

    if ( ! empty( $title ) ) {
        echo $before . $title . $after;
    }
}
endif;

if ( ! function_exists( 'the_archive_description' ) ) :
/**
 * Shim for `the_archive_description()`.
 *
 * Display category, tag, or term description.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 *
 * @param string $before Optional. Content to prepend to the description. Default empty.
 * @param string $after  Optional. Content to append to the description. Default empty.
 */
function the_archive_description( $before = '', $after = '' ) {
    $description = apply_filters( 'get_the_archive_description', term_description() );

    if ( ! empty( $description ) ) {
        /**
         * Filter the archive description.
         *
         * @see term_description()
         *
         * @param string $description Archive description to be displayed.
         */
        echo $before . $description . $after;
    }
}
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function duvine_categorized_blog() {
    if ( false === ( $all_the_cool_cats = get_transient( 'duvine_categories' ) ) ) {
        // Create an array of all the categories that are attached to posts.
        $all_the_cool_cats = get_categories( array(
            'fields'     => 'ids',
            'hide_empty' => 1,

            // We only need to know if there is more than one category.
            'number'     => 2,
        ) );

        // Count the number of categories that are attached to the posts.
        $all_the_cool_cats = count( $all_the_cool_cats );

        set_transient( 'duvine_categories', $all_the_cool_cats );
    }

    if ( $all_the_cool_cats > 1 ) {
        // This blog has more than 1 category so duvine_categorized_blog should return true.
        return true;
    } else {
        // This blog has only 1 category so duvine_categorized_blog should return false.
        return false;
    }
}

/**
 * Flush out the transients used in duvine_categorized_blog.
 */
function duvine_category_transient_flusher() {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    // Like, beat it. Dig?
    delete_transient( 'duvine_categories' );
}
add_action( 'edit_category', 'duvine_category_transient_flusher' );
add_action( 'save_post',     'duvine_category_transient_flusher' );

/**
 * Output featured image
 */
function duvine_featured_image( $size='full' ) {

    if ( has_post_thumbnail() ) {  ?>
        <div class="Content-Header-Image">
            <?php the_post_thumbnail( $size ); ?>
            <span class="Image-Caption"></span>
        </div><!--/.Content-Header-Image-->
    <?php }
}

/**
 * Output Pagination Links
 */
function duvine_pagination_links( $query, $label = '', $before = '' ) {
    if( $query ) {

        $currentPage    = max( 1, get_query_var('paged') );
        $ppp = $query->query_vars['posts_per_page'];
        $end = $ppp * $currentPage;
        $start = $end - $ppp + 1;

        if( $query->post_count < $ppp ) {
            $diff = $ppp - $query->post_count;
            $end-= $diff;
        }

        $pagination_label = sprintf('%s %d - %d of %d ', $label, $start, $end, $query->found_posts);

        if( $query->found_posts != $ppp ) {
            $pagination_label.= '<span class="Pagination-Separator">&nbsp;|&nbsp;</span>';
        }

        $big = 999999999;
        $pagination_links =  paginate_links( array(
            'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
            'format'    => '?paged=%#%',
            'current'   => $currentPage,
            'total'     => $query->max_num_pages,
            'prev_text' => 'Back',
            'next_text' => 'Next'
        ) );


        if( $pagination_links ) { ?>
            <div class="Pagination">
                <?php if( $before ) { echo $before; } ?>
                <span class="Pagination-Links">
                    <?php if( $pagination_label ) { ?>
                        <span class="Pagination-Label"><?php echo $pagination_label; ?></span>
                    <?php } ?>
                    <?php echo $pagination_links; ?>
                </span><!--/.Pagination-Links-->
            </div><!--/.Pagination-->
        <?php } // if pagination_links
        else
        if( !empty($before) ) { ?>

            <div class="Pagination">
                <?php  echo $before; ?>
                <span class="Pagination-Links">
                </span><!--/.Pagination-Links-->
            </div><!--/.Pagination-->

        <?php }
    } // if $query
}

/**
 * Output Duvine ShareThis Links
 */
 function duvine_sharethis() { ?>
    <div class="Share-This">
        <span class="st_sharethis_large">&nbsp;</span>
        <span class="st_facebook_large">&nbsp;</span>
        <span class="st_twitter_large">&nbsp;</span>
        <span class="st_googleplus_large">&nbsp;</span>
        <span class="st_pinterest_large">&nbsp;</span>
        <span class="st_linkedin_large">&nbsp;</span>
        <span class="st_fblike_large">&nbsp;</span>
        <span class="st_email_large">&nbsp;</span>
    </div><!--/.Share-This-->
<?php
} //duvine_sharethis

/*
 * Get the tour Level text
 */
function duvine_tour_level( $tour_level_value ) {
  switch ( $tour_level_value ) {
    case "occasionalRider":
      //$tour_level = "Occasional";
      $tour_level = "One";
    break;

    case "weekendRider":
      //$tour_level = "Weekend";
      $tour_level = "Two";
    break;

    case "activeRider":
      //$tour_level = "Active";
      $tour_level = "Three";
    break;

    case "proRider":
      //$tour_level = "Pro/Challenge";
      $tour_level = "Four";
    break;

    default:
      $tour_level = "Any Level";
    break;
  }

	return $tour_level;
}

function duvine_valid_tour_levels() {
    return array(
        'anyLevel',
        'occasionalRider',
        'weekendRider',
        'activeRider',
        'proRider'
    );
}

/*
 * Get the booking link
 */
function duvine_booking_link( $centaur_id, $company, $start_date ) {
	$start_date = date( 'm/d/Y', $start_date );
	$url = "https://centaur.duvine.com/centaur6/online/OPR_departureDateSelect?tourId=$centaur_id&company=$company&depDate=$start_date&sFlag=true";

	return $url;
}

/*
 * Get page child nav
 */
function duvine_get_child_navigation() {
    if( !is_front_page() ) {

        $do_submenu = true;
        if( is_singular( 'duvine_bikes' ) || is_singular( 'duvine_faqs' ) ||  is_singular( 'duvine_hotels' )) {
            // 5701 = bikes/Your Experience menu ID
            $do_submenu =  5701;
        }
        else
        if( is_singular( 'post' ) || is_singular( 'duvine_videos' ) || is_singular( 'duvine_gallery' ) || is_singular( 'duvine_news' ) || is_singular( 'duvine_andyblog' ) ) {
            // 5708 = Blog menu ID
            $do_submenu = 5708;
        }
        else
        if( is_singular( 'duvine_staff' ) ) {
            // 5704 = Meet Our Team menu ID
            $do_submenu =  5704;
        }
        else
        if( is_singular( 'duvine_tours' ) || is_page_template( 'page-calendar.php' ) || is_post_type_archive( 'duvine_regions' ) ) {
            $do_submenu =  5697;
        }

        $submenu = wp_nav_menu(array(
            'echo'              => false,
            'theme_location'    => 'header',
            'container'         => false,
            'menu_class'        => 'nav-menu child-menu',
            'submenu'           => $do_submenu
        ));

        if( $submenu ) { ?>

            <nav class="Page">
                <div class="Sub-Nav Inline-List  Clear">
                    <div class="Wrapper Clear">
                        <?php echo $submenu; ?>
                    </div>
                </div>
            </nav>

    <?php
        } // if $submenu
    } // if !is_front_page
}


function duvine_format_price( $price ) {
    if( !$price ) return;

    $price = str_replace(',', '', $price);
    $price = str_replace('$', '', $price) ;
    return number_format($price, 0, '', '');
}

/*
 * Get next post type by menu order
 */
function duvine_get_next_post_link( $post_type = 'page', $menu_order = '0', $link_text = 'Next' ) {
    global $wpdb;
    $row = $wpdb->get_row( $wpdb->prepare( "
        SELECT ID
        FROM $wpdb->posts
        WHERE post_type = '%s'
        AND menu_order > '%d'
        AND post_status = 'publish'
        ORDER BY menu_order
        LIMIT 1
        ",
        $post_type,
        $menu_order
    ));

    if( $row && isset( $row->ID ) ) {
        $the_link = get_permalink( $row->ID );
        if( $the_link ) { ?>
            <div class="Next Right">
                <a href="<?php echo esc_url( $the_link ); ?>" rel="next"><?php echo $link_text; ?></a>
            </div>
        <?php }
    }
}

/*
 * Get previous post type by menu order
 */
function duvine_get_previous_post_link( $post_type = 'page', $menu_order = '0', $link_text = 'Previous' ) {
    global $wpdb;
    $row = $wpdb->get_row( $wpdb->prepare( "
        SELECT ID
        FROM $wpdb->posts
        WHERE post_type = '%s'
        AND menu_order < '%d'
        AND post_status = 'publish'
        ORDER BY menu_order
        LIMIT 1
        ",
        $post_type,
        $menu_order
    ));

    if( $row && isset( $row->ID ) ) {
        $the_link = get_permalink( $row->ID );
        if( $the_link ) { ?>
            <div class="Back Left">
                <a href="<?php echo esc_url( $the_link ); ?>" rel="next"><?php echo $link_text; ?></a>
            </div>
        <?php }
    }
}


/*
 * Get next post type by alphabetical order
 */
function duvine_get_next_post_link_alpha( $post_type = 'page', $post_title = '', $link_text = 'Next' ) {
    global $wpdb;
    $row = $wpdb->get_row( $wpdb->prepare( "
        SELECT ID
        FROM $wpdb->posts
        WHERE post_type = '%s'
        AND post_title > '%s'
        AND post_status = 'publish'
        ORDER BY post_title
        LIMIT 1
        ",
        $post_type,
        $post_title
    ));

    if( $row && isset( $row->ID ) ) {
        $the_link = get_permalink( $row->ID );
        if( $the_link ) { ?>
            <div class="Next Right">
                <a href="<?php echo esc_url( $the_link ); ?>" rel="next"><?php echo $link_text; ?></a>
            </div>
        <?php }
    }
}


/*
 * Get previous post type by alphabetical order
 */
function duvine_get_previous_post_link_alpha( $post_type = 'page', $post_title = '', $link_text = 'Previous' ) {
    global $wpdb;
    $row = $wpdb->get_row( $wpdb->prepare( "
        SELECT ID
        FROM $wpdb->posts
        WHERE post_type = '%s'
        AND post_title < '%s'
        AND post_status = 'publish'
        ORDER BY post_title
        LIMIT 1
        ",
        $post_type,
        $post_title
    ));

    if( $row && isset( $row->ID ) ) {
        $the_link = get_permalink( $row->ID );
        if( $the_link ) { ?>
            <div class="Back Left">
                <a href="<?php echo esc_url( $the_link ); ?>" rel="next"><?php echo $link_text; ?></a>
            </div>
        <?php }
    }
}
