<?php
/**
 * Created by PhpStorm.
 * User: zhima
 * Date: 2018/7/3
 * Time: 下午7:02
 */

try {
    $projectid =  isset($_POST['projectid'])?safeCheck($_POST['projectid']):0;
    $type =  isset($_POST['type'])?safeCheck($_POST['type']):0;
    $url      = safeCheck($_POST['url'], 0);

    if(empty($url)) throw new MyException("图片路径不能为空",101);

    if(empty($projectid)) throw new MyException("缺少项目ID参数",101);

    $projectinfo = Project::getInfoById($projectid) ;

    if(empty($projectinfo))
    {
        throw new MyException("项目信息不存在",101);
    }

    if(empty($type))
        throw new MyException("请指定图库类型",101);

    if(!in_array($type, array(1, 2)))
        throw new MyException("图库类型错误",101);

    $attrs = array(
        "projectid"=>$projectid,
        "type"=>$type,
        "url"=>$url,
        "time"=>time()
    );
    $thisid = Project_pictures::add($attrs);
    $resData = array(
        'id' => $thisid,
        'url' => $HTTP_PATH.$url
    );

    echo action_msg_data(API::SUCCESS_MSG, API::SUCCESS, $resData);

}catch (MyException $e){
    $api->ApiError($e->getCode(), $e->getMessage());
}
