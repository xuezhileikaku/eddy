<?php

include_once 'mt.php';

function changeAccountBalance($params, $retry_num = 3, $sleep_time = 1) {
    $flag = false;
    $errmsg = '';
    $i = 0;
    $balance = 0.00;

    $mt4request = new CMT4DataReciver ();
    $connResult = $mt4request->OpenConnection(SERVER_ADDRESS, SERVER_PORT);
    while ($connResult == - 1 && $i < $retry_num) {
        sleep($sleep_time);
		$t = $t = date('Y-m-d H:i:s');
        file_put_contents('./log.txt', "{$t}:{$params[login]}尝试重连MT4服务器$i\r\n", FILE_APPEND);
        $connResult = $mt4request->OpenConnection(SERVER_ADDRESS, SERVER_PORT);
        $i++;
    }
    if ($connResult == - 1) {
        $errmsg = '网络故障，与MT4服务器通信失败';
    } else {
        $answerData = $mt4request->MakeRequest("changebalance", $params);
        $answerData = iconv('gbk', 'utf-8', $answerData);
        if (mb_substr($answerData, 0, 4, 'utf-8') == '密码错误') {
            $errmsg = '密码错误';
        } else if (mb_substr($answerData, 0, 4, 'utf-8') == '查询用户') {
            $errmsg = '交易帐号不存在，请检查';
        } else if ($answerData == 'Fail!') {
            $errmsg = '其他错误';
        } else if (mb_substr($answerData, 0, 4, 'utf-8') == '余额不足') {
            $errmsg = '余额不足';
        } else if (mb_substr($answerData, 0, 3, 'utf-8') == '不支持') {
            $errmsg = $answerData;
        } else {
        	$tmpArr = explode('&', $answerData);
        	if (count($tmpArr) === 2) {
            	$firs = end($tmpArr);
            	$tmp = explode('=', $firs);
            	$balance = round(end($tmp), 2);
            	$flag = true;
        	} else {
        		$errmsg = '未知错误';
        		file_put_contents('./log.txt', $answerData . "\r\n",FILE_APPEND);
        	}
        }
        $mt4request->CloseConnection();
    }

    return $rs = array(
        'errmsg' => $errmsg,
        'balance' => $balance,
        'flag' => $flag
    );
}

/**
 * 获取客户端IP地址
 * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
 * @return mixed
 */
function get_client_ip($type = 0) {
	$type       =  $type ? 1 : 0;
	static $ip  =   NULL;
	if ($ip !== NULL) return $ip[$type];
	if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
		$pos    =   array_search('unknown',$arr);
		if(false !== $pos) unset($arr[$pos]);
		$ip     =   trim($arr[0]);
	}elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
		$ip     =   $_SERVER['HTTP_CLIENT_IP'];
	}elseif (isset($_SERVER['REMOTE_ADDR'])) {
		$ip     =   $_SERVER['REMOTE_ADDR'];
	}
	// IP地址合法验证
	$long = sprintf("%u",ip2long($ip));
	$ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
	return $ip[$type];
}

?>