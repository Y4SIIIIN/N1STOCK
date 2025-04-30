<?php
//Time Will Tell How Much I Love You
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

function sendMessage($chat_id, $message) {
    global $token;
    $url = "https://api.telegram.org/bot$token/sendMessage";
    $post_fields = array(
        'chat_id' => $chat_id,
        'text' => $message
    );
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type:multipart/form-data"));
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
    curl_exec($ch);
    curl_close($ch);
}
?>
