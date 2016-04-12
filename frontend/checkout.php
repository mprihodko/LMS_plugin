<div class="lms">
	<div class='links-bar'>	
		<?php require_once(TPL_DIR."loops/linksbar.php");?>
	</div>	
	<?php if($GLOBALS['LMS_Cart']->total_cart_items>0): ?>
		<div class="checkout_wrapper">
			<ul class="checkout_tabs">
				<li class="checkout_tab identyfy_user">
					<h1 class="tab-header active">USER DETAILS <i class="fa fa-caret-square-o-down"></i></h1>
					<?php require_once(TPL_DIR."shop/user_checkout.php");?>
				</li>
				<li class="checkout_tab items_list">
					<h1 class="tab-header not-active">ORDER ITEMS <i class="fa fa-caret-square-o-up"></i></h1>
					<?php require_once(TPL_DIR."shop/cart_checkout.php");?>
				</li>				
				<li class="checkout_tab payment">
					<h1 class="tab-header not-active">PAYMENT <i class="fa fa-caret-square-o-up"></i></h1>
					<?php require_once(TPL_DIR."shop/payment.php"); ?>
				</li>
			</ul>
		</div>
	<?php else: ?>
		<div class="empty-info">
			<h2>Your Cart is empty</h2>
			<a href="<?=home_url('lms_shop')?>" class="go_shop_btn">Go Shop!</a>
		</div>
	<?php endif; ?>
</div>