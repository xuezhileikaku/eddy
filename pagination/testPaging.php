<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
<link rel="stylesheet" type="text/css" href="./bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="./bootstrap/js/bootstrap.min.js">
<script type="text/javascript" src="./jquery-1.8.3.js"></script>

<?php
include './pagination.class.php';

$pg = new pagination(700,20,'testPaging.php?page=',10);
$pg->curPageNum = (($_GET['page'] > $pg->pageNum) or (intval($_GET['page']) <= 0)) ? 1 : $_GET['page'];
echo $pg->generatePageNav();
?>