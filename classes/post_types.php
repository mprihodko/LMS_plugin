<?php 

class LMS_post_types{


	public function __construct(){
		add_action( 'init',						array($this, 'create_product_taxonomies'), 0 );
		add_action(	'init', 					array($this, 'register_lms_product_posttype'),1);		
		add_action('init', 							array( $this, 'rewrite_rule_tax' ),1);
		add_filter( 'generate_rewrite_rules',	array($this, 'taxonomy_slug_rewrite'),1);
        // add_filter( 'post_type_link', 			array($this, 'filter_post_type_link'), 10, 2);
        add_filter('post_link',						array( $this, 'filter_post_type_link'), 1, 3);
		add_filter('post_type_link', 				array( $this, 'filter_post_type_link'), 1, 3);
		add_filter( 'rewrite_rules_array', 		array($this, 'my_insert_rewrite_rules') );
		add_action( 'wp_loaded', 				array($this, 'my_flush_rules') ); 
	}


#####################################################################################################################
#																													#
#  THE PRODUCT POST TYPE OPTIONS 																					#
#																													#
#####################################################################################################################

	public function rewrite_rule_tax(){
			global $wp_rewrite;	
			$queryarg = 'post_type=lms_product&p=%lms_product%&id=';
			$wp_rewrite->add_rewrite_tag('%product_type%', '([^&]+)', $queryarg);						
			$wp_rewrite->add_permastruct('lms_product', 'lms_shop/%product_type%/%lms_product%/', true);		
	}		

	function filter_post_type_link( $post_link, $id, $leavename = FALSE ) {		
	    if ( strpos($post_link, '%product_type%') === 'FALSE' ) {
	      return $post_link;
	    }
	    $post = get_post($id);

	    if (!$post) return $post_link;
	    if ( !is_object($post) || $post->post_type != 'lms_product' ) {
	      return $post_link;
	    }
	    $terms = wp_get_object_terms($post->ID, 'product_type');
	  
	    if (!is_wp_error($terms) && !empty($terms) && is_object($terms[0]))
        	$taxonomy_slug = $terms[0]->slug;
        else 
        	$taxonomy_slug = 'no-type';
        $new_link=str_replace('%product_type%', $taxonomy_slug, $post_link);
	    
	    return $new_link;
	}

	

	function create_product_taxonomies(){

	  	register_taxonomy(
		        'product_type',
		        'lms_product',
		        array(
		            'hierarchical' 	=> true,
		            'public'		=> true,
		            'label' 		=> 'product_type',  //Display name
		            'query_var' 	=> true,
		            'rewrite' 		=> array(
		                'slug' 			=> 'lms_shop/product_type',
		                'with_front' => false // This controls the base slug that will display before each term
		            ),
		            '_builtin'		=> false
		        )
		    );
	}


	/*Register POST TYPE PRODUCT*/
	public function register_lms_product_posttype(){

		register_post_type( 'lms_product',	    
		    array(
			    'labels' => array(
			        'name' => __( 'Products' ),
			        'singular_name' => __( 'Product' )
			    ),
			    'public' => true,
			    'has_archive' => true,
			    'menu_position' => 77,
			    'taxonomies' => array("product_type"),
			    'rewrite' => array('slug' => 'lms_shop', 'with_front' => false)	
		    	)
		  	);
	}

	
	function my_flush_rules(){
	    $rules = get_option( 'rewrite_rules' );
	            global $wp_rewrite;
	    $wp_rewrite->flush_rules();
	} 

	// Adding a new rule    
	function my_insert_rewrite_rules( $rules ){
	    $newrules = array();
	    $newrules['lms_shop/?$'] = 'index.php?post_type=lms_product';
	    $newrules['lms_shop/page/?([0-9]{1,})/?$'] = 'index.php?post_type=lms_product&paged=$matches[1]';
	    $newrules['lms_shop/(.+?)/page/?([0-9]{1,})/?$'] = 'index.php?post_type=lms_product&product_type=$matches[1]&paged=$matches[2]';

	  
	    // print_r($rules);
	    return $newrules + $rules;
	}


	function taxonomy_slug_rewrite($wp_rewrite){
		$newrules = array();
		$taxonomies = get_taxonomies(array('_builtin' => false, 'name' => 'product_type'), 'objects');

		    // get all custom post types 
        foreach ($taxonomies as $taxonomy) {

            // go through all post types which this taxonomy is assigned to
            foreach ($taxonomy->object_type as $object_type) {	
                          
                    // get category objects
                    $terms = get_categories(array('type' => $object_type, 'taxonomy' => $taxonomy->name, 'hide_empty' => 0));		             
                    
                    // make rules
                    foreach ($terms as $term) {                    	
                        $newrules['lms_shop/' . $term->slug. '/?$'] = 'index.php?' . $term->taxonomy . '=' . $term->slug;	
                    	$newrules["lms_shop/" . $term->slug. "/page/?([0-9]{1,})/?$"]="index.php?" . $term->taxonomy ."=". $term->slug.'&paged=$matches[1]';
                    }
               
            }
        }        
        $wp_rewrite->rules = $newrules + $wp_rewrite->rules;
	}



#####################################################################################################################
#																													#
#  THE TEST POST TYPE OPTIONS 																						#
#																													#
#####################################################################################################################




}