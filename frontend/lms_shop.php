<?php is_archive('lms_product') ? get_header() : '' ?>
<?php $user=$GLOBALS['users']->user ?>
<?php $var = get_query_var('product_type');?> 
<?php $page = get_query_var('paged');?> 

<div class="lms">
	<div class='links-bar'>	
		<?php require_once(TPL_DIR."loops/linksbar.php");?>
	</div>
	<form id="lms_search" method="GET">
		<input type='text' name="s" value="<?= isset($_GET['s'])? $_GET['s'] : ''?>" placeholder="Search..." reuqired>
		<button type='submit' class=""><i class="fa fa-search" aria-hidden="true"></i></button>			
	</form>
	<?php if(!isset($_GET['s'])): ?>
	<div class="shop_sort with_search">		
		<a href="<?=home_url('lms_shop')?>" <?= (!$var)? 'class="active"' : '' ?> >All</a>
		<a href="<?=home_url('lms_shop/goods_test')?>" <?= ($var=="goods_test")? 'class="active"' : '' ?>>Tests</a>
		<a href="<?=home_url('lms_shop/goods_groups')?>" <?= ($var=="goods_groups")? 'class="active"' : '' ?>>Groups</a>
	</div>
	<h2>Categories</h2>
	<div class="shop_sort" style="text-align: left;">	
	<?php foreach($GLOBALS['LMS_Shop']->lms_get_terms() as $key => $value): ?>
		<a href="<?=home_url('lms_shop/'.$value->slug)?>" <?= ($var==$value->slug)? 'class="active"' : '' ?>><?=$value->name?></a>
	<?php endforeach; ?>		
	</div>
	<?php endif; ?>	
	<div class="product-list" id="product-list">
		<?php if(have_posts()):
		while (have_posts()): the_post();		
			  	$price = get_post_meta(get_the_ID(), "_lms_price", true);
			  	$lms_test=get_post_meta(get_the_ID(), "_lms_test_id", true);
				$meta_img = get_post_meta($lms_test, "uploader_custom_thumbnail", true);
			  	$img = get_post_meta($meta_img, "_wp_attached_file", true);
		?>
			<div class="the_product_wrapper item-<?the_ID()?>" id="product-<?=get_the_ID()?>">
				<div class="product-info">
					<h3 class="product-title" onclick="window.location='<?=get_the_permalink(get_the_ID())?>'">
						<span><?=the_title()?></span>
						<strong>$ <?=number_format($price, 2, '.', '') ?></strong>
					</h3>
					<ul class="wrapper_info">
						<li class="image_contain" onclick="window.location='<?=get_the_permalink(get_the_ID())?>'">
							<?php if(!empty($img)){?>

								<img src="<?=home_url()?>/wp-content/uploads/<?=$img?>">

							<?php }else{?>

								<img src="<?=home_url()?>/wp-content/plugins/LMSinstruct/assets/images/no-image.jpg">	

							<?php } ?>
						</li>
						<li class="info_contain" onclick="window.location='<?=get_the_permalink(get_the_ID())?>'">
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
					<span class="taxes">Categories:
						<?php foreach (get_the_terms (get_the_ID(), 'product_type') as $key => $term) { ?>
								<a href="<?=home_url('lms_shop/'.$term->slug)?>"><?=$term->name?></a>
						<?php } ?>	
					</span>
				</div>
			</div>
		<?php endwhile; ?>
		<div class="pagenation_wrap"><?php echo LMS_Shop::pagination_products(); ?></div>
	<?php endif;?>
	</div>
</div>

<?php is_archive('lms_product') ?  get_footer() : '' ?>