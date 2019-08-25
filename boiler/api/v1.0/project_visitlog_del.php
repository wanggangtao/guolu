<?php
/**
 * Created by PhpStorm.
 * User: zhima
 * Date: 2018/7/3
 * Time: 下午7:02
 */

try {
    $id =  isset($_POST['id'])?safeCheck($_POST['id']):0;

    if(empty($id)) throw new MyException("缺少ID",101);

    $info = Project_visitlog::getInfoById($id) ;

    if(empty($info))
    {
        throw new MyException("记录不存在",101);
    }

    $projectinfo = Project::getInfoById($info['projectid']);
    if($uid != $projectinfo['user']){
        throw new MyException("没有删除权限！",101);
    }
    if(!empty($info['comuser']))
        throw new MyException("该记录已评论，不能删除！",101);

    $res =  Project_visitlog::del($id);

    if($res > 0)
        echo action_msg_data(API::SUCCESS_MSG, API::SUCCESS, null);
    else
        echo action_msg_data('fail', 101);
}catch (MyException $e){
    $api->ApiError($e->getCode(), $e->getMessage());
}
