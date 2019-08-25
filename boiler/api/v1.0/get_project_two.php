<?php
/**
 * Created by PhpStorm.
 * User: dlk
 * Date: 17/7/12
 * Time: 上午9:52
 */



try {
    $id =  isset($_POST['id'])?safeCheck($_POST['id']):0;

    $project_two = Project_two::getInfoByProjectId($id);
    if(empty($project_two))
    {
        throw new MyException("项目二阶段信息不存在!",1041);
    }
    if(!Project::checkProjectForUser($uid,$project_two["project_id"])) throw new MyException("无权限查看该项目",1039);

    $linkmanlist = Project_linkman::getInfoByPtId($id);
    if($linkmanlist){
        foreach ($linkmanlist as $thisman){
            $linkmanyarr = array();
            $linkmanyarr["name"] = $thisman["name"];
            $linkmanyarr["phone"] = $thisman["phone"];
            $linkmanyarr["department"] = $thisman["department"];
            $linkmanyarr["duty"] = HTMLDecode($thisman["duty"]);
            $linkmanyarr["position"] = $thisman["position"];
            $linkmanyarr["isimportant"] = $thisman["isimportant"];
            $project_two["project_linkman"][] =  $linkmanyarr;
        }
    }else{
        $project_two["project_linkman"] = null;
    }

    echo action_msg_data(API::SUCCESS_MSG,API::SUCCESS,$project_two);

    //检查手机验证码

}catch (MyException $e){
    $api->ApiError($e->getCode(), $e->getMessage());
}


?>
