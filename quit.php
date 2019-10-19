<?php
require_once "ip.php";
session_start();
setcookie('password','', 0); 
setcookie('username','', 0); 
setcookie('admin_id','',0);
session_destroy();
header('Location: http://'.$ip.'/auth.php');
?>