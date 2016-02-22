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
				<li class="view_limit">View Limit</li>
				<li class="owner">Owner</li>
				<li class="actions">Actions</li>
			</ul>
		</li>
		<?php if(is_array($groups)): ?>
			<?php foreach ($groups as $key => $group_data): ?>
				<li id="group-<?=$group_data->group_id?>" class="group_row <?=(($group_data->remove==1)? 'removed' : '')?>">				
					<ul class="group-data-list">
						<li class="id">
							<?=$group_data->group_id?>
						</li>
						<li class="name">
						<?php if($group_data->name): ?>
							<?=$group_data->name?>
						<?php else: ?>
							No-name
						<?php endif; ?>
						</li>
						<li class="view_limit">
							<?=$group_data->view_limit?>
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