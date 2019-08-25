<?php
/**
 * Created by PhpStorm.
 * User: zhima
 * Date: 2018/7/3
 * Time: 下午7:02
 */
try {

    $id         =  isset($_POST['id'])?safeCheck($_POST['id']):0;
    if(empty($id)) throw new MyException("缺少记录ID参数",101);
    $info = Project_visitlog::getInfoById($id) ;
    if(empty($info))
        throw new MyException("记录不存在",101);
    $projectinfo = Project::getInfoById($info['projectid']);
    if($uid != $projectinfo['user']){
        throw new MyException("没有编辑权限！",101);
    }
    if(!empty($info['comuser']))
        throw new MyException("该记录已评论，不能编辑！",101);

    $content           = HTMLEncode($_POST['content']);
    $effect            = HTMLEncode($_POST['effect']);
    $plan              = HTMLEncode($_POST['plan']);
    $visitway          = safeCheck($_POST['visitway']);
    $positionstr       = safeCheck($_POST['positionstr'], 0);
    $tel               = safeCheck($_POST['tel'], 0);
    $target            = safeCheck($_POST['target'], 0);

    if(empty($target)){
        throw new MyException("拜访对象不能为空",101);
    }
    if(empty($tel)){
        throw new MyException("联系方式不能为空",101);
    }
    if(empty($positionstr)){
        throw new MyException("职位不能为空",101);
    }
    if(empty($visitway)){
        throw new MyException("拜访方式不能为空",101);
    }
    if(empty($content)){
        throw new MyException("拜访内容不能为空",101);
    }
    if(empty($effect)){
        throw new MyException("拜访效果不能为空",101);
    }
    if(empty($plan)){
        throw new MyException("下步计划不能为空",101);
    }
    $attrslog = array(
        "target"=>$target,
        "tel"=>$tel,
        "position"=>$positionstr,
        "visitway"=>$visitway,
        "content"=>$content,
        "effect"=>$effect,
        "plan"=>$plan,
        "updatetime"=>time()
    );
    Project_visitlog::update($id, $attrslog);
    echo action_msg_data(API::SUCCESS_MSG, API::SUCCESS, null);
}catch (MyException $e){
    $api->ApiError($e->getCode(), $e->getMessage());
}
?>