<?php

/*Requires*/
require_once(IAMD_TD.'/core/js_wp_editor.php');
require_once(IAMD_TD.'/classes/groups.php');
require_once(IAMD_TD.'/classes/users.php');
require_once(IAMD_TD.'/classes/tests.php');
require_once(IAMD_TD.'/classes/reports.php');
require_once(IAMD_TD.'/classes/templates.php');


/*Class Objects*/
add_action ('plugins_loaded', 'init_classes');
function init_classes(){
	$GLOBALS['users']=new Users();
	$GLOBALS['groups']=new Groups();
	$GLOBALS['tests']=new Tests();	
	$GLOBALS['reports']=new Reports();
	$GLOBALS['template']=new Templates();	
	return $GLOBALS;
}


/*BACKEND scripts*/
// add_action( 'wp_enqueue_scripts', 'js_enqueue' );
add_action( 'admin_enqueue_scripts', 'js_enqueue' );
function js_enqueue(){
	wp_enqueue_script( 'tests-edit-page-script', ASSETS_DIR . 'js_min/admin.min.js', array('jquery', 'ap_wpeditor_init'), '1.0', true);	
}

/*BACKEND styles*/
add_action('admin_enqueue_scripts', 'admin_style_lms');
add_action('login_enqueue_scripts', 'admin_style_lms');
function admin_style_lms() {
	wp_enqueue_style('admin_style_fontawersome', ASSETS_DIR.'css/admin.min.css');	
	wp_enqueue_style('admin_style_lms', ASSETS_DIR.'css/admin.min.css', array('admin_style_fontawersome'));	
}

/*FRONTEND STYLES*/
add_action('wp_enqueue_scripts', 'front_style_lms');
function front_style_lms() {
	wp_enqueue_style('style_lms', ASSETS_DIR.'css/stylesheet.min.css');	
	wp_enqueue_style(  'vjs-css', '//vjs.zencdn.net/4.12/video-js.css'  );
}

/*FRONTEND scripts*/
add_action( 'wp_enqueue_scripts', 'front_js_enqueue' );
function front_js_enqueue(){
	
	wp_enqueue_script( 'jquery-cookie', ASSETS_DIR . 'js_min/jquery.cookie.js', array( 'jquery' ), '1.1', true );
	wp_enqueue_script( 'ap_wpeditor_init', ASSETS_DIR . 'js_min/js_wp_editor.min.js', array( 'jquery' ), '1.1', true );
	wp_enqueue_script( 'custom-script-lms', ASSETS_DIR . 'js_min/custom_script.min.js', array('jquery', 'ap_wpeditor_init', 'jquery-cookie'), '1.0', true);	
  	wp_enqueue_script( 'vjs', '//vjs.zencdn.net/4.12/video.js',  '', '',  true );

}

/*AJAX CLASSES*/
require_once(IAMD_TD.'/classes/AJAX_Handler.php');
require_once(IAMD_TD.'/classes/reports_ajax.php');
require_once(IAMD_TD.'/classes/groups_ajax.php');

