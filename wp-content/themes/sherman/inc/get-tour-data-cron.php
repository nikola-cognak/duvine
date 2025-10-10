<?php
/*********************************************************
 *
 * This script pulls data from the source API.
 * If no errors are found, then the script backs up
 * current feed and moves new data live.
 *
 * If "critical" errors are found, then data
 * will not be pushed live
 *
 * If there are "critical" or "warning" errors, then
 * script will notify client/tank
 *
 * Meant to run as a cron
 *
 *********************************************************/
define('WP_USE_THEMES', false);
include($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');
require_once(WP_PLUGIN_DIR . '/sagittarius/centaur.php');

$centaur = new Centaur();

if (is_a($centaur, 'Centaur')) {
    // get all API data, check and send errors
    $centaur->get_api_data(false);
    $errorLevel = $centaur->log_has_errors();
    // if no CRITITCAL errors, then move live
    if (!$errorLevel || $errorLevel === 'warning') {
        echo '<br><br>warnings or no errors...pushing live.<br><br>';
        $centaur->push_data_live();
    }
}
