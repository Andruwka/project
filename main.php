<?php
require_once "ip.php";

if (isset($_COOKIE['admin_id'])){
	echo <<<EOT
	<!DOCTYPE html>
	<html>
	<head>
		<title>Main page</title>
	</head>
	<body>
	<center>
	<h1>Привет, {$_COOKIE['username']}!</h1>
	<form action="quit.php"  method='post'>
	<input type='submit' value='razloginitsya'/>
	</form>	
	</center>
	</body>
	</html>
EOT;
}
else{
	header('Location: http://'.$ip.'/auth.php');
}
?>