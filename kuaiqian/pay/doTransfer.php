<?php
session_start();
error_reporting(0);
if(!isset($_SESSION['username']) || !isset($_SESSION['password'])){
	header('location:../index.php?error=非法访问');
	exit;
}

$fromU = isset($_POST['from_username']) ? $_POST['from_username'] : '';
$fromP = $_SESSION['password'];
$toU = isset($_POST['to_username']) ? $_POST['to_username'] : '';
$toP = isset($_POST['to_password']) ? $_POST['to_password'] : '';
$value = isset($_POST['amount']) ? $_POST['amount'] : 0;
file_put_contents('./conf/curserv.txt',$_POST['server']);
include 'mt.php';
$status = '';
//账户有效性验证
$mt4request = new CMT4DataReciver;
	$connResult = $mt4request->OpenConnection(SERVER_ADDRESS, SERVER_PORT);
		if($connResult==-1){
			header('location:transfer.php?error=与MT4服务器通信失败[账户验证]');
			exit;
		}else{	
			//登陆验证
			$params['login'] = $toU;
			$params['password'] = $toP;
			$params['value'] = 0;
			$params['comment'] = "vertify account $username";
			$answerData = $mt4request->MakeRequest("changebalance", $params);
			//var_dump($answerData);
			if(mb_substr($answerData,0,4,'GBK') == '密码错误'){
				header('location:transfer.php?error=密码错误');
				exit;
			}else if(mb_substr($answerData,0,4,'GBK') == '查询用户'){
				header('location:transfer.php?error=转入帐号不存在，请检查');
				exit;
			}else if($answerData == 'Fail!'){
				header('location:transfer.php?error=其他错误');
				exit;
			}else if(mb_substr($answerData,0,3,'GBK') == '不支持'){
				header("location:transfer.php?error=$answerData");
				exit;
			}else{
				/*
				$firs = end(explode('&',$answerData));
				$balance = number_format(end(explode('=',$firs)),2);
				$_SESSION['password']=$password;
				$_SESSION['username']=$username;
				$_SESSION['balance']=$balance;*/
				$status .= '转入账户'.$toU."有效性验证成功<br />";
			}
			$mt4request->CloseConnection();
			$mt4request = null;
		}

//////////////////////////////////////////////////
$mt4request = new CMT4DataReciver;
$connResult = $mt4request->OpenConnection(SERVER_ADDRESS, SERVER_PORT);
	if($connResult==-1){
		header('location:transfer.php?error=与MT4服务器通信失败[出金]');
		exit;
	}else{	
		//出金
		$params['login'] = $fromU;
		$params['password'] = $fromP;
		$params['value'] = -$value;
		$params['comment'] = "transfer to $toU";
		$answerData = $mt4request->MakeRequest("changebalance", $params);
		//var_dump($answerData);
		if(mb_substr($answerData,0,4,'GBK') == '密码错误'){
			header('location:transfer.php?error=密码错误');
			exit;
		}else if(mb_substr($answerData,0,4,'GBK') == '查询用户'){
			header('location:transfer.php?error=转入帐号不存在，请检查');
			exit;
		}else if($answerData == 'Fail!'){
			header('location:transfer.php?error=其他错误');
			exit;
		}else if(mb_substr($answerData,0,3,'GBK') == '不支持'){
			header("location:transfer.php?error=$answerData");
			exit;
		}else{
			$firs = end(explode('&',$answerData));
			$balance = round(end(explode('=',$firs)),2);
			//$_SESSION['password']=$password;
			//$_SESSION['username']=$username;
			$_SESSION['balance']=$balance;
			$mt4request->CloseConnection();
			$status .= '转出账户'.$fromU.'出金$'.$value."成功<br />";
			//入金
			$connResult = $mt4request->OpenConnection(SERVER_ADDRESS, SERVER_PORT);
	if($connResult==-1){
		header('location:transfer.php?error=与MT4服务器通信失败[入金]');
		exit;
	}else{	
		//登陆验证
		$params['login'] = $toU;
		$params['password'] = $toP;
		$params['value'] = $value;
		$params['comment'] = "transfer from $fromU ";
		$answerData = $mt4request->MakeRequest("changebalance", $params);
		//var_dump($answerData);
		if(mb_substr($answerData,0,4,'GBK') == '密码错误'){
			header('location:transfer.php?error=密码错误');
			exit;
		}else if(mb_substr($answerData,0,4,'GBK') == '查询用户'){
			header('location:transfer.php?error=转入帐号不存在，请检查');
			exit;
		}else if($answerData == 'Fail!'){
			header('location:transfer.php?error=其他错误');
			exit;
		}else if(mb_substr($answerData,0,3,'GBK') == '不支持'){
			header("location:transfer.php?error=$answerData");
			exit;
		}else{
			//$firs = end(explode('&',$answerData));
			//$balance = number_format(end(explode('=',$firs)),2);
			$status .= '转入账户'.$toU.'入金$'.$value."成功<br />";
		}
		$mt4request->CloseConnection();
	}
		}
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=gbk">
    <title>转账结果</title>
    <link href="../css/main.css" rel="stylesheet" type="text/css">  
	<style>
	.attention{
color:red;
font-size:13px;
margin:20px auto;
}
</style>
</head>
<body>
<div>
<?php
echo $status,'<a class="link" href="rujin.php">返回首页</a>';
?>
<p class="attention">
备注：<br />
1、出金与入金都显示成功说明本笔转账交易成功，否则失败。<br />
2、如有任何疑问，请联系官方客服QQ：873901871、2695500379。
</p>
</div>
</body>