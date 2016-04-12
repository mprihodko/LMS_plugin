<?php 

class LMS_Cart extends LMS_Shop{
	
	private $cart;
	private $cart_items;
	private $total;
	public $total_cart_items;
	public $total_price;

	public function __construct(){
		parent::__construct();
		if(!isset($_COOKIE['lms_cart'])){			
			setcookie("lms_cart", serialize(array()), time()+3600*24, '/');
			$this->cart=array();
			$this->total_cart_items=0;
			$this->total_price=number_format($this->the_cart_total(), 2, '.', '');
		}	
		if(isset($_COOKIE['lms_cart']) && $_COOKIE['lms_cart']!='null'){			
			$this->cart=unserialize($_COOKIE['lms_cart']);
			$this->total_cart_items=count(unserialize($_COOKIE['lms_cart']));
			$this->total_price=number_format($this->the_cart_total(), 2, '.', '');
		}else{
			$this->cart=array();
			$this->total_cart_items=0;
			$this->total_price=number_format($this->the_cart_total(), 2, '.', '');
		}	
        $this->cart_items=$this->the_Cart();	
        add_shortcode('cart', 							array($this,  'cart_page'));

		add_action('wp_ajax_add_to_cart', 				array( $this, 'add_to_Cart'));
        add_action('wp_ajax_nopriv_add_to_cart', 		array( $this, 'add_to_Cart'));
        add_action('wp_ajax_delete_cart_item', 			array( $this, 'delete_Cart_item'));
        add_action('wp_ajax_nopriv_delete_cart_item', 	array( $this, 'delete_Cart_item'));
        add_action('wp_ajax_get_cart_items', 			array( $this, 'get_cart_items_html'));
        add_action('wp_ajax_nopriv_get_cart_items', 	array( $this, 'get_cart_items_html'));
        add_action('wp_ajax_update_cart', 				array( $this, 'update_Cart'));
        add_action('wp_ajax_nopriv_update_cart', 		array( $this, 'update_Cart'));
       
	}

#####################################################################################################################
#																													#
#  CART OPTIONS AND HTML TPLS																						#
#																													#
#####################################################################################################################


	/*CART HTML*/
	public function get_cart_items_html(){
		require_once(TPL_DIR.'/shop/cart.php');
		wp_die();
	}

	

	/*CART PAGE HTML*/
	public function cart_page(){
		require_once(TPL_DIR.'/cart.php');

	}


#####################################################################################################################
#																													#
#  CART AJAX HANDLERS 																								#
#																													#
#####################################################################################################################

	/*AJAX ADD TO CART*/
	public function add_to_Cart(){		
		if(isset($_POST['product_id'])){
			$product_id=strip_tags(trim($_POST['product_id']));	
			$views=strip_tags(trim($_POST['views']));
			$product_exist=0;	

			foreach ($this->cart as $k => $product) {
				foreach ($product as $key => $value) {					
					if($product_id==$key){
						$this->cart[$k][$key]=$value+$views;
						$product_exist=1;
					}
				}
			}			
			if($product_exist==0){
				array_push($this->cart, array($product_id => $views));
				setcookie("lms_cart", serialize($this->cart), time()+3600*24, '/');
				echo json_encode($this->cart);
			}else{
				setcookie("lms_cart", serialize($this->cart), time()+3600*24, '/');
				echo json_encode(array("add"=>"Add ".$views." views"));
			}
		}else{
			echo json_encode(array("error"=>"Undefined item ID"));
		}
		wp_die();
	}

	/*AJAX DELETE CART ITEM*/
	public function delete_Cart_item(){
		if(isset($_POST['product_id'])){
			$product_id=strip_tags(trim($_POST['product_id']));							
			$product_exist=0;			
			foreach ($this->cart as $key => $product) {
				foreach ($product as $k => $value) {					
					if($product_id==$k){
						unset($this->cart[$key]);
					}
				}
			}							
			setcookie("lms_cart", serialize($this->cart), time()+3600*24, '/');
			$this->total_cart_items=count($this->cart);
			if($this->total_cart_items>0)
				$this->total_price=number_format($this->the_cart_total(), 2, '.', '');
			else
				$this->total_price=number_format(0, 2, '.', '');;
			echo json_encode(array("success"=>$this->total_price, "items"=>$this->total_cart_items));
		}else{
			echo json_encode(array("error"=>"Undefined item ID"));
		}
		wp_die();
	}

	/*AJAX UPDATE CART*/
	public function update_Cart(){
		if(isset($_POST['product_id'])){
			$product_id=strip_tags(trim($_POST['product_id']));	
			$views=strip_tags(trim($_POST['views']));
			foreach ($this->cart as $k => $product) {
				foreach ($product as $key => $value) {					
					if($product_id==$key){
						$this->cart[$k][$key]=$views;						
						$price=get_post_meta($key, "_lms_price", true)*$this->cart[$k][$key];						
					}
				}
			}
			if(isset($price) && isset($views)){
				setcookie("lms_cart", serialize($this->cart), time()+3600*24, '/');	
				$this->total_price=number_format($this->the_cart_total(), 2, '.', '');
				echo json_encode(
								array(	"price"=>number_format($price, 2, '.', ''),
										"total"=>$this->total_price,
										"views"=>$views
									)
								);	
			}

		}
		wp_die();
	}

	/*AJAX CLEAR CART*/
	public function clear_Cart(){
		unset($_COOKIE['lms_cart']);
		$this->cart=array();
		$_COOKIE['lms_cart'] = $this->cart;
		echo json_encode($this->cart);
		wp_die();
	}


#####################################################################################################################
#																													#
#  CART DETAILS METHODS																								#
#																													#
#####################################################################################################################


	/*COUNT CART ITEMS*/
	public function the_total_items(){
		$items=0;
		if(is_array(unserialize($_COOKIE['lms_cart']))){	
			foreach (unserialize($_COOKIE['lms_cart']) as $product) {
				foreach ($product as $product_id => $views) {	
					$items++;	
				}
			}	
		}	
		return $items;
	}	

	/*GET CART TOTAL PRICE*/
	public function the_cart_total(){
		$this->total=0;
		if(is_array($this->cart)){	
			foreach ($this->cart as $product) {
				foreach ($product as $product_id => $views) {	
					$this->total=$this->total+get_post_meta($product_id, "_lms_price", true)*$views;	
				}		
			}		
		}
		return $this->total;
	}

	/*GET CART*/
	public function the_Cart(){
		$cart_items=array();
		if(is_array($this->cart)){
			$i=0;
			foreach ($this->cart as $key => $product) {
					$i++;
				foreach ($product as $product_id => $views) {	
					$cart_items[$i]=get_post($product_id);
					$cart_items[$i]->price=get_post_meta($product_id, "_lms_price", true)*$views;
					$cart_items[$i]->views=$views;
				}
			}			
		}		
		return $cart_items;
	}
}

