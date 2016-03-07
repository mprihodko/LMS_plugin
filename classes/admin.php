<?php



Class LMS_Admin{
	
	/*
	*Vars
	*/
	public $user;	
	private $db;
	private $data;

	/*construct*/

	public function __construct() {
		global $wpdb;
		$this->db=$wpdb;
		$this->data=array();
		$this->user=$GLOBALS['users']->user;
		add_action( 'admin_init', array($this, 'script_admin_init'));
		add_action( 'admin_menu', array($this, 'lms_admin_setting_page'));	
	}

	/*  Register script  */
	public function script_admin_init() {
		wp_register_script( 'group-edit-page-script', ASSETS_DIR . 'js_min/admin.min.js', array('jquery'), '1.0', true );
	}

	/*ADD MENU PAGE */	
	public function lms_admin_setting_page() {
  		$page_hook_suffix = add_menu_page( 	'LMS Admin',
					  					   	'LMS Admin',
					  					   	'administrator',
					  					   	'lms_admin_setup',
					  					   	array( 	$this,
					  					   	 	 	'lms_admin_setup_page'
					  					   		 ),
					  					   	'dashicons-admin-network',
					  					   	74					  					   	
					  					 );
  		add_action('admin_print_scripts-'.$page_hook_suffix, array($this, 'add_admin_scripts'));

		$page_hook_suffix = add_submenu_page( 	'lms_admin_setup',
							 					'Test Management',
							 					'Test Management',
							 					'administrator',
							 					'lms_admin_setup_test',
							 					array(	$this,
							 					 		'lms_admin_setup_test_page'
							 					 	)
		
						 				);		
		add_action('admin_print_scripts-'.$page_hook_suffix, array($this, 'add_admin_scripts'));	
	}

	/*  enqueue script  */
	public function add_admin_scripts() {		
		wp_enqueue_script( 'group-edit-page-script' );
	}

	public function lms_admin_setup_page(){

	}

	public function lms_admin_setup_test_page(){
		require_once(IAMD_TD.'/admin/lms_admin/test_admin.php');	   
	}
}