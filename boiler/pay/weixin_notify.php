<?php
/**
 * Created by PhpStorm.
 * User: tangqiang
 * Date: 17/7/26
 * Time: 下午9:30
 */

require_once("../init.php");
$data = weixin_pay::getNotifyData();
$attrs = array(
    "request"=>json_encode($data,true),
    "addtime"=>time(),
    "adddate"=>date("Y-m-d H:i:s")
);
$id = weixin_log::add($attrs);
$out_trade_no = $data["out_trade_no"];
$backData = $data["attach"];
$total_fee = $data["total_fee"];

$weixin = new weixin_pay($WEIXIN_INFO["appid"],$WEIXIN_INFO["mch_id"],$WEIXIN_INFO["notify_url"],$WEIXIN_INFO["pay_key"]);
$verySign = $weixin->MakeSign($data);
if($verySign==$data["sign"]&&!empty($data))
{
    $code = "SUCCESS";
    $msg = "OK";
    weixin_pay::replyNotify($code,$msg);
    $backData = $data["attach"];
    require_once("pay_success_solve.php");
}
else
{
    $code = "FAIL";
    $msg = "NO";
    weixin_pay::replyNotify($code,$msg);
}

weixin_log::update($id,array("response"=>$code));
