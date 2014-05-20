<?php
session_start();
require_once("util.php");
require_once("DB.class.php");


if (!isset($_GET['coupon_name'])) {
    alertMessage("Invalid Request!", "item_list.php");
    return;
}

$item_name = trim($_GET['coupon_name']);

if (!isset($_SESSION['logon']) || !isset($_SESSION['user'])) {
    header("Location: logon_form.php?redeem={$item_name}");
    return;
}


$format = sprintf('<form action="redeem_item.php" method="post">
                Item Name: <input type="text" name="name" value="%s" readonly/>
                Coupon Code: <input type="text" name="code" size="30" maxlength="30" value=""/>
                <input type="submit" value="Use" />
                </form><br/>', $item_name);
echo $format;

?>
