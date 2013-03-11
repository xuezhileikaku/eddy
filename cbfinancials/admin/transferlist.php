<?php
session_start();
if(!isset($_SESSION ['pw'])){
	header('location:index.php');
	exit;
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
	<link rel="stylesheet" type="text/css" href="./bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="./bootstrap/js/bootstrap.min.js">
	<script type="text/javascript" src="./jquery-1.8.3.js"></script>
	<title>MT4 Member Management System</title>
</head>
<body>
<?php
$list = array();
$db = mysqli_connect('localhost','root','yiyiyi','yeepay');
if (mysqli_connect_errno() !== 0) {
	exit('数据库连接失败！');
}
mysqli_query($db,'set names utf8');
$sql = "select * from mt4_transfer";
$rs = mysqli_query($db,$sql);
while ($row = mysqli_fetch_assoc($rs)) {
	$list[] = $row;
}
mysqli_close($db);
?>
<table class="table table-bordered table-hover">
	<caption><h3>内部转账记录</h3></caption>
	<thead>
		<th>序号</th>
		<th>转出帐号</th>
		<th>转入帐号</th>
		<th>金额（$）</th>
		<th>时间</th>
		<th>是否成功</th>
	</thead>
	<?php
	if(!empty($list)){
		foreach ($list as $v) {
	?>
	<tr <?php echo $v['is_success'] == 0 ? 'class="error"' : 'class="success"' ?>>
		<td><?php echo $v['id'] ?></td>
		<td><?php echo $v['transfer_from'] ?></td>
		<td><?php echo $v['transfer_to'] ?></td>
		<td><?php echo $v['amount'] ?></td>
		<td><?php echo $v['transfer_time'] ?></td>
		<td><?php echo $v['is_success'] == 1 ? '成功' : '失败' ?></td>
	</tr>
	<?php }} ?>
</table>
<a href="check.php" class="btn btn-large btn-block">返回</a>
</body>
</html>