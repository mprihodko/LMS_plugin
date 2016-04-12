<?php if(is_user_logged_in()){ ?>
	<?php $role=$GLOBALS['users']->user->roles[0] ?>
	<?php if($role=='administrator'){?>
		<p class='access-reports'><a href='<?=home_url()?>/groups/?page=lms_reports'>Reports</a></p>
		<p class='access-reports'><a href='<?=home_url()?>/groups/'>Groups</a></p>
		<p class='access-reports'><a href='<?=home_url()?>/examination/?part=add_new_test&post_type=lms_test'>Add Test</a></p>
		<p class='access-reports'><a href='<?=home_url()?>/groups/?page=lms_new_group&group=0'>Add Group</a></p>
		<p class='access-reports'><a href='<?=home_url()?>/examination/'>All Tests</a></p>
		<p class='access-reports'><a href='<?=home_url()?>/lms_shop/'>LMS Shop</a></p>
		<p class='access-reports'><a href='<?=home_url()?>/checkout/'>Checkout</a></p>
		<p class='access-reports'><a href='<?=home_url()?>/cart/'>Cart</a></p>				
	<?php }elseif($role=="teacher"){ ?>
		<p class='access-reports'><a href='<?=home_url()?>/groups/?page=lms_reports'>Reports</a></p>
		<p class='access-reports'><a href='<?=home_url()?>/groups/'>Groups</a></p>
		<p class='access-reports'><a href='<?=home_url()?>/examination/?part=add_new_test&post_type=lms_test'>Add Test</a></p>
		<p class='access-reports'><a href='<?=home_url()?>/groups/?page=lms_new_group&group=0'>Add Group</a></p>
		<p class='access-reports'><a href='<?=home_url()?>/examination/'>All Tests</a></p>
		<p class='access-reports'><a href='<?=home_url()?>/lms_shop/'>LMS Shop</a></p>
		<p class='access-reports'><a href='<?=home_url()?>/checkout/'>Checkout</a></p>
		<p class='access-reports'><a href='<?=home_url()?>/cart/'>Cart</a></p>
	<?php }elseif($role!="teacher" && $role!='administrator'){ ?>
	    <p class='access-reports'><a href='<?=home_url()?>/examination/'>All Tests</a></p> 
		<p class='access-reports'><a href='<?=home_url()?>/groups/?page=lms_reports'>My Reports</a></p>
		<p class='access-reports'><a href='<?=home_url()?>/groups/'>My Groups</a></p>
		<p class='access-reports'><a href='<?=home_url()?>/lms_shop/'>LMS Shop</a></p>
		<p class='access-reports'><a href='<?=home_url()?>/checkout/'>Checkout</a></p>
		<p class='access-reports'><a href='<?=home_url()?>/cart/'>Cart</a></p>
	<?php } ?>
<?php }else{ ?>
	<p class='access-reports'><a href='<?=home_url()?>/lms_shop/'>LMS Shop</a></p>
	<p class='access-reports'><a href='<?=home_url()?>/checkout/'>Checkout</a></p>
	<p class='access-reports'><a href='<?=home_url()?>/cart/'>Cart</a></p>
<?php } ?>