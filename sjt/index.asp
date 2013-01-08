<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Language" content="zh-cn" />
<title>在线入金-波士顿金融集团（BTN LTD)</title>
<style type="text/css">
body{
	width:100%;
	height:100%;
	margin:0px auto;
background:url(/images/bj1.jpg) repeat 0 0;
	}
#title{
	width:700px;
	border:solid 1px #E5B31F;
	font-weight:100;
	margin:0px auto;
	margin-top:50px;
	}	
#tab{
	width:700px;
	height:auto;
	margin:0px auto;

	border-left:1px solid #666;
	border-top:1px solid #666;
	}

.hid{
	display:none;
}
</style>

<script type="text/javascript">
function checkForm(oForm){
	var name = oForm.p5_Pid.value;
	var account = oForm.Sjt_UserName.value;
	var amount = oForm.p3_Amt.value;
	var phone = oForm.p7_Pdesc.value;
	var email = oForm.p6_Pcat.value;

	if(name=="" || account==""  || amount=="" || phone=="" || email=="" ){
		alert("信息填写不完整，请检查！");
		return  false;
	}else{
		return true;
	}
}
</script>

</head>

<body>
<div id="title">
<form name="Form1" id="Form1" method="post" action="bankpay.asp" onsubmit="return checkForm(this)">
<table width="100%" border="0" align="center" cellpadding="5" cellspacing="1" id="tab">
<tr>
<td height="20" colspan="2" bgcolor="#CEE7BD">在线入金</td>
</tr>

<tr class="hid">
<td>业务类型</td>
<td><input type="text" name="p0_Cmd" value="Buy"></td>
</tr>

<tr class="hid">
<td>商户订单号</td>
<td><input type="text" name="p2_Order" value="">&nbsp;&nbsp;<span style="color:#F00;">如果为空自动生成，建议为空</span></td>
</tr>

<!--start-->
<tr>
<td>帐户姓名</td>
<td><input size="30" type="text" name="p5_Pid" value="">&nbsp;<span style="color:#FF0000;font-weight:100;">*(请填写您的姓名)</span></td>
</tr>

<tr>
<td>交易账户</td>
<td><input size="30" type="text" name="Sjt_UserName" value="">&nbsp;<span style="color:#FF0000;font-weight:100;">*(请填写您的交易账号)</span></td>
</tr>

<tr>
<td>入金金额</td>
<td><input size="30" type="text" name="p3_Amt" value="">&nbsp;<span style="color:#FF0000;font-weight:100;">*(单位：元,币种：人民币)</span></td>
</tr>

<tr>
<td>联系电话</td>
<td><input size="30" type="text" name="p7_Pdesc" value="">&nbsp;<span style="color:#FF0000;font-weight:100;">*(重要，以便我们联系您确认信息的真实情况)</span></td>
</tr>

<tr>
<td>联系邮箱</td>
<td><input size="30" type="text" name="p6_Pcat" value="">&nbsp;<span style="color:#FF0000;font-weight:100;">*(重要，以便我们联系您确认信息的真实情况)</span></td>
</tr>
<!--end-->

<tr class="hid">
<td>交易币种</td>
<td><input type="text" name="p4_Cur" value="CNY"></td>
</tr>

<tr class="hid">
<td>通知商户地址</td>
<td><input type="text" name="p8_Url" value="http://www.btnfx.com/sjt/merchant_url.asp"></td>
</tr>

<tr class="hid">
<td>送货地址</td>
<td><input type="text" name="p9_SAF" value=""></td>
</tr>

<tr>
<td>支付渠道</td>
<td>
<select name="pd_FrpId">
		<option value="zsyh">招商银行</option>
		<option value="gsyh">工商银行</option>
		<option value="jsyh">建设银行</option>
		<option value="shpdfzyh">浦发银行</option>
		<option value="nyyh">农业银行</option>
		<option value="msyh">民生银行</option>
		<option value="szfzyh">深圳发展银行</option>
		<option value="xyyh">兴业银行</option>
		<option value="jtyh">交通银行</option>
		<option value="gdyh">光大银行</option>
		<option value="zgyh">中国银行</option>
		<option value="payh">平安银行</option>
		<option value="gfyh">广发银行</option>
		<option value="zxyh">中信银行</option>
        	<option value="nbyh">宁波银行</option>
		<option value="fdyh">富滇银行</option>
      </select>
</td>
</tr>

<tr class="hid">
<td>应答机制</td>
<td><input type="text" name="pr_NeedResponse" value="1"></td>
</tr>

<tr class="hid">
<td>支付类型</td>
<td><input type="text" name="Sjt_Paytype" value="b"></td>
</tr>


<tr><td colspan="2" align="center">
<input type="submit" value="马上支付" >&nbsp;&nbsp;
<input type="reset" value="重置表单">
</td></tr>
</table>
</form>

</div>
</body>
</html>
