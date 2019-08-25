<?php
/**
 * admin_init.php 管理端初始化文件
 *
 * @version       v0.02
 * @create time   2014/7/24
 * @update time   2016/3/27
 * @author        jt
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

require('../init.php');


//判断是不是微信环境
$isWeixin = 0;
if(!empty($_SERVER['HTTP_USER_AGENT']))
{
    if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false)
    {
        $isWeixin = 1;
    }
}


?>