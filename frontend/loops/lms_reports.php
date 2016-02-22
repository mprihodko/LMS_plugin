<div class="lms">
	<div class="report_wrapper" id="admin_reports">
	<div class="title_page_reports">
		<span>Reports</span>	
	</div>	
	<form action="" method="POST" class="form_prepare_results">
		<ul class="table_form">
			<li class="table-row">
				<ul class="header-form">
					<li class="table-cell">
						<span>Report type:</span>
					</li>
					<li class="table-cell">
						<div class="report_selector">
							<div class="selector_item group_report active" data-type="group_report" >Group</div>
							<div class="selector_item user_report" data-type="user_report" >User</div>
							<div class="selector_item test_report" data-type="test_report" >Test</div>
							<input type="hidden" value="group_report" id="current_report">
						</div>
					</li>
				</ul>
			</li>
			<!-- Group Select -->
			<li class="table-row report-type active" id="group_report" >
				<ul class="row-form">
					<li class="table-cell">
						<span>Select Group</span>
					</li>
					<li class="table-cell">
						<input type=text id="groups_search" class="search_panel" placeholder="Search groups..." >
						<ul id="group_suggestions"  class="autocomplete_list"></ul>						
					</li>
				</ul>
				<ul class="row-form">
					<li class="table-cell">
						<span>Selected Groups</span>
					</li>
					<li class="table-cell">
						<table class='widefat' id='selected_groups'>
							<thead>
								<tr>
									<th>Group name</th>									
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
								<script type="text/template" id="group_add">
									<tr id='groups-{group_id}' data-id="{group_id}">
										<td><span>{groupname}</span></td>								
										<td>
											<input type="hidden" value="{group_id}" name="groups[]">
											<a href='javascript:delGroup({group_id}); '>[remove]</a>
										</td>
									</tr>
								</script>
							</tbody>
						</table>						
					</li>
				</ul>
			</li>
			<!-- Group Select -->
			

			<!-- User Select -->
			<li class="table-row report-type" id="user_report">
				<ul class="row-form">
					<li class="table-cell">
						<span>Select User</span>
					</li>
					<li class="table-cell">
						<input type=text id="users_search" class="search_panel" placeholder="Search users...">
						<ul id="user_suggestions"  class="autocomplete_list"></ul>
					</li>
				</ul>
				<ul class="row-form">
					<li class="table-cell">
						<span>Select User</span>
					</li>
					<li class="table-cell">
						<table  class='widefat' id='selected_users' >
							<thead>
								<tr>
									<th>Username</th>									
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
								<script type="text/template" id="user_to_group">
									<tr id='users-{user_id}' data-id="{user_id}">
										<td><span>{username}</span></td>								
										<td>
											<input type="hidden" value="{user_id}" name="users[]">
											<a href='javascript:removeUser({user_id}); '>[remove]</a>
										</td>
									</tr>
								</script>
							</tbody>
						</table>
					</li>
				</ul>					
			</li>
			<!-- User Select -->


			<!-- Test Select -->
			<li class="table-row report-type" id="test_report">
				<ul class="row-form">
					<li class="table-cell">
						<span>Select Test</span>
					</li>
					<li class="table-cell">
						<input type=text id="tests_search" class="search_panel" placeholder="Search tests..." >
						<ul id="test_suggestions" class="autocomplete_list"></ul>
					</li>
				</ul>
				<ul class="row-form">
					<li class="table-cell">
						<span>Selected Tests</span>
					</li>
					<li class="table-cell">
						<table class='widefat' id='selected_tests'>
							<thead>
								<tr>
									<th>Test name</th>							
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
								<script type="text/template" id="test_to_group">
									<tr id='tests-{test_id}' data-id="{test_id}">
										<td>
											 <span>{test_name}</span>
										</td>										
										<td>
											<input type='hidden' name='tests[]' value='{test_id}' />											
											<a href='javascript:removeTest({test_id}); '>[remove]</a>
										</td>
									</tr>
								</script>					
							</tbody>
						</table>					
					</li>
				</ul>
			</li>
			<!-- Test Select -->

			<!-- FILTERS -->
			<!-- date -->
			<li class="table-row" id="filters">
				<ul class="row-form">
					<li class="table-cell">
						<span>Date Filters</span>
					</li>
					<li class="table-cell">
						<label for="day_from">From :</label><input type="date" id="day_from" name="day_from">
						<label for="day_to">To :</label><input type="date" id="day_to"name="day_to">
					</li>
				</ul>
			</li>
			<!-- date -->
			<!-- view attempt enable/disable -->
			<li class="table-row" id="view_attempts">
				<ul class="row-form">
					<li class="table-cell">
						<span>View/Attempts</span>
					</li>
					<li class="table-cell">
						<label for="attempts">Only Attempts :</label><input type="checkbox" id="attempts" name="attempts" value="on">
						<label for="hits">Only Views :</label><input type="checkbox" id="hits"name="hits" value="on">
					</li>
				</ul>
			</li>
			<!-- view attempt enable/disable -->
			<!-- FILTERS -->
		</ul>
		<div class="submit_reports">
			<input type="button" value="Generate Reports" name="generate_reports" id="generate_reports" class="generate_reports">
		</div>
	</form>

	<!-- RESULTS -->
	<div class="results_wrapper">
		<table class='widefat' style='margin-top:20px;'>
			<thead>
		 		<tr>
		 			<th>ID</th>	 	
			 		<th>Result</th>
			 		<th>Full Name</th>	 		
			 		<th>Course</th>
			 		<th>Completed %</th>
			 		<th>Date Completed</th>
			 		<th>Date View</th>
			 		<th>Attempts</th>
			 		<th>View</th>
			 		<th>Interaction</th>
			 		<th>Due</th>	 		
		 		</tr>
			</thead>
			<tbody id="results_table">
			
			</tbody>
		</table>

	</div>
	<!-- RESULTS -->
</div>
<script type="text/template" id="results_template">
	<tr class="result-row">
		<td>{num}</td>					
		<td>{test_result_id}</td>
		<td>{first_name} {last_name}</td>					
		<td>{post_title}</td>
		<td style="text-align: right; padding-right: 60px;">{score}{symbol}</td>
		<td>{time}</td>
		<td>{date_hits}</td>
		<td>{attempts}/{attempts_limit}</td>
		<td>{hits}/{hits_limit}</td>
		<td>{lms_interaction_date}</td>					
	</tr>
</script>
</div>