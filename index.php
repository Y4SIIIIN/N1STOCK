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
// Extract message ID, user ID, chat type, first name, last name, username, reply-to message, forward-from ID, and forward-from chat ID
$message_id = $message->message_id;
$from_id = $message->from->id;
$tc = $message->chat->type;
$first_name = $message->from->first_name;
$last_name = $message->from->last_name;
$username = $message->from->username;
$reply = $message->reply_to_message;
$forward_from = $message->forward_from->id;
$channel_forward_chat_id = $message->forward_from_chat->id;

$bot_id = /*Enter your Telegram bot ID here*/; 
// Set the bot's username and name
$bot_username = "N1Stock_Bot";
$bot_name = "N1Stock";

$adds = $message->new_chat_members;
$removed = $message->left_chat_member->id;
// Generate a mention of the user
$mention = mentionUser($from_id);
// extract text or message caption
$fulltext = $message->text;
if(isset($message->document) || isset($message->video) || isset($message->photo) || isset($message->voice) || isset($message->audio) || isset($message->animation)) {
    $fulltext = $message->caption;
}

$startKey = json_encode([
    'keyboard' => [        
        [
            ['text' => "PAY"]
        ], 
    ],
    "resize_keyboard" => true, 'one_time_keyboard' => true
]);
