<?php

function  rezerv($card){

$cart=json_encode($card);
$ch = curl_init();

$url = "https://sandbox.booknetic.com/sandboxes/sandbox-saas-6f49ae724d32a0cf3823/wp-admin/admin-ajax.php";


$headers = [
    'Content-Type: multipart/form-data; boundary=----WebKitFormBoundaryZbga5PnV4uBfli1p',
];


$payload = [
    "payment_method"=> "local",
    "deposit_full_amount"=> 0,
    "client_time_zone"=> "-",
    "google_recaptcha_token"=> "undefined",
    "google_recaptcha_action"=> "booknetic_booking_panel_1",
    "step"=> "confirm",
    "cart"=> "[$cart]",
    "current"=> 0,
    "query_params"=> '{}',
    "coupon"=> "",
    "giftcard"=> "",
    "action"=> "bkntc_confirm",
    "tenant_id"=> 3
];


$boundary = '----WebKitFormBoundaryZbga5PnV4uBfli1p';
$body = '';
foreach ($payload as $key => $value) {
    $body .= "--$boundary\r\n";
    $body .= "Content-Disposition: form-data; name=\"$key\"\r\n\r\n";
    $body .= "$value\r\n";
}
$body .= "--$boundary--\r\n";


curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);


$response = curl_exec($ch);


curl_close($ch);


$data=json_decode($response,true);


return $data['id'];
}