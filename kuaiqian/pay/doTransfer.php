<?php
session_start();
error_reporting(0);
if(!isset($_SESSION['username']) || !isset($_SESSION['password'])){
	header('location:../index.php?error=�Ƿ�����');
	exit;
}

$fromU = isset($_POST['from_username']) ? $_POST['from_username'] : '';
$fromP = $_SESSION['password'];
$toU = isset($_POST['to_username']) ? $_POST['to_username'] : '';
$toP = isset($_POST['to_password']) ? $_POST['to_password'] : '';
$flag = false;
$value = isset($_POST['amount']) ? $_POST['amount'] : -1;
if($value<0){
	header('location:transfer.php?error=ת�ʽ����С��0');
	exit;
}
file_put_contents('./conf/curserv.txt',$_POST['server']);
include 'mt.php';
$status = '';
//�˻���Ч����֤
$mt4request = new CMT4DataReciver;
	$connResult = $mt4request->OpenConnection(SERVER_ADDRESS, SERVER_PORT);
		if($connResult==-1){
			header('location:transfer.php?error=��MT4������ͨ��ʧ��[�˻���֤]');
			exit;
		}else{	
			//��½��֤
			$params['login'] = $toU;
			$params['password'] = $toP;
			$params['value'] = 0;
			$params['comment'] = "vertify account $username";
			$answerData = $mt4request->MakeRequest("changebalance", $params);
			//var_dump($answerData);
			if(mb_substr($answerData,0,4,'GBK') == '��������'){
				header('location:transfer.php?error=��������');
				exit;
			}else if(mb_substr($answerData,0,4,'GBK') == '��ѯ�û�'){
				header('location:transfer.php?error=ת���ʺŲ����ڣ�������');
				exit;
			}else if($answerData == 'Fail!'){
				header('location:transfer.php?error=��������');
				exit;
			}else if(mb_substr($answerData,0,3,'GBK') == '��֧��'){
				header("location:transfer.php?error=$answerData");
				exit;
			}else{
				/*
				$firs = end(explode('&',$answerData));
				$balance = number_format(end(explode('=',$firs)),2);
				$_SESSION['password']=$password;
				$_SESSION['username']=$username;
				$_SESSION['balance']=$balance;*/
				$status .= 'ת���˻�'.$toU."��Ч����֤�ɹ�<br />";
			}
			$mt4request->CloseConnection();
			$mt4request = null;
		}

//////////////////////////////////////////////////
$mt4request = new CMT4DataReciver;
$connResult = $mt4request->OpenConnection(SERVER_ADDRESS, SERVER_PORT);
	if($connResult==-1){
		header('location:transfer.php?error=��MT4������ͨ��ʧ��[����]');
		exit;
	}else{	
		//����
		$params['login'] = $fromU;
		$params['password'] = $fromP;
		$params['value'] = -$value;
		$params['comment'] = "transfer to $toU";
		$answerData = $mt4request->MakeRequest("changebalance", $params);
		//var_dump($answerData);
		if(mb_substr($answerData,0,4,'GBK') == '��������'){
			header('location:transfer.php?error=��������');
			exit;
		}else if(mb_substr($answerData,0,4,'GBK') == '��ѯ�û�'){
			header('location:transfer.php?error=ת���ʺŲ����ڣ�������');
			exit;
		}else if($answerData == 'Fail!'){
			header('location:transfer.php?error=��������');
			exit;
		}else if(mb_substr($answerData,0,3,'GBK') == '��֧��'){
			header("location:transfer.php?error=$answerData");
			exit;
		}else{
			$firs = end(explode('&',$answerData));
			$balance = round(end(explode('=',$firs)),2);
			//$_SESSION['password']=$password;
			//$_SESSION['username']=$username;
			$_SESSION['balance']=$balance;
			$mt4request->CloseConnection();
			$status .= 'ת���˻�'.$fromU.'����$'.$value."�ɹ�<br />";
			//����
			$connResult = $mt4request->OpenConnection(SERVER_ADDRESS, SERVER_PORT);
	if($connResult==-1){
		//header('location:transfer.php?error=��MT4������ͨ��ʧ��[����]');
		//exit;
		$status .= '��MT4������ͨ��ʧ��[����]';
	}else{	
		//��½��֤
		$params['login'] = $toU;
		$params['password'] = $toP;
		$params['value'] = $value;
		$params['comment'] = "transfer from $fromU ";
		$answerData = $mt4request->MakeRequest("changebalance", $params);
		//var_dump($answerData);
		if(mb_substr($answerData,0,4,'GBK') == '��������'){
			header('location:transfer.php?error=��������');
			exit;
		}else if(mb_substr($answerData,0,4,'GBK') == '��ѯ�û�'){
			header('location:transfer.php?error=ת���ʺŲ����ڣ�������');
			exit;
		}else if($answerData == 'Fail!'){
			header('location:transfer.php?error=��������');
			exit;
		}else if(mb_substr($answerData,0,3,'GBK') == '��֧��'){
			header("location:transfer.php?error=$answerData");
			exit;
		}else{
			//$firs = end(explode('&',$answerData));
			//$balance = number_format(end(explode('=',$firs)),2);
			$status .= 'ת���˻�'.$toU.'����$'.$value."�ɹ�<br />";
			$flag = true;
		}
		$mt4request->CloseConnection();
	}
		}

		//д����¼�����ݿ�
		$db = mysqli_connect('localhost','root','');
		if (!mysqli_connect_errno()) {
			mysqli_select_db($db,'mt4_member');
			mysqli_query($db,'set names gbk');
			$d = date('Y-m-d H:i:s');
			$s = $flag ? 1 : 0;
			$sql = "insert into mt4_transfer values (null,'{$fromU}','{$toU}','{$value}','{$d}','{$s}','')";
			$rs = mysqli_query($db,$sql);
			if (!$rs) {
				file_put_contents('./log.txt', '�ڲ�ת�˼�¼д�����ݿ�ʧ��[write failed] - ' . mysqli_error($db) . $d . "\r\n",FILE_APPEND);
			}
			mysqli_close($db);
		}else{
			file_put_contents('./log.txt', '�ڲ�ת�˼�¼д�����ݿ�ʧ��[open failed] - ' . mysqli_error($db) . $d . "\r\n",FILE_APPEND);
		}

		//�����ʼ�
		require './lib/class.phpmailer.php';
		try {
				$mail = new PHPMailer(true); 
				$mail->IsSMTP();
				$mail->CharSet='GBK'; //�����ʼ����ַ����룬������Ҫ����Ȼ��������
				$mail->SMTPAuth   = true;                  //������֤
				$mail->Port       = 25;                    
				$mail->Host       = "smtp.163.com"; 
				$mail->Username   = "yiyiyitest@163.com";    
				$mail->Password   = "";            
				//$mail->IsSendmail(); //����û��sendmail������ע�͵����������֡�Could  not execute: /var/qmail/bin/sendmail ���Ĵ�����ʾ
				$mail->AddReplyTo("yiyiyitest@163.com","Admin");//�ظ���ַ
				$mail->From       = "yiyiyitest@163.com";
				$mail->FromName   = "Admin";
				
				$to = "1021992745@qq.com";
				$mail->AddAddress($to);
				$mail->AddAddress('574814416@qq.com');
				$mail->AddAddress('1690974371@qq.com');
				$mail->AddAddress('eddy@rrgod.com');
				if($flag){
					$mail->Subject  = "�����ڲ�ת��֪ͨ[�ɹ�]";
				}else{
					$mail->Subject  = "�����ڲ�ת��֪ͨ[ʧ��]";
				}
				//���͵�����
				$mail->Body = str_replace('<br />',"\r\n",$status);
				//$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; //���ʼ���֧��htmlʱ������ʾ������ʡ��
				$mail->WordWrap   = 80; // ����ÿ���ַ����ĳ���
				//$mail->AddAttachment("f:/test.png");  //�������Ӹ���
				$mail->IsHTML(false); 
				$mail->Send();
				$status .= '�ʼ�֪ͨ���ͳɹ�';
			} catch (phpmailerException $e) {
				$status .= $e->errorMessage();
			}
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=gbk">
    <title>ת�˽���</title>
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
echo $status,'<a class="link" href="rujin.php">������ҳ</a>';
?>
<p class="attention">
��ע��<br />
1����������������ʾ�ɹ�˵������ת�˽��׳ɹ�������ʧ�ܡ�<br />
2�������κ����ʣ�����ϵ�ٷ��ͷ�QQ��873901871��2695500379��
</p>
</div>
</body>
