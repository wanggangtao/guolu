<?php
/**
 * Created by PhpStorm.
 * User: zhima
 * Date: 2018/7/3
 * Time: 下午7:02
 */

try {
    $projectid = isset($_POST['projectid'])?safeCheck($_POST['projectid']):'0';
    $target = isset($_POST['visittarget'])?safeCheck($_POST['visittarget'], 0):"";
    $stday = isset($_POST['stday'])?strtotime(safeCheck($_POST['stday'], 0)):0;
    $endday = isset($_POST['endday'])?strtotime(safeCheck($_POST['endday'], 0)):0;
    $way = isset($_POST['way'])?safeCheck($_POST['way']):0;
    $page = isset($_POST['page'])?safeCheck($_POST['page']):1;
    $pageSize  = isset($_POST['pageSize'])?safeCheck($_POST['pageSize']):15;

    if(empty($projectid)) throw new MyException("缺少项目ID参数",101);

    $projectinfo = Project::getInfoById($projectid) ;

    if(empty($projectinfo))
        throw new MyException("项目信息不存在",101);

    if(!empty($way))
        if(!in_array($way, array(1, 2)))
            throw new MyException("拜访方式错误",101);

    $resData = array();

    $resData["total"]  = Project_visitlog::getPageList(1, 10, 0, $projectid, $target, $way, $stday, $endday);
    $result = Project_visitlog::getPageList($page, $pageSize, 1, $projectid, $target, $way, $stday, $endday);

    if(!empty($result))
    {
        foreach ($result as $item)
        {
            $dataItem = array();
            $dataItem["id"] = $item["id"];
            $dataItem["target"] = $item['target'];
            $dataItem["tel"] = $item['tel'];
            $dataItem["date"] = getDateStrS($item['visittime']);
            $dataItem["visitway"] = $ARRAY_visit_way[$item['visitway']];
            $resData["data"][] = $dataItem;
        }
    }

    echo action_msg_data(API::SUCCESS_MSG, API::SUCCESS, $resData);

}catch (MyException $e){
    $api->ApiError($e->getCode(), $e->getMessage());
}
