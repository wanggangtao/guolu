<?php
/**
 * Created by PhpStorm.
 * User: dlk
 * Date: 18/7/8
 * Time: 上午9:52
 */



try {
    $id =  isset($_POST['msgid'])?safeCheck($_POST['msgid']):0;

    $messageinfo =  Message_info::getInfoById($id);
    if(empty($messageinfo))
    {
        throw new MyException("信息不存在!",421);
    }
    if($uid != $messageinfo['recipients']) throw new MyException("无权限查看该站内信",422);
    $msgconten = $messageinfo['content'];
    $arr1 = explode("<a", $msgconten);
    $idstr = "";
    if(count($arr1) >=2){
        $arr2 = explode("?id=", $arr1[1]);
        $arr3 = explode('"', $arr2[1]);
        $idstr = $arr3[0];
    }
    $messageinfo['projectid'] = $idstr;
    $status = 0;
    $level = 0;
    $del_flag = 0;
    $name="";
    if(!empty($idstr)){
        $projectinfo = Project::getInfoById($idstr);
        $status = $projectinfo['status'];
        $level = $projectinfo['level'];
        $del_flag = $projectinfo['del_flag'];
        $name = $projectinfo['name'];
    }
    $messageinfo['project_name'] = $name;
    $messageinfo['project_level'] = $level;
    $messageinfo['project_status'] = $status;
    $messageinfo['project_del_flag'] = $del_flag;
    $messageinfo['date'] = date('Y年m月d日 H:i:s',$messageinfo['addtime']);
    $messageinfo['content'] = $arr1[0];
    unset($messageinfo["addtime"]);
    if($messageinfo['openflag'] == 0){
        $arr = array('openflag' => 1);
        Message_info::update($id, $arr);
    }
    echo action_msg_data(API::SUCCESS_MSG,API::SUCCESS,$messageinfo);

    //检查手机验证码

}catch (MyException $e){
    $api->ApiError($e->getCode(), $e->getMessage());
}


?>
