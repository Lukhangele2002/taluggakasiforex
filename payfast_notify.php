<?php

$passphrase = 'Llll1234Rhoji_';

$pfData = $_POST;

$pfSignature = $pfData['signature'] ?? '';
unset($pfData['signature']);

ksort($pfData);

$pfString = '';

foreach ($pfData as $key => $val) {
    if ($val !== '' && $val !== null) {
        $pfString .= $key . '=' . urlencode(trim($val)) . '&';
    }
}

$pfString = rtrim($pfString, '&');
$pfString .= '&passphrase=' . urlencode($passphrase);

if (md5($pfString) !== $pfSignature) {
    http_response_code(400);
    exit("Invalid signature");
}

if (($pfData['payment_status'] ?? '') === 'COMPLETE') {

    $name   = $pfData['custom_str1'] ?? '';
    $surname= $pfData['custom_str2'] ?? '';
    $email  = $pfData['email_address'] ?? '';
    $amount = $pfData['amount_gross'] ?? '0.00';
    $item   = $pfData['item_name'] ?? '';

    // ADMIN EMAIL
    mail(
        "cognacblueberryscalpersupport@gmail.com",
        "New Payment Received",
        "Name: $name $surname\nEmail: $email\nProduct: $item\nAmount: R$amount",
        "From: no-reply@system.com"
    );

    // CUSTOMER EMAIL
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        mail(
            $email,
            "Payment Confirmation - Cognac Blueberry Scalper",
            "Hi $name,\n\nYour payment was successful.\n\nProduct: $item\nAmount: R$amount\n\nThank you.",
            "From: no-reply@system.com"
        );
    }
}

http_response_code(200);
echo "OK";

?>