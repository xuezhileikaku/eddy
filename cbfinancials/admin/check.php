<?php
session_start();
if(!isset($_SESSION ['pw'])){
	header('location:index.php');
	exit;
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
	<link rel="stylesheet" type="text/css" href="./bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="./bootstrap/js/bootstrap.min.js">
	<script type="text/javascript" src="./jquery-1.8.3.js"></script>
	<title>MT4 Member Management System</title>
</head>
<body>
	<div class="container">
		<h1>报表</h1>
		<a href="depositionlist.php" class="btn btn-large btn-block">在线入金记录</a>
		<a href="withdrawlist.php" class="btn btn-large btn-block">在线出金记录</a>
		<a href="transferlist.php" class="btn btn-large btn-block">在线内部转账记录</a>
	</div>
</body>
</html>