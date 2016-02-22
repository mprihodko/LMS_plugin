<h4>Upload Custom Media</h4>
<p class="description">
	Upload your Media here.
</p>
<div class="media_inputs">

	<div id="media_section">	
		<?php if(is_array($attached_media)){ ?>	
		
		<?php foreach($attached_media as $key => $value) :?>
			<div class="lms_attach_media_files_contain" id="attached-<?=$key?>">
				<p>
					<input type="file" class="lms_attach_media_file" name="lms_attach_media_file[<?=$key?>]" size="25" /><br>
					<small class='description'>Custom media (PDF, etc): to add a custom media item upload something using the box below and save the post.</small>
				</p>
				<p>
					<h4>File: </h4><a href="<?=$value['url']?>"><?=$value['file']?></a>
					<input type="hidden" name="lms_attach_media_path[<?=$key?>]" value="<?=$value['file']?>" size="25"/>
					<input type="hidden" name="lms_attach_media_url[<?=$key?>]"	 value="<?=$value['url']?>" size="25" />	
				</p>
				<p>
					<h4>Display: </h4> 
					<input type='radio' name='lms_media[<?=$key?>][pos]' value='before' <?=(($value['pos'] == 'before' || $value['pos'] == '') ? 'checked' : '')?> /> <span>Before video &nbsp;</span>
					<input type='radio' name='lms_media[<?=$key?>][pos]' value='after' <?=(($value['pos'] == 'after') ? 'checked' : '')?> /> <span>After video &nbsp;</span>
					<input type='radio' name='lms_media[<?=$key?>][pos]' value='none' <?=(($value['pos'] == 'none') ? 'checked' : '')?> /> <span>None</span>
				</p>
				<p>
					<h4>Permissions: </h4> 
					<input type='radio' name='lms_media[<?=$key?>][perms]' value='everyone' <?=(($value['perms'] == 'everyone' || $value['perms'] == '') ? 'checked' : '')?> /> <span>Everyone &nbsp;</span>
					<input type='radio' name='lms_media[<?=$key?>][perms]' value='limited' <?=(($value['perms'] == 'limited') ? 'checked' : '')?> /><span>Managers/Supervisors</span>
				</p>
				<a class='delete-attach-media button btn-danger'   data-del='<?=$key?>'>Delete media</a>	
			</div>	
			<hr>								
		<?php endforeach ?>
		<a class="add-attach-media button"   data-num="<?=((isset($num))? $num : 1)?>">Add Media</a>
	<?php  }else{ ?>
			<div class="lms_attach_media_files_contain">
				<p>
					<input type="file" class="lms_attach_media_file" name="lms_attach_media_file[0]" size="25" /><br>
					<small class='description'>Custom media (PDF, etc): to add a custom media item upload something using the box below and save the post.</small>
				</p>
				<p>
					Display: 
					<input type='radio' name='lms_media[0][pos]' value='before' checked /><span>Before video &nbsp;</span>
					<input type='radio' name='lms_media[0][pos]' value='after' /> <span>After video &nbsp;</span>
					<input type='radio' name='lms_media[0][pos]' value='none' /> <span>None</span>
				</p>
				<p>
					Permissions: 
					<input type='radio' name='lms_media[0][perms]' value='everyone' checked /> <span>Everyone &nbsp;</span>
					<input type='radio' name='lms_media[0][perms]' value='limited' /> <span>Managers/Supervisors</span>
				</p>
			</div>
			<hr>
			<a class="add-attach-media button"   data-num="1">Add Media</a>
	<?php  } ?>
	</div>
	<script type="text/template" id="attach_media_add_tpl">
		<div class="lms_attach_media_files_contain">
			<p>
				<input type="file" class="lms_attach_media_file" name="lms_attach_media_file[{numb}]" size="25" /><br>
				<small class='description'>Custom media (PDF, etc): to add a custom media item upload something using the box below and save the post.</small>
			</p>
			<p>
				Display: 
				<input type='radio' name='lms_media[{numb}][pos]' value='before' checked /><span>Before video &nbsp;</span>
				<input type='radio' name='lms_media[{numb}][pos]' value='after' /><span> After video &nbsp;</span>
				<input type='radio' name='lms_media[{numb}][pos]' value='none' /> <span>None</span>
			</p>
			<p>
				Permissions: 
				<input type='radio' name='lms_media[{numb}][perms]' value='everyone' checked /> <span>Everyone &nbsp;</span>
				<input type='radio' name='lms_media[{numb}][perms]' value='limited' /><span>Managers/Supervisors</span>
			</p>
		</div>
		<hr>
		<a class="add-attach-media button"   data-num="{next}">Add Media</a>
	</script>
	<script type="text/template" id="attach_media_new_tpl">
		<div class="lms_attach_media_files_contain">
			<p>
				<input type="file" class="lms_attach_media_file" name="lms_attach_media_file[{numb}]" size="25" /><br>
				<small class='description'>Custom media (PDF, etc): to add a custom media item upload something using the box below and save the post.</small>
			</p>
			<p>
				Display: 
				<input type='radio' name='lms_media[{numb}][pos]' value='before' checked /><span>Before video &nbsp;</span>
				<input type='radio' name='lms_media[{numb}][pos]' value='after' /><span> After video &nbsp;</span>
				<input type='radio' name='lms_media[{numb}][pos]' value='none' /> <span>None</span>
			</p>
			<p>
				Permissions: 
				<input type='radio' name='lms_media[{numb}][perms]' value='everyone' checked /> <span>Everyone &nbsp;</span>
				<input type='radio' name='lms_media[{numb}][perms]' value='limited' /><span>Managers/Supervisors</span>
			</p>
		</div>				
	</script>
</div>
		