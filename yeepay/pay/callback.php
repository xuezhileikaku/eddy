<meta http-equiv="Content-Type" content="text/html; charset=gbk">
<?php

/*
 * @Description �ױ�֧��B2C����֧���ӿڷ��� 
 * @V3.0
 * @Author rui.xin
 */
 
include 'yeepayCommon.php';	

include 'mt.php';


require './lib/class.phpmailer.php';
ini_set("magic_quotes_runtime",0);

#	ֻ��֧���ɹ�ʱ�ױ�֧���Ż�֪ͨ�̻�.
##֧���ɹ��ص������Σ�����֪ͨ������֧����������е�p8_Url�ϣ�������ض���;��������Ե�ͨѶ.

#	�������ز���.
$return = getCallBackValue($r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType,$hmac);

#	�жϷ���ǩ���Ƿ���ȷ��True/False��
$bRet = CheckHmac($r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType,$hmac);
#	���ϴ���ͱ�������Ҫ�޸�.
	 	
#	У������ȷ.
if($bRet){
	if($r1_Code=="1"){
		
	#	��Ҫ�ȽϷ��صĽ�����̼����ݿ��ж����Ľ���Ƿ���ȣ�ֻ����ȵ�����²���Ϊ�ǽ��׳ɹ�.
	#	������Ҫ�Է��صĴ������������ƣ����м�¼�������Դ����ڽ��յ�֧�����֪ͨ���ж��Ƿ���й�ҵ���߼�������Ҫ�ظ�����ҵ���߼�������ֹ��ͬһ�������ظ��������������.      	  	
		
		if($r9_BType=="1"){
			echo "���׳ɹ�";
			echo  "<br />����֧��ҳ�淵��<br />";

			//��MT4������ͨ��
			$encryptionKey = "asfas1";
			$secretHash = "fsdvgfygfsddsag";

			$mt4request = new CMT4DataReciver;
			//$mt4request->SetSafetyData($secretHash, $encryptionKey); // you can turn on encryption and hash by uncommenting this line. (you need to turn it on on the server too)
			$mt4request->OpenConnection(SERVER_ADDRESS, SERVER_PORT);
			if($mt4request==-1){
				exit('��MT4������ͨ��ʧ�ܣ��������δ�������˻�������ϵ����Ա�ֶ�����');
			}

			//��ȡ�����˻����ǰ���
			$params['login'] = $r5_Pid;
			$params['value'] = $r3_Amt; // above zero for deposits, below zero for withdraws
			$params['comment'] = "get account balance from yeepay";
			$answerData = $mt4request->MakeRequest("getbalance", $params);
			if($answerData == 'fail!'){
					echo '��ȡ�����˻����ǰ���ʧ�ܡ�','<br />';
			}else{
					$data = explode('&',$answerData);
					$data = explode('=',end($data));
					echo '�û�'.$r5_Pid.'���ǰ�˻����Ϊ��'.end($data).'<br />';
			}
			$mt4request->CloseConnection();
			$mt4request->OpenConnection(SERVER_ADDRESS, SERVER_PORT);
			if($mt4request==-1){
				exit('��MT4������ͨ��ʧ�ܣ��������δ�������˻�������ϵ����Ա�ֶ�����');
			}
			
			//�����˻����
			$params['comment'] = "change account balance from yeepay";
			$answerData = $mt4request->MakeRequest("changebalance", $params);
			if($answerData == 'fail!'){
				echo '���½����˻����ʧ�ܣ�����ϵ����Ա�ֶ�����','<br />';
			}else{
				echo '���½����˻����ɹ����������'.$r3_Amt.'<br />';

				$mt4request->CloseConnection();
				$mt4request->OpenConnection(SERVER_ADDRESS, SERVER_PORT);
				if($mt4request==-1){
					exit('��MT4������ͨ��ʧ�ܣ���ȡ�����˻��������ʧ�ܡ�');
				}
				$params['comment'] = "get account balance from yeepay";
				$answerData = $mt4request->MakeRequest("getbalance", $params);
				if($answerData == 'fail!'){
					echo '��ȡ�����˻��������ʧ�ܡ�';
				}else{
					$data = explode('&',$answerData);
					$data = explode('=',end($data));
					echo '�û�'.$r5_Pid.'��ǰ�˻����Ϊ��'.end($data).'<br />';
				}
			}

			$mt4request->CloseConnection();

			//�����ʼ�֪ͨ
			try {
				$mail = new PHPMailer(true); 
				$mail->IsSMTP();
				$mail->CharSet='GBK'; //�����ʼ����ַ����룬�����Ҫ����Ȼ��������
				$mail->SMTPAuth   = true;                  //������֤
				$mail->Port       = 25;                    
				$mail->Host       = "smtp.163.com"; 
				$mail->Username   = "yiyiyitest@163.com";    
				$mail->Password   = "";            
				//$mail->IsSendmail(); //���û��sendmail�����ע�͵���������֡�Could  not execute: /var/qmail/bin/sendmail ���Ĵ�����ʾ
				$mail->AddReplyTo("yiyiyitest@163.com","Admin");//�ظ���ַ
				$mail->From       = "yiyiyitest@163.com";
				$mail->FromName   = "Admin";
				$to = "eddy@rrgod.com";
				$mail->AddAddress($to);
				$mail->Subject  = "�������֪ͨ";
				//���͵�����
				$mail->Body = "�ͻ��������ɹ����������£�\r\n"."�˻�ID��$r5_Pid\r\n"."��$r3_Amt\r\n"."ʱ�䣺".date('Y-m-d H:i',time());
				//$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; //���ʼ���֧��htmlʱ������ʾ������ʡ��
				$mail->WordWrap   = 80; // ����ÿ���ַ����ĳ���
				//$mail->AddAttachment("f:/test.png");  //������Ӹ���
				$mail->IsHTML(false); 
				$mail->Send();
				echo '�������֪ͨ�ʼ��ѷ���������Ա���䡣';
			} catch (phpmailerException $e) {
				echo "�������֪ͨ�ʼ�����ʧ�ܣ�".$e->errorMessage();
			}

		}elseif($r9_BType=="2"){
			#�����ҪӦ�����������д��,��success��ͷ,��Сд������.
			echo "success";
			//echo "<br />���׳ɹ�";
			//echo  "<br />����֧������������";      			 
		}
	}
	
}else{
	echo "������Ϣ���۸�";
}
   
?>
<html>
<head>
<title>Return from YeePay Page</title>
</head>
<body>
</body>
</html>