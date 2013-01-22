<?php
header("Content-type:text/html; charset=gbk"); 
//error_reporting(E_ALL);
include 'mt.php';
require './lib/class.phpmailer.php';
//----------------------------------------------------
//  接收数据
//  Receive the data
//----------------------------------------------------
$billno = $_GET['billno'];
$amount = $_GET['amount'];
$mydate = $_GET['date'];
$succ = $_GET['succ'];
$msg = $_GET['msg'];
$attach = $_GET['attach'];
$ipsbillno = $_GET['ipsbillno'];
$retEncodeType = $_GET['retencodetype'];
$currency_type = $_GET['Currency_type'];
$signature = $_GET['signature'];

$ar = explode('#',$attach);
$r5_Pid = isset($ar[0])?trim($ar[0]):'';
//'----------------------------------------------------
//'   Md5摘要认证
//'   verify  md5
//'----------------------------------------------------

//RetEncodeType设置为17（MD5摘要数字签名方式）
//交易返回接口MD5摘要认证的明文信息如下：
//billno+【订单编号】+currencytype+【币种】+amount+【订单金额】+date+【订单日期】+succ+【成功标志】+ipsbillno+【IPS订单编号】+retencodetype +【交易返回签名方式】+【商户内部证书】
//例:(billno000001000123currencytypeRMBamount13.45date20031205succYipsbillnoNT2012082781196443retencodetype17GDgLwwdK270Qj1w4xho8lyTpRQZV9Jm5x4NwWOTThUa4fMhEBK9jOXFrKRT6xhlJuU2FEa89ov0ryyjfJuuPkcGzO5CeVx5ZIrkkt1aBlZV36ySvHOMcNv8rncRiy3DQ)

//返回参数的次序为：
//billno + mercode + amount + date + succ + msg + ipsbillno + Currecny_type + retencodetype + attach + signature + bankbillno
//注2：当RetEncodeType=17时，摘要内容已全转成小写字符，请在验证的时将您生成的Md5摘要先转成小写后再做比较
$content = 'billno'.$billno.'currencytype'.$currency_type.'amount'.$amount.'date'.$mydate.'succ'.$succ.'ipsbillno'.$ipsbillno.'retencodetype'.$retEncodeType;
//请在该字段中放置商户登陆merchant.ips.com.cn下载的证书
$cert = '533452902246130271367731867811846929080294801757909837579268418039910128016283000991201945082188686173778172038515741783897XXXXX';
$signature_1ocal = md5($content . $cert);
//print_r($_GET);
if ($signature_1ocal == $signature)
{
	//----------------------------------------------------
	//  判断交易是否成功
	//  See the successful flag of this transaction
	//----------------------------------------------------
	if ($succ == 'Y')
	{
		/**----------------------------------------------------
		*比较返回的订单号和金额与您数据库中的金额是否相符
		*compare the billno and amount from ips with the data recorded in your datebase
		*----------------------------------------------------
		
		if(不等)
			echo "从IPS返回的数据和本地记录的不符合，失败！"
			exit
		else
			'----------------------------------------------------
			'交易成功，处理您的数据库
			'The transaction is successful. update your database.
			'----------------------------------------------------
		end if
		**/
		echo '交易成功';
		echo  "<br />在线支付页面返回，请查看交易账户是否已入金成功，如有疑问，请联系400-006-8599<br />";

			//业务逻辑处理
			//检查订单是否已处理，防止重复入金
            $conn = mysql_connect('127.0.0.1','root','');//配置密码
            mysql_select_db('yeepay');
            mysql_query('set names gbk');
			//$sqlLock = 'LOCK TABLES jyzbpme WRITE';
			//mysql_query($sqlLock);
            $sql = "select * from jyzbpme where orderNum = '" . trim($ipsbillno) . "'";
            $result = mysql_query($sql);
            $row = mysql_fetch_array($result);
            if($row){
                //订单已处理，退出
                mysql_close($conn);
				file_put_contents('log.txt','订单已处理，退出',FILE_APPEND);
                exit();
            }
			
			//处理订单，写入数据库
			
			$sql = "insert into jyzbpme values (0 , '" . trim($ipsbillno) . "')";
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
				$params['value'] = $amount;
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
				$mail->Password   = "";//配置密码     
				//$mail->IsSendmail(); //如果没有sendmail组件就注释掉，否则出现“Could  not execute: /var/qmail/bin/sendmail ”的错误提示
				$mail->AddReplyTo("yiyiyitest@163.com","Admin");//回复地址
				$mail->From       = "yiyiyitest@163.com";
				$mail->FromName   = "Admin";
				$to = "623165581@qq.com";
				$mail->AddAddress($to);
				$mail->AddBCC("eddy@rrgod.com");
				$mail->Subject  = "在线入金通知";
				//发送的内容
				$mail->Body = "客户在线入金成功，详情如下：\r\n"."账户ID：$r5_Pid\r\n"."金额(￥)：$amount\r\n"."订单号：$ipsbillno\r\n"."时间：".$mydate."\r\n与MT4服务器交互状态：\r\n".$status;
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
	}
	else
	{
		echo '交易失败！';
		exit;
	}
}
else
{
	echo '签名不正确！';
	exit;
}
?>
