<?php
header("Content-type:text/html; charset=gbk"); 
//error_reporting(E_ALL);
include 'mt.php';
require './lib/class.phpmailer.php';
//----------------------------------------------------
//  ��������
//  Receive the data
//----------------------------------------------------
$billno = $_GET['billno'];
$amount = $_GET['amount'];
$mydate = $_GET['date'];
$succ = $_GET['succ'];
$msg = $_GET['msg'];
$attach = $_GET['attach'];
$ipsbillno = $_GET['ipsbillno'];
$retEncodeType = $_GET['retencodetype'];
$currency_type = $_GET['Currency_type'];
$signature = $_GET['signature'];

$ar = explode('#',$attach);
$r5_Pid = isset($ar[0])?trim($ar[0]):'';
//'----------------------------------------------------
//'   Md5ժҪ��֤
//'   verify  md5
//'----------------------------------------------------

//RetEncodeType����Ϊ17��MD5ժҪ����ǩ����ʽ��
//���׷��ؽӿ�MD5ժҪ��֤��������Ϣ���£�
//billno+��������š�+currencytype+�����֡�+amount+��������+date+���������ڡ�+succ+���ɹ���־��+ipsbillno+��IPS������š�+retencodetype +�����׷���ǩ����ʽ��+���̻��ڲ�֤�顿
//��:(billno000001000123currencytypeRMBamount13.45date20031205succYipsbillnoNT2012082781196443retencodetype17GDgLwwdK270Qj1w4xho8lyTpRQZV9Jm5x4NwWOTThUa4fMhEBK9jOXFrKRT6xhlJuU2FEa89ov0ryyjfJuuPkcGzO5CeVx5ZIrkkt1aBlZV36ySvHOMcNv8rncRiy3DQ)

//���ز����Ĵ���Ϊ��
//billno + mercode + amount + date + succ + msg + ipsbillno + Currecny_type + retencodetype + attach + signature + bankbillno
//ע2����RetEncodeType=17ʱ��ժҪ������ȫת��Сд�ַ���������֤��ʱ�������ɵ�Md5ժҪ��ת��Сд�������Ƚ�
$content = 'billno'.$billno.'currencytype'.$currency_type.'amount'.$amount.'date'.$mydate.'succ'.$succ.'ipsbillno'.$ipsbillno.'retencodetype'.$retEncodeType;
//���ڸ��ֶ��з����̻���½merchant.ips.com.cn���ص�֤��
$cert = '533452902246130271367731867811846929080294801757909837579268418039910128016283000991201945082188686173778172038515741783897XXXXX';
$signature_1ocal = md5($content . $cert);
//print_r($_GET);
if ($signature_1ocal == $signature)
{
	//----------------------------------------------------
	//  �жϽ����Ƿ�ɹ�
	//  See the successful flag of this transaction
	//----------------------------------------------------
	if ($succ == 'Y')
	{
		/**----------------------------------------------------
		*�ȽϷ��صĶ����źͽ���������ݿ��еĽ���Ƿ����
		*compare the billno and amount from ips with the data recorded in your datebase
		*----------------------------------------------------
		
		if(����)
			echo "��IPS���ص����ݺͱ��ؼ�¼�Ĳ����ϣ�ʧ�ܣ�"
			exit
		else
			'----------------------------------------------------
			'���׳ɹ��������������ݿ�
			'The transaction is successful. update your database.
			'----------------------------------------------------
		end if
		**/
		echo '���׳ɹ�';
		echo  "<br />����֧��ҳ�淵�أ���鿴�����˻��Ƿ������ɹ����������ʣ�����ϵ400-006-8599<br />";

			//ҵ���߼�����
			//��鶩���Ƿ��Ѵ�����ֹ�ظ����
            $conn = mysql_connect('127.0.0.1','root','');//��������
            mysql_select_db('yeepay');
            mysql_query('set names gbk');
			//$sqlLock = 'LOCK TABLES jyzbpme WRITE';
			//mysql_query($sqlLock);
            $sql = "select * from jyzbpme where orderNum = '" . trim($ipsbillno) . "'";
            $result = mysql_query($sql);
            $row = mysql_fetch_array($result);
            if($row){
                //�����Ѵ����˳�
                mysql_close($conn);
				file_put_contents('log.txt','�����Ѵ����˳�',FILE_APPEND);
                exit();
            }
			
			//��������д�����ݿ�
			
			$sql = "insert into jyzbpme values (0 , '" . trim($ipsbillno) . "')";
			$r = mysql_query($sql);
			if(!$r){
				$status .="������Ϣд�����ݿ�ʧ�ܣ����ܻ�����ظ��������";
				file_put_contents('log.txt',$status,FILE_APPEND);
				
			}
			

			//$sqlLock = 'UNLOCK TABLES';
			//mysql_query($sqlLock);
			mysql_close($conn);
            
            //��MT4������ͨ��
			$encryptionKey = "asfas1";
			$secretHash = "fsdvgfygfsddsag";
			$status = "";

			$mt4request = new CMT4DataReciver;
			////////////////////////////////////////////////////////////////////////////////////////////
			$connResult = $mt4request->OpenConnection(SERVER_ADDRESS, SERVER_PORT);
			if($connResult==-1){
				//echo '��MT4������ͨ��ʧ�ܣ��������δ�������˻�������ϵ����Ա�ֶ�����','<br />';
				$status .= "��MT4������ͨ��ʧ�ܣ��������δ�������˻������ֶ�����\r\n";
			}else{	
				//�����˻����
				$params['login'] = $r5_Pid;
				$params['value'] = $amount;
				$params['comment'] = "change account balance from yeepay";
				$answerData = $mt4request->MakeRequest("changebalance", $params);
				if($answerData == 'Fail!'){
					//echo '���½����˻����ʧ�ܣ�����ϵ����Ա�ֶ�����','<br />';
					$status .= "���½����˻����ʧ�ܣ��������δ�������˻������ֶ�����\r\n";
				}else{
					//echo '���½����˻����ɹ����������$'.$r3_Amt.'<br />';
					$status .= "���½����˻����ɹ���\r\n";
				}
				$mt4request->CloseConnection();
			}

			//�����ʼ�֪ͨ
			try {
				$mail = new PHPMailer(true); 
				$mail->IsSMTP();
				$mail->CharSet='GBK'; //�����ʼ����ַ����룬�����Ҫ����Ȼ��������
				$mail->SMTPAuth   = true;                  //������֤
				$mail->Port       = 25;                    
				$mail->Host       = "smtp.163.com"; 
				$mail->Username   = "yiyiyitest@163.com";    
				$mail->Password   = "";//��������     
				//$mail->IsSendmail(); //���û��sendmail�����ע�͵���������֡�Could  not execute: /var/qmail/bin/sendmail ���Ĵ�����ʾ
				$mail->AddReplyTo("yiyiyitest@163.com","Admin");//�ظ���ַ
				$mail->From       = "yiyiyitest@163.com";
				$mail->FromName   = "Admin";
				$to = "623165581@qq.com";
				$mail->AddAddress($to);
				$mail->AddBCC("eddy@rrgod.com");
				$mail->Subject  = "�������֪ͨ";
				//���͵�����
				$mail->Body = "�ͻ��������ɹ����������£�\r\n"."�˻�ID��$r5_Pid\r\n"."���(��)��$amount\r\n"."�����ţ�$ipsbillno\r\n"."ʱ�䣺".$mydate."\r\n��MT4����������״̬��\r\n".$status;
				//$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; //���ʼ���֧��htmlʱ������ʾ������ʡ��
				$mail->WordWrap   = 80; // ����ÿ���ַ����ĳ���
				//$mail->AddAttachment("f:/test.png");  //������Ӹ���
				$mail->IsHTML(false); 
				$mail->Send();
				//echo '�������֪ͨ�ʼ��ѷ���������Ա���䡣';
				//var_dump(file_put_contents('log.txt','�������֪ͨ�ʼ��ѷ���������Ա���䡣',FILE_APPEND));
			} catch (phpmailerException $e) {
				//echo "�������֪ͨ�ʼ�����ʧ�ܣ�".$e->errorMessage();
				$errmsg = "�������֪ͨ�ʼ�����ʧ�ܣ�".$e->errorMessage();
				file_put_contents('log.txt',$errmsg,FILE_APPEND);
			}
	}
	else
	{
		echo '����ʧ�ܣ�';
		exit;
	}
}
else
{
	echo 'ǩ������ȷ��';
	exit;
}
?>
