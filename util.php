<?php

function alertMessage($msg="", $redirect="item_list.php") {
    echo "<script> alert(\"{$msg}\"); window.location = '{$redirect}'; </script>";
}


?>