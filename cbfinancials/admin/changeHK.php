<?php
if (!isset($_POST['id']) or !is_int((int)$_POST['id'])){
	exit('error');
}
$id = addslashes($_POST['id']);

$db = mysqli_connect ( 'localhost', 'root', 'yiyiyi', 'yeepay' );
if (mysqli_connect_errno () !== 0) {
	exit ( 'DB error' );
}
mysqli_query ( $db, 'set names utf8' );
$sql = "update mt4_withdraw set params = case params when '0' then '1' when '1' then '0' end where id = $id";
$rs = mysqli_query($db,$sql);
if(!$rs){
	exit('Update error');
}
$rs = mysqli_query($db,"select params from mt4_withdraw where id = $id");
$row = mysqli_fetch_assoc($rs);
if ($row['params']=='1'){
	echo '已汇款';
}else{
	echo '未汇款';
}
?>
