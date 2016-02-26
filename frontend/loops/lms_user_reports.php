<div class="lms">
	<div class="report_wrapper" id="admin_reports">
	
	<div class="title_page_reports">
		<span>Reports</span>	
	</div>
	<form action="" method="POST" class="form_prepare_results">
		<ul class="table_form">	
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
			<input type="button" value="Generate Reports" data-user="<?=$GLOBALS['users']->user->ID?>" name="generate_reports" id="generate_user_reports" class="generate_reports">
		</div>
	</form>
	<!-- RESULTS -->
	<div class="results_wrapper">
		<table class='widefat' style='margin-top:20px;'>
			<thead>
		 		<tr>
		 			<th>ID</th>
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
			<tbody id="results_table"></tbody>
		</table>
	</div>
	<!-- RESULTS -->
</div>
<script type="text/template" id="results_template">
	<tr class="result-row">
		<td>{num}</td>	
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