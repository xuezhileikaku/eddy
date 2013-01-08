<%@LANGUAGE="VBSCRIPT" CODEPAGE="65001"%>
<!--#include file="asp_md5.asp"-->
<%
        Response.CharSet = "utf-8"
        Sjt_MerchantID = request("Sjt_MerchantID")
	Sjt_Username = request("Sjt_Username")
	Sjt_TransID = request("Sjt_TransID")
	Sjt_Return = request("Sjt_Return")
	Sjt_Error = request("Sjt_Error")
	Sjt_factMoney = request("Sjt_factMoney")
	Sjt_SuccTime = request("Sjt_SuccTime")
	Sjt_BType = request("Sjt_BType")
	Sjt_Sign = request("Sjt_Sign")
	key = "632e0addbeb7e49fc4df95233e8e396b"    '密钥
	
	Sign = asp_md5(Sjt_MerchantID&Sjt_Username&Sjt_TransID&Sjt_Return&Sjt_Error&Sjt_factMoney&Sjt_SuccTime&Sjt_BType&key)
	
	if Sjt_Sign = Sign then
		if Sjt_BType = 1 then
		
	
		response.Write("入金成功！<br>")
	
		response.Write("订单号："&Sjt_TransID&"<br>")
	
		response.Write("入金时间"&Sjt_SuccTime)
		
		else
		     if Sjt_BType = 2 then
				 response.Write("ok")
			 end if	
		end if
		
	else
        response.Write("数据验证失败"&Sjt_Error)
		   
	end if
	
%>