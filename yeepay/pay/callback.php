<meta http-equiv="Content-Type" content="text/html; charset=gbk">
<?php

/*
 * @Description 易宝支付B2C在线支付接口范例 
 * @V3.0
 * @Author rui.xin
 */
 
include 'yeepayCommon.php';	

include 'mt.php';
	
#	只有支付成功时易宝支付才会通知商户.
##支付成功回调有两次，都会通知到在线支付请求参数中的p8_Url上：浏览器重定向;服务器点对点通讯.

#	解析返回参数.
$return = getCallBackValue($r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType,$hmac);

#	判断返回签名是否正确（True/False）
$bRet = CheckHmac($r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType,$hmac);
#	以上代码和变量不需要修改.
	 	
#	校验码正确.
if($bRet){
	if($r1_Code=="1"){
		
	#	需要比较返回的金额与商家数据库中订单的金额是否相等，只有相等的情况下才认为是交易成功.
	#	并且需要对返回的处理进行事务控制，进行记录的排它性处理，在接收到支付结果通知后，判断是否进行过业务逻辑处理，不要重复进行业务逻辑处理，防止对同一条交易重复发货的情况发生.      	  	
		
		if($r9_BType=="1"){
			echo "交易成功";
			echo  "<br />在线支付页面返回<br />";

			//与MT4服务器通信
			$encryptionKey = "asfas1";
			$secretHash = "fsdvgfygfsddsag";

			$mt4request = new CMT4DataReciver;
			//$mt4request->SetSafetyData($secretHash, $encryptionKey); // you can turn on encryption and hash by uncommenting this line. (you need to turn it on on the server too)
			$mt4request->OpenConnection(SERVER_ADDRESS, SERVER_PORT);
			if($mt4request==-1){
				exit('与MT4服务器通信失败，本次入金未更新至账户，请联系管理员手动处理。');
			}

			//获取交易账户入金前余额
			$params['login'] = $r5_Pid;
			$params['value'] = $r3_Amt; // above zero for deposits, below zero for withdraws
			$params['comment'] = "get account balance from yeepay";
			$answerData = $mt4request->MakeRequest("getbalance", $params);
			if($answerData == 'fail!'){
					echo '获取交易账户入金前余额失败。','<br />';
			}else{
					$data = explode('&',$answerData);
					$data = explode('=',end($data));
					echo '用户'.$r5_Pid.'入金前账户余额为：'.end($data).'<br />';
			}
			$mt4request->CloseConnection();
			$mt4request->OpenConnection(SERVER_ADDRESS, SERVER_PORT);
			if($mt4request==-1){
				exit('与MT4服务器通信失败，本次入金未更新至账户，请联系管理员手动处理。');
			}
			
			//更新账户余额
			$params['comment'] = "change account balance from yeepay";
			$answerData = $mt4request->MakeRequest("changebalance", $params);
			if($answerData == 'fail!'){
				echo '更新交易账户余额失败，请联系管理员手动处理。','<br />';
			}else{
				echo '更新交易账户余额成功。本次入金：'.$r3_Amt.'<br />';

				$mt4request->CloseConnection();
				$mt4request->OpenConnection(SERVER_ADDRESS, SERVER_PORT);
				if($mt4request==-1){
					exit('与MT4服务器通信失败，获取交易账户入金后余额失败。');
				}
				$params['comment'] = "get account balance from yeepay";
				$answerData = $mt4request->MakeRequest("getbalance", $params);
				if($answerData == 'fail!'){
					echo '获取交易账户入金后余额失败。';
				}else{
					$data = explode('&',$answerData);
					$data = explode('=',end($data));
					echo '用户'.$r5_Pid.'当前账户余额为：'.end($data).'<br />';
				}
			}

			$mt4request->CloseConnection();

		}elseif($r9_BType=="2"){
			#如果需要应答机制则必须回写流,以success开头,大小写不敏感.
			//echo "success";
			//echo "<br />交易成功";
			//echo  "<br />在线支付服务器返回";      			 
		}
	}
	
}else{
	echo "交易信息被篡改";
}
   
?>
<html>
<head>
<title>Return from YeePay Page</title>
</head>
<body>
</body>
</html>