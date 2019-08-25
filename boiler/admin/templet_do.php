<?php
/**
 * Created by PhpStorm.
 * User: tianyi
 * Date: 2018/10/21
 * Time: 16:45
 */
require_once('admin_init.php');
require_once('admincheck.php');

$act = safeCheck($_GET['act'], 0);
switch($act){
    case 'add'://添加修改
        $name   =  safeCheck($_POST['name'], 0);
        $content   =  stripslashes(safeCheck($_POST['content'], 0));
        $attrid   =  safeCheck($_POST['attrid']);

        try {


            $attrs = array(
                "name"=>$name,
                "attrid"=>$attrid,
                "lastupdate"=>time(),
                "operator"=>$_SESSION[$session_ADMINID],
            );
            //先插入case_attr表拿到insertid，作为attrid插入到case_tplcontent表
            $rs = Case_tpl::add($attrs);
            if($rs > 0){
                $contentattrs = array(
                    'attrid'=>$attrid,
                    'tplid'=>$rs,
                    'content'=>$content,
                    'addtime'=>time(),
                    'lastupdate'=>time()
                );
                $rs2 = Case_tplcontent::add($contentattrs);
                if($rs2>0){
                    echo action_msg("添加成功",1);
                }
            }

        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;
    case  'edit':
        $name   =  safeCheck($_POST['name'], 0);
        $content   =  stripslashes(safeCheck($_POST['content'], 0));
        $id =  safeCheck($_POST['id']);

        try {


            $attrs['name'] = $name;
            $rs = Case_tpl::update($id, $attrs);
            if($rs >= 0){
                $contentttrs = array(
                    'content'=>$content,
                    'lastupdate'=>time()
                );
                $rs2 = Case_tplcontent::updateByAttridandtplid(7,$id,$contentttrs);
                if($rs2){
                    echo action_msg("修改成功",1);
                }
            }

        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;
        break;

    case 'addvalue':
        $name   =  safeCheck($_POST['name'], 0);
        $code   =  safeCheck($_POST['code'], 0);
        $id     =  safeCheck($_POST['id']);
        $modelid =  safeCheck($_POST['modelid']);
        $pic =  safeCheck($_POST['pic'], 0);
        try {
            $attrs = array(
                "name"=>$name,
                "code"=>$code,
                "parent"=>$id,
                "addtime"=>time(),
                "model"=>$modelid,
                "operator"=>$_SESSION[$session_ADMINID],
                "pic"=>$pic
            );
            $rs = Dict::add($attrs);
            if($rs > 0){
                echo action_msg("添加成功",1);
            }
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;
    case 'del'://删除
        $id = safeCheck($_POST['id']);

        try {
            //先删除内容，再删除属性名称
            Case_tplcontent::delByTplid($id);
            Case_tpl::del($id);
            echo action_msg("删除成功",1);
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;

    case 'dels'://删除
        $idStr = safeCheck($_POST['idStr'], 0);
        $array = explode(',', $idStr);
        if($array && count($array)){
            for ($index = 0; $index < (count($array) -1);$index++){
                Dict::del($array[$index]);
            }
        }
        echo action_msg("删除成功",1);
        break;

    case 'editvalue'://修改属性值
        $name   =  safeCheck($_POST['name'], 0);
        $id =  safeCheck($_POST['id']);
        $pic =  safeCheck($_POST['pic'], 0);
        try {
            $attrs = array(
                "name"=>$name,
                "pic"=>$pic
            );
            $rs = Dict::update($id, $attrs);
            if($rs >= 0){
                echo action_msg("修改成功",1);
            }
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;
}

?>