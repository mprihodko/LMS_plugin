<div class="groups_wrapper" id="admin_groups">
	<div class="title_page_groups">
		<span>Test Settings</span>		
	</div>
	<h2 class="setting_lms_admin">Assign a user as having completed a test</h2>
	<div class="form-setting-admin">
		<div class="form-section horizontal_form">
			<label for="user_field">Username</label>
			<input type="text" id="user_field" placeholder="Username">
			<input type="hidden" name="user_id" id="user_id" value="0">
			<ul id="user_suggestions"></ul>
		</div>
		<div class="form-section horizontal_form">
			<label for="group_field">Group Name</label>
			<select name="group_field" id="group_field" disabled="disabled"></select>
		</div>
		<div class="form-section horizontal_form">
			<label for="test_field">Test Name</label>
			<select name="test_field" id="test_field" disabled="disabled"></select>
		</div>
		<div class="form-section horizontal_form">
			<label for="date_field">Date Completed</label>
			<input type="date" name="date_field" id="date_field">
		</div>
		<div class="form-section horizontal_form">
			<button id="assign" class="group_action">Assign</button>
		</div>
	</div>	
</div>
