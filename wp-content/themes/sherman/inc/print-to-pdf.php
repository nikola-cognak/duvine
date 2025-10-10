<?php

/* ========================================================
 *
 * Print to PDF functions
 *
 * ======================================================== */

if( !function_exists('render_itinerary_day') ) :
function render_itinerary_day($daynumber = 0, $heightfix = 0) {

    $dayDescription = '<div class="itinerary__daydescription">';
    $dayDescription .= wpautop(get_sub_field('description', $tour_id));
    $dayDescription .= '</div>';

    // the header
    $dayBlock = '<div class="itinerary__daymarker"><div class="itinerary__daymarker__inner">Day <div>'.$daynumber.'</div></div></div>';

    $dayTitle = '<div class="itinerary__title">'.$dayBlock.'<h4 class="accordion__title"';
    if( $heightfix === 1 ) {
        if( strlen(get_sub_field('title', $tour_id)) < 53 ) :
            $dayTitle .= ' style="line-height:54px;"';
        else :
            $dayTitle .= ' style="line-height:28px;"';
        endif;
    }
    $dayTitle .= '>';
    $dayTitle .= get_sub_field('title', $tour_id);
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
