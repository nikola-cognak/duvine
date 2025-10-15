<?php
/**
 *
 * Functions to render the miscellaneous text fields from the CMS
 *
 */


if( !function_exists( 'duvine_text_contact_promo' ) ) :
/**
 * field - misc_contact_cta
 */
function duvine_text_contact_promo(){
    echo '<div class="privatepromo">';

    if( $copy_private_promo = get_field('misc_contact_cta', 'option') ){
        echo '<div class="calloutbanner__content d-content">'.$copy_private_promo.'</div>';
    } else {
        echo 'Any scheduled tour can be made private. Your group, your dates.';

        if( $pvt_tour_link = get_field('d_private_tours_page', 'option') ) {
            $pvt_tour_url = $pvt_tour_link['url'];
            echo "<br><a href=\"$pvt_tour_url\" class=\"basiclink\">Schedule your private tour.</a>";
        }
    }

    echo '</div>';
}
endif; // duvine_text_contact_promo

if( !function_exists( 'duvine_text_private_promo' ) ) :
/**
 * field - misc_copy_private_promo_tourpage
 */
function duvine_text_private_promo(){
    echo '<div class="privatepromo">';

    if( $copy_private_promo_header = get_field('misc_copy_header_private_promo_tourpage', 'option') ) :
        echo '<h3 class="tertiaryheader">'.$copy_private_promo_header.'</h3>';

    endif;

    if( $copy_private_promo = get_field('misc_copy_private_promo_tourpage', 'option') ){
        echo '<div class="calloutbanner__content d-content">'.$copy_private_promo.'</div>';
    } else {
        echo 'Any scheduled tour can be made private. Your group, your dates.';

        if( $pvt_tour_link = get_field('d_private_tours_page', 'option') ) {
            $pvt_tour_url = $pvt_tour_link['url'];
            echo "<br><a href=\"$pvt_tour_url\" class=\"basiclink\">Schedule your private tour.</a>";
        }
    }

    echo '</div>';
}
endif; // duvine_text_private_promo




if( !function_exists( 'duvine_text_tour_disclaimer' ) ) :
/**
 * field - misc_copy_disclaimer
 */
function duvine_text_tour_disclaimer(){
    echo '<div class="itinerary__disclaimer">';

    if( $copy_disclaimer = get_field('misc_copy_disclaimer', 'option') ) {
        echo $copy_disclaimer;
    } else {
        echo '<p>DuVine strives to consistently amaze, surprise, and delight. For this reason, each itinerary may be subject to slight modifications. Contact us to speak with a destination specialist for the most up to date tour inclusions.</p>';
    }

    echo '</div>';
}
endif; // duvine_text_tour_disclaimer

if( !function_exists( 'duvine_text_tour_arrival_departure_disclaimer' ) ) :
/**
 * field - misc_copy_arrival_destination_copy
 */
function duvine_text_tour_arrival_departure_disclaimer(){
    echo '<div class="itinerary__disclaimer" style="margin-top:0px">';

    if( $copy_arrival_departure_disclaimer = get_field('misc_copy_arrival_destination_copy', 'option') ) {
        echo $copy_arrival_departure_disclaimer;
    } else {
        echo '<p>Arrival and departure details for 2025 tours may be subject to change.</p>';
    }

    echo '</div>';
}
endif; // duvine_text_tour_arrival_departure_disclaimer

if( !function_exists( 'duvine_text_tour_2025_date_disclaimer' ) ) :
/**
 * field - misc_copy_2025_date_disclaimer_copy
 */
function duvine_text_tour_2025_date_disclaimer(){

    if( $copy_2025_disclaimer_disclaimer = get_field('misc_copy_2025_date_disclaimer_copy', 'option') ) {
        echo '<span class="note2025">';
        echo $copy_2025_disclaimer_disclaimer;
        echo '</span>';
    } 
}

endif; // misc_copy_2025_date_disclaimer_copy


if(!function_exists( 'duvine_text_call_to_book_space_limited')) {
  function duvine_text_call_to_book_space_limited()
  {
    echo '<div class="dateblock__calltobook">';
    if ($call_to_book = get_field('misc_copy_call_to_book', 'option')) {
      echo wpautop('Limited space. ' . $call_to_book);
    } else {
      $phone = duvine_get_phone_number();
      $formatted_phone = str_replace(' ', '-', $phone);
      echo 'Must call to book';
      if ($phone) {
        echo ': <br><a href="tel:' . $formatted_phone . '">$phone</a>';
      }
    }
    echo '</div>';
  }
}

if( !function_exists( 'duvine_text_call_to_book' ) ) :
/**
 * field - misc_copy_call_to_book
 */
function duvine_text_call_to_book(){
    
    echo '<div class="dateblock__calltobook">';
    if( $call_to_book = get_field('misc_copy_call_to_book', 'option') ){
        echo wpautop($call_to_book);
    } else {
        $phone = duvine_get_phone_number();
        $formatted_phone = str_replace(' ', '-', $phone);
        echo 'Must call to book';
        if( $phone ){
            echo ': <br><a href="tel:'.$formatted_phone.'">$phone</a>';
        }
    }

    echo '</div>';


}
endif; // duvine_text_call_to_book

if( !function_exists( 'duvine_text_30_days_call_to_book' ) ) :
/**
 * field - misc_copy_30_days_call_to_book
 */
function duvine_text_30_days_call_to_book(){
    
    echo '<div class="dateblock__calltobook">';
    if( $call_to_book = get_field('misc_copy_30_days_call_to_book', 'option') ){
        echo wpautop($call_to_book);
    } else {
        $phone = duvine_get_phone_number();
        $formatted_phone = str_replace(' ', '-', $phone);
        echo 'Must call to book';
        if( $phone ){
            echo ': <br><a href="tel:'.$formatted_phone.'">$phone</a>';
        }
    }

    echo '</div>';


}
endif; // duvine_text_30_days_call_to_book


if( !function_exists( 'duvine_get_text_call_to_book' ) ) :
/**
 * field - misc_copy_call_to_book
 */
function duvine_get_text_call_to_book(){
    
    if( $call_to_book = get_field('misc_copy_call_to_book', 'option') ){
        return $call_to_book;
    } else {
        return false;
    }

}
endif; // duvine_get_text_call_to_book




if( !function_exists( 'duvine_text_dates_coming_soon' ) ) :
/**
 * field - misc_copy_dates_coming_soon
 */
function duvine_text_dates_coming_soon($classes = 'tourdates__noscheduled'){
    echo "<div class=\"$classes\">";

    if( $copy_dates_coming_soon = get_field('misc_copy_dates_coming_soon', 'option') ) {
        echo $copy_dates_coming_soon;
    } else {
        $contact_page = get_field('d_contact_page', 'option');
        $contact_cta = $contact_page ? ' <br><br><a href="' . $contact_page['url'] . '" class="cta cta--hoverwhite">Contact us</a>' : '';
        echo '<p>Specific schedules for this tour are yet to be determined. Check back soon or contact us for more details.' . $contact_cta . '</p>';
    }

    echo '</div>';
}
endif; // duvine_text_dates_coming_soon



if(!function_exists('duvine_text_private_tour_date_dropdown')) :
/**
 * Private Tour date dropdown
 * field - misc_copy_private_tour_date_dropdown
 */
function duvine_text_private_tour_date_dropdown(){
    if($dropdown_text = get_field('misc_copy_private_tour_date_dropdown', 'option')){
		$collections = get_field('d_tour_collection');
		if (in_array(50658, $collections)) {
			$text = get_field('misc_copy_private_tour_date_dropdown_villas', 'option');
			echo "<div class=\"tourdates__privatepromo\">$text</div>";
		} else {
			echo "<div class=\"tourdates__privatepromo\">$dropdown_text</div>";
		}
    } elseif($pvt_tour_link = get_field('d_private_tours_page', 'option')){
        $pvt_tour_url = $pvt_tour_link['url'];
        echo '<div class="tourdates__privatepromo">';
        echo '<pThis tour is for private groups only. There are no scheduled dates.</p>';
        echo "<p><a href=\"$pvt_tour_url\" class=\"cta cta--hoverwhite\">Go Private</a></p>";
        echo '</div>';
    }
}
endif; // duvine_text_private_tour_date_dropdown




if(!function_exists('duvine_text_family_tour_date_dropdown')) :
/**
 * Family (Private) Tour date dropdown
 * field - misc_copy_family_tour_date_dropdown
 */
function duvine_text_family_tour_date_dropdown(){
    if($dropdown_text = get_field('misc_copy_family_tour_date_dropdown', 'option')){
		echo "<div class=\"tourdates__privatepromo\">$dropdown_text</div>";
    } elseif($pvt_tour_link = get_field('d_private_tours_page', 'option')){
        $pvt_tour_url = $pvt_tour_link['url'];
        echo '<div class="tourdates__privatepromo">';
        echo '<pThis tour is for private groups only. There are no scheduled dates.</p>';
        echo "<p><a href=\"$pvt_tour_url\" class=\"cta cta--hoverwhite\">Go Private</a></p>";
        echo '</div>';
    }
}
endif; // duvine_text_family_tour_date_dropdown




if(!function_exists('duvine_text_scheduled_tour_date_dropdown')) :
/**
 * Scheduled (Public) Tour date dropdown
 * misc_copy_scheduled_tour_date_dropwdown
 */
function duvine_text_scheduled_tour_date_dropdown(){
    if($dropdown_text = get_field('misc_copy_scheduled_tour_date_dropwdown', 'option')){
		$collections = get_field('d_tour_collection');
		if (in_array(50658, $collections)) {
			$text = get_field('misc_copy_private_tour_date_dropdown_villas', 'option');
			echo "<div class=\"tourdates__privatepromo\">$text</div>";
		} else {
			echo "<div class=\"tourdates__privatepromo\">$dropdown_text</div>";
		}
    } elseif( $pvt_tour_link = get_field('d_private_tours_page', 'option') ) {
        $pvt_tour_url = $pvt_tour_link['url'];
        echo '<div class="tourdates__privatepromo">';
        echo '<p>Any scheduled tour can be made private. Your group, your dates.</p>';
		echo "<p><a href=\"$pvt_tour_url\" class=\"cta cta--hoverwhite\">Go Private</a></p>";	
        echo '</dv>';
    }
}
endif; // duvine_text_scheduled_tour_date_dropdown
