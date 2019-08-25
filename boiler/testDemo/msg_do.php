<?php
/**
 * 管理员处理  admin_do.php
 *
 * @version       v0.03
 * @create time   2014-9-4
 * @update time   2016/3/25
 * @author        dxl jt
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */
require_once('../init.php');



$act = safeCheck($_GET['act'], 0);
switch($act){
    case 'send'://添加管理员
        $room   =  safeCheck($_POST['room'], 0);
        $type  =  safeCheck($_POST['type'], 0);
        $content     =  safeCheck($_POST['content'],0);

        try {
            $rs = socket_message::sendMsgForRoomId($room,$content,$type);
            echo action_msg("发送成功!",1);
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;


}
?>