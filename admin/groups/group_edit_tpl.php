<div class="groups_wrapper" id="admin_edit_groups">
	<form action='<?=((is_admin())? admin_url('admin.php?page=lms_groups_edit&action=edit_group') : home_url('groups/'))?>' method='POST'>
	<div class="title_page_groups">
		<span><?=(($group->group_id>0)?$group->name: "NEW GROUP")?></span>
		<input type='submit' class="add-new-group" value='<?php echo (($_GET['group']>0) ? 'Save Group' : 'Add New Group'); ?>'/>
	</div>			
		<input type='hidden' id="group_id" name='group_id' value='<?=$group->group_id?>' />		
		<div class='form-table'>
			<ul class="table-row">
				<li class="column-head">Name</li>
				<li class="column-input">
					<input type='text' name='name' <?php echo (($group->group_id>0)? 'value="'.htmlentities($group->name).'"' : 'placeholder="'.htmlentities($group->name).'"'); ?> required/>
				</li>						
			</ul>
			<ul class="table-row">
				<li class="column-head">Video on demand</li>
				<li class="column-input">
					<input type="radio" value="1" <?php echo (($group->video_demand == '1') ? 'checked' : '') ?> name="video_demand"> - Yes / 
					<input type="radio" value="0" <?php echo (($group->video_demand == '0' || !$group->video_demand) ? 'checked' : '') ?> name="video_demand"> - No
				</li>
			</ul>
			<ul class="table-row">
				<li class="column-head">Disable review video</li>
				<li class="column-input">
					<input type="radio" value="1" <?php echo (($group->video_review == '1') ? 'checked' : '') ?> name="video_review"> - Yes / 
					<input value="0" <?php echo (($group->video_review == '0' || !$group->video_demand) ? 'checked' : '') ?> type="radio" name="video_review"> - No
				</li>			
			</ul>
			<ul class="table-row">
				<li class="column-head">ID</li>
				<li class="column-input">
					<input type='text' name='text_id' value="<?=$group->text_id?>" />
				</li>
			</ul>
			<ul class="table-row">
				<li class="column-head">Test view limit(default)</li>
				<li class="column-input">
					<input type='text' name='group_test_view' value="<?=$group->group_test_view?>" />
				</li>
			</ul>	
			<ul class="table-row">
				<li class="column-head">Global view limit</li>
				<li class="column-input">
					<input type='text' name='view_limit_group' <?php echo (isset($group) ? 'value="'.htmlentities($group->view_limit).'"' : ''); ?> />
				</li>
			</ul>
			<ul class="table-row">
				<li class="column-head">Description</li>
				<li class="column-input">
					<?php wp_editor($group->description, 'editor_id', array(
							'wpautop' => 1,
							'media_buttons' => 1,
							'textarea_name' => 'description', 
							'textarea_rows' => 5,
							'tabindex'      => null,
							'editor_css'    => '',
							'editor_class'  => '',
							'teeny'         => 0,
							'dfw'           => 0,
							'tinymce'       => 1,
							'quicktags'     => 1,
							'drag_drop_upload' => true
						) ); 
					?>				
				</li>
			</ul>
			<ul class="table-row">
				<li class="column-head">Group Notes</li>
				<li class="column-input">
					<?php	wp_editor($group->notice, 'notes_id', array(
								'wpautop' => 1,
								'media_buttons' => 1,
								'textarea_name' => 'notice', 
								'textarea_rows' => 5,
								'tabindex'      => null,
								'editor_css'    => '',
								'editor_class'  => '',
								'teeny'         => 0,
								'dfw'           => 0,
								'tinymce'       => 1,
								'quicktags'     => 1,
								'drag_drop_upload' => true
							) ); 
					?>				
				</li>
			</ul>
		</div>
		<div class="group_attach">
		 <!-- TESTS Add -->

			<h2 class="title_groups_part">Tests</h2>	
			<div class="select_section test_select">
				<p>
					<input type='text' class="search_field_complete" id='search_test' onkeyup='setTimeout(function() { searchTest(jQuery("#search_test").val()); }, 200);' placeholder='Search...'  autocomplete="off" />
					<ul id='test_suggestions' class="search_results"></ul>
				</p>

				<!-- groups tests exists -->
				<h3 class="title_table_widefat">Selected tests</h3>
				<table class='widefat' id='selected_tests'>
					<thead>
						<tr>
							<th>Test name</th>
							<th>View Limit</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php if(isset($tests)) { ?>
							<?php foreach($tests as $test) { ?>
								<tr id='tests-<?=$test->ID?>'>
									<td>
										 <a href='post.php?post=<?=$test->ID?>&action=edit'><?=$test->post_title?></a>
									</td>
									<td>
										<input name='view_limit[<?=$test->ID?>]' value='<?=$test->view_limit?>' />
									</td>
									<td>										
										<a href='javascript:removeTest(<?=$test->ID?>); '>[remove]</a>
									</td>
								</tr>
							<?php } ?>
						<?php } ?>
					</tbody>
				</table>
				<!-- groups tests exists -->
				<!-- Pagination -->
				<ul class="group_part_pagination">					
				
				</ul>
			<!-- Pagination -->
			</div>
		<!-- TESTS Add -->
			
		<!-- users add -->
			<h2 class="title_groups_part">Users</h2>
			<div class="select_section test_select">
				<p>
					<input type='text' class="search_field_complete" id='search_user' onkeyup='setTimeout(function() { searchUser(jQuery("#search_user").val()); }, 200);' placeholder='Search...'  autocomplete="off" />
					<ul id='user_suggestions' class="search_results"></ul>
				</p>

				<!-- users exists -->
				<h3 class="title_table_widefat" >Selected users</h3>
				<table  class='widefat' id='selected_users' >
					<thead>
						<tr>
							<th>Username</th>
							<th>User level</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php if(isset($users)) { ?>
							<?php foreach($users as $user) { ?>
								<tr id='users-<?=$user->ID?>'>
									<td><a href="<?=admin_url('user-edit.php?user_id='.$user->ID)?>"><?=$user->user_login?></a></td>
									<td>										
										<select name='userlevel[<?=$user->ID?>]' <?=(($user->ID==$group->user_id)? 'disabled': '') ?> >
											<option value='0' <?=($user->user_level == 0 ? 'selected' : '')?>>User</option>
											<option value='1' <?=($user->user_level == 1 ? 'selected' : '')?>>HR</option>
											<option value='2' <?=($user->user_level == 2 ? 'selected' : '')?>>Manager</option>
										</select>
									</td>
									<td>
										<a href='javascript:removeUser(<?=$user->ID?>); '>[remove]</a>
									</td>
								</tr>
							<?php } ?>
						<?php } ?>
					</tbody>
				</table>
				<!-- users exists -->

				<!-- Pagination -->
				<ul  class="group_part_pagination">
					
				</ul>
				<!-- Pagination -->
			</div>
			<!-- users add -->

			<h2 class="title_groups_part">Courses</h2>
			<div class="select_section test_select">
				<p>
					<input type='text' class="search_field_complete"  id='search_courses' onkeyup='setTimeout(function() { searchCourses(jQuery("#search_courses").val()); }, 200);' placeholder='Search...' autocomplete="off" />
					<ul id='courses_suggestions' class="search_results"></ul>
				</p>

				<!-- courses exists -->
				<h3 class="title_table_widefat">Selected courses</h3>
				<table  class='widefat' id='selected_courses' >
					<thead>
						<tr>
							<th>Course Name</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
					<?php if(isset($courses)) { ?>
						<?php foreach($courses as $course) { ?>
							<tr id='courses-<?=$course->ID?>'>
								<td>
									<a href='post.php?post=<?=$course->ID?>&action=edit'><?=$course->post_title?></a>
								</td>
								<td>
									<input type='hidden' name='courses[<?=$course->ID?>]' value='true' />
									<a href='javascript:removeCourse(<?=$course->ID?>); '>[remove]</a>
								</td>
							</tr>
						<?php } ?>
					<?php } ?>
					</tbody>
				</table>
				<!--courses exists -->
				

				<!-- Pagination -->
				<ul class="group_part_pagination">
					
				</ul>
				<!-- Pagination -->		
			</div>
		</div>	
			<div class='submit-form-group'>
				<input type='submit' value='<?php echo (($_GET['group']>0) ? 'Save Group' : 'Add New Group') ?>' class='add-new-group' />
			</div>
	</form>
</div>


<script type="text/template" id="test_to_group">
	<tr id='tests-{test_id}'>
		<td>
			 <a href='post.php?post={test_id}&action=edit'>{test_name}</a>
		</td>
		<td>
			<input name='view_limit[{test_id}]' value='0' />
		</td>
		<td>
			<input type='hidden' name='tests[{test_id}]' value='true' />
			<a href='javascript:removeTest({test_id}); '>[remove]</a>
		</td>
	</tr>
</script>

<script type="text/template" id="user_to_group">
	<tr id='users-{user_id}'>
		<td><a href="user-edit.php?user_id={user_id}">{username}</a></td>
		<td>
			<input type='hidden' name='users[{user_id}]' value='true' />
			<select name='userlevel[{user_id}]'>
				<option value='0'>User</option>
				<option value='1'>HR</option>
				<option value='2'>Manager</option>
			</select>
		</td>
		<td>
			<a href='javascript:removeUser({user_id}); '>[remove]</a>
		</td>
	</tr>
</script>

<script type="text/template" id="course_to_group">
	<tr id='courses-{courseid}'>
		<td>
			<a href='post.php?post={courseid}&action=edit'>{course-name}</a>
		</td>
		<td>
			<input type='hidden' name='courses[{courseid}]' value='true' />
			<a href='javascript:removeCourse({courseid}); '>[remove]</a>
		</td>
	</tr>
</script>