<div class="lms">
	<div class='links-bar'>	
		<?php require_once(TPL_DIR."loops/linksbar.php");?>
	</div>
	<?php if($GLOBALS['LMS_Cart']->total_cart_items>0): ?>
		<div class="cart_wrapper_content" id="cart">
			<ul class="cart_items container">
				<li class="cart-items-head">
					<div class="cart_info product_num">#</div>
					<div class="cart_info product_name">Product</div>
					<div class="cart_info views">Views</div>
					<div class="cart_info product_price">Price</div>
				</li>
				<?php $total=0; ?>
				<?php if(is_array($this->cart_items) && count($this->cart_items)>0): ?>
				<?php foreach ($this->cart_items as $key => $product):  ?>
				<li class="cart-item item-{ID}">
					<div class="cart_info delete_item" data-product_id='<?=$product->ID?>'><i class="fa fa-times"></i></div>
					<div class="cart_info product_name"><?=$product->post_title?></div>
					<div class="cart_info views"><input type="number" data-product_id="<?=$product->ID?>" value="<?=$product->views?>" class="checkout_views" size="6" step="1" min="1" name="test_views"></div>
					<div class="cart_info product_price" id="price-<?=$product->ID?>">$ <?=number_format($product->price, 2, '.', '')?></div>
				</li>	
				<?php $total=$total+$product->price;?>
				<?php endforeach; ?>	
				<?php endif; ?>
				<li class="cart-totals">
					<div class="cart_info product_total">Total:</div>
					<div class="cart_info product_price" id="lms_total">$ <?=number_format($total, 2, '.', '')?></div>				
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
	<?php else: ?>
		<div class="empty-info">
			<h2>Your Cart is empty</h2>
			<a href="<?=home_url('lms_shop')?>" class="go_shop_btn">Go Shop!</a>
		</div>
	<?php endif; ?>
</div>