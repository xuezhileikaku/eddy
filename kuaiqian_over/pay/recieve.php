<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php
require_once '../conf/config.php';
require_once '../common/commonfunc.php';
$flag = false;
$status = "";
set_time_limit(0);

function kq_ck_null($kq_va, $kq_na) {
    if ($kq_va == "") {
        return $kq_va = "";
    } else {
        return $kq_va = $kq_na . '=' . $kq_va . '&';
    }
}

// 人民币网关账号，该账号为11位人民币网关商户编号+01,该值与提交时相同。
$kq_check_all_para = kq_ck_null($_REQUEST [merchantAcctId], 'merchantAcctId');
// 网关版本，固定值：v2.0,该值与提交时相同。
$kq_check_all_para .= kq_ck_null($_REQUEST [version], 'version');
// 语言种类，1代表中文显示，2代表英文显示。默认为1,该值与提交时相同。
$kq_check_all_para .= kq_ck_null($_REQUEST [language], 'language');
// 签名类型,该值为4，代表PKI加密方式,该值与提交时相同。
$kq_check_all_para .= kq_ck_null($_REQUEST [signType], 'signType');
// 支付方式，一般为00，代表所有的支付方式。如果是银行直连商户，该值为10,该值与提交时相同。
$kq_check_all_para .= kq_ck_null($_REQUEST [payType], 'payType');
// 银行代码，如果payType为00，该值为空；如果payType为10,该值与提交时相同。
$kq_check_all_para .= kq_ck_null($_REQUEST [bankId], 'bankId');
// 商户订单号，,该值与提交时相同。
$kq_check_all_para .= kq_ck_null($_REQUEST [orderId], 'orderId');
// 订单提交时间，格式：yyyyMMddHHmmss，如：20071117020101,该值与提交时相同。
$kq_check_all_para .= kq_ck_null($_REQUEST [orderTime], 'orderTime');
// 订单金额，金额以“分”为单位，商户测试以1分测试即可，切勿以大金额测试,该值与支付时相同。
$kq_check_all_para .= kq_ck_null($_REQUEST [orderAmount], 'orderAmount');
// 快钱交易号，商户每一笔交易都会在快钱生成一个交易号。
$kq_check_all_para .= kq_ck_null($_REQUEST [dealId], 'dealId');
// 银行交易号 ，快钱交易在银行支付时对应的交易号，如果不是通过银行卡支付，则为空
$kq_check_all_para .= kq_ck_null($_REQUEST [bankDealId], 'bankDealId');
// 快钱交易时间，快钱对交易进行处理的时间,格式：yyyyMMddHHmmss，如：20071117020101
$kq_check_all_para .= kq_ck_null($_REQUEST [dealTime], 'dealTime');
// 商户实际支付金额 以分为单位。比方10元，提交时金额应为1000。该金额代表商户快钱账户最终收到的金额。
$kq_check_all_para .= kq_ck_null($_REQUEST [payAmount], 'payAmount');
// 费用，快钱收取商户的手续费，单位为分。
$kq_check_all_para .= kq_ck_null($_REQUEST [fee], 'fee');
// 扩展字段1，该值与提交时相同
$kq_check_all_para .= kq_ck_null($_REQUEST [ext1], 'ext1');
// 扩展字段2，该值与提交时相同。
$kq_check_all_para .= kq_ck_null($_REQUEST [ext2], 'ext2');
// 处理结果， 10支付成功，11 支付失败，00订单申请成功，01 订单申请失败
$kq_check_all_para .= kq_ck_null($_REQUEST [payResult], 'payResult');
// 错误代码 ，请参照《人民币网关接口文档》最后部分的详细解释。
$kq_check_all_para .= kq_ck_null($_REQUEST [errCode], 'errCode');

$trans_body = substr($kq_check_all_para, 0, strlen($kq_check_all_para) - 1);
$MAC = base64_decode($_REQUEST [signMsg]);

$fp = fopen("../conf/99bill.cert.rsa.20140728.cer", "r");
$cert = fread($fp, 8192);
fclose($fp);
$pubkeyid = openssl_get_publickey($cert);
$ok = openssl_verify($trans_body, $MAC, $pubkeyid);

if ($ok == 1) {
    switch ($_REQUEST [payResult]) {
        case '10' :
            // 支付成功，业务逻辑处理
            $rtnOK = 1;
            $rtnUrl = MY_HOST . '/pay/show.php?msg=success';

            $id = $_REQUEST [dealId];//快钱交易号
            $r5_Pid = $_REQUEST [ext1];//用户字段
            $amount = $_REQUEST [orderAmount] / 100; // 分转为元
            $mydate = $_REQUEST [dealTime];//交易时间
            $huilv = file_get_contents('../conf/huilv.txt');
            //时间判断 当前时间大于订单时间8分钟则不处理
            $d_time = strtotime($mydate);
            if ((time() - $d_time) > (8 * 60)) {
                break;
            }

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
                    $res = file_put_contents('./log.txt', "{$t}:订单{$_REQUEST[dealId]}已处理，退出\r\n", FILE_APPEND);
                    break;
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
                    break;
                }
				mysqli_query($db, 'UNLOCK TABLES');
                mysqli_close($db);

                // 更新账户余额
                $pw = file_get_contents('../conf/pw_' . $r5_Pid . '.txt');
                $params ['login'] = $r5_Pid;
                $params ['password'] = $pw;
                $params ['value'] = round($amount * 0.99 / $huilv, 2);
                $params ['comment'] = "D online $id";
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
                break;
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
            break;
        default :
            $rtnOK = 1;
            // 支付失败
            $rtnUrl = MY_HOST . '/pay/show.php?msg=false';
            break;
    }
} else {
    $rtnOK = 1;
    // 验证签名失败
    $rtnUrl = MY_HOST . '/pay/show.php?msg=error';
}
?>

<result><?PHP echo $rtnOK; ?></result>
<redirecturl><?PHP echo $rtnUrl; ?></redirecturl>