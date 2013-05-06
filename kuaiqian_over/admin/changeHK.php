<?php
session_start();
if (!isset($_SESSION ['pw'])) {
	header('location:index.php');
	exit();
}
require '../init.php';
require ROOT . '/common/mysqldb.class.php';
if (!isset($_POST['id'])) {
    exit('error');
}

$id = $_POST['id'];
$mysql = mysqldb::getIns();
$mysql->setCharset('utf8');
$sql = "update mt4_withdraw set params = case params when '0' then '1' when '1' then '0' end where id = $id";
if (!$mysql->query($sql)) {
    exit('Update error');
}
$row = $mysql->getOne("select params from mt4_withdraw where id = $id");
if (!$row) {
    exit('Get data error');
}
if ($row['params'] == '1') {
    echo '已汇款';
} else {
    echo '未汇款';
}
?>
