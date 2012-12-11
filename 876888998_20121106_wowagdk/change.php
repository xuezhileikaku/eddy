<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
<?php
session_start ();
//默认的登陆密码是admin888
if ($_SESSION ['pw'] == 'admin888' || $_POST ['pw'] == 'admin888') {
	$_SESSION ['pw'] = 'admin888';
	if ($_POST ['username'] && $_POST ['password'] && $_POST ['hostaddr']) {
		$filename = './config.php';
		$p = fopen ( $filename, 'w' );
		$content = '<?php';
		$content = $content . "\r\n";
		$content = $content . "\$loginname='" . $_POST ['username'] . "';\r\n";
		$content = $content . "\$password='" . $_POST ['password'] . "';\r\n";
		$content = $content . "\$hostaddr='" . $_POST ['hostaddr'] . "';\r\n";
		$content = $content . '?>';
		fwrite ( $p, $content );
		fclose ( $p );
		echo '配置信息修改成功', '<br />';
		echo "<a href='./change.php'>返回</a>";
	} else {
		?>
<div style="width: 800px; margin: 50px auto;">
	<h3>网站信息配置</h3>
	<h4>注意：密码必需和账号信息匹配，否则网站不能正常运行</h4>
</div>
<div style="border: solid 1px blue; width: 800px; margin: 0 auto;">
	<form method='post' action=''>
		地址: <input type='text' name='hostaddr'
			value='http://y0uh.dsk77.net:868' size='50' /><font color='red'>注意地址格式：开头http://，末尾不要带/</font><br /> 
			账户: <input type='text'
			name='username' size='50' /><br /> 
			密码: <input type='password' name='password' size='50' /><br />
		<input type='submit' value='确认修改' />
	</form>
</div>
<?php
	}
} else {
	?>
<form method='post' action=''>
	登陆密码: <input type='password' name='pw' /> <input type='submit' value='登陆' />
</form>
<?php
}
?>