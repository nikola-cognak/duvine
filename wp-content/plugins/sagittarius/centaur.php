<?php
/*************************************************************
 *
 * Centaur class used to call Centaur API to get Tour info,
 * and to deliver tour info to tour pages
 *
 *************************************************************/

include ('vendor/autoload.php') ;

/* Use Guzzle for all API calls */
use GuzzleHttp\Client as Client;
use GuzzleHttp\Psr7 as Psr7;
use GuzzleHttp\Psr7\Request as Request;
use GuzzleHttp\Exception\RequestException as RequestException;
use GuzzleHttp\Exception\ClientException as ClientExeption;
use GuzzleHttp\Exception\ServerException as ServerException;
use GuzzleHttp\Exception\BadResponseException as BadResponseException;
use GuzzleHttp\Exception\SeekException as SeekException;
use Exception;

if (!class_exists('Centaur')) :
class Centaur {

	private $client;
	//private $login_endpoint;
	private $tour_endpoint;
	private $request_headers;
	private $api_user;
	private $api_pass;
	private $api_company;
	private $user_session_id;
	private $tours_feed;
	private $tours_feed_tmp;
	private $upload_directory;
	public $error_log;

	// cron will send email if errors getting API data
	private $email_recipients;
	private $critical_email_recipients;
	private $email_sender;

    /**
     * @var null|string
     */
    private ?string $errorLevel = null;

	public function __construct() {

		// as of 11/15/18, do not need to login/get session id to get api data...
		//$this->login_endpoint = 'http://dvi.centaursystemsinc.com:9080/centaurws/Login/v20';

		//$this->request_headers = ['Content-Type' => 'application/xml', 'Accept' => 'application/xml'];

		$this->request_headers = ['Content-Type' => 'application/xml', 'Accept' => 'application/xml'];

		// creds are no longer used or necessary
		$this->api_user = 'jkuntal';
	    $this->api_pass = '1234abcd';
	    $this->api_company = 'dvi021';

	    // no longer need a session id for Centaur
	    //$this->user_session_id = $this->apiLogin();

		// check to see if there is centaur dir in uploads
		$upload = wp_upload_dir();
    	$upload_dir = $upload['basedir'];
    	$upload_dir = $upload_dir . '/centaur';

	    if (! is_dir($upload_dir)) {
	       mkdir( $upload_dir, 0700 );
	    }

	    $this->upload_directory = $upload_dir;

	    // create Tour feed
	    $this->tours_feed = $upload_dir.'/tours.xml';
	    $this->create_file($this->tours_feed);

		// create Tour feed tmp file (used to pull in data from Centaur)
		$this->tours_feed_tmp = $upload_dir.'/tours_tmp.xml';
	    $this->create_file($this->tours_feed_tmp);

		// create error log
		$this->error_log = $upload_dir.'/tour_error_log.xml';
	    $this->create_file($this->error_log);

	    // this is for notifying tank and/or client about cron/api data issues
        $this->email_sender = 'info@duvine.com';
	    if(wp_get_environment_type() === 'production') {
            // for live
            $this->email_recipients = 'operations@duvine.com';
            $this->critical_email_recipients = 'operations@duvine.com,gkidera@duvine.com,jamal@gangverk.is';
        } else {
            // for testing
            $this->email_recipients = 'gkidera@duvine.com,jamal@gangverk.is,jay@cognak.com';
            $this->critical_email_recipients = 'gkidera@duvine.com,jamal@gangverk.is,jay@cognak.com';
        }

		add_action( 'admin_enqueue_scripts', array( $this, 'centaur_scripts' ) );

		// used to make AJAX calls from plugin admin pages
		add_action( 'wp_ajax_get_api_data', array( $this, 'get_api_data' ) );
		add_action( 'wp_ajax_push_data_live', array( $this, 'push_data_live' ) );
		add_action( 'wp_ajax_restore_tour_data', array( $this, 'restore_tour_data' ) );
		add_action( 'wp_ajax_remove_backups', array( $this, 'remove_backup_files' ) );
		add_action( 'wp_ajax_check_for_tour', array( $this, 'check_for_tour' ) );
	}

	/**
	 * centaur_scripts
	 *
	 * @since    1.0.0
	 */
	public function centaur_scripts() {
		wp_register_script( 'sagittarius-template-js', plugins_url( 'templates/js/template.js', __FILE__ ), array('jquery'), null, true );
		wp_localize_script( 'sagittarius-template-js', 'sagittarius_ajax', array( 'ajax_url' => admin_url('admin-ajax.php')) );
	  	wp_enqueue_script( 'sagittarius-template-js' );
	}

	/**
	 * is_valid_endpoint
	 *
	 * @since    1.0.0
	 * @param      string    $endpoint   Centaur API Endpoint
	 */
	public function is_valid_endpoint( $endpoint ) {
		return filter_var($endpoint, FILTER_VALIDATE_URL) ? true : false;
	}

	/**
	 * append_to_tour_feed
	 *
	 * @since    1.0.0
	 * @param      string    $xml_content   tour xml content from API
	 */
	public function append_to_tour_feed( $xml_content ) {

		if( !file_exists($this->tours_feed_tmp) ) {
			$this->create_file($this->tours_feed_tmp);
		}
		
		$file=fopen($this->tours_feed_tmp,"a+");
		if( $file !== FALSE) {
			fwrite($file,$xml_content."\n");
	    	fclose($file);
		} else {
			ini_set("error_log", $this->error_log);
			error_log('Unable to open tmp file for appending');
		}
	}

	/**
	 * get_api_data
	 *
	 * @since    1.0.0
	 *
	 */
	public function get_api_data( $called_from_ajax = true ) {

		ini_set("error_log", $this->error_log);

		//error_log('ini_set');

		// clear out error log and tmp file, then back up current feed
		$this->clear_file($this->error_log);
		$this->clear_file($this->tours_feed_tmp);
		$this->back_up_tour_data();

		// get all tours from WordPress
		$tours = get_posts( array(
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

		if( count($tours) < 1 ) :
		  error_log('No WordPress tours found.');
		  return false;
		endif; 

		error_log('WordPress tour count (non-private): '.count($tours));

		$tour_str = '';
		$dates_str = '';

		$i = 1;
		$len = count($tours);

		// append opening 'tours' tag
		$this->append_to_tour_feed('<?xml version="1.0" encoding="UTF-8"?>');
		$this->append_to_tour_feed('<tours>');

		foreach($tours as $post) : setup_postdata($post);

		  $centaur_tour_id = trim( get_field('d_tour_centaur_id',$post->ID) );
		  $tour_number = substr($centaur_tour_id, 0, 6);
		  $tour_code = substr($centaur_tour_id, 6);
		  $post_title = get_the_title($post->ID);

		  // no centaur ID in this tour CPT
		  if( strlen($centaur_tour_id) < 1 ) :
		    error_log('No WordPress Centaur ID found for: '.$post_title);
		    ++$i;
		    continue;
		  endif;

		  $api_tour_number = '';
		  $api_tour_code = '';
		  $api_tour_status = '';
		  $api_tour_level = '';

		  $dates_str = "\t\t".'<dates>';
		  $current_tour_id = '';

		  $single_date_tour = array();
		  $single_date_tour_inventory = array();

		  $current_tour_date = '';

		  //error_log('-----------------------!!!!!!!!this is the TOUR INFO:');
		  //error_log( print_r( $tour_info, true) );
          try {
              // get all tour info from API for this tour
              $tour_info = $this->get_tour_info_from_api( $tour_number, $tour_code );
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
                              $current_tour_date = $value2;

                          } elseif( $key2 === 'ReturnDate' ) {
                              $dates_str = $dates_str."\t\t\t\t".'<ReturnDate>'.$value2.'</ReturnDate>'."\n";

                          } elseif( $key2 === 'TourStatus' ) {
                              $api_tour_status = trim($value2);

                          } elseif( $key2 === 'TourActivityLevel' ) {
                              $api_tour_level = trim($value2);
                              if( strlen($api_tour_level) < 1) error_log('No Level given for '.$post_title.' ( '.$current_tour_date.' )');

                          } elseif( $key2 === 'OnlineTourStatus' ) {
                              $dates_str = $dates_str."\t\t\t\t".'<OnlineTourStatus>'.$value2.'</OnlineTourStatus>'."\n";

                          } elseif( $key2 === 'PriceDetailsTwinList' ) {
                              $dates_str = $dates_str."\t\t\t\t".'<PriceDetailsTwinList>'.$value2.'</PriceDetailsTwinList>'."\n";
                              if( $value2 < 1 ) error_log('No PriceDetailsTwinList given for '.$post_title.' ( '.$current_tour_date.' )');

                          } elseif( $key2 === 'PriceDetailsSingleList' ) {
                              $dates_str = $dates_str."\t\t\t\t".'<PriceDetailsSingleList>'.$value2.'</PriceDetailsSingleList>'."\n";
                              if( $value2 < 1 ) error_log('No PriceDetailsSingleList given for '.$post_title.' ( '.$current_tour_date.' )');

                          } elseif( $key2 === 'PriceDetailsDoubleList' ) {
                              $dates_str = $dates_str."\t\t\t\t".'<PriceDetailsDoubleList>'.$value2.'</PriceDetailsDoubleList>'."\n";
                              if( $value2 < 1 ) error_log('No PriceDetailsDoubleList given for '.$post_title.' ( '.$current_tour_date.' )');

                          } elseif( $key2 === 'PriceDetailsSingleSupplement' ) {
                              $dates_str = $dates_str."\t\t\t\t".'<PriceDetailsSingleSupplement>'.$value2.'</PriceDetailsSingleSupplement>'."\n";
                              if( $value2 < 1 ) error_log('No PriceDetailsSingleSupplement given for '.$post_title.' ( '.$current_tour_date.' )');

                          } elseif( $key2 === 'DepDateText' ) {
                              $dates_str = $dates_str."\t\t\t\t".'<DepDateText>'.$value2.'</DepDateText>'."\n";

                          } elseif( $key2 === 'Currency' ) {
                              $dates_str = $dates_str."\t\t\t\t".'<Currency>'.$value2.'</Currency>'."\n";


                          } elseif( $key2 === 'GuaranteedDeparture' ) {
                              $dates_str = $dates_str."\t\t\t\t".'<GuaranteedDeparture>'.$value2.'</GuaranteedDeparture>'."\n";


                          } elseif( $key2 === 'DepartureDateOnlineFlag' ) {
                              $dates_str = $dates_str."\t\t\t\t".'<DepartureDateOnlineFlag>'.$value2.'</DepartureDateOnlineFlag>'."\n";

                          } elseif( $key2 === 'GuaranteeDepTextShowOnline' ) {
                              if( is_array($value2) and count($value2) == 0 ) $value2 = '';
                              else  $value2 = print_r($value2, true);

                              if( $value2 == '') :
                                  $dates_str = $dates_str."\t\t\t\t".'<GuaranteeDepTextShowOnline>'.$value2.'</GuaranteeDepTextShowOnline>'."\n";
                              else :
                                  $dates_str = $dates_str."\t\t\t\t".'<GuaranteeDepTextShowOnline><![CDATA['.$value2.']]></GuaranteeDepTextShowOnline>'."\n";
                              endif;

                          } elseif( $key2 === 'NumberOfPax' ) {
                              //if( is_array($value2) and count($value2) == 0 ) $value2 = '';
                              $dates_str = $dates_str."\t\t\t\t".'<NumberOfPax>'.$value2.'</NumberOfPax>'."\n";

                          } elseif( $key2 === 'InventoryType' ) {
                              $dates_str = $dates_str."\t\t\t\t".'<InventoryType>'.$value2.'</InventoryType>'."\n";


                          } elseif( $key2 === 'NoInventoryMessage' ) {
                              if( is_array($value2) and count($value2) == 0 ) $value2 = '';
                              $dates_str = $dates_str."\t\t\t\t".'<NoInventoryMessage>'.$value2.'</NoInventoryMessage>'."\n";


                          } elseif( $key2 === 'HoldInventory' ) {
                              if( is_array($value2) and count($value2) == 0 ) $value2 = '';
                              $dates_str = $dates_str."\t\t\t\t".'<HoldInventory>'.$value2.'</HoldInventory>'."\n";


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

                  // put together tour and tour date(s) for XML, also include tour status and tour level
                  if( strlen($current_tour_id) > 0) {
                      $tour_str = $tour_str."\t".'<tour id="'.$current_tour_id.'" status="'.$api_tour_status.'" level="'.$api_tour_level.'">'."\n";

                      // dates are unsorted coming from API, so let's sort now rather than in page template
                      $dates = $this->sort_dates($dates_str.'</dates>');


                      $tour_str = $tour_str.$dates."\n";
                      $tour_str = $tour_str."\t".'</tour>';
                  }

                  // append tour data chunk to XML file...can't write all at once or we run out of memory
                  $this->append_to_tour_feed($tour_str);

                  $tour_str = '';

              } else {
                  error_log('No Centaur API data available for: '.$post_title);
              }
          } catch (Exception $exception) {
              error_log('Centaur API data unavailable for: '.$post_title);
              error_log($exception->getMessage());
          }
		  ++$i;
		endforeach;

		// append closing 'tours' tag
		$this->append_to_tour_feed('</tours>');
		
		error_log("\n\nSync complete");
		
		$this->send_log_errors($this->log_has_errors());

		$fh = fopen($this->error_log, 'r');

		if( $fh !== FALSE) {
			$pageText = fread($fh, filesize($this->error_log));
	    	echo nl2br($pageText);
	    	fclose($fh);
		} else {
			ini_set("error_log", $this->error_log);
			error_log('Unable to open log file');
			//echo 'Unable to open log file';
		}

		if( $called_from_ajax === true ) :
			die();
		else :
			return true;
	    endif;

	}

	/**
	 * push_data_live
	 *
	 * @since    1.0.0
	 *
	 */
	public function push_data_live() {
		ini_set("error_log", $this->error_log);
		
		// now overwrite feed file with tmp file
		if( copy($this->tours_feed_tmp,$this->tours_feed) ) {
			echo 'Success!!! API data has been updated.';
			return true;
		} else {
			echo 'Unable to overwrite live feed file.';
			error_log('Unable to overwrite live feed file.');
			return false;
		}
		die();
	}

	/**
	 * back_up_tour_data
	 *
	 * @since    1.0.0
	 *
	 */
	private function back_up_tour_data() {
		
		// backup current feed file
		$time = $_SERVER['REQUEST_TIME'];
	    if( file_exists($this->tours_feed) ) {
	    	$destination_file = $this->upload_directory . '/'.$time.'_tours.xml';
			if( !copy($this->tours_feed, $destination_file) ) {
				ini_set("error_log", $this->error_log);
				error_log('Unable to backup tour feed file.');
			}
		}
	}

	/**
	 * restore_tour_data
	 *
	 * @since    1.0.0
	 *
	 */
	public function restore_tour_data() {

		$backup = $this->upload_directory.'/'.$_POST['restorefile'];

		if( file_exists($backup) ) {

			// overwrite feed file with backup file
			if( rename($backup,$this->tours_feed) ) {
				echo 'Success!!! API data has been restored.';
			} else {
				echo 'Unable to restore Tour feed.';
			}
		} else {
			echo 'Error - unable to find file.';
		}
		die();
	}

	/**
	 * log_has_errors
	 *
	 * return: critical -- don't push data changes live
	 *         warning -- errors found, but according to client, ok to push live
	 *         null -- no errors were found, ok to push live
	 *
	 * @since    1.0.0
	 *
	 */
	public function log_has_errors(): ?string
    {
        if (!$this->errorLevel) {
            $logFile = fopen($this->error_log, 'r');
            if ($logFile !== false) {
                // look at log file content to see if there are any errors
                $logText = fread($logFile, filesize($this->error_log));
                $errorsFound = null;
                // according to the client, these errors can happen
                // and we can still make the feed live
                if ($this->isWarningErrorHappened($logText)) {
                    $errorsFound = 'warning';
                }
                // - don't make feed live if these happen
                if ($this->isCriticalErrorHappened($logText)) {
                    $errorsFound = 'critical';
                }
                fclose($logFile);
            } else {
                $errorsFound = 'critical';
            }
            $this->errorLevel = $errorsFound;
        }

		return $this->errorLevel;
	}

    /**
     * @param string $errorLog
     * @return bool
     */
    private function isWarningErrorHappened(string $errorLog): bool
    {
        $warningErrors = [
            'Get Tour Info API error: no dates available',
            'No Level',
            'No PriceDetailsTwinList',
            'No PriceDetailsDoubleList',
            'No PriceDetailsSingleList',
            'PriceDetailsSingleSupplement'
        ];

        return $this->checkErrorsInLog($warningErrors, $errorLog);
    }

    /**
     * @param string $errorLog
     * @return bool
     */
    private function isCriticalErrorHappened(string $errorLog): bool
    {
        $criticalErrors = [
            'No Centaur API data available',
            'No WordPress Centaur ID found',
            'get_tour_info_from_api error',
            'client exception',
            'server exception',
            'badreq exception',
            'seek exception',
            'request exception',
            'unknown exception',
            'Response invalid from Centaur API',
            'no response code from Centaur API',
            'Centaur API timeout',
            'No WordPress tours found',
            'Invalid Endpoint in get_api_data',
            'Unable to open tmp file for appending',
            'Unable to open log file',
            'Unable to overwrite live feed file'
        ];

        return $this->checkErrorsInLog($criticalErrors, $errorLog);
    }

    /**
     * @param array $errors
     * @param string $errorLog
     * @return bool
     */
    private function checkErrorsInLog(array $errors, string $errorLog): bool
    {
        $isHappened = false;
        foreach ($errors as $error) {
            if (!$isHappened) {
                $isHappened = $this->isErrorExist($error, $errorLog);
            }
        }

        return $isHappened;
    }

    /**
     * @param string $error
     * @param string $errorLog
     * @return bool
     */
    private function isErrorExist(string $error, string $errorLog): bool
    {
        return strpos($errorLog, $error) !== false;
    }

	/**
	 * send_log_errors
	 *
	 * @since    1.0.0
	 *
	 */
	public function send_log_errors($error_level) {

		ini_set("error_log", $this->error_log);

		$fh = fopen($this->error_log, 'r');

		if( $fh !== FALSE) {
			$log_text = fread($fh, filesize($this->error_log));
	    	fclose($fh);
		} else {
			$log_text = "Can't open error log file. Please notify Tank Design.";
		}

		$recipients = $this->email_recipients;
		
		if( $error_level === 'critical' ) :
			$log_text = str_replace('Sync complete', '', $log_text);
			$log_text = $log_text.'<br><strong>Critical errors were found. Data was NOT pushed live.</strong> Please fix Centaur data issues and rerun the sync plugin. Please contact Tank for other issues.';
			$recipients = $this->critical_email_recipients;
		elseif( $error_level === 'warning' ) :
			$log_text = str_replace('Sync complete', '', $log_text);
			$log_text = $log_text.'<br><strong>Warning errors were found. Data WAS pushed live.</strong> Please fix any Centaur data issues and rerun the sync plugin.';
		endif;

		$log_text .= '<br><a href="https://www.duvine.com/wp-admin/admin.php?page=sagittarius%2Ftemplates%2Fsagitarrius-home.php">Go to Sync Administration</a>';

		$subject = 'Duvine Data API Errors: '.$error_level;
		$body = nl2br($log_text);

		// To send HTML mail, the Content-type header must be set
		$headers[] = 'MIME-Version: 1.0';
		$headers[] = 'Content-type: text/html; charset=UTF-8';

		// Additional headers
		$headers[] = 'From: '.$this->email_sender;
		//$headers[] = 'To: deeblake@gmail.com';
		//$headers[] = 'Cc: birthdayarchive@example.com';
		//$headers[] = 'Bcc: birthdaycheck@example.com';

		$email_sent = false;
		$email_sent = wp_mail($recipients, $subject, $body, implode("\r\n", $headers));
	
		if( $email_sent === TRUE ) :
			echo 'Email sent to recipients';
		else :
			echo 'Error sending email to recipients';
			error_log('Error sending email to recipients');
		endif;
		
	}

	/**
	 * clearLogFile
	 *
	 * @since    1.0.0
	 *
	 */
	private function clear_file($file) {
		if( file_exists($file) ) {
			$contents = file_put_contents($file, "");
			if( $contents === FALSE ) {
				ini_set("error_log", $this->error_log);
				error_log('Unable to clear file: '.$file);
			}
		}
	}

	/**
	 * createFile
	 *
	 * @since    1.0.0
	 *
	 */
	private function create_file($file) {
		if( !file_exists($file) ) {
			$fh = fopen($file, 'w');
			if( $fh !== FALSE) {

				fclose($fh);
				chmod($file, 0777);
			} else {
				ini_set("error_log", $this->error_log);
				error_log("Can't create file: ".$file);
			}
		}
	}

	/**
	 * delete_file
	 *
	 * @since    1.0.0
	 *
	 */
	private function delete_file($file) {
		if( file_exists($file) ) {
			$deleted = unlink($file);
			if( $deleted === FALSE) {
				ini_set("error_log", $this->error_log);
				error_log("Can't delete file: ".$file);
			}
		}
	}

	/**
	 * createFile
	 *
	 * @since    1.0.0
	 *
	 */
	public function list_backup_files() {

		$file_array = glob($this->upload_directory.'/*_tours.xml');
		usort($file_array, create_function('$b,$a', 'return filemtime($a) - filemtime($b);'));

		if( count($file_array) < 1 ) echo '<p><strong>No backup files to restore.</strong><p>';

		echo '<ul>';
		foreach ($file_array as $filename) {
			if( filesize($filename) == 0) {
				$this->delete_file($filename);
			} else {
				echo '<li><input name="restore-file" type="radio" value="'.basename($filename).'" /> '.basename($filename).' ('.date('m-d-Y H:i:s',filemtime($filename)).')</li>';
			}
		}
		echo '</ul>';
	}

	/**
	 * remove_backup_files
	 *
	 * @since    1.0.0
	 *
	 */
	public function remove_backup_files() {
		$file_array = glob($this->upload_directory.'/*_tours.xml');
		foreach ($file_array as $filename) {
			$this->delete_file($filename);
		}
		echo 'Old feed files removed.';
		die();
	}

    /**
     * send request
     *
     * @param string $tour_num The tour number.
     * @param string $tour_code The tour code.
     * @return false|mixed
     * @throws Exception
     * @since    1.0.0
     */
	public function get_tour_info_from_api( $tour_num, $tour_code ) {

		$body = '<AvailableOnlineToursWithInventoryDetailsRequest>
				    <TourNumber>'.$tour_num.'</TourNumber>
				    <TourCode>'.$tour_code.'</TourCode>
				    <Company>'.$this->api_company.'</Company>
				 </AvailableOnlineToursWithInventoryDetailsRequest>';

		$endpoint = get_option('sagittarius_plugin_options');
		if(!$this->is_valid_endpoint($endpoint['text_string'])) {
            throw new Exception('Invalid Endpoint in get_api_data');
        } else {
            $this->tour_endpoint = $endpoint['text_string'];
        }

		$request = new Request('POST',  $this->tour_endpoint, $this->request_headers, $body);

		// DEV NOTE: CURLOPT_SSLVERSION may need to change depending on the server configuration
		// use $reponse 'debug' => true below to see if request is successful
		$tour_client = new Client([
			'base_uri' => $this->tour_endpoint,
			'curl' => [CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_2]
		]);

		$response = '';

		try {

			// this is for production
			$response = $tour_client->send($request, ['timeout' => 3]);
			
			// or use this to debug
			//$response = $tour_client->send($request, ['timeout' => 3, 'debug' => true]);

		} catch (GuzzleHttp\Exception\ClientException $e) {
            $errorMessage = '-------client exception: in get_tour_info_from_api';
            $errorMessage .= Psr7\str($e->getRequest());
    		if ($e->hasResponse()) {
                $errorMessage .= Psr7\str($e->getResponse());
    		}
    		throw new Exception($errorMessage);
        } catch (GuzzleHttp\Exception\ServerException $e) {
            $errorMessage = '-------server exception: in get_tour_info_from_api';
            $errorMessage .= Psr7\str($e->getRequest());
    		if ($e->hasResponse()) {
                $errorMessage .= Psr7\str($e->getResponse());
    		}
    		throw new Exception($errorMessage);
        } catch (GuzzleHttp\Exception\BadResponseException $e) {
            $errorMessage = '---------badreq exception: error in get_tour_info_from_api';
            $errorMessage .= Psr7\str($e->getRequest());
    		if ($e->hasResponse()) {
                $errorMessage .= Psr7\str($e->getResponse());
    		}
            throw new Exception($errorMessage);
        } catch (GuzzleHttp\Exception\SeekException $e) {
            $errorMessage = '---------seek exception: error in get_tour_info_from_api';
            $errorMessage .= Psr7\str($e->getRequest());
            if ($e->hasResponse()) {
                $errorMessage .= Psr7\str($e->getResponse());
    		}
    		throw new Exception($errorMessage);
        } catch (GuzzleHttp\Exception\RequestException $e) {
            $errorMessage = '---------request exception: error in get_tour_info_from_api';
            $errorMessage .= Psr7\str($e->getRequest());
            $errorMessage .= '---------end request-------';
            if ($e->hasResponse()) {
                $errorMessage .=
                $errorMessage .= '---------begin response-------';
                $errorMessage .= Psr7\str($e->getResponse());
                $errorMessage .= 'full response...';
                $errorMessage .= $e->getResponse()->getBody()->getContents();
                $errorMessage .= '---------end response-------';
    		} else {
                $errorMessage .= 'NO RESPONSE';
    		}
    		throw new Exception($errorMessage);
        } catch( Exception $e){
            $errorMessage = '---------unknown exception: error in get_tour_info_from_api';
            $errorMessage .= print_r( $e, true );
        	throw new Exception($errorMessage);
        }

        if($response) {
            $xml = simplexml_load_string($response->getBody(), "SimpleXMLElement", LIBXML_NOCDATA);
            $json = json_encode($xml);
            $array = json_decode($json, TRUE);

            if (isset($array['ResponseCode'])) {
                if ($array['ResponseCode'] == 0) :
                    // success retieving tour info
                    return $array;
                elseif ($array['ResponseCode'] == 2000) :
                    // no tours available
                    throw new Exception('Get Tour Info API error: no dates available: ' . $tour_num . $tour_code);
                elseif ($array['ResponseCode'] == 28) :
                    // no tours available
                    throw new Exception('get_tour_info_from_api error: Centaur API timeout');
                else :
                    return false;
                endif;
            } else {
                throw new Exception('get_tour_info_from_api error: no response code from Centaur API');
            }
        } else {
            error_log('Response invalid from Centaur API....Guzzle error unknown.');
        }

        return false;
	}

	/**
	 * get data from local XML file if data available for this tour
	 *
	 * @since    1.0.0
	 * @param      string    $centaur_id       The tour centaur id
	 */
	/*public function getTourInfoByCentaurID( $centaur_id ) {

		$xml = simplexml_load_file($this->tours_feed);
		foreach ($xml->tour as $tour) {
		    if ((string) $tour['id'] == $centaur_id) {
		        return $tour;
		    }
		}
		// no data found for this id
		return false;
	}*/

	//////////////////////////////////////////////////////////////////////////////////
	//
	// PLEASE NOTE THAT THE FOLLOWING 2 FUNCTIONS ARE ALSO IN THE THEME FOLDER!!!!!!
	//
	//////////////////////////////////////////////////////////////////////////////////

	/**
	 * get data from local XML file if data available for this tour
	 *
	 * @since    1.0.0
	 * @param      string    $centaur_id       The tour centaur id
	 */
	public function get_tour_dates_by_centaur_id( $centaur_id ) {

		$xml = simplexml_load_file($this->tours_feed,'SimpleXMLElement', LIBXML_NOCDATA);
		if( $xml ===  FALSE || !isset($xml->tour['id']) ) {
			error_log('no file or id');
			return false;
		}
		foreach ($xml->tour as $tour) {
			//error_log('loop');
		    if ((string) $tour['id'] == $centaur_id) {
		    	$json = json_encode($tour);
				$dates = json_decode($json,TRUE);
		    	//$dates = $this->xml2array($tour);
		    	//error_log('THIS TOUR WAS FOUND-----------');
		    	return $dates;
		    }
		}
		// no data found for this id
		return false;
	}

	/**
	 * transform objects to arrays
	 *
	 * @since    1.0.0
	 * @param      string    $xmlObject       The tour centaur id
	 */
	private function xml2array ( $xmlObject, $out = array () ) {
		error_log('node: '.$node);
        foreach ( (array) $xmlObject as $index => $node )
            $out[$index] = ( is_object ( $node ) ||  is_array ( $node ) ) ? $this->xml2array ( $node ) : $node;

        return $out;
	}

	/**
	 * this is called from plugin admin, so echoing reponse
	 *
	 * @since    1.0.0
	 * @param      string    $centaur_id       The tour centaur id
	 */
	public function check_for_tour() {

		$centaur_id = $_POST['centaurid'];
		//error_log('insie check_for_tour: '.$centaur_id);

		$tour = $this->get_tour_dates_by_centaur_id($centaur_id);

		$found['status'] = ( $tour === false ) ? false : true;

		echo json_encode($found);

		exit(0);
	}

	/**
	 * send request
	 *
	 * @since    1.0.0
	 * @param      string    $a   a tour date
	 * @param      string    $b   a tour date
	 */

	public function sort_dates($dates_xml) {

		$d = simplexml_load_string($dates_xml);
		// turn into array
		$e = array();
		foreach ($d->date as $date) {
		        $e[] = $date;
		}
		// sort the array
		usort($e, function($a, $b) {
			$d1 = strtotime($a->TourDate);
        	$d2 = strtotime($b->TourDate);
			return $d1 - $d2;
		});
		// put it back together
		$new_dates_xml = "\t\t".'<dates>';
		foreach ($e as $node) {
		        $new_dates_xml = $new_dates_xml."\n"."\t\t\t".$node->saveXML();
		}
		$new_dates_xml = $new_dates_xml."\n\t\t".'</dates>';

    	return $new_dates_xml;
	}

	/**
   * api login
   *
   * @since    1.0.0
   * @param      string    $api_user       username.
   * @param      string    $api_pass    password.
   * @param      string    $api_company    The company code.
   */
	/* private function api_login() {

	    $login_xml = '<LoginRequest>
	          <UserName>'.$this->api_user.'</UserName>
	          <UserPassword>'.$this->api_pass.'</UserPassword>
	          <UserCompany>'.$this->api_company.'</UserCompany>
	          </LoginRequest>';

	    $request = new Request('POST',  $this->login_endpoint, $this->request_headers, $login_xml);
	    $login_client = new Client(['base_uri' => $this->login_endpoint]);

	    try {
			$response = $login_client->send($request, ['timeout' => 2]);
		} catch (GuzzleHttp\Exception\ClientException $e) {

            error_log( 'client login error...' );
            error_log( Psr7\str($e->getRequest()) );
    		error_log( Psr7\str($e->getResponse()) );

        } catch (GuzzleHttp\Exception\ServerException $e) {

            error_log( 'server login error..' );
            error_log( Psr7\str($e->getRequest()) );
    		error_log( Psr7\str($e->getResponse()) );

        } catch (GuzzleHttp\Exception\BadResponseException $e) {

            error_log( 'badreq login error...' );
            error_log( Psr7\str($e->getRequest()) );
    		error_log( Psr7\str($e->getResponse()) );

        } catch( Exception $e){

        	error_log('something else...login error');
        }
	    

	    $xml = simplexml_load_string($response->getBody(), "SimpleXMLElement", LIBXML_NOCDATA);

	    $json = json_encode($xml);
		$array = json_decode($json,TRUE);
		
		//error_log('---------------login array--------------');
		//error_log( print_r( $array, true ) );
		//error_log('---------------end login array--------------');

		if( $array['ResponseCode'] ):
			if( $array['ResponseCode'] == 0 ) :
				// login request success

				// return the userSessionID
			elseif( $array['ResponseCode'] == 301 ) :
				// invalid user
				error_log('Login error: invalid user');
				return false;
			elseif( $array['ResponseCode'] == 302 ) :
				// invalid login details
				error_log('Login error: invalid login details');
				return false;
			else :
				return false;
			endif;

		else :

			error_log('Login error: no response code from API');
			return false;

		endif;

		// either return the session id or false

		//return $array;

	}*/

}
endif;

if( !isset($GLOBALS['sagittarius']) ) :
	$GLOBALS['sagittarius'] = new Centaur();
endif;

