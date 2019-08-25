<?php
/**
 * Created by PhpStorm.
 * User: ww
 * Date: 2019/3/25
 * Time: 11:28
 */
require_once("admin_init.php");

$phone = safeCheck($_POST['mobile'], 0);

//$code = code_random(4);
//检查参数
try{

    $code = randcode(4);


    $rt = sms::send_code($phone,$code);

    if($rt)
    {
        $info = mobile_code::getInfoByMobile($phone);
        if(empty($info))
        {
            $rs = mobile_code::add($phone,$code);
        }
        else
        {
            $rs = mobile_code::update($info["id"],$code);
        }

        if($rs)
        {
            echo action_msg("发送成功!",1);
        }
    }

}catch (MyException $e)
{
    echo $e->jsonMsg();
}
