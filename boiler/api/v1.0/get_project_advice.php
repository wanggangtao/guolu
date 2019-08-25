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

    if(empty($projectinfo) || $projectinfo['del_flag'] == 1)
        throw new MyException("项目信息不存在",101);

    $resData = array();

    $resData["total"]  = Project_advice::getPageList(1, 10, 0, $projectid, "");
    $result = Project_advice::getPageList($page, $pageSize, 1, $projectid, "");

    if(!empty($result))
    {
        foreach ($result as $item)
        {
            $dataItem = array();
            $dataItem["id"] = $item["id"];
            $dataItem["project_id"] = $item['projectid'];
            $dataItem["content"] = HTMLDecode($item['content']);
            $dataItem["user"] = $item['user'];
            $userinfo = User::getInfoById($item['user']);
            $dataItem["user_name"] = $userinfo['name'];
            $dataItem["date"] = getDateStrS($item['addtime']);
            $resData["data"][] = $dataItem;
        }
    }

    echo action_msg_data(API::SUCCESS_MSG, API::SUCCESS, $resData);

}catch (MyException $e){
    $api->ApiError($e->getCode(), $e->getMessage());
}
