<?php

Class Groups {
	
	/*
	*Vars
	*/
	public $data;
	public $group_id;
	public $user;
	public $pagination;
	public $group_data;	
	private $db;
	private $group_per_page;


	/**construct**/
	public function __construct(){
		global $wpdb;
		$this->db=$wpdb;		
		$this->data=array();
		$this->user=$GLOBALS['users']->user->ID;		
		if(isset($_POST['lms_gropup_name'])){			 
			$insert_id=$this->save_groups();
			$this->save_group_test($insert_id);
			$this->save_group_users($insert_id);
			$this->save_group_courses($insert_id);			 
		}
		$this->group_id=((isset($_GET['group']))?$_GET['group']:0);	
		$this->group_data=$this->get_group();	
		$this->group_per_page=20;
		
		/*actions*/
		add_action( 'admin_init', array($this, 'script_admin_init'));
		add_action( 'admin_menu', array($this, 'lms_groups_list_menu'));		
	} 


	/*  Register script  */
	public function script_admin_init() {
		wp_register_script( 'group-edit-page-script', ASSETS_DIR . 'js_min/admin.min.js', array('jquery'), '1.0', true );
	}

	/*ADD MENU PAGE */	
	public function lms_groups_list_menu() {
  		$page_hook_suffix = add_menu_page( 	'Groups',
					  					   	'Groups',
					  					   	'delete_published_posts',
					  					   	'lms_groups',
					  					   	array( 	$this,
					  					   	 	 	'lms_groups_list_admin'
					  					   		 ),
					  					   	'dashicons-schedule',
					  					   	72 
					  					 );
  		add_action('admin_print_scripts-'.$page_hook_suffix, array($this, 'add_admin_scripts'));

		$page_hook_suffix = add_submenu_page( 	'lms_groups',
							 					'Group Management',
							 					'Add New',
							 					'delete_published_posts',
							 					'lms_groups_edit',
							 					array(	$this,
							 					 		'lms_groups_edit_admin'
							 					 	)
		
						 				);		
		add_action('admin_print_scripts-'.$page_hook_suffix, array($this, 'add_admin_scripts'));	
	}

	/*  enqueue script  */
	public function add_admin_scripts() {		
		wp_enqueue_script( 'group-edit-page-script' );
	}

	/*GET GROUPS*/	
	public function get_groups($orderby='name', $offset=0, $limit=null, $order_type="ASC"){
		$query_param="";
		if($orderby!=null)	
			$query_param.="ORDER BY `".$orderby."` ".$order_type." ";		
		if($limit!=null)
		 	$query_param.="LIMIT ".$offset.", ".$limit." ";		
		if($GLOBALS['users']->user->roles[0]=='administrator'){
			$query=$this->db->get_results("SELECT * FROM `".$this->db->prefix."lms_groups` ".$query_param);			
		}else{
			$group_by_user=$GLOBALS['users']->get_user_groups($this->user);
			foreach ($group_by_user as $k => $v){
				$group_id[$k]=$v->group_id;
			}			
			$query=$this->get_group("group_id", $group_id);
		}
		return $query;
	}

	/*GET GROUPS FRONTEND*/	
	public function lms_groups_list_frontend(){
		/* define current page */
		if ( !$current_page = get_query_var('paged') )
		    $current_page = 1;  
		/* get pages */
	   
	    /*define offset*/
	    if($current_page>1) $offset=$current_page*$this->group_per_page; else $offset=0;
	    /*get groups*/
	    if($GLOBALS['users']->user->roles[0]=='administrator'){		    	
			$groups=$this->get_groups('name', $offset, $this->group_per_page);
			$this->pagination=$this->group_pagination($current_page, false);
		}else{
			$group_by_user=$GLOBALS['users']->get_user_groups($this->user);
			if($group_by_user){
				foreach ($group_by_user as $k => $v) {
					$group_id[$k]=$v->group_id;
				}
				$groups=$this->get_group("group_id", $group_id);
				$this->pagination=$this->group_pagination($current_page, false);
			}
		}
		if(isset($groups))
			return $groups;
	}

	/*GET GROUP BY */
	public function get_group($field='group_id', $value=null){
		if($value==null){
			$val=$this->group_id;
		}elseif(is_array($value)){
			$val=implode(" OR `".$field."`=", $value);
		}else{
			$val=$value;	
		}
		$query_param="`".$field."`=".$val;		
		$query=$this->db->get_results("SELECT * FROM `".$this->db->prefix."lms_groups` WHERE ".$query_param);
		return $query;
	}


	/*Get Group Tests*/
	public function get_group_test($group_id=null, $offset=0, $limit=10){ 
		if($group_id==null){ 
			$group_id=$this->group_id;	
		}
		if(is_array($group_id)){ 
			$query_param=implode(" OR `group_id`=", $group_id);
		}else{ 
			$query_param=$group_id;	
		}
		if($limit>0){
			$query_param.=" LIMIT ".$offset.", ".$limit." ";		
		}
		$query=$this->db->get_results("SELECT * FROM `".$this->db->prefix."lms_group_tests` WHERE `group_id`=".$query_param);
		if(is_object($query)|| is_array($query)){	
			foreach ($query as $key => $value) {
				$tests[$key]=get_post($value->test_id);
				$tests[$key]->view_limit=$value->view_limit;
			}
			
		}
		if(isset($tests)) return $tests;
		return;		
	}


	/* get group users*/
	public function get_group_users($group_id=null, $offset=0, $limit=10){
		if($group_id==null){ 
			$group_id=$this->group_id;	
		}
		if(is_array($group_id)){ 
			$query_param=implode(" OR `group_id`=", $group_id);
		}else{ 
			$query_param=$group_id;	
		}
		if($limit>0)
			$query_param.=" LIMIT ".$offset.", ".$limit." ";		
		$query=$this->db->get_results("SELECT * FROM `".$this->db->prefix."lms_group_users` WHERE `group_id`=".$query_param);
		if(is_object($query)|| is_array($query)){	
			foreach ($query as $key => $value) {
				$users[$key]=get_user_by('id', $value->user_id);
				$users[$key]->user_level=$value->user_level;
			}
		}
		if(isset($users)) return $users;
		return;
	}


	/* get group courses*/
	public function get_group_courses($group_id=null, $offset=0, $limit=10){
		if($group_id==null){ 
			$group_id=$this->group_id;	
		}
		if(is_array($group_id)){ 
			$query_param=implode(" OR `group_id`=", $group_id);
		}else{ 
			$query_param=$group_id;	
		}
		$query_param.=" LIMIT ".$offset.", ".$limit." ";		
		$query=$this->db->get_results("SELECT * FROM `".$this->db->prefix."lms_group_courses` WHERE `group_id`=".$query_param);
		
		if(is_object($query) || is_array($query)){
				foreach ($query as $key => $value) {
					$courses[$key]=get_post($value->course_id);		
				}
		}		
		if(isset($courses)) return $courses;		
		return;
	}


	



	/*Callback functions group page admin*/
	public function lms_groups_list_admin(){
		/* define current page */
			if ( !$current_page = ((isset($_GET['paged']))?$_GET['paged']:0)) $current_page = 0;
		/* get pages */
	    	$pagination=$this->group_pagination($current_page);
	    /*define offset*/
	    	if($current_page>0) $offset=$current_page*$this->group_per_page; else $offset=0;
	    /*get groups*/
			$groups=$this->get_groups('name', $offset, $this->group_per_page);			
		/* include template */
	    	require_once(IAMD_TD.'/admin/groups/groups_tpl.php');	   
	}
	

	/*Callback functions group edit page admin*/
	public function lms_groups_edit_admin(){
		/* get group data*/
		$new_group=array(
					'group_id'			=> 0,
					'name' 				=> 'Group Name',
					'view_limit' 		=> 0, 
					'text_id' 			=> '',
					'description' 		=> 'Group Description', 
					'user_id' 			=> $this->user, 
					'notice' 			=> 'Group Notes',
					'video_demand' 		=> 0, 
					'video_review' 		=> 0, 
					'group_test_view' 	=> 0, 
					'remove'			=> ''
			);
		$group=(object)$new_group;
		if(array_key_exists(0, $this->group_data))
			$group=$this->group_data[0];
		$tests=$this->get_group_test();
		$users=$this->get_group_users();
		$courses=$this->get_group_courses();		
		/* include template */			
		require_once(IAMD_TD.'/admin/groups/group_edit_tpl.php');	
	}


	/*GROUPS Pagination*/
	public function group_pagination($current_page, $admin=true){
		$pages=ceil(count($this->get_groups())/$this->group_per_page);	
		$output='';	
		$link=home_url("groups/page/");
		if($admin) $link=admin_url("admin.php?page=lms_groups&paged=");
		for($page=1; $page<$pages; $page++){
			if(($current_page==0 && $page==1) || $current_page==$page){
				$output.="<span class='page_icon' >{$page}</span>";
			}else{
				if($admin){
					$output.="<a class='page_icon' href='".$link.$page."'>{$page}</a>";
				}else{
					$output.='<a class="next-page" href="'.$link.$page.'"><span aria-hidden="true">'.$page.'</span></a>';
				}
			}
		}
		return $output;
	}
	public function ingroup_users($group_id=null, $flag='count'){
		if($group_id==null) return 0;
		$query=$this->db->get_results("	SELECT *
										FROM `".$this->db->prefix."lms_group_users`
										WHERE  `group_id`=".$group_id);
		switch ($flag) {
			case 'count':
				if($query) return count($query);
				break;
			
			case 'users':
				if($query) return $query;
				break;
		}
	}


	/* Save Groups */

	public function save_groups(){
		$this->data['group_id']=((isset($_POST['group_id']))?strip_tags(trim($_POST['group_id'])) : '');
		$this->data['name']=((isset($_POST['lms_gropup_name']))?strip_tags(trim($_POST['lms_gropup_name'])) : '');
		$this->data['view_limit']=((isset($_POST['view_limit_group']))?strip_tags(trim($_POST['view_limit_group'])) : '');
		$this->data['text_id']=((isset($_POST['text_id']))?strip_tags(trim($_POST['text_id'])) : '');
		$this->data['description']=((isset($_POST['description']))?strip_tags(trim($_POST['description'])) : '');
		$this->data['user_id']=$this->user;
		$this->data['notice']=((isset($_POST['notice']))?strip_tags(trim($_POST['notice'])) : '');
		$this->data['group_test_view']=((isset($_POST['group_test_view']))?strip_tags(trim($_POST['group_test_view'])) : '');
		$this->data['video_review']=((isset($_POST['video_review']))?strip_tags(trim($_POST['video_review'])) : '');
		$this->data['video_demand']=((isset($_POST['video_demand']))?strip_tags(trim($_POST['video_demand'])) : '');
		$this->data['remove']='0';
		if($this->data['group_id'] > 0):
			$query=$this->db->update($this->db->prefix."lms_groups", $this->data, array("group_id"=>$this->data['group_id']), $format = null, $where_format = null);
			return $this->data['group_id'];
		else:
			unset($this->data['group_id']);
			$query=$this->db->insert($this->db->prefix."lms_groups", $this->data);
			$query=$this->db->insert($this->db->prefix."lms_group_users", array("group_id"=>$this->db->insert_id,
																				"user_id"=>$this->user,
																				"user_level"=>2
																				)
																			);
			return $this->db->insert_id;
		endif;
		
	}

	public function save_group_test($group_id=null){
		/*check group id*/
		if($group_id==null) return;		

		if(isset($_POST['view_limit']) && is_array($_POST['view_limit'])){

			foreach ($_POST['view_limit'] as $key => $value) {
				
				if($value==0 && isset($_POST['group_test_view']))
					$value=strip_tags(trim($_POST['group_test_view']));

				/*insert*/
				$query=$this->db->get_results("	SELECT `test_id`
												FROM  ".$this->db->prefix."lms_group_tests
												WHERE `group_id`=".$group_id."
												AND `test_id`=".$key );			
				if(isset($query[0]->test_id) && $query[0]->test_id==$key):
					$query=$this->db->update($this->db->prefix."lms_group_tests",
											 array(	'view_limit'=>strip_tags(trim($value))),
											 array(	"group_id"=>$group_id,
											 	   	"test_id"=>$key)
											);
				else:
					$query=$this->db->insert($this->db->prefix."lms_group_tests",
											 array(	'test_id'=>$key,
											 		'group_id'=>$group_id,
											 		'view_limit'=>strip_tags(trim($value))
											 		)
											);
				endif;								
			}
		}			
	}

	public function save_group_users($group_id=null){
		/*check group id*/
		if($group_id==null) return;

		if(isset($_POST['userlevel']) && is_array($_POST['userlevel'])){

			foreach ($_POST['userlevel'] as $key => $value) {

				/*insert*/
				$query=$this->db->get_results("	SELECT `user_id`
												FROM  ".$this->db->prefix."lms_group_users
												WHERE `group_id`=".$group_id."
												AND `user_id`=".$key );	

				if(isset($query[0]->user_id) && $query[0]->user_id==$key):
					$query=$this->db->update($this->db->prefix."lms_group_users",
											 array(	"user_level"=>strip_tags(trim($value))),
											 array(	"group_id"=>$group_id, 
											 		"user_id"=>$key)
											);

				else:

					$query=$this->db->insert($this->db->prefix."lms_group_users",
											 array(	'user_id'=>$key,
											 		'group_id'=>$group_id,
											 		'user_level'=>strip_tags(trim($value))
											 		)
											);

				endif;			
			}
		}
	}

	public function save_group_courses($group_id=null){
		/*check group id*/
		if($group_id==null) return;		

		if(isset($_POST['courses']) && is_array($_POST['courses'])){

			foreach ($_POST['courses'] as $key => $value) {
				
				/*insert*/
				$query=$this->db->get_results("	SELECT `course_id`
												FROM  ".$this->db->prefix."lms_group_courses
												WHERE `group_id`=".$group_id."
												AND `course_id`=".$key );
				
				if(count($query)==0):
					$query=$this->db->insert($this->db->prefix."lms_group_courses",
											 array(	'course_id'=>$key,
											 		'group_id'=>$group_id											 		
											 		)
											);
				endif;					
			}

		}

	}

	public function identify_group(){
		if($_COOKIE['current_group']=='administrator') return true;
		global $post;
		$query=$this->db->get_results("SELECT `group_id` FROM `".$this->db->prefix."lms_group_tests` WHERE `test_id`=".$post->ID);
		if(!$query) return false;
		foreach ($query as $key => $value) {
			if($value->group_id == $_COOKIE['current_group']) return $value->group_id;
		}
	}



}
