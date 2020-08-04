<?php
	session_start();
	header('Content-Type: text/html; charset=utf-8');

	$host = "localhost";
	$user = "root";
	$password = "";
	$database = "coldb";

	$connection = mysqli_connect($host, $user, $password, $database);
	mysqli_set_charset($connection, "utf8"); 

	
?>