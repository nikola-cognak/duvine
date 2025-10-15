<?php

/* ========================================================
 *
 * Centaur functions to get data from the Centaur XML tour feed
 *
 * ======================================================== */


$centaur_xml = $_SERVER['DOCUMENT_ROOT'] .'/wp-content/uploads/centaur/tours.xml';

$centaur_tour_link = "https://centaur.duvine.com/centaur6/online/OPR_departureDateSelect?tourId=CENTAUR_ID&company=dvi&depDate=START_DATE&sFlag=true";


if( !function_exists('get_tour_data_from_centaur') ) :
	/**
	 * get all tour data for the given tour id
	 *
	 * @param string $centaur_id : The centaur assigned id of the tour
	 * 
	 * @return false if tour not found in feed, otherwise complete data for tour
	 */
	function get_tour_data_from_centaur( $centaur_id ){

		$feed = get_tour_feed();

		//error_log('FEDD:');
		//error_log( print_r($feed,true));

		if( $feed === false ) :
			error_log('tour feed returned false');
			return false;
		else : 
			$tour_data = $feed->xpath('tour[@id="'.$centaur_id.'"]');
			if( count($tour_data) > 0 ) :
				//error_log('TOUR DATA:');
				//error_log(print_r($tour_data,true));
				return $tour_data;
			else :
				error_log('NO DATA FOUND FOR THIS TOUR');
				return false;
			endif;
		endif;

	}
endif;



if( !function_exists('get_tour_feed') ) :
	/**
	 * load the Centaur XML feed
	 * 
	 * @return false if feed does not exist or is empty, otherwise complete xml feed file
	 */
	function get_tour_feed(){

		global $centaur_xml;

		//error_log('tour file is: '.$centaur_xml);

		if( file_exists($centaur_xml) ) :
			$feed = simplexml_load_file($centaur_xml);
			if( $feed === false ) :
				error_log('false from file load');
				return false;
			else : 
				//error_log('returning FOUND feed');
				return $feed;
			endif;
		else :
			error_log('cant find centaur xml file');
			return false;
		endif;

	}
endif;


if( !function_exists( 'get_tour_dates_by_centaur_id' ) ) :
/**
 * Returns tour dates from the Centaur feed
 *
 * @return array
 */
function get_tour_dates_by_centaur_id( $centaur_id ) {

    if( !$centaur_id ) return false;

    global $centaur_xml;

    if( !is_file($centaur_xml) ) {
    	return false;
    }

    $xml = simplexml_load_file($centaur_xml,'SimpleXMLElement', LIBXML_NOCDATA);
    if( $xml ===  FALSE || !isset($xml->tour['id']) ) return false;
    foreach ($xml->tour as $tour) {
        if ((string) $tour['id'] == $centaur_id) {
        	$json = json_encode($tour);
			$dates = json_decode($json,TRUE);
            //$dates = xml2array($tour);
            return $dates;
        }
    }
    // no data found for this id
    return false;
}
endif;

if( !function_exists( 'xml2array' ) ) :
/**
 * converts XML into array
 *
 * @return array
 */
function xml2array ( $xmlObject, $out = array () ) {
    foreach ( (array) $xmlObject as $index => $node )
        $out[$index] = ( is_object ( $node ) ||  is_array ( $node ) ) ? xml2array ( $node ) : $node;

    return $out;
}
endif;

if( !function_exists( 'get_single_supplement_price' ) ) :
/**
 * get the least expensive single supplement from the feed for this tour
 *
 * @return string
 */
function get_single_supplement_price ( $centaur_id ) {

	$price_single_supplement = 10000; // it won't be higher than this

	$dates = get_tour_dates_by_centaur_id($centaur_id);

	if( !$dates ) {
    	return false;
    }

    $price = false;

    if( !isset($dates['dates']['date'][0]) ) :
    	//only one tour date
    	$price_single_supplement = $dates['dates']['date']['PriceDetailsSingleSupplement'];
    else :
		foreach( $dates as $date ) :
			if( !$date['date'] ) continue;
			for($i = 0;$i < count($date['date']);$i++) {

				if( $price = $date['date'][$i]['PriceDetailsSingleSupplement'] ) :
					if( intval($price) < intval($price_single_supplement) ) :
						$price_single_supplement = intval($price);
					endif;
				endif;
			}
		endforeach;
	endif;

	if( $price_single_supplement == 10000 ) return false;

    return $price_single_supplement;

}
endif;

if (!function_exists('get_supplement_price_by_year')) {
    /**
     * get the least expensive single supplement from the feed for this tour.
     *
     * @param mixed $tourdates
     * @param mixed $year
     *
     * @return string
     */
    function get_supplement_price_by_year($tourdates, $year)
    {
        $price_single_supplement = 10000; // it won't be higher than this

        $price = false;

        foreach ($tourdates as $date) {
            $price = $date['single_price'];
            if ($price) {
                if ((int) $price < (int) $price_single_supplement) {
                    $price_single_supplement = (int) $price;
                }
            }
        }

        if (10000 === $price_single_supplement) {
            return false;
        }

        return $price_single_supplement;
    }
}


if( !function_exists( 'duvine_get_centaur_link' ) ) :
/**
 * Retrieves and returns the link to the centaur booking site
 *
 * @param string $centaurId ID of the tour
 * @param string $startDate Starting date of the tour
 *
 * @return string | false (if no $centaurId)
 */
function duvine_get_centaur_link( $centaurId, $startDate ){

	global $centaur_tour_link;

    if( !$centaurId ){
        return false;
    }

    $url = $centaur_tour_link;

    // pass the centaurId and the start date
    $url = str_replace ( 'CENTAUR_ID' , $centaurId , $url );
    $url = str_replace ( 'START_DATE' , $startDate , $url );

    //$url = "https://centaur.duvine.com/centaur6/online/OPR_departureDateSelect?tourId=CENTAUR_ID&company=dvi&depDate=START_DATE&sFlag=true";

    return $url;
}
endif; // duvine_get_centaur_link


if( !function_exists( 'duvine_get_tour_level' ) ) :
/**
 * Retrieves and returns the tour level from the Centaur feed
 *
 * @param string $centaurId ID of the tour
 *
 * @return string | false (if no $centaurId)
 */
function duvine_get_tour_level( $centaurId ){

	if( !$centaurId ){
        return false;
    }

    $tour_data = get_tour_data_from_centaur( $centaurId );

    if( !$tour_data ){
        return false;
    }

    $att = 'level';
    $level = (string)$tour_data[0]->attributes()->$att;
    if( !$level ) $level = 0;

    return $level;
}
endif; // duvine_get_tour_level

if( !function_exists( 'duvine_get_tours_not_in_centaur_level' ) ) :
/**
 * Retrieves tour ids of tour that are not in centaur levels
 *
 * @param string $levels - levels we don't want
 *
 * @return array | array of tour centaur ids
 */
function duvine_get_tours_not_in_centaur_level( $levels ) {
	$tour_ids = array();

	global $centaur_xml;

    if( !is_file($centaur_xml) ) {
        return false;
    }

    $xml = simplexml_load_file($centaur_xml);
    if( $xml ===  FALSE || !isset($xml->tour['id']) ) return false;
    foreach ($xml->tour as $tour) {
        if ( in_array($tour['level'],$levels) ) {
            continue;
        } else {
        	$tour_ids[] = (string)$tour['id'];
        }
    }
    return $tour_ids;
}
endif; // duvine_get_tours_not_in_centaur_level

if (!function_exists('duvine_get_tours_with_daterange')) {
    /**
     * @param string $startDate
     * @param string $endDate
     * @return array|bool
     */
    function duvine_get_tours_with_daterange(string $startDate, string $endDate)
    {
        try {
            $startDate = new DateTime($startDate);
            $endDate = new DateTime($endDate);
        } catch (Exception $exception) {
            return false;
        }
        $tourIds = [];
        global $centaur_xml;

        if(!is_file($centaur_xml)) {
            return false;
        }
        $xml = simplexml_load_file($centaur_xml);
        if($xml ===  FALSE || !isset($xml->tour['id'])) {
            return false;
        }
        foreach ($xml->tour as $tour) {
            $data = xml2array($tour);
            foreach ($data as $item) {
                if(!$item['date']) {
                    continue;
                }
                // we might have only one date (end date - $item['date']['ReturnDate'])
                if ($startTourDate = $item['date']['TourDate']) {
                    if (isInDateRange($startDate, $endDate, $startTourDate)) {
                        $tourIds[] = (string)$tour['id'];
                        continue 2;
                    }
                } else {
                    $tourDatesCount = count($item['date']);
                    // or we have multiple dates (end date - $item['date'][$i]['ReturnDate'])
                    for($i = 0; $i < $tourDatesCount; $i++) {
                        if ($startTourDate = $item['date'][$i]['TourDate']) {
                            if (isInDateRange($startDate, $endDate, $startTourDate)) {
                                $tourIds[] = (string)$tour['id'];
                                continue 3;
                            }
                        }
                    }
                }
            }
        }

        return $tourIds;
    }

    /**
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @param string $tourStartDate
     * @return bool
     */
    function isInDateRange(
        DateTime $startDate,
        DateTime $endDate,
        string $tourStartDate
    ): bool
    {
        $format = 'Y-m-d';
        try {
            $tourStartDate = new DateTime($tourStartDate);

            return ($startDate->format($format) <= $tourStartDate->format($format)) &&
                ($endDate->format($format) >= $tourStartDate->format($format));
        } catch (Exception $exception) {
            return false;
        }
    }
}

if( !function_exists( 'duvine_get_tours_with_date' ) ) :
/**
 * Retrieves tour centaur ids of tours that are in months(s)
 *
 * @param string $dates - dates we want
 *
 * @return array | array of tour centaur ids
 */
function duvine_get_tours_with_date( $query_dates ) {

	if( count($query_dates) < 1 ) return false;

	$tour_ids = array();

	global $centaur_xml;

    if( !is_file($centaur_xml) ) {
        return false;
    }

    $xml = simplexml_load_file($centaur_xml);
    if( $xml ===  FALSE || !isset($xml->tour['id']) ) return false;
    foreach ($xml->tour as $tour) {

    	$dates = xml2array($tour);

	    if( !$dates ) {
	    	continue;
	    }

		foreach( $dates as $date ) :

			if( !$date['date'] ) continue;

			// we might have only one date
			if( ($start_date = $date['date']['TourDate']) && ($end_date = $date['date']['ReturnDate']) ) :

				$start_date = explode('/', $start_date);
				if( !isset($start_date[0]) || !isset($start_date[2]) ) {
					continue;
				} else {
					$start_date = $start_date[2].$start_date[0];
					if(  in_array($start_date, $query_dates) ) {
						$tour_ids[] = (string)$tour['id'];
						continue 2;
					}
				}

				$end_date = explode('/', $end_date);
				if( !isset($end_date[0]) || !isset($end_date[2]) ) {
					continue;
				} else {
					$end_date = $end_date[2].$end_date[0];
					if(  in_array($end_date, $query_dates) ) {
						$tour_ids[] = (string)$tour['id'];
						continue 2;
					}
				}

			else :

				// or we have multiple dates
				for($i = 0;$i < count($date['date']);$i++) {

					// this works for start date
					if( $tour_date = $date['date'][$i]['TourDate'] ) :
						// tour filter currently uses YYYYMM format...202002

						$tour_date = explode('/', $tour_date);
						if( !isset($tour_date[0]) || !isset($tour_date[2]) ) {
							continue;
						} else {
							$tour_date = $tour_date[2].$tour_date[0];
							if(  in_array($tour_date, $query_dates) ) {
								$tour_ids[] = (string)$tour['id'];
								continue 3;
							}
						}

					endif;

					// this works for return date
					if( $tour_date = $date['date'][$i]['ReturnDate'] ) :

						$tour_date = explode('/', $tour_date);
						if( !isset($tour_date[0]) || !isset($tour_date[2]) ) {
							continue;
						} else {
							$tour_date = $tour_date[2].$tour_date[0];
							if(  in_array($tour_date, $query_dates) ) {
								$tour_ids[] = (string)$tour['id'];
								continue 3;
							}
						}

					endif;

				}
			endif;
		endforeach;

    }
    return $tour_ids;
}
endif; // duvine_get_tours_with_date
