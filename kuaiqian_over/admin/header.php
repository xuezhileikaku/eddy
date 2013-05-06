<?php
session_start();
if (!isset($_SESSION ['pw'])) {
    header('location:index.php');
    exit();
}
require '../init.php';
require ROOT . '/common/mysqldb.class.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
        <title>MT4 Member Management System</title>
        
        <link rel="stylesheet" type="text/css"
              href="../public/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="../public/css/admin.css">
        <link rel="stylesheet" type="text/css"
              href="../public/css/bootstrap-datetimepicker.min.css">
        
        <script type="text/javascript" src="../public/js/jquery.min.js"></script>
        <script type="text/javascript" src="../public/js/bootstrap.min.js"></script>
        <script type="text/javascript"
        src="../public/js/bootstrap-datetimepicker.min.js"></script>
        <script type="text/javascript"
        src="../public/js/bootstrap-datetimepicker.zh-CN.js"></script>
</head>
<body>
    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container-fluid">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="brand" href="check.php">后台管理系统</a>
          <div class="nav-collapse collapse">
            <p class="navbar-text pull-right">
              当前登录用户： <a href="#" class="navbar-link">Admin</a><span style="margin: 0 8px;vertical-align: 1px">|</span><a href="quit.php" class="navbar-link">退出登录</a>
            </p>
            <ul class="nav">
              <li class=<?php echo stristr($_SERVER['SCRIPT_NAME'],"check.php") !==false ? "active" : ""; ?>><a href="check.php">首页</a></li>
              <li class=<?php echo stristr($_SERVER['SCRIPT_NAME'],"depositionlist.php") !==false ? "active" : ""; ?>><a href="depositionlist.php">在线入金</a></li>
              <li class=<?php echo stristr($_SERVER['SCRIPT_NAME'],"withdrawlist.php") !==false ? "active" : ""; ?>><a href="withdrawlist.php">在线出金</a></li>
              <li class=<?php echo stristr($_SERVER['SCRIPT_NAME'],"transferlist.php") !==false ? "active" : ""; ?>><a href="transferlist.php">内部转账</a></li>
              <li class=<?php echo stristr($_SERVER['SCRIPT_NAME'],"loglist.php") !==false ? "active" : ""; ?>><a href="loglist.php">系统日志</a></li>
              <li class=<?php echo stristr($_SERVER['SCRIPT_NAME'],"about.php") !==false ? "active" : ""; ?>><a href="about.php">关于</a></li>
              <li class=<?php echo stristr($_SERVER['SCRIPT_NAME'],"contact.php") !==false ? "active" : ""; ?>><a href="contact.php">联系</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>