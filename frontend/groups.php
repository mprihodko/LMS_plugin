<?php get_header(); ?>
<?php $user=$GLOBALS['users']->user ?>

<div class="lms">
	<div class='links-bar'>	
		<?php require_once(TPL_DIR."loops/linksbar.php");?>
	</div>

	<!-- GROUP LIST TEMPLATE  -->
	<?php if(!isset($_GET['page'])) : ?>
		<?php if($user->roles[0]=='administrator' || $user->roles[0]=='teacher'): ?>
			<?php require_once(TPL_DIR.'/loops/group-list-admin-template.php'); ?>
		<?php else: ?>
			<?php require_once(TPL_DIR.'/loops/group-list-user-template.php'); ?>	
		<?php endif; ?>

		<!-- pagination -->
		<div class="tablegroups-pages">
			<span class="pagination-links">
			<?php echo $GLOBALS['groups']->pagination; ?>
			</span>
		</div>
		<!-- pagination -->
	<?php endif; ?>
	<!-- GROUP LIST TEMPLATE  -->

	<!-- REPORTS FOR ADMIN AND TEACHERS -->
	<?php if(isset($_GET['page']) && $_GET['page']=='lms_reports' && ($role=="teacher" || $role=='administrator')): ?>
		<?php require_once(TPL_DIR.'/loops/lms_reports.php'); ?>
	<?php endif; ?>
	<!-- REPORTS FOR ADMIN AND TEACHERS -->

	<!-- EDIT AND CREATE GROUP -->
	<?php if(isset($_GET['page']) && $_GET['page']=='lms_new_group' && ($role=="teacher" || $role=='administrator')): ?>
		<?php $GLOBALS['groups']->lms_groups_edit_admin() ?>
	<?php endif; ?>
	<!-- EDIT AND CREATE GROUP -->

	<!-- REPORTS FOR USERS -->
	<?php if(isset($_GET['page']) && $_GET['page']=='lms_reports' && $role!="teacher" && $role!='administrator'): ?>
		<?php require_once(TPL_DIR.'/loops/lms_user_reports.php'); ?>
	<?php endif; ?>
	<!-- REPORTS FOR USERS -->

</div>
<?php get_footer(); ?>