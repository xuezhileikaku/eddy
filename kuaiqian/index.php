<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- saved from url=(0033)http://pay.ybyzw.com/default.aspx -->
<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=gbk">
    <title>�������</title>
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
        <h3>����ǩԼ����֧�� - �û���½</h3>
        <form method="post" action="pay/rujin.php" id="yeepay_form">
            <table>
                <colgroup><col width="80"><col width="129"><col width="80"><col></colgroup>
                <tbody>
				<tr>            
                    <td class="tc">�����ʺţ�</td>
                    <td colspan="3"><input type="text" id="txt_username" name="username" value=""></td>
                </tr>
				<tr>            
                    <td class="tc">��½���룺</td>
                    <td colspan="3"><input type="password" id="txt_password" name="password" value=""></td>
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
                    <td colspan="3"><input type="button" id="doSubmit" value="��½" onclick="submitOrder()"><input type="reset" value="����" onclick="$(&#39;#txt_Amount, #txt_Remark&#39;).val(&#39;&#39;)"><span><?php echo isset($_GET['error']) ? $_GET['error'] : '��ǰ���ڣ�'.date('Y-m-d'); ?></span></td>
                </tr>
            </tbody></table>
        </form>
        <a href="./index.php" class="link">ˢ�±�ҳ</a>
		<p class="attention">ע�⣺Ϊ��֤�ͻ��ʽ��ܼ�ʱ׼ȷ���ˣ��������ǰ�������Ľ����ʺź�������е�½��֤��
		</p>
		<p class="attention">
		1���������Ҫ�������������߳������ô�������������ʺŵ�½��<br />
		2���������Ҫ�ڲ�ת�ˣ�����ת���ʽ����ʺŵ�½��
		</p>
		
    </div>
    
    <script type="text/javascript">

	$('#txt_username').blur(function(){
		$('table span').remove();
		var name=this.value.trim();
		if(name.length ==0 ){
			$(this).parent().append('<span style="margin-left:6px;">�����뽻���˺�</span>');
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

		var password = $('#txt_password').val().trim();
		if(password.length == 0){
			$('#txt_password').parent().append('<span style="margin-left:6px;">�������½����</span>');
            $('#txt_password').focus();
			return false;
		}

        $('#yeepay_form').submit();
    }
    </script>
</body></html>