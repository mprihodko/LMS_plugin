<div class="groups_wrapper" id="admin_groups">
	<div class="title_page_groups">
		<span>Groups</span>
		<a href="<?=admin_url('admin.php?page=lms_groups_edit&group=0')?>" class="add-new-group">Add New</a>
	</div>	
	<ul>
		<li id="group-header">
			<ul class="group-header-titles">
				<li class="id">ID</li>
				<li class="name">Title</li>
				<li class="view_limit">Views</li>
				<li class="owner">Owner</li>
				<li class="actions">Actions</li>
			</ul>
		</li>
		<?php if(is_array($groups)): ?>
			<?php foreach ($groups as $key => $group_data): ?>
				<li id="group-<?=$group_data->group_id?>" class="group_row <?=(($group_data->remove==1)? 'removed' : '')?>">				
					<ul class="group-data-list">
						<li class="id">
							<?= $group_data->text_id ? $group_data->text_id : 'none' ?>
						</li>
						<li class="name">
						<?php if($group_data->name): ?>
							<?=$group_data->name?>
						<?php else: ?>
							No-name
						<?php endif; ?>
						</li>
						<li class="view_limit">
							<?=$GLOBALS['reports']->get_used_views($group_data->group_id)?>/<?=$group_data->view_limit?>
						</li>
						<li class="owner">
							<a href="<?=admin_url('user-edit.php?user_id='.$group_data->user_id)?>">
								<?php $username=$GLOBALS['users']->get_user_fullname($group_data->user_id) ?>
								<?php if($username): ?>
									<?=$username['first_name']?> <?=$username['last_name']?>
								<?php endif ?>
							</a>
						</li>
						<li class="actions">	
																	
							<a id="delete-<?=$group_data->group_id?>" href="javascript: <?=(($group_data->remove==1)? 'deleteGroup('.$group_data->group_id.');' : 'removeGroup('.$group_data->group_id.');')?>" class="group_action delButton">
								Delete <i class="fa fa-trash"></i>
							</a>
							<a  id="edit-<?=$group_data->group_id?>" href="<?=admin_url('admin.php?page=lms_groups_edit&group='.$group_data->group_id)?>" class="group_action viewButton">
								Edit <i class="fa fa-pencil"></i>
							</a>
							<a  id="view_group_reports-<?=$group_data->group_id?>" href="#" class="group_action view_group_reports" data-id="<?=$group_data->group_id?>">
								View Reports <i class="fa fa-table"></i>
							</a>
							<a  id="copy_group-<?=$group_data->group_id?>" href="#" class="group_action copy_group" data-id="<?=$group_data->group_id?>">
								Copy <i class="fa fa-copy"></i>
							</a>								
							<?php if($group_data->remove==1) $display="style='display: inline'"; else $display="style='display: none'"; ?>
								 <a  id="restore-<?=$group_data->group_id?>" class="group_action group_restore " <?=(isset($display)? $display : '')?> href="javascript: restoreGroup(<?=$group_data->group_id?>)">
								 	Restore <i class="fa fa-upload"></i>
								 </a>
						</li>
					</ul>				
				</li>
			<?php endforeach; ?>
		<?php endif; ?>
	</ul>
	<div class="group_pagination">
		<?=$pagination?>
	</div>
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