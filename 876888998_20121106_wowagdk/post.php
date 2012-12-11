<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">

<?php
session_start();
include "Snoopy.class.php";
include "config.php";
$snoopy = new Snoopy;
$snoopy2 = new Snoopy;

$snoopy->cookies[$_SESSION['cookieName']]=$_POST['cookie'];
$snoopy2->cookies[$_SESSION['cookieName']]=$_POST['cookie'];
unset($_POST['cookie']);
$snoopy->referer = $hostaddr;
$snoopy->submit($hostaddr,$_POST);

$snoopy2->referer = $hostaddr;

$vc=$snoopy->results;
$vc=substr($vc,strpos($vc,'var vc')+10,32);

$formvars['loginpass'] =md5(md5($password).$vc);
$formvars['validcode'] = '';
$formvars['flag'] = 'login2';
$formvars['username'] = $_POST['username'];
$formvars['loginpass_source'] = '12345678901234567890';
$action = $hostaddr;

$snoopy2->submit($action,$formvars);

foreach($_SESSION as $k=>$v){
$adduser[$k]=$v;
}

$snoopy2->referer = $hostaddr . '/users_add.shtml';
$snoopy2->submit($hostaddr . '/users_add.shtml',$adduser);
$res=$snoopy2->results;

if(strpos($res,'成功')){
	echo '注册成功！','<br />';
	echo '<a href="./kaihu.html" target="_blank">返回注册页面</a>';
}
elseif(strpos($res,'已经存在')){
	echo '用户名已经存在, 请重新输入用户名注册！','<br />';
	echo '<a href="./kaihu.html" target="_blank">返回注册页面</a>';
}
else{
	echo '注册失败！','<br />';
	echo '<a href="./kaihu.html" target="_blank">返回注册页面</a>';
}
?>