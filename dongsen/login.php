<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script src="js/md5.js"></script>
<script type="text/javascript">
function checkCode(){
	var code = document.getElementById('validcodesource');
	if (code.value=="") {
		alert("请输入验证码");
		return false;
	}else{
		return code;
	}
}

function postData(){
	var ret = checkCode();
	if(ret == false){
		return false;
	}
	var scode = ret;
	var code = document.getElementById('validcode');
	var hash = hex_md5(scode.value);
	code.value = hash;
	return true;
}
</script>

<?php
/***********************************
Author:Eddy
Date:2013-02-26
***********************************/
session_start();
include "Snoopy.class.php";
include "config.php";

$snoopy = new Snoopy;
$snoopy->fetch($hostaddr . '/?useValid=0.'.time());

foreach($snoopy->headers as $value){
	if(strpos($value,'Cookie')){
		$strR=$value;
	}
}
$data = explode('=',$strR);//Array ( [0] => Set-Cookie: __CookieVerify__ [1] => ff8163bbcb4f994b8079fbdb24f6e239 )

//php版本判断
if(version_compare(PHP_VERSION, '5.3.0') >= 0){
	$cookieName =trim(strstr($data[0],' '));
	$cookie = trim(strstr($data[1],';',true));
	//失败
	if($cookie=''){
		$cookie=trim($data[1]);
	}
}else{
	$cookieName =trim(strstr($data[0],' '));
	if(strpos($data[1],';')!==false){
		//$cookie = trim(substr($data[1],0,32));//也有可能是32
		$cookie = trim(substr($data[1],0,26)); 
		//加个判断，把两种情况都考虑
		$p = trim(substr($data[1],0,27));
		if(substr($p,-1) != ';'){
			$cookie = trim(substr($data[1],0,32));
		}
	}else{
		$cookie = trim($data[1]);
	}
}

if($cookie=='')
{
	$cookie = trim($data[1]);
}

$_SESSION['cookieName']=$cookieName;
//保留vertify cookie
$_SESSION['vertifyName']=$cookieName;
$_SESSION['vertifyValue']=$cookie;

$imgcode=base64_encode($snoopy->results);

//保存图片
file_put_contents('code.png', $snoopy->results);

if($imgcode=='')
{
	$snoopy2 = new Snoopy;
	$snoopy2->cookies[$cookieName]=$cookie;
	$snoopy2->fetch($hostaddr . '/?useValid=0.'.time());
	$imgcode=base64_encode($snoopy2->results);

	//保存图片
	file_put_contents('code.png', $snoopy->results);

	//重新获取cookie
	foreach($snoopy2->headers as $value){
        if(strpos($value,'Cookie')){
            $strR=$value;
        }
	}
/********************************************************
	$data = explode('=',$strR);
	print_r($data);
	if(version_compare(PHP_VERSION, '5.3.0') >= 0){
        $cookieName =trim(strstr($data[0],' '));
        $cookie = trim(strstr($data[1],';',true));
	}else{
        $cookieName =trim(strstr($data[0],' '));
        //$cookie = trim(substr($data[1],0,32));
        $cookie = trim(substr($data[1],0,26)); 
        $p = trim(substr($data[1],0,27));
        if(sub_str($p,-1) != ';'){
            $cookie = trim(substr($data[1],0,32));
        }
	}
********************************************************/

/*** Set-Cookie: SessionId=lvti78g80ekvuhpo1317q7s663; path=/ */
	$data = explode(' ',$strR);
	$tmp = explode('=',$data[1]);
	$cookieName=current($tmp);
	$cookie = str_replace(';','',end($tmp));

	$_SESSION['cookieName']=$cookieName;
}

//保存post数据
foreach($_POST as $k=>$v){
$_SESSION[$k]=$v;
}

//识别验证码
include './recognition/recognition.php'

//<body onload="document.forms['form1'].submit();">
?>
<body>
<form method="post" action="post.php" name="form1" onsubmit="return postData()">
<table>
   <tr>
    <th></th>
    <td><input name="username" type="hidden" value="<?php echo $loginname?>"></td>
   </tr>
   <tr>
    <th>验证码</th>
    <td><input name="validcode_source" id="validcodesource" value="<?php echo $resul?>" />
    <img alt="" src="data:image/gif;base64,<?php echo $imgcode?>">
    </td>
   </tr>
   <tr>
    <td><input type="submit" value="下一步" ></td>
   </tr>
	<input name="validcode" id="validcode" type="hidden" value="" >
	<input name="flag" type="hidden" value="login">
	<input name="cookie"  type="hidden" value="<?php echo $cookie?>">
   <form>
</table>
<body>
