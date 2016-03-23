<script type="text/template" id="image_quest">
	<li data-questnumber="{q_num}">
		<h3>Quest Block <small>(Graphic)</small><i class="delete"></i><i class="hide"></i></h3>
		<div class="quest_content">
			<label>Title: </label>
			<input class="quest_title_field" type="text" name="int_quest[{step}][{q_num}]">
			<input type="hidden" name="type_of_quest[{step}][{q_num}]" value="image" required>
			<h5>Answers</h5>
			<ul class="step-{step}-quest-{q_num}-answers">
				<li>
					<input type="radio" name="int_true_select[{step}][{q_num}][]" class="select_true"  value="" required>
					<input type="hidden" class="answer_hidden" name="int_true[{step}][{q_num}][]" value="" required>
					<input type="file" name="img_answer[{step}][{q_num}][]" required>
					<a data-answer="{a_num}" data-questnum="{q_num}" data-step="{step}" class="addAnswerImg"><i class="fa fa-plus-circle"></i></a>
				</li>
			</ul>
			<h4>Quest Description</h4>
			<textarea rows="10" style="width: 100%" id="step-{step}-quest-{q_num}" name="quest_desc[{step}][{q_num}]"></textarea>
		</div>
	<hr>
	</li>
</script>
<script type="text/template" id="image_quest_answer">
	<li>
		<input type="radio" name="int_true_select[{step}][{q_num}][]" class="select_true" value="" required >
		<input type="hidden" class="answer_hidden" name="int_true[{step}][{q_num}][]" value="" required>
		<input type="file" name="img_answer[{step}][{q_num}][]" required>
		<a data-answer="{a_num}" data-questnum="{q_num}" data-step="{step}" class="delAnswer"><i class="fa fa-minus-circle"></i></a>
	</li>
</script>