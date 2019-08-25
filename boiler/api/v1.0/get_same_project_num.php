<?php
/**
 * Created by PhpStorm.
 * User: zhima
 * Date: 2018/7/3
 * Time: 下午7:02
 */

try {
    $projectid = isset($_POST['projectid'])?safeCheck($_POST['projectid']):'0';

    if(empty($projectid)) throw new MyException("缺少项目ID参数",101);

    $projectinfo = Project::getInfoById($projectid) ;

    if(empty($projectinfo))
        throw new MyException("项目信息不存在",101);

    if(!Project::checkProjectForUser($uid,$projectinfo["id"])) throw new MyException("无权限查看该项目",1039);

    $resData = array();
    $project_one = Project_one::getInfoByProjectId($projectinfo["id"]);
    $samecount1 = Project_one::getPageSameList(1, 10, 0, $project_one['project_name'], '', '', '', '', '');
    $samecount2 = Project_one::getPageSameList(1, 10, 0, '', $project_one['project_detail'], '', '', '', '');
    /* $samecount3 = Project_one::getPageSameList(1, 10, 0, '', '', $project_one['project_partya'], '', '', '');
    $samecount4 = Project_one::getPageSameList(1, 10, 0, '', '', '', $project_one['project_partya_address'], '', '');
    $samecount5 = Project_one::getPageSameList(1, 10, 0, '', '', '', '', $project_one['project_linkman'], '');
    $samecount6 = Project_one::getPageSameList(1, 10, 0, '', '', '', '', '', $project_one['project_linktel']); */
    $samecount3 = 1;
    $samecount4 = 1;
    $samecount5 = 1;
    $samecount6 = 1;

    $resData = array(
        "project_name_num" => $samecount1 - 1,
        "project_detail_num" => $samecount2 -1,
        "project_partya_num" => $samecount3 -1,
        "project_partya_address_num" => $samecount4 -1,
        "project_linkman_num" => $samecount5 -1,
        "project_linktel_num" => $samecount6 -1);

    echo action_msg_data(API::SUCCESS_MSG, API::SUCCESS, $resData);

}catch (MyException $e){
    $api->ApiError($e->getCode(), $e->getMessage());
}
