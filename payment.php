<?php
require 'config.php';
header('Content-Type: application/json');
if(isset($_GET['trans_id']) && isset($_GET['order_id']) && isset($_GET['amount'])) {
    $paid = getTrans($_GET['trans_id'], 'paid');
    if($paid == '0') {
        $verify = verifyTransaction($_GET['trans_id']);
        $verify = json_decode($verify, true);
        $custom = json_decode($verify['custom'], true);
        $code = $verify['code'];
        $stockcoins = $custom['stockcoins'];
        $pid = $custom['pid'];
        if($code == '0') {
            $user = getTrans($_GET['trans_id'], 'user');
            $percent = getPayment($pid, 'discount');
            $mention = mentionUser($user);
            setTrans($_GET['trans_id'], 'paid', '1');
            setTrans($_GET['trans_id'], 'cardno', $verify['card_holder']);
            setTrans($_GET['trans_id'], 'time', time());
            setPayment($pid, 'status', '1');
            setUser($user, 'balance', (getUser($user, 'balance') + $stockcoins));
            sendMessage($user, "Payment of receipt #".$_GET['order_id']." was successfully\n<b>$stockcoins</b> stock coins has added to your account wallet");
            sendAdminMessage("#admin\n#Payment(dbID:<code>$pid</code>)\n<b>$stockcoins Stock Coins</b> has added to $mention's account wallet by paying ".number_format($_GET['amount'])." IRT".($percent > 0 ? " by using <b>$percent%</b> discount code" : ""));
            checkReferralPrize($user, $stockcoins);
            echo json_encode(array('status' => true, 'result' => number_format($stockcoins).' stock coins added to user '.$user), 128);
        }
        else {
            echo json_encode(array('status' => false, 'result' => array('error' => $code, 'message' => 'an error occurred')), 128);
        }
    }
    else {
        echo json_encode(array('status' => false, 'result' => array('error' => -1, 'message' => 'transaction already paid')), 128);
    }
}
else {
    echo json_encode(array('status' => false, 'result' => array('error' => -1, 'message' => 'parameters error')), 128);
}