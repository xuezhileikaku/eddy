<?php
session_start ();
if (! isset ( $_SESSION ['pw'] )) {
	header ( 'location:index.php' );
	exit ();
}
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
<link rel="stylesheet" type="text/css"
	href="./bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css"
	href="./bootstrap/js/bootstrap.min.js">
<script type="text/javascript" src="./jquery-1.8.3.js"></script>
<title>MT4 Member Management System</title>
<script type="text/javascript">
	function goto(th){
		window.location.href = th.value;
	}

	function changeHK(th){
		var id = $(th).parent().parent().children(":first-child").text();
		$(th).text("处理中，请稍等！");
		$(th).load(
			"./changeHK.php",
			{"id":id},
			function(){
			return false;
		}
			);
	}
</script>
</head>
<body>
<?php
$list = array ();
$pageSize = 20;
$pageNow = 1;
$pageCount = 0;

$list = array ();
$db = mysqli_connect ( 'localhost', 'root', 'yiyiyi', 'yeepay' );
if (mysqli_connect_errno () !== 0) {
	exit ( '数据库连接失败！' );
}
mysqli_query ( $db, 'set names utf8' );

$rs = mysqli_query ( $db, 'select count(1) as num from mt4_withdraw' );
$r = mysqli_fetch_assoc ( $rs );
$pageCount = ceil ( $r ['num'] / $pageSize ); // 总页数

if (isset ( $_GET ['pageNow'] ) && is_numeric ( $_GET ['pageNow'] ) && $_GET ['pageNow'] >= 1 && $_GET ['pageNow'] <= $pageCount) {
	$pageNow = $_GET ['pageNow'];
}
$p = ($pageNow - 1) * $pageSize;

$sql = "select * from mt4_withdraw order by time desc limit $p , $pageSize";
$rs = mysqli_query ( $db, $sql );
while ( $row = mysqli_fetch_assoc ( $rs ) ) {
	$list [] = $row;
}
mysqli_close ( $db );
?>
<table class="table table-bordered table-hover">
		<caption>
			<h3>在线出金记录</h3>
		</caption>
		<thead>
			<th>序号</th>
			<th>交易帐号</th>
			<th>金额（$）</th>
			<th>收款人姓名</th>
			<th>收款人银行帐号</th>
			<th>收款账户开户行名称</th>
			<th>时间</th>
			<th>是否成功</th>
			<th>是否已汇款</th>
		</thead>
	<?php
	if (! empty ( $list )) {
		foreach ( $list as $v ) {
			?>
	<tr
			<?php echo $v['is_success'] == 0 ? 'class="error"' : 'class="success"' ?>>
			<td><?php echo $v['id'] ?></td>
			<td><?php echo $v['account'] ?></td>
			<td><?php echo $v['amount'] ?></td>
			<td><?php echo $v['name'] ?></td>
			<td><?php echo $v['bank_code'] ?></td>
			<td><?php echo $v['bank_name'] ?></td>
			<td><?php echo $v['time'] ?></td>
			<td><?php echo $v['is_success'] == 1 ? '成功' : '失败' ?></td>
			<td><?php echo $v['params'] == 1 ? '<a href="#" title="单击切换汇款状态" onclick="return changeHK(this)">已汇款</a>' : '<a href="#" title="单击切换汇款状态" onclick="return changeHK(this)">未汇款</a>' ?></td>
		</tr>
	<?php }} ?>
</table>
	<div class="pagination pagination-right">
		<ul>
<?php
// 如果不是第一页，则显示上一页
if ($pageNow != 1) {
	echo "<li><a href='withdrawlist.php?pageNow=" . ($pageNow - 1) . "'>上一页</a></li>";
}

// 页面导航共10页
if (! ($start = floor ( $pageNow / 10 ) * 10)) {
	$start = 1;
}
if ($pageCount >= 10) {
	if (($pageCount - $pageNow) >= 10) {
		for($i = $start; $i < $start + 10; ++ $i) {
			if ($i > $pageCount) {
				break;
			}
			if ($i != $pageNow) {
				echo "<li><a href='withdrawlist.php?pageNow=$i'>$i</a></li>";
			} else {
				echo "<li class='active'><a href='withdrawlist.php?pageNow=$i'><font color=red>$i</font></a></li>";
			}
		}
	} else {
		for($i = $pageCount - 9; $i <= $pageCount; ++ $i) {
			if ($i > $pageCount) {
				break;
			}
			if ($i != $pageNow) {
				echo "<li><a href='withdrawlist.php?pageNow=$i'>$i</a><li>";
			} else {
				echo "<li class='active'><a href='withdrawlist.php?pageNow=$i'><font color=red>$i</font></a><li>";
			}
		}
	}
} else {
	for($i = $start; $i < $pageCount + 1; $i ++) {
		if ($i != $pageNow) {
			echo "<li><a href='withdrawlist.php?pageNow=$i'>$i</a></li>";
		} else {
			echo "<li class='active'><a href='withdrawlist.php?pageNow=$i'><font color=red>$i</font></a></li>";
		}
	}
}

// 不是最后一页，则显示下一页
if ($pageNow != $pageCount) {
	echo "<li><a href='withdrawlist.php?pageNow=" . ($pageNow + 1) . "'>下一页</a><li>";
}
echo "<li><a>当前第<font color=red>$pageNow</font>页|共<font color=red>$pageCount</font>页</a></li>";
?>
<li><a>跳转至：<select onchange="goto(this)">
<?php
for($i = 1; $i < $pageCount + 1; $i ++) {
	if ($i == $pageNow) {
		echo "<option selected='selected' value='withdrawlist.php?pageNow=$i'>第 $i 页</option>";
	} else {
		echo "<option value='withdrawlist.php?pageNow=$i'>第 $i 页</option>";
	}
}
?>
</select></a></li>
		</ul>
	</div>
	<a href="check.php" class="btn btn-large btn-block">返回</a>
</body>
</html>