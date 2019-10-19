<?php
require_once "connect.php";
require_once "ip.php";

$users = mysqli_query($link,"SELECT * FROM users WHERE ID < 100");
echo "<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
<table border=\"1px\" width=\"90%\">
<tr><td>Имя</td><td>Капитал</td><td>Нефть</td><td>Железо</td><td>Легитимность</td><td>ВПК</td></tr>";
while ($row=mysqli_fetch_array($users)){
	echo "<tr> <td>{$row[1]}</td><td>{$row[2]}</td><td>{$row[3]}</td><td>{$row[4]}</td><td>{$row[5]}</td><td>{$row[6]}</td>";
}
echo "</body>
</html>";
?>