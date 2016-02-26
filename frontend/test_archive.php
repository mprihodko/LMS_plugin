<?php get_header(); ?>
<?php $user=$GLOBALS['users']->user?>
<div class='lms'>
	<div class='links-bar'>			
		<?php require_once(TPL_DIR."loops/linksbar.php");?>
	</div>
	<!-- ADD NEW TEST  -->
	<?php if(isset($_GET['part']) && $_GET['part']=='add_new_test' && ($role=="teacher" || $role=='administrator')): ?>
		<?php require_once(TPL_DIR."loops/add_new_test.php");?>	
	
	<!-- ADD NEW TEST  -->

	<!-- TEST  AVAILABLE -->
	<?php elseif(!isset($_GET['part'])): ?>	
		<?php require_once(TPL_DIR."loops/test-list.php");?>
	<div class="pagenation_wrap"><?$GLOBALS['tests']->test_pagination();?></div>
	<?php else: ?>
		<?php require_once(TPL_DIR."loops/sorry_page.php"); ?>	
	<?php endif; ?>	
	<!-- TEST  AVAILABLE -->
</div> 
<?php get_footer(); ?>