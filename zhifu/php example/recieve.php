<?php
header("content-Type: text/html; charset=utf-8");
require_once '../conf/config.php';
require_once '../common/commonfunc.php';
$flag = false;
$status = "";
set_time_limit(0);
error_reporting(0);

  // 公共函数定义
  function HexToStr($hex)
  {
     $string="";
     for ($i=0;$i<strlen($hex)-1;$i+=2)
         $string.=chr(hexdec($hex[$i].$hex[$i+1]));
     return $string;
  }

//=========================== 把商家的相关信息返回去 =======================

	$m_id		= 	'';			 //商家号
	$m_orderid	= 	'';			//商家订单号
	$m_oamount	= 	'';			//支付金额
	$m_ocurrency= 	'';			//币种
	$m_language	= 	'';			//语言选择
	$s_name		= 	'';			//消费者姓名
	$s_addr		= 	'';			//消费者住址
	$s_postcode	= 	''; 		//邮政编码
	$s_tel		= 	'';			//消费者联系电话
	$s_eml		= 	'';			//消费者邮件地址
	$r_name		= 	'';			//消费者姓名
	$r_addr		= 	'';			//收货人住址
	$r_postcode	= 	''; 		//收货人邮政编码
	$r_tel		= 	'';			//收货人联系电话
	$r_eml		= 	'';			//收货人电子地址
	$m_ocomment	= 	''; 		//备注
	$modate		=	'';			//返回日期
	$State		=	'';			//支付状态2成功,3失败

	//接收组件的加密
	$OrderInfo	=	$_POST['OrderMessage'];			//订单加密信息

	$signMsg 	=	$_POST['Digest'];				//密匙
	//接收新的md5加密认证

	//检查签名
	$key = SEC;   //<--支付密钥--> 注:此处密钥必须与商家后台里的密钥一致
	//$digest = $MD5Digest->encrypt($OrderInfo.$key);
	$digest = strtoupper(md5($OrderInfo.$key));

	if ($digest == $signMsg)
	{
		//解密
		//$decode = $DES->Descrypt($OrderInfo, $key);
		$OrderInfo = HexToStr($OrderInfo);
		//=========================== 分解字符串 ====================================
		$parm=explode("|", $OrderInfo);

		$m_id		= 	$parm[0];
		$m_orderid	= 	$parm[1];
		$m_oamount	= 	$parm[2];
		$m_ocurrency= 	$parm[3];
		$m_language	= 	$parm[4];
		$s_name		= 	$parm[5];
		$s_addr		= 	$parm[6];
		$s_postcode	= 	$parm[7];
		$s_tel		= 	$parm[8];
		$s_eml		= 	$parm[9];
		$r_name		= 	$parm[10];
		$r_addr		= 	$parm[11];
		$r_postcode	= 	$parm[12];
		$r_tel		= 	$parm[13];
		$r_eml		= 	$parm[14];
		$m_ocomment	= 	$parm[15];
		$modate		=	$parm[16];
		$State		=	$parm[17];

		if ($State == 2)
			{
				echo "支付成功".'<br>';
				//echo "商家号=".$m_id.'<br>';
				//echo "订单号=".$m_orderid.'<br>';
				echo "金额：".$m_oamount.'<br>';
				//echo "币种=".$m_ocurrency.'<br>';
				//echo ".................";
			
			$id = $m_orderid;//交易号
            $r5_Pid = $s_name;//用户字段
            $amount = $m_oamount;
            $mydate = $modate;//交易时间
            $huilv = file_get_contents('../conf/huilv.txt');

			// 检查订单是否已处理，防止重复入金
            $db = mysqli_connect($db_info ['db_addr'], $db_info ['db_user'], $db_info ['db_pwd']);
            if (!mysqli_connect_errno()) {
                mysqli_select_db($db, $db_info ['db_name']);
                mysqli_query($db, 'set names utf8');
				mysqli_query($db, 'LOCK TABLE mt4_deposition WRITE');//锁表
                $sql = "select id from mt4_deposition where order_id = '" . trim($id) . "'";
                $result = mysqli_query($db, $sql);
                $row = mysqli_fetch_assoc($result);
                if ($row) {
                    // 订单已处理，退出
					mysqli_query($db, 'UNLOCK TABLES');//表解锁
                    mysqli_close($db);
					$t = date('Y-m-d H:i:s');
                    $res = file_put_contents('./log.txt', "{$t}:订单{$id}已处理，退出\r\n", FILE_APPEND);
                    exit;
                }
                //写入记录至数据库
                $d = date('Y-m-d H:i:s');
                $s = 0;
                $sql = "insert into mt4_deposition values (null,'{$r5_Pid}','{$amount}','{$id}','{$d}','{$s}','')";
                $rs = mysqli_query($db, $sql);
                if (!$rs) {
                    mysqli_query($db, 'UNLOCK TABLES');//表解锁
                    mysqli_close($db);
                    file_put_contents('./log.txt', '在线入金记录写入数据库失败[write failed] - ' . $sql . ' - ' . mysqli_error($db) . $d . "\r\n", FILE_APPEND);
                    exit;
                }
				mysqli_query($db, 'UNLOCK TABLES');
                mysqli_close($db);

                // 更新账户余额
                $pw = file_get_contents('../conf/pw_' . $r5_Pid . '.txt');
                $params ['login'] = $r5_Pid;
                $params ['password'] = $pw;
                $params ['value'] = round($amount / $huilv, 2);
                $params ['comment'] = "online $id";
                $result = changeAccountBalance($params, 10, 3);
                if ($result ['flag']) {
                    $status .= "更新交易账户余额成功。\r\n";
                    $flag = true;
                } else {
                    $status .= $result ['errmsg'] . "，本次入金未更新至账户，请手动处理。\r\n";
                }

                //更新记录至数据库
                $db = mysqli_connect($db_info ['db_addr'], $db_info ['db_user'], $db_info ['db_pwd']);
                if (!mysqli_connect_errno()) {
                    mysqli_select_db($db, $db_info ['db_name']);
                    mysqli_query($db, 'set names utf8');
                    $s = $flag ? 1 : 0;
                    $sql = "update mt4_deposition set is_success = {$s} where order_id = '{$id}'";
                    $rs = mysqli_query($db, $sql);
                    if (!$rs) {
                        file_put_contents('./log.txt', '在线入金记录更新数据库失败[write failed] - ' . $sql . ' - ' . mysqli_error($db) . $d . "\r\n", FILE_APPEND);
                    }
                    mysqli_close($db);
                } else {
                    file_put_contents('./log.txt', '在线入金记录更新失败[open failed] - ' . $sql . ' - ' . mysqli_error($db) . $d . "\r\n", FILE_APPEND);
                }
                mysqli_close($db);               
            } else {
                file_put_contents('./log.txt', '在线入金查询数据库失败[open failed] - ' . $sql . ' - ' . mysqli_error($db) . date('Y-m-d H:m:s') . "\r\n", FILE_APPEND);
            }


				// 发送邮件通知
            if (defined('SEND_EMAIL') && SEND_EMAIL) {
                require '../common/class.phpmailer.php';
                try {
                    $mail = new PHPMailer(true);
                    $mail->IsSMTP();
                    $mail->CharSet = $email ['charset']; // 设置邮件的字符编码，这很重要，不然中文乱码
                    $mail->SMTPAuth = true; // 开启认证
                    $mail->Port = 25;
                    $mail->Host = $email ['host'];
                    $mail->Username = $email ['username'];
                    $mail->Password = $email ['password'];
                    // $mail->IsSendmail(); //如果没有sendmail组件就注释掉，否则出现“Could not
                    // execute:
                    // /var/qmail/bin/sendmail ”的错误提示
                    $mail->AddReplyTo($email ['username'], "Admin"); // 回复地址
                    $mail->From = $email ['username'];
                    $mail->FromName = "Admin";

                    foreach ($email_list as $v) {
                        $mail->AddAddress($v);
                    }
                    if ($flag) {
                        $mail->Subject = "在线入金通知[更新成功]";
                    } else {
                        $mail->Subject = "在线入金通知[更新失败-$status]";
                    }
                    // 发送的内容
                    $mail->Body = "客户在线入金通知，详情如下：\r\n" . "账户ID：$r5_Pid\r\n" . "金额(￥)：$amount\r\n" . "订单号：$id\r\n" . "时间：" . $mydate . "\r\n与MT4服务器交互状态：\r\n" . $status;
                    // $mail->AltBody = "To view the message, please use an HTML
                    // compatible email viewer!"; //当邮件不支持html时备用显示，可以省略
                    $mail->WordWrap = 80; // 设置每行字符串的长度
                    // $mail->AddAttachment("f:/test.png");
                    // //可以添加附件
                    $mail->IsHTML(false);
                    $mail->Send();
                    // echo '在线入金通知邮件已发送至管理员邮箱。';
                    // var_dump(file_put_contents('log.txt','在线入金通知邮件已发送至管理员邮箱。',FILE_APPEND));
                } catch (phpmailerException $e) {
                    // echo "在线入金通知邮件发送失败：".$e->errorMessage();
                    $errmsg = "在线入金通知邮件发送失败：" . $e->errorMessage();
                    file_put_contents('log.txt', $errmsg . ' | ' . date('Y-m-d H:i:s') . "\r\n", FILE_APPEND);
                }
            }

			}
		else
			{
				echo "支付失败";
			}

	}else{
	echo '失败，信息可能被篡改';
	}
?>