<div class="groups_wrapper" id="admin_groups">
	<div class="title_page_groups">
		<span>Pay Pal Settings</span>		
	</div>
	<div class="paypal_settings wrapper">
		<form method="POST" action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>" autocomplete="off" >
			<div class="pp_setting_row">
				<h1>PayPal Mode</h1>
				<label for="test_mode">Test Mode</label>
				<input name="paypal_mode" id="test_mode" type="radio" value="sandbox" <?=isset($pp_option['mode']) && $pp_option['mode']=="sandbox" ? 'checked' : ''?>>
				<label for="prod_mode">Live Mode</label>
				<input name="paypal_mode" id="prod_mode" type="radio" value="live" <?=isset($pp_option['mode']) && $pp_option['mode']=="live" ? 'checked' : ''?>>
				<label for="tls_mode">TLS Mode</label>
				<input name="paypal_mode" id="tls_mode" type="radio" value="tls" <?=isset($pp_option['mode']) && $pp_option['mode']=="tls" ? 'checked' : ''?>>
			</div>			
			<div class="pp_setting_row">
				<h1>PayPal API Username</h1>
				<label for="paypal_api_username">PayPal API Username</label>
				<input name="paypal_api_username" id="paypal_api_username" type="text" value="<?=isset($pp_option['api_username'])? $pp_option['api_username'] : ''?>"  autocomplete="off">					
			</div>
			<div class="pp_setting_row">
				<h1>PayPal API Password</h1>
				<label for="paypal_api_password">PayPal API Password</label>
				<input name="paypal_api_password" id="paypal_api_password" type="password" value="<?=isset($pp_option['api_password'])? $pp_option['api_password'] : ''?>"  autocomplete="off">
				<button id="show_pass">Show Password</button>					
			</div>
			<div class="pp_setting_row">
				<h1>PayPal API CLIENT_SECRET </h1>
				<label for="paypal_api_client_secret">CLIENT_SECRET</label>
				<input name="paypal_api_client_secret" id="paypal_api_client_secret" type="text" value="<?=isset($pp_option['api_client_secret'])? $pp_option['api_client_secret'] : ''?>"  autocomplete="off">					
			</div>
			<div class="pp_setting_row">
				<h1>PayPal API CLIENT_ID</h1>
				<label for="paypal_api_client_id">CLIENT_ID</label>
				<input name="paypal_api_client_id" id="paypal_api_client_id" type="text" value="<?=isset($pp_option['api_client_id'])? $pp_option['api_client_id'] : ''?>"  autocomplete="off">					
			</div>
			<div class="pp_setting_row">
				<h1>PayPal API Signature</h1>
				<label for="paypal_api_signature">PayPal API Signature</label>
				<input name="paypal_api_signature" id="paypal_api_signature" type="text" value="<?=isset($pp_option['api_signature'])? $pp_option['api_signature'] : ''?>"  autocomplete="off">					
			</div>
			<div class="pp_setting_row">
				<h1>PayPal API ID</h1>
				<label for="paypal_api_id">PayPal API ID</label>
				<input name="paypal_api_id" id="paypal_api_id" type="text" value="<?=isset($pp_option['api_id'])? $pp_option['api_id'] : ''?>" autocomplete="off">				
			</div>
			<div class="pp_setting_row">					
				<input name="paypal_api_submit" id="paypal_api_submit"  class="btn-save" type="submit" value="Save">					
			</div>
		</form>
	</div>
</div>	

<script type="text/javascript">
	$ = jQuery;
	$("#show_pass").live("click", function(e){
		e.preventDefault();
		$(this).text("Hide Password").attr("id", "hide_pass");
		$("#paypal_api_password").attr("type", "text");
	})
	$("#hide_pass").live("click", function(e){
		e.preventDefault();
		$(this).text("Show Password").attr("id", "show_pass");
		$("#paypal_api_password").attr("type", "password");
	})
</script>