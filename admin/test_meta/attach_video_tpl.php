<h4>Upload Video</h4>
<p class="description">
	Upload your Video here.
</p>
<div class="video_inputs">	
	
	<div class="attach">
	<?php if(is_array($attached_video)){ ?>		
		<?php foreach ($attached_video as $key => $value) :	  ?>
			<div class="video_space" id="video-<?=$key?>"> 
				<div class="inputs_data">
					<div class="tb_td">
						<input type="file" style="display:block;" class="lms_attach_video" name="lms_attach_video[]" size="25" />
					</div>	
					<div class="tb_td">
						<input type="text" style="display:block;" id="pathTo-<?=$key?>" value="<?=$value['file']?>" size="25"  />
					</div>	
					<div class="tb_td">
						<input type="checkbox"	name="lms_attach_video_display[<?=$key?>]"	value="none" <?=(($value['none']=="true")? 'checked' : '')?> />	
						<span> - Hide </span>						
					</div>	
					<div class="tb_td">	
						<a class="delete-attach-video button btn-danger"   data-del="<?=$key?>">Delete Video</a>
					</div>					
				</div>
				<div class="param_data">
					<input type="hidden" name="lms_attach_video_path[<?=$key?>]" value="<?=$value['file']?>" size="25"/>
					<input type="hidden" name="lms_attach_video_url[<?=$key?>]"	 value="<?=$value['url']?>" size="25" />					
				</div>
			</div>
			<hr>			
		<?php endforeach; ?>
		<a class="add-attach-video button"   data-num="<?=((isset($num))? $num : 1)?>">Add Video</a>
	<?php }else{ ?>
			<div class="video_space" id="video-0">
				<div class="select_type_video">
					<div data-type="url" class="url">URL</div>
					<div data-type="file" class="file active">FILE</div>
				</div>
				<div class="inputs_data">
					<div class="tb_td">	
						<input type="file" class="lms_attach_video" name="lms_attach_video[0]" 	size="25" />
					</div>					
					<div class="tb_td">		
						<input type="checkbox" class="lms_attach_video_none" name="lms_attach_video_display[0]" value="none" />
						<span> - Hide </span>
					</div>					
				</div>				
			</div>
			<hr>
			<a class="add-attach-video button"   data-num="1">Add Video</a>
	<?php } ?>
	</div>
	<script type="text/template" id="attach_video_add_tpl" >
		<div class="video_space" id="video-{numb}">
			<div class="select_type_video">
				<div data-type="url" class="url">URL</div>
				<div data-type="file" class="file active">FILE</div>
			</div>
			<div class="inputs_data">
				<div class="tb_td">	
					<input type="file" class="lms_attach_video" 	name="lms_attach_video[{numb}]" size="25" >
				</div>	
				
				<div class="tb_td">
					<input type="checkbox"  class="lms_attach_video_none" name="lms_attach_video_display[{numb}]" value="none" >
					<span> - Hide </span>
				</div>
			</div>			
		</div>
		<hr>
		<a class="add-attach-video button"   data-num="{next}">Add Video</a>
	</script>
	<script type="text/template" id="attach_video_new_tpl">
		<div class="video_space" id="video-{numb}">
			<div class="select_type_video">
				<div data-type="url" class="url">URL</div>
				<div data-type="file" class="file active">FILE</div>
			</div>
			<div class="inputs_data">
				<div class="tb_td">	
					<input type="file" class="lms_attach_video" 	name="lms_attach_video[{numb}]" size="25" >
				</div>	
				
				<div class="tb_td">
					<input type="checkbox"  class="lms_attach_video_none" name="lms_attach_video_display[{numb}]" value="none" >
					<span> - Hide </span>
				</div>
			</div>			
		</div>
	</script>
</div>	    	