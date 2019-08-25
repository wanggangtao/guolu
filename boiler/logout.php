z<?php
	/**
	***登出表单处理
    * @version       v0.02
    * @create time   2014/9/4
    * @update time   2016/3/25
    * @author        wzp
    * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
	**/
	require_once('init.php');
	User::logout();
	header("Location: login.php");exit();
?>