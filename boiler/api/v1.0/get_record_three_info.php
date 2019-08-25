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

    $record_three = Project_three_record::getInfoById($recordid);

    if(empty($record_three))
        throw new MyException("记录不存在",101);

    if(!Project::checkProjectForUser($uid,$record_three["project_id"])) throw new MyException("无权限查看该项目",1039);

    $project_three_before = Project_three_bak::getInfoById($record_three['before_id']);
    $project_three_after = Project_three_bak::getInfoById($record_three['after_id']);

    $resData = array();

    if($project_three_before){
        $project_three_before['competitive_brand_situation'] = HTMLDecode($project_three_before['competitive_brand_situation']);
        $project_three_before['progress_situation'] = HTMLDecode($project_three_before['progress_situation']);
        $project_three_before['invitation_situation'] = HTMLDecode($project_three_before['invitation_situation']);
        $project_three_before['other_situation'] = HTMLDecode($project_three_before['other_situation']);
    }else{
        $project_three_before = null;
    }
    if($project_three_after){
        $project_three_after['competitive_brand_situation'] = HTMLDecode($project_three_after['competitive_brand_situation']);
        $project_three_after['progress_situation'] = HTMLDecode($project_three_after['progress_situation']);
        $project_three_after['invitation_situation'] = HTMLDecode($project_three_after['invitation_situation']);
        $project_three_after['other_situation'] = HTMLDecode($project_three_after['other_situation']);
    }else{
        $project_three_after = null;
    }
    $resData = array("before" => $project_three_before, "after" => $project_three_after);
    echo action_msg_data(API::SUCCESS_MSG, API::SUCCESS, $resData);

}catch (MyException $e){
    $api->ApiError($e->getCode(), $e->getMessage());
}
