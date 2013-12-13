<?php
session_start();
include '../init.php';
include '../common/commonfunc.php';
include '../common/IpLocation.class.php';
include '../common/mysqldb.class.php';
$username = trim(isset($_POST ['username']) ? $_POST ['username'] : null);
$password = isset($_POST ['password']) ? $_POST ['password'] : null;
if (!empty($username) && !empty($password)) {
    file_put_contents('../conf/curserv.txt', $_POST ['server']);
    $params ['login'] = $username;
    $params ['password'] = $password;
    $params ['value'] = 0;
    $params ['comment'] = "account $username login";
    $result = changeAccountBalance ( $params );
    if ($result ['flag']) {
    	$balance = $result ['balance'];
    	$_SESSION ['password'] = $password;
    	$_SESSION ['username'] = $username;
    	$_SESSION ['balance'] = $balance;
    	file_put_contents ( '../conf/pw_' . $username . '.txt', $password );
    
    	// 记录登陆日志信息
    	$Ip = new IpLocation ( 'UTFWry.dat' ); // 实例化类 参数表示IP地址库文件
    	$area = $Ip->getlocation ( '' ); // 获取某个IP地址所在的位置
    	$mysql = mysqldb::getIns ();
    	$mysql->setCharset ( 'utf8' );
    	$d = date ( 'Y-m-d H:i:s' );
    	$sql = "insert into mt4_logs values (null,'1','用户{$username}登陆。IP：{$area['ip']} 区域：{$area['country']}','{$d}')";
    	$mysql->query ( $sql );
    	$mysql->close ();
    } else {
    	header ( "location:../index.php?error={$result ['errmsg']}" );
    	exit ();
    }
} else {
    if (!isset($_SESSION ['username']) || !isset($_SESSION ['password'])) {
        header('location:../index.php?error=非法访问');
        exit();
    } else {
        $password = $_SESSION ['password'];
        $username = $_SESSION ['username'];
        $balance = $_SESSION ['balance'];
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>在线入金</title>
        <link href="../public/css/main.css" rel="stylesheet" type="text/css" />
        <style>
            .mylink {
                color: blue;
                margin: 0 10px 0 10px;
                height: 28px;
                line-height: 28px;
                float: right;
            }
        </style>
	<script type="text/javascript" src="../public/js/jquery.min.js"></script>
        <script type="text/javascript" src="../public/js/prototypes.js"></script>
    </head>
    <body>
        <div>
            <h3>银行签约支付 - 在线入金/出金/内部转帐</h3>
            <form method="post" target="_blank" action="submitpay.php"
                  id="yeepay_form">
                <table>
                    <colgroup>
                        <col width="80" />
                        <col width="129" />
                        <col width="80" />
                    </colgroup>
                    <tbody>
                        <tr>
                            <td class="tc">实盘帐号：</td>
                            <td colspan="3"><input style="width: 118px;" type="text"
                                                   id="txt_username" name="username" value="<?php echo $username; ?>"
                                                   readonly="readonly" />
                                <div style="display: inline; margin-left: 10px; height: 28px;">账户余额：$<?php echo $balance ?></div></td>

                        </tr>
                        <tr>
                            <td class="tc">类 型：</td>
                            <td colspan="3"><label class="tips"></label> <label><input
                                        type="radio" value="0" name="type" checked="checked" />存款</label>
                                <label><input type="radio" value="1" name="type" id="withdraw" />取款</label>
                                <label style="display:none"><input type="radio" value="2" name="type" id="transfer" />内部转账</label>
                            </td>
                        </tr>
                        <tr>
                            <td class="tc">金 额：</td>
                            <td colspan="3"><input type="text" id="txt_Amount" name="amount"
                                                   maxlength="10" class="im" style="width: 118px;" /></td>
                        </tr>
                        <tr>
                            <td class="tc">银 行：</td>
                            <td colspan="3"><select id="slt_BankCode" name="bank"
                                                    style="width: 124px;">
                                    <option value="">-请选择-</option>
                                    <option value="ICBC">工商银行</option>
                            <option value="ABC">农业银行</option>
                            <option value="CCB">建设银行</option>
							<option value="CMB">招商银行</option>
							<option value="CMBC">民生银行</option>
							<option value="ECITIC">中信银行</option>
							<option value="HXB">华夏银行</option>
                            <option value="BCOM">交通银行</option>
                            <option value="CIB">兴业银行</option>
                            <option value="CEBB">光大银行</option>
                            <option value="BOC">中国银行</option>
                            <option value="SPABANK">平安银行</option>
                            <option value="BEA">东亚银行</option>
                            <option value="SDB">深圳发展银行</option>
                            <option value="GDB">广发银行</option>
                            <option value="SPDB">浦发银行</option> 
                            <option value="PSBC">中国邮政</option>
                            <option value="ZYC">智游卡</option>
                            <option value="CMPAY">手机支付</option>
                                </select></td>
                        </tr>

                        <tr style="display:none">
                            <td class="tc">服务器：</td>
                            <td colspan="3"><select id="slt_ServerCode" name="server"
                                                    style="width: 124px;">
                                    <option value="server1">server1</option>
                                    <option value="server2" selected="selected">server2</option>
                                    <option value="server3">server3</option>
                                </select></td>
                        </tr>

                        <tr>
                            <td class="tt">备 注：</td>
                            <td colspan="3" style="padding: 4px;"><textarea id="txt_Remark"
                                                                            name="remark"></textarea></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td colspan="3"><input type="button" id="doSubmit" value="确定"
                                                   onclick="submitOrder()" /><input type="reset" id="chongzhi"
                                                   value="重置" /></td>
                        </tr>
                    </tbody>
                </table>
            </form>
            <a href="./quit.php" class="link">退出登陆</a>

        </div>
        <div id="glasslayer"></div>
        <div id="alter">
            <div>
                <b>系统提示</b>
                <table>
                    <tbody>
                        <tr>
                            <td id="tdMessage">请在新窗口中完成支付，然后再点击“确定”按钮</td>
                        </tr>
                        <tr>
                            <td style="height: 30px;"></td>
                        </tr>
                        <tr>
                            <td style="height: 42px; text-align: right;"><input type="button"
                                                                                value="确定" onclick="location.href = location.href;" /></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <script type="text/javascript">
                                                       $('#txt_Amount').blur(function() {
                                                           $('table span').remove();
                                                           var amount = this.value.trim();
                                                           var patrn = /^(([1-9]{1}\d*)|([0]{1}))(\.(\d){1,2})?$/;
                                                           var type = $(':radio[name=type]:checked').val();
                                                           if (amount.length == 0) {
                                                               $(this).parent().append('<span style="margin-left:6px;">请输入金额</span>');
                                                               return false;
                                                           } else if (!patrn.test(amount)) {
                                                               $(this).parent().append('<span style="margin-left:6px;">金额格式有误</span>');
                                                               return false;
                                                           } else if (type == 0 && amount < 0) {
                                                               $(this).parent().append('<span style="margin-left:6px;">存款金额不得小于300元</span>');
                                                               return false;
                                                           } else if (type == 1 && parseFloat(amount) > parseFloat("<?php echo $balance ?>")) {
                                                               $(this).parent().append('<span style="margin-left:6px;">取款金额不得大于账户余额</span>');
                                                               return false;
                                                           } else if (amount == 0) {
                                                               $(this).parent().append('<span style="margin-left:6px;">金额不能为0</span>');
                                                               return false;
                                                           }
                                                           if (amount.isInt()) {
                                                               amount += '.00';
                                                           } else if (amount.split('.').length > 1 && amount.split('.')[1].length == 1) {
                                                               amount += '0';
                                                           }
                                                           this.value = amount;
                                                       });

                                                       $('#txt_username').blur(function() {
                                                           $('table span').remove();
                                                           var name = this.value.trim();
                                                           if (name.length == 0) {
                                                               $(this).parent().append('<span style="margin-left:6px;">请输入交易账号</span>');
                                                               return false;
                                                           }
                                                       });
                                                       function submitOrder() {
                                                           $('table span').remove();
                                                           var type = $(':radio[name=type]:checked').val();
                                                           if (type.length == 0)
                                                               return false;

                                                           var username = $('#txt_username').val().trim();
                                                           var p = /^\d+$/;
                                                           if (username.length == 0) {
                                                               $('#txt_username').parent().append('<span style="margin-left:6px;">请输入交易账号</span>');
                                                               $('#txt_username').focus();
                                                               return false;
                                                           } else if (!p.test(username))
                                                           {
                                                               $('#txt_username').parent().append('<span style="margin-left:6px;">交易账号必须为数字</span>');
                                                               $('#txt_username').focus();
                                                               return false;
                                                           }

                                                           var beizhu = $('#txt_Remark').val().trim();

                                                           var amount = $('#txt_Amount').val().trim();
                                                           var patrn = /^(([1-9]{1}\d*)|([0]{1}))(\.(\d){1,2})?$/;
                                                           if (amount.length == 0) {
                                                               $('#txt_Amount').parent().append('<span style="margin-left:6px;">请输入金额</span>');
                                                               $('#txt_Amount').focus();
                                                               return false;
                                                           } else if (!patrn.test(amount)) {
                                                               $('#txt_Amount').parent().append('<span style="margin-left:6px;">金额格式有误</span>');
                                                               $('#txt_Amount').focus();
                                                               return false;
                                                           } else if (type == 0 && amount < 0) {
                                                               $('#txt_Amount').parent().append('<span style="margin-left:6px;">存款金额不得小于300元</span>');
                                                               $('#txt_Amount').focus();
                                                               return false;
                                                           } else if (type == 1 && parseFloat(amount) > parseFloat("<?php echo $balance ?>")) {
                                                               $('#txt_Amount').parent().append('<span style="margin-left:6px;">取款金额不得大于账户余额</span>');
                                                               $('#txt_Amount').focus();
                                                               return false;
                                                           } else if (amount == 0) {
                                                               $('#txt_Amount').parent().append('<span style="margin-left:6px;">金额不能为0</span>');
                                                               $('#txt_Amount').focus();
                                                               return false;
                                                           }

                                                           if (amount.isInt()) {
                                                               amount += '.00';
                                                           } else if (amount.split('.').length > 1 && amount.split('.')[1].length == 1) {
                                                               amount += '0';
                                                           }

                                                           var bank = $('#slt_BankCode').val();
                                                           if (type == 0 && bank.length == 0) {
                                                               $('#slt_BankCode').parent().append('<span style="margin-left:6px;">请选择银行</span>');
                                                               return false;
                                                           }

                                                           $('#doSubmit').attr('disabled', true);
                                                           if (type == 0) {
                                                               $('#yeepay_form').submit();
                                                               $('#glasslayer, #alter').show();
                                                           } else if (type == 1) {
                                                               //window.open('http://cbfinancials.com/cn/login.asp');

                                                               $('#chongzhi').parent().append('<span style="margin-left:6px;">正在处理中。。。请勿关闭本窗口</span>');
                                                               $.ajax({
                                                                   type: 'post',
                                                                   url: './qukuan.php',
                                                                   contentType: "application/x-www-form-urlencoded; charset=utf-8",
                                                                   data: {
                                                                       active: 'expelorder',
                                                                       user: username,
                                                                       amount: amount,
                                                                       beizhu: beizhu
                                                                   },
                                                                   success: function(r) {
                                                                       if (r == 1) {
                                                                           $('#tdMessage').html('在线出金下账成功！<br />请务必在弹出的页面中登陆官网提交本次出金的相关信息，方便客服汇款给您！<br /><font color=red>注意：未登陆官网提交本次出金信息或提交信息与本次出金信息不符，会造成我们无法汇款给您！！！</font>');
                                                                           $('#glasslayer, #alter').show();
                                                                       } else {
                                                                           alert(r);
                                                                           $('#doSubmit').attr('disabled', false);
                                                                       }
                                                                   }
                                                               });
                                                           }
                                                       }

                                                       $('#transfer').click(function() {
                                                           window.location.href = "transfer.php";
                                                       });

                                                       $('#withdraw').click(function() {
                                                           window.location.href = "withdraw.php";
                                                       });
        </script>
    </body>
</html>