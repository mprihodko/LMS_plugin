<div class="checkout_tab_content ">	
	<div class="OrderItems">
		<ul class="cart_products">
		<?php $total=0; ?>
		<?php $cart_items=$GLOBALS['LMS_Cart']->the_Cart(); ?>
		<?php if(is_array($cart_items) && count($cart_items)>0): ?>
			<?php foreach ($cart_items as $key => $product):  ?>
			<?php $total=$total+$product->price;?>			
			<li class="cart-items">
				<div class="cart_info product_name"><?=$product->post_title?></div>
				<input type="hidden" value="<?=$product->ID?>" class="products_input" name="product_id[]">
				<div class="cart_info views"><input type="number" data-product_id="<?=$product->ID?>" value="<?=$product->views?>" class="checkout_views" size="6" step="1" min="1" name="product_views[]"></div>				
				<div class="cart_info product_price" id="price-<?=$product->ID?>">$ <?=number_format($product->price, 2, '.', '')?></div>
			</li>			
			<?php endforeach; ?>			
		<?php endif; ?>
			<li class="cart-totals">
				<div class="cart_info product_total">Total:</div>
				<div class="cart_info product_price" id="lms_total">$ <?=number_format($total, 2, '.', '')?></div>				
			</li>
		</ul>
	</div>
	<button class="prev_btn" type="button">PREV</button>
	<button class="next_btn" type="button">NEXT</button>
</div>