<script type="text/template" id="interaction_video_step">
	<div class="video_setting_up">
		<h3 class="video_part_title">Video Part <i class="delete"></i><i class="hide"></i></h3> 
		<div class="video-box video-box-{video}">
			<div class="select_type_video">
				<div data-type="url" class="url">URL</div>
				<div data-type="file" class="file active">FILE</div>
			</div>
			<div class="inputs_data">
				<div class="tb_td">
	 				<input type="file" class="lms_attach_video"  name="lms_course_builder_video[{step}][{video}]" size="25" />  				
	 			</div>	
	 			<div class="tb_td">
	 				<input type="checkbox" class="lms_attach_video_none" name="lms_course_video_display[{step}][{video}]" value="none" />	 				
 					<span> - Hide </span>
					</div>
				</div>
				<textarea id="video_descr-{step}-{video}" name="video_descr[{step}][{video}]" class="video_descr_{step}_{video}"></textarea>

			<hr>					
		</div>
		<div class="add_video_button_wrapper">
			<a class="AddStepVideo" data-step="{step}" data-video="{video}" href="#">Add Video</a>
		</div>	
	</div>
</script>