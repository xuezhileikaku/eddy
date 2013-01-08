<%@LANGUAGE="VBSCRIPT" CODEPAGE="65001"%>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!--#include file="asp_md5.asp"-->
<%

      p0_Cmd = request("p0_Cmd")
	  
	  p2_Order = request("p2_Order")
	  
	  p3_Amt = request("p3_Amt")
	  
	  p4_Cur = request("p4_Cur")
	  
	  p5_Pid = request("p5_Pid")
	  
	  p6_Pcat = request("p6_Pcat")
	  
	  p7_Pdesc = request("p7_Pdesc")
	  
	  p8_Url = request("p8_Url")
	  
	  p9_SAF = request("p9_SAF")
	  
	  pa_MP = request("pa_MP")
	  
	  pd_FrpId = request("pd_FrpId")
	  
	  pr_NeedResponse = request("pr_NeedResponse")
	  
	  Sjt_Paytype = request("Sjt_Paytype")
	  
	  Sjt_UserName = request("Sjt_UserName")
	  
	  key = "632e0addbeb7e49fc4df95233e8eXXXX"   '密钥
	  
	  p1_MerId = "10320"   '商户编号
	  
	  hmacstr = p0_Cmd&p1_MerId&p2_Order&p3_Amt&p4_Cur&p5_Pid&p6_Pcat&p7_Pdesc&p8_Url&p9_SAF&pa_MP&pd_FrpId&pr_NeedResponse&key

	  
	  hmac = asp_md5(hmacstr)


%>

<form name="Form1" id="Form1" method="post" action="http://cs.sj887.com/Payapi_Index_Pay.html">
<input type="hidden" name="p0_Cmd" value="<%=p0_Cmd%>">
<input type="hidden" name="p1_MerId" value="<%=p1_MerId%>">
<input type="hidden" name="p2_Order" value="<%=p2_Order%>">
<input type="hidden" name="p3_Amt" value="<%=p3_Amt%>">
<input type="hidden" name="p4_Cur" value="<%=p4_Cur%>">
<input type="hidden" name="p5_Pid" value="<%=p5_Pid%>">
<input type="hidden" name="p6_Pcat" value="<%=p6_Pcat%>">
<input type="hidden" name="p7_Pdesc" value="<%=p7_Pdesc%>">
<input type="hidden" name="p8_Url" value="<%=p8_Url%>">
<input type="hidden" name="p9_SAF" value="<%=p9_SAF%>">
<input type="hidden" name="pa_MP" value="<%=pa_MP%>">
<input type="hidden" name="pd_FrpId" value="<%=pd_FrpId%>">
<input type="hidden" name="pr_NeedResponse" value="<%=pr_NeedResponse%>">
<input type="hidden" name="Sjt_Paytype" value="<%=Sjt_Paytype%>">
<input type="hidden" name="Sjt_UserName" value="<%=Sjt_UserName%>" >
<input type="hidden" name="hmac" value="<%=hmac%>">
<input type="submit" value="提 交">
</form>
<script type="text/javascript">
document.forms["Form1"].submit();
</script>