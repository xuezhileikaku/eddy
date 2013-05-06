<?php

//define('DEBUG', true);
define('ROOT', dirname(__FILE__));
//报错级别
if (defined('DEBUG')) {
    error_reporting(E_ALL & ~E_NOTICE);
} else {
    error_reporting(0);
}
//字符转义
if (!get_magic_quotes_gpc()) {

    function _addSlashes(&$v, $k) {
        $v = addslashes($v);
    }

    array_walk_recursive($_GET, '_addSlashes');
    array_walk_recursive($_POST, '_addSlashes');
    array_walk_recursive($_COOKIE, '_addslashes');
}

require ROOT . '/conf/config.php';
?>
