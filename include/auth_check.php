<?php
Bd_Init::init();
require_once("/home/op/odp_miao/webroot/include/Id.class.php");

define(HOST,                        'http://miao.baidu.com');//miao登录信息的cookie名
define(COOKIE_KEY,                  'miao_utoken');//miao登录信息的cookie名
define(COOKIE_DOMAIN,               '/');//miao登录信息的cookie作用域
define(UTOKEN_EFFECTIVE_PERIOD,     86400*30);//每30天要求重新登录一次
define(ERRNO_SUCCESS,                0);
define(ERRNO_COOKIE_NOT_EXIST,      -1);
define(ERRNO_COOKIE_LENGTH_ERR,     -2);
define(ERRNO_COOKIE_EXPIRED,        -3);
define(ERRNO_UID_NOT_EXIST,         -4);
define(ERRNO_UID_NOT_EXIST,         -5);
define(ERRNO_WRONG_PASSWD,          -6);

check();

function check() {
    $ret = _check();
    if ($ret != ERRNO_SUCCESS) {
        //rm cookie
        setcookie(COOKIE_KEY, "", time()-3600);
        $uri = urlencode(HOST.$_SERVER['REQUEST_URI']);
        //header to login page
        header("location: https://miao.baidu.com/login?from=$uri");
        //echo "no:$ret\n";
    }
}

function _check() {
    //cookie exist
    if(!isset($_COOKIE['miao_utoken'])) {
        return ERRNO_COOKIE_NOT_EXIST;
    }
    $utoken = $_COOKIE['miao_utoken'];

    //utoken length check
    if (strlen($utoken) != 80) {
        return ERRNO_COOKIE_LENGTH_ERR;
    }

    //uid
    $uidStr = substr($utoken, 0, 24);
    $uid = Id::getIdFromStr($uidStr);

    //timestamp
    $timestampStr = substr($utoken, 24, 24);
    $timestamp = Id::getIdFromStr($timestampStr);
    
    //utoken effective period
    if (time() - $timestamp > UTOKEN_EFFECTIVE_PERIOD) {
        return ERRNO_COOKIE_EXPIRED;
    }

    //get passwd_md5 from db by uid
    $conn = Bd_Db_ConnMgr::getConn("db_miao");
    $ret = $conn->select('auth', array('id', 'uname', 'passwd_md5',), array('id = '.$uid), null, null);
    if (!$ret || !isset($ret[0]['passwd_md5'])) {
        return ERRNO_UID_NOT_EXIST;
    }
    $passwd = $ret[0]['passwd_md5'];

    //passwd check
    $passwdHashStr = substr($utoken, 48, 32);
    if ($passwdHashStr != MD5($uidStr.$timestampStr.$passwd)) {
        return ERRNO_WRONG_PASSWD;
    }

    return ERRNO_SUCCESS;
}

