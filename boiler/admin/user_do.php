<?php
/**
 * 用户处理  user_do.php
 *
 * @version       v0.01
 * @create time   2018/6/27
 * @update time
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */
require_once('admin_init.php');
require_once('admincheck.php');

$act = safeCheck($_GET['act'], 0);
switch($act){
    case 'add'://添加用户
        $account    =  safeCheck($_POST['account'], 0);
        $password   =  safeCheck($_POST['password'], 0);
        $name       =  safeCheck($_POST['name'], 0);
        $birthday   =  safeCheck($_POST['birthday'], 0);
        $headimg    =  safeCheck($_POST['headimg'], 0);
        $parent     =  safeCheck($_POST['parentid']);
        $role       =  safeCheck($_POST['role']);
        $department =  safeCheck($_POST['department']);

        if(!ParamCheck::is_mobile($account)){
            echo action_msg("手机号格式不正确！",103);
            die();
        }
        if(ParamCheck::is_weakPwd($password)){
            echo action_msg("密码格式不正确！",103);
            die();
        }
        if($role == 2 && $department != 0){//每个部门销售部经理只能有一个，添加前先判断该部是否已有
            $rs = User::getInfoForParent($department, 1);
            if(!empty($rs)) {
                echo action_msg('当前部门已有销售经理！', 103);
                die();
            }
        }

        try {
            $userinfo = User::getInfoByAccount($account);
            if(!empty($userinfo)){
                echo action_msg("账号已存在",103);
                die();
            }
            $attrs = array(
                "account"=>$account,
                "name"=>$name,
                "password"=>$password,
                "birthday"=>$birthday,
                "headimg"=>$headimg,
                "parent"=>$parent,
                "role"=>$role,
                "department"=>$department,
                "addtime"=>time(),
                "lastupdate"=>time()
            );
            $rs = User::add($attrs);

            if ($role==1) {
                $room = Chat_room::AddRoomByUid($rs);
            }

            if($role==3){
                $room_list = Chat_room::getRoomList();
                foreach ($room_list as $k=>$val){
                    $attr = array(
                        "room_id"=>$val,
                        "uid"=>$rs,
                        "addtime"=>time()
                    );
                    Chat_room_msg_config::add($attr);
                }
            }elseif ($role==4){
             $uid = User::getIdByDepartment($department);
             foreach ($uid as $k=>$val){
                 $info = Chat_room::getListByUId($val);
                 foreach ($info as $key=>$value){
                     $attr = array(
                         "room_id"=>$value,
                         "uid"=>$rs,
                         "addtime"=>time()
                     );
                     Chat_room_msg_config::add($attr);
                 }
             }

            }
            echo action_msg("添加成功",1);
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;

    case 'edit':
        $id =  safeCheck($_POST['id']);
        $account    =  safeCheck($_POST['account'], 0);
        $name       =  safeCheck($_POST['name'], 0);
        $birthday   =  safeCheck($_POST['birthday'], 0);
        $headimg    =  safeCheck($_POST['headimg'], 0);
        $parent     =  safeCheck($_POST['parentid']);
        $role       =  safeCheck($_POST['role']);
        $status     =  safeCheck($_POST['status']);
        $department =  safeCheck($_POST['department']);

        try {
            if(!ParamCheck::is_mobile($account)){
                echo action_msg("手机号格式不正确！",103);
                die();
            }
            $userinfo = Table_user::getInfoByAccount($account);
            if(!empty($userinfo) && $userinfo['id'] != $id){
                echo action_msg("账号已存在",103);
                die();
            }
            $attrs = array(
                "account"=>$account,
                "name"=>$name,
                "birthday"=>$birthday,
                "headimg"=>$headimg,
                "parent"=>$parent,
                "role"=>$role,
                "status"=>$status,
                "department"=>$department,
                "lastupdate"=>time()
            );

            $rs = User::update($id, $attrs);
            $room_list = Chat_room::getListByUId($id);
            if (!empty($room_list)){
                foreach ($room_list as $key=>$value){
                        $attr = array(
                            'principal_uname'=>$name
                        );
                    Chat_room::update($value,$attr);
                }
            }
            echo action_msg("修改成功",1);
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;

    case 'del'://删除用户
        $id = safeCheck($_POST['id']);

        try {
            $parentlist = User::getInfoByParentid($id);
            if($parentlist){
                echo action_msg("存在其他用户的主管是该用户，请变更其他人作为主管后，再删除该用户",-1);
                die();
            }
            $rs = User::del($id);
            echo action_msg("删除成功",1);
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;

    case 'getParentuser':
        $department = safeCheck($_POST['department']);
        $role = safeCheck($_POST['role']);

        try {
            if($role == 2 and $department != 0){
                $rs = User::getInfoForParent($department, 1);
                if(!empty($rs)){
                    echo action_msg('当前部门已有销售经理！',0);
                    break;
                }else{
                    $department = 0;
                    $rs = User::getInfoForParent($department, $role);
                    echo action_msg($rs,1);
                    break;
                }
            }

            if($role == 4 and $department != 0){
                $rs = User::getInfoForParent($department, 1);
                echo action_msg($rs,1);
                break;
            }

            $rs = User::getInfoForParent($department, $role);
            echo action_msg($rs,1);
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;

    case 'reset'://重置密码
        $id = safeCheck($_POST['id']);
        $newpass = safeCheck($_POST['newpass'], 0);
        if(ParamCheck::is_weakPwd($newpass)){
            echo action_msg("密码格式不正确！",103);
            die();
        }
        try{
            $r = User::resetPwd($id, $newpass);
            echo action_msg("重置成功",1);
        }catch(MyException $e){
            echo $e->jsonMsg();
        }
        break;

    case 'checkAccount'://检查联系人
        $id            = safeCheck($_POST['id']);
        $account       = safeCheck($_POST['account'], 0);
        $code = 1;
        $msg = "";
        try {
            if(!ParamCheck::is_mobile($account)){
                $code = 103;
                $msg = "手机号格式不正确!";
            }
            $userinfo = User::getInfoByAccount($account);
            if(empty($id)){
                if(!empty($userinfo)){
                    $code = 101;
                    $msg = "该账号（手机号）已存在!";
                }
            }else{
                if(!empty($userinfo)){
                    if($userinfo['id'] != $id){
                        $code = 102;
                        $msg = "该账号（手机号）已存在!";
                    }
                }
            }
            echo json_encode_cn(array('code' => $code, 'msg' => $msg));
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;
}
?>