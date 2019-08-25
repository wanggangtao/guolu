<?php
/**
 * Created by PhpStorm.
 * User: sxm
 * Date: 2019/3/4
 * Time: 17:32
 */

try {
    $page = isset($_POST['page'])?safeCheck($_POST['page']):1;
    $pageSize  = isset($_POST['pageSize'])?safeCheck($_POST['pageSize']):15;

    $keyword  = isset($_POST['keyword'])?safeCheck($_POST['keyword'],0):'';

    if(empty($uid)) throw new MyException("缺少用户uid参数",411);


    /**判断是否在公司大群里面****/

    chat_room_msg_config::checkFromFirstRoom($uid);



    $attrs = array();

    if(!empty($keyword))
    {
        $attrs["keyword"] = $keyword;
    }

    $chat_record_list = Chat_room::getListByUser($uid,$attrs,$page,$pageSize);
    $chat_list = Chat_room::getListByUser($uid,$attrs,0,$pageSize);
    $num = 0;
    foreach ($chat_list as $Item){
        $num +=$Item['new_count'];
    }

    $resultArray = array();
    $data = array();
    if(!empty($chat_record_list))
    {
        $count = 0;
        foreach ($chat_record_list as  $item)
        {
            $dataItem = array();
            $dataItem["room_id"] = $item["room_id"];
            $dataItem["room_name"] = $item["room_name"];
            $dataItem["room_first"] = $item["room_first"];
            $dataItem["room_principal_uid"] = $item["room_principal_uid"];
            $dataItem["room_principal_uname"] = $item["room_principal_uname"];
            $dataItem["room_project"] = $item["room_project"];
            $dataItem["isShowTime"] = $item["isShowTime"];

            $dataItem["room_last_msg_id"] = empty($item["room_last_msg_id"])?"":$item["room_last_msg_id"];
            $dataItem["room_last_msg_uid"] = empty($item["room_last_msg_uid"])?"":$item["room_last_msg_uid"];
            $dataItem["room_last_msg_uname"] = empty($item["room_last_msg_uname"])?"":$item["room_last_msg_uname"];
            $dataItem["room_last_msg_content"] = empty($item["room_last_msg_content"])?"":$item["room_last_msg_content"];
            $dataItem["room_last_msg_addtime"] = getDateStr($item['room_last_msg_addtime']);
            $dataItem["room_new_count"] = $item['new_count'];

            //获取该room对应该uid的最新消息数
            $count +=$item['new_count'];
            $resultArray[] = $dataItem;
        }

        $data['data'] = $resultArray;
        $data['count'] = $num;
    }

    echo action_msg_data(API::SUCCESS_MSG, API::SUCCESS, $data);

}catch (MyException $e){
    $api->ApiError($e->getCode(), $e->getMessage());
}
