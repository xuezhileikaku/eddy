<meta http-equiv="Content-Type" content="text/html; charset=gbk">
<?php

include 'mt.php';
require './lib/class.phpmailer.php';

	function kq_ck_null($kq_va,$kq_na){if($kq_va == ""){return $kq_va="";}else{return $kq_va=$kq_na.'='.$kq_va.'&';}}
	//人民币网关账号，该账号为11位人民币网关商户编号+01,该值与提交时相同。
	$kq_check_all_para=kq_ck_null($_REQUEST[merchantAcctId],'merchantAcctId');
	//网关版本，固定值：v2.0,该值与提交时相同。
	$kq_check_all_para.=kq_ck_null($_REQUEST[version],'version');
	//语言种类，1代表中文显示，2代表英文显示。默认为1,该值与提交时相同。
	$kq_check_all_para.=kq_ck_null($_REQUEST[language],'language');
	//签名类型,该值为4，代表PKI加密方式,该值与提交时相同。
	$kq_check_all_para.=kq_ck_null($_REQUEST[signType],'signType');
	//支付方式，一般为00，代表所有的支付方式。如果是银行直连商户，该值为10,该值与提交时相同。
	$kq_check_all_para.=kq_ck_null($_REQUEST[payType],'payType');
	//银行代码，如果payType为00，该值为空；如果payType为10,该值与提交时相同。
	$kq_check_all_para.=kq_ck_null($_REQUEST[bankId],'bankId');
	//商户订单号，,该值与提交时相同。
	$kq_check_all_para.=kq_ck_null($_REQUEST[orderId],'orderId');
	//订单提交时间，格式：yyyyMMddHHmmss，如：20071117020101,该值与提交时相同。
	$kq_check_all_para.=kq_ck_null($_REQUEST[orderTime],'orderTime');
	//订单金额，金额以“分”为单位，商户测试以1分测试即可，切勿以大金额测试,该值与支付时相同。
	$kq_check_all_para.=kq_ck_null($_REQUEST[orderAmount],'orderAmount');
	// 快钱交易号，商户每一笔交易都会在快钱生成一个交易号。
	$kq_check_all_para.=kq_ck_null($_REQUEST[dealId],'dealId');
	//银行交易号 ，快钱交易在银行支付时对应的交易号，如果不是通过银行卡支付，则为空
	$kq_check_all_para.=kq_ck_null($_REQUEST[bankDealId],'bankDealId');
	//快钱交易时间，快钱对交易进行处理的时间,格式：yyyyMMddHHmmss，如：20071117020101
	$kq_check_all_para.=kq_ck_null($_REQUEST[dealTime],'dealTime');
	//商户实际支付金额 以分为单位。比方10元，提交时金额应为1000。该金额代表商户快钱账户最终收到的金额。
	$kq_check_all_para.=kq_ck_null($_REQUEST[payAmount],'payAmount');
	//费用，快钱收取商户的手续费，单位为分。
	$kq_check_all_para.=kq_ck_null($_REQUEST[fee],'fee');
	//扩展字段1，该值与提交时相同
	$kq_check_all_para.=kq_ck_null($_REQUEST[ext1],'ext1');
	//扩展字段2，该值与提交时相同。
	$kq_check_all_para.=kq_ck_null($_REQUEST[ext2],'ext2');
	//处理结果， 10支付成功，11 支付失败，00订单申请成功，01 订单申请失败
	$kq_check_all_para.=kq_ck_null($_REQUEST[payResult],'payResult');
	//错误代码 ，请参照《人民币网关接口文档》最后部分的详细解释。
	$kq_check_all_para.=kq_ck_null($_REQUEST[errCode],'errCode');



	$trans_body=substr($kq_check_all_para,0,strlen($kq_check_all_para)-1);
	$MAC=base64_decode($_REQUEST[signMsg]);

	$fp = fopen("./99bill.cert.rsa.20140728.cer", "r"); 
	$cert = fread($fp, 8192); 
	fclose($fp); 
	$pubkeyid = openssl_get_publickey($cert); 
	$ok = openssl_verify($trans_body, $MAC, $pubkeyid); 


	if ($ok == 1) { 
		switch($_REQUEST[payResult]){
				case '10':
						//此处做商户逻辑处理
						$rtnOK=1;
						//以下是我们快钱设置的show页面，商户需要自己定义该页面。
						//支付成功

						//业务逻辑处理
						$status='';
						$id= $_REQUEST[dealId];
						$r5_Pid=$_REQUEST[ext1];
						$amount=$_REQUEST[orderAmount];
						$mydate=$_REQUEST[dealTime];
						$huilv = file_get_contents('./conf/huilv.txt');

			//检查订单是否已处理，防止重复入金
            $conn = mysql_connect('127.0.0.1','root','');//配置数据库密码
            mysql_select_db('yeepay');
            mysql_query('set names gbk');
			//$sqlLock = 'LOCK TABLES jyzbpme WRITE';
			//mysql_query($sqlLock);
            $sql = "select * from cbfinancials where orderNum = '" . trim($id) . "'";
            $result = mysql_query($sql);
            $row = mysql_fetch_array($result);
            if($row){
                //订单已处理，退出
                mysql_close($conn);
				file_put_contents('log.txt','订单已处理，退出',FILE_APPEND);
                exit();
            }
			
			//处理订单，写入数据库
			
			$sql = "insert into cbfinancials values (0 , '" . trim($id) . "')";
			$r = mysql_query($sql);
			if(!$r){
				$status .="订单信息写入数据库失败，可能会出现重复入金，请检查";
				file_put_contents('log.txt',$status,FILE_APPEND);
				
			}
			

			//$sqlLock = 'UNLOCK TABLES';
			//mysql_query($sqlLock);
			mysql_close($conn);
            
            //与MT4服务器通信
			$encryptionKey = "asfas1";
			$secretHash = "fsdvgfygfsddsag";
			$status = "";

			$mt4request = new CMT4DataReciver;
			////////////////////////////////////////////////////////////////////////////////////////////
			$connResult = $mt4request->OpenConnection(SERVER_ADDRESS, SERVER_PORT);
			if($connResult==-1){
				//echo '与MT4服务器通信失败，本次入金未更新至账户，请联系管理员手动处理。','<br />';
				$status .= "与MT4服务器通信失败，本次入金未更新至账户，请手动处理。\r\n";
			}else{	
				//更新账户余额
				$params['login'] = $r5_Pid;
				$params['value'] = round($amount/(100*$huilv),2);
				$params['comment'] = "change account balance from yeepay";
				$answerData = $mt4request->MakeRequest("changebalance", $params);
				if($answerData == 'Fail!'){
					//echo '更新交易账户余额失败，请联系管理员手动处理。','<br />';
					$status .= "更新交易账户余额失败，本次入金未更新至账户，请手动处理。\r\n";
				}else{
					//echo '更新交易账户余额成功。本次入金：$'.$r3_Amt.'<br />';
					$status .= "更新交易账户余额成功。\r\n";
				}
				$mt4request->CloseConnection();
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
				$mail->Password   = ""; //配置邮箱密码
				//$mail->IsSendmail(); //如果没有sendmail组件就注释掉，否则出现“Could  not execute: /var/qmail/bin/sendmail ”的错误提示
				$mail->AddReplyTo("yiyiyitest@163.com","Admin");//回复地址
				$mail->From       = "yiyiyitest@163.com";
				$mail->FromName   = "Admin";
				$to = "574814416@qq.com";
				$mail->AddAddress($to);
				$mail->AddBCC("eddy@rrgod.com");
				$mail->Subject  = "在线入金通知";
				//发送的内容
				$mail->Body = "客户在线入金成功，详情如下：\r\n"."账户ID：$r5_Pid\r\n"."金额(￥)：$amount\r\n"."订单号：$id\r\n"."时间：".$mydate."\r\n与MT4服务器交互状态：\r\n".$status;
				//$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; //当邮件不支持html时备用显示，可以省略
				$mail->WordWrap   = 80; // 设置每行字符串的长度
				//$mail->AddAttachment("f:/test.png");  //可以添加附件
				$mail->IsHTML(false); 
				$mail->Send();
				//echo '在线入金通知邮件已发送至管理员邮箱。';
				//var_dump(file_put_contents('log.txt','在线入金通知邮件已发送至管理员邮箱。',FILE_APPEND));
			} catch (phpmailerException $e) {
				//echo "在线入金通知邮件发送失败：".$e->errorMessage();
				$errmsg = "在线入金通知邮件发送失败：".$e->errorMessage();
				file_put_contents('log.txt',$errmsg,FILE_APPEND);
			}
						

						$rtnUrl="http://kq.cbfinancials.net/pay/show.php?msg=success";
						break;
				default:
						$rtnOK=1;
						//以下是我们快钱设置的show页面，商户需要自己定义该页面。
						//支付失败
						$rtnUrl="http://kq.cbfinancials.net/pay/show.php?msg=false";
						break;	
		
		}

	}else{
						$rtnOK=1;
						//以下是我们快钱设置的show页面，商户需要自己定义该页面。
						//验证签名失败
						$rtnUrl="http://kq.cbfinancials.net/pay/show.php?msg=error";
							
	}



?>

<result><?PHP echo $rtnOK; ?></result> <redirecturl><?PHP echo $rtnUrl; ?></redirecturl>