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
	public $insert_id;
	public $product_insert_id;
	private $error;
	private $db;
	private $group_per_page;


	/**construct**/
	public function __construct(){
		global $wpdb;
		$this->db=$wpdb;		
		$this->data=array();
		$this->user=$GLOBALS['users']->user->ID;		
		if(isset($_POST['lms_gropup_name'])){			 
			$this->insert_id=$this->save_groups();
			if($this->insert_id>0){
				$this->save_group_test($this->insert_id);
				$this->save_group_users($this->insert_id);
				$this->save_group_courses($this->insert_id);
			}else{
				$this->error='<h3 style="color: red; ">ERROR: Group with this ID already exists</h3>';
			}			 
		}else{
			$this->insert_id=((isset($_GET['group']))?$_GET['group']:0);	
		}
		$this->group_id=((isset($_GET['group']))?$_GET['group']:0);
		$this->group_data=$this->get_group();	
		$this->group_per_page=20;
		// if(isset($_POST['lms_product_group_name'])){
		// 	$this->product_insert_id=$this->save_product_group();			
		// }else{
		$this->product_insert_id=((isset($_GET['group']))?$_GET['group']:0);
		// }
		/*actions*/
		add_action( 'admin_init', array($this, 'script_admin_init'));
		add_action( 'admin_menu', array($this, 'lms_groups_list_menu'));

		/*AJAX*/
		add_action("wp_ajax_if_group_exists",			array($this, 'is_group_exists'));
    	add_action('wp_ajax_nopriv_if_group_exists', 	array($this, 'is_group_exists'));		

    	add_action("wp_ajax_save_product_group",			array($this, 'save_product_group'));
    	add_action('wp_ajax_nopriv_save_product_group', 	array($this, 'save_product_group'));	

    	add_action("wp_ajax_delete_product_group",			array($this, 'deleteProductGroup'));
    	add_action('wp_ajax_nopriv_delete_product_group', 	array($this, 'deleteProductGroup'));	
	} 


	/*  Register script  */
	public function script_admin_init() {
		wp_register_script( 'tests-edit-page-script', ASSETS_DIR . 'js_min/admin.min.js', array('jquery'), '1.0', true );
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
							 					'Add New Group',
							 					'delete_published_posts',
							 					'lms_groups_edit',
							 					array(	$this,
							 					 		'lms_groups_edit_admin'
							 					 	)
		
						 				);		
		add_action('admin_print_scripts-'.$page_hook_suffix, array($this, 'add_admin_scripts'));

		$page_hook_suffix = add_submenu_page( 	'lms_groups',
							 					'Group Management',
							 					'Group Templates',
							 					'delete_published_posts',
							 					'lms_product_groups',
							 					array(	$this,
							 					 		'lms_product_groups_admin'
							 					 	)
		
						 				);		
		add_action('admin_print_scripts-'.$page_hook_suffix, array($this, 'add_admin_scripts'));	

		$page_hook_suffix = add_submenu_page( 	'lms_groups',
							 					'Group Management',
							 					'Add New Template',
							 					'delete_published_posts',
							 					'lms_product_groups_edit',
							 					array(	$this,
							 					 		'lms_product_groups_edit'
							 					 	)
		
						 				);		
		add_action('admin_print_scripts-'.$page_hook_suffix, array($this, 'add_admin_scripts'));

		$page_hook_suffix = add_submenu_page( 	'lms_groups',
							 					'Group Management',
							 					'Product Categories',
							 					'delete_published_posts',
							 					'edit-tags.php?taxonomy=product_type&post_type=lms_product'
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

	public function is_group_exists($text_id){
		if($text_id==null)
			if(!isset($_POST['ajax_text_id']))
				return false;
			else
				$text_id=$_POST['ajax_text_id'];
		if(!isset($_POST['ajax_text_id'])){
			$query=$this->db->get_results("SELECT * FROM `".$this->db->prefix."lms_groups` WHERE `text_id`='".$text_id."'");
			if(!$query) $query=$this->db->get_results("SELECT * FROM `".$this->db->prefix."lms_groups` WHERE `text_id`=".$text_id);
			if(!$query)	return false;
			return $query[0]->group_id;
		}else{
		$groups=array();
		parse_str($text_id, $groups);	
		
		$exist=0;
		foreach ($groups['group_id'] as $key => $text_id) {		
			$query=$this->db->get_results("SELECT * FROM `".$this->db->prefix."lms_groups` WHERE `text_id`='".$text_id."'");
			if(!$query) $query=$this->db->get_results("SELECT * FROM `".$this->db->prefix."lms_groups` WHERE `text_id`=".$text_id);			
			if(count($query)!=0){
				$exist++;
				$groups_ex[]=$key+1;
			}	

		}
			if($exist==0 || $this->get_owner_group($query[0]->group_id)==$this->user) echo json_encode(array("exist"=>"ok"));
			else echo json_encode(array("exist"=>"fail", "groups"=>$groups_ex));
			wp_die();
		}
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
		if(!$query){
			$query_param="`".$field."`='".$val."'";
			$query=$this->db->get_results("SELECT * FROM `".$this->db->prefix."lms_groups` WHERE ".$query_param);		
		}
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
				if(property_exists($value, 'test_id') && property_exists($value, 'view_limit')){
					if(get_post($value->test_id)){
						$tests[$key]=get_post($value->test_id);
						$tests[$key]->view_limit=$value->view_limit;
					}else{
						$this->db->delete($this->db->prefix."lms_group_tests", array('test_id' => $value->test_id));	
					}
				}
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
		if(is_object($query) || is_array($query)){	
			foreach ($query as $key => $value) {				
				if($GLOBALS['users']->is_user_exists($value->user_id)){
					$users[$key]=get_user_by('id', $value->user_id);
					$users[$key]->user_level=$value->user_level;
				}else{
					$this->db->delete($this->db->prefix."lms_group_users", array('user_id'=>$value->user_id));
				}
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
		if(isset($_POST['lms_gropup_name'])){			
			$new_group['group_id']=$this->insert_id;
			$new_group['name']=((isset($_POST['lms_gropup_name']))?strip_tags(trim($_POST['lms_gropup_name'])) : '');
			$new_group['view_limit']=((isset($_POST['view_limit_group']))?strip_tags(trim($_POST['view_limit_group'])) : '');
			$new_group['text_id']=((isset($_POST['text_id']))?strip_tags(trim($_POST['text_id'])) : '');
			$new_group['description']=((isset($_POST['description']))?strip_tags(trim($_POST['description'])) : '');
			$new_group['user_id']=$this->user;
			$new_group['notice']=((isset($_POST['notice']))?strip_tags(trim($_POST['notice'])) : '');
			$new_group['group_test_view']=((isset($_POST['group_test_view']))?strip_tags(trim($_POST['group_test_view'])) : '');
			$new_group['video_review']=((isset($_POST['video_review']))?strip_tags(trim($_POST['video_review'])) : '');
			$new_group['video_demand']=((isset($_POST['video_demand']))?strip_tags(trim($_POST['video_demand'])) : '');
			$new_group['remove']='0';
			$group=(object)$new_group;
		}
		$tests=$this->get_group_test();
		$users=$this->get_group_users();
		$courses=$this->get_group_courses();		
		/* include template */			
		require_once(IAMD_TD.'/admin/groups/group_edit_tpl.php');	
	}

	/*Callback functions group template edit page admin*/
	public function lms_product_groups_admin(){
		$args=array(
					'numberposts'   => 20, 
					'offset'        => 0,
					"post_type"		=>'lms_product',
					'meta_key'   	=> '_lms_product_type',
   					'meta_value' 	=> 'groups'
					);
		$products=get_posts($args);
		$pagination='';
		require_once(IAMD_TD.'/admin/groups/product_group_tpl.php');	
		wp_reset_postdata();
	}

	public function lms_product_groups_edit(){
		$product=get_post($this->product_insert_id);		
		$tests_in=unserialize(get_post_meta($this->product_insert_id, "_lms_tests", true));
		if(is_array($tests_in)){
			foreach ($tests_in as $key => $value) {
				$tests[]=get_post($value);
			}
		}
		require_once(IAMD_TD.'/admin/groups/product_group_edit.php');	
		wp_reset_postdata();
	}

	public function deleteProductGroup(){
		// $postid=$_POST['id'];
		
		do_action( 'delete_post', $_POST['id']);	
		if(wp_delete_post($_POST['id'], true)){
			echo json_encode(array("status"=>"success"));
		}else{
			echo json_encode(array("status"=>"Failed"));
		}
		wp_die();

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


	/* Save Product Groups */
	public function save_product_group(){
		$params=array();
		parse_str($_POST['data_value'], $params);		
		$tests=array();
		if(isset($params['tests']) && is_array($params['tests'])){
			foreach ($params['tests'] as $key => $value) {
				$tests[]=$key;
			}
		}		
		if($params['group_id']>0){	
			$args=array(
				"ID"			=> $params['group_id'],
				"post_title"    => wp_strip_all_tags( $params['lms_product_group_name'] ),
				"post_type"		=>'lms_product',
				"post_status"	=>"publish",
				'tax_input'      => array( 'product_type' => array( 'goods_groups' ))				
				);
				$the_id=wp_update_post($args);
				update_post_meta($params['group_id'], '_lms_price', $params['lms_gropup_price']);
				update_post_meta($params['group_id'], '_lms_product_type', "groups");
				update_post_meta($params['group_id'], '_lms_tests', serialize($tests));	
				$terms=array('goods_groups');

				if(isset($params['groups_terms'])){
					foreach ($params['groups_terms'] as $key => $value) {
						$terms[]=$value;
					}
				}				
				wp_set_object_terms( $params['group_id'], $terms, 'product_type', false );
				$this->product_insert_id=$params['group_id'];					
			echo $params['group_id'];
		}else{			
			$args=array(
				"post_title"    => wp_strip_all_tags( $params['lms_product_group_name'] ),
				"post_type"		=>'lms_product',
				"post_status"	=>"publish",
				'tax_input'      => array( 'product_type' => array( 'goods_groups' ))			
				);
			$the_id=wp_insert_post($args);			
			update_post_meta($the_id, '_lms_price', $params['lms_gropup_price']);
			update_post_meta($the_id, '_lms_product_type', "groups");
			update_post_meta($the_id, '_lms_tests', serialize($tests));
			$terms=array('goods_groups');
			if(isset($params['groups_terms'])){
				foreach ($params['groups_terms'] as $key => $value) {
					$terms[]=$value;
				}
			}		
			wp_set_object_terms($the_id, $terms, 'product_type', false );
			$this->product_insert_id=$the_id;
			echo $the_id;
		}
		wp_die();
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
			
			// unset($this->data['text_id']);
			$query=$this->db->update($this->db->prefix."lms_groups", $this->data, array("group_id"=>$this->data['group_id']), $format = null, $where_format = null);
			return $this->data['group_id'];
		else:
			unset($this->data['group_id']);
			if(!$this->is_group_exists($this->data['text_id'])){
				$query=$this->db->insert($this->db->prefix."lms_groups", $this->data);
				$query=$this->db->insert($this->db->prefix."lms_group_users", array("group_id"=>$this->db->insert_id,
																					"user_id"=>$this->user,
																					"user_level"=>2
																					)
																				);
				return $this->db->insert_id;
			}else{
				return 0;
			}
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

	public function get_owner_group($group_id=null){
		if($group_id==null) return;		
		$query=$this->db->get_results("	SELECT `user_id`
										FROM  ".$this->db->prefix."lms_group_users
										WHERE `group_id`=".$group_id." 
										AND `user_level`=2");
		return	$query[0]->user_id;									

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
		global $post;
		$query=$this->db->get_results("SELECT `group_id` FROM `".$this->db->prefix."lms_group_tests` WHERE `test_id`=".$post->ID);
		if(!$query) return false;
		foreach ($query as $key => $value) {
			if(isset($_COOKIE['current_group']))
				if($value->group_id == $_COOKIE['current_group']) return $value->group_id;
		}
	}

	public function add_user_in_group($group_id, $user_id){
		if($group_id==null || $user_id==null){
			return false;
		}else{
			$this->db->insert($this->db->prefix."lms_group_users", array(	'user_id'=>$user_id,
																	 		'group_id'=>$group_id,
																	 		'user_level'=>0
																	 		));
		}
	}



}
