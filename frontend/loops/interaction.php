<?php 	$course_data=unserialize(get_post_meta(get_the_ID(), 'lms_interaction_data', true)); ?>

	  	<!--MENU TABS START-->	  		  		
	  	<div id="progress_panel" class="">
	  		<h2 id="progress_title">Progress Bar</h2>
	  		<div id="progress_bar" class="progress">	
		  	<?php 
		  	$c=count($course_data)*2;
		  	foreach ($course_data as $key => $value) { ?>
		  	<?php if($steps_done>=$key){ ?>
		  		<div class="progress_part bar-step-<?=$key?>" style="width:<?php echo (100/$c)?>%; background: rgba(100, 255, 100, 0.5)"><strong><?=$value['title']?></strong></div>
		  		<div class="progress_part bar-step-<?=$key?>i" style="width:<?php echo (100/$c)?>%; background: rgba(100, 255, 100, 0.5)"><strong>Interaction</strong></div>
		  	<?php }else{ ?>
		  		<div class="progress_part bar-step-<?=$key?>" style="width:<?php echo (100/$c)?>%"><strong><?=$value['title']?></strong></div>
		  		<div class="progress_part bar-step-<?=$key?>i" style="width:<?php echo (100/$c)?>%"><strong>Interaction</strong></div>
		  	<?php }
		  	}
	  		?> 
	  		</div>
	  	</div>
	  	<div id="show_nav_ineractive">
	  		<div class="mobile-menu-button"></div>
	  	</div>
	  	<!--MENU TABS END-->


	  	<!-- interaction tabs -->

		<ul id="interaction_tabs">
	    <?php $m=0; ?>	    
	    <?php foreach ($course_data as $key => $value) { ?>
	    	<!-- foreach items steps -->
	    	<?php if($value['questions']!=NULL && $value['video']!=NULL){ $m++; ?>	    	    
	        <li   <?php echo (($m=='1' || $steps_done>=$m || $steps_done+1>=$m) ? 'id="step_menu_'.$m.'" class="parent  done step-'.$m.'"' : 'class="not-active  parent step-'.$m.'"')?>>
	          	<a href="#step_<?=$m?>" data-step="<?=$m?>" data-test="1"  <?php echo (($m=='1' || $steps_done>=$m || $steps_done+1>=$m) ? 'id="first_step" class="parent_link check"' : 'class="not-active no-check parent_link"')?>>
	            	<?=$value['title']?>
	          	</a>
	          	<ul <?php echo (($m=='1' || $steps_done>=$m || $steps_done+1>=$m) ? 'class="first_parts '.(($steps_done+1==$m)? '' : 'partsHidden').'"' : 'class="not-active partsHidden"')?> >
		            <?php $n=0; ?>	            
		            <?php foreach ($value['video'] as $k => $v) { $n++; ?>
		            <?php $pos = strrpos($v['url'], "."); ?>
		            <?php $ext = substr($v['url'], $pos+1); ?>	             
	              	<li <?php echo ((($m=='1' && $n==1) || ($steps_done>=$m)  || ($steps_done+1>=$m && $n==1)) ? '' : 'class="not-active "')?> >
	                	<a href="video-follow-<?=$n?>" data-step="<?=$m?>" <?php echo ((($m=='1' && $n==1) || ($steps_done>=$m)  || ($steps_done+1>=$m && $n==1)) ? 'class="check"' : 'class="no-check not-active"')?> >Part - <?=$n?></a>
	              	</li>
	           		<?php } ?>
	            	<li <?php echo (($steps_done>=$m)? 'class="done interaction_link"' : 'class="not-active interaction_link"')?> >
		          		<a href="#interaction_<?=$m?>" <?php echo (($steps_done>=$m)? 'class="interaction_link check"' : 'class="not-active interaction_link no-check"')?>>
		            		<span>Interaction</span>
		          		</a>
		        	</li>
	          	</ul>
	        </li>	        
	        <?php } ?>
	        <!-- foreach items steps -->	        
	    <?php } ?>

	    <!-- FINAL STEP -->
	    <?php $next=count($course_data)+1; ?>
	    	<li  <?php echo (($steps_done>=$m ) ? 'id="step_menu_'.$next.'" class="parent  done step-'.$next.'"' : 'class="not-active parent step-'.$next.'"')?> >
	      		<a href="#step_<?=$next?>" <?php echo (($steps_done>=$m ) ? 'id="first_step" class="parent_link check"' : 'class="not-active no-check  parent_link"')?>>Course Final</a>
	    	</li>	  
	  	</ul>
	  	<!-- FINAL STEP -->

	  <!--interaction tabs -->	



	  <?php 
	  	$letters=range("A", "Z", $step = 1 );  
        $numbers=range("0", "9", $step = 1 );
        $prepare=array_merge ($letters, $numbers);
        $letters_small=range("a", "z", $step = 1 );
        $array=array_merge ($letters_small, $prepare);
        global $wpdb;
	  	$m=0;
	  	foreach ($course_data as $key => $value) { 
	    	if($value['questions']!=NULL && $value['video']!=NULL){	    	
	    	$m++;    
	    ?>
	    <!-- LESSON BLOCK START --> 
	    <div id="step_<?=$m?>" class="testing_tab tab_lesson" <?php echo (($steps_done+1==$m)? "style='display:block'" : '')?>>
	      	<h2><?=$value['title']?></h2>
	      	<?php $i=0; ?>
	     	
	      	<?php foreach ($value['video'] as $k => $v) { ?>
	       		<?php $rand_keys = array_rand($array, 15);	?>       		
	            <?php $code=array(); ?>   
	            <?php foreach ($rand_keys as $s_number => $symbol) { ?>
	            	<?php $code[$v['url']][]=$array[$symbol]; ?>
	            <?php } ?>
	            <?php $arr=array(); ?>
	            <?php $arr[$v['url']]=implode($code[$v['url']]); ?>
	            <?php //$query=$wpdb->insert($wpdb->prefix.'timelinks', array('code' => $arr[$v['url']], 'origin_link' => $v['url'])); ?>
	            <?php $i++; ?>  
		        <?php $pos = strrpos($v['url'], "."); ?>
		        <?php $ext = substr($v['url'], $pos+1); ?>
	         	<?php // echo $arr[$v['url']].' --->  '.$v['url']; ?>
		        <center <?php echo (($i==1) ? 'class="video-follow-'.$i.' video-follow  v_part-'.$k.'"' : 'class="v_part-'.$k.' video-follow pretest_video video-follow-'.$i.'"')?>id="pre_video_block_<?php if($ext == 'flv'): ?>flv<?php else: echo $ext; endif; ?><?=$i?>">
		          	<video id="pre_video_<?=$i?>" class=" video-js vjs-default-skin" controls preload="auto" width="720" height="405" data-setup='{"controls": true, "autoplay": false, "preload": "false"}'>
		            	<source src="<?=$v['url']?>" type="video/<?php echo (($ext == 'm4v') ? 'mp4' :  $ext ) ?>">
		            	<p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p>
		          	</video>
		          	<div style="margin-top:40px;"><?=((isset($v['description']))? $v['description'] : '' )?></div>		                   
		        </center>
	        
	        <script type="text/javascript">
	        	jQuery('.vjs-progress-control').hide();
	          		setInterval(function(){ 
	            		jQuery(document).ready(function($){
			              	$("#step_<?=$m?> #pre_video_<?=$i?>_html5_api").on('ended',function(){               
			                	var videoview<?=$i?>='true';
			                	$('.step-<?=$m?> [href="video-follow-<?=$i+1?>"]').parent().removeClass('not-active');  
			                	$('.step-<?=$m?> [href="video-follow-<?=$i+1?>"]').removeClass('not-active'); 
			                	$('.step-<?=$m?> [href="video-follow-<?=$i+1?>"]').removeClass('no-check');  
			                	$('.step-<?=$m?> [href="video-follow-<?=$i+1?>"]').addClass('check');     
			                	jQuery(".video-follow-<?=$i?>").hide(); 	                
			                	jQuery(".video-follow-<?=$i+1?>").show();	                 
			                	jQuery('#step_<?=$m?> .video-follow-<?=$i+1?> .vjs-big-play-button').click();
			              	});
			              	$("#step_<?=$m?> #pre_video_<?=$i?>").on('ended',function(){
			                	var videoview<?=$i?>='true';
				                $('.step-<?=$m?> [href="video-follow-<?=$i+1?>"]').parent().removeClass('not-active');  
				                $('.step-<?=$m?> [href="video-follow-<?=$i+1?>"]').removeClass('not-active');  
				                $('.step-<?=$m?> [href="video-follow-<?=$i+1?>"]').removeClass('no-check');  
				                $('.step-<?=$m?> [href="video-follow-<?=$i+1?>"]').addClass('check');  
				                jQuery(".video-follow-<?=$i?>").hide(); 
				                jQuery(".video-follow-<?=$i+1?>").show();
				            }); 
			              	$("#step_<?=$m?> #pre_video_<?=count($value['video'])?>_html5_api").on('ended',function(){ 
				                $('.step-<?=$m?> ul').find(".interaction_link").removeClass('not-active');
				                $('.step-<?=$m?> ul').find(".interaction_link").removeClass('not-active');	              
				                // $('.step-<?=$m?>').find("ul").addClass("partsHidden");
				                $(".bar-step-<?=$m?>").css("background", "rgba(100, 255, 100, 0.5)");
				                $('.tab_lesson').hide();
				                $('#interaction_<?=$m?>').show();
			                }); 
			                $("#step_<?=$m?> #pre_video_<?=count($value['video'])?>").on('ended',function(){ 
				                $('.step-<?=$m?> ul').find(".interaction_link").removeClass('not-active');
				                $('.step-<?=$m?> ul').find(".interaction_link").removeClass('not-active');	               
				                $(".bar-step-<?=$m?>").css("background", "rgba(100, 255, 100, 0.5)"); 
				                // $('.step-<?=$m?>').find("ul").addClass("partsHidden");
				                $('.tab_lesson').hide();
				                $('#interaction_<?=$m?>').show();
			                });      
	            		});
	          		},1000);
	          		setTimeout(function(){
			          	// jQuery(".video-follow-<?=$i?>").find("source").attr('src', "<?=home_url()?>/videos/?get=<?=$arr[$v['url']]?>");
			        }, 500) 
	        </script>	        
	        <?php } ?>
	    </div> 
	    <!-- LESSON BLOCK END -->
	    <!-- INTEACTION BLOCK START -->
	    <div id="interaction_<?=$m?>" class="testing_tab tab_check">
	      	<h2 style="width:100%;"><?=$value['title']?></h2>
	      	<?php if($steps_done>=$m){ ?>
	      		<?php $next=$m + 1; ?>
	        	<h1 class="passed_message">You have already passed this test!</h1>
	        	<h4 class="passed_message">
	        		<span>Please proceed to the </span>
	        			<a href="#step_<?=$next?>" data-step="<?=$next?>" class="processed_next">Next Step</a>
	        		 	<span>or</span> 
	        		 	<a href="#" data-step="<?=$m?>" class="show-form">Retake This Step</a>
	        	</h4>	        
	        	<form id="step_check_<?=$m?>" style="display:none;">
	        	<?php $next=$m + 1; ?>
	       		<?php foreach ($value['questions'] as $task_num => $task) { ?>
	        		<section class="quest-part quest-<?=$task_num?>">
	        			<hr>
	          			<h2 style="width:100%; float: left;"><?=$task['quest_title']?></h2>
	          			<ul class="answers" id="answers-<?=$task_num?>">
	            		<?php foreach ($task['answers'] as $ans_num => $answer) { ?>
	            			<?php if($task['quest_type']!="image"){ ?>
	               				<li>
	                				<input type="radio" name="answer-<?=$task_num?>" class="answer-check" id="answer-<?=$ans_num?>" value="<?=$answer?>">
	                				<span><?=$answer?></span>
	               				</li>
	            			<?php }else{ ?>
	            				<li class="image_answer_front">
									<div class="img-answer-cover">
										<div class="img-answer-front" style="background: url('<?=$answer["url"]?>');  background-repeat: no-repeat; background-size: contain; background-position: center;"></div>
									</div>			   												
									<div class="radio_answer">
										<input type="radio" name="answer-<?=$task_num?>" class="answer-check" id="answer-<?=$ans_num?>" value="<?=substr($answer, strripos($answer, '/')+1)?>">
									</div>						
								</li>
	           				<?php } ?>
	           			<?php } ?> 
	          			</ul>
	           			<p><?=((isset($task['quest_description']))? $task['quest_description'] : '')?></p>  
	            	</section>
	          	<?php } ?> 
		        	<div class="submit_answers">
		        		<button type="button" class="button submit_step" data-step="<?=$m?>">Submit Answers</button> 	
		        	</div>
	      		</form>
	        	<?php }else{ ?>
	      
	      		<form id="step_check_<?=$m?>">
			        <?php $next=$m + 1; ?>
			        <?php foreach ($value['questions'] as $task_num => $task) { ?>
	        			<section class="quest-part quest-<?=$task_num?>">
	        				<hr>
	          				<h2 style="width:100%; float: left;"><?=$task['quest_title']?></h2>
	          				<ul class="answers" id="answers-<?=$task_num?>">
	            				<?php foreach ($task['answers'] as $ans_num => $answer) { ?>
	            					<?php if($task['quest_type']!="image"){ ?>	               
			               				<li>
			                				<input type="radio" name="answer-<?=$task_num?>" class="answer-check" id="answer-<?=$ans_num?>" value="<?=$answer?>">
			                				<span><?=$answer?></span>
			               				</li>
	            					<?php }else{ ?>
	            						<li class="image_answer_front">
											<div class="img-answer-cover">
												<div class="img-answer-front" style="background: url('<?=$answer["url"]?>');  background-repeat: no-repeat; background-size: contain; background-position: center;"></div>
											</div>			   												
											<div class="radio_answer">
												<input type="radio" name="answer-<?=$task_num?>" class="answer-check" id="answer-<?=$ans_num?>" value="<?=substr($answer, strripos($answer, '/')+1)?>">
											</div>						
										</li>
	           						<?php } ?>
	          					<?php } ?> 
	          				</ul>
	           				<p><?=((isset($task['quest_description']))? $task['quest_description'] : '')?></p> 
	        			</section> 
	          		<?php } ?>	        
			        <div class="submit_answers">
			        	<button type="button" class="button submit_step" data-step="<?=$m?>">Submit Answers</button> 	
			        </div>
	      		</form>
		      	<div class="test_passed test_passed_<?=$m?>">
		      		<h1>Congratulations, you passed!</h1>
		      		<h4><span>Please proceed to the</span>
		      			<a class="processed_next" data-step="<?=$next?>" href="#step_<?=$next?>">Next Step</a>
		      				<span>or</span>
		      			<a href="#" data-step="<?=$m?>" class="show-form">Retake This Step</a>
		      		</h4>
		      	</div>
		      	<div class="test_fail">
		      		<div class="step_result_<?=$m?>">
			      		<h1>Aw, you failed!</h1>
			      		<h3>You have 
			      			<b class="error_count_<?=$m?>"></b>
			      			Please
			      			<a class="try_again" data-step="<?=$m?>" href="#step_check_<?=$m?>">Try again</a>
			      			or <a href="#" class="review_train review_train_<?=$m?>" data-step="<?=$m?>" >Review Training</a>
			      			or <a href="#" class="retake_questions retake_questions_<?=$m?>">Retake Question</a>
			      		</h3>
			      	</div>
	      		</div>	      
	      <?php } ?>
	    </div>
	    <!-- INTEACTION BLOCK END -->
	<?php } ?>
<?php } ?>
<div id="step_<?=$next?>" class="testing_tab tab_lesson test_passed" style="display:<?php echo (($steps_done==$m)? 'block' : 'none' )?>">	
	<h1>You have passed this level!</h1>
	<h4><span>Please proceed to the</span> 
		<a class="complete_test" href="<?=the_permalink()?>?part=<?=$GLOBALS['tests']->get_part_after_interaction(get_the_ID())?>">
			<span>Complete Test</span>
		</a>
		<span>or</span> 
		<a class="review_test" data-test="<?=get_the_ID()?>" data-user="<?=get_current_user_id()?>" href="#">Retake Interaction</a>
	</h4>
</div>
<input type="hidden" value="<?=get_the_ID()?>" id="course_id">
