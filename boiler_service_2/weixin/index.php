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

         $content = "您好，欢迎关注小元壁挂炉服务公众号。小元可以为您提供专业的壁挂炉安装、调试、维修、保养、更换等相关服务。24小时服务热线4009665890。";

        $reply = $weixin->makeText($content);

    }

    $weixin->reply($reply);


}

