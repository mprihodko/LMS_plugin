<div class='lms'>
<?php if(isset($_POST["answer"])): ?>
	<?php if($GLOBALS['tests']->is_passed($_POST["answer"], get_the_ID())): ?>
		<h2>Congratulations, you passed!</h2>
		<p>Your score: <?=$GLOBALS['tests']->the_score?></p>
	<?php else:  ?>
		<h2>I'm sorry, you failed to pass this test!</h2>
		<p>Your score: <?=$GLOBALS['tests']->the_score?></p>
	<?php endif; ?>
	<ul class='test-questions'>
	<?php $questions=$GLOBALS['tests']->get_questions()?>			
	<?php if(is_array($questions)): ?>
		<?php foreach ($questions as $key => $value) { ?>
			<li class='test'>
			<h4><?=$value->title?></h4>
			<p><?php $GLOBALS['tests']->the_quest_answers($value->options, $value->answer); ?></p>
		</li>
		<?php } ?>
	<?php endif; ?>				
	</ul>
	<a href="<?=home_url()?>" title="" class="dt-sc-button small">Back to Home</a> 
<?php else: ?>
	
	<div class="error-info">
		<h2> Sorry!</h2>
		<h3> The page you are looking is close!</h3>
		<a href="<?=home_url()?>" title="" class="dt-sc-button small">Back to Home</a> 
	</div>
<?php endif; ?>
</div>
<script type="text/javascript">
	jQuery.cookie("test_access", "false");
	jQuery(document).bind('keydown keyup', function(e) {
    if(e.which === 116) {       
       return false;
    }
    if(e.which === 82 && e.ctrlKey) {      
       return false;
    }
});
</script>