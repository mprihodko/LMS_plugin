<script type="text/template" id="true_false_quest">
	<li data-questnumber="{q_num}">
		<h3>Quest Block <small>(True/False)</small><i class="delete"></i><i class="hide"></i></h3>
		<div class="quest_content">
			<label>Title: </label>
			<input class="quest_title_field" type="text" name="int_quest[{step}][{q_num}]">
			<input type="hidden" name="type_of_quest[{step}][{q_num}]" value="true_false" required>
			<h5>Answers</h5>
			<ul>
				<li>
					<input type="checkbox" class="select_true" value="">
					<input type="hidden" class="answer_hidden" name="int_true[{step}][{q_num}][]" value="" required>
					<input type="text" name="int_answer[{step}][{q_num}][]" required>
				</li>
				<li>
					<input type="checkbox" class="select_true" value="">
					<input type="hidden" class="answer_hidden" name="int_true[{step}][{q_num}][]" value="" required>
					<input type="text" name="int_answer[{step}][{q_num}][]" required>
				</li>
			</ul>
			<h4>Quest Description</h4>
			<textarea rows="10" style="width: 100%" id="step-{step}-quest-{q_num}" name="quest_desc[{step}][{q_num}]"></textarea>
		</div>
	<hr>
	</li>
</script>