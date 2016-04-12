<?php if ($_GET['access']==$GLOBALS['tests']->get_token(get_the_ID())){ ?>	
	<form action='<?php echo the_permalink()?>?part=results' method='POST'>		
		<div class='lms'>
			<ul class='test-questions'>
			<?php $questions=$GLOBALS['tests']->get_questions()?>			
			<?php if(is_array($questions)): ?>
				<?php foreach ($questions as $key => $value) { ?>
					<li class='test'>
					<h4><?=$value->title?></h4>
					<p><?php $GLOBALS['tests']->the_quest_options($value->options, $value->question_id); ?></p>
				</li>
				<?php } ?>
			<?php endif; ?>				
			</ul>
			<p class='questions-submit'>
				<input type='submit' name="submit_answers" value='Submit Answers' />
			</p>
		</div>
	</form>

<?php } else{ ?>
	
	<div class="error-info">
		<h2> Sorry!</h2>
		<h3> The page you are looking is close!</h3>
		<a href="<?=home_url()?>" title="" class="dt-sc-button small">Back to Home</a> 
	</div>

<?php } ?>
