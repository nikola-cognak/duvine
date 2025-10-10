<?php

/* -------------------------------------------------
 *
 * --API
 *
 * ------------------------------------------------- */


/**
 * Add a load more button to your page
 * @param object $query
 * @param string $context
 * @param string $text. (optional) Text to display on button.
 * @param int $paged. (optional) WP query var.
 * @return void
 */
function sk_load_more_button( $query = null, $context = 'default', $text = 'Load More', $paged = 0 ) {
    if ( empty( $paged ) ) {
        $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1; 
    }
    $load_more = new SK_Load_More_Posts();
    $load_more->load_more_button( $query, $context, $text, $paged );
}



/* -------------------------------------------------
 *
 * --CLASS
 *
 * ------------------------------------------------- */


if ( !class_exists( 'SK_Load_More_Posts' ) ) :
/**
 * Load more button functionality  
 * Uses WP pagination
 */
class SK_Load_More_Posts {
    /**
     * Add hooks
     */
    public function __construct() {
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
        add_action( 'wp_ajax_load_more_posts', array( $this, 'load_more_posts' ) );
        add_action( 'wp_ajax_nopriv_load_more_posts', array( $this, 'load_more_posts' ) );
    }
    /**
     * Enqueue the necessary assets
     */
    public function enqueue_assets() {
        wp_enqueue_script( 'load-more-posts-js', get_template_directory_uri() . '/js/theme/loadmore-posts.js', array( 'jquery' ), '0.1', true );
        wp_localize_script( 'load-more-posts-js', 'wp_ajax_url', admin_url( 'admin-ajax.php' ) );
    }

    /**
     * Spit out the button html
     * @param $text. (optional) Text to display on button
     * @param $paged. (optional) WP query var
     * @return void
     */
    public function load_more_button( $query, $context, $text, $paged ) {
        if( $query === null ){
            global $wp_query;
            $query = $wp_query;
        }

        if( $query->max_num_pages < 2 ){
            return false;
        }

        // Lets recreate the current query within our ajax call
        wp_localize_script( 'load-more-posts-js', 'load_more_data', array( 'query' => $query->query ) );
        // echo '<div id="load-more-posts-area-wrapper" class="loadmore--' . $context . '"></div>';
        wp_nonce_field( 'load-more-posts-nonce-' . $context, 'load-more-posts-nonce' );
        echo '<div class="load-more-posts-error error" style="display:none;">' . esc_html__( 'Something has gone wrong. Please try again.', 'load-more-posts' ) . '</div>';
        echo '<button class="cta cta--hasloader js-loadmore-posts" data-context="'.esc_attr__( $context, 'load-more-posts' ).'" data-paged="'.esc_attr__( $paged, 'load-more-posts' ).'" data-max-pages="'. $query->max_num_pages.'">'.esc_html__( $text, 'load-more-posts' ).'</button>';
    }
    /**
     * Ajax handler for load more posts
     */
    public function load_more_posts() {
        if ( empty( $_POST['nonce'] ) || empty( $_POST['paged'] ) || ! wp_verify_nonce( $_POST['nonce'], 'load-more-posts-nonce-'  . $_POST['context'] ) ) {
            exit;
        } else {
            global $post; // required by setup post data
            $context = ( ! empty( $_POST['context'] ) ) ? sanitize_text_field( $_POST['context'] ) : 'default';
            $args = (array) $_POST['query'];
            $args['paged'] = sanitize_text_field( $_POST['paged'] );
            // A filter if you want to customize the query
            $args = apply_filters( 'load-more-posts-args-' . sanitize_text_field( $_POST['context'] ), $args );
            $args['post_status'] = 'publish';
            
            $query = new WP_Query( $args );

            if(function_exists('relevanssi_do_query') && $query->is_search()){
                $posts = relevanssi_do_query($query);
            } else {
                $posts = $query->get_posts();
            }

            foreach( $posts as $post ) {
                setup_postdata( $post );
                get_template_part( 'content', $context );
                wp_reset_postdata();
            }
        }
        exit;
    }
}
new SK_Load_More_Posts();
endif;