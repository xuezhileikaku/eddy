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
			header('location:../index.php?error=��MT4������ͨ��ʧ��');
			exit;
		}else{	
			//��½��֤
			$params['login'] = $username;
			$params['password'] = $password;
			$params['value'] = 0;
			$params['comment'] = "account $username login";
			$answerData = $mt4request->MakeRequest("changebalance", $params);
			//var_dump($answerData);exit;
			if(mb_substr($answerData,0,4,'GBK') == '�������'){
				header('location:../index.php?error=�������');
				exit;
			}else if(mb_substr($answerData,0,4,'GBK') == '��ѯ�û�'){
				header('location:../index.php?error=�����ʺŲ����ڣ�����');
				exit;
			}else if($answerData == 'Fail!'){
				header('location:../index.php?error=��������');
				exit;
			}else if(mb_substr($answerData,0,3,'GBK') == '��֧��'){
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
		header('location:../index.php?error=�Ƿ�����');
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
    <title>�������</title>
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
        <h3>����ǩԼ֧�� - �������/����</h3>
        <form method="post" target="_blank" action="submitpay.php" id="yeepay_form">
            <table>
                <colgroup><col width="80"><col width="129"><col width="80"><col></colgroup>
                <tbody>
				<tr>            
                    <td class="tc">�û��ʺţ�</td>
                    <td colspan="3"><input style="width:118px;" type="text" id="txt_username" name="username" value="<?php echo $username;?>" readonly="readonly"><div style="display:inline;margin-left:10px;height:28px;">�˻���$<?php echo $balance?></div></td>
					
                </tr>
                <tr>
                    <td class="tc">�ࡡ���ͣ�</td>
                    <td colspan="3"><label class="tips"></label>
					<label><input type="radio" value="0" name="type" checked="checked">���</label>
					<label><input type="radio" value="1" name="type">ȡ��</label>
					<label><input type="radio" value="2" name="type" id="transfer">�ڲ�ת��</label>
					</td>
                </tr>
                <tr>
                    <td class="tc">�𡡡��</td>
                    <td colspan="3"><input type="text" id="txt_Amount" name="amount" maxlength="10" class="im" style="width:118px;"></td>        
                </tr>
                <tr>
                    <td class="tc">�������У�</td>
                    <td colspan="3">
                        <select id="slt_BankCode" name="bank" style="width:124px;">
							<option value="">-��ѡ��-</option>
                             <option value="ICBC">��������</option>
                            <option value="ABC">ũҵ����</option>
                            <option value="CCB">��������</option>
							<option value="CMB">��������</option>
							<option value="CMBC">��������</option>
							<option value="CITIC">��������</option>
							<option value="CITIC">��������</option>
							<option value="GZCB">��������</option>
							<option value="GZRCC">����ũ����ҵ����</option>
                            <option value="BOB">��������</option>
                            <option value="BCOM">��ͨ����</option>
                            <option value="CIB">��ҵ����</option>
                            <option value="NJCB">�Ͼ�����</option>
                            <option value="UPOP">��������֧��</option>
                            <option value="CEB">�������</option>
                            <option value="BOC">�й�����</option>
                            <option value="PAB">ƽ������</option>
                            <option value="CBHB">��������</option>
                            <option value="BEA">��������</option>
                            <option value="NBCB">��������</option>
                            <option value="SDB">���ڷ�չ����</option>
                            <option value="GDB">�㷢����</option>
                            <option value="SHB">�Ϻ�����</option>
                            <option value="SPDB">�ַ�����</option> 
                            <option value="POST">�й�����</option>
                            <option value="BJRCB">����ũ����ҵ����</option>
                            <option value="CZB">��������</option>
                            <option value="HZB">��������</option>
                            <option value="SRCB">�Ϻ�ũ����ҵ����</option> 
                            <option value="HSB">��������</option>
                            <option value="PSBC">�й�������������</option>                   
                        </select>
                    </td>
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
                    <td class="tt">������ע��</td>
                    <td colspan="3" style="padding:4px;"><textarea id="txt_Remark" name="remark"></textarea></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="3"><input type="button" id="doSubmit" value="ȷ��" onclick="submitOrder()"><input type="reset" id="chongzhi" value="����" onclick="$(&#39;#txt_Amount, #txt_Remark&#39;).val(&#39;&#39;)"></td>
                </tr>
            </tbody></table>
        </form>
        <a href="./quit.php" class="link">�˳���½</a>
		<p class="link">
		<font color="red">���ʣ�1��Ԫ(USD) = <?php echo file_get_contents('./conf/huilv.txt'); ?> �����(CNY)</font>
		</p>
		
    </div>
    <div id="glasslayer"></div>
    <div id="alter">
        <div>
            <b>ϵͳ��ʾ</b>
            <table>
                <tbody>
                    <tr>
                        <td id="tdMessage">�����´��������֧����Ȼ���ٵ����ȷ������ť</td>
                    </tr>
                    <tr><td style="height:30px;"></td></tr>
                    <tr>
                        <td style="height:42px; text-align:right;"><input type="button" value="ȷ��" onclick="location.href=location.href;"></td>
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
            $(this).parent().append('<span style="margin-left:6px;">��������</span>');
            return false;
        } else if (!patrn.test(amount)) {
            $(this).parent().append('<span style="margin-left:6px;">����ʽ����</span>');
            return false;
        } else if(type == 0 && amount < 300){
			$(this).parent().append('<span style="margin-left:6px;">������С��300Ԫ</span>');
            return false;
		} else if (type == 1 && parseFloat(amount) > parseFloat("<?php echo $balance?>")){
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

	$('#txt_username').blur(function(){
		$('table span').remove();
		var name=this.value.trim();
		if(name.length ==0 ){
			$(this).parent().append('<span style="margin-left:6px;">�����뽻���˺�</span>');
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
			$('#txt_username').parent().append('<span style="margin-left:6px;">�����뽻���˺�</span>');
            $('#txt_username').focus();
			return false;
		} else if (!p.test(username))
		{
			$('#txt_username').parent().append('<span style="margin-left:6px;">�����˺ű���Ϊ����</span>');
            $('#txt_username').focus();
			return false;
		}

		var beizhu = $('#txt_Remark').val().trim();

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
        } else if(type == 0 && amount < 300){
			$('#txt_Amount').parent().append('<span style="margin-left:6px;">������С��300Ԫ</span>');
            $('#txt_Amount').focus();
            return false;
		} else if (type == 1 && parseFloat(amount) > parseFloat("<?php echo $balance?>")){
            $('#txt_Amount').parent().append('<span style="margin-left:6px;">ȡ����ô����˻����</span>');
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

        var bank = $('#slt_BankCode').val();
        if (type == 0 && bank.length == 0) {
            $('#slt_BankCode').parent().append('<span style="margin-left:6px;">��ѡ������</span>');
            return false;
        }

        $('#doSubmit').attr('disabled', true);
        if (type == 0){
            $('#yeepay_form').submit();
            $('#glasslayer, #alter').show();
        } else if (type == 1){
			window.open('http://cbfinancials.com/cn/login.asp');
			
			$('#chongzhi').parent().append('<span style="margin-left:6px;">���ڴ����С���������رձ�����</span>');
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
                        $('#tdMessage').html('���߳������˳ɹ���<br />������ڵ�����ҳ���е�½�����ύ���γ���������Ϣ������ͷ���������<br /><font color=red>ע�⣺δ��½�����ύ���γ�����Ϣ���ύ��Ϣ�뱾�γ�����Ϣ����������������޷�������������</font>');
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