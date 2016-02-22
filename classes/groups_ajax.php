<?php 

/*********************************************************TESTS*****************************************************/

class Group_Tests extends AJAX_Handler {

	function _constract(){
		add_action('wp_ajax_maxim',array($this,'test'));
	}
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
new Group_Users('search_users');

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