<?php
/**
 * Created by PhpStorm.
 * User: ftx
 * Date: 2019/3/16
 * Time: 11:56 AM
 */


try {
    $room_id = isset($_POST['room_id'])?safeCheck($_POST['room_id']):'0';
    $page = isset($_POST['page'])?safeCheck($_POST['page']):1;
    $pageSize  = isset($_POST['pageSize'])?safeCheck($_POST['pageSize']):15;
    $time =  isset($_POST['time'])?safeCheck($_POST['time'],0):"";
    $keyword =  isset($_POST['keyword'])?safeCheck($_POST['keyword'],0):"";

    if(empty($room_id)) throw new MyException("缺少room_id参数",101);

    //$projectinfo = Project::getInfoById($projectid) ;
    $room_info = Chat_room::getInfoById($room_id) ;

    if(empty($room_info))
        throw new MyException("群信息不存在",101);

    $msgs  =  Chat_room_msg::getCurrentIdByQuery($keyword,$time,$page,$pageSize,$room_id);
    $resultArray = array();
    if(!empty($msgs))
    {

        foreach ($msgs as $key => $item)
        {
            $dataItem = array();
            $dataItem["id"] = $item["id"];
            $dataItem["room_id"] = $item["room_id"];
            $dataItem["uid"] = $item["uid"];
            $uinfo = User::getInfoById($item['uid']);
            $dataItem['uheadimg'] = $HTTP_PATH.$uinfo['headimg'];
            $dataItem["uname"] = $item["uname"];
            $dataItem["type"] = $item["type"];
            $dataItem["system_type"] = $item["system_type"];
            $dataItem["project"] = $room_info["project"];
            $dataItem["isShowTime"] = $item["isShowTime"];
            $dataItem["content"] = $item["content"];
            $dataItem["extra"] = $item["extra"];
            $dataItem["size"] = $item["size"];
            $dataItem["date"] = getDateStr($item['addtime']);

            $resultArray[$key] = $dataItem;
        }
    }

    echo action_msg_data(API::SUCCESS_MSG, API::SUCCESS, $resultArray);

}catch (MyException $e){
    $api->ApiError($e->getCode(), $e->getMessage());
}
