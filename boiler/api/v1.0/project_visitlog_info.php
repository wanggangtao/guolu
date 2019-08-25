<?php
/**
 * Created by PhpStorm.
 * User: zhima
 * Date: 2018/7/3
 * Time: 下午7:02
 */

try {
    if(!isset($_POST['id']))
        throw new MyException("请传入ID",101);

    $id = safeCheck($_POST['id']);

    $info = Project_visitlog::getInfoById($id) ;

    if(empty($info))
        throw new MyException("记录不存在",101);

    $resData = array();

    $resData["id"] = $info["id"];
    $resData["visittime"] = getDateStrS($info['visittime']);
    $resData["target"] = $info['target'];
    $resData["tel"] = $info["tel"];
    $resData["position"] = $info['position'];
    $resData["visitway"] =  $ARRAY_visit_way[$info["visitway"]];
    $resData["content"] = $info['content'];
    $resData["effect"] = $info['effect'];
    $resData["plan"] = $info['plan'];
    /*$resData["comment"] = $info['comment'];
    $resData["comuserid"] = $info['comuser'];
    if($info['comuser']){
        $cuserinfo = User::getInfoById($info['comuser']);
        $resData["comusername"] = $cuserinfo['name'];
    }else{
        $resData["comusername"] = "";
    }*/
    $commentarr = array();
    $commentlist = Project_visitlog_comment::getInfoByVisitlogid($info['id']);
    foreach ($commentlist as $thiscomment){
        $thiscommentarr =array();
        $thiscommentarr["comment"] = $thiscomment['content'];
        $thiscommentarr["comuserid"] = $thiscomment['comuser'];
        $comuser = '';
        if($thiscomment['comuser']){
            $comuserinfo = User::getInfoById($thiscomment['comuser']);
            $comuser = $comuserinfo['name'];

        }
        $thiscommentarr["comusername"] = $comuser;
        $commentarr[] = $thiscommentarr;
    }
    $resData["commentinfo"] = $commentarr;
    echo action_msg_data(API::SUCCESS_MSG, API::SUCCESS, $resData);

}catch (MyException $e){
    $api->ApiError($e->getCode(), $e->getMessage());
}
