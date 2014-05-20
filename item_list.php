<?php session_start(); 
if (isset($_SESSION['logon']) && isset($_SESSION['user']) && 
    $_SESSION['logon'] === true) {
    echo "Hello, {$_SESSION['user']}", '<br/>', 
        '<a href="logout.php">Log out</a><br/><br/>';
} else {
    echo "Hello, Guest", '<br/>', 
        '<a href="logon_form.php">Please log on first!</a><br/><br/>';
}

?>
<html>
    <head>
        <Title> Item List </Title>
    </head>
    <body>
        <!--<a href="index.php"> Go to index.php </a><br/><br/>-->
        
        <?php
            
            require_once('DB.class.php');
            
            $db = new DB();
            $items = $db->getItemList();
            $db->close();
            
            $format = '<form action="request_coupon.php" method="post">
                        Item Name: <input type="text" name="name" value="%s" readonly />
                        Available Quantity: 
                        <input type="text" value="%d" readonly />
                        <input type="hidden" name="id" value="%s" />
                        %s
                        </form><br/>';
            
            
            foreach ($items as $it) {
                // if still OK, appears the button 
                $btn = ($it['amount'] > 0) ? 
                    '<input type="submit" value="Get e-coupon" />' : '';
                echo sprintf($format, $it['item_name'], $it['amount'], 
                        $it['coupon_id'], $btn);
            }
        ?>
    </body>
</html>
