<?php
/**
 * 登录表单处理
 * @version       v0.02
 * @create time   2014/9/4
 * @update time   2016/3/25
 * @author        wzp
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 **/
require_once('init.php');
require_once('usercheck.php');

//获取值
$id   = safeCheck($_POST['id']);
$type   = safeCheck($_POST['type']);
$content  = HTMLEncode($_POST['content']);

try {
    $attrs = array(
        "type"=>$type,
        "content"=>$content,
        "lasttime"=>time()
    );
    if(empty($id)){
        Message_tpl::add($attrs);
    }else{
        Message_tpl::update($id, $attrs);
    }

    echo action_msg("保存成功！", 1);
}catch(MyException $e){
    echo $e->jsonMsg();
}

?>