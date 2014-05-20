<?php
    session_start();
?>
<html>
<head>
    <title>Logon Form</title>
</head>
<body>
    <?php
    
    if (isset($_SESSION['logon']) && isset($_SESSION['user']) && 
        $_SESSION['logon'] === true) {
    
        echo "You are logging on, please log out first!<br/>",
            '<a href="logout.php">Log out</a><br/>',
            '<a href="item_list.php">Back to item_list.php </a><br/>';
    } else {
        echo '<a href="logon_form.php"> Log on </a><br/><br/>
            <form action="register.php" method="post">
            User ID: <input type="text" name="username"/><br/>
            Email: <input type="text" name="email"/><br/>
            Password: <input type="password" name="pwd"/><br/>
            Password Again: <input type="password" name="pwd2"/>
            <input type="submit" value="Register"/>
            </form>';
    }
    ?>
    
</body>
</html>