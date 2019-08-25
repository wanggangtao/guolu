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

    $record_four = Project_four_record::getInfoById($recordid);

    if(empty($record_four))
        throw new MyException("记录不存在",101);

    if(!Project::checkProjectForUser($uid,$record_four["project_id"])) throw new MyException("无权限查看该项目",1039);

    $project_four_before = Project_four_bak::getInfoById($record_four['before_id']);
    $project_four_after = Project_four_bak::getInfoById($record_four['after_id']);

    $companyListBefore = 0;
    if($record_four['before_id'])
        $companyListBefore = Project_bid_company_bak::getInfoByPfId($record_four['before_id']);

    $companyListAfter = 0;
    if($record_four['after_id'])
        $companyListAfter = Project_bid_company_bak::getInfoByPfId($record_four['after_id']);

    $resData = array();

    if($project_four_before){
        $project_four_before["project_cid_file"] = $project_four_before["project_cid_file"]?$HTTP_PATH.$project_four_before["project_cid_file"]:'';
        $project_four_before["project_bid_file"]  = $project_four_before["project_bid_file"]?$HTTP_PATH.$project_four_before["project_bid_file"]:'';
        $project_four_before["project_cbid_situation"] = HTMLDecode($project_four_before["project_cbid_situation"]);
    }else{
        $project_four_before = null;
    }
    if($project_four_after){
        $project_four_after["project_cid_file"] = $project_four_after["project_cid_file"]?$HTTP_PATH.$project_four_after["project_cid_file"]:'';
        $project_four_after["project_bid_file"]  = $project_four_after["project_bid_file"]?$HTTP_PATH.$project_four_after["project_bid_file"]:'';
        $project_four_after["project_cbid_situation"] = HTMLDecode($project_four_after["project_cbid_situation"]);
    }else{
        $project_four_after = null;
    }
    $sucess_companyb = "";
    if($companyListBefore){
        foreach ($companyListBefore as $thiscomp){
            if($thiscomp["isimportant"] == 1){
                $sucess_companyb = $thiscomp["name"];
            }
            $companyarrb = array();
            $companyarrb["name"] = $thiscomp["name"];
            $companyarrb["price"] = $thiscomp["price"];
            $companyarrb["brand"] = $thiscomp["brand"];
            $companyarrb["isimportant"] = $thiscomp["isimportant"];
            $project_four_before["bid_company"][] =  $companyarrb;
        }
    }else{
        $project_four_before["bid_company"] =  null;
    }
    $project_four_before["sucess_company"] = $sucess_companyb;
    $sucess_companya = "";
    if($companyListAfter){
        foreach ($companyListAfter as $thiscomp){
            if($thiscomp["isimportant"] == 1){
                $sucess_companya = $thiscomp["name"];
            }
            $companyarra = array();
            $companyarra["name"] = $thiscomp["name"];
            $companyarra["price"] = $thiscomp["price"];
            $companyarra["brand"] = $thiscomp["brand"];
            $companyarra["isimportant"] = $thiscomp["isimportant"];
            $project_four_after["bid_company"][] =  $companyarra;
        }
    }else{
        $project_four_after["bid_company"] =  null;
    }
    $project_four_after["sucess_company"] = $sucess_companya;
    $resData = array("before" => $project_four_before, "after" => $project_four_after);
    echo action_msg_data(API::SUCCESS_MSG, API::SUCCESS, $resData);

}catch (MyException $e){
    $api->ApiError($e->getCode(), $e->getMessage());
}
