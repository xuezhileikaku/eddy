<meta http-equiv="Content-Type" content="text/html; charset=gbk">
<?php
include 'yeepayCommon.php';	
include 'mt.php';
require './lib/class.phpmailer.php';
ini_set("magic_quotes_runtime",0);

#	只有支付成功时易宝支付才会通知商户.
##支付成功回调有两次，都会通知到在线支付请求参数中的p8_Url上：浏览器重定向;服务器点对点通讯.

#	解析返回参数.
$return = getCallBackValue($r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType,$hmac);

#	判断返回签名是否正确（True/False）
$bRet = CheckHmac($r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType,$hmac);

#	校验码正确.
if($bRet){
	if($r1_Code=="1"){	
		if($r9_BType=="1"){
			echo "交易成功";
			echo  "<br />在线支付页面返回<br />";
            /*
			//与MT4服务器通信
			$encryptionKey = "asfas1";
			$secretHash = "fsdvgfygfsddsag";
			$status = "在线支付成功\r\n";

			$mt4request = new CMT4DataReciver;
			$connResult = $mt4request->OpenConnection(SERVER_ADDRESS, SERVER_PORT);
			////////////////////////////////////////////////////////////////////////////////////////////
			if($connResult == -1){
				echo '与MT4服务器通信失败1。','<br />';
			}else{
				//获取交易账户入金前余额
				//$r3_Amt=round($r3_Amt/7,2);
				$params['login'] = $r5_Pid;
				$params['value'] = round($r3_Amt/7,2); // above zero for deposits, below zero for withdraws
				$params['comment'] = "get account balance from yeepay";
				$answerData = $mt4request->MakeRequest("getbalance", $params);
				if($answerData == 'Fail!'){
						echo '获取交易账户入金前余额失败。','<br />';
				}else{
						$data = explode('&',$answerData);
						$data = explode('=',end($data));
						echo '用户'.$r5_Pid.'入金前账户余额为：$'.end($data).'<br />';
				}
			}
			$mt4request->CloseConnection();
			
			////////////////////////////////////////////////////////////////////////////////////////////
			$connResult = $mt4request->OpenConnection(SERVER_ADDRESS, SERVER_PORT);
			if($connResult==-1){
				echo '与MT4服务器通信失败，本次入金未更新至账户，请联系管理员手动处理。','<br />';
				$status .= "与MT4服务器通信失败，本次入金未更新至账户，请手动处理。\r\n";
			}else{	
				//更新账户余额
				$params['comment'] = "change account balance from yeepay";
				$answerData = $mt4request->MakeRequest("changebalance", $params);
				if($answerData == 'Fail!'){
					echo '更新交易账户余额失败，请联系管理员手动处理。','<br />';
					$status .= "更新交易账户余额失败，本次入金未更新至账户，请手动处理。\r\n";
				}else{
					echo '更新交易账户余额成功。本次入金：$'.$r3_Amt.'<br />';
					$status .= "更新交易账户余额成功。\r\n";
					$mt4request->CloseConnection();
					
					////////////////////////////////////////////////////////////////////////////////////////////
					$connResult = $mt4request->OpenConnection(SERVER_ADDRESS, SERVER_PORT);
					if($connResult == -1){
						echo '与MT4服务器通信失败，获取交易账户入金后余额失败。','<br />';
					}else{
						$params['comment'] = "get account balance from yeepay";
						$answerData = $mt4request->MakeRequest("getbalance", $params);
						if($answerData == 'Fail!'){
							echo '获取交易账户入金后余额失败。','<br />';
						}else{
							$data = explode('&',$answerData);
							$data = explode('=',end($data));
							echo '用户'.$r5_Pid.'当前账户余额为：$'.end($data).'<br />';
						}
					}
					$mt4request->CloseConnection();
				}
			}

			//发送邮件通知
			try {
				$mail = new PHPMailer(true); 
				$mail->IsSMTP();
				$mail->CharSet='GBK'; //设置邮件的字符编码，这很重要，不然中文乱码
				$mail->SMTPAuth   = true;                  //开启认证
				$mail->Port       = 25;                    
				$mail->Host       = "smtp.163.com"; 
				$mail->Username   = "yiyiyitest@163.com";    
				$mail->Password   = "";            
				//$mail->IsSendmail(); //如果没有sendmail组件就注释掉，否则出现“Could  not execute: /var/qmail/bin/sendmail ”的错误提示
				$mail->AddReplyTo("yiyiyitest@163.com","Admin");//回复地址
				$mail->From       = "yiyiyitest@163.com";
				$mail->FromName   = "Admin";
				$to = "eddy@rrgod.com";
				$mail->AddAddress($to);
				$mail->Subject  = "在线入金通知";
				//发送的内容
				$mail->Body = "客户在线入金成功，详情如下：\r\n"."账户ID：$r5_Pid\r\n"."金额(￥)：$r3_Amt\r\n"."订单号：$r2_TrxId\r\n"."时间：".date('Y-m-d H:i',time())."\r\n与MT4服务器交互状态：\r\n".$status;
				//$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; //当邮件不支持html时备用显示，可以省略
				$mail->WordWrap   = 80; // 设置每行字符串的长度
				//$mail->AddAttachment("f:/test.png");  //可以添加附件
				$mail->IsHTML(false); 
				$mail->Send();
				echo '在线入金通知邮件已发送至管理员邮箱。';
			} catch (phpmailerException $e) {
				echo "在线入金通知邮件发送失败：".$e->errorMessage();
			}*/

		}elseif($r9_BType=="2"){
			#如果需要应答机制则必须回写流,以success开头,大小写不敏感.
			echo "success";
            //检查订单是否已处理，防止重复入金
            $conn = mysql_connect('127.0.0.1','root','yiyiyi');
            mysql_select_db('yeepay');
            mysql_query('set names gbk');
            $sql = "select * from jyzbpme where orderNum = '" . trim($r2_TrxId) . "'";
            $result = mysql_query($sql);
            $row = mysql_fetch_array($result);
            if($row){
                //订单已处理，退出
                mysql_close($conn);
                exit();
            }
            
            //与MT4服务器通信
			$encryptionKey = "asfas1";
			$secretHash = "fsdvgfygfsddsag";
			$status = "";

			$mt4request = new CMT4DataReciver;
			//$connResult = $mt4request->OpenConnection(SERVER_ADDRESS, SERVER_PORT);
			////////////////////////////////////////////////////////////////////////////////////////////
			/*
            if($connResult == -1){
				//echo '与MT4服务器通信失败1。','<br />';
			}else{
				//获取交易账户入金前余额
				//$r3_Amt=round($r3_Amt/7,2);
				$params['login'] = $r5_Pid;
				$params['value'] = round($r3_Amt/7,2); // above zero for deposits, below zero for withdraws
				$params['comment'] = "get account balance from yeepay";
				$answerData = $mt4request->MakeRequest("getbalance", $params);
				if($answerData == 'Fail!'){
						echo '获取交易账户入金前余额失败。','<br />';
				}else{
						$data = explode('&',$answerData);
						$data = explode('=',end($data));
						echo '用户'.$r5_Pid.'入金前账户余额为：$'.end($data).'<br />';
				}
			}
			$mt4request->CloseConnection();*/
			
			////////////////////////////////////////////////////////////////////////////////////////////
			$connResult = $mt4request->OpenConnection(SERVER_ADDRESS, SERVER_PORT);
			if($connResult==-1){
				//echo '与MT4服务器通信失败，本次入金未更新至账户，请联系管理员手动处理。','<br />';
				$status .= "与MT4服务器通信失败，本次入金未更新至账户，请手动处理。\r\n";
			}else{	
				//更新账户余额
				$params['comment'] = "change account balance from yeepay";
				$answerData = $mt4request->MakeRequest("changebalance", $params);
				if($answerData == 'Fail!'){
					//echo '更新交易账户余额失败，请联系管理员手动处理。','<br />';
					$status .= "更新交易账户余额失败，本次入金未更新至账户，请手动处理。\r\n";
				}else{
					//echo '更新交易账户余额成功。本次入金：$'.$r3_Amt.'<br />';
					$status .= "更新交易账户余额成功。\r\n";
					$mt4request->CloseConnection();
					//处理订单，写入数据库
                    $conn = mysql_connect('127.0.0.1','root','yiyiyi');
                    mysql_select_db('yeepay');
                    mysql_query('set names gbk');
                    $sql = "insert into jyzbpme values (0 , '" . trim($r2_TrxId) . "')";
                    $r = mysql_query($sql);
                    if(!$r){
                        $status .="订单信息写入数据库失败，可能会出现重复入金，请检查";
                    }
                    mysql_close($conn);
					////////////////////////////////////////////////////////////////////////////////////////////
					/*
                    $connResult = $mt4request->OpenConnection(SERVER_ADDRESS, SERVER_PORT);
					if($connResult == -1){
						echo '与MT4服务器通信失败，获取交易账户入金后余额失败。','<br />';
					}else{
						$params['comment'] = "get account balance from yeepay";
						$answerData = $mt4request->MakeRequest("getbalance", $params);
						if($answerData == 'Fail!'){
							echo '获取交易账户入金后余额失败。','<br />';
						}else{
							$data = explode('&',$answerData);
							$data = explode('=',end($data));
							echo '用户'.$r5_Pid.'当前账户余额为：$'.end($data).'<br />';
						}
					}
					$mt4request->CloseConnection();*/
				}
			}

			//发送邮件通知
			try {
				$mail = new PHPMailer(true); 
				$mail->IsSMTP();
				$mail->CharSet='GBK'; //设置邮件的字符编码，这很重要，不然中文乱码
				$mail->SMTPAuth   = true;                  //开启认证
				$mail->Port       = 25;                    
				$mail->Host       = "smtp.163.com"; 
				$mail->Username   = "yiyiyitest@163.com";    
				$mail->Password   = "";            
				//$mail->IsSendmail(); //如果没有sendmail组件就注释掉，否则出现“Could  not execute: /var/qmail/bin/sendmail ”的错误提示
				$mail->AddReplyTo("yiyiyitest@163.com","Admin");//回复地址
				$mail->From       = "yiyiyitest@163.com";
				$mail->FromName   = "Admin";
				$to = "eddy@rrgod.com";
				$mail->AddAddress($to);
				$mail->Subject  = "在线入金通知";
				//发送的内容
				$mail->Body = "客户在线入金成功，详情如下：\r\n"."账户ID：$r5_Pid\r\n"."金额(￥)：$r3_Amt\r\n"."订单号：$r2_TrxId\r\n"."时间：".date('Y-m-d H:i',time())."\r\n与MT4服务器交互状态：\r\n".$status;
				//$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; //当邮件不支持html时备用显示，可以省略
				$mail->WordWrap   = 80; // 设置每行字符串的长度
				//$mail->AddAttachment("f:/test.png");  //可以添加附件
				$mail->IsHTML(false); 
				$mail->Send();
				echo '在线入金通知邮件已发送至管理员邮箱。';
			} catch (phpmailerException $e) {
				echo "在线入金通知邮件发送失败：".$e->errorMessage();
			}
		}
	}
	
}else{
	echo "交易信息被篡改";
}
   
?>
<html>
<head>
<title>Return from YeePay Page</title>
</head>
<body>
</body>
</html>