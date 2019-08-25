<?php
/**
 * Created by PhpStorm.
 * User: zhima
 * Date: 2018/7/3
 * Time: 下午7:02
 */

try {
    $openflag = isset($_POST['openflag'])?safeCheck($_POST['openflag']):-1;
    $page = isset($_POST['page'])?safeCheck($_POST['page']):1;
    $pageSize  = isset($_POST['pageSize'])?safeCheck($_POST['pageSize']):20;

    if(empty($uid)) throw new MyException("缺少用户uid参数",411);

    $resData = array();

    $result = Message_info::getPageList($page, $pageSize, 1, $uid, 0, '', $openflag);

    if(!empty($result))
    {
        foreach ($result as $item)
        {
            $dataItem = array();
            $dataItem["id"] = $item["id"];
            $dataItem["title"] = $item['title'];
            $dataItem["openflag"] = $item['openflag'];
            $dataItem["date"] = date('Y年m月d日 H:i:s',$item['addtime']);
            $dataItem["sender"] = $item['sender'];
            $picurl = "";
            if($item['sender'] != 0){
                $senderinfo = User::getInfoById($item['sender']);
                $picurl = empty($senderinfo["headimg"])?"":$HTTP_PATH.$senderinfo["headimg"];
            }
            $dataItem["send_headpic"] = $picurl;
            $resData[] = $dataItem;
        }
    }
    echo action_msg_data(API::SUCCESS_MSG, API::SUCCESS, $resData);

}catch (MyException $e){
    $api->ApiError($e->getCode(), $e->getMessage());
}
