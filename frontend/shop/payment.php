<div class="checkout_tab_content ">
	<select id="payment_sys">
		<option value="">Select the payment method</option>
		<option value="paypal">PayPal</option>
		<option value="authorize">Authorize</option>
	</select>
	
	<div id="paypal" class="payment-form">
		<label for="pp_card_number">Card Info</label>
		<input id="pp_card_number" class="card_number" name="card_number" placeholder="xxxxxxxxxxxxxxxx" type="text" size="16" onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.keyCode==8">		
		<input id="pp_card_month" class="date" placeholder="mm" name="card_month" type="text" size="2" onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.keyCode==8">
		<span class="date date-separator">/</span>	
		<input id="pp_card_year"  class="date" placeholder="yy" name="card_year" type="text" size="2" onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.keyCode==8">
		<input id="pp_card_cvv"   class="cvv" placeholder="cvv" name="card_cvv" type="text" size="3" onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.keyCode==8">
		<select id="card_type_pp" class="card_type_select">		
			<option value=''>Card Type</option>
			<option value='visa'>Visa</option>
			<option value="mastercard">Mastercard</option>
			<option value="american_express">American Express</option>
			<option value="discover">Discover</option>			
		</select>
		<input type="hidden" name="card_type" id="pp_card_type">
		<label for="pp_first_name">Payment First Name</label>
		<input id="pp_first_name" class="fname" placeholder="First Name" name="payment_fname" type="text">
		<label for="pp_last_name">Payment Last Name</label>
		<input id="pp_last_name" class="lname" placeholder="Last Name" name="payment_lname" type="text" >
		<button class="pay_btn" type="button">PAY</button>
	</div>
	<div id="authorize" class="payment-form">
		<?php echo do_shortcode('[authorize_net]'); ?>
		<!-- <button class="pay_btn" type="button">PAY</button> -->
	</div>
	<button class="prev_btn" type="button">PREV</button>
	
</div>
<script type="text/javascript">
	$ = jQuery
	$("#pp_card_number").on('keydown', function(e){
		if($(this).val().length==16 && e.keyCode!=8){
			return false
		}
	});
	$("#pp_card_month").on('keydown', function(e){
		if($(this).val().length==1 && $(this).val()>1){
			$(this).val("0"+$(this).val())
		}
		if($(this).val().length==2 && e.keyCode!=8){
			return false
		}
	});
	$("#pp_card_year").on('keydown', function(e){
		if($(this).val().length==2 && e.keyCode!=8){
			return false
		}
	});
	$("#pp_card_cvv").on('keydown', function(e){
		if($(this).val().length==3 && e.keyCode!=8){
			return false
		}
	});
	$("#payment_sys").on("change", function(){
		$(".payment-form").hide();
		$("#"+$(this).val()).show();
		$.each($(".payment-form input"), function(){
			$(this).val('');
		})
		
	})
	$("#card_type_pp").on("change", function(){
		$("#pp_card_type").val($(this).val());
	})
</script>