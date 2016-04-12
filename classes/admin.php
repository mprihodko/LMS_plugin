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
		wp_register_script( 'tests-edit-page-script', ASSETS_DIR . 'js_min/admin.min.js', array('jquery'), '1.0', true );
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
		add_action('admin_print_scripts-'.$page_hook_suffix, 	array($this, 'add_admin_scripts'));		

		$page_hook_suffix = add_submenu_page( 	'lms_admin_setup',
							 					'PayPal Settings',
							 					'PayPal Settings',
							 					'administrator',
							 					'lms_admin_paypal_setup',
							 					array(	$this,
							 					 		'lms_admin_paypal_setup'
							 					 	)
		
						 				);		
		add_action('admin_print_scripts-'.$page_hook_suffix, 	array($this, 'add_admin_scripts'));		
	}

	/*  enqueue script  */
	public function add_admin_scripts() {		
		wp_enqueue_script( 'group-edit-page-script' );
	}

	public function lms_admin_paypal_setup(){
		if(isset($_POST['paypal_api_submit'])){
			$paypal['mode']					=$_POST['paypal_mode'];
			$paypal['api_username']			=$_POST['paypal_api_username'];
			$paypal['api_password']			=$_POST['paypal_api_password'];
			$paypal['api_signature']		=$_POST['paypal_api_signature'];
			$paypal['api_id']				=$_POST['paypal_api_id'];
			$paypal['api_client_secret']	=$_POST['paypal_api_client_secret'];
			$paypal['api_client_id']		=$_POST['paypal_api_client_id'];
			$pp_option=get_option( "_lms_pay_pal_settings", false);
			if(!$pp_option)
				add_option("_lms_pay_pal_settings", serialize($paypal), '', 'no');
			else
				update_option("_lms_pay_pal_settings", serialize($paypal), 'no'); 
		}
		$pp_option=unserialize(get_option( "_lms_pay_pal_settings", false));
 		require_once(IAMD_TD.'/admin/lms_admin/paypal.php');
	}

	public function lms_admin_setup_test_page(){
		require_once(IAMD_TD.'/admin/lms_admin/test_admin.php');	   
	}
}