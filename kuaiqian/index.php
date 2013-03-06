<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- saved from url=(0033)http://pay.ybyzw.com/default.aspx -->
<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=gbk">
    <title>在线入金</title>
    <script type="text/javascript" src="./js/jquery.js"></script>
    <script type="text/javascript" src="./js/prototypes.js"></script>
    <link href="./css/main.css" rel="stylesheet" type="text/css">    
<style type="text/css">
.attention{
color:red;
font-size:13px;
margin:20px auto;
}
</style></head>

<body>
    <div>
        <h3>银行签约在线支付 - 用户登陆</h3>
        <form method="post" action="pay/rujin.php" id="yeepay_form">
            <table>
                <colgroup><col width="80"><col width="129"><col width="80"><col></colgroup>
                <tbody>
				<tr>            
                    <td class="tc">交易帐号：</td>
                    <td colspan="3"><input type="text" id="txt_username" name="username" value=""></td>
                </tr>
				<tr>            
                    <td class="tc">登陆密码：</td>
                    <td colspan="3"><input type="password" id="txt_password" name="password" value=""></td>
                </tr>
				<tr>
                    <td class="tc">服务器：</td>
                    <td colspan="3">
                        <select id="slt_ServerCode" name="server" style="width:124px;">
								<option value="server1">server1</option> 
								<option value="server2" selected="selected">server2</option>
								<option value="server3">server3</option>
						</select>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="3"><input type="button" id="doSubmit" value="登陆" onclick="submitOrder()"><input type="reset" value="重置" onclick="$(&#39;#txt_Amount, #txt_Remark&#39;).val(&#39;&#39;)"><span><?php echo isset($_GET['error']) ? $_GET['error'] : '当前日期：'.date('Y-m-d'); ?></span></td>
                </tr>
            </tbody></table>
        </form>
        <a href="./index.php" class="link">刷新本页</a>
		<p class="attention">注意：为保证客户资金能即时准确到账，在线入金前需用您的交易帐号和密码进行登陆验证。
		</p>
		<p class="attention">
		1、如果您需要在线入金或是在线出金，请用待入金或待出金交易帐号登陆。<br />
		2、如果您需要内部转账，请用转出资金交易帐号登陆。
		</p>
		
    </div>
    
    <script type="text/javascript">

	$('#txt_username').blur(function(){
		$('table span').remove();
		var name=this.value.trim();
		if(name.length ==0 ){
			$(this).parent().append('<span style="margin-left:6px;">请输入交易账号</span>');
            return false;
		}
	});

	$('#txt_password').blur(function(){
		$('table span').remove();
		var name=this.value.trim();
		if(name.length ==0 ){
			$(this).parent().append('<span style="margin-left:6px;">请输入登陆密码</span>');
            return false;
		}
	});

    function submitOrder() {
        $('table span').remove();

		var username = $('#txt_username').val().trim();
		var p =/^\d+$/;
		if(username.length == 0){
			$('#txt_username').parent().append('<span style="margin-left:6px;">请输入交易账号</span>');
            $('#txt_username').focus();
			return false;
		} else if (!p.test(username))
		{
			$('#txt_username').parent().append('<span style="margin-left:6px;">交易账号必须为数字</span>');
            $('#txt_username').focus();
			return false;
		}

		var password = $('#txt_password').val().trim();
		if(password.length == 0){
			$('#txt_password').parent().append('<span style="margin-left:6px;">请输入登陆密码</span>');
            $('#txt_password').focus();
			return false;
		}

        $('#yeepay_form').submit();
    }
    </script>
</body></html>