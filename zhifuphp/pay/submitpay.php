<?php
error_reporting(0);
         // ������Ϣ���ܺ���
        function strToHex($string)
        {
             $hex="";
             for ($i=0;$i<strlen($string);$i++)
                 $hex.=dechex(ord($string[$i]));
             $hex=strtoupper($hex);
             return $hex;
        }

	$m_id		=	'2220112';
	$m_orderid	=	date('YmdHis') . mt_rand(0,10000);
	$m_oamount	=	$_POST['amount'];
	$m_ocurrency   = '1';
	$m_url		=	'http://zt-gold.com/zf/pay/callback.php'; //���ص�ַ ��Ҫ
	$m_language	=	'1';
	$s_name		=	$_POST['username'];
	$s_addr		=	'';
	$s_postcode	=	'';
	$s_tel		=	'';
	$s_eml		=	'';
	$r_name		=	'';
	$r_addr		=	'';
	$r_postcode	=	'';
	$r_tel		=	'';
	$r_eml		=	'';
	$m_ocomment	=	$_POST['remark'];
	$modate		=	date('Y-m-d H:i:s');
	$m_status	= 	0;
	$pBank      =   $_POST['bank'];

	//��֯������Ϣ
	$m_info = $m_id."|".$m_orderid."|".$m_oamount."|".$m_ocurrency."|".$m_url."|".$m_language;
	$s_info = $s_name."|".$s_addr."|".$s_postcode."|".$s_tel."|".$s_eml;
	$r_info = $r_name."|".$r_addr."|".$r_postcode."|".$r_tel."|".$r_eml."|".$m_ocomment."|".$m_status."|".$modate;

	$orderInfo = $m_info."|".$s_info."|".$r_info;

	//��ӡ��ɵ���Ϣ
	//echo $orderInfo;

	//������Ϣ����
	$key = '';     //<--֧����Կ--> ע:�˴���Կ�������̼Һ�̨�����Կһ��
	$orderInfo = strToHex($orderInfo);
	//����ǩ��
	$digest = strtoupper(md5($orderInfo.$key));
?>

<html>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=gb2312'>
</head>
<body onload='document.dinpayForm.submit();'>
<form name='dinpayForm' method='post' action='https://payment.dinpay.com/PHPReceiveMerchantAction.do'>
	<input type='hidden' name='OrderMessage' value='<?echo $orderInfo?>'>
	<input type='hidden' name='digest' value='<?echo $digest?>'>
	<input type='hidden' name='M_ID' value='<?echo $m_id?>'>
	<input type='hidden' name='P_Bank' value='<?echo $pBank?>' />
</form>
</body>
</html>