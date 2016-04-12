<div id="_lms_cartWrapper" class="_lms_cart_wrapper">
	<div id="cart-icon" class="<?=isset($_POST['active'])? $_POST['active'] : ""?>"><i class="fa fa-shopping-cart"></i></div>
	<div class="lms_cart <?=isset($_POST['active'])? $_POST['active'] : ""?>">
		<ul class="cart_products">
			<li class="cart-items-head">
				<div class="cart_info product_num">#</div>
				<div class="cart_info product_name">Product</div>
				<div class="cart_info views">Views</div>
				<div class="cart_info product_price">Price</div>
			</li>
		<?php $total=0; ?>
		<?php if(is_array($this->cart_items) && count($this->cart_items)>0): ?>
			<?php foreach ($this->cart_items as $key => $product):  ?>
			
			<li class="cart-items">
				<div class="cart_info delete_item" data-product_id='<?=$product->ID?>'><i class="fa fa-times"></i></div>
				<div class="cart_info product_name"><?=$product->post_title?></div>
				<div class="cart_info views"><?=$product->views?></div>
				<div class="cart_info product_price">$ <?=number_format($product->price, 2, '.', '')?></div>
			</li>
			<?php $total=$total+$product->price;?>
			<?php endforeach; ?>
		<?php else: ?>
			<li>
				<h2>Your Cart is empty</h2>
			</li>	
		<?php endif; ?>
			<li class="cart-totals">
				<div class="cart_info product_total">Total:</div>
				<div class="cart_info product_price">$ <?=number_format($total, 2, '.', '')?></div>				
			</li>
			<li class="cart-checkout">
			<?php if(is_array($this->cart_items) && count($this->cart_items)>0): ?>
				<div class="cart_info product_checkout">
					<a href="/checkout" class="checkout" id="checkout"> Checkout </a>
				</div>
			<?php endif; ?>
			</li>
		</ul>		
	</div>
</div>
<div id="succeses_add_to_cart">
	<p>
		<span>This course is added to your cart, would you like to</span>
		<a href="#" id="continue_shopping">Continue Shopping</a>
		<span> or </span>
		<a href="/checkout">Checkout?</a>
	</p>
</div>
<script type="text/javascript">
	 jQuery("#continue_shopping").live("click", function(e){   
	 	e.preventDefault();        
            jQuery(".ui-dialog-titlebar button").click()
          }) 
</script>