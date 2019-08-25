<?php
/**
 * Created by PhpStorm.
 * User: zhima
 * Date: 2018/7/3
 * Time: 下午7:02
 */

try {
    $projectid = isset($_POST['projectid'])?safeCheck($_POST['projectid']):'0';
    $page = isset($_POST['page'])?safeCheck($_POST['page']):1;
    $pageSize  = isset($_POST['pageSize'])?safeCheck($_POST['pageSize']):15;

    if(empty($projectid)) throw new MyException("缺少项目ID参数",101);

    $projectinfo = Project::getInfoById($projectid) ;

    if(empty($projectinfo))
        throw new MyException("项目信息不存在",101);

    if(!Project::checkProjectForUser($uid,$projectinfo["id"])) throw new MyException("无权限查看该项目",1039);

    $resData = array();
    $project_one = Project_one::getInfoByProjectId($projectinfo["id"]);
    $resData["total"]  = (Project_one::getPageSameList(1, 10, 0, $project_one['project_name'], $project_one['project_detail'], '', '', '', '', $projectinfo['notsame_id'])) - 1;
    $result  = Project_one::getPageSameList($page, $pageSize, 1, $project_one['project_name'], $project_one['project_detail'], '', '', '', '', $projectinfo['notsame_id']);

    if(!empty($result))
    {
        foreach ($result as $item)
        {
            if($item['project_id'] != $projectid){
                $dataItem = array();
                $thisproject = Project::getInfoById($item['project_id']);
                $dataItem["projectid"] = $thisproject["id"];
                $dataItem["projectcode"] = $thisproject["code"];
                $dataItem["projectname"] = $thisproject["name"];
                $dataItem["address"] = $thisproject["detail"];

                $dataItem["type"] = $thisproject["type"];

                $typeName = "";
                if(!empty($thisproject["type"]))
                {
                    $typeInfo = project_type::getInfoById($thisproject["type"]);

                    if(!empty($typeInfo))
                    {
                        $typeName = $typeInfo["name"];
                    }

                }
                $dataItem["typeName"] = $typeName;

                $dataItem["addtime"] = date("Y-m-d H:i",$thisproject["addtime"]);
                $dataItem["lastupdate"] = date("Y-m-d H:i:s",$thisproject["lastupdate"]);

                $dataItem["level"] = $thisproject["level"];
                $dataItem["status"] = $thisproject["status"];

                $dataItem["stop_flag"] = $thisproject["stop_flag"];
                $dataItem["statusName"] = isset($ARRAY_project_status_review[$thisproject["status"]])?$ARRAY_project_status_review[$thisproject["status"]]:"未知";

                $dataItem["userid"] = $thisproject["user"];
                $puserinfo = User::getInfoById($thisproject["user"]);
                $dataItem["username"] = $puserinfo["name"];
                $resData["data"][] = $dataItem;
            }
        }
    }

    echo action_msg_data(API::SUCCESS_MSG, API::SUCCESS, $resData);

}catch (MyException $e){
    $api->ApiError($e->getCode(), $e->getMessage());
}
