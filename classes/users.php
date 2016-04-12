<?php

Class Users{
	
	/*
	*Vars
	*/
	public $user;	
	public $user_groups;
	public $user_tests;
	private $error;
	private $db;
	private $date;

	private $username;
    private $email;
    private $password; 
    private $password_confirm;   
    private $first_name;
    private $last_name;
    private $nickname;
    private $captcha_resp;
    private $captcha;
    private $group_selected;
	/*construct*/

	public function __construct() {
		global $wpdb;		 
		$this->db=$wpdb;
		$this->data=array();
		$this->user=$this->get_user_data();	

		add_shortcode('lms_registration_form', 		array($this, 'shortcode'));
    	// ajax actions 
    	add_action("wp_ajax_ajaxlogin",				array($this, 'ajax_login'));
    	add_action('wp_ajax_nopriv_ajaxlogin', 		array( $this, 'ajax_login'));
    	add_action("wp_ajax_ajax_register",			array($this, 'ajax_register'));
    	add_action('wp_ajax_nopriv_ajax_register', 	array( $this, 'ajax_register'));
	}



#####################################################################################################################
#																													#
# USER DATA																											#
#																													#
#####################################################################################################################


	public function get_user_data(){
		return  wp_get_current_user();
	}
	
	public function is_user_exists($id=null){
		if($id==null) return false;
		$aux = get_userdata( $id );
		if($aux==false)
			return false;	 
		return true;
	}

#####################################################################################################################
#																													#
# USER DATA																											#
#																													#
#####################################################################################################################


	/*get user tests report*/
	public function get_user_test_report($user_id){
		if(is_array($user_id)){
			$query_param=implode(" OR `user_id`=", $user_id);
		}else{
			$query_param=$user_id;
		}
		$query_results=$this->db->get_results("SELECT * FROM `".$this->db->prefix."lms_test_results` WHERE `user_id`=".$query_param);
		foreach ($query_results as $key => $value) {
 			$this->data[$value->test_id][$value->user_id]['attempts'][]=1;
 			$this->data[$value->test_id][$value->user_id]['results']=$value;
 		}
 		$query_hits=$this->db->get_results("SELECT * FROM `".$this->db->prefix."lms_test_hits` WHERE `user_id`=".$query_param);
		foreach ($query_results as $key => $value) {
 			$this->data[$value->test_id][$value->user_id]['hits'][]=1;
 			$this->data[$value->test_id][$value->user_id]['hit_results']=$value;
 		}
 		if($this->data)
 			return $this->data;

	}

	/*get group for current user*/
	public function get_user_groups($user=null){
		if($user==null) $user=$this->user->ID;
		$groups=$GLOBALS['groups']->get_group("remove", 0);
		foreach ($groups as $key => $value) {

			$query=$this->db->get_results("	SELECT `group_id` 
											FROM `".$this->db->prefix."lms_group_users` 
											WHERE `group_id`=".$value->group_id." 
											AND `user_id`=".$user);
		
			if($query) 
				$groups_user[]=$query[0];
		}
		
		if(isset($groups_user)&& is_array($groups_user)) return $groups_user;
	}

	/*get fullname of current user*/
	public function get_user_fullname($id){
		$this->data['first_name']=get_user_meta($id, 'first_name', true);
		$this->data['last_name']=get_user_meta($id, 'last_name', true);
		return $this->data;
	}


#####################################################################################################################
#																													#
# USER LMS LEVEL PERMISSIONS && ACCESS																				#
#																													#
#####################################################################################################################


	/*is current user level permissions*/
	public function is_user_can($group_id=null){
		if($group_id==null) return false;
		$query=$this->db->get_results("	SELECT `user_level`
										FROM `".$this->db->prefix."lms_group_users`
										WHERE  `user_id`=".$this->user->ID."
										AND `group_id`=".$group_id);
		if(!$query) return false;
		if($query[0]->user_level>0) return true;
		return false;
	}	

	public function have_attempts($test_id=null, $group_id=null){
		if($test_id==null || $group_id==null) return false;
		$test_attempts = $GLOBALS['reports']->get_attempts_limit($test_id, $group_id);
		$user_attempts = $GLOBALS['reports']->get_used_attempts($test_id, $group_id, $this->user->ID);	
		if($test_attempts<=$user_attempts) return false;
		return true;
	}

	public function have_views($test_id=null, $group_id=null){		
		if($test_id==null || $group_id==null) return false;
		$test_views = $GLOBALS['reports']->get_hits_limit($group_id);
		$user_views = $GLOBALS['reports']->get_user_used_views($test_id, $group_id);	
		if($test_views<=$user_views) return false;
		return true;
			
	}


#####################################################################################################################
#																													#
# ADD USER 																											#
#																													#
#####################################################################################################################


	public function registration_form(){ 
		require_once(TPL_DIR."register_new_user.php");
    }

   	public function validation(){
   		$this->error=array();
 		if (!empty($this->captcha) && isset($_COOKIE['current_captcha'])){ 			
 			if($this->captcha!=$_COOKIE['current_captcha']){
 				$this->error['captcha']='<span class="error_message">Captcha not valid</span>';
 				// return new WP_Error('field', $_COOKIE['current_captcha']);
 			}
 		}else{
 			$this->error['captcha']='error';
 			// return new WP_Error('field', $_COOKIE['current_captcha']);
 		}
        if (empty($this->username) || empty($this->password) || empty($this->email) || empty($this->group_selected)) {
        	if(empty($this->username)) $this->error['reg_name']='<span></span>';
        	if(empty($this->password)) $this->error['reg_password']='<span></span>';
        	if(empty($this->email)) $this->error['reg_email']='<span></span>';
        	if(empty($this->group_selected)) $this->error['group_selected']='<span class="error_message">Required form field is missing</span>';	
            // return new WP_Error('field', 'Required form field is missing');
        }
 
        if (strlen($this->username) < 4) {
        	$this->error['reg_name']='<span class="error_message">Username too short. At least 4 characters is required</span>';
            // return new WP_Error('username_length', 'Username too short. At least 4 characters is required');
        }
 
        if (strlen($this->password) < 5){ 
        	$this->error['reg_password']='<span class="error_message">Password length must be greater than 5</span>';
            // return new WP_Error('password', 'Password length must be greater than 5');
        }
 		if($this->password != $this->password_confirm) {
 			$this->error['reg_password']=' ';
 			$this->error['confirm_reg_password']='<span class="error_message">Passwords don\'t match</span>';
 			// return new WP_Error('password', 'Passwords don\'t match');
 		}
        if (!is_email($this->email)) {
        	$this->error['reg_email']='<span class="error_message">Email is not valid</span>';
            // return new WP_Error('email_invalid', 'Email is not valid');
        }
 
        if (email_exists($this->email)) {
        	$this->error['reg_email']='<span class="error_message">Email Already in use</span>';
            // return new WP_Error('email', 'Email Already in use');
        }
 		
 		if(!empty($this->group_selected)){
 			// var_dump($GLOBALS['groups']->is_group_exists($this->group_selected));
 			if(!$GLOBALS['groups']->is_group_exists($this->group_selected))
 				$this->error['group_selected']='<span class="error_message">Please Check Your Group ID</span>';	
 				// return new WP_Error('field', 'Please Check Your Group ID');
 		}

        $details = array('reg_name' => $this->username,
            'reg_fname' => $this->first_name,
            'reg_lname' => $this->last_name,
                       
        );
 
        foreach ($details as $field => $detail) {
            if (!validate_username($detail)) {   
            	$this->error[$field]='<span class="error_message">Sorry, the "' . $field . '" you entered is not valid</span>';         	
                // return new WP_Error('name_invalid', 'Sorry, the "' . $field . '" you entered is not valid');
            }
        }
 		return $this->error;
    }

   	public function registration($ajax=false){
 
	    $userdata = array(
	        'user_login' => esc_attr($this->username),
	        'user_email' => esc_attr($this->email),
	        'user_pass' => esc_attr($this->password),        
	        'first_name' => esc_attr($this->first_name),
	        'last_name' => esc_attr($this->last_name),
	        'nickname' => esc_attr($this->nickname),       
	        'role' => "customer"
	    );

        $register_user = wp_insert_user($userdata);
        if (!is_wp_error($register_user)) {	 
            $GLOBALS['groups']->add_user_in_group($GLOBALS['groups']->is_group_exists($this->group_selected), $register_user);            
            if(!$ajax): 
        		echo '<script type="text/javascript"> window.location="/login";</script>';
			elseif($ajax):
				echo json_encode(array("success" => "success"));	 
			endif;         
        } elseif(!$ajax) {
            echo '<strong>' . $register_user->get_error_message() . '</strong>';          
        } else {
        	echo json_encode(array("WP_error" => $register_user->get_error_message()));
        }
	}
 
 	public function shortcode(){
 
        ob_start();
 
        if (isset($_POST['reg_submit'])) {
        	$this->captcha=((isset($_POST['captcha']))? $_POST['captcha'] : '');        	
            $this->username = ((isset($_POST['reg_name']))? $_POST['reg_name'] : '');
            $this->email = ((isset($_POST['reg_email']))? $_POST['reg_email'] : '');
            $this->password = ((isset($_POST['reg_password']))? $_POST['reg_password'] : '');
           	$this->password_confirm = ((isset($_POST['confirm_reg_password']))? $_POST['confirm_reg_password'] : '');
            $this->first_name = ((isset($_POST['reg_fname']))? $_POST['reg_fname'] : '');
            $this->last_name = ((isset($_POST['reg_lname']))? $_POST['reg_lname'] : '');
            $this->nickname = ((isset($_POST['reg_fname']))? $_POST['reg_fname'] : '');           
            $this->group_selected=((isset($_POST['group_selected']))? $_POST['group_selected'] : ''); 
            if(count($this->validation())==0){
            	$this->registration();
            }
            else
            	echo implode(' ', $this->error);
            	// return new WP_Error('field', 'Sorry, you entered is not valid information');
        }
 
        $this->registration_form();
        return ob_get_clean();
    }

    public function the_captcha(){
		$first_num=rand(1, 15);	
		$second_num=rand(1, 15);
		?>
		<style>#captcha{width: 100px; display: inline-block;} .captcha_label{padding: 5px 0; position: relative; display: inline-block; top: 3px;}</style>
		<label class="login-field-icon fui-new captcha_label" for="captcha">
			<?php echo $first_num.' + '.$second_num.' ='; ?>
		</label>
        <input name="captcha" size="4" type="text" class="form-control login-field <?=isset($this->error['captcha']) ? 'error' : ''?>" id="captcha" /> 
        <script type="text/javascript">
			jQuery(document).ready(function(){		
				jQuery.cookie('current_captcha', '<?=$first_num + $second_num?>', {
				    expires: 1,
				    path: '/',
				});
			})
		</script>     
		<?php
		
	}

	public function ajax_login(){

	    // Nonce is checked, get the POST data and sign user on
	    $info = array();
	    $info['user_login'] = $_POST['username'];
	    $info['user_password'] = $_POST['password'];
	    $info['remember'] = $_POST['remember'];

	    $user_signon = wp_signon( $info, false );
	    if ( is_wp_error($user_signon) ){
	        echo json_encode(array('loggedin'=>false, 'message'=>__('Wrong username or password.')));
	    } else {    	
	        echo json_encode(array('loggedin'=>true));
	    }

	    wp_die();
	}

	public function ajax_register(){
		if(isset($_POST)){
			$this->captcha=((isset($_POST['captcha']))? $_POST['captcha'] : '');        	
            $this->username = ((isset($_POST['reg_name']))? $_POST['reg_name'] : '');
            $this->email = ((isset($_POST['reg_email']))? $_POST['reg_email'] : '');
            $this->password = ((isset($_POST['reg_password']))? $_POST['reg_password'] : '');
           	$this->password_confirm = ((isset($_POST['confirm_reg_password']))? $_POST['confirm_reg_password'] : '');
            $this->first_name = ((isset($_POST['reg_fname']))? $_POST['reg_fname'] : '');
            $this->last_name = ((isset($_POST['reg_lname']))? $_POST['reg_lname'] : '');
            $this->nickname = ((isset($_POST['reg_fname']))? $_POST['reg_fname'] : '');           
            $this->group_selected=((isset($_POST['group_selected']))? $_POST['group_selected'] : '');
            if(count($this->validation())==0){
            	$this->registration(true);
            }
            else{            	
            	echo json_encode($this->error); 	
            }
		}
		wp_die();
	}
}
