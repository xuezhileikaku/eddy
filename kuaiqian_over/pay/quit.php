<?php

session_start();
error_reporting(0);
if (!isset($_SESSION['username']) || !isset($_SESSION['password'])) {
    header('location:../index.php?error=非法访问');
    exit;
}

session_destroy();
header('location:../index.php');
?>