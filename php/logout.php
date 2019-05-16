<?php 
	session_start();
	$_SESSION['admin'] = 0;
	session_unset();
	header('Location: navigation.php');
 ?>