<?php
session_start ();
error_reporting ( 0 );
?>
<!DOCTYPE html>
<html>
<head>
<title>管理登录</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../public/css/bootstrap.min.css" rel="stylesheet"
	media="screen">
<link rel="stylesheet" type="text/css" href="../public/css/admin.css">

<script src="../public/js/jquery.min.js"></script>
<script src="../public/js/bootstrap.min.js"></script>
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
	-webkit-box-shadow: 0 1px 2px rgba(0, 0, 0, .05);
	-moz-box-shadow: 0 1px 2px rgba(0, 0, 0, .05);
	box-shadow: 0 1px 2px rgba(0, 0, 0, .05);
}

.form-signin-heading {
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
            $(function() {
                var valp = $("#login input[name=pw]");
                $("#loginbtn").click(function() {
                    if (valp.val() == "") {
                        alert("密码错误");
                        return false;
                    }
                });

                var huilv = $("#set input[name=huilv]");
                var cjhuilv = $("#set input[name=cjhuilv]");
                $("#setbtn").click(function() {
                    if (huilv.val() === "" || cjhuilv.val() === "") {
                        alert("汇率值不能为空");
                        return false;
                    } else if (!$.isNumeric(huilv.val()) || !$.isNumeric(cjhuilv.val())) {
                        alert("汇率值必须为数字");
                        return false;
                    }
                });
                var i = 3;
				if($("#return").text()){
					var t =setInterval(function(){
						$("#return").html(i);
						i--;
						if(i === 0){
							window.clearInterval(t);
						}
						},1000)
				}
            });</script>
</head>
<body>
	<div class="container">
        <?php
								// 默认的登陆密码是admin888
								if ($_SESSION ['pw'] == 'admin888' || $_POST ['pw'] == 'admin888') {
									$_SESSION ['pw'] = 'admin888';
									if ($_POST ['huilv'] && $_POST ['cjhuilv']) {
										$result = file_put_contents ( '../conf/huilv.txt', $_POST ['huilv'] );
										$result2 = file_put_contents ( '../conf/cjhuilv.txt', $_POST ['cjhuilv'] );
										header ( 'refresh:3;url=index.php' );
										if ($result && $result2) {
											echo '配置信息修改成功，页面<font id="return" color="red">3</font>秒后自动返回！', '<br />';
										} else {
											echo '配置信息修改失败，页面<font id="return" color="red">3</font>秒后自动返回！', '<br />';
										}
										echo "<a href='./index.php'>立即返回</a>";
									} else {
										?>
                    <form class="form-signin" id="set" method="post"
			action="">
			<h2 class="form-signin-heading">汇率设置</h2>
			<hr />
			<label>入金汇率：</label> <input type="text" class="input-block-level"
				name="huilv" placeholder="请输入入金汇率">
			<label>出金汇率：</label> <input type="text" class="input-block-level"
				name="cjhuilv" placeholder="请输入出金汇率">
			<button class="btn btn-large btn-primary" type="submit" id="setbtn">设置</button><hr />
			<span><font color="red">入金汇率：1美元(USD) = <?php echo file_get_contents('../conf/huilv.txt'); ?> 人民币(CNY)<br />出金汇率：1美元(USD) = <?php echo file_get_contents('../conf/cjhuilv.txt'); ?> 人民币(CNY)</font></span>
			<a href="check.php" class="btn btn-large btn-block"
				style="margin-top: 20px;">报表管理</a>
		</form>

                <?php
									}
								} else {
									?>
                <form class="form-signin" id="login" method="post"
			action="">
			<h2 class="form-signin-heading">登陆认证</h2>
			<label>密码：</label> <input type="password" class="input-block-level"
				name="pw" placeholder="Password">
			<button class="btn btn-large btn-primary" type="submit" id="loginbtn">登陆</button>
		</form>
            <?php
								}
								require 'footer.php';
								?>
    

</body>
</html>