<!--#include file="md5.asp"-->
<%
	'm_id		=	request("M_ID")        '<----�̼Һ�-------->
	'modate		=	request("MODate")      '<---������Ϊ�յ�--->
	'm_orderid	=	request("MOrderID")    '<----������-------->
    RANDOMIZE
    m_id        =   "2170219"
    modate      =   now()
    m_orderid   =   Year(now()) & Month(Now()) & Day(Now()) & Minute(now()) & Second(now()) & Int(Rnd()*(10000+1))
	m_oamount	=	request("MOAmount")    '<----�������------> 
	'm_ocurrency	=	request("MOCurrency")  '<----����-Ĭ��Ϊ1---->
    m_ocurrency	=	1
	'm_url		=	request("M_URL")       '<--���ص�ַ-����Ĭ��Ϊ�ղ�������-->
    m_url       =   "http://www.btnforex.com/zhifu/DinpayVirReceive.asp"
	'm_language	=	request("M_Language")   
    m_language	=	1
	s_name		=	request("S_Name")    	       
	s_addr		=	request("S_Address")	
	s_postcode	=	request("S_PostCode")     	    
	s_tel		=	request("S_Telephone")     			
	s_eml		=	request("S_Email")    	
	r_name		=	request("R_Name")    	
	r_addr		=	request("R_Address")	
	r_postcode	=	request("R_PostCode")     	
	r_tel		=	request("R_Telephone")     	
	r_eml		=	request("R_Email")	
	m_ocomment	=	request("MOComment")  		
	m_status	=	request("State")    	
	
	key		=	"SHANGHAI89_chenli37_shiyan83"   '<--֧����Կ--> ע:�˴���Կ�������̼Һ�̨�����Կһ��
	
	OrderMessage =m_id&m_orderid&m_oamount&m_ocurrency&m_url&m_language&s_postcode&s_tel&s_eml&r_postcode&r_tel&r_eml&modate&key

	'response.write "�������Ķ�����ϢΪ��" & OrderMessage & "<br>"
	
	digest = Ucase(trim(md5(OrderMessage)))
	
	'Response.Write "������֤Ϊ��" & digest
	
%>
<html>
<body onload="document.FORM.submit();">
	<form name = "FORM" method="post" action="https://payment.dinpay.com/VirReceiveMerchantAction.do">
	        <input Type="hidden" Name="M_ID" value="<%=m_id%>">
		<input Type="hidden" Name="MOrderID" value="<%=m_orderid%>">
		<input Type="hidden" Name="MOAmount" value="<%=m_oamount%>">
		<input Type="hidden" Name="MOCurrency" value="<%=m_ocurrency%>">
		<input Type="hidden" name="M_URL" value="<%=m_url%>">
		<input Type="hidden" Name="M_Language" value="<%=m_language%>">
		<input Type="hidden" Name="S_Name" value="<%=s_name%>">
		<input Type="hidden" Name="S_Address" value="<%=s_addr%>">
	        <input Type="hidden" Name="S_PostCode" value="<%=s_postcode%>">
		<input Type="hidden" Name="S_Telephone" value="<%=s_tel%>">
		<input Type="hidden" Name="S_Email" value="<%=s_eml%>">
		<input Type="hidden" Name="R_Name" value="<%=r_name%>"> 
		<input Type="hidden" Name="R_Address" value="<%=r_addr%>">
		<input Type="hidden" Name="R_PostCode" value="<%=r_postcode%>">
		<input Type="hidden" Name="R_Telephone" value="<%=r_tel%>">
		<input Type="hidden" Name="R_Email" value="<%=r_eml%>">
		<input Type="hidden" name="MOComment" value="<%=m_ocomment%>">
		<input Type="hidden" Name="MODate" value="<%=modate%>">
		<input Type="hidden" Name="State" value="<%=m_status%>">
		<input Type="hidden" Name="digestinfo" value="<%=digest%>">
	</form>

</body>
</html>



