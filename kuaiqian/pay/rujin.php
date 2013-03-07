<?php 
error_reporting(0);
session_start();
$username = isset($_POST['username']) ? $_POST['username'] : null;
$password = isset($_POST['password']) ? $_POST['password'] : null;
if(!empty($username) && !empty($password)){
	file_put_contents('./conf/curserv.txt',$_POST['server']);
	include 'mt.php';
	$mt4request = new CMT4DataReciver;
	$connResult = $mt4request->OpenConnection(SERVER_ADDRESS, SERVER_PORT);
		if($connResult==-1){
			header('location:../index.php?error=与MT4服务器通信失败');
			exit;
		}else{	
			//登陆验证
			$params['login'] = $username;
			$params['password'] = $password;
			$params['value'] = 0;
			$params['comment'] = "account $username login";
			$answerData = $mt4request->MakeRequest("changebalance", $params);
			//var_dump($answerData);exit;
			if(mb_substr($answerData,0,4,'GBK') == '密码错误'){
				header('location:../index.php?error=密码错误');
				exit;
			}else if(mb_substr($answerData,0,4,'GBK') == '查询用户'){
				header('location:../index.php?error=交易帐号不存在，请检查');
				exit;
			}else if($answerData == 'Fail!'){
				header('location:../index.php?error=其他错误');
				exit;
			}else if(mb_substr($answerData,0,3,'GBK') == '不支持'){
				header("location:../index.php?error=$answerData");
				exit;
			}else{
				$firs = end(explode('&',$answerData));
				$balance = round(end(explode('=',$firs)),2);
				$_SESSION['password']=$password;
				$_SESSION['username']=$username;
				$_SESSION['balance']=$balance;
				file_put_contents('./conf/pw_' . $username .'.txt',$password);
			}
			$mt4request->CloseConnection();
		}
}else{
	if(!isset($_SESSION['username']) || !isset($_SESSION['password'])){
		header('location:../index.php?error=非法访问');
		exit;
	}else{
		$password = $_SESSION['password'];
		$username = $_SESSION['username'];
		$balance = $_SESSION['balance'];
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=gbk">
    <title>在线入金</title>
    <script type="text/javascript" src="../js/jquery.js"></script>
    <script type="text/javascript" src="../js/prototypes.js"></script>
    <link href="../css/main.css" rel="stylesheet" type="text/css">
	<style>
	.mylink{
	color:blue;
	margin: 0 10px 0 10px;
	height: 28px;
    line-height: 28px;
	float:right;
	}
	</style>
</head>
<body>
<div>
        <h3>银行签约支付 - 在线入金/出金</h3>
        <form method="post" target="_blank" action="submitpay.php" id="yeepay_form">
            <table>
                <colgroup><col width="80"><col width="129"><col width="80"><col></colgroup>
                <tbody>
				<tr>            
                    <td class="tc">用户帐号：</td>
                    <td colspan="3"><input style="width:118px;" type="text" id="txt_username" name="username" value="<?php echo $username;?>" readonly="readonly"><div style="display:inline;margin-left:10px;height:28px;">账户余额：$<?php echo $balance?></div></td>
					
                </tr>
                <tr>
                    <td class="tc">类　　型：</td>
                    <td colspan="3"><label class="tips"></label>
					<label><input type="radio" value="0" name="type" checked="checked">存款</label>
					<label><input type="radio" value="1" name="type">取款</label>
					<label><input type="radio" value="2" name="type" id="transfer">内部转账</label>
					</td>
                </tr>
                <tr>
                    <td class="tc">金　　额：</td>
                    <td colspan="3"><input type="text" id="txt_Amount" name="amount" maxlength="10" class="im" style="width:118px;"></td>        
                </tr>
                <tr>
                    <td class="tc">银　　行：</td>
                    <td colspan="3">
                        <select id="slt_BankCode" name="bank" style="width:124px;">
							<option value="">-请选择-</option>
                             <option value="ICBC">工商银行</option>
                            <option value="ABC">农业银行</option>
                            <option value="CCB">建设银行</option>
							<option value="CMB">招商银行</option>
							<option value="CMBC">民生银行</option>
							<option value="CITIC">中信银行</option>
							<option value="CITIC">华夏银行</option>
							<option value="GZCB">广州银行</option>
							<option value="GZRCC">广州农村商业银行</option>
                            <option value="BOB">北京银行</option>
                            <option value="BCOM">交通银行</option>
                            <option value="CIB">兴业银行</option>
                            <option value="NJCB">南京银行</option>
                            <option value="UPOP">银联在线支付</option>
                            <option value="CEB">光大银行</option>
                            <option value="BOC">中国银行</option>
                            <option value="PAB">平安银行</option>
                            <option value="CBHB">渤海银行</option>
                            <option value="BEA">东亚银行</option>
                            <option value="NBCB">宁波银行</option>
                            <option value="SDB">深圳发展银行</option>
                            <option value="GDB">广发银行</option>
                            <option value="SHB">上海银行</option>
                            <option value="SPDB">浦发银行</option> 
                            <option value="POST">中国邮政</option>
                            <option value="BJRCB">北京农村商业银行</option>
                            <option value="CZB">浙商银行</option>
                            <option value="HZB">杭州银行</option>
                            <option value="SRCB">上海农村商业银行</option> 
                            <option value="HSB">徽商银行</option>
                            <option value="PSBC">中国邮政储蓄银行</option>                   
                        </select>
                    </td>
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
                    <td class="tt">备　　注：</td>
                    <td colspan="3" style="padding:4px;"><textarea id="txt_Remark" name="remark"></textarea></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="3"><input type="button" id="doSubmit" value="确定" onclick="submitOrder()"><input type="reset" id="chongzhi" value="重置" onclick="$(&#39;#txt_Amount, #txt_Remark&#39;).val(&#39;&#39;)"></td>
                </tr>
            </tbody></table>
        </form>
        <a href="./quit.php" class="link">退出登陆</a>
		<p class="link">
		<font color="red">汇率：1美元(USD) = <?php echo file_get_contents('./conf/huilv.txt'); ?> 人民币(CNY)</font>
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
                    <tr><td style="height:30px;"></td></tr>
                    <tr>
                        <td style="height:42px; text-align:right;"><input type="button" value="确定" onclick="location.href=location.href;"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <script type="text/javascript">
    $('#txt_Amount').blur(function(){
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
        } else if(type == 0 && amount < 300){
			$(this).parent().append('<span style="margin-left:6px;">存款金额不得小于300元</span>');
            return false;
		} else if (type == 1 && parseFloat(amount) > parseFloat("<?php echo $balance?>")){
            $(this).parent().append('<span style="margin-left:6px;">取款金额不得大于账户余额</span>');
            return false;
        } else if (amount == 0){
            $(this).parent().append('<span style="margin-left:6px;">金额不能为0</span>');
            return false;
        }
        if (amount.isInt()) {
            amount += '.00';
        } else if (amount.split('.').length > 1 && amount.split('.')[1].length == 1){
            amount += '0';
        }
        this.value = amount;
    });

	$('#txt_username').blur(function(){
		$('table span').remove();
		var name=this.value.trim();
		if(name.length ==0 ){
			$(this).parent().append('<span style="margin-left:6px;">请输入交易账号</span>');
            return false;
		}
	});
    function submitOrder() {
        $('table span').remove();           
        var type = $(':radio[name=type]:checked').val();
        if (type.length == 0) return false;

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
        } else if(type == 0 && amount < 300){
			$('#txt_Amount').parent().append('<span style="margin-left:6px;">存款金额不得小于300元</span>');
            $('#txt_Amount').focus();
            return false;
		} else if (type == 1 && parseFloat(amount) > parseFloat("<?php echo $balance?>")){
            $('#txt_Amount').parent().append('<span style="margin-left:6px;">取款金额不得大于账户余额</span>');
            $('#txt_Amount').focus();
            return false;
        } else if (amount == 0){
            $('#txt_Amount').parent().append('<span style="margin-left:6px;">金额不能为0</span>');
            $('#txt_Amount').focus();
            return false;
        }

        if (amount.isInt()) {
            amount += '.00';
        } else if (amount.split('.').length > 1 && amount.split('.')[1].length == 1){
            amount += '0';
        }

        var bank = $('#slt_BankCode').val();
        if (type == 0 && bank.length == 0) {
            $('#slt_BankCode').parent().append('<span style="margin-left:6px;">请选择银行</span>');
            return false;
        }

        $('#doSubmit').attr('disabled', true);
        if (type == 0){
            $('#yeepay_form').submit();
            $('#glasslayer, #alter').show();
        } else if (type == 1){
			window.open('http://cbfinancials.com/cn/login.asp');
			
			$('#chongzhi').parent().append('<span style="margin-left:6px;">正在处理中。。。请勿关闭本窗口</span>');
            $.ajax({
                type: 'post',
                url: './qukuan.php',
				contentType:"application/x-www-form-urlencoded; charset=gbk",
                data: {
                    active: 'expelorder',
						user: username,
						amount: amount,
						beizhu: beizhu
                },
                success: function (r) {
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

	$('#transfer').click(function(){
		window.location.href = "transfer.php";
	});
    </script>
	</body></html>