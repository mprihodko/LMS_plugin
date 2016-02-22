<?php 

class Get_Report_Tests extends AJAX_Handler {

	function callback() {
		$query= $this->db->get_results(
						        	'SELECT ID, post_title, post_author
						        	 FROM '.$this->db->posts.'
						        	 WHERE post_type=\'lms_test\'							        	 
						        	 AND post_title LIKE \'%'.$this->db->escape($_GET['value']).'%\' LIMIT 10'
						        	);		
		foreach ($query as $k => $v) {
			$test_find[]=$v;
		}		
		echo json_encode($test_find);
		die();
	}

}

new Get_Report_Tests('search_tests_report');

class Get_Report_Groups extends AJAX_Handler {

	function callback() {
		$groups = $this->db->get_results(
							        	'SELECT *
							        	 FROM '.$this->db->prefix.'lms_groups
							        	 WHERE name LIKE \'%'.$this->db->escape($_GET['value']).'%\' LIMIT 10'
							        	);
		foreach($groups as $k=>$v):
			if($v->name=='')
				$v->name="no name - ".$v->group_id;		
			$group[$k]=$v;
		endforeach;	
		if(isset($group))
			echo json_encode($group);
		die();
	}

}

new Get_Report_Groups('search_groups_report');


class Get_Report_Users extends AJAX_Handler {

	function callback() {

       	$users = $this->db->get_results('SELECT ID, user_login 
       									 FROM '.$this->db->users.' 
       									 WHERE user_login LIKE \'%'.$this->db->escape($_GET['value']).'%\'
       									 LIMIT 10');
		foreach($users as $key => $val):
			$customer[$key]=$val;			
		endforeach;	
		echo json_encode($customer);
		die();
	}
}

new Get_Report_Users('search_users_report');


class Get_Reports_By_Group extends AJAX_Handler{

	function callback(){
		$groups_id=$_POST['data_value'];
		if(!is_array($groups_id)) return;

		/*define filters*/		
		$filter['attempts']=strip_tags(trim($_POST['attempts']));
		$filter['hits']=strip_tags(trim($_POST['hits']));
		$filter['date_from']=strip_tags(trim($_POST['day_from']));
		$filter['date_to']=strip_tags(trim($_POST['day_to']));

		// var_dump($filter);
		$tests=$GLOBALS['tests']->get_group_tests($groups_id);
		foreach ($tests as $key => $value) {
			$test_results[$value->ID]=$GLOBALS['reports']->get_group_tests_reports($value->ID)[$value->ID];
			$test_results[$value->ID]['test_info']=$value;
		}
		// var_dump($test_results);
		/* parse data tests */
		$response=$GLOBALS['reports']->generate_report_table($test_results, $filter);
		/*encode json response*/	

		echo json_encode($response);		
		die;
	}
}

new Get_Reports_By_Group('get_reports_group_report');

class Get_Reports_By_User extends AJAX_Handler{

	function callback(){
		$user_id=$_POST['data_value'];
		if(!is_array($user_id)) return;

		/*define filters*/		
		$filter['attempts']=strip_tags(trim($_POST['attempts']));
		$filter['hits']=strip_tags(trim($_POST['hits']));
		$filter['date_from']=strip_tags(trim($_POST['day_from']));
		$filter['date_to']=strip_tags(trim($_POST['day_to']));

		$tests=$GLOBALS['users']->get_user_test_report($user_id);
		foreach ($tests as $key => $value) {
			$test_results[$key]=$value;
			$test_results[$key]['test_info']=get_post($key);
		}
		/* parse data tests */
			$response=$GLOBALS['reports']->generate_report_table($test_results, $filter);
		
		echo json_encode($response);		
		die;
	}
}

new Get_Reports_By_User('get_reports_user_report');


class Get_Reports_By_Test extends AJAX_Handler{

	function callback(){
		$test_id=$_POST['data_value'];
		if(!is_array($test_id)) return;

		/*define filters*/		
		$filter['attempts']=strip_tags(trim($_POST['attempts']));
		$filter['hits']=strip_tags(trim($_POST['hits']));
		$filter['date_from']=strip_tags(trim($_POST['day_from']));
		$filter['date_to']=strip_tags(trim($_POST['day_to']));

		
		foreach ($test_id as $key => $value) {
			$test_results[$value]=$GLOBALS['reports']->get_group_tests_reports($value)[$value];
			$test_results[$value]['test_info']=get_post($value);
		}
		// var_dump($test_results)
		/* parse data tests */
			$response=$GLOBALS['reports']->generate_report_table($test_results, $filter);
		/*encode json response*/
		// var_dump($test_results);
		echo json_encode($response);		
		die;
	}
}

new Get_Reports_By_Test('get_reports_test_report');
/*Haltom City Administration  substr($data['date_hits']->time,0, 10);*/


class Hits_Add extends AJAX_Handler{

	function callback(){
		$test_id=$_GET['test_id'];
		$user_id=$_GET['user_id'];
		$query=$this->db->insert($this->db->prefix."lms_test_hits", array(	"test_id"=>$test_id,
																	"user_id"=>$user_id,
																	"time"=>date("Y-m-d H:i:s")		 
																	) 
						);	
		if($query){
			echo json_encode(array("success"=>"success"));
		}		
		die;
	}
}

new Hits_Add('hits_add');

class lms_steps_result extends AJAX_Handler{

	function callback(){
		if(isset($_POST['test_id']) && isset($_POST['user_id'])){
			$user=strip_tags($_POST['user_id']);
			$test_id=strip_tags($_POST['test_id']);
			delete_user_meta($user, "lms_steps_".$test_id);
		}
		if(isset($_POST['ans'])&&isset($_POST['test_id'])&&isset($_POST['step'])){
			// var_dump($_POST['ans']);
			foreach ($_POST['ans'] as $key => $value) {
				$results[trim(strip_tags(substr($value['name'], 7)))]=trim(strip_tags($value['value']));		
			}
			$id=trim(strip_tags($_POST['test_id']));
			$steps=unserialize(get_post_meta($id, 'lms_interaction_data', true));
			$success='';
			foreach ($steps[$_POST['step']]['questions'] as $key => $value) {
				if($results[$key]==$value['true']){
					$success[$key]=$value['true'];
				}else{
					$error[$key]=$results[$key];
					$review[$key]=$steps[$_POST['step']]['video'][1]['url'];
				}		
			}
			$current_step=trim(strip_tags($_POST['step']));
			if(count($success)==count($results)){
				$json['error']=0;
				$json['count']=0;
				$user=get_current_user_id();
				$user_data=get_user_meta($user, "lms_steps_".$id, true);
				// var_dump($user_data);		
				if($user_data){	
					if (!in_array($current_step, $user_data)) {
	    				array_push($user_data, $current_step);
						update_user_meta($user, "lms_steps_".$id, $user_data);
						update_user_meta($user, "lms_interaction_date_".$id, date("Y-m-d H:i:s"));						
						$query=$this->db->insert($this->db->prefix."lms_test_hits", array(	"test_id"=>$id,
																					"user_id"=>$user,
																					"time"=>date("Y-m-d H:i:s")		 
																					) 
										);
					}				
				}else{
					$user_data=array();
					array_push($user_data, $current_step);			
					update_user_meta($user, "lms_steps_".$id, $user_data);
					update_user_meta($user, "lms_interaction_date_".$id, date("Y-m-d H:i:s")); 
					$query=$this->db->insert($this->db->prefix."lms_test_hits", array(	"test_id"=>$id,
																					"user_id"=>$user,
																					"time"=>date("Y-m-d H:i:s")		 
																					) 
										);
				}
			}else{
				foreach ($error as $key => $value) {
					$json['error'][]=$key;
				}
				$json['count']=count($error);
				foreach ($review as $key => $value) {
					$json['review'][]=$value;
				}
				
			}
			echo json_encode($json);
			wp_die();
		}
		if(isset($_POST['hash'])){		
			unset($_SESSION[$_POST['hash']]);
		}
		die;
	}
}

new lms_steps_result('lms_steps_result');