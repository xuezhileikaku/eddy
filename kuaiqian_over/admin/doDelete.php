<?php 
session_start();
if (!isset($_SESSION ['pw'])) {
    header('location:index.php');
    exit();
}
if (!isset($_POST['id']) || empty($_POST['id'])) {
	exit('非法操作！');
}
require '../init.php';
require ROOT . '/common/mysqldb.class.php';
$con = explode('#', $_POST['id']);
$table = trim($_POST['tb']);
$condition = ' (';
foreach ($con as $v){
	if($v !== ''){
		$condition .= $v . ',';
	}
}
$condition = rtrim($condition,',');
$condition .= ') ';
$mysql = mysqldb::getIns();
$mysql->setCharset('utf8');
$sql = "delete from $table where id in $condition";
if($mysql->query($sql) && $mysql->getAffectedRows()){
	echo '1';
}else{
	echo '0';
}
?>