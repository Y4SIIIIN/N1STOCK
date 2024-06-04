<?php
header('Content-Type: application/json');

$telegram = false;
$telegram_ip_ranges = [
    ['lower' => '149.154.160.0', 'upper' => '149.154.175.255'],
    ['lower' => '91.108.4.0', 'upper' => '91.108.7.255']
];
$ip_dec = (float) sprintf("%u", ip2long(getIP()));
foreach($telegram_ip_ranges as $telegram_ip_range) {
    if(!$telegram) {
        $lower_dec = (float) sprintf("%u", ip2long($telegram_ip_range['lower']));
        $upper_dec = (float) sprintf("%u", ip2long($telegram_ip_range['upper']));
        if($ip_dec >= $lower_dec and $ip_dec <= $upper_dec) {
            $telegram = true;
        }
    }
}
if(!$telegram) {
    echo json_encode(array('status' => false), 128).PHP_EOL;
    die;
}
// Read the incoming update
$update = json_decode(file_get_contents('php://input'));
$message = $update->message;
// Extract chat ID and message text
$chat_id = $message->chat->id;
$text = $message->text;
//Extracting Message ID, and user ID
$message_id = $message->message_id;
$from_id = $message->from->id;

$bot_id = /*Enter your Telegram bot ID here*/; 




$startKey = json_encode([
    'keyboard' => [        
        [
            ['text' => "PAY"]
        ], 
    ],
    "resize_keyboard" => true, 'one_time_keyboard' => true
]);
