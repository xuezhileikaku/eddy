<meta http-equiv="Content-Type" content="text/html; charset=gbk">
<?php

include 'mt.php';
require './lib/class.phpmailer.php';

	function kq_ck_null($kq_va,$kq_na){if($kq_va == ""){return $kq_va="";}else{return $kq_va=$kq_na.'='.$kq_va.'&';}}
	//����������˺ţ����˺�Ϊ11λ����������̻����+01,��ֵ���ύʱ��ͬ��
	$kq_check_all_para=kq_ck_null($_REQUEST[merchantAcctId],'merchantAcctId');
	//���ذ汾���̶�ֵ��v2.0,��ֵ���ύʱ��ͬ��
	$kq_check_all_para.=kq_ck_null($_REQUEST[version],'version');
	//�������࣬1����������ʾ��2����Ӣ����ʾ��Ĭ��Ϊ1,��ֵ���ύʱ��ͬ��
	$kq_check_all_para.=kq_ck_null($_REQUEST[language],'language');
	//ǩ������,��ֵΪ4������PKI���ܷ�ʽ,��ֵ���ύʱ��ͬ��
	$kq_check_all_para.=kq_ck_null($_REQUEST[signType],'signType');
	//֧����ʽ��һ��Ϊ00���������е�֧����ʽ�����������ֱ���̻�����ֵΪ10,��ֵ���ύʱ��ͬ��
	$kq_check_all_para.=kq_ck_null($_REQUEST[payType],'payType');
	//���д��룬���payTypeΪ00����ֵΪ�գ����payTypeΪ10,��ֵ���ύʱ��ͬ��
	$kq_check_all_para.=kq_ck_null($_REQUEST[bankId],'bankId');
	//�̻������ţ�,��ֵ���ύʱ��ͬ��
	$kq_check_all_para.=kq_ck_null($_REQUEST[orderId],'orderId');
	//�����ύʱ�䣬��ʽ��yyyyMMddHHmmss���磺20071117020101,��ֵ���ύʱ��ͬ��
	$kq_check_all_para.=kq_ck_null($_REQUEST[orderTime],'orderTime');
	//����������ԡ��֡�Ϊ��λ���̻�������1�ֲ��Լ��ɣ������Դ������,��ֵ��֧��ʱ��ͬ��
	$kq_check_all_para.=kq_ck_null($_REQUEST[orderAmount],'orderAmount');
	// ��Ǯ���׺ţ��̻�ÿһ�ʽ��׶����ڿ�Ǯ����һ�����׺š�
	$kq_check_all_para.=kq_ck_null($_REQUEST[dealId],'dealId');
	//���н��׺� ����Ǯ����������֧��ʱ��Ӧ�Ľ��׺ţ��������ͨ�����п�֧������Ϊ��
	$kq_check_all_para.=kq_ck_null($_REQUEST[bankDealId],'bankDealId');
	//��Ǯ����ʱ�䣬��Ǯ�Խ��׽��д����ʱ��,��ʽ��yyyyMMddHHmmss���磺20071117020101
	$kq_check_all_para.=kq_ck_null($_REQUEST[dealTime],'dealTime');
	//�̻�ʵ��֧����� �Է�Ϊ��λ���ȷ�10Ԫ���ύʱ���ӦΪ1000���ý������̻���Ǯ�˻������յ��Ľ�
	$kq_check_all_para.=kq_ck_null($_REQUEST[payAmount],'payAmount');
	//���ã���Ǯ��ȡ�̻��������ѣ���λΪ�֡�
	$kq_check_all_para.=kq_ck_null($_REQUEST[fee],'fee');
	//��չ�ֶ�1����ֵ���ύʱ��ͬ
	$kq_check_all_para.=kq_ck_null($_REQUEST[ext1],'ext1');
	//��չ�ֶ�2����ֵ���ύʱ��ͬ��
	$kq_check_all_para.=kq_ck_null($_REQUEST[ext2],'ext2');
	//�������� 10֧���ɹ���11 ֧��ʧ�ܣ�00��������ɹ���01 ��������ʧ��
	$kq_check_all_para.=kq_ck_null($_REQUEST[payResult],'payResult');
	//������� ������ա���������ؽӿ��ĵ�����󲿷ֵ���ϸ���͡�
	$kq_check_all_para.=kq_ck_null($_REQUEST[errCode],'errCode');



	$trans_body=substr($kq_check_all_para,0,strlen($kq_check_all_para)-1);
	$MAC=base64_decode($_REQUEST[signMsg]);

	$fp = fopen("./99bill.cert.rsa.20140728.cer", "r"); 
	$cert = fread($fp, 8192); 
	fclose($fp); 
	$pubkeyid = openssl_get_publickey($cert); 
	$ok = openssl_verify($trans_body, $MAC, $pubkeyid); 


	if ($ok == 1) { 
		switch($_REQUEST[payResult]){
				case '10':
						//�˴����̻��߼�����
						$rtnOK=1;
						//���������ǿ�Ǯ���õ�showҳ�棬�̻���Ҫ�Լ������ҳ�档
						//֧���ɹ�

						//ҵ���߼�����
						$status='';
						$id= $_REQUEST[dealId];
						$r5_Pid=$_REQUEST[ext1];
						$amount=$_REQUEST[orderAmount];
						$mydate=$_REQUEST[dealTime];
						$huilv = file_get_contents('./conf/huilv.txt');

			//��鶩���Ƿ��Ѵ�����ֹ�ظ����
            $conn = mysql_connect('127.0.0.1','root','');//�������ݿ�����
            mysql_select_db('yeepay');
            mysql_query('set names gbk');
			//$sqlLock = 'LOCK TABLES jyzbpme WRITE';
			//mysql_query($sqlLock);
            $sql = "select * from cbfinancials where orderNum = '" . trim($id) . "'";
            $result = mysql_query($sql);
            $row = mysql_fetch_array($result);
            if($row){
                //�����Ѵ����˳�
                mysql_close($conn);
				file_put_contents('log.txt','�����Ѵ����˳�',FILE_APPEND);
                exit();
            }
			
			//��������д�����ݿ�
			
			$sql = "insert into cbfinancials values (0 , '" . trim($id) . "')";
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
				$params['value'] = round($amount/(100*$huilv),2);
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
				$mail->Password   = ""; //������������
				//$mail->IsSendmail(); //���û��sendmail�����ע�͵���������֡�Could  not execute: /var/qmail/bin/sendmail ���Ĵ�����ʾ
				$mail->AddReplyTo("yiyiyitest@163.com","Admin");//�ظ���ַ
				$mail->From       = "yiyiyitest@163.com";
				$mail->FromName   = "Admin";
				$to = "574814416@qq.com";
				$mail->AddAddress($to);
				$mail->AddBCC("eddy@rrgod.com");
				$mail->Subject  = "�������֪ͨ";
				//���͵�����
				$mail->Body = "�ͻ��������ɹ����������£�\r\n"."�˻�ID��$r5_Pid\r\n"."���(��)��$amount\r\n"."�����ţ�$id\r\n"."ʱ�䣺".$mydate."\r\n��MT4����������״̬��\r\n".$status;
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
						

						$rtnUrl="http://kq.cbfinancials.net/pay/show.php?msg=success";
						break;
				default:
						$rtnOK=1;
						//���������ǿ�Ǯ���õ�showҳ�棬�̻���Ҫ�Լ������ҳ�档
						//֧��ʧ��
						$rtnUrl="http://kq.cbfinancials.net/pay/show.php?msg=false";
						break;	
		
		}

	}else{
						$rtnOK=1;
						//���������ǿ�Ǯ���õ�showҳ�棬�̻���Ҫ�Լ������ҳ�档
						//��֤ǩ��ʧ��
						$rtnUrl="http://kq.cbfinancials.net/pay/show.php?msg=error";
							
	}



?>

<result><?PHP echo $rtnOK; ?></result> <redirecturl><?PHP echo $rtnUrl; ?></redirecturl>