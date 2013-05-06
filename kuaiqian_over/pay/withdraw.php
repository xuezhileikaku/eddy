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

require '../conf/config.php';
$name = '';
$bank_code = '';
$bank_code = '';
$db = mysqli_connect($db_info ['db_addr'], $db_info ['db_user'], $db_info ['db_pwd'], $db_info ['db_name']);
if (mysqli_connect_errno() !== 0) {
    exit('数据库连接失败！');
}
mysqli_query($db, 'set names utf8');
$rs = mysqli_query($db, "select name,bank_code,bank_name from mt4_withdraw where account = '$username' order by time desc limit 1");
$r = mysqli_fetch_assoc($rs);
if ($r) {
    $name = $r['name'];
    $bank_code = $r['bank_code'];
    $bank_name = $r['bank_name'];
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>在线出金</title>
        <script type="text/javascript" src="../public/js/jquery.min.js"></script>
        <script type="text/javascript" src="../public/js/prototypes.js"></script>
        <link href="../public/css/main.css" rel="stylesheet" type="text/css" />
        <style>
            body div {
                padding: 0;
            }
        </style>
    </head>

    <body>
        <div>
            <h3>在线出金</h3>
            <form method="post" action="doWithdraw.php" id="yeepay_form">
                <table>
                    <colgroup>
                        <col width="130" />
                    </colgroup>
                    <tbody>
                        <tr>
                            <td class="tc">取款交易帐号：</td>
                            <td colspan="3"><input style="width: 118px;" type="text"
                                                   id="from_username" name="from_username" readonly="readonly"
                                                   value="<?php echo $username ?>" />
                                <div style="display: inline; margin-left: 10px; height: 28px;">账户余额：$<?php echo $balance ?></div></td>
                        </tr>
                        <tr>
                            <td class="tc">提款金额（USD 美元）：</td>
                            <td colspan="3"><input type="text" id="txt_Amount" name="amount"
                                                   maxlength="10" class="im" style="width: 118px;" /></td>
                        </tr>
                        <tr>
                            <td class="tc">收款人姓名：</td>
                            <td colspan="3"><input style="width: 118px;" type="text"
                                                   id="to_username" name="to_username" value="<?php echo $name; ?>" maxlength="20" /></td>
                        </tr>
                        <tr>
                            <td class="tc">收款人银行帐号：</td>
                            <td colspan="3"><input style="width: 160px;" type="text"
                                                   id="bankcode" name="bankcode" value="<?php echo $bank_code; ?>" maxlength="19" /></td>
                        </tr>
                        <tr>
                            <td class="tc">收款账户开户行名称：</td>
                            <td colspan="3"><input style="width: 260px;" type="text"
                                                   id="bankname" name="bankname" value="<?php echo $bank_name; ?>" maxlength="100" /></td>
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
                            <td colspan="3"><input type="button" id="doSubmit" value="取款"
                                                   onclick="submitOrder()" /><input id="chongzhi" type="reset"
                                                   value="重置" /><span><?php echo isset($_GET['error']) ? $_GET['error'] : date('Y-m-d'); ?></span></td>
                        </tr>
                    </tbody>
                </table>
            </form>
            <a href="./rujin.php" class="link">返回首页</a>
            <!--
            <p class="attention">
                收款账户开户行名称必须按规定格式填写详细，否则不予汇款。格式：XX银行XX省分行XX市XX支行。例如：<i>中国</i>银行<i>广东</i>省分行<i>广州</i>市<i>越秀</i>支行
                <br />
            </p>-->
			<p class="link">
                <font color="red">出金汇率：1美元(USD) = <?php echo file_get_contents('../conf/cjhuilv.txt'); ?> 人民币(CNY)<br />
                备注：出金汇率值来自当日中国银行美元现汇买入价<br /><br />
                收款账户开户行名称填写格式：XX银行XX省分行XX市XX支行
                </font>
            </p>
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
                        <tr id="is_show">
                            <td style="height: 42px; text-align: right;"><input type="button"
                                                                                value="确定" onclick="hideMe()" /></td>
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

                                                       $('#to_username').blur(function() {
                                                           $('table span').remove();
                                                           var name = this.value.trim();
                                                           if (name.length == 0) {
                                                               $(this).parent().append('<span style="margin-left:6px;">请输入姓名</span>');
                                                               return false;
                                                           }
                                                       });

                                                       $('#bankcode').blur(function() {
                                                           $('table span').remove();
                                                           var name = this.value.trim();
                                                           var p = /^\d+$/;
                                                           if (name.length == 0) {
                                                               $(this).parent().append('<span>请输入银行帐号</span>');
                                                               return false;
                                                           } else if (!p.test(name))
                                                           {
                                                               $('#bankcode').parent().append('<span style="margin-left:6px;">必须为数字</span>');
                                                               $('#bankcode').focus();
                                                               return false;
                                                           }
                                                       });

                                                       $('#bankname').blur(function() {
                                                           $('table font').remove();
                                                           var name = this.value.trim();
                                                           if (name.length == 0) {
                                                               $(this).parent().append('<font color="red">*</font>');
                                                               return false;
                                                           }
                                                       });



                                                       function submitOrder() {
                                                           $('table span').remove();

                                                           var username = $('#from_username').val().trim();
                                                           var p = /^\d+$/;
                                                           if (username.length == 0) {
                                                               $('#from_username').parent().append('<span style="margin-left:6px;">请输入交易账号</span>');
                                                               $('#from_username').focus();
                                                               return false;
                                                           } else if (!p.test(username))
                                                           {
                                                               $('#from_username').parent().append('<span style="margin-left:6px;">交易账号必须为数字</span>');
                                                               $('#from_username').focus();
                                                               return false;
                                                           }

                                                           var to_username = $('#to_username').val().trim();
                                                           if (to_username.length == 0) {
                                                               $('#to_username').parent().append('<span style="margin-left:6px;">请输入姓名</span>');
                                                               $('#to_username').focus();
                                                               return false;
                                                           }

                                                           var bankcode = $('#bankcode').val().trim();
                                                           var p = /^\d+$/;
                                                           if (bankcode.length == 0) {
                                                               $('#bankcode').parent().append('<span>请输入银行帐号</span>');
                                                               $('#bankcode').focus();
                                                               return false;
                                                           } else if (!p.test(bankcode))
                                                           {
                                                               $('#bankcode').parent().append('<span style="margin-left:6px;">必须为数字</span>');
                                                               $('#bankcode').focus();
                                                               return false;
                                                           }

                                                           var bankname = $('#bankname').val().trim();
                                                           if (bankname.length < 6) {
                                                               $('#bankname').parent().append('<font color="red">*</font>');
                                                               $('#bankname').focus();
                                                               alert('请认真按页面底部规定格式填写收款账户开户行名称，未按格式填写将不予汇款！');
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

                                                           if (!confirm("请确认收款账户开户行名称是否按规定格式填写详细，未按格式填写将不予汇款！\r\n格式：XX银行XX省分行XX市XX支行。例如：中国工商银行湖南省分行长沙市湘雅支行\r\n点击确定提交本次出金申请，点击取消返回修改信息！")) {
                                                               return false;
                                                           }

                                                           var server = $('#slt_ServerCode').val().trim();
                                                           $('#doSubmit').attr('disabled', true);
                                                           $('#doSubmit').parent().append('<span style="margin-left:6px;">处理中！请勿关闭窗口</span>');
                                                           $('#tdMessage').html('系统正在处理中。。。请勿关闭本窗口！');
                                                           $('#glasslayer, #alter').show();
                                                           //$('#yeepay_form').submit();
                                                           $.ajax({
                                                               type: 'post',
                                                               url: './doWithdraw.php',
                                                               contentType: "application/x-www-form-urlencoded; charset=utf-8",
                                                               data: {
                                                                   active: 'expelorder',
                                                                   user: username,
                                                                   amount: amount,
                                                                   to_username: to_username,
                                                                   bankcode: bankcode,
                                                                   bankname: bankname,
                                                                   server: server
                                                               },
                                                               success: function(r) {
                                                                   if (r == 1) {
                                                                       $('#tdMessage').html('在线出金下账成功！<br />请在2个工作日内查询是否收到汇款。如有任何疑问，请联系官方客服。');
                                                                       $('#is_show').show();
                                                                       $('#glasslayer, #alter').show();
                                                                   } else {
                                                                       $('#tdMessage').html(r);
                                                                       $('#is_show').show();
                                                                       $('#glasslayer, #alter').show();
                                                                       $('#doSubmit').attr('disabled', false);
                                                                   }
                                                               }
                                                           });
                                                       }

                                                       function hideMe() {
                                                           $("#glasslayer, #alter").hide();
                                                           $('#doSubmit').attr('disabled', false);
                                                           $('table span').remove();
                                                           var str = $('#tdMessage').html();
                                                           if (str.indexOf("成功") != -1) {
                                                               //$("#chongzhi").click();
                                                               location = location;
                                                           }
                                                       }
        </script>
    </body>
</html>