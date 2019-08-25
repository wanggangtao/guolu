<?php
/**
 * Created by PhpStorm.
 * User: dlk
 * Date: 17/7/12
 * Time: 上午9:52
 */



try {
    $id =  isset($_POST['id'])?safeCheck($_POST['id']):0;

    $project_five = Project_five::getInfoByProjectId($id);

    if(empty($project_five))
    {
        throw new MyException("项目五阶段信息不存在!",1041);
    }

    if(!Project::checkProjectForUser($uid,$project_five["project_id"])) throw new MyException("无权限查看该项目",1039);

    $project_five['after_solve'] = HTMLDecode($project_five['after_solve']);
    $project_five['pay_condition'] = HTMLDecode($project_five['pay_condition']);
    $project_five['cost_plan'] = HTMLDecode($project_five['cost_plan']);

    $project_five['pre_build_time'] = getDateStrC($project_five['pre_build_time']);
    $project_five['pre_check_time'] = getDateStrC($project_five['pre_check_time']);
    echo action_msg_data(API::SUCCESS_MSG,API::SUCCESS,$project_five);

    //检查手机验证码

}catch (MyException $e){
    $api->ApiError($e->getCode(), $e->getMessage());
}


?>
