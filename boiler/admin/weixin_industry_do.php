<?php
/**
 * Created by PhpStorm.
 * User: hhx
 * Date: 2019/3/20
 * Time: 17:08
 */
require_once('admin_init.php');
require_once('admincheck.php');

$act = safeCheck($_GET['act'],0);
$params =array();
switch($act){
    case 'add':

        $params['title'] = safeCheck($_POST['title'],0);
        $params['content'] = safeCheck($_POST['content'],0);
        $params['picurl'] = safeCheck($_POST['picurl'],0);
        $params['if_top'] = safeCheck($_POST['if_top']);
        $params['addtime'] = time();

        try{
            weixin_situation::add($params);
            echo  action_msg("添加成功", 1);
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;
    case 'edit':

        $id = safeCheck($_POST['eid'],0);


        $params['title'] = safeCheck($_POST['title'],0);
        $params['content'] = safeCheck($_POST['content'],0);
        $params['picurl'] = safeCheck($_POST['picurl'],0);
        $params['if_top'] = safeCheck($_POST['if_top']);
        //$params['addtime'] = time();

        try{
            weixin_situation::edit($params,$id);
            echo action_msg('修改成功',1);
        }catch(MyException $e){
            echo $e->jsonMsg();
        }
        break;

    case 'del':
        $id = safeCheck($_POST['id']);
        try{
            weixin_situation::del($id);
            echo action_msg("删除成功", 1);
        }catch(MyException $e){
            echo $e->jsonMsg();
        }
        break;
    case 'top':

        $id = safeCheck($_POST['id'],0);
        $row = weixin_situation::getInfoById($id);
        if($row['if_top'] == 1){
            $params['if_top'] = 0 ;
        }else {
            $params['if_top'] = 1 ;
        }

        try{
            weixin_situation::edit($params,$id);
            echo action_msg("修改成功", 1);
        }catch(MyException $e){
            echo $e->jsonMsg();
        }
}