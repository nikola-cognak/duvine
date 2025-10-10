<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              tankdesign.com
 * @since             1.0.0
 * @package           Sagittarius
 *
 * @wordpress-plugin
 * Plugin Name:       Sagittarius
 * Plugin URI:        tankdesign.com
 * Description:       A plugin to connect to the Centaur Webservice API
 * Version:           1.0.0
 * Author:            Tank Design
 * Author URI:        tankdesign.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       sagittarius
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'PLUGIN_NAME_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-sagittarius-activator.php
 */
function activate_sagittarius() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-sagittarius-activator.php';
	Sagittarius_Activator::activate();

	// Schedule the sync event to run every 2 hours
	if (!wp_next_scheduled('sagittarius_sync_event')) {
		wp_schedule_event(time(), 'twohourly', 'sagittarius_sync_event');
	}

	set_transient( 'sagittarius-admin-notice-activation', true, 5 );
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-sagittarius-deactivator.php
 */
function deactivate_sagittarius() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-sagittarius-deactivator.php';
	Sagittarius_Deactivator::deactivate();

	// Remove the scheduled sync event
	wp_clear_scheduled_hook('sagittarius_sync_event');

	delete_transient( 'sagittarius-admin-notice-activation' );
}

register_activation_hook( __FILE__, 'activate_sagittarius' );
register_deactivation_hook( __FILE__, 'deactivate_sagittarius' );

//register_activation_hook( __FILE__, 'sagittarius_check_activation_hook' );
//function sagittarius_check_activation_hook() {
    //set_transient( 'sagittarius-admin-notice-activation', true, 5 );
//}

add_action( 'admin_notices', 'sagittarius_check_activation_notice' );
function sagittarius_check_activation_notice(){
     if( get_transient( 'sagittarius-admin-notice-activation' ) ){
        ?>
        <div class="updated notice is-dismissible">
            <p>Be sure to add the API endpoint on the <a href="/wp-admin/admin.php?page=sagittarius/templates/sagitarrius-settings.php">setttings page</a>.</p>
        </div>
        <?php
        delete_transient( 'sagittarius-admin-notice-activation' );
    }
}

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-sagittarius.php';

/**
 * admin-specific hooks
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/options.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_sagittarius() {
	$plugin = new Sagittarius();
	$plugin->run();

}
run_sagittarius();

// our class for API calls
require_once('centaur.php');

// Add the sync action handler
add_action('sagittarius_sync_event', function() {
	if (isset($GLOBALS['sagittarius'])) {
		$GLOBALS['sagittarius']->get_api_data(false);
		$GLOBALS['sagittarius']->push_data_live();
	}
});

// Register the two-hourly interval
add_filter('cron_schedules', function($schedules) {
	$schedules['twohourly'] = array(
		'interval' => 7200, // 2 hours in seconds
		'display'  => __('Every 2 Hours')
	);
	return $schedules;
});


