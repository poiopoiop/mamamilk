<?php
require_once('/home/ubuntu/workroot/include/NLog.class.php');
require_once("/home/ubuntu/workroot/include/Auth.class.php");
require_once("/home/ubuntu/workroot/tables/DB_Auth.class.php");

define('COOKIE_KEY', 'mmm_utoken');//miao登录信息的cookie名

var_dump($_COOKIE);
if (isset($_COOKIE[COOKIE_KEY])) {
    $uinfo = Auth::checkUtoken($_COOKIE[COOKIE_KEY]);
    var_dump($uinfo);
}
