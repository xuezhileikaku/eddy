<!DOCTYPE html>
<html>
  <head>
    <title>汇率设置</title>
	<link href="./bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
	<script src="./bootstrap/js/jquery.min.js"></script>
	<script src="./bootstrap/js/bootstrap.min.js"></script>
	<meta http-equiv="Content-Type" content="text/html; charset=gbk" />
  <style type="text/css">
      body {
        padding-top: 40px;
        padding-bottom: 40px;
        background-color: #f5f5f5;
      }

      .form-signin {
        max-width: 400px;
        padding: 19px 29px 29px;
        margin: 0 auto 20px;
        background-color: #fff;
        border: 1px solid #e5e5e5;
        -webkit-border-radius: 5px;
           -moz-border-radius: 5px;
                border-radius: 5px;
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
           -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
                box-shadow: 0 1px 2px rgba(0,0,0,.05);
      }

      .form-signin-heading{
        margin-bottom: 10px;
      }

      .form-signin input[type="password"] {
        font-size: 16px;
        height: auto;
        margin-bottom: 15px;
        padding: 7px 9px;
      }
  </style>

  <script type="text/javascript">
  $(function(){
    var valp = $("#login input[name=pw]");
    $("#loginbtn").click(function(){
      if (valp.val() == "") {
        alert("密码错误")
        return false;
      }else if(valp.val() != "admin888"){
        alert("密码错误")
        return false;
      }
    });

    var huilv = $("#set input[name=huilv]");
    $("#setbtn").click(function(){
      if (huilv.val() == "") {
        alert("汇率值不能为空")
        return false;
      }else if(!$.isNumeric(huilv.val())){
        alert("汇率值必须为数字")
        return false;
      }
    });

     }); </script>

  </head>
  <body>
  <?php
session_start ();
error_reporting(0);
//默认的登陆密码是admin888
if ($_SESSION ['pw'] == 'admin888' || $_POST ['pw'] == 'admin888') {
	$_SESSION ['pw'] = 'admin888';
	
	
	if ($_POST ['huilv']) {
		$result = file_put_contents('../pay/conf/huilv.txt',$_POST ['huilv']);
    if ($result) {
      echo '配置信息修改成功', '<br />';
    }else{
      echo '配置信息修改失败', '<br />';
    }
		echo "<a href='./index.php'>返回</a>";

	} else {
		?>
    <div class="container">
      <form class="form-signin" id="set" method="post" action="">
        <h2 class="form-signin-heading">汇率设置</h2>
		<label>汇率：</label>
        <input type="text" class="input-block-level" name="huilv" placeholder="请输入汇率值">
        <button class="btn btn-large btn-primary" type="submit" id = "setbtn">设置</button>
        <span><font color="red">当前汇率设置：1美元(USD) = <?php echo file_get_contents('../pay/conf/huilv.txt'); ?> 人民币(CNY)</font></span
      </form>
    </div>

		<?php
	}
} else {
	?>
    <div class="container">
      <form class="form-signin" id="login" method="post" action="">
        <h2 class="form-signin-heading">登陆认证</h2>
		<label>密码：</label>
        <input type="password" class="input-block-level" name="pw" placeholder="Password">
        <button class="btn btn-large btn-primary" type="submit" id = "loginbtn">登陆</button><span><?php echo $_GET['errmsg'];?></span>
      </form>
    </div>
	<?php
}
?>
  </body>
</html>