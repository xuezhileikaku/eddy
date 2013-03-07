<?php
session_start();
header('content-type:text/html;charset=utf-8');
error_reporting(0);

require './lib/class.phpmailer.php';
ini_set("magic_quotes_runtime",0);

			$r5_Pid=$_POST['user'];
			$r3_Amt=$_POST['amount'];
			$beizhu=$_POST['beizhu'];
			$status =false;
			if($r5_Pid=='' || $r3_Amt==''){
				exit('参数非法，在线出金失败');
			}

include 'mt.php';
	$mt4request = new CMT4DataReciver;
	$connResult = $mt4request->OpenConnection(SERVER_ADDRESS, SERVER_PORT);
		if($connResult==-1){
			header('location:../index.php?error=与MT4服务器通信失败');
		}else{	
			//登陆验证
			$params['login'] = $r5_Pid;
			$params['password'] = $_SESSION['password'];
			$params['value'] = -$r3_Amt;
			$params['comment'] = "account $username widthdraw $r3_Amt";
			$answerData = $mt4request->MakeRequest("changebalance", $params);
			//var_dump($answerData);
			if(mb_substr($answerData,0,4,'GBK') == '密码错误'){
				echo '密码错误';
			}else if(mb_substr($answerData,0,4,'GBK') == '查询用户'){
				echo '交易帐号不存在，请检查';
			}else if($answerData == 'Fail!'){
				echo '其他错误';
			}else if(mb_substr($answerData,0,3,'GBK') == '不支持'){
				echo $answerData;
			}else{
				$firs = end(explode('&',$answerData));
				$balance = round(end(explode('=',$firs)),2);
				$status = true;
				$_SESSION['balance']=$balance;
			}
			$mt4request->CloseConnection();
		}

//发送邮件通知
//echo '2';exit;
			try {
				$mail = new PHPMailer(true); 
				$mail->IsSMTP();
				$mail->CharSet='UTF-8'; //设置邮件的字符编码，这很重要，不然中文乱码
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
				$mail->AddAddress('1690974371@qq.com');
				//$mail->AddAddress('eddy@rrgod.com');
				if($status){
					$mail->Subject  = "在线出金通知[成功]";
				}else{
					$mail->Subject  = "在线出金通知[失败]";
				}
				//发送的内容
				$mail->Body = "客户在线出金请求，详情如下：\r\n"."账户ID：$r5_Pid\r\n"."金额：$r3_Amt\r\n"."时间：".date('Y-m-d H:i',time())."\r\n备注：$beizhu"."\r\n与MT4服务器交互状态：".($status ? '成功' : '失败');
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