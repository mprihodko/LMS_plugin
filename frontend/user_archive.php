<?php get_header(); ?>
<?php $user=$GLOBALS['users']->user?>
<?php $var = get_query_var('test_category');?> 
<?php $page = (isset($_GET['list']))?$_GET['list']: 0;?> 
<?php //global $wp_rewrite; ?>
<?php //var_dump($wp_rewrite); die; ?>
<div class='lms'>
	<div class='links-bar'>			
		<?php require_once(TPL_DIR."loops/linksbar.php");?>
	</div>
	<div class="shop_sort" style="text-align: left;">
		<a href="<?=home_url('examination/')?>" <?= ($var=='')? 'class="active"' : '' ?>>All</a>
	<?php foreach($GLOBALS['tests']->lms_get_terms() as $key => $value): ?>
		<a href="<?=home_url('examination/'.$value->slug)?>" <?= ($var==$value->slug)? 'class="active"' : '' ?>><?=$value->name?></a>
	<?php endforeach; ?>		
	</div>	
	<!-- TEST  AVAILABLE -->
		<?php //$tests=$GLOBALS['tests']->get_available_tests(); ?>
		<ul class='test-list'>

		<?php $available_tests=$GLOBALS['tests']->all_tests_page_data($var, $page); ?>
		<?php if(is_array($available_tests)) : ?>
			<?php foreach ($available_tests as $group => $data): ?>		
				<?php if(isset($data['tests']) && is_array($data['tests'])) : ?>
					<h1 class="group_title_archive"><?=$data['group'][0]->name?></h1>
					<?php foreach ($data['tests'] as $test): ?>
					<?php 	$meta_img = get_post_meta($test->ID, "uploader_custom_thumbnail", true);
						  	$img = get_post_meta($meta_img, "_wp_attached_file", true);
						  	$price = get_post_meta($test->ID, "test_price", true);
							$pass = get_post_meta($test->ID, "test_pass", true);
					?>
					<li class='test'>		
						<!-- test links && info -->
						<div class="menu-links">
							<h4>
								<a href='<?php echo get_the_permalink($test->ID); ?>?part=<?=$GLOBALS['tests']->get_part($test->ID)?>&group=<?=$data['group'][0]->group_id?>'>
									<?php echo $test->post_title; ?>
								</a>
							</h4>

							<?php if($GLOBALS['reports']->has_result($test->ID, $data['group'][0]->group_id) && $GLOBALS['reports']->the_test_score($test->ID, $data['group'][0]->group_id, $user->ID)!=0) { ?>

								<h4>Your score: <?php echo $GLOBALS['reports']->the_test_score($test->ID, $data['group'][0]->group_id, $user->ID); ?>/<?=$pass?> </h4>	

							<?php }else{ ?>

								<h4  >Pass Score: <?=$pass?></h4>

							<?php } ?>						
							<?php if($GLOBALS['reports']->has_passed($test->ID, $data['group'][0]->group_id, $user->ID)) { ?>

								<h4><a href="<?php echo get_the_permalink($test->ID); ?>/?part=certificate">View Certificate</a></h4>

							<?php }elseif($GLOBALS['reports']->has_result($test->ID, $data['group'][0]->group_id) && $GLOBALS['reports']->the_test_score($test->ID, $data['group'][0]->group_id, $user->ID)!=0){ ?>

								<h4 class="retakeTest" >
									<a href='<?php echo get_the_permalink($test->ID); ?>/?part=<?=$GLOBALS['tests']->get_part_questions($test->ID)?>&group=<?=$data['group'][0]->group_id?>'>Retake Test</a>
								</h4>
								<h4 id="retakeCourse" >
									<a href='<?php echo get_the_permalink($test->ID); ?>?part=<?=$GLOBALS['tests']->get_part($test->ID)?>&group=<?=$data['group'][0]->group_id?>'>Retake Course</a>
								</h4>

							<?php }else{ ?>

								<h4>
									<a href='<?php echo get_the_permalink($test->ID); ?>?part=<?=$GLOBALS['tests']->get_part($test->ID)?>&group=<?=$data['group'][0]->group_id?>'>Start Test</a>
								</h4>

							<?php } ?>
							<?php if(!$price || $price<0){ $prices="Free"; }else{ $prices="$".$price; } ?>	

							<h4>Price: <?=$prices?></h4>
						</div>
						<!-- test links && info -->


						<!-- content -->
						<div class="contentTest">
							<div class="test_thumb_wrap test-list-col" onclick="javascript:window.location='<?php echo get_the_permalink($test->ID); ?>?part=<?=$GLOBALS['tests']->get_part($test->ID)?>&group=<?=$data['group'][0]->group_id?>'">
							<?php if(!empty($img)){?>

								<img src="<?=home_url()?>/wp-content/uploads/<?=$img?>">

							<?php }else{?>

								<img src="<?=home_url()?>/wp-content/plugins/LMSinstruct/assets/images/no-image.jpg">	

							<?php } ?>
							</div>
							
							<div class="test_content_wrap test-list-col" onclick="javascript:window.location='<?php echo get_the_permalink($test->ID); ?>?part=<?=$GLOBALS['tests']->get_part($test->ID)?>&group=<?=$data['group'][0]->group_id?>'">
								<?php $content = $test->post_content; ?>
								<?php if($content && strlen($content)>250)	print  mb_substr(strip_tags($content), 0, 250, "UTF8").'...'; else print $content; ?>			
							</div>
							<!-- Resourses -->
							<!-- <div class="test_resours_wrap test-list-col">
								<h3>Test Resourses:</h3>
								<ul>		 -->			
								<?php 
								// $lms_resources = get_post_meta($test->ID, 'lms_attach_media', true);
								// if(is_array($lms_resources)) {						
								// 	foreach($lms_resources as $res) {
								// 		if(!current_user_can('edit_page')){
								// 			if($res['pos'] == 'before' && $res['perms'] == 'everyone' ) {
								// 				$start=strripos($res['file'], '/');
								// 				$name=substr($res['file'],$start+1);							
								// 				echo "<li><a href='{$res['url']}'>{$name}</a></li>";				
								// 			}
								// 		}else{
								// 			$start=strripos($res['file'], '/');
								// 			$name=substr($res['file'],$start+1);							
								// 			echo "<li><a href='{$res['url']}'>{$name}</a></li>";	
								// 		}
								// 	}
								// }
								?>
							<!-- 	</ul>
							</div> -->
							<!-- Resourses -->
						</div>
						<!-- content -->


					</li>
				<?php endforeach;?>
			<?php endif; ?>
		<?php endforeach;?>
	<?php endif; ?>
	</ul>
		<div class="tablegroups-pages"><span class="pagination-links"><?=$GLOBALS['tests']->the_pagination_all_test($var, $page);?></span></div>

	<!-- TEST  AVAILABLE -->
</div> 
<?php get_footer(); ?>