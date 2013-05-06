<?php

define('MY_HOST', 'http://' . $_SERVER['HTTP_HOST']);
//接口信息
define('BG_URL', MY_HOST . '/pay/recieve.php');
define('ACCOUNT_ID', '');
//是否开启邮件通知
define('SEND_EMAIL', true);
//发件箱信息
$email = array(
    'username' => '',
    'password' => '',
    'host' => 'smtp.163.com',
    'charset' => 'utf-8',
);
//邮件通知列表
$email_list = array(

    //'eddy@rrgod.com',
);
//数据库信息
$db_info = array(
	/*本地
    'db_addr' => '127.0.0.1',
    'db_name' => 'mt4_member',
    'db_user' => 'root',
    'db_pwd' => 'root',
    */
);
?>