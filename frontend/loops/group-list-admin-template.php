<h2 class="page-title-groups">GROUP LIST</h2>
<div class="wrap groups-list">
	<ul class="groups-available">
		<li class="head-group-table">
			<h4>Group Name</h4>
			<h4>Views Used</h4>
			<h4>Views Limit</h4>
			<h4>Actions</h4>
		</li> 
	<?php if($GLOBALS['groups']->lms_groups_list_frontend()){ ?>
		<?php foreach($GLOBALS['groups']->lms_groups_list_frontend() as $k => $group) { ?>
			<?php $user_views=$GLOBALS['reports']->get_used_views($group->group_id); ?> 			
				<li id='group-<?=$group->group_id?>'>
					<div class="group-name">
						<strong>
							<a href='<?=home_url()?>/examination?group_name=<?=$group->group_id?>' >
							<?php if($group->remove!=1){ ?>
								<?=(($group->name)? $group->name : "no-name")?><span id='removed-<?=$group->group_id?>' style='color: #000'></span>
							<?php }else{ ?>
								<?=(($group->name)? $group->name : "no-name")?><span id='removed-<?=$group->group_id?>' style='color: #000'> (removed)</span>
							<?php }	 ?>					
							</a>
						</strong>
					</div>
					<div class="group-user-views"><?=$user_views?></div>
					<div class="group-views-limit">
						<?=((!$group->view_limit)? '0' : (($group->view_limit<$user_views)? $user_views : $group->view_limit) )?>
					</div>
					<div class="actions-group-block">
						<?php if($group->remove==1){ ?>					
							<a href='javascript:restoreGroup(<?=$group->group_id?>);' id='restore-<?=$group->group_id?>' style='color: green; display: inline; '>[RESTORE]</a>
							<a href='javascript:deleteGroup(<?=$group->group_id?>);' id='delete-<?=$group->group_id?>' >[remove]</a>
						<?php }else{ ?>
							<a href='javascript:restoreGroup(<?=$group->group_id?>);' id='restore-<?=$group->group_id?>' style='color: green; display: none; '>[RESTORE]</a>
							<a href='javascript:removeGroup(<?=$group->group_id?>);' id='delete-<?=$group->group_id?>' >[remove]</a>
						<?php } ?>					
							<a href='<?=home_url()?>/groups/?page=lms_new_group&group=<?=$group->group_id?>' >[edit]</a>
							<a href='<?=home_url()?>/examination?group_name=<?=$group->group_id?>' >[view]</a>
							<a href='#' data-id="<?=$group->group_id?>" class="view_group_reports">[view group reports]</a>
							<a href='#' data-id="<?=$group->group_id?>" class="copy_group ">[Copy]</a>
					</div>
				</li>					
		<?php } ?>
		
	<?php } ?>
	</ul>
</div>
<div id="report_modal" style="display: none;">
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
				<tbody id="results_table">
				
				</tbody>
			</table>

		</div>
	<!-- RESULTS -->
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
				<td>{due}</td>					
			</tr>
		</script>
	</div>