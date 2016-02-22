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

	public function get_user_data(){
		return  wp_get_current_user();
	}
	
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
	/*get users in group*/
	public function get_group_users($group_id){
		if(is_array($group_id)){
			$query_param=implode(" OR `group_id`=", $group_id);
		}else{
			$query_param=$group_id;
		}
		$query=$this->db->get_results("SELECT `user_id` FROM `".$this->db->prefix."lms_group_users` WHERE  `group_id`=".$query_param);
		if(!$query) return;

		foreach ($query as $key => $value) {
			$get_users[$key]=get_user_by('id', $value->user_id);
		}
		foreach ($get_users as $key => $value) {
			$this->data[$key]=$value->data;
		}
		return $this->data;
	}

	public function get_user_groups(){
		$query=$this->db->get_results("SELECT `group_id` FROM `".$this->db->prefix."lms_group_users` WHERE  `user_id`=".$this->user->ID);
		if($query) return $query;
	}

	public function get_user_fullname($id){
		$this->data['first_name']=get_user_meta($id, 'first_name', true);
		$this->data['last_name']=get_user_meta($id, 'last_name', true);
		return $this->data;
	}


}