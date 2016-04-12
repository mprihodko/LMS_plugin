<?php 	get_header(); ?>
<?php 	$user=$GLOBALS['users']->user ?>
<?php 	global $post; ?>
<?php 	
		$price 			= get_post_meta($post->ID, "_lms_price", true);
		$lms_test 		= get_post_meta($post->ID, "_lms_test_id", true);
		$meta_img 		= get_post_meta($lms_test, "uploader_custom_thumbnail", true);
		$img 		 	= get_post_meta($meta_img, "_wp_attached_file", true);		
		$interaction 	= get_post_meta($lms_test, "lms_interactive_status", true);
		$media 			= get_post_meta($lms_test, "lms_attach_video", true);
		$docs 			= get_post_meta($lms_test, "lms_attach_media", true);
		$type 			= get_post_meta($post->ID, "_lms_product_type", true);
?>
<div class="lms">
	<!-- PLUGIN MENU LINKS -->
		<div class='links-bar'>	
			<?php 	require_once(TPL_DIR."loops/linksbar.php");	?>
		</div>
	<!-- PLUGIN MENU LINKS -->

	<div id="product-<?=$post->ID?>" class="single-product-wrapper">
		<h3 class="product-title">
			<span><?=$post->post_title?></span>
			<strong>$ <?=number_format($price, 2, ".", '')?></strong>
		</h3>
		<div class="single-product-col">
			<div class="thumbnail">
				<?php if(!empty($img)){?>

					<img src="<?=home_url()?>/wp-content/uploads/<?=$img?>">

				<?php }else{?>

					<img src="<?=home_url()?>/wp-content/plugins/LMSinstruct/assets/images/no-image.jpg">	

				<?php } ?>
			</div> 
			<div class="additional-info">
			<?php if($type=='tests'): ?>
				<ul class="product-rows">
					<li class="row-info">
						<ul class="row-item">
							<li>Interactive :</li>
							<li><?=$interaction ? '<i class="fa fa-check"></i>' : '<i class="fa fa-minus"></i>' ?></li>
						</ul>
					</li>
					<li class="row-info">
						<ul class="row-item">
							<li>Attached Media :</li>
							<li><?=$media ? '<i class="fa fa-check"></i>' : '<i class="fa fa-minus"></i>'?></li>
						</ul>
					</li>
					<li class="row-info">
						<ul class="row-item">
							<li>Attached Documents :</li>
							<li><?=$docs ? '<i class="fa fa-check"></i>' : '<i class="fa fa-minus"></i>'?></li>
						</ul>
					</li>
					
				</ul>
			<?php elseif($type=='groups'): ?>
				<?php $g_tests=unserialize(get_post_meta($post->ID, "_lms_tests", true)); ?>
				<?php if(is_array($g_tests)): ?>
					<?php foreach ($g_tests as $test_id) :
					$interaction 	= get_post_meta($test_id, "lms_interactive_status", true);
					$media 			= get_post_meta($test_id, "lms_attach_video", true);
					$docs 			= get_post_meta($test_id, "lms_attach_media", true);
					?>
					<h2><?php echo get_post($test_id)->post_title?></h2>
					<ul class="product-rows">
						<li class="row-info">
							<ul class="row-item">
								<li>Interactive :</li>
								<li><?=$interaction ? '<i class="fa fa-check"></i>' : '<i class="fa fa-minus"></i>' ?></li>
							</ul>
						</li>
						<li class="row-info">
							<ul class="row-item">
								<li>Attached Media :</li>
								<li><?=$media ? '<i class="fa fa-check"></i>' : '<i class="fa fa-minus"></i>'?></li>
							</ul>
						</li>
						<li class="row-info">
							<ul class="row-item">
								<li>Attached Documents :</li>
								<li><?=$docs ? '<i class="fa fa-check"></i>' : '<i class="fa fa-minus"></i>'?></li>
							</ul>
						</li>					
					</ul>
				<?php endforeach; ?>
				<?php endif; ?>
			<?php endif; ?>
			</div> 
		</div>
		<div class="single-product-col">
			<div class="post-content"><?=$post->post_content?></div>
			<div class="actionWrap">
				<button class="addToCart" type="button" id="addToCart-<?=$post->ID?>" data-views="1" data-product_id="<?=$post->ID?>">Add To Cart</button>
				<div class="cart_info views"><span>Views - </span><input type="number" value="1" size="6" step="1" min="1" id="lms_test_views-<?=$post->ID?>" name="test_views"></div>
			</div>
		</div>		
	</div>
</div>
<?php 	get_footer(); ?>