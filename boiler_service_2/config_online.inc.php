<?php

/**
 * config.inc.php 配置文件
 *
 * @version       v0.06
 * @create time   2014/9/1
 * @update time   2016/3/16 2016/3/27 2016/6/25 2016/7/30
 * @author        jt
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

//基础设置=================================================
//error_reporting(0);                          //网站开发时，务必关闭此项；网站上线时，务必打开此项。
define("ZHIMAPHP_VERSION", '3.10.1');             //ZhimaPHP版本号（年-2014.月.当月第x次发布）
define("ZHIMAPHP_UPDATE",  '20171026');           //ZhimaPHP更新日期
header("content-type:text/html;charset=utf-8");
date_default_timezone_set('PRC');              //时区设置，服务器放置在国外的需要打开此项
session_start();
//ob_start();
define("PROJECTCODE",    'xayuanju');            //项目编号，建议修改，每个项目应该不同

//路径定义=================================================
$FILE_PATH = str_replace('\\','/',dirname(__FILE__)).'/'; //网站根目录路径
$LIB_PATH        = $FILE_PATH.'lib/';
$LIB_COMMON_PATH = $LIB_PATH.'common/';
$LIB_TABLE_PATH  = $LIB_PATH.'table/';

$API_LIB_PATH = $FILE_PATH.'api/lib/';
$API_LIB_TABLE_PATH  = $FILE_PATH.'api/lib/table/';

$HTTP_PATH = 'http://bs.xazhima.com/';              //网站访问路径，根据实际情况修改

//$LOCAL_FILE_PATH_UPLOAD='http://localhost/boiler/';
//$LOCAL_FILE_PATH=$LOCAL_FILE_PATH_UPLOAD.'lib/common/';

//数据库连接参数设置=======================================
 $DB_host   = 'localhost';                                 //数据库地址
$DB_user   = 'boiler';                                      //数据库用户
$DB_pass   = '*boiler#';                                      //数据库用户密码
$DB_name   = 'boiler';                                     //数据库名称
$DB_prefix = 'boiler_';                                    //表前缀，可以为空

//日志文件路径==============================================
//请给以下日志文件设置写权限
$LOG_PATH   = $FILE_PATH.'logs/';
$LOG_config = array(
    'common'      => $LOG_PATH.'common.log',
    'debug'       => $LOG_PATH.'debug.log'
);

//管理员Cookie 和 Session===================================
$cookie_ADMINID      = PROJECTCODE.'ACID';
$cookie_ADMINCODE    = PROJECTCODE.'ACCODE';
$session_ADMINID     = PROJECTCODE.'ASID';

//用户Cookie 和 Session===================================
$cookie_USERID      = PROJECTCODE.'UCID';
$cookie_USERCODE    = PROJECTCODE.'UCODE';
$session_USERID     = PROJECTCODE.'USID';






//关于socket的配置==================
$SOCKET_ADDRESS = "0.0.0.0";
$SOCKET_PORT = 7272;

//微信推送消息模板
$kf_template_id = "sqgOQCJAp29iIMY5-4esUi52DBxqQDTNC7WNjiuQozc";

$coupon_template_id = "ZcWqed1yNPyrHmn-QQWD9Ac3LCu1O9oAaDS6TohT1_U";

?>
