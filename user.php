<?php
require_once "connect.php";
require_once "ip.php";

session_start();

if ($_COOKIE['admin_id'] ){
	print($GLOBALS['move_number']);
	$admin_id = $_COOKIE['admin_id'];
	$user_id = $_GET['user_id'];
	if ($_SESSION['user_id'] != $user_id){
		$_SESSION['prev_user_id'] = $_SESSION['user_id'];
		$_SESSION['user_id']= $user_id;
		$_SESSION['last_request'] = -1;
	}
	else{
		if($_SESSION['last_request'] == 1){
			$last_request_message = 'Успешно';
		}
		else if ($_SESSION['last_request'] == 0){
			$last_request_message = 'Что- то пошло не так';
		}
		else{ }
	}
	$request = mysqli_fetch_array(mysqli_query($link, "SELECT * FROM users WHERE id='{$user_id}'"));
	$user_name = $request[1];
	$head = "<!DOCTYPE html>
			<html>
				<head>
					<title>Main page</title>
				</head>
				<body>
					<center>
						<h2>Добро пожаловать, {$user_name}!</h2>
					</center>";

	$admin_2 ="
				<center>
				<form action=\"admin_action.php\"  method=\"POST\">
					<input type=\"submit\" value=\"Апгрейд экономики\" name=\"upgrade_P1\"/>
					<input type=\"submit\" value=\"Апгрейд ВПК\" name=\"upgrade_P3\"/>
				</form>
				<br><br><br>
				</center>";

	$admin_3="
				<center>
				<form action=\"admin_action.php\"  method=\"POST\">
					<input type=\"submit\" value=\"Работа. Экономика\" name=\"work_economy\"/>
					<input type=\"submit\" value=\"Работа. Легитимность\" name=\"work_leg\"/>
					<input type=\"submit\" value=\"Работа. ВПК\" name=\"work_vpk\"/>
				</form>
				<br><br><br>
				<center>";
	$admin_5="
				<center>
				<h2>From {$_SESSION['prev_user_id']} To {$_SESSION['user_id']}</h2>
				<form action=\"admin_action.php\"  method=\"POST\">
					<input type=\"checkbox\" name=\"oil\"> Нефть <br>
					<input type=\"checkbox\" name=\"metal\"> Металл<br>
					<input type=\"number\" name=\"point_cost\"> Цена товара<br>
					<input type=\"submit\" value=\"Совершить сделку\"/>
				</form>
				<br><br><br><br>
				<center>";
	$admin_6="
				<center>
				<form action=\"admin_action.php\"  method=\"POST\">
					<input type=\"submit\" value=\"Ход\" name=\"move\"/>
				</form>
				<center>";

	$tail = "<br><br><br>
			$last_request_message
		</body>
	</html>";

	$page = $head;
	if (($admin_id==2) or ($admin_id == 6)){
		$page .= $admin_2;
	}
	if (($admin_id == 3)||($admin_id == 6)){
		$page .= $admin_3;
	}
	if (($admin_id == 5)||($admin_id == 6)){
		$page .= $admin_5;
	}
	if ($admin_id == 6){
		$page .= $admin_6;
	}
	$page .= $tail;
	echo $page;
}
else if(!$_COOKIE['admin_id'] ){
	header('Location: http://'.$ip.'/auth.php');
}
?>