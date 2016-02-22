<h2 class="page-title-groups">GROUP LIST</h2>
<div class="wrap groups-list">
	<ul class="groups-available">
		<li class="head-group-table">
			<h4>Group Name</h4>
			<h4>Views Used</h4>
			<h4>Views Limit</h4>
			<h4>Actions</h4>
		</li>
		<?php if(get_groups()){	?>		
			<?php foreach(get_groups() as $k => $group) { ?>
				<?php $user_views=get_used_views($group->group_id); ?>
				<?php $current_user_view=get_used_views_per_user($group->group_id);?>
				<li id='group-id-<?=$group->group_id?>'>
					<div class="group-name">
						<strong>
							<a href='<?=home_url()?>/tests?group_id=<?=$group->group_id?>' >
							<?=(($group->name)? $group->name : "no-name")?>						
							</a>
						</strong>
					</div>
						<?php if(check_owner_group($group->group_id)==true){ ?>
					<div class="group-user-views"><?(($user_views[$group->group_id])? $user_views[$group->group_id] : '0')?></div>
					<div class="group-views-limit"><?=$group->view_limit*count_user_by_group($group->group_id)?></div>
						<?php }else{ ?>
					<div class="group-user-views"><?=(($current_user_view[$group->group_id]) ? $current_user_view[$group->group_id] : '0')?></div>
					<div class="group-views-limit"><?=$group->view_limit?></div>
						<?php } ?>
					<div class="actions-group-block">
						<?php if(check_owner_group($group->group_id)==true){ ?>
						<a href='javascript:removeGroup(<?=$group->group_id?>);'  >[remove]</a>
						<a href='<?=home_url()?>/groups/?page=lms_group_edit&group_id=<?=$group->group_id?>' >[edit]</a>
						<a href='<?=home_url()?>/groups/?page=group_reports&group_id=<?=$group->group_id?>'>[view group reports]</a>
						<?php }else{ ?>
						<a href='<?=home_url()?>/groups/?page=group_reports&group_id=<?=$group->group_id?>'>[view group reports]</a>
						<?php } ?>
					</div>
				</li>
		<?php } ?>
	<?php } ?>
	</ul>	
</div>