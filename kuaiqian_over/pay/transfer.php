<?php
session_start();
error_reporting(0);
if (!isset($_SESSION ['username']) || !isset($_SESSION ['password'])) {
    header('location:../index.php?error=非法访问');
    exit();
}
$username = $_SESSION ['username'];
$password = $_SESSION ['password'];
$balance = $_SESSION ['balance'];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>在线入金</title>
        <script type="text/javascript" src="../public/js/jquery.min.js"></script>
        <script type="text/javascript" src="../public/js/prototypes.js"></script>
        <link href="../public/css/main.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <div>
            <h3>内部转账</h3>
            <form method="post" action="doTransfer.php" id="yeepay_form">
                <table>
                    <colgroup>
                        <col width="80" />
                        <col width="129" />
                        <col width="80" />
                    </colgroup>
                    <tbody>
                        <tr>
                            <td class="tc">转出帐号：</td>
                            <td colspan="3"><input style="width: 118px;" type="text"
                                                   id="from_username" name="from_username" readonly="readonly"
                                                   value="<?php echo $username ?>" />
                                <div style="display: inline; margin-left: 10px; height: 28px;">
                                    账户余额：$<?php echo $balance ?></div></td>
                        </tr>
                        <tr>
                            <td class="tc">转入帐号：</td>
                            <td colspan="3"><input style="width: 118px;" type="text"
                                                   id="to_username" name="to_username" value="" /></td>
                        </tr>
                        <tr>
                            <td class="tc">登陆密码：</td>
                            <td colspan="3"><input style="width: 118px;" type="password"
                                                   id="txt_password" name="to_password"
                                                   value="<?php echo $password ?>" /></td>
                        </tr>
                        <tr>
                            <td class="tc">金 额：</td>
                            <td colspan="3"><input type="text" id="txt_Amount" name="amount"
                                                   maxlength="10" class="im" style="width: 118px;" /></td>
                        </tr>
                        <tr>
                            <td class="tc">服务器：</td>
                            <td colspan="3"><select id="slt_ServerCode" name="server"
                                                    style="width: 124px;">
                                    <option value="server1">server1</option>
                                    <option value="server2" selected="selected">server2</option>
                                    <option value="server3">server3</option>
                                </select></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td colspan="3"><input type="button" id="doSubmit" value="转账"
                                                   onclick="submitOrder()" /><input type="reset" value="重置" /><span><?php echo isset($_GET['error']) ? $_GET['error'] : '当前日期：' . date('Y-m-d'); ?></span></td>
                        </tr>
                    </tbody>
                </table>
            </form>
            <a href="./rujin.php" class="link">返回首页</a>
            <p class="attention">
                1、登陆密码为转入帐号的登陆密码，默认与转出帐号密码相同。若不同请手动填入密码。<br />
            </p>

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
                                                           } else if (parseFloat(amount) > parseFloat("<?php echo $balance ?>")) {
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

                                                       $('#from_username').blur(function() {
                                                           $('table span').remove();
                                                           var name = this.value.trim();
                                                           if (name.length == 0) {
                                                               $(this).parent().append('<span style="margin-left:6px;">请输入转出交易账号</span>');
                                                               return false;
                                                           }
                                                       });

                                                       $('#to_username').blur(function() {
                                                           $('table span').remove();
                                                           var name = this.value.trim();
                                                           if (name.length == 0) {
                                                               $(this).parent().append('<span style="margin-left:6px;">请输入转入交易账号</span>');
                                                               return false;
                                                           }
                                                       });

                                                       $('#txt_password').blur(function() {
                                                           $('table span').remove();
                                                           var name = this.value.trim();
                                                           if (name.length == 0) {
                                                               $(this).parent().append('<span style="margin-left:6px;">请输入登陆密码</span>');
                                                               return false;
                                                           }
                                                       });



                                                       function submitOrder() {
                                                           $('table span').remove();

                                                           var username = $('#to_username').val().trim();
                                                           var fUser = $('#from_username').val().trim();
                                                           if (username == fUser) {
                                                               $('#to_username').parent().append('<span style="margin-left:6px;">转入与转出帐号不能相同</span>');
                                                               $('#to_username').focus();
                                                               return false;
                                                           }
                                                           var p = /^\d+$/;
                                                           if (username.length == 0) {
                                                               $('#to_username').parent().append('<span style="margin-left:6px;">请输入转入交易账号</span>');
                                                               $('#to_username').focus();
                                                               return false;
                                                           } else if (!p.test(username))
                                                           {
                                                               $('#to_username').parent().append('<span style="margin-left:6px;">交易账号必须为数字</span>');
                                                               $('#to_username').focus();
                                                               return false;
                                                           }

                                                           var password = $('#txt_password').val().trim();
                                                           if (password.length == 0) {
                                                               $('#txt_password').parent().append('<span style="margin-left:6px;">请输入登陆密码</span>');
                                                               $('#txt_password').focus();
                                                               return false;
                                                           }

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
                                                           } else if (parseFloat(amount) > parseFloat("<?php echo $balance ?>")) {
                                                               $('#txt_Amount').parent().append('<span style="margin-left:6px;">转出金额不得大于账户余额</span>');
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

                                                           
														   $("#doSubmit").attr("disabled",true);
														   $('#yeepay_form').submit();
                                                       }
        </script>
    </body>
</html>