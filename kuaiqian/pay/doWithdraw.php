<?php
header('content-type:text/html;charset=utf-8');
session_start();
error_reporting(0);
if(!isset($_SESSION['username']) || !isset($_SESSION['password'])){
	header('location:../index.php?error=非法访问');
	exit;
}

require './lib/class.phpmailer.php';
ini_set("magic_quotes_runtime",0);

$r5_Pid=$_POST['user'];
$r3_Amt=$_POST['amount'];
$username=$_POST['to_username'];
$bankcode=$_POST['bankcode'];
$bankname=$_POST['bankname'];
$status =false;
if($r5_Pid=='' || $r3_Amt==''){
	header('location:../index.php?error=非法访问');
	exit;
}

if($r3Amt<0){
	header('location:../index.php?error=非法访问');
	exit;
}

include 'mt.php';
$mt4request = new CMT4DataReciver;
$connResult = $mt4request->OpenConnection(SERVER_ADDRESS, SERVER_PORT);
if($connResult==-1){
	//header('location:../index.php?error=与MT4服务器通信失败');
	exit('与MT4服务器通信失败');
}else{	
	//登陆验证
	$params['login'] = $r5_Pid;
	$params['password'] = $_SESSION['password'];
	$params['value'] = -$r3_Amt;
	$params['comment'] = "widthdraw online";
	$answerData = $mt4request->MakeRequest("changebalance", $params);
	//var_dump($answerData);
	if(mb_substr($answerData,0,4,'GBK') == '密码错误'){
		exit( '密码错误');
	}else if(mb_substr($answerData,0,4,'GBK') == '查询用户'){
		exit('交易帐号不存在，请检查');
	}else if($answerData == 'Fail!'){
		exit('其他错误');
	}else if(mb_substr($answerData,0,3,'GBK') == '不支持'){
		exit($answerData);
	}else{
		$firs = end(explode('&',$answerData));
		$balance = round(end(explode('=',$firs)),2);
		$status = true;
		$_SESSION['balance']=$balance;
	}
	$mt4request->CloseConnection();
}

//写入记录至数据库
$db = mysqli_connect('localhost','root','yiyiyi');
if (!mysqli_connect_errno()) {
	mysqli_select_db($db,'yeepay');
	mysqli_query($db,'set names utf8');
	$d = date('Y-m-d H:i:s');
	$s = $status ? 1 : 0;
	$sql = "insert into mt4_withdraw values (null,'{$r5_Pid}','{$r3_Amt}','{$username}','{$bankcode}','{$bankname}','{$d}','{$s}','')";
	$rs = mysqli_query($db,$sql);
	if (!$rs) {
		file_put_contents('./log.txt', '在线出金记录写入数据库失败[write failed] - ' . mysqli_error($db) . $d . "\r\n",FILE_APPEND);
	}
	mysqli_close($db);
}else{
	file_put_contents('./log.txt', '在线出金记录写入数据库失败[open failed] - ' . mysqli_error($db) . $d . "\r\n",FILE_APPEND);
}

try {
	$mail = new PHPMailer(true); 
	$mail->IsSMTP();
	$mail->CharSet='utf-8'; //设置邮件的字符编码，这很重要，不然中文乱码
	$mail->SMTPAuth   = true;                  //开启认证
	$mail->Port       = 25;                    
	$mail->Host       = "smtp.163.com"; 
	$mail->Username   = "yiyiyitest@163.com";    
	$mail->Password   = "yiyiyitest1314";            
	//$mail->IsSendmail(); //如果没有sendmail组件就注释掉，否则出现“Could  not execute: /var/qmail/bin/sendmail ”的错误提示
	$mail->AddReplyTo("yiyiyitest@163.com","Admin");//回复地址
	$mail->From       = "yiyiyitest@163.com";
	$mail->FromName   = "Admin";
	
	$to = "1021992745@qq.com";
	$mail->AddAddress($to);
	$mail->AddAddress('574814416@qq.com');
	$mail->AddAddress('eddy@rrgod.com');
	if($status){
		$mail->Subject  = "在线出金通知[成功]";
	}else{
		$mail->Subject  = "在线出金通知[失败]";
	}
	//发送的内容
	$mail->Body = "客户在线出金请求，详情如下：\r\n".
		"取款交易帐号：$r5_Pid\r\n".
		"提款金额（USD 美元）：$r3_Amt\r\n".
		"收款人姓名：$username\r\n".
		"收款人银行帐号：$bankcode\r\n".
		"收款账户开户行名称：$bankname\r\n".
		"时间：".date('Y-m-d H:i',time()).
		"\r\n与MT4服务器交互状态：".
		($status ? '成功' : '失败');
	//$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; //当邮件不支持html时备用显示，可以省略
	$mail->WordWrap   = 80; // 设置每行字符串的长度
	//$mail->AddAttachment("f:/test.png");  //可以添加附件
	$mail->IsHTML(false); 
	$mail->Send();
	if($status){
		echo '1';
	}else{
		echo '账户余额更新失败，请联系客服手动下账处理';
	}
} catch (phpmailerException $e) {
	echo $e->errorMessage();
}
?>