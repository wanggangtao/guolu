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
require_once('admin_init.php');
require_once('admincheck.php');

$act = safeCheck($_GET['act'], 0);
switch($act){
    case 'add'://添加管理员
        $keyword   =  safeCheck($_POST['keyword'], 0);
        $content  =  safeCheck($_POST['content'], 0);
        $category =  safeCheck($_POST['category']);



        try {

            $attrs = array(
              "keyword"=>$keyword,
              "content"=>$content,
              "category"=>$category,
              "addtime"=>time(),
              "operator"=>$_SESSION[$session_ADMINID],
            );
            $rs = fact::add($attrs);
            echo action_msg("添加成功",1);
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;

    case 'edit':
        $keyword   =  safeCheck($_POST['keyword'], 0);
        $content  =  safeCheck($_POST['content'], 0);
        $id  =  safeCheck($_POST['id'], 0);
        $category =  safeCheck($_POST['category']);


        try {

            $attrs = array(
                "keyword"=>$keyword,
                "content"=>$content,
                "operator"=>$_SESSION[$session_ADMINID],
                "category"=>$category,

            );

            $rs = fact::update($id, $attrs);
            echo action_msg("修改成功",1);
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;

    case 'del'://删除管理员
        $id = safeCheck($_POST['id']);

        try {
            $rs = fact::del($id);
            echo action_msg("删除成功",1);
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;

    case 'getFact':
        $category = safeCheck($_POST['category']);

        try {
            $rs = fact::getPageList(0,0,"",$category);
            echo action_msg($rs,1);
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;


    case 'getInfo'://删除管理员
        $code = safeCheck($_POST['code'],0);

        try {
            $rs = fact::getInfoByCode($code);
            echo action_msg($rs,1);
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;


}
?>