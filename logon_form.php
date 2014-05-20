
<html>
<head>
    <title>Logon Form</title>
</head>
<body>
    <?php 
    session_start();
    
    if (isset($_SESSION['logon']) && isset($_SESSION['user']) && 
        $_SESSION['logon'] === true) {
        echo "You are already log on as {$_SESSION['user']}",
                '<br/>',
                '<a href="logout.php">logout</a><br />',
                '<br/>',
                '<a href="item_list.php"> Go to item_list.php</a><br />';
        
    } else { //
        $frm = '<form action="logon.php" method="post">
            User ID: <input type="text" name="username" /><br />
            Password: <input type="password" name="pwd" />
            <input type="submit" value="Submit" />';
        
        if (isset($_GET['redeem'])) {
            $frm .= sprintf('<input type="hidden" name="name" value="%s" />',
                        $_GET['redeem']);
        }
        $frm .= '</form><br/><a href="register_form.php">Register User</a>';
        
        echo $frm;
    }
    ?>
</body>
</html>