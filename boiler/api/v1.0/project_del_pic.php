<?php
/**
 * Created by PhpStorm.
 * User: zhima
 * Date: 2018/7/3
 * Time: 下午7:02
 */

try {
    $id =  isset($_POST['id'])?safeCheck($_POST['id']):0;

    if(empty($id)) throw new MyException("缺少图片ID参数",101);

    $picinfo = Project_pictures::getInfoById($id) ;

    if(empty($picinfo))
    {
        throw new MyException("图片不存在",101);
    }

    $res = Project_pictures::del($id);

    if($res > 0)
        echo action_msg_data(API::SUCCESS_MSG, API::SUCCESS);
    else
        echo action_msg_data('fail', 101);
}catch (MyException $e){
    $api->ApiError($e->getCode(), $e->getMessage());
}
