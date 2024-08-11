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
            ['text' => "LenZzZ"]
        ],
        [
            ['text' => "PAY"], ['text' => "Subscriptions"]
        ], 
    ],
    "resize_keyboard" => true, 'one_time_keyboard' => true
]);
$sbsKey = json_encode([
    'keyboard' => [
        [
            ['text' => "PREMIUM"]
        ],
        [
            ['text' => "Continental Hotel"]
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
            ['text' => "Buy Stock Coins"]
        ],
        [
            ['text' => "Back"]
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
                sendMessage($chat_id, "[$mention]\n<b>You have no subscription inside the bot , Subscriptions > PREMIUM > BUY</b>");
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
            elseif(isFind($product_id, 'BSSC_')) {
                $product = "$cost Stock Coins Requested";
            }
            elseif(isFind($product_id, 'BSTON_')) {
                $product = "$cost Stock Coins TON Requested";
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


