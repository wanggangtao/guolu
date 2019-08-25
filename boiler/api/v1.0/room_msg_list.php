<?php
/**
 * Created by PhpStorm.
 * User: sxm
 * Date: 2019/3/4
 * Time: 16:24
 */

try {
    $room_id = isset($_POST['room_id'])?safeCheck($_POST['room_id']):'0';
    $page = isset($_POST['page'])?safeCheck($_POST['page']):1;
    $pageSize  = isset($_POST['pageSize'])?safeCheck($_POST['pageSize']):15;

    $current_msg_id = isset($_POST['current_msg_id'])?safeCheck($_POST['current_msg_id'],0):'0';
    $direction = $_POST['direction'];

    if(empty($room_id)) throw new MyException("缺少room_id参数",101);

//    if(empty($current_msg_id)) throw new MyException("缺少current_msg_id参数",101);
    //$projectinfo = Project::getInfoById($projectid) ;
    $room_info = Chat_room::getInfoById($room_id) ;

    if(empty($room_info))
        throw new MyException("群信息不存在",101);


    $msgs  =  Chat_room_msg::getMsgRecord($room_id,$current_msg_id,$direction,$page,$pageSize);

    $resultArray = array();
    if(!empty($msgs))
    {

        foreach ($msgs as $key => $item)
        {
            $dataItem = array();
            $dataItem["id"] = $item["msg_id"];
            $dataItem["room_id"] = $item["msg_room_id"];
            $dataItem["uid"] = $item["msg_uid"];
            $dataItem["uname"] = $item["msg_uid"]==-1?"机器人":$item["user_name"];
            $dataItem["uheadimg"] = $item["msg_uid"]==-1?$HTTP_PATH."static/images/rabot.png":$HTTP_PATH.$item["user_headimg"];
            $dataItem["isShowTime"] = $item["msg_isShowTime"];
            $dataItem["type"] = $item["msg_type"];
            $dataItem["system_type"] = $item["msg_system_type"];
            $dataItem["project"] = $room_info["project"];

            $dataItem["content"] = $item["msg_content"];
            $dataItem["extra"] = $item["msg_extra"];
            $dataItem["size"] = $item["msg_size"];
            $dataItem["date"] = getDateStr($item['msg_addtime']);

            $resultArray[$key] = $dataItem;
        }
    }

    echo action_msg_data(API::SUCCESS_MSG, API::SUCCESS, $resultArray);

}catch (MyException $e){
    $api->ApiError($e->getCode(), $e->getMessage());
}
