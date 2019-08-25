<?php
/**
 * Created by PhpStorm.
 * User: ChrisLin3
 * Date: 2019/8/16
 * Time: 13:19
 * @author zhh_fu
 */
require_once('admin_init.php');
require_once('admincheck.php');

$FLAG_TOPNAV	= "weixincontent";
$FLAG_LEFTMENU  = 'weixin_repair_untreated';
if(isset($_GET['id'])){
    $id = safeCheck($_GET['id'],1);
}else{
    exit();
}
$info = repair_order::getInfoById($id);
if (!empty($info['status'])){
    if ($info['status'] == 1){
        $FLAG_LEFTMENU = "weixin_repair_untreated";
    }
    if ($info['status'] == 2){
        $FLAG_LEFTMENU = "weixin_repair_treating";
    }
    if ($info['status'] == 3){
        $FLAG_LEFTMENU = "weixin_repair_treated";
    }
}

/*$type = 1;
if(isset($_GET['type'])){
    $type = $_GET['type'];
}*/

//if (isset($_GET['child_status'])){
//    $child_status = safeCheck($_GET['child_status'],1);
//}
//else{
//    exit();
//}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>公众号管理 - 预约管理</title>
    <link rel="stylesheet" href="css/style.css" type="text/css" />
    <link rel="stylesheet" href="css/form.css" type="text/css" />
    <link rel="stylesheet" href="css/boxy.css" type="text/css" />
    <link rel="stylesheet" href="css/newlayui.css">
    <link rel="stylesheet" href="layui/css/layui.css">
    <link rel="stylesheet" href="layui/css/layui.css">
    <script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="js/layer/layer.js"></script>
    <script type="text/javascript" src="js/common.js"></script>

</head>
<body>
<div id="header">
    <?php include('top.inc.php');?>
    <?php include('nav.inc.php');?>
</div>
<style>
    .table_all_list {
        table-layout: fixed;
    }

    .table_all_list tbody td {
        /*text-align: left !important;*/
        border: 0px !important;
        vertical-align:middle;
    }
    .table_all_list tbody th {
        /*text-align: left !important;*/
        border: 0px !important;
    }

    .table_all_list tbody td input {
        margin: 0px !important;
    }
</style>
<div id="container">
    <?php include('weixin_menu_inc.php');?>
    <div id="maincontent">
        <div id="position">当前位置：<a href="weixin_employees_info.php">公众号管理</a> &gt; 预约管理</div>
<!--        所有状态公用的历史页面，因此通过子状态进行判断并显示-->
        <?php
        if($info['child_status'] == 33){
            echo '
                    <div class="orderstream">
                        <div class="base">
                            <div id="u001" class="ax_default" data-left="381" data-top="207" data-width="30" data-height="30">
                                <div id="u002" class="ax_default ">
                                    <img  class="img " src="images/u2172.png">
                                </div>
                                <div id="u003" class="ax_default _三级标题 wordsize">
                                    <p>
                                        <span>1</span>
                                    </p>
                                </div>
                            </div>
                            <div id="u004" class="ax_default label orderstyle">
                                <div id="u005" class="text ">
                                    <p><span>待派单</span></p>
                                </div>
                            </div>
                            <div id="u006" class="ax_default pic1">
                                <img  class="img " src="images/u2170.png">
                            </div>
                        </div>
            
                        <div class="base">
                            <div id="u007" class="ax_default" data-left="381" data-top="207" data-width="30" data-height="30">
                                <div id="u008" class="ax_default ">
                                    <img  class="img " src="images/u2172.png">
                                </div>
                                <div id="u009" class="ax_default _三级标题 wordsize">
                                    <p>
                                        <span>2</span>
                                    </p>
                                </div>
                            </div>
                            <div id="u010" class="ax_default label orderstyle">
                                <div id="u011" class="text ">
                                    <p><span>待接单</span></p>
                                </div>
                            </div>
                            <div id="u012" class="ax_default pic1">
                                <img  class="img " src="images/u2170.png">
                            </div>
                        </div>
        
                        <div class="base">
                            <div id="u013" class="ax_default" data-left="381" data-top="207" data-width="30" data-height="30">
                                <div id="u014" class="ax_default ">
                                    <img  class="img " src="images/u2172.png">
                                </div>
                                <div id="u015" class="ax_default _三级标题 wordsize">
                                    <p>
                                        <span>3</span>
                                    </p>
                                </div>
                            </div>
                            <div id="u016" class="ax_default label orderstyle">
                                <div id="u017" class="text ">
                                    <p><span>已接单</span></p>
                                </div>
                            </div>
                            <div id="u018" class="ax_default pic1">
                                <img  class="img " src="images/u2170.png">
                            </div>
                        </div>
            
                        <div class="base">
                            <div id="u019" class="ax_default" data-left="381" data-top="207" data-width="30" data-height="30">
                                <div id="u020" class="ax_default ">
                                    <img  class="img " src="images/u2172.png">
                                </div>
                                <div id="u021" class="ax_default _三级标题 wordsize">
                                    <p>
                                        <span>4</span>
                                    </p>
                                </div>
                            </div>
                            <div id="u022" class="ax_default label orderstyle">
                                <div id="u023" class="text ">
                                    <p><span>待支付</span></p>
                                </div>
                            </div>
                            <div id="u024" class="ax_default pic1">
                                <img  class="img " src="images/u2170.png">
                            </div>
                        </div>
            
                        <div class="base">
                            <div id="u025" class="ax_default" data-left="381" data-top="207" data-width="30" data-height="30">
                                <div id="u026" class="ax_default ">
                                    <img  class="img " src="images/u2172.png">
                                </div>
                                <div id="u027" class="ax_default _三级标题 wordsize">
                                    <p>
                                        <span>5</span>
                                    </p>
                                </div>
                            </div>
                            <div id="u028" class="ax_default label orderstyle">
                                <div id="u029" class="text ">
                                    <p><span>已完工</span></p>
                                </div>
                            </div>
                            <div id="u099" class="ax_default tupian">
                                <img  class="status img " src="images/已审核_u3141.png">
                            </div>
                        </div>
                    </div>
                ';
        }
        if ($info['child_status'] == 32){
            echo '
                    <div class="orderstream">
                        <div class="base">
                            <div id="u001" class="ax_default" data-left="381" data-top="207" data-width="30" data-height="30">
                                <div id="u002" class="ax_default ">
                                    <img  class="img " src="images/u2172.png">
                                </div>
                                <div id="u003" class="ax_default _三级标题 wordsize">
                                    <p>
                                        <span>1</span>
                                    </p>
                                </div>
                            </div>
                            <div id="u004" class="ax_default label orderstyle">
                                <div id="u005" class="text ">
                                    <p><span>待派单</span></p>
                                </div>
                            </div>
                            <div id="u006" class="ax_default pic1">
                                <img  class="img " src="images/u2170.png">
                            </div>
                        </div>
            
                        <div class="base">
                            <div id="u007" class="ax_default" data-left="381" data-top="207" data-width="30" data-height="30">
                                <div id="u008" class="ax_default ">
                                    <img  class="img " src="images/u2172.png">
                                </div>
                                <div id="u009" class="ax_default _三级标题 wordsize">
                                    <p>
                                        <span>2</span>
                                    </p>
                                </div>
                            </div>
                            <div id="u010" class="ax_default label orderstyle">
                                <div id="u011" class="text ">
                                    <p><span>待接单</span></p>
                                </div>
                            </div>
                            <div id="u012" class="ax_default pic1">
                                <img  class="img " src="images/u2170.png">
                            </div>
                        </div>
        
                        <div class="base">
                            <div id="u013" class="ax_default" data-left="381" data-top="207" data-width="30" data-height="30">
                                <div id="u014" class="ax_default ">
                                    <img  class="img " src="images/u2172.png">
                                </div>
                                <div id="u015" class="ax_default _三级标题 wordsize">
                                    <p>
                                        <span>3</span>
                                    </p>
                                </div>
                            </div>
                            <div id="u016" class="ax_default label orderstyle">
                                <div id="u017" class="text ">
                                    <p><span>已接单</span></p>
                                </div>
                            </div>
                            <div id="u018" class="ax_default pic1">
                                <img  class="img " src="images/u2170.png">
                            </div>
                        </div>
            
                        <div class="base">
                            <div id="u019" class="ax_default" data-left="381" data-top="207" data-width="30" data-height="30">
                                <div id="u020" class="ax_default ">
                                    <img  class="img " src="images/u2172.png">
                                </div>
                                <div id="u021" class="ax_default _三级标题 wordsize">
                                    <p>
                                        <span>4</span>
                                    </p>
                                </div>
                            </div>
                            <div id="u022" class="ax_default label orderstyle">
                                <div id="u023" class="text ">
                                    <p><span>待支付</span></p>
                                </div>
                            </div>
                            <div id="u024" class="ax_default pic1">
                                <img  class="img " src="images/u2170.png">
                            </div>
                        </div>
            
                        <div class="base">
                            <div id="u025" class="ax_default" data-left="381" data-top="207" data-width="30" data-height="30">
                                <div id="u026" class="ax_default ">
                                    <img  class="img " src="images/u2172.png">
                                </div>
                                <div id="u027" class="ax_default _三级标题 wordsize">
                                    <p>
                                        <span>5</span>
                                    </p>
                                </div>
                            </div>
                            <div id="u028" class="ax_default label orderstyle">
                                <div id="u029" class="text ">
                                    <p><span>已完工</span></p>
                                </div>
                            </div>
                            <div id="u099" class="ax_default tupian">
                                <img  class="status img " src="images/待审核_u3139.png">
                            </div>
                        </div>
                    </div>
                ';
        }
        if ($info['child_status'] == 31){
            echo '
                    <div class="orderstream">
                        <div class="base">
                            <div id="u001" class="ax_default" data-left="381" data-top="207" data-width="30" data-height="30">
                                <div id="u002" class="ax_default ">
                                    <img  class="img " src="images/u2172.png">
                                </div>
                                <div id="u003" class="ax_default _三级标题 wordsize">
                                    <p>
                                        <span>1</span>
                                    </p>
                                </div>
                            </div>
                            <div id="u004" class="ax_default label orderstyle">
                                <div id="u005" class="text ">
                                    <p><span>待派单</span></p>
                                </div>
                            </div>
                            <div id="u006" class="ax_default pic1">
                                <img  class="img " src="images/u2170.png">
                            </div>
                        </div>
            
                        <div class="base">
                            <div id="u007" class="ax_default" data-left="381" data-top="207" data-width="30" data-height="30">
                                <div id="u008" class="ax_default ">
                                    <img  class="img " src="images/u2172.png">
                                </div>
                                <div id="u009" class="ax_default _三级标题 wordsize">
                                    <p>
                                        <span>2</span>
                                    </p>
                                </div>
                            </div>
                            <div id="u010" class="ax_default label orderstyle">
                                <div id="u011" class="text ">
                                    <p><span>待接单</span></p>
                                </div>
                            </div>
                            <div id="u012" class="ax_default pic1">
                                <img  class="img " src="images/u2170.png">
                            </div>
                        </div>
        
                        <div class="base">
                            <div id="u013" class="ax_default" data-left="381" data-top="207" data-width="30" data-height="30">
                                <div id="u014" class="ax_default ">
                                    <img  class="img " src="images/u2172.png">
                                </div>
                                <div id="u015" class="ax_default _三级标题 wordsize">
                                    <p>
                                        <span>3</span>
                                    </p>
                                </div>
                            </div>
                            <div id="u016" class="ax_default label orderstyle">
                                <div id="u017" class="text ">
                                    <p><span>已接单</span></p>
                                </div>
                            </div>
                            <div id="u018" class="ax_default pic1">
                                <img  class="img " src="images/u2170.png">
                            </div>
                        </div>
            
                        <div class="base">
                            <div id="u019" class="ax_default" data-left="381" data-top="207" data-width="30" data-height="30">
                                <div id="u020" class="ax_default ">
                                    <img  class="img " src="images/u2172.png">
                                </div>
                                <div id="u021" class="ax_default _三级标题 wordsize">
                                    <p>
                                        <span>4</span>
                                    </p>
                                </div>
                            </div>
                            <div id="u022" class="ax_default label orderstyle">
                                <div id="u023" class="text ">
                                    <p><span>待支付</span></p>
                                </div>
                            </div>
                            <div id="u024" class="ax_default pic1">
                                <img  class="img " src="images/u2170.png">
                            </div>
                        </div>
            
                        <div class="base">
                            <div id="u025" class="ax_default" data-left="381" data-top="207" data-width="30" data-height="30">
                                <div id="u026" class="ax_default ">
                                    <img  class="img " src="images/u2172.png">
                                </div>
                                <div id="u027" class="ax_default _三级标题 wordsize">
                                    <p>
                                        <span>5</span>
                                    </p>
                                </div>
                            </div>
                            <div id="u028" class="ax_default label orderstyle">
                                <div id="u029" class="text ">
                                    <p><span>已完工</span></p>
                                </div>
                            </div>
                            <div id="u099" class="ax_default tupian">
                                <img  class="status img " src="images/已取消_u3140.png">
                            </div>
                        </div>
                    </div>
                ';
        }
        if ($child_status == 23){
            if ((time()-$info['finish_time']) > 604800){
                echo '
                    <div class="orderstream">
                        <div class="base">
                            <div id="u001" class="ax_default" data-left="381" data-top="207" data-width="30" data-height="30">
                                <div id="u002" class="ax_default ">
                                    <img  class="img " src="images/u2172.png">
                                </div>
                                <div id="u003" class="ax_default _三级标题 wordsize">
                                    <p>
                                        <span>1</span>
                                    </p>
                                </div>
                            </div>
                            <div id="u004" class="ax_default label orderstyle">
                                <div id="u005" class="text ">
                                    <p><span>待派单</span></p>
                                </div>
                            </div>
                            <div id="u006" class="ax_default pic1">
                                <img  class="img " src="images/u2170.png">
                            </div>
                        </div>
            
                        <div class="base">
                            <div id="u007" class="ax_default" data-left="381" data-top="207" data-width="30" data-height="30">
                                <div id="u008" class="ax_default ">
                                    <img  class="img " src="images/u2172.png">
                                </div>
                                <div id="u009" class="ax_default _三级标题 wordsize">
                                    <p>
                                        <span>2</span>
                                    </p>
                                </div>
                            </div>
                            <div id="u010" class="ax_default label orderstyle">
                                <div id="u011" class="text ">
                                    <p><span>待接单</span></p>
                                </div>
                            </div>
                            <div id="u012" class="ax_default pic1">
                                <img  class="img " src="images/u2170.png">
                            </div>
                        </div>
        
                        <div class="base">
                            <div id="u013" class="ax_default" data-left="381" data-top="207" data-width="30" data-height="30">
                                <div id="u014" class="ax_default ">
                                    <img  class="img " src="images/u2172.png">
                                </div>
                                <div id="u015" class="ax_default _三级标题 wordsize">
                                    <p>
                                        <span>3</span>
                                    </p>
                                </div>
                            </div>
                            <div id="u016" class="ax_default label orderstyle">
                                <div id="u017" class="text ">
                                    <p><span>已接单</span></p>
                                </div>
                            </div>
                            <div id="u018" class="ax_default pic1">
                                <img  class="img " src="images/u2170.png">
                            </div>
                            <div id="u099" class="ax_default tupian">
                                <img  class="img status1" src="images/异常_u2791.png">
                            </div>
                        </div>
            
                        <div class="base">
                            <div id="u019" class="ax_default" data-left="381" data-top="207" data-width="30" data-height="30">
                                <div id="u020" class="ax_default ">
                                    <img  class="img " src="images/u2172.png">
                                </div>
                                <div id="u021" class="ax_default _三级标题 wordsize">
                                    <p>
                                        <span>4</span>
                                    </p>
                                </div>
                            </div>
                            <div id="u022" class="ax_default label orderstyle">
                                <div id="u023" class="text ">
                                    <p><span>待支付</span></p>
                                </div>
                            </div>
                            <div id="u024" class="ax_default pic1">
                                <img  class="img " src="images/u2170.png">
                            </div>
                        </div>
            
                        <div class="base">
                            <div id="u025" class="ax_default" data-left="381" data-top="207" data-width="30" data-height="30">
                                <div id="u026" class="ax_default ">
                                    <img  class="img " src="images/u2167.png">
                                </div>
                                <div id="u027" class="ax_default _三级标题 wordsize">
                                    <p>
                                        <span>5</span>
                                    </p>
                                </div>
                            </div>
                            <div id="u028" class="ax_default label orderstyle">
                                <div id="u029" class="text ">
                                    <p><span>已完工</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    ';
            }
            else{
                echo '
                            <div class="orderstream">
                                <div class="base">
                                    <div id="u001" class="ax_default" data-left="381" data-top="207" data-width="30" data-height="30">
                                        <div id="u002" class="ax_default ">
                                            <img  class="img " src="images/u2172.png">
                                        </div>
                                        <div id="u003" class="ax_default _三级标题 wordsize">
                                            <p>
                                                <span>1</span>
                                            </p>
                                        </div>
                                    </div>
                                    <div id="u004" class="ax_default label orderstyle">
                                        <div id="u005" class="text ">
                                            <p><span>待派单</span></p>
                                        </div>
                                    </div>
                                    <div id="u006" class="ax_default pic1">
                                        <img  class="img " src="images/u2170.png">
                                    </div>
                                </div>
                    
                                <div class="base">
                                    <div id="u007" class="ax_default" data-left="381" data-top="207" data-width="30" data-height="30">
                                        <div id="u008" class="ax_default ">
                                            <img  class="img " src="images/u2172.png">
                                        </div>
                                        <div id="u009" class="ax_default _三级标题 wordsize">
                                            <p>
                                                <span>2</span>
                                            </p>
                                        </div>
                                    </div>
                                    <div id="u010" class="ax_default label orderstyle">
                                        <div id="u011" class="text ">
                                            <p><span>待接单</span></p>
                                        </div>
                                    </div>
                                    <div id="u012" class="ax_default pic1">
                                        <img  class="img " src="images/u2170.png">
                                    </div>
                                </div>
                
                                <div class="base">
                                    <div id="u013" class="ax_default" data-left="381" data-top="207" data-width="30" data-height="30">
                                        <div id="u014" class="ax_default ">
                                            <img  class="img " src="images/u2172.png">
                                        </div>
                                        <div id="u015" class="ax_default _三级标题 wordsize">
                                            <p>
                                                <span>3</span>
                                            </p>
                                        </div>
                                    </div>
                                    <div id="u016" class="ax_default label orderstyle">
                                        <div id="u017" class="text ">
                                            <p><span>已接单</span></p>
                                        </div>
                                    </div>
                                    <div id="u018" class="ax_default pic1">
                                        <img  class="img " src="images/u2170.png">
                                    </div>
                                </div>
                    
                                <div class="base">
                                    <div id="u019" class="ax_default" data-left="381" data-top="207" data-width="30" data-height="30">
                                        <div id="u020" class="ax_default ">
                                            <img  class="img " src="images/u2172.png">
                                        </div>
                                        <div id="u021" class="ax_default _三级标题 wordsize">
                                            <p>
                                                <span>4</span>
                                            </p>
                                        </div>
                                    </div>
                                    <div id="u022" class="ax_default label orderstyle">
                                        <div id="u023" class="text ">
                                            <p><span>待支付</span></p>
                                        </div>
                                    </div>
                                    <div id="u024" class="ax_default pic1">
                                        <img  class="img " src="images/u2170.png">
                                    </div>
                                </div>
                    
                                <div class="base">
                                    <div id="u025" class="ax_default" data-left="381" data-top="207" data-width="30" data-height="30">
                                        <div id="u026" class="ax_default ">
                                            <img  class="img " src="images/u2167.png">
                                        </div>
                                        <div id="u027" class="ax_default _三级标题 wordsize">
                                            <p>
                                                <span>5</span>
                                            </p>
                                        </div>
                                    </div>
                                    <div id="u028" class="ax_default label orderstyle">
                                        <div id="u029" class="text ">
                                            <p><span>已完工</span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        ';
            }
        }
        if ($info['child_status'] == 22){
            echo '
                    <div class="orderstream">
                        <div class="base">
                            <div id="u001" class="ax_default" data-left="381" data-top="207" data-width="30" data-height="30">
                                <div id="u002" class="ax_default ">
                                    <img  class="img " src="images/u2172.png">
                                </div>
                                <div id="u003" class="ax_default _三级标题 wordsize">
                                    <p>
                                        <span>1</span>
                                    </p>
                                </div>
                            </div>
                            <div id="u004" class="ax_default label orderstyle">
                                <div id="u005" class="text ">
                                    <p><span>待派单</span></p>
                                </div>
                            </div>
                            <div id="u006" class="ax_default pic1">
                                <img  class="img " src="images/u2170.png">
                            </div>
                        </div>
            
                        <div class="base">
                            <div id="u007" class="ax_default" data-left="381" data-top="207" data-width="30" data-height="30">
                                <div id="u008" class="ax_default ">
                                    <img  class="img " src="images/u2172.png">
                                </div>
                                <div id="u009" class="ax_default _三级标题 wordsize">
                                    <p>
                                        <span>2</span>
                                    </p>
                                </div>
                            </div>
                            <div id="u010" class="ax_default label orderstyle">
                                <div id="u011" class="text ">
                                    <p><span>待接单</span></p>
                                </div>
                            </div>
                            <div id="u012" class="ax_default pic1">
                                <img  class="img " src="images/u2170.png">
                            </div>
                        </div>
        
                        <div class="base">
                            <div id="u013" class="ax_default" data-left="381" data-top="207" data-width="30" data-height="30">
                                <div id="u014" class="ax_default ">
                                    <img  class="img " src="images/u2172.png">
                                </div>
                                <div id="u015" class="ax_default _三级标题 wordsize">
                                    <p>
                                        <span>3</span>
                                    </p>
                                </div>
                            </div>
                            <div id="u016" class="ax_default label orderstyle">
                                <div id="u017" class="text ">
                                    <p><span>已接单</span></p>
                                </div>
                            </div>
                            <div id="u018" class="ax_default pic1">
                                <img  class="img " src="images/u2170.png">
                            </div>
                        </div>
            
                        <div class="base">
                            <div id="u019" class="ax_default" data-left="381" data-top="207" data-width="30" data-height="30">
                                <div id="u020" class="ax_default ">
                                    <img  class="img " src="images/u2167.png">
                                </div>
                                <div id="u021" class="ax_default _三级标题 wordsize">
                                    <p>
                                        <span>4</span>
                                    </p>
                                </div>
                            </div>
                            <div id="u022" class="ax_default label orderstyle">
                                <div id="u023" class="text ">
                                    <p><span>待支付</span></p>
                                </div>
                            </div>
                            <div id="u024" class="ax_default pic1">
                                <img  class="img " src="images/u2170.png">
                            </div>
                        </div>
            
                        <div class="base">
                            <div id="u025" class="ax_default" data-left="381" data-top="207" data-width="30" data-height="30">
                                <div id="u026" class="ax_default ">
                                    <img  class="img " src="images/u2167.png">
                                </div>
                                <div id="u027" class="ax_default _三级标题 wordsize">
                                    <p>
                                        <span>5</span>
                                    </p>
                                </div>
                            </div>
                            <div id="u028" class="ax_default label orderstyle">
                                <div id="u029" class="text ">
                                    <p><span>已完工</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                ';
        }
        if ($info['child_status'] == 21){
            echo '
                    <div class="orderstream">
                        <div class="base">
                            <div id="u001" class="ax_default" data-left="381" data-top="207" data-width="30" data-height="30">
                                <div id="u002" class="ax_default ">
                                    <img  class="img " src="images/u2172.png">
                                </div>
                                <div id="u003" class="ax_default _三级标题 wordsize">
                                    <p>
                                        <span>1</span>
                                    </p>
                                </div>
                            </div>
                            <div id="u004" class="ax_default label orderstyle">
                                <div id="u005" class="text ">
                                    <p><span>待派单</span></p>
                                </div>
                            </div>
                            <div id="u006" class="ax_default pic1">
                                <img  class="img " src="images/u2170.png">
                            </div>
                        </div>
            
                        <div class="base">
                            <div id="u007" class="ax_default" data-left="381" data-top="207" data-width="30" data-height="30">
                                <div id="u008" class="ax_default ">
                                    <img  class="img " src="images/u2172.png">
                                </div>
                                <div id="u009" class="ax_default _三级标题 wordsize">
                                    <p>
                                        <span>2</span>
                                    </p>
                                </div>
                            </div>
                            <div id="u010" class="ax_default label orderstyle">
                                <div id="u011" class="text ">
                                    <p><span>待接单</span></p>
                                </div>
                            </div>
                            <div id="u012" class="ax_default pic1">
                                <img  class="img " src="images/u2170.png">
                            </div>
                        </div>
        
                        <div class="base">
                            <div id="u013" class="ax_default" data-left="381" data-top="207" data-width="30" data-height="30">
                                <div id="u014" class="ax_default ">
                                    <img  class="img " src="images/u2167.png">
                                </div>
                                <div id="u015" class="ax_default _三级标题 wordsize">
                                    <p>
                                        <span>3</span>
                                    </p>
                                </div>
                            </div>
                            <div id="u016" class="ax_default label orderstyle">
                                <div id="u017" class="text ">
                                    <p><span>已接单</span></p>
                                </div>
                            </div>
                            <div id="u018" class="ax_default pic1">
                                <img  class="img " src="images/u2170.png">
                            </div>
                        </div>
            
                        <div class="base">
                            <div id="u019" class="ax_default" data-left="381" data-top="207" data-width="30" data-height="30">
                                <div id="u020" class="ax_default ">
                                    <img  class="img " src="images/u2167.png">
                                </div>
                                <div id="u021" class="ax_default _三级标题 wordsize">
                                    <p>
                                        <span>4</span>
                                    </p>
                                </div>
                            </div>
                            <div id="u022" class="ax_default label orderstyle">
                                <div id="u023" class="text ">
                                    <p><span>待支付</span></p>
                                </div>
                            </div>
                            <div id="u024" class="ax_default pic1">
                                <img  class="img " src="images/u2170.png">
                            </div>
                        </div>
            
                        <div class="base">
                            <div id="u025" class="ax_default" data-left="381" data-top="207" data-width="30" data-height="30">
                                <div id="u026" class="ax_default ">
                                    <img  class="img " src="images/u2167.png">
                                </div>
                                <div id="u027" class="ax_default _三级标题 wordsize">
                                    <p>
                                        <span>5</span>
                                    </p>
                                </div>
                            </div>
                            <div id="u028" class="ax_default label orderstyle">
                                <div id="u029" class="text ">
                                    <p><span>已完工</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                ';
        }
        if (($info['child_status'] == 11)||($info['child_status'] == 12)){
            echo '
                    <div class="orderstream">
                        <div class="base">
                            <div id="u001" class="ax_default" data-left="381" data-top="207" data-width="30" data-height="30">
                                <div id="u002" class="ax_default ">
                                    <img  class="img " src="images/u2172.png">
                                </div>
                                <div id="u003" class="ax_default _三级标题 wordsize">
                                    <p>
                                        <span>1</span>
                                    </p>
                                </div>
                            </div>
                            <div id="u004" class="ax_default label orderstyle">
                                <div id="u005" class="text ">
                                    <p><span>待派单</span></p>
                                </div>
                            </div>
                            <div id="u006" class="ax_default pic1">
                                <img  class="img " src="images/u2170.png">
                            </div>
                        </div>
            
                        <div class="base">
                            <div id="u007" class="ax_default" data-left="381" data-top="207" data-width="30" data-height="30">
                                <div id="u008" class="ax_default ">
                                    <img  class="img " src="images/u2167.png">
                                </div>
                                <div id="u009" class="ax_default _三级标题 wordsize">
                                    <p>
                                        <span>2</span>
                                    </p>
                                </div>
                            </div>
                            <div id="u010" class="ax_default label orderstyle">
                                <div id="u011" class="text ">
                                    <p><span>待接单</span></p>
                                </div>
                            </div>
                            <div id="u012" class="ax_default pic1">
                                <img  class="img " src="images/u2170.png">
                            </div>
                        </div>
        
                        <div class="base">
                            <div id="u013" class="ax_default" data-left="381" data-top="207" data-width="30" data-height="30">
                                <div id="u014" class="ax_default ">
                                    <img  class="img " src="images/u2167.png">
                                </div>
                                <div id="u015" class="ax_default _三级标题 wordsize">
                                    <p>
                                        <span>3</span>
                                    </p>
                                </div>
                            </div>
                            <div id="u016" class="ax_default label orderstyle">
                                <div id="u017" class="text ">
                                    <p><span>已接单</span></p>
                                </div>
                            </div>
                            <div id="u018" class="ax_default pic1">
                                <img  class="img " src="images/u2170.png">
                            </div>
                        </div>
            
                        <div class="base">
                            <div id="u019" class="ax_default" data-left="381" data-top="207" data-width="30" data-height="30">
                                <div id="u020" class="ax_default ">
                                    <img  class="img " src="images/u2167.png">
                                </div>
                                <div id="u021" class="ax_default _三级标题 wordsize">
                                    <p>
                                        <span>4</span>
                                    </p>
                                </div>
                            </div>
                            <div id="u022" class="ax_default label orderstyle">
                                <div id="u023" class="text ">
                                    <p><span>待支付</span></p>
                                </div>
                            </div>
                            <div id="u024" class="ax_default pic1">
                                <img  class="img " src="images/u2170.png">
                            </div>
                        </div>
            
                        <div class="base">
                            <div id="u025" class="ax_default" data-left="381" data-top="207" data-width="30" data-height="30">
                                <div id="u026" class="ax_default ">
                                    <img  class="img " src="images/u2167.png">
                                </div>
                                <div id="u027" class="ax_default _三级标题 wordsize">
                                    <p>
                                        <span>5</span>
                                    </p>
                                </div>
                            </div>
                            <div id="u028" class="ax_default label orderstyle">
                                <div id="u029" class="text ">
                                    <p><span>已完工</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                ';
        }

        ?>

        <br><br><br><br>
        <div class="base1">
            <div id="u031" >
                <img  class="img " src="images/u2032.png">
            </div>
            <div id="u030">
                <p><scan>历史记录</scan></p>
            </div>
<!--            多合一页面，通过子状态选择返回链接-->
            <div class="fanhui">
            <?php
                    if($info['child_status']  == 31){
                        echo '<a href="weixin_repair_cancel.php?id='.$id.'">返回</a>';
                    }
                    else if(($info['child_status']  == 23)||($info['child_status']  == 32)||($info['child_status']  == 33)){
                        echo '<a href="weixin_repair_notchange.php?id='.$id.'">返回</a>';
                    }
                    else if (($info['status'] ==1)||($info['child_status'] == 21)||($info['child_status'] == 22)){
                        echo '<a href="weixin_repair_untreated_detail.php?id='.$id.'&&status='.$info['status'].'">返回</a>';
                    }
             ?>

            </div>
        </div>
        <hr style="width: 850px"/>

        <table style="float: left;width: 100%;text-align: center" border="1" cellspacing="1" cellpadding="0">
            <?php
            $params['code'] = $info['bar_code'];
            $params['status'] = 3;
            $rows = repair_order::getListByCode($params);
            $product_info = Product_info::getInfoBycode($info['bar_code']);

            if(!empty($rows)){
                echo'<tr>
            <td width="7%" >服务次数</td>
            <td width="10%">客户联系电话</td>
            <td width="10%">服务时间</td>
            <td width="8%">服务类型</td>
            <td width="20%">维修结果</td>
            <td width="20%">配件信息</td>
            <td width="10%">支付信息</td>
            <td width="8%">服务人员</td>
            <td width="10%">解决途径</td>

                </tr>';
                $i = 1;
                foreach($rows as $row){
                    $name="";
                    $phone="";
                    $person=Repair_person::getInfoById($row['person']);
                    if($person){
                        $name=$person['name'];
                        $phone=$person['phone'];
                    }

                    $service_type = "";
                    if(isset($row['service_type'])){
                        $service_info = Service_type::getInfoById($row['service_type']);
                        if(isset($service_info['name'])){
                            $service_type = $service_info['name'];
                        }
                    }
                    $final_status="";
                    if ($product_info['period'] == "过保"){
                        if ($row['child_status'] ==23){
                            $final_status = "待支付<br>￥".$row['sum_money'];
                        }
                        if (($row['child_status'] ==33)||($row['child_status'] ==32)){
                            $final_status = "已支付<br>￥".$row['sum_money'];
                        }
                        if ($row['child_status'] == 31){
                            $final_status = "已取消";
                        }
                    }
                    else{
                        $final_status = "在保";
                    }

                    $solution  = "";
                    if ($row['master_solution'] == 1){
                        $solution = "上门服务";
                    }
                    if ($row['master_solution'] == 2){
                        $solution = "电话服务";
                    }

                    $result = "";
                    if (!empty($row['result'])){
                        $result = $row['result'];
                    }
                    else{
                        $result = "暂无";
                    }
                    echo '<tr>
            <td style="text-align: center">'.$i.'</td>
            <td >'.$row['phone'].'</td>
            <td >'.date("Y-m-d",$row['finish_time']).'</td>
            <td >'.$service_type.'</td>
            <td style="text-align: center">'.$result.'</td>
            <td>';
                $repair_part = Boiler_repair_parts::getrepair_part($row['id']);
                if (!empty($repair_part)){
                    foreach ($repair_part as $part){
                        echo $part['Info_part']."，".$part['part_num']."个，￥".$part['part_money']."<br>";
                    }
                }
                else{
                    echo "暂无";
                }

            echo '</td>
            <td style="text-align: center">'.$final_status.'</td>
            <td >'.$name."<br>".$phone.'</td>
            <td >'.$solution.'</td>
            </tr>';
                            $i++;
                        }
                    }else{
                        echo "暂无";
                    }
                    ?>
        </table>


        <script type="text/javascript"></script>

    </div>
</div>
<div class="clear"></div>
</div>
<?php include('footer.inc.php');?>
</body>
</html>