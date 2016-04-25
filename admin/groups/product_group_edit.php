<div class="groups_wrapper" id="admin_edit_groups">
	<form id="groupProduct" action='<?=((is_admin())? admin_url('admin.php?page=lms_product_groups_edit&group='.$this->product_insert_id) : home_url('/groups/'))?>' method='POST'>
		<div class="title_page_groups">
			<span><?=((isset($product->post_title) && $product->post_title!='')?$product->post_title: "NEW GROUP")?></span>
			<input type='button' value='<?php echo ((isset($this->product_insert_id)) ? (($this->product_insert_id>0)? 'Save Product' : 'Add New Product') : 'Add New Product'); ?>' class='add-new-group add' />					
		</div>	

		<?php echo $this->error!='' ?  $this->error : '' ?>		
		<input type='hidden' id="group_id" name='group_id' value='<?=(!$this->product_insert_id)? "0" : $this->product_insert_id?>' />		
		<div class='form-table'>
			<ul class="table-row">
				<li class="column-head">Name</li>
				<li class="column-input">
					<input type='text' name='lms_product_group_name' <?php echo ((isset($product->post_title) && $product->post_title!='Group Name')? 'value="'.htmlentities($product->post_title).'"' : 'placeholder="Name"'); ?> required/>
				</li>						
			</ul>	
			<ul class="table-row">
				<li class="column-head">Price</li>
				<li class="column-input">
				<?php $price = (isset($product->ID)? get_post_meta($product->ID, "_lms_price", true) : "0.00" )?>
				<input type='number' min="0.00" step="0.01" name='lms_gropup_price' value = "<?=((isset($price) && $price!='')? number_format($price, 2, ".", '') : '0.00')?>" required/>
				</li>						
			</ul>
			<ul class="table-row">
				<li class="column-head">Categories</li>	
				<?php //var_dump(get_the_terms($product->ID, "product_type")); ?>			
				<?php foreach(LMS_Shop::lms_get_terms_products_type() as $key => $value): ?>
					<div class="term_group_wrapper">
						<input type="checkbox" name="groups_terms[]" value="<?=$value->slug?>" <?=isset($product->ID)?(has_term($value->slug, "product_type", $product->ID)? 'checked' : '') : ''?>>
						<span> - <strong><?=$value->name?></strong></span> 
					</div> 
				<?php endforeach; ?>
				<div class="btn_add_cat">
					<a href="<?=admin_url('edit-tags.php?taxonomy=product_type&post_type=lms_product')?>" target='blank' class="add_category_p_group" >+ Add Category </a>
				</div>						
			</ul>	
		</div>
		<div class="group_attach">
		 <!-- TESTS Add -->

			<h2 class="title_groups_part">Tests</h2>	
			<div class="select_section test_select">
				<p>
					<input type='text' class="search_field_complete" id='search_test' onkeyup='setTimeout(function() { searchTest(jQuery("#search_test").val()); }, 200);' placeholder='Search...'  autocomplete="off" />
					<ul id='test_suggestions' class="search_results"></ul>
				</p>

				<!-- groups tests exists -->
				<h3 class="title_table_widefat">Selected tests</h3>
				<table class='widefat' id='selected_tests'>
					<thead>
						<tr>
							<th>Test name</th>							
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php if(isset($tests)) { ?>
							<?php foreach($tests as $test) { ?>
								<tr id='tests-<?=$test->ID?>'>
									<td>
										 <a href='post.php?post=<?=$test->ID?>&action=edit'><?=$test->post_title?></a>
									</td>									
									<td>	
										<input type='hidden' name='tests[<?=$test->ID?>]' value='true' />									
										<a href='javascript:removeTest(<?=$test->ID?>); '>[remove]</a>
									</td>
								</tr>
							<?php } ?>
						<?php } ?>
					</tbody>
				</table>
				<!-- groups tests exists -->
				<!-- Pagination -->
				<ul class="group_part_pagination">					
				
				</ul>
			<!-- Pagination -->
			</div>
		<!-- TESTS Add -->
		</div>
		<div class='submit-form-group'>
			<input type='button' value='<?php echo ((isset($this->product_insert_id)) ? (($this->product_insert_id>0)? 'Save Product' : 'Add New Product') : 'Add New Product'); ?>' class='add-new-group add' />			
		</div>
	</form>
</div>
<script type="text/template" id="test_to_group">
	<tr id='tests-{test_id}'>
		<td>
			 <a href='post.php?post={test_id}&action=edit'>{test_name}</a>
		</td>		
		<td>
			<input type='hidden' name='tests[{test_id}]' value='true' />
			<a href='javascript:removeTest({test_id}); '>[remove]</a>
		</td>
	</tr>
</script>