<?php 


class Templates {

	/*vars*/
	public $data;
	public $user;
	public $userrole;
	private $db;


	public function __construct(){
		global $wpdb;
		$this->db=$wpdb;
		$this->user=$GLOBALS['users']->user->ID;
		$this->userrole='visitor';
		if(is_user_logged_in())
			$this->userrole=$GLOBALS['users']->user->roles[0];
	
		add_action( 'pre_get_posts', 				array( $this, 'test_archive'), 1);		
		add_filter( 'template_include', 			array( $this, 'page_templates'));
	}


	public function page_templates($template){
		if(!is_user_logged_in() && !is_page('login') && !is_front_page()){
			wp_redirect(home_url('login'));
		}
		if((is_archive("lms_test") && $this->userrole=='administrator' && !isset($_GET["part"])) || (is_archive("lms_test") && !isset($_GET['group_name']) 
			&& !isset($_GET["part"]))){
			$template=TPL_DIR."user_archive.php";
		}
		if((is_archive("lms_test") && $this->userrole=='administrator' && isset($_GET["part"])) || (is_archive("lms_test") && isset($_GET['group_name']) 
			|| isset($_GET["part"]))){
			$template=TPL_DIR."test_archive.php";
		}
		if((is_archive("lms_test") && !isset($_GET['group_name']) && $this->userrole!='administrator' )){
			$template=TPL_DIR."user_archive.php";
		}
		if(is_page('groups')){	
			$template=TPL_DIR."groups.php";
		}
		if ( is_singular( 'lms_test' )  ) {
			$template=TPL_DIR."test.php";
		}
		return $template; 	
	}

	public function test_archive($query){		
		
		if(is_archive("lms_test") && $query->is_main_query() && $this->userrole!='administrator'){
			$groups=$GLOBALS['users']->get_user_groups();
			if(!$groups){
				$query->set('post__in', array(0));
				return $query;
			}
			
			foreach ($groups as $key => $value) {
				$group_id[$key]=$value->group_id;
			}			
			if(is_array($group_id)){
				$query_param=implode(" OR `group_id`=", $group_id);
			}else{
				$query_param=$group_id;
			}
			$rows=$this->db->get_results("SELECT `test_id` FROM ".$this->db->prefix."lms_group_tests WHERE group_id=".$query_param);
			    	
	    	$arr=array();
	    	foreach ($rows as $key => $value) {
	    		$arr[]=$value->test_id;
	    	} 	
			if(!empty($arr)){			
				$query->set('post__in', ($arr));
			}
			else{				
				$query->set('post__in', array(0));
			}						
		}

		if(is_archive("lms_test") && $query->is_main_query() && isset($_GET['group_name'])){
			$tests=$GLOBALS['groups']->get_group_test($_GET['group_name'],0,0);
			if(!$tests){
				$query->set('post__in', array(0));
				return $query;
			} 
			foreach ($tests as $key => $value) {
				$test_id[$key]=$value->ID;
			}
			if(isset($test_id)){
				$query->set('post__in', ($test_id));
			}else{				
				$query->set('post__in', array(0));
			}			
		}

		return $query;
	}



}