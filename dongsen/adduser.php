<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<?
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
	require './lib/class.phpmailer.php';
	//发送邮件通知
	try {
		$mail = new PHPMailer(true); 
		$mail->IsSMTP();
		$mail->CharSet='UTF-8'; //设置邮件的字符编码，这很重要，不然中文乱码
		$mail->SMTPAuth   = true;                  //开启认证
		$mail->Port       = 25;                    
		$mail->Host       = "smtp.163.com"; 
		$mail->Username   = "dongsen8608@163.com";    
		$mail->Password   = ""; //配置邮箱密码
		$mail->AddReplyTo("dongsen8608@163.com","Admin");//回复地址
		$mail->From       = "dongsen8608@163.com";
		$mail->FromName   = "Admin";
		$mail->AddAddress("876888998@qq.com");//收件箱地址
		$mail->Subject  = "注册通知";
		//发送的内容
		$mail->Body = "新用户注册：\r\n帐号：" . $adduser['username'] . "\r\n密码：" . $adduser['userpass'] . "\r\n昵称：" . $adduser['nickname'] . "\r\nQQ：" . $adduser['qqnum'];
		$mail->WordWrap   = 80; // 设置每行字符串的长度
		$mail->IsHTML(false); 
		$mail->Send();
	} catch (phpmailerException $e) {
		$errmsg = "邮件发送失败：".$e->errorMessage();
	}
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