<?php
require 'config.php';
require 'services.php';
require 'validate.php';
require 'times.php';
require 'confirm.php';

$update = json_decode(file_get_contents('php://input'), true);

if (isset($update['message'])) {
    $chatId = $update['message']['chat']['id'];
    $message = $update['message']['text'];
    $cart = loadCart($chatId);

    if (!isset($cart['step'])) {
        switch ($message) {
            // case '/start':
            default:
                $responseMessage = "Salam!";
                sendMessage($chatId, $responseMessage, $apiToken);

                $keyboard = ['inline_keyboard' => []];
                foreach ($serviceList as $course) {
                    $keyboard['inline_keyboard'][] = [
                        ['text' => str_replace(' ', '', $course['title']) . '-' . $course['price'], 'callback_data' => 'course_' . $course['id']]
                    ];
                }

                sendKeyboard($chatId, "İstədiyiniz servis xidmətini seçin:", $keyboard, $apiToken);

                initializeCart($chatId);
                updateCart($chatId, 'step', 'course_selected');
                break;
            // default:
            //     $responseMessage = "Zəhmət olmasa yuxarıdakı servislərdən birini seçin.";
            //     sendMessage($chatId, $responseMessage, $apiToken);
        }
    } else {
        handleSteps($cart, $chatId, $message, $apiToken);
    }
} elseif (isset($update['callback_query'])) {
    handleCallbackQuery($update['callback_query'], $apiToken);
}

function handleSteps($cart, $chatId, $message, $apiToken) {
    switch ($cart['step']) {
        case 'date_selection':
            handleDateSelection($cart, $chatId, $message, $apiToken);
            break;
        case 'time_selection':
            handleTimeSelection($cart, $chatId, $message, $apiToken);
            break;
        case 'name_selection':
            handleNameSelection($cart, $chatId, $message, $apiToken);
            break;
        case 'last_selection':
            handleLastSelection($cart, $chatId, $message, $apiToken);
            break;
        case 'email_selection':
            handleEmailSelection($cart, $chatId, $message, $apiToken);
            break;
        case 'phone_selection':
            handlePhoneSelection($cart, $chatId, $message, $apiToken);
            break;
    }
}

function handleDateSelection($cart, $chatId, $message, $apiToken) {
    
    if (validateDate($message)) {
        if (!isPastDate($message)) {
            $keyboard_time = ['inline_keyboard' => []];
            $service_id = $cart['service']; 
            $times = get_time($message, $service_id);
            if(!$times){
                sendMessage($chatId, "Başqa tarix seçin.", $apiToken);
            }
            updateCart($chatId, 'date', $message); 
            $group = [];
foreach ($times as $time) {
    $group[] = ['text' => $time['start_time'] . ' - ' . $time['end_time'], 'callback_data' => 'time_' . $time['start_time']];
    if (count($group) == 2) {
        $keyboard_time['inline_keyboard'][] = $group;
        $group = [];
    }
}

if (count($group) > 0) {
    $keyboard_time['inline_keyboard'][] = $group;
}

            sendKeyboard($chatId, "Saatı seçin:", $keyboard_time, $apiToken);
        } else {
            $currentDate = (new DateTime())->format('Y-m-d');
            sendMessage($chatId, "Tarix keçmişdir. Yeni tarix əlavə edin. ", $apiToken);
        }
    } else {
        sendMessage($chatId, "Yanlış tarix formatı. Tarixi YYYY-MM-DD formatında daxil edin.", $apiToken);
    }
}

function handleTimeSelection($cart, $chatId, $message, $apiToken) {
    $startTime = str_replace('time_', '', $message);
    updateCart($chatId, 'time', $startTime);
    updateCart($chatId, 'step', 'name_selection');
    sendMessage($chatId, "Adınızı daxil edin:", $apiToken);
}

function handleNameSelection($cart, $chatId, $message, $apiToken) {
    updateCart($chatId, 'customer_data', ['first_name' => $message]);
    updateCart($chatId, 'step', 'last_selection');
    sendMessage($chatId, "Soyadınızı daxil edin:", $apiToken);
}

function handleLastSelection($cart, $chatId, $message, $apiToken) {
    updateCart($chatId, 'customer_data', ['last_name' => $message]);
    updateCart($chatId, 'step', 'email_selection');
    sendMessage($chatId, "Email ünvaınızı daxil edin:", $apiToken);
}

function handleEmailSelection($cart, $chatId, $message, $apiToken) {
     if (!filter_var($message, FILTER_VALIDATE_EMAIL)) {
        sendMessage($chatId, "Düzgün bir e-posta adresi girmelisiniz. Tekrar yoxlayın.", $apiToken);
        return;
    }
    
    updateCart($chatId, 'customer_data', ['email' => $message]);
    updateCart($chatId, 'step', 'phone_selection');
    sendMessage($chatId, "Telefon nömrənizi daxil edin:", $apiToken);
}

function handlePhoneSelection($cart, $chatId, $message, $apiToken) {
    
     if (!ctype_digit($message)) {
        sendMessage($chatId, "Telefon nömrəniz rəqəmlərdən ibarət olmalıdır. Təkrar yoxlayın.", $apiToken);
        return;
    }
    
    updateCart($chatId, 'customer_data', ['phone' => $message]);

    $keyboard_confirm = ['inline_keyboard' => []];
    $keyboard_confirm['inline_keyboard'][] = [
        ['text' => 'Rezerv et', 'callback_data' => 'ok'],
        ['text' => 'Rədd et', 'callback_data' => 'no']
    ];
    
    $infoTable = "\nAd Soyad: " . $cart['customer_data']['first_name'] . " " . $cart['customer_data']['last_name'] . "\n";
    $infoTable .= "Telefon: " . $message . "\n";
    $infoTable .= "Email: " . $cart['customer_data']['email'] . "\n";

    sendKeyboard($chatId, "Məlumatların düzgünlüyünü təsdiqləyirsizmi?\n$infoTable", $keyboard_confirm, $apiToken);
    
}

function handleCallbackQuery($callbackQuery, $apiToken) {
    $chatId = $callbackQuery['message']['chat']['id'];
    $data = $callbackQuery['data'];

    if (strpos($data, 'course_') === 0) {
        $courseId = str_replace('course_', '', $data);
        updateCart($chatId, 'service', $courseId);
        updateCart($chatId, 'step', 'date_selection');
        sendMessage($chatId, "Tarix daxil edin:  (" . date('Y-m-d') . ")", $apiToken);
    } elseif (strpos($data, 'time_') === 0) {
        $startTime = str_replace('time_', '', $data);
        updateCart($chatId, 'time', $startTime);
        updateCart($chatId, 'step', 'name_selection');
        sendMessage($chatId, "Adınızı daxil edin:", $apiToken);
    } elseif ($data === 'ok') {
        $cart = loadCart($chatId);
        $code = rezerv($cart);
        sendMessage($chatId, "Təbriklər! Qeydiyyatınız tamamlandı.\n\nKod: *$code*\n\nKodu yaddaşda saxlamağı unutmayın.", $apiToken);
        // sendToBackend($cart);
        updateCart($chatId, 'step', null);
    }
     elseif ($data === 'no') {
        deleteCart($chatId);
        sendMessage($chatId, "Yenidən başlayın.", $apiToken);
    }
}

function sendMessage($chatId, $message, $apiToken) {
    $url = "https://api.telegram.org/bot$apiToken/sendMessage?chat_id=$chatId&text=" . urlencode($message);
    file_get_contents($url);
}

function sendKeyboard($chatId, $message, $keyboard, $apiToken) {
    $url = "https://api.telegram.org/bot$apiToken/sendMessage";
    $postData = [
        'chat_id' => $chatId,
        'text' => $message,
        'reply_markup' => json_encode($keyboard)
    ];

    $options = [
        'http' => [
            'header'  => "Content-type: application/json\r\n",
            'method'  => 'POST',
            'content' => json_encode($postData),
        ],
    ];
    $context  = stream_context_create($options);
    file_get_contents($url, false, $context);
}

function initializeCart($chatId) {
    $cart = [
        "location" => -1,
        "staff" => -1,
        "service_category" => "",
        "service" => "",
        "service_extras" => [],
        "date" => "",
        "time" => "",
        "brought_people_count" => 0,
        "recurring_start_date" => "",
        "recurring_end_date" => "",
        "recurring_times" => "{}",
        "appointments" => "[]",
        "customer_data" => [],
        "step" => null
    ];
    file_put_contents("cart_$chatId.json", json_encode($cart));
}

function loadCart($chatId) {
    $file = "cart_$chatId.json";
    if (file_exists($file)) {
        $data = file_get_contents($file);
        return json_decode($data, true);
    }
    return null;
}

function updateCart($chatId, $key, $value) {
    $file = "cart_$chatId.json";
    $cart = loadCart($chatId);
    if ($key === 'customer_data') {
        $cart[$key] = array_merge($cart[$key], $value);
    } else {
        $cart[$key] = $value;
    }
    file_put_contents($file, json_encode($cart));
}

function deleteCart($chatId) {
    $file = "cart_$chatId.json";
    if (file_exists($file)) {
        unlink($file);
    }
}

function sendToBackend($data) {
    //database isleri
}
?>
