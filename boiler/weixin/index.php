<?php
/**
 * Created by PhpStorm.
 * User: ww
 * Date: 2019/3/17
 * Time: 20:24
 */


require_once ("../init.php");

$weixin = new weixin();



$reply = "";
$weixin->getMsg();
if($weixin->msgtype==='event')
{
    if($weixin->msg['Event']=="subscribe")
    {

        $str1 = "<a href='".$HTTP_PATH."weixin/weixin_repair.php?type=2'>";
        $str2 = "<a href='".$HTTP_PATH."weixin/weixin_intel_service.php'>";
        $str3 = "</a>";


//        $content = "您好, 西安元聚环保设备有限公司 欢迎您。
//            如果您想咨询，请点击“ ".$str2."智能客服 ".$str3."”。
//            如果您想报修，请点击“".$str1." 一键报修 ".$str3."”
//        ";

         $content = "您好，欢迎关注小元壁挂炉服务公众号。小元可以为您提供专业的壁挂炉安装、调试、维修、保养、更换等相关服务。24小时服务热线4009665890。";

        $reply = $weixin->makeText($content);

    }

    $weixin->reply($reply);


}
if($weixin->msgtype==='text')
{
    $miyuInfo = Baseconfig::getInfoByKey(weixin_customer::BASECONFIG_KEY);

    weixin_customer::_doText($weixin,"您好，客服人员",$miyuInfo['value']);
}
