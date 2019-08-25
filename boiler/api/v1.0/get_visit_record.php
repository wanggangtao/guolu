<?php
/**
 * Created by PhpStorm.
 * User: zhima
 * Date: 2018/7/3
 * Time: 下午7:02
 */

try {

    $projectId =  isset($_POST['projectId'])?safeCheck($_POST['projectId'],0):0;

    $page =  isset($_POST['page'])?safeCheck($_POST['page'],0):0;
    $pageSize =  isset($_POST['pageSize'])?safeCheck($_POST['pageSize'],0):1000;
    $content =  isset($_POST['content'])?safeCheck($_POST['content'],0):"";

    if(empty($uid)) throw new MyException("缺少用户uid参数",101);
    if(empty($projectId)) throw new MyException("缺少项目id参数",102);

    $userInfo = user::getInfoById($uid);
    if(empty($userInfo))
    {
        throw new MyException("用户信息不存在",101);
    }

    $projectInfo = project::getInfoById($projectId);

    if($projectInfo["user"]!=$uid)
    {
        throw new MyException("无权限查看项目记录",101);

    }


    $resData = array();



    $result = project_visitlog::getPageList($page,$pageSize,1,$projectId,"",0,0,0,$content);

    $resData["total"] = project_visitlog::getPageList($page,$pageSize,0,$projectId,"",0,0,0,$content);

    if(!empty($result))
    {
        foreach ($result as $item)
        {
            $dataItem = array();
            $dataItem["visitid"] = $item["id"];
            $dataItem["target"] = $item["target"];
            $dataItem["phone"] = $item["tel"];
            $dataItem["type"] = $item["visitway"];
            $dataItem["typeName"] = isset($ARRAY_visit_way[$item["visitway"]])?$ARRAY_visit_way[$item["visitway"]]:"未知";
            $dataItem["visittime"] = date("Y-m-d H:i:s",$item["visittime"]);
            $resData["data"][] = $dataItem;

        }
    }




    echo action_msg_data(API::SUCCESS_MSG,API::SUCCESS,$resData);

    //检查手机验证码

}catch (MyException $e){
    $api->ApiError($e->getCode(), $e->getMessage());
}
