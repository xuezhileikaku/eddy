<?

$snoopy2 = unserialize($_SESSION['adduser_snoopy']);
unset($_SESSION['adduser_snoopy']);

foreach($_SESSION as $k=>$v){
$adduser[$k]=$v;
}

$adduser['validcode_source']=$_POST['validcode_source'];
print_r($adduser);exit;
//添加用户
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