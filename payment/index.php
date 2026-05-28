<?php
header('Content-Type: application/json');
require '../config.php';
define('API_URL', 'https://shoppy.gg/api/');
define('HEADERS', array('Content-Type:application/json', 'User-Agent:Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.110 Safari/537.36', 'Authorization:'.PAY_API));

if(isset($_GET['name'], $_GET['price'], $_GET['user_id'], $_GET['coin'], $_GET['key'])) {
    if($_GET['key'] == PAY_KEY) {
        $name = $_GET['name'];
        $price = $_GET['price']*85;
        $user_id = $_GET['user_id'];
        $coin = $_GET['coin'];
        
        $ch = curl_init(API_URL.'v2/pay');
        $payload = json_encode(array("title" => $name, 'value' => $price, 'webhook_urls' => array(currentPath()."webhook.php"), 'custom_fields' => array('user_id' => $user_id, 'coin' => $coin)));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, HEADERS);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        
        $data = json_decode($result, true);
        $url = $data['url'];
        $id = $data['id'];
        echo json_encode(array('ok' => true, 'url' => $url, 'id' => $id), 128);
    }
}
unlink('sendtoall.json');