<?php
/**
 * Created by PhpStorm.
 * User: tianyi
 * Date: 2018/10/21
 * Time: 15:38
 */
require_once('admin_init.php');
require_once('admincheck.php');

$act = safeCheck($_GET['act'], 0);
switch($act){
    case 'add'://添加修改
        $name   =  safeCheck($_POST['name'], 0);
        $content   =  $_POST['content'];
        $intelligence   =  $_POST['intelligence'];
        $logo   =  safeCheck($_POST['logo'], 0);
        $attrid   =  safeCheck($_POST['attrid']);

        try {


            $attrs = array(
                "name"=>$name,
                "attrid"=>$attrid,
                "lastupdate"=>time(),
                "operator"=>$_SESSION[$session_ADMINID],
                "code"=>Case_tpl::createCode($attrid)
            );
            //先插入case_attr表拿到insertid，作为attrid插入到case_tplcontent表
            $rs = Case_tpl::add($attrs);

            if($rs > 0){
                $contentattrs = array(
                    'attrid'=>8,//case_attr表中，“企业简介”属性id=8
                    'tplid'=>$rs,
                    'content'=>$content,
                    'addtime'=>time(),
                    'lastupdate'=>time()
                );
                $contentattrs2 = array(
                    'attrid'=>9,//case_attr表中，“企业资质”属性id=9
                    'tplid'=>$rs,
                    'content'=>$intelligence,
                    'addtime'=>time(),
                    'lastupdate'=>time()
                );
                $contentattrs3 = array(
                    'attrid'=>10,//case_attr表中，“企业logo”属性id=10
                    'tplid'=>$rs,
                    'content'=>$logo,
                    'addtime'=>time(),
                    'lastupdate'=>time()
                );
                $rs2 = Case_tplcontent::add($contentattrs);
                $rs3 = Case_tplcontent::add($contentattrs2);
                $rs4 = Case_tplcontent::add($contentattrs3);
                if($rs2>0 && $rs3>0 && $rs4>0){
                    echo action_msg("添加成功",1);
                }
            }

        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;
    case  'edit':
        $name   =  safeCheck($_POST['name'], 0);
        $content   =  $_POST['content'];
        $intelligence   =  $_POST['intelligence'];
        $logo   =  safeCheck($_POST['logo'], 0);
        $id =  safeCheck($_POST['id']);

        try {


            $attrs['name'] = $name;
            $rs = Case_tpl::update($id, $attrs);
//            $con_attrid=8;//case_attr表中，“企业简介”属性id=8
//            $inl_attrid=9;//case_attr表中，“企业资质”属性id=9
//            $pic_attrid=10;//case_attr表中，“企业logo”属性id=10
            if($rs >= 0){
                $contentttrs = array(
                    'content'=>$content,
                    'lastupdate'=>time()
                );
                $contentttrs2 = array(
                    'content'=>$intelligence,
                    'lastupdate'=>time()
                );
                $contentttrs3 = array(
                    'content'=>$logo,
                    'lastupdate'=>time()
                );
                $rs2 = Case_tplcontent::updateByAttridandtplid(8,$id,$contentttrs);
                $rs3 = Case_tplcontent::updateByAttridandtplid(9,$id,$contentttrs2);
                $rs4 = Case_tplcontent::updateByAttridandtplid(10,$id,$contentttrs3);
                if($rs2 && $rs3 && $rs4){
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