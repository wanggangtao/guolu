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

    $record_one = Project_one_record::getInfoById($recordid);

    if(empty($record_one))
        throw new MyException("记录不存在",101);

    if(!Project::checkProjectForUser($uid,$record_one["project_id"])) throw new MyException("无权限查看该项目",1039);

    $project_one_before = Project_one_bak::getInfoById($record_one['before_id']);
    $project_one_after = Project_one_bak::getInfoById($record_one['after_id']);

    $resData = array();

    if($project_one_before){
        $pic_partya_pic_before = array();
        if($project_one_before['project_partya_pic']) {
            $picarrb = explode("|", $project_one_before['project_partya_pic']);
            $piccountb = count($picarrb);
            foreach ($picarrb as $pic){
                $pic_partya_pic_before[] = $HTTP_PATH.$pic;
            }
        }
        unset($project_one_before["boiler_num"]);
        unset($project_one_before["boiler_tonnage"]);
        unset($project_one_before["project_partya_pic"]);
        if($project_one_before["project_type"] != 2){
            $burnerlistb = Project_burner_type_bak::getInfoByPoId($record_one['before_id']);
            if($burnerlistb){
                foreach ($burnerlistb as $thistype){
                    $typeData = array();
                    $typeData["guolu_tonnage"] = $thistype["guolu_tonnage"];
                    $typeData["guolu_num"] = $thistype["guolu_num"];
                    $project_one_before["burner_type"][] =  $typeData;
                }
            }else{
                $project_one_before["burner_type"] = null;
            }
        }else{
            $project_one_before["burner_type"] = null;
        }
        $project_one_before["project_partya_pic"] = $pic_partya_pic_before;
        $project_one_before["project_partya_desc"] = HTMLDecode($project_one_before["project_partya_desc"]);
        $project_one_before["project_competitive_desc"] = HTMLDecode($project_one_before["project_competitive_desc"]);
        $project_one_before["project_desc"] = HTMLDecode($project_one_before["project_desc"]);
        $typeInfo = project_type::getInfoById($project_one_before["project_type"]);
        $typeName = "";
        if(!empty($typeInfo))
        {
            $typeName = $typeInfo["name"];
        }
        $project_one_before["typeName"] = $typeName;
        $project_one_before["buildTypeName"] = $ARRAY_project_build_type[$project_one_before["project_build_type"]];
        $project_one_before["project_pre_buildtime"] = getDateStrS($project_one_before["project_pre_buildtime"]);
    }else{
        $project_one_before = null;
    }
    if($project_one_after){
        $pic_partya_pic_after = array();
        if($project_one_after['project_partya_pic']) {
            $picarra = explode("|", $project_one_after['project_partya_pic']);
            $piccounta = count($picarra);
            foreach ($picarra as $pic){
                $pic_partya_pic_after[] = $HTTP_PATH.$pic;
            }
        }
        unset($project_one_after["boiler_num"]);
        unset($project_one_after["boiler_tonnage"]);
        unset($project_one_after["project_partya_pic"]);
        if($project_one_after["project_type"] != 2){
            $burnerlista = Project_burner_type_bak::getInfoByPoId($record_one['after_id']);
            if($burnerlista){
                foreach ($burnerlista as $thistype){
                    $typeData = array();
                    $typeData["guolu_tonnage"] = $thistype["guolu_tonnage"];
                    $typeData["guolu_num"] = $thistype["guolu_num"];
                    $project_one_after["burner_type"][] =  $typeData;
                }
            }else{
                $project_one_after["burner_type"] = null;
            }
        }else{
            $project_one_after["burner_type"] = null;
        }
        $project_one_after["project_partya_pic"] = $pic_partya_pic_after;
        $project_one_after["project_partya_desc"] = HTMLDecode($project_one_after["project_partya_desc"]);
        $project_one_after["project_competitive_desc"] = HTMLDecode($project_one_after["project_competitive_desc"]);
        $project_one_after["project_desc"] = HTMLDecode($project_one_after["project_desc"]);
        $typeInfoa = project_type::getInfoById($project_one_after["project_type"]);
        $typeNamea = "";
        if(!empty($typeInfoa))
        {
            $typeNamea = $typeInfoa["name"];
        }
        $project_one_after["typeName"] = $typeNamea;
        $project_one_after["buildTypeName"] = $ARRAY_project_build_type[$project_one_after["project_build_type"]];
        $project_one_after["project_pre_buildtime"] = getDateStrS($project_one_after["project_pre_buildtime"]);
    }else{
        $project_one_after = null;
    }
    $resData = array("before" => $project_one_before, "after" => $project_one_after);
    echo action_msg_data(API::SUCCESS_MSG, API::SUCCESS, $resData);

}catch (MyException $e){
    $api->ApiError($e->getCode(), $e->getMessage());
}
