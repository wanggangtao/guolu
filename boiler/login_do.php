<?php
/**
 * 登录表单处理
 * @version       v0.02
 * @create time   2014/9/4
 * @update time   2016/3/25
 * @author        wzp
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 **/
require_once('init.php');

//获取值
$account   = safeCheck($_POST['account'], 0);
$password  = safeCheck($_POST['pass'], 0);

$remember  = safeCheck($_POST['remember']);//是否记住cookie


try {
    $user = new User();
    $r = $user->login($account, $password, $remember);
    echo $r;
}catch(MyException $e){
    echo $e->jsonMsg();
}

?>