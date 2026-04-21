<?php

$merchant_id  = "34267879";
$merchant_key = "bvcmqjbdhpdf2";
$passphrase   = "Llll1234Rhoji_";

$pfHost = "https://www.payfast.co.za/eng/process";

$return_url = $_POST['return_url'] ?? 'https://luggafxcollege.iblogger.org/thankyou.html';
$cancel_url = $_POST['cancel_url'] ?? 'https://luggafxcollege.iblogger.org/cancel.html';

$m_payment_id  = $_POST['m_payment_id'] ?? uniqid("order_");
$amount        = $_POST['amount'] ?? '';
$item_name     = $_POST['item_name'] ?? '';
$email_address = $_POST['email_address'] ?? '';

if (!filter_var($email_address, FILTER_VALIDATE_EMAIL)) {
    die("Invalid email");
}

$data = array(
    'merchant_id'   => $merchant_id,
    'merchant_key'  => $merchant_key,
    'return_url'    => $return_url,
    'cancel_url'    => $cancel_url,

    // IMPORTANT: ITN handler
    'notify_url'    => 'https://luggafxcollege.iblogger.org/payfast_notify.php',

    'm_payment_id'  => $m_payment_id,
    'amount'        => number_format((float)$amount, 2, '.', ''),
    'item_name'     => $item_name,
    'email_address' => $email_address
);

$pfOutput = "";

foreach ($data as $key => $val) {
    if ($val !== '' && $val !== null) {
        $pfOutput .= $key . '=' . urlencode(trim($val)) . '&';
    }
}

$pfOutput = rtrim($pfOutput, '&');
$pfOutput .= '&passphrase=' . urlencode($passphrase);

$data['signature'] = md5($pfOutput);

header("Location: $pfHost?" . http_build_query($data));
exit;

?>