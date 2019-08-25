<?php
/**
 * 规则处理  services_label_list_do.php
 *
 * @version       v0.01
 * @create time   2019/3/20
 * @update time   2019/3/20
 * @author        guanxin
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */
require_once('admin_init.php');
require_once('admincheck.php');

$act = safeCheck($_GET['act'], 0);
switch($act){
    case 'add'://添加
        $after   =  safeCheck($_POST['after'], 0);
        $before  =  safeCheck($_POST['before'], 0);
        $keyword =  safeCheck($_POST['keyword'],0);
        $first_charter = getFirstCharter($keyword);
        try {

            $attrs = array(
                "before"=>$before,
                "after"=>$after,
                "keyword"=>$keyword,
                "addtime"=>time(),
                "operator"=>$_SESSION[$session_ADMINID],
                "first_charter"=>$first_charter
            );
            $rs = Service_label::add($attrs);
            echo action_msg("添加成功",1);
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;

    case 'edit':
        $after   =  safeCheck($_POST['after'], 0);
        $before  =  safeCheck($_POST['before'], 0);
        $keyword =  safeCheck($_POST['keyword'],0);
        $id =  safeCheck($_POST['id']);


        try {

            $attrs = array(
                "before"=>$before,
                "after"=>$after,
                "keyword"=>$keyword,
                "operator"=>$_SESSION[$session_ADMINID],
            );

            $rs = Service_label::update($id, $attrs);
            echo action_msg("修改成功",1);
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;

    case 'del'://删除
        $id = safeCheck($_POST['id']);

        try {
            $attrs = array(
                "status"=>-1,
            );

            $rs = Service_label::update($id, $attrs);
            echo action_msg("删除成功",1);
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;
}
?>