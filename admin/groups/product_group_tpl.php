<div class="groups_wrapper" id="admin_groups">
	<div class="title_page_groups">
		<span>Product Groups Templates</span>
		<a href="<?=admin_url('admin.php?page=lms_product_groups_edit&group=0')?>" class="add-new-group">Add New</a>
	</div>	
	<ul class="product-group-list">
		<li id="group-header">
			<ul class="group-header-titles">				
				<li class="group-name">Title</li>
				<li class="binding">Binding</li>		
				<li class="group-actions">Actions</li>
				<li class="price">Price</li>
			</ul>
		</li>
		<?php if(is_array($products)): ?>
			<?php foreach ($products as $key => $group_data): ?>
				<li id="group-<?=$group_data->ID?>" class="group_row">				
					<ul class="group-data-list">						
						<li class="group-name">
						<?php if($group_data->post_name): ?>
							<?=$group_data->post_name?>
						<?php else: ?>
							No-name
						<?php endif; ?>
						</li>
						
						<li class="binding">
							<?php 
								$tests_in=unserialize(get_post_meta($group_data->ID, "_lms_tests", true));
								if(is_array($tests_in)){
									foreach ($tests_in as $key => $value) {
										$tests[]=get_post($value);
									}
								}
							?>
							<?php if(isset($tests)) { ?>
							<ul class="binding-list">
							<?php foreach($tests as $test) { ?>
								<li id='tests-<?=$test->ID?>'>
									<a href='post.php?post=<?=$test->ID?>&action=edit'><?=$test->post_title?></a>
								</li>
							<?php } ?>
							</ul>
						<?php } ?>
						</li>
						<li class="group-actions">
							<a  id="edit-<?=$group_data->ID?>" href="<?=admin_url('admin.php?page=lms_product_groups_edit&group='.$group_data->ID)?>" class="group_action viewButton">
								Edit <i class="fa fa-pencil"></i>
							</a>							
							<a  id="deleteProductGroup-<?=$group_data->ID?>" href="#" class="group_action deleteProductGroup" data-id="<?=$group_data->ID?>">
								Delete <i class="fa fa-trash"></i>
							</a>					
							
						</li>
						<li class="price">
							<?="$ ".number_format(get_post_meta($group_data->ID, "_lms_price", true), 2, ".", ""); ?>
						</li>
					</ul>				
				</li>
			<?php endforeach; ?>
		<?php endif; ?>
	</ul>
	<div class="group_pagination">
		<?=$pagination?>
	</div>
</div>