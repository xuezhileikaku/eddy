<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- saved from url=(0033)http://pay.ybyzw.com/default.aspx -->
<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=gbk">
    <title>���߳����</title>
    <script type="text/javascript" src="./js/jquery.js"></script>
    <script type="text/javascript" src="./js/prototypes.js"></script>
    <link href="./css/main.css" rel="stylesheet" type="text/css">    
<style type="text/css"></style></head>
<body>
    <div>
        <h3>����֧�� - ���߳����</h3>
        <form method="post" target="_blank" action="pay/submitpay.php" id="yeepay_form">
            <table>
                <colgroup><col width="80"><col width="129"><col width="80"><col></colgroup>
                <tbody><tr>            
                    <td class="tc">�û��ʺţ�</td>
                    <td colspan="3"><input type="text" id="txt_username" name="username" value=""></td>
                </tr>
                <tr>
                    <td class="tc">�ࡡ���ͣ�</td>
                    <td colspan="3"><label class="tips"></label><label><input type="radio" value="0" name="type" checked="checked">���</label><label><input type="radio" value="1" name="type">ȡ��</label></td>
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
							<option value="ECITIC">��������</option>
							<option value="HXB">��������</option>
                            <option value="BCOM">��ͨ����</option>
                            <option value="CIB">��ҵ����</option>
                            <option value="CEBB">�������</option>
                            <option value="BOC">�й�����</option>
                            <option value="SPABANK">ƽ������</option>
                            <option value="BEA">��������</option>
                            <option value="SDB">���ڷ�չ����</option>
                            <option value="GDB">�㷢����</option>
                            <option value="SPDB">�ַ�����</option> 
                            <option value="PSBC">�й�����</option>
                            <option value="ZYC">���ο�</option>
                            <option value="CMPAY">�ֻ�֧��</option>                   
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="tt">������ע��</td>
                    <td colspan="3" style="padding:4px;"><textarea id="txt_Remark" name="remark"></textarea></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="3"><input type="button" id="doSubmit" value="ȷ��" onclick="submitOrder()"><input type="reset" value="����" onclick="$(&#39;#txt_Amount, #txt_Remark&#39;).val(&#39;&#39;)"></td>
                </tr>
            </tbody></table>
        </form>
        <a href="./index.php" class="link">ˢ�±�ҳ</a>
		<p class="link"><p>
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
        } else if (type == 1 && amount > 1000000){
            $(this).parent().append('<span style="margin-left:6px;">ȡ����ô���1000000</span>');
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
		if(username.length == 0){
			$('#txt_username').parent().append('<span style="margin-left:6px;">�����뽻���˺�</span>');
            $('#txt_username').focus();
			return false;
		}

		var bank = $('#slt_BankCode').val();
        if (type == 0 && bank.length == 0) {
            $('#slt_BankCode').parent().append('<span style="margin-left:6px;">��ѡ������</span>');
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
        } else if (type == 1 && amount > 1000000){
            $('#txt_Amount').parent().append('<span style="margin-left:6px;">ȡ����ô���1000000</span>');
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

		var beizhu = $('#txt_Remark').val().trim();
		if (type == 1 && beizhu.length == 0) {
            alert("���ڱ�ע�����뱾��ȡ��������Ϣ");
            return false;
        }

        $('#doSubmit').attr('disabled', true);
        if (type == 0){
            $('#yeepay_form').submit();
            $('#glasslayer, #alter').show();
        } else if (type == 1){
			$('#yeepay_form').parent().append('<span style="margin-left:6px;">���ڴ����С���������رձ�����</span>');
            $.ajax({
                type: 'post',
                url: './pay/qukuan.php',
				contentType:"application/x-www-form-urlencoded; charset=gbk",
                data: {
                    active: 'expelorder',
						user: username,
						amount: amount,
						beizhu: beizhu
                },
                success: function (r) {
                    if (r == 1) {
                        $('#tdMessage').html('ȡ�������ѵݽ��ɹ����ȴ�ϵͳȷ�ϴ���');
                        $('#glasslayer, #alter').show();
                    } else {
                        alert(r);
                        $('#doSubmit').attr('disabled', false);
                    }
                }
            });
        }
    }
    </script>
    


</body></html>