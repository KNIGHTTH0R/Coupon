<?php
    session_start();
    require_once("util.php");
    require_once("DB.class.php");
    
    if (!isset($_SESSION['logon']) || !isset($_SESSION['user'])) {
        header("Location: logon_form.php");
        return;
    }
    
    if (!isset($_POST['id']) || !isset($_POST['name'])) {
        header("Location: item_list.php");
        return;
    }
    
    
    // get user email
    $db = new DB();
    list($ret, $email) = $db->getEmail($_SESSION['user']);
    $db->close();
    
    if (!$ret) {
        alertMessage("Could not get user email!", "item_list.php");
        return;
    } 
    
    $url = "Link: https://{$_SERVER['HTTP_HOST']}/Coupon/redeem_form.php?";
    $url .= "coupon_name={$_POST['name']}\r\n";
    $url .= "Item Name: {$_POST['name']}\r\n";
    $url .= "Coupon Code: {$_POST['id']}";
    
    
    if (mail($email, 'Your Issued Coupon', $url)) {
        alertMessage("Mail Sent!", "item_list.php");
    } else {
        alertMessage("Fail to sent mail! Please retry it!", "item_list.php");
    }
   
?>