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
        $intelligence   = $_POST['intelligence'];
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
                    'attrid'=>16,//case_attr表中，“售后介绍”属性id=16
                    'tplid'=>$rs,
                    'content'=>$content,
                    'addtime'=>time(),
                    'lastupdate'=>time()
                );
                $contentattrs2 = array(
                    'attrid'=>17,//case_attr表中，“售后资质”属性id=17
                    'tplid'=>$rs,
                    'content'=>$intelligence,
                    'addtime'=>time(),
                    'lastupdate'=>time()
                );
                $rs2 = Case_tplcontent::add($contentattrs);
                $rs3 = Case_tplcontent::add($contentattrs2);
                if($rs2>0 && $rs3>0){

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
        $id =  safeCheck($_POST['id']);

        try {


            $attrs['name'] = $name;
            $rs = Case_tpl::update($id, $attrs);
//            $case_attrid=16;//case_attr表中，“售后介绍”属性id=16
//            $caseattrid=17;//case_attr表中，“售后资质”属性id=17
            if($rs >= 0){
                $contentttrs = array(
                    'content'=>$content,
                    'lastupdate'=>time()
                );
                $contentttrs2 = array(
                    'content'=>$intelligence,
                    'lastupdate'=>time()
                );
                $rs2 = Case_tplcontent::updateByAttridandtplid(16,$id,$contentttrs);
                $rs3 = Case_tplcontent::updateByAttridandtplid(17,$id,$contentttrs2);
                if($rs2 && $rs3){
                    echo action_msg("修改成功",1);
                }
            }

        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;
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

}
?>