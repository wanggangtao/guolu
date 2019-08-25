<?php
/**
 * Created by PhpStorm.
 * User: ww
 * Date: 2019/3/15
 * Time: 15:12
 * @author  zhh_fu
 */
require_once('admin_init.php');
require_once('admincheck.php');

$status = 1;
if(isset($_GET['id'])){
    $id = safeCheck($_GET['id'],1);
}else{
    exit();
}
$info = repair_order::getInfoById($id);
$all_usr = repair_person::getList();

$FLAG_TOPNAV	= "weixincontent";
$FLAG_LEFTMENU  = "weixin_repair_untreated";
if (($info['child_status']==11)||($info['child_status']==12)){
    $FLAG_LEFTMENU  = 'weixin_repair_untreated';
}
else if($info['status'] == 2){
    $FLAG_LEFTMENU  = 'weixin_repair_treating';
}
/*else if($status == 3){
    $FLAG_LEFTMENU = "weixin_repair_treated";
}*/



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="author" content="芝麻开发 http://www.zhimawork.com" />
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
    <script type="text/javascript">
        window.onload = function () {
            var status = <?php echo $status?>;
            if(status == 2){
//            $("#status1").attr("checked"," ");
                $("#status2").attr("checked","checked");
                $("#status2").change();
                $("#repair_check").change();
            }
        }
    </script>
</head>
<style>
    .table_all_list {
        table-layout: fixed;
        margin: 0px !important;

    }

    .table_all_list tbody td {
        /*text-align: left !important;*/
        border: 0px !important;
    }
    .table_all_list tbody th {
        /*text-align: left !important;*/
        border: 0px !important;
    }

    .table_all_list tbody td input {
        margin: 0px !important;
    }
</style>
<body>
<div id="header">
    <?php include('top.inc.php');?>
    <?php include('nav.inc.php');?>
</div>
<div id="container">
    <?php include('weixin_menu_inc.php');?>
    <div id="maincontent">
        <div id="position">当前位置：<a href="weixin_employees_info.php">公众号管理</a> &gt; 预约管理</div>
<!--        根据不同子状态来判断前端页面-->
        <?php
            if ($FLAG_LEFTMENU == 'weixin_repair_untreated'){
                echo '<div class="orderstream">
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
        </div>';
            }
            if ($info['child_status'] == 21){
                echo '<div class="orderstream">
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
        </div>';
            }
            if ($info['child_status'] == 22){
            echo '<div class="orderstream">
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
        </div>';
        }
//            if ($info['child_status'] == 23){
//            echo '<div class="orderstream">
//            <div class="base">
//                <div id="u001" class="ax_default" data-left="381" data-top="207" data-width="30" data-height="30">
//                    <div id="u002" class="ax_default ">
//                        <img  class="img " src="images/u2172.png">
//                    </div>
//                    <div id="u003" class="ax_default _三级标题 wordsize">
//                        <p>
//                            <span>1</span>
//                        </p>
//                    </div>
//                </div>
//                <div id="u004" class="ax_default label orderstyle">
//                    <div id="u005" class="text ">
//                        <p><span>待派单</span></p>
//                    </div>
//                </div>
//                <div id="u006" class="ax_default pic1">
//                    <img  class="img " src="images/u2170.png">
//                </div>
//            </div>
//
//            <div class="base">
//                <div id="u007" class="ax_default" data-left="381" data-top="207" data-width="30" data-height="30">
//                    <div id="u008" class="ax_default ">
//                        <img  class="img " src="images/u2172.png">
//                    </div>
//                    <div id="u009" class="ax_default _三级标题 wordsize">
//                        <p>
//                            <span>2</span>
//                        </p>
//                    </div>
//                </div>
//                <div id="u010" class="ax_default label orderstyle">
//                    <div id="u011" class="text ">
//                        <p><span>待接单</span></p>
//                    </div>
//                </div>
//                <div id="u012" class="ax_default pic1">
//                    <img  class="img " src="images/u2170.png">
//                </div>
//            </div>
//
//            <div class="base">
//                <div id="u013" class="ax_default" data-left="381" data-top="207" data-width="30" data-height="30">
//                    <div id="u014" class="ax_default ">
//                        <img  class="img " src="images/u2172.png">
//                    </div>
//                    <div id="u015" class="ax_default _三级标题 wordsize">
//                        <p>
//                            <span>3</span>
//                        </p>
//                    </div>
//                </div>
//                <div id="u016" class="ax_default label orderstyle">
//                    <div id="u017" class="text ">
//                        <p><span>已接单</span></p>
//                    </div>
//                </div>
//                <div id="u018" class="ax_default pic1">
//                    <img  class="img " src="images/u2170.png">
//                </div>
//            </div>
//
//            <div class="base">
//                <div id="u019" class="ax_default" data-left="381" data-top="207" data-width="30" data-height="30">
//                    <div id="u020" class="ax_default ">
//                        <img  class="img " src="images/u2172.png">
//                    </div>
//                    <div id="u021" class="ax_default _三级标题 wordsize">
//                        <p>
//                            <span>4</span>
//                        </p>
//                    </div>
//                </div>
//                <div id="u022" class="ax_default label orderstyle">
//                    <div id="u023" class="text ">
//                        <p><span>待支付</span></p>
//                    </div>
//                </div>
//                <div id="u024" class="ax_default pic1">
//                    <img  class="img " src="images/u2170.png">
//                </div>
//            </div>
//
//            <div class="base">
//                <div id="u025" class="ax_default" data-left="381" data-top="207" data-width="30" data-height="30">
//                    <div id="u026" class="ax_default ">
//                        <img  class="img " src="images/u2167.png">
//                    </div>
//                    <div id="u027" class="ax_default _三级标题 wordsize">
//                        <p>
//                            <span>5</span>
//                        </p>
//                    </div>
//                </div>
//                <div id="u028" class="ax_default label orderstyle">
//                    <div id="u029" class="text ">
//                        <p><span>已完工</span></p>
//                    </div>
//                </div>
//            </div>
//        </div>';
//        }
        ?>
        <br><br><br><br>
        <div class="base1">
            <div id="u031" >
                <img  class="img " src="images/u2032.png">
            </div>
            <div id="u030">
                <p><scan>保修详情</scan></p>
            </div>
            <div class="fanhui">
                <?php 
                    if ($info['status'] == 1) {
                        echo '<a href="weixin_repair_untreated.php">返回</a>';
                    }
                    if ($info['status'] == 2) {
                        echo '<a href="weixin_repair_treating.php">返回</a>';
                    }
                ?>            
          </div>
        </div>
        <hr style="width: 850px"/>
        <div class="tablelist">
            <table style="border: 0px;" class="table_all_list">
                <tbody>
                <tr>
                    <th width= "5% "  ></th>
                    <td width= "30% " ></td>
                    <td width= "30% " ></td>
                    <td width= "40% "></td>
                </tr>

                <tr height="50px">
                    <th ></th>
                    <td ><label style="font-weight:bolder">联系人&#12288;：</label>
                        <?php if(isset($info['register_person'])){
                            echo $info['register_person'];
                        } ?></td>
                    <td ><label style="font-weight:bolder">服务联系电话：</label>
                        &nbsp;<input  type="number" id = "phone" style="width:110px" value="<?php if(isset($info['phone'])){echo $info['phone'];} ?>" ></td>
                    <td ><label style="font-weight:bolder">注册电话：</label><?php if(isset($info['register_phone'])){
                            echo $info['register_phone'];
                        } ?></td>
                </tr>
<!--                    <td ><label style="font-weight:bolder">优惠券&#12288;：</label>--><?php
//
//
//                        $coupon_name = "";
//                        if(isset($info['coupon_id'])){
//                            if($info['coupon_id'] == -1){
//                                $coupon_name = "&nbsp;暂无";
//                            }else{
//                                $coupon_info = Weixin_coupon::getInfoById($info['coupon_id']);
//                                if(isset($coupon_info['name'])){
//                                    $coupon_name = $coupon_info['name'];
//                                }
//                            }
//                        }
//                        echo $coupon_name;
//
//                        ?><!--</td>-->
                <tr height="50px">
                    <th ></th>
                    <td><label style="font-weight:bolder">提交时间：</label><?php if(isset($info['addtime'])){
                            echo date("Y-m-d H:i:s", $info['addtime']);
                        } ?></td>
                    <td><label style="font-weight:bolder">品牌型号：</label><?php if(isset($info['brand'])){
                            echo $info['brand'];
                        }
                        if(isset($info['model']) && !empty($info['model'])){
                            echo "—".$info['model'];
                        }
                        ?></td>
                </tr>

                <tr height="50px">
                    <th ></th>
                    <td  colspan="3" ><label style="font-weight:bolder">联系地址：</label><?php if(isset($info['address_all'])) echo $info['address_all'];?></td>
                </tr>

                <tr height="50px">
                    <th ></th>
                    <td  colspan="3"><label style="font-weight:bolder">服务类型：</label><?php
                        $service_type = "";
                        if(isset($info['service_type'])){
                            $service_info = Service_type::getInfoById($info['service_type']);
                            if(isset($service_info['name'])){
                                $service_type = $service_info['name'];
//                                if($service_type === "安全检查"){
//                                    $is_safe = 1;
//                                }
                            }
                        }
                        echo $service_type;
                        ?></td>
                </tr>

                <tr height="50px">
                    <th></th>
                    <td colspan="3"><label style="font-weight:bolder">故障描述：</label>
                        <?php
                            if(!empty($info['failure_cause'])) {
                                echo $info['failure_cause'];
                            }
                            else{
                                echo "暂无描述";
                            }
                        ?>
                    </td>
                </tr>

                <tr height="50px">
                    <th ></th>
                    <td  colspan="3"><label style="font-weight:bolder">图片详情：</label>
                        <?php
                        $has_img = 0;
                        if(!empty($info['picture_url'])){

                            $has_img = 1;
                            $url_array = explode(",",$info['picture_url']);
                            $url_array = array_filter($url_array);
//                            print_r($url_array);
                        }
                        if($has_img) {
                        $pos = strripos($url_array[0],"/");
                        $now_path =  $HTTP_PATH .substr($url_array[0],0,$pos + 1)."s_".substr($url_array[0],$pos + 1);
                        ?>
                        <table class="table_all_list">
                            <tr>
                                <td rowspan="4" align="center">
                                    <img src="<?php echo $now_path; ?>" id="big_img" alt="">
                                </td>

                                <td>
                                </td>
                            </tr>
                            <?php
                            foreach ($url_array as $key => $item) {
                                if($key > 2) break;
                                $pos_item = strripos($item,"/");
                                $now_item_path =  substr($item,0,$pos_item + 1)."s_".substr($item,$pos_item + 1);
                                ?>

                                <tr>
                                    <td>
                                        <a onclick="setImg('<?php echo $now_item_path; ?>')"> <img
                                                    src="<?php echo $HTTP_PATH . $item ?>" width="100px"
                                                    height="100px" alt=""></a>
                                    </td>
                                </tr>

                                <?php
                            }?>

                            <?php
                            echo "</table>";
                            }
                            else{
                                echo "暂无照片";
                            }
                            ?>
                    </td>
                </tr>
                <!--此处除非去获取是否有openid，不然无法将公众号提交的订单和后台提交的订单区分开
                    因为两种途径传过来的信息是完全一样的
                    -->
                <?php
                    if ($info['child_status']==11){
                        echo '<tr>
                                <th></th>
                                <td  colspan="3"><label style="font-weight:bolder;float: left;">备注信息：</label><textarea style="width: 60%;" id = "remarks" rows="8" cols="80"></textarea>
                                <br>&#12288;&#12288;&#12288;&#12288;&#12288;(注：最多输入200个字。)
                                </td>
                            </tr>
                        ';
                    }
                    else{
                        echo '<tr>
                                <th></th>
                                <td  colspan="3"><label style="font-weight:bolder;float: left;">备注信息：</label>';
                                $remarks = "";
                                if(!empty($info['remarks'])) {
                                    $remarks = $info['remarks'];
                                }
                                else{
                                    $remarks = "暂无";
                                }
                                echo $remarks;
                        echo '
                                <br>
                                </td>
                            </tr>
                        '; 
                    }
                        
                ?>
                </tbody>
            </table>

            <div class="base1">
                <div id="u031" >
                    <img  class="img " src="images/u2032.png">
                </div>
                <div id="u030">
                    <p><scan>维修详情</scan></p>
                </div>
                <div class="history">
                    <input type="button" class="btn-handle" id="history_back" value="历史记录">
                </div>
            </div>
            <hr style="width: 850px"/>
            <table  style="border: 1px;" class="table_all_list">
                <tbody>
                <tr>
                    <th width= "5% "  ></th>
                    <td width= "10% " ></td>
                    <td width= "30% " ></td>
                    <td width= "60% "></td>
                </tr>
                <tr height="50px">
                    <?php
                        if ($info['status']==1){
                            echo '
                                <th></th>
                                <td >
                                    <label style="font-weight:bolder">维修人员 ：</label>
                                </td>
                                <td>服务人员：
                                <select  id="service_person" class="select-handle">
                                <option value=""></option>';
                                if (!empty($all_usr)){
                                    foreach ($all_usr as $row){
                                        echo '<option value="'.$row['name'].'">'.$row['name'].'</option>';
                                    }
                                }
                                echo '
                                </select>
                                </td>
                                <td>联系电话:  <input type="text" name="service_phone" id="service_phone"/></td>
                                ';
                        }
                        else{
                            if (isset($info['person'])){
                                $service_person = Repair_person::getInfoById($info['person']);
                                $name="";
                                $phone="";
                                //$person=Repair_person::getInfoById($row['person']);
                                if($service_person){
                                    $name=$service_person['name'];
                                    $phone=$service_person['phone'];
                                }
                            }
                            echo '
                                <th></th>
                                <td >
                                    <label style="font-weight:bolder">维修人员 ：</label>
                                </td>
                                <td>服务人员：'.$name.'</td>
                                <td>联系电话: '.$phone.'</td>';
                        }
                    ?>
                </tr>
                <tr height="50px">
                    <th></th>
                    <td>
                        <label style="font-weight:bolder">解决途径 ：</label>
                    </td>
                    <td colspan="2">
                        <?php
                            if (($info['child_status'] == 11)||($info['child_status'] == 12)){
                                echo '<div>
                            <label><input type="radio" name="service_type" value="电话服务">电话服务</label>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <label><input type="radio" name="service_type" value="上门服务" checked>上门服务</label>
                            </div>';
                            }
                            else{
                                if ($info['solutions'] == 1){
                                    echo "上门服务";
                                }
                                else if ($info['solutions'] == 2){
                                    echo "电话服务";
                                }
                                else {
                                    echo "暂无";
                                }
                            }
                        ?>
                    </td>
                </tr>
                <tr height="50px">
                    <th></th>
                    <td>
                        <label style="font-weight:bolder">订单跟踪 ：</label>
                    </td>
                    <td colspan="2">
                        <?php
                        $rs = Boiler_order_process::getInfoById($info['id']);
                        $order_process = "";
                        $order_status = "";
                        $service_person_phone = "";
                        if (!empty($rs)){
                            echo '<table>
                                     <tbody>
                                  ';
                            foreach ($rs as $row){
                                if ($row['operation']!=-1){
                                    if ($row['operation'] == 1){
                                        $order_process = "客户下单预约";
                                    }
                                    if ($row['operation'] == 2){
                                        $order_process = "管理员已派单";
                                    }
                                    if ($row['operation'] == 3){
                                        $order_process = "维修师傅已接单";
                                    }
                                    if ($row['operation'] == 4){
                                        $order_process = "维修师傅维修完成";
                                    }
                                    if ($row['operation'] == 5){
                                        $order_process = "客户支付完成";
                                    }
                                    if ($row['operation'] == 6){
                                        $order_process = "管理员进行审核";
                                    }
                                    if ($row['operation'] == 7){
                                        $order_process = "客户取消订单";
                                    }
                                    if (($row['operation'] == 8)&&($row['order_status']==12)){
                                        $order_process = "维修师傅申请重派.重派原因：".$row['person_reason'];
                                    }
                                    if ($row['operation'] == 9){
                                        $order_process = "订单改派中";
                                    }
                                    if ($row['operation'] == 10) {
                                        $order_process = "客服通过电话解决问题";
                                    }
                                }
                                else{
                                    $order_status ="";
                                }

                                if ($row['order_status']!=-1){
                                    if ($row['order_status'] == 11){
                                        $order_status = "订单状态：正常待派单";
                                    }
                                    if ($row['order_status'] == 12){
                                        $order_status = "订单状态：重派单";
                                    }
                                    if ($row['order_status'] == 21){
                                        $order_status = "订单状态：待接单";
                                    }
                                    if ($row['order_status'] == 22){
                                        $order_status = "订单状态：已接单";
                                    }
                                    if ($row['order_status'] == 23){
                                        $order_status = "订单状态：待支付";
                                    }
                                    if ($row['order_status'] == 31){
                                        $order_status = "订单状态：已取消";
                                    }
                                    if ($row['order_status'] == 32){
                                        $order_status = "订单状态：待审核";
                                    }
                                    if ($row['order_status'] == 33){
                                        $order_status = "订单状态：已审核";
                                    }
                                }
                                else{
                                    $order_status = "";
                                }
                                if ((!empty($row['service_person']))&&(!empty($row['person_phone']))&&(($row['order_status']==21)||($row['order_status']==22))){
                                    $service_person_phone = "服务人员: ".$row['service_person']."&nbsp;&nbsp;&nbsp;&nbsp;联系电话：".$row['person_phone'];
                                }
                                $format_time = date("Y-m-d H:i:s",$row['addtime']);
                                echo '
                                    <tr>
                                    <th></th>
                                    <td width="20%">'.$format_time.'</td>
                                    <td width="20%">'.$order_process.'</td>
                                    <td width="20%">'.$order_status.'</td>
                                    <td width="40%">'.$service_person_phone.'</td>
                                    </tr>
                                ';
                                    //date("Y-m-d H:i:s",$row['addtime'])." ".$order_process." ".$order_status." ".$service_person_phone."<br>";
                            }
                            echo '</tbody>
                                     </table>
                                  ';
                        }
//                        ?>
                    </td>
                </tr>
                </tbody>
            </table>


                        <script type="text/javascript">

                            $(function(){

                                $('#btn_sumit').click(function() {

                                    layer.confirm('确认提交吗？', {
                                        btn: ['确认','取消']
                                    }, function(){
                                        var status =  $("input[name='status']:checked").val();
                                        var repair_status =  <?php echo $status?>;
                                        var coupon_id  = <?php echo $info['coupon_id']?>;

//                                    alert();
                                        var remarks =   $.trim($("#remarks").val());
                                        var repair_check = -1 ;
                                        var linkPone ="";
                                        var content = "";
                                        var result = "";
                                        var finish_time = 0;
                                        var solve_type = "";
                                        var uid = <?php echo $info['uid']?>;
//
                                        var phone = $("#phone").val();

                                        var id = <?php echo $id?>;
                                        if(status == 2){
                                            repair_check =   $("#repair_check option:selected").val() ;
                                            linkPone =   $("#linkPone").val();
                                        }
                                        if(status == 3){
                                            solve_type = $("input[name='solutions']:checked").val();

                                            repair_check =   $("#repair_check option:selected").val() ;
                                            linkPone =   $("#linkPone").val().trim() ;

                                            content = $.trim($("#content").val()).trim() ;
                                            result = $.trim($("#result").val()).trim() ;
                                            finish_time = $("#finish_time").val();
                                            if(finish_time == ""){
                                                layer.alert("服务完成时间不能为空",{icon:5});
                                                return;
                                            }

                                            if(repair_check == -1){
                                                layer.alert("服务人员不能为空",{icon:5});
                                                return;
                                            }
                                            if(linkPone == ""){
                                                layer.alert("联系电话不能为空",{icon:5});
                                                return;
                                            }
                                            if(result  == ""){
                                                layer.alert("服务结果不能为空",{icon:5});
                                                return;
                                            }
                                        }

                                        var type ="<?php echo $status;?>";
                                        $.ajax({
                                            type: 'post',
                                            url: 'weixin_repair_do.php?act=edit',
                                            data: {
                                                id:id,
                                                linkPone: linkPone,
                                                repair_check:repair_check,
                                                remarks:remarks,
                                                status:status,
                                                content:content,
                                                result:result,
                                                finish_time :finish_time,
                                                phone:phone,
                                                repair_status :repair_status,
                                                solve_type:solve_type,
                                                coupon_id:coupon_id,
                                                uid :uid
                                            },
                                            dataType: 'json',
                                            success: function (data) {
                                                var code = data.code;
                                                var msg = data.msg;
                                                switch (code){
                                                    case 1:
                                                        layer.alert(msg, {icon: 6}, function (index) {

                                                            if(type == 1){
                                                                parent.location.href = "weixin_repair_untreated.php";

                                                            }else if(type == 2){
                                                                parent.location.href = "weixin_repair_treating.php";

                                                            }else{
                                                                parent.location.href="javascript:history.go(-1)";
                                                            }

                                                        });

                                                        break;
                                                    default:
                                                        layer.alert("请求失败");
                                                }
                                            },
                                            error: function () {
                                                layer.alert("请求失败");
                                            }
                                        });

                                    });



                                });
                                $('#status1').change(function () {
                                    var content ="";
                                    $(".is_show").html(content);

                                });
                                $('#status2').change(function () {
                                    var all_usr = <?php echo json_encode($all_usr)?>;

                                    var name_id = "<?php if(isset($info['person'])) echo $info['person'];?>";

                                    var linkPone = "<?php if(isset($info['linkphone'])) echo $info['linkphone'];?>";
                                    var option = "<option value='-1'>---</option>";

                                    for(var index_usr = 0 ;index_usr < all_usr.length ; index_usr ++){
                                        var checked = "";
//                                        alert(all_usr[index_usr].id);
                                        if(name_id == all_usr[index_usr].id) checked = "selected";
                                        option += "<option value='"+all_usr[index_usr].id+"' " +
                                            checked +
                                            ">"+all_usr[index_usr].name+"</option>";
                                    }
                                    var content = "<tr>" +
                                        "<td></td><td><label style='font-weight:bolder'>服务人员：</label>" +
                                        "<select name= 'repair_check' id = 'repair_check'>" +
                                        option +
                                        "</select></td>" +
                                        "<td colspan='2'><label style='font-weight:bolder'>联系电话：</label>&#12288;" +
                                        "<input id = 'linkPone' name = 'linkPone' value='" +
                                        linkPone +
                                        "'/></td>" +
                                        "</tr>";

                                    $(".is_show").html(content);

                                });
                                $('#status3').change(function () {
                                    var $is_safe = "<?php echo $is_safe?>";
                                    var status = "<?php echo $status?>";
                                    var check1 = "";
                                    var check2 = "";

                                    if(status == 1){
                                        if($is_safe == 1){
                                            check2 = "checked";

                                        }else{
                                            check1 = "checked";
                                        }

                                    }else{
                                        check2 = "checked";

                                    }


                                    var nowTime = "<?php echo date("Y-m-d H:i:s") ?>";
                                    var all_usr = <?php echo json_encode($all_usr)?>;
                                    var name_id = "<?php if(isset($info['person'])) echo $info['person'];?>";
                                    var linkPone = "<?php if(isset($info['linkphone'])) echo $info['linkphone'];?>";
                                    var option = "<option value='-1'>---</option>";


                                    for(var index_usr = 0 ;index_usr < all_usr.length ; index_usr ++){
                                        var checked = "";
                                        if(name_id == all_usr[index_usr].id) checked = "selected";
                                        option += "<option value='"+all_usr[index_usr].id+"' " +
                                            checked +
                                            ">"+all_usr[index_usr].name+"</option>";
                                    }
                                    var content = "<tr>" +
                                        "<td></td><td ><label style='font-weight:bolder'>服务人员：</label>" +
                                        "<select name= 'repair_check' id = 'repair_check'>" +
                                        option +
                                        "</select></td>" +
                                        "<td colspan='2'><label style='font-weight:bolder'>联系电话：</label>&#12288;" +
                                        "<input id = 'linkPone' name = 'linkPone' value='" +
                                        linkPone +
                                        "'/>" +
                                        "</td></tr>" +
                                        "<tr><th style='text-align: right'><label style='font-weight:bolder'>服务完成情况：</label></th></tr>" +
                                        "" +
                                        "<tr> <td></td><td colspan='3'><label style='font-weight:bolder'>解决途径：</label>" +
                                        "<input name='solutions' id ='solutions1' type='radio' value='1'" +
                                        " "+ check2 +
                                        "/> 上门服务" +
                                        " <input name='solutions' id ='solutions2' type='radio'  value='2'" +
                                        check1 +
                                        "/>电话服务" +
                                        "</td></tr>" +
                                        "<tr> <td></td><td colspan='3'><label style='font-weight:bolder;float: left;'>服务结果：</label>" +
                                        "<textarea style='width: 85%' rows='5' cols='120' id = 'result' name = 'result'></textarea><br>" +
                                        "&#12288;&#12288;&#12288;&#12288;&#12288;（注：最多输入200个字）" +
                                        "</td></tr>"+
                                        "<tr> <td></td><td colspan='3'><label style='font-weight:bolder'>服务时间：</label> " +
                                        "<input id = 'finish_time' name = 'finish_time'" +
                                        "value='"+nowTime+"'/>" +
                                        "</td></tr>";

                                    $(".is_show").html(content);

                                });

                                $('#order_treating').click(function() {
                                    layer.confirm('确认派单？', {
                                        btn: ['确认','取消']
                                    }, function(){
                                        var id = <?php echo $info['id'];?>;
                                        
                                        var remarks = $('#remarks').val();
                                        //alert(remarks);
                                        var service_phone = $('#service_phone').val();
                                        
                                        var service_type = $("input[name='service_type']:checked").val();

                                        var service_person=$("#service_person").find("option:selected").text();
                                        
                                        if(service_type == ""){
                                            layer.alert("服务类型未选择",{icon:5});
                                            return;
                                        }
                                        if(service_person == ""){
                                            layer.alert("服务人员未选择",{icon:5});
                                            return;
                                        }

                                        $.ajax({
                                            type: 'post',
                                            url: 'weixin_repair_do.php?act=reedit',
                                            data: {
                                                id              :  id,
                                                remarks         :  remarks,
                                                service_phone   :  service_phone,
                                                service_type    :  service_type,
                                                service_person  :  service_person
                                            },
                                            dataType: 'json',
                                            success: function (data) {
                                                var code = data.code;
                                                var msg = data.msg;
                                                switch (code){
                                                    case 1:
                                                        layer.alert(msg, {icon: 6}, function (index) {
                                                            location.href = "weixin_repair_untreated.php";
                                                        });
                                                        break;
                                                    default:
                                                        layer.alert("请求失败");
                                                }
                                            }
                                        });

                                    });
                                });

                                $('#order_retreat').click(function(){
                                    var id=<?php echo $info['id']?>;
                            
                                    layer.open({
                                        type: 2,
                                        title: '请选择维修师傅',
                                        shadeClose: true,
                                        shade: 0.3,
                                        area: ['500px', '300px'],
                                        content: 'weixin_repair_retreat.php?id='+id
                                    });
                                });

                                $('#service_person').change(function(){
                                    var service_person1 = $("#service_person option:selected").val();
                                    if(service_person1 != ""){
                                        $.ajax({
                                            type:'post',
                                            url: 'weixin_repair_do.php?act=person_info',
                                            data:{
                                                service_person1 :　service_person1
                                            },
                                            dataType:'json',
                                            success:function (data) {

                                                var code = data.code;
                                                var msg = data.msg;

                                                console.log(msg['repair_phone']);
                                                switch (code) {
                                                    case 1:
                                                        if (msg != "") {
                                                            $("#service_phone").val(msg['0'].repair_phone);
                                                        }
                                                        break;
                                                    default:
                                                        layer.alert(msg, {icon: 5});
                                                }
                                            }
                                        });
                                    }
                                });

                                $('#order_cancel').click(function() {
                                    layer.confirm('确认取消吗？', {
                                        btn: ['确认','取消']
                                    }, function(){
                                        var id = <?php echo $info['id'];?>;
                                        $.ajax({
                                            type: 'post',
                                            url: 'weixin_repair_do.php?act=cancel',
                                            data: {
                                                id:id
                                            },
                                            dataType: 'json',
                                            success: function (data) {
                                                var code = data.code;
                                                var msg = data.msg;
                                                switch (code){
                                                    case 1:
                                                        layer.alert(msg, {icon: 6}, function (index) {
                                                                parent.location.href = "weixin_repair_cancel.php?id="+id;
                                                        });
                                                        break;
                                                    default:
                                                        layer.alert("请求失败");
                                                }
                                            },
                                            error: function () {
                                                layer.alert("请求失败");
                                            }
                                        });
                                    });

                                });
                                $('#history_back').click(function(){
                                    var id = <?php echo $info['id']?>;

                                    //var FLAG_LEFTMENU= <?php //echo $FLAG_LEFTMENU;?>//;

                                    //alert(FLAG_LEFTMENU);
                                    location.href="weixin_repair_history.php?id="+id;
                                });

                            });


                            $(document).on("change","#repair_check",function(){
                                var repair_id =   $("#repair_check option:selected").val() ;

                                $.ajax({
                                    type: 'post',
                                    url: 'weixin_repair_do.php?act=select_repair',
                                    data: {
                                        repair_id: repair_id,
                                    },
                                    dataType: 'json',
                                    success: function (data) {
                                        var code = data.code;
                                        var msg = data.msg;
                                        switch (code){
                                            case 1:
                                                $('#linkPone').val(msg);
                                                break;
                                            default:
                                                layer.alert("请求失败");
                                        }
                                    },
                                    error: function () {
                                        layer.alert("请求失败");
                                    }
                                });
                            });
                            function setImg(path){
                                //alert(path);
                                var web_path = "<?php echo $HTTP_PATH?>";
                                var all_path = web_path + path;
                                $('#big_img').attr('src',all_path);
                            }
                        </script>
                        <script type="text/javascript" src="laydate/laydate.js"></script>
                        <script type="text/javascript">
                            $(document).on("click","#finish_time",function(){
                                laydate.skin("molv");//设置皮肤
                                var start = {
                                    elem: '#finish_time',
                                    format: 'YYYY-MM-DD hh:mm:ss',
                                    min: '2000-06-16 23:59:59', //设定最小日期为当前日期
                                    max: '2099-06-16 23:59:59', //最大日期
                                    istime: true,
                                    istoday: false,
                                };
                                laydate(start);
                            })
                        </script>
        </div>
<!--        多合一页面，通过子状态来判断出现哪个按钮-->
        <div style="text-align:center;">
            <?php               
                    if (($info['child_status']==11)||($info['child_status']==12)){
                      echo '
                        <input type="button" name="order_treating" class="btn-handle" id="order_treating" value="派单"/>
                    ';
                }
                if ($info['child_status']==21){
                    echo '
                        <input type="button" name="order_retreat" class="btn-handle" id="order_retreat" value="改派"/>
                    ';
                }
                ?>
            <input type="button" name="order_cancel" class="btn-handle btn-grey" id="order_cancel" value="取消订单"/>
        </div>
    </div>

    <div class="clear"></div>
</div>
<?php include('footer.inc.php');?>
</body>
</html>