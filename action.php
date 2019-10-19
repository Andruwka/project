<?php 
require_once "connect.php";
require_once "ip.php";

	$username = isset($_POST["username"]) ? trim (mysqli_real_escape_string($link, $_POST["username"])) : "";
	$password = isset($_POST["password"]) ? trim (mysqli_real_escape_string($link, $_POST["password"])) : "";


	$list = mysqli_fetch_array(mysqli_query($link, "SELECT * FROM admins WHERE username='{$username}' AND password='{$password}'")); 	
	if(isset($list)){
		$admin_id = $list[0];
		setcookie('admin_id', $admin_id, time() + 3600*5);
		setcookie('username',$username, time() + 3600*5);
		setcookie('password',$password, time() + 3600*5);
		header('Location: http://'.$ip.'/main.php');
	}
	else{
		echo "Something went wrong <br> <a href=\"auth.php\">Try again</a>";
	}

?>