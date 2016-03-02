<?php get_header(); ?>
<?php $user=$GLOBALS['users']->user?>
<div class='lms'>
	<div class='links-bar'>			
		<?php require_once(TPL_DIR."loops/linksbar.php");?>
	</div>	
	<!-- TEST  AVAILABLE -->
		<?php //$tests=$GLOBALS['tests']->get_available_tests(); ?>
		<ul class='test-list'>
	<?php foreach ($GLOBALS['tests']->get_available_tests() as $group => $data): ?>		
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
					<a href='<?php echo get_the_permalink($test->ID); ?>?part=<?=$GLOBALS['tests']->get_part($test->ID)?>&group=<?=$group?>'>
						<?php echo $test->post_title; ?>
					</a>
				</h4>

				<?php if($GLOBALS['reports']->has_result($test->ID, $group) && $GLOBALS['reports']->the_test_score($test->ID, $group)!=0) { ?>

					<h4>Your score: <?php echo $GLOBALS['reports']->the_test_score($test->ID, $group); ?>/<?=$pass?> </h4>	

				<?php }else{ ?>

					<h4  >Pass Score: <?=$pass?></h4>

				<?php } ?>
				<?php if($GLOBALS['reports']->has_passed($test->ID, $group)) { ?>

					<h4><a href="<?php echo get_the_permalink($test->ID); ?>/?part=certificate">View Certificate</a></h4>

				<?php }elseif($GLOBALS['reports']->has_result($test->ID, $group) && $GLOBALS['reports']->the_test_score($test->ID, $group)!=0){ ?>

					<h4 class="retakeTest" >
						<a href='<?php echo get_the_permalink($test->ID); ?>/?part=<?=$GLOBALS['tests']->get_part_questions($test->ID)?>&group=<?=$group?>'>Retake Test</a>
					</h4>
					<h4 id="retakeCourse" >
						<a href='<?php echo get_the_permalink($test->ID); ?>?part=<?=$GLOBALS['tests']->get_part($test->ID)?>&group=<?=$group?>'>Retake Course</a>
					</h4>

				<?php }else{ ?>

					<h4>
						<a href='<?php echo get_the_permalink($test->ID); ?>?part=<?=$GLOBALS['tests']->get_part($test->ID)?>&group=<?=$group?>'>Start Test</a>
					</h4>

				<?php } ?>
				<?php if(!$price || $price<0){ $prices="Free"; }else{ $prices="$".$price; } ?>	

				<h4>Price: <?=$prices?></h4>
			</div>
			<!-- test links && info -->


			<!-- content -->
			<div class="contentTest">
				<div class="test_thumb_wrap test-list-col" onclick="javascript:window.location='<?php echo get_the_permalink($test->ID); ?>?part=<?=$GLOBALS['tests']->get_part($test->ID)?>&group=<?=$group?>'">
				<?php if(!empty($img)){?>

					<img src="<?=home_url()?>/wp-content/uploads/<?=$img?>">

				<?php }else{?>

					<img src="<?=home_url()?>/wp-content/plugins/LMSinstruct/assets/images/no-image.jpg">	

				<?php } ?>
				</div>
				
				<div class="test_content_wrap test-list-col" onclick="javascript:window.location='<?php echo get_the_permalink($test->ID); ?>?part=<?=$GLOBALS['tests']->get_part($test->ID)?>&group=<?=$group?>'">
					<?php $content = $post->post_content; ?>
					<?php if($content && strlen($content)>250)	print substr(strip_tags($content), 0, 250).'...'; else print $content; ?>			
				</div>
				<div class="test_resours_wrap test-list-col">
					<h3>Test Resourses:</h3>
					<ul>					
					<?php $lms_resources = get_post_meta($test->ID, 'lms_attach_media', true);
					if(is_array($lms_resources)) {						
						foreach($lms_resources as $res) {
							if(!current_user_can('edit_page')){
								if($res['pos'] == 'before' && $res['perms'] == 'everyone' ) {
									$start=strripos($res['file'], '/');
									$name=substr($res['file'],$start+1);							
									echo "<li><a href='{$res['url']}'>{$name}</a></li>";				
								}
							}else{
								$start=strripos($res['file'], '/');
								$name=substr($res['file'],$start+1);							
								echo "<li><a href='{$res['url']}'>{$name}</a></li>";	
							}
						}
					}?>
					</ul>
				</div>
			</div>
			<!-- content -->


		</li>
	<?php endforeach;?>
	<?php endforeach;?>
	</ul>
		<div class="pagenation_wrap"><?$GLOBALS['tests']->test_pagination();?></div>

	<!-- TEST  AVAILABLE -->
</div> 
<?php get_footer(); ?>