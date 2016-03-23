<?php 

Class Tests{


	/*
	*vars
	*/
	private $db;
	public $test;
	public $test_id;
	public $user;
	public $token;
	public $the_score;

	/*construct*/
	public function __construct(){
		global $wpdb;		
		$this->db=$wpdb;
		$this->user=$GLOBALS['users']->user->ID;
		$this->token='';

		if(isset($_GET['post']))
			$this->test=$_GET['post'];	

		if(isset($_GET['certificate'])): 
			$this->generate_test_certificate($_GET['certificate']);
			die;
		endif;


		/*actions*/
		if(isset($_GET['save_test'])){			
			add_action('init',						array($this, 'save_post_front_end'));
		}
		add_action('init', 						array($this, 'register_test_posttype'));
		add_action('add_meta_boxes', 			array($this, 'lms_add_meta'));	
		add_action('admin_enqueue_scripts', 	array($this, 'include_upload_script_thumbnail'));
		add_action('save_post', 				array($this, 'save_test'), 10, 1); 
		add_action('post_edit_form_tag', 		function(){ echo ' enctype="multipart/form-data"'; });


	}




#####################################################################################################################
#																													#
#  THE TEST POST TYPE OPTIONS 																						#
#																													#
#####################################################################################################################
	public function include_upload_script_thumbnail() {													
		if ( ! did_action( 'wp_enqueue_media' ) ) {
			wp_enqueue_media();
		}						 	
	}
	public function register_test_posttype(){

		register_post_type( 'lms_test',	    
		    array(
			    'labels' => array(
			        'name' => __( 'Tests' ),
			        'singular_name' => __( 'Test' )
			    ),
			    'public' => true,
			    'has_archive' => true,
			    'menu_position' => 71,
			    'rewrite' => array('slug' => 'tests'),
		    	)
		  	);
	}
	public function lms_add_meta() {
	
	    add_meta_box(    	
	        'wp_custom_attachment',
	        'Attach Video',
	    	array($this, 'lms_attach_video'),
	        'lms_test',
	        'advanced'
	    );
	    add_meta_box(    	
	        'lms_attach_media_files',
	        'Attach Media',
	    	array($this, 'lms_attach_media_files'),
	        'lms_test',
	        'advanced'
	    );
		add_meta_box(    	
	        'wp_course_builder',
	        'LMS Course Buidler',
	    	array($this, 'lms_course_builder'),
	        'lms_test',
	        'advanced'
	    );
	    add_meta_box(
			'mixtape_meta_box', 
			'LMS Information', 
			array($this, 'show_mixtape_meta_box'), 
			'lms_test', 
			'normal',
			'high'  
		);
	}
	public function fields(){
		$prefix = 'test_';	
		return array(
					array(
						'label'	=> 'Certificate',
						'desc'	=> 'Upload a certificate for this test',
						'id'	=> 'uploader_custom_image',
						'type'	=> 'image',
						'required' => false
					),
					array(
						'label'	=> 'Test thumbnail',
						'desc'	=> 'Upload a test thumbnail for this test',
						'id'	=> 'uploader_custom_thumbnail',
						'type'	=> 'thumbnail',
						'required' => false
					),
					array(
						'label'	=> 'Hub Test ID',
						'desc'	=> 'If part of a hub, this is the test ID',
						'id'	=> $prefix.'hub_test_id',
						'type'	=> 'text',
						'required' => false
					),
					array(
						'label'	=> 'Price',
						'desc'	=> 'Price per view',
						'id'	=> $prefix.'price',
						'type'	=> 'text',
						'required' => false
					),
					array(
						'label'	=> 'Questions',
						'desc'	=> 'Insert questions for this test',
						'id'	=> $prefix.'questions',
						'type'	=> 'questions',
						'required' => false
					),
					array(
						'label'	=> 'Pass score (%)',
						'desc'	=> 'The pass score',
						'id'	=> $prefix.'pass',	
						'type'	=> 'text',
						'required' => true
					),
				);
	}


	/* meta fields HTML Callback*/
	public function lms_attach_video(){	
		$attached_video=array();
		if(isset($_GET['post'])){	
		   	$post=get_post($_GET['post']);
		   	$attached_video=unserialize(get_post_meta($post->ID, 'lms_attach_video', true));
		}
	    if(is_array($attached_video)) $num  = count($attached_video);
	    require_once(IAMD_TD.'/admin/test_meta/attach_video_tpl.php');	   
	}
	public function lms_attach_media_files(){
		$attached_media=array();
		if(isset($_GET['post'])){
			$post=get_post($_GET['post']);
			$attached_media=unserialize(get_post_meta($post->ID, 'lms_attach_media', true));
		}
		if(is_array($attached_media)) $num  = count($attached_media);
		require_once(IAMD_TD.'/admin/test_meta/attach_media_tpl.php');	
	}
	public function lms_course_builder(){
		$interaction_status='';	
		$interaction_data='';	
		if(isset($_GET['post'])){
			$post=get_post($_GET['post']);
			$interaction_status=get_post_meta($post->ID, "lms_interactive_status", true);
			$interaction_data=unserialize(get_post_meta($post->ID, 'lms_interaction_data', true));
		}
		require_once(IAMD_TD.'/admin/test_meta/interaction_tpl.php');	
	}

	public function show_mixtape_meta_box(){
		$questions='';
		if(isset($_GET['post'])){		 
			$post=get_post($_GET['post']);
			$questions=$this->get_questions($post->ID);
		}
		$lms_meta_fields = $this->fields();
		require_once(IAMD_TD.'/admin/test_meta/mixtape_meta_tpl.php');
	}
	/* meta fields HTML Callback*/







#####################################################################################################################
#																													#
#  THE TEST DATA 																									#
#																													#
#####################################################################################################################

	public function get_available_tests(){
		if(!is_user_logged_in()) return;
		if(current_user_can('administrator')){
		$user_groups=$GLOBALS['groups']->get_groups();	
		}else{
			$user_groups=$GLOBALS['users']->get_user_groups();
		}
		foreach ($user_groups as $key => $value) {
			$tests[$value->group_id]['group']=$GLOBALS['groups']->get_group('group_id', $value->group_id);
			$tests[$value->group_id]['tests']=$this->get_group_tests($value->group_id);
		}		
		return $tests;
	}
	public function get_group_tests($group_id=null){
		if($group_id==null) return;
		if(is_array($group_id)){
			$query_param=implode(" OR `group_id`=", $group_id);
		}else{
			$query_param=$group_id;
		}
		$query=$this->db->get_results("SELECT `test_id` FROM `".$this->db->prefix."lms_group_tests` WHERE  `group_id`=".$query_param);
		
		if(!$query) return;

		foreach ($query as $key => $value) {
			$tests[$key]=get_post($value->test_id);
		}
		return $tests;
	}

	public function test_pagination(){
		global $wp_query;
		$total = $wp_query->max_num_pages;		
		if ( $total > 1 )  {
	     // get the current page
		    if ( !$current_page = get_query_var('paged') )
		        $current_page = 1;	    
		     	// structure of "format" depends on whether we're using pretty permalinks
		    if( get_option('permalink_structure') ) {
			     $format = 'page/%#%/';
		    } else {
			     $format = 'page/%#%/';
		    }
		    echo paginate_links(array(
		          'base'     => get_pagenum_link(1) . '%_%',
		          'format'   => $format,
		          'current'  => $current_page,
		          'total'    => $total,
		          'mid_size' => 4,
		          'type'     => 'list'
		    ));
		}
	}	

	public function the_video(){		
	    global $post;	
	   	$attached_video=unserialize(get_post_meta($post->ID, 'lms_attach_video', true));
	    if(is_array($attached_video)) return $attached_video; else return false;	      
	}	

	public function get_questions($test_id=null){
		global $post;
		if($test_id==null) $test_id=$post->ID;
		$questions = $this->db->get_results('SELECT * FROM '.$this->db->prefix.'lms_questions WHERE test_id = '.$test_id);
		return $questions;
	}

	public function get_test_group($test_id=null){
		global $post;
		if($test_id==null) $test_id=$post->ID;
		$groups = $this->db->get_results('SELECT * FROM '.$this->db->prefix.'lms_group_tests WHERE test_id = '.$test_id);
		return $groups;
	}

	public function the_quest_options($options, $num){
		$s = json_decode($options);
		echo "<fieldset><ul>";	
		foreach($s as $k => $v) {
			echo "<li><input type='radio' value='{$k}' name='answer[{$num}]' required/> {$v}</li>";
		}	
		echo "</ul></fieldset>";
	}

	public function the_quest_answers($options, $answer){
		$s = json_decode($options);
		echo "<fieldset><ul>";	
			foreach($s as $k => $v) {
				if($answer==$k){
					echo "<li style='font-weight:bolder; color:green;'> {$v}</li>";
				}else{
					echo "<li> {$v}</li>";
				}
			}	
		echo "</ul></fieldset>";
	}




#####################################################################################################################
#																													#
#																													#
#  TEST HAS OPTIONS																									#
#																													#
#																													#
#####################################################################################################################


	public function has_media($test_id=null){
		if($test_id==null) return false;
		$is_media = get_post_meta($test_id, "lms_attach_media", true);
		if($is_media!=''){
			return $is_media;
		}else{
			return false;
		}
	}
	public function has_media_before($test_id=null){
		if($test_id==null) return false;
		$is_media = get_post_meta($test_id, "lms_attach_media", true);
		if($is_media!=''){
			foreach (unserialize($is_media) as $key => $value) {
				if($value['pos']=='before') return true;
			}
		}
	}
	public function has_media_after($test_id=null){
		if($test_id==null) return false;
		$is_media = get_post_meta($test_id, "lms_attach_media", true);
		if($is_media!=''){
			foreach (unserialize($is_media) as $key => $value) {
				if($value['pos']=='after') return true;
			}
		}
	}
	public function has_video($test_id=null){
		if($test_id==null) return false;	   
		$is_video = get_post_meta($test_id, "lms_attach_video", true);
		if($is_video!='') return true;
		return false;		
	}
	public function has_interaction($test_id=null){
		if($test_id==null) return false;
		$is_interaction = get_post_meta($test_id, "lms_interactive_status", true);
		if($is_interaction=='on') return true;
		return false;
	}


#####################################################################################################################
#																													#
#  PARTS URL OPTIONS																								#
#																													#
#####################################################################################################################
	

	public function get_part_after_before($test_id=null){
		if($test_id==null) return false;
		$part="questions";		
		if($this->has_interaction($test_id))  return "interaction";
		if($this->has_video($test_id)) return "video";
		if($this->has_media_after($test_id)) return "after";		
		if($part=="questions") return $part."&access=".$this->get_token($test_id);	
	}
	public function get_part_after_interaction($test_id=null){
		if($test_id==null) return false;
		$part="questions";
		if($this->has_video($test_id)) return "video";
		if($this->has_media_after($test_id)) return "after";		
		if($part=="questions") return $part."&access=".$this->get_token($test_id);	
	}
	public function get_part_after_video($test_id=null){
		if($test_id==null) return false;
		$part="questions";
		if($this->has_media_after($test_id)) return "after";		
		if($part=="questions") return $part."&access=".$this->get_token($test_id);	
	}

	public function get_part_questions($test_id=null){
		if($test_id==null) return false;
		$part="questions";			
		if($part=="questions") return $part."&access=".$this->get_token($test_id);	
	}
	public function get_part($test_id=null){
		if($test_id==null) return false;
		$part="questions";
		if($this->has_media_before($test_id))  return "before";
		if($this->has_interaction($test_id))  return "interaction";
		if($this->has_video($test_id)) return "video";
		if($this->has_media_after($test_id)) return "after";		
		if($part=="questions") return $part."&access=".$this->get_token($test_id);	
		
	}
	public function get_certificate($test_id=null){
		if($test_id==null) return false;

	}
	public function get_token($test_id=null){
		if($test_id==null) return false;
		$this->token=md5($test_id.date("Y-m-d H").$this->user);
		return $this->token;
	}

	public function get_max_test_attempts($test_id=null, $group_id=null){
		if($test_id==null || $group_id==null) return 0;
		$query=$this->db->get_results("SELECT * FROM `".$this->db->prefix."lms_group_tests` WHERE test_id=".$test_id." AND group_id=".$group_id);
		if(!$query)return 0;
		return $query[0]->view_limit;
	}


#####################################################################################################################
#																													#
# PASSED OPTIONS																									#
#																													#
#####################################################################################################################


	public function is_passed($answers, $test_id=null, $group_id=null){
		global $post;
		if(!is_array($answers)) return false;
		if($test_id==null) $test_id=$post->ID;
		$questions=$this->get_questions();
		$correct=0;
		foreach ($questions as $key => $value) {			
			if($answers[$value->question_id]==$value->answer)
				$correct++;
		}
		$this->the_score=($correct*100)/count($questions);
		$pass=get_post_meta($test_id, "test_pass", true);
		if($group_id==null) return false;
		if($group_id=='administrator') $group_id=0;
		$insert_result=array(	"test_id" => $test_id,
								"group_id" => $group_id,
								"user_id" => $this->user,
								"correct" => $correct,
								"pass"	  => (($this->the_score>=$pass)? 1 : 0),
								"score"	  => $this->the_score,
								"answers" => json_encode($answers),
								"time"	  => date("Y-m-d H:i:s")
								);

		$query=$this->db->insert($this->db->prefix."lms_test_results", $insert_result);			
		if($this->the_score>=$pass) return true;
		return false;
	}







#####################################################################################################################
#																													#
#  CERTIFICATE OPTIONS   									  			  											#
#																													#
#####################################################################################################################


	/*is_user_passed Test*/
	public function is_user_passed($test_id=null, $user_id=null){
		global $post;
		$this->test_id=$post->ID;		
		if($test_id==null) $test_id=$this->test_id;
		if($user_id==null) $user_id=$this->user;
		$query=$this->db->get_results("	SELECT `pass` 
									FROM wp_lms_test_results 
									WHERE test_id='".$test_id."' 
									AND user_id='".$user_id."' 
									AND pass='1' 
									ORDER BY time DESC ");
		if($query) return $query[0]->pass;
		return false;
	}
	/*LoadJpeg Certificate*/
	public function LoadJpeg($imgname, $test_name){
		// global $post;					
		get_currentuserinfo();	
		$name=$GLOBALS['users']->user->user_firstname;
		$l_name=$GLOBALS['users']->user->user_lastname;
		$query=$this->db->get_results("SELECT `pass` 
									   FROM wp_lms_test_results 
									   WHERE test_id='".$this->test_id."' 
									   AND user_id='".$this->user."' 
									   AND pass='1' ORDER BY time DESC ");
		$pass=$query[0]->pass;		   	
	    $im = @imagecreatefromjpeg($imgname); 
	    if(!$im || $pass!='1'){      
	        $im  = imagecreatetruecolor(1000, 30);
	        $bgc = imagecolorallocate($im, 255, 255, 255);
	        $tc  = imagecolorallocate($im, 0, 0, 0);
	        imagefilledrectangle($im, 0, 0, 1000, 30, $bgc);   
	        imagestring($im, 1, 5, 5, 'Error loading ' . $imgname, $tc);
	    }else{
	    	$ix=450; 
			$iy=300;
			$text= $name." ".$l_name;
			$text2=date("F, jS Y");
			$text3=$test_name; 
			$ix2=420; 
			$iy2=420;
			$ix3=100; 
			$iy3=515;

			imageString($im, 20, $ix, $iy, $text, 0x000000);
			imageString($im, 24, $ix2, $iy2,  $text2, 0x000000);
			imageString($im, 24, $ix3, $iy3,  $text3, 0x000000);
	    }
		return $im;		
	}
	/*generate_test_certificate*/
	public function generate_test_certificate($test_id){	
		$this->test_id=$test_id;
		$test_name = get_post($test_id);		
		$meta_img = get_post_meta($this->test_id, "test_image", true);
		$img = get_post_meta($meta_img, "_wp_attached_file", true);	
		$upl=wp_upload_dir();
		$imgname=$upl['basedir'].'/'.$img;
		$img = $this->LoadJpeg($imgname, $test_name->post_title);
		header('Content-Type: image/jpeg');		
		imagejpeg($img);
		imagedestroy($img);
	}

#####################################################################################################################
#																													#
#   SAVE TEST OPTIONS  									  				  											#
#																													#
#####################################################################################################################

	/*save test content*/
	public function save_post_front_end(){
		
				$post_data = array(
			  	'post_title'    => wp_strip_all_tags( $_POST['post_title'] ),
			  	'post_content'  => $_POST['post_content'],
			  	'post_status'   => 'publish',
			  	'post_author'   => $this->user,
			  	'post_type' => "lms_test"
			);			
			$post_id = wp_insert_post( $post_data );
			$this->save_test($post_id);			
		
	}

	/*save test content*/
	public function save_test($post_id) {		
		if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
			return $post_id;
		


		/*save quests*/
		if(isset($_POST['titles']))
			foreach($_POST['titles'] as $k => $title) {
				if($k != 0) {				
					$row = $this->db->get_row('SELECT * FROM '.$this->db->prefix.'lms_questions WHERE test_id = '.$post_id.' AND num = '.$k);
					if($row) {
						// update existing
						$this->db->update($this->db->prefix.'lms_questions', array('title' => $title,  'test_id' => $post_id, 'options' => json_encode($_POST['questions'][$k]), 'type' => 'option', 'answer' => $_POST['answers'][$k]), array('test_id' => $post_id, 'num' => $k));
					} else {
						// insert new
						$this->db->insert($this->db->prefix.'lms_questions', array('title' => $title, 'num' => $k, 'test_id' => $post_id, 'options' => json_encode($_POST['questions'][$k]), 'answer' => $_POST['answers'][$k], 'type' => 'option'));
					}
					$c = $k;
				}
			}
		// delete additional
			if(isset($c)){
				$this->db->query('DELETE FROM '.$this->db->prefix.'lms_questions WHERE test_id = '.$post_id.' AND num > '.$c);	
			}
		


		/*save certificate*/
		if(isset($_POST['uploader_custom_image']))
			update_post_meta( $post_id, 'uploader_custom_image', $_POST['uploader_custom_image']);
		


		/*save thumb*/
		if(isset($_POST['uploader_custom_thumbnail']))
			update_post_meta( $post_id, 'uploader_custom_thumbnail', $_POST['uploader_custom_thumbnail']);



		/*save data test*/
		$lms_meta_fields = $this->fields();
		foreach ($lms_meta_fields as $field) {
			if($field['type'] == 'text'){
				$old = get_post_meta($post_id, $field['id'], true);
				$new = ((isset($_POST[$field['id']]))? $_POST[$field['id']] : null);
				if ($new && $new != $old) {
					update_post_meta($post_id, $field['id'], $new);
				} elseif ('' == $new && $old) {
					delete_post_meta($post_id, $field['id'], $old);
				}
			}
		}



		/*save videos course*/
		if(isset($_FILES['lms_attach_video']['name']) || isset($_POST['lms_attach_video'])){
			if(isset($_POST['lms_attach_video'])){
				foreach ($_POST['lms_attach_video'] as $key => $value) {					
					if(substr(strip_tags(trim($value)), strlen(strip_tags(trim($value)))-4)==".mp4"){	
						if(isset($_POST['lms_attach_video_display'][$key]) && $_POST['lms_attach_video_display'][$key]=="none"){    
							$upload[$key]["file"]=strip_tags(trim($value));
		    				$upload[$key]["url"]=strip_tags(trim($value));
	    					$upload[$key]['none']= "true";
	    				}else{
	    					$upload[$key]["file"]=strip_tags(trim($value));
		    				$upload[$key]["url"]=strip_tags(trim($value));
		    				$upload[$key]['none']= "false"; 
						}
					}
				}
			}			
			if(isset($_FILES['lms_attach_video']['name'])){
				foreach ($_FILES['lms_attach_video']['name'] as $key => $value) {
					if($_FILES['lms_attach_video']['error'][$key]==0 && $_FILES['lms_attach_video']['type'][$key]=="video/mp4"){					
						if(isset($_POST['lms_attach_video_display'][$key]) && $_POST['lms_attach_video_display'][$key]=="none"){    				
		    				$upload[$key]  = wp_upload_bits($_FILES['lms_attach_video']['name'][$key], null, file_get_contents($_FILES['lms_attach_video']['tmp_name'][$key]));
		    				$upload[$key]['none'] = "true"; 
	    				}else{
		    				$upload[$key]  = wp_upload_bits($_FILES['lms_attach_video']['name'][$key], null, file_get_contents($_FILES['lms_attach_video']['tmp_name'][$key]));
		    				$upload[$key]['none'] = "false"; 
		    			}
		    			unset($upload[$key]['error']);
		    			unset($upload[$key]['type']);
					}
				}	
			}		
			if(isset($upload) && is_array($upload))
				update_post_meta($post_id, 'lms_attach_video', serialize($upload));
		}



		/*save exists videos course*/
		if(isset($_POST['lms_attach_video_path']) && isset($_POST['lms_attach_video_url'])){
	    	foreach ($_POST['lms_attach_video_path'] as $key => $value) {
	    		if(!empty($_POST['lms_attach_video_path'][$key])){
	    			if(isset($_POST['lms_attach_video_display'][$key])&& $_POST['lms_attach_video_display'][$key]=="none"){
	    				$data[$key]["file"]=$_POST['lms_attach_video_path'][$key];
	    				$data[$key]["url"]=$_POST['lms_attach_video_url'][$key];
	    				$data[$key]['none']= "true";  
	    			}else{
	    				$data[$key]["file"]=$_POST['lms_attach_video_path'][$key];
	    				$data[$key]["url"]=$_POST['lms_attach_video_url'][$key];
	    				$data[$key]['none']= "false";    
	    			}
	    		}
	    	}	
    		if(isset($upload)&&$upload!=NULL)
    			$video_data=array_merge($upload, $data);
    		else{
    			$video_data=$data;
    		}
    		if(is_array($video_data))
				update_post_meta($post_id, 'lms_attach_video', serialize($video_data)); 

	    }
	    if(!isset($upload) && !isset($video_data)){
			delete_post_meta($post_id, 'lms_attach_video'); 
	    }



	    /*save attached media files*/
	    if(isset($_FILES['lms_attach_media_file']['name'])){
	    	foreach ($_FILES['lms_attach_media_file']['name'] as $key => $value) {
	    		if($_FILES['lms_attach_media_file']['error'][$key]==0){
	    			$media[$key] = wp_upload_bits(	$_FILES['lms_attach_media_file']['name'][$key],
	    										   	null,
	    										   	file_get_contents($_FILES['lms_attach_media_file']['tmp_name'][$key])
	    										 );
	    			if(isset($_POST['lms_media'][$key]['pos'])){
	    				$media[$key]['pos']=strip_tags(trim($_POST['lms_media'][$key]['pos']));
	    			}else{
	    				$media[$key]['pos'] ="none";						
					}
					if(isset($_POST['lms_media'][$key]['perms'])){
						$media[$key]['perms']=strip_tags(trim($_POST['lms_media'][$key]['perms']));
					}else{
						$media[$key]['perms']="everyone";
	    			}
	    			unset($media[$key]['error']);
		    		unset($media[$key]['type']);
	    		}
	    	}
	    	if(isset($media) && is_array($media)){
	    		update_post_meta($post_id, 'lms_attach_media', serialize($media)); 
	    	}
	    }



	    /*save exists attached media files*/
	    if(isset($_POST['lms_attach_media_path']) && isset($_POST['lms_attach_media_url'])){
	    	foreach ($_POST['lms_attach_media_path'] as $key => $value) {
	    		$exists_media[$key]['file']=strip_tags(trim($value));
	    		$exists_media[$key]['url']=strip_tags(trim($_POST['lms_attach_media_url'][$key]));
	    		if(isset($_POST['lms_media'][$key]['pos'])){
	    			$exists_media[$key]['pos']=strip_tags(trim($_POST['lms_media'][$key]['pos']));
	    		}else{
	    			$exists_media[$key]['pos']='none';
	    		}
	    		if(isset($_POST['lms_media'][$key]['perms'])){
					$exists_media[$key]['perms']=strip_tags(trim($_POST['lms_media'][$key]['perms']));
				}else{
					$exists_media[$key]['perms']="everyone";
    			}
	    	}
	    	if(isset($media)&&$media!=NULL)
    			$media_data=array_merge($upload, $exists_media);
    		else{
    			$media_data=$exists_media;
    		}
    		if(is_array($media_data))
				update_post_meta($post_id, 'lms_attach_media', serialize($media_data)); 

	    }
	    if(!isset($media_data) && !isset($media)){
			delete_post_meta($post_id, 'lms_attach_media');
	    }


	    /*******************************************************************************************************/
	    /********************************** START SAVING INTERACTION FIELDS ************************************/
	    /*******************************************************************************************************/
	    
	    /* save status */
	    if(isset($_POST['use_steps'])){
	    	update_post_meta($post_id, 'lms_interactive_status', strip_tags(trim($_POST['use_steps']))); 
	    }else{
	    	delete_post_meta($post_id, 'lms_interactive_status');
	    }

	   	if(isset($_POST['step_title']) && isset($_POST['use_steps'])){
	   		foreach ($_POST['step_title'] as $step_num => $step_name) {
	   			$steps[$step_num]['title']=strip_tags(trim($step_name));
	   			$video_counter=0;
	   			/*IF ISSET EXISTS VIDEO*/
	   			if(isset($_POST['lms_course_video_file'][$step_num]) && isset($_POST['lms_course_video_url'][$step_num])){
	   				
	   				foreach ($_POST['lms_course_video_file'][$step_num] as $video_num => $video_data) {
	   					$video_counter++;
		   					$steps[$step_num]['video'][$video_counter]["file"]=strip_tags(trim($_POST['lms_course_video_file'][$step_num][$video_num]));
		    				$steps[$step_num]['video'][$video_counter]["url"]=strip_tags(trim($_POST['lms_course_video_url'][$step_num][$video_num]));
	   					if(isset($_POST['lms_course_video_display'][$step_num][$video_num]) && $_POST['lms_course_video_display'][$step_num][$video_num]=="none"){   
	    					$steps[$step_num]['video'][$video_counter]['none']= "true";
	    				}else{
	    					$steps[$step_num]['video'][$video_counter]['none']= "false"; 
	   					}
	   					if(isset($_POST['video_descr'][$step_num][$video_num]) && strip_tags(trim($_POST['video_descr'][$step_num][$video_num]))!=''){
							$steps[$step_num]['video'][$video_counter]['description']=strip_tags(trim($_POST['video_descr'][$step_num][$video_num]));
						}
	   				}
	   			}



	   			/*IF ISSET DATA VIDEO*/
	   			if(isset($_FILES['lms_course_builder_video']) || isset($_POST['lms_course_builder_video'])){
	   				
	   				/*IF ISSET DATA VIDEO LINK*/
	   				if(isset($_POST['lms_course_builder_video'][$step_num])){
	   					
	   					/*PARSE DATA VIDEO LINK*/
						foreach ($_POST['lms_course_builder_video'][$step_num] as $video_num => $video_data) {	

							$video_counter++;
							/*IF CORRECT DATA VIDEO LINK*/
							if(substr(strip_tags(trim($video_data)), strlen(strip_tags(trim($video_data)))-4)==".mp4"){

								/* IF VIDEO NOT HIDE */
								if(isset($_POST['lms_course_video_display'][$step_num][$video_num]) && $_POST['lms_course_video_display'][$step_num][$video_num]=="none"){    
									$steps[$step_num]['video'][$video_counter]["file"]=strip_tags(trim($video_data));
				    				$steps[$step_num]['video'][$video_counter]["url"]=strip_tags(trim($video_data));
			    					$steps[$step_num]['video'][$video_counter]['none']= "true";
			    				}else{
			    					$steps[$step_num]['video'][$video_counter]["file"]=strip_tags(trim($video_data));
				    				$steps[$step_num]['video'][$video_counter]["url"]=strip_tags(trim($video_data));
				    				$steps[$step_num]['video'][$video_counter]['none']= "false"; 
								}
								if(isset($_POST['video_descr'][$step_num][$video_num]) && strip_tags(trim($_POST['video_descr'][$step_num][$video_num]))!=''){
									$steps[$step_num]['video'][$video_counter]['description']=strip_tags(trim($_POST['video_descr'][$step_num][$video_num]));
								}								
							}
						}
					}					
					/*IF ISSET DATA VIDEO FILES*/
					if(isset($_FILES['lms_course_builder_video'])){
						
						/*PARSE DATA VIDEO FILES*/

						foreach ($_FILES['lms_course_builder_video']['name'][$step_num] as $video_num => $video_data) {
							
							$video_counter++;
							/*IF CORRECT DATA VIDEO FILES*/
							if(	$_FILES['lms_course_builder_video']['error'][$step_num][$video_num]==0 && $_FILES['lms_course_builder_video']['type'][$step_num][$video_num]=="video/mp4"){					
								
								/* IF VIDEO NOT HIDE */
								if(	isset($_POST['lms_course_video_display'][$step_num][$video_num]) &&	$_POST['lms_course_video_display'][$step_num][$video_num]=="none"){
				    				$steps[$step_num]['video'][$video_counter]  = wp_upload_bits(
				    													$_FILES['lms_course_builder_video']['name'][$step_num][$video_num],
				    													null,
				    													file_get_contents($_FILES['lms_course_builder_video']['tmp_name'][$step_num][$video_num])
				    													);
				    				$steps[$step_num]['video'][$video_counter]['none'] = "true"; 
			    				}else{
				    				$steps[$step_num]['video'][$video_counter]  = wp_upload_bits($_FILES['lms_course_builder_video']['name'][$step_num][$video_num],
				    													 null,
				    													 file_get_contents($_FILES['lms_course_builder_video']['tmp_name'][$step_num][$video_num])
				    													);
				    				$steps[$step_num]['video'][$video_counter]['none'] = "false"; 
				    			}
				    			if(isset($_POST['video_descr'][$step_num][$video_num]) && strip_tags(trim($_POST['video_descr'][$step_num][$video_num]))!=''){
									$steps[$step_num]['video'][$video_counter]['description']=strip_tags(trim($_POST['video_descr'][$step_num][$video_num]));
								}
				    			/*unset trash data*/
				    			unset($steps[$step_num]['video'][$video_counter]['error']);
				    			unset($steps[$step_num]['video'][$video_counter]['type']);
							}
						}	
					}
					/*order video correctly*/
					if(isset($steps[$step_num]['video']) && is_array($steps[$step_num]['video'])){
						ksort($steps[$step_num]['video']);
					}

	   			}
				

				/*questions saving*/
				if(isset($_POST['int_quest'][$step_num]) && is_array($_POST['int_quest'][$step_num])){

					/*get quest titles*/
					$number_quest=0;
					foreach ($_POST['int_quest'][$step_num] as $quest_num => $quest_title) {
						$number_quest++;
						$steps[$step_num]['questions'][$number_quest]['quest_title']=$quest_title;
						$steps[$step_num]['questions'][$number_quest]['quest_type']=$_POST['type_of_quest'][$step_num][$quest_num];
						
						/*get quest answers*/
						if(isset($_POST['int_answer'][$step_num][$quest_num]) && is_array($_POST['int_answer'][$step_num][$quest_num])){
							
							$number_answer=0;
							foreach ($_POST['int_answer'][$step_num][$quest_num] as $answer_num => $answer) {
								$number_answer++;
								if(!empty($answer))
									$steps[$step_num]['questions'][$number_quest]['answers'][$number_answer]=$answer;								
								if(isset($_POST['int_true'][$step_num][$quest_num][$answer_num]) && $_POST['int_true'][$step_num][$quest_num][$answer_num]=="true"){
									$steps[$step_num]['questions'][$number_quest]['true']=$answer;
								}
							}

						}

						/*get quest answers image*/
						
						if(isset($_POST['image_a_url'][$step_num][$quest_num])){
							
							$number_answer_img[$step_num][$quest_num]=0;
							foreach ($_POST['image_a_url'][$step_num][$quest_num] as $answer_num => $answer) {
								$number_answer_img[$step_num][$quest_num]++;
								if(!empty($answer))
									$steps[$step_num]['questions'][$number_quest]['answers'][$number_answer_img[$step_num][$quest_num]]=$answer;								
								if(isset($_POST['int_true'][$step_num][$quest_num][$answer_num]) && $_POST['int_true'][$step_num][$quest_num][$answer_num]=="true"){
									$steps[$step_num]['questions'][$number_quest]['true']=$answer;
								}
							}

						}
						
						if(isset($_FILES['img_answer']['name'][$step_num][$quest_num])){
							if(!isset($number_answer_img[$step_num][$quest_num])) $number_answer_img[$step_num][$quest_num]=0;
							
							foreach ($_FILES['img_answer']['name'][$step_num][$quest_num] as $answer_num => $answer) {
								$number_answer_img[$step_num][$quest_num]++;
								if($_FILES['img_answer']['error'][$step_num][$quest_num][$answer_num]==0){
									$image_data=wp_upload_bits($_FILES['img_answer']['name'][$step_num][$quest_num][$answer_num],
																				    								null,
																				    								file_get_contents($_FILES['img_answer']['tmp_name'][$step_num][$quest_num][$answer_num]));

									$steps[$step_num]['questions'][$number_quest]['answers'][$number_answer_img[$step_num][$quest_num]]=$image_data['url'];
								}
								if(!isset($steps[$step_num]['questions'][$number_quest]['true'])){		    				
									if(isset($_POST['int_true'][$step_num][$quest_num][$answer_num]) && $_POST['int_true'][$step_num][$quest_num][$answer_num]=="true"){
										$steps[$step_num]['questions'][$number_quest]['true']=$image_data['url'];
									}
								}
							}
						}

						if(isset($_POST['quest_desc'][$step_num][$quest_num]) && $_POST['quest_desc'][$step_num][$quest_num]!=''){
							$steps[$step_num]['questions'][$number_quest]['quest_description']=strip_tags(trim($_POST['quest_desc'][$step_num][$quest_num]));
						}
					}
				}

	   		}
	   					
			if(isset($steps) && is_array($steps)){				
				update_post_meta($post_id, "lms_interaction_data", serialize($steps));
			}	
	   	}

		return $post_id;
	}
	 
		 
	
	
}