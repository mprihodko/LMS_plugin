<div class="order_wrapper">
	<input type="hidden" value="Order #<?=$post->ID?>" name="post_title">	
	<?php wp_nonce_field('save_order','_lms_order_nonce'); ?>
	<div class="order_information">
		<div class="order_products order_info_column">
			<h2 class="column_head">Products</h2>
			<ul class="order_product_list">

				<!-- product header -->
				<li class="row row-product row-product-header">
					<div class="product-title product-cell"><strong>Product</strong></div>
					<div class="ordered-views product-cell"><strong>Views</strong></div>
					<div class="product-actions product-cell"><strong>Actions</strong></div>
					<div class="product-price product-cell"><strong>Price</strong></div>
					<div class="product-subtotal-price product-cell"><strong>Subtotal</strong></div>
				</li>
				<!-- product header -->
			<?php $total_views=0; $total_price=0;?>
			<?php if(is_array($order_products) || count($order_products)>0): ?>	
				<?php foreach ($order_products as $product_id => $views) : ?>
				<?php $total_views=$total_views+$views; ?>
				<?php $product = get_post($product_id); ?>
				<?php $product_price = get_post_meta($product_id, "_lms_price", true); ?>
				<?php $total_price=$total_price+$product_price*$views; ?>
				<!-- products -->
				<li class="row row-product product-<?=$product_id?> row-ordered-products" data-product_id="<?=$product_id?>">
					<input type="hidden" name="product_id[]" class="prod-id" value="<?=$product_id?>">
					<input type="hidden" name="product_views[]" class="prod-view" value="<?=$views?>">

					<div class="product-title product-cell">
						<?=$product->post_title?>
					</div>
					<div class="ordered-views product-cell">
						<?=$views?>
					</div>
					<div class="product-actions product-cell">
						<i class="fa fa-minus"></i>
					</div>
					<div class="product-price product-cell">
						$ <b class="product-price"><?=number_format($product_price, 2, '.', '')?></b>
					</div>
					<div class="product-subtotal-price product-cell">
						$ <b class="product-subtotal-price-active"><?=number_format($product_price*$views, 2, '.', '')?></b>
					</div>
				</li>
				<!-- products -->
			<?php endforeach; ?>
			<?php endif; ?>

				<!-- add one -->
				<li class="row row-product" id="addProduct">
					<div class="product-title product-cell">
						<input type='hidden' id="productID" autocomplete="off" >
						<input type='text' id="productName" class="form-control" autocomplete="off" placeholder="Product Name">
						<ul id="autocomplete"></ul>
					</div>
					<div class="ordered-views product-cell">
						<input type='number' min="1" step="1" id="productViews" value="1" size="6" class="form-control">
					</div>
					<div class="product-actions product-cell">
						<i id="add_new_product" class="fa fa-plus"></i>
					</div>
					<div class="product-price product-cell">
						$ <b id="priceContain" class="product-price">0.00</b>
					</div>
					<div class="product-subtotal-price product-cell">
						$ <b id="subtotalContain" class="product-subtotal-price">0.00</b>
					</div>
				</li>
				<!-- add one -->

				<!-- totals -->
				<li class="row row-total">
					<div class="total">Order Total :</div>
					<div class="total_price">$ <b id="total_price"><?=number_format($total_price, 2, '.', '')?></b></div>
				</li>
				<!-- totals -->
			</ul>
		</div>



		<div class="user_info order_info_column">
			<h2 class="column_head">User Information</h2>
			<ul class="user_data_information">

				<!-- user information -->
				<li class="row user-data-row">
					<div class="row-head">Login :</div>
					<div class="row-info">
						<input type="hidden" name="order_user_id" id="order_user_id" value="<?=isset($order_user_data['id']) ? $order_user_data['id'] : '' ?>">
						<input type="text" name="order_user_login" class="form-control" id="order_user_login" value="<?=isset($order_user_data['login']) ? $order_user_data['login'] : '' ?>" required >
						<ul id="user_suggestions"></ul>
					</div>
				</li>
				<li class="row user-data-row">
					<div class="row-head">Email :</div>
					<div class="row-info">
						<span id="email"><?=isset($order_user_data['email']) ? $order_user_data['email'] : ' - ' ?></span>
						<input type="hidden" name="order_user_email" id="order_user_email" value="<?=isset($order_user_data['email']) ? $order_user_data['email'] : '' ?>" required>
					</div>
				</li>
				<li class="row user-data-row">
					<div class="row-head">First Name :</div>
					<div class="row-info">
						<span id="first_name"><?=isset($order_user_data['fname']) ? $order_user_data['fname'] : ' - ' ?></span>
						<input type="hidden" name="order_user_fname"  id="order_user_fname" value="<?=isset($order_user_data['fname']) ? $order_user_data['fname'] : '' ?>"  required>
					</div>
				</li>
				<li class="row user-data-row">
					<div class="row-head">Last Name :</div>
					<div class="row-info">
						<span id="last_name"><?=isset($order_user_data['lname']) ? $order_user_data['lname'] : ' - ' ?></span>
						<input type="hidden" name="order_user_lname" id="order_user_lname" value="<?=isset($order_user_data['lname']) ? $order_user_data['lname'] : '' ?>" required>
					</div>
				</li>

				<!-- user information -->

			</ul>
		</div>


		<div class="order_status order_info_column">
			<h2 class="column_head">Order Data</h2>
			<ul class="order_data_information">

				<!-- order additional info -->
				<li class="row order-data-info">
					<div class="row-head">Date :</div>
					<div class="row-info">
					<?=isset($post->post_date) ? $post->post_date : '<span>'.date("d-m-Y H:i:s").'</span><input type="hidden" value="'.date("d-m-Y H:i:s").'" name="order_info_date" required>' ?>
					</div>
				</li>
				<li class="row order-data-info">
					<div class="row-head">Status :</div>
					<div class="row-info">
						<select name="order_status" class="form-control">							
							<option value="new" <?=isset($order_data_info['status']) && $order_data_info['status']=='new' ? 'selected' : '' ?>>
								New Order
							</option>
							<option value="complete" <?=isset($order_data_info['status']) && $order_data_info['status']=='complete' ? 'selected' : '' ?>>
								Complete
							</option>
							<option value="canceled" <?=isset($order_data_info['status']) && $order_data_info['status']=='canceled' ? 'selected' : '' ?>>
								Canceled
							</option>
							<option value="pending" <?=isset($order_data_info['status']) && $order_data_info['status']=='pending' ? 'selected' : '' ?>>
								Pending
							</option>
						</select>					
					</div>
				</li>
				<li class="row order-data-info">
					<div class="row-head">Transication ID :</div>
					<div class="row-info"><b id="order_total_views"><?=isset($order_data_info['payment_id'])? $order_data_info['payment_id'] : '-' ?></b></div>
				</li>
				<li class="row order-data-info">
					<div class="row-head">Attached Group :</div>
					<div class="row-info">
					<?=isset($order_data_info['group_id'])? $order_data_info['group_id'].'<input type="hidden" value="'.$order_data_info['group_id'].'" required name="group_id" >' : '<input type="text" required name="group_id" class="form-control" placeholder="Group ID">' ?>
					</div>
				</li>
				<li class="row order-data-info">
					<div class="row-head">Total Views :</div>
					<div class="row-info"><b id="order_total_views"><?=$total_views?></b></div>
				</li>
				<!-- order additional info -->

			</ul>
		</div>
	</div>
</div>


<script type="text/template" id="addItem">
	<li class="row row-product row-ordered-products product-{id}" data-product_id="{id}">
		<input type="hidden" name="product_id[]" class="prod-id"  value="{id}">
		<input type="hidden" name="product_views[]" class="prod-view"  value="{views}">
		<div class="product-title product-cell">{title}</div>
		<div class="ordered-views product-cell">{views}</div>
		<div class="product-actions product-cell"><i class="fa fa-minus"></i></div>
		<div class="product-price product-cell">$ <b class="product-price">{price}</b></div>
		<div class="product-subtotal-price product-cell">$ <b class="product-subtotal-price-active">{subtotal}</b></div>
	</li>
</script>