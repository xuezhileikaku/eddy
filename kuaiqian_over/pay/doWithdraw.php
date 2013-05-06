<?php

header('content-type:text/html;charset=utf-8');
session_start();
error_reporting(0);
if (!isset($_SESSION ['username']) || !isset($_SESSION ['password'])) {
    header('location:../index.php?error=非法访问');
    exit();
}

require '../conf/config.php';
require '../common/commonfunc.php';
ini_set("magic_quotes_runtime", 0);

$r5_Pid = $_POST ['user'];
$r3_Amt = $_POST ['amount'];
$username = $_POST ['to_username'];
$bankcode = $_POST ['bankcode'];
$bankname = $_POST ['bankname'];
$status = false;
if ($r5_Pid == '' || $r3_Amt == '') {
    header('location:../index.php?error=非法访问');
    exit();
}

if ($r3_Amt < 0) {
    header('location:../index.php?error=非法访问');
    exit();
}

$params ['login'] = $r5_Pid;
$params ['password'] = $_SESSION ['password'];
$params ['value'] = - $r3_Amt;
$params ['comment'] = "withdraw online";

$result = changeAccountBalance($params);
if ($result ['flag']) {
    $status = true;
    $_SESSION ['balance'] = $result ['balance'];
} else {
    exit($result ['errmsg']);
}

// 写入记录至数据库
$db = mysqli_connect($db_info ['db_addr'], $db_info ['db_user'], $db_info ['db_pwd']);
if (!mysqli_connect_errno()) {
    mysqli_select_db($db, $db_info ['db_name']);
    mysqli_query($db, 'set names utf8');
    $d = date('Y-m-d H:i:s');
    $s = $status ? 1 : 0;
    
	$cjhv = file_get_contents('../conf/cjhuilv.txt');
	if ($cjhv === false){
		$cjhv = 0;//
		file_put_contents('./log.txt', "出金获取汇率出错\r\n",FILE_APPEND);
	}
	$rmb = round(0.999 * $cjhv * $r3_Amt,2);
	$sql = "insert into mt4_withdraw values (null,'{$r5_Pid}','{$r3_Amt}','{$username}','{$bankcode}','{$bankname}','{$d}','{$s}','0',{$cjhv},{$rmb})";
     
    //$sql = "insert into mt4_withdraw values (null,'{$r5_Pid}','{$r3_Amt}','{$username}','{$bankcode}','{$bankname}','{$d}','{$s}','0')";
    $rs = mysqli_query($db, $sql);
    if (!$rs) {
        file_put_contents('./log.txt', '在线出金记录写入数据库失败[write failed] - ' . mysqli_error($db) . $d . "\r\n", FILE_APPEND);
    }
    mysqli_close($db);
} else {
    file_put_contents('./log.txt', '在线出金记录写入数据库失败[open failed] - ' . mysqli_error($db) . $d . "\r\n", FILE_APPEND);
}

if (defined('SEND_EMAIL') && SEND_EMAIL) {
    require '../common/class.phpmailer.php';
    try {
        $mail = new PHPMailer(true);
        $mail->IsSMTP();
        $mail->CharSet = $email['charset']; // 设置邮件的字符编码，这很重要，不然中文乱码
        $mail->SMTPAuth = true; // 开启认证
        $mail->Port = 25;
        $mail->Host = $email['host'];
        $mail->Username = $email['username'];
        $mail->Password = $email['password'];
        // $mail->IsSendmail(); //如果没有sendmail组件就注释掉，否则出现“Could not execute:
        // /var/qmail/bin/sendmail ”的错误提示
        $mail->AddReplyTo($email['username'], "Admin"); // 回复地址
        $mail->From = $email['username'];
        $mail->FromName = "Admin";

        foreach ($email_list as $v) {
            $mail->AddAddress($v);
        }
        if ($status) {
            $mail->Subject = "出金通知[成功]";
        } else {
            $mail->Subject = "出金通知[失败]";
        }
        // 发送的内容
        $mail->Body = "客户在线出金请求，详情如下：\r\n" . "取款交易帐号：$r5_Pid\r\n" . "提款金额（USD 美元）：$r3_Amt\r\n" . "收款人姓名：$username\r\n" . "收款人银行帐号：$bankcode\r\n" . "收款账户开户行名称：$bankname\r\n" . "时间：" . date('Y-m-d H:i', time()) . "\r\n与MT4服务器交互状态：" . ($status ? '成功' : '失败');
        // $mail->AltBody = "To view the message, please use an HTML compatible
        // email viewer!"; //当邮件不支持html时备用显示，可以省略
        $mail->WordWrap = 80; // 设置每行字符串的长度
        // $mail->AddAttachment("f:/test.png"); //可以添加附件
        $mail->IsHTML(false);
        $mail->Send();
    } catch (phpmailerException $e) {
        echo $e->errorMessage();
    }
}

if ($status) {
    echo '1';
} else {
    echo '账户余额更新失败，请联系客服手动下账处理';
}
?>