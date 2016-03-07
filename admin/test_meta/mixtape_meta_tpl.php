<table class="form-table">
	<tbody>
		<?php if(isset($_GET['post'])) $post=get_post($_GET['post']); ?>
		<?php foreach ($lms_meta_fields as $field) : ?>
		<?php if(isset($_GET['post'])) $meta = get_post_meta($post->ID, $field['id'], true); ?>
		<tr>
			<th><label for="<?=$field['id']?>"><?=$field['label']?></label></th>
			<td>
				<?php 
					switch($field['type']) {
					// text
					case 'text': ?>
						
						<input type="text" name="<?=$field['id']?>" id="<?=$field['id']?>" value="<?=((isset($meta)) ? $meta : '')?>" size="50" <?=(($field['required'])? 'required' :'')?>/><br />
						<span class="description"><?=$field['desc']?></span>
						
					<?php break;

					// questions
					case 'questions': ?>

						<div class="question-space">
							<div id="question-template" class="question" rel-id="0" style="display:none;">
								<span class="title-quest"> Title: </span>
								<input type="text" class="question-title" name="titles[]" />
								<a class="repeatable-add button" href="#">+</a>
								<fieldset>
									<ul id="'.$field['id'].'-repeatable" class="mixtape_repeatable">
										<?php $i = 0; ?>
										<li class="original" rel-id="0">
										<input type="radio" class="question-answer-radio" name="answers[]"  />
										<input type="text" class="question-answer" name="questions[][]" id="<?=$field['id']?>" value="" size="30"  />
										<a class="repeatable-remove button" href="#">-</a></li>
									</ul>
								</fieldset>
								<a href="#" class="remove-question button">Remove question</a>
							</div>
							<?php if(is_array($questions)): ?>							
								<?php foreach($questions as $k => $question) { ?>
									<div class="question" rel-id="<?=$question->num?>" style="margin-bottom:20px;">
										<span>Title: </span>
										<input type="text" class="question-title" name="titles[<?=$question->num?>]" value="<?=htmlentities($question->title)?>"  />
										<a class="repeatable-add button" href="#">+</a>
										<fieldset>
											<ul id="<?=$field['id']?>-repeatable" class="mixtape_repeatable">
											<?php $i = 0;?>
											<?php $options = json_decode($question->options,1); ?>
											<?php foreach($options as $kk => $o) { ?>
												<li class="<?=(($kk == 0) ? 'original' : 'n')?>" rel-id="<?=$kk?>">
													<input type="radio" class="question-answer-radio"  name="answers[<?=$question->num?>]" value="<?=$kk?>" <?=($question->answer == $kk ? 'checked': '')?>  />
													<input type="text" class="question-answer" name="questions[<?=$question->num?>][<?=$kk?>]" id="<?=$field['id']?>" value="<?=htmlentities($o)?>" size="30"  />
													<a class="repeatable-remove button" href="#">-</a>
												</li>
											<?php } ?>
											</ul>
										</fieldset>
										<a href="#" class="remove-question button">Remove question</a>
									</div>
								<?php } ?>
							<?php endif; ?>
						</div>
						<a class="add-question button" href="#">Add question</a>
					<?php break;
					case 'thumbnail': ?>
						<span class="custom_default_image" style="display:none"><?=$thumbnail?></span>
						<?php if (isset($meta)) { $thumbnail = wp_get_attachment_image_src($meta, 'medium');	$thumbnail = $thumbnail[0]; } ?>	
						<?php if(empty($thumbnail)){ $display="none"; }?>						
						<div> 
							<?php if(isset($thumbnail)){ ?>
								<img data-src="<?=$thumbnail?>" src="<?=$thumbnail?>" width="200px" id="image_thumb" style="display: <?=$display?>" /> 
							<?php }else{ ?>
								<img data-src="" src="<?=IAMD_BASE_URL?>assets/images/no-image.jpg" width="200px" id="image_thumb"  /> 
							<?php } ?>
							<div>
								<input type="hidden" name="<?=$field['id']?>" id="<?=$field['id']?>" value="<?=((isset($meta)) ? $meta : '')?>" />
								<button type="button" class="upload_thumbnail_button button">Choose Image</button>
							<?php if(isset($thumbnail)){ ?>
								<button type="button" class="remove_thumbnail_button button">&times;</button>
							<?php } ?>
							</div>
						</div>
					<?php break;
					case 'image': ?>
						<span class="custom_default_image" style="display:none"><?=$image?></span>	
						<?php if (isset($meta)) { $image = wp_get_attachment_image_src($meta, 'medium');	$image = $image[0];  } ?>	
						<?php if(empty($image)){ $display="none"; }?>		
						<div> 
							<?php if(isset($image)){ ?>
								<img data-src="<?=$image?>" src="<?=$image?>" width="200px" id="img_thumb" style="display: <?=$display?>" /> 
							<?php }else{ ?>
								<img data-src="" src="<?=IAMD_BASE_URL?>assets/images/no-image.jpg" width="200px" id="img_thumb" /> 
							<?php } ?>
							<div>
								<input type="hidden" name="<?=$field['id']?>" id="<?=$field['id']?>" value="<?=((isset($meta)) ? $meta : '')?>" />
								<button type="button" class="upload_image_button button">Choose Image</button>
							<?php if(isset($image)){ ?>
								<button type="button" class="remove_image_button button">&times;</button>
							<?php } ?>
							</div>
						</div>
					<?php										
					break;
				}
				?>
			</td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>