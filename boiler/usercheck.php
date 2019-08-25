<?php
/**
 * 登陆验证
 *
 * @version       v0.03
 * @create time   2014/9/4
 * @update time   2016/3/25
 * @author        wzp
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */
require_once('./init.php');

$check = User::checkLogin();
if(empty($check)) {
	header('Location: login.php');
	exit();//header()之后一定要加上退出
}else{
    $USERId = User::getSession();
	$USERINFO = User::getInfoById($USERId);
}
	
?>