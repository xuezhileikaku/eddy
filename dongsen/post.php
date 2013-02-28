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
session_start();
include "Snoopy.class.php";
include "config.php";

$snoopy = new Snoopy;
$snoopy2 = new Snoopy;

$snoopy->cookies[$_SESSION['cookieName']]=$_POST['cookie'];
//设置vertify cookie
$snoopy->cookies[$_SESSION['vertifyName']]=$_SESSION['vertifyValue'];
$snoopy2->cookies[$_SESSION['cookieName']]=$_POST['cookie'];
//设置vertify cookie
$snoopy2->cookies[$_SESSION['vertifyName']]=$_SESSION['vertifyValue'];
unset($_POST['cookie']);
$snoopy->referer = $hostaddr;
$snoopy->submit($hostaddr,$_POST);

$snoopy2->referer = $hostaddr;
$vc=$snoopy->results;
$vc=substr($vc,strpos($vc,'var vc')+10,32);

$formvars['loginpass'] =md5(md5($password).$vc);
$formvars['validcode'] = '';
$formvars['flag'] = 'login2';
$formvars['username'] = $_POST['username'];
$formvars['loginpass_source'] = '12345678901234567890';
$action = $hostaddr;

$snoopy2->submit($action,$formvars);

//取验证码
$snoopy->fetch($hostaddr . '/?useValid=0.'.time());
$imgcode=base64_encode($snoopy->results);

//保存验证码
file_put_contents('code.png', $snoopy->results);
//识别验证码
include './recognition/recognition.php'
?>

<form method="post" action="adduser.php" name="form1" onsubmit="return postData()">
<table>
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
   <input name="cookie"  type="hidden" value="<?php echo $snoopy->cookies[$_SESSION['cookieName']]?>">
   <form>
</table>