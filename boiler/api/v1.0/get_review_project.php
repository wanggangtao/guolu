<?php
/**
 * Created by PhpStorm.
 * User: zhima
 * Date: 2018/7/3
 * Time: 下午7:02
 */

try {


    $page =  isset($_POST['page'])?safeCheck($_POST['page'],0):0;
    $pageSize =  isset($_POST['pageSize'])?safeCheck($_POST['pageSize'],0):1000;
    $name =  isset($_POST['name'])?safeCheck($_POST['name'],0):"";
    $username =  isset($_POST['projectuser'])?safeCheck($_POST['projectuser'],0):"";
    $type = isset($_POST['type'])?safeCheck($_POST['type']):0;
    $level = isset($_POST['level'])?safeCheck($_POST['level']):-1;
    $status = isset($_POST['status'])?safeCheck($_POST['status']):2;
    $startDate =  isset($_POST['startDate'])?safeCheck($_POST['startDate'],0):0;
    $endDate =  isset($_POST['endDate'])?safeCheck($_POST['endDate'],0):0;


    if(empty($uid)) throw new MyException("缺少用户uid参数",101);

    $userInfo = user::getInfoById($uid);


    if(empty($userInfo))
    {
        throw new MyException("用户信息不存在",101);
    }


    $resData = array();

    $startTime = 0;
    if(!empty($startDate))
    {
        $startTime = strtotime($startDate);
    }

    $endTime = 0;
    if(!empty($endDate))
    {
        $endTime = strtotime($endDate);
    }
    if ($userInfo['role']==3){
        $uid=0;
    }
    $result = Project::getPageReviewList($page, $pageSize, 1, $name, $type, $status, $level, $uid, '', $startTime, $endTime);

    $total1 = Project::getPageReviewList($page, $pageSize, 0, $name, $type, $status, $level, $uid, '', $startTime, $endTime);
    $pidarr = array();
    if(!empty($result))
    {
        foreach ($result as $item)
        {
            $dataItem = array();
            $pidarr[] = $item["id"];
            $dataItem["projectid"] = $item["id"];
            $dataItem["projectcode"] = $item["code"];
            $dataItem["projectname"] = $item["name"];
            $dataItem["address"] = $item["detail"];

            $dataItem["type"] = $item["type"];

            $typeName = "";
            if(!empty($item["type"]))
            {
                $typeInfo = project_type::getInfoById($item["type"]);

                if(!empty($typeInfo))
                {
                    $typeName = $typeInfo["name"];
                }

            }
            $dataItem["typeName"] = $typeName;

            $dataItem["addtime"] = date("Y-m-d H:i",$item["addtime"]);
            $dataItem["lastupdate"] = date("Y-m-d H:i:s",$item["lastupdate"]);

            $dataItem["level"] = $item["level"];
            $dataItem["status"] = $item["status"];

            $dataItem["stop_flag"] = $item["stop_flag"];
            $dataItem["statusName"] = isset($ARRAY_project_status_review[$item["status"]])?$ARRAY_project_status_review[$item["status"]]:"未知";
            $dataItem["userid"] = $item["user"];
            $puserinfo = User::getInfoById($item["user"]);
            $dataItem["username"] = $puserinfo["name"];

            $project_one = Project_one::getInfoByProjectId($item["id"]);
            $dataItem["same_num"]  = (Project_one::getPageSameList(1, 10, 0, $project_one['project_name'], $project_one['project_detail'], '', '', '', '', $item['notsame_id'])) - 1;

            $resData["data"][] = $dataItem;

        }
    }
    $result2 = Project::getPageReviewList($page, $pageSize, 1, '', $type, $status, $level, $uid, $name, $startTime, $endTime);

    $total2 = Project::getPageReviewList(1, 10, 0, '', $type, $status, $level, $uid, $name, $startTime, $endTime);
    if(!empty($result2))
    {
        foreach ($result2 as $item)
        {
            $dataItem = array();
            if(!in_array($item["id"], $pidarr)){
                $pidarr[] = $item["id"];
                $dataItem["projectid"] = $item["id"];
                $dataItem["projectcode"] = $item["code"];
                $dataItem["projectname"] = $item["name"];
                $dataItem["address"] = $item["detail"];

                $dataItem["type"] = $item["type"];

                $typeName = "";
                if(!empty($item["type"]))
                {
                    $typeInfo = project_type::getInfoById($item["type"]);

                    if(!empty($typeInfo))
                    {
                        $typeName = $typeInfo["name"];
                    }

                }
                $dataItem["typeName"] = $typeName;

                $dataItem["addtime"] = date("Y-m-d H:i",$item["addtime"]);
                $dataItem["lastupdate"] = date("Y-m-d H:i:s",$item["lastupdate"]);

                $dataItem["level"] = $item["level"];
                $dataItem["status"] = $item["status"];

                $dataItem["stop_flag"] = $item["stop_flag"];
                $dataItem["statusName"] = isset($ARRAY_project_status_review[$item["status"]])?$ARRAY_project_status_review[$item["status"]]:"未知";
                $dataItem["userid"] = $item["user"];
                $puserinfo = User::getInfoById($item["user"]);
                $dataItem["username"] = $puserinfo["name"];

                $project_one = Project_one::getInfoByProjectId($item["id"]);
                $dataItem["same_num"]  = (Project_one::getPageSameList(1, 10, 0, $project_one['project_name'], $project_one['project_detail'], '', '', '', '', $item['notsame_id'])) - 1;

                $resData["data"][] = $dataItem;

            }
        }

    }

    $resData["total"] = $total1;
    echo action_msg_data(API::SUCCESS_MSG,API::SUCCESS,$resData);

    //检查手机验证码

}catch (MyException $e){
    $api->ApiError($e->getCode(), $e->getMessage());
}
