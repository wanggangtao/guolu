<?php
/**
 * Created by PhpStorm.
 * User: ww
 * Date: 2019/3/20
 * Time: 11:48
 */

require_once "admin_init.php";

$openId = "";
if(isset($_POST['openId'])){
    $openId = $_POST['openId'];
}

try{
    if(!empty($openId)){


        $rs = user_account::login_out($openId);
        if($rs > 0){
            echo action_msg("注销成功",1);
        }else{
            echo action_msg("注销失败",1);
        }

    }else{
        echo action_msg("注销失败",109);

    }



}catch(MyException $e){

    $e->jsonMsg();

}



?>