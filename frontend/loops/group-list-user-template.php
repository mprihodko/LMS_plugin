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
				<?php if($GLOBALS['users']->is_user_can($group->group_id)==true){ ?>
					<?php $user_views=$GLOBALS['reports']->get_used_views($group->group_id); ?>
				<?php }else{ ?>
					<?php $user_views=$GLOBALS['reports']->get_user_used_views_in_group($group->group_id); ?>
				<?php } ?>
					<?php //$current_user_view=get_used_views_per_user($group->group_id);?>
					<li id='group-id-<?=$group->group_id?>'>
						<div class="group-name">
							<strong>
								<a href='<?=home_url()?>/tests?group_name=<?=$group->group_id?>' >
								<?=(($group->name)? $group->name : "no-name")?>						
								</a>
							</strong>
						</div>					
						<div class="group-user-views">
							<?=$user_views?>
						</div>					
						<div class="group-views-limit">
							<?=((!$group->view_limit)? '0' : (($group->view_limit<$user_views)? $user_views : $group->view_limit) )?>
						</div>
						<div class="actions-group-block">
						<?php if($GLOBALS['users']->is_user_can($group->group_id)==true){ ?>
							<a id="delete-<?=$group->group_id?>" href='javascript:removeGroup(<?=$group->group_id?>);'> [remove] </a>
							<a href='<?=home_url()?>/groups/?page=lms_new_group&group=<?=$group->group_id?>'> [edit] </a>
							<a href='<?=home_url()?>/groups/?page=lms_reports' data-id="<?=$group->group_id?>" class="view_group_reports">[view group reports]</a>
						<?php }else{ ?>
							<!-- <a href='#' data-id="<?=$group->group_id?>" class="view_group_reports">[view group reports]</a> -->
						<?php } ?>
						</div>
					</li>
			<?php } ?>
	<?php } ?>
	</ul>	
</div>