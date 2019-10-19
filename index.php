<?php 
if (isset($_COOKIE['username']) and isset($_COOKIE['password'])){
	header("Location: http://game.ru/main.php");
}
else{
	require_once "connect.php";	
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Главная</title>
</head>
<body>
<form action="action.php" method="POST">
	<label>Логин</label><br>
	<input type="name" name="username"><br>
	<label>Пароль</label><br>
	<input type="password" name="password">
	<input type="submit" name="pofig" value="Авторизация">
</form>
</body>
</html>