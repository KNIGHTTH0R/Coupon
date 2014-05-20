<?php

class DB {
    private $conn = false;
    private $msg = '';
    
    public function __construct() {
        require_once("DB.config.php");
        try {
            if (!defined('DB_USER') || !defined('DB_PASSWORD') || 
                !defined('DB_DATABASE') || !defined('DB_HOST')) {
                throw new Exception("Undefined Config!");        
            } else {
                $str = sprintf("mysql:host=%s;dbname=%s", 
                            DB_HOST, DB_DATABASE);
                $this->conn = new PDO($str, DB_USER, DB_PASSWORD, 
                            array(PDO::ATTR_PERSISTENT => true));
                            
                if (!$this->conn->setAttribute(PDO::ATTR_ERRMODE, 
                                            PDO::ERRMODE_EXCEPTION)) {
                    throw new Exception('error');
                }
            }
        } catch (Exception $e) { 
            die("Opps, there is a website internal error!");
        }
    } 
    
    public function __destruct() {
        $this->close();
    }
    
    public function close() {
        $this->conn = null;
    }
    
    private function setMessage($m='') {
        $this->msg = $m;
    }
    
    public function getErrorMessage() {
        return $this->msg;
    }
    
    public function addUser($user=false, $email='', $pwd=''){
        if (!$user) {
            return array(false, "No user name!");
        }
        
        try {
            // check whether user already register
            $sql = "SELECT count(*) FROM Users WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(array($user));
            if ($stmt->fetchColumn() > 0) {
                return array(false, "User '{$user}' is already registered!");
            }
            
            // start insert data into table Users
            $sql = "INSERT INTO Users(id, email, passwd) VALUES (?,?,?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(array($user, $email, $pwd));
            
            
            if ($stmt->rowCount() > 0) {
                return array(true, $stmt->rowCount());
            } else { 
                return array(false, $stmt->rowCount());
            }
        } catch(Exception $e) {
            return array(false, "Error: {$e->getMessage()}");
        }
        
    }
    
    public function getEmail($user=false) {
        if (!$user) {
            return array(false, "No user name!");
        }
        
        try{
            $sql = "SELECT email FROM Users WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(array($user));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $email = ($row) ? $row['email'] : false;
            return ($email) ? array(true, $email) : array(false, '');
        } catch(Exception $e) {
            return array(false, "Error: {$e->getMessage()}");
        }
        
    }
    
    public function verifyUser($user=false, $pwd='') {
        if(!$user) {
            return array(false, "No user name!");
        }
        
        try {
            $sql = "SELECT id FROM Users WHERE id = ? AND passwd = ?";
            $stmt = $this->conn->prepare($sql);
            
            $stmt->execute(array($user, $pwd));
            
            $row = $stmt->fetchAll();
            $ret = (count($row) > 0) ? array(true, "") : array(false, "") ;
            return $ret;
        } catch(Exception $e) {
            return array(false, "Error: {$e->getMessage()}");
        }
    }
    
    public function getItemList() {
        $sql = "SELECT coupon_id, item_name, 
                    max_quantity - quantity AS amount FROM Coupons";
        try {
            $ret = array();
            $result = $this->conn->query($sql);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $ret[] = $row;
            }
            
            return $ret;
        } catch(Exception $e) {
            return array(false, "Error: {$e->getMessage()}");
        }
    }
    
    public function getCouponItem($code='') {
        try {
            $sql = "SELECT item_name FROM Coupons WHERE coupon_id=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(array($code));
            
            if ($stmt->rowCount() <= 0) {
                return array(false, "Invalid Code!");
            } else {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                return array(true, $row['item_name']);
            }
        } catch(Exception $e) {
            return array(false, "Error: {$e->getMessage()}");
        }
    }
    
    public function redeemCoupon($user=false, $code='', $name='') {
        if (!$user) {
            return array(3, "No user name!");
        }
        
        try {
            // Do the transaction!!
            if (!$this->conn->beginTransaction()) {
                throw new Exception("Could not start the transaction!");   
            }
            
            // try to redeem this item
            $sql = "UPDATE Coupons SET quantity = quantity + 1 
                    WHERE coupon_id = ? AND item_name = ? 
                        AND max_quantity > quantity";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(array($code, $name));
            
            // could not redeem the item
            if ($stmt->rowCount() <= 0) {
               throw new Exception("Opps, there are insufficient amount to redeem!");
            }
            $stmt->closeCursor();
          
            //write to the redeem list
            $sql = "INSERT INTO redeem_list(cid, uid, count) VALUES (?, ?, 1)
                    ON DUPLICATE KEY UPDATE count = count + 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(array($code, $user));
            
            // could not redeem this item
            if ($stmt->rowCount() <= 0) {
               throw new Exception("Opps, fail to insert into redeem list");
            }
            
            $this->conn->commit();
            return array(0, "OK~");
        } catch(Exception $e) {
            $this->conn->rollBack();
            return array(3, "Error: {$e->getMessage()}");
        }        
    }
}

/*
$db = new DB();
$res = $db->getCouponItem('yamlin', '2111');
$db->close();
var_dump($res);
*/

?>