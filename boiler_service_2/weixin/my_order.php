<?php
//echo 'hello';
require_once "admin_init.php";
$person_openid = "";
//$person_openid="oWV210uiur2oZ9TaIgkFKTrFgAcI";
if($isWeixin)
{
    $person_openid = common::getOpenId();//获取用户的openid，只要用户关注就会有一个openid且唯一
}
$master_info = Repair_person::getInfoByOpenid($person_openid);//根据openid查找出用户的相关信息
if(empty($master_info)){//如果用户信息为空，则返回到登录的页面
    echo "未查询到此人的消息";
    header("Location: master_login.php");//师傅端的登录页面
}
else
{
    $id=$master_info["id"];
    header("Location: ../weixinHtml/my_work.html?mid=$id");//师傅端的订单种类
}
?>