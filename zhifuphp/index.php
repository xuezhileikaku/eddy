<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- saved from url=(0033)http://pay.ybyzw.com/default.aspx -->
<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=gbk">
    <title>在线出入金</title>
    <script type="text/javascript" src="./js/jquery.js"></script>
    <script type="text/javascript" src="./js/prototypes.js"></script>
    <link href="./css/main.css" rel="stylesheet" type="text/css">    
<style type="text/css"></style></head>
<body>
    <div>
        <h3>网银支付 - 在线出入金</h3>
        <form method="post" target="_blank" action="pay/submitpay.php" id="yeepay_form">
            <table>
                <colgroup><col width="80"><col width="129"><col width="80"><col></colgroup>
                <tbody><tr>            
                    <td class="tc">用户帐号：</td>
                    <td colspan="3"><input type="text" id="txt_username" name="username" value=""></td>
                </tr>
                <tr>
                    <td class="tc">类　　型：</td>
                    <td colspan="3"><label class="tips"></label><label><input type="radio" value="0" name="type" checked="checked">存款</label><label><input type="radio" value="1" name="type">取款</label></td>
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
							<option value="ECITIC">中信银行</option>
							<option value="HXB">华夏银行</option>
                            <option value="BCOM">交通银行</option>
                            <option value="CIB">兴业银行</option>
                            <option value="CEBB">光大银行</option>
                            <option value="BOC">中国银行</option>
                            <option value="SPABANK">平安银行</option>
                            <option value="BEA">东亚银行</option>
                            <option value="SDB">深圳发展银行</option>
                            <option value="GDB">广发银行</option>
                            <option value="SPDB">浦发银行</option> 
                            <option value="PSBC">中国邮政</option>
                            <option value="ZYC">智游卡</option>
                            <option value="CMPAY">手机支付</option>                   
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="tt">备　　注：</td>
                    <td colspan="3" style="padding:4px;"><textarea id="txt_Remark" name="remark"></textarea></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="3"><input type="button" id="doSubmit" value="确定" onclick="submitOrder()"><input type="reset" value="重置" onclick="$(&#39;#txt_Amount, #txt_Remark&#39;).val(&#39;&#39;)"></td>
                </tr>
            </tbody></table>
        </form>
        <a href="./index.php" class="link">刷新本页</a>
		<p class="link"><p>
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
        } else if (type == 1 && amount > 1000000){
            $(this).parent().append('<span style="margin-left:6px;">取款金额不得大于1000000</span>');
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
		if(username.length == 0){
			$('#txt_username').parent().append('<span style="margin-left:6px;">请输入交易账号</span>');
            $('#txt_username').focus();
			return false;
		}

		var bank = $('#slt_BankCode').val();
        if (type == 0 && bank.length == 0) {
            $('#slt_BankCode').parent().append('<span style="margin-left:6px;">请选择银行</span>');
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
        } else if (type == 1 && amount > 1000000){
            $('#txt_Amount').parent().append('<span style="margin-left:6px;">取款金额不得大于1000000</span>');
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

		var beizhu = $('#txt_Remark').val().trim();
		if (type == 1 && beizhu.length == 0) {
            alert("请在备注栏输入本次取款的相关信息");
            return false;
        }

        $('#doSubmit').attr('disabled', true);
        if (type == 0){
            $('#yeepay_form').submit();
            $('#glasslayer, #alter').show();
        } else if (type == 1){
			$('#yeepay_form').parent().append('<span style="margin-left:6px;">正在处理中。。。请勿关闭本窗口</span>');
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
                        $('#tdMessage').html('取款申请已递交成功，等待系统确认处理');
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