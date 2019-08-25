<?php
/**
 * Created by PhpStorm.
 * User: 张鑫
 * Date: 2019/3/20
 * Time: 13:35
 */

require_once('admin_init.php');
require_once('admincheck.php');

$act = safeCheck($_GET['act'], 0);
switch($act) {
    case 'add'://添加
        $url=safeCheck($_POST['pic_path0'],0);
        $keyword=safeCheck($_POST['keyword'],0);
        $content=$_POST['content'];
        $category=safeCheck($_POST['category'],0);
        $video_cover=safeCheck($_POST['video_cover_path'],0);
        $cover=safeCheck($_POST['pic_path1'],0);
        $type=safeCheck($_POST['type'],0);

        $url_type = safeCheck($_POST['url_type'],0);

        try {
            $params=array();
            $params['url']=$url;
            $params['type']=$type;
            $params['keyword']=$keyword;
            $params['content']=$content;
            $params['category']=$category;
            $params['video_cover']=$video_cover;
            $params['cover']=$cover;
            $params['addtime']=time();
            $params['operator']=$_SESSION[$session_ADMINID];
            $params['lastupdatetime']=time();
            $params['url_type']=$url_type;
            $rs = Service_problem::add($params);
            echo action_msg("添加成功",1);
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;

    case 'edit'://修改
        $id=safeCheck($_POST['id'],0);
        $url=safeCheck($_POST['pic_path0'],0);
        $keyword=safeCheck($_POST['keyword'],0);
        $content=$_POST['content'];
        $category=safeCheck($_POST['category'],0);
        $video_cover=safeCheck($_POST['video_cover_path'],0);
        $cover=safeCheck($_POST['pic_path1'],0);
        $type=safeCheck($_POST['type'],0);
        $url_type=safeCheck($_POST['url_type'],0);

        try {
            $params=array();
            $params['url']=$url;
            $params['type']=$type;
            $params['url_type']=$url_type;
            $params['keyword']=$keyword;
            $params['content']=$content;
            $params['category']=$category;
            $params['video_cover']=$video_cover;
            $params['cover']=$cover;
            $params['operator']=$_SESSION[$session_ADMINID];
            $params['lastupdatetime']=time();

            $rs = Service_problem::update($id, $params);
            echo action_msg("修改成功",1);
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;
    case 'del':{
        $id=safeCheck($_POST['id']);

        try
        {
            $attrs=array(
              "status"=>-1
            );

            $rst=Service_problem::update($id, $attrs);
            echo action_msg("删除成功", 1);
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
    }
        break;
    case 'getInfo':{
        $code = safeCheck($_POST['code'],0);

        try {
            $rs = Service_problem::getInfoByCode($code);
            echo action_msg($rs,1);
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
    }
        break;
    case 'getFact':
        $category = safeCheck($_POST['category'],0);

        $params=array(
            "category"=>$category
        );
        try {
            $rs = Service_problem::getList($params);
            echo action_msg($rs,1);
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;
        break;
    default:
        break;
}