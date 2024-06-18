<?php



function get_time($date,$service_id){
    $dateTime = new DateTime($date);
$dateString = $dateTime->format('Y-m-d');


$year = $dateTime->format('Y');
$month = $dateTime->format('m');


$ch = curl_init();


$url = "https://sandbox.booknetic.com/sandboxes/sandbox-saas-6f49ae724d32a0cf3823/wp-admin/admin-ajax.php";


$headers = [
    'Content-Type: multipart/form-data; boundary=----WebKitFormBoundaryZbga5PnV4uBfli1p',
];

$payload = [
    'year' => $year,
    'month' => $month,
    'step' => 'date_time',
    'cart' => '[{"location":-1,"staff":-1,"service_category":"","service":' . $service_id . ',"service_extras":[],"date":"' . $dateString . '","time":"","brought_people_count":0,"recurring_start_date":"","recurring_end_date":"","recurring_times":"{}","appointments":"[]","customer_data":{}}]',
    'action' => 'bkntc_get_data_date_time',
    'tenant_id' => '3',
];


$boundary = '----WebKitFormBoundaryZbga5PnV4uBfli1p';
$body = '';
foreach ($payload as $key => $value) {
    $body .= "--$boundary\r\n";
    $body .= "Content-Disposition: form-data; name=\"$key\"\r\n\r\n";
    $body .= "$value\r\n";
}
$body .= "--$boundary--\r\n";

// cURL seçeneklerini ayarlayın
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);


$response = curl_exec($ch);

curl_close($ch);


$data = json_decode($response, true);
$dates = $data['data']['dates'];


$time_datas = isset($dates[$dateString]) ? $dates[$dateString] : [];
$times = [];

foreach ($time_datas as $time) {
    $times[] = [
        'start_time' => $time['start_time'],
        'end_time' => $time['end_time']
    ];
}

return $times;
}
