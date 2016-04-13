<?php 


class LMS_Order extends LMS_Shop{

	
	public function __construct(){
		parent::__construct();
		$this->data=array();
		/*actions*/
		add_action(	"init", 									array( $this, 'register_pt_Order'));
		add_action( 'admin_init', 								array( $this, 'script_admin_init'));		
		add_action(	'manage_lms_order_posts_custom_column', 	array( $this, 'manage_lms_order_columns'), 10, 2);
		add_action( 'add_meta_boxes', 							array( $this, 'remove_metaboxes'),11 );
		add_action( 'add_meta_boxes', 							array( $this, 'add_meta_box_order_lms'),11 );
		add_action( 'save_post', 								array( $this, 'lms_save_order'));
		/*filters*/
		add_filter('manage_edit-lms_order_columns', 			array( $this, 'orders_page_columns'));
		add_action('wp_ajax_save_order', 						array( $this, 'lms_save_order_front_end'));
        add_action('wp_ajax_nopriv_save_order', 				array( $this, 'lms_save_order_front_end'));
	}



#####################################################################################################################
#																													#
#  ORDER POST TYPE OPTIONS 																							#
#																													#
#####################################################################################################################

	/*REGISTER NEW POST TYPE ORDER*/
	public function register_pt_Order(){
	    register_post_type( 'lms_order',

				array(
					'labels'              => array(
							'name'               => __( 'Orders', 'lms_admin_setup' ),
							'singular_name'      => __( 'Order', 'lms_admin_setup' ),
							'add_new'            => __( 'Add Order', 'lms_admin_setup' ),
							'add_new_item'       => __( 'Add New Order', 'lms_admin_setup' ),
							'edit'               => __( 'Edit', 'lms_admin_setup' ),
							'edit_item'          => __( 'Edit Order', 'lms_admin_setup' ),
							'new_item'           => __( 'New Order', 'lms_admin_setup' ),
							'view'               => __( 'View Order', 'lms_admin_setup' ),
							'view_item'          => __( 'View Order', 'lms_admin_setup' ),
							'search_items'       => __( 'Search Orders', 'lms_admin_setup' ),
							'not_found'          => __( 'No Orders found', 'lms_admin_setup' ),
							'not_found_in_trash' => __( 'No Orders found in trash', 'lms_admin_setup' ),
							'parent'             => __( 'Parent Orders', 'lms_admin_setup' ),
							'menu_name'          => _x( 'Orders', 'Admin menu name', 'lms_admin_setup' )
						),
					'description'         => __( 'This is where store orders are stored.', 'lms_admin_setup' ),
					'public'              => false,
					'show_ui'             => true,
					// 'capability_type'     => 'administrator',
					'map_meta_cap'        => true,
					'publicly_queryable'  => false,
					'exclude_from_search' => true,
					'show_in_menu'        => current_user_can( 'administrator' ) ? 'lms_admin_setup' : true,
					'hierarchical'        => false,
					'show_in_nav_menus'   => false,
					'rewrite'             => false,
					'query_var'           => false,
					'supports'            => array('custom-fields', 'revisions'),
					'has_archive'         => false,
				)
		  	);	
	}

	/*  Register scripts  */
	public function script_admin_init() {
		wp_register_script( 'tests-edit-page-script', ASSETS_DIR . 'js_min/admin.min.js', array('jquery'), '1.0', false );
		wp_enqueue_script( 'tests-edit-page-script' );
	}

	/*POST TYPE ORDER COLUMNS FILTERS*/
	public function orders_page_columns(){
		$new_columns['cb'] 		= '<input type="checkbox" />'; 
    	$new_columns['status'] 	= __('Status');  
    	$new_columns['title'] 	= __('Order #');
    	$new_columns['customer'] 	= __('Customer');     
    	$new_columns['total'] 	= __('Order Total');    	
    	$new_columns['date'] 	= _x('Date', 'column name');
 
    	return $new_columns;
	}

	/*POST TYPE ORDER COLUMNS INSERTING DATA*/
	public function manage_lms_order_columns($column_name, $id){		
		$order_user_data=$this->get_lms_order_customer_info($id);
		$order_data_info=$this->get_lms_order_info($id);
		switch ($column_name) {
		    case 'id':
		       		echo "#".$id;
		            break;		   
		    case 'status':	       
			        if(isset($order_data_info['status'])){
			        	if($order_data_info['status']=='canceled'){
			        		echo '<div class="status_wrapper"><i class="fa fa-times-circle-o"></i><span class="status_info">'.$order_data_info['status'].'</span></div>';
			        	}
			        	if($order_data_info['status']=='complete'){
			        		echo '<div class="status_wrapper"><i class="fa fa-check-circle-o"></i><span class="status_info">'.$order_data_info['status'].'</span></div>';
			        	}
			        	if($order_data_info['status']=='new'){
			        		echo '<div class="status_wrapper"><i class="fa fa-circle-o"></i><span class="status_info">'.$order_data_info['status'].'</span></div>';
			        	}
			        	if($order_data_info['status']=='pending'){
			        		echo '<div class="status_wrapper"><i class="fa fa-usd"></i><span class="status_info">'.$order_data_info['status'].'</span></div>';
			        	}
			        }
			        break;		   
		    case 'total':	       
			        echo "$ ". number_format($this->get_order_total($id)['price'], 2, ".", "");
			        break;
			case 'customer':
					echo "<a href='/wp-admin/user-edit.php?user_id=".$order_user_data['id']."'><strong>".$order_user_data['fname']." ".$order_user_data['lname']."</strong><br><span>".$order_user_data['email']."</span></a>";
					break;
		    default:
		        break;
		} 
	}

	/*POST TYPE ORDER METABOXES ACTION REMOVE*/
	public function remove_metaboxes(){		
		remove_meta_box( 'mymetabox_revslider_0', 'lms_order', 'normal' );          
	}

	/*POST TYPE ORDER METABOXES ACTION ADD*/
	public function add_meta_box_order_lms(){
		add_meta_box( "_lms_order_totals", 'Order Information', array($this, 'lms_order_callback'), 'lms_order', 'normal', 'high');
	}

	/*ORDER HTML*/
	public function lms_order_callback(){
		global $post;		
		$order_products=$this->get_lms_order_products_info($post->ID);
		$order_user_data=$this->get_lms_order_customer_info($post->ID);
		$order_data_info=$this->get_lms_order_info($post->ID);
		require_once(IAMD_TD.'/admin/shop/order_callback.php');
	}



#####################################################################################################################
#																													#
#  GET ORDER DETAILS 																								#
#																													#
#####################################################################################################################	


	/*ORDER CUSTOMER INFORMATION*/
	public function get_lms_order_customer_info($order_id=null){
		if($order_id==null) return false;
		$order_info=unserialize(get_post_meta($order_id, '_lms_order_totals', true));			
		if(is_array($order_info)){
			return $order_info['user_data'];			
		}
		return false;
	} 

	/*ORDER PRODUCTS INFORMATION*/
	public function get_lms_order_products_info($order_id=null){
		if($order_id==null) return false;
		$order_info=unserialize(get_post_meta($order_id, '_lms_order_totals', true));			
		if(is_array($order_info)){
			return $order_info['products'];			
		}
		return false;
	}

	/*ORDER INFORMATION*/
	public function get_lms_order_info($order_id=null){
		if($order_id==null) return false;
		$order_info=unserialize(get_post_meta($order_id, '_lms_order_totals', true));			
		if(is_array($order_info)){
			return $order_info['data_info'];			
		}
		return false;
	}	

	/*ORDER TOTALS*/
	public function get_order_total($id=null){
		$total['price']=0; 
		$total['views']=0;
		if($id==null) return $total;
		foreach ($this->get_lms_order_products_info($id) as $product_id =>$views) {
			$total['price']=$total['price']+$views*get_post_meta($product_id, "_lms_price", true);
			$total['views']=$total['views']+$views;
		}
		return $total;
	}

#####################################################################################################################
#																													#
#  SAVE ORDER METHOD 																								#
#																													#
#####################################################################################################################	

	public function lms_save_order($post_id){		
		if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
			return $post_id;
		if(!isset($_POST['_lms_order_nonce']))			
			return $post_id;
		$this->data=array();
		remove_action('save_post', 				array($this, 'lms_save_order'), 10, 1); 

		$product = array(
			'ID'			=> $post_id,
			'post_title'    =>  "Order #".$post_id,	
			'post_status'   => 'publish',			
			'post_type'     => 'lms_order'							
		);
		$product_id = wp_update_post( $product );

		add_action('save_post', 				array($this, 'lms_save_order'), 10, 1);

		$order_info['user_data']['id']			=$_POST['order_user_id'];
		$order_info['user_data']['login']		=$_POST['order_user_login'];
		$order_info['user_data']['email']		=$_POST['order_user_email'];
		$order_info['user_data']['fname']		=$_POST['order_user_fname'];
		$order_info['user_data']['lname']		=$_POST['order_user_lname'];
		$order_info['data_info']['status']		=$_POST['order_status'];
		$order_info['data_info']['group_id']	=$_POST['group_id'];
		$order_info['data_info']['total_views'] = 0;

		foreach ($_POST['product_id'] as $key => $value) {
			$order_info['products'][$value]=$_POST['product_views'][$key];	
			$order_info['data_info']['total_views'] =$order_info['data_info']['total_views']+$_POST['product_views'][$key];
			$tests[get_post_meta($value,'_lms_test_id', true)]=$_POST['product_views'][$key];
		}

		if(!$GLOBALS['groups']->is_group_exists($_POST['group_id'])){

			$this->data['name']=((isset($_POST['group_id']))?strip_tags(trim($_POST['group_id'])) : '');
			$this->data['view_limit']=$order_info['data_info']['total_views'];
			$this->data['text_id']=((isset($_POST['group_id']))?strip_tags(trim($_POST['group_id'])) : '');
			$this->data['description']='';
			$this->data['user_id']=$_POST['order_user_id'];
			$this->data['notice']=((isset($_POST['notice']))?strip_tags(trim($_POST['notice'])) : '');
			$this->data['group_test_view']=((isset($_POST['group_test_view']))?strip_tags(trim($_POST['group_test_view'])) : '');
			$this->data['video_review']=((isset($_POST['video_review']))?strip_tags(trim($_POST['video_review'])) : '');
			$this->data['video_demand']=((isset($_POST['video_demand']))?strip_tags(trim($_POST['video_demand'])) : '');
			$this->data['remove']='0';
			$query=$this->db->insert($this->db->prefix."lms_groups", $this->data);
			$new_group_id=$this->db->insert_id;
			$query=$this->db->insert($this->db->prefix."lms_group_users",
									 array("group_id"=>$new_group_id, "user_id"=>$_POST['order_user_id'], "user_level"=>2));
		}
		foreach ($tests as $test => $views) {

			/*insert*/
			if(!isset($new_group_id))
				$new_group_id=$GLOBALS['groups']->is_group_exists($_POST['group_id']);
			$query=$this->db->get_results("	SELECT *
											FROM  ".$this->db->prefix."lms_group_tests
											WHERE `group_id`=".$new_group_id."
											AND `test_id`=".$test );			
			if(isset($query[0]->test_id) && $query[0]->test_id==$test):
				$update_views=$views+$query[0]->view_limit;
				$query=$this->db->update($this->db->prefix."lms_group_tests",
										 array(	'view_limit'=>strip_tags(trim($update_views))),
										 array(	"group_id"=>$new_group_id,
										 	   	"test_id"=>$test)
										);
			else:
				$query=$this->db->insert($this->db->prefix."lms_group_tests",
										 array(	'test_id'=>$test,
										 		'group_id'=>$new_group_id,
										 		'view_limit'=>strip_tags(trim($views))
										 		)
										);
			endif;								
		}

		$order=serialize($order_info);
		update_post_meta($post_id, '_lms_order_totals', $order);		
	}
	

	public function lms_save_order_front_end(){
		// var_dump($_POST); die;
		if(isset($_POST['action']) && $_POST['action']=="save_order"){
			
			$ordered_groups=array();
			$order_info=array();
			if(isset($_POST['group_id'])){
				parse_str($_POST['group_id'], $ordered_groups);
				// var_dump($ordered_groups);
				foreach ($ordered_groups['group_id'] as $key => $group) {

					$order_info[$group]['user_data']['id']			=$_POST['order_user_id'];
					$order_info[$group]['user_data']['login']		=$_POST['order_user_login'];
					$order_info[$group]['user_data']['email']		=$_POST['order_user_email'];
					$order_info[$group]['user_data']['fname']		=$_POST['order_user_fname'];
					$order_info[$group]['user_data']['lname']		=$_POST['order_user_lname'];
					$order_info[$group]['data_info']['status']		=$_POST['order_status'];
					$order_info[$group]['data_info']['group_id']	=$group;					
					$order_info[$group]['data_info']['total_views'] = 0;

					foreach ($_POST['product_id'] as $num => $value) {	
						if(get_post_meta($value, "_lms_product_type", true)=='groups'){
							$order_info[$group]['products'][$value]=$_POST['product_views'][$num];
							$tests=unserialize(get_post_meta($value,'_lms_tests', true));
							foreach ($tests as $k => $test_id) {							
								$order_info[$group]['tests'][$test_id]=ceil($_POST['product_views'][$num]/count($tests));
							}	
							$order_info[$group]['data_info']['total_views'] =$order_info[$group]['data_info']['total_views']+$_POST['product_views'][$num];	
						}
					}
				}
			}
			if(isset($_POST['group_custom'])){
				$ordered_groups=array();			
				parse_str($_POST['group_custom'], $ordered_groups);			
				foreach ($ordered_groups['group_id'] as $key => $group) {

					$order_info[$group]['user_data']['id']			=$_POST['order_user_id'];
					$order_info[$group]['user_data']['login']		=$_POST['order_user_login'];
					$order_info[$group]['user_data']['email']		=$_POST['order_user_email'];
					$order_info[$group]['user_data']['fname']		=$_POST['order_user_fname'];
					$order_info[$group]['user_data']['lname']		=$_POST['order_user_lname'];
					$order_info[$group]['data_info']['status']		=$_POST['order_status'];
					$order_info[$group]['data_info']['group_id']	=$group;					
					$order_info[$group]['data_info']['total_views'] = 0;

					foreach ($_POST['product_id'] as $num => $value) {	
						if(get_post_meta($value, "_lms_product_type", true)=='tests'){
							$order_info[$group]['products'][$value]=$_POST['product_views'][$num];						
							$order_info[$group]['tests'][get_post_meta($value,'_lms_test_id', true)]=$_POST['product_views'][$num];							
							$order_info[$group]['data_info']['total_views'] =$order_info[$group]['data_info']['total_views']+$_POST['product_views'][$num];	
						}
					}
				}
			}
					
						
			foreach ($order_info as $key => $data) {				
				if(!$GLOBALS['groups']->is_group_exists($key)){

					$this->data['name']=((isset($key))?strip_tags(trim($key)) : '');
					$this->data['view_limit']=$data['data_info']['total_views'];
					$this->data['text_id']=((isset($key))?strip_tags(trim($key)) : '');
					$this->data['description']='';
					$this->data['user_id']=$_POST['order_user_id'];
					$this->data['notice']=((isset($_POST['notice']))?strip_tags(trim($_POST['notice'])) : '');
					$this->data['group_test_view']=((isset($_POST['group_test_view']))?strip_tags(trim($_POST['group_test_view'])) : '');
					$this->data['video_review']=((isset($_POST['video_review']))?strip_tags(trim($_POST['video_review'])) : '');
					$this->data['video_demand']=((isset($_POST['video_demand']))?strip_tags(trim($_POST['video_demand'])) : '');
					$this->data['remove']='0';
					$query=$this->db->insert($this->db->prefix."lms_groups", $this->data);
					$new_group_id=$this->db->insert_id;
					$query=$this->db->insert($this->db->prefix."lms_group_users",
											 array("group_id"=>$new_group_id, "user_id"=>$_POST['order_user_id'], "user_level"=>2));
					
					foreach ($data['tests'] as $test => $views) {
						/*insert*/
						$query=$this->db->get_results("	SELECT *
														FROM  ".$this->db->prefix."lms_group_tests
														WHERE `group_id`=".$new_group_id."
														AND `test_id`=".$test );			
						if(isset($query[0]->test_id) && $query[0]->test_id==$test):
							$update_views=$views+$query[0]->view_limit;
							$query=$this->db->update($this->db->prefix."lms_group_tests",
													 array(	'view_limit'=>strip_tags(trim($update_views))),
													 array(	"group_id"=>$new_group_id,
													 	   	"test_id"=>$test)
													);
						else:
							$query=$this->db->insert($this->db->prefix."lms_group_tests",
													 array(	'test_id'=>$test,
													 		'group_id'=>$new_group_id,
													 		'view_limit'=>strip_tags(trim($views))
													 		)
													);
						endif;								
					}			
				}
				remove_action('save_post', 				array($this, 'lms_save_order'), 10, 1); 
					$post_data = array(
					  'post_title'    =>  "Order #",			  
					  'post_status'   => 'publish',
					  'post_author'   => 1,	
					  'post_type'     => 'lms_order'		  
					);
					$post_id = wp_insert_post( $post_data );
					$product = array(
						'ID'			=> $post_id,
						'post_title'    =>  "Order #".$post_id,	
						'post_status'   => 'publish',			
						'post_type'     => 'lms_order'							
					);
					$product_id = wp_update_post( $product );

				add_action('save_post', 				array($this, 'lms_save_order'), 10, 1);
				$order=serialize($data);
				if(update_post_meta($post_id, '_lms_order_totals', $order)){
					
				}
			}
			if(isset($_POST['payment'])){
				$total=number_format($GLOBALS['LMS_Cart']->the_cart_total(), 2, ".", "");				
				$payment_data=array();
				parse_str($_POST['payment'], $payment_data);
				$payment=pay($payment_data, $total);
				$debug =(array)$payment;
				setcookie("lms_cart", serialize(array()), time()+3600*24, '/');
			}
			echo json_encode(array("success"=>"Order #".$post_id." has been created ", "status"=>1, "debug"=>$debug));

		}else{
			echo json_encode(array("error"=>"Error Action Pay", "status"=>0));
		}
		wp_die();
	}

}