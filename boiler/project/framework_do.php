<?php
/**
 * 组织框架处理  project_do.php
 *
 * @version       v0.01
 * @create time   2018/6/29
 * @update time
 * @author        wzp
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */
require_once('web_init.php');
require_once('usercheck.php');

$act = safeCheck($_GET['act'], 0);
switch($act){
    case 'add'://添加
        $pid   = safeCheck($_GET['pid']);
        $ppid  = safeCheck($_GET['ppid']);
        $name  = safeCheck($_GET['name'], 0);
        $pInfo = Project_client_framework::getInfoById($pid);
        try {
            $attrs = array(
                "pt_id"=>$ppid,
                "name"=>$name,
                "pid"=>$pid,
                "level"=>($pInfo['level']+1),
                "addtime"=>time()
            );

            $id = Project_client_framework::add($attrs);
            if($id)
                echo $id;
            else
                echo 0;

        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;
    case 'edit'://修改
        $id   = safeCheck($_GET['id']);
        $name  = safeCheck($_GET['name'], 0);
        try {
            $attrs = array(
                "name"=>$name
            );
            $rs = Project_client_framework::update($id, $attrs);
            echo $rs;

        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;
    case 'del'://删除
        $id   = safeCheck($_GET['id']);
        try {
            $rs = Project_client_framework::delAllByPid($id);
            echo $rs;

        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;
}
?>