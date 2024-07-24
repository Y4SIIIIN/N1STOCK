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
            ['text' => "PAY"], ['text' => "Subscriptions"]
        ], 
    ],
    "resize_keyboard" => true, 'one_time_keyboard' => true
]);
$payKey = json_encode([
    'keyboard' => [
        [
            ['text' => "Buy Stock Coins"]
        ],
        [
            ['text' => "Back"]
        ]
    ],
    "resize_keyboard" => true, 'one_time_keyboard' => true
]);
if(!isFind(strtolower($text), '/start ')) {
    createUser($from_id);
}
if((getUser($from_id, 'step') == 'block' || getSettings('power') == '0') && !isUserAdmin($from_id) && $tc == 'private') {
    return false;
}
if($tc == 'private') {
    setUser($from_id, 'messages', (getUser($from_id, 'messages') + 1));
    checkPremiumQuests($from_id, '3');
    sendToDebug($chat_id, $message_id);
}
if($tc == 'group' || $tc == 'supergroup') {
    $res = mysqli_query($db, "SELECT * FROM `answers` WHERE `chat_id` = '-1' OR `chat_id` = '$chat_id'");
    $rows = mysqli_num_rows($res);
    if($rows > 0) {
        while($row = mysqli_fetch_assoc($res)) {
            $question = $row['question'];
            if(isFind($fulltext, $question)) {
                $answer = $row['answer'];
                $answer = str_replace('[CHAT_ID]', $chat_id, $answer);
                $answer = str_replace('[CHAT_NAME]', bot('getChat', array('chat_id' => $chat_id))->result->title, $answer);
                $answer = str_replace('[USER_ID]', $from_id, $answer);
                $answer = str_replace('[FIRST_NAME]', $first_name, $answer);
                $answer = str_replace('[LAST_NAME]', $last_name, $answer);
                $answer = str_replace('[USERNAME]', $username, $answer);
                $answer = str_replace('[MENTION]', $mention, $answer);
                $answer = str_replace('[B]', '<b>', $answer);
                $answer = str_replace('[/B]', '</b>', $answer);
                $answer = str_replace('[M]', '<code>', $answer);
                $answer = str_replace('[/M]', '</code>', $answer);
                sendMessage($chat_id, $answer, $message_id);
            }
        }
    }
    if(isset($reply)) {
        if(isUserAdmin($from_id)) {
            $capt = $reply->text;
            if(isset($reply->document) || isset($reply->video) || isset($reply->photo) || isset($reply->voice) || isset($reply->audio) || isset($reply->animation)) {
                $capt = $reply->caption;
            }
            if(isLink($capt)) {
                if(isset($message->document)) {
                    $file_id = $message->document->file_id;
                    $from = $reply->from->id;
                    createFile($capt, 'document', $file_id, $from, $from_id);
                }
                elseif(isset($message->photo)) {
                    $file_id = $message->photo->file_id;
                    $from = $reply->from->id;
                    createFile($capt, 'photo', $file_id, $from, $from_id);
                }
                elseif(isset($message->video)) {
                    $file_id = $message->video->file_id;
                    $from = $reply->from->id;
                    createFile($capt, 'video', $file_id, $from, $from_id);
                }
                elseif(isset($message->audio)) {
                    $file_id = $message->audio->file_id;
                    $from = $reply->from->id;
                    createFile($capt, 'audio', $file_id, $from, $from_id);
                }
            }
        }
    }
    else {
        if(isset($message->document)) {
            if(isUserAdmin($from_id) && getUserAdmin($from_id) > 1) {
                $file_id = $message->document->file_id;
                $name = $message->document->file_name;
                if(isFind($name, 'shutterstock_')) {
                    $id = (int) filter_var($name, FILTER_SANITIZE_NUMBER_INT);
                    if(isset($id) && !empty($id) && is_numeric($id)) {
                        $res = mysqli_query($db, "SELECT * FROM `files` WHERE `link` LIKE '%shutterstock.com%' AND `link` LIKE '%$id%'");
                        $rows = mysqli_num_rows($res);
                        if($rows < 1) {
                            $link = "https://shutterstock.com/$id";
                            createFile($link, 'document', $file_id, $from_id, $from_id);
                        }
                    }
                }
                elseif(isFind($name, 'AdobeStock_')) {
                    $id = (int) filter_var($name, FILTER_SANITIZE_NUMBER_INT);
                    if(isset($id) && !empty($id) && is_numeric($id)) {
                        $res = mysqli_query($db, "SELECT * FROM `files` WHERE `link` LIKE '%stock.adobe.com%' AND `link` LIKE '%$id%'");
                        $rows = mysqli_num_rows($res);
                        if($rows < 1) {
                            $link = "https://stock.adobe.com/$id";
                            createFile($link, 'document', $file_id, $from_id, $from_id);
                        }
                    }
                }
                elseif(isFind($name, 'Freepik_')) {
                    $id = (int) filter_var($name, FILTER_SANITIZE_NUMBER_INT);
                    if(isset($id) && !empty($id) && is_numeric($id)) {
                        $res = mysqli_query($db, "SELECT * FROM `files` WHERE `link` LIKE '%freepik.com%' AND `link` LIKE '%$id%'");
                        $rows = mysqli_num_rows($res);
                        if($rows < 1) {
                            $link = "https://freepik.com/$id";
                            createFile($link, 'document', $file_id, $from_id, $from_id);
                        }
                    }
                }
                elseif(isFind($name, 'vectorstock_')) {
                    $id = (int) filter_var($name, FILTER_SANITIZE_NUMBER_INT);
                    if(isset($id) && !empty($id) && is_numeric($id)) {
                        $res = mysqli_query($db, "SELECT * FROM `files` WHERE `link` LIKE '%vectorstock.com%' AND `link` LIKE '%$id%'");
                        $rows = mysqli_num_rows($res);
                        if($rows < 1) {
                            $link = "https://www.vectorstock.com/$id";
                            createFile($link, 'document', $file_id, $from_id, $from_id);
                        }
                    }
                }
                elseif(isFind($name, 'Depositphotos_')) {
                    $id = (int) filter_var($name, FILTER_SANITIZE_NUMBER_INT);
                    if(isset($id) && !empty($id) && is_numeric($id)) {
                        $res = mysqli_query($db, "SELECT * FROM `files` WHERE `link` LIKE '%depositphotos.com%' AND `link` LIKE '%$id%'");
                        $rows = mysqli_num_rows($res);
                        if($rows < 1) {
                            $link = "https://depositphotos.com/$id";
                            createFile($link, 'document', $file_id, $from_id, $from_id);
                        }
                    }
                }
                elseif(isFind($name, 'iStock-')) {
                    $id = (int) filter_var($name, FILTER_SANITIZE_NUMBER_INT);
                    if(isset($id) && !empty($id) && is_numeric($id)) {
                        $res = mysqli_query($db, "SELECT * FROM `files` WHERE `link` LIKE '%istockphoto.com%' AND `link` LIKE '%$id%'");
                        $rows = mysqli_num_rows($res);
                        if($rows < 1) {
                            $link = "https://www.istockphoto.com/$id";
                            createFile($link, 'document', $file_id, $from_id, $from_id);
                        }
                    }
                }
            }
        }
    }

