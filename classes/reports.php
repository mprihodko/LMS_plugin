<?php 

class Reports{

	/*vars*/
	public $user;
	public $data;
	private $db;

	/*construct*/

	public function __construct(){
		global $wpdb;
		$this->db=$wpdb;
		$this->data=array();
		$this->user=$GLOBALS['users']->user->ID;		

		/*actions*/		
		add_action( 'admin_init', 		array($this, 'script_admin_init'));
		add_action( 'admin_menu', 		array($this, 'add_reports_page'));
	}

 	
#####################################################################################################################
#																													#
# REGISTER REPORTS OPTIONS 																							#
#																													#
#####################################################################################################################


	/*  Register script  */
	public function script_admin_init() {
		wp_register_script( 'reports-page-script', ASSETS_DIR . 'js_min/admin.min.js', array('jquery'), '1.0', true );
	}
	/*  Register page  */
	public function add_reports_page(){
		$page_hook_suffix = add_menu_page( 	'Reports',
					  					   	'Reports',
					  					   	'delete_published_posts',
					  					   	'lms_reports',
					  					   	array( 	$this,
					  					   	 	 	'lms_reports_page'
					  					   		 ),
					  					   	'dashicons-editor-table',
					  					   	74 
					  					 );
  		add_action('admin_print_scripts-'.$page_hook_suffix, array($this, 'add_admin_scripts'));  		
	}


	/*  enqueue script  */
	public function add_admin_scripts() {		
		wp_enqueue_script( 'reports-page-script' );
	}


 	/*callback Reports*/
 	public function lms_reports_page(){
 		require_once(IAMD_TD.'/admin/reports/reports_tpl_admin.php');	
 	}



#####################################################################################################################
#																													#
# ORDER REPORTS OPTIONS 																							#
#																													#
#####################################################################################################################


 	public function get_all_reports(){ 
 		$query_results=$this->db->get_results("SELECT * FROM `".$this->db->prefix."lms_test_results`");
 		foreach ($query_results as $key => $value) {
 			$this->data[$value->test_id][$value->user_id]['attempts'][]=1;
 			$this->data[$value->test_id][$value->user_id]['results']=$value;
 		}
 		$query_hits=$this->db->get_results("SELECT * FROM `".$this->db->prefix."lms_test_hits`");
 		foreach ($query_hits as $key => $value) {
 			$this->data[$value->test_id][$value->user_id]['hits'][]=1;
 			$this->data[$value->test_id][$value->user_id]['hit_results']=$value;
 		}
 		$query_results=$this->db->get_results("SELECT * FROM `".$this->db->prefix."lms_test_results_story`");
 		foreach ($query_results as $key => $value) {
 			$this->data[$value->test_id][$value->user_id]['attempts'][]=1;
 			$this->data[$value->test_id][$value->user_id]['results']=$value;
 		}
 		$query_hits=$this->db->get_results("SELECT * FROM `".$this->db->prefix."lms_test_hits_story`");
 		foreach ($query_hits as $key => $value) {
 			$this->data[$value->test_id][$value->user_id]['hits'][]=1;
 			$this->data[$value->test_id][$value->user_id]['hit_results']=$value;
 		} 		 		
 		if($this->data)
 			return $this->data;
 	}


 	public function get_group_reports($group_id){ 
		/* define query parametrs */		
 		if(is_array($group_id))	$query_param=implode(" OR `group_id`=", $group_id); else $query_param=$group_id;
 		$query_results=$this->db->get_results("SELECT * FROM `".$this->db->prefix."lms_test_results` WHERE `group_id`=".$query_param);
 		foreach ($query_results as $key => $value) {
 			$this->data[$value->test_id][$value->user_id]['attempts'][]=1;
 			$this->data[$value->test_id][$value->user_id]['results']=$value;
 		}
 		$query_hits=$this->db->get_results("SELECT * FROM `".$this->db->prefix."lms_test_hits` WHERE `group_id`=".$query_param);
 		foreach ($query_hits as $key => $value) {
 			$this->data[$value->test_id][$value->user_id]['hits'][]=1;
 			$this->data[$value->test_id][$value->user_id]['hit_results']=$value;
 		} 
 		$query_results=$this->db->get_results("SELECT * FROM `".$this->db->prefix."lms_test_results_story` WHERE `group_id`=".$query_param);
 		foreach ($query_results as $key => $value) {
 			$this->data[$value->test_id][$value->user_id]['attempts'][]=1;
 			$this->data[$value->test_id][$value->user_id]['results']=$value;
 		}
 		$query_hits=$this->db->get_results("SELECT * FROM `".$this->db->prefix."lms_test_hits_story` WHERE `group_id`=".$query_param);
 		foreach ($query_hits as $key => $value) {
 			$this->data[$value->test_id][$value->user_id]['hits'][]=1;
 			$this->data[$value->test_id][$value->user_id]['hit_results']=$value;
 		} 			
 		if($this->data)
 			return $this->data;
 	}


 	public function get_test_reports($test_id){ 
		/* define query parametrs */		
 		if(is_array($test_id))	$query_param=implode(" OR `test_id`=", $test_id); else $query_param=$test_id;
 		$query_results=$this->db->get_results("SELECT * FROM `".$this->db->prefix."lms_test_results` WHERE `test_id`=".$query_param);
 		foreach ($query_results as $key => $value) {
 			$this->data[$value->test_id][$value->user_id]['attempts'][]=1;
 			$this->data[$value->test_id][$value->user_id]['results']=$value;
 		}
 		$query_hits=$this->db->get_results("SELECT * FROM `".$this->db->prefix."lms_test_hits` WHERE `test_id`=".$query_param);
 		foreach ($query_hits as $key => $value) {
 			$this->data[$value->test_id][$value->user_id]['hits'][]=1;
 			$this->data[$value->test_id][$value->user_id]['hit_results']=$value;
 		} 
 		$query_results=$this->db->get_results("SELECT * FROM `".$this->db->prefix."lms_test_results_story` WHERE `test_id`=".$query_param);
 		foreach ($query_results as $key => $value) {
 			$this->data[$value->test_id][$value->user_id]['attempts'][]=1;
 			$this->data[$value->test_id][$value->user_id]['results']=$value;
 		}
 		$query_hits=$this->db->get_results("SELECT * FROM `".$this->db->prefix."lms_test_hits_story` WHERE `test_id`=".$query_param);
 		foreach ($query_hits as $key => $value) {
 			$this->data[$value->test_id][$value->user_id]['hits'][]=1;
 			$this->data[$value->test_id][$value->user_id]['hit_results']=$value;
 		} 				
 		if($this->data)
 			return $this->data;
 	}


 	public function get_user_reports($user_id){ 
		/* define query parametrs */		
 		if(is_array($user_id))	$query_param=implode(" OR `user_id`=", $user_id); else $query_param=$user_id;
 		$query_results=$this->db->get_results("SELECT * FROM `".$this->db->prefix."lms_test_results` WHERE `user_id`=".$query_param);
 		foreach ($query_results as $key => $value) {
 			$this->data[$value->test_id][$value->user_id]['attempts'][]=1;
 			$this->data[$value->test_id][$value->user_id]['results']=$value;
 		}
 		$query_hits=$this->db->get_results("SELECT * FROM `".$this->db->prefix."lms_test_hits` WHERE `user_id`=".$query_param);
 		foreach ($query_hits as $key => $value) {
 			$this->data[$value->test_id][$value->user_id]['hits'][]=1;
 			$this->data[$value->test_id][$value->user_id]['hit_results']=$value;
 		} 	
 		$query_results=$this->db->get_results("SELECT * FROM `".$this->db->prefix."lms_test_results_story` WHERE `user_id`=".$query_param);
 		foreach ($query_results as $key => $value) {
 			$this->data[$value->test_id][$value->user_id]['attempts'][]=1;
 			$this->data[$value->test_id][$value->user_id]['results']=$value;
 		}
 		$query_hits=$this->db->get_results("SELECT * FROM `".$this->db->prefix."lms_test_hits_story` WHERE `user_id`=".$query_param);
 		foreach ($query_hits as $key => $value) {
 			$this->data[$value->test_id][$value->user_id]['hits'][]=1;
 			$this->data[$value->test_id][$value->user_id]['hit_results']=$value;
 		} 			
 		if($this->data)
 			return $this->data;
 	}

#####################################################################################################################
#																													#
# HITS ATTEMPTS	AND OTHER REPORTS INFO																				#
#																													#
#####################################################################################################################


 	/*group-hits-used*/
 	function get_used_views($group_id){		
		$hits=0;		
		$hit=$this->db->get_results('SELECT * FROM '.$this->db->prefix.'lms_test_hits WHERE group_id='.$group_id.'');	
		if(isset($hit))
			$hits=count($hit);	
		return $hits;		
	}


	/*user-hits-used*/
	function get_user_used_views($test_id=null, $group_id=null){
		if($test_id==null || $group_id==null) return 0;		
		$hits=0;
		$hit=$this->db->get_results('SELECT * 
										FROM '.$this->db->prefix.'lms_test_hits 
										WHERE test_id='.$test_id.'
										AND user_id='.$this->user.'
										AND group_id='.$group_id);
		if(!$hit) return 0;		
		$hits=count($hit);	
		return $hits;		
	}
	function get_user_used_views_in_group($group_id=null){
		if($group_id==null) return 0;
		$hits=0;
		$hit=$this->db->get_results('SELECT * 
										FROM '.$this->db->prefix.'lms_test_hits 
										WHERE user_id='.$this->user.'
										AND group_id='.$group_id);
		if(!$hit) return 0;		
		$hits=count($hit);	
		return $hits;		

	}

	/*max-hits*/
 	public function get_hits_limit($group_id=null){ 		
 		if($group_id==null) return 0; 		
		$get_hit_limit=$this->db->get_results("SELECT `view_limit` FROM `".$this->db->prefix."lms_groups` WHERE `group_id`=".$group_id);
		if(!$get_hit_limit) return 0;		
		$users=$GLOBALS['groups']->ingroup_users($group_id, "count");		
		return ceil($get_hit_limit[0]->view_limit/$users);
	}


	/*user-attempts*/
	public function get_used_attempts($test_id=null, $group_id=null){
		if($test_id==null || $group_id==null) return 0;		
		return $this->the_test_result_info($test_id, $group_id)['attempts'];
	}


	/*max-attempts*/
	public function get_attempts_limit($test_id=null, $group_id=null){	
		if($test_id==null || $group_id==null) return 0;
		$query=$this->db->get_results("SELECT * FROM `".$this->db->prefix."lms_group_tests` WHERE test_id=".$test_id." AND group_id=".$group_id);
		if(!$query)return 0;
		return $query[0]->view_limit;
	}


	/*the_test_result_info per user*/
	public function the_test_result_info($test_id=null, $group_id=null){
		if($test_id==null || $group_id=null) return false;
		$summary = $this->db->get_row('SELECT SUM(`pass`) AS `passes`, COUNT(*) AS `attempts`, MAX(`score`) AS `score` FROM '.$this->db->prefix.'lms_test_results WHERE user_id = '.$this->user.' AND test_id = '.$test_id.' AND group_id='.$group_id);		
		if(!$summary || count($summary)<1) return false;		
		$this->data['passes'] = $summary->passes;
		$this->data['attempts'] = $summary->attempts;
		$this->data['score'] = $summary->score;
		return $this->data;
	}


	/*check result per user*/
	public function has_result($test_id=null, $group_id=null){
		if($test_id==null || $group_id=null) return false;
		$query=$this->db->get_results('SELECT * FROM '.$this->db->prefix.'lms_test_results WHERE user_id = '.$this->user.' AND test_id = '.$test_id.' AND group_id='.$group_id);
		if(!$query || count($query)<1) return false;			
		return true;
	}


	/*return score if RESULTS EXISTS*/
	public function the_test_score($test_id=null, $group_id=null){
		if($test_id==null || $group_id=null) return false;
		$score=$this->the_test_result_info($test_id, $group_id);
		if($score==null) return false;
		if(array_key_exists('score', $score)) return $score['score'];
		return false;
	}


	/*check pass test per user*/
	public function has_passed($test_id=null, $group_id=null){
		if($test_id==null || $group_id=null) return false;
		$passes=$this->the_test_result_info($test_id, $group_id);
		if($passes==null) return false;
		if(array_key_exists('passes', $passes))	return $passes['passes'];		
		return false;
	}

	

#####################################################################################################################
#																													#
# GENERATE OPTION OF REPORTS INFO																					#
#																													#
#####################################################################################################################


	public function generate_report_table($test_results, $filter){
		if(!is_array($test_results)) return;
		$i=0;
				
		foreach ($test_results as $test => $test_data) {

			/* existing test_info */
			$test_info=get_post($test);
			

			/* parse results array tests */		
			foreach ($test_data as $user => $data) {
				$attempts=0;
				$hits=0;

				/* existing result data */
				if(is_array($data)){					
					if(array_key_exists('results', $data))	
						$results=$data['results'];
					if(array_key_exists('attempts', $data))		
						$attempts=count($data['attempts']);
					if(array_key_exists('hits', $data))	
						$hits=count($data['hits']);
					if(array_key_exists('hit_results', $data))	
						$hit_result=$data['hit_results'];
					if(isset($results)){
						$results_time=(($results->time!="0000-00-00 00:00:00" && $results->time!='')? $results->time : $hit_result->time);
						$results_date=substr((($results->time!="0000-00-00 00:00:00" && $results->time!='')? $results->time : $hit_result->time),0,10);
						$symbol=(($results->pass=='1')? '  <i class="fa fa-check"></i>' : '  <i class="fa fa-times"></i>');
					}
					if(isset($hit_result)){
						$hit_time=(($hit_result->time!="0000-00-00 00:00:00" && $hit_result->time!='')? $hit_result->time : $results_time);
						$hit_date=substr((($hit_result->time!="0000-00-00 00:00:00" && $hit_result->time!='')? $hit_result->time : $results_time),0,10);
					}

					/* get result data */
					if(isset($hit_result)){
						$hits_limit=$this->get_hits_limit($hit_result->group_id);
					}else{
						$hits_limit=0;
					}
					if(isset($results)){
						$attempts_limit=$this->get_attempts_limit($test, $results->group_id);
					}else{
						$attempts_limit=0;
					}

					/* create json obj*/
					if(!isset( $results_date))  $results_date ="0000-00-00";
					$date2=((isset($hit_date))? $hit_date : $results_date);
					if(
							($filter['date_from']<=$results_date &&	$filter['date_to']>=$results_date) ||
							($filter['date_to']>=$date2 && $filter['date_from']<=$date2)
						){
						if(
								($filter['attempts']=="true" && $attempts>0) ||
								($filter['hits']=="true" && $hits>0) ||
								($filter['hits']=="false" && $filter['attempts']=="false")
							){
							$i++;
							$response[$i][$user]['num']=$i;							
							$response[$i][$user]['first_name']=get_user_meta($user, 'first_name', true);
							$response[$i][$user]['last_name']=get_user_meta($user, 'last_name', true);
							$response[$i][$user]['post_title']=(($test_info=='')? 'no-name - '.$test : $test_info->post_title);
							$response[$i][$user]['score']=((isset($results))? $results->score : 'none');
							$response[$i][$user]['symbol']=((isset($symbol))? $symbol : '0  <i class="fa fa-times"></i>');
							$response[$i][$user]['time']=((isset($results_time))? $results_time : $hit_result->time);
							$response[$i][$user]['date_hits']=((isset($hit_time))? $hit_time : $results_time);
							$response[$i][$user]['attempts']=((isset($attempts))? $attempts : '0');
							$response[$i][$user]['attempts_limit']=(($attempts_limit>=$attempts)? $attempts_limit : $attempts);
							$response[$i][$user]['hits']=((isset($hits) && $hits>0)? $hits : $attempts);
							$response[$i][$user]['hits_limit']=(($hits_limit>0)? $hits_limit : $attempts_limit);			
							$response[$i][$user]['lms_interaction_date']=get_user_meta($user,'lms_interaction_date_'.$test, true);
						}
					}
				}
			}
		}
		if(isset($response)){
			return $response;
		}
	}


 	public function convert_to_csv($input_array, $filename){
	    $fp = fopen(LMS_DIR.'/csv/'.$filename.'.csv', 'w');
	    	if($input_array){
				foreach ($input_array as $fields) {
				    fputcsv($fp, $fields);
				}
			}
		fclose($fp);
	}	
}



	