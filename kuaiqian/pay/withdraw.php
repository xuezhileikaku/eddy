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
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=gbk">
        <title>���߳���</title>
        <script type="text/javascript" src="../js/jquery.js"></script>
        <script type="text/javascript" src="../js/prototypes.js"></script>
        <link href="../css/main.css" rel="stylesheet" type="text/css">    
        <style type="text/css">
            .attention{
                color:red;
                font-size:13px;
                margin:5px auto;
            }
            body div{
                padding: 0;
            }
        </style>
    </head>

    <body>
        <div>
            <h3>���߳���</h3>
            <form method="post" action="doWithdraw.php" id="yeepay_form">
                <table>
                    <colgroup><col width="130"></colgroup>
                    <tbody>
    				<tr>            
                        <td class="tc">ȡ����ʺţ�</td>
                        <td colspan="3"><input  style="width:118px;" type="text" id="from_username" name="from_username" readonly="readonly" value="<?php echo $username ?>"><div style="display:inline;margin-left:10px;height:28px;">�˻���$<?php echo $balance?></td>
                    </tr>
                    <tr>
                        <td class="tc">����USD ��Ԫ����</td>
                        <td colspan="3"><input type="text" id="txt_Amount" name="amount" maxlength="10" class="im" style="width:118px;"></td>        
                    </tr>
    				<tr>            
                        <td class="tc">�տ���������</td>
                        <td colspan="3"><input  style="width:118px;" type="text" id="to_username" name="to_username" value="" maxlength="8"></td>
                    </tr>
                    <tr>            
                        <td class="tc">�տ��������ʺţ�</td>
                        <td colspan="3"><input  style="width:160px;" type="text" id="bankcode" name="bankcode" value="" maxlength="19"></td>
                    </tr>
    				<tr>            
                        <td class="tc">�տ��˻����������ƣ�</td>
                        <td colspan="3"><input  style="width:260px;" type="text" id="bankname" name="bankname" value="" maxlength="100"></td>
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
                        <td colspan="3"><input type="button" id="doSubmit" value="ȡ��" onclick="submitOrder()"><input  id="chongzhi" type="reset" value="����" onclick="$(&#39;#txt_Amount, #txt_Remark&#39;).val(&#39;&#39;)"><span><?php echo isset($_GET['error']) ? $_GET['error'] : date('Y-m-d'); ?></span></td>
                    </tr>
                </tbody></table>
            </form>
            <a href="./rujin.php" class="link">������ҳ</a>
    		<p class="attention">
            �տ��˻����������Ʊ��밴�涨��ʽ��д��ϸ�����������ʽ��XX����XXʡ����XX��XX֧�С����磺<i>�й�</i>����<i>����</i>ʡ����<i>��ɳ</i>��<i>����</i>֧��
            <br />
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
                        <td style="height:42px; text-align:right;"><input type="button" value="ȷ��" onclick="hideMe()"></td>
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

    	$('#to_username').blur(function(){
    		$('table span').remove();
    		var name=this.value.trim();
    		if(name.length ==0 ){
    			$(this).parent().append('<span style="margin-left:6px;">����������</span>');
                return false;
    		}
    	});

    	$('#bankcode').blur(function(){
    		$('table span').remove();
    		var name=this.value.trim();
            var p =/^\d+$/;
    		if(name.length ==0 ){
    			$(this).parent().append('<span>�����������ʺ�</span>');
                return false;
    		} else if (!p.test(name))
            {
                $('#bankcode').parent().append('<span style="margin-left:6px;">����Ϊ����</span>');
                $('#bankcode').focus();
                return false;
            }
    	});

        $('#bankname').blur(function(){
            $('table font').remove();
            var name=this.value.trim();
            if(name.length ==0 ){
                $(this).parent().append('<font color="red">*</font>');
                return false;
            }
        });



        function submitOrder() {
            $('table span').remove();

    		var username = $('#from_username').val().trim();
    		var p =/^\d+$/;
    		if(username.length == 0){
    			$('#from_username').parent().append('<span style="margin-left:6px;">�����뽻���˺�</span>');
                $('#from_username').focus();
    			return false;
    		} else if (!p.test(username))
    		{
    			$('#from_username').parent().append('<span style="margin-left:6px;">�����˺ű���Ϊ����</span>');
                $('#from_username').focus();
    			return false;
    		}

    		var to_username = $('#to_username').val().trim();
    		if(to_username.length == 0){
    			$('#to_username').parent().append('<span style="margin-left:6px;">����������</span>');
                $('#to_username').focus();
    			return false;
    		}

            var bankcode = $('#bankcode').val().trim();
            var p =/^\d+$/;
            if(bankcode.length == 0){
                $('#bankcode').parent().append('<span>�����������ʺ�</span>');
                $('#bankcode').focus();
                return false;
            } else if (!p.test(bankcode))
            {
                $('#bankcode').parent().append('<span style="margin-left:6px;">����Ϊ����</span>');
                $('#bankcode').focus();
                return false;
            }

            var bankname = $('#bankname').val().trim();
            if(bankname.length == 0){
                $('#bankname').parent().append('<font color="red">*</font>');
                $('#bankname').focus();
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

            var server = $('#slt_ServerCode').val().trim();
            $('#doSubmit').attr('disabled', true);
            $('#doSubmit').parent().append('<span style="margin-left:6px;">�����У�����رմ���</span>');
            //$('#yeepay_form').submit();
            $.ajax({
                type: 'post',
                url: './doWithdraw.php',
                contentType:"application/x-www-form-urlencoded; charset=gbk",
                data: {
                    active: 'expelorder',
                    user: username,
                    amount: amount,
                    to_username: to_username,
                    bankcode: bankcode,
                    bankname: bankname,
                    server: server
                },
                success: function (r) {
                    if (r == 1) {
                        $('#tdMessage').html('���߳������˳ɹ���<br />����2���������ڲ�ѯ�Ƿ��յ��������κ����ʣ�����ϵ�ٷ��ͷ���');
                        $('#glasslayer, #alter').show();
                    } else {
                        $('#tdMessage').html(r);
                        $('#glasslayer, #alter').show();
                        $('#doSubmit').attr('disabled', false);
                    }
                }
            });
        }

        function hideMe(){
            $("#glasslayer, #alter").hide();
            $('#doSubmit').attr('disabled', false);
			$('table span').remove();
			var str = $('#tdMessage').html();
			if(str.indexOf("�ɹ�")!=-1){
				//$("#chongzhi").click();
				location=location;
			}
        }
        </script>
    </body>
</html>