<?php
/**
 * Created by PhpStorm.
 * User: zhima
 * Date: 2018/7/3
 * Time: 下午4:30
 */
/**
 * api_init.php API初始化文件
 *
 * @version       v0.01
 * @create time   2016/5/22
 * @update time
 * @author        jt
 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
 */

require_once('../../init.php');

//加载API相关类
require($API_LIB_PATH.'api.class.php');
require($API_LIB_TABLE_PATH.'table_apicount.class.php');
require($LIB_COMMON_PATH.'sms.class.php');



//API相关的设置(WiiPHP下也可以放setting.inc.php中)
$Array_API_Source = array(
    1  => 'weixin',
    2  => 'android',
    3  => 'ios',
    99 => 'test'
);
//调用接口的IP白名单
$Array_API_IP_WhiteList = array(
    '127.0.0.1',
);


$secret = array(
    "2"=>"boiler_android",
    "3"=>"boiler_ios",
);
