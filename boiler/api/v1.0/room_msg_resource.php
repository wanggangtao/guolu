<?php
/**
 * Created by PhpStorm.
 * User: ftx
 * Date: 2019/3/17
 * Time: 11:27 AM
 */


try {
    $room_id = isset($_POST['room_id'])?safeCheck($_POST['room_id']):'0';
    $page = isset($_POST['page'])?safeCheck($_POST['page']):1;
    $pageSize  = isset($_POST['pageSize'])?safeCheck($_POST['pageSize']):15;

    if(empty($room_id)) throw new MyException("缺少room_id参数",101);

    //$projectinfo = Project::getInfoById($projectid) ;
    $room_info = Chat_room::getInfoById($room_id) ;

    if(empty($room_info))
        throw new MyException("群信息不存在",101);

    $msgs = Chat_room_msg::getResource($page,$pageSize,$room_id);

    $resultArray = array();
    $data_value = array();
    $dataItem = array();
    $data = array();
    if(!empty($msgs))
    {
        $i =0;
        $begin_time = getDateStrC($msgs[0]['addtime']);
        $dataItem['time'] = $begin_time;
        foreach ($msgs as $key => $item)
        {
            if ($begin_time==getDateStrC($item['addtime'])){
                $data['msg_id'] = $item['id'];
                $data['type'] = $item['type'];
                $data['content'] = $item['content'];
                $data['extra'] = $item['extra'];
                array_push($data_value, $data);
                
            }else{
                $dataItem['data'] = $data_value;
                $resultArray[$i]=$dataItem;
                $i++;
                $begin_time = getDateStrC($item['addtime']);
                $dataItem['time'] = $begin_time;
                $data_value = array();
                $data = array();
                $data['msg_id'] = $item['id'];
                $data['type'] = $item['type'];
                $data['content'] = $item['content'];
                $data['extra'] = $item['extra'];
                array_push($data_value, $data);
            }
        }
        $dataItem['data'] = $data_value;
        $resultArray[$i]=$dataItem;
    }
    echo action_msg_data(API::SUCCESS_MSG, API::SUCCESS, $resultArray);

}catch (MyException $e){
    $api->ApiError($e->getCode(), $e->getMessage());
}
