<?php
/**
 * Created by PhpStorm.
 * User: zhima
 * Date: 2018/12/27
 * Time: 下午4:51
 */

require_once('web_init.php');
require_once('usercheck.php');


$id = isset($_GET['id'])?safeCheck($_GET['id']):0;//获取项目的id，即历史id
$isUpdate = isset($_GET['isUpdate'])?safeCheck($_GET['isUpdate']):0;// 是（1）否（0）是从方案生成页面点击“上一步”回到本页面


$info = Selection_history::getInfoById($id);

if($info["type"]==selection_history::HISTORY_HAND) {
    if ($isUpdate != 0) {
        require_once("selection_plan_one_manual_update.php");
    }else{
        require_once("selection_plan_one_manual.php");
    }
}
else {
    if ($isUpdate != 0) {
        require_once("selection_plan_one_update.php");
    }else{
        require_once("selection_plan_one.php");
    }

}