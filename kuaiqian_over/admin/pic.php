<?php
/**
 * @author Eddy eddy@rrgod.com
 * @link http://www.rrgod.com Eddy Blog
 * 2013-3-25
*/
require './header.php';
require './lib/graidle/graidle.php';

$typeData = array('l','b','hb','s','p');
$type = isset($_GET['type']) ? $_GET['type'] : 'b';
if (!in_array($type, $typeData)){
	$type = 'b';
}

$mysql = mysqldb::getIns();
$mysql->setCharset('utf8');
$sql = 'select date_format(time,\'%m.%d\') as d ,sum(amount) as s from mt4_deposition group by day(time)';
$list = $mysql->getAll($sql);
$mysql->close();
foreach ($list as $v){
	$data[] = $v['s'];
	$xData[] = $v['d'];
}
$n = count($xData);
if($n >=20){
	$xData = array_slice($xData, $n-20);
	$data = array_slice($data, $n-20);
}

$rp = new graidle('在线日入金总量趋势图');
$rp->setHeight(600);
$rp->setWidth(800);
$rp->setXtitle("日期");
$rp->setYtitle("金额（￥）");
$rp->setBgCl("#FFFFCC");
$rp->setFontCl("#3300FF");
$rp->setAxisCl("#000000");
$rp->setValue($data, $type);
$rp->setXValue($xData);
$rp->setExtLegend(0);
$rp->setSecondaryAxis(true, true);
$rp->setLegend('日入金总量','right');
$rp->setDivision(10000);
$rp->create();
//$rp->carry()//;
$rp->carry2file('./pic','qs');
?>
<div class="container-fluid">
<div class="mt4_title"><img src="./pic/qs.png" title="在线日入金总量趋势图"  alt="在线日入金总量趋势图"/></div>
<table class="table table-bordered table-condensed table-hover">
            <thead>
                <tr>
                    <th>日期</th>
					<th>金额（￥）</th>
                </tr>
            </thead>
<?php
            if (!empty($list)) {
                foreach ($list as $v) {
                    ?>
                    <tr>
                        <td><?php echo $v['d'] ?></td>
                        <td><?php echo $v['s'] ?></td>
                    </tr>
                <?php }
            }
            ?>
        </table>
        <?php require './footer.php'; ?>