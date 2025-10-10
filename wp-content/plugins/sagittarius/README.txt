=== Sagittarius ===
Contributors: (this should be a list of wordpress.org userid's)
Donate link: tankdesign.com
Tags: api
Requires at least: 3.0.1
Tested up to: 4.8
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Sagittarius plugin uses the Centaur API to pull Tour data, which includes dates, pricing and availability.

== Description ==

Sagittarius plugin uses the Centaur API to pull Tour data, which includes dates, pricing and availability. Centaur provides data in an XML format.

Data is ready in and processed, then put in a better organized XML format by <tour>. Final files exist in the the WordPress uploads directory, placed in a "centaur" subdirectory.

Admin pages/functionality:

* "Sync" -- pull latest Centaur API data is store in /wp-content/uploads/centaur/tours_tmp.xml. After running, the error log will be displayed.
* "Make Live" -- this option will display after the Sync has been run. Data is copied from /wp-content/uploads/centaur/tours_tmp.xml and stored in /wp-content/uploads/centaur/tours.xml
* Backup files are also stored with a timestamp in the same directory. Note that these backup files are created when "Sync" is run, not on  
  cron. Please create separate cron if you would like daily/weekly backups.
* Restore -- allow an admin user to move a previouly backed up XML file to "Live" data
* Error Log -- displays all errors from the last time that "Sync" was run
* Settings -- contains input field to set the Centaur API endpoint

== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Upload `sagittarius` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Enter the Centaur API endpoing on the plugin's Settings page
4. Run "Sync" on the plugin's "Sync" admin page

== Frequently Asked Questions ==

= Why is data stored locally, rather than pulled directory from Centaur? =

During the first week of plugin development, the Centaur API was unavailable, that is, broken. So it's a safer bet to read data from a local file rather than depend on uptime of an API web service.

= How do I display the pulled data? =

All data is stored in /wp-content/uploads/centaur/tours.xml. Your page templates will need to parse the XML, or better yet use the plugin's "get_tour_dates_by_centaur_id" method, which returns an array of tours:

if( isset($GLOBALS['sagittarius']) ) :
    $client = $GLOBALS['sagittarius'];
    $dates = $client->get_tour_dates_by_centaur_id($centaur_id);
else :
    error_log('No sagittarius instance in api.php');
endif;

== Screenshots ==

1. This screen shot description corresponds to screenshot-1.(png|jpg|jpeg|gif). Note that the screenshot is taken from
the /assets directory or the directory that contains the stable readme.txt (tags or trunk). Screenshots in the /assets
directory take precedence. For example, `/assets/screenshot-1.png` would win over `/tags/4.3/screenshot-1.png`
(or jpg, jpeg, gif).
2. This is the second screen shot

== Changelog ==

= 1.0 =
* Functional plugin


`<?php code(); // goes in backticks ?>`