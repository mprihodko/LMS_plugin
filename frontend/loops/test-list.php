<?php 	
	if(isset($_GET['group_name'])): 
		$_COOKIE['current_group']=$_GET['group_name'];
		$group = $_COOKIE['current_group'];
	elseif(current_user_can('administrator')):
		$_COOKIE['current_group']="administrator";
		$group = $_COOKIE['current_group'];
	endif; 
?>	
<ul class='test-list'>
	<?php while(have_posts()): the_post(); ?>
		<?php 	$meta_img = get_post_meta(get_the_ID(), "uploader_custom_thumbnail", true);
			  	$img = get_post_meta($meta_img, "_wp_attached_file", true);
			  	$price = get_post_meta(get_the_ID(), "test_price", true);
				$pass = get_post_meta(get_the_ID(), "test_pass", true);
		?>
		<li class='test'>		
			<!-- test links && info -->
			<div class="menu-links">
				<h4><a href='<?php the_permalink(); ?>?part=<?=$GLOBALS['tests']->get_part(get_the_ID())?>&group=<?=$group?>'><?php the_title(); ?></a></h4>

				<?php if($GLOBALS['reports']->has_result(get_the_ID(), $group) && $GLOBALS['reports']->the_test_score(get_the_ID(), $group)!=0) { ?>

					<h4>Your score: <?php echo $GLOBALS['reports']->the_test_score(get_the_ID(), $group); ?>/<?=$pass?> </h4>	

				<?php }else{ ?>

					<h4  >Pass Score: <?=$pass?></h4>

				<?php } ?>
				<?php if($GLOBALS['reports']->has_passed(get_the_ID(), $group)) { ?>

					<h4><a href="<?php the_permalink(); ?>/?part=certificate">View Certificate</a></h4>

				<?php }elseif($GLOBALS['reports']->has_result(get_the_ID(), $group) && $GLOBALS['reports']->the_test_score(get_the_ID(), $group)!=0){ ?>

					<h4 class="retakeTest" >
						<a href='<?php the_permalink(); ?>/?part=<?=$GLOBALS['tests']->get_part_questions(get_the_ID())?>&group=<?=$group?>'>Retake Test</a>
					</h4>
					<h4 id="retakeCourse" >
						<a href='<?php the_permalink(); ?>?part=<?=$GLOBALS['tests']->get_part(get_the_ID())?>&group=<?=$group?>'>Retake Course</a>
					</h4>

				<?php }else{ ?>

					<h4>
						<a href='<?php the_permalink(); ?>?part=<?=$GLOBALS['tests']->get_part(get_the_ID())?>&group=<?=$group?>'>Start Test</a>
					</h4>

				<?php } ?>
				<?php if(!$price || $price<0){ $prices="Free"; }else{ $prices="$".$price; } ?>	

				<h4>Price: <?=$prices?></h4>
			</div>
			<!-- test links && info -->


			<!-- content -->
			<div class="contentTest">
				<div class="test_thumb_wrap test-list-col" onclick="javascript:window.location='<?php the_permalink(); ?>?part=<?=$GLOBALS['tests']->get_part(get_the_ID())?>&group=<?=$group?>'">
				<?php if(!empty($img)){?>

					<img src="<?=home_url()?>/wp-content/uploads/<?=$img?>">

				<?php }else{?>

					<img src="<?=home_url()?>/wp-content/plugins/LMSinstruct/assets/images/no-image.jpg">	

				<?php } ?>
				</div>
				
				<div class="test_content_wrap test-list-col" onclick="javascript:window.location='<?php the_permalink(); ?>?part=<?=$GLOBALS['tests']->get_part(get_the_ID())?>&group=<?=$group?>'">
					<?php $content = get_the_content('Read More..'); ?>
					<?php if($content && strlen($content)>250)	print substr(strip_tags($content), 0, 250).'...'; else print $content; ?>			
				</div>
				<div class="test_resours_wrap test-list-col">
					<h3>Test Resourses:</h3>
					<ul>					
					<?php $lms_resources = get_post_meta(get_the_ID(), 'lms_attach_media', true);
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
	<?php endwhile;?>
	</ul>