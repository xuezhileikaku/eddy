<?php
session_start();
error_reporting(0);
if(!isset($_SESSION['username']) || !isset($_SESSION['password'])){
	header('location:../index.php?error=�Ƿ�����');
	exit;
}
$username=$_SESSION['username'];
$password= $_SESSION['password'];
$balance = $_SESSION['balance'];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=gbk">
    <title>�������</title>
    <script type="text/javascript" src="../js/jquery.js"></script>
    <script type="text/javascript" src="../js/prototypes.js"></script>
    <link href="../css/main.css" rel="stylesheet" type="text/css">    
<style type="text/css">
.attention{
color:red;
font-size:13px;
margin:20px auto;
}
</style></head>

<body>
    <div>
        <h3>�ڲ�ת��</h3>
        <form method="post" action="doTransfer.php" id="yeepay_form">
            <table>
                <colgroup><col width="80"><col width="129"><col width="80"><col></colgroup>
                <tbody>
				<tr>            
                    <td class="tc">ת���ʺţ�</td>
                    <td colspan="3"><input  style="width:118px;" type="text" id="from_username" name="from_username" readonly="readonly" value="<?php echo $username ?>"><div style="display:inline;margin-left:10px;height:28px;">�˻���$<?php echo $balance?></td>
                </tr>
				<tr>            
                    <td class="tc">ת���ʺţ�</td>
                    <td colspan="3"><input  style="width:118px;" type="text" id="to_username" name="to_username" value=""></td>
                </tr>
				<tr>            
                    <td class="tc">��½���룺</td>
                    <td colspan="3"><input  style="width:118px;" type="password" id="txt_password" name="to_password" value="<?php echo $password ?>"></td>
                </tr>
				<tr>
                    <td class="tc">�𡡡��</td>
                    <td colspan="3"><input type="text" id="txt_Amount" name="amount" maxlength="10" class="im" style="width:118px;"></td>        
                </tr>
				<tr>
                    <td class="tc">��������</td>
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
                    <td colspan="3"><input type="button" id="doSubmit" value="ת��" onclick="submitOrder()"><input type="reset" value="����" onclick="$(&#39;#txt_Amount, #txt_Remark&#39;).val(&#39;&#39;)"><span><?php echo isset($_GET['error']) ? $_GET['error'] : '��ǰ���ڣ�'.date('Y-m-d'); ?></span></td>
                </tr>
            </tbody></table>
        </form>
        <a href="./rujin.php" class="link">������ҳ</a>
		<p class="attention">
		1����½����Ϊת���ʺŵĵ�½���룬Ĭ����ת���ʺ�������ͬ������ͬ���ֶ��������롣<br />
		</p>
		
    </div>
    
    <script type="text/javascript">
	
	$('#txt_Amount').blur(function(){
        $('table span').remove();
        var amount = this.value.trim();
        var patrn = /^(([1-9]{1}\d*)|([0]{1}))(\.(\d){1,2})?$/;
        var type = $(':radio[name=type]:checked').val();
        if (amount.length == 0) {
            $(this).parent().append('<span style="margin-left:6px;">��������</span>');
            return false;
        } else if (!patrn.test(amount)) {
            $(this).parent().append('<span style="margin-left:6px;">����ʽ����</span>');
            return false;
        } else if (parseFloat(amount) > parseFloat("<?php echo $balance?>")){
            $(this).parent().append('<span style="margin-left:6px;">ȡ����ô����˻����</span>');
            return false;
        } else if (amount == 0){
            $(this).parent().append('<span style="margin-left:6px;">����Ϊ0</span>');
            return false;
        }
        if (amount.isInt()) {
            amount += '.00';
        } else if (amount.split('.').length > 1 && amount.split('.')[1].length == 1){
            amount += '0';
        }
        this.value = amount;
    });

	$('#from_username').blur(function(){
		$('table span').remove();
		var name=this.value.trim();
		if(name.length ==0 ){
			$(this).parent().append('<span style="margin-left:6px;">������ת�������˺�</span>');
            return false;
		}
	});

	$('#to_username').blur(function(){
		$('table span').remove();
		var name=this.value.trim();
		if(name.length ==0 ){
			$(this).parent().append('<span style="margin-left:6px;">������ת�뽻���˺�</span>');
            return false;
		}
	});

	$('#txt_password').blur(function(){
		$('table span').remove();
		var name=this.value.trim();
		if(name.length ==0 ){
			$(this).parent().append('<span style="margin-left:6px;">�������½����</span>');
            return false;
		}
	});



    function submitOrder() {
        $('table span').remove();

		var username = $('#to_username').val().trim();
		var fUser = $('#from_username').val().trim();
		if(username == fUser){
			$('#to_username').parent().append('<span style="margin-left:6px;">ת����ת���ʺŲ�����ͬ</span>');
            $('#to_username').focus();
			return false;
		}
		var p =/^\d+$/;
		if(username.length == 0){
			$('#to_username').parent().append('<span style="margin-left:6px;">������ת�뽻���˺�</span>');
            $('#to_username').focus();
			return false;
		} else if (!p.test(username))
		{
			$('#to_username').parent().append('<span style="margin-left:6px;">�����˺ű���Ϊ����</span>');
            $('#to_username').focus();
			return false;
		}

		var password = $('#txt_password').val().trim();
		if(password.length == 0){
			$('#txt_password').parent().append('<span style="margin-left:6px;">�������½����</span>');
            $('#txt_password').focus();
			return false;
		}

		var amount = $('#txt_Amount').val().trim();
        var patrn = /^(([1-9]{1}\d*)|([0]{1}))(\.(\d){1,2})?$/;
        if (amount.length == 0) {
            $('#txt_Amount').parent().append('<span style="margin-left:6px;">��������</span>');
            $('#txt_Amount').focus();
            return false;
        } else if (!patrn.test(amount)) {
            $('#txt_Amount').parent().append('<span style="margin-left:6px;">����ʽ����</span>');
            $('#txt_Amount').focus();
            return false;
        } else if (parseFloat(amount) > parseFloat("<?php echo $balance?>")){
            $('#txt_Amount').parent().append('<span style="margin-left:6px;">ת�����ô����˻����</span>');
            $('#txt_Amount').focus();
            return false;
        } else if (amount == 0){
            $('#txt_Amount').parent().append('<span style="margin-left:6px;">����Ϊ0</span>');
            $('#txt_Amount').focus();
            return false;
        }

        if (amount.isInt()) {
            amount += '.00';
        } else if (amount.split('.').length > 1 && amount.split('.')[1].length == 1){
            amount += '0';
        }

        $('#yeepay_form').submit();
    }
    </script>
</body></html>