<?php
	/**
	* OZ\User logout demo
	*/
	session_start();
	require_once('./../user.class.php');

	/* make it short */
	use OZ\User as User;
	
	/* Mysql access */
$sql_driver = 'mysql';
	$sql_host = 'localhost';
	$sql_name = 'javimart_m6';
	$sql_user = 'javimart_json';
	$sql_pass = 'nombreapellido.';
	User::init($sql_driver, $sql_host, $sql_name, $sql_user, $sql_pass);
	
	User::logout();
	
	header('Location: login.php');
	exit();
