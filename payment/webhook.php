<?php
require '../config.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'));
if($data->event === 'order:paid') {
    $device = "Desktop";
    $order_id = $data->data->order->id;
    $product_id = $data->data->order->product_id;
    $price = $data->data->order->price;
    $currency = $data->data->order->currency;
    $email = $data->data->order->email;
    $gateway = $data->data->order->gateway;
    $title = $data->data->order->product->title;
    $user_id = $data->data->order->product->custom_fields->user_id;
    $coin = $data->data->order->product->custom_fields->coin;
    $ip = $data->data->order->agent->geo->ip;
    $country = $data->data->order->agent->geo->country;
    $city = $data->data->order->agent->geo->city;
    $is_mobile = $data->data->order->agent->data->is_mobile;
    $is_tablet = $data->data->order->agent->data->is_tablet;
    $is_desktop = $data->data->order->agent->data->is_desktop;
    $platform = $data->data->order->agent->data->platform;
    $browser = $data->data->order->agent->data->browser->name;
    $version = $data->data->order->agent->data->browser->version;
    $payment = -1;
    $pp = "payment";
    if($is_mobile) {
        $device = "Mobile";
    }
    elseif($is_tablet) {
        $device = "Tablet";
    }
    elseif($is_desktop) {
        $device = "Desktop";
    }
    if(isFind($title, '@')) {
        $payment = explode('@', $title)[1];
        setPayment($payment, 'status', '1');
        $pp = "#Payment(dbID:<code>$payment</code>)";
    }
    $time = date('Y-m-d H:i:s');
    $mention = mentionUser($user_id);
    setUser($user_id, 'balance', (getUser($user_id, 'balance') + $coin));
    sendMessage($user_id, "<b>*</b> Your payment was successfully, information listed below:\n\n_____________________________\nTitle: <code>$title</code>\nOrderID: <code>$order_id</code>\nPrice: <code>$price $currency</code>\nEmail: <code>$email</code>\nGateway: <code>$gateway</code>\nStock Coins Added: <code>$coin</code>\n_____________________________\n\n[ <code>$time</code> ]");
    sendMessage($user_id, "An email has sent you from <b>Shoppy</b>.\nCheck your mail and send us your <b>feedback</b> about our services, thanks.");
    sendAdminMessage("#admin\nUser $mention has made a successfully $pp\n\n_____________________________\nTitle: <code>$title</code>\nOrderID: <code>$order_id</code>\nPrice: <code>$price $currency</code>\nEmail: <code>$email</code>\nGateway: <code>$gateway</code>\nIP: <code>$ip</code>\nGeography: <code>$country</code>/<code>$city</code>\nDevice: <code>$device</code>\nPlatform: <code>$platform</code>\nBrowser: <code>$browser $version</code>\nStock Coins Added: <code>$coin</code>\n_____________________________");
    checkReferralPrize($user_id, $coin);
    echo json_encode(array('ok' => true, 'result' => number_format($coin).' stock coins added to '.$user_id.' account wallet'), 128);
}
else {
    if(isset($_POST['item_number'])) {
        $item = $_POST['item_number'];
        if(getPayPalPayment($item, 'payment_status') == '1') {
            $stockcoins = getPayPalPayment($item, 'stockcoins');
            $user_id = getPayPalPayment($item, 'user');
    	    echo json_encode(array('ok' => true, 'result' => number_format($stockcoins).' stock coins was already added to '.$user_id.' account wallet'), 128);
    	    exit;
    	}
    }
    $data = file_get_contents('php://input');
    $datas = explode('&', $data);
    $post = array();
    foreach($datas as $d) {
    	$d = explode ('=', $d);
    	if(count($d) == 2) {
    	    $post[$d[0]] = urldecode($d[1]);
    	}
    }
    $req = 'cmd=_notify-validate';
    if(function_exists('get_magic_quotes_gpc')) {
    	$get_magic_quotes_exists = true;
    }
    foreach($post as $key => $value) {
        if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
    		$value = urlencode(stripslashes($value));
    	}
    	else {
    	    $value = urlencode($value);
    	}
    	$req .= "&$key=$value";
    }
    $ch = curl_init(PAYPAL_URL);
    if(!$ch) {
        echo json_encode(array('ok' => false, 'result' => 'error'), 128);
    	return false;
    }
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
    curl_setopt($ch, CURLOPT_SSLVERSION, 6);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close', 'User-Agent: PHP-IPN-Verification-Script'));
    $res = curl_exec($ch);
    if(curl_errno($ch) != 0) {
        curl_close($ch);
        echo json_encode(array('ok' => false, 'result' => 'error'), 128);
        exit;
    }
    else {
    	curl_close($ch);
    }
    $tokens = explode("\r\n\r\n", trim($res));
    $res = trim(end($tokens));
    if(strcmp($res, "VERIFIED") == 0 || strcasecmp($res, "VERIFIED") == 0) {
    	$item_number = $_POST['item_number'];
    	if(!isPayPalPaymentExist($item_number)) exit;
    	$item_name = $_POST['item_name'];
    	$payment_status = $_POST['payment_status'];
    	$amount = $_POST['mc_gross'];
    	$currency = $_POST['mc_currency'];
    	$txn_id = $_POST['txn_id'];
    	$receiver_email = $_POST['receiver_email'];
    	if(strtolower($receiver_email) != strtolower(PAYPAL_EMAIL) || strtolower($currency) != strtolower(getPayPalPayment($item_number, 'currency'))) {
    	    echo json_encode(array('ok' => false, 'result' => 'error'), 128);
    	    exit;
    	}
    	$time = date('Y-m-d H:i:s');
    	setPayPalPayment($item_number, 'payment_status', '1');
    	setPayPalPayment($item_number, 'txn_id', "$txn_id");
    	$user_id = getPayPalPayment($item_number, 'user');
    	$stockcoins = getPayPalPayment($item_number, 'stockcoins');
        $mention = mentionUser($user_id);
        setUser($user_id, 'balance', (getUser($user_id, 'balance') + $stockcoins));
        sendMessage($user_id, "<b>*</b> Your payment was successfully, information listed below:\n\n_____________________________\nPaymentID: <code>$item_number</code>\nPrice: <code>$amount $currency</code>\nGateway: <code>PayPal</code>\nStock Coins Added: <code>$stockcoins</code>\n_____________________________\n\n[ <code>$time</code> ]");
        sendAdminMessage("#admin\nUser $mention has made a successfully PayPal Payment\n\n_____________________________\nPaymentID: <code>$item_number</code>\nPrice: <code>$price $amount</code>\nGateway: <code>PayPal</code>\nStock Coins Added: <code>$stockcoins</code>\n_____________________________");
        checkReferralPrize($user_id, $stockcoins);
        echo json_encode(array('ok' => true, 'result' => number_format($stockcoins).' stock coins added to '.$user_id.' account wallet'), 128);
    }
    else {
        echo json_encode(array('ok' => false, 'result' => 'error'), 128);
    }
}
unlink('sendtoall.json');