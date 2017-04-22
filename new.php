<?php
require_once('/home/ubuntu/workroot/include/NLog.class.php');
require_once("/home/ubuntu/workroot/include/Id.class.php");
require_once("/home/ubuntu/workroot/tables/DB_Auth.class.php");

define('COOKIE_KEY',                  'mmm_utoken');//miao登录信息的cookie名
define('COOKIE_DOMAIN',               '/');//miao登录信息的cookie作用域
define('UTOKEN_EFFECTIVE_PERIOD',     86400*30);//每30天要求重新登录一次
define('ERRNO_SUCCESS',                0);
define('ERRNO_COOKIE_NOT_EXIST',      -1);
define('ERRNO_COOKIE_LENGTH_ERR',     -2);
define('ERRNO_COOKIE_EXPIRED',        -3);
define('ERRNO_UID_NOT_EXIST',         -4);
define('ERRNO_UNAME_NOT_EXIST',       -5);
define('ERRNO_WRONG_PASSWD',          -6);
define('ERRNO_UNAME_ILLEGAL',         -7);


$logger = new NLog();
$logger->init('./log/login.log');

/////////////////////////////////////////////////////////

$from_params = NULL;
$from        = NULL;
if (isset($_GET['from']) && ""!=$_GET['from']) {
    $from_params = $_GET['from'];
    $from        = urldecode($_GET['from']);
}
if (!isset($_POST['uname'])) {
    $_POST['uname'] = '';
}
if (!isset($_POST['passwd'])) {
    $_POST['passwd'] = '';
}

page_head($from_params);

if (isset($_POST['uname']) && isset($_POST['passwd']) && $_POST['uname']!='' && $_POST['passwd']!='') {
    $uname  = trim($_POST['uname']);
    $passwd = MD5($_POST['passwd']);
    
    //$ret = login($uname, $passwd);
    if (ERRNO_SUCCESS == $ret) {
        echo "<b>login success</b>";
        $logger->addLog("NOTICE", "login success: [uname:$uname] [passwd:$passwd]");
        if ($from != NULL) {
            header("location: $from");
        }
    }
    else {
        echo "<b>login failed</b>";
        $logger->addLog("NOTICE", "login failed: [errno:$ret] [uname:$uname] [passwd:$passwd]");

        //rm cookie
        setcookie(COOKIE_KEY, "", time()-3600, COOKIE_DOMAIN);
    }
}

page_tail();

/////////////////////////////////////////////////////////


/////////////////////////////////////////////////////////
function page_head($from) {
echo '
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><title>用户登录</title></head>
<body>

<form style="width:2000 height:3000" onsubmit = "return checkinput()" action="http://52.68.164.129/login?from='.$from.'" method="post">

用户名: 
<input type="text" name="uname" value="'.$_POST['uname'].'"/>
<br>
密码&nbsp: 
<input type="password" name="passwd" value="'.$_POST['passwd'].'"/>
<br>
<br>

<input type="submit" value="登录">
</form><form action="http://52.68.164.129/mamamilk/new.php" mothod="get"><input type="submit" value="创建新用户"></form>';
}


function page_tail() {
echo '
</body>
</html>
';
}

