<?php 

use PayPal\Auth\OAuthTokenCredential;
	use PayPal\Rest\ApiContext;
	use PayPal\Api\CreditCard;
	use PayPal\Api\FundingInstrument;
	// use PayPal\Api\Address;
	use PayPal\Api\Payer;
	use PayPal\Api\Amount;
	use PayPal\Api\Transaction;
	use PayPal\Api\Payment;
	use PayPal\Api\Details;
	use PayPal\Exception\PayPalConnectionException;
$pp_option=unserialize(get_option( "_lms_pay_pal_settings", false));
if(isset($pp_option['api_client_id']) && isset($pp_option['api_client_secret'])){ 
	$oauthCredential = new OAuthTokenCredential($pp_option['api_client_id'], $pp_option['api_client_secret']);
	$accessToken     = $oauthCredential->getAccessToken(array('mode' => $pp_option['mode']));
	// var_dump($accessToken);
}
function pay($data, $total){
	
	$pp_option=unserialize(get_option( "_lms_pay_pal_settings", false));
	$apiContext = new ApiContext(new OAuthTokenCredential(
			$pp_option['api_client_id'],  $pp_option['api_client_secret']));
			


	$card = new CreditCard();
	$card->setNumber($data["card_number"]);
	$card->setType($data["card_type"]);
	$card->setExpireMonth($data["card_month"]);
	$card->setExpireYear('20'.$data["card_year"]);
	$card->setCvv2($data["card_cvv"]);
	$card->setFirstName($data["payment_fname"]);
	$card->setLastName($data["payment_lname"]);
	// $card->setBilling_address($addr);

	$fi = new FundingInstrument();
	$fi->setCreditCard($card);

	$payer = new Payer();
	$payer->setPaymentMethod('credit_card');
	$payer->setFundingInstruments(array($fi));

	$amountDetails = new Details();
	$amountDetails->setSubtotal($total);
	$amountDetails->setTax('0.00');
	$amountDetails->setShipping('0.00');

	$amount = new Amount();
	$amount->setCurrency('USD');
	$amount->setTotal($total);
	$amount->setDetails($amountDetails);

	$transaction = new Transaction();
	$transaction->setAmount($amount);
	$transaction->setDescription('This is the payment transaction description.');

	$payment = new Payment();
	$payment->setIntent('sale');
	$payment->setPayer($payer);
	$payment->setTransactions(array($transaction));

	// $payment->create($apiContext);
	try {

    $payment->create($apiContext);

    // Generate and store hash
    // Prepare and execute transaction storage



	} catch (PayPalConnectionException $e) {
	    // echo $e->getData();
	    // Perhaps log an error
	    // header('Location: ../PayPall/error.php');
	}

	return $payment;
}
// var_dump($payment);
/*

  ["card_number"]=>
  string(16) "4032033090220679"
  ["card_month"]=>
  string(2) "01"
  ["card_year"]=>
  string(2) "21"
  ["card_cvv"]=>
  string(3) "874"
  ["card_type"]=>
  string(4) "visa"
  ["payment_fname"]=>
  string(8) "sfhdfcvb"
  ["payment_lname"]=>
  string(10) "dfhdfhdfhd"
  */