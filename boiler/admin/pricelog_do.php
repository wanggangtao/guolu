<?php
/**
 * 价格记录处理  pricelog_do.php
 *
 * @version       v0.01
 * @create time   2018/10/22
 * @update time
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */
require_once('admin_init.php');
require_once('admincheck.php');

$act = safeCheck($_GET['act'], 0);
switch($act){
    case 'add'://添加
        $objectid   =  safeCheck($_POST['objectid']);
        $type       =  safeCheck($_POST['type']);
        $addtype    =  safeCheck($_POST['addtype']);
        $price      =  safeCheck($_POST['price']);
        try {
            $attrs = array(
                "objectid"=>$objectid,
                "type"=>$type,
                "addtype"=>$addtype,
                "price"=>$price,
                "addtime"=>time()
            );
            $rs = Case_pricelog::add($attrs);
            echo action_msg("添加成功", 1);
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;

    case 'del'://删除
        $id = safeCheck($_POST['id']);
        try {
            $rs = Case_pricelog::del($id);
            echo action_msg("删除成功", 1);
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;
}
?>