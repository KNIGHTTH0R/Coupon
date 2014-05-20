<?php

require_once("util.php");
require_once('DB.class.php');

if (!isset($_POST['username']) || !isset($_POST['email'])
    || !isset($_POST['pwd']) || !isset($_POST['pwd2']) ) {
    alertMessage("Data error!", "register_form.php");
    return;
}


$username = trim($_POST['username']);
$email = trim($_POST['email']);
$pwd = trim($_POST['pwd']);
$pwd2 = trim($_POST['pwd2']);

// check email
if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
    alertMessage("Invalid email!", "register_form.php");
    return;
}

// verify password
if ($pwd !== $pwd2) {
    alertMessage("Passwords are different!", "register_form.php");
    return;
}


$db = new DB();
$ret = $db->addUser($username, $email, md5($pwd));
$db->close();

if (!$ret[0]) {
    alertMessage("Registration Error {$ret[1]}!", "register_form.php");
} else {
    alertMessage("Register user {$username} OK!", "logon_form.php");
}
/*
echo <<<S
    <br/> <br/>
    <a href="index.php"> Go To index.php</a><br/>
    <a href="register_form.php"> Go To register_form.php</a><br/>
S;
*/
?>