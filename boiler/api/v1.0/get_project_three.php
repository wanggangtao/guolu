<?php
/**
 * Created by PhpStorm.
 * User: dlk
 * Date: 17/7/12
 * Time: 上午9:52
 */



try {
    $id =  isset($_POST['id'])?safeCheck($_POST['id']):0;

    $project_three = Project_three::getInfoByProjectId($id);
    if(empty($project_three))
    {
        throw new MyException("项目三阶段信息不存在!",1041);
    }

    if(!Project::checkProjectForUser($uid,$project_three["project_id"])) throw new MyException("无权限查看该项目",1039);

    $project_three['competitive_brand_situation'] = HTMLDecode($project_three['competitive_brand_situation']);
    $project_three['progress_situation'] = HTMLDecode($project_three['progress_situation']);
    $project_three['invitation_situation'] = HTMLDecode($project_three['invitation_situation']);
    $project_three['other_situation'] = HTMLDecode($project_three['other_situation']);
    echo action_msg_data(API::SUCCESS_MSG,API::SUCCESS,$project_three);

    //检查手机验证码

}catch (MyException $e){
    $api->ApiError($e->getCode(), $e->getMessage());
}


?>
