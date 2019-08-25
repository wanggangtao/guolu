<?php
/**
 * Created by PhpStorm.
 * User: tianyi
 * Date: 2018/10/21
 * Time: 20:06
 */
require_once('admin_init.php');
require_once('admincheck.php');

$act = safeCheck($_GET['act'], 0);
switch($act){
    case 'add'://添加修改
        //保证过来的第一条数据里，首条记录是用户名称，先插入拿到insertid（即tplid），然后用于关联数据
        $content   =  safeCheck($_POST['content'], 0);
        //锅炉厂家，用户类型和用户状态三个参数一同传过来插入到case_tpl表中
        $vender =safeCheck($_POST['vender']);
        $usertype =safeCheck($_POST['usertype']);
        $userstate =safeCheck($_POST['userstate']);
        //富文本框中的内容插入case_tplcontent表中，且attrid=parentid
        $other =$_POST['other'];
//        echo $other;
//        exit;
        try {
            $tpls = explode(',',$content);
            foreach ($tpls as $k=>$tpl){
                if(!empty($tpl)){
                    $tplcontent = explode('_',$tpl);
                    if($tplcontent[1] == 20||$tplcontent[1] == 23){
                        $attrs = array(
                            "name"=>$tplcontent[0],
                            "attrid"=>$tplcontent[1],
                            "vender"=>$vender,
                            "usertype"=>$usertype,
                            "userstate"=>$userstate,
                            "lastupdate"=>time(),
                            "operator"=>$_SESSION[$session_ADMINID],
                            "code"=>Case_tpl::createCode($tplcontent[1])
                        );
                        $tplid = Case_tpl::add($attrs);
                        //tplcontent中冗余存一份用户名称的数据，方便读取
                        $contentattrs = array(
                            'attrid'=>$tplcontent[1],
                            'tplid'=>$tplid,
                            'content'=>$tplcontent[0],
                            'addtime'=>time(),
                            'lastupdate'=>time()
                        );
                        $rs2 = Case_tplcontent::add($contentattrs);
                        //富文本框中的内容插入case_tplcontent表中，且attrid=parentid
                        if(!empty($other)) {
                            $other_attrid = Case_attr::getInfoById($tplcontent[1]);
                            $contentattrs = array(
                                'attrid'=>$other_attrid[0]['parentid'],
                                'tplid'=>$tplid,
                                'content'=>$other,
                                'addtime'=>time(),
                                'lastupdate'=>time()
                            );
                            $rs3 = Case_tplcontent::add($contentattrs);
                        }
                    }else{
                        $contentattrs = array(
                            'attrid'=>$tplcontent[1],
                            'tplid'=>$tplid,
                            'content'=>$tplcontent[0],
                            'addtime'=>time(),
                            'lastupdate'=>time()
                        );
                        $rs2 = Case_tplcontent::add($contentattrs);
                        $rs3 = -1;
                    }
                }
            }

            echo action_msg("添加成功",1);

        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;
    case  'edit':
        $content   =  safeCheck($_POST['content'], 0);
        $tplid   =  safeCheck($_POST['tplid']);
        $vender =safeCheck($_POST['vender']);
        $usertype =safeCheck($_POST['usertype']);
        $userstate =safeCheck($_POST['userstate']);
        $other =$_POST['other'];
        try {
            $tpls = explode(',',$content);
            foreach ($tpls as $k=>$tpl){
                if(!empty($tpl)){
                    $tplcontent = explode('_',$tpl);
                    if($tplcontent[1] == 20||$tplcontent[1] == 23){
                        $attrs = array(
                            "name"=>$tplcontent[0],
                            "vender"=>$vender,
                            "usertype"=>$usertype,
                            "userstate"=>$userstate,
                            "lastupdate"=>time()
                        );
                        $rs1 = Case_tpl::update($tplid,$attrs);
                        //tplcontent中冗余存一份用户名称的数据，方便读取
                        $contentattrs = array(
                            'content'=>$tplcontent[0],
                            'lastupdate'=>time()
                        );
                        $rs2 = Case_tplcontent::updateByAttridandtplid($tplcontent[1],$tplid,$contentattrs);
                        //富文本框中的内容插入case_tplcontent表中，且attrid=parentid
                        if(!empty($other)) {
                            $other_info = Case_attr::getInfoById($tplcontent[1]);
                            $other_attrid=$other_info[0]['parentid'];
                            //判断富文本框中有没有内容有没有，没有的话，添加
                            $other_content=Case_tplcontent::getInfoByAttridAndTplid($other_attrid,$tplid);
                            if(!empty($other_content)){
                                $contentattrs = array(
                                    'content' => $other,
                                    'lastupdate' => time()
                                );
                                $rs3 = Case_tplcontent::updateByAttridandtplid($other_attrid, $tplid, $contentattrs);
                            }else {
                                $contentattrs = array(
                                    'attrid'=>$other_attrid,
                                    'tplid'=>$tplid,
                                    'content' => $other,
                                    'addtime'=>time(),
                                    'lastupdate' => time()
                                );
                                $rs3 = Case_tplcontent::add($contentattrs);
                            }
                        }else{
                            $rs3=-1;
                        }
                    }else{
                        //判断有没有，没有的话，添加
                        $contentinfo = Case_tplcontent::getInfoByAttridAndTplid($tplcontent[1],$tplid);
                        if(!empty($contentinfo)){
                            $contentattrs = array(
                                'content'=>$tplcontent[0],
                                'lastupdate'=>time()
                            );
                            $rs2 = Case_tplcontent::updateByAttridandtplid($tplcontent[1],$tplid,$contentattrs);
                        }else{
                            $contentattrs = array(
                                'attrid'=>$tplcontent[1],
                                'tplid'=>$tplid,
                                'content'=>$tplcontent[0],
                                'addtime'=>time(),
                                'lastupdate'=>time()
                            );
                            $rs2 = Case_tplcontent::add($contentattrs);
                        }
                    }
                }
            }

            echo action_msg("修改成功",1);

        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;

    case 'addattr':
        $name   =  safeCheck($_POST['name'], 0);
        $parentid     =  safeCheck($_POST['parentid']);
        $level =  safeCheck($_POST['level']);
        try {
            $attrs = array(
                "name"=>$name,
                "parentid"=>$parentid,
                "level"=>$level,
                "addtime"=>time(),
                "operator"=>$_SESSION[$session_ADMINID],
                "isshow"=>1
            );
            $rs = Case_attr::add($attrs);
            if($rs > 0){
                echo action_msg("添加成功",1);
            }
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;


    case 'del'://删除
        $tplid   =  safeCheck($_POST['tplid']);
        try {
            //先删除tpl表，再删除tplcontent表
            Case_tplcontent::delByTplid($tplid);
            Case_tpl::del($tplid);
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
                //删掉属性所有的数据，再删掉这个属性
                Case_tplcontent::delByAttrid($array[$index]);
                Case_attr::del($array[$index]);
            }
        }
        echo action_msg("删除成功",1);
        break;

    case 'editattr'://修改属性值
        $name   =  safeCheck($_POST['name'], 0);
        $id =  safeCheck($_POST['id']);
        try {
            $attrs = array(
                "name"=>$name
            );
            $rs = Case_attr::update($id, $attrs);
            if($rs >= 0){
                echo action_msg("修改成功",1);
            }
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;

        //--------------------------用户类型button------------------------
    case 'addusertype'://添加用户类型
        $name   =  safeCheck($_POST['name'], 0);
        $parentid     =  safeCheck($_POST['parentid']);
        $level =  safeCheck($_POST['level']);
        try {
            $attrs = array(
                "name"=>$name,
                "parentid"=>$parentid,
                "level"=>$level,
                "addtime"=>time(),
                "operator"=>$_SESSION[$session_ADMINID],
                "isshow"=>1
            );
            $rs = Case_attr::add($attrs);
            if($rs > 0){
                echo action_msg("添加成功",1);
            }
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;

    case 'editusertype'://修改用户类型
        $name   =  safeCheck($_POST['name'], 0);
        $id =  safeCheck($_POST['id']);
        try {
            $attrs = array(
                "name"=>$name
            );
            $rs = Case_attr::update($id, $attrs);
            if($rs >= 0){
                echo action_msg("修改成功",1);
            }
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;
    case 'delt'://删除用户类型
        $idStr = safeCheck($_POST['idStr'], 0);
        $array = explode(',', $idStr);
        if($array && count($array)){
            for ($index = 0; $index < (count($array) -1);$index++){
                //删掉属性所有的数据，再删掉这个属性
                Case_tplcontent::delByAttrid($array[$index]);
                Case_attr::del($array[$index]);
            }
        }
        echo action_msg("删除成功",1);
        break;
}
?>