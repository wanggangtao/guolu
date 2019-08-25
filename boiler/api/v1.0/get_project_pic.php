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
    $projectid =  isset($_POST['projectid'])?safeCheck($_POST['projectid']):0;
    $type =  isset($_POST['type'])?safeCheck($_POST['type']):0;

    if(empty($projectid)) throw new MyException("缺少项目ID参数",101);

    $projectinfo = Project::getInfoById($projectid) ;

    if(empty($projectinfo))
    {
        throw new MyException("项目信息不存在",101);
    }

    if(empty($type))
        throw new MyException("请指定图库类型",101);

    if(!in_array($type, array(1,2)))
        throw new MyException("图库类型错误",101);

    $resData = array();

    $result = Project_pictures::getPageList($page,$pageSize,1, $projectid, $type);
    $resData["total"] = Project_pictures::getPageList(1, 10, 0, $projectid, $type);

    if(!empty($result))
    {
        foreach ($result as $item)
        {
            $dataItem = array();
            $dataItem["id"] = $item["id"];
            $dataItem["url"] = $HTTP_PATH.$item['url'];
            $resData["data"][] = $dataItem;
        }
    }

    echo action_msg_data(API::SUCCESS_MSG, API::SUCCESS, $resData);

}catch (MyException $e){
    $api->ApiError($e->getCode(), $e->getMessage());
}
