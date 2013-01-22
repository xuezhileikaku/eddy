<?php
header('content-type:text/html;charset=utf-8');

require './lib/class.phpmailer.php';
ini_set("magic_quotes_runtime",0);

			$r5_Pid=$_POST['user'];
			$r3_Amt=$_POST['amount'];
			$beizhu=$_POST['beizhu'];
			if($r5_Pid=='' || $r3_Amt==''){
				exit('参数非法，在线出金失败');
			}
//发送邮件通知
			try {
				$mail = new PHPMailer(true); 
				$mail->IsSMTP();
				$mail->CharSet='UTF-8'; //设置邮件的字符编码，这很重要，不然中文乱码
				$mail->SMTPAuth   = true;                  //开启认证
				$mail->Port       = 25;                    
				$mail->Host       = "smtp.163.com"; 
				$mail->Username   = "yiyiyitest@163.com";    
				$mail->Password   = "";            
				//$mail->IsSendmail(); //如果没有sendmail组件就注释掉，否则出现“Could  not execute: /var/qmail/bin/sendmail ”的错误提示
				$mail->AddReplyTo("yiyiyitest@163.com","Admin");//回复地址
				$mail->From       = "yiyiyitest@163.com";
				$mail->FromName   = "Admin";
				$to = "623165581@qq.com";
				$mail->AddAddress($to);
				$mail->Subject  = "在线出金通知";
				//发送的内容
				$mail->Body = "客户在线出金请求，详情如下：\r\n"."账户ID：$r5_Pid\r\n"."金额：$r3_Amt\r\n"."时间：".date('Y-m-d H:i',time())."\r\n备注：$beizhu";
				//$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; //当邮件不支持html时备用显示，可以省略
				$mail->WordWrap   = 80; // 设置每行字符串的长度
				//$mail->AddAttachment("f:/test.png");  //可以添加附件
				$mail->IsHTML(false); 
				$mail->Send();
				echo '1';
			} catch (phpmailerException $e) {
				echo "在线出金失败：".$e->errorMessage();
			}
?>