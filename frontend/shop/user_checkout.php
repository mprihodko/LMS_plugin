<div class="checkout_tab_content user active">
	<?php if(!is_user_logged_in()): ?>
		<div class="userAction login">
			<?php echo do_shortcode('[lms_login_form]'); ?>	
			<span id="login_message"></span>
		</div>
		<div class="userAction registration">
			<?php echo do_shortcode('[lms_registration_form]'); ?>
			<span id="register_message"></span>
		</div>
	<?php else: ?>		
		<div class="userAction user_billing_info">
			<div class="form-group">
				<input type="hidden" name="order_user_id" id="order_user_id" value="<?php echo(isset($GLOBALS['users']->user->ID) ? $GLOBALS['users']->user->ID : null); ?>">
	            <label class="login-field-icon fui-user" for="order_user_login">Username:</label>
	            <input name="order_user_login" id="order_user_login" type="text" class="form-control login-field"
	                   value="<?php echo(isset($GLOBALS['users']->user->user_login) ? $GLOBALS['users']->user->user_login : null); ?>"
	                   placeholder="Username" required/>
	        </div>

	        <div class="form-group">
	            <label class="login-field-icon fui-mail" for="order_user_email">Email:</label>
	            <input name="order_user_email" type="email" class="form-control login-field"
	                   value="<?php echo(isset($GLOBALS['users']->user->user_email) ? $GLOBALS['users']->user->user_email : null); ?>"
	                   placeholder="Email" id="order_user_email" required/>
	        </div>

	        <div class="form-group">
	            <label class="login-field-icon fui-user" for="order_user_fname">First Name:</label>
	            <input name="order_user_fname" type="text" class="form-control login-field"
	                   value="<?php echo(isset($GLOBALS['users']->user->first_name) ? $GLOBALS['users']->user->first_name : null); ?>"
	                   placeholder="First Name" id="order_user_fname"/>
	        </div>

	        <div class="form-group">
	            <label class="login-field-icon fui-user" for="order_user_lname">Last Name:</label>
	            <input name="order_user_lname" type="text" class="form-control login-field"
	                   value="<?php echo(isset($GLOBALS['users']->user->last_name) ? $GLOBALS['users']->user->last_name: null); ?>"
	                   placeholder="Last Name" id="order_user_lname"/>
	        </div>
	        <?php $cart_items=$GLOBALS['LMS_Cart']->the_Cart(); ?>
	        <?php $templates=array(); ?>
			<?php if(is_array($cart_items) && count($cart_items)>0): ?>
				<?php foreach ($cart_items as $key => $product):  ?>
					<?php if(get_post_meta($product->ID, "_lms_product_type", true)=="groups"): ?>
					<?php $templates[]=$product->ID; ?>					
			        <div class="form-group">
			            <label class="login-field-icon fui-new" for="group_selected"><?=$product->post_title?></label>
			            <input name="group_id[]" type="text" class="form-control login-field group_selected group_template"	                  
			                   placeholder="Group ID" data-type="template" id="group_selected-<?=$key?>"/>
			        </div>
			        <?php endif; ?>
		   		<?php endforeach; ?>
		   		<?php if(count($cart_items)>count($templates)): ?>
			   		<div class="form-group">
			            <label class="login-field-icon fui-new" for="group_selected">Group ID:</label>
			            <input name="group_id[]" type="text" class="form-control login-field group_selected group_custom"	                  
			                   placeholder="Group ID" data-type="custom" id="group_selected-<?=count($templates)+1?>"/>
			        </div>
		         <?php endif; ?>			
			<?php endif; ?>
		</div>
	    <button class="next_btn user-tab" type="button">NEXT</button>
	<?php endif; ?>
</div>