<?php
require_once ('braintree-php-3.30.0/lib/Braintree.php');

$gateway = new Braintree_Gateway([
  'environment' => 'sandbox',
  'merchantId' => 'dgg5x66vptdfnbtb',
  'publicKey' => 'kqx2gkjpwj59gdqz',
  'privateKey' => '4ce889ab9cbd4e0f15b13fe6d22399f2'
]);
	$amount = $_POST['amount'];
    $payload_nonce = $_POST['payload_nonce'];
    $orderId = $_POST['orderID'];

	$result = $gateway->transaction()->sale([
	    'amount' => $amount,
	    'paymentMethodNonce' => $payload_nonce,
	    'orderId' => $orderId,
	    'options' => [
	        'submitForSettlement' => True
	    ],
	]);
	if ($result->success) {
	  print_r("Success ID: " . $result->transaction->id);
	  echo "<a href='http://localhost/paypalWithBraintree/'>Go back</a>";
	} else {
	  print_r("Error Message: " . $result->message);
	  echo "<a href='http://localhost/paypalWithBraintree/'>Go back</a>";
	}
?>