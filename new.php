<?php
require_once('/home/ubuntu/workroot/include/NLog.class.php');
require_once("/home/ubuntu/workroot/include/Auth.class.php");
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
define('ERRNO_UNAME_EXIST',           -100);
define('ERRNO_UNAME_TOO_LONG',        -101);
define('ERRNO_PASSWD_NOT_SAME',       -102);
define('ERRNO_NEW_USER_FAILED',       -103);

define('UNAME_MAX_LENGTH',            20);

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
if (!isset($_POST['passwd2'])) {
    $_POST['passwd2'] = '';
}

page_head($from_params);

if (isset($_POST['uname']) && isset($_POST['passwd']) && $_POST['uname']!='' && $_POST['passwd']!='') {
    $uname   = trim($_POST['uname']);
    $passwd  = MD5($_POST['passwd']);
    $passwd2 = MD5($_POST['passwd2']);
    
    $ret = useradd($uname, $passwd, $passwd2);
    if (ERRNO_SUCCESS == $ret && $ret['id']>0) {
        echo "<b>创建新用户成功，感谢您的注册</b>";
        $logger->addLog("NOTICE", "new success: [uname:$uname] [passwd:$passwd]");

        //set cookie
        $utoken = Auth::buildUtoken($ret['id'], $ret['utype'], UTOKEN_EFFECTIVE_PERIOD);
        setcookie(COOKIE_KEY, $utoken, time()+UTOKEN_EFFECTIVE_PERIOD, COOKIE_DOMAIN);
        
        if ($from != NULL) {
            header("location: $from");
        }
    }
    elseif(ERRNO_UNAME_EXIST == $ret) {
        echo "<b>该用户名已存在，请前往登录页，或选择新的用户名</b>";
        $logger->addLog("NOTICE", "new failed: [errno:$ret] [uname:$uname] [passwd:$passwd]");

        //rm cookie
        setcookie(COOKIE_KEY, "", time()-3600, COOKIE_DOMAIN);
    }
    elseif(ERRNO_UNAME_TOO_LONG == $ret) {
        echo "<b>用户名超过12字符，请重新输入</b>";
        $logger->addLog("NOTICE", "new failed: [errno:$ret] [uname:$uname] [passwd:$passwd]");

        //rm cookie
        setcookie(COOKIE_KEY, "", time()-3600, COOKIE_DOMAIN);
    }
    elseif(ERRNO_PASSWD_NOT_SAME == $ret) {
        echo "<b>两次输入的密码不一致，请重新输入</b>";
        $logger->addLog("NOTICE", "new failed: [errno:$ret] [uname:$uname] [passwd:$passwd]");

        //rm cookie
        setcookie(COOKIE_KEY, "", time()-3600, COOKIE_DOMAIN);
    }
    elseif (false == $ret) {
        echo "<b>创建新用户失败，请确认您输入的信息</b>";
        $logger->addLog("NOTICE", "new failed: [errno:$ret] [uname:$uname] [passwd:$passwd]");

        //rm cookie
        setcookie(COOKIE_KEY, "", time()-3600, COOKIE_DOMAIN);
    }
    else {
        echo "<b>创建新用户失败，请确认您输入的信息</b>";
        $logger->addLog("NOTICE", "new failed: [errno:$ret] [uname:$uname] [passwd:$passwd]");

        //rm cookie
        setcookie(COOKIE_KEY, "", time()-3600, COOKIE_DOMAIN);
    }
}

page_tail();

/////////////////////////////////////////////////////////
function page_head($from) {
echo '
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><title>创建新用户</title></head>
<body>

<form style="width:2000 height:3000" onsubmit = "return checkinput()" action="https://52.68.164.129/new?from='.$from.'" method="post">

用户名: 
<input type="text" name="uname" value="'.$_POST['uname'].'"/>
<br>
密码&nbsp: 
<input type="password" name="passwd" value="'.$_POST['passwd'].'"/>
<br>
再次输入密码&nbsp: 
<input type="password" name="passwd2" value="'.$_POST['passwd2'].'"/>
<br>
<br>

<input type="submit" value="创建">
</form>';
}


function page_tail() {
echo '
</body>
</html>
';
}

/////////////////////////////////////////////////////////

function useradd($uname, $passwd, $passwd2) {
    //sql注入防护
    if (!preg_match("/^[a-zA-Z0-9_]*$/", $uname)) {
        return ERRNO_UNAME_ILLEGAL;
    }
    if (strlen($uname)>UNAME_MAX_LENGTH) {
        return ERRNO_UNAME_TOO_LONG;
    }
    if ($passwd != $passwd2) {
        return ERRNO_PASSWD_NOT_SAME;
    }

    $db = new DB_Auth();
    $ret = $db->newUser($uname, $passwd, Auth::UTYPE_NEW_USER);

    if (false == $ret['ret'] && preg_match("/^Duplicate entry/", $ret['error'])) {
        return ERRNO_UNAME_EXIST;
    }
    elseif (false == $ret['ret']) {
        return ERRNO_NEW_USER_FAILED;
    }

    return ERRNO_SUCCESS;
}


