<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
<script src="md5.js"></script>
<script src="jquery-1.8.2.js"></script>
<script type="text/javascript">
function postData(obj){
	var hash = hex_md5($('#validcodesource').val());
	$('#validcode').val(hash);
	return true;
}
</script>

<?php
/***********************************
备注：在某些特定的服务器上，获取验证码及登录验证等加入了额外的cookie验证，可用此版本解决
将本目录下的文件替换掉同名文件即可。
Snoopy.class.php保存成了UTF-8版。
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

$data = explode('=',$strR);

//php版本判断
if(version_compare(PHP_VERSION, '5.3.0') >= 0){
	$cookieName =trim(strstr($data[0],' '));
	$cookie = trim(strstr($data[1],';',true));
}else{
	$cookieName =trim(strstr($data[0],' '));
	//$cookie = trim(substr($data[1],0,32));//也有可能是32
	$cookie = trim(substr($data[1],0,26)); 
	//加个判断，把两种情况都考虑
	$p = trim(substr($data[1],0,27));
	if(sub_str($p,-1) != ';'){
		$cookie = trim(substr($data[1],0,32));
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

if($imgcode=='')
{
	$snoopy2 = new Snoopy;
	$snoopy2->cookies[$cookieName]=$cookie;
	$snoopy2->fetch($hostaddr . '/?useValid=0.'.time());
	$imgcode=base64_encode($snoopy2->results);

	//重新获取cookie
	foreach($snoopy2->headers as $value){
        if(strpos($value,'Cookie')){
            $strR=$value;
        }
	}
	$data = explode('=',$strR);
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

	$_SESSION['cookieName']=$cookieName;
}



foreach($_POST as $k=>$v){
$_SESSION[$k]=$v;
}
?>

<form method="post" action="post.php" name="form1" onsubmit="return postData(this)">
<table>
   <tr>
    <th></th>
    <td><input name="username" type="hidden" value="<?php echo $loginname?>"></td>
   </tr>
   <tr>
    <th>验证码</th>
    <td><input name="validcode_source" id="validcodesource">
    <img alt="" src="data:image/gif;base64,<?php echo $imgcode?>">
    </td>
   </tr>
   <tr>
    <td><input type="submit" value="提交" ></td>
   </tr>
	<input name="validcode" id="validcode" type="hidden" value="" >
	<input name="flag" type="hidden" value="login">
	<input name="cookie"  type="hidden" value="<?php echo $cookie?>">
   <form>
</table>
