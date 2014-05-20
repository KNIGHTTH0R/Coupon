<?php
session_start();
require_once("util.php");
require_once("DB.class.php");


if (!isset($_SESSION['logon']) || !isset($_SESSION['user'])) {
    header("Location: item_list.php");
    return;
}

if (!isset($_POST['code']) || !isset($_POST['name'])) {
    alertMessage("You must input the coupon code and item name", 
            "item_list.php");
}

$code = trim($_POST['code']);
$name = trim($_POST['name']);
$user = $_SESSION['user'];

// open db 1. check user 2. check quantity 3. if enough, return user ok
$db = new DB();
list($ret, $info) = $db->redeemCoupon($user, $code, $name);
$db->close();

if ($ret === 0) {
    alertMessage("You redeemed the coupon {$code}", "item_list.php");
} else {
    $msg = "Opps, Fail to redeem the item. ";
    $msg .= "Maybe exceeds the item max limitation or some error occurs!";
    alertMessage($msg, "item_list.php");
} 
