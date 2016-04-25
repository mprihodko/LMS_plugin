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
		else $offset=20*$page;
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

	public static function pagination_products(){		
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

	public function lms_get_terms(){
		$terms=array();
		if(get_bloginfo('version')<4.5){
			$terms = get_terms( 'product_type', array(
				'hide_empty' => true,
			) );
		}else{
			$terms = get_terms( array(
				'taxonomy' => 'product_type',
				'hide_empty' => true,
			) );
		}		
		foreach ($terms as $key => $value) {
			if($value->parent>0)
				$taxes[$key]=$value;
		}
		return $taxes;
	}

	public static function lms_get_terms_products_type(){
		$terms=array();
		if(get_bloginfo('version')<4.5){
			$terms = get_terms( 'product_type', array(
				'hide_empty' => true,
			) );
		}else{
			$terms = get_terms( array(
				'taxonomy' => 'product_type',
				'hide_empty' => true,
			) );
		}		
		foreach ($terms as $key => $value) {
			if($value->parent>0)
				$taxes[$key]=$value;
		}
		return $taxes;
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
