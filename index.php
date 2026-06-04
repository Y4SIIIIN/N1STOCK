<?php
# FOR YOUR TELEGRAM BOT TO PROVIDE SERVICES, YOU NEED A SERVER LOCATED OUTSIDE OF IRAN. CURRENTLY, SERVERS INSIDE IRAN CANNOT RELIABLY COMMUNICATE WITH FOREIGN SERVERS.
header('Content-Type: application/json');
require 'config.php';
//Telegram IP range
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

$update = json_decode(file_get_contents('php://input'));
$message = $update->message;
$chat_id = $message->chat->id;
$message_id = $message->message_id;
$from_id = $message->from->id;
$tc = $message->chat->type;
$text = $message->text;
$first_name = $message->from->first_name;
$last_name = $message->from->last_name;
$username = $message->from->username;
$reply = $message->reply_to_message;
$forward_from = $message->forward_from->id;
$channel_forward_chat_id = $message->forward_from_chat->id;
# $bot_id = bot('getMe')->result->id;
# $bot_username = bot('getMe')->result->username;
# $bot_name = bot('getMe')->result->first_name;
$bot_id = 2003652111;
$bot_username = "N1Stock_Bot";
$bot_name = "N1Stock";
$adds = $message->new_chat_members;
$removed = $message->left_chat_member->id;

$mention = mentionUser($from_id);

$fulltext = $message->text;
if(isset($message->document) || isset($message->video) || isset($message->photo) || isset($message->voice) || isset($message->audio) || isset($message->animation)) {
    $fulltext = $message->caption;
}

$startKey = json_encode([
    'keyboard' => [
        [
            ['text' => "LenZzZ"]
        ],
        [
            ['text' => "PAY"], ['text' => "Subscriptions"]
        ],
        [
            ['text' => "Products"], ['text' => "Sellers Panel"], ['text' => "Files"]
        ],
        [
            ['text' => "Invite People"], ['text' => "HELP"], ['text' => "Contact"]
        ],
        [
            ['text' => "API Access"]
        ]
    ],
    "resize_keyboard" => true, 'one_time_keyboard' => true
]);
$hostKey = json_encode([
    'keyboard' => [
        [
            ['text' => "BUY NEW HOST"], ['text' => "My Hosts"]
        ],
        [
            ['text' => "Back"]
        ]
    ],
    "resize_keyboard" => true, 'one_time_keyboard' => true
]);
$sbsKey = json_encode([
    'keyboard' => [
        [
            ['text' => "PREMIUM"]
        ],
        [
            ['text' => "Continental Hotel"], ['text' => "Shared Accounts"]
        ],
        [
            ['text' => "Hosting Servers"]
        ],
        [
            ['text' => "Back"]
        ]
    ],
    "resize_keyboard" => true, 'one_time_keyboard' => true
]);
$payKey = json_encode([
    'keyboard' => [
        [
            ['text' => "Buy Stock Coins"], ['text' => "Transfer Coins"]
        ],
        [
            ['text' => "Wallet"], /*['text' => "🎁"], */['text' => "My Payments"]
        ],
        [
            ['text' => "Back"]
        ]
    ],
    "resize_keyboard" => true, 'one_time_keyboard' => true
]);
$hpKey = json_encode([
    'keyboard' => [
        [
            ['text' => '512MB - $'.HOST_MONTH['1'].'/mo']
        ],
        [
            ['text' => "Back"]
        ]
    ],
    "resize_keyboard" => true, 'one_time_keyboard' => true
]);
$contactsKey = json_encode([
    'keyboard' => [
        [
            ['text' => "Open a Ticket"], ['text' => "My Tickets"]
        ],
        [
            ['text' => "Back"]
        ]
    ],
    "resize_keyboard" => true, 'one_time_keyboard' => true
]);
$prizeKey = json_encode([
    'keyboard' => [
        [
            ['text' => "Higher Lower Game"], ['text' => "Emoji Game"]
        ]
    ],
    "resize_keyboard" => true, 'one_time_keyboard' => true
]);
$gsKey = json_encode([
    'keyboard' => [
        [
            ['text' => "BUY"], ['text' => "Status"]
        ],
        [
            ['text' => "Back"]
        ]
    ],
    "resize_keyboard" => true, 'one_time_keyboard' => true
]);
$shKey = json_encode([
    'keyboard' => [
        [
            ['text' => "CHANGE DETAILS"], ['text' => "CHANGE PRICE"]
        ],
        [
            ['text' => "Add a Shared Account"], ['text' => "Add an Account"]
        ],
        [
            ['text' => "Delete a Shared Account"], ['text' => "Delete an Account"]
        ],
        [
            ['text' => "Check a Shared Account"]
        ],
        [
            ['text' => "Back"]
        ]
    ],
    "resize_keyboard" => true, 'one_time_keyboard' => true
]);
$subKey = json_encode([
    'keyboard' => [
        [
            ['text' => "🚀 x2 Links Per Day"], ['text' => "💸 Free Groups' Subscription"]
        ],
        [
            ['text' => '1 Day | Trial']
        ],
        [
            ['text' => '1 Month | ('.getGSPrice('1').') Stock Coins']
        ],
        [
            ['text' => '3 Months | ('.getGSPrice('3').') Stock Coins']
        ],
        [
            ['text' => '6 Months | ('.getGSPrice('6').') Stock Coins']
        ],
        [
            ['text' => "Back"]
        ]
    ],
    "resize_keyboard" => true, 'one_time_keyboard' => true
]);
$psubKey = json_encode([
    'keyboard' => [
        [
            ['text' => '1 Month | ('.PREMIUM_PRICE["1"].') Stock Coins']
        ],
        [
            ['text' => '3 Months | ('.PREMIUM_PRICE["3"].') Stock Coins']
        ],
        [
            ['text' => '6 Months | ('.PREMIUM_PRICE["6"].') Stock Coins']
        ],
        [
            ['text' => "Back"]
        ]
    ],
    "resize_keyboard" => true, 'one_time_keyboard' => true
]);
$buysKey = json_encode([
    'keyboard' => [
        [
            /*['text' => "Iranian Payment"],*/['text' => "BNB"], ['text' => "UPI"]
        ],
        /*[
            ['text' => "Stripe"]
        ],*/
        [
            ['text' => "Back"]
        ]
    ],
    "resize_keyboard" => true, 'one_time_keyboard' => true
]);
$numKey = json_encode([
    'keyboard' => [
        [
            ['text' => "CHECK AGAIN"]
        ],
        [
            ['text' => "Number is Banned"], ['text' => "Stop the Process"]
        ],
        [
            ['text' => "Finish"], ['text' => "Back"]
        ]
    ],
    "resize_keyboard" => true, 'one_time_keyboard' => true
]);
$adminKey = json_encode([
    'keyboard' => [
        [
            ['text' => "Settings"]
        ],
        [
            ['text' => "Create an Account"], ['text' => "Create Discount Code"]
        ],
        [
            ['text' => "Add Things"], ['text' => 'Delete Things'], ['text' => 'Shared Accounts Things']
        ],
        [
            ['text' => "Block/Unblock a User"], ['text' => "Promote/Demote a User"]
        ],
        [
            ['text' => "Check a Payment"], ['text' => "Check a User"], ['text' => "Set Users' Balance"], ['text' => "Send Message to All"]
        ],
        [
            ['text' => "Back"]
        ]
    ],
    "resize_keyboard" => true, 'one_time_keyboard' => true
]);
$addKey = json_encode([
    'keyboard' => [
        [
            ['text' => "Add a Reminder"]
        ],
        [
            ['text' => "Add a Product"], ['text' => "Add a File"], ['text' => "Add a Number"]
        ],
        [
            ['text' => "Back"]
        ]
    ],
    "resize_keyboard" => true, 'one_time_keyboard' => true
]);
$delKey = json_encode([
    'keyboard' => [
        [
            ['text' => "Delete an Archived File"]
        ],
        [
            ['text' => "Delete a Product"], ['text' => "Delete a File"], ['text' => "Delete a Number"]
        ],
        [
            ['text' => "Back"]
        ]
    ],
    "resize_keyboard" => true, 'one_time_keyboard' => true
]);
$crKey = json_encode([
    'keyboard' => [
        [
            ['text' => "Create a Manually Shutterstock Account"]
        ],
        [
            ['text' => "Create a Random Shutterstock Account"]
        ],
        [
            ['text' => "Back"]
        ]
    ],
    "resize_keyboard" => true, 'one_time_keyboard' => true
]);
$backKey = json_encode([
    'keyboard' => [
        [
            ['text' => "Back"]
        ]
    ],
    "resize_keyboard" => true, 'one_time_keyboard' => true
]);
$skipKey = json_encode([
    'keyboard' => [
        [
            ['text' => "Skip"]
        ]
    ],
    "resize_keyboard" => true, 'one_time_keyboard' => true
]);
$contactKey = json_encode([
    'keyboard'=>[
        [
            ['text' => "Share Contact", 'request_contact' => true]
        ],
        [
            ['text' => "Back"]
        ]
    ],
    "resize_keyboard" => true, 'one_time_keyboard' => true
]);
$prmKey = json_encode([
    'keyboard'=>[
        [
            ['text' => "BUY"]
        ],
        [
            ['text' => "Quests"], ['text' => "Status"], ['text' => "What is Premium?"]
        ],
        [
            ['text' => "Back"]
        ]
    ],
    "resize_keyboard" => true, 'one_time_keyboard' => true
]);
$sellersKey = json_encode([
    'keyboard'=>[
        [
            ['text' => "New Post"], ['text' => "My Posts"], ['text' => "Statics"]
        ],
        [
            ['text' => "Back"]
        ]
    ],
    "resize_keyboard" => true, 'one_time_keyboard' => true
]);
$wareKey = json_encode([
    'keyboard'=>[
        [
            ['text' => "Software"], ['text' => "Hardware"]
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

if(isset($update->edited_message)) {
    /*$chtype = $update->edited_message->chat->type;
    if($chtype != 'private') {
        $edit = $update->edited_message;
        $by = $edit->from->id;
        $chat = $edit->chat->id;
        $msgid = $edit->message_id;
        $full = $edit->text;
        $rank = getChatMember($chat, $by);
        if(isset($edit->document) || isset($edit->video) || isset($edit->photo) || isset($edit->voice) || isset($edit->audio) || isset($edit->animation)) {
            $full = $edit->caption;
        }
        if(isLink($full) && !isUserAdmin($by) && $rank != 'administrator' && $rank != 'creator') {
            deleteMessage($chat, $msgid);
        }
    }*/
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
        if(isUserAdmin($from_id)/* || (getChatMember($chat_id, $from_id) == 'administrator' || getChatMember($chat_id, $from_id) == 'creator')*/) {
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
                elseif(isFind($name, 'Gettyimages_')) {
                    $id = (int) filter_var($name, FILTER_SANITIZE_NUMBER_INT);
                    if(isset($id) && !empty($id) && is_numeric($id)) {
                        $res = mysqli_query($db, "SELECT * FROM `files` WHERE `link` LIKE '%gettyimages.com%' AND `link` LIKE '%$id%'");
                        $rows = mysqli_num_rows($res);
                        if($rows < 1) {
                            $link = "https://www.gettyimages.com/$id";
                            createFile($link, 'document', $file_id, $from_id, $from_id);
                        }
                    }
                }
                elseif(isFind($name, 'dreamstime_')) {
                    $id = (int) filter_var($name, FILTER_SANITIZE_NUMBER_INT);
                    if(isset($id) && !empty($id) && is_numeric($id)) {
                        $res = mysqli_query($db, "SELECT * FROM `files` WHERE `link` LIKE '%dreamstime.com%' AND `link` LIKE '%$id%'");
                        $rows = mysqli_num_rows($res);
                        if($rows < 1) {
                            $link = "https://www.dreamstime.com/$id";
                            createFile($link, 'document', $file_id, $from_id, $from_id);
                        }
                    }
                }
                elseif(isFind($name, 'Yellowimages_')) {
                    $id = (int) filter_var($name, FILTER_SANITIZE_NUMBER_INT);
                    if(isset($id) && !empty($id) && is_numeric($id)) {
                        $res = mysqli_query($db, "SELECT * FROM `files` WHERE `link` LIKE '%yellowimages.com%' AND `link` LIKE '%$id%'");
                        $rows = mysqli_num_rows($res);
                        if($rows < 1) {
                            $link = "https://yellowimages.com/$id";
                            createFile($link, 'document', $file_id, $from_id, $from_id);
                        }
                    }
                }
                elseif(isFind($name, '123rf_')) {
                    $id = (int) filter_var($name, FILTER_SANITIZE_NUMBER_INT);
                    if(isset($id) && !empty($id) && is_numeric($id)) {
                        $res = mysqli_query($db, "SELECT * FROM `files` WHERE `link` LIKE '%123rf.com%' AND `link` LIKE '%$id%'");
                        $rows = mysqli_num_rows($res);
                        if($rows < 1) {
                            $link = "https://123rf.com/$id";
                            createFile($link, 'document', $file_id, $from_id, $from_id);
                        }
                    }
                }
                elseif(isFind($name, 'iconscout_')) {
                    $id = (int) filter_var($name, FILTER_SANITIZE_NUMBER_INT);
                    if(isset($id) && !empty($id) && is_numeric($id)) {
                        $res = mysqli_query($db, "SELECT * FROM `files` WHERE `link` LIKE '%iconscout.com%' AND `link` LIKE '%$id%'");
                        $rows = mysqli_num_rows($res);
                        if($rows < 1) {
                            $link = "https://iconscout.com/$id";
                            createFile($link, 'document', $file_id, $from_id, $from_id);
                        }
                    }
                }
                elseif(isFind($name, 'pngtree_')) {
                    $id = (int) filter_var($name, FILTER_SANITIZE_NUMBER_INT);
                    if(isset($id) && !empty($id) && is_numeric($id)) {
                        $res = mysqli_query($db, "SELECT * FROM `files` WHERE `link` LIKE '%pngtree.com%' AND `link` LIKE '%$id%'");
                        $rows = mysqli_num_rows($res);
                        if($rows < 1) {
                            $link = "https://pngtree.com/$id";
                            createFile($link, 'document', $file_id, $from_id, $from_id);
                        }
                    }
                }
            }
        }
    }

    if(isLink($fulltext)) {
        $send = true;
        $linksinfo = getTodayLinks($from_id, $chat_id);
        $links = $linksinfo['links'];
        $credits = $linksinfo['credits'];
        $gadmin = isChatAdmin($from_id, $chat_id);
        $rank = getChatMember($chat_id, $from_id);
        $channel = getSettings('post_channel');
        $crank = getChatMember($channel, $from_id);
        $maxlinks = getChats($chat_id, 'links');
        $trials = getChats($chat_id, 'trials');
        $domain = getDomain($fulltext);
        $double = 1;
        $credit = 1;
        if(getUser($from_id, 'double') > '0') {
            $double = getUser($from_id, 'double');
        }
        if(isCreditExist($domain)) {
            $credit = getCredit($domain, 'price');
        }
        if($crank == 'left' && !isUserAdmin($from_id) && !$gadmin) {
            $link = createChatInviteLink($channel, null);
            if(isLink($link)) {
                deleteMessage($chat_id, $message_id);
                sendMessage($chat_id, "[$mention]\n<b>You should join our channel to send links here</b>", -1, retIKey14($link));
                $send = false;
                return false;
            }
        }
        $left = strtotime('tomorrow') - time();
        $data = secondsToTime($left);
        $hours = $data['hours'];
        $minutes = $data['minutes'];
        $seconds = $data['seconds'];
        $allowed = ($maxlinks * $double);
        $action = ($allowed - $credits);
        $extra = "\n";
        if($action > 0) {
            $extra = "\n<b>Remaining Credits:</b> <code>$action</code><b>/</b><code>$allowed</code> <b>(Link's Credit: </b><code>$credit</code><b>)</b>\n";
        }
        $trial_sent = false;
        if($trials > '0') {
            if((($credits + $credit) > $trials) && !isUserAdmin($from_id) && !$gadmin) {
                $send = false;
            }
            else {
                addLink($chat_id, $from_id, $fulltext);
                $trial_sent = true;
            }
        }
        if(!$trial_sent && $maxlinks > '0') {
            if(!isChatVIP($chat_id) && isFSubExpired($from_id) && !isPremium($from_id)) {
                deleteMessage($chat_id, $message_id);
                sendMessage($chat_id, "[$mention]\n<b>You have no subscription inside the bot , Premium users can send more links</b>");
                $send = false;
                return false;
            }
            if((($credits + $credit) > ($maxlinks * $double)) && !isUserAdmin($from_id) && !$gadmin) {
                deleteMessage($chat_id, $message_id);
                sendMessage($chat_id, "[$mention]\n<b>You are allowed to send $allowed ".($allowed == 1 ? "link" : "links")." every 24 hours in this chat.</b>".$extra."Try again in <code>$hours hours</code> : <code>$minutes minutes</code> : <code>$seconds seconds</code>");
                $send = false;
            }
            else {
                addLink($chat_id, $from_id, $fulltext);
                $send = true;
            }
        }
        if($send) {
            sleep(3);
            if(isMessageExist($chat_id, $message_id)) {
                sendFile($from_id, $chat_id, $fulltext, $message_id);
            }
        }
    }

    if(isNewMember($bot_id, $adds)) {
        if(isUserAdmin($from_id) && getUserAdmin($from_id) > 1) {
            addChat($chat_id, $from_id);
        }
        else {
            if(!isChatVIP($chat_id) || !isChatExist($chat_id)) {
                sendMessage($chat_id, "<b>*</b> I would rather not to be here");
                deleteGroup($chat_id);
            }
        }
    }
    
    addChat($chat_id, -1);

    if($removed == $bot_id) {
        deleteGroup($chat_id);
    }

    if(isChatVIP($chat_id)) {
        if(isUserExist($removed) && !isSubExpired($removed)) {
            $link = createChatInviteLink($chat_id, getUser($removed, 'subscription'));
            sendMessage($removed, "<code>*</code> You left a VIP group <b>with active subscription</b>.\nJoin back with link below whenever you want", -1, retIKey11($link));
            setUser($removed, 'alarm', '0');
        }
        $data = objectToArrays($adds);
        if(sizeof($data) > 0) {
            for($i = 0; $i < sizeof($data); $i ++) {
                $id = $data[$i]['id'];
                if((isSubExpired($id) || !isUserExist($id)) && $id != $bot_id) {
                    $mention = mentionUser($id);
                    banChatMember($chat_id, $id);
                    setUser($id, 'alarm', '0');
                    sendMessage($chat_id, "<code>*</code> User $mention joined group <b>without any group subscriptions</b>. (<b>Kicked</b>)");
                }
            }
        }
        if((!isUserExist($from_id) || isSubExpired($from_id)) && $from_id != $bot_id) {
            banChatMember($chat_id, $from_id);
            sendMessage($chat_id, "<code>*</code> User $mention's subscription expired. (<b>Kicked</b>)");
        }
    }
    if(strtolower($text) == '/install' || strtolower($text) == strtolower("/install@$bot_username")) {
        if(isUserAdmin($from_id) && getUserAdmin($from_id) > 1) {
            if(getChatMember($chat_id, $bot_id) == 'administrator') {
                if(isChatVIP($chat_id)) {
                    sendMessage($chat_id, "This chat is already installed as a VIP chat", $message_id);
                }
                else {
                    setChats($chat_id, 'vip', '1');
                    sendMessage($chat_id, "This chat is now installed as a VIP chat", $message_id);
                }
            }
            else {
                sendMessage($chat_id, "Robot is not admin in this chat\nTry again when you made me an admin of this chat", $message_id);
            }
        }
    }
    if(strtolower($text) == '/uninstall' || strtolower($text) == strtolower("/uninstall@$bot_username")) {
        if(isUserAdmin($from_id) && getUserAdmin($from_id) > 1) {
            sendMessage($chat_id, "Uninstalled chat", $message_id);
            deleteGroup($chat_id);
        }
    }
    if(strtolower($text) == '/api' || strtolower($text) == strtolower("/api@$bot_username")) {
        if(isUserAdmin($from_id) && getUserAdmin($from_id) > 1) {
            if(getChats($chat_id, 'api') < '1' || isChatVIP($chat_id)) {
                setChats($chat_id, 'api', '1');
            }
            else {
                setChats($chat_id, 'api', '0');
            }
            sendMessage($chat_id, "<b>*</b> This chat is <b>".(getChats($chat_id, 'api') < '1' ? "not accepting" : "accepting")."</b> API things ".(getChats($chat_id, 'api') < '1' ? "anymore" : "from now on"), $message_id);
        }
    }
}

if($update->callback_query) {
    $id = $update->callback_query->id;
	$tc = $update->callback_query->message->chat->type;
    $chatid = $update->callback_query->message->chat->id;
	$fromid = $update->callback_query->from->id;
	$firstname = $update->callback_query->from->first_name;
	$lastname = $update->callback_query->from->last_name;
    $messageid = $update->callback_query->message->message_id;
	$cusername = $update->callback_query->from->username;
    $data = $update->callback_query->data;
    $mention = mentionUser($fromid);
    if(isFind($data, 'acc_')) {
        if(isUserAdmin($fromid)) {
            $target = str_replace('acc_', '', explode(':', $data)[0]);
            $price = explode(':', $data)[1];
            $pid = explode(':', $data)[2];
            $amount = explode(':', $data)[3];
            if(isPaymentExist($pid)) {
                if(getPayment($pid, 'status') == '0') {
                    $mention2 = mentionUser($target);
                    if(isUserExist($target)) {
                        setPayment($pid, 'status', '1');
                        setUser($target, 'balance', (getUser($target, 'balance') + $amount));
                        sendMessage($target, "Your #Payment accepted\n<code>$amount</code> Stock Coins added to your wallet");
                        answerCallbackQuery($id, "Payment accepted. $amount Stock Coins added to $target's wallet");
                        sendAdminMessage("#admin\nUser $mention2's #Payment(dbID:<code>$pid</code>) has accepted by admin $mention");
                        checkReferralPrize($target, $amount);
                    }
                    else {
                        answerCallbackQuery($id, "An error occurred");
                    }
                }
                else {
                    answerCallbackQuery($id, "This payment is already accepted");
                }
            }
            else {
                answerCallbackQuery($id, "An error occurred");
            }
        }
        else {
            answerCallbackQuery($id, "An error occurred");
        }
        return true;
    }
    elseif(isFind($data, 'rej_')) {
        if(isUserAdmin($fromid)) {
            $target = str_replace('rej_', '', $data);
            $dbid = explode(':', $target)[1];
            $target = explode(':', $target)[0];
            if(isPaymentExist($dbid)) {
                if(getPayment($dbid, 'status') == '0') {
                    $mention2 = mentionUser($target);
                    setPayment($dbid, 'status', '-1');
                    answerCallbackQuery($id, "Payment rejected");
                    sendMessage($target, "Your #Payment rejected");
                    sendAdminMessage("#admin\nUser $mention2's #Payment(dbID:<code>$dbid</code>) has rejected by admin $mention");
                }
                else {
                    answerCallbackQuery($id, "This payment is already accepted/rejected");
                }
            }
            else {
                answerCallbackQuery($id, "An error occurred");
            }
        }
        else {
            answerCallbackQuery($id, "An error occurred");
        }
        return true;
    }
    elseif(isFind($data, 'accp_')) {
        if(isUserAdmin($fromid)) {
            $postid = str_replace('accp_', '', $data);
            $price = explode(':', $postid)[1];
            $postid = explode(':', $postid)[0];
            if(isPostExist($postid)) {
                $target = getPost($postid, 'addby');
                $mention2 = mentionUser($target);
                if(isUserExist($target)) {
                    if(getPost($postid, 'sent') == '0') {
                        setPost($postid, 'admin', $fromid);
                        $post = getPost($postid, 'link');
                        $ftype = strtoupper(getPost($postid, 'ftype'));
                        $caption = "🖼 $ftype#$postid\n🌐 https://instagram.com/p/$post/";
                        if(getPost($postid, 'ftype') == 'photo') {
                            $info = sendPhoto(getSettings('post_channel'), getPost($postid, 'fid'), $caption, -1, retIKey4($postid));
                            setPost($postid, 'sent', '1');
                            answerCallbackQuery($id, "Photo has sent to channel successfully");
                            sendMessage($target, "Your post has accepted", -1, retIKey3($info->result->message_id));
                            sendAdminMessage("#admin\nAdmin $mention has accepted $mention2's photo post");
                        }
                        elseif(getPost($postid, 'ftype') == 'video') {
                            $info = sendVideo(getSettings('post_channel'), getPost($postid, 'fid'), $caption, -1, retIKey4($postid));
                            setPost($postid, 'sent', '1');
                            answerCallbackQuery($id, "Video has sent to channel successfully");
                            sendMessage($target, "Your post has accepted", -1, retIKey3($info->result->message_id));
                            sendAdminMessage("#admin\nAdmin $mention has accepted $mention2's video post");
                        }
                        else {
                            answerCallbackQuery($id, "An error has occurred during accepting this post");
                            setPost($postid, 'sent', '-1');
                            setUser($target, 'balance', (getUser($target, 'balance') + $price));
                        }
                    }
                    else {
                        answerCallbackQuery($id, "This post is already accepted/rejected");
                    }
                }
                else {
                    answerCallbackQuery($id, "An error has occurred during accepting this post");
                }
            }
            else {
                answerCallbackQuery($id, "An error has occurred");
            }
        }
        return true;
    }
    elseif(isFind($data, 'rejp_')) {
        if(isUserAdmin($fromid)) {
            $postid = str_replace('rejp_', '', $data);
            $price = explode(':', $postid)[1];
            $postid = explode(':', $postid)[0];
            if(isPostExist($postid)) {
                $target = getPost($postid, 'addby');
                $mention2 = mentionUser($target);
                if(isUserExist($target)) {
                    if(getPost($postid, 'sent') == '0') {
                        setPost($postid, 'sent', '-1');
                        setPost($postid, 'admin', $fromid);
                        answerCallbackQuery($id, "Post has rejected successfully");
                        sendAdminMessage("#admin\nAdmin $mention has rejected user $mention2's post");
                        setUser($target, 'balance', (getUser($target, 'balance') + $price));
                        sendMessage($target, "Your post has rejected\n<b>$price</b> Stock Coins refunded to your account wallet");
                    }
                    else {
                        answerCallbackQuery($id, "This post is already accepted/rejected");
                    }
                }
                else {
                    answerCallbackQuery($id, "An error has occurred during accepting this post");
                }
            }
            else {
                answerCallbackQuery($id, "An error has occurred");
            }
        }
        return true;
    }
    elseif(isFind($data, 'dislike_')) {
        $post = str_replace('dislike_', '', $data);
        if(isPostExist($post)) {
            setPost($post, 'dislikes', (getPost($post, 'dislikes') + 1));
            editMessageReplyMarkup($chatid, $messageid, retIKey4($post));
        }
        return true;
    }
    elseif(isFind($data, 'like_')) {
        $post = str_replace('like_', '', $data);
        if(isPostExist($post)) {
            setPost($post, 'likes', (getPost($post, 'likes') + 1));
            editMessageReplyMarkup($chatid, $messageid, retIKey4($post));
        }
        return true;
    }
    elseif(isFind($data, 'dmsg_')) {
        $target = str_replace('dmsg_', '', $data);
        $chat = explode(':', $target)[0];
        $msgid = explode(':', $target)[1];
        if(isUserAdmin($fromid)) {
            if(isUserExist($chat)) {
                $info = deleteMessage($chat, $msgid);
                if($info->ok) {
                    answerCallbackQuery($id, "Sent message has deleted for user $chat");
                }
                else {
                    answerCallbackQuery($id, "Message is not exist in their chat");
                }
            }
            else {
                answerCallbackQuery($id, "An error has occurred");
            }
        }
        return true;
    }
    elseif(isFind($data, 'refund_')) {
        $d = str_replace('refund_', '', $data);
        $target = explode(':', $d)[0];
        $payment = explode(':', $d)[1];
        $price = explode(':', $d)[2];
        if(isUserAdmin($fromid)) {
            if(getUserAdmin($fromid) > 1) {
                $refunded = getPayment($payment, 'refunded');
                if($refunded == '0') {
                    setPayment($payment, 'refunded', '1');
                    setUser($target, 'balance', (getUser($target, 'balance') + $price));
                    sendMessage($target, "<b>$price Stock Coins</b> has refunded #Payment(dbID:<code>$payment</code>)");
                    answerCallbackQuery($id, "$price Stock Coins has refunded successfully");
                }
                else {
                    answerCallbackQuery($id, "Payment is already refunded");
                }
            }
        }
    }
    elseif(isFind($data, 'msg_')) {
        $target = str_replace('msg_', '', $data);
        if(isUserAdmin($fromid)) {
            if(isUserExist($target)) {
                $mention = mentionUser($target);
                setUser($fromid, 'step', "msg_$target");
                sendMessage($chatid, "Send me your message for sending to user $mention", $messageid, $backKey);
            }
            else {
                answerCallbackQuery($id, "An error has occurred");
            }
        }
        return true;
    }
    elseif(isFind($data, 'prev_')) {
        $info = str_replace('prev_', '', $data);
        $dbid = explode(':', $info)[0];
        $page = explode(':', $info)[1];
        $prev = ($page - 1);
        $pages = sizeof(getPayments($fromid, 0));
        $payments = getPayments($fromid, $dbid, 0);
        if(sizeof($payments) > 0 && $payments != '-1') {
            $payment = $payments[(sizeof($payments) - 1)];
            $iid = getPayment($payment, 'id');
            $product_id = getPayment($payment, 'product_id');
            $pid = explode('_', $product_id)[1];
            $product = "NOT AVAILABLE";
            $cost = getPayment($payment, 'cost');
            $discount = getPayment($payment, 'discount');
            $email = getPayment($payment, 'email');
            $status = getPayment($payment, 'status');
            $time = getPayment($payment, 'time');
            $time = date('Y-m-d H:i:s', $time);
            $price = "$cost";
            if($price < '1') {
                $price = "FREE";
            }
            if($discount > '0') {
                $price .= " ($discount% DISCOUNT)";
            }
            if($email == '-1') {
                $email = "NOT ENTERED";
            }
            if($status == '-1') {
                $status = "NOT CONFIRMED";
            }
            else {
                $status = "CONFIRMED";
            }
            if(isFind($product_id, 'PROD_')) {
                if(mysqli_num_rows(mysqli_query($db, "SELECT * FROM `products` WHERE `id` = '$pid'")) > 0) {
                	while($row = mysqli_fetch_assoc($res)) {
                	    $product = "Product ".$row['name'];
                	}
                }
            }
            elseif(isFind($product_id, 'BSC_')) {
                $product = "$cost Stock Coins Requested";
            }
            elseif(isFind($product_id, 'BSCT_')) {
                $product = "$cost Stock Coins Toman Requested";
            }
            elseif(isFind($product_id, 'BSCPY_')) {
                $product = "$cost Stock Coins PayPal Requested";
            }
            elseif(isFind($product_id, 'BSCW_')) {
                $product = "$cost Stock Coins WebMoney Requested";
            }
            elseif(isFind($product_id, 'BSCU_')) {
                $product = "$cost Stock Coins USDT Requested";
            }
            elseif(isFind($product_id, 'PST_')) {
                $product = "Instagram Post";
            }
            elseif(isFind($product_id, 'GS_')) {
                $product = "Group Subscription";
            }
            elseif(isFind($product_id, 'PREMIUM_')) {
                $product = "PREMIUM";
            }
            elseif(isFind($product_id, 'TGS_')) {
                $product = "Trial Group Subscription";
            }
            elseif(isFind($product_id, 'DBL_')) {
                $product = "Made Double Links Can Send Per Day";
            }
            elseif(isFind($product_id, 'FTG_')) {
                if(mysqli_num_rows(mysqli_query($db, "SELECT * FROM `footage` WHERE `id` = '$pid'")) > 0) {
                	while($row = mysqli_fetch_assoc($res)) {
                	    $product = "File ".$row['name'];
                	}
                }
            }
            elseif(isFind($product_id, 'NUM_')) {
                $product = "Virtual Number";
            }
            elseif(isFind($product_id, 'SHACC_')) {
                $product = "Shared Account";
            }
            editMessageText($chatid, $messageid, "Payments you made\n\n____________________________\nPaymentID<code>#$iid</code>\nInformation: <code>$product</code>\nStatus: <code>$status</code>\nPrice: <code>$price</code>\nEmail: <code>$email</code>\nTime: <code>$time</code>\n____________________________\n\nPage: <code>$prev</code>/<code>$pages</code>", retIKey8($iid, $prev));
        }
        else {
            answerCallbackQuery($id, "There is nothing there");
        }
        return true;
    }
    elseif(isFind($data, 'nxt_')) {
        $info = str_replace('nxt_', '', $data);
        $dbid = explode(':', $info)[0];
        $page = explode(':', $info)[1];
        $next = ($page + 1);
        $pages = sizeof(getPayments($fromid, 0));
        $payments = getPayments($fromid, $dbid);
        if(sizeof($payments) > 0 && $payments != '-1') {
            $payment = $payments[0];
            $iid = getPayment($payment, 'id');
            $product_id = getPayment($payment, 'product_id');
            $pid = explode('_', $product_id)[1];
            $product = "NOT AVAILABLE";
            $cost = getPayment($payment, 'cost');
            $discount = getPayment($payment, 'discount');
            $email = getPayment($payment, 'email');
            $status = getPayment($payment, 'status');
            $time = getPayment($payment, 'time');
            $time = date('Y-m-d H:i:s', $time);
            $price = "$cost Stock Coins";
            if($discount > '0') {
                $price .= " ($discount% DISCOUNT)";
            }
            if($email == '-1') {
                $email = "NOT ENTERED";
            }
            if($status == '-1') {
                $status = "NOT CONFIRMED";
            }
            else {
                $status = "CONFIRMED";
            }
            if(isFind($product_id, 'PROD_')) {
                if(mysqli_num_rows(mysqli_query($db, "SELECT * FROM `products` WHERE `id` = '$pid'")) > 0) {
                	while($row = mysqli_fetch_assoc($res)) {
                	    $product = $row['name'];
                	}
                }
            }
            elseif(isFind($product_id, 'BSC_')) {
                $product = "$cost Stock Coins Requested";
            }
            elseif(isFind($product_id, 'BSCT_')) {
                $product = "$cost Stock Coins Toman Requested";
            }
            elseif(isFind($product_id, 'BSCPY_')) {
                $product = "$cost Stock Coins PayPal Requested";
            }
            elseif(isFind($product_id, 'BSCW_')) {
                $product = "$cost Stock Coins WebMoney Requested";
            }
            elseif(isFind($product_id, 'BSCU_')) {
                $product = "$cost Stock Coins USDT Requested";
            }
            elseif(isFind($product_id, 'PST_')) {
                $product = "Instagram Post";
            }
            elseif(isFind($product_id, 'GS_')) {
                $product = "Group Subscription";
            }
            elseif(isFind($product_id, 'TGS_')) {
                $product = "Trial Group Subscription";
            }
            elseif(isFind($product_id, 'DBL_')) {
                $product = "Made Double Links Can Send Per Day";
            }
            elseif(isFind($product_id, 'FTG_')) {
                if(mysqli_num_rows(mysqli_query($db, "SELECT * FROM `footage` WHERE `id` = '$pid'")) > 0) {
                	while($row = mysqli_fetch_assoc($res)) {
                	    $product = "File ".$row['name'];
                	}
                }
            }
            elseif(isFind($product_id, 'NUM_')) {
                $product = "Virtual Number";
            }
            elseif(isFind($product_id, 'SHACC_')) {
                $product = "Shared Account";
            }
            editMessageText($chatid, $messageid, "Payments you made\n\n____________________________\nPaymentID<code>#$iid</code>\nInformation: <code>$product</code>\nStatus: <code>$status</code>\nPrice: <code>$price</code>\nEmail: <code>$email</code>\nTime: <code>$time</code>\n____________________________\n\nPage: <code>$next</code>/<code>$pages</code>", retIKey8($iid, $next));
        }
        else {
            answerCallbackQuery($id, "There is nothing there");
        }
        return true;
    }
    elseif($data == 'chg_credits') {
        if(isUserAdmin($fromid)) {
            if(getUserAdmin($fromid) > 1) {
                $message = "";
                $res = mysqli_query($db, "SELECT * FROM `credits`");
                $rows = mysqli_num_rows($res);
                if($rows > 0) {
            		while($row = mysqli_fetch_assoc($res)) {
            		    $message .= "\n<code>".$row['domain']."</code> - <code>".$row['price']."</code>";
            		}
            		sendMessage($chatid, "List of Domains with their custom credits:\n____________________________________$message\n____________________________________");
                }
                setUser($fromid, 'step', 'chg_credits');
                sendMessage($chatid, "Send me a link or domain from site you want to change their credit", $messageid, $backKey);
            }
        }
    }
    elseif($data == 'chg_answers') {
        if(isUserAdmin($fromid) && getUserAdmin($fromid) > 1) {
            $res = mysqli_query($db, "SELECT * FROM `answers`");
            $rows = mysqli_num_rows($res);
            if($rows > 0) {
                $message = "";
                while($row = mysqli_fetch_assoc($res)) {
                    $id = $row['id'];
                    $question = $row['question'];
                    $answer = $row['answer'];
                    $chat_id = $row['chat_id'];
                    $chatname = "<b>All Chats</b>";
                    if($chat_id != '-1') {
                        $chatname = "Specified for <b>".bot('getChat', array('chat_id' => $chat_id))->result->title."</b>";
                    }
                    if(strlen($question) > 16) {
                        $question = str_split($question, 16)[0]."...";
                    }
                    if(strlen($answer) > 32) {
                        $answer = str_split($answer, 32)[0]."...";
                    }
                    $message .= "\n(<code>#$id</code>) <b>Q:</b> <code>$question</code> | <b>A:</b> <code>$answer</code> (Type: $chatname)";
                }
                $get = sendMessage($chatid, "List of Answers:\n____________________________________$message\n____________________________________");
                setUser($fromid, 'step', 'delans');
                sendMessage($chatid, "If you want to delete an answer, send me the AnswerID from the list above", $get->result->message_id, $backKey);
            }
            else {
                sendMessage($chatid, "There are no answers set", $messageid);
            }
        }
    }
    elseif($data == 'chg_reminders') {
        if(isUserAdmin($fromid)) {
            $res = mysqli_query($db, "SELECT * FROM `reminders` WHERE `endtime` > '".time()."' AND `noticed` = '0' AND `addby` = '$fromid'");
            $rows = mysqli_num_rows($res);
            if($rows > 0) {
                $count = 0;
                $message = "You have <b>$rows</b> active reminder".($rows > 1 ? "s" : "")."\n\n______________________________________\n";
            	while($row = mysqli_fetch_assoc($res)) {
            	    $count ++;
            	    $id = $row['id'];
            	    $details = $row['details'];
            	    $endtime = $row['endtime'];
            	    $name = explode("\n", $details)[0];
            	    if(strlen($name) > 10) {
            	        $name = str_split($name, 10)[0]."...";
            	    }
            	    $data = secondsToTime(($endtime - time()));
                    $days = $data['days'];
                    $hours = $data['hours'];
                    $minutes = $data['minutes'];
                    $seconds = $data['seconds'];
                    $timestr = "<code>$days</code>d : <code>$hours</code>h : <code>$minutes</code>m : <code>$seconds</code>s";
                    if($days < 1) {
                        $timestr = "<code>$hours</code>h : <code>$minutes</code>m : <code>$seconds</code>s";
                    }
            	    $message .= "$count. <b>$name</b> ($timestr remaining) [<b>#$id</b>]\n";
            	}
            	$message .= "______________________________________";
            	setUser($fromid, 'step', '-1');
                sendMessage($chatid, $message, $messageid, $adminKey);
            }
            else {
                setUser($fromid, 'step', '-1');
                sendMessage($chatid, "You don't have any active reminders", $messageid, $adminKey);
            }
        }
    }
    elseif($data == 'chg_income') {
        if(isUserAdmin($fromid)) {
            if(getUserAdmin($fromid) > 1) {
                setUser($fromid, 'step', '-1');
                $aincome = 0;
                $gincome = 0;
                $pincome = 0;
                $asub = 0;
                $res = mysqli_query($db, "SELECT * FROM `payments` WHERE (`product_id` LIKE '%BSC_%' OR (`product_id` LIKE '%GS_%' AND `product_id` NOT LIKE '%TGS_%') OR `product_id` LIKE '%PROD_%') AND `status` = '1' AND `discount` < '100'");
                $rows = mysqli_num_rows($res);
                if($rows > 0) {
            		while($row = mysqli_fetch_assoc($res)) {
            		    if(isUserAdmin($row['user_id']) && getUserAdmin($row['user_id']) > 1) {
            		        continue;
            		    }
            		    $p = $row['product_id'];
                		$num = 0;
                	    if($row['discount'] > '0') {
                		    $num = getPercent($row['cost'], $row['discount']);
                		}
                		if(isFind($p, 'GS_')) {
                		    $gincome = ($gincome + $row['cost']);
                		}
                		elseif(isFind($p, 'PROD_')) {
                		    $pincome = ($pincome + $row['cost']);
                		}
                		else {
                		    $aincome = (int) (($aincome + $row['cost']) - $num);
                		}
            		}
                }
                $res = mysqli_query($db, "SELECT * FROM `users` WHERE `subscription` > '".time()."'");
                $asub = mysqli_num_rows($res);
                sendMessage($chatid, "Income Status\n\n________________________\nUsers Paid: <code>".'$'."$aincome</code>\nPaid for Group Subscription: <code>".'$'."$gincome</code>\nPaid for Products: <code>".'$'."$pincome</code>\nUsers have active Group Subscription: <code>$asub</code>\n________________________", $messageid, $adminKey);
            }
        }
    }
    elseif($data == 'chg_ahelp') {
        if(isUserAdmin($fromid)) {
            if(getUserAdmin($fromid) > 1) {
                setUser($fromid, 'step', '-1');
                sendMessage($chatid, "- /panel\nOpens admin panel\n\n- /report [USER_ID]\nShows a user's API subscription status\n\n- /query [SQL Query]\nSends SQL query to the server\n\n- /sub [USER_ID] [TIME]\nAdds time to a user's group subscription expire time\n\n- /apisub [USER_ID] [TIME] [MAX_USES] [DOMAIN]\nSets a user's api subscription\n\n- /csmta_[MSGID]\nCancels a message from sending to all robot users\n\n- /leave_[CHATID]\nLeaves from a chat\n\n- /delarch_[DBID]\nDeletes the files' archive by their database id\n\n- /delfile_[DBID]\nDeletes a single file from an archive\n\n- /img [Image Details]\nGenerates an AI image about what you want\n\n- /links [USER_ID <b>or</b> REPLY]\nChecks how many a user sent links today in current chat and all chats (This command works in chats)\n\n- /ans [~Question~] [Answer]\nSets an answer for a question a user ask in groups (This command works in chats & private)\n\n- /del [COUNT]\nDeletes messages in the chat by count you entered between 1 to 50 (This command works in chats)\n\n- /ml [MAX_LINKS]\nSets maximum number of links can users send in group (This command works in chats)\n\n- /trials [MAX_LINKS]\nSets maximum number of links can users send in group as trial (This command works in chats)\n\n- /install\nSets a chat as a VIP group (This command works in chats)\n\n- /uninstall\nRemoves a chat from VIP groups (This command works in chats)\n\n- /chat\nShows the current chat id information (This command works in chats)\n\n- /id\nShows replied user's information (This command works in chats)\n\n- /promoteme\nPromotes you in current chat if the robot have access to do that (This command works in chats)\n\n- /demoteme\nDemotes you from current chat if the robot have access to do that (This command works in chats)", $messageid, $adminKey);
            }
        }
    }
    elseif($data == 'chg_robotstatus') {
        if(isUserAdmin($fromid)) {
            if(getUserAdmin($fromid) > 1) {
                $users = number_format(getUsers());
                $cusers = number_format(getSettings('cron'));
                if(getSettings('cron') > getUsers()) {
                    $cusers = $users;
                }
                $useddis = 0;
                $sucpay = 0;
                $trial = "DISABLE";
                $postprice = getSettings('post_price');
                $res = mysqli_query($db, "SELECT * FROM `discounts`");
                $discounts = mysqli_num_rows($res);
                if($discounts > 0) {
            		while($row = mysqli_fetch_assoc($res)) {
                        if(isUserExist($row['user'])) {
                            $useddis ++;
                        }
            	    }
                }
                $res = mysqli_query($db, "SELECT * FROM `accounts`");
                $accounts = mysqli_num_rows($res);
                $res = mysqli_query($db, "SELECT * FROM `files`");
                $files = number_format(mysqli_num_rows($res));
                $res = mysqli_query($db, "SELECT * FROM `footage`");
                $footage = mysqli_num_rows($res);
                $res = mysqli_query($db, "SELECT * FROM `chats` WHERE `vip` != '0'");
                $groups = mysqli_num_rows($res);
                $res = mysqli_query($db, "SELECT * FROM `payments`");
                $payments = mysqli_num_rows($res);
                if($payments > 0) {
            		while($row = mysqli_fetch_assoc($res)) {
                        if($row['status'] == '1') {
                            $sucpay ++;
                        }
            	    }
                }
                $res = mysqli_query($db, "SELECT * FROM `posts`");
                $posts = mysqli_num_rows($res);
                $res = mysqli_query($db, "SELECT * FROM `products`");
                $products = mysqli_num_rows($res);
                if(getSettings('trial') == '1') {
                    $trial = "ENABLE";
                }
                if($postprice == '0') {
                    $postprice = "FREE";
                }
                $ping = sys_getloadavg()[0];
                $mintime = completeCronTime();
                $time = completeCronTime(getSettings('cron'));
                setUser($fromid, 'step', '-1');
                sendMessage($chatid, "Robot Status\n\n________________________\nUsers: <code>$users</code> (<code>$mintime min</code>)\nCronJob Checked: <code>$cusers</code>/<code>$users</code> (<code>$time min to complete</code>)\nUsed Discounts: <code>$useddis</code>/<code>$discounts</code>\nAccounts: <code>$accounts</code>\nArchived Files: <code>$files</code>\nFiles in Shop: <code>$footage</code>\nProducts in Shop: <code>$products</code>\nVIP Groups: <code>$groups</code>\nSuccessful Payments: <code>$sucpay</code>/<code>$payments</code>\nInstagram Posts: <code>$posts</code>\nInstagram Campaign Post Price: <code>$postprice</code>\nTrial Status: <code>$trial</code>\nServer PING: <code>$ping</code>ms\n________________________", $messageid, $adminKey);
            }
        }
    }
    elseif($data == 'chg_chats') {
        if(isUserAdmin($fromid)) {
            if(getUserAdmin($fromid) > 1) {
                $count = 0;
                $message = "List of all Chats:\n\n_________________________________\n";
                $res = mysqli_query($db, "SELECT * FROM `chats`");
                $rows = mysqli_num_rows($res);
                if($rows > 0) {
                    while($row = mysqli_fetch_assoc($res)) {
                        $count ++;
                        $chat = $row['chat_id'];
                        $addby = $row['addby'];
                        $time = date('Y-m-d H:i:s', $row['time']);
                        $link = createChatInviteLink($chat, null);
                        $name = bot('getChat', array('chat_id' => $chat))->result->title;
                        $namee = $name;
                        if(!isset($name)) {
                            $name = $chat;
                        }
                        if(isLink($link)) {
                            $namee = '<a href="'.$link.'">'.$name.'</a> (<code>'.$chat.'</code>)';
                        }
                        else {
                            $namee = "<b>$name</b> (<code>$chat</code>)";
                        }
                        if(isUserExist($addby)) {
                            $add = "by ".mentionUser($addby);
                        }
                        else {
                            $add = "<b>automatically</b>";
                        }
                        $message .= "$count. $namee added $add at <code>$time</code>\n";
                    }
                }
                $message .= "Chats Count: <code>$count</code>\n_________________________________";
                sendMessage($chatid, $message, $messageid);
            }
        }
        return true;
    }
    elseif($data == 'nothing') {
        answerCallbackQuery($id, "This button is for decoration");
        return true;
    }
    elseif($data == 'chg_paypal') {
        if(isUserAdmin($fromid)) {
            if(getUserAdmin($fromid) > 1) {
                setUser($fromid, 'step', 'chg_paypal');
                sendMessage($chatid, "Send me the new address for <b>PayPal</b>", $messageid, $backKey);
            }
        }
        return true;
    }
    elseif($data == 'chg_webmoney') {
        if(isUserAdmin($fromid)) {
            if(getUserAdmin($fromid) > 1) {
                setUser($fromid, 'step', 'chg_webmoney');
                sendMessage($chatid, "Send me the new address for <b>WebMoney</b>", $messageid, $backKey);
            }
        }
        return true;
    }
    elseif($data == 'chg_usdt') {
        if(isUserAdmin($fromid)) {
            if(getUserAdmin($fromid) > 1) {
                setUser($fromid, 'step', 'chg_usdt');
                sendMessage($chatid, "Send me the new address for <b>USDT</b>", $messageid, $backKey);
            }
        }
        return true;
    }
    elseif($data == 'chg_upi') {
        if(isUserAdmin($fromid)) {
            if(getUserAdmin($fromid) > 1) {
                setUser($fromid, 'step', 'chg_upi');
                sendMessage($chatid, "Send me the new address for <b>UPI</b>", $messageid, $backKey);
            }
        }
        return true;
    }
    elseif($data == 'chg_stripe') {
        if(isUserAdmin($fromid)) {
            if(getUserAdmin($fromid) > 1) {
                setUser($fromid, 'step', 'chg_stripe');
                sendMessage($chatid, "Send me the new address for <b>Stripe</b>", $messageid, $backKey);
            }
        }
        return true;
    }
    elseif($data == 'chg_toman') {
        if(isUserAdmin($fromid)) {
            if(getUserAdmin($fromid) > 1) {
                setUser($fromid, 'step', 'chg_toman');
                sendMessage($chatid, "Send me the new address for <b>Toman API</b>", $messageid, $backKey);
            }
        }
        return true;
    }
    elseif($data == 'chg_backup') {
        if(isUserAdmin($fromid)) {
            if(getUserAdmin($fromid) > 1) {
                $info = sendMessage($chatid, "Creating backup file...", $messageid, $adminKey);
                $backup = createBackup();
                $fileName = $backup['fileName'];
                $sqlName = $backup['sqlName'];
                $files = $backup['files'];
                $size = $backup['size'];
                $document = $backup['link'];
                sendDocument($chatid, $document, "Files: <code>$files</code>\nSize: <code>$size</code>\n\n#backup\n[ <code>".date('Y-m-d H:i:s')."</code> ]", $messageid, $adminKey);
                unlink($fileName);
                unlink($sqlName);
                deleteMessage($chatid, $info->result->message_id);
            }
        }
    }
    elseif($data == 'chg_refresh') {
        if(isUserAdmin($fromid)) {
            if(getUserAdmin($fromid) > 1) {
                setUser($fromid, 'step', '-1');
                editMessageReplyMarkup($chatid, $messageid, retIKey10($fromid));
            }
        }
        return true;
    }
    elseif($data == 'chg_trial') {
        if(isUserAdmin($fromid)) {
            if(getUserAdmin($fromid) > 1) {
                if(getSettings('trial') == '1') {
                    setSettings('trial', '0');
                }
                else {
                    setSettings('trial', '1');
                }
                setUser($fromid, 'step', '-1');
                sendAdminMessage("#admin\nAdmin $mention has toggled Trial Subscription <b>".(getSettings('trial') == '1' ? 'enabled' : 'disabled')."</b>");
                editMessageReplyMarkup($chatid, $messageid, retIKey10($fromid));
            }
        }
        return true;
    }
    elseif($data == 'chg_debug') {
        if(isUserAdmin($fromid)) {
            if(getUserAdmin($fromid) > 1) {
                if(getUser($fromid, 'debug') == '0') {
                    setUser($fromid, 'debug', '1');
                }
                else {
                    setUser($fromid, 'debug', '0');
                }
                setUser($fromid, 'step', '-1');
                sendAdminMessage("#admin\nAdmin $mention has toggled Debug Mode <b>".(getUser($fromid, 'debug') == '1' ? 'enabled' : 'disabled')."</b> for themself");
                editMessageReplyMarkup($chatid, $messageid, retIKey10($fromid));
            }
        }
        return true;
    }
    elseif($data == 'chg_dnd') {
        if(isUserAdmin($fromid)) {
            if(getUserAdmin($fromid) > 1) {
                if(getUser($fromid, 'dnd') == '0') {
                    setUser($fromid, 'dnd', '1');
                }
                else {
                    setUser($fromid, 'dnd', '0');
                }
                setUser($fromid, 'step', '-1');
                answerCallbackQuery($id, "Do Not Disturb Mode has got ".(getUser($fromid, 'dnd') == '1' ? 'enabled' : 'disabled'));
                editMessageReplyMarkup($chatid, $messageid, retIKey10($fromid));
            }
        }
        return true;
    }
    elseif($data == 'chg_power') {
        if(isUserAdmin($fromid)) {
            if(getUserAdmin($fromid) > 1) {
                if(getSettings('power') == '1') {
                    setSettings('power', '0');
                }
                else {
                    setSettings('power', '1');
                }
                setUser($fromid, 'step', '-1');
                sendAdminMessage("#admin\nAdmin $mention has toggled Power Status <b>".(getSettings('power') == '1' ? 'enabled' : 'disabled')."</b>");
                editMessageReplyMarkup($chatid, $messageid, retIKey10($fromid));
            }
        }
        return true;
    }
    elseif($data == 'chg_shcookies' || $data == 'chg_rfcookies' || $data == 'chg_adcookies') {
        if(isUserAdmin($fromid)) {
            if(getUserAdmin($fromid) > 1) {
                setUser($fromid, 'step', $data);
                sendMessage($chatid, "Send me cookie file for upload\n<b>*</b> For delete all cookie files and reset cookies, send me '<code>delete</code>'", $messageid, $backKey);
            }
        }
    }
    elseif($data == 'chg_invite') {
        if(isUserAdmin($fromid)) {
            if(getUserAdmin($fromid) > 1) {
                setUser($fromid, 'step', 'chg_invite');
                sendMessage($chatid, "Send me how much users should get invited to the robot by another user to get a prize?", $messageid, $backKey);
            }
        }
        return true;
    }
    elseif($data == 'chg_percentprize') {
        if(isUserAdmin($fromid)) {
            if(getUserAdmin($fromid) > 1) {
                setUser($fromid, 'step', 'chg_percentprize');
                sendMessage($chatid, "Send me how many percents should users' referral get stock coins per payment?", $messageid, $backKey);
            }
        }
        return true;
    }
    elseif($data == 'chg_price') {
        if(isUserAdmin($fromid)) {
            if(getUserAdmin($fromid) > 1) {
                setUser($fromid, 'step', 'chg_price');
                sendMessage($chatid, "Send me needed Stock Coins of <b>Instagram Campaign</b>", $messageid, $backKey);
            }
        }
        return true;
    }
    elseif($data == 'chg_nude') {
        if(isUserAdmin($fromid)) {
            if(getUserAdmin($fromid) > 1) {
                setUser($fromid, 'step', 'chg_nude');
                sendMessage($chatid, "Send me needed Stock Coins of <b>Nude</b>", $messageid, $backKey);
            }
        }
        return true;
    }
    elseif($data == 'chg_cron') {
        if(isUserAdmin($fromid)) {
            if(getUserAdmin($fromid) > 1) {
                setUser($fromid, 'step', 'chg_cron');
                sendMessage($chatid, "Where should <b>CronJob</b> starts from?", $messageid, $backKey);
            }
        }
        return true;
    }
    elseif($data == 'chg_post') {
        if(isUserAdmin($fromid)) {
            if(getUserAdmin($fromid) > 1) {
                setUser($fromid, 'step', 'chg_post');
                sendMessage($chatid, "Forward me a message from chat you want to set the <b>Instagram Campaign</b> posts", $messageid, $backKey);
            }
        }
        return true;
    }
    elseif($data == 'chg_secret') {
        if(isUserAdmin($fromid)) {
            if(getUserAdmin($fromid) > 1) {
                setUser($fromid, 'step', 'chg_secret');
                sendMessage($chatid, "Forward me a message from chat you want to set the <b>Secret Channel</b>", $messageid, $backKey);
            }
        }
        return true;
    }
    elseif($data == 'chg_sellers') {
        if(isUserAdmin($fromid)) {
            if(getUserAdmin($fromid) > 1) {
                setUser($fromid, 'step', 'chg_sellers');
                sendMessage($chatid, "Forward me a message from chat you want to set the <b>Sellers Market</b>", $messageid, $backKey);
            }
        }
        return true;
    }
    elseif($data == 'chg_resetpayments') {
        if(isUserAdmin($fromid)) {
            if(getUserAdmin($fromid) > 1) {
                $step = getUser($frmoid, 'step');
                if($step == 'rpconfirm') {
                    sendQuery("DELETE FROM `payments` WHERE `product_id` LIKE '%BSC_%'");
                    setUser($fromid, 'step', '-1');
                    sendAdminMessage("#admin\nAdmin $mention has resetted payments");
                }
                else {
                    setUser($fromid, 'step', 'rpconfirm');
                    answerCallbackQuery($id, "For resetting all users' made payments, use this button again");
                }
            }
        }
        return true;
    }
    elseif($data == 'chg_unblock') {
        if(isUserAdmin($fromid)) {
            if(getUserAdmin($fromid) > 1) {
                $step = getUser($frmoid, 'step');
                if($step == 'ubconfirm') {
                    sendQuery("UPDATE `users` SET `step` = '-1' WHERE `step` = 'block'");
                    setUser($fromid, 'step', '-1');
                    sendAdminMessage("#admin\nAdmin $mention has unblocked all users");
                }
                else {
                    setUser($fromid, 'step', 'ubconfirm');
                    answerCallbackQuery($id, "For unblocking all users from the robot, use this button again");
                }
            }
        }
        return true;
    }
    elseif($data == 'chg_demote') {
        if(isUserAdmin($fromid)) {
            if(getUserAdmin($fromid) > 1) {
                $step = getUser($frmoid, 'step');
                if($step == 'dmconfirm') {
                    sendQuery("UPDATE `users` SET `admin` = '-1' WHERE `admin` < '2' AND `id` != '$fromid'");
                    setUser($fromid, 'step', '-1');
                    sendAdminMessage("#admin\nAdmin $mention has demoted all admins from the robot");
                }
                else {
                    setUser($fromid, 'step', 'dmconfirm');
                    answerCallbackQuery($id, "For demoting all admins from the robot, use this button again");
                }
            }
        }
        return true;
    }
    elseif(isFind($data, 'sendticket_')) {
        $ticketid = str_replace('sendticket_', '', $data);
        $userid = getTicket($ticketid, 'user_id');
        $message = getTicket($ticketid, 'message');
        $sent = getTicket($ticketid, 'sent');
        if($sent == 0 && $userid == $fromid) {
            $key = retIKey16($ticketid);
            setUser($fromid, 'step', '-1');
            sendMessage($chatid, "Ticket#$ticketid has sent to admins", $messageid, $contactsKey);
            sendAdminMessage("#admin\n\n📥 #Ticket(ID:<code>$ticketid</code>)\n👤 OPENED BY $mention ($fromid)\n<code>_____________________________________________________</code>\n$message\n<code>_____________________________________________________</code>", $key);
            setTicket($ticketid, 'sent', 1);
        }
        return true;
    }
    elseif(isFind($data, 'ansticket_')) {
        $ticketid = str_replace('ansticket_', '', $data);
        $sent = getTicket($ticketid, 'sent');
        if($sent == 1) {
            setUser($fromid, 'step', "ansticket_$ticketid");
            sendMessage($chatid, "Send a message as <b>text</b> to send to ticket#$ticketid", $messageid, $backKey);
        }
        return true;
    }
    elseif($data == 'chnapi') {
        setUser($fromid, 'secret', genAccessKey());
        editMessageText($chatid, $messageid, getAccessText($fromid), retIKey17());
        answerCallbackQuery($id, "API Access Key changed");
    }
    elseif($data == 'rvkapi') {
        if(getUser($fromid, 'secret') != '-1') {
            setUser($fromid, 'secret', '-1');
            editMessageText($chatid, $messageid, getAccessText($fromid), retIKey17());
            answerCallbackQuery($id, "API Access Key revoked");
        }
        else {
            answerCallbackQuery($id, "You don't have API Access Key");
        }
    }
    elseif($data == 'apiusage') {
        $curtime = time();
        $info = "";
        $res = mysqli_query($db, "SELECT * FROM `api` WHERE `user_id` = '$fromid' AND `uses` < `maxuses` AND `time` > '$curtime'");
        $rows = mysqli_num_rows($res);
        if($rows > 0) {
            while($row = mysqli_fetch_assoc($res)) {
                $date = date('Y-m-d H:i:s', $row['time']);
            	$left = ($row['time'] - $curtime);
                $data = secondsToTime($left);
                $days = $data['days'];
                $hours = $data['hours'];
                $minutes = $data['minutes'];
                $seconds = $data['seconds'];
                $uses = $row['uses'];
                $maxuses = $row['maxuses'];
                $domain = $row['domain'];
                $info .= "<code>$domain</code> uses of <b>$uses</b>/<b>$maxuses</b>, until <b>$date</b> (<b>$days days</b>, <b>$hours hours</b>, <b>$minutes minutes</b> & <b>$seconds seconds</b> remaining)\n";
            }
            sendMessage($chatid, "Your API subscriptions:\n\n___________________________________________\n$info"."___________________________________________", $messageid);
            answerCallbackQuery($id, "API Subscriptions report sent");
        }
        else {
            answerCallbackQuery($id, "You don't have any API subscriptions");
        }
    }
    elseif(isFind($data, 'trc_')) {
        $dataa = str_replace('trc_', '', $data);
        $target = explode(':', $dataa)[0];
        $coins = explode(':', $dataa)[1];
        $bal = getUser($fromid, 'balance');
        if($fromid != $target && isUserExist($target) && $coins > 0) {
            if($bal >= $coins) {
                $time = date("Y-m-d H:i:s");
                $ment = mentionUser($target);
                setUser($fromid, 'balance', ($bal - $coins));
                setUser($target, 'balance', (getUser($target, 'balance') + $coins));
                sendMessage($chatid, "You've successfully transferred <b>$coins Stock Coins</b> to $ment\n\n[ <b>$time</b> ]");
                sendMessage($target, "#TRANSFER\nUser $mention has transferred <b>$coins Stock Coins</b> to you");
                sendAdminMessage("#admin\nUser $mention has transferred $coins Stock Coins to $ment");
                if($coins >= 1) {
                    checkPremiumQuests($fromid, '4');
                }
            }
            else {
                answerCallbackQuery($id, "You don't have enough stock coins to transfer");
            }
        }
        else {
            answerCallbackQuery($id, "Something went wrong");
        }
        deleteMessage($chatid, $messageid);
    }
    elseif(isFind($data, 'sprv_')) {
        $sid = str_replace('sprv_', '', $data);
        $file_id = getVFile($sid, 'p_file_id');
        $type = getVFile($sid, 'p_type');
        $name = getVFile($sid, 'name');
        $caption = "Preview of art <code>$name</code>";
        if($type == 'photo') {
            sendPhoto($chatid, $file_id, $caption, $messageid);
        }
        elseif($type == 'video') {
            sendVideo($chatid, $file_id, $caption, $messageid);
        }
        elseif($type == 'voice') {
            sendVoice($chatid, $file_id, $caption, $messageid);
        }
        elseif($type == 'audio') {
            sendAudio($chatid, $file_id, $caption, $messageid);
        }
    }
    elseif(isFind($data, 'sprplc_')) {
        $sid = str_replace('sprplc_', '', $data);
        $file_id = getVFile($sid, 'file_id');
        $type = getVFile($sid, 'type');
        $name = getVFile($sid, 'name');
        $rec_name = getVFileName($sid);
        $caption = "Original Name: <code>$name</code>\nRecommended Name: <code>$rec_name</code>";
        $done = false;
        if($type == 'document') {
            sendDocument($chatid, $file_id, $caption, $messageid);
            $done = true;
        }
        elseif($type == 'audio') {
            sendAudio($chatid, $file_id, $caption, $messageid);
            $done = true;
        }
        if($done) {
            setUser($fromid, 'step', "sprplc_$sid");
            sendMessage($chatid, "For replacing file send me the file again with new name", -1, $backKey);
        }
    }
    elseif(isFind($data, 'rejsp_')) {
        $sid = str_replace('rejsp_', '', $data);
        $status = getVFile($sid, 'status');
        if($status == '0') {
            setVFile($sid, 'status', '-2');
            $ware = getVFile($sid, 'ware');
            $user_id = getVFile($sid, 'user_id');
            $name = getVFile($sid, 'name');
            $umention = mentionUser($user_id);
            answerCallbackQuery($id, ($ware == '1' ? "Art" : "Product")." has got rejected");
            sendMessage($user_id, "Your ".($ware == '1' ? "art" : "product")." <code>$name</code> has got rejected by admin $mention");
            sendAdminMessage("#admin\nAdmin $mention has rejected $umention's ".($ware == '1' ? "art" : "product")." (<code>$name</code>)");
        }
        else {
            answerCallbackQuery($id, "You cannot reject this ".($ware == '1' ? "art" : "product")." now");
        }
    }
    elseif(isFind($data, 'accsp_')) {
        $sid = str_replace('accsp_', '', $data);
        $ware = getVFile($sid, 'ware');
        $status = getVFile($sid, 'status');
        if($status == '0') {
            $channel = getSettings('sellers_channel');
            $channel_username = bot('getChat', array('chat_id' => $channel))->result->username;
            $file_id = getVFile($sid, 'p_file_id');
            $type = getVFile($sid, 'p_type');
            if($ware == '2') {
                $file_id = getVFile($sid, 'file_id');
                $type = getVFile($sid, 'type');
            }
            $key = retIKey21($sid);
            $caption = getVFileCaption($sid);
            $info = "";
            if($type == 'photo') {
                $info = sendPhoto($channel, $file_id, $caption, -1, $key);
            }
            elseif($type == 'video') {
                $info = sendVideo($channel, $file_id, $caption, -1, $key);
            }
            elseif($type == 'voice') {
                $info = sendVoice($channel, $file_id, $caption, -1, $key);
            }
            elseif($type == 'audio') {
                $info = sendAudio($channel, $file_id, $caption, -1, $key);
            }
            if($info->ok) {
                $msg_id = $info->result->message_id;
                $name = getVFile($sid, 'name');
                $user_id = getVFile($sid, 'user_id');
                $umention = mentionUser($user_id);
                $link = "https://t.me/$channel_username/$msg_id";
                setVFile($sid, 'chat_id', $channel);
                setVFile($sid, 'message_id', $msg_id);
                setVFile($sid, 'status', '1');
                sendMessage($user_id, 'Your '.($ware == '1' ? "art" : "product").' (<a href="'.$link.'">'.$name.'</a>) has got accepted by admin '.$mention);
                sendAdminMessage("#admin\nAdmin $mention has accepted $umention's ".($ware == '1' ? "art" : "product")." (<code>$name</code>)");
                checkPremiumQuests($from_id, '5');
            }
            else {
                answerCallbackQuery($id, "Something went wrong on sending art to the channel");
            }
        }
        else {
            answerCallbackQuery($id, "You cannot accept this art now");
        }
    }
    elseif($data == 'withdraw') {
        $sbalance = getUser($fromid, 'sbalance');
        if($sbalance >= 3) {
            setUser($fromid, 'balance', (getUser($fromid, 'balance') + $sbalance));
            setUser($fromid, 'sbalance', '0');
            sendAdminMessage("#admin\nUser $mention has withdrawn <b>$$sbalance</b> from their market's balance");
            setUser($fromid, 'step', 'sellers');
            sendMessage($chatid, "You have withdrawn <b>$$sbalance</b> to your main wallet", -1, $sellersKey);
        }
        else {
            answerCallbackQuery($id, 'You should have more than $3 to request to withdraw your money');
        }
    }
    elseif(isFind($data, 'stblck_')) {
        $info = str_replace('stblck_', '', $data);
        $id = explode(':', $info)[0];
        $type = explode(':', $info)[1];
        $step = getUser($fromid, 'step');
        if(isFind($step, 'stblck_')) {
            $link = str_replace('stblck_', '', $step);
            sendStoryblocks($chatid, $link, $id, $type, $messageid, $fromid);
        }
        else {
            answerCallbackQuery($id, "You cannot use this button");
        }
    }
    elseif(isFind($data, 'hostpassword_')) {
        $host_id = str_replace('hostpassword_', '', $data);
        $owner = getHost($host_id, 'user_id');
        $username = getHost($host_id, 'username');
        $status = getHost($host_id, 'status');
        if($owner == $fromid) {
            if(!isHostExpired($host_id) && $status == '1') {
                $get = changeHost($username);
                if($get != null) {
                    setUser($fromid, 'step', '-1');
                    sendMessage($chatid, "New password for <code>$username</code>: <code>$get</code>", $messageid);
                }
                else {
                    answerCallbackQuery($id, "Something went wrong");
                }
            }
            else {
                answerCallbackQuery($id, "Your host is expired, renew it first");
            }
        }
        else {
            answerCallbackQuery($id, "You are not the owner of this host");
        }
    }
    elseif(isFind($data, 'hostdomain_')) {
        $host_id = str_replace('hostdomain_', '', $data);
        $owner = getHost($host_id, 'user_id');
        $username = getHost($host_id, 'username');
        $domain = getHost($host_id, 'domain');
        $status = getHost($host_id, 'status');
        if($owner == $fromid) {
            if(!isHostExpired($host_id) && $status == '1') {
                setUser($fromid, 'step', "hostdomain_$host_id");
                sendMessage($chatid, "Send me new domain for your host (<code>$username</code>)\nCurrent Domain: <code>$domain</code>", $messageid, $backKey);
            }
            else {
                answerCallbackQuery($id, "Your host is expired, renew it first");
            }
        }
        else {
            answerCallbackQuery($id, "You are not the owner of this host");
        }
    }
    elseif(isFind($data, 'hostcharge_')) {
        $host_id = str_replace('hostcharge_', '', $data);
        $owner = getHost($host_id, 'user_id');
        $username = getHost($host_id, 'username');
        $plan = getHost($host_id, 'plan');
        $status = getHost($host_id, 'status');
        if($owner == $fromid) {
            $price = HOST_MONTH["$plan"];
            setUser($fromid, 'step', "hostcharge_$host_id");
            sendMessage($chatid, "You are about to charge your host for <b>$plan</b> ".($plan < 2 ? "month" : "months")." for <b>$price Stock Coins</b>\nIf you are sure about this payment, use confirm button", $messageid, retIKey25($fromid));
            sendMessage($chatid, "Back to Home", -1, $backKey);
        }
        else {
            answerCallbackQuery($id, "You are not the owner of this host");
        }
    }
    elseif(isFind($data, 'conf_')) {
        $info = str_replace('conf_', '', $data);
        $user_id = explode(':', $info)[0];
        $time = explode(':', $info)[1];
        if($fromid == $user_id) {
            if(($time + 120) > time()) {
                $step = getUser($fromid, 'step');
                $balance = getUser($fromid, 'balance');
                if(isFind($step, 'gs_')) {
                    $month = str_replace('gs_', '', explode(':', $step)[0]);
                    $price = explode(':', $step)[1];
                    if($balance >= $price) {
                        setUser($fromid, 'balance', ($balance - $price));
                        $payment = createPayment($fromid, "GS_$month", $price, '-1');
                        setPayment($payment, 'status', '1');
                        sendMessage($chatid, "You have successfully bought <code>$month months group subscription</code>\n<code>$price Stock Coins</code> took from your wallet\nCheck Subscription Status button for groups' links", $messageid, $startKey);
                        addSub($fromid, $month);
                        sendAdminMessage("#admin\n#Payment(dbID:<code>$payment</code>)\nUser $mention has bought <code>$month months group subscription</code> for <code>$price</code> Stock Coins");
                    }
                    else {
                        sendMessage($chatid, "You don't have enough <code>Stock Coins</code> in your wallet\nYou need <code>".($price - $balance)."</code> more Stock Coins", $messageid, $startKey);
                    }
                    setUser($fromid, 'step', '-1');
                }
                elseif(isFind($step, 'trial_')) {
                    if(getUser($fromid, 'trial') == '0') {
                        setUser($fromid, 'step', '-1');
                        setUser($fromid, 'trial', '1');
                        $days = str_replace('trial_', '', $step);
                        $payment = createPayment($fromid, "TGS_$days", '0', '-1');
                        setPayment($payment, 'status', '1');
                        sendMessage($chatid, "You have successfully bought <code>$days days group subscription</code> as trial\nCheck Subscription Status button for groups' links", $messageid, $startKey);
                        addSub($fromid, $days, true);
                        sendAdminMessage("#admin\n#Payment(dbID:<code>$payment</code>)\nUser $mention has bought <code>$days days group subscription</code> for <code>FREE</code> as a trial subscription");
                    }
                    else {
                        setUser($fromid, 'step', '-1');
                        sendMessage($chatid, "You already used your trial subscription", $messageid, $startKey);
                    }
                }
                elseif(isFind($step, 'buy_')) {
                    $name = str_replace('buy_', '', $step);
                    $dbid = getProduct($name, 'id');
                    $name = getProduct($name, 'name');
                    $price = getProduct($name, 'price');
                    $sort = getProduct($name, 'sort');
                    $wait = "<b>WAITING FOR THEIR EMAIL</b>";
                    if($balance >= $price) {
                        setUser($fromid, 'step', "buy:$name");
                        setUser($fromid, 'balance', ($balance - $price));
                        $payment = createPayment($fromid, "PROD_$dbid", $price, '-1');
                        setProduct($name, 'sort', ($sort + 1));
                        setPayment($payment, 'status', '1');
                        sendMessage($chatid, "You have successfully bought product named <code>$name</code>\n<code>$price Stock Coins</code> took from your wallet", $messageid);
                        if(isShutterFootageName($name)) {
                            sendMessage($chatid, "Please send a shutterstock link", $messageid, $backKey);
                            $wait = "<b>WAITING FOR THEM TO SEND A LINK</b>";
                        }
                        else {
                            sendMessage($chatid, "Please send your Email Address to continue", $messageid, $backKey);
                        }
                        sendAdminMessage("#admin\nUser $mention did a successful product #Payment(dbID:<code>$payment</code>)\n\n___________________\nProduct Name: <code>$name</code>\nSpent Stock Coins: <code>$price</code>\n___________________\n\n$wait");
                    }
                    else {
                        setUser($fromid, 'step', '-1');
                        sendMessage($chatid, "You don't have enough <code>Stock Coins</code> in your wallet\nYou need <code>".($price - $balance)."</code> more Stock Coins", $messageid, $startKey);
                    }
                }
                elseif(isFind($step, 'buyf_')) {
                    $name = str_replace('buyf_', '', $step);
                    $dbid = getFootage($name, 'id');
                    $name = getFootage($name, 'name');
                    $price = getFootage($name, 'price');
                    $sort = getFootage($name, 'sort');
                    if($balance >= $price) {
                        $payment = createPayment($fromid, "FTG_$dbid", $price, '-1');
                        setUser($fromid, 'step', "buyf:$name:$payment");
                        setUser($fromid, 'balance', ($balance - $price));
                        setFootage($name, 'sort', ($sort + 1));
                        setPayment($payment, 'status', '1');
                        sendMessage($chatid, "You have successfully bought file named <code>$name</code>\n<code>$price Stock Coins</code> took from your wallet", $messageid);
                        sendMessage($chatid, "Please send your link from <b>$name</b> to have the file", $messageid);
                        sendAdminMessage("#admin\nUser $mention did a successful file #Payment(dbID:<code>$payment</code>)\n\n___________________\nFile Name: <code>$name</code>\nSpent Stock Coins: <code>$price</code>\n___________________");
                    }
                    else {
                        setUser($fromid, 'step', '-1');
                        sendMessage($chatid, "You don't have enough <code>Stock Coins</code> in your wallet\nYou need <code>".($price - $balance)."</code> more Stock Coins", $messageid, $startKey);
                    }
                }
                elseif($step == 'lpd') {
                    $price = getDoublePrice(getUser($fromid, 'double'));
                    if($balance >= $price) {
                        setUser($fromid, 'step', '-1');
                        setUser($fromid, 'balance', ($balance - $price));
                        $double = 2;
                        if(getUser($fromid, 'double') < '1') {
                            $double = 2;
                        }
                        else {
                            $double = (getUser($fromid, 'double') + 1);
                        }
                        $payment = createPayment($fromid, "DBL_$double", $price, '-1');
                        setPayment($payment, 'status', '1');
                        setUser($fromid, 'double', $double);
                        sendMessage($chatid, "You have successfully bought <code>x2 Links Per Day</code> in groups\n<code>$price Stock Coins</code> took from your wallet", $messageid, $startKey);
                        sendAdminMessage("#admin\n#Payment(dbID:<code>$payment</code>)\nUser $mention has bought <code>x2 Links Per Day</code> for <code>$price</code> Stock Coins");
                    }
                    else {
                        setUser($fromid, 'step', '-1');
                        sendMessage($chatid, "You don't have enough <code>Stock Coins</code> in your wallet\nYou need <code>".($price - $balance)."</code> more Stock Coins", $messageid, $startKey);
                    }
                }
                elseif($step == 'hlgame') {
                    $number = rand(1, 100);
                    $cooldown = 2;
                    if(isPremium($fromid)) {
                        $cooldown = 1;
                    }
                    setUser($fromid, 'prize', (time() + DAYS($cooldown)));
                    $data = sendMessage($chatid, "Starting game...", -1, $backKey);
                    sleep(2);
                    deleteMessage($chatid, $data->result->message_id);
                    setUser($fromid, 'step', "hlgame_$number.1");
                    sendMessage($chatid, "I chose my number\nSend me the number you think I chose", $messageid, $backKey);
                }
                elseif($step == 'emjgame') {
                    $emojies = array('🎲', '🎳', '🎯');
                    $emoji = $emojies[array_rand($emojies)];
                    setUser($fromid, 'prize', (time() + DAYS(1)));
                    $cooldown = 3;
                    if(isPremium($fromid)) {
                        $cooldown = 2;
                    }
                    $data = sendMessage($chatid, "Starting game...", -1, $backKey);
                    sleep(2);
                    deleteMessage($chatid, $data->result->message_id);
                    $result = sendDice($chatid, $emoji);
                    $value = $result->result->dice->value;
                    $msgid = $result->result->message_id;
                    sleep(5);
                    if($value > 4) {
                        $prize = 0.0;
                        if($value == 5) {
                            $prize = 0.01;
                        }
                        else {
                            $prize = 0.02;
                        }
                        setUser($fromid, 'prize', (time() + DAYS($cooldown)));
                        setUser($fromid, 'balance', ($balance + $prize));
                        setUser($fromid, 'step', '-1');
                        sendMessage($chatid, ($value < 6 ? "<b>It was so close!</b>" : "<b>You made it!</b>")."\n<b>$prize Stock Coins</b> has been added to your account wallet", $msgid, $startKey);
                    }
                    else {
                        setUser($fromid, 'step', '-1');
                        setUser($fromid, 'prize', (time() + DAYS($cooldown)));
                        sendMessage($chatid, "You couldn't make it\nWish you luck next time", $msgid, $startKey);
                    }
                }
                elseif($step == 'resetpayments') {
                    if(isUserAdmin($fromid)) {
                        if(getUserAdmin($fromid) > 1) {
                            sendQuery("DELETE FROM `payments` WHERE `product_id` LIKE '%BSC_%'");
                            setUser($fromid, 'step', '-1');
                            sendMessage($chatid, "All payments has resetted", $messageid, $adminKey);
                            sendAdminMessage("#admin\nAdmin $mention has resetted payments");
                        }
                        else {
                            setUser($fromid, 'step', '-1');
                            sendMessage($chatid, "You cannot use this section", $messageid, $adminKey);
                        }
                    }
                }
                elseif($step == 'cookie_shdelete' || $step == 'cookie_rfdelete' || $step == 'cookie_addelete') {
                    if(isUserAdmin($fromid)) {
                        if(getUserAdmin($fromid) > 1) {
                            $ck = 'shutterstock';
                            if($step == 'cookie_rfdelete') {
                                $ck = '123rf';
                            }
                            elseif($step == 'cookie_addelete') {
                                $ck = 'adobe';
                            }
                            $cookies = getAllCookies($ck);
                            foreach($cookies as $name) {
                                unlink("api/cookies/$ck/$name");
                            }
                            file_put_contents("api/cookies/$ck/cookies.txt", '1');
                            setUser($fromid, 'step', '-1');
                            sendMessage($chatid, "<b>*</b> <code>".sizeof($cookies)."</code> cookies has deleted from the server", $messageid, $adminKey);
                            sendAdminMessage("#admin\nAdmin $mention has deleted all <b>".sizeof($cookies)."</b> $ck cookies from the server");
                        }
                        else {
                            setUser($fromid, 'step', '-1');
                            sendMessage($chatid, "You cannot use this section", $messageid, $adminKey);
                        }
                    }
                    return true;
                }
                elseif(isFind($step, 'buyn_')) {
                    $name = str_replace('buyn_', '', $step);
                    $dbid = getNumber($name, 'id');
                    $name = getNumber($name, 'name');
                    $price = numberPrice($name);
                    if($price != null) {
                        if($balance >= $price) {
                            $nameEx = str_replace(' ', '', $name);
                            setUser($fromid, 'balance', ($balance - $price));
                            $payment = createPayment($fromid, "NUM_$dbid", $price, '-1');
                            setPayment($payment, 'status', '1');
                            sendMessage($chatid, "You have successfully bought virtual number named <code>$name</code>\n<code>$price Stock Coins</code> took from your wallet", $messageid);
                            $country = getNumber($name, 'country');
                            $service = getNumber($name, 'service');
                            $data = json_decode(getNum($service, $country), true);
                            if($data['RESULT'] == '1') {
                                $gcode = $data['ID'];
                                $number = $data['NUMBER'];
                                $time = $data['TIME'];
                                setUser($fromid, 'step', "getcode_$gcode:$price");
                                sendMessage($chatid, "Your <b>$nameEx</b> virtual number information:\n\n_____________________________\nPhone Number: <code>+$number</code>\nExpire Time: $time\n_____________________________\n\n<b>PLEASE WAIT, WE WILL NOTICE YOU WHEN WE GOT THE CODE</b>", $messageid, $numKey);
                            }
                            else {
                                setUser($fromid, 'balance', ($balance + $price));
                                setUser($fromid, 'step', '-1');
                                sendMessage($chatid, "An error has occurred\n<code>$price Stock Coins</code> refunded to your account wallet", $messageid, $startKey);
                            }
                            sendAdminMessage("#admin\nUser $mention did a successful number #Payment(dbID:<code>$payment</code>)\n\n___________________\nVirtual Number Name: <code>$name</code>\nSpent Stock Coins: <code>$price</code>\n___________________");
                        }
                        else {
                            setUser($fromid, 'step', '-1');
                            sendMessage($chatid, "You don't have enough <code>Stock Coins</code> in your wallet\nYou need <code>".($price - $balance)."</code> more Stock Coins", $messageid, $startKey);
                        }
                    }
                    else {
                        setUser($fromid, 'step', '-1');
                        sendMessage($chatid, "An error has occurred", $messageid, $startKey);
                    }
                }
                elseif(isFind($step, 'buysh_')) {
                    $name = str_replace('buysh_', '', $step);
                    if(isSharedExist($name) && isSharedAvailable($name)) {
                        $price = getShared($name, 'price');
                        $dbid = getShared($name, 'id');
                        if($balance >= $price) {
                            $payment = createPayment($fromid, "SHACC_$dbid", $price, '-1');
                            setPayment($payment, 'status', '1');
                            $ids = array();
                            $emails = array();
                            $usernames = array();
                            $passwords = array();
                            $maxs = array();
                            $username = "null";
                            $password = "null";
                            $res = mysqli_query($db, "SELECT * FROM `sharedaccounts` WHERE `dbid` = '$dbid'");
                            $rows = mysqli_num_rows($res);
                            if($rows > 0) {
                                while($row = mysqli_fetch_assoc($res)) {
                                    array_push($ids, $row['id']);
                                    array_push($emails, $row['email']);
                                    array_push($usernames, $row['username']);
                                    array_push($passwords, $row['password']);
                                    array_push($maxs, $row['max']);
                                }
                                $uc = -1;
                                foreach($ids as $sid) {
                                    $uc ++;
                                    $res = mysqli_query($db, "SELECT * FROM `sharedbuy` WHERE `dbid` = '$sid'");
                                    $rows = mysqli_num_rows($res);
                                    if($rows >= $maxs[$uc]) {
                                        continue;
                                    }
                                    $username = $usernames[$uc];
                                    $password = $passwords[$uc];
                                }
                                $uc = -1;
                                foreach($ids as $sid) {
                                    $uc ++;
                                    $res = mysqli_query($db, "SELECT * FROM `sharedbuy` WHERE `dbid` = '$sid' AND `user` = '$fromid'");
                                    $rows = mysqli_num_rows($res);
                                    if($rows > 0) {
                                        $time = 0;
                                        $ftime = time();
                                        $tid = -1;
                                        while($row = mysqli_fetch_assoc($res)) {
                                            $time = $row['time'];
                                            $tid = $row['id'];
                                        }
                                        if((($time + SHARED_EXPIRE) - time()) > '0') {
                                            $ftime = (($time + SHARED_EXPIRE) - time());
                                            $ftime = (time() + $ftime);
                                        }
                                        if(isSharedExpired($fromid, $name)) {
                                            $ftime = (time() + SHARED_EXPIRE);
                                        }
                                        sendQuery("UPDATE `sharedbuy` SET `time` = '$ftime', `alarm` = '0' WHERE `dbid` = '$tid'");
                                    }
                                    else {
                                        addSharedBuy($sid, $fromid);
                                    }
                                    break;
                                }
                                setUser($fromid, 'balance', ($balance - $price));
                                setUser($fromid, 'step', '-1');
                                sendMessage($chatid, "You have successfully bought a shared account named <code>$name</code>\n<code>$price Stock Coins</code> took from your wallet", $messageid, $startKey);
                                sendMessage($chatid, "<code>$name</code> Shared Account Information:\n\n_________________________________\nUsername: <code>$username</code>\nPassword: <code>$password</code>\n_________________________________", $messageid, $startKey);
                                sendAdminMessage("#admin\nUser $mention did a successful shared account #Payment(dbID:<code>$payment</code>)\n\n___________________\nShared Account Name: <code>$name</code>\nSpent Stock Coins: <code>$price</code>\n___________________");
                                if(!isSharedAvailable($name)) {
                                    sendAdminMessage("#admin\n<code>$name</code> shared account has reached at it's maximum users");
                                }
                            }
                            else {
                                setUser($fromid, 'step', '-1');
                                sendMessage($chatid, "An error has occurred", $messageid, $startKey);
                            }
                        }
                        else {
                            setUser($fromid, 'step', '-1');
                            sendMessage($chatid, "You don't have enough <code>Stock Coins</code> in your wallet\nYou need <code>".($price - $balance)."</code> more Stock Coins", $messageid, $startKey);
                        }
                    }
                    else {
                        setUser($fromid, 'step', '-1');
                        sendMessage($chatid, "An error has occurred", $messageid, $startKey);
                    }
                }
                elseif(isFind($step, 'buymarket_')) {
                    $mid = str_replace('buymarket_', '', $step);
                    $ware = getVFile($mid, 'ware');
                    $user_id = getVFile($mid, 'user_id');
                    $name = getVFile($mid, 'name');
                    $price = getVFile($mid, 'price');
                    $s_chat_id = getVFile($mid, 'chat_id');
                    $s_message_id = getVFile($mid, 'message_id');
                    $sbalance = getUser($user_id, 'sbalance');
                    if($balance >= $price) {
                        $minus = getPercent($price, 20);
                        $rprice = ($price - $minus);
                        setUser($user_id, 'sbalance', ($sbalance + $rprice));
                        setUser($fromid, 'balance', ($balance - $price));
                        setUser($fromid, 'step', 'sellers');
                        sendMessage($chatid, "You have successfully bought <code>$name</code> ".($ware == '1' ? "file" : "product"), $messageid, $sellersKey);
                        sendMessage($user_id, "#MARKET\nAn user has bought your ".($ware == '1' ? "art" : "product")." (<code>$name</code>) from the market. (<b>+$$rprice</b>)");
                        if($ware == '1') {
                            sendVFile($fromid, $mid);
                        }
                        $insert = setUserBoughtVFile($fromid, $mid);
                        editMessageReplyMarkup($s_chat_id, $s_message_id, retIKey21($mid));
                        if($ware == '2') {
                            if($insert != null) {
                                setUser($fromid, 'step', "billing_$insert");
                                sendMessage($chatid, "Send me the address to delivery the product", -1, $backKey);
                            }
                        }
                    }
                    else {
                        setUser($fromid, 'step', 'sellers');
                        sendMessage($chatid, "You don't have enough Stock Coins to buy this ".($ware == '1' ? "file" : "product"), $messageid, $sellersKey);
                    }
                }
                elseif(isFind($step, 'bhst:')) {
                    $plan = explode(':', $step)[1];
                    $domain = explode(':', $step)[2];
                    $email = explode(':', $step)[3];
                    $price = HOST_MONTH["$plan"];
                    if($balance >= $price) {
                        $info = createHost($fromid, $domain, $email, $plan);
                        if(is_array($info)) {
                            setUser($fromid, 'balance', ($balance - $price));
                            $i_domain = $info['domain'];
                            $i_username = $info['username'];
                            $i_password = $info['password'];
                            $i_email = $info['contact_email'];
                            setUser($fromid, 'step', '-1');
                            sendMessage($chatid, "Your host has been created successfully!\n\n_________________________________________________\nLogin URL: https://n1stock.net:2083\nUsername: <code>$i_username</code>\nPassword: <code>$i_password</code>\n_________________________________________________\n\n<b>*</b> Note: Change your ".'<a href="'.$i_domain.'">domain</a>'."'s DNS to the following DNS:\n<code>ns1.n1stock.net</code>\n<code>ns2.n1stock.net</code>", $messageid, $hostKey);
                            sendAdminMessage("#admin\nAdmin $mention has bought $plan ".($plan < 2 ? "month" : "months")." hosting server for $price Stock Coins");
                        }
                        else {
                            if($info == -1) {
                                setUser($fromid, 'step', "bhst:$plan:$domain:$email");
                                sendMessage($chatid, "Failed to randomize the username, use confirm a few moments later", $messageid, retIKey25($fromid));
                                sendMessage($chatid, "Back to Home", -1, $backKey);
                            }
                            elseif($info == -2) {
                                setUser($fromid, 'step', '-1');
                                sendMessage($chatid, "Entered domain is already exist in the database, choose another one", $messageid, $hostKey);
                            }
                            else {
                                setUser($fromid, 'step', "bhst:$plan:$domain:$email");
                                sendMessage($chatid, "Something went wrong, use confirm a few moments later", $messageid, retIKey25($fromid));
                                sendMessage($chatid, "Back to Home", -1, $backKey);
                            }
                        }
                    }
                    else {
                        setUser($fromid, 'step', '-1');
                        sendMessage($chatid, "You don't have enough Stock Coins to buy this hosting server", $messageid, $hostKey);
                    }
                }
                elseif(isFind($step, 'hostcharge_')) {
                    $host_id = str_replace('hostcharge_', '', $step);
                    $owner = getHost($host_id, 'user_id');
                    $username = getHost($host_id, 'username');
                    $plan = getHost($host_id, 'plan');
                    $status = getHost($host_id, 'status');
                    $expire = getHost($host_id, 'expire');
                    $now = time();
                    if($owner == $fromid) {
                        $price = HOST_MONTH["$plan"];
                        if($balance >= $price) {
                            setUser($fromid, 'balance', ($balance - $price));
                            if(!isHostExpired($host_id)) {
                                setHost($host_id, 'expire', ($expire + MONTHS($plan)));
                            }
                            else {
                                setHost($host_id, 'expire', ($now + MONTHS($plan)));
                            }
                            $new_expire = getHost($host_id, 'expire');
                            $dexpire = date('Y-m-d H:i:s', $new_expire);
                            unSuspendHost($username);
                            setHost($host_id, 'status', '1');
                            setUser($fromid, 'step', '-1');
                            sendMessage($chatid, "Your host (<code>$username</code>) has charged until <b>$dexpire</b>", $messageid, $hostKey);
                            sendAdminMessage("#admin\nUser $mention has renewed their host (<code>$username</code>) until <b>$dexpire</b>");
                        }
                        else {
                            setUser($fromid, 'step', '-1');
                            sendMessage($chatid, "You don't have enough Stock Coins", $messageid, $hostKey);
                        }
                    }
                    else {
                        setUser($fromid, 'step', '-1');
                        sendMessage($chatid, "You are not the owner of this host", $messageid, $hostKey);
                    }
                }
                elseif($step == 'grsubfree') {
                    $price = GS_MONTH['FREE'];
                    if($balance >= $price) {
                        setUser($fromid, 'balance', ($balance - $price));
                        addFSub($fromid, '1');
                        setUser($fromid, 'step', 'choose');
                        sendMessage($chatid, "You have successfully bought <b>1 month of subscription to send links in free groups</b> for <b>$price Stock Coins</b>", $messageid, $gsKey);
                        sendAdminMessage("#admin\nUser $mention has bought 1 month of subscription to send links in free groups for $price Stock Coins");
                    }
                    else {
                        setUser($fromid, 'step', '-1');
                        sendMessage($chatid, "You don't have enough Stock Coins", $messageid, $subKey);
                    }
                }
                elseif(isFind($step, 'bprm_')) {
                    $month = str_replace('bprm_', '', explode(':', $step)[0]);
                    $price = explode(':', $step)[1];
                    if($balance >= $price) {
                        setUser($fromid, 'balance', ($balance - $price));
                        $payment = createPayment($fromid, "PREMIUM_$month", $price, '-1');
                        setPayment($payment, 'status', '1');
                        sendMessage($chatid, "You have successfully bought <code>$month months premium subscription</code>\n<code>$price Stock Coins</code> took from your wallet", $messageid, $prmKey);
                        addPremium($fromid, $month);
                        sendAdminMessage("#admin\n#Payment(dbID:<code>$payment</code>)\nUser $mention has bought <code>$month months premium subscription</code> for <code>$price</code> Stock Coins");
                    }
                    else {
                        sendMessage($chatid, "You don't have enough <code>Stock Coins</code> in your wallet\nYou need <code>".($price - $balance)."</code> more Stock Coins", $messageid, $prmKey);
                    }
                    setUser($fromid, 'step', 'premium');
                }
                else {
                    setUser($fromid, 'step', '-1');
                    sendMessage($chatid, "There is nothing to confirm yet", $messageid, $startKey);
                }
            }
            else {
                answerCallbackQuery($id, "You cannot use this button anymore");
            }
        }
        else {
            answerCallbackQuery($id, "This is not your order");
        }
    }
    elseif($data == 'close') {
        setUser($fromid, 'step', '-1');
        answerCallbackQuery($id, "Panel closed");
        deleteMessage($chatid, $messageid);
        return true;
    }
}

$step = getUser($from_id, 'step');
if($step != '-1' && !isFind(strtolower($text), '/start') && strtolower($text) != 'back' && $step != 'choose' && $step != 'subchoose' && $step != 'prchoose' && $step != 'planhost' && $step != 'lpd' && $step != 'products' && $step != 'footages' && $step != 'premium' && !isFind($step, 'getcode_') && $step != 'virnumbers' && $step != 'sharedaccs' && $step != 'buyskey' && $step != 'sellers' && $step != 'choosell' && !isFind($step, 'market_') && !isFind($step, 'buymarket_') && strtolower($text) != 'create a random shutterstock account' && strtolower($text) != 'create a manually shutterstock account' && $tc == 'private') {
    if($step == 'addproduct') {
        setUser($from_id, 'step', "ap_$text");
        sendMessage($chat_id, "Now please send a short information about product named <code>$text</code>", $message_id, $backKey);
        return true;
    }
    elseif(isFind($step, 'ap_')) {
        $pName = str_replace('ap_', '', strip_tags($step));
        $info = strip_tags($text);
        setUser($from_id, 'step', "ap:$pName:$info");
        sendMessage($chat_id, "Now please send me the price of product named <code>$pName</code>", $message_id, $backKey);
        return true;
    }
    elseif(isFind($step, 'ap:')) {
        if(isUserAdmin($from_id)) {
            $pName = explode(':', $step)[1];
            $info = explode(':', $step)[2];
            $price = (int) filter_var(strip_tags(toEnNumber($text)), FILTER_SANITIZE_NUMBER_INT);
            $product = addProduct($pName, $info, $price, $from_id);
            if($product != null) {
                setUser($from_id, 'step', '-1');
                sendMessage($chat_id, "Product added successfully!\n\n________________________\nProduct ID: <code>$product</code>\nProduct Name: <code>$pName</code>\nProduct Information: <code>$info</code>\nPrice: <code>$price</code> Stock Coins\n________________________", $message_id, $adminKey);
            }
            else {
                setUser($from_id, 'step', '-1');
                sendMessage($chat_id, "There is already a product named <code>$pName</code>", $message_id, $adminKey);
            }
            return true;
        }
        return false;
    }
    elseif($step == 'addfootage') {
        setUser($from_id, 'step', "af_$text");
        sendMessage($chat_id, "Now please send a short information about file named <code>$text</code>", $message_id, $backKey);
        return true;
    }
    elseif(isFind($step, 'af_')) {
        $fName = str_replace('af_', '', strip_tags($step));
        $info = strip_tags($text);
        setUser($from_id, 'step', "af:$fName:$info");
        sendMessage($chat_id, "Now please send me the price of file named <code>$fName</code>", $message_id, $backKey);
        return true;
    }
    elseif(isFind($step, 'af:')) {
        if(isUserAdmin($from_id)) {
            $fName = explode(':', $step)[1];
            $info = explode(':', $step)[2];
            $price = strip_tags(toEnNumber(str_replace(array('+', '/', '-', '*'), '', $text)));
            $footage = addFootage($fName, $info, $price, $from_id);
            if($footage != null) {
                setUser($from_id, 'step', '-1');
                sendMessage($chat_id, "File added successfully!\n\n________________________\nFile ID: <code>$footage</code>\nFile Name: <code>$fName</code>\nFile Information: <code>$info</code>\nPrice: <code>$price</code> Stock Coins\n________________________", $message_id, $adminKey);
            }
            else {
                setUser($from_id, 'step', '-1');
                sendMessage($chat_id, "There is already a file named <code>$fName</code>", $message_id, $adminKey);
            }
            return true;
        }
        return false;
    }
    elseif($step == 'addnum') {
        if(isUserAdmin($from_id)) {
            if(!isNumberExist($text)) {
                setUser($from_id, 'step', "an_$text");
                sendMessage($chat_id, "Now please send a short information about number named <code>$text</code>", $message_id, $backKey);
                return true;
            }
            else {
                setUser($from_id, 'step', '-1');
                sendMessage($chat_id, "Number named <code>$text</code> is already exist", $message_id, $adminKey);
                return false;
            }
        }
        return false;
    }
    elseif(isFind($step, 'an_')) {
        if(isUserAdmin($from_id)) {
            $nName = str_replace('an_', '', strip_tags($step));
            $info = strip_tags($text);
            setUser($from_id, 'step', "an:$nName:$info");
            sendMessage($chat_id, "Now please send me the country code for number named <code>$nName</code>", $message_id, $backKey);
            return true;
        }
        return false;
    }
    elseif(isFind($step, 'an:')) {
        if(isUserAdmin($from_id)) {
            $nName = explode(':', $step)[1];
            $info = explode(':', $step)[2];
            $countrycode = (int) filter_var(strip_tags(toEnNumber($text)), FILTER_SANITIZE_NUMBER_INT);
            setUser($from_id, 'step', "an&$nName:$info:$countrycode");
            sendMessage($chat_id, "Now please send me the service id for number named <code>$nName</code>", $message_id, $backKey);
            return true;
        }
        return false;
    }
    elseif(isFind($step, 'an&')) {
        if(isUserAdmin($from_id)) {
            $data = str_replace('an&', '', $step);
            $nName = explode(':', $data)[0];
            $info = explode(':', $data)[1];
            $countrycode = explode(':', $data)[2];
            $service = (int) filter_var(strip_tags(toEnNumber($text)), FILTER_SANITIZE_NUMBER_INT);
            $number = addNumber($nName, $info, $countrycode, $service, $from_id);
            setUser($from_id, 'step', '-1');
            if($number != null) {
                sendMessage($chat_id, "Number added successfully!\n\n________________________\nNumber ID: <code>$number</code>\nNumber Information: <code>$info</code>\nCountry ID: <code>$country</code>\nService ID: <code>$service</code>\n________________________", $message_id, $adminKey);
            }
            else {
                sendMessage($chat_id, "An error occurred", $message_id, $adminKey);
            }
            return true;
        }
        return false;
    }
    elseif($step == 'checkshared') {
        if(isUserAdmin($from_id)) {
            if(getUserAdmin($from_id) > 1) {
                if(isSharedExist($text)) {
                    $name = $text;
                    $sharedid = getShared($name, 'id');
                    $email = "null";
                    $username = "null";
                    $password = "null";
                    $shaccid = -1;
                    $users = array();
                    $times = array();
                    $timesex = array();
                    $emails = array();
                    $usernames = array();
                    $passwords = array();
                    $times = array();
                    $shaccids = array();
                    $max = 0;
                    $infolist = "";
                    $res = mysqli_query($db, "SELECT * FROM `sharedaccounts` WHERE `dbid` = '$sharedid'");
                    $rows = mysqli_num_rows($res);
                    if($rows > 0) {
                        while($row = mysqli_fetch_assoc($res)) {
                            array_push($shaccids, $row['id']);
                            array_push($emails, $row['email']);
                            array_push($usernames, $row['username']);
                            array_push($passwords, $row['password']);
                            array_push($timesex, $row['time']);
                            $max = ($max + $row['max']);
                        }
                    }
                    $uc = 0;
                    foreach($shaccids as $shaccid) {
                        $res = mysqli_query($db, "SELECT * FROM `sharedbuy` WHERE `dbid` = '$shaccid'");
                        $rows = mysqli_num_rows($res);
                        if($rows > 0) {
                            while($row = mysqli_fetch_assoc($res)) {
                                if(!isSharedExpired($row['user'], $name)) {
                                    array_push($users, $row['user']);
                                    array_push($times, $row['time']);
                                }
                            }
                        }
                        $email = $emails[$uc];
                        $username = $usernames[$uc];
                        $password = $passwords[$uc];
                        $time = date('Y-m-d H:i:s', $timesex[$uc]);
                        $infolist .= "Email: <code>$email</code>\nUsername: <code>$username</code>\nPassword: <code>$password</code>\nCreated Time: <code>$time</code>\n___________________________________\n";
                        $uc ++;
                    }
                    $ucount = sizeof($users);
                    $userslist = "";
                    $stock = "";
                    $cc = 0;
                    $tt = time();
                    foreach($users as $userid) {
                        $time = $times[$cc];
                        $cc ++;
                        $timestr = "Expired";
                        $remaining = ((($time + SHARED_EXPIRE) - $tt));
                        if($remaining > '0') {
                            $tdata = secondsToTime($remaining);
                            $days = $tdata['days'];
                            $hours = $tdata['hours'];
                            $minutes = $tdata['minutes'];
                            $seconds = $tdata['seconds'];
                            $timestr = "<code>$days</code>d : <code>$hours</code>h : <code>$minutes</code>m : <code>$seconds</code>s";
                        }
                        $mention = mentionUser($userid);
                        $userslist .= $cc.". $mention (Remaining Time: $timestr)\n";
                    }
                    if($ucount >= $max) {
                        $stock = " (Out of Stock)";
                    }
                    setUser($from_id, 'step', '-1');
                    sendMessage($chat_id, "Information about <code>$name</code> shared account:\n\n___________________________________\n$infolist".$userslist."Users Count: <code>$ucount</code>/<code>$max</code>$stock\n___________________________________", $message_id, $adminKey);
                }
                else {
                    setUser($from_id, 'step', '-1');
                    sendMessage($chat_id, "This shared account is not exist", $message_id, $adminKey);
                }
            }
            else {
                setUser($from_id, 'step', '-1');
                sendMessage($chat_id, "You cannot use this section", $message_id, $adminKey);
            }
        }
        return true;
    }
    elseif($step == 'chprcshared') {
        if(isUserAdmin($from_id)) {
            if(getUserAdmin($from_id) > 1) {
                setUser($from_id, 'step', "chpsh_$text");
                sendMessage($chat_id, "How much Stock Coins do you want to set for shared account named <code>$text</code>", $message_id, $backKey);
            }
            else {
                setUser($from_id, 'step', '-1');
                sendMessage($chat_id, "You cannot use this section", $message_id, $adminKey);
            }
        }
        return true;
    }
    elseif(isFind($step, 'chpsh_')) {
        if(isUserAdmin($from_id)) {
            if(getUserAdmin($from_id) > 1) {
                $name = str_replace('chpsh_', '', $step);
                if(isSharedExist($name)) {
                    $id = getShared($name, 'id');
                    $newprice = (int) filter_var(strip_tags(toEnNumber($text)), FILTER_SANITIZE_NUMBER_INT);
                    $price = getShared($name, 'price');
                    if($newprice >= '0' && $newprice <= '100') {
                        sendQuery("UPDATE `shared` SET `price` = '$newprice' WHERE `id` = '$id'");
                        setUser($from_id, 'step', '-1');
                        sendMessage($chat_id, "<code>$name</code> shared account's price has set to <code>$newprice Stock Coins</code>", $message_id, $adminKey);
                        sendAdminMessage("#admin\nAdmin $mention has set <code>$name</code>'s shared account price to <code>$newprice Stock Coins</code> from <code>$price Stock Coins</code>");
                    }
                    else {
                        setUser($from_id, 'step', '-1');
                        sendMessage($chat_id, "Invalid Stock Coins entered", $message_id, $adminKey);
                    }
                }
                else {
                    setUser($from_id, 'step', '-1');
                    sendMessage($chat_id, "Invalid shared account name entered", $message_id, $adminKey);
                }
            }
            else {
                setUser($from_id, 'step', '-1');
                sendMessage($chat_id, "You cannot use this section", $message_id, $adminKey);
            }
        }
        return true;
    }
    elseif($step == 'chngshared') {
        if(isUserAdmin($from_id)) {
            if(getUserAdmin($from_id) > 1) {
                setUser($from_id, 'step', "chsh_$text");
                sendMessage($chat_id, "Now please send me email address related to shared account named <code>$text</code>", $message_id, $backKey);
            }
            else {
                setUser($from_id, 'step', '-1');
                sendMessage($chat_id, "You cannot use this section", $message_id, $adminKey);
            }
        }
        return true;
    }
    elseif(isFind($step, 'chsh_')) {
        if(isUserAdmin($from_id)) {
            if(getUserAdmin($from_id) > 1) {
                $name = str_replace('chsh_', '', $step);
                if(isSharedExist($name)) {
                    $id = getShared($name, 'id');
                    $res = mysqli_query($db, "SELECT * FROM `sharedaccounts` WHERE `dbid` = '$id' AND `email` = '$text'");
                    $rows = mysqli_num_rows($res);
                    if($rows > 0) {
                        $iid = -1;
                        while($row = mysqli_fetch_assoc($res)) {
                            $iid = $row['id'];
                        }
                        setUser($from_id, 'step', "chsh:$iid");
                        sendMessage($chat_id, "Now please send me new username and password for <code>$name</code> account with email address: <code>$text</code>\n\n<code>TIP</code>: For change username and password, send me <code>Username:Password</code>. For example: <code>test1:qwerty</code>. In this case <code>test1</code> is new username and <code>qwerty</code> is new password\n\nFor change only username or password use <code>_</code> for another case. For example for changing only password of an account, send me <code>_:qwerty</code>. In this case only password will be changed to <code>qwerty</code>", $message_id, $backKey);
                    }
                    else {
                        setUser($from_id, 'step', '-1');
                        sendMessage($chat_id, "There is no <code>$name</code> account with email address <code>$text</code>", $message_id, $backKey);
                    }
                }
                else {
                    setUser($from_id, 'step', '-1');
                    sendMessage($chat_id, "Invalid shared account name entered", $message_id, $adminKey);
                }
            }
            else {
                setUser($from_id, 'step', '-1');
                sendMessage($chat_id, "You cannot use this section", $message_id, $adminKey);
            }
        }
        return true;
    }
    elseif(isFind($step, 'chsh:')) {
        if(isUserAdmin($from_id)) {
            if(getUserAdmin($from_id) > 1) {
                $id = str_replace('chsh:', '', $step);
                if(isFind($text, ':')) {
                    $username = explode(':', $text)[0];
                    $password = explode(':', $text)[1];
                    if(empty($username) || is_null($username)) {
                        $username = "_";
                    }
                    if(empty($password) || is_null($password)) {
                        $password = "_";
                    }
                    $scount = 0;
                    $iid = -1;
                    $name = "null";
                    $res = mysqli_query($db, "SELECT * FROM `sharedaccounts` WHERE `id` = '$id'");
                    $rows = mysqli_num_rows($res);
                    if($rows > 0) {
                        while($row = mysqli_fetch_assoc($res)) {
                            $iid = $row['dbid'];
                            if($username == '_') {
                                $username = $row['username'];
                            }
                            if($password == '_') {
                                $password = $row['password'];
                            }
                        }
                        sendQuery("UPDATE `sharedaccounts` SET `username` = '$username', `password` = '$password' WHERE `id` = '$id'");
                    }
                    $res = mysqli_query($db, "SELECT * FROM `shared` WHERE `id` = '$iid'");
                    $rows = mysqli_num_rows($res);
                    if($rows > 0) {
                        while($row = mysqli_fetch_assoc($res)) {
                            $name = $row['name'];
                        }
                    }
                    $res = mysqli_query($db, "SELECT * FROM `sharedbuy` WHERE `dbid` = '$id'");
                    $rows = mysqli_num_rows($res);
                    if($rows > 0) {
                        while($row = mysqli_fetch_assoc($res)) {
                            $user = $row['user'];
                            if(!isSharedExpired($user, $name)) {
                                $scount ++;
                                sendMessage($user, "New login details for shared account named <code>$name</code>:\n\n_____________________________________\nUsername: <code>$username</code>\nPassword: <code>$password</code>\n_____________________________________");
                            }
                        }
                        setUser($from_id, 'step', '-1');
                        sendMessage($chat_id, "<code>$name</code>'s shared account username and password has changed to <code>$username</code>:<code>$password</code>", $message_id, $adminKey);
                        sendAdminMessage("#admin\nAdmin $mention has changed <code>$name</code>'s shared account details");
                    }
                    else {
                        setUser($from_id, 'step', '-1');
                        sendMessage($chat_id, "An error has occurred", $message_id, $adminKey);
                    }
                }
                else {
                    setUser($from_id, 'step', "chsh:$id");
                    sendMessage($chat_id, "Invalid syntax entered for username and password. Try another.", $message_id, $backKey);
                }
            }
            else {
                setUser($from_id, 'step', '-1');
                sendMessage($chat_id, "You cannot use this section", $message_id, $adminKey);
            }
        }
        return true;
    }
    elseif($step == 'addshared') {
        if(isUserAdmin($from_id)) {
            setUser($from_id, 'step', "as_$text");
            sendMessage($chat_id, "Now please send a short information about shared account named <code>$text</code>", $message_id, $backKey);
        }
        return true;
    }
    elseif($step == 'addacc') {
        if(isUserAdmin($from_id)) {
            $name = strip_tags($text);
            if(isSharedExist($name)) {
                $dbid = -1;
                $res = mysqli_query($db, "SELECT * FROM `shared` WHERE `name` = '$name'");
                $rows = mysqli_num_rows($res);
                if($rows > 0) {
                    while($row = mysqli_fetch_assoc($res)) {
                	    $dbid = $row['id'];
                	}
                	if($dbid != -1) {
                	    setUser($from_id, 'step', "addacc_$dbid");
                        sendMessage($chat_id, "Send me an email address related to account named <code>$name</code>", $message_id, $backKey);
                	}
                	else {
                	    setUser($from_id, 'step', '-1');
                        sendMessage($chat_id, "An error has occurred", $message_id, $adminKey);
                	}
                }
                else {
                    setUser($from_id, 'step', '-1');
                    sendMessage($chat_id, "Invalid shared account name entered", $message_id, $adminKey);
                }
            }
            else {
                setUser($from_id, 'step', '-1');
                sendMessage($chat_id, "Invalid shared account name entered", $message_id, $adminKey);
            }
        }
    }
    elseif(isFind($step, 'as_')) {
        if(isUserAdmin($from_id)) {
            $sName = str_replace('as_', '', strip_tags($step));
            $info = strip_tags($text);
            setUser($from_id, 'step', "as:$sName:$info");
            sendMessage($chat_id, "Now please send me the price of shared account named <code>$sName</code>", $message_id, $backKey);
        }
        return true;
    }
    elseif(isFind($step, 'as:')) {
        if(isUserAdmin($from_id)) {
            $sName = explode(':', $step)[1];
            $info = explode(':', $step)[2];
            $price = (int) filter_var(strip_tags(toEnNumber($text)), FILTER_SANITIZE_NUMBER_INT);
            $shared = addShared($sName, $info, $price, $from_id);
            if($shared != null) {
                setUser($from_id, 'step', "addacc_$shared");
                sendMessage($chat_id, "Shared Account added successfully!\n\n________________________\nShared Account ID: <code>$shared</code>\nShared Account Name: <code>$sName</code>\nShared Account Information: <code>$info</code>\nPrice: <code>$price</code> Stock Coins\n________________________\n\nIf you want to add an account to this shared account, send me an email address related to account named <code>$sName</code>", $message_id, $backKey);
            }
            else {
                setUser($from_id, 'step', '-1');
                sendMessage($chat_id, "There is already a shared account named <code>$sName</code>", $message_id, $adminKey);
            }
            return true;
        }
        return false;
    }
    elseif(isFind($step, 'addacc_')) {
        if(isUserAdmin($from_id)) {
            $dbid = str_replace('addacc_', '', $step);
            if(filter_var($text, FILTER_VALIDATE_EMAIL)) {
                setUser($from_id, 'step', "addacc:$dbid:$text");
                sendMessage($chat_id, "Now send me an username related to email <code>$text</code>", $message_id, $backKey);
            }
            else {
                setUser($from_id, 'step', "addacc_$dbid");
                sendMessage($chat_id, "Invalid Email Address. Try another one.", $message_id, $backKey);
            }
        }
        return true;
    }
    elseif(isFind($step, 'addacc:')) {
        if(isUserAdmin($from_id)) {
            $data = str_replace('addacc:', '', $step);
            $dbid = explode(':', $data)[0];
            $email = explode(':', $data)[1];
            $username = strip_tags($text);
            setUser($from_id, 'step', "addacc@$dbid:$email:$username");
            sendMessage($chat_id, "Now send me a password related to email <code>$email</code>", $message_id, $backKey);
        }
        return true;
    }
    elseif(isFind($step, 'addacc@')) {
        if(isUserAdmin($from_id)) {
            $data = str_replace('addacc@', '', $step);
            $dbid = explode(':', $data)[0];
            $email = explode(':', $data)[1];
            $username = explode(':', $data)[2];
            $password = strip_tags($text);
            setUser($from_id, 'step', "addacc!$dbid:$email:$username:$password");
            sendMessage($chat_id, "How much users can use this shared account?", $message_id, $backKey);
        }
        return true;
    }
    elseif(isFind($step, 'addacc!')) {
        if(isUserAdmin($from_id)) {
            $data = str_replace('addacc!', '', $step);
            $dbid = explode(':', $data)[0];
            $email = explode(':', $data)[1];
            $username = explode(':', $data)[2];
            $password = explode(':', $data)[3];
            $max = (int) filter_var(strip_tags(toEnNumber($text)), FILTER_SANITIZE_NUMBER_INT);
            if($max > '0' && $max < '101') {
                $name = "null";
                $res = mysqli_query($db, "SELECT * FROM `shared` WHERE `id` = '$dbid'");
                $rows = mysqli_num_rows($res);
                if($rows > 0) {
                    while($row = mysqli_fetch_assoc($res)) {
                	    $name = $row['name'];
                	}
                }
                $id = addSharedAccount($dbid, $name, $email, $username, $password, $max, $from_id);
                setUser($from_id, 'step', '-1');
                sendMessage($chat_id, "A Shared Account added to <code>$name</code>\n\n__________________________________________\nShared Account ID: <code>$id</code>\nEmail: <code>$email</code>\nUsername: <code>$username</code>\nPassword: <code>$password</code>\nMax Users: <code>$max</code>\n__________________________________________", $message_id, $adminKey);
            }
            else {
                setUser($from_id, 'step', "addacc!$dbid:$email:$username:$password");
                sendMessage($chat_id, "How much users can use this shared account?", $message_id, $backKey);
            }
        }
        return true;
    }
    elseif($step == 'blockuser') {
        if(isUserAdmin($from_id) && getUserAdmin($from_id) > 1) {
            setUser($from_id, 'step', '-1');
            if(isUserExist($text) && !isUserAdmin($text)) {
                $mention = mentionUser($text);
                $block = getUser($text, 'step');
                if($block == 'block') {
                    setUser($text, 'step', '-1');
                    sendMessage($text, "You are unblocked", -1, $startKey);
                    sendMessage($chat_id, "User $mention is now unblocked", $message_id, $adminKey);
                }
                else {
                    setUser($text, 'step', 'block');
                    sendMessage($text, "You are blocked", -1, $startKey);
                    sendMessage($chat_id, "User $mention is now blocked", $message_id, $adminKey);
                }
            }
            else {
                setUser($from_id, 'step', '-1');
                sendMessage($chat_id, "User is not exist", $message_id, $adminKey);
            }
        }
    }
    elseif($step == 'cu') {
        if(strtolower($text) == 'me') {
            $text = $from_id;
        }
        $text = (int) filter_var(strip_tags(toEnNumber($text)), FILTER_SANITIZE_NUMBER_INT);
        if(isUserAdmin($from_id)) {
            setUser($from_id, 'step', '-1');
            if(isUserExist($text)) {
                $mention = mentionUser($text);
                $dbid = getUser($text, 'dbid');
                $admin = getUser($text, 'admin');
                $balance = getUser($text, 'balance');
                $step = getUser($text, 'step');
                $sub = getUser($text, 'subscription');
                $fsub = getUser($text, 'fsubscription');
                $premium = getUser($text, 'premium');
                $trial = getUser($text, 'trial');
                $links = number_format(getTodayLinks($text)['links']);
                $secret = getUser($text, 'secret');
                $regtime = getUser($text, 'regtime');
                $invs = getUserInviteds($text);
                $ref = getUser($text, 'ref');
                $atext = "<code>No</code>";
                $btext = "<code>No</code>";
                $ttext = "<code>No</code>";
                $sectext = "<code>N/A</code>";
                $rtext = date('Y-m-d H:i:s', $regtime);
                $stext = "";
                $rftext = "";
                $phtext = "";
                $fftext = "";
                $prtext = "";
                if($secret > -1) {
                    $sectext = "<code>$secret</code>";
                }
                if($admin > '0') {
                    $atext = "<code>Yes</code>";
                }
                if($step == 'block') {
                    $btext = "<code>Yes</code>";
                }
                if($trial != '0') {
                    $ttext = "<code>Yes</code>";
                }
                if($ref != '-1') {
                    $rftext = "\nInvited By: ".mentionUser($ref)."\n";
                }
                if(isSubExpired($text)) {
                    $stext = "<code>Expired</code>";
                    if($sub == '-1') {
                        $stext = "<code>Never Had Have</code>";
                    }
                }
                else {
                    $diff = secondsToTime(($sub - time()));
                    $stext = '<code>'.$diff['days'].'</code> days, <code>'.$diff['hours'].'</code> hours, <code>'.$diff['minutes'].'</code> minutes & <code>'.$diff['seconds'].'</code> seconds left';
                }
                if(isFSubExpired($text)) {
                    $fftext = "<code>Expired</code>";
                    if($fsub == '-1') {
                        $fftext = "<code>Never Had Have</code>";
                    }
                }
                else {
                    $fdiff = secondsToTime(($fsub - time()));
                    $fftext = '<code>'.$fdiff['days'].'</code> days, <code>'.$fdiff['hours'].'</code> hours, <code>'.$fdiff['minutes'].'</code> minutes & <code>'.$fdiff['seconds'].'</code> seconds left';
                }
                if(!isPremium($text)) {
                    $prtext = "<code>Expired</code>";
                    if($premium == '-1') {
                        $prtext = "<code>Never Had Have</code>";
                    }
                }
                else {
                    $pdiff = secondsToTime(($premium - time()));
                    $prtext = '<code>'.$pdiff['days'].'</code> days, <code>'.$pdiff['hours'].'</code> hours, <code>'.$pdiff['minutes'].'</code> minutes & <code>'.$pdiff['seconds'].'</code> seconds left';
                }
                $lstr = "<code>Each Group Max Link";
                if(getUser($text, 'double') > '0') {
                    $lstr .= " x".getUser($text, 'double')."</code>";
                }
                else {
                    $lstr .= "</code>";
                }
                if(getUser($text, 'number') != '-1') {
                    $phtext = "Phone Number: <code>".getUser($text, 'number')."</code>\n\n";
                }
                sendMessage($chat_id, "Information about user $mention\n\n_______________________________\ndbID: <code>$dbid</code>\n\n".$phtext."Trial Status: $ttext\nBlock Status: $btext\nAdmin Status: $atext\n\nPremium: $prtext\nSubscription Status: $stext\nFREE Subscription Status: $fftext\n\nAPI Secret: $sectext\n\nStock Coins: <code>$balance</code>\nInvited People: <code>$invs</code>\n\nLinks Info: $lstr\nLinks Sent Today: <code>$links</code>\n\nRegister Time: <code>$rtext</code>\n".$rftext."_______________________________", $message_id, $adminKey);
            }
            else {
                setUser($from_id, 'step', '-1');
                sendMessage($chat_id, "User is not exist", $message_id, $adminKey);
            }
        }
    }
    elseif(isFind($step, 'hlgame_')) {
        $num = (int) filter_var(toEnNumber($text), FILTER_SANITIZE_NUMBER_INT);
        $data = str_replace('hlgame_', '', $step);
        $number = explode('.', $data)[0];
        $try = explode('.', $data)[1];
        if($num == $number) {
            $prize = 0.02;
            setUser($from_id, 'balance', (getUser($from_id, 'balance') + $prize));
            setUser($from_id, 'step', '-1');
            sendMessage($chat_id, "<b>You made it!</b>\nYou guessed my number on your <b>".numberSuffix($try)."</b> try!\n<b>$prize Stock Coins</b> has been added to your account wallet", $message_id, $startKey);
        }
        else {
            setUser($from_id, 'step', "hlgame_$number.".($try + 1));
            sendMessage($chat_id, "The number I chose is <b>".($number > $num ? "higher" : "lower")."</b> than <b>$num</b>\nTries: <b>$try/10</b>", $message_id, $backKey);
            if(($try + 1) > 10) {
                setUser($from_id, 'step', '-1');
                sendMessage($chat_id, "You <b>failed</b> on guessing my number\nMy number was <b>$number</b>\nWish you luck next time", $message_id, $startKey);
            }
        }
    }
    elseif($step == 'cp') {
        if(isUserAdmin($from_id)) {
            if(isPaymentExist($text)) {
                $user = getPayment($text, 'user_id');
                $prod = getPayment($text, 'product_id');
                $cost = getPayment($text, 'cost');
                $discount = getPayment($text, 'discount');
                $email = getPayment($text, 'email');
                $status = getPayment($text, 'status');
                $time = getPayment($text, 'time');
                $mention = mentionUser($user);
                $info = "";
                $stext = "";
                $sttext = "Not Active";
                if($status == '1') {
                    $sttext = 'Active';
                }
                if($email == '-1') {
                    $email = '<code>Not Entered</code>';
                }
                $time = date('Y-m-d H:i:s', $time);
                if(isFind($prod, 'BSC_')) {
                    $stock = number_format(str_replace('BSC_', '', $prod));
                    $info = "Charging Wallet for $stock Stock Coins";
                    if($discount > '0') {
                        $info .= " ($discount% DISCOUNT)";
                    }
                    $stext = "$cost Stock Coins Received";
                }
                if(isFind($prod, 'BSCT_')) {
                    $stock = number_format(str_replace('BSCT_', '', $prod));
                    $info = "Charging Wallet by Iranian Toman for $stock Stock Coins";
                    if($discount > '0') {
                        $info .= " ($discount% DISCOUNT)";
                    }
                    $stext = "$cost Stock Coins Received";
                }
                if(isFind($prod, 'BSCPY_')) {
                    $stock = number_format(str_replace('BSCPY_', '', $prod));
                    $info = "Charging Wallet by PayPal for $stock Stock Coins";
                    if($discount > '0') {
                        $info .= " ($discount% DISCOUNT)";
                    }
                    $stext = "$cost Stock Coins Received";
                }
                if(isFind($prod, 'BSCW_')) {
                    $stock = number_format(str_replace('BSCW_', '', $prod));
                    $info = "Charging Wallet by WebMoney for $stock Stock Coins";
                    if($discount > '0') {
                        $info .= " ($discount% DISCOUNT)";
                    }
                    $stext = "$cost Stock Coins Received";
                }
                if(isFind($prod, 'BSCU_')) {
                    $stock = number_format(str_replace('BSCU_', '', $prod));
                    $info = "Charging Wallet by USDT for $stock Stock Coins";
                    if($discount > '0') {
                        $info .= " ($discount% DISCOUNT)";
                    }
                    $stext = "$cost Stock Coins Received";
                }
                if(isFind($prod, 'GS_')) {
                    $month = number_format(str_replace('GS_', '', $prod));
                    $info = "Buying $month months Group Subscription";
                    $stext = "$cost Stock Coins Spent";
                }
                if(isFind($prod, 'TGS_')) {
                    $day = number_format(str_replace('TGS_', '', $prod));
                    $info = "Using $day days trial Group Subscription";
                    $stext = "FREE";
                }
                if(isFind($prod, 'PROD_')) {
                    $product = number_format(str_replace('PROD_', '', $prod));
                    $name = "Unknown";
                    $res = mysqli_query($db, "SELECT * FROM `products` WHERE `id` = '$product'");
                	$rows = mysqli_num_rows($res);
                	if($rows > 0) {
                        while($row = mysqli_fetch_assoc($res)) {
                		    $name = $row['name'];
                	    }
                    }
                    $info = "Buying Product '$name'";
                    $stext = "$cost Stock Coins Spent";
                }
                setUser($from_id, 'step', '-1');
                sendMessage($chat_id, "Information about Payment #<code>$text</code>\n\n_______________________________\nUser: $mention\nInformation: <code>$info</code>\nPayment Status: <code>$stext</code>\nEmail: $email\nStatus: <code>$sttext</code>\nReceipt Create Time: <code>$time</code>\n_______________________________", $message_id, $adminKey);
            }
            elseif(isTCodeExist($text)) {
                $code = orderToTrans($text);
                if(isTransExist($code)) {
                    $user = getTrans($code, 'user');
                    $price = number_format(getTrans($code, 'price'));
                    $stockcoins = number_format(getTrans($code, 'stockcoins'));
                    $order_id = getTrans($code, 'orderid');
                    $trans_id = getTrans($code, 'transid');
                    $paid = getTrans($code, 'paid');
                    $card_no = getTrans($code, 'cardno');
                    $status = 'Not Paid';
                    if($paid != '0') {
                        $status = "Paid with card $card_no";
                    }
                    $mention = mentionUser($user);
                    $time = date('Y-m-d H:i:s', getTrans($code, 'time'));
                    setUser($from_id, 'step', '-1');
                    sendMessage($chat_id, "Information about Payment #$order_id\n\n_______________________________\nUser: $mention\nStockCoins: <code>$stockcoins</code>\nPrice: <code>$price IRT</code>\nTransID: <code>$trans_id</code>\nStatus: <code>$status</code>\nTime: <code>$time</code>\n_______________________________", $message_id, $adminKey);
                }
                else {
                    setUser($from_id, 'step', '-1');
                    sendMessage($chat_id, "Payment error occurred", $message_id, $adminKey);
                }
            }
            else {
                setUser($from_id, 'step', '-1');
                sendMessage($chat_id, "Payment is not exist", $message_id, $adminKey);
            }
        }
    }
    elseif($step == 'delprod') {
        setUser($from_id, 'step', '-1');
        $name = strip_tags($text);
        if(isProductExist($name)) {
            deleteProduct($name);
            sendMessage($chat_id, "Product named <code>$name</code> deleted", $message_id, $adminKey);
        }
        else {
            sendMessage($chat_id, "Product named <code>$name</code> is not exist", $message_id, $adminKey);
        }
        return true;
    }
    elseif($step == 'delfoot') {
        setUser($from_id, 'step', '-1');
        $name = strip_tags($text);
        if(isFootageExist($name)) {
            deleteFootage($name);
            sendMessage($chat_id, "File named <code>$name</code> deleted", $message_id, $adminKey);
        }
        else {
            sendMessage($chat_id, "File named <code>$name</code> is not exist", $message_id, $adminKey);
        }
        return true;
    }
    elseif($step == 'delshared') {
        setUser($from_id, 'step', '-1');
        $name = strip_tags($text);
        if(isSharedExist($name)) {
            deleteShared($name);
            sendMessage($chat_id, "Shared Account named <code>$name</code> deleted", $message_id, $adminKey);
        }
        else {
            sendMessage($chat_id, "Shared Account named <code>$name</code> is not exist", $message_id, $adminKey);
        }
        return true;
    }
    elseif($step == 'delshacc') {
        $name = strip_tags($text);
        setUser($from_id, 'step', "delshacc_$name");
        sendMessage($chat_id, "Now send me email related to shared account named <code>$name</code>", $message_id, $backKey);
        return true;
    }
    elseif(isFind($step, 'delshacc_')) {
        $name = str_replace('delshacc_', '', $step);
        $email = strip_tags($text);
        $res = mysqli_query($db, "SELECT * FROM `sharedaccounts` WHERE `account` = '$name' AND `email` = '$email'");
        $rows = mysqli_num_rows($res);
        setUser($from_id, 'step', '-1');
        if($rows > 0) {
            while($row = mysqli_fetch_assoc($res)) {
                $dbid = $row['id'];
                sendQuery("DELETE FROM `sharedaccounts` WHERE `account` = '$name' AND `email` = '$email'");
                sendQuery("DELETE FROM `sharedbuy` WHERE `dbid` = '$dbid'");
            }
            sendMessage($chat_id, "Shared Account with email <code>$email</code> has deleted from account named <code>$name</code>", $message_id, $adminKey);
        }
        else {
            sendMessage($chat_id, "There isn't any shared account named <code>$name</code> related to <code>$email</code> email", $message_id, $adminKey);
        }
        return true;
    }
    elseif($step == 'delarch') {
        setUser($from_id, 'step', '-1');
        if(isLink($text)) {
            $count = sendFile($from_id, null, $text);
            if($count > 0) {
                sendFile($from_id, $chat_id, $text, $message_id, $adminKey, true, false);
            }
            else {
                setUser($from_id, 'step', '-1');
                sendMessage($chat_id, "Sent link is not exist", $message_id, $adminKey);
            }
        }
        elseif(isset($message->document) || isset($message->photo) || isset($message->video) || isset($message->audio)) {
            $file_id = null;
            if(isset($message->document)) {
                $file_id = $message->document->file_id;
            }
            elseif(isset($message->photo)) {
                $file_id = $message->photo->file_id;
            }
            elseif(isset($message->video)) {
                $file_id = $message->video->file_id;
            }
            elseif(isset($message->audio)) {
                $file_id = $message->audio->file_id;
            }
            $res = mysqli_query($db, "SELECT * FROM `files` WHERE `fid` = '$file_id'");
            $rows = mysqli_num_rows($res);
            if($rows > 0) {
                while($row = mysqli_fetch_assoc($res)) {
                    $id = $row['id'];
                    $link = $row['link'];
                    $ftype = $row['ftype'];
                    $fid = $row['fid'];
                    $requester = mentionUser($row['requester']);
                    $addby = mentionUser($row['addby']);
                    $time = date('Y-m-d H:i:s', $row['time']);
                    setUser($from_id, 'step', '-1');
                    $caption = "A file found with these informations:\n\n______________________________\nDatabaseID: <code>$id</code>\nLink: <code>$link</code>\nRequested By: $requester\nSent By: $addby\nArchived At: <code>$time</code>\n______________________________\n\nFor delete all files related to this link from archive, use /delarch_$id\nFor delete only this file from archive, use /delfile_$id";
                    if($ftype == 'document') {
                        sendDocument($chat_id, $fid, $caption, $message_id, $adminKey);
                    }
                    elseif($ftype == 'photo') {
                        sendPhoto($chat_id, $fid, $caption, $message_id, $adminKey);
                    }
                    elseif($ftype == 'video') {
                        sendVideo($chat_id, $fid, $caption, $message_id, $adminKey);
                    }
                    elseif($ftype == 'audio') {
                        sendAudio($chat_id, $fid, $caption, $message_id, $adminKey);
                    }
                    else {
                        sendMessage($chat_id, "An error has occurred", $message_id, $adminKey);
                        break;
                    }
                }
            }
            else {
                setUser($from_id, 'step', '-1');
                sendMessage($chat_id, "Sent file is not exist", $message_id, $adminKey);
            }
        }
        else {
            setUser($from_id, 'step', 'delarch');
            sendMessage($chat_id, "Please send me <b>the file</b> or <b>it's link</b> to delete", $message_id, $backKey);
        }
        return true;
    }
    elseif($step == 'delnum') {
        setUser($from_id, 'step', '-1');
        $name = strip_tags($text);
        if(isNumberExist($name)) {
            deleteNumber($name);
            sendMessage($chat_id, "Virtual Number named <code>$name</code> deleted", $message_id, $adminKey);
        }
        else {
            sendMessage($chat_id, "Virtual Number named <code>$name</code> is not exist", $message_id, $adminKey);
        }
        return true;
    }
    elseif($step == 'buytmn') {
        if(isPhoneVerified($from_id)) {
            $stockcoins = (int) filter_var(strip_tags(toEnNumber($text)), FILTER_SANITIZE_NUMBER_INT);
            if($stockcoins > 0 && $stockcoins < 1001) {
                $pid = createPayment($from_id, "BSCT_$stockcoins", $stockcoins, '-1');
                setUser($from_id, 'step', "buytmn_$stockcoins:$pid");
                $price = (int) filter_var(strip_tags(toEnNumber(substr_replace(dollarPrice($stockcoins), "", -1))), FILTER_SANITIZE_NUMBER_INT);
                $code = genTCode();
                $link = createTransaction($from_id, $price, $stockcoins, $code, $pid);
                $key = retIKey12($price, $link);
                setUser($from_id, 'step', '-1');
                sendMessage($chat_id, "Receipt #$code created!\nAfter paying successfully, <b>$stockcoins Stock Coins</b> will be added to your account wallet <b>automatically</b>", $message_id, $key);
                sendMessage($chat_id, "Back to Home", -1, $startKey);
            
            }
            else {
                setUser($from_id, 'step', '-1');
                sendMessage($chat_id, "Sent amount of <b>Stock Coins</b> is invalid", $message_id, $startKey);
            }
        }
        return true;
    }
    elseif($step == 'buyautow' || $step == 'buyautou' || $step == 'buyautor' || $step == 'buyautos') {
        $stockcoins = (int) filter_var(strip_tags(toEnNumber($text)), FILTER_SANITIZE_NUMBER_INT);
        if($stockcoins > 0 && $stockcoins < 1001) {
            $pid = createPayment($from_id, "BSC".strtoupper($step[(strlen($step) - 1)])."_$stockcoins", $stockcoins, '-1');
            setUser($from_id, 'step', "buyauto_$stockcoins:$pid");
            sendMessage($chat_id, "If you have any discount codes, send here, else use skip button", $message_id, $skipKey);
        }
        else {
            setUser($from_id, 'step', '-1');
            sendMessage($chat_id, "Sent amount of <b>Stock Coins</b> is invalid", $message_id, $startKey);
        }
        return true;
    }
    elseif(isFind($step, 'buyauto_')) {
        $data = str_replace('buyauto_', '', $step);
        $amount = explode(':', $data)[0];
        $pid = explode(':', $data)[1];
        $code = str_replace('-', '', $text);
        $type = getPayment($pid, 'product_id');
        $wallet = "";
        if(isDiscountExist($code) && isDiscountUsable($code)) {
            $percent = getDiscount($code, 'percent');
            setDiscount($code, 'user', $from_id);
            setDiscount($code, 'used', time());
            setPayment($pid, 'discount', $percent);
        }
        $newamount = getPercent($amount, $percent);
        $newamount = ($amount - $newamount);
        if($newamount < 1) {
            $newamount = 0;
        }
        if($newamount > 0) {
            if(isFind(strtolower($type), 'bscu')) {
                $type = 'BNB';
                $wallet = getSettings('usdt');
            }
            elseif(isFind(strtolower($type), 'bscpy')) {
                $type = 'PayPal';
                $wallet = getSettings('paypal');
            }
            elseif(isFind(strtolower($type), 'bscw')) {
                $type = 'WebMoney';
                $wallet = getSettings('webmoney');
                $newamount = ($newamount * 1.66);
            }
            elseif(isFind(strtolower($type), 'bscr')) {
                $type = 'UPI';
                $wallet = getSettings('upi');
                $newamount = ($newamount * 90);
            }
            elseif(isFind(strtolower($type), 'bscs')) {
                $type = 'Stripe';
                $wallet = getSettings('stripe');
            }
            setUser($from_id, 'step', "bauto_$pid:$newamount:$amount:$type");
            sendMessage($chat_id, "Send <b>".($type != 'UPI' ? "$" : "")."$newamount".($type == 'UPI' ? " RS" : "")."</b>".($percent > 0 ? " <code>with $percent% discount</code>" : "")." to our <b>$type</b> for adding <b>$amount Stock Coins</b> to your account\nAddress: <code>$wallet</code>\n\nAFTER YOU PAID, SEND THE SCREENSHOT OF RECEIPT HERE", $message_id, $backKey);
        }
        else {
            setUser($from_id, 'step', '-1');
            setPayment($pid, 'status', '1');
            setUser($from_id, 'balance', (getUser($from_id, 'balance') + $amount));
            sendMessage($chat_id, "<b>$amount Stock Coins</b> has been added to your account wallet", $message_id, $startKey);
            sendAdminMessage("#admin\n#Payment(dbID:<code>$pid</code>)\n<b>$amount Stock Coins</b> has added to $mention's account wallet by using <b>$percent%</b> discount code");
        }
        return true;
    }
    elseif(isFind($step, 'bauto_')) {
        $data = str_replace('bauto_', '', $step);
        $pid = explode(':', $data)[0];
        $newamount = explode(':', $data)[1];
        $amount = explode(':', $data)[2];
        $type = explode(':', $data)[3];
        $percent = getPayment($pid, 'percent');
        $res = mysqli_query($db, "SELECT * FROM `users` WHERE `admin` != '-1'");
	    $rows = mysqli_num_rows($res);
	    $sent = false;
        if($percent > 0) {
            $extra = "(<code>$percent% DISCOUNT</code> | <code>$amount</code>)";
        }
        if(isset($message->document)) {
            $file_id = $message->document->file_id;
            $caption = $message->caption;
            $caption = strip_tags($caption);
            if(is_null($caption) || empty($caption)) {
                $caption = "👤 $mention (<code>$from_id</code>)";
            }
            else {
                $caption = "👤 $mention (<code>$from_id</code>)\n📝 $caption";
            }
            if($rows > 0) {
		        while($row = mysqli_fetch_assoc($res)) {
		            sendDocument($row['id'], $file_id, "#admin\n#Payment $pid $extra\nStock Coins Requested: <code>$amount</code>\nType: $type\n".$caption, -1, retIKey($from_id, $newamount, $pid, $amount));
		        }
            }
            $sent = true;
        }
        elseif(isset($message->photo)) {
            $photo = $message->photo;
            $file_id = $photo[count($photo)-1]->file_id;
            $caption = $message->caption;
            $caption = strip_tags($caption);
            if(is_null($caption) || empty($caption)) {
                $caption = "👤 $mention (<code>$from_id</code>)";
            }
            else {
                $caption = "👤 $mention (<code>$from_id</code>)\n📝 $caption";
            }
            if($rows > 0) {
		        while($row = mysqli_fetch_assoc($res)) {
		            sendPhoto($row['id'], $file_id, "#admin\n#Payment $pid $extra\nStock Coins Requested: <code>$amount</code>\nType: $type\n".$caption, -1, retIKey($from_id, $newamount, $pid, $amount));
		        }
            }
            $sent = true;
        }
        elseif(isset($message->text)) {
            $text = strip_tags($text);
            $message = "👤 $mention (<code>$from_id</code>)\n📝 $text";
            if($rows > 0) {
		        while($row = mysqli_fetch_assoc($res)) {
		            sendMessage($row['id'], "#admin\n#Payment $pid $extra\nStock Coins Requested: <code>$amount</code>\nType: $type\n".$message, -1, retIKey($from_id, $newamount, $pid, $amount));
		        }
            }
            $sent = true;
        }
        setUser($from_id, 'step', '-1');
        if($sent) {
            sendMessage($chat_id, "Please wait for a reply by admins", $message_id, $startKey);
        }
        else {
            sendMessage($chat_id, "An error occurred.\nTry sending your screenshot as Image or Document", $message_id, $startKey);
        }
    }
    elseif($step == 'orderpayment') {
        $stockcoins = (int) filter_var(strip_tags(toEnNumber($text)), FILTER_SANITIZE_NUMBER_INT);
        if($stockcoins >= '1' && $stockcoins <= '100') {
            $cp = createPayment($from_id, "BSCPY_$stockcoins", $stockcoins, '-1');
            setUser($from_id, 'step', "orderpayment_$stockcoins:$cp");
            sendMessage($chat_id, "If you have any discount codes, send here, else use skip button", $message_id, $skipKey);
        }
        else {
            setUser($from_id, 'step', 'orderpayment');
            sendMessage($chat_id, "<b>*</b> Entered stock coins must be between 1 to 100\nPlease send me how much <code>Stock Coins</code> do you need?", $message_id, $backKey);
        }
        return true;
    }
    elseif(isFind($step, 'orderpayment_')) {
        $data = str_replace('orderpayment_', '', $step);
        $stockcoins = explode(':', $data)[0];
        $payment = explode(':', $data)[1];
        $price = $stockcoins;
        if($stockcoins >= '1' && $stockcoins <= '100') {
            $code = str_replace('-', '', $text);
            if(isDiscountExist($code) && isDiscountUsable($code)) {
                $percent = getDiscount($code, 'percent');
                setDiscount($code, 'user', $from_id);
                setDiscount($code, 'used', time());
                setPayment($payment, 'discount', $percent);
            }
            $newprice = getPercent($price, $percent);
            $newprice = ($price - $newprice);
            if($newprice < 1) {
                $newprice = 0;
            }
            if($newprice > '0') {
                /*$path = str_replace('index.php', '', currentPath());
                $api = json_decode(file_get_contents($path."payment/"."?key=".PAY_KEY."&name=Telegram%20Payment%20of%20User%20$from_id@$payment&price=$newprice&coin=$stockcoins&user_id=$from_id"), true);
                $url = $api['url'];
                if(isLink($url)) {
                    $id = $api['id'];
                    if($percent > 0) {
                        $extra = " (<code>$percent% DISCOUNT</code>)";
                    }
                    setUser($from_id, 'step', '-1');
                    sendMessage($chat_id, '$'."$newprice$extra invoice #$id has created!\nWhen you finished your payment successfully, <code>$stockcoins Stock Coins</code> will be added to your account wallet automatically", $message_id, retIKey9($url));
                    sendMessage($chat_id, "Back to Home", -1, $startKey);
                }
                else {
                    setUser($from_id, 'step', '-1');
                    sendMessage($chat_id, "An error has occurred", $message_id, $startKey);
                }*/
                $payment = createPayPalPayment($from_id, $newprice, $stockcoins);
                $url = 'https://'.$_SERVER['HTTP_HOST']."/pay/$payment";
                if($percent > 0) {
                    $extra = " (<code>$percent% DISCOUNT</code>)";
                }
                setUser($from_id, 'step', '-1');
                sendMessage($chat_id, '$'."$newprice$extra invoice #$payment has created!\nWhen you finished your payment successfully, <code>$stockcoins Stock Coins</code> will be added to your account wallet automatically", $message_id, retIKey9($url));
                sendMessage($chat_id, "Back to Home", -1, $startKey);
            }
            else {
                setUser($from_id, 'balance', (getUser($from_id, 'balance') + $stockcoins));
                setPayment($payment, 'status', '1');
                setUser($from_id, 'step', '-1');
                sendMessage($chat_id, "Your payment was successfully!\n<code>$stockcoins Stock Coins</code> has added to your account wallet", $message_id, $startKey);
            }
        }
        else {
            setUser($from_id, 'step', 'orderpayment');
            sendMessage($chat_id, "<b>*</b> Entered stock coins must be between 1 to 100\nPlease send me how much <code>Stock Coins</code> do you need?", $message_id, $backKey);
        }
        return true;
    }
    elseif($step == 'buypy') {
        if(isValidEmail($text)) {
            setUser($from_id, 'step', "buypy:$text");
            sendMessage($chat_id, "How many <b>Stock Coins</b> do you want to charge your wallet?", $message_id, $backKey);
        }
        else {
            setUser($from_id, 'step', '-1');
            sendMessage($chat_id, "Sent email address is invalid", $message_id, $startKey);
        }
        return true;
    }
    elseif(isFind($step, 'buypy:')) {
        $email = str_replace('buypy:', '', $step);
        $stockcoins = (int) filter_var(strip_tags(toEnNumber($text)), FILTER_SANITIZE_NUMBER_INT);
        if($stockcoins > 0 && $stockcoins < 1001) {
            $pid = createPayment($from_id, "BSCPY_$stockcoins", $stockcoins, $email);
            setUser($from_id, 'step', "buypy_$stockcoins:$pid");
            sendMessage($chat_id, "If you have any discount codes, send here, else use skip button", $message_id, $skipKey);
        }
        else {
            setUser($from_id, 'step', '-1');
            sendMessage($chat_id, "Sent amount of <b>Stock Coins</b> is invalid", $message_id, $startKey);
        }
        return true;
    }
    elseif(isFind($step, 'buypy_')) {
        $data = str_replace('buypy_', '', $step);
        $amount = explode(':', $data)[0];
        $pid = explode(':', $data)[1];
        $email = getPayment($pid, 'email');
        $code = str_replace('-', '', $text);
        if(isDiscountExist($code) && isDiscountUsable($code)) {
            $percent = getDiscount($code, 'percent');
            setDiscount($code, 'user', $from_id);
            setDiscount($code, 'used', time());
            setPayment($pid, 'discount', $percent);
        }
        $newamount = getPercent($amount, $percent);
        $newamount = ($amount - $newamount);
        if($newamount < 1) {
            $newamount = 0;
        }
        setUser($from_id, 'step', '-1');
        if($newamount > 0) {
            $user = getOwnerUsername();
            sendMessage($chat_id, "Your order has been registered with this amount\nPlease refer to username @$user to get the <b>PayPal</b> payment link", $message_id, $startKey);
            sendAdminMessage("#admin\nUser $mention requested a <b>".'$'."$newamount</b>".($percent > 0 ? " <code>with $percent% discount</code>" : "")." #Payment(dbID:<code>$pid</code>) from <b>PayPal</b> for adding <b>$amount Stock Coins</b> to their wallet.\nEmail: $email", retIKey($from_id, $newamount, $pid, $amount));
        }
        else {
            setPayment($pid, 'status', '1');
            setUser($from_id, 'balance', (getUser($from_id, 'balance') + $amount));
            sendMessage($chat_id, "<b>$amount Stock Coins</b> has been added to your account wallet", $message_id, $startKey);
            sendAdminMessage("#admin\n#Payment(dbID:<code>$pid</code>)\n<b>$amount Stock Coins</b> has added to $mention's account wallet by using <b>$percent%</b> discount code");
        }
        return true;
    }
    elseif(isFind($step, 'buy:')) {
        $name = str_replace('buy:', '', $step);
        if(isShutterFootageName($name)) {
            if(isLink($text)) {
                if(isFind(strtolower($text), 'shutterstock.com')) {
                    setUser($from_id, 'step', '-1');
                    sendAdminMessage("#admin\nUser $mention sent a link for bought product named <code>$name</code>\n\nLink: $text", retIKey6($from_id));
                    sendMessage($chat_id, "Shutterstock Link submitted.\nWait for a reply by admins.", $message_id, $startKey);
                }
                else {
                    setUser($from_id, 'step', "buy:$name");
                    sendMessage($chat_id, "Invalid Shutterstock Link. Try another one.", $message_id, $backKey);
                }
            }
            else {
                setUser($from_id, 'step', "buy:$name");
                sendMessage($chat_id, "Invalid Shutterstock Link. Try another one.", $message_id, $backKey);
            }
        }
        else {
            if(filter_var($text, FILTER_VALIDATE_EMAIL)) {
                setUser($from_id, 'step', '-1');
                sendAdminMessage("#admin\nUser $mention sent an email address for bought product named <code>$name</code>\n\nEmail: $text", retIKey6($from_id));
                sendMessage($chat_id, "Email Address submitted.\nWait for a reply by admins.", $message_id, $startKey);
            }
            else {
                    setUser($from_id, 'step', "buy:$name");
                    sendMessage($chat_id, "Invalid Email Address. Try another one.", $message_id, $backKey);
            }
        }
        return true;
    }
    elseif(isFind($step, 'buyf:')) {
        $max_similar = 3;
        $data = str_replace('buyf:', '', $step);
        $name = explode(':', $data)[0];
        $payment = explode(':', $data)[1];
        $price = getFootage($name, 'price');
        if(isLink($text)) {
            $url = str_replace(array(' '), '', $text);
            $domain = parse_url($url)['host'];
            $similar = similar_text(strtolower($name), strtolower($domain), $percent);
            if(!isFileExist($text)) {
                if($similar >= $max_similar && $percent >= 40) {
                    $count = sendFile($from_id, $chat_id, $text, $message_id, null, true, true, true);
                    if($count == 1) {
                        setUser($from_id, 'step', '-1');
                        sendMessage($chat_id, "File has sent to you by <b>API</b>", $message_id, $startKey);
                    }
                    else {
                        setUser($from_id, 'step', '-1');
                        sendAdminMessage("#admin\nUser $mention sent a link for bought file named <code>$name</code>\n\nLink: $text", retIKey18($from_id, $payment, $price));
                        sendMessage($chat_id, "Link submitted.\nWait for a reply by admins.", $message_id, $startKey);
                    }
                }
                else {
                    setUser($from_id, 'step', "buyf:$name:$payment");
                    sendMessage($chat_id, "I think your link is not from <code>$name</code>. Try another one.", $message_id, $backKey);
                }
            }
            else {
                $balance = getUser($from_id, 'balance');
                setUser($from_id, 'step', '-1');
                if($similar >= $max_similar) {
                    $fcount = sendFile($from_id, null, $text);
                    if($fcount > 0) {
                        if($balance >= $price) {
                            sendMessage($chat_id, 'Requested <a href="'.$text.'">file</a> has found in our database.', $message_id, $startKey);
                            sendFile($from_id, $chat_id, $text, $message_id, $startKey);
                        }
                        else {
                            setUser($from_id, 'balance', (getUser($from_id, 'balance') + $price));
                            sendMessage($chat_id, 'Requested <a href="'.$text.'">file</a> has found in our database. You need <code>'.($price - $balance).'</code> more Stock Coins', $message_id, $startKey);
                        }
                    }
                    else {
                        setUser($from_id, 'balance', (getUser($from_id, 'balance') + $price));
                        sendMessage($chat_id, "An error occurred", $message_id, $startKey);
                    }
                }
                else {
                    setUser($from_id, 'balance', (getUser($from_id, 'balance') + $price));
                    setUser($from_id, 'step', "buyf:$name:$payment");
                    sendMessage($chat_id, "Your link is exist in our database but it's not from <code>$name</code>. Request it in right place", $message_id, $backKey);
                }
            }
        }
        else {
            setUser($from_id, 'step', "buyf:$name:$payment");
            sendMessage($chat_id, "Invalid Link. Try another one.", $message_id, $backKey);
        }
        return true;
    }
    elseif($step == 'cd') {
        $percent = (int) filter_var(strip_tags(toEnNumber($text)), FILTER_SANITIZE_NUMBER_INT);
        if($percent > 0 && $percent < 101) {
            $code = formatToCode(addDiscount($percent, $from_id));
            setUser($from_id, 'step', '-1');
            sendMessage($chat_id, "#Discount code created!\n________________________\nCode: <code>$code</code>\n________________________", $message_id, $adminKey);
        }
        else {
            setUser($from_id, 'step', 'cd');
            sendMessage($chat_id, "Invalid percent sent. Try between 1 to 100", $message_id, $backKey);
        }
        return true;
    }
    elseif($step == 'setbal') {
        if(isUserAdmin($from_id)) {
            $target = (int) filter_var(strip_tags(toEnNumber($text)), FILTER_SANITIZE_NUMBER_INT);
            if(strtolower($text) == 'me') {
                $target = $from_id;
            }
            if(isUserExist($target)) {
                setUser($from_id, 'step', "setbal_$target");
                $balance = getUser($target, 'balance');
                sendMessage($chat_id, "Send me how much do you want to set user <code>$target</code>'s account balance\nTheir current balance is: <code>$balance</code> Stock Coins\n\nFor give/take use symbols (<code>+ -</code>) before number\nFor example for taking <code>20</code> coins from their balance, send me <code>-20</code>", $message_id, $backKey);
            }
            else {
                setUser($from_id, 'step', '-1');
                sendMessage($chat_id, "User is not exist", $message_id, $adminKey);
            }
        }
        return true;
    }
    elseif(isFind($step, 'setbal_')) {
        $target = str_replace('setbal_', '', $step);
        $balance = filter_var(strip_tags(toEnNumber($text)), FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $type = 0;
        if(isFind($text, '+')) {
            $type = 1;
        }
        if(!is_numeric($balance[0]) && !isFind($balance, '-')) {
            $balance = ltrim($balance, $balance[0]);
        }
        if(isUserAdmin($from_id)) {
            if(getUserAdmin($from_id) > 1) {
                if(isUserExist($target)) {
                    $mention = mentionUser($target);
                    if($balance > 0) {
                        if($type == 0) {
                            setUser($from_id, 'step', '-1');
                            setUser($target, 'balance', $balance);
                            sendMessage($chat_id, "User $mention's account balance has set to <code>$balance</code> Stock Coins", $message_id, $adminKey);
                            sendMessage($target, "Your account balance has set to <code>$balance</code> Stock Coins");
                        }
                        elseif($type == 1) {
                            $newbalance = (getUser($target, 'balance') + $balance);
                            setUser($from_id, 'step', '-1');
                            setUser($target, 'balance', $newbalance);
                            sendMessage($chat_id, "User $mention's account balance has set to <code>$newbalance</code> Stock Coins", $message_id, $adminKey);
                            sendMessage($target, "Your account balance has set to <code>$newbalance</code> Stock Coins");
                        }
                    }
                    else {
                        $newbalance = (getUser($target, 'balance') + $balance);
                        setUser($from_id, 'step', '-1');
                        setUser($target, 'balance', $newbalance);
                        sendMessage($chat_id, "User $mention's account balance has set to <code>$newbalance</code> Stock Coins", $message_id, $adminKey);
                        sendMessage($target, "Your account balance has set to <code>$newbalance</code> Stock Coins");
                    }
                }
                else {
                    setUser($from_id, 'step', '-1');
                    sendMessage($chat_id, "User is not exist", $message_id, $adminKey);
                }
            }
            else {
                setUser($from_id, 'step', '-1');
                sendMessage($chat_id, "You cannot use this section", $message_id, $adminKey);
            }
        }
        return true;
    }
    elseif($step == 'cr') {
        if(isUserAdmin($from_id)) {
            if(isValidEmail($text)) {
                if(isAccountExist($text)) {
                    setUser($from_id, 'step', '-1');
                    $id = getAccount($text, 'id');
                    $type = getAccount($text, 'type');
                    $username = getAccount($text, 'username');
                    $email = getAccount($text, 'email');
                    $password = getAccount($text, 'password');
                    $time = getAccount($text, 'time');
                    $ftype = getAccount($text, 'ftype');
                    $fid = getAccount($text, 'fid');
                    $date = date('Y-m-d H:i:s', $time);
                    $caption = "Account: <code>$type</code>\nUsername: <code>$username</code>\nEmail: $email\nPassword: <code>$password</code>\nDate: <code>$date</code>";
                    if($ftype == 'document') {
                        sendMessage($chat_id, "<code>$type</code> account found with email: <code>$email</code>", $message_id, $backKey);
                        sendDocument($chat_id, $fid, $caption, $message_id, $backKey);
                        setUser($from_id, 'step', "cr2_$text");
                        sendMessage($chat_id, "If you still want to create an account with this email\n\nSend me a username for email <code>$text</code>", $message_id, $backKey);
                    }
                    elseif($ftype == 'photo') {
                        sendMessage($chat_id, "<code>$type</code> account found with email: <code>$email</code>", $message_id, $backKey);
                        sendPhoto($chat_id, $fid, $caption, $message_id, $backKey);
                        setUser($from_id, 'step', "cr2_$text");
                        sendMessage($chat_id, "If you still want to create an account with this email\n\nSend me a username for email <code>$text</code>", $message_id, $backKey);
                    }
                    else {
                        setUser($from_id, 'step', "cr2_$text");
                        sendMessage($chat_id, "An error occurred while loading account on dbID: <code>$id</code>", $message_id, $backKey);
                        sendMessage($chat_id, "Send me a username for email <code>$text</code>", $message_id, $backKey);
                    }
                }
                else {
                    setUser($from_id, 'step', "cr2_$text");
                    sendMessage($chat_id, "Send me a username for email <code>$text</code>", $message_id, $backKey);
                }
            }
            else {
                setUser($from_id, 'step', '-1');
                sendMessage($chat_id, "Invalid email entered", $message_id, $adminKey);
            }
        }
        return true;
    }
    elseif(isFind($step, 'cr2_')) {
        if(isUserAdmin($from_id)) {
            $email = str_replace('cr2_', '', $step);
            setUser($from_id, 'step', "cr3_$email:".strip_tags($text));
            sendMessage($chat_id, "Send me a password", $message_id, $backKey);
        }
        return true;
    }
    elseif(isFind($step, 'cr3_')) {
        if(isUserAdmin($from_id)) {
            $data = str_replace('cr3_', '', $step);
            $email = explode(':', $data)[0];
            $username = explode(':', $data)[1];
            $password = strip_tags($text);
            setUser($from_id, 'step', "cr4_$email:$username:$password");
            sendMessage($chat_id, "Send me account type", $message_id, $backKey);
        }
        return true;
    }
    elseif(isFind($step, 'cr4_')) {
        if(isUserAdmin($from_id)) {
            $data = str_replace('cr4_', '', $step);
            $email = explode(':', $data)[0];
            $username = explode(':', $data)[1];
            $password = explode(':', $data)[2];
            $type = strip_tags($text);
            setUser($from_id, 'step', "cr5_$email:$username:$password:$type");
            sendMessage($chat_id, "Send me a photo of account", $message_id, $backKey);
        }
        return true;
    }
    elseif(isFind($step, 'cr5_')) {
        if(isUserAdmin($from_id)) {
            $data = str_replace('cr5_', '', $step);
            $email = explode(':', $data)[0];
            $username = explode(':', $data)[1];
            $password = explode(':', $data)[2];
            $type = explode(':', $data)[3];
            $cr = false;
            $file_id = 0;
            if(isset($message->document)) {
                $file_id = $message->document->file_id;
                $cr = createAccount($type, $email, $username, $password, $file_id, 'document', $from_id);
            }
            elseif(isset($message->photo)) {
                $photo = $message->photo;
                $file_id = $photo[count($photo)-1]->file_id;
                $cr = createAccount($type, $email, $username, $password, $file_id, 'photo', $from_id);
            }
            else {
                setUser($from_id, 'step', "cr5_$email:$username:$password:$type");
                sendMessage($chat_id, "Please send me a <code>photo</code> of account", $message_id, $backKey);
            }
            if($cr) {
                setUser($from_id, 'step', '-1');
                $ftype = getAccount($email, 'ftype');
                $fid = getAccount($email, 'fid');
                $time = getAccount($email, 'time');
                $date = date('Y-m-d H:i:s');
                $caption = "Account: <code>$type</code>\nUsername: <code>$username</code>\nEmail: $email\nPassword: <code>$password</code>\nDate: <code>$date</code>\n<b>Thank you for your trust and purchase!</b>";
                if($ftype == 'photo') {
                    sendPhoto($chat_id, $file_id, $caption, $message_id, $adminKey);
                }
                elseif($ftype == 'document') {
                    sendDocument($chat_id, $file_id, $caption, $message_id, $adminKey);
                }
            }
            else {
                setUser($from_id, 'step', '-1');
                sendMessage($chat_id, "An error has occurred while creating account", $message_id, $adminKey);
            }
        }
    }
    elseif($step == 'crshutter') {
        if(isUserAdmin($from_id)) {
            if(isValidEmail($text)) {
                setUser($from_id, 'step', "crshutter1_$text");
                sendMessage($chat_id, "Send me a password\n\nSend me '<code>random</code>' for a random password", $message_id, $backKey);
            }
            else {
                setUser($from_id, 'step', '-1');
                sendMessage($chat_id, "Invalid email entered", $message_id, $adminKey);
            }
        }
        return true;
    }
    elseif(isFind($step, 'crshutter1_')) {
        if(isUserAdmin($from_id)) {
            $email = str_replace('crshutter1_', '', $step);
            $password = $text;
            if(strtolower($text) == 'random') {
                $password = randomString(10);
            }
            $account = createShutterstock($email, $password);
            setUser($from_id, 'step', '-1');
            sendMessage($chat_id, "Shutterstock account created!\n\n__________________________________\nAccount Email: $email\nAccount Password: <code>$password</code>\n__________________________________", $message_id, $adminKey);
        }
        return true;
    }
    elseif($step == 'pdadmin') {
        if(isUserAdmin($from_id)) {
            if(getUserAdmin($from_id) > 1) {
                $target = (int) filter_var(strip_tags(toEnNumber($text)), FILTER_SANITIZE_NUMBER_INT);
                if(isUserExist($target)) {
                    if(getUserAdmin($target) < getUserAdmin($from_id)) {
                        $mention = mentionUser($target);
                        if(isUserAdmin($target)) {
                            setUser($from_id, 'step', '-1');
                            setUser($target, 'step', '-1');
                            setUser($target, 'admin', '-1');
                            setUser($target, 'debug', '0');
                            sendMessage($chat_id, "You've demoted user $mention", $message_id, $adminKey);
                            sendMessage($target, "You are no longer admin", -1, $startKey);
                        }
                        else {
                            setUser($from_id, 'step', '-1');
                            setUser($target, 'admin', '1');
                            setUser($target, 'debug', '0');
                            sendMessage($chat_id, "You've promoted user $mention", $message_id, $adminKey);
                            sendMessage($target, "You are now an admin");
                        }
                    }
                    else {
                        setUser($from_id, 'step', '-1');
                        sendMessage($chat_id, "You cannot do this action on this user", $message_id, $adminKey);
                    }
                }
                else {
                    setUser($from_id, 'step', '-1');
                    sendMessage($chat_id, "User is not exist", $message_id, $adminKey);
                }
            }
            else {
                setUser($from_id, 'step', '-1');
                sendMessage($chat_id, "You cannot use this section", $message_id, $adminKey);
            }
        }
        return true;
    }
    elseif($step == 'ic') {
        if(isLink($text)) {
            if(isFind($text, 'instagram.com')) {
                $post = toSinglePostID($text);
                if(!empty($post)) {
                    setUser($from_id, 'step', "ic2:$post");
                    sendMessage($chat_id, "Now please send me the image or video related to the post", $message_id, $backKey);
                }
                else {
                    setUser($from_id, 'step', '-1');
                    sendMessage($chat_id, "Sent link is invalid", $message_id, $startKey);
                }
            }
            else {
                setUser($from_id, 'step', '-1');
                sendMessage($chat_id, "Sent link is not from Instagram", $message_id, $startKey);
            }
        }
        else {
            setUser($from_id, 'step', '-1');
            sendMessage($chat_id, "You didn't send me a link", $message_id, $startKey);
        }
        return true;
    }
    elseif(isFind($step, 'ic2:')) {
        $post = str_replace('ic2:', '', $step);
        $price = getSettings('post_price');
        $res = mysqli_query($db, "SELECT * FROM `users` WHERE `admin` != '-1'");
	    $rows = mysqli_num_rows($res);
        $caption = "#admin\n👤 $mention\n🌐 https://instagram.com/p/$post/";
        if(getUser($from_id, 'balance') >= $price) {
            if(isset($message->video)) {
                setUser($from_id, 'step', '-1');
                $file_id = $message->video->file_id;
                $id = createPost('video', $file_id, $post, $from_id);
                $payment = createPayment($from_id, "PST_$id", $price, '-1');
                setPayment($payment, 'status', '1');
                setUser($from_id, 'balance', (getUser($from_id, 'balance') - $price));
                if($rows > 0) {
                    while($row = mysqli_fetch_assoc($res)) {
                        sendVideo($row['id'], $file_id, $caption, -1, retIKey2($id, $price));
                    }
                }
                sendMessage($chat_id, "Your request has sent to admins, wait for a reply", $message_id, $startKey);
            }
            elseif(isset($message->photo)) {
                setUser($from_id, 'step', '-1');
                $photo = $message->photo;
                $file_id = $photo[count($photo)-1]->file_id;
                $id = createPost('photo', $file_id, $post, $from_id);
                createPayment($from_id, "PST_$id", $price, '-1');
                setUser($from_id, 'balance', (getUser($from_id, 'balance') - $price));
                if($rows > 0) {
                    while($row = mysqli_fetch_assoc($res)) {
                        sendPhoto($row['id'], $file_id, $caption, -1, retIKey2($id, $price));
                    }
                }
                sendMessage($chat_id, "Your request has sent to admins, wait for a reply", $message_id, $startKey);
            }
            else {
                setUser($from_id, 'step', "ic2:$post");
                sendMessage($chat_id, "You send me your content as an incorrect way!\nSend me your content as an <b>image</b> or <b>video</b>", $message_id, $backKey);
            }
        }
        else {
            setUser($from_id, 'step', '-1');
            sendMessage($chat_id, "You don't have enough Stock Coins to continue", $message_id, $startKey);
        }
    }
    elseif($step == 'icprice') {
        if(isUserAdmin($from_id)) {
            $price = (int) filter_var(strip_tags(toEnNumber($text)), FILTER_SANITIZE_NUMBER_INT);
            if($price > -1 && $price < 1000000) {
                setSettings('post_price', $price);
                setUser($from_id, 'step', '-1');
                sendMessage($chat_id, "Instagram Campaign needed Stock Coins has set to <b>$price</b> Stock Coins", $message_id, $adminKey);
                sendAdminMessage("#admin\nAdmin $mention has set Instagram Campaign's needed Stock Coins to <b>$price</b>");
            }
            else {
                setUser($from_id, 'step', '-1');
                sendMessage($chat_id, "Invalid number sent", $message_id, $adminKey);
            }
        }
        return true;
    }
    elseif($step == 'pmall') {
        if(isUserAdmin($from_id) && getUserAdmin($from_id) > 1) {
            $id = 0;
            if(isset($message->document)) {
                $file_id = $message->document->file_id;
                $caption = $message->caption;
                $result = sendAll('sendDocument', [
                    'chat_id' => '[*USER*]',
                    'document' => $file_id,
                    'caption' => $caption
                ]);
                $id = $result['result'];
            }
            elseif(isset($message->video)) {
                $file_id = $message->video->file_id;
                $caption = $message->caption;
                $result = sendAll('sendVideo', [
                    'chat_id' => '[*USER*]',
                    'video' => $file_id,
                    'caption' => $caption
                ]);
                $id = $result['result'];
            }
            elseif(isset($message->photo)) {
                $photo = $message->photo;
                $file_id = $photo[count($photo)-1]->file_id;
                $caption = $message->caption;
                $result = sendAll('sendPhoto', [
                    'chat_id' => '[*USER*]',
                    'photo' => $file_id,
                    'caption' => $caption
                ]);
                $id = $result['result'];
            }
            elseif(isset($message->voice)) {
                $file_id = $message->voice->file_id;
                $caption = $message->caption;
                $result = sendAll('sendVoice', [
                    'chat_id' => '[*USER*]',
                    'voice' => $file_id,
                    'caption' => $caption
                ]);
                $id = $result['result'];
            }
            elseif(isset($message->audio)) {
                $file_id = $message->audio->file_id;
                $caption = $message->caption;
                $result = sendAll('sendAudio', [
                    'chat_id' => '[*USER*]',
                    'audio' => $file_id,
                    'caption' => $caption
                ]);
                $id = $result['result'];
            }
            elseif(isset($message->sticker)) {
                $file_id = $message->sticker->file_id;
                $result = sendAll('sendSticker', [
                    'chat_id' => '[*USER*]',
                    'sticker' => $file_id
                ]);
                $id = $result['result'];
            }
            elseif(isset($message->animation)) {
                $file_id = $message->animation->file_id;
                $caption = $message->caption;
                $result = sendAll('sendAnimation', [
                    'chat_id' => '[*USER*]',
                    'animation' => $file_id,
                    'caption' => $caption
                ]);
                $id = $result['result'];
            }
            elseif(isset($message->text)) {
                $result = sendAll('sendMessage', [
                    'chat_id' => '[*USER*]',
                    'text' => $text
                ]);
                $id = $result['result'];
            }
            else {
                setUser($from_id, 'step', '-1');
                sendMessage($chat_id, "An error has occurred", $message_id, $adminKey);
            }
            if($id != 0) {
                $users = number_format(getUsers());
                $mintime = completeCronTime();
                setUser($from_id, 'step', '-1');
                sendMessage($chat_id, "Your message is now in queue with ID#<code>$id</code> to send to <code>$users</code> users\nMinimum Time To Complete: <code>$mintime minutes</code>", $message_id, $adminKey);
                sendAdminMessage("#admin\nAdmin $mention added Message ID#<code>$id</code> to send to all <code>$users</code> users. This message will be sent to all users in <code>$mintime minutes</code>\n\n<b>*</b> Cancel it by using /csmta_$id");
            }
            else {
                setUser($from_id, 'step', '-1');
                sendMessage($chat_id, "An error has occurred", $message_id, $adminKey);
            }
        }
        return true;
    }
    elseif(isFind($step, 'msg_')) {
        $target = str_replace('msg_', '', $step);
        if(isUserAdmin($from_id)) {
            if(isUserExist($target)) {
                $mention2 = mentionUser($target);
                if(isset($message->document)) {
                    $file_id = $message->document->file_id;
                    $caption = $message->caption;
                    $info = sendDocument($target, $file_id, $caption);
                    $msgid = $info->result->message_id;
                    setUser($from_id, 'step', '-1');
                    sendMessage($chat_id, "Your message has sent", $message_id, retIKey7($target, $msgid));
                    sendMessage($chat_id, "Back to Home", -1, $adminKey);
                    sendAdminMessage("#admin\nAdmin $mention has sent a file to $mention2");
                }
                elseif(isset($message->video)) {
                    $file_id = $message->video->file_id;
                    $caption = $message->caption;
                    $info = sendVideo($target, $file_id, $caption);
                    $msgid = $info->result->message_id;
                    setUser($from_id, 'step', '-1');
                    sendMessage($chat_id, "Your message has sent", $message_id, retIKey7($target, $msgid));
                    sendMessage($chat_id, "Back to Home", -1, $adminKey);
                    sendAdminMessage("#admin\nAdmin $mention has sent a video to $mention2");
                }
                elseif(isset($message->audio)) {
                    $file_id = $message->audio->file_id;
                    $caption = $message->caption;
                    $info = sendAudio($target, $file_id, $caption);
                    $msgid = $info->result->message_id;
                    setUser($from_id, 'step', '-1');
                    sendMessage($chat_id, "Your message has sent", $message_id, retIKey7($target, $msgid));
                    sendMessage($chat_id, "Back to Home", -1, $adminKey);
                    sendAdminMessage("#admin\nAdmin $mention has sent a audio to $mention2");
                }
                elseif(isset($message->photo)) {
                    $photo = $message->photo;
                    $file_id = $photo[count($photo)-1]->file_id;
                    $caption = $message->caption;
                    $info = sendPhoto($target, $file_id, $caption);
                    $msgid = $info->result->message_id;
                    setUser($from_id, 'step', '-1');
                    sendMessage($chat_id, "Your message has sent", $message_id, retIKey7($target, $msgid));
                    sendMessage($chat_id, "Back to Home", -1, $adminKey);
                    sendAdminMessage("#admin\nAdmin $mention has sent a photo to $mention2");
                }
                elseif(isset($message->text)) {
                    $info = sendMessage($target, $text);
                    $msgid = $info->result->message_id;
                    setUser($from_id, 'step', '-1');
                    sendMessage($chat_id, "Your message has sent", $message_id, retIKey7($target, $msgid));
                    sendMessage($chat_id, "Back to Home", -1, $adminKey);
                    sendAdminMessage("#admin\nAdmin $mention has sent a text to $mention2");
                }
                else {
                    setUser($from_id, 'step', '-1');
                    sendMessage($chat_id, "An error has occurred", $message_id, $adminKey);
                }
            }
            else {
                setUser($from_id, 'step', '-1');
                sendMessage($chat_id, "User is not exist", $message_id, $adminKey);
            }
        }
        return true;
    }
    elseif(isFind($step, 'setcredit_')) {
        $credit = str_replace('setcredit_', '', $step);
        $price = (int) filter_var(strip_tags(toEnNumber($text)), FILTER_SANITIZE_NUMBER_INT);
        if(isUserAdmin($from_id) && getUserAdmin($from_id) > 1) {
            if($price > 0 && $price < 101) {
                $domain = "NULL";
                $res = mysqli_query($db, "SELECT * FROM `credits` WHERE `id` = '$credit'");
                $rows = mysqli_num_rows($res);
                if($rows > 0) {
                    while($row = mysqli_fetch_assoc($res)) {
                        $domain = $row['domain'];
                    }
                }
                sendQuery("UPDATE `credits` SET `price` = '$price' WHERE `id` = '$credit'");
                setUser($from_id, 'step', '-1');
                sendMessage($chat_id, "Credit of <code>$domain</code> has set to <code>$price</code>", $message_id, $adminKey);
            }
            else {
                setUser($from_id, 'step', "setcredit_$credit");
                sendMessage($chat_id, "Invalid credit entered. Try a credit between 1 to 100", $message_id, $backKey);
            }
        }
        return true;
    }
    elseif($step == 'chg_shcookies' || $step == 'chg_rfcookies' || $step == 'chg_adcookies') {
        if(isUserAdmin($from_id) && getUserAdmin($from_id) > 1) {
            $ck = 'shutterstock';
            if($step == 'chg_rfcookies') {
                $ck = '123rf';
            }
            elseif($step == 'chg_adcookies') {
                $ck = 'adobe';
            }
            if(isset($message->document)) {
                $file_id = $message->document->file_id;
                $dl = getDirectDownloadLinkWithFileID($file_id);
                if(isLink($dl)) {
                    $last = (int) filter_var(getLastCookie($ck), FILTER_SANITIZE_NUMBER_INT);
                    $msg = sendMessage($chat_id, "Downloading file...", $message_id);
                    $msgid = $msg->result->message_id;
                    $filename = downloadFile($dl);
                    editMessageText($chat_id, $msgid, "File has downloaded successfully");
                    $ex = getExtension($filename);
                    if($ex == 'txt') {
                        rename($filename, "api/cookies/$ck/cookies".($last == 0 ? 1 : ($last + 1)).".$ex");
                        setUser($from_id, 'step', '-1');
                        editMessageText($chat_id, $msgid, "File has been uploaded");
                        sendMessage($chat_id, "Back to Home", -1, $adminKey);
                    }
                    else {
                        unlink($filename);
                        setUser($from_id, 'step', '-1');
                        editMessageText($chat_id, $msgid, "File format should be <code>txt</code>");
                    }
                }
                else {
                    setUser($from_id, 'step', '-1');
                    editMessageText($chat_id, $msgid, "Failed to download the file");
                }
            }
            elseif(isset($message->text)) {
                if($text == 'delete') {
                    $cookies = getCookies($ck)['cookies'];
                    if($ck == 'shutterstock') {
                        setUser($from_id, 'step', 'cookie_shdelete');
                    }
                    elseif($ck == '123rf') {
                        setUser($from_id, 'step', 'cookie_rfdelete');
                    }
                    elseif($ck == 'adobe') {
                        setUser($from_id, 'step', 'cookie_addelete');
                    }
                    sendMessage($chat_id, "Are you sure you want to delete <code>$cookies</code> cookies from the server?\nIf you are sure about what you are doing, use confirm button", $message_id, retIKey25($from_id));
                    sendMessage($chat_id, "Back to Home", -1, $backKey);
                }
            }
            else {
                setUser($from_id, 'step', $step);
                sendMessage($chat_id, "You should send me file as a <code>document</code>. Try another one.", $message_id, $backKey);
            }
        }
        return true;
    }
    elseif($step == 'chg_credits') {
        if(isUserAdmin($from_id) && getUserAdmin($from_id) > 1) {
            $domain = getDomain(toEnNumber(urldecode($text)));
            if(!isCreditExist($domain)) {
                $credit = addCredit($domain, $from_id);
                setUser($from_id, 'step', "setcredit_$credit");
                sendMessage($chat_id, "Send me the credit you want to set for domain <code>$domain</code>", $message_id, $backKey);
            }
            else {
                deleteCredit($domain);
                setUser($from_id, 'step', '-1');
                sendMessage($chat_id, "Domain <code>$domain</code> has deleted", $message_id, $adminKey);
            }
        }
        return true;
    }
    elseif($step == 'chg_invite') {
        if(isUserAdmin($from_id) && getUserAdmin($from_id) > 1) {
            $users = (int) filter_var(strip_tags(toEnNumber($text)), FILTER_SANITIZE_NUMBER_INT);
            if($users > 0 && $users < 11) {
                setSettings('max_invite', $users);
                sendAdminMessage("#admin\nAdmin $mention has set <b>minimum users should invite to get a prize</b> to <b>$users</b>");
                setUser($from_id, 'step', '-1');
                sendMessage($chat_id, "Back to Home", -1, $adminKey);
            }
            else {
                setUser($from_id, 'step', 'chg_invite');
                sendMessage($chat_id, "Enter a number between <b>1</b> to <b>10</b>", $message_id, $backKey);
            }
        }
        return true;
    }
    elseif($step == 'chg_percentprize') {
        if(isUserAdmin($from_id) && getUserAdmin($from_id) > 1) {
            $percent = (int) filter_var(strip_tags(toEnNumber($text)), FILTER_SANITIZE_NUMBER_INT);
            if($percent >= 0 && $percent <= 100) {
                setSettings('percent_prize', $percent);
                sendAdminMessage("#admin\nAdmin $mention has set <b>user referrals' prize</b> to <b>$percent%</b>");
                setUser($from_id, 'step', '-1');
                sendMessage($chat_id, "Back to Home", -1, $adminKey);
            }
            else {
                setUser($from_id, 'step', 'chg_percentprize');
                sendMessage($chat_id, "Enter a number between <b>0</b> to <b>100</b>", $message_id, $backKey);
            }
        }
        return true;
    }
    elseif($step == 'chg_price') {
        if(isUserAdmin($from_id) && getUserAdmin($from_id) > 1) {
            $price = (int) filter_var(strip_tags(toEnNumber($text)), FILTER_SANITIZE_NUMBER_INT);
            setUser($from_id, 'step', '-1');
            if($price > -1 && $price < 1000000) {
                setSettings('post_price', $price);
                sendAdminMessage("#admin\nAdmin $mention has set <b>Instagram Campaign</b> needed Stock Coins to <b>".number_format($price)."</b>");
                sendMessage($chat_id, "Back to Home", -1, $adminKey);
            }
            else {
                sendMessage($chat_id, "Invalid number sent", $message_id, $adminKey);
            }
        }
        return true;
    }
    elseif($step == 'chg_nude') {
        if(isUserAdmin($from_id) && getUserAdmin($from_id) > 1) {
            $price = filter_var(strip_tags(toEnNumber($text)), FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            setUser($from_id, 'step', '-1');
            if($price > -1 && $price < 1000000) {
                setSettings('nude_price', $price);
                sendAdminMessage("#admin\nAdmin $mention has set <b>Nude</b> needed Stock Coins to <b>$price</b>");
                sendMessage($chat_id, "Back to Home", -1, $adminKey);
            }
            else {
                sendMessage($chat_id, "Invalid number sent", $message_id, $adminKey);
            }
        }
        return true;
    }
    elseif($step == 'chg_post') {
        if(isUserAdmin($from_id) && getUserAdmin($from_id) > 1) {
            $channel = $channel_forward_chat_id;
            if(isset($channel)) {
                $rank = getChatMember($channel, $bot_id);
                if($rank == 'administrator' || $rank == 'creator') {
                    $name = bot('getChat', array('chat_id' => $channel))->result->title;
                    setSettings('post_channel', $channel);
                    setUser($from_id, 'step', '-1');
                    sendAdminMessage("#admin\nAdmin $mention has set <b>Instagram Campaign</b>'s chat to <b>$name</b> (<b>$channel</b>)");
                    sendMessage($chat_id, "Back to Home", -1, $adminKey);
                }
                else {
                    setUser($from_id, 'step', '-1');
                    sendMessage($chat_id, "I don't have access to chat you forwarded from", $message_id, $adminKey);
                }
            }
            else {
                setUser($from_id, 'step', 'chg_post');
                sendMessage($chat_id, "You should <b>FORWARD</b> me a message from chat you want to set", $message_id, $backKey);
            }
        }
        return true;
    }
    elseif($step == 'chg_secret') {
        if(isUserAdmin($from_id) && getUserAdmin($from_id) > 1) {
            $channel = $channel_forward_chat_id;
            if(isset($channel)) {
                $rank = getChatMember($channel, $bot_id);
                if($rank == 'administrator' || $rank == 'creator') {
                    $name = bot('getChat', array('chat_id' => $channel))->result->title;
                    setSettings('secret_channel', $channel);
                    setUser($from_id, 'step', '-1');
                    sendAdminMessage("#admin\nAdmin $mention has set <b>Secret Channel</b>'s chat to <b>$name</b> (<b>$channel</b>)");
                    sendMessage($chat_id, "Back to Home", -1, $adminKey);
                }
                else {
                    setUser($from_id, 'step', '-1');
                    sendMessage($chat_id, "I don't have access to chat you forwarded from", $message_id, $adminKey);
                }
            }
            else {
                setUser($from_id, 'step', 'chg_secret');
                sendMessage($chat_id, "You should <b>FORWARD</b> me a message from chat you want to set", $message_id, $backKey);
            }
        }
        return true;
    }
    elseif($step == 'chg_sellers') {
        if(isUserAdmin($from_id) && getUserAdmin($from_id) > 1) {
            $channel = $channel_forward_chat_id;
            if(isset($channel)) {
                $rank = getChatMember($channel, $bot_id);
                if($rank == 'administrator' || $rank == 'creator') {
                    $name = bot('getChat', array('chat_id' => $channel))->result->title;
                    setSettings('sellers_channel', $channel);
                    setUser($from_id, 'step', '-1');
                    sendAdminMessage("#admin\nAdmin $mention has set <b>Sellers Market</b> to <b>$name</b> (<b>$channel</b>)");
                    sendMessage($chat_id, "Back to Home", -1, $adminKey);
                }
                else {
                    setUser($from_id, 'step', '-1');
                    sendMessage($chat_id, "I don't have access to chat you forwarded from", $message_id, $adminKey);
                }
            }
            else {
                setUser($from_id, 'step', 'chg_sellers');
                sendMessage($chat_id, "You should <b>FORWARD</b> me a message from chat you want to set", $message_id, $backKey);
            }
        }
        return true;
    }
    elseif($step == 'chg_cron') {
        if(isUserAdmin($from_id) && getUserAdmin($from_id) > 1) {
            $cron = (int) filter_var(strip_tags(toEnNumber($text)), FILTER_SANITIZE_NUMBER_INT);
            setUser($from_id, 'step', '-1');
            if($cron > -1 && $cron < (getUsers() - PER_MIN)) {
                setSettings('cron', $cron);
                sendAdminMessage("#admin\nAdmin $mention has set <b>CronJob</b> checking to <b>$cron</b>");
                sendMessage($chat_id, "Back to Home", -1, $adminKey);
            }
            else {
                sendMessage($chat_id, "Invalid number sent", $message_id, $adminKey);
            }
        }
        return true;
    }
    elseif($step == 'chg_paypal') {
        if(isUserAdmin($from_id) && getUserAdmin($from_id) > 1) {
            setSettings('paypal', strip_tags($text));
            sendAdminMessage("#admin\nAdmin $mention has set a new address for <b>PayPal</b>: <code>$text</code>");
            setUser($from_id, 'step', '-1');
            sendMessage($chat_id, "Back to Home", -1, $adminKey);
        }
        return true;
    }
    elseif($step == 'chg_webmoney') {
        if(isUserAdmin($from_id) && getUserAdmin($from_id) > 1) {
            setSettings('webmoney', strip_tags($text));
            sendAdminMessage("#admin\nAdmin $mention has set a new address for <b>WebMoney</b>: <code>$text</code>");
            setUser($from_id, 'step', '-1');
            sendMessage($chat_id, "Back to Home", -1, $adminKey);
        }
        return true;
    }
    elseif($step == 'chg_usdt') {
        if(isUserAdmin($from_id) && getUserAdmin($from_id) > 1) {
            setSettings('usdt', strip_tags($text));
            sendAdminMessage("#admin\nAdmin $mention has set a new address for <b>USDT</b>: <code>$text</code>");
            setUser($from_id, 'step', '-1');
            sendMessage($chat_id, "Back to Home", -1, $adminKey);
        }
        return true;
    }
    elseif($step == 'chg_upi') {
        if(isUserAdmin($from_id) && getUserAdmin($from_id) > 1) {
            setSettings('upi', strip_tags($text));
            sendAdminMessage("#admin\nAdmin $mention has set a new address for <b>UPI</b>: <code>$text</code>");
            setUser($from_id, 'step', '-1');
            sendMessage($chat_id, "Back to Home", -1, $adminKey);
        }
        return true;
    }
    elseif($step == 'chg_stripe') {
        if(isUserAdmin($from_id) && getUserAdmin($from_id) > 1) {
            setSettings('stripe', strip_tags($text));
            sendAdminMessage("#admin\nAdmin $mention has set a new address for <b>Stripe</b>: <code>$text</code>");
            setUser($from_id, 'step', '-1');
            sendMessage($chat_id, "Back to Home", -1, $adminKey);
        }
        return true;
    }
    elseif($step == 'chg_toman') {
        if(isUserAdmin($from_id) && getUserAdmin($from_id) > 1) {
            setSettings('usdt', strip_tags($text));
            sendAdminMessage("#admin\nAdmin $mention has set a new address for <b>Toman API</b>: <code>$text</code>");
            setUser($from_id, 'step', '-1');
            sendMessage($chat_id, "Back to Home", -1, $adminKey);
        }
        return true;
    }
    elseif($step == 'contact') {
        if(!isPhoneVerified($from_id)) {
            if(isset($message->contact)) {
                if($message->contact->user_id == $from_id) {
                    $number = $message->contact->phone_number;
                    if(strpos($number, '98') === 0 || strpos($number, '+98') === 0) {
                        $number = '0'.strrev(substr(strrev($number), 0, 10));
                        setUser($from_id, 'number', $number);
                        setUser($from_id, 'step', '-1');
                        sendMessage($chat_id, "Your phone number verified", $message_id, $startKey);
                        checkPremiumQuests($from_id, '2');
                    }
                    else {
                        setUser($from_id, 'step', '-1');
                        sendMessage($chat_id, "Only Iranian phone numbers will be verified", $message_id, $startKey);
                    }
                }
                else {
                    setUser($from_id, 'step', 'contact');
                    sendMessage($chat_id, "Sent phone number doesn't belong to you", $message_id, $contactKey);
                }
            }
            else {
                setUser($from_id, 'step', 'contact');
                sendMessage($chat_id, "Send your <b>phone number</b> using button below", $message_id, $contactKey);
            }
        }
        else {
            setUser($from_id, 'step', '-1');
            sendMessage($chat_id, "Your phone number was already verified", $message_id, $startKey);
        }
        return true;
    }
    elseif($step == 'openticket') {
        $message = makeAntiInjection($text);
        $ticketid = createTicket($from_id, $message);
        setUser($from_id, 'step', '-1');
        if($ticketid != false) {
            sendMessage($chat_id, "📤 Ticket\n📝 $message", $message_id, retIKey15($ticketid));
        }
        else {
            sendMessage($chat_id, "Failed to get ticket's informations", $message_id, $startKey);
        }
    }
    elseif($step == 'addreminder') {
        if(isUserAdmin($from_id)) {
            $days = (int) filter_var(toEnNumber($text), FILTER_SANITIZE_NUMBER_INT);
            if($days > 0 && $days < 1001) {
                setUser($from_id, 'step', "addreminder_$days");
                sendMessage($chat_id, "Send me the information you want to get noticed", $message_id, $backKey);
            }
            else {
                setUser($from_id, 'step', 'addreminder');
                sendMessage($chat_id, "Days should be between <b>1 to 1000</b>\nTry another one", $message_id, $backKey);
            }
        }
        return true;
    }
    elseif(isFind($step, 'addreminder_')) {
        if(isUserAdmin($from_id)) {
            $days = str_replace('addreminder_', '', $step);
            $information = strip_tags($text);
            $time = (time() + DAYS($days));
            $id = addReminder($information, $from_id, $time);
            $date = date('Y-m-d H:i:s', $time);
            if($id != null) {
                setUser($from_id, 'step', '-1');
                sendMessage($chat_id, "ReminderID#<code>$id</code> has been added and you will get noticed this reminder at <code>$date</code>", $message_id, $adminKey);
            }
            else {
                setUser($from_id, 'step', '-1');
                sendMessage($chat_id, "Something went wrong", $message_id, $adminKey);
            }
        }
        return true;
    }
    elseif(isFind($step, 'ansticket_')) {
        $ticketid = str_replace('ansticket_', '', $step);
        $userid = getTicket($ticketid, 'user_id');
        $sent = getTicket($ticketid, 'sent');
        $message = makeAntiInjection($text);
        if($sent == 1 && strlen($message) > 0) {
            $key = retIKey16($ticketid);
            setUser($from_id, 'step', '-1');
            if(isUserAdmin($from_id)) {
                sendMessage($from_id, "Message has sent to ticket#$ticketid", $message_id, $adminKey);
                sendMessage($userid, "📥 #Ticket(ID:<code>$ticketid</code>)\n👤 $mention\n<code>_____________________________________________________</code>\n$message\n<code>_____________________________________________________</code>", $message_id, $key);
                sendAdminMessage("#admin\nAdmin $mention has sent a message to ticket#$ticketid");
            }
            else {
                sendMessage($from_id, "Message has sent to ticket#$ticketid", $message_id, $startKey);
                sendAdminMessage("#admin\n\n📥 #Ticket(ID:<code>$ticketid</code>)\n👤 $mention\n<code>_____________________________________________________</code>\n$message\n<code>_____________________________________________________</code>", $key);
            }
        }
        return true;
    }
    elseif($step == 'apiuse') {
        if(isLink($text)) {
            $link = strip_tags($text);
            $api = json_decode(file_get_contents("https://y4siiiin.com/api/?url=$link&secret=".getUser($from_id, 'secret')));
            $message = "";
            if($api->status) {
                if(isset($api->result->filename)) $message .= "- Filename: <b>".$api->result->filename."</b>\n";
                if(isset($api->result->filesize)) $message .= "- Size: <b>".$api->result->filesize."</b>\n";
                if(isset($api->result->download)) $message .= "- Download: <b>".$api->result->download."</b>\n";
                if(isset($api->result->license)) $message .= "- License: <b>".$api->result->license."</b>\n";
                setUser($from_id, 'step', '-1');
                sendMessage($chat_id, "API response:\n\n_____________________________________\n$message"."_____________________________________", $message_id, $startKey);
            }
            else {
                setUser($from_id, 'step', '-1');
                sendMessage($chat_id, "There was an error in your request to API\nError: <code>".$api->result->message."</code>", $message_id, $startKey);
            }
        }
        else {
            setUser($from_id, 'step', 'apiuse');
            sendMessage($chat_id, "Send a valid link", $message_id, $backKey);
        }
    }
    elseif($step == 'trcoins') {
        $target = (int) filter_var(toEnNumber($text), FILTER_SANITIZE_NUMBER_INT);
        if(isUserExist($target)) {
            $ment = mentionUser($target);
            setUser($from_id, 'step', "trcoi_$target");
            sendMessage($chat_id, "How much <b>Stock Coins</b> do you want to transfer to $ment", $message_id, $backKey);
        }
        else {
            setUser($from_id, 'step', 'trcoins');
            sendMessage($chat_id, "<b>*</b> Invalid <b>user id</b> sent\nSend me their <b>UserID</b> to transfer coins", $message_id, $backKey);
        }
    }
    elseif(isFind($step, 'trcoi_')) {
        $target = str_replace('trcoi_', '', $step);
        if($from_id != $target && isUserExist($target)) {
            $ment = mentionUser($target);
            $balance = getUser($from_id, 'balance');
            $coins = filter_var(strip_tags(toEnNumber($text)), FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            if($coins > 0) {
                if($balance >= $coins) {
                    setUser($from_id, 'step', '-1');
                    sendMessage($chat_id, "Are you sure you want to transfer <b>$coins Stock Coins</b> to $ment?", $message_id, retIKey19($target, $coins));
                    sendMessage($chat_id, "Back to Home", -1, $startKey);
                }
                else {
                    setUser($from_id, 'step', "trcoi_$target");
                    sendMessage($chat_id, "<b>*</b> You don't have enough <b>Stock Coins</b> to send\nHow much <b>Stock Coins</b> do you want to transfer to $ment", $message_id, $backKey);
                }
            }
            else {
                setUser($from_id, 'step', "trcoi_$target");
                sendMessage($chat_id, "<b>*</b> Invalid coins sent\nHow much <b>Stock Coins</b> do you want to transfer to $ment", $message_id, $backKey);
            }
        }
        else {
            setUser($from_id, 'step', '-1');
            sendMessage($chat_id, "Something went wrong", $message_id, $startKey);
        }
    }
    elseif($step == 'hwsellers_newpost') {
        $file_id = '-1';
        $size = 0;
        $type = 'null';
        if(isset($message->photo)) {
            $photo = $message->photo;
            $file_id = $photo[count($photo)-1]->file_id;
            $size = $photo[count($photo)-1]->file_size;
            $type = 'photo';
        }
        else {
            $types = "";
            foreach(SELLERS_CATEGORIES_HW as $category) {
                $types .= "<code>$category</code>, ";
            }
            $types = substr($types, 0, -1);
            $types = substr($types, 0, -1);
            setUser($from_id, 'step', 'hwsellers_newpost');
            sendMessage($chat_id, "<b>* You should send your product in PHOTO type of files</b>\nSend me your product's image\n\n<b>* Allowed types of products:</b>\n<b>-</b> $types", $message_id, $backKey);
        }
        if($file_id != '-1') {
            $id = createVFile($from_id, $type, $file_id, $size, 2);
            if($id != null) {
                setUser($from_id, 'step', "sellers2_$id");
                sendMessage($chat_id, "Send me a name about your product", $message_id, $backKey);
            }
            else {
                setUser($from_id, 'step', '-1');
                sendMessage($chat_id, "Something went wrong", $message_id, $sellersKey);
            }
        }
    }
    elseif($step == 'sellers_newpost') {
        $file_id = '-1';
        $size = 0;
        $type = 'null';
        if(isset($message->document)) {
            $file_id = $message->document->file_id;
            $size = $message->document->file_size;
            $type = 'document';
        }
        elseif(isset($message->audio)) {
            $file_id = $message->audio->file_id;
            $size = $message->audio->file_size;
            $type = 'audio';
        }
        else {
            $types = "";
            foreach(SELLERS_CATEGORIES_SW as $category) {
                $types .= "<code>$category</code>, ";
            }
            $types = substr($types, 0, -1);
            $types = substr($types, 0, -1);
            setUser($from_id, 'step', 'sellers_newpost');
            sendMessage($chat_id, "<b>* You should send your art in DOCUMENT or AUDIO type of files</b>\nSend me your art\n\n<b>* Allowed types of arts:</b>\n<b>-</b> $types", $message_id, $backKey);
        }
        if($file_id != '-1') {
            $id = createVFile($from_id, $type, $file_id, $size);
            if($id != null) {
                setUser($from_id, 'step', "sellers1_$id");
                sendMessage($chat_id, "Now send me a preview of your art", $message_id, $backKey);
            }
            else {
                setUser($from_id, 'step', '-1');
                sendMessage($chat_id, "Something went wrong", $message_id, $sellersKey);
            }
        }
    }
    elseif(isFind($step, 'sellers1_')) {
        $id = str_replace('sellers1_', '', $step);
        $type = getVFile($id, 'type');
        $done = false;
        if($type == 'document') {
            if(isset($message->video)) {
                $file_id = $message->video->file_id;
                setVFile($id, 'p_type', 'video');
                setVFile($id, 'p_file_id', $file_id);
                $done = true;
            }
            elseif(isset($message->photo)) {
                $photo = $message->photo;
                $file_id = $photo[count($photo)-1]->file_id;
                setVFile($id, 'p_type', 'photo');
                setVFile($id, 'p_file_id', $file_id);
                $done = true;
            }
            else {
                setUser($from_id, 'step', "sellers1_$id");
                sendMessage($chat_id, "<b>* You should send me the preview as VIDEO or PHOTO type of file</b>\nNow send me a preview of your art", $message_id, $backKey);
            }
        }
        elseif($type == 'audio') {
            if(isset($message->voice)) {
                $file_id = $message->voice->file_id;
                setVFile($id, 'p_type', 'voice');
                setVFile($id, 'p_file_id', $file_id);
                $done = true;
            }
            elseif(isset($message->audio)) {
                $file_id = $message->audio->file_id;
                setVFile($id, 'p_type', 'audio');
                setVFile($id, 'p_file_id', $file_id);
                $done = true;
            }
            else {
                setUser($from_id, 'step', "sellers1_$id");
                sendMessage($chat_id, "<b>* You should send me the preview as VOICE or AUDIO type of file</b>\nNow send me a preview of your art", $message_id, $backKey);
            }
        }
        if($done) {
            setUser($from_id, 'step', "sellers2_$id");
            sendMessage($chat_id, "Send me a name about your art", $message_id, $backKey);
        }
    }
    elseif(isFind($step, 'sellers2_')) {
        $id = str_replace('sellers2_', '', $step);
        $ware = getVFile($id, 'ware');
        $name = makeAntiInjection($text);
        setVFile($id, 'name', $name);
        setUser($from_id, 'step', "sellers3_$id");
        sendMessage($chat_id, "Send me a description about your ".($ware == 1 ? "art" : "product"), $message_id, $backKey);
    }
    elseif(isFind($step, 'sellers3_')) {
        $id = str_replace('sellers3_', '', $step);
        $ware = getVFile($id, 'ware');
        $description = makeAntiInjection($text);
        setVFile($id, 'description', $description);
        setUser($from_id, 'step', "sellers4_$id");
        sendMessage($chat_id, "Send me the price of your ".($ware == 1 ? "art" : "product"), $message_id, $backKey);
    }
    elseif(isFind($step, 'sellers4_')) {
        $id = str_replace('sellers4_', '', $step);
        $ware = getVFile($id, 'ware');
        $price = filter_var(makeAntiInjection(toEnNumber($text)), FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $max = 500.0;
        if(isUserAdmin($from_id)) {
            $max = 2000.0;
        }
        if($price > 0 && $price <= $max) {
            $key = getSellersCategories($ware);
            setVFile($id, 'price', $price);
            setUser($from_id, 'step', "sellers5_$id");
            sendMessage($chat_id, "As the final step, choose a category for your ".($ware == 1 ? "art" : "product")." from the buttons below", $message_id, $key);
        }
        else {
            setUser($from_id, 'step', "sellers4_$id");
            sendMessage($chat_id, "<b>* Price of your art should be between 0.1 to $max</b>\nSend me the price of your ".($ware == 1 ? "art" : "product"), $message_id, $backKey);
        }
    }
    elseif(isFind($step, 'sellers5_')) {
        $id = str_replace('sellers5_', '', $step);
        $ware = getVFile($id, 'ware');
        $type = getVFile($id, 'type');
        $file_id = getVFile($id, 'file_id');
        $category = makeAntiInjection($text);
        if(($ware == '1' && in_array($category, SELLERS_CATEGORIES_SW)) || ($ware == '2' && in_array($category, SELLERS_CATEGORIES_HW))) {
            setVFile($id, 'category', $category);
            setVFile($id, 'status', '0');
            $name = getVFile($id, 'name');
            $description = getVFile($id, 'description');
            $price = getVFile($id, 'price');
            $key = ($ware == '1' ? retIKey20($id) : retIKey27($id));
            $rec_name = getVFileName($id);
            $message = "#MARKET_POST #$rec_name\nUser $mention has uploaded a new post for sellers market (<b>".($ware == 1 ? "Software" : "Hardware")."</b>)\n\nName: <code>$name</code>\nDescription: <code>$description</code>\nCategory: <code>$category</code>\nPrice: <code>$$price</code>";
            $res = mysqli_query($db, "SELECT * FROM `users` WHERE `admin` > '2'");
            $rows = mysqli_num_rows($res);
            if($rows > 0) {
                while($row = mysqli_fetch_assoc($res)) {
                    if($type == 'document') {
                        sendDocument($row['id'], $file_id, $message, -1, $key);
                    }
                    elseif($type == 'audio') {
                        sendAudio($row['id'], $file_id, $message, -1, $key);
                    }
                    elseif($type == 'photo') {
                        sendPhoto($row['id'], $file_id, $message, -1, $key);
                    }
                }
            }
            setUser($from_id, 'step', 'sellers');
            sendMessage($chat_id, "Your ".($ware == 1 ? "art" : "product")." has been submitted, wait for admins to reply", $message_id, $sellersKey);
        }
        else {
            setUser($from_id, 'step', "sellers5_$id");
            sendMessage($chat_id, "<b>* Choose a category from the list below</b>\nAs the final step, choose a category for your ".($ware == 1 ? "art" : "product")." from the buttons below", $message_id, $key);
        }
    }
    elseif(isFind($step, 'sprplc_')) {
        $id = str_replace('sprplc_', '', $step);
        $type = getVFile($id, 'type');
        $file_id = '-1';
        $size = '0';
        if($type == 'document' && isset($message->document)) {
            $file_id = $message->document->file_id;
            $size = $message->document->file_size;
        }
        elseif($type == 'audio' && isset($message->audio)) {
            $file_id = $message->audio->file_id;
            $size = $message->audio->file_size;
        }
        else {
            setUser($from_id, 'step', "sprplc_$id");
            sendMessage($chat_id, "<b>* Send me the file in the same format as current file has</b>\nFor replacing file send me the file again with new name", $message_id, $backKey);
        }
        if($file_id != '-1') {
            $old_size = getVFile($id, 'filesize');
            if(($old_size - 1024) <= $size && ($old_size + 1024) >= $size) {
                setVFile($id, 'file_id', $file_id);
                setVFile($id, 'filesize', $size);
                setUser($from_id, 'step', '-1');
                sendMessage($chat_id, "File has been modified", $message_id, $adminKey);
            }
            else {
                setUser($from_id, 'step', "sprplc_$id");
                sendMessage($chat_id, "<b>* Sent file's size does not match with the current file</b>\nFor replacing file send me the file again with new name", $message_id, $backKey);
            }
        }
    }
    elseif(isFind($step, 'buyhost_')) {
        $plan = str_replace('buyhost_', '', $step);
        if(isLink($text)) {
            $domain = getDomain($text);
            setUser($from_id, 'step', "bhst_$plan:$domain");
            sendMessage($chat_id, "Send me your contact email for your host", $message_id, $backKey);
        }
        else {
            setUser($from_id, 'step', "buyhost_$plan");
            sendMessage($chat_id, "<b>* Invalid domain</b>\nPlease send me your domain", $message_id, $backKey);
        }
    }
    elseif(isFind($step, 'bhst_')) {
        $info = str_replace('bhst_', '', $step);
        $plan = explode(':', $info)[0];
        $domain = explode(':', $info)[1];
        if(filter_var($text, FILTER_VALIDATE_EMAIL)) {
            setUser($from_id, 'step', "bhst:$plan:$domain:$text");
            sendMessage($chat_id, "You are about to buy <b>$plan ".($plan < 2 ? "month" : "months")."</b> hosting server for <b>".HOST_MONTH["$plan"]." Stock Coins</b>\nIf you are sure about this payment, use confirm button", $message_id, retIKey25($from_id));
            sendMessage($chat_id, "Back to Home", -1, $backKey);
        }
        else {
            setUser($from_id, 'step', "bhst_$plan:$domain");
            sendMessage($chat_id, "<b>* Invalid email</b>\nSend me your contact email for your host", $message_id, $backKey);
        }
    }
    elseif($step == 'delans') {
        if(isUserAdmin($from_id) && getUserAdmin($from_id) > 1) {
            $id = (int) filter_var(toEnNumber($text), FILTER_SANITIZE_NUMBER_INT);
            if(isAnswerExist($id)) {
                sendQuery("DELETE FROM `answers` WHERE `id` = '$id'");
                setUser($from_id, 'step', 'delans');
                sendMessage($chat_id, "<b>* AnswerID $id deleted</b>\nSend me the AnswerID to delete", $message_id, $backKey);
            }
            else {
                setUser($from_id, 'step', 'delans');
                sendMessage($chat_id, "<b>* Entered AnswerID does not exist</b>\nSend me the AnswerID to delete", $message_id, $backKey);
            }
        }
    }
    elseif(isFind($step, 'hostdomain_')) {
        $host_id = str_replace('hostdomain_', '', $step);
        $owner = getHost($host_id, 'user_id');
        $username = getHost($host_id, 'username');
        $domain = getHost($host_id, 'domain');
        $status = getHost($host_id, 'status');
        if($owner == $from_id) {
            if(isLink($text)) {
                $newdomain = getDomain($text);
                $result = changeHostDomain($username, $newdomain);
                if($result) {
                    setHost($host_id, 'domain', $newdomain);
                    setUser($from_id, 'step', '-1');
                    sendMessage($chat_id, "Your host (<code>$username</code>)'s domain has changed from <code>$domain</code> to <code>$newdomain</code>", $message_id, $hostKey);
                    sendAdminMessage("#admin\nUser $mention has changed their host (<code>$username</code>)'s domain from <b>$domain</b> to <b>$newdomain</b>");
                }
                else {
                    setUser($fromid, 'step', "hostdomain_$host_id");
                    sendMessage($chatid, "<b>* Something went wrong with the target domain, send me another one</b>\nSend me new domain for your host (<code>$username</code>)\nCurrent Domain: <code>$domain</code>", $messageid, $backKey);
                }
            }
            else {
                setUser($fromid, 'step', "hostdomain_$host_id");
                sendMessage($chatid, "<b>* Send me a DOMAIN</b>\nSend me new domain for your host (<code>$username</code>)\nCurrent Domain: <code>$domain</code>", $messageid, $backKey);
            }
        }
        else {
            setUser($from_id, 'step', '-1');
            sendMessage($chat_id, "You are not the owner of this host", $message_id, $hostKey);
        }
    }
    elseif($step == 'nude') {
        $price = getSettings('nude_price');
        $balance = getUser($from_id, 'balance');
        if($balance >= $price) {
            if(isset($message->document)) {
                $file_id = $message->document->file_id;
                $dl_link = getDirectDownloadLinkWithFileID($file_id);
                $file_name = downloadFile($dl_link);
                $extension = getExtension($file_name);
                $new_name = randomString(10).".$extension";
                rename($file_name, "temp/$new_name");
                $id = sendNudeRequest($new_name);
                if($id != -1) {
                    setUser($from_id, 'balance', ($balance - $price));
                    setUser($from_id, 'step', "wnud_$id:0");
                    sendMessage($chat_id, "Your request has sent to the server.\n<b>PLEASE WAIT AND DON'T DO ANYTHING</b>", $message_id, $backKey);
                }
                else {
                    setUser($from_id, 'step', '-1');
                sendMessage($chat_id, "Something went wrong", $message_id, $startKey);
                }
            }
            else {
                setUser($from_id, 'step', 'nude');
                sendMessage($chat_id, "<b>Sent file is not document</b>\nPlease send me the photo as <b>DOCUMENT</b> type", $message_id, $backKey);
            }
        }
        else {
            setUser($from_id, 'step', '-1');
            sendMessage($chat_id, "You don't have enough Stock Coins", $message_id, $startKey);
        }
    }
    elseif(isFind($step, 'billing_')) {
        $sid = str_replace('billing_', '', $step);
        $address = makeAntiInjection($text);
        setVBFile($sid, 'address', $address);
        setUser($from_id, 'step', "billing2_$sid");
        sendMessage($chat_id, "Send me the zipcode related to address you sent", $message_id, $backKey);
    }
    elseif(isFind($step, 'billing2_')) {
        $sid = str_replace('billing2_', '', $step);
        $zipcode = makeAntiInjection($text);
        setVBFile($sid, 'zipcode', $zipcode);
        setUser($from_id, 'step', "billing3_$sid");
        sendMessage($chat_id, "Send me a phone number to get contact with you about delivery", $message_id, $backKey);
    }
    elseif(isFind($step, 'billing3_')) {
        $sid = str_replace('billing3_', '', $step);
        $s_id = getVBFile($sid, 's_id');
        $address = getVBFile($sid, 'address');
        $zipcode = getVBFile($sid, 'zipcode');
        $user_id = getVFile($s_id, 'user_id');
        $name = getVFile($s_id, 'name');
        $phone = makeAntiInjection($text);
        setVBFile($sid, 'phone', $phone);
        setUser($from_id, 'step', 'sellers');
        sendMessage($chat_id, "Your informations saved. Wait to get contacted", $message_id, $sellersKey);
        sendMessage($user_id, "User $mention has sent billing information for bought product (<b>$name</b>)\n\nAddress: <code>$address</code>\nZipcode: <code>$zipcode</code>\nPhone: <code>$phone</code>");
    }
    return false;
}

if((strtolower($text) == '/start' || strtolower($text) == 'back' || isFind(strtolower($text), '/start ')) && $tc == 'private') {
    if(strtolower($text) == 'back' && isFind(getUser($from_id, 'step'), 'getcode_')) {
        sendMessage($chat_id, "You are waiting for getting code. If you want to really stop this process, use /start", $message_id, $backKey);
        return false;
    }
    if(isFind(strtolower($text), '/start ')) {
        $info = str_replace('/start ', '', strtolower($text));
        if(isFind($info, 'inv_')) {
            $target = str_replace('inv_', '', $info);
            if(isUserExist($target) && !isUserExist($from_id)) {
                createUser($from_id, $target);
                sendMessage($target, "User $mention has started bot using your invite link");
                if(getUserInviteds($target) >= getSettings('max_invite') && isNumberDivisible(getUserInviteds($target), getSettings('max_invite'))) {
                    if(getUser($target, 'trial') != '0') {
                        setUser($target, 'trial', '0');
                        if(getSettings('trial') == '1') {
                            setUser($target, 'trial', '0');
                            sendMessage($target, "<b>*</b> Now you can use your <b>trial subscription</b> again");
                        }
                    }
                }
                checkPremiumQuests($target, '1');
            }
        }
        elseif(isFind($info, 'market_')) {
            $sid = str_replace('market_', '', $info);
            $ware = getVFile($sid, 'ware');
            $user_id = getVFile($sid, 'user_id');
            if(!isUserExist($from_id)) {
                createUser($from_id);
            }
            if($ware == '1') {
                if(!isUserBoughtVFile($from_id, $sid) && $from_id != $user_id) {
                    $file_id = getVFile($sid, 'p_file_id');
                    $type = getVFile($sid, 'p_type');
                    $price = getVFile($sid, 'price');
                    $caption = "Preview of file you are about to download";
                    $info = "";
                    if($type == 'photo') {
                        $info = sendPhoto($chat_id, $file_id, $caption, $message_id);
                    }
                    elseif($type == 'video') {
                        $info = sendVideo($chat_id, $file_id, $caption, $message_id);
                    }
                    elseif($type == 'voice') {
                        $info = sendVoice($chat_id, $file_id, $caption, $message_id);
                    }
                    elseif($type == 'audio') {
                        $info = sendAudio($chat_id, $file_id, $caption, $message_id);
                    }
                    if($info->ok) {
                        $msg_id = $info->result->message_id;
                        setUser($from_id, 'step', "buymarket_$sid");
                        sendMessage($chat_id, "You are about to buy this file for <b>$price Stock Coins</b>, if you are sure about this payment, use confirm button", $msg_id, retIKey25($from_id));
                        sendMessage($chat_id, "Back to Home", -1, $backKey);
                    }
                }
                else {
                    sendVFile($from_id, $sid, true);
                    setUser($from_id, 'step', '-1');
                    sendMessage($chat_id, "Back to Home", -1, $startKey);
                }
            }
            elseif($ware == '2') {
                $file_id = getVFile($sid, 'file_id');
                $type = getVFile($sid, 'type');
                $price = getVFile($sid, 'price');
                $caption = "Preview of product you are about to buy";
                $info = "";
                if($type == 'photo') {
                    $info = sendPhoto($chat_id, $file_id, $caption, $message_id);
                }
                if($info->ok) {
                    $msg_id = $info->result->message_id;
                    setUser($from_id, 'step', "buymarket_$sid");
                    sendMessage($chat_id, "You are about to buy this product for <b>$price Stock Coins</b>, if you are sure about this payment, use confirm button", $msg_id, retIKey25($from_id));
                    sendMessage($chat_id, "Back to Home", -1, $backKey);
                }
            }
            return false;
        }
    }
    setUser($from_id, 'step', '-1');
	sendMessage($chat_id, "Hello there!", $message_id, $startKey);
}
elseif(strtolower($text) == 'products' && $tc == 'private') {
    setUser($from_id, 'step', 'products');
    sendMessage($chat_id, "List of Products opened", $message_id, getProducts());
}
elseif(isProductExist($text) && $step == 'products' && $tc == 'private') {
    $dbid = getProduct($text, 'id');
    $info = getProduct($text, 'details');
    $price = getProduct($text, 'price');
    setUser($from_id, 'step', "buy_$text");
    sendMessage($chat_id, "You are about to buy this product:\n\n_____________________\nProduct Name: <code>$text</code>\nProduct Price: <code>$price</code> Stock Coins\nInformation: <code>$info</code>\n_____________________\n\nIf you are sure about buying this product, use confirm button", $message_id, retIKey25($from_id));
    sendMessage($chat_id, "Back to Home", -1, $backKey);
}
elseif(strtolower($text) == 'premium' && $tc == 'private') {
    setUser($from_id, 'step', 'premium');
    sendMessage($chat_id, "Premium Panel opened", $message_id, $prmKey);
}
elseif(strtolower($text) == 'pay' && $tc == 'private') {
    setUser($from_id, 'step', '-1');
    sendMessage($chat_id, "Payments Category opened", $message_id, $payKey);
}
elseif(strtolower($text) == 'subscriptions' && $tc == 'private') {
    setUser($from_id, 'step', '-1');
    sendMessage($chat_id, "Subscriptions Category opened", $message_id, $sbsKey);
}
elseif(strtolower($text) == 'buy' && $step == 'premium' && $tc == 'private') {
    setUser($from_id, 'step', 'prchoose');
    sendMessage($chat_id, "Choose a plan for your premium", $message_id, $psubKey);
}
elseif(isFind(strtolower($text), ' | (') && $step == 'prchoose' && $tc == 'private') {
    $month = (int) filter_var(strip_tags(toEnNumber(explode(' |', $text)[0])), FILTER_SANITIZE_NUMBER_INT);
    if($month > 0 && $month < 13) {
        $price = PREMIUM_PRICE["$month"];
        if($price != null && !empty($price) && $price > '0') {
            setUser($from_id, 'step', "bprm_$month:$price");
            sendMessage($chat_id, "You are about to buy <code>$month months premium subscription</code> for <code>$price Stock Coins</code>.\nIf you are sure about this payment, use confirm button", $message_id, retIKey25($from_id));
            sendMessage($chat_id, "Back to Home", -1, $backKey);
        }
        else {
            setUser($from_id, 'step', '-1');
            sendMessage($chat_id, "An error occurred", $message_id, $startKey); 
        }
    }
}
elseif(strtolower($text) == 'status' && $step == 'premium' && $tc == 'private') {
    $curtime = time();
    $time = getUser($from_id, 'premium');
    $str = "";
    if(!isPremium($from_id)) {
        $str = "<code>Expired</code>";
        if($time == '-1') {
            $str = "<code>Never Had Have</code>";
        }
    }
    else {
       $diff = secondsToTime(($time - $curtime));
       $str = '<code>'.$diff['days'].'</code> days, <code>'.$diff['hours'].'</code> hours, <code>'.$diff['minutes'].'</code> minutes & <code>'.$diff['seconds'].'</code> seconds left';
    }
    $quests = sizeof(unlockedQuests($from_id));
    $max = sizeof(PREMIUM_PRIZE);
    setUser($from_id, 'step', 'premium');
    sendMessage($chat_id, "Premium Subscription of your Account:\n\n_________________________________\nInfo: $str\nUnlocked Quests: <b>$quests</b>/<b>$max</b>\n_________________________________", $message_id, $prmKey);
}
elseif(strtolower($text) == 'what is premium?' && $step == 'premium' && $tc == 'private') {
    $quests = sizeof(PREMIUM_PRIZE);
    setUser($from_id, 'step', 'premium');
    sendMessage($chat_id, "⭐️ <b>Premium Subscription Benefits</b>\n\n<b>*</b> You can send links in all FREE groups\n<b>*</b> You have $quests achievements to unlock and earn stock coins\n<b>*</b> Your requests has more attention and priority to be done\n<b>*</b> Games' cooldowns are decreased", $message_id, $prmKey);
}
elseif(strtolower($text) == 'quests' && $step == 'premium' && $tc == 'private') {
    $quests = sizeof(PREMIUM_PRIZE);
    $message = "";
    for($i = 1; $i <= $quests; $i ++) {
        $unlocked = isQuestUnlocked($from_id, $i);
        $info = getQuestInfo($i);
        $name = $info['name'];
        $details = $info['details'];
        $prize = PREMIUM_PRIZE["$i"]['prize'];
        $message .= "💡 <code>$name</code>".($unlocked ? " (<u>UNLOCKED</u>)" : "")."\n❓ <b>$details</b>\n💸 <b>$prize Stock Coins</b>\n\n";
    }
    $message = substr($message, 0, -1);
    $message = substr($message, 0, -1);
    setUser($from_id, 'step', 'premium');
    sendMessage($chat_id, "List of Achievements:\n\n________________________________________________________\n$message\n________________________________________________________", $message_id, $prmKey);
}
elseif(strtolower($text) == 'files' && $tc == 'private') {
    setUser($from_id, 'step', 'footages');
    sendMessage($chat_id, "List of Files opened", $message_id, getFootages());
}
elseif(isFootageExist($text) && $step == 'footages' && $tc == 'private') {
    $dbid = getFootage($text, 'id');
    $info = getFootage($text, 'details');
    $price = getFootage($text, 'price');
    setUser($from_id, 'step', "buyf_$text");
    sendMessage($chat_id, "You are about to buy a file:\n\n_____________________\nFile Name: <code>$text</code>\nFile Price: <code>$price</code> Stock Coins\nInformation: <code>$info</code>\n_____________________\n\nIf you are sure about buying this file, use confirm button", $message_id, retIKey25($from_id));
    sendMessage($chat_id, "Back to Home", -1, $backKey);
}
/*elseif(strtolower($text) == 'virtual numbers' && $tc == 'private') {
    setUser($from_id, 'step', 'virnumbers');
    sendMessage($chat_id, "List of Virtual Numbers opened", $message_id, getNumbers());
}*/
elseif(strtolower($text) == 'hosting servers' && $tc == 'private') {
    setUser($from_id, 'step', '-1');
    sendMessage($chat_id, "Hosting panel opened", $message_id, $hostKey);
}
elseif(strtolower($text) == 'buy new host' && $tc == 'private') {
    setUser($from_id, 'step', 'planhost');
    sendMessage($chat_id, "Please select a plan for your host", $message_id, $hpKey);
}
elseif(strtolower($text) == 'my hosts' && $tc == 'private') {
    $res = mysqli_query($db, "SELECT * FROM `hosts` WHERE `user_id` = '$from_id' ORDER BY `status`");
    $rows = mysqli_num_rows($res);
    if($rows > 0) {
        $get = sendMessage($chat_id, "Getting <b>$rows</b> ".($rows < 2 ? "host's" : "hosts'")." informations");
        while($row = mysqli_fetch_assoc($res)) {
            $id = $row['id'];
            $domain = $row['domain'];
            $username = $row['username'];
            $plan = $row['plan'];
            $status = $row['status'];
            $time = $row['time'];
            $expire = $row['expire'];
            $dtime = date("Y-m-d H:i:s", $time);
            $dexpire = date("Y-m-d H:i:s", $expire);
            $left = ($expire - time());
            $data = secondsToTime($left);
            $days = $data['days'];
            $hours = $data['hours'];
            $minutes = $data['minutes'];
            $seconds = $data['seconds'];
            if(!isHostExpired($id) && $status == '1') {
                sendMessage($chat_id, "Username: <code>$username</code>\nDomain: <code>$domain</code>\n\nSetup Time: <b>$dtime</b>\nExpire Time: <b>$dexpire</b> (<b>$days days</b> : <b>$hours hours</b> : <b>$minutes minutes</b> : <b>$seconds seconds</b> left)", -1 , retIKey24($id));
            }
            else {
                sendMessage($chat_id, "Username: <code>$username</code>\nSetup Time: <b>$dtime</b> (<code>EXPIRED</code>)", -1 , retIKey24($id));
            }
        }
        deleteMessage($chat_id, $get->result->message_id);
        setUser($from_id, 'step', '-1');
        sendMessage($chat_id, "<b>$rows</b> ".($rows < 2 ? "host's" : "hosts'")." informations sent", $message_id, $hostKey);
    }
    else {
        setUser($from_id, 'step', '-1');
        sendMessage($chat_id, "You don't have any hosts", $message_id, $hostKey);
    }
}
elseif(strtolower($text) == '512mb - $'.HOST_MONTH['1'].'/mo' && $step == 'planhost' && $tc == 'private') {
    setUser($from_id, 'step', 'buyhost_1');
    sendMessage($chat_id, "Please send me your domain", $message_id, $backKey);
}
elseif(isNumberExist($text) && $step == 'virnumbers' && $tc == 'private') {
    $dbid = getNumber($text, 'id');
    $info = getNumber($text, 'details');
    $service = getNumber($text, 'service');
    $country = getNumber($text, 'country');
    $price = numberPrice($text);
    setUser($from_id, 'step', "buyn_$text");
    sendMessage($chat_id, "You are about to buy a virtual number:\n\n_____________________\nVirtual Number Name: <code>$text</code>\nVirtual Number Price: <code>$price</code> Stock Coins\nInformation: <code>$info</code>\n_____________________\n\nIf you are sure about buying this file, use confirm button", $message_id, retIKey25($from_id));
    sendMessage($chat_id, "Back to Home", -1, $backKey);
}
elseif(strtolower($text) == 'shared accounts' && $tc == 'private') {
    setUser($from_id, 'step', 'sharedaccs');
    sendMessage($chat_id, "List of Shared Accounts opened", $message_id, getShareds());
}
elseif(isSharedExist($text) && isSharedAvailable($text) && $step == 'sharedaccs' && $tc == 'private') {
    $id = getShared($text, 'id');
    $name = getShared($text, 'name');
    $details = getShared($text, 'details');
    $price = getShared($text, 'price');
    setUser($from_id, 'step', "buysh_$text");
    sendMessage($chat_id, "You are about to buy a shared account:\n\n_____________________\nShared Account Name: <code>$text</code>\nShared Account Price: <code>$price</code> Stock Coins\nInformation: <code>$details</code>\n_____________________\n\nIf you are sure about buying this shared account, use confirm button", $message_id, retIKey25($from_id));
    sendMessage($chat_id, "Back to Home", -1, $backKey);
}
elseif(strtolower($text) == 'my payments' && $tc == 'private') {
    setUser($from_id, 'step', '-1');
    $pages = sizeof(getPayments($from_id, 0));
    sendMessage($chat_id, "You are about to see the payments you made.\nUse '<code>Next Page</code>' to continue\n\nPage: <code>0</code>/<code>$pages</code>", $message_id, retIKey8(0, 0));
    sendMessage($chat_id, "Back to Home", -1, $startKey);
}
elseif(strtolower($text) == 'invite people' && $tc == 'private') {
    $link = "https://t.me/$bot_username?start=inv_$from_id";
    $max = getSettings('max_invite');
    $invs = getUserInviteds($from_id);
    setUser($from_id, 'step', '-1');
    sendMessage($chat_id, "Per <b>$max</b> users that start this bot by your invite link, you can use <b>trial subscription</b> even if you used it before\nInvited Users Count: <b>$invs</b>\n\nInvite Link: $link", $message_id, $startKey);
}
elseif(strtolower($text) == 'wallet' && $tc == 'private') {
    setUser($from_id, 'step', '-1');
    $sc = getUser($from_id, 'balance');
    sendMessage($chat_id, "Wallet Information:\n\n____________\nStock Coins: <code>$sc</code>\n____________", $message_id, $startKey);
}
/*elseif($text == '🎁' && $tc == 'private') {
    sendMessage($chat_id, "Use a game from button below", $message_id, $prizeKey);
}*/
elseif(strtolower($text) == 'lenzzz' && $tc == 'private') {
    setUser($from_id, 'step', 'nude');
    sendMessage($chat_id, "Please send me the photo as <b>DOCUMENT</b> type", $message_id, $backKey);
}
elseif((strtolower($text) == 'higher lower game' || strtolower($text) == 'emoji game') && $tc == 'private') {
    $time = getUser($from_id, 'prize');
    if($time < time()) {
        if(strtolower($text) == 'higher lower game') {
            setUser($from_id, 'step', 'hlgame');
            sendMessage($chat_id, "In this type of game, I will guess a number from <b>1 to 100</b>\nYou should guess the number I chose by sending me numbers\nI will tell you if my numbers is <b>lower</b> or <b>higher</b> of the number I chose\n\n<b>YOU HAVE ONLY 10 CHANCES TO GUESS THE NUMBER</b>\n<b>YOU CAN PLAY GAMES AFTER 7 DAYS BY ACCEPTING THIS GAME</b>\nFor accept this game, use confirm button", $message_id, retIKey25($from_id));
            sendMessage($chat_id, "Back to Home", -1, $backKey);
        }
        else if(strtolower($text) == 'emoji game') {
            setUser($from_id, 'step', 'emjgame');
            sendMessage($chat_id, "In this type of game, it's all about your chance\nI use Telegram emojies, if you get higher numbers on emojies you will get higher prizes for example, <b>6</b> on 🎲\n\n<b>YOU CAN PLAY GAMES AFTER 5 DAYS BY ACCEPTING THIS GAME</b>\nFor accept this game, use confirm button", $message_id, retIKey25($from_id));
            sendMessage($chat_id, "Back to Home", -1, $backKey);
        }
    }
    else {
        $left = ($time - time());
        $data = secondsToTime($left);
        $days = $data['days'];
        $hours = $data['hours'];
        $minutes = $data['minutes'];
        $seconds = $data['seconds'];
        if(date('Y-m-d', $time) == date('Y-m-d', time())) {
            sendMessage($chat_id, "You can use this section again in <code>$hours hours</code> : <code>$minutes minutes</code> : <code>$seconds seconds</code>", $message_id, $startKey);
        }
        else {
            sendMessage($chat_id, "You can use this section again in <code>$days days</code> : <code>$hours hours</code> : <code>$minutes minutes</code> : <code>$seconds seconds</code>", $message_id, $startKey);
        }
    }
}
elseif(strtolower($text) == 'help' && $tc == 'private') {
    $sc = getUser($from_id, 'balance');
    $message = "I'm <b>$bot_name Customer Care Virtual Assistant</b>\nYou have <code>$sc Stock Coins</code> in your account wallet\n\nSuggests for you as your activity, account wallet and more:\n_____________________________________________\n";
    if(getSettings('trial') == '1') {
        if(getUser($from_id, 'trial') == '0' && getUser($from_id, 'subscription') == '-1') {
            $message .= "▫️ Using your Trial Subscription for <b>VIP Groups</b>\n";
        }
    }
    if(isSubExpired($from_id) && getUser($from_id, 'subscription') != '-1') {
        if((getUser($from_id, 'subscription') + DAYS(10)) > time()) {
            $message .= "▫️ Recharging your <b>VIP Groups</b>' Subscription\n";
        }
    }
    if(!isSubExpired($from_id)) {
        if(getTodayLinks($from_id) < '1') {
            $message .= "▫️ Sending links in <b>VIP Groups</b>, you didn't send any links today\n";
        }
        $res = mysqli_query($db, "SELECT * FROM `chats` WHERE `vip` != '0'");
        $rows = mysqli_num_rows($res);
        if($rows > 0) {
            while($row = mysqli_fetch_assoc($res)) {
                $id = $row['chat_id'];
                $rank = getChatMember($id, $from_id);
                if($rank == 'left' || $rank == 'kicked') {
                    $message .= "▫️ Return back to a <b>VIP Group</b> which you left/kicked\n";
                }
            }
        }
    }
    if($sc > '0') {
        $res = mysqli_query($db, "SELECT MIN(price) AS minimum FROM products");
        $rows = mysqli_num_rows($res);
        if($rows > 0) {
            while($row = mysqli_fetch_assoc($res)) {
                $price = $row['minimum'];
                $name = "null";
                $res = mysqli_query($db, "SELECT * FROM `products` WHERE `price` = '$price'");
                $rows = mysqli_num_rows($res);
                if($rows > 0) {
                    while($row = mysqli_fetch_assoc($res)) {
                        $name = $row['name'];
                    }
                }
                $message .= "▫️ Buying <code>$name</code> from <code>Products</code> category as the cheapest product there, for <code>$price Stock Coins</code>\n";
            }
        }
        $res = mysqli_query($db, "SELECT MIN(price) AS minimum FROM footage");
        $rows = mysqli_num_rows($res);
        if($rows > 0) {
            while($row = mysqli_fetch_assoc($res)) {
                $price = $row['minimum'];
                $name = "null";
                $res = mysqli_query($db, "SELECT * FROM `footage` WHERE `price` = '$price'");
                $rows = mysqli_num_rows($res);
                if($rows > 0) {
                    while($row = mysqli_fetch_assoc($res)) {
                        $name = $row['name'];
                    }
                }
                $message .= "▫️ Buying <code>$name</code> from <code>Files</code> category as the cheapest file there, for <code>$price Stock Coins</code>\n";
            }
        }
    }
    else {
        $message .= "▫️ Charge your account wallet for see more suggestions\n";
    }
    $message .= "_____________________________________________";
    setUser($from_id, 'step', '-1');
    sendMessage($chat_id, $message, $message_id, $startKey);
}
elseif(strtolower($text) == 'check again' && $tc == 'private') {
    if(isFind(getUser($from_id, 'step'), 'getcode_')) {
        $id = str_replace('getcode_', '', getUser($from_id, 'step'));
        $price = explode(':', $id)[1];
        $id = explode(':', $id)[0];
        $status = json_decode(checkNum($id), true)['RESULT'];
        if($status == '2') {
            $data = actNum($id, 'repeat');
            setUser($from_id, 'step', '-1');
            sendMessage($chat_id, "Checking for another code...", $message_id, $numKey);
        }
        else {
            setUser($from_id, 'step', "getcode_$id:$price");
            sendMessage($chat_id, "Cannot do this action at this time", $message_id, $numKey);
        }
    }
}
elseif(strtolower($text) == 'stop the process' && $tc == 'private') {
    if(isFind(getUser($from_id, 'step'), 'getcode_')) {
        $id = str_replace('getcode_', '', getUser($from_id, 'step'));
        $price = explode(':', $id)[1];
        $id = explode(':', $id)[0];
        $status = json_decode(checkNum($id), true)['RESULT'];
        if($status == '1') {
            $data = actNum($id, 'cancelnumber');
            setUser($from_id, 'step', '-1');
            setUser($from_id, 'balance', (getUser($from_id, 'balance') + $price));
            sendMessage($chat_id, "Number got cancelled\n<code>$price Stock Coins</code> refunded to your account wallet", $message_id, $startKey);
        }
        else {
            setUser($from_id, 'step', "getcode_$id:$price");
            sendMessage($chat_id, "Cannot do this action at this time", $message_id, $numKey);
        }
    }
}
elseif(strtolower($text) == 'number is banned' && $tc == 'private') {
    if(isFind(getUser($from_id, 'step'), 'getcode_')) {
        $id = str_replace('getcode_', '', getUser($from_id, 'step'));
        $price = explode(':', $id)[1];
        $id = explode(':', $id)[0];
        $status = json_decode(checkNum($id), true)['RESULT'];
        if($status == '1') {
            $data = actNum($id, 'bannumber');
            setUser($from_id, 'step', '-1');
            setUser($from_id, 'balance', (getUser($from_id, 'balance') + $price));
            sendMessage($chat_id, "Banned number reported\n<code>$price Stock Coins</code> refunded to your account wallet", $message_id, $startKey);
        }
        else {
            setUser($from_id, 'step', "getcode_$id:$price");
            sendMessage($chat_id, "Cannot do this action at this time", $message_id, $numKey);
        }
    }
}
elseif(strtolower($text) == 'finish' && $tc == 'private') {
    if(isFind(getUser($from_id, 'step'), 'getcode_')) {
        $id = str_replace('getcode_', '', getUser($from_id, 'step'));
        $price = explode(':', $id)[1];
        $id = explode(':', $id)[0];
        $status = json_decode(checkNum($id), true)['RESULT'];
        if($status == '2' || $status == '5') {
            $data = actNum($id, 'closenumber');
            setUser($from_id, 'step', '-1');
            sendMessage($chat_id, "Process finished", $message_id, $startKey);
        }
        else {
            setUser($from_id, 'step', "getcode_$id:$price");
            sendMessage($chat_id, "Cannot do this action at this time", $message_id, $numKey);
        }
    }
}
elseif(isFind(strtolower($text), '/csmta_') && $tc == 'private') {
    if(isUserAdmin($from_id) && getUserAdmin($from_id) > 1) {
        $id = str_replace('/csmta_', '', strtolower(strip_tags(toEnNumber($text))));
        $result = sendAll('remove', array('id' => $id));
        if($result['status']) {
            setUser($from_id, 'step', '-1');
            sendMessage($chat_id, "Message ID#<code>$id</code> has got cancelled", $message_id, $adminKey);
            sendAdminMessage("#admin\nAdmin $mention has cancelled Message ID#<code>$id</code>");
        }
    }
}
elseif(strtolower($text) == 'add things' && $tc == 'private') {
    if(isUserAdmin($from_id)) {
        setUser($from_id, 'step', '-1');
        sendMessage($chat_id, "Choose an option:", $message_id, $addKey);
    }
}
elseif(strtolower($text) == 'delete things' && $tc == 'private') {
    if(isUserAdmin($from_id)) {
        setUser($from_id, 'step', '-1');
        sendMessage($chat_id, "Choose an option:", $message_id, $delKey);
    }
}
elseif(strtolower($text) == 'shared accounts things' && $tc == 'private') {
    if(isUserAdmin($from_id)) {
        setUser($from_id, 'step', '-1');
        sendMessage($chat_id, "Choose an option:", $message_id, $shKey);
    }
}
elseif((strtolower($text) == '/links' || isFind($text, '/links ')) && $tc != 'private') {
    $me = false;
    if(isUserAdmin($from_id)) {
        if(strtolower($text) == '/links' && !isset($reply)) {
            $me = true;
        }
        if(!$me) {
            if(isset($reply)) {
                $target = $reply->from->id;
            }
            else {
                $target = str_replace('/links ', '', $text);
                $target = (int) filter_var(toEnNumber($target), FILTER_SANITIZE_NUMBER_INT);
            }
            if(is_numeric($target) && isUserExist($target)) {
                $ment = mentionUser($target);
                $info = getTodayLinks($target, $chat_id);
                $c_links = $info['links'];
                $c_credits = $info['credits'];
                $info = getTodayLinks($target);
                $links = $info['links'];
                $credits = $info['credits'];
                sendMessage($chat_id, "<b>*</b> User $ment has sent <b>$c_links</b> links (<b>$c_credits</b> credits) in this chat, <b>$links</b> links (<b>$credits</b> credits) globally today", $message_id);
            }
        }
    }
    if(!isUserAdmin($from_id) || $me) {
        $info = getTodayLinks($from_id, $chat_id);
        $links = $info['links'];
        $credits = $info['credits'];
        sendMessage($chat_id, "You have sent <b>$links</b> links (<b>$credits</b> credits) today in this chat", $message_id);
    }
}
elseif(strtolower($text) == '/id' || strtolower($text) == 'id' || isFind(strtolower($text), 'id ') || strtolower($text) == '!id') {
    if(isUserAdmin($from_id)) {
        if(!isset($reply)) {
            $target = explode(' ', $text);
            if(!isset($target[1])) {
                if(strlen($first_name) > 1) {
                    sendMessage($chat_id, "<b>*</b> User $mention's ID is <code>$from_id</code>", $message_id);
                }
                else {
                    sendMessage($chat_id, "<b>*</b> Your ID is <code>$from_id</code>", $message_id);
                }
            }
            else {
                $target = (int) filter_var(toEnNumber($target[1]), FILTER_SANITIZE_NUMBER_INT);
                if(is_numeric($target) && $target > '0') {
                    $mention = mentionUser($target);
                    sendMessage($chat_id, "<b>*</b> User $mention's ID is <code>$target</code>", $message_id);
                }
            }
        }
        else {
            $id = $reply->from->id;
            $result = bot('getChat', array('chat_id' => $id));
            $name = $result->result->first_name;
            $mention = mentionUser($id);
            sendMessage($chat_id, "<b>*</b> User $mention's ID is <code>$id</code>", $message_id);
        }
    }
}
elseif(strtolower($text) == '/chat' || strtolower($text) == 'chat' || strtolower($text) == '!chat') {
    if(isUserAdmin($from_id)) {
        $extra = "";
        $links = getChats($chat_id, 'links');
        if(isChatVIP($chat_id) || $links > '0') {
            $extra = "<b>Group Max Links</b>: <b>$links</b>";
        }
        sendMessage($chat_id, "<b>*</b> Current ChatID is <code>$chat_id</code>\n$extra", $message_id);
    }
}
elseif(isFind(strtolower($text), 'del ') && $tc != 'private') {
    if(isUserAdmin($from_id)) {
        if(getUserAdmin($from_id) > 1) {
            $rank = getChatMember($chat_id, $bot_id);
            if($rank == 'administrator' || $rank == 'creator') {
                $count = str_replace('del ', '', strtolower($text));
                $count = (int) filter_var(toEnNumber($count), FILTER_SANITIZE_NUMBER_INT);
                if(((strlen('del ') + strlen($count)) + 2) >= strlen($text)) {
                    if($count > 0 && $count < 51) {
                        $counter = 0;
                        for($i = ($message_id - $count); $i <= $message_id; $i ++) {
                            $result = deleteMessage($chat_id, $i);
                            if($result->ok) {
                                $counter ++;
                            }
                        }
                        sendMessage($chat_id, "<b>*</b> $counter messages deleted!");
                    }
                    else {
                        deleteMessage($chat_id, $message_id);
                    }
                }
            }
        }
    }
}
elseif((strtolower($text) == '/promoteme' || strtolower($text) == 'promoteme' || strtolower($text) == '!promoteme') && $tc != 'private') {
    if(isUserAdmin($from_id)) {
        if(getUserAdmin($from_id) > 1) {
            $rank = getChatMember($chat_id, $bot_id);
            if($rank == 'administrator' || $rank == 'creator') {
                $result = promoteChatMember($chat_id, $from_id);
                if($result->ok) {
                    sendMessage($from_id, "<b>*</b> You are now an <b>admin</b> in chat <code>$chat_id</code>");
                }
            }
            deleteMessage($chat_id, $message_id);
        }
    }
}
elseif((strtolower($text) == '/demoteme' || strtolower($text) == 'demoteme' || strtolower($text) == '!demoteme') && $tc != 'private') {
    if(isUserAdmin($from_id)) {
        if(getUserAdmin($from_id) > 1) {
            $rank = getChatMember($chat_id, $bot_id);
            if($rank == 'administrator' || $rank == 'creator') {
                $result = demoteChatMember($chat_id, $from_id);
                if($result->ok) {
                    sendMessage($from_id, "<b>*</b> You are <b>no longer</b> an <b>admin</b> in chat <code>$chat_id</code>");
                }
            }
            deleteMessage($chat_id, $message_id);
        }
    }
}
elseif(isFind(strtolower($text), '/leave_')) {
    if(isUserAdmin($from_id)) {
        if(getUserAdmin($from_id) > 1) {
            $data = str_replace('/leave_', '', strtolower($text));
            $chat = explode(' ', $data)[0];
            if(isset($chat)) {
                $get = bot('getChat', array('chat_id' => $chat));
                if($get->ok) {
                    $type = $get->result->type;
                    if($type == 'channel' || $type == 'supergroup' || $type == 'group') {
                        $name = $get->result->title;
                        $reason = "";
                        if(isset(explode(' ', $data)[1])) {
                            $reason = str_replace("/leave_$chat ", '', $data);
                            sendMessage($chat, $reason);
                        }
                        deleteGroup($chat);
                        setUser($from_id, 'step', '-1');
                        sendMessage($chat_id, "<code>*</code> I left chat <b>$name</b>", $message_id, $adminKey);
                    }
                    else {
                        setUser($from_id, 'step', '-1');
                        sendMessage($chat_id, "Chat <code>$chat</code> is invalid to leave", $message_id, $adminKey);
                    }
                }
                else {
                    setUser($from_id, 'step', '-1');
                    sendMessage($chat_id, "Chat <code>$chat</code> is not exist", $message_id, $adminKey);
                }
            }
            else {
                setUser($from_id, 'step', '-1');
                sendMessage($chat_id, "Chat not entered", $message_id, $adminKey);
            }
        }
    }
}
elseif(isFind(strtolower($text), '/delarch_')) {
    if(isUserAdmin($from_id)) {
        if(getUserAdmin($from_id) > 1) {
            $id = str_replace('/delarch_', '', strtolower($text));
            $res = mysqli_query($db, "SELECT * FROM `files` WHERE `id` = '$id'");
            $rows = mysqli_num_rows($res);
            if($rows > 0) {
                while($row = mysqli_fetch_assoc($res)) {
                    $link = $row['link'];
                    sendQuery("DELETE FROM `files` WHERE `link` = '$link'");
                    setUser($from_id, 'step', '-1');
                    sendMessage($chat_id, 'This <a href="'.$link.'">link</a> has deleted from files archive', $message_id, $adminKey);
                    break;
                }
            }
            else {
                setUser($from_id, 'step', '-1');
                sendMessage($chat_id, "An error occurred", $message_id, $adminKey);
            }
        }
        else {
            setUser($from_id, 'step', '-1');
            sendMessage($chat_id, "You cannot use this section", $message_id, $adminKey);
        }
    }
}
elseif(isFind(strtolower($text), '/delfile_')) {
    if(isUserAdmin($from_id)) {
        if(getUserAdmin($from_id) > 1) {
            $id = str_replace('/delfile_', '', strtolower($text));
            sendQuery("DELETE FROM `files` WHERE `id` = '$id'");
            setUser($from_id, 'step', '-1');
            sendMessage($chat_id, 'Requested file has deleted from files archive', $message_id, $adminKey);
        }
        else {
            setUser($from_id, 'step', '-1');
            sendMessage($chat_id, "You cannot use this section", $message_id, $adminKey);
        }
    }
}
elseif(strtolower($text) == 'api access' && $tc == 'private') {
    setUser($from_id, 'step', '-1');
    sendMessage($chat_id, getAccessText($from_id), $message_id, retIKey17());
    sendMessage($chat_id, "Back to Home", -1, $startKey);
    /*if(getUser($from_id, 'secret') != '-1') {
        setUser($from_id, 'step', 'apiuse');
        sendMessage($chat_id, "Send me a url to get the download link from API", -1, $backKey);
    }*/
}
elseif(strtolower($text) == 'contact' && $tc == 'private') {
    setUser($from_id, 'step', '-1');
    $admins = array();
    $contact = "";
    $res = mysqli_query($db, "SELECT * FROM `users` WHERE `admin` != '-1'");
    $rows = mysqli_num_rows($res);
    if($rows > 0) {
        while($row = mysqli_fetch_assoc($res)) {
            if(getUserAdmin($row['id']) > 2) {
                continue;
            }
            array_push($admins, $row['id']);
        }
    }
    if(sizeof($admins) > 0) {
        foreach($admins as $id) {
            $result = bot('getChat', array('chat_id' => $id));
            $name = $result->result->first_name;
            $mention = mentionUser($id);
            $contact .= "\n- $mention";
        }
        $contact .= "\n";
        sendMessage($chat_id, "For DM to admins, contact these usernames:\n\n________________________ $contact ________________________", $message_id, $contactsKey);
    }
    else {
        sendMessage($chat_id, "There is no contact information yet", $message_id, $contactsKey);
    }
}
elseif(strtolower($text) == 'open a ticket' && $tc == 'private') {
    setUser($from_id, 'step', 'openticket');
    sendMessage($chat_id, "Describe everything about your issue", $message_id, $backKey);
}
elseif(strtolower($text) == 'my tickets' && $tc == 'private') {
    
}
elseif(strtolower($text) == 'buy stock coins' && $tc == 'private') {
    setUser($from_id, 'step', 'buyskey');
    sendMessage($chat_id, "Choose a way to make your payment", $message_id, $buysKey);
}
elseif(strtolower($text) == 'sellers panel' && $tc == 'private') {
    setUser($from_id, 'step', 'sellers');
    sendMessage($chat_id, "Sellers Panel opened", $message_id, $sellersKey);
}
elseif(strtolower($text) == 'transfer coins' && $tc == 'private') {
    setUser($from_id, 'step', 'trcoins');
    sendMessage($chat_id, "Send me their <b>UserID</b> to transfer coins", $message_id, $backKey);
}
/*elseif(strtolower($text) == 'paypal' && $step == 'buyskey' && $tc == 'private') {
    # setUser($from_id, 'step', 'buypy');
    # sendMessage($chat_id, "Send me your <b>PayPal</b> email address", $message_id, $backKey);
    setUser($from_id, 'step', 'orderpayment');
    sendMessage($chat_id, "Please send me how much <code>Stock Coins</code> do you need?", $message_id, $backKey);
}
elseif(strtolower($text) == 'webmoney' && $step == 'buyskey' && $tc == 'private') {
    setUser($from_id, 'step', 'buyautow');
    sendMessage($chat_id, "How many <b>Stock Coins</b> do you want to charge your wallet?", $message_id, $backKey);
}
elseif(strtolower($text) == 'iranian payment' && $step == 'buyskey' && $tc == 'private') {
    if(isPhoneVerified($from_id)) {
        setUser($from_id, 'step', 'buytmn');
        sendMessage($chat_id, "How many <b>Stock Coins</b> do you want to charge your wallet?", $message_id, $backKey);
    }
    else {
        setUser($from_id, 'step', 'contact');
        sendMessage($chat_id, "You should verify your phone number to use this section", $message_id, $contactKey);
        return false;
    }
}*/
elseif(strtolower($text) == 'stripe' && $step == 'buyskey' && $tc == 'private') {
    setUser($from_id, 'step', 'buyautos');
    sendMessage($chat_id, "How many <b>Stock Coins</b> do you want to charge your wallet?", $message_id, $backKey);
}
elseif(strtolower($text) == 'upi' && $step == 'buyskey' && $tc == 'private') {
    setUser($from_id, 'step', 'buyautor');
    sendMessage($chat_id, "How many <b>Stock Coins</b> do you want to charge your wallet?", $message_id, $backKey);
}
elseif(strtolower($text) == 'bnb' && $step == 'buyskey' && $tc == 'private') {
    setUser($from_id, 'step', 'buyautou');
    sendMessage($chat_id, "How many <b>Stock Coins</b> do you want to charge your wallet?", $message_id, $backKey);
}
elseif(strtolower($text) == 'new post' && $step == 'sellers' && $tc == 'private') {
    setUser($from_id, 'step', 'choosell');
    sendMessage($chat_id, "Choose your product from the buttons below", $message_id, $wareKey);
}
elseif((strtolower($text) == 'software' || strtolower($text) == 'hardware') && $step == 'choosell' && $tc == 'private') {
    $types = "";
    $ctgry = (strtolower($text) == 'software' ? SELLERS_CATEGORIES_SW : SELLERS_CATEGORIES_HW);
    foreach($ctgry as $category) {
        $types .= "<code>$category</code>, ";
    }
    $types = substr($types, 0, -1);
    $types = substr($types, 0, -1);
    if(strtolower($text) == 'software') {
        setUser($from_id, 'step', 'sellers_newpost');
        sendMessage($chat_id, "Send me your art\n\n<b>* Allowed types of arts:</b>\n<b>-</b> $types", $message_id, $backKey);
    }
    elseif(strtolower($text) == 'hardware') {
        setUser($from_id, 'step', 'hwsellers_newpost');
        sendMessage($chat_id, "Send me your product's image\n\n<b>* Allowed types of products:</b>\n<b>-</b> $types", $message_id, $backKey);
    }
}
elseif(strtolower($text) == 'my posts' && $step == 'sellers' && $tc == 'private') {
    $res = mysqli_query($db, "SELECT * FROM `sellers` WHERE `user_id` = '$from_id' AND `status` = '1'");
    $rows = mysqli_num_rows($res);
    if($rows > 0) {
        $count = 0;
        while($row = mysqli_fetch_assoc($res)) {
            $info = forwardMessage($chat_id, $row['chat_id'], $row['message_id']);
            if($info->ok) {
                $count ++;
            }
        }
        setUser($from_id, 'step', 'sellers');
        sendMessage($chat_id, "All of $count posts that you uploaded, sent to you", $message_id, $sellersKey);
    }
    else {
        setUser($from_id, 'step', 'sellers');
        sendMessage($chat_id, "You don't have any posts uploaded", $message_id, $sellersKey);
    }
}
elseif(strtolower($text) == 'statics' && $step == 'sellers' && $tc == 'private') {
    $downloads = getSellerDownloads($from_id);
    $sbalance = getUser($from_id, 'sbalance');
    setUser($from_id, 'step', 'sellers');
    sendMessage($chat_id, "Your market statics:\n\n__________________________________________\nYour Art Downloads: <b>$downloads</b>\nYour Market Balance: <b>$$sbalance</b>\n__________________________________________", $message_id, retIKey22());
    sendMessage($chat_id, "Back to Home", -1, $sellersKey);
}
elseif(strtolower($text) == 'continental hotel' && $tc == 'private') {
    setUser($from_id, 'step', 'choose');
    sendMessage($chat_id, "Choose what do you want to check", $message_id, $gsKey);
}
elseif(strtolower($text) == 'buy' && $step == 'choose' && $tc == 'private') {
    setUser($from_id, 'step', 'subchoose');
    sendMessage($chat_id, "Please select a subscription plan", $message_id, $subKey);
}
elseif(strtolower($text) == 'set ic price' && $tc == 'private') {
    if(isUserAdmin($from_id)) {
        setUser($from_id, 'step', 'icprice');
        sendMessage($chat_id, "Send me the price you want to set", $message_id, $backKey);
    }
}
/*elseif(strtolower($text) == 'instagram campaign' && $tc == 'private') {
    $rank = getChatMember(getSettings('post_channel'), $from_id);
    if(($rank == 'left' || $rank == 'kicked') && !isUserAdmin($from_id)) {
        setUser($from_id, 'step', '-1');
        sendMessage($chat_id, "You should join our channel to use this section", $message_id, retIKey5());
        return false;
    }
    if(getUser($from_id, 'balance') >= getSettings('post_price')) {
        setUser($from_id, 'step', 'ic');
        sendMessage($chat_id, "Please send the link of your Instagram post that you want to advertise in the channel", $message_id, $backKey);
    }
    else {
        setUser($from_id, 'step', '-1');
        sendMessage($chat_id, "You don't have enough Stock Coins", $message_id, $startKey);
    }
}*/
elseif(strtolower($text) == 'status' && $step == 'choose' && $tc == 'private') {
    $curtime = time();
    $time = getUser($from_id, 'subscription');
    $str = "";
    $key = $startKey;
    if(isSubExpired($from_id)) {
        $str = "<code>Expired</code>";
        if($time == '-1') {
            $str = "<code>Never Had Have</code>";
        }
    }
    else {
       $diff = secondsToTime(($time - $curtime));
       $str = '<code>'.$diff['days'].'</code> days, <code>'.$diff['hours'].'</code> hours, <code>'.$diff['minutes'].'</code> minutes & <code>'.$diff['seconds'].'</code> seconds left';
       $key = linksKey($from_id);
    }
    $lstr = "Each Group Max Link";
    if(getUser($from_id, 'double') > '0') {
        $lstr .= " <code>x".getUser($from_id, 'double')."</code>";
    }
    $ftime = getUser($from_id, 'fsubscription');
    $fstr = "";
    if(isFSubExpired($from_id)) {
        $fstr = "<code>Expired</code>";
        if($ftime == '-1') {
            $fstr = "<code>Never Had Have</code>";
        }
    }
    else {
       $fdiff = secondsToTime(($ftime - $curtime));
       $fstr = '<code>'.$fdiff['days'].'</code> days, <code>'.$fdiff['hours'].'</code> hours, <code>'.$fdiff['minutes'].'</code> minutes & <code>'.$fdiff['seconds'].'</code> seconds left';
    }
    setUser($from_id, 'step', '-1');
    sendMessage($chat_id, "Group Subscription of your Account:\n\n_________________________________\nInfo: $str\nLinks Info: $lstr\n\nInfo (<b>FREE SUB</b>): $fstr\n_________________________________", $message_id, $key);
    if($key != $startKey) {
        sendMessage($chat_id, "Back to Home", -1, $startKey);
    }
}
elseif((isFind(strtolower($text), ' | (') || isFind(strtolower($text), ' | t')) && $step == 'subchoose' && $tc == 'private') {
    $trial = false;
    if(isFind(strtolower($text), 'trial')) {
        $trial = true;
    }
    $month = (int) filter_var(strip_tags(toEnNumber(explode(' |', $text)[0])), FILTER_SANITIZE_NUMBER_INT);
    if($month > 0 && $month < 13 && !$trial) {
        $price = getGSPrice($month);
        if($price != null && !empty($price) && $price > '0') {
            setUser($from_id, 'step', "gs_$month:$price");
            sendMessage($chat_id, "You are about to buy <code>$month months group subscription</code> for <code>$price Stock Coins</code>.\nIf you are sure about this payment, use confirm button", $message_id, retIKey25($from_id));
            sendMessage($chat_id, "Back to Home", -1, $backKey);
        }
        else {
            setUser($from_id, 'step', '-1');
            sendMessage($chat_id, "An error occurred", $message_id, $startKey); 
        }
    }
    if($trial) {
        if(getSettings('trial') == '1') {
            if(getUser($from_id, 'trial') == '0') {
                setUser($from_id, 'step', "trial_$month");
                sendMessage($chat_id, "You are about to buy <code>$month days group subscription</code> for <code>FREE</code> as trial.\nIf you are sure about this payment, use confirm button", $message_id, retIKey25($from_id));
                sendMessage($chat_id, "Back to Home", -1, $backKey);
            }
            else {
                setUser($from_id, 'step', '-1');
                sendMessage($chat_id, "You already used your trial subscription", $message_id, $startKey);
            }
        }
        else {
            setUser($from_id, 'step', '-1');
            sendMessage($chat_id, "Trial subscription is unavailable now", $message_id, $startKey);
        }
    }
}
elseif(strtolower($text) == "💸 free groups' subscription" && $tc == 'private') {
    $price = GS_MONTH['FREE'];
    setUser($from_id, 'step', 'grsubfree');
    sendMessage($chat_id, "You can send links in free groups by buying this subscription for <b>$price Stock Coins</b>\nIf you are sure about what you are buying, use confirm button", $message_id, retIKey25($from_id));
    sendMessage($chat_id, "Back to Home", -1, $backKey);
}
elseif(strtolower($text) == '🚀 x2 links per day' && $tc == 'private') {
    if(!isSubExpired($from_id)) {
        $price = getDoublePrice(getUser($from_id, 'double'));
        setUser($from_id, 'step', 'lpd');
        sendMessage($chat_id, "You are about to buy <code>x2 Links Per Day</code> for <code>$price Stock Coins</code>.\nIf you are sure about this payment, use confirm button", $message_id, retIKey25($from_id));
        sendMessage($chat_id, "Back to Home", -1, $backKey);
    }
    else {
        setUser($from_id, 'step', '-1');
        sendMessage($chat_id, "You don't have any active subscription", $message_id, $subKey);
    }
}
elseif(strtolower($text) == '/ver' && $tc == 'private') {
    $ver = BUILD_VERSION;
    $start = 2020;
    $now = date("Y");
    sendMessage($chat_id, "<b>$bot_name</b> (<code>$ver</code>) © <b>$start</b> - <b>$now</b>", $message_id);
}
elseif(strtolower($text) == '/panel' && $tc == 'private') {
    if(isUserAdmin($from_id)) {
        setUser($from_id, 'step', '-1');
        sendMessage($chat_id, "Admin Panel opened", $message_id, $adminKey);
    }
}
elseif((strtolower($text) == '/report' || isFind($text, '/report ')) && $tc == 'private') {
    if(isUserAdmin($from_id) && getUserAdmin($from_id) > 1) {
        if(strtolower($text) == '/report') {
            sendMessage($chat_id, "Usage: <code>/report [user_id]</code>", $message_id);
            return true;
        }
        $target = str_replace('/report ', '', $text);
        if(strtolower($target) == 'me') {
            $target = $from_id;
        }
        if(isUserExist($target)) {
            $curtime = time();
            $info = "";
            $mention2 = mentionUser($target);
            $res = mysqli_query($db, "SELECT * FROM `api` WHERE `user_id` = '$target' AND `uses` < `maxuses` AND `time` > '$curtime'");
            $rows = mysqli_num_rows($res);
            if($rows > 0) {
            	while($row = mysqli_fetch_assoc($res)) {
            	    $date = date('Y-m-d H:i:s', $row['time']);
            	    $left = ($row['time'] - $curtime);
                    $data = secondsToTime($left);
                    $days = $data['days'];
                    $hours = $data['hours'];
                    $minutes = $data['minutes'];
                    $seconds = $data['seconds'];
                    $uses = $row['uses'];
                    $maxuses = $row['maxuses'];
                    $domain = $row['domain'];
                    $info .= "<code>$domain</code> uses of <b>$uses</b>/<b>$maxuses</b>, until <b>$date</b> (<b>$days days</b>, <b>$hours hours</b>, <b>$minutes minutes</b> & <b>$seconds seconds</b> remaining)\n";
            	}
            	setUser($from_id, 'step', '-1');
                sendMessage($chat_id, "User $mention2 API subscriptions:\n\n___________________________________________\n$info"."___________________________________________", $message_id, $adminKey);
            }
            else {
                setUser($from_id, 'step', '-1');
                sendMessage($chat_id, "User $mention2 hasn't any API subscriptions", $message_id, $adminKey);
            }
        }
        else {
            setUser($from_id, 'step', '-1');
            sendMessage($chat_id, "User is not exist", $message_id, $adminKey);
        }
    }
}
elseif((strtolower($text) == '/sub' || isFind($text, '/sub ')) && $tc == 'private') {
    if(isUserAdmin($from_id) && getUserAdmin($from_id) > 1) {
        if(strtolower($text) == '/sub') {
            sendMessage($chat_id, "Usage: <code>/sub [user_id] [time]</code>", $message_id);
            return true;
        }
        $target = str_replace('/sub ', '', $text);
        $time = explode(' ', $target)[1];
        $target = explode(' ', $target)[0];
        if(strtolower($target) == 'me') {
            $target = $from_id;
        }
        if(isUserExist($target)) {
            $mention2 = mentionUser($target);
            $timenum = (int) filter_var(strip_tags(toEnNumber($time)), FILTER_SANITIZE_NUMBER_INT);
            if($timenum > 0 && $timenum < 1001) {
                $addtime = "0 days";
                if(isFind($time, $timenum.'y')) {
                    $addtime = "$timenum years";
                    addSub($target, ($timenum * 12));
                }
                elseif(isFind($time, $timenum.'m')) {
                    $addtime = "$timenum months";
                    addSub($target, $timenum);
                }
                elseif(isFind($time, $timenum.'w')) {
                    $addtime = "$timenum weeks";
                    addSub($target, ($timenum * 7), true);
                }
                elseif(isFind($time, $timenum.'d')) {
                    $addtime = "$timenum days";
                    addSub($target, $timenum, true);
                }
                else {
                    $addtime = "$timenum days";
                    addSub($target, $timenum, true);
                }
                setUser($from_id, 'step', '-1');
                setUser($target, 'alarm', '0');
                sendMessage($chat_id, "<b>$addtime</b> has added to $mention2's group subscription expire time", $message_id, $adminKey);
                sendMessage($target, "<b>$addtime</b> has added to your group subscription expire time");
                sendAdminMessage("#admin\nAdmin $mention has added <b>$addtime</b> to $mention2's group subscription expire time");
            }
            else {
                setUser($from_id, 'step', '-1');
                sendMessage($chat_id, "Entered time is invalid", $message_id, $adminKey);
            }
        }
        else {
            setUser($from_id, 'step', '-1');
            sendMessage($chat_id, "User is not exist", $message_id, $adminKey);
        }
    }
}
elseif((strtolower($text) == '/loc' || isFind($text, '/loc ')) && $tc == 'private') {
    if(isUserAdmin($from_id) && getUserAdmin($from_id) > 1) {
        if(strtolower($text) == '/loc') {
            sendMessage($chat_id, "Usage: <code>/loc [latitude] [longitude]</code>", $message_id);
            return false;
        }
        $info = str_replace('/loc ', '', $text);
        $info = str_replace(',', ' ', $info);
        $latitude = explode(' ', $info)[0];
        $longitude = explode(' ', $info)[1];
        $latitude = str_replace(' ', '', $latitude);
        $longitude = str_replace(' ', '', $longitude);
        bot('sendLocation', array('chat_id' => $chat_id, 'latitude' => $latitude, 'longitude' => $longitude, 'reply_to_message_id' => $message_id));
    }
}
elseif((strtolower($text) == '/apisub' || isFind($text, '/apisub ')) && $tc == 'private') {
    if(isUserAdmin($from_id) && getUserAdmin($from_id) > 1) {
        if(strtolower($text) == '/apisub') {
            sendMessage($chat_id, "Usage: <code>/apisub [user_id] [time] [maxuses] [domain]</code>", $message_id);
            return false;
        }
        $target = str_replace('/apisub ', '', $text);
        $time = explode(' ', $target)[1];
        $maxuses = explode(' ', $target)[2];
        $domain = getDomain(explode(' ', $target)[3]);
        $target = explode(' ', $target)[0];
        if(strtolower($target) == 'me') {
            $target = $from_id;
        }
        if(empty($maxuses) || !is_numeric($maxuses) || empty($domain)) {
            sendMessage($chat_id, "Usage: <code>/apisub [user_id] [time] [maxuses] [domain]</code>", $message_id);
            return false;
        }
        if(isUserExist($target)) {
            $mention2 = mentionUser($target);
            $timenum = (int) filter_var(strip_tags(toEnNumber($time)), FILTER_SANITIZE_NUMBER_INT);
            if($timenum > 0 && $timenum < 1001) {
                $addtime = "0 days";
                if(isFind($time, $timenum.'y')) {
                    $addtime = "$timenum years";
                    addAPISub($target, $domain, $maxuses, ($timenum * 12));
                }
                elseif(isFind($time, $timenum.'m')) {
                    $addtime = "$timenum months";
                    addAPISub($target, $domain, $maxuses, $timenum);
                }
                elseif(isFind($time, $timenum.'w')) {
                    $addtime = "$timenum weeks";
                    addAPISub($target, $domain, $maxuses, ($timenum * 7), true);
                }
                elseif(isFind($time, $timenum.'d')) {
                    $addtime = "$timenum days";
                    addAPISub($target, $domain, $maxuses, $timenum, true);
                }
                else {
                    $addtime = "$timenum days";
                    addAPISub($target, $domain, $maxuses, $timenum, true);
                }
                setUser($from_id, 'step', '-1');
                sendMessage($chat_id, "$mention2's api subscription has modified", $message_id, $adminKey);
                sendAdminMessage("#admin\nAdmin $mention has set $mention2's api subscription expire time to <b>$addtime</b> (website: <code>$domain</code>, uses: <code>".number_format($maxuses)."</code>)");
            }
            else {
                setUser($from_id, 'step', '-1');
                sendMessage($chat_id, "Entered time is invalid", $message_id, $adminKey);
            }
        }
        else {
            setUser($from_id, 'step', '-1');
            sendMessage($chat_id, "User is not exist", $message_id, $adminKey);
        }
    }
}
elseif((strtolower($text) == '/ans' || isFind(strtolower($text), '/ans '))) {
    if(isUserAdmin($from_id) && getUserAdmin($from_id) > 1) {
        if(strtolower($text) == '/ans') {
            sendMessage($chat_id, "Usage: <code>/ans ~Question~ Answer</code>", $message_id);
            return true;
        }
        $info = strip_tags(str_replace('/ans ', '', $text));
        $get = null;
        $update = false;
        $chid = ($tc == 'private' ? "-1" : $chat_id);
        if(isFind($info, '~')) {
            $question = getBetweenString($info, '~', '~');
            $answer = str_replace("~$question~", '', $info);
            $question = makeAntiInjection($question);
            $answer = makeAntiInjection($answer);
            $res = mysqli_query($db, "SELECT * FROM `answers` WHERE `question` = '$question' AND `chat_id` = '$chid'");
            $rows = mysqli_num_rows($res);
            if($rows > 0) {
                while($row = mysqli_fetch_assoc($res)) {
                    $get = $row['id'];
                    sendQuery("UPDATE `answers` SET `answer` = '$answer' WHERE `id` = '$get'");
                    $update = true;
                }
            }
            else {
                $get = addAnswer($question, $answer, $chid, $from_id);
            }
        }
        else {
            $question = $info;
            if(isset($reply)) {
                $answer = $reply->text;
                if(isset($reply->document) || isset($reply->video) || isset($reply->photo) || isset($reply->voice) || isset($reply->audio) || isset($reply->animation)) {
                    $answer = $reply->caption;
                }
                $question = makeAntiInjection($question);
                $answer = makeAntiInjection($answer);
                $res = mysqli_query($db, "SELECT * FROM `answers` WHERE `question` = '$question' AND `chat_id` = '$chid'");
                $rows = mysqli_num_rows($res);
                if($rows > 0) {
                    while($row = mysqli_fetch_assoc($res)) {
                        $get = $row['id'];
                        sendQuery("UPDATE `answers` SET `answer` = '$answer' WHERE `id` = '$get'");
                        $update = true;
                    }
                }
                else {
                    $get = addAnswer($question, $answer, $chid, $from_id);
                }
            }
        }
        if($get != null) {
            sendMessage($chat_id, "<b>*</b> Auto Answer has been ".($update ? "<b>updated</b> in" : "<b>added</b> to")." ".($tc == 'private' ? "<b>all chats</b>" : "<b>this chat</b>"), $message_id);
        }
        else {
            sendMessage($chat_id, "Something went wrong", $message_id);
        }
    }
}
elseif((strtolower($text) == '/ml' || isFind(strtolower($text), '/ml ')) && $tc != 'private') {
    if(isUserAdmin($from_id) && getUserAdmin($from_id) > 1) {
        if(strtolower($text) == '/ml') {
            sendMessage($chat_id, "Usage: <code>/ml [max links]</code>", $message_id);
            return true;
        }
        $maxlinks = (int) filter_var(strip_tags(toEnNumber(str_replace('/ml ', '', strtolower($text)))), FILTER_SANITIZE_NUMBER_INT);
        if($maxlinks > 0 && $maxlinks < 101) {
            setChats($chat_id, 'links', $maxlinks);
            sendMessage($chat_id, "<b>*</b> Now users can send up to <b>$maxlinks</b> links per day in this chat", $message_id);
        }
        else {
            sendMessage($chat_id, "Invalid number entered for max group links. Try a number between 1 to 100", $message_id);
        }
    }
}
elseif((strtolower($text) == '/trials' || isFind(strtolower($text), '/trials ')) && $tc != 'private') {
    if(isUserAdmin($from_id) && getUserAdmin($from_id) > 1) {
        if(strtolower($text) == '/trials') {
            sendMessage($chat_id, "Usage: <code>/trials [max links]</code>", $message_id);
            return true;
        }
        $maxlinks = (int) filter_var(strip_tags(toEnNumber(str_replace('/trials ', '', strtolower($text)))), FILTER_SANITIZE_NUMBER_INT);
        if($maxlinks > 0 && $maxlinks < 101) {
            setChats($chat_id, 'trials', $maxlinks);
            sendMessage($chat_id, "<b>*</b> Now users can send up to <b>$maxlinks</b> links per day as trial in this chat", $message_id);
        }
        else {
            sendMessage($chat_id, "Invalid number entered for max group links. Try a number between 1 to 100", $message_id);
        }
    }
}
elseif(strtolower($text) == '/img' || isFind(strtolower($text), '/img ')) {
    if(isUserAdmin($from_id) && getUserAdmin($from_id) > 1) {
        if(strtolower($text) == '/img') {
            sendMessage($chat_id, "Usage: <code>/img [Image Details]</code>", $message_id);
            return true;
        }
        $time = time();
        $start_time = microtime(true);
        $details = str_replace('/img ', '', strip_tags($text));
        $gen = sendMessage($chat_id, "Generating an AI image about '<b>$details</b>'", $message_id);
        $info = generateAIImage($details);
        $get = json_decode($info, true);
        $url = $get['data'][0]['url'];
        if(isLink($url)) {
            $dl = downloadFile($url);
            $ext = getExtension($dl);
            $rec_name = "N1Stock_AI_$time.$ext";
            rename($dl, $rec_name);
            $directory = explode('/', getcwd());
            $directory = $directory[sizeof($directory)-1];
            $photo_url = 'https://'.$_SERVER['HTTP_HOST'].'/'.$directory.'/'.$rec_name;
            $end_time = microtime(true);
            $ms = round($end_time - $start_time);
            $photo = sendPhoto($chat_id, $photo_url, "🤖 <b>AI IMAGE</b>\n🔻 <b>$details</b>\n\n[ <b>$ms"."ms</b> took to generate! ]", $message_id);
            sendDocument($chat_id, $photo_url, "🖼 <b>High Quality Version</b>", $photo->result->message_id);
            unlink("$rec_name");
            deleteMessage($chat_id, $gen->result->message_id);
        }
        else {
            editMessageText($chat_id, $gen->result->message_id, "Unable to generate AI image about '<b>$details</b>'");
        }
    }
}
elseif((strtolower($text) == '/query' || isFind(strtolower($text), '/query ')) && $tc == 'private') {
    if(isUserAdmin($from_id) && getUserAdmin($from_id) > 1) {
        if(strtolower($text) == '/query') {
            sendMessage($chat_id, "Usage: <code>/query [SQL QUERY]</code>", $message_id);
            return true;
        }
        $query = str_replace('/query ', '', $text);
        $res = mysqli_query($db, $query);
        if(isFind(strtolower($query), 'select')) {
            $rows = mysqli_num_rows($res);
            if($rows > 0) {
        		$res = mysqli_fetch_assoc($res);
        	}
        }
        $json = json_encode($res);
        setUser($from_id, 'step', '-1');
        sendMessage($chat_id, "SQL Query has sent to the server\n\n<b>* Query:</b> <code>$query</code>\n<b>* Response:</b> <code>$json</code>", $message_id, $adminKey);
    }
}
elseif(strtolower($text) == 'block/unblock a user' && $tc == 'private') {
    if(isUserAdmin($from_id) && getUserAdmin($from_id) > 1) {
        setUser($from_id, 'step', 'blockuser');
        sendMessage($chat_id, "Send me the user id of who you want to block/unblock", $message_id, $backKey);
    }
}
elseif(strtolower($text) == 'create discount code' && $tc == 'private') {
    if(isUserAdmin($from_id)) {
        setUser($from_id, 'step', 'cd');
        sendMessage($chat_id, "Send percentage of discount code\nFor example: 10", $message_id, $backKey);
    }
}
elseif(strtolower($text) == 'check a user' && $tc == 'private') {
    if(isUserAdmin($from_id)) {
        setUser($from_id, 'step', 'cu');
        sendMessage($chat_id, "Send me the user id of who you want to check them", $message_id, $backKey);
    }
}
elseif(strtolower($text) == 'settings') {
    if(isUserAdmin($from_id)) {
        if(getUserAdmin($from_id) > 1) {
            setUser($from_id, 'step', '-1');
            sendMessage($chat_id, "Settings Panel opened", $message_id, retIKey10($from_id));
            sendMessage($chat_id, "Back to Home", -1, $adminKey);
        }
        else {
            setUser($from_id, 'step', '-1');
            sendMessage($chat_id, "You cannot use this section", $message_id, $adminKey);
        }
    }
}
elseif(strtolower($text) == 'reset payments' && $tc == 'private') {
    if(isUserAdmin($from_id)) {
        if(getUserAdmin($from_id) > 1) {
            setUser($from_id, 'step', 'resetpayments');
            sendMessage($chat_id, "This section is for RESETTING all payments made by users.\nIf you are sure about what are you doing, use confirm button", $message_id, retIKey25($from_id));
            sendMessage($chat_id, "Back to Home", -1, $backKey);
        }
        else {
            setUser($from_id, 'step', '-1');
            sendMessage($chat_id, "You cannot use this section", $message_id, $adminKey);
        }
    }
}
elseif(strtolower($text) == 'check a payment' && $tc == 'private') {
    if(isUserAdmin($from_id)) {
        setUser($from_id, 'step', 'cp');
        sendMessage($chat_id, "Send me the id of the payment", $message_id, $backKey);
    }
}
elseif(strtolower($text) == "set users' balance" && $tc == 'private') {
    if(isUserAdmin($from_id)) {
        if(getUserAdmin($from_id) > 1) {
            setUser($from_id, 'step', 'setbal');
            sendMessage($chat_id, "Send me the user id of who you want to set their balance\n\nSend '<code>me</code>' for yourself", $message_id, $backKey);
        }
        else {
            setUser($from_id, 'step', '-1');
            sendMessage($chat_id, "You cannot use this section", $message_id, $adminKey);
        }
    }
}
elseif(strtolower($text) == 'send message to all' && $tc == 'private') {
    if(isUserAdmin($from_id)) {
        if(getUserAdmin($from_id) > 1) {
            $users = number_format(getUsers());
            setUser($from_id, 'step', 'pmall');
            sendMessage($chat_id, "Send me whatever you want to send to <code>$users</code> users", $message_id, $backKey);
        }
        else {
            setUser($from_id, 'step', '-1');
            sendMessage($chat_id, "You cannot use this section", $message_id, $adminKey);
        }
    }
}
elseif(strtolower($text) == "promote/demote a user" && $tc == 'private') {
    if(isUserAdmin($from_id)) {
        if(getUserAdmin($from_id) > 1) {
            setUser($from_id, 'step', 'pdadmin');
            sendMessage($chat_id, "Send me the user id of who you want to promote/demote", $message_id, $backKey);
        }
        else {
            setUser($from_id, 'step', '-1');
            sendMessage($chat_id, "You cannot use this section", $message_id, $backKey);
        }
    }
}
elseif(strtolower($text) == "create an account" && $tc == 'private') {
    if(isUserAdmin($from_id)) {
        setUser($from_id, 'step', 'cr');
        sendMessage($chat_id, "Send me an email address", $message_id, $crKey);
    }
}
elseif(strtolower($text) == 'create a manually shutterstock account' && $tc == 'private') {
   if(isUserAdmin($from_id)) {
        setUser($from_id, 'step', 'crshutter');
        sendMessage($chat_id, "Send me an email", $message_id, $backKey);
    } 
}
elseif(strtolower($text) == 'create a random shutterstock account' && $tc == 'private') {
   if(isUserAdmin($from_id)) {
        $account = createShutterstock();
        $email = $account['email'];
        $password = $account['password'];
        setUser($from_id, 'step', '-1');
        sendMessage($chat_id, "Shutterstock account created!\n\n__________________________________\nAccount Email: $email\nAccount Password: <code>$password</code>\n__________________________________", $message_id, $adminKey);
    } 
}
elseif(strtolower($text) == 'add a product' && $tc == 'private') {
    if(isUserAdmin($from_id)) {
        setUser($from_id, 'step', 'addproduct');
        sendMessage($chat_id, "Please send me the product name", $message_id, $backKey);
    }
}
elseif(strtolower($text) == 'add a reminder' && $tc == 'private') {
    if(isUserAdmin($from_id)) {
        setUser($from_id, 'step', 'addreminder');
        sendMessage($chat_id, "Please send me how many days later do you want to get noticed this reminder", $message_id, $backKey);
    }
}
elseif(strtolower($text) == 'delete a product' && $tc == 'private') {
    if(isUserAdmin($from_id)) {
        setUser($from_id, 'step', 'delprod');
        sendMessage($chat_id, "Please send me the product name TO DELETE", $message_id, $backKey);
    }
}
elseif(strtolower($text) == 'add a shared account' && $tc == 'private') {
    if(isUserAdmin($from_id)) {
        setUser($from_id, 'step', 'addshared');
        sendMessage($chat_id, "Please send me the shared account name", $message_id, $backKey);
    }
}
elseif(strtolower($text) == 'change details' && $tc == 'private') {
    if(isUserAdmin($from_id)) {
        setUser($from_id, 'step', 'chngshared');
        sendMessage($chat_id, "Please send me the shared account name", $message_id, $backKey);
    }
}
elseif(strtolower($text) == 'change price' && $tc == 'private') {
    if(isUserAdmin($from_id)) {
        setUser($from_id, 'step', 'chprcshared');
        sendMessage($chat_id, "Please send me the shared account name", $message_id, $backKey);
    }
}
elseif(strtolower($text) == 'check a shared account' && $tc == 'private') {
    if(isUserAdmin($from_id)) {
        if(getUserAdmin($from_id) > 1) {
            setUser($from_id, 'step', 'checkshared');
            sendMessage($chat_id, "Please send me the shared account name", $message_id, $backKey);
        }
        else {
            setUser($from_id, 'step', '-1');
            sendMessage($chat_id, "You cannot use this section", $message_id, $adminKey);
        }
    }
}
elseif(strtolower($text) == 'add an account' && $tc == 'private') {
    if(isUserAdmin($from_id)) {
        setUser($from_id, 'step', 'addacc');
        sendMessage($chat_id, "Please send me the shared account name", $message_id, $backKey);
    }
}
elseif(strtolower($text) == 'delete a shared account' && $tc == 'private') {
    if(isUserAdmin($from_id)) {
        setUser($from_id, 'step', 'delshared');
        sendMessage($chat_id, "Please send me the shared account name TO DELETE", $message_id, $backKey);
    }
}
elseif(strtolower($text) == 'delete an account' && $tc == 'private') {
    if(isUserAdmin($from_id)) {
        setUser($from_id, 'step', 'delshacc');
        sendMessage($chat_id, "Please send me the shared account name TO DELETE", $message_id, $backKey);
    }
}
elseif(strtolower($text) == 'add a file' && $tc == 'private') {
    if(isUserAdmin($from_id)) {
        setUser($from_id, 'step', 'addfootage');
        sendMessage($chat_id, "Please send me the file name", $message_id, $backKey);
    }
}
elseif(strtolower($text) == 'delete a file' && $tc == 'private') {
    if(isUserAdmin($from_id)) {
        setUser($from_id, 'step', 'delfoot');
        sendMessage($chat_id, "Please send me the file name TO DELETE", $message_id, $backKey);
    }
}
elseif(strtolower($text) == 'delete an archived file' && $tc == 'private') {
    if(isUserAdmin($from_id)) {
        setUser($from_id, 'step', 'delarch');
        sendMessage($chat_id, "Please send me the file or it's link to delete", $message_id, $backKey);
    }
}
elseif(strtolower($text) == 'add a number' && $tc == 'private') {
    if(isUserAdmin($from_id)) {
        setUser($from_id, 'step', 'addnum');
        sendMessage($chat_id, "Please send me the number name", $message_id, $backKey);
    }
}
elseif(strtolower($text) == 'delete a number' && $tc == 'private') {
    if(isUserAdmin($from_id)) {
        setUser($from_id, 'step', 'delnum');
        sendMessage($chat_id, "Please send me the number name TO DELETE", $message_id, $backKey);
    }
}
elseif($text == SECRET_CHANNEL_CODE && $tc == 'private') {
    if(isPremium($from_id) && !isSubExpired($from_id)) {
        $invite_link = createChatInviteLink(getSettings('secret_channel'));
        if(isLink($invite_link)) {
            setUser($from_id, 'step', '-1');
            sendMessage($chat_id, "Click the button below to join our secret channel", $message_id, retIKey14($invite_link));
            sendMessage($chat_id, "Back to Home", -1, $startKey);
            checkPremiumQuests($from_id, 6);
        }
    }
}
else {
    if(!is_null($text) && !empty($text) && $tc == 'private') {
        $ai = getAIAnswer($text);
        $ai_js = json_decode($ai, true);
        $content = $ai_js['choices'][0]['message']['content'];
        if(!is_null($content) && !empty($content)) {
            sendMessage($chat_id, "$content", $message_id);
        }
    }
}
