<meta http-equiv="Content-Type" content="text/html; charset=gbk">
<?php
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

#	У������ȷ.
if($bRet){
	if($r1_Code=="1"){	
		if($r9_BType=="1"){
			echo "���׳ɹ�";
			echo  "<br />����֧��ҳ�淵��<br />";
            /*
			//��MT4������ͨ��
			$encryptionKey = "asfas1";
			$secretHash = "fsdvgfygfsddsag";
			$status = "����֧���ɹ�\r\n";

			$mt4request = new CMT4DataReciver;
			$connResult = $mt4request->OpenConnection(SERVER_ADDRESS, SERVER_PORT);
			////////////////////////////////////////////////////////////////////////////////////////////
			if($connResult == -1){
				echo '��MT4������ͨ��ʧ��1��','<br />';
			}else{
				//��ȡ�����˻����ǰ���
				//$r3_Amt=round($r3_Amt/7,2);
				$params['login'] = $r5_Pid;
				$params['value'] = round($r3_Amt/7,2); // above zero for deposits, below zero for withdraws
				$params['comment'] = "get account balance from yeepay";
				$answerData = $mt4request->MakeRequest("getbalance", $params);
				if($answerData == 'Fail!'){
						echo '��ȡ�����˻����ǰ���ʧ�ܡ�','<br />';
				}else{
						$data = explode('&',$answerData);
						$data = explode('=',end($data));
						echo '�û�'.$r5_Pid.'���ǰ�˻����Ϊ��$'.end($data).'<br />';
				}
			}
			$mt4request->CloseConnection();
			
			////////////////////////////////////////////////////////////////////////////////////////////
			$connResult = $mt4request->OpenConnection(SERVER_ADDRESS, SERVER_PORT);
			if($connResult==-1){
				echo '��MT4������ͨ��ʧ�ܣ��������δ�������˻�������ϵ����Ա�ֶ�����','<br />';
				$status .= "��MT4������ͨ��ʧ�ܣ��������δ�������˻������ֶ�����\r\n";
			}else{	
				//�����˻����
				$params['comment'] = "change account balance from yeepay";
				$answerData = $mt4request->MakeRequest("changebalance", $params);
				if($answerData == 'Fail!'){
					echo '���½����˻����ʧ�ܣ�����ϵ����Ա�ֶ�����','<br />';
					$status .= "���½����˻����ʧ�ܣ��������δ�������˻������ֶ�����\r\n";
				}else{
					echo '���½����˻����ɹ����������$'.$r3_Amt.'<br />';
					$status .= "���½����˻����ɹ���\r\n";
					$mt4request->CloseConnection();
					
					////////////////////////////////////////////////////////////////////////////////////////////
					$connResult = $mt4request->OpenConnection(SERVER_ADDRESS, SERVER_PORT);
					if($connResult == -1){
						echo '��MT4������ͨ��ʧ�ܣ���ȡ�����˻��������ʧ�ܡ�','<br />';
					}else{
						$params['comment'] = "get account balance from yeepay";
						$answerData = $mt4request->MakeRequest("getbalance", $params);
						if($answerData == 'Fail!'){
							echo '��ȡ�����˻��������ʧ�ܡ�','<br />';
						}else{
							$data = explode('&',$answerData);
							$data = explode('=',end($data));
							echo '�û�'.$r5_Pid.'��ǰ�˻����Ϊ��$'.end($data).'<br />';
						}
					}
					$mt4request->CloseConnection();
				}
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
				$mail->Password   = "";            
				//$mail->IsSendmail(); //���û��sendmail�����ע�͵���������֡�Could  not execute: /var/qmail/bin/sendmail ���Ĵ�����ʾ
				$mail->AddReplyTo("yiyiyitest@163.com","Admin");//�ظ���ַ
				$mail->From       = "yiyiyitest@163.com";
				$mail->FromName   = "Admin";
				$to = "eddy@rrgod.com";
				$mail->AddAddress($to);
				$mail->Subject  = "�������֪ͨ";
				//���͵�����
				$mail->Body = "�ͻ��������ɹ����������£�\r\n"."�˻�ID��$r5_Pid\r\n"."���(��)��$r3_Amt\r\n"."�����ţ�$r2_TrxId\r\n"."ʱ�䣺".date('Y-m-d H:i',time())."\r\n��MT4����������״̬��\r\n".$status;
				//$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; //���ʼ���֧��htmlʱ������ʾ������ʡ��
				$mail->WordWrap   = 80; // ����ÿ���ַ����ĳ���
				//$mail->AddAttachment("f:/test.png");  //������Ӹ���
				$mail->IsHTML(false); 
				$mail->Send();
				echo '�������֪ͨ�ʼ��ѷ���������Ա���䡣';
			} catch (phpmailerException $e) {
				echo "�������֪ͨ�ʼ�����ʧ�ܣ�".$e->errorMessage();
			}*/

		}elseif($r9_BType=="2"){
			#�����ҪӦ�����������д��,��success��ͷ,��Сд������.
			echo "success";
            //��鶩���Ƿ��Ѵ�����ֹ�ظ����
            $conn = mysql_connect('127.0.0.1','root','yiyiyi');
            mysql_select_db('yeepay');
            mysql_query('set names gbk');
            $sql = "select * from jyzbpme where orderNum = '" . trim($r2_TrxId) . "'";
            $result = mysql_query($sql);
            $row = mysql_fetch_array($result);
            if($row){
                //�����Ѵ����˳�
                mysql_close($conn);
                exit();
            }
            
            //��MT4������ͨ��
			$encryptionKey = "asfas1";
			$secretHash = "fsdvgfygfsddsag";
			$status = "";

			$mt4request = new CMT4DataReciver;
			//$connResult = $mt4request->OpenConnection(SERVER_ADDRESS, SERVER_PORT);
			////////////////////////////////////////////////////////////////////////////////////////////
			/*
            if($connResult == -1){
				//echo '��MT4������ͨ��ʧ��1��','<br />';
			}else{
				//��ȡ�����˻����ǰ���
				//$r3_Amt=round($r3_Amt/7,2);
				$params['login'] = $r5_Pid;
				$params['value'] = round($r3_Amt/7,2); // above zero for deposits, below zero for withdraws
				$params['comment'] = "get account balance from yeepay";
				$answerData = $mt4request->MakeRequest("getbalance", $params);
				if($answerData == 'Fail!'){
						echo '��ȡ�����˻����ǰ���ʧ�ܡ�','<br />';
				}else{
						$data = explode('&',$answerData);
						$data = explode('=',end($data));
						echo '�û�'.$r5_Pid.'���ǰ�˻����Ϊ��$'.end($data).'<br />';
				}
			}
			$mt4request->CloseConnection();*/
			
			////////////////////////////////////////////////////////////////////////////////////////////
			$connResult = $mt4request->OpenConnection(SERVER_ADDRESS, SERVER_PORT);
			if($connResult==-1){
				//echo '��MT4������ͨ��ʧ�ܣ��������δ�������˻�������ϵ����Ա�ֶ�����','<br />';
				$status .= "��MT4������ͨ��ʧ�ܣ��������δ�������˻������ֶ�����\r\n";
			}else{	
				//�����˻����
				$params['comment'] = "change account balance from yeepay";
				$answerData = $mt4request->MakeRequest("changebalance", $params);
				if($answerData == 'Fail!'){
					//echo '���½����˻����ʧ�ܣ�����ϵ����Ա�ֶ�����','<br />';
					$status .= "���½����˻����ʧ�ܣ��������δ�������˻������ֶ�����\r\n";
				}else{
					//echo '���½����˻����ɹ����������$'.$r3_Amt.'<br />';
					$status .= "���½����˻����ɹ���\r\n";
					$mt4request->CloseConnection();
					//��������д�����ݿ�
                    $conn = mysql_connect('127.0.0.1','root','yiyiyi');
                    mysql_select_db('yeepay');
                    mysql_query('set names gbk');
                    $sql = "insert into jyzbpme values (0 , '" . trim($r2_TrxId) . "')";
                    $r = mysql_query($sql);
                    if(!$r){
                        $status .="������Ϣд�����ݿ�ʧ�ܣ����ܻ�����ظ��������";
                    }
                    mysql_close($conn);
					////////////////////////////////////////////////////////////////////////////////////////////
					/*
                    $connResult = $mt4request->OpenConnection(SERVER_ADDRESS, SERVER_PORT);
					if($connResult == -1){
						echo '��MT4������ͨ��ʧ�ܣ���ȡ�����˻��������ʧ�ܡ�','<br />';
					}else{
						$params['comment'] = "get account balance from yeepay";
						$answerData = $mt4request->MakeRequest("getbalance", $params);
						if($answerData == 'Fail!'){
							echo '��ȡ�����˻��������ʧ�ܡ�','<br />';
						}else{
							$data = explode('&',$answerData);
							$data = explode('=',end($data));
							echo '�û�'.$r5_Pid.'��ǰ�˻����Ϊ��$'.end($data).'<br />';
						}
					}
					$mt4request->CloseConnection();*/
				}
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
				$mail->Password   = "";            
				//$mail->IsSendmail(); //���û��sendmail�����ע�͵���������֡�Could  not execute: /var/qmail/bin/sendmail ���Ĵ�����ʾ
				$mail->AddReplyTo("yiyiyitest@163.com","Admin");//�ظ���ַ
				$mail->From       = "yiyiyitest@163.com";
				$mail->FromName   = "Admin";
				$to = "eddy@rrgod.com";
				$mail->AddAddress($to);
				$mail->Subject  = "�������֪ͨ";
				//���͵�����
				$mail->Body = "�ͻ��������ɹ����������£�\r\n"."�˻�ID��$r5_Pid\r\n"."���(��)��$r3_Amt\r\n"."�����ţ�$r2_TrxId\r\n"."ʱ�䣺".date('Y-m-d H:i',time())."\r\n��MT4����������״̬��\r\n".$status;
				//$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; //���ʼ���֧��htmlʱ������ʾ������ʡ��
				$mail->WordWrap   = 80; // ����ÿ���ַ����ĳ���
				//$mail->AddAttachment("f:/test.png");  //������Ӹ���
				$mail->IsHTML(false); 
				$mail->Send();
				echo '�������֪ͨ�ʼ��ѷ���������Ա���䡣';
			} catch (phpmailerException $e) {
				echo "�������֪ͨ�ʼ�����ʧ�ܣ�".$e->errorMessage();
			}
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