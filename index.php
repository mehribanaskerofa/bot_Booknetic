<?php 
require 'config.php';


$webhook="https://meh.cybersecuritylab.az/main.php";

$apiUrl = "https://api.telegram.org/bot$apiToken/setWebhook?url=$webhook";

$response = file_get_contents($apiUrl);
echo $response;