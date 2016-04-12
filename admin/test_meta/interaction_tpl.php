<div class="enable_disable">
	<div data-type="on" class="butt on <?=((isset($interaction_status) && $interaction_status=="on")? 'active' : '')?>">ON</div>
	<div data-type="off" class="butt off <?=((isset($interaction_status) && $interaction_status!="on")? 'active' : '')?>">OFF</div>
	<div class="short_desc">- Use Interactive Course</div>	
</div>
<div class="init_interaction">
	<input type="checkbox" name="use_steps" value="on" <?php echo ((isset($interaction_status) && $interaction_status=="on")? 'checked' : '') ?>>
</div>
<div class="course_steps">
	<!-- start exists -->
	<?php if(isset($interaction_data) && is_array($interaction_data)) { ?>
	
	<?php foreach ($interaction_data as $step_num => $data) : ?>
		<!-- step content -->
		<div class="step-content">
			<span class="step_numb" data-step="<?=$step_num?>">STEP <?=$step_num?> <small><?=(($data['title']!="")?"(".$data['title'].")" : '' )?></small><i class="show" data-step='<?=$step_num?>'></i><i class="delete" data-step='<?=$step_num?>'></i></span>
			<div class="step step-<?=$step_num?>" style="display:none;">

				<!-- STEP TITLE -->
				<div class="step_title">
					<label >STEP TITLE: </label>
					<input type="text" name="step_title[<?=$step_num?>]" value="<?=$data['title']?>">
				</div>	
				<!-- STEP TITLE -->

				<!-- VIDEO		 -->
				<span class="video_section">VIDEO</span>
				<div class="video_data_wrapper_<?=$step_num?>">

				<!-- FOREACH VIDEO -->
				<?php if(isset($data['video'])): ?>
				<?php foreach($data['video'] as $video_num => $video) : ?>
				<!-- videos -->
					<div class="video_setting_up"> 
						<h3 class="video_part_title">Video Part <i class="delete"></i><i class="show"></i></h3>
						<div class="video-box video-box-<?=$video_num?>" style="display:none;">							
							<div class="inputs_data">
								<div class="tb_td">
					 				<h2>Video File Path: </h2><a href="<?=$video['url']?>"><?=$video['file']?></a>				
					 				<input type="hidden" name="lms_course_video_file[<?=$step_num?>][<?=$video_num?>]" value="<?=$video['file']?>">
					 				<input type="hidden" name="lms_course_video_url[<?=$step_num?>][<?=$video_num?>]" value="<?=$video['url']?>">
					 			</div>	
					 			<div class="tb_td">
					 				<input type="checkbox" class="lms_attach_video_none" name="lms_course_video_display[<?=$step_num?>][<?=$video_num?>]" value="none" <?=(($video['none']=="true")? 'checked' : '')?> />	 				
				 					<span> - Hide </span>
			 					</div>
			 				</div>
								<?php 	$editor_id='video_descr-'.$step_num.'-'.$video_num;	
										if(isset($video['description'])) $description=	$video['description']; else $description='';					
									  	wp_editor($description, $editor_id, array(
																	'wpautop' => 1,
																	'media_buttons' => 1,
																	'textarea_name' => 'video_descr['.$step_num.']['.$video_num.']', 
																	'textarea_rows' => 5,
																	'tabindex'      => null,
																	'editor_css'    => '',
																	'editor_class'  => 'test_create_desc',
																	'teeny'         => 0,
																	'dfw'           => 0,
																	'tinymce'       => 1,
																	'quicktags'     => 1,
																	'drag_drop_upload' => true
																) 
															);
								?>

							<hr>					
						</div>

						<!-- ADD NEW BUTTON  -->
						<?php if(count($data['video'])==$video_num): ?>
							<div class="add_video_button_wrapper">
								<a class="AddStepVideo" data-step="<?=$step_num?>" data-video="<?=$video_num?>" href="1">Add Video</a>
							</div>
						<?php endif; ?>	
						<!-- ADD NEW BUTTON  -->

					</div>	
				<!-- videos -->
				<?php endforeach; ?>
				<?php else: ?>
					<div class="add_video_button_wrapper">
						<a class="AddStepVideo" data-step="<?=$step_num?>" data-video="1" href="1">Add Video</a>
					</div>	
				<?php endif; ?>	
				<!-- ENDFOREACH VIDEO -->

				</div>
				<!-- VIDEO		 -->	


				<!-- INTERACTION -->
				<span class="video_section">INTERACTION</span>
				<div class="interaction-box">
					<div class="quest_setting_up_space">
						<ul class="questContent questContent-<?=$step_num?>">

							<!-- FOREACH QUESTS  -->
							<?php if(isset($data['questions'])): ?>
							<?php foreach($data['questions'] as $quest_num => $quest_data ) : ?>
								<li data-questnumber="<?=$quest_num?>">
									<h3>Quest Block 
										<small>
										<?php if($quest_data['quest_type']=="true_false"):?>
											(True/False)
										<?php elseif($quest_data['quest_type']=="multiple"):?>
											(Multiple)
										<?php elseif($quest_data['quest_type']=="image"):?>
											(Graphic)
										<?php endif; ?>
										</small>
										<i class="delete"></i><i class="show"></i></h3>
									<div class="quest_content" style="display:none;">
										<label>Title: </label>
										<input class="quest_title_field" type="text" name="int_quest[<?=$step_num?>][<?=$quest_num?>]" value="<?=$quest_data['quest_title']?>">
										<input type="hidden" name="type_of_quest[<?=$step_num?>][<?=$quest_num?>]" value="<?=$quest_data['quest_type']?>" required>
										<h5>Answers</h5>
										<ul class="step-<?=$step_num?>-quest-<?=$quest_num?>-answers">

											<!-- FOREACH ANSWERS -->
											<!-- ANSWERS TRUE/FALSE -->
											<?php if($quest_data['quest_type']=="true_false"):?>
												<?php if(isset($quest_data['answers'])): ?>
												<?php foreach ($quest_data['answers'] as $answer_num => $answer): ?>												
													<li>
														<input type="checkbox" class="select_true" value="" <?=(($answer==$quest_data['true'])? 'checked' : '')?>>
														<input type="hidden" class="answer_hidden" name="int_true[<?=$step_num?>][<?=$quest_num?>][]" value="<?=(($answer==$quest_data['true'])? 'true' : 'false')?>" required>
														<input type="text" name="int_answer[<?=$step_num?>][<?=$quest_num?>][]" value="<?=$answer?>" required>
													</li>
												<?php endforeach; ?>
												<?php endif; ?>	

											<!-- ANSWERS MULTIPLE -->
											<?php elseif($quest_data['quest_type']=="multiple"):?>
												<?php if(isset($quest_data['answers'])): ?>
												<?php foreach ($quest_data['answers'] as $answer_num => $answer): ?>												
													<li>
														<input type="checkbox" class="select_true" value="" <?=(($answer==$quest_data['true'])? 'checked' : '')?>>
														<input type="hidden" class="answer_hidden" name="int_true[<?=$step_num?>][<?=$quest_num?>][]" value="<?=(($answer==$quest_data['true'])? 'true' : 'false')?>" required>
														<input type="text" name="int_answer[<?=$step_num?>][<?=$quest_num?>][]" value="<?=$answer?>" required>
														<?php if($answer_num==count($quest_data['answers'])):?>
															<a data-answer="<?=$answer_num?>" data-questnum="<?=$quest_num?>" data-step="<?=$step_num?>" class="addAnswer"><i class="fa fa-plus-circle"></i></a>
														<?php endif; ?>
													</li>
												<?php endforeach; ?>
												<?php endif; ?>	

											<!-- ANSWERS IMAGES -->		
											<?php elseif($quest_data['quest_type']=="image"):?>
												<?php if(isset($quest_data['answers'])): ?>

												<?php foreach ($quest_data['answers'] as $answer_num => $answer): ?>												
													<li>
														<input required type="radio" name="int_true_select[<?=$step_num?>][<?=$quest_num?>][]" class="select_true" style="position: relative; top: -45px;" value="" <?=(($answer==$quest_data['true'])? 'checked' : '')?>>
														<input type="hidden" class="answer_hidden" name="int_true[<?=$step_num?>][<?=$quest_num?>][]" value="<?=(($answer==$quest_data['true'])? 'true' : 'false')?>" required>
														<img height="100" src="<?=$answer?>" alt="answer-<?=$answer_num?>">
														<input type="hidden" name="image_a_url[<?=$step_num?>][<?=$quest_num?>][]" value="<?=$answer?>">
														<a data-answer="<?=$answer_num?>" data-questnum="<?=$quest_num?>" data-step="<?=$step_num?>" class="delAnswer actionImage"><i class="fa fa-minus-circle"></i></a>
														<?php if($answer_num==count($quest_data['answers'])):?>
															<a data-answer="<?=$answer_num?>" data-questnum="<?=$quest_num?>" data-step="<?=$step_num?>" class="addAnswerImg actionImage"><i class="fa fa-plus-circle"></i></a>
														<?php endif; ?>

													</li>
												<?php endforeach; ?>
												<?php endif; ?>	
											<?php endif; ?>	
											<!-- ENDFOREACH ANSWERS -->	

										</ul>

										<!-- QUEST DESCRIPTION -->

										<?php 	$editor_id='step-'.$step_num.'-quest-'.$quest_num;	
										if(isset($quest_data['quest_description'])) $description=	$quest_data['quest_description']; else $description='';							
									  	wp_editor($description, $editor_id, array(
																	'wpautop' => 1,
																	'media_buttons' => 1,
																	'textarea_name' => 'quest_desc['.$step_num.']['.$quest_num.']', 
																	'textarea_rows' => 5,
																	'tabindex'      => null,
																	'editor_css'    => '',
																	'editor_class'  => 'test_create_desc',
																	'teeny'         => 0,
																	'dfw'           => 0,
																	'tinymce'       => 1,
																	'quicktags'     => 1,
																	'drag_drop_upload' => true
																) 
															);
										?>
										<!-- QUEST DESCRIPTION -->
								</li>
							<?php endforeach; ?>
							<?php endif;?>
							<!-- ENDFOREACH QUESTS  -->
						</ul>


						<div class="add_quest_button_wrap">
							<a href="#" data-step="<?=$step_num?>" data-questnum="<?=$quest_num?>" class="add_q AddQuest">Add Quest (True/False)</a>
							<a href="#" data-step="<?=$step_num?>" data-questnum="<?=$quest_num?>" class="add_q AddMultiple">Add Multiple Quest</a>
							<a href="#" data-step="<?=$step_num?>" data-questnum="<?=$quest_num?>" class="add_q AddImageQuest">Add Quest (Image answers)</a>
						</div>
					</div>
				</div>
				<!-- INTERACTION -->
			</div>

			<?php if($step_num==count($interaction_data)){?>
				<div class="add_step_button_wrapper">
					<a href="#" class="addStep" data-step="<?=count($interaction_data)?>">Add Step</a>
				</div>
			<?php } ?>

		</div>
		<!-- step content -->
	<?php endforeach; ?>



	<!-- end exists -->
	<?php }else{ ?>
	<!-- step content -->
	<div class="step-content">
		<span class="step_numb" data-step="1">STEP 1 <i class="hide" data-step='1'></i></span>			
		<div class="step step-1">
			
			<!-- STEP TITLE -->
			<div class="step_title">
				<label >STEP TITLE: </label>
				<input type="text" name="step_title[1]">
			</div>	
			<!-- STEP TITLE -->

			<!-- VIDEO		 -->
			<span class="video_section">VIDEO</span>			
			<div class="video_data_wrapper_1">
				<div class="video_setting_up"> 
					<h3 class="video_part_title">Video Part <i class="hide"></i></h3> 
					<div class="video-box video-box-1">
						<div class="select_type_video">
							<div data-type="url" class="url">URL</div>
							<div data-type="file" class="file active">FILE</div>
						</div>
						<div class="inputs_data">
							<div class="tb_td">
				 				<input type="file" class="lms_attach_video"  name="lms_course_builder_video[1][1]" size="25" />  				
				 			</div>	
				 			<div class="tb_td">
				 				<input type="checkbox" class="lms_attach_video_none" name="lms_course_video_display[1][1]" value="none" />	 				
			 					<span> - Hide </span>
		 					</div>
		 				</div>
							<?php 	$editor_id='video_descr-1-1';							
								  	wp_editor('', $editor_id, array(
																'wpautop' => 1,
																'media_buttons' => 1,
																'textarea_name' => 'video_descr[1][1]', 
																'textarea_rows' => 5,
																'tabindex'      => null,
																'editor_css'    => '',
																'editor_class'  => 'test_create_desc',
																'teeny'         => 0,
																'dfw'           => 0,
																'tinymce'       => 1,
																'quicktags'     => 1,
																'drag_drop_upload' => true
															) 
														);
							?>

						<hr>					
					</div>
					<div class="add_video_button_wrapper">
						<a class="AddStepVideo" data-step="1" data-video="1" href="1">Add Video</a>
					</div>	
				</div>	
			</div>
			<!-- VIDEO		 -->

			<!-- INTERACTION -->
			<span class="video_section">INTERACTION</span>
			<div class="interaction-box">
				<div class="quest_setting_up_space">
					<ul class="questContent questContent-1"></ul>
					<div class="add_quest_button_wrap">
						<a href="#" data-step="1" data-questnum="0" class="add_q AddQuest">Add Quest (True/False)</a>
						<a href="#" data-step="1" data-questnum="0" class="add_q AddMultiple">Add Multiple Quest</a>
						<a href="#" data-step="1" data-questnum="0" class="add_q AddImageQuest">Add Quest (Image answers)</a>
					</div>
				</div>
			</div>
			<!-- INTERACTION -->


		</div>
		<div class="add_step_button_wrapper">
			<a href="#" class="addStep" data-step="1">Add Step</a>
		</div>
	</div>
	<!-- step content end-->
	<?php } ?>
</div>


<?php js_wp_editor(); ?>
<?php
	/*requires js_tpls*/
	require_once(IAMD_TD.'/admin/test_meta/admin_js_tpl/interaction_step_tpl.php');
	require_once(IAMD_TD.'/admin/test_meta/admin_js_tpl/interaction_video_step.php');
	require_once(IAMD_TD.'/admin/test_meta/admin_js_tpl/multiple_quest.php');
	require_once(IAMD_TD.'/admin/test_meta/admin_js_tpl/true_false_quest.php');
	require_once(IAMD_TD.'/admin/test_meta/admin_js_tpl/image_quest.php');
?>








