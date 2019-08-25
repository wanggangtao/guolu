<?php
/**
 * Created by PhpStorm.
 * User: dlk
 * Date: 17/7/12
 * Time: 上午9:52
 */



try {
    $id =  isset($_POST['id'])?safeCheck($_POST['id']):0;

    $project_one = Project_one::getInfoByProjectId($id);

    if(empty($project_one))
    {
        throw new MyException("项目一阶段信息不存在!",1041);
    }

    if(!Project::checkProjectForUser($uid,$project_one["project_id"])) throw new MyException("无权限查看该项目",1039);


    $pic_partya_pic = array();
    if($project_one['project_partya_pic']) {
        $picarr = explode("|", $project_one['project_partya_pic']);
        $piccount = count($picarr);
        foreach ($picarr as $pic){
            $pic_partya_pic[] = $HTTP_PATH.$pic;
        }
    }
    unset($project_one["project_boiler_num"]);
    unset($project_one["project_boiler_tonnage"]);
    unset($project_one["project_partya_pic"]);
    if($project_one["project_type"] != 2){
        $burnerlist = Project_burner_type::getInfoByPoId($id);
        if($burnerlist){
            foreach ($burnerlist as $thistype){
                $typeData = array();
                $typeData["guolu_tonnage"] = $thistype["guolu_tonnage"];
                $typeData["guolu_num"] = $thistype["guolu_num"];
                $project_one["burner_type"][] =  $typeData;
            }
        }else{
            $project_one["burner_type"] = null;
        }
    }else{
        $project_one["burner_type"] = null;
    }
    $project_one["project_partya_pic"] = $pic_partya_pic;
    $project_one["project_partya_desc"] = HTMLDecode($project_one["project_partya_desc"]);
    $project_one["project_competitive_desc"] = HTMLDecode($project_one["project_competitive_desc"]);
    $project_one["project_desc"] = HTMLDecode($project_one["project_desc"]);
    $typeInfo = project_type::getInfoById($project_one["project_type"]);
    $typeName = "";
    if(!empty($typeInfo))
    {
        $typeName = $typeInfo["name"];
    }
    $project_one["typeName"] = $typeName;
    $project_one["buildTypeName"] = $ARRAY_project_build_type[$project_one["project_build_type"]];
    //日期修改
    $project_one["project_pre_buildtime"] = getDateStrC($project_one["project_pre_buildtime"]);

    echo action_msg_data(API::SUCCESS_MSG,API::SUCCESS,$project_one);

    //检查手机验证码

}catch (MyException $e){
    $api->ApiError($e->getCode(), $e->getMessage());
}


?>
