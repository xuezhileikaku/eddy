<?php
session_start();
error_reporting(0);
if (!isset($_SESSION ['username']) || !isset($_SESSION ['password'])) {
    header('location:../index.php?error=非法访问');
    exit();
}

$fromU = isset($_POST ['from_username']) ? $_POST ['from_username'] : '';
$fromP = $_SESSION ['password'];
$toU = isset($_POST ['to_username']) ? $_POST ['to_username'] : '';
$toP = isset($_POST ['to_password']) ? $_POST ['to_password'] : '';
$flag = false;
$value = isset($_POST ['amount']) ? $_POST ['amount'] : - 1;
if ($value < 0) {
    header('location:transfer.php?error=转帐金额不能小于0');
    exit();
}
file_put_contents('../conf/curserv.txt', $_POST ['server']);

require '../common/commonfunc.php';
require '../conf/config.php';
$status = '';

// 账户有效性验证
$params ['login'] = $fromU;
$params ['password'] = $fromP;
$params ['value'] = 0;
$params ['comment'] = "vertify account $fromU";

$result = changeAccountBalance($params);
if ($result ['flag']) {
    if($result ['balance'] < $value){
		header('location:transfer.php?error=非法转账');
		exit();
	}
} else {
    header('location:transfer.php?error=' . $result ['errmsg']);
    exit();
}

// 账户有效性验证
$params ['login'] = $toU;
$params ['password'] = $toP;
$params ['value'] = 0;
$params ['comment'] = "vertify account $toU";

$result = changeAccountBalance($params);
if ($result ['flag']) {
    $status .= '转入账户' . $toU . "有效性验证成功<br />";
} else {
    header('location:transfer.php?error=' . $result ['errmsg']);
    exit();
}

// 出金
$params ['login'] = $fromU;
$params ['password'] = $fromP;
$params ['value'] = - $value;
$params ['comment'] = "transfer to $toU";

$result = changeAccountBalance($params);
if ($result ['flag']) {
    $_SESSION ['balance'] = $result ['balance'];
    $status .= '转出账户' . $fromU . '出金$' . $value . "成功<br />";
} else {
    header('location:transfer.php?error=' . $result ['errmsg']);
    exit();
}

// 入金
$params ['login'] = $toU;
$params ['password'] = $toP;
$params ['value'] = $value;
$params ['comment'] = "transfer from $fromU ";

$result = changeAccountBalance($params);
if ($result ['flag']) {
    $status .= '转入账户' . $toU . '入金$' . $value . "成功<br />";
    $flag = true;
} else {
    header('location:transfer.php?error=' . $result ['errmsg']);
    exit();
}

// 写入记录至数据库
$db = mysqli_connect($db_info ['db_addr'], $db_info ['db_user'], $db_info ['db_pwd']);
if (!mysqli_connect_errno()) {
    mysqli_select_db($db, $db_info ['db_name']);
    mysqli_query($db, 'set names utf8');
    $d = date('Y-m-d H:i:s');
    $s = $flag ? 1 : 0;
    $sql = "insert into mt4_transfer values (null,'{$fromU}','{$toU}','{$value}','{$d}','{$s}','')";
    $rs = mysqli_query($db, $sql);
    if (!$rs) {
        file_put_contents('./log.txt', '内部转账记录写入数据库失败[write failed] - ' . mysqli_error($db) . $d . "\r\n", FILE_APPEND);
    }
    mysqli_close($db);
} else {
    file_put_contents('./log.txt', '内部转账记录写入数据库失败[open failed] - ' . mysqli_error($db) . $d . "\r\n", FILE_APPEND);
}

if (defined('SEND_EMAIL') && SEND_EMAIL) {
    // 发送邮件
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
        // $mail->IsSendmail(); //如果没有sendmail组件就注释掉，否则出现“Could not execute:
        // /var/qmail/bin/sendmail ”的错误提示
        $mail->AddReplyTo($email ['username'], "Admin"); // 回复地址
        $mail->From = $email ['username'];
        $mail->FromName = "Admin";

        foreach ($email_list as $v) {
            $mail->AddAddress($v);
        }
        if ($flag) {
            $mail->Subject = "内部转账[成功]";
        } else {
            $mail->Subject = "内部转账[失败]";
        }
        // 发送的内容
        $mail->Body = str_replace('<br />', "\r\n", $status);
        // $mail->AltBody = "To view the message, please use an HTML compatible
        // email viewer!"; //当邮件不支持html时备用显示，可以省略
        $mail->WordWrap = 80; // 设置每行字符串的长度
        // $mail->AddAttachment("f:/test.png"); //可以添加附件
        $mail->IsHTML(false);
        $mail->Send();
        $status .= '邮件通知发送成功';
    } catch (phpmailerException $e) {
        $status .= $e->errorMessage();
    }
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>转账结果</title>
        <link href="../public/css/main.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <div>
            <?php
            echo $status, '<a class="link" href="rujin.php">返回首页</a>';
            ?>
            <p class="attention">
                备注：<br /> 1、出金与入金都显示成功说明本笔转账交易成功，否则失败。<br /> 2、如有任何疑问，请联系官方客服。
            </p>
        </div>
    </body>
</html>