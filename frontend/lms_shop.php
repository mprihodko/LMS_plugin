<?php is_archive('lms_product') ? get_header() : '' ?>
<?php $user=$GLOBALS['users']->user ?>
<?php $var = get_query_var('product_type');?> 
<?php $page = get_query_var('paged');?> 


<div class="lms">
	<div class='links-bar'>	
		<?php require_once(TPL_DIR."loops/linksbar.php");?>
	</div>
	<div class="shop_sort">
		<a href="<?=home_url('lms_shop')?>" <?= (!$var)? 'class="active"' : '' ?> >All</a>
		<a href="<?=home_url('lms_shop/goods_test')?>" <?= ($var=="goods_test")? 'class="active"' : '' ?>>Tests</a>
		<a href="<?=home_url('lms_shop/goods_groups')?>" <?= ($var=="goods_groups")? 'class="active"' : '' ?>>Groups</a>
	</div>
	<div class="product-list" id="product-list">
		<?php if(have_posts()):
		while (have_posts()): the_post();
		
			  	$price = get_post_meta(get_the_ID(), "_lms_price", true);
			  	$lms_test=get_post_meta(get_the_ID(), "_lms_test_id", true);
				$meta_img = get_post_meta($lms_test, "uploader_custom_thumbnail", true);
			  	$img = get_post_meta($meta_img, "_wp_attached_file", true);
		?>
			<div class="the_product_wrapper item-<?the_ID()?>" id="product-<?=get_the_ID()?>">
				<a href="<?=get_the_permalink(get_the_ID())?>" class="product-info">
					<h3 class="product-title">
						<span><?=the_title()?></span>
						<strong>$ <?=number_format($price, 2, '.', '') ?></strong>
					</h3>
					<ul class="wrapper_info">
						<li class="image_contain">
							<?php if(!empty($img)){?>

								<img src="<?=home_url()?>/wp-content/uploads/<?=$img?>">

							<?php }else{?>

								<img src="<?=home_url()?>/wp-content/plugins/LMSinstruct/assets/images/no-image.jpg">	

							<?php } ?>
						</li>
						<li class="info_contain">
							<?php 	if(get_the_content() && strlen(get_the_content())>250)
										print mb_substr(strip_tags(get_the_content()), 0, 250, "UTF8").'...';
							      	else 
							      		print get_the_content(); 
							?>
						</li>
						<li class="addToCart_contain">
							<input type="hidden" value="1" id="lms_test_views-<?=get_the_ID()?>">
							<button class="addToCart" type="button" id="addToCart-<?=get_the_ID()?>" data-views="1" data-product_id="<?=get_the_ID()?>">Add To Cart</button>
						</li>
					</ul>
				</a>
			</div>
		<?php endwhile; ?>
	<?php endif;?>
	</div>
</div>

<?php is_archive('lms_product') ?  get_footer() : '' ?>