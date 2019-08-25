<?php
/**
 * Created by PhpStorm.
 * User: imyuyang
 * Date: 2019-04-24
 * Time: 14:56
 */

require_once('admin_init.php');
require_once('admincheck.php');

$act = safeCheck($_GET['act'], 0);
switch($act){
    case 'addvideo'://添加
        $product_id = safeCheck($_POST['product_id']);
        $title   =  safeCheck($_POST['title'],0);
        $img   =  $_POST['img']?safeCheck($_POST['img'],0):'';
        $video   =  $_POST['video']?safeCheck($_POST['video'],0):'';
        $addtime = time();
        $weight = 0;
        try {

            $attrsPro = array(
                "product_id" => $product_id,
                "path" => $video,
                "name" => $title,
                "add_time" => $addtime,
                "imgpath" => $img,
                "weight" => 0,
            );
            $rs = weixin_video::add($attrsPro);
            if($rs > 0){
                echo action_msg('添加成功', 1);
            }else
                echo action_msg('添加失败', 101);

        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;

    case 'editvideo'://添加
        $id = safeCheck($_POST['id']);
        $title   =  safeCheck($_POST['title'],0);
        $img   =  $_POST['img']?safeCheck($_POST['img'],0):'';
        $video   =  $_POST['video']?safeCheck($_POST['video'],0):'';
        $attrsPro = array(
            "path" => $video,
            "name" => $title,
            "imgpath" => $img,
        );
        $rs = weixin_video::update($id,$attrsPro);
        if($rs >= 0){
            echo action_msg('修改成功', 1);
        }else {
            echo action_msg('修改失败', 101);
        }
        break;

    case 'updateWeight'://修改权重
        $id = safeCheck($_POST['id']);
        $weight   =  safeCheck($_POST['weight']);
        try {
            $attrsPro = array(
                "weight" => $weight,
            );
            $rs = weixin_video::update($id,$attrsPro);
            if($rs >= 0){
                echo action_msg('修改成功', 1);
            }else {
                echo action_msg('修改失败', 101);
            }

        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;


    case 'del'://删除
        $id = safeCheck($_POST['id']);
        $rs = weixin_video::del($id);
        if($rs){
            echo action_msg("删除成功", 1);
        }else
            echo action_msg("删除失败",101);
        break;

}
