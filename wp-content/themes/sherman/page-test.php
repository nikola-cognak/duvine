<?php /* Template Name: Jay Test */
get_header();


// get all tours from WordPress
$tours = get_posts(array(
    'post_type'      => 'tour',
    'posts_per_page' => -1,
    'meta_query' => array(
        array(
            'key' => 'd_private_only', // we don't want private tours
            'value' => '1',
            'compare' => '!='
        )
    )
));

foreach ( $tours as $post ) {
	
	echo the_field('d_tour_centaur_id', $post->ID);
	echo '<br>';
}

get_footer();
