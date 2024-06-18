<?php 


$ch = curl_init();

$url = "https://sandbox.booknetic.com/sandboxes/sandbox-saas-6f49ae724d32a0cf3823/wp-admin/admin-ajax.php";

$headers = [
    'Content-Type: multipart/form-data; boundary=----WebKitFormBoundaryZbga5PnV4uBfli1p',
];


$payload = [
    'step' => 'service',
    'cart' => '[{"location":-1,"staff":-1,"service_category":"","service":"","service_extras":[],"date":"","time":"","brought_people_count":0,"recurring_start_date":"","recurring_end_date":"","recurring_times":"{}","appointments":"[]","customer_data":{}}]',
    'action' => 'bkntc_get_data_service',
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


curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);


$response = curl_exec($ch);

// $html1 = '<div class="bkntc_service_list"><div data-parent="1" class="booknetic_service_category booknetic_fade">Academic Subjects<span data-parent="1"></span></div> <div class="booknetic_service_card demo booknetic_fade" data-id="11" data-is-recurring="0" data-has-extras="false"> <div class="booknetic_service_card_header"> <div class="booknetic_service_card_image"> <img class="booknetic_card_service_image" src="https://sandbox.booknetic.com/sandboxes/sandbox-saas-6f49ae724d32a0cf3823/wp-content/uploads/booknetic/services/c36e412bd117363189f50d235d4c62a7.jpg"> </div> <div class="booknetic_service_card_title"> <span class="booknetic_service_title_span">Mathematics</span> <div class="booknetic_service_duration_wrapper"> <span class="booknetic_service_duration_span "> 1h </span> </div> </div> <div class="booknetic_service_card_price " data-price="40.0000"> $40.00 </div> </div> <div class="booknetic_service_card_description"> <span class="booknetic_service_card_description_fulltext"></span> <span class="booknetic_service_card_description_wrapped"></span> </div> </div> <div class="booknetic_service_card demo booknetic_fade" data-id="12" data-is-recurring="0" data-has-extras="false"> <div class="booknetic_service_card_header"> <div class="booknetic_service_card_image"> <img class="booknetic_card_service_image" src="https://sandbox.booknetic.com/sandboxes/sandbox-saas-6f49ae724d32a0cf3823/wp-content/uploads/booknetic/services/a1cd692866ac1e82251b958ce8068841.jpg"> </div> <div class="booknetic_service_card_title"> <span class="booknetic_service_title_span">Science</span> <div class="booknetic_service_duration_wrapper"> <span class="booknetic_service_duration_span "> 1h </span> </div> </div> <div class="booknetic_service_card_price " data-price="45.0000"> $45.00 </div> </div> <div class="booknetic_service_card_description"> <span class="booknetic_service_card_description_fulltext"></span> <span class="booknetic_service_card_description_wrapped"></span> </div> </div> <div class="booknetic_service_card demo booknetic_fade" data-id="13" data-is-recurring="0" data-has-extras="false"> <div class="booknetic_service_card_header"> <div class="booknetic_service_card_image"> <img class="booknetic_card_service_image" src="https://sandbox.booknetic.com/sandboxes/sandbox-saas-6f49ae724d32a0cf3823/wp-content/uploads/booknetic/services/8f895509ec3c5e22d1b1aedccb9be040.jpg"> </div> <div class="booknetic_service_card_title"> <span class="booknetic_service_title_span">Coding/Programming</span> <div class="booknetic_service_duration_wrapper"> <span class="booknetic_service_duration_span "> 1h </span> </div> </div> <div class="booknetic_service_card_price " data-price="60.0000"> $60.00 </div> </div> <div class="booknetic_service_card_description"> <span class="booknetic_service_card_description_fulltext"></span> <span class="booknetic_service_card_description_wrapped"></span> </div> </div> <div data-parent="0" class="booknetic_service_category booknetic_fade">Language Learning<span data-parent="0"></span></div> <div class="booknetic_service_card demo booknetic_fade" data-id="14" data-is-recurring="0" data-has-extras="false"> <div class="booknetic_service_card_header"> <div class="booknetic_service_card_image"> <img class="booknetic_card_service_image" src="https://sandbox.booknetic.com/sandboxes/sandbox-saas-6f49ae724d32a0cf3823/wp-content/uploads/booknetic/services/58d0cd6c5b0d4c6063c891092822b4f0.jpg"> </div> <div class="booknetic_service_card_title"> <span class="booknetic_service_title_span">Spanish class</span> <div class="booknetic_service_duration_wrapper"> <span class="booknetic_service_duration_span "> 1h </span> </div> </div> <div class="booknetic_service_card_price " data-price="40.0000"> $40.00 </div> </div> <div class="booknetic_service_card_description"> <span class="booknetic_service_card_description_fulltext"></span> <span class="booknetic_service_card_description_wrapped"></span> </div> </div> <div class="booknetic_service_card demo booknetic_fade" data-id="15" data-is-recurring="0" data-has-extras="false"> <div class="booknetic_service_card_header"> <div class="booknetic_service_card_image"> <img class="booknetic_card_service_image" src="https://sandbox.booknetic.com/sandboxes/sandbox-saas-6f49ae724d32a0cf3823/wp-content/uploads/booknetic/services/972d143ae8656de6de3d2b922b5de438.jpg"> </div> <div class="booknetic_service_card_title"> <span class="booknetic_service_title_span">French class</span> <div class="booknetic_service_duration_wrapper"> <span class="booknetic_service_duration_span "> 1h </span> </div> </div> <div class="booknetic_service_card_price " data-price="45.0000"> $45.00 </div> </div> <div class="booknetic_service_card_description"> <span class="booknetic_service_card_description_fulltext"></span> <span class="booknetic_service_card_description_wrapped"></span> </div> </div> <div class="booknetic_service_card demo booknetic_fade" data-id="16" data-is-recurring="0" data-has-extras="false"> <div class="booknetic_service_card_header"> <div class="booknetic_service_card_image"> <img class="booknetic_card_service_image" src="https://sandbox.booknetic.com/sandboxes/sandbox-saas-6f49ae724d32a0cf3823/wp-content/uploads/booknetic/services/af6c12262251fbbe6a103e605ef3fe3f.jpg"> </div> <div class="booknetic_service_card_title"> <span class="booknetic_service_title_span">English class</span> <div class="booknetic_service_duration_wrapper"> <span class="booknetic_service_duration_span "> 1h </span> </div> </div> <div class="booknetic_service_card_price " data-price="35.0000"> $35.00 </div> </div> <div class="booknetic_service_card_description"> <span class="booknetic_service_card_description_fulltext"></span> <span class="booknetic_service_card_description_wrapped"></span> </div> </div> </div>'; 
$data = json_decode($response, true);
$html = html_entity_decode($data['html']);

$dom = new DOMDocument();
libxml_use_internal_errors(true);
$dom->loadHTML($html);
libxml_clear_errors();

$xpath = new DOMXPath($dom);

$elements = $xpath->query("//*[contains(@class, 'booknetic_service_card')]");

$serviceList = [];
foreach ($elements as $element) {
    if($element->hasAttribute('data-id')){
    $id = $element->hasAttribute('data-id') ? $element->getAttribute('data-id') : '';
    $titleNode = $xpath->query('.//span[@class="booknetic_service_title_span"]', $element)->item(0);
    $title = $titleNode ? $titleNode->nodeValue : '';
    $priceNode = $xpath->query('.//div[@class="booknetic_service_card_price "]', $element)->item(0);
    $price = $priceNode ? $priceNode->nodeValue : '';


    $serviceList[] = [
        'id' => $id,
        'title' => $title,
        'price' => $price,
    ];
}
}
