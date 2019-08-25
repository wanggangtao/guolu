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

    $params["page"] = $page;
    $params["pageSize"] = $pageSize;
    $resData["total"]  = Selection_plan_front::getByProidCount($projectid);
    $result = Selection_plan_front::getListByProid($params,$projectid);

    if(!empty($result))
    {
        foreach ($result as $item)
        {
            $dataItem = array();
            $dataItem["id"] = $item["id"];
            $dataItem["project_id"] = $item['project_id'];
            $dataItem["name"] = $item['name'];
//            $dataItem["url"] = $HTTP_PATH.$item['url'];
            $dataItem["pdf_url"] = $HTTP_PATH.$item['pdf_url'];

            $type_item = Selection_history::getInfoById($item['history_id']);
            if(!empty($type_item)){
                $type_value = $type_item['type'];
                switch ($type_value){
                    case 1:$type = "智能选型";break;
                    case 2:$type = "手动选型";break;
                    case 3:$type = "更换锅炉";break;
                    default: $type="";break;
                }
            }else{
                $type="";
                $type_value="";
            }

            $dataItem["type"] = $type;
            $dataItem["date"] = getDateStrS($item['addtime']);
            $resData["data"][] = $dataItem;
        }
    }

    echo action_msg_data(API::SUCCESS_MSG, API::SUCCESS, $resData);

}catch (MyException $e){
    $api->ApiError($e->getCode(), $e->getMessage());
}
