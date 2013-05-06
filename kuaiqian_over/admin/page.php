<?php
require ROOT . '/common/pagination.class.php';
if (count($_GET) > 1) {
    if (isset($_GET['pageNow'])) {
        unset($_GET['pageNow']);
    }
    foreach ($_GET as $v => $k) {
        $queryStr .= $v . '=' . urlencode($k) . '&';
    }
    $pageUrl = MY_HOST . $_SERVER['PHP_SELF'] . '?' . $queryStr . 'pageNow=';
} else {
    $pageUrl = MY_HOST . $_SERVER['PHP_SELF'] . '?pageNow=';
}
$pg = new pagination($totalCount, $pageSize, $pageUrl, 10, true, true, 'right');
$pg->curPageNum = $pageNow;
echo $pg->generatePageNav();
?>