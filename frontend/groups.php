<?php get_header(); ?>
<?php $user=$GLOBALS['users']->user ?>
<?php //$groups = new lms_groups(); ?>
<div class="lms">
	<div class='links-bar'>	
		<?php require_once(TPL_DIR."loops/linksbar.php");?>
	</div>
	<?php if(!isset($_GET['page'])) : ?>
		<?php if($user->roles[0]='administrator'){ ?>
			<?php require_once(TPL_DIR.'/loops/group-list-admin-template.php'); ?>
		<?php }else{ ?>
			<?php require_once(TPL_DIR.'/loops/group-list-user-template.php'); ?>	
		<?php }	?>
	<!-- pagination -->
	<div class="tablegroups-pages">
		<span class="pagination-links">
		<?php echo $GLOBALS['groups']->pagination; ?>
		</span>
	</div>
	<!-- pagination -->
	<?php endif; ?>
	<?php if(isset($_GET['page']) && $_GET['page']=='lms_reports'): ?>
		<?php require_once(TPL_DIR.'/loops/lms_reports.php'); ?>
	<?php endif; ?>
	<?php if(isset($_GET['page']) && $_GET['page']=='lms_new_group'): ?>
		<?php $GLOBALS['groups']->lms_groups_edit_admin() ?>
	<?php endif; ?>
</div>
<?php get_footer(); ?>