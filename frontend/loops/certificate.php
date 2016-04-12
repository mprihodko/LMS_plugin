<?php if($GLOBALS['tests']->is_user_passed()){ ?>
	<div id="content-wrap" class="container">
		<div id="forprint">
			<img class="certificate-img" src="<?the_permalink()?>?certificate=<?=get_the_ID()?>">
		</div>	
		<a id="printIt" href="javascript:printBlock();">Print</a>
		<script type="text/javascript">		
			function printBlock(){
			    certificate = jQuery('#forprint').html();
			    jQuery('body').addClass('printSelected');
			    jQuery('body').append('<div class="printSelection">' + certificate + '</div>');
			    window.print();		    
			    window.setTimeout(pageCleaner, 0);		    
			    return false;
			}
			function pageCleaner(){
			    jQuery('body').removeClass('printSelected'); 
			    jQuery('.printSelection').remove(); 
			}		
		</script>
	</div>
<?php }else{ ?>
	<div class="error-info">
		<h2> Sorry!</h2>
		<h3> The page you are looking is close!</h3>
		<a href="<?=home_url()?>" title="" class="dt-sc-button small">Back to Home</a> 
	</div>
<?php } ?>

