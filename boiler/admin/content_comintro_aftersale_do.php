<?php
/**
 * Created by PhpStorm.
 * User: Lin
 * Date: 2018/12/27 0027
 * Time: 下午 4:18
 */
require_once('admin_init.php');
require_once('admincheck.php');

$act = safeCheck($_GET['act'], 0);
switch($act){

    case 'add_pic'://添加修改
        $picurl1   = safeCheck($_POST['picurl1'],0);
        $picurl2   = safeCheck($_POST['picurl2'],0);
        $picurl3   = safeCheck($_POST['picurl3'],0);
        $picurl4   = safeCheck($_POST['picurl4'],0);
        $attrs = array(
            "picurl1"=>$picurl1,
            "picurl2"=>$picurl2,
            "picurl3"=>$picurl3,
            "picurl4"=>$picurl4,
        );

        $rs =Web_intro_aftersale_pic::add($attrs);
        if($rs > 0){
            echo action_msg("添加成功！", 1);
        }else{
            echo action_msg("添加失败！", 101);
        }
        break;

    case 'edit_pic':
        $id = safeCheck($_POST['id']);
        $picurl1 = safeCheck($_POST['picurl1'],0);
        $picurl2 = safeCheck($_POST['picurl2'],0);
        $picurl3 = safeCheck($_POST['picurl3'],0);
        $picurl4 = safeCheck($_POST['picurl4'],0);
        $attrs = array(
            "picurl1"=>$picurl1,
            "picurl2"=>$picurl2,
            "picurl3"=>$picurl3,
            "picurl4"=>$picurl4,
        );
        $rs = Web_intro_aftersale_pic::update($id, $attrs);
        echo $rs;
        break;

    case 'ad_edit':
        $id = safeCheck($_POST['id'],1);
        $title = safeCheck($_POST['title'],0);
        $content = safeCheck($_POST['content'],0);

        $attrs = array(
            "title"=>$title,
            "content"=>$content
        );
        $rs = Web_intro_advantage::update($id, $attrs);
        echo $rs;
        break;

    case 'order_advantage':
        $id = safeCheck($_POST['id'],1);
        $weight = safeCheck($_POST['weight'],1);
        $attrs = array(
            "weight"=>$weight
        );
        $rs = Web_intro_advantage::update($id, $attrs);

        echo $rs;
        break;

    case 'order_ourpresence':
        $id = safeCheck($_POST['id'],1);
        $weight = safeCheck($_POST['weight'],1);
        $attrs = array(
            "weight"=>$weight
        );
        $rs = Web_intro_aftersale_pic::update($id, $attrs);

        echo $rs;
        break;

    case 'display_status':
        $id = safeCheck($_POST['id'],1);
        $status = Web_intro_aftersale_pic::getInfoById($id)["display"];
        if($status == 1){
            $change_status  = -1;
        }else{
            $change_status  = 1;
        }

        $attrs = array(
            "display"=>$change_status
        );
        $rs = Web_intro_aftersale_pic::update($id, $attrs);
        echo $rs;
        break;

    case 'del_pic'://删除我们的风采
        $id = safeCheck($_POST['id'],1);
        $rs = Web_intro_aftersale_pic::del($id);
        if($rs >= 0){
            echo action_msg("删除成功！", 1);
        }else{
            echo action_msg("删除失败！", 101);
        }
        break;
}
?>