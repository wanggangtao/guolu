<?php
/**
 * 管理员处理  admin_do.php
 *
 * @version       v0.03
 * @create time   2014-9-4
 * @update time   2016/3/25
 * @author        dxl jt
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */
require_once('web_init.php');
$act = safeCheck($_GET['act'], 0);
switch($act){
    case 'getAfter'://添加管理员
        $code   =  safeCheck($_POST['code'], 0);



        try {

            $rs = rule::getPageList(0,0,$code,'');
            echo action_msg($rs,1);
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;

    case 'getInfo'://添加管理员
        $code   =  safeCheck($_POST['code'], 0);

        try {

            $rs = fact::getInfoByCode($code);
            echo action_msg($rs,1);
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;

    case 'getInfoByContent'://添加管理员
        $content   =  safeCheck($_POST['content'], 0);
        $before   =  safeCheck($_POST['before'], 0);

        try {

            $ruleInfo = rule::getInfoBybeforeAndKeyword($before,$content);

            if(!empty($ruleInfo))
            {
                $rs = fact::getInfoByCode($ruleInfo["after"]);
                echo action_msg($rs,1);

            }
            else
            {
                echo action_msg("对不起，小服的知识还不够，暂不能理解您说的！",2);
            }
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;
}
?>