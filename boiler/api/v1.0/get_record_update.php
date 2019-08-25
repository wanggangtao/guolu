<?php
/**
 * Created by PhpStorm.
 * User: zhima
 * Date: 2018/7/3
 * Time: 下午7:02
 */

try {
    $projectid = isset($_POST['projectid'])?safeCheck($_POST['projectid']):'0';
    $postion = isset($_POST['postion'])?safeCheck($_POST['postion'], 0):"";
    $page = isset($_POST['page'])?safeCheck($_POST['page']):1;
    $pageSize  = isset($_POST['pageSize'])?safeCheck($_POST['pageSize']):15;

    if(empty($projectid)) throw new MyException("缺少项目ID参数",1381);

    $projectinfo = Project::getInfoById($projectid) ;

    if(empty($projectinfo))
        throw new MyException("项目信息不存在",1382);

    $resData = array();
    $result = array();
    if($postion == "one"){
        $resData["total"]  = Project_one_record::getPageList(1, 10, 0, $projectid);
        $result = Project_one_record::getPageList($page, $pageSize, 1, $projectid);
    }elseif ($postion == "two"){
        $resData["total"]  = Project_two_record::getPageList(1, 10, 0, $projectid);
        $result = Project_two_record::getPageList($page, $pageSize, 1, $projectid);
    }elseif ($postion == "three"){
        $resData["total"]  = Project_three_record::getPageList(1, 10, 0, $projectid);
        $result = Project_three_record::getPageList($page, $pageSize, 1, $projectid);
    }elseif ($postion == "four"){
        $resData["total"]  = Project_four_record::getPageList(1, 10, 0, $projectid);
        $result = Project_four_record::getPageList($page, $pageSize, 1, $projectid);
    }elseif ($postion == "five"){
        $resData["total"]  = Project_five_record::getPageList(1, 10, 0, $projectid);
        $result = Project_five_record::getPageList($page, $pageSize, 1, $projectid);
    }else{
        throw new MyException("位置参数postion不正确",1383);
    }

    if(!empty($result))
    {
        foreach ($result as $item)
        {
            $dataItem = array();
            $dataItem["record_id"] = $item["id"];
            $dataItem["user_name"] = $item['user_name'];
            $dataItem["date"] = getDateStr($item['addtime']);
            $resData["data"][] = $dataItem;
        }
    }

    echo action_msg_data(API::SUCCESS_MSG, API::SUCCESS, $resData);

}catch (MyException $e){
    $api->ApiError($e->getCode(), $e->getMessage());
}
