<?php 

class LMS_Shop extends Tests{
	
	public $data;
	public $user;
	public $products;	
	public $db;


	public function __construct(){
		global $wpdb;
		$this->db=$wpdb;
		$this->user=$GLOBALS['users']->user->ID;
		$this->products=$this->the_products();
		
		add_shortcode('checkout', 									array($this, 'checkout'));
		add_shortcode('lms_shop', 									array($this, 'lms_shop'));

		add_action('wp_ajax_get_user_checkout_detail', 				array( $this, 'get_user_checkout_detail'));
        add_action('wp_ajax_nopriv_get_user_checkout_detail', 		array( $this, 'get_user_checkout_detail'));
        
	}



#####################################################################################################################
#																													#
#  THE PRODUCT DETAILS 																								#
#																													#
#####################################################################################################################	

	/*get_price*/
	public function the_item_price($product_id){
		return get_post_meta($product_id, "_lms_price", true);
	}

	/*Get Products*/
	public static function the_products($var=null, $page=null){
		if($page==null) $offset=0;
		else $offset=1*$page;
		$args=array(
			'numberposts'     => 20, 
			'offset'          => $offset,
			'order'           => 'DESC',			
			'post_type'       => 'lms_product',			
			'post_status'     => 'publish'
		);
		if($var == "goods_test"){
			$args['meta_key']='_lms_product_type';
			$args['meta_value']= "tests";
		}elseif($var == "goods_groups"){
			$args['meta_key']='_lms_product_type';
			$args['meta_value']= "groups";
		}
			
		$products=get_posts($args);
		return $products;
		
	
	}


#####################################################################################################################
#																													#
#  THE PRODUCT TEMPLATES 																							#
#																													#
#####################################################################################################################	
	public function lms_shop(){
		require_once(TPL_DIR."lms_shop.php");
	}

	public function checkout(){
		require_once(TPL_DIR."checkout.php");		
	}
	public function get_user_checkout_detail(){
		require_once(TPL_DIR."shop/user_checkout.php");	
		wp_die();
	}
	

	public function the_group_templates(){		
		
	}

}
