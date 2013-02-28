<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<?php
session_start();
include "Snoopy.class.php";
include "config.php";

$snoopy = new Snoopy;
$snoopy->cookies[$_SESSION['cookieName']]=$_POST['cookie'];
//设置vertify cookie
$snoopy->cookies[$_SESSION['vertifyName']]=$_SESSION['vertifyValue'];

foreach($_SESSION as $k=>$v){
	$adduser[$k]=$v;
}
$adduser['validcode_source']=$_POST['validcode_source'];
$adduser['validcode']=$_POST['validcode'];

//添加用户
$snoopy->referer = $hostaddr . '/users_add.shtml';
$snoopy->submit($hostaddr . '/users_add.shtml',$adduser);

$res=$snoopy->results;
unset($_SESSION);
if(strpos($res,'成功')){
	echo '注册成功！','<br />';
	echo '<a href="./index.html" target="_blank">返回注册页面</a>';
}
elseif(strpos($res,'已经存在')){
	echo '用户名已经存在, 请重新输入用户名注册！','<br />';
	echo '<a href="./index.html" target="_blank">返回注册页面</a>';
}
else{
	echo '注册失败！','<br />';
	echo '<a href="./index.html" target="_blank">返回注册页面</a>';
}
?>