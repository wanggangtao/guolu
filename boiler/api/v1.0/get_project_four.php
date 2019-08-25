<?php
/**
 * Created by PhpStorm.
 * User: dlk
 * Date: 17/7/12
 * Time: 上午9:52
 */



try {
    $id =  isset($_POST['id'])?safeCheck($_POST['id']):0;

    $project_four = Project_four::getInfoByProjectId($id);
    if(empty($project_four))
    {
        throw new MyException("项目四阶段信息不存在!",1041);
    }
    if(!Project::checkProjectForUser($uid,$project_four["project_id"])) throw new MyException("无权限查看该项目",1039);

    unset($project_four["project_success_bid_company"]);
    $companylist = Project_bid_company::getInfoByPfId($id);
    $sucess_company = "";
    if($companylist){
        foreach ($companylist as $thiscomp){
            if($thiscomp["isimportant"] == 1){
                $sucess_company = $thiscomp["name"];
            }
            $companyarr = array();
            $companyarr["name"] = $thiscomp["name"];
            $companyarr["price"] = $thiscomp["price"];
            $companyarr["brand"] = $thiscomp["brand"];
            $companyarr["isimportant"] = $thiscomp["isimportant"];
            $project_four["bid_company"][] =  $companyarr;
        }
    }else{
        $project_four["bid_company"] = null;
    }
    $project_four["sucess_company"] = $sucess_company;
    $project_four["project_cid_file"] = $project_four["project_cid_file"]?$HTTP_PATH.$project_four["project_cid_file"]:'';
    $project_four["project_bid_file"]  = $project_four["project_bid_file"]?$HTTP_PATH.$project_four["project_bid_file"]:'';
    $project_four["project_cbid_situation"] = HTMLDecode($project_four["project_cbid_situation"]);
    echo action_msg_data(API::SUCCESS_MSG,API::SUCCESS,$project_four);

    //检查手机验证码

}catch (MyException $e){
    $api->ApiError($e->getCode(), $e->getMessage());
}


?>
