<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- saved from url=(0033)http://pay.ybyzw.com/default.aspx -->
<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=gbk">
    <title>�������</title>
    <script type="text/javascript" src="./js/jquery.js"></script>
    <script type="text/javascript" src="./js/prototypes.js"></script>
    <link href="./css/main.css" rel="stylesheet" type="text/css">    
<style type="text/css"></style></head>
<?php 

include('./config.php'); ?>
<body>
    <div>
        <h3>����֧��</h3>
        <form method="post" target="_blank" action="<?php echo $actionPayUrl; ?>" id="yeepay_form">
            <table>
                <colgroup><col width="80"><col width="129"><col width="80"><col></colgroup>
                <tbody><tr>            
                    <td class="tc">�û��ʺţ�</td>
                    <td colspan="3"><input type="text" id="txt_username" name="username" value=""></td>
                </tr>
                <tr>
                    <td class="tc">�ࡡ���ͣ�</td>
                    <td colspan="3"><label class="tips"></label><label><input type="radio" value="0" name="type" checked="checked">���</label></td>
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
                            <option value="ICBC-NET-B2C">��������</option>
                            <option value="CMBCHINA-NET-B2C">��������</option>
                            <option value="ABC-NET-B2C">�й�ũҵ����</option>
                            <option value="CCB-NET-B2C">��������</option>
                            <option value="BCCB-NET-B2C">��������</option>
                            <option value="BOCO-NET-B2C">��ͨ����</option>
                            <option value="CIB-NET-B2C">��ҵ����</option>
                            <option value="NJCB-NET-B2C">�Ͼ�����</option>
                            <option value="CMBC-NET-B2C">�й���������</option>
                            <option value="CEB-NET-B2C">�������</option>
                            <option value="BOC-NET-B2C">�й�����</option>
                            <option value="PINGANBANK-NET">ƽ������</option>
                            <option value="CBHB-NET-B2C">��������</option>
                            <option value="HKBEA-NET-B2C">��������</option>
                            <option value="NBCB-NET-B2C">��������</option>
                            <option value="SDB-NET-B2C">���ڷ�չ����</option>
                            <option value="GDB-NET-B2C">�㷢����</option>
                            <option value="SHB-NET-B2C">�Ϻ�����</option>
                            <option value="SPDB-NET-B2C">�Ϻ��ֶ���չ����</option> 
                            <option value="POST-NET-B2C">�й�����</option>
                            <option value="BJRCB-NET-B2C">����ũ����ҵ����</option>
                            <option value="CZ-NET-B2C">��������</option>
                            <option value="HZBANK-NET-B2C">��������</option>
                            <option value="SHRCB-NET-B2C">�Ϻ�ũ����ҵ����</option> 
                            <option value="NCBBANK-NET-B2C">������ҵ����</option>
                            <option value="SCCB-NET-B2C">�ӱ�����</option>                    
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
		<p class="link">ע�⣺�û��˺�Ϊ��Ľ����˻�ID���������д��ȷ�������������������˻����ܼ�ʱ���¡�<p>
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
        } else if (type == 1 && amount > 2.14){
            $(this).parent().append('<span style="margin-left:6px;">ȡ����ô��ڿ��ý��</span>');
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
        } else if (type == 1 && amount > 2.14){
            $('#txt_Amount').parent().append('<span style="margin-left:6px;">ȡ����ô��ڿ��ý��</span>');
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
            $.ajax({
                type: 'post',
                url: '/ajax/yeepay.ashx',
                data: {
                    active: 'expelorder',
                    amount: amount
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