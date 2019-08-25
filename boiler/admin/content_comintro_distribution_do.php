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
    case 'introductiondel'://删除
        $id = safeCheck($_POST['id'],1);
        $rs = Web_introduction::del($id);
        if($rs >= 0){
            echo action_msg("删除成功！", 1);
        }else{
            echo action_msg("删除失败！", 101);
        }
        break;

    case 'introductionadd':
        $kind = safeCheck($_POST['kind'],0);
        $content = $_POST['content'];
        $attrs = array(
            "kind"=>$kind,
            "content"=>$content,
        );
        $rs = Web_introduction::add($attrs);
        if($rs > 0){
            echo action_msg("添加成功！", 1);
        }else{
            echo action_msg("添加失败！", 101);
        }
        break;

    case 'ad_edit':
        $id = safeCheck($_POST['id'],1);
        $title = safeCheck($_POST['title'],0);
        $purl = safeCheck($_POST['purl'],0);
        $content = safeCheck($_POST['content'],0);

        $attrs = array(
            "title"=>$title,
            "purl"=>$purl,
            "content"=>$content
        );
        $rs = Web_intro_advantage::update($id, $attrs);
   //     echo "======".$id;
        echo $rs;
        break;

    case 'order':
        $id = safeCheck($_POST['id'],1);
        $weight = safeCheck($_POST['weight'],1);
        $attrs = array(
            "weight"=>$weight
        );
        $rs = Web_intro_advantage::update($id, $attrs);
        echo $rs;
        break;

}
?>