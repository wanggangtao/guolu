<?php
/**
 * Created by PhpStorm.
 * User: zhima
 * Date: 2018/7/3
 * Time: 下午7:02
 */

try {
    $projectid = isset($_POST['project_id'])?safeCheck($_POST['project_id']):'0';

    if(empty($projectid)) throw new MyException("缺少项目ID参数",101);
    if(!Project::checkProjectForUser($uid,$projectid)) throw new MyException("无权限查看该项目",1501);
    $projectinfo = Project::getInfoById($projectid) ;

    if(empty($projectinfo))
        throw new MyException("项目信息不存在",101);

    $resData = array();
    $loginuserinfo = User::getInfoById($uid);

    if($loginuserinfo['role'] == 3){
        $userlist = User::getPageList(1, 999, 1, '', 1, 0, 0);
    }else{
        $userlist = User::getInfoByParentid($uid);
        $userlist[] = $loginuserinfo;
    }

    if(!empty($userlist))
    {
        foreach ($userlist as $item)
        {
            $dataItem = array();
            $dataItem["id"] = $item["id"];
            $dataItem["name"] = $item['name'];
            $resData["data"][] = $dataItem;
        }
    }

    echo action_msg_data(API::SUCCESS_MSG, API::SUCCESS, $resData);

}catch (MyException $e){
    $api->ApiError($e->getCode(), $e->getMessage());
}
