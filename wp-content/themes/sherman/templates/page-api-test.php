<?php
// use global sag object to get tour info from API
$client = $GLOBALS['sagittarius'];

if( gettype($client) !== 'object' ) :
  return 'Unable to create Sagittarius object in Sync.';
  exit;
endif;

ini_set("error_log", $client->error_log);

$endpoint = get_option('sagittarius_plugin_options');

if( !$client->isValidEndpoint($endpoint['text_string']) ) :
  error_log('Invalid Endpoint');
  exit;
endif;

// get all tours from WordPress
$tours = get_posts( array(
    'post_type'      => 'tour',
    'posts_per_page' => -1
));

if( count($tours) < 1 ) :
  error_log('No WordPress tours found.');
  exit;
endif; 

error_log('WordPress tour count: '.count($tours));

$tour_str = '';
$dates_str = '';

$i = 0;
$len = count($tours);

foreach($tours as $post) : setup_postdata($post);
  //var_dump($tour);
  $centaur_tour_id = trim(get_field('d_tour_centaur_id'));
  $tour_number = substr($centaur_tour_id, 0, 6);
  $tour_code = substr($centaur_tour_id, 6);
  $post_title = get_the_title();

  // no centaur ID in this tour CPT
  if( strlen($centaur_tour_id) < 1 ) :
    error_log('No Centaur id found for: '.$post_title);
    ++$i;
    continue;
  else :
    //error_log('starting: <b>'.$centaur_tour_id);
  endif;

  // get all tour info from API for this tour
  $tour_info = $client->getTourInfoFromAPI( $tour_number, $tour_code );

  $api_tour_number = '';
  $api_tour_code = '';

  $dates_str = "\t\t".'<dates>';
  $current_tour_id = '';

  $single_date_tour = array();
  $single_date_tour_inventory = array();
  

  if( $tour_info !== false) {
    foreach ($tour_info['Tours'] as $key => $value) {

      // check to see if this tour only has one date...if only one date, 
      // we have to manually build out array for this tour before passing to next foreach
      if( !is_array($value) ) {
        $single_date_tour[$key] = $value;
        continue;
      } elseif( $key == 'TotalRoomsInventory' && count($value) == 3 ) {
        // this will be the last fields for this tour, so add the pass final value to '$value' in order to get parsed into XML
        $single_date_tour_inventory = $single_date_tour;
        $single_date_tour_inventory[$key]['TotalRooms'] = $value['TotalRooms'];
        $single_date_tour_inventory[$key]['AvailableInventory'] = $value['AvailableInventory'];
        $single_date_tour_inventory[$key]['ThresholdInventory'] = $value['ThresholdInventory'];
        
        // we have complete data for this tour with single data, so pass on to next foreach
        $value = $single_date_tour_inventory;
      }

      // if we have more than one tour date we will skip the previous if/else and build out XML for this tour
      foreach ($value as $key2 => $value2) {

        if( $key2 === 'TourNumber' && $api_tour_number == '' ) {
          $api_tour_number = trim($value2);
          

        } elseif( $key2 === 'TourCode' && $api_tour_code == '' ) {
          $api_tour_code = trim($value2);
          

        } elseif( $key2 === 'TourDate' ) {
          $dates_str = $dates_str."\n"."\t\t\t".'<date>'."\n"."\t\t\t\t".'<TourDate>'.$value2.'</TourDate>'."\n";


        } elseif( $key2 === 'ReturnDate' ) {
          $dates_str = $dates_str."\t\t\t\t".'<ReturnDate>'.$value2.'</ReturnDate>'."\n";


        } elseif( $key2 === 'DepDateText' ) {
          $dates_str = $dates_str."\t\t\t\t".'<DepDateText>'.$value2.'</DepDateText>'."\n";


        } elseif( $key2 === 'PriceDetailsTwinList' ) {
          $dates_str = $dates_str."\t\t\t\t".'<PriceDetailsTwinList>'.$value2.'</PriceDetailsTwinList>'."\n";


        } elseif( $key2 === 'PriceDetailsSingleList' ) {
          $dates_str = $dates_str."\t\t\t\t".'<PriceDetailsSingleList>'.$value2.'</PriceDetailsSingleList>'."\n";


        } elseif( $key2 === 'PriceDetailsSingleSupplement' ) {
          $dates_str = $dates_str."\t\t\t\t".'<PriceDetailsSingleSupplement>'.$value2.'</PriceDetailsSingleSupplement>'."\n";


        } elseif( $key2 === 'Currency' ) {
          $dates_str = $dates_str."\t\t\t\t".'<Currency>'.$value2.'</Currency>'."\n";


        } elseif( $key2 === 'GuaranteedDeparture' ) {
          $dates_str = $dates_str."\t\t\t\t".'<GuaranteedDeparture>'.$value2.'</GuaranteedDeparture>'."\n";


        } elseif( $key2 === 'DepartureDateOnlineFlag' ) {
          $dates_str = $dates_str."\t\t\t\t".'<DepartureDateOnlineFlag>'.$value2.'</DepartureDateOnlineFlag>'."\n";


          // !!!!!!! NOT WORKING !!!!!!!!!
        } elseif( $key2 === 'GuaranteeDepTextShowOnline' ) {
          if( is_array($value2) and count($value2) == 0 ) $value2 = ''; 
          $dates_str = $dates_str."\t\t\t\t".'<GuaranteeDepTextShowOnline>'.$value2.'</GuaranteeDepTextShowOnline>'."\n";


        } elseif( $key2 === 'InventoryType' ) {
          $dates_str = $dates_str."\t\t\t\t".'<InventoryType>'.$value2.'</InventoryType>'."\n";


        } elseif( $key2 === 'NoInventoryMessage' ) {
          if( is_array($value2) and count($value2) == 0 ) $value2 = '';
          $dates_str = $dates_str."\t\t\t\t".'<NoInventoryMessage>'.$value2.'</NoInventoryMessage>'."\n";


        } elseif( $key2 === 'TotalRoomsInventory' ) {
          $dates_str = $dates_str."\t\t\t\t".'<TotalRoomsInventory>'."\n";

          foreach ($value2 as $key3 => $value3) {
            //echo '<br><br><strong>key3: '.$key3.'</strong><br><br>';

            if( $key3 === 'TotalRooms' ) {
              $dates_str = $dates_str."\t\t\t\t\t".'<TotalRooms>'.$value3.'</TotalRooms>'."\n";


            } elseif( $key3 === 'AvailableInventory' ) {
              $dates_str = $dates_str."\t\t\t\t\t".'<AvailableInventory>'.$value3.'</AvailableInventory>'."\n";


            } elseif( $key3 === 'ThresholdInventory' ) {
              $dates_str = $dates_str."\t\t\t\t\t".'<ThresholdInventory>'.$value3.'</ThresholdInventory>'."\n"."\t\t\t\t".'</TotalRoomsInventory>'."\n"."\t\t\t".'</date>';
            }

          }

        }

        if( strlen($api_tour_number) > 0 && strlen($api_tour_code) > 0 ) {
          $current_tour_id = $api_tour_number.$api_tour_code;
        }

      } // end inner foreach
    } // end outer foreach

    // put together tour and tour date(s) for XML
    if( strlen($current_tour_id) > 0) {
      $tour_str = $tour_str."\t".'<tour id="'.$current_tour_id.'">'.$tour_str."\n";

      // dates are unsorted coming from API, so let's sort now rather than in page template
      $dates = $client->sortDates($dates_str.'</dates>');


      $tour_str = $tour_str.$dates."\n";
      $tour_str = $tour_str."\t".'</tour>'."\n";
    }

    // prepend XML with opening 'tours' tag
    if ($i == 0 && !stristr($tour_str,'<tours>')) {
      $tour_str = '<tours>'."\n".$tour_str;
    }

    // if we are on the last tour, then append closing 'tours' tag
    if ($i == $len-1) {
      $tour_str = $tour_str.'</tours>'."\n";
    }

    // append tour data chunk to XML file...can't write all at once or we run out of memory
    $client->appendToTourFeed($tour_str);

    //error_log('done - <b>'.$tour_number.$tour_code);

    $tour_str = '';

  } else {
    error_log('No data available for: '.$post_title);
  }
  ++$i;
endforeach;

error_log('Sync complete');
?>