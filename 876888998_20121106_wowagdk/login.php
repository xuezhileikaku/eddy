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
session_start();
include "Snoopy.class.php";
include "config.php";
$snoopy = new Snoopy;
$snoopy->fetch($hostaddr . '/?useValid=0.'.time());

/*
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
}
*/
//其实上面只是获取session cookie，后来知道php中有相应函数可以获取，很简单
$cookieName=session_name();
$cookie=session_id();
$_SESSION['cookieName']=$cookieName;

$imgcode=base64_encode($snoopy->results);

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
