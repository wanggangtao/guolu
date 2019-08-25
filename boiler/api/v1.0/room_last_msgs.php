<?php
/**
 * Created by PhpStorm.
 * User: sxm
 * Date: 2019/3/4
 * Time: 17:34
 *
 *
 *
 *
 * 以前用于获取最新消息，现在改为更新用户读取消息
 */


try {
    $room_id = isset($_POST['room_id'])?safeCheck($_POST['room_id'], 0):"";

    if(empty($room_id)) throw new MyException("缺少room_id参数",101);
    if(empty($uid)) throw new MyException("缺少uid参数",102);

    //$projectinfo = Project::getInfoById($projectid) ;
    $room_info = Chat_room::getInfoById($room_id) ;

    if(empty($room_info))
        throw new MyException("群信息不存在",103);

    $resData = array();

    $msgs  =  Chat_room_msg::getLastMsg($room_id);

    $resultArray = array();


    if(!empty($msgs))
    {

//        foreach ($msgs as $item)
//        {
//            $dataItem = array();
//            $dataItem["id"] = $item["id"];
//            $dataItem["room_id"] = $item["room_id"];
//            $dataItem["uid"] = $item["uid"];
//            $dataItem["uname"] = $item["uname"];
//            $dataItem["type"] = $item["type"];
//            $dataItem["system_type"] = $item["system_type"];
//            $dataItem["type"] = $item["type"];
//            $dataItem["content"] = $item["content"];
//            $dataItem["isShowTime"] = $item["isShowTime"];
//            $dataItem["extra"] = $item["extra"];
//            $dataItem["size"] = $item["size"];
//            $dataItem["project"] = $room_info["project"];
//            $dataItem["date"] = getDateStr($item['addtime']);
//
//            $resultArray[] = $dataItem;
//        }


        $lastMsgId = $msgs["id"];

        chat_room_msg_config::updateByUidAndRoom($uid,$room_id,array("last_msg_id"=>$lastMsgId,"lastupdate"=>time()));

    }

    echo action_msg_data(API::SUCCESS_MSG, API::SUCCESS,$resultArray);

}catch (MyException $e){
    $api->ApiError($e->getCode(), $e->getMessage());
}
