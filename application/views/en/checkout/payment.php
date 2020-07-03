This is the 3rd party payment page (e.g. Sagepay, Alipay, Paypal, etc), we should wrap this data in a form and post to payment processor onpageload

<?php

$payment_data = ( ! empty( $payment_data ) ) ? $payment_data : "";

echo "<pre>";
print_r($payment_data);
echo "</pre>";

?>