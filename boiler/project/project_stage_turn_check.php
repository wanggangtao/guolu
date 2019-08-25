<?php
/**
 * Created by PhpStorm.
 * User: wzp
 * Date: 2018/6/30
 * Time: 9:18
 */
require_once('web_init.php');
require_once('usercheck.php');

if(!isset($_GET['id']))
    die();

$id = safeCheck($_GET['id']);
//通过tag值识别选型方案页面
$tag=isset($_GET['tag'])?safeCheck($_GET['tag']):0;

$projectInfo = Project::getInfoById($id);
if(empty($projectInfo))
    die();

if($projectInfo['del_flag'] == 1){
    header("Location: project_deleteed.php");exit();
}
//if($USERId != $projectInfo['user'])
//    die();

/*$turnlevel = 0;
if($projectInfo['status'] == 2){
    $turnlevel = $projectInfo['level'] + 1;
}else{
    $turnlevel = $projectInfo['level'];
}*/

if($projectInfo['stop_flag'] != 1){
    switch ($projectInfo['level']){
        case 1:header("Location: project_stage_one_check.php?tag=".$tag ."&id=".$id);exit();break;
        case 2:header("Location: project_stage_two_check.php?tag=".$tag ."&id=".$id);exit();break;
        case 3:header("Location: project_stage_three_check.php?tag=".$tag ."&id=".$id);exit();break;
        case 4:header("Location: project_stage_four_check.php?tag=".$tag ."&id=".$id);exit();break;
        case 5:header("Location: project_stage_five_check.php?tag=".$tag ."&id=".$id);exit();break;
        default:
            header("Location: project_stage_one_check.php?tag=".$tag ."&id=".$id);exit();break;
    }
}else{
    header("Location: project_stop_check.php?tag=".$tag ."&id=".$id);exit();
}

?>