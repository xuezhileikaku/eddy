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
        
        key			=	"xxxxxxxx"     '<--֧����Կ--> ע:�˴���Կ�������̼Һ�̨�����Կһ��
		
	if request("newmd5info")="" then
		response.Write("��֤ǩ��Ϊ��ֵ")
		response.end
	end if
	
	newOrderMessage = m_id&m_orderid&m_oamount&key&m_status
	
	newMd5text = trim(md5(newOrderMessage))		

	if Ucase(newMd5text)<>Ucase(newmd5info) then
			response.write("��֤ʧ��!!!")
	else
			if m_status = 2 then
				response.write	("��֤�ɹ������Ը������ݿ�!!!")		&	"<br>"
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
				Response.Write "֧��ʧ��"
			end if
	end if
%>

<!--
����ʹ��dinpayʵʱ�����ӿڵ��̻���ע�⣺
    Ϊ�˴Ӹ����Ͻ������֧���ɹ����̻��ղ���������Ϣ������(��Ƶ���).
�ҹ�˾��������Ϣ��������ʵ�з������˶Է������˵ķ�����ʽ.���ͻ�֧������.
����ϵͳ����̻�����վ��������֧����Ϣ�ķ���(����ͬһ�ʶ�����Ϣ�������η���).
��һ���Ƿ������˶Է������˵ķ���.�ڶ�������ҳ�����ʽ����.���η�����ʱ�Ӳ���10��֮��.
    ���̻��Ǳ����ö����Ƿ�����Ϣ�Ĵ���. ������ϵͳ������ͬ�Ķ�����Ϣ���Ǳ�ֻ
    ��һ�δ���Ϳ�����.��ȷ�������ߵ�ÿһ�ʶ�����Ϣ�����Ǳ�ֻ�õ�һ����Ӧ�ķ���!!
-->