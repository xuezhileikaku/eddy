<?php
header("Content-type:text/html; charset=gbk"); 

//define('TEST',1);
define('RETURNURL','http://b2b.jyzbpme.com/pay/OrderReturn.php');
//�ύ��ַ
if(TEST == 1)
{
	$form_url = 'https://pay.ips.net.cn/ipayment.aspx'; //����
}
else
{
	$form_url = 'https://pay.ips.com.cn/ipayment.aspx'; //��ʽ
}


//�̻���
$Mer_code = '';

//�̻�֤�飺��½http://merchant.ips.com.cn/�̻���̨���ص��̻�֤������
$Mer_key = '';

//�̻��������
$Billno = date('YmdHis') . mt_rand(100000,999999);

//�������(����2λС��)
$Amount = number_format($_POST['amount'], 2, '.', '');

//��������
$Date = date('Ymd');

//����
$Currency_Type = 'RMB';

//֧������
$Gateway_Type = '01';

//����
$Lang = 'GB';

//֧������ɹ����ص��̻�URL
$Merchanturl = RETURNURL;

//֧�����ʧ�ܷ��ص��̻�URL
$FailUrl = RETURNURL;

//֧��������󷵻ص��̻�URL
$ErrorUrl = "";

//�̻����ݰ�
$Attach = $_POST['username'].'#'.$_POST['remark'];

//��ʾ���
//$DispAmount = $_POST['DispAmount'];

//����֧���ӿڼ��ܷ�ʽ
$OrderEncodeType = 5;

//���׷��ؽӿڼ��ܷ�ʽ 
$RetEncodeType = 17;

//���ط�ʽ
$Rettype = 1;

//Server to Server ����ҳ��URL
$ServerUrl = RETURNURL;
//OrderEncodeType����Ϊ5�����ڶ���֧���ӿڵ�Signmd5�ֶ��д��MD5ժҪ��֤��Ϣ��
//�����ύ�ӿ�MD5ժҪ��֤�����İ���ָ����������ֵ������������������֤��ͬʱƴ�ӵ������ַ���β������md5����֮����ת����Сд��������Ϣ���£�
//billno+��������š�+ currencytype +�����֡�+ amount +��������+ date +���������ڡ�+ orderencodetype +������֧���ӿڼ��ܷ�ʽ��+���̻��ڲ�֤���ַ�����
//��:(billno000001000123currencytypeRMBamount13.45date20031205orderencodetype5GDgLwwdK270Qj1w4xho8lyTpRQZV9Jm5x4NwWOTThUa4fMhEBK9jOXFrKRT6xhlJuU2FEa89ov0ryyjfJuuPkcGzO5CeVx5ZIrkkt1aBlZV36ySvHOMcNv8rncRiy3DQ)
//����֧���ӿڵ�Md5ժҪ��ԭ��=������+���+����+֧������+�̻�֤�� 
$orge = 'billno'.$Billno.'currencytype'.$Currency_Type.'amount'.$Amount.'date'.$Date.'orderencodetype'.$OrderEncodeType.$Mer_key ;
//echo '����:'.$orge ;
//$SignMD5 = md5('billno'.$Billno.'currencytype'.$Currency_Type.'amount'.$Amount.'date'.$Date.'orderencodetype'.$OrderEncodeType.$Mer_key);
$SignMD5 = md5($orge) ;
//echo '����:'.$SignMD5 ;
//sleep(20);
?>
<html>
  <head>
    <title>��ת......</title>
    <meta http-equiv="content-Type" content="text/html; charset=gb2312" />
  </head>
  <body>
    <form action="<?php echo $form_url ?>" method="post" id="frm1">
      <input type="hidden" name="Mer_code" value="<?php echo $Mer_code ?>">
      <input type="hidden" name="Billno" value="<?php echo $Billno ?>">
      <input type="hidden" name="Amount" value="<?php echo $Amount ?>" >
      <input type="hidden" name="Date" value="<?php echo $Date ?>">
      <input type="hidden" name="Currency_Type" value="<?php echo $Currency_Type ?>">
      <input type="hidden" name="Gateway_Type" value="<?php echo $Gateway_Type ?>">
      <input type="hidden" name="Lang" value="<?php echo $Lang ?>">
      <input type="hidden" name="Merchanturl" value="<?php echo $Merchanturl ?>">
      <input type="hidden" name="FailUrl" value="<?php echo $FailUrl ?>">
      <input type="hidden" name="ErrorUrl" value="<?php echo $ErrorUrl ?>">
      <input type="hidden" name="Attach" value="<?php echo $Attach ?>">
      <input type="hidden" name="OrderEncodeType" value="<?php echo $OrderEncodeType ?>">
      <input type="hidden" name="RetEncodeType" value="<?php echo $RetEncodeType ?>">
      <input type="hidden" name="Rettype" value="<?php echo $Rettype ?>">
      <input type="hidden" name="ServerUrl" value="<?php echo $ServerUrl ?>">
      <input type="hidden" name="SignMD5" value="<?php echo $SignMD5 ?>">
    </form>
    <script language="javascript">
      document.getElementById("frm1").submit();
    </script>
  </body>
</html>
