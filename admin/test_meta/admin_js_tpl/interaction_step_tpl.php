<script type="text/template" id="interaction_step_tpl">
	<div class="step-content">
		<span class="step_numb" data-step="{step}">STEP {step} <i class="hide" data-step='{step}'></i><i class="delete" data-step='{step}'></i></span>			
		<div class="step step-{step}">
			<div class="step_title">
				<label >STEP TITLE: </label>
				<input type="text" name="step_title[{step}]" required>
			</div>			
			<span class="video_section">VIDEO</span>
			<div class="video_data_wrapper_{step}">
				<div class="video_setting_up"> 
					<h3 class="video_part_title">Video Part <i class="hide"></i></h3> 
					<div class="video-box video-box-1">
						<div class="select_type_video">
							<div data-type="url" class="url">URL</div>
							<div data-type="file" class="file active">FILE</div>
						</div>
						<div class="inputs_data">
							<div class="tb_td">
				 				<input type="file" class="lms_attach_video"  name="lms_course_builder_video[{step}][1]" size="25" />  				
				 			</div>	
				 			<div class="tb_td">
				 				<input type="checkbox" class="lms_attach_video_none" name="lms_course_video_display[{step}][1]" value="none" />	 				
			 					<span> - Hide </span>
								</div>
							</div>
							<textarea id="video_descr-{step}-1" name="video_descr[{step}][1]" class="editor_wrap_step_{step}"></textarea>
						<hr>					
					</div>
					<div class="add_video_button_wrapper">
						<a class="AddStepVideo" data-step="{step}" data-video="1" href="#">Add Video</a>
					</div>	
				</div>	
			</div>		
			<span class="video_section">INTERACTION</span>
			<div class="interaction-box">
				<ul class="questContent questContent-{step}"></ul>
				<div class="add_quest_button_wrap">
					<a href="#" data-step="{step}" data-questnum="0" class="add_q AddQuest">Add Quest (True/False)</a>
					<a href="#" data-step="{step}" data-questnum="0" class="add_q AddMultiple">Add Multiple Quest</a>
					<a href="#" data-step="{step}" data-questnum="0" class="add_q AddImageQuest">Add Quest (Image answers)</a>
				</div>
			</div>		
		</div>
		<div class="add_step_button_wrapper">
			<a href="{step}"  class="addStep" data-step="{step}">Add Step</a>
		</div>
	</div>
</script>