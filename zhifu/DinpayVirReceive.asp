<%@LANGUAGE="VBSCRIPT" CODEPAGE="936"%>
<!--#include file="md5.asp"-->
<%
	m_id			=	request("m_id")
	m_orderid		=	request("m_orderid")
        m_oamount		=	request("m_oamount")
        m_ocurrency		=	request("m_ocurrency")
        m_url			=	request("m_url")
        m_language		=	request("m_language")
        s_name			=	request("s_name")
        s_addr			=	request("s_addr")
        s_postcode		=	request("s_postcode")
        s_tel			=	request("s_tel")
        s_eml			=	request("s_eml")
        r_name			=	request("r_name")
        r_addr			=	request("r_addr")
        r_postcode		=	request("r_postcode")
        r_eml			=	request("r_eml")
        r_tel			=	request("r_tel")
        m_ocomment		=	request("m_ocomment")
        m_status		=	request("m_status")
        modate			=	request("modate")
        newmd5info		=	request("newmd5info")
        
        key			=	"xxxxxxxx"     '<--支付密钥--> 注:此处密钥必须与商家后台里的密钥一致
		
	if request("newmd5info")="" then
		response.Write("认证签名为空值")
		response.end
	end if
	
	newOrderMessage = m_id&m_orderid&m_oamount&key&m_status
	
	newMd5text = trim(md5(newOrderMessage))		

	if Ucase(newMd5text)<>Ucase(newmd5info) then
			response.write("认证失败!!!")
	else
			if m_status = 2 then
				response.write	("认证成功，可以更新数据库!!!")		&	"<br>"
				Response.Write "m_id ="				&	m_id		&	"<br>"
				Response.Write "m_orderid ="		&	m_orderid	&	"<br>"
				Response.Write "m_oamount ="		&	m_oamount	&	"<br>"
				Response.Write "m_ocurrency ="		&	m_ocurrency &	"<br>"
				Response.Write "m_language ="		&	m_language	&	"<br>"
				Response.Write "s_name ="			&	s_name		&	"<br>"
				Response.Write "s_addr ="			&	s_addr		&	"<br>"
				Response.Write "s_postcode ="		&	s_postcode	&	"<br>"
				Response.Write "s_tel ="			&	s_tel		&	"<br>"
				Response.Write "s_eml ="			&	s_eml		&	"<br>"
				Response.Write "r_name ="			&	r_name		&	"<br>"
				Response.Write "r_addr ="			&	r_addr		&	"<br>"
				Response.Write "r_postcode ="		&	r_postcode	&	"<br>"
				Response.Write "r_eml ="			&	r_eml		&	"<br>"
				Response.Write "r_tel ="			&	r_tel		&	"<br>"
				Response.Write "m_ocomment ="		&	m_ocomment	&	"<br>"
				Response.Write "m_status ="			&	m_status	&	"<br>"
				Response.Write "modate ="			&	modate		&	"<br>"
				Response.Write "newmd5info="			&	newmd5info		&	"<br>"
			else
				Response.Write "支付失败"
			end if
	end if
%>

<!--
对于使用dinpay实时反馈接口的商户请注意：
    为了从根本上解决订单支付成功而商户收不到反馈信息的问题(简称掉单).
我公司决定在信息反馈方面实行服务器端对服务器端的反馈方式.即客户支付过后.
我们系统会对商户的网站进行两次支付信息的反馈(即对同一笔订单信息进行两次反馈).
第一次是服务器端对服务器端的反馈.第二次是以页面的形式反馈.两次反馈的时延差在10秒之内.
    请商户那边做好对我们反馈信息的处理. 对我们系统反馈相同的订单信息您那边只
    做一次处理就可以了.以确保消费者的每一笔订单信息在您那边只得到一次相应的服务!!
-->