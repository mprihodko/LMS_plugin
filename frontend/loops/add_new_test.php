<form action="<?=home_url('tests')?>?<?=((!isset($_GET['post']))? 'save_test=true' : 'edit_test='.$_GET['post'] )?>" method="post" class="addtestForm" enctype="multipart/form-data" autocomplete="off">
		<?php if(isset($_GET['post']) && !isset($_GET['post_type'])){ $test=get_post($_GET['post']); }?>
		
		<div id="titlewrap">
			<label class="" id="title-prompt-text" for="title">Enter title here</label>

			<input type="text" name="post_title" size="30" id="title" spellcheck="true" autocomplete="off"  value="<?=((isset($test))? $test->post_title : '' )?>" >
			
		</div>
		<?php wp_editor( ((isset($test))? $test->post_content : '' ), 'content', array(
							'wpautop' => 1,
							'media_buttons' => 1,
							'textarea_name' => 'post_content', 
							'textarea_rows' => 5,
							'tabindex'      => null,
							'editor_css'    => '',
							'editor_class'  => 'wp-editor-area',
							'teeny'         => 0,
							'dfw'           => 0,
							'tinymce'       => 1,
							'quicktags'     => 1,
							'drag_drop_upload' => true
						) ); 
		?>
		
		<?php
		  $GLOBALS['tests']->lms_course_builder();
		  $GLOBALS['tests']->lms_attach_video();
		  $GLOBALS['tests']->lms_attach_media_files();
		  $GLOBALS['tests']->show_mixtape_meta_box();
		?>	
		<div id="submit-test-new">
			<input type="submit" name="publishFrontpost" id="publish" class="button-large" value="Publish">
		</div>
	</form>