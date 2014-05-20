<?php
    session_start();
    require_once("util.php");
    require_once('DB.class.php');
    if (!isset($_POST['username']) || !isset($_POST['pwd'])) {
        echo "No data!";
        return;
    }
    
    $user = trim($_POST['username']);
    $pwd = trim($_POST['pwd']);
    
   
    $db = new DB();
    $ret = $db->verifyUser($user, md5($pwd));
    $db->close();
    
    if ($ret[0] === true) {
        
        $_SESSION['logon'] = true;
        $_SESSION['user'] = $user;
        
        // redirect to redeem page
        if (isset($_POST['name'])) {
            header("Location: redeem_form.php?coupon_name={$_POST['name']}");
        } else {
            header("Location: item_list.php");
        }
    } else {
        alertMessage("Authenitication Fail!\\nPlease try it again!", "logon_form.php");
    }
    //var_dump($_SESSION);
    
    
?>