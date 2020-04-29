<!-- Load PayPal's checkout.js Library. -->
<script src="https://www.paypalobjects.com/api/checkout.js" data-version-4 log-level="warn"></script>

<!-- Load the client component. -->
<script src="https://js.braintreegateway.com/web/3.60.0/js/client.min.js"></script>

<!-- Load the PayPal Checkout component. -->
<script src="https://js.braintreegateway.com/web/3.60.0/js/paypal-checkout.min.js"></script>

<div id="paypal-button"></div>

<script type="text/javascript">
// Create a client.
braintree.client.create({
  authorization: 'sandbox_s9hw272b_dgg5x66vptdfnbtb'
}, function (clientErr, clientInstance) {

  // Stop if there was a problem creating the client.
  // This could happen if there is a network error or if the authorization
  // is invalid.
  if (clientErr) {
    console.error('Error creating client:', clientErr);
    return;
  }

  // Create a PayPal Checkout component.
  braintree.paypalCheckout.create({
    client: clientInstance
  }, function (paypalCheckoutErr, paypalCheckoutInstance) {

    // Stop if there was a problem creating PayPal Checkout.
    // This could happen if there was a network error or if it's incorrectly
    // configured.
    if (paypalCheckoutErr) {
      console.error('Error creating PayPal Checkout:', paypalCheckoutErr);
      return;
    }

    // Set up PayPal with the checkout.js library
    paypal.Button.render({
      env: 'sandbox',
      commit: true, // This will add the transaction amount to the PayPal button

      payment: function () {
        return paypalCheckoutInstance.createPayment({
          flow: 'checkout', // Required
          amount: 0.10, // Required
          currency: 'USD', // Required
          enableShippingAddress: true,
          shippingAddressEditable: false,
          shippingAddressOverride: {
            recipientName: 'Scruff McGruff',
            line1: '1234 Main St.',
            line2: 'Unit 1',
            city: 'Chicago',
            countryCode: 'US',
            postalCode: '60652',
            state: 'IL',
            phone: '123.456.7890'
          }
        });
      },

      onAuthorize: function (data, actions) {
        return paypalCheckoutInstance.tokenizePayment(data, function (err, payload) {
          // Submit `payload.nonce` to your server
          var intent = data.intent;
	        var paymentID = data.paymentID;
	        var payerID = data.payerID;
	        var paymentToken = data.paymentToken;
	        var paymentMethod = 'PayPal';
	        var orderID = data.orderID;
	        console.log(data);
	        document.getElementById('payload_nonce').value = payload.nonce;
  			document.getElementById('orderID').value = orderID;
  
	        if(orderID){
	        	document.getElementById("myForm").submit();
	        }
        });
      },

      onCancel: function (data) {
        console.log('checkout.js payment cancelled', JSON.stringify(data, 0, 2));
      },

      onError: function (err) {
        console.error('checkout.js error', err);
      }
    }, '#paypal-button').then(function () {
      // The PayPal button will be rendered in an html element with the id
      // `paypal-button`. This function will be called when the PayPal button
      // is set up and ready to be used.
    });

  });

});
</script>
<form method="POST" action="http://localhost/paypalWithBraintree/server_side.php" id="myForm">

    <input type="hidden" name="amount" value="0.10" id="amount">
    <input type="hidden" name="payload_nonce" value="" id="payload_nonce">  
    <input type="hidden" name="orderID" value="" id="orderID">  

</form>