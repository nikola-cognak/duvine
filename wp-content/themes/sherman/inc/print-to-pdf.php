<?php

/* ========================================================
 *
 * Print to PDF functions
 *
 * ======================================================== */

if( !function_exists('render_itinerary_day') ) :
function render_itinerary_day($daynumber = 0, $heightfix = 0, $tour_id = null) {

    // Debug: Log the raw description content for Tour 361
    if ($tour_id == 361) {
        $raw_description = get_sub_field('description', $tour_id);
        file_put_contents('/tmp/tour361_description_debug.log', 
            "Day $daynumber - Raw description length: " . strlen($raw_description) . "\n" .
            "Day $daynumber - Raw description content: " . substr($raw_description, 0, 500) . "...\n" .
            "Day $daynumber - wpautop result length: " . strlen(wpautop($raw_description)) . "\n" .
            "Day $daynumber - wpautop result preview: " . substr(wpautop($raw_description), 0, 500) . "...\n\n", 
            FILE_APPEND
        );
    }

    $dayDescription = '<div class="itinerary__daydescription">';
    
    // Get the description - use tour_id if provided, otherwise use current post context
    if ($tour_id) {
        $raw_description = get_sub_field('description', $tour_id);
    } else {
        $raw_description = get_sub_field('description');
    }
    
    // Clean up problematic HTML for Tour 361
    if ($tour_id == 361) {
        // Remove Google Analytics tracking parameters from URLs
        $raw_description = preg_replace('/\?_gl=[^"\s]*/', '', $raw_description);
        $raw_description = preg_replace('/\&_ga=[^"\s]*/', '', $raw_description);
        $raw_description = preg_replace('/\&_ga_[^"\s]*/', '', $raw_description);
        
        // Remove malformed CSS classes with long lists
        $raw_description = preg_replace('/class="[^"]*ui-provider[^"]*"/', 'class="clean-link"', $raw_description);
        
        // Clean up any remaining malformed class attributes
        $raw_description = preg_replace('/class="[^"]*a b c d e f g h i j k l m n o p q r s t u v w x y z[^"]*"/', 'class="clean-link"', $raw_description);
    }
    
    $dayDescription .= wpautop($raw_description);
    $dayDescription .= '</div>';

    // the header
    $dayBlock = '<div class="itinerary__daymarker"><div class="itinerary__daymarker__inner">Day <div>'.$daynumber.'</div></div></div>';

    $dayTitle = '<div class="itinerary__title">'.$dayBlock.'<h4 class="accordion__title"';
    if( $heightfix === 1 ) {
        $title_text = $tour_id ? get_sub_field('title', $tour_id) : get_sub_field('title');
        if( strlen($title_text) < 53 ) :
            $dayTitle .= ' style="line-height:54px;"';
        else :
            $dayTitle .= ' style="line-height:28px;"';
        endif;
    }
    $dayTitle .= '>';
    $dayTitle .= $tour_id ? get_sub_field('title', $tour_id) : get_sub_field('title');
    $dayTitle .= '</h4></div>';

    $dayContent = '<div class="itinerary__daywrapper">'. $dayTitle . $dayDescription . '</div>';

    return $dayContent;
}
endif;

if( !function_exists('render_optional_itinerary') ) :
function render_optional_itinerary( $preOrPost = null, $tour_id, $heightfix=0 ){
    if( $preOrPost === null || ($preOrPost !== 'pre' && $preOrPost !== 'post' )  ){
        error_log( print_r( "No optional itinerary indicated.", true ) );
        return false;
    }

    global $post;

    //$phoneNumber = str_replace(' ', '-', duvine_get_phone_number());
    //$calltobook = '<p><strong>To reserve call ' . duvine_get_phone_number() . '</strong></p>';

    $titleField = $preOrPost === 'pre' ? 'd_itinerary_pre_tour_title' : 'd_itinerary_post_tour_title';
    $imageField = $preOrPost === 'pre' ? 'd_itinerary_pre_tour_image' : 'd_itinerary_post_tour_image';
    $descriptionField = $preOrPost === 'pre' ? 'd_itinerary_pre_tour_description' : 'd_itinerary_post_tour_description';
    $priceField = $preOrPost === 'pre' ? 'd_itinerary_pre_tour_price' : 'd_itinerary_post_tour_price';
    $priceField = $preOrPost === 'pre' ? 'd_itinerary_pre_tour_price' : 'd_itinerary_post_tour_price';
    $durationField = $preOrPost === 'pre' ? 'd_itinerary_pre_tour_duration' : 'd_itinerary_post_tour_duration';

    $graybox_title = $preOrPost === 'pre' ? 'Pre-tour' : 'Post-tour' ;

    $title = '<h4 class="accordion__title"';
    if( $heightfix == 1 ) {
        $title .= ' style="line-height:54px;"';
    }
    $title .= '>';
    $title .= get_field($titleField, $tour_id);
    $title .= '</h4>';
 	
	if (get_field($priceField, $tour_id) != '') {
		$price = '<p><strong>Price Per Person: </strong>';
		$price = $price.'$'.number_format(get_field($priceField, $tour_id));
		$price .= '</p>';
	} else {
		$price = '';
	}
    

    $description = '<div class="itinerary__daydescription">';
    $description .= get_field($descriptionField, $tour_id);
    $description = $description . $price . '</div>';

    // title
    $header = '<div class="itinerary__title">';
    $header .=  '<div class="itinerary__daymarker itinerary__daymarker--gray"><div class="itinerary__daymarker__inner">' . $graybox_title . '</div></div>';
    $header .=  $title;
    $header .= '</div>';

    // body
    $content = '<div class="itinerary__daywrapper">' . $header . $description . '</div>';

    return $content;


}
endif;
