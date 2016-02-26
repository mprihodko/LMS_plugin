<?php

Class Users{
	
	/*
	*Vars
	*/
	public $user;	
	public $user_groups;
	public $user_tests;
	private $db;
	private $date;

	/*construct*/

	public function __construct() {
		global $wpdb;
		$this->db=$wpdb;
		$this->data=array();
		$this->user=$this->get_user_data();	
	}



#####################################################################################################################
#																													#
# USER DATA																											#
#																													#
#####################################################################################################################


	public function get_user_data(){
		return  wp_get_current_user();
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
	public function get_user_groups(){
		$groups=$GLOBALS['groups']->get_group("remove", 0);
		foreach ($groups as $key => $value) {

			$query=$this->db->get_results("	SELECT `group_id` 
											FROM `".$this->db->prefix."lms_group_users` 
											WHERE `group_id`=".$value->group_id." 
											AND `user_id`=".$this->user->ID);
			if($query) 
				$groups[]=$query[0];
		}
		if(isset($groups)&& is_array($groups)) return $groups;
	}

	/*get fullname of current user*/
	public function get_user_fullname($id){
		$this->data['first_name']=get_user_meta($id, 'first_name', true);
		$this->data['last_name']=get_user_meta($id, 'last_name', true);
		return $this->data;
	}

	// public function get_group_users($group_id){
	// 	if(is_array($group_id)){
	// 		$query_param=implode(" OR `group_id`=", $group_id);
	// 	}else{
	// 		$query_param=$group_id;
	// 	}
	// 	$query=$this->db->get_results("SELECT `user_id` FROM `".$this->db->prefix."lms_group_users` WHERE  `group_id`=".$query_param);
	// 	if(!$query) return;

	// 	foreach ($query as $key => $value) {
	// 		$get_users[$key]=get_user_by('id', $value->user_id);
	// 	}
	// 	foreach ($get_users as $key => $value) {
	// 		$this->data[$key]=$value->data;
	// 	}
	// 	return $this->data;
	// }



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
		$user_attempts = $GLOBALS['reports']->get_used_attempts($test_id, $group_id);	
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
# ADD USER ROLES																									#
#																													#
#####################################################################################################################




}
