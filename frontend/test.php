<?php global $post; ?>
<?php if(!isset($_GET['part']) && !isset($_GET['certificate'])){ wp_redirect(get_the_permalink()."?part=".$GLOBALS['tests']->get_part(get_the_ID())); } ?>
<?php $user=$GLOBALS['users']->user ?>
<?php $media = unserialize($GLOBALS['tests']->has_media($post->ID)); ?>

<?php $user_data=get_user_meta($user->ID, "lms_steps_".get_the_ID(), true); ?>

<?php if(!$user_data)  $steps_done=0; else  $steps_done=count($user_data); ?>
<?php $steps=unserialize(get_post_meta(get_the_ID(), 'lms_course_steps', true)); ?>		   
<?php $segments=get_post_meta(get_the_ID(), 'lms_interactive_status', true); ?>

<?php get_header(); ?>
<?php if($group_id=$GLOBALS['groups']->identify_group()): ?>
<div class='lms'>
	<h2 class="page-title-groups"><?=the_title();?></h2>	

	<!-- BEFORE MEDIA ITEMS -->
	<?php if(isset($_GET['part']) && $_GET['part']=='before' && $GLOBALS['users']->have_views($post->ID, $group_id)): ?>	
		<?php include(TPL_DIR."loops/media_tpl.php");?>	
	<?php elseif(isset($_GET['part']) && $_GET['part']=='before' && !$GLOBALS['users']->have_views($post->ID, $group_id)): ?>
		<?php include(TPL_DIR."loops/sorry_page.php");?>		
	<?php endif; ?>
	<!-- BEFORE MEDIA ITEMS -->

	<!-- INTERACTION -->
	<?php if($segments=='on' && isset($_GET['part']) && $_GET['part']=='interaction'): ?>
		<div class="interaction_course" id="interaction_course">
			<?php require_once(TPL_DIR."loops/interaction.php");?>
		</div>		
	<?php endif; ?>
	<!-- INTERACTION -->

	<!-- VIDEO PARTS -->

	<?php if(isset($_GET['part']) && $_GET['part']=='video' && $GLOBALS['users']->have_views($post->ID, $group_id)): ?>
		<?php $video=$GLOBALS['tests']->the_video(); ?>
		<?php require_once(TPL_DIR."loops/test_video.php");?>		
	<?php endif; ?>
	<!-- VIDEO PARTS -->

	<!-- AFTER MEDIA ITEMS -->
	<?php if(isset($_GET['part']) && $_GET['part']=='after' && $GLOBALS['users']->have_views($post->ID, $group_id) ): ?>	
		<?php include(TPL_DIR."loops/media_tpl.php");?>		
	<?php endif; ?>
	<!-- AFTER MEDIA ITEMS -->

	<!-- QUESTIONS PART -->
	<?php if(isset($_GET['part']) && $_GET['part']=='questions' && $GLOBALS['users']->have_attempts($post->ID, $group_id)): ?>	
		<?php include(TPL_DIR."loops/questions.php");?>
	<?php elseif(isset($_GET['part']) && $_GET['part']=='questions' && !$GLOBALS['users']->have_attempts($post->ID, $group_id)): ?>
		<?php include(TPL_DIR."loops/sorry_page.php");?>		
	<?php endif; ?>
	<!-- QUESTIONS PART -->

	<!-- RESULTS PART -->
	<?php if(isset($_GET['part']) && $_GET['part']=='results' && $GLOBALS['users']->have_attempts($post->ID, $group_id)): ?>	
		<?php include(TPL_DIR."loops/results.php");?>	
	<?php elseif(isset($_GET['part']) && $_GET['part']=='results' && !$GLOBALS['users']->have_attempts($post->ID, $group_id)): ?>
		<?php include(TPL_DIR."loops/sorry_page.php");?>		
	<?php endif; ?>
	<!-- RESULTS PART -->

	<!-- CERTIFICATE -->
	<?php if(isset($_GET['part']) && $_GET['part']=='certificate'): ?>	
		<?php include(TPL_DIR."loops/certificate.php");?>	
	<?php endif; ?>	
	<!-- CERTIFICATE -->
	


	<?php if(isset($_GET['part'])
		  && $_GET['part']!='interaction'
		  && $_GET['part']!='questions'
		  && $_GET['part']!='results'
		  && $_GET['part']!='certificate'): ?>
		<h2 class="page-title-groups">About The Test</h2>
		<div class="testDesc">
			<?php echo $post->post_content ?>
		</div>
	<?php endif; ?>
</div>
<?php else: ?>
	<?php include(TPL_DIR."loops/sorry_page.php");?>	
<?php endif; ?>
<?php get_footer(); ?>