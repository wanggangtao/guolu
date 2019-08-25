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

    $record_five = Project_five_record::getInfoById($recordid);

    if(empty($record_five))
        throw new MyException("记录不存在",101);

    if(!Project::checkProjectForUser($uid,$record_five["project_id"])) throw new MyException("无权限查看该项目",1039);

    $project_five_before = Project_five_bak::getInfoById($record_five['before_id']);
    $project_five_after = Project_five_bak::getInfoById($record_five['after_id']);

    $resData = array();

    if($project_five_before){
        $project_five_before['after_solve'] = HTMLDecode($project_five_before['after_solve']);
        $project_five_before['pay_condition'] = HTMLDecode($project_five_before['pay_condition']);
        $project_five_before['cost_plan'] = HTMLDecode($project_five_before['cost_plan']);
        $project_five_before['pre_build_time'] = getDateStrS($project_five_before['pre_build_time']);
        $project_five_before['pre_check_time'] = getDateStrS($project_five_before['pre_check_time']);
    }else{
        $project_five_before = null;
    }
    if($project_five_after){
        $project_five_after['after_solve'] = HTMLDecode($project_five_after['after_solve']);
        $project_five_after['pay_condition'] = HTMLDecode($project_five_after['pay_condition']);
        $project_five_after['cost_plan'] = HTMLDecode($project_five_after['cost_plan']);
        $project_five_after['pre_build_time'] = getDateStrS($project_five_after['pre_build_time']);
        $project_five_after['pre_check_time'] = getDateStrS($project_five_after['pre_check_time']);
    }else{
        $project_five_after = null;
    }
    $resData = array("before" => $project_five_before, "after" => $project_five_after);
    echo action_msg_data(API::SUCCESS_MSG, API::SUCCESS, $resData);

}catch (MyException $e){
    $api->ApiError($e->getCode(), $e->getMessage());
}
