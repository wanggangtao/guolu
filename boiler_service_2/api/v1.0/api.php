<?php
require_once('api_init.php');
//var_dump($_REQUEST['master_id']."进来了");
//exit();
$method     = safeCheck($_POST['method'],0);
$api= New API($method);//参数1是接口编号，每个API应不一样
//$api->checkIP();//检查调用是否来自IP白名单
$source     = safeCheck($_POST['source'],0);//来源
$sign      = safeCheck($_POST['sign'], 0);//校验码
$timestamp     = safeCheck($_POST['timestamp'],0);//来源
$sign_raw = md5($method.$timestamp.$source.$secret[$source]);
$master_id     = safeCheck($_POST['master_id'],0);//来源
if($sign_raw != $sign) $api->ApiError('002', '校验不通过!');
//设置API来源
$api->setsource($source);
if(!in_array($source, array_keys($Array_API_Source)))  $api->ApiError('002', '来源错误');


if(isset($_POST["uid"]))
{
    $uid = $_POST["uid"];
//    $info = User::getInfoById($uid);
}
//else{
//    $admin = User::getSession();
//    $info = User::getInfoById($admin);
//}
//
//
//if($info['status']==-1){
//    $api->ApiError('505', '用户状态不对，不允许登录!');
//}

require_once($method.".php");

?>