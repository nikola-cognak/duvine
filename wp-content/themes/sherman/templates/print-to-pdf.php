<?php
/*
Template Name: Print to PDF
*/
/**
 * The template for printing a tour to pdf.
 *
 * @package sherman
 */

header("X-Robots-Tag: noindex, nofollow", true);

//////////////////////////////////////////////////////////////////////
/* 
 * This template expects a tour_id passed in the query string.
 * The tour_id is the WordPress post id
 * It pulls in data from the passed tour id
 * There is also hard coded content in this template
 * May eventually remove hard coded content and add to the CMS
 * Script uses mpdf to generate a pdf file (https://mpdf.github.io/)
 *
 * NOTE: mpdf has some limitations with CSS, so some of the HTML is
 * done with tables and br tags
 */
//////////////////////////////////////////////////////////////////////
global $post;

if ( post_password_required( $post ) ) {
    echo get_the_password_form( $post );
} else {

    require get_template_directory() . '/inc/print-to-pdf.php';

    $stylesheet = file_get_contents(dirname( __FILE__ ).'/../styles/mpdf-style.css');

    require_once dirname( __FILE__ ).'/../mpdf/vendor/autoload.php';

    $upload_dir = wp_upload_dir();
    $temp_dir = $upload_dir['path'] . '/mpdf';

    // for custom fonts
    $defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
    $fontDirs = $defaultConfig['fontDir'];

    $defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
    $fontData = $defaultFontConfig['fontdata'];

    $mpdf = new \Mpdf\Mpdf([
        'tempDir' => $temp_dir, 
        'margin_left' => 0,
        'margin_right' => 0,
        'margin_top' => 0,
        'margin_bottom' => 0,
        'mode' => 'utf-8',
        'format' => [215, 279], // 8.5" x 11", converted to mm
        'fontDir' => array_merge($fontDirs, [
            dirname( __FILE__ ) . '/../styles/fonts',
        ]),
        'fontdata' => $fontData + [
            'gotham' => [
                'R' => 'gotham-book.ttf',
                'B' => 'gotham-bold.ttf',
            ],
            'chronicle' => [
                'R' => 'ChronicleDisp-Bold.ttf',
                'B' => 'ChronicleDisp-Bold.ttf',
            ]
        ],
        'default_font' => 'gotham'
    ]);

    $mpdf->SetCompression(false);
    $mpdf->useSubstitutions = true;
    $mpdf->showImageErrors = true;
    $mpdf->useActiveForms = true; // for checkboxes

    //error_log('TEMP DIR PATH: '.$temp_dir);

    /*****************************************************
     *
     * for page footers and headers
     * called in dynamically depending on page/section
     *
     *****************************************************/
    $mpdf->DefHTMLFooterByName('firstPageFooter','<div class="copyright" style="padding-left:50px;color:#adafb2;">&copy; '.date('Y').' DuVine Adventure + Cycling Co.</div>');
	
	$mpdf->DefHTMLFooterByName('blankFooter','');

    $mpdf->DefHTMLFooterByName('pageFooter','<div class="footer-logo" style="width: 100%;padding-right:50px;height:16px;margin:0;"><img src="wp-content/themes/sherman/img/print-to-pdf/duvine-logo-footer-new.png'.'" width="70" height="21" style="width:70px;height:21px;float:right;" /></div>');

    $copy_disclaimer = '';
    if( get_field('misc_copy_disclaimer', 'option') ) {
        $copy_disclaimer = get_field('misc_copy_disclaimer', 'option');
    }

    $footerHTML = '<table style="width:100%;margin:0;border-collapse:collapse;">
        <tr>
            <td align="left" valign="top" style="padding-left:50px;color:#adafb2;font-size: 12px;line-height:14px;">'.$copy_disclaimer.'</td>
            <td align="right" valign="top" style="padding-right:50px;vertical-align:middle;"><img src="wp-content/themes/sherman/img/print-to-pdf/duvine-logo-footer-new.png'.'" width="70" height="21" style="width:70px;height:21px;float:right;" /></td>
        </tr>
    </table>';
    $mpdf->DefHTMLFooterByName('itineraryFooter',$footerHTML);

    $mpdf->DefHTMLHeaderByName('itineraryHeader','<div class="itinerary-header"><img src="wp-content/themes/sherman/img/print-to-pdf/tour-itinerary.png" /></div>');

    $mpdf->DefHTMLHeaderByName('packingListHeader','<div class="packing-list-header"><img src="wp-content/themes/sherman/img/print-to-pdf/your-packing-list.png" /></div>');


    /*****************************************************
     *
     * Expecting tour_id in the query string. This is
     * the tour's post id
     *
     *****************************************************/
    $tour_id = $_GET['tour_id'];

    if( !get_post($tour_id) ) {
        error_log('---------Invalid tour id passed in query string---------');
        $html = '<h1>Invalid tour ID</h1>';
        $mpdf->WriteHTML($html,\Mpdf\HTMLParserMode::HTML_BODY);
        $mpdf->Output();
        exit;
    }


    /*****************************************************
     *
     * Start: Section 1
     *
     *****************************************************/

    // top banner area
    $html = '<div class="top-header">';
    $html .= '<table style="width:100%;margin:30px 0;border-collapse: collapse;">';
    $html .= '<tr>';
    $html = $html.'<td width="50%" align="left" valign="top" style="padding:0; margin:0;"><img src="'.get_template_directory().'/img/print-to-pdf/duvine-logo.png'.'" style="width:236px;height:67px;vertical-align:top;" /></td>';
    $html .= '<td width="50%" align="right" valign="top" style="padding:0; margin:0;color:#ffffff;text-align:right;">';
    $html .= '+1 888 396 5383<br />617 776 4441<br /><a href="mailto:team@duvine.com" style="">team@duvine.com</a><br /><a href="https://duvine.com" style="">DuVine.com</a>';
    $html .= '</td>';
    $html .= '</tr>';
    $html .= '</table>';
    $html .= '</div>';

    //error_log($html);

    // main tour image
    $header_img = get_field('d_hero_image',$tour_id);
    if( $header_img ) :
        $imageUrl = str_replace(get_site_url() . '/', '', $header_img['url']);
        $html .= '<img src="'.$imageUrl.'" style="width: 100%;;height:auto;" />';
    endif;

    // itinerary image
    $img = '<img src="'.get_template_directory().'/img/print-to-pdf/tour-itinerary.png'.'" />';

    // level image
    $level = get_field('d_cycling_level',$tour_id);
    $img2 = '<img src="'.get_template_directory() . '/img/print-to-pdf/riding-level-'.$level['value'].'--circle.png'.'" style="width:100px;height:auto;" />';

    $html = $html.'<table style="width:100%;margin:30px 0 0;border-collapse: collapse;">';
    $html = $html.'<tr>';
    $html = $html.'<td width="50%" align="left" valign="top" style="padding:0; margin:0;">'.$img.'</td>';
    $html = $html.'<td width="50%" align="right" valign="top" style="padding:0; margin:0;padding-right:50px;">'.$img2.'</td>';
    $html = $html.'</tr>';
    $html = $html.'</table>';

    // margin for upcoming content
    $html = $html.'<div class="side-margin">';

    // country/region
    $locations = get_field('d_tour_location', $tour_id);

    if( $locations ){
        $i = 0;
        $len = count($locations);
        foreach( $locations as $locID ){
            $locTitle = get_the_title( $locID );
            $breadcrumbs .= '<span>';
            $breadcrumbs .= $locTitle;
            $breadcrumbs .= '</span>';
            if ($i !== $len - 1) {
                $breadcrumbs .= '<span> / </span>';
            }
            ++$i;
        }

        $html = $html.$breadcrumbs;
    }

    // Tour name
    $html = $html.'<h1>'.get_the_title($tour_id).'</h1>';

    // Subtitle
    $html = $html.'<h3 class="subtitle">'.get_field('d_tour_subtitle', $tour_id).'</h3>';

    // end side margin
    $html = $html.'</div>';

    $mpdf->SetHTMLFooterByName('firstPageFooter');

    ob_clean();

    $mpdf->WriteHTML($stylesheet,\Mpdf\HTMLParserMode::HEADER_CSS);
    $mpdf->WriteHTML($html,\Mpdf\HTMLParserMode::HTML_BODY);

    /*****************************************************
     *
     * Start: Tour Highlights
     *
     *****************************************************/

    // note that the '10' is the top margin in mm, and new footer as well
    $mpdf->AddPage('P','','','','','','','10','','','','','','html_pageFooter', '', 1, 0, 1, 0);

    // itinerary image
    $img = '<img src="'.get_template_directory().'/img/print-to-pdf/tour-highlights.png'.'" />';

    $html = $img;

    // Tour highlights
    $highlights = '<div class="print-highlights">'.get_field('d_tour_unique', $tour_id).'</div>';
    $html = $html.$highlights;

    $images = get_field('d_tour_gallery', $tour_id);
    $gallery = '';

    if( $images ): 
        $gallery = '<div class="print-gallery">';
        $cnt = 1;
        foreach( $images as $image ):
            $imageUrl = str_replace(get_site_url() . '/', '', $image['url']);
            if( $cnt !== 3 ) :
                $gallery = $gallery.'<img src="'.$imageUrl.'" style="height:273px;margin-bottom:20px;';
                if( $cnt == 1 || $cnt == 4 ) $gallery = $gallery.'margin-right:20px;';
                $gallery = $gallery.'" />';
            else :
                // for use on the next page
                $third_img = '<img src="'.$imageUrl.'" style="" />';
            endif;
            ++$cnt;
        endforeach;

        $gallery = $gallery.'</div>';

    else :

        error_log('No gallery photos');

    endif;

    $html = $html.$gallery;

    ob_clean();

    $mpdf->WriteHTML($html,\Mpdf\HTMLParserMode::HTML_BODY);


    /*****************************************************
     *
     * Start: Arrival/Departure
     *
     *****************************************************/

    // note that the '10' is the top margin in mm
    $mpdf->AddPage('P','','','','','','','10');

    // itinerary image
    $img = '<img src="'.get_template_directory().'/img/print-to-pdf/tour-arrival-departure.png'.'" />';

    $html = $img;

    // arrival/departure
    $arrival_city = get_field('d_itinerary_arrival_city', $tour_id);
    $arrival_location = get_field('d_itinerary_arrival_location', $tour_id);
    $arrival_time = get_field('d_itinerary_arrival_time', $tour_id);
    $departure_city = get_field('d_itinerary_departure_city', $tour_id);
    $departure_location = get_field('d_itinerary_departure_location', $tour_id);
    $departure_time = get_field('d_itinerary_departure_time', $tour_id);

    $html = $html.'<div class="side-margin">';
    $html = $html.'<table style="width:100%;margin:30px 0;border-collapse: collapse;">';
    $html = $html.'<tr>';
    $html = $html.'<td width="50%" align="left" valign="top" style="padding:0 5px 7px; margin:0;">';
    $html = $html.'<table style="width:100%;border-collapse: collapse;">';
    $html = $html.'<tr>';
    $html = $html.'<td width="13" align="left" valign="middle">&nbsp;</td>';
    $html = $html.'<td align="left" valign="top"><h4>Arrival Details</h4></td>';
    $html = $html.'</tr>';
    $html = $html.'</table>';
    $html = $html.'</td>';
    $html = $html.'<td width="50%" align="left" valign="top" style="padding:0 5px; margin:0;">';
    $html = $html.'<table style="width:100%;border-collapse: collapse;">';
    $html = $html.'<tr>';
    $html = $html.'<td width="13" align="left" valign="middle">&nbsp;</td>';
    $html = $html.'<td align="left" valign="top"><h4>Departure Details</h4></td>';
    $html = $html.'</tr>';
    $html = $html.'</table>';
    $html = $html.'</td>';
    $html = $html.'</tr>';

    $html = $html.'<tr>';
    $html = $html.'<td width="50%" align="left" valign="top" style="padding:0 5px 7px; margin:0;">';
    $html = $html.'<table style="width:100%;border-collapse: collapse;">';
    $html = $html.'<tr>';
    $html = $html.'<td width="13" align="left" valign="middle" style="padding-right: 5px;"><img src="'.get_template_directory().'/img/print-to-pdf/airplane.png'.'" /></td>';
    $html = $html.'<td align="left" valign="top"><strong>Airport City</strong>:<br />'.$arrival_city.'</td>';
    $html = $html.'</tr>';
    $html = $html.'<tr>';
    $html = $html.'<td width="13" align="left" valign="middle" style="padding-right: 5px;"><img src="'.get_template_directory().'/img/print-to-pdf/map-icon.png'.'" /></td>';
    $html = $html.'<td align="left" valign="top"><strong>Pick-Up Location</strong>:<br />'.$arrival_location.'</td>';
    $html = $html.'</tr>';

    if( strlen($arrival_time) > 0 ) :
        $html = $html.'<tr>';
        $html = $html.'<td width="13" align="left" valign="middle" style="padding-right: 5px;"><img src="'.get_template_directory().'/img/print-to-pdf/clock.png'.'" /></td>';
        $html = $html.'<td align="left" valign="top"><strong>Pick-Up Time</strong>:<br />'.$arrival_time.'</td>';
        $html = $html.'</tr>';
    endif;
    $html = $html.'</table>';
    $html = $html.'</td>';
    $html = $html.'<td width="50%" align="left" valign="top" style="padding:0; margin:0;">';

    $html = $html.'<table style="width:100%;border-collapse: collapse;">';
    $html = $html.'<tr>';
    $html = $html.'<td width="13" align="left" valign="middle" style="padding-right: 5px;"><img src="'.get_template_directory().'/img/print-to-pdf/airplane.png'.'" /></td>';
    $html = $html.'<td align="left" valign="top"><strong>Airport City</strong>:<br />'.$departure_city.'</td>';
    $html = $html.'</tr>';
    $html = $html.'<tr>';
    $html = $html.'<td width="13" align="left" valign="middle" style="padding-right: 5px;"><img src="'.get_template_directory().'/img/print-to-pdf/map-icon.png'.'" /></td>';
    $html = $html.'<td align="left" valign="top"><strong>Drop-Off Location</strong>:<br />'.$departure_location.'</td>';
    $html = $html.'</tr>';

    if( strlen($departure_time) > 0 ) :
        $html = $html.'<tr>';
        $html = $html.'<td width="13" align="left" valign="middle" style="padding-right: 5px;"><img src="'.get_template_directory().'/img/print-to-pdf/clock.png'.'" /></td>';
        $html = $html.'<td align="left" valign="top"><strong>Drop-Off Time</strong>:<br />'.$departure_time.'</td>';
        $html = $html.'</tr>';
    endif;

    $html = $html.'</table>';
    $html = $html.'</td>';
    $html = $html.'</tr>';
    $html = $html.'</table>';

    $html = $html.'<div class="straight-talk">';
    $html = $html.'<div class="straight-talk-footnote"><strong>NOTE:</strong> DuVine provides group transfers to and from the tour from the pick-up and drop-off locations stated on the itinerary. In the event your train, flight, or other travel falls outside the arrival or departure times or locations, you may be responsible for extra costs incurred in arranging a separate transfer.</div>';
    $html = $html.'</div>';

    $html = $html.'<hr />';

    $html = $html.'<div class="straight-talk">';
    $html = $html.'<h4>Emergency Assistance</h4>';
    $html = $html.'<div class="straight-talk-body">For urgent assistance on your way to tour or while on tour, please always contact your guides first. You may also contact the Boston office during business hours at +1 617 776 4441 or <a href="mailto:emergency@duvine.com">emergency@duvine.com</a>.</div>';
    $html = $html.'</div>';
    //$html = $html.'<hr />';
    if( get_field('d_tour_always_unique_header', $tour_id) && get_field('d_tour_always_unique', $tour_id) ) :
        $html = $html.'<hr />';
        $html = $html.'<div class="straight-talk">';
        $html = $html.'<h4>'.get_field('d_tour_always_unique_header', $tour_id).'</h4>';
        $html = $html.'<div class="straight-talk-body">'.get_field('d_tour_always_unique', $tour_id).'</div>';
    endif;

    if( strlen(get_field('d_tour_always_unique', $tour_id)) < 450 ) :
        // image
        $html = $html.'<div>'.$third_img.'</div>';

    endif;

    // end margin
    $html = $html.'</div>';

    ob_clean();

    $mpdf->WriteHTML($html,\Mpdf\HTMLParserMode::HTML_BODY);


    /*****************************************************
     *
     * Start: Itinerary/Tour Days
     *
     *****************************************************/

    // weird alignment bug for day title...only happens on first day on page, except for first page
    $title_height_fix = 0;

    // note that the '30' is the top margin in mm
    $mpdf->AddPage('P','','','','','','','30','','','','html_itineraryHeader','','html_blankFooter', '', 1, 0, 1, 0);

    $html = '<div class="side-margin">';

    $html = $html.'<h3>Tour By Day</h3>';

    $html = $html.'</div>';

    ob_clean();

    $mpdf->WriteHTML($html,\Mpdf\HTMLParserMode::HTML_BODY);

    $html = '';

    // now add the 'days' of the tour...will show 2 days/PDF page
    $itineraryDayCount = 1;
    $totalTourDays = count(get_field('d_intinerary', $tour_id));
    if( get_field('d_itinerary_pre_tour', $tour_id) ) ++$totalTourDays;
    if( get_field('d_itinerary_post_tour', $tour_id) ) ++$totalTourDays;

    // see if pre-day
    if( get_field('d_itinerary_pre_tour', $tour_id) ) {
        //error_log('found pre tour');
        $html = '<div class="side-margin">';
        $html .= render_optional_itinerary('pre',$tour_id);
        $html .= '</div>';

        ob_clean();

        $mpdf->WriteHTML($html,\Mpdf\HTMLParserMode::HTML_BODY);
        $html = '';
        ++$itineraryDayCount;
    }

    // Tour by Day
    $itineraryDay = 1;
    while( have_rows( 'd_intinerary', $tour_id ) ) {
        the_row();
        $html .= '<div class="side-margin">';
        $day = render_itinerary_day($itineraryDay, $title_height_fix);
        $title_height_fix = 0;
        $html .= $day;
        $html .= '</div>';
        if( $itineraryDayCount % 2 == 0 ) :
            ob_clean();
            $mpdf->WriteHTML($html,\Mpdf\HTMLParserMode::HTML_BODY);
            $html = '';
			if ( $totalTourDays != $itineraryDayCount ) {
				// start a new page
				if( ($totalTourDays - $itineraryDayCount == 1) || ($totalTourDays - $itineraryDayCount == 2) ) {
					$mpdf->AddPage('P','','','','','','','30','','','','html_itineraryHeader','','html_itineraryFooter', '', 1, 0, 1, 0);
				} else {
					$mpdf->AddPage('P','','','','','','','30','','','','html_itineraryHeader','','html_blankFooter', '', 1, 0, 1, 0);
				}
				$title_height_fix = 1;
			}
        endif;
        ++$itineraryDay;
        ++$itineraryDayCount;
    }

    // post tour
    if( get_field('d_itinerary_post_tour', $tour_id) ) {
        $html .= '<div class="side-margin">';
        $html .= render_optional_itinerary('post',$tour_id,$title_height_fix);
        $title_height_fix = 0;
        $html .= '</div>';
    }

    ob_clean();
    $mpdf->WriteHTML($html,\Mpdf\HTMLParserMode::HTML_BODY);


    /*****************************************************
     *
     * Start: Your Tour Details (1)
     *
     *****************************************************/

    $mpdf->AddPage('P','','','','','','','10','','','','','','html_pageFooter', '', -1, 0, 1, 0);

    // itinerary image
    $img = '<img src="'.get_template_directory().'/img/print-to-pdf/your-tour-details.png'.'" />';

    $html = $img;

    $html .= '<div class="side-margin">';
    $html .= '<h3>Preparing for Your Tour</h3>';
    $html .= '<strong>Travel Services</strong><br />';
    $html .= 'DuVine can assist with the following reservations for up to three days before and after your tour.<br />
        <ul class="regular-list" style="margin-top: 10px;margin-bottom:10px;">
        <li>Pre and post-trip hotels in all major cities, in addition to the first and last on-tour hotels</li>
        <li>Private transfers</li>
        </ul>
		DuVine\'s travel advisor partners are happy to assist with flight reservations. If you are not already working with an agent, please request a referral from your Tour Coordinator.<br /><br />
    <strong>Travel Protection</strong><br />
    DuVine offers a <a href="https://www.duvine.com/trip-essentials/travel-protection/">Travel Protection Plan</a> to help protect your travel investment, your belongings,
    and most importantly, you. The offered Travel Protection Plan is non-refundable, but it is strongly recommended in the unfortunate event that you have to cancel or leave your trip.
    <div class="footnote">NOTE: Plan benefits, limits, and provisions may vary by state or jurisdiction. In order to receive full benefits, Travel Protection must be purchased within 21 days of initial tour deposit. This plan is available to citizens of the U.S. and Canada only.</div>
    <strong>Gratuity</strong><br />
    Gratuities are much appreciated to thank DuVine guides for exceptional service, support, and expertise. The industry standard is for each guest to tip 10-15% (U.S. tours and Cycle + Sail tours) or 7.5-10% (all other tours) of their trip price. The recommended per-traveler amount is an appropriate gratuity for your guide team as a whole. (It is not necessary to tip this amount <em>per</em> guide.) Tips are customary at the end of your tour, and local currency is always preferred. We recommend bringing extra cash or visiting the ATM at the beginning of your trip. For alternate methods of tipping (including Venmo, PayPal, and TransferWise), <a href="https://www.duvine.com/trip-essentials/faqs/#Tour" target="_blank">please see our FAQs</a>.';

    $gallery = '<div class="details-gallery">';

    // left side image
    $gallery = '<div style="float:left;width:50%;margin-top:25px;margin-right:0;">';
    $gallery = $gallery.'<img src="'.get_template_directory().'/img/print-to-pdf/your-tour-details-img1.png'.'" />';
    $gallery .= '</div>'; // end left side image

    $gallery .= '<div style="float:left;width:50%;">';

    $gallery = $gallery.'<img src="'.get_template_directory().'/img/print-to-pdf/your-tour-details-img2.png'.'" />';

    $gallery .= '<br /><br />';

    $gallery = $gallery.'<div class="social">';
    $gallery = $gallery.'<h3>Share Your<br>Experience</h3>';

    $gallery = $gallery.'<ul class="checkbox-list">';
    $gallery = $gallery.'<li>'.'<img src="'.get_template_directory().'/img/print-to-pdf/facebook.png'.'" />'.'&nbsp;<a href="https://facebook.com/duvine">fb.com/duvine</a></li>';
    $gallery = $gallery.'<li>'.'<img src="'.get_template_directory().'/img/print-to-pdf/instagram.png'.'" />'.'&nbsp;<a href="https://www.instagram.com/duvine/">@duvine</a></li>';
    $gallery = $gallery.'<li>'.'<img src="'.get_template_directory().'/img/print-to-pdf/twitter.png'.'" />'.'&nbsp;<a href="https://twitter.com/DuVine">@duvine</a></li>';
    $gallery = $gallery.'<li>'.'<img src="'.get_template_directory().'/img/print-to-pdf/hash.png'.'" />'.'&nbsp;#DuVine #DuVineStyle</li>';
    $gallery = $gallery.'</ul>';

    $gallery = $gallery.'</div>';

    $gallery .= '</div>';


    $gallery .= '</div>'; // end gallery

    $html .= $gallery;

    $html .= '</div>'; // end side margin

    ob_clean();
    $mpdf->WriteHTML($stylesheet,\Mpdf\HTMLParserMode::HEADER_CSS);
    $mpdf->WriteHTML($html,\Mpdf\HTMLParserMode::HTML_BODY);

    /*****************************************************
     *
     * Start: Your Tour Details (2)
     *
     *****************************************************/

    $mpdf->AddPage('P','','','','','','','10','','','','','','html_pageFooter', '', -1, 0, 1, 0);

    // itinerary image
    $img = '<img src="'.get_template_directory().'/img/print-to-pdf/your-tour-details.png'.'" />';

    $html = $img;

    $html .= '<div class="side-margin">';
    $html .= '<br /><strong>Bikes</strong><br />
    DuVine\'s top-of-the-line bikes are tuned to perfection and fit specifically to you. Our performance bikes from premier manufacturers feature light frames, smooth-rolling tires, comfortable seats, and wide gear ranges for the best ride possible. Please see our website for the exact bike models offered on tour. Additionally, e-bikes are available on most DuVine tours. Electric assist provides an extra boost to your own pedal power, so you can ride longer distances, tackle tougher climbs, and maintain a faster pace. Electric assist is available on a first-come, first-served basis. Contact your Tour Coordinator if you are interested.<br /><br />
    <strong>Electrical Overseas</strong><br />
    If you\'re traveling abroad, you will most likely need an <a href="https://www.worldstandards.eu/electricity/plug-voltage-by-country/" target="_blank">adapter</a>, which allows your device’s plug to fit into foreign outlets. North American devices run on 110/125V electricity while the majority of the world runs on 220/240V. Converters and transformers change the voltage of electricity to match your device.<br /><br />
    <strong>Training</strong><br />
    First and foremost, get out on your bike and start logging some miles. Nothing compares to the real thing, but if you can\'t cycle outside, consider spin classes. Experience with the elements of wind, actual hills, terrain, etc will help with your comfort level (balance, unexpected conditions, etc.) on tour. Always remember, training is a gradual process—don\'t try to overdo it or push yourself when you aren\'t ready. However, the most important part of training is to enjoy your ride! <a href="https://www.duvine.com/why-duvine/levels/training-guides-download/" target="_blank">Download a training guide</a> based on your Tour Level.<br /><br />
    <strong>Travel Sustainably</strong><br />
        DuVine is committed to sustainable travel and has been a 100% carbon neutral company since 2022. While cycling is an inherently eco-conscious mode of travel, we continuously seek ways to lessen our footprint, whether it be biodegradable water bottles or compostable gear mailers. <a href="https://www.duvine.com/why-duvine/sustainable-travel/">Read more</a> about sustainability at DuVine.';

    $img = '<br /><br /><img src="'.get_template_directory().'/img/print-to-pdf/your-tour-details-img5.png'.'" style="width:100%;" />';

    $html .= $img;

    $html .= '</div>';

    ob_clean();
    $mpdf->WriteHTML($html,\Mpdf\HTMLParserMode::HTML_BODY);

    /*****************************************************
     *
     * Packing List
     *
     *****************************************************/

    $mpdf->AddPage('P','','','','','','','10','','','','html_packingListHeader','','html_pageFooter', '', 1, 0, 1, 0);


    $html = '<div class="side-margin">';
    $html = $html.'<div style="float:left;width:56%;padding-right:15px;padding-top:50px;background-color:#ffffff;">';
    $html = $html.'<strong>On The Bike</strong>';
    $html = $html.'<ul class="checkbox-list">';
    $html = $html.'<li><input type="checkbox" name="jerseys" value="1" /> Cycling jerseys or athletic shirts</li>';
    $html = $html.'<li><input type="checkbox" name="shorts" value="1" /> Cycling shorts</li>';
    $html = $html.'<li><input type="checkbox" name="shoes" value="1" /> Cycling shoes (if you bring your own pedals)</li>';
    $html = $html.'<li><input type="checkbox" name="sneakers" value="1" /> Sneakers (if you don\'t bring your own pedals)</li>';
    $html = $html.'<li><input type="checkbox" name="socks" value="1" /> Athletic socks</li>';
    $html = $html.'<li><input type="checkbox" name="warmers" value="1" /> Arm/leg warmers</li>';
    $html = $html.'<li><input type="checkbox" name="vest" value="1" /> Lightweight, waterproof, wind-resistant jacket or vest</li>';
    $html = $html.'<li><input type="checkbox" name="gloves" value="1" /> Bike gloves</li>';
    $html = $html.'<li><input type="checkbox" name="saddle" value="1" /> Bike saddle/cover (if you prefer your own)</li>';
    $html = $html.'</ul>';

    $html = $html.'<strong>Off The Bike</strong>';
    $html = $html.'<ul class="checkbox-list">';
    $html = $html.'<li><input type="checkbox" name="walk" value="1" /> Walking shoes</li>';
    $html = $html.'<li><input type="checkbox" name="hiking" value="1" /> Hiking shoes (if required)</li>';
    $html = $html.'<li><input type="checkbox" name="longshort" value="1" /> Long + short-sleeved shirts</li>';
    $html = $html.'<li><input type="checkbox" name="sweater" value="1" /> Sweater/jacket for evenings</li>';
    $html = $html.'<li><input type="checkbox" name="dinner" value="1" /> Dinner attire (dressy casual) + dress shoes</li>';
    $html = $html.'<li><input type="checkbox" name="swim" value="1" /> Swimsuit</li>';
    $html = $html.'</ul>';

    $html = $html.'<strong>Travel Items</strong><span style="color:#666666;">*</span>';
    $html = $html.'<ul class="checkbox-list">';
    $html = $html.'<li><input type="checkbox" name="travel" value="1" /> Travel confirmations + tickets for air, rail, etc.</li>';
    $html = $html.'<li><input type="checkbox" name="passport" value="1" /> Passport, including photocopy</li>';
    $html = $html.'<li><input type="checkbox" name="currency" value="1" /> Local currency</li>';
    $html = $html.'<li><input type="checkbox" name="guide" value="1" /> DuVine Tour Itinerary (with meeting + departing info)</li>';
    //$html = $html.'<li><input type="checkbox" name="contacts" value="1" /> Emergency contacts</li>';
    $html = $html.'<li><input type="checkbox" name="insurance" value="1" /> Health insurance information</li>';
    $html = $html.'<li><input type="checkbox" name="medications" value="1" /> Medications</li>';
    $html = $html.'<li><input type="checkbox" name="gear" value="1" /> Cycling gear (+ pedals) if riding on Day 1</li>';
    $html = $html.'</ul>';
    $html = $html.'<div style="color:#666666;font-size:12px;line-height:14px;padding-left:15px;margin-bottom:5px;">*We recommend keeping these items in your carry-on</div>';

    $html = $html.'<strong>Additional Items</strong>';
    $html = $html.'<ul class="checkbox-list">';
    $html = $html.'<li><input type="checkbox" name="cell" value="1" /> Cell phone + charger</li>';
    $html = $html.'<li><input type="checkbox" name="photography" value="1" /> Photography gear + charger</li>';
    $html = $html.'<li><input type="checkbox" name="adapter" value="1" /> Power/plug adapter</li>';
    $html = $html.'<li><input type="checkbox" name="toiletries" value="1" /> Toiletries</li>';
    $html = $html.'<li><input type="checkbox" name="sunscreen" value="1" /> Sunscreen, sunglasses, + other sun protection gear</li>';
    $html = $html.'<li><input type="checkbox" name="repellent" value="1" /> Insect repellent</li>';
    $html = $html.'</ul>';


    $html = $html.'<div style="width:100%;margin-top:20px;">';
    $html = $html.'<strong>What We Provide</strong>';
    $html = $html.'<div style="float:left;width:50%;padding:0 20px 0 0;">';
    $html = $html.'<ul class="regular-list">';
    $html = $html.'<li>Bike saddle</li>';
    $html = $html.'<li>GPS (where available)</li>';
    $html = $html.'<li>Flat or caged pedals</li>';
    $html = $html.'<li>DuVine t-shirt and jersey</li>';
    $html = $html.'</ul>';
    $html = $html.'</div>';
    $html = $html.'<div style="float:left;width:40%;padding:0 0 0 0;">';
    $html = $html.'<ul class="regular-list">';
    $html = $html.'<li>Helmet</li>';
    $html = $html.'<li>Water bottle</li>';
    $html = $html.'<li>Snacks/nutrition</li>';
    $html = $html.'<li>Drawstring bag</li>';
    $html = $html.'</ul>';
    $html = $html.'</div>';
    $html = $html.'</div>';


    $html = $html.'</div>';

    $html = $html.'<div style="float:left;width:36%;padding:20px;background-color:#f1f2f2;">';
    $html = $html.'<strong>Do I need to bring special gear?</strong>';
    $html = $html.'<ul class="regular-list">';
    $html = $html.'<li>Cycling shorts are designed to provide extra padding when spending the whole day in the saddle. Don\'t forget the chamois cream!</li>';
    $html = $html.'<li>If you bring your own cycling shoes, you must bring your own pedals. If you\'ve never used clip-in pedals before, we don\'t recommend using them on tour.</li>';
    $html = $html.'<li>Arm and leg warmers are essential for cooler weather. Wear them with 
       your short-sleeve jersey and shorts for lightweight, easy-to pack, and effective warmth.</li>';
    $html = $html.'<li>Bike gloves are a preference, but are recommended for Level 3 and 4 tours. The padding in gloves can ease arm, shoulder, and joint fatigue.</li>';
    $html = $html.'</ul>';

    $html = $html.'<br /><strong>Dressing for the Weather</strong>';
    $html = $html.'<ul class="regular-list">';
    $html = $html.'<li>The location and time of year of your tour can bring all kinds of weather, from extreme heat to relentless rain. Check extended forecasts before your trip and pack accordingly.</li>';
    $html = $html.'</ul>';

    $html = $html.'<br /><strong>Before You Go</strong>';
    $html = $html.'<ul class="regular-list">';
    $html = $html.'<li>Ensure your passport is valid for 3-6 months after return date.</li>';
    $html = $html.'<li>Call your bank/credit cards to notify them of upcoming travel.</li>';
    $html = $html.'<li>Please limit your luggage to one medium-sized suitcase and one carry-on.</li>';
    $html = $html.'</ul>';
    $html = $html.'</div>';

    $html = $html.'<div style="width:100%;margin-top:20px;">';
    $html = $html.'<strong>Order DuVine Gear</strong>';
    $html = $html.'<div>Want DuVine bike shorts to match your complimentary jersey? To order, contact <a href="mailto:tourcoordinators@duvine.com">tourcoordinators@duvine.com</a>. Gear is shipped free of charge to DuVine guests in the U.S. and Canada. For expedited shipping and guests outside of the U.S. and Canada, additional shipping fees will apply.</div>';
    $html = $html.'</div>'; // end order gear

    $html = $html.'</div>'; // side-margin

    ob_clean();
    $mpdf->WriteHTML($html,\Mpdf\HTMLParserMode::HTML_BODY);

    /*****************************************************
     *
     * Start: Bike Safety
     *
     *****************************************************/

    $mpdf->AddPage('P','','','','','','','10','','','','','','html_pageFooter', '', -1, 0, 1, 0);

    // itinerary image
    $img = '<img src="'.get_template_directory().'/img/print-to-pdf/bike-safety.png'.'" />';

    $html = $img;

    $html .= '<div class="side-margin">';
    $html .= '<br />We strongly suggest that you read these simple safety instructions. They will help you bike safely before and during your tour!<br /><br />
        <ul class="regular-list">
        <li>Wear a helmet—this is mandatory on tour!</li>
        <li>Familiarize yourself with traffic laws and obey them. Bikes do not have the right of way, but most drivers are respectful to cyclists on the road. Always ride in the direction of traffic.</li>
        <li>Be aware of your surroundings at all times. Knowing what is in front of and behind you may help prevent an accident.</li>
        <li>Alert other riders around you by announcing the presence of traffic ("car up") or pot holes ("pot hole").</li>
		<li>When riding as a group, maintain distance between yourself and other cyclists</li>
        <li>Learn the hand signals to indicate when you are stopping or turning.</li>
        <li>Operate your bike consistently and predictably—avoiding erratic movements.</li>
        <li>Keep at least one hand on the handlebars at all times.</li>
        <li>Primarily use your rear brake.</li>
        <li>Always be aware of weather and road conditions and how they may affect your ability to brake.</li>
        <li>Never wear headphones or use speakers during rides.</li>
        <li>Always listen to additional instructions from your guides on tour.</li>
        </ul>
    <br />If you have chosen an e-bike, be aware that it is slightly heavier, faster, and more powerful than a non-electric bike. Therefore, you should anticipate a different reaction when riding downhill, breaking, or dismounting from an e-bike. Your guides can offer further instruction during your bike fitting and safety review.';

    $img = '<br /><br /><img src="'.get_template_directory().'/img/print-to-pdf/bike-safety-img.png'.'" style="width:100%;" />';

    $html .= $img;

    $html .= '</div>';

    ob_clean();
    $mpdf->WriteHTML($html,\Mpdf\HTMLParserMode::HTML_BODY);


    /*****************************************************
     *
     * Start: Tour Quote (1)
     *
     *****************************************************/

    $mpdf->AddPage('P','','','','','','','10','','','','','','html_pageFooter', '', -1, 0, 1, 0);

    $img = '<img src="'.get_template_directory().'/img/print-to-pdf/your-tour-quote.png" />';

    $html = $img;

    $html .= '<div class="side-margin">';
    $html = $html.'<div style="float:left;width:57%;padding-right:15px;background-color:#ffffff;font-size:14px;">';
    $html = $html.'<h3>Private Tour Details</h3>';
    $html = $html.'<strong>Price</strong><br />';
    $html = $html.'Price below is in USD, per person, based on <span class="highlight">double</span> occupancy, and is valid until <span class="highlight">XX/XX/XX</span> (30 days from day proposal was sent):<br />';

    $html = $html.'<ul style="width:45%;vertical-align:text-top;list-style-type: none; float:left;">
    <li><strong>Group Size</strong></li>
    <li><span class="highlight">XX-XX</span> travelers</li>
    <li><span class="highlight">XX-XX</span> travelers</li>
    <li><span class="highlight">XX-XX</span> travelers*</li>
    </ul>
    <ul style="width:40%;vertical-align:text-top;list-style-type:none;float:left;margin-top:0px;">
    <li><strong>Tour Price</strong></li>
    <li><span class="highlight">$0,000</span></li>
    <li><span class="highlight">$0,000</span></li>
    <li><span class="highlight">$0,000</span></li>
    </ul>';

    $html = $html.'<div style="clear:both">Single supplement of <span class="highlight">$XXX</span> per person will be applied if a single room is requested. <span style="color:#666666;font-size:12px;line-height:14px;">*Should group size fall below this number, itinerary and pricing are subject to review.</span></div>';

    $html = $html.'<br /><strong>Included</strong> <span class="highlight">[Ensure all inclusions are indeed accurate. Items in black are standard, but still may always not apply]</span>';
    $html = $html.'<ul class="regular-list">
    <li>Accommodations:
    <ul class="nested">
    <li>Thoughtfully selected accommodations that reveal the true character of the region</li>
    <li>Luggage transfers</li>
    </ul>
    </li>
    <li>Meals:
    <ul class="nested">
    <li>Daily breakfasts, all lunches, nutritious snacks, and après velo cocktails</li>
    <li><span class="highlight">X</span> gourmet dinners at our favorite local restaurants and renowned culinary establishments</li>
    <li>Carefully selected local wine with every meal</li>
    </ul>
    </li>
    <li>Activities: 
    <ul class="nested">
    <li>Wine tastings and activities as outlined in the tour itinerary</li>
    <li>Entrance fees to historic sites, museums, parks, and all other exclusive events</li>
    <li>Gratuities for baggage, porters, and hotel service</li>
    </ul>
    </li>
    <li>Gear: 
    <ul class="nested">
    <li>Top-of-the-line bicycle selection and bike helmet</li>
    <li>Complimentary DuVine gear, including a custom cycling jersey, DuVine t-shirt, water bottle, and drawstring bag</li>
    <li>GPS in most destinations</li>
    </ul>
    </li>
    <li>Support: 
    <ul class="nested">
    <li>Expert bilingual guides with extensive local knowledge</li>
    <li>Support vehicle that follows the day\'s route, distributes refreshments, and offers lifts</li>
    <li>Pick-up and drop-off before and after your tour from predetermined meeting points</li>
    <li>Daily bike maintenance for optimal performance</li>
    </ul>
    </li>
    </ul>
    </div>'; //end left column div

    // start right column grey box content
    $html = $html.'<div style="float:left;width:40.5%;">';
    $html = $html.'<div style="padding:20px;background-color:#f1f2f2;font-size:14px;">';
    $html = $html.'<strong>Begins + Ends</strong><br />';
    $html = $html.'<span class="highlight">Start location / End location [as specific as possible]</span><br /><br />';
    $html = $html.'<strong>Difficulty Level</strong><br />';
    $html = $html.'<span class="highlight">[Level] </span><br /><br />';
    $html = $html.'<strong>Date</strong><br />';
    $html = $html.'<span class="highlight">[Start date] day of the week, month day, year – [Start date] day of the week, month day, year</span><br /><br />';

    $html = $html.'<strong>Terms & Conditions</strong><br />';
    $html = $html.'<ul class="regular-list">';
    $html = $html.'<li>Hotel selection based upon availability at time of confirmation</li>';
    $html = $html.'<li>USD $1,000 per person non-refundable deposit required</li>';
    $html = $html.'<li>Full Payment is due 90 days prior to start date</li>';
    $html = $html.'<li>Price will increase if group size drops below XX guests</li>';
    $html = $html.'<li>Cancellation Penalties as follows:';

    $html = $html.'<ul style="width:45%;vertical-align:text-top;list-style-type: none;float:left;padding-left:0;font-size:11px;">
    <li><strong>Days Prior to Trip</strong></li>
    <li>91+</li>
    <li>90-66</li>
    <li>65-31</li>
	<li>30-0</li>
    </ul>
    <ul style="width:55%;vertical-align:text-top;list-style-type:none;float:left;margin-top:0px;padding-left:0;font-size:11px;">
    <li><strong>Fee Per Person</strong></li>
    <li>$1,000</li>
	<li>50% of trip price</li>
	<li>75% of trip price</li>
    <li>100% of trip price</li>
    </ul>';

    $html = $html.'</li>';

    $html = $html.'<li>All bookings are subject to the <a href="https://www.duvine.com/trip-essentials/terms-and-conditions/">Terms + Conditions</a></li>';

    $html = $html.'</ul>';

    $html = $html.'<br /><strong>Not Included</strong><br />';
    $html = $html.'<ul class="regular-list">
    <li>Airfare</li>
    <li>Dinner on <span class="highlight">X</span> free night(s)</li>
	<li>E-bikes are offered at a $300 supplement</li>
    <li>Gratuities for DuVine guides</li>
    <li>Travel Protection</li>
    </ul>';

    $html = $html.'</div>'; // end grey right column
    $html = $html.'<div style="padding:0px 0 0 0;">'; // image under grey column
    $html = $html.'<img src="'.get_template_directory().'/img/print-to-pdf/group-selfie.jpg'.'" style="width:100%;" />';
    $html = $html.'</div>'; // end image under grey column
    $html = $html.'</div>'; // end right column

    $html = $html.'</div>'; // side-margin

    ob_clean();
    $mpdf->WriteHTML($html,\Mpdf\HTMLParserMode::HTML_BODY);

    // generate the PDF
    //$mpdf->Output();
    $customFilename = preg_replace("/[^A-Za-z0-9]/", "", get_the_title($tour_id));
    $customFilename = 'DuVine'.$customFilename.'.pdf';
    ob_clean();
    $mpdf->Output($customFilename, 'I');

} // end password required
?>