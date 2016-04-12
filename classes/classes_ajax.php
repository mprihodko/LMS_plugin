<?php 

/*Ajax Search Products*/
class Search_products extends AJAX_Handler {
    function callback() {
        $test=array();        
        $tests = $this->db->get_results(
                        "SELECT ID, post_title
                         FROM ".$this->db->posts."
                         WHERE `post_type`='lms_product'
                         AND post_title LIKE '%".$this->db->escape($_GET['value'])."%' LIMIT 10"
                        );   
           
        foreach($tests as $k=>$v):  
          $test[$k]=$v;
          $test[$k]->price=get_post_meta($v->ID, '_lms_price', true);
        endforeach; 
        echo json_encode($test);
        die();
    }
}
new Search_products('search_products');


/*********************************************************TESTS*****************************************************/
class Group_Tests extends AJAX_Handler {
	
    function callback() {

        $tests = $this->db->get_results(
							        	'SELECT ID, post_title, post_author
							        	 FROM '.$this->db->posts.'
							        	 WHERE post_type=\'lms_test\'
							        	 AND post_title LIKE \'%'.$this->db->escape($_GET['value']).'%\' LIMIT 10'
							        	);
		foreach($tests as $k=>$v):		
			if(current_user_can("administrator") || get_current_user_id()==$v->post_author) $test[$k]=$v;
		endforeach;	
		echo json_encode($test);
		die();
    }
}
new Group_Tests('search_tests');

class Write_Tests extends AJAX_Handler {
  
    function callback() {
      $getTest=$GLOBALS['reports']->get_one_report($_GET['user'], $_GET['test'], $_GET['group']);

      if(!$getTest){ 


        $insert=$this->db->insert($this->db->prefix."lms_test_results", array("group_id"=>$_GET['group'],
                                                                      "test_id"=>$_GET['test'],
                                                                      "user_id"=>$_GET['user'],
                                                                      "score"=>100,
                                                                      "pass"=>1,
                                                                      "correct"=>count($GLOBALS['tests']->get_questions($_GET['test'])),
                                                                      "answers"=>"auto",
                                                                      "time"=>$_GET['date']." ".date("H:i:s")));
        if($insert) echo json_encode(array("status"=>"success")); 


      }else{

        $attempts=count($getTest);        
        $attempts_used=$GLOBALS['reports']->get_attempts_limit($_GET['test'], $_GET['group']);       
        if($attempts>=$attempts_used){
          $latest=$getTest[$attempts-1]->test_result_id;
          $update=$this->db->update($this->db->prefix."lms_test_results", array("group_id"=>$_GET['group'],
                                                                      "test_id"=>$_GET['test'],
                                                                      "user_id"=>$_GET['user'],
                                                                      "score"=>100,
                                                                      "pass"=>1,
                                                                      "correct"=>count($GLOBALS['tests']->get_questions($_GET['test'])),
                                                                      "answers"=>"auto",
                                                                      "time"=>$_GET['date']." ".date("H:i:s")),
                                                                      array("test_result_id"=>$latest));
          if($update) echo json_encode(array("status"=>"success")); 


        }else{


          $insert=$this->db->insert($this->db->prefix."lms_test_results", array("group_id"=>$_GET['group'],
                                                                      "test_id"=>$_GET['test'],
                                                                      "user_id"=>$_GET['user'],
                                                                      "score"=>100,
                                                                      "pass"=>1,
                                                                      "correct"=>count($GLOBALS['tests']->get_questions($_GET['test'])),
                                                                      "answers"=>"auto",
                                                                      "time"=>$_GET['date']." ".date("H:i:s")));
          if($insert) echo json_encode(array("status"=>"success")); 

           
        }
      }      
      die;
    }
}
new Write_Tests('write_user_test');


class Search_Group_Tests extends AJAX_Handler {
  
    function callback() {
      $tests = $GLOBALS['groups']->get_group_test($_GET['value'], '0', -1);
      echo json_encode($tests);
      die();
    }
}
new Search_Group_Tests('search_user_group_test');


/* GROUP Remove Test */
class Group_Remove_Test extends AJAX_Handler {
    function callback() {
       	$id=strip_tags(trim($_GET['group']));
       	$test=strip_tags(trim($_GET['value']));
       	if($this->db->delete($this->db->prefix."lms_group_tests", array('group_id'=>$id, 'test_id'=>$test))){      	
       		echo json_encode(array("result"=>'success')); die();
       	}else{
       		echo json_encode(array("result"=>'fail')); die();
       	}
    }
}
new Group_Remove_Test('remove_test');

/*********************************************************TESTS*****************************************************/


/***************************************************** GROUP USERS *************************************************/

class Group_Users extends AJAX_Handler {
    function callback() {
     	$users = $this->db->get_results('SELECT ID, user_login, user_email 
     									 FROM '.$this->db->users.' 
     									 WHERE user_login LIKE \'%'.$this->db->escape($_GET['value']).'%\'
     									 LIMIT 10');

      
  		foreach($users as $key => $val):
        if(isset($_GET['order']) && $_GET['order']==true){
          $customer[$key]=$val;  
          $customer[$key]->first_name=get_user_meta($val->ID, "first_name", true);
          $customer[$key]->last_name=get_user_meta($val->ID, "last_name", true);
        }else{
  			   $customer[$key]=$val;	
        }		
  		endforeach;	
  		echo json_encode($customer);
  		die();
    }
}
new Group_Users('search_users');

class Get_Group_User extends AJAX_Handler {
    function callback() {
        $groups = $GLOBALS['users']->get_user_groups($_GET['value']);
        foreach ($groups as $key => $value) {
          $response[]=$GLOBALS['groups']->get_group('group_id', $value->group_id)[0];
        }        // var_dump($groups);
        echo json_encode($response);
        die();
    }
}
new Get_Group_User('search_user_groups');

/* GROUP Remove USER */
class Group_Remove_User extends AJAX_Handler {
    function callback() {
       	$id=strip_tags(trim($_GET['group']));
       	$user=strip_tags(trim($_GET['value']));
       	$query=$this->db->get_results("SELECT `user_id` 
       								   FROM ".$this->db->prefix."lms_groups
       								   WHERE `group_id`=".$id);
       	if(isset($query[0]->user_id) && $query[0]->user_id!=$user){
       		if($this->db->delete($this->db->prefix."lms_group_users", array('group_id'=>$id, 'user_id'=>$user))){
       			echo json_encode(array("result"=>'success')); die();
       		}else{ 	
        		echo json_encode(array("result"=>'Fail')); die(); 
        	}     		
        }else{
        	echo json_encode(array("result"=>'Fail')); die(); 
        }
    }
}
new Group_Remove_User('remove_user');

/***************************************************** GROUP USERS *************************************************/


/*************************************************** GROUP Courses *************************************************/

class Group_Courses extends AJAX_Handler {
    function callback() {

       	$courses = $this->db->get_results('	SELECT ID, post_title, post_author
       										FROM '.$this->db->posts.'
       										WHERE post_type=\'dt_courses\' 
       										AND post_title LIKE \'%'.$this->db->escape($_GET['value']).'%\' LIMIT 10');
		foreach($courses as $k=>$v):
			if(get_current_user_id()==$v->post_author || current_user_can("administrator"))	$course[$k]=$v;			
		endforeach;	
		echo json_encode($course); die();
    }
}
new Group_Courses('search_courses');


/* GROUP Remove Course */
class Group_Remove_Course extends AJAX_Handler {
    function callback() {
       	$id=strip_tags(trim($_GET['group']));
       	$course=strip_tags(trim($_GET['value']));
       	if($this->db->delete($this->db->prefix."lms_group_courses", array('group_id'=>$id, 'course_id'=>$course))){      	
       		echo json_encode(array("result"=>'success')); die();
       	}else{
       		echo json_encode(array("result"=>'fail')); die();
       	}
    }
}
new Group_Remove_Course('remove_courses');

/*************************************************** GROUP Courses *************************************************/



/*******************************************************************************************************************
===========================================	GROUP LIST FUNCTIONS CALLBACK 	========================================
*******************************************************************************************************************/

/* GROUP Remove */
class Group_Remove extends AJAX_Handler {
    function callback() {
       	$id=strip_tags(trim($_GET['value']));
       	$update=$this->db->update( $this->db->prefix."lms_groups", array("remove"=>1), array('group_id'=>$id), $format = null, $where_format = null );
       	echo json_encode(array("result"=>'success')); die();
    }
}
new Group_Remove('remove_group');

/* GROUP Delete */
class Group_Delete extends AJAX_Handler {
    function callback() {
       	$id=strip_tags(trim($_GET['value']));       
       	$this->db->delete($this->db->prefix."lms_groups", array('group_id'=>$id));
       	$this->db->delete($this->db->prefix."lms_group_tests", array('group_id'=>$id));
       	$this->db->delete($this->db->prefix."lms_group_users", array('group_id'=>$id));
       	$this->db->delete($this->db->prefix."lms_group_courses", array('group_id'=>$id));
       	echo json_encode(array("result"=>'success')); die();
    }
}
new Group_Delete('delete_group');

/* GROUP Restore */
class Group_Restore extends AJAX_Handler {
    function callback() {
       	$id=strip_tags(trim($_GET['value']));
       	$update=$this->db->update( $this->db->prefix."lms_groups", array("remove"=>0), array('group_id'=>$id), $format = null, $where_format = null );
       	echo json_encode(array("result"=>'success')); die();
    }
}
new Group_Restore('restore_group');


class Group_Copy extends AJAX_Handler {
    function callback() {
        $id=strip_tags(trim($_GET['value']));
        $get_data=$this->db->get_results( "SELECT * FROM ".$this->db->prefix."lms_groups WHERE group_id=".$id);
        if(!$get_data) {echo json_encode(array("result"=>'fail')); die();}
        unset($get_data[0]->group_id);
        $get_data[0]->name=$get_data[0]->name."-copy";
        $get_data[0]->text_id='';
        $copy=(array)$get_data[0];        
        $this->db->insert($this->db->prefix."lms_groups", $copy);  
        $this->db->insert($this->db->prefix."lms_group_users", array("user_level"=>2,
                                                                     "user_id"=>$GLOBALS['users']->user->ID,
                                                                     "group_id"=>$this->db->insert_id));      
        echo json_encode(array("result"=>'success')); die();
    }
}
new Group_Copy('copy_group');

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
    if(isset($_POST['data_value']))
      $groups_id=$_POST['data_value'];
    // if(!is_array($groups_id)) return;

    /*define filters*/    
    $filter['attempts']=strip_tags(trim($_POST['attempts']));
    $filter['hits']=strip_tags(trim($_POST['hits']));
    $filter['date_from']=strip_tags(trim($_POST['day_from']));
    $filter['date_to']=strip_tags(trim($_POST['day_to']));
    if(isset($groups_id)):
      $group_results=$GLOBALS['reports']->get_group_reports($groups_id);
    else:
      $group_results=$GLOBALS['reports']->get_all_reports();
    endif;

    $table=$GLOBALS['reports']->generate_report_table($group_results, $filter);
    
    /*encode json response*/
    echo json_encode($table);   
    die;
  }
}

new Get_Reports_By_Group('get_reports_group_report');

class Get_Reports_By_User extends AJAX_Handler{

  function callback(){
    if(isset($_POST['data_value']))
      $user_id=$_POST['data_value'];    

    /*define filters*/    
    $filter['attempts']=strip_tags(trim($_POST['attempts']));
    $filter['hits']=strip_tags(trim($_POST['hits']));
    $filter['date_from']=strip_tags(trim($_POST['day_from']));
    $filter['date_to']=strip_tags(trim($_POST['day_to']));
    
    if(isset($user_id)):
      $user_results=$GLOBALS['reports']->get_user_reports($user_id);
    else:
      $user_results=$GLOBALS['reports']->get_all_reports();
    endif;

    $table=$GLOBALS['reports']->generate_report_table($user_results, $filter);

    /*encode json response*/  
    echo json_encode($table);   
    die;
  }
}

new Get_Reports_By_User('get_reports_user_report');


class Get_Reports_By_Test extends AJAX_Handler{

  function callback(){
    if(isset($_POST['data_value']))
      $test_id=$_POST['data_value'];    

    /*define filters*/    
    $filter['attempts']=strip_tags(trim($_POST['attempts']));
    $filter['hits']=strip_tags(trim($_POST['hits']));
    $filter['date_from']=strip_tags(trim($_POST['day_from']));
    $filter['date_to']=strip_tags(trim($_POST['day_to']));
    
    if(isset($test_results)):
      $test_results=$GLOBALS['reports']->get_test_reports($test_id);
    else:
      $test_results=$GLOBALS['reports']->get_all_reports();
    endif;

    $table=$GLOBALS['reports']->generate_report_table($test_results, $filter);
    
    /*encode json response*/    
    echo json_encode($table);   
    die;
  }
}

new Get_Reports_By_Test('get_reports_test_report');
/*Haltom City Administration  substr($data['date_hits']->time,0, 10);*/


class Hits_Add extends AJAX_Handler{

  function callback(){
    $test_id=$_GET['test_id'];
    $user_id=$_GET['user_id'];
    $group_id=(($_COOKIE['current_group']=="administrator")? 0 : $_COOKIE['current_group']);
    $query=$this->db->insert($this->db->prefix."lms_test_hits", array(  "test_id"=>$test_id,
                                  "group_id"=>$group_id,
                                  "user_id"=>$user_id,
                                  "time"=>date("Y-m-d H:i:s")    
                                  ) 
            );  
    if($query){
      echo json_encode(array("success"=>"success"));
    }else{
      echo json_encode(array("success"=>"fail"));
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
      $success=0;
      foreach ($steps[$_POST['step']]['questions'] as $key => $value) {        
        if($results[$key]==$value['true']){
          $success++;
        }else{
          $error[$key]=$results[$key];
          $review[$key]=$steps[$_POST['step']]['video'][1]['url'];
        }   
      }      
      $current_step=trim(strip_tags($_POST['step']));
      if($success==count($results)){
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
            $query=$this->db->insert($this->db->prefix."lms_test_hits", array(  "test_id"=>$id,
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
          $query=$this->db->insert($this->db->prefix."lms_test_hits", array(  "test_id"=>$id,
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
