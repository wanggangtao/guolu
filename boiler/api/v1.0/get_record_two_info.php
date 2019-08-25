<?php
/**
 * Created by PhpStorm.
 * User: zhima
 * Date: 2018/7/3
 * Time: 下午7:02
 */

try {
    if(!isset($_POST['recordid']))
        throw new MyException("请传入ID",101);

    $recordid = safeCheck($_POST['recordid']);

    $record_two = Project_two_record::getInfoById($recordid);

    if(empty($record_two))
        throw new MyException("记录不存在",101);

    if(!Project::checkProjectForUser($uid,$record_two["project_id"])) throw new MyException("无权限查看该项目",1039);

    $project_two_before = Project_two_bak::getInfoById($record_two['before_id']);
    $project_two_after = Project_two_bak::getInfoById($record_two['after_id']);

    $linkManListBefore = 0;
    if($record_two['before_id'])
        $linkManListBefore = Project_linkman_bak::getInfoByPtId($record_two['before_id']);

    $linkManListAfter = 0;
    if($record_two['after_id'])
        $linkManListAfter = Project_linkman_bak::getInfoByPtId($record_two['after_id']);

    $resData = array();

    if($linkManListBefore){
        foreach ($linkManListBefore as $thisman){
            $linkmanyarrb = array();
            $linkmanyarrb["name"] = $thisman["name"];
            $linkmanyarrb["phone"] = $thisman["phone"];
            $linkmanyarrb["department"] = $thisman["department"];
            $linkmanyarrb["duty"] = HTMLDecode($thisman["duty"]);
            $linkmanyarrb["position"] = $thisman["position"];
            $linkmanyarrb["isimportant"] = $thisman["isimportant"];
            $project_two_before["project_linkman"][] =  $linkmanyarrb;
        }
    }else{
        $project_two_before["project_linkman"] =  null;
    }
    if($linkManListAfter){
        foreach ($linkManListAfter as $thisman){
            $linkmanyarra = array();
            $linkmanyarra["name"] = $thisman["name"];
            $linkmanyarra["phone"] = $thisman["phone"];
            $linkmanyarra["department"] = $thisman["department"];
            $linkmanyarra["duty"] = HTMLDecode($thisman["duty"]);
            $linkmanyarra["position"] = $thisman["position"];
            $linkmanyarra["isimportant"] = $thisman["isimportant"];
            $project_two_after["project_linkman"][] =  $linkmanyarra;
        }
    }else{
        $project_two_after["project_linkman"] =  null;
    }
    $resData = array("before" => $project_two_before, "after" => $project_two_after);
    echo action_msg_data(API::SUCCESS_MSG, API::SUCCESS, $resData);

}catch (MyException $e){
    $api->ApiError($e->getCode(), $e->getMessage());
}
