<meta http-equiv="Content-Type" content="text/html;charset=gbk">
<?PHP

	file_put_contents('./conf/curserv.txt',$_POST['server']);

	//����������˺ţ����˺�Ϊ11λ����������̻����+01,�ò������
	$merchantAcctId = "1002232897701";

	//���뷽ʽ��1���� UTF-8; 2 ���� GBK; 3���� GB2312 Ĭ��Ϊ1,�ò������
	$inputCharset = "2";

	//����֧�������ҳ���ַ���ò���һ����Ϊ�ռ��ɡ�
	$pageUrl = "";
	//����������֧������ĺ�̨��ַ���ò��������д������Ϊ�ա�
	$bgUrl = "http://kq.cbfinancials.net/pay/recieve.php";
	//���ذ汾���̶�ֵ��v2.0,�ò������
	$version =  "v2.0";
	//�������࣬1����������ʾ��2����Ӣ����ʾ��Ĭ��Ϊ1,�ò������
	$language =  "1";
	//ǩ������,��ֵΪ4������PKI���ܷ�ʽ,�ò������
	$signType =  "4";

	//֧��������,����Ϊ�ա�
	$payerName= $_POST['username']; 

	//֧������ϵ���ͣ�1 ��������ʼ���ʽ��2 �����ֻ���ϵ��ʽ������Ϊ�ա�
	$payerContactType =  "1";
	//֧������ϵ��ʽ����payerContactType���ö�Ӧ��payerContactTypeΪ1������д�����ַ��payerContactTypeΪ2������д�ֻ����롣����Ϊ�ա�
	$payerContact =  "";
	//�̻������ţ����²���ʱ�������嶩���ţ��̻����Ը����Լ������ŵĶ�������������ֵ������Ϊ�ա�
	$orderId = date("YmdHis");

	//����������ԡ��֡�Ϊ��λ���̻�������1�ֲ��Լ��ɣ������Դ�����ԡ��ò������
	$orderAmount = $_POST['amount']*100;

	//�����ύʱ�䣬��ʽ��yyyyMMddHHmmss���磺20071117020101������Ϊ�ա�
	$orderTime = date("YmdHis");
	//��Ʒ���ƣ�����Ϊ�ա�
	$productName= ""; 
	//��Ʒ����������Ϊ�ա�
	$productNum = "";
	//��Ʒ���룬����Ϊ�ա�
	$productId = "";

	//��Ʒ����������Ϊ�ա�
	$productDesc = $_POST['remark'];

	//��չ�ֶ�1���̻����Դ����Լ���Ҫ�Ĳ�����֧�����Ǯ��ԭֵ���أ�����Ϊ�ա�
	$ext1 = $_POST['username'];
	//��չ�Զ�2���̻����Դ����Լ���Ҫ�Ĳ�����֧�����Ǯ��ԭֵ���أ�����Ϊ�ա�
	$ext2 = "";

	//֧����ʽ��һ��Ϊ00���������е�֧����ʽ�����������ֱ���̻�����ֵΪ10�����
	$payType = "10";

	//���д��룬���payTypeΪ00����ֵ����Ϊ�գ����payTypeΪ10����ֵ������д��������ο������б�
	$bankId = $_POST['bank'];

	//ͬһ������ֹ�ظ��ύ��־��ʵ�ﹺ�ﳵ��1�������Ʒ��0��1����ֻ���ύһ�Σ�0������֧�����ɹ�����¿������ύ����Ϊ�ա�
	$redoFlag = "1";
	//��Ǯ���������ʻ��ţ����̻���ţ���Ϊ�ա�
	$pid = "";
	// signMsg ǩ���ַ��� ���ɿգ����ɼ���ǩ����

	function kq_ck_null($kq_va,$kq_na){if($kq_va == ""){$kq_va="";}else{return $kq_va=$kq_na.'='.$kq_va.'&';}}


	$kq_all_para=kq_ck_null($inputCharset,'inputCharset');
	$kq_all_para.=kq_ck_null($pageUrl,"pageUrl");
	$kq_all_para.=kq_ck_null($bgUrl,'bgUrl');
	$kq_all_para.=kq_ck_null($version,'version');
	$kq_all_para.=kq_ck_null($language,'language');
	$kq_all_para.=kq_ck_null($signType,'signType');
	$kq_all_para.=kq_ck_null($merchantAcctId,'merchantAcctId');
	$kq_all_para.=kq_ck_null($payerName,'payerName');
	$kq_all_para.=kq_ck_null($payerContactType,'payerContactType');
	$kq_all_para.=kq_ck_null($payerContact,'payerContact');
	$kq_all_para.=kq_ck_null($orderId,'orderId');
	$kq_all_para.=kq_ck_null($orderAmount,'orderAmount');
	$kq_all_para.=kq_ck_null($orderTime,'orderTime');
	$kq_all_para.=kq_ck_null($productName,'productName');
	$kq_all_para.=kq_ck_null($productNum,'productNum');
	$kq_all_para.=kq_ck_null($productId,'productId');
	$kq_all_para.=kq_ck_null($productDesc,'productDesc');
	$kq_all_para.=kq_ck_null($ext1,'ext1');
	$kq_all_para.=kq_ck_null($ext2,'ext2');
	$kq_all_para.=kq_ck_null($payType,'payType');
	$kq_all_para.=kq_ck_null($bankId,'bankId');
	$kq_all_para.=kq_ck_null($redoFlag,'redoFlag');
	$kq_all_para.=kq_ck_null($pid,'pid');
	

	$kq_all_para=substr($kq_all_para,0,strlen($kq_all_para)-1);


	
	/////////////  RSA ǩ������ ///////// ��ʼ //
	$fp = fopen("./99bill-rsa.pem", "r");
	$priv_key = fread($fp, 8192);
	fclose($fp);
	$pkeyid = openssl_get_privatekey($priv_key);

	// compute signature
	openssl_sign($kq_all_para, $signMsg, $pkeyid,OPENSSL_ALGO_SHA1);

	// free the key from memory
	openssl_free_key($pkeyid);

	 $signMsg = base64_encode($signMsg);
	/////////////  RSA ǩ������ ///////// ���� //


?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
		<title></title>
	</head>
	<body onLoad="document.kqPay.submit();">
			<form name="kqPay" action="https://www.99bill.com/gateway/recvMerchantInfoAction.htm" method="post">
				<input type="hidden" name="inputCharset" value="<?PHP echo $inputCharset; ?>" />
				<input type="hidden" name="pageUrl" value="<?PHP echo $pageUrl; ?>" />
				<input type="hidden" name="bgUrl" value="<?PHP echo $bgUrl; ?>" />
				<input type="hidden" name="version" value="<?PHP echo $version; ?>" />
				<input type="hidden" name="language" value="<?PHP echo $language; ?>" />
				<input type="hidden" name="signType" value="<?PHP echo $signType; ?>" />
				<input type="hidden" name="signMsg" value="<?PHP echo $signMsg; ?>" />
				<input type="hidden" name="merchantAcctId" value="<?PHP echo $merchantAcctId; ?>" />
				<input type="hidden" name="payerName" value="<?PHP echo $payerName; ?>" />
				<input type="hidden" name="payerContactType" value="<?PHP echo $payerContactType; ?>" />
				<input type="hidden" name="payerContact" value="<?PHP echo $payerContact; ?>" />
				<input type="hidden" name="orderId" value="<?PHP echo $orderId; ?>" />
				<input type="hidden" name="orderAmount" value="<?PHP echo $orderAmount; ?>" />
				<input type="hidden" name="orderTime" value="<?PHP echo $orderTime; ?>" />
				<input type="hidden" name="productName" value="<?PHP echo $productName; ?>" />
				<input type="hidden" name="productNum" value="<?PHP echo $productNum; ?>" />
				<input type="hidden" name="productId" value="<?PHP echo $productId; ?>" />
				<input type="hidden" name="productDesc" value="<?PHP echo $productDesc; ?>" />
				<input type="hidden" name="ext1" value="<?PHP echo $ext1; ?>" />
				<input type="hidden" name="ext2" value="<?PHP echo $ext2; ?>" />
				<input type="hidden" name="payType" value="<?PHP echo $payType; ?>" />
				<input type="hidden" name="bankId" value="<?PHP echo $bankId; ?>" />
				<input type="hidden" name="redoFlag" value="<?PHP echo $redoFlag; ?>" />
				<input type="hidden" name="pid" value="<?PHP echo $pid; ?>" />
			</form>
	</body>
</html>