<meta http-equiv="Content-Type" content="text/html; charset=gbk">
<?php

/*
 * @Description �ױ�֧��B2C����֧���ӿڷ��� 
 * @V3.0
 * @Author rui.xin
 */
 
include 'yeepayCommon.php';	

include 'mt.php';
	
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

		}elseif($r9_BType=="2"){
			#�����ҪӦ�����������д��,��success��ͷ,��Сд������.
			//echo "success";
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