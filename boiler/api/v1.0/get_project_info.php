<?php
/**
 * Created by PhpStorm.
 * User: dlk
 * Date: 18/7/8
 * Time: 上午9:52
 */



try {
    $id =  isset($_POST['id'])?safeCheck($_POST['id']):0;

    $project_info = Project::getInfoById($id);
    if(empty($project_info))
    {
        throw new MyException("项目信息不存在!",1041);
    }
    $project_info['stopreason'] = HTMLDecode($project_info['stopreason']);
    $project_info['summarize'] = HTMLDecode($project_info['summarize']);
    $project_info['reviewopinion'] = HTMLDecode($project_info['reviewopinion']);

    $project_info['bonus_stage_ratio'] = $constant_project_first_reward."|".$constant_project_second_reward."|".$constant_project_third_reward;

    echo action_msg_data(API::SUCCESS_MSG,API::SUCCESS,$project_info);

    //检查手机验证码

}catch (MyException $e){
    $api->ApiError($e->getCode(), $e->getMessage());
}


?>
