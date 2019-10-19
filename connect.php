<?php
require_once "ip.php";
$link = mysqli_connect('localhost', 'root', "", 'game');
if (!$link){
	die("Что-то пошло не так! Обратись к Админу.");
mysql_query($link, "SET NAMES utf8");

}