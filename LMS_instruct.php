<?php
/*
Plugin Name: LMS-instruct
Plugin Script: instructlms.php
Plugin URI: http://edangollc.com
Description: LMS.
Version: 0.1a
Author: eDango LLC
Author URI: http://edangollc.com
*/
define('LMS_DIR', dirname(__FILE__) );

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

define('IAMD_TD', plugin_dir_path( __FILE__ ));
define('IAMD_BASE_URL', plugins_url().'/LMSinstruct/');
define('ASSETS_DIR', IAMD_BASE_URL.'assets/');
define('TPL_DIR', IAMD_TD.'frontend/');

require_once(IAMD_TD.'/core/init.php');
