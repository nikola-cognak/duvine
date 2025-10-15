<?php

add_action('centaur_sync', 'sync_centaur_by_cron', 10, 0 );

function sync_centaur_by_cron(){
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
}