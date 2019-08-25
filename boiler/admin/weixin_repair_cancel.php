<?php
/**
 * Created by PhpStorm.
 * User: ChrisLin3
 * Date: 2019/8/15
 * Time: 16:25
 */
require_once('admin_init.php');
require_once('admincheck.php');

$FLAG_TOPNAV	= "weixincontent";
$FLAG_LEFTMENU  = 'weixin_repair_treated';

/*$type = 1;
if(isset($_GET['type'])){
    $type = $_GET['type'];
}*/
if(isset($_GET['id'])){
    $id = safeCheck($_GET['id'],1);
}else{
    exit();
}
//if (isset($_GET['child_status'])){
//    $child_status = safeCheck($_GET['child_status'],1);
//}
//else{
//    exit();
//}

$info = repair_order::getInfoById($id);
$child_status = 31;

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
                    <img  class="img status" src="images/已取消_u3140.png">
                </div>
            </div>
        </div>

        <br><br><br><br>
        <div class="base1">
            <div id="u031" >
                <img  class="img " src="images/u2032.png">
            </div>
            <div id="u030">
                <p><scan>保修详情</scan></p>
            </div>
            <div class="fanhui">
                <a href="weixin_repair_treated.php">返回</a>
            </div>
        </div>
        <hr style="width: 850px"/>

        <div class="tablelist">
            <table  style="border: 1px;" class="table_all_list">
                <tbody>
                <tr>
                    <th width= "2% " ></th>
                    <td width= "30% " ></td>
                    <td width= "30% " ></td>
                    <td width= "40% " ></td>
                </tr>
                <tr height="50px">
                    <th ></th>
                    <td ><label style="font-weight:bolder">联系人&#12288;：</label>
                        <?php if(isset($info['register_person'])){
                            echo $info['register_person'];
                        } ?></td>
                    <td ><label style="font-weight:bolder">服务联系电话：</label>
                        &#12288;<input readonly type="number" id = "phone" style="width:110px" value="<?php if(isset($info['phone'])){echo $info['phone'];} ?>" ></td>
                    <td ><label style="font-weight:bolder">注册电话：</label>&#12288;<?php if(isset($info['register_phone'])){
                            echo $info['register_phone'];
                        } ?></td>
                </tr>
                <!--                    <td ><label style="font-weight:bolder">优惠券&#12288;：</label>-->
                <!--                        --><?php
                //                        $coupon_name = "";
                //                        if(isset($info['coupon_id'])){
                //                            if($info['coupon_id'] == -1){
                //                                $coupon_name = "&nbsp;暂无";
                //                            }else{
                //                                $coupon_info = Weixin_coupon::getInfoById($info['coupon_id']);
                //                                if(isset($coupon_info['name'])){
                //                                    $coupon_name = $coupon_info['name'];
                //
                //                                    if($info['solutions'] == 2){
                //                                        $coupon_name .="(已退回)";
                //                                    }
                //                                }
                //                            }
                //                        }
                //                        echo $coupon_name;
                //                        ?>
                <!--                    </td>-->
                <tr height="50px">
                    <th ></th>
                    <td><label style="font-weight:bolder">提交时间：</label><?php if(isset($info['addtime'])){
                            echo date("Y-m-d H:i:s", $info['addtime']);
                        } ?></td>
                    <td><label style="font-weight:bolder">品牌型号：</label>&#12288;<?php if(isset($info['brand'])){
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
                            }
                        }
                        echo $service_type;

                        ?></td>
                </tr>

                <tr height="50px">
                    <th ></th>
                    <td  colspan="3"><label style="font-weight:bolder">备注信息：</label><?php
                        $remarks = "";
                        if(!empty($info['remarks'])) {
                            $remarks = $info['remarks'];
                        }
                        else{
                            $remarks = "暂无信息";
                        }
                        echo $remarks;
                        ?></td>
                </tr>
                </tbody>
            </table>
        </div>
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
        <div class="tablelist">
            <table  style="border: 1px;" class="table_all_list">
                <tbody>
                    <tr>
                        <th width= "2% " ></th>
                        <td width= "10% " ></td>
                        <td width= "30% " ></td>
                        <td width= "60% " ></td>
                    </tr>

                    <tr height="50px">
                        <th></th>
                        <td><label style="font-weight:bolder">订单跟踪:</label></td>
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
                                    echo '<tr>
                                    <th>
                                    <td width="20%">'.$format_time.'</td>
                                    <td width="20%">'.$order_process.'</td>
                                    <td width="20%">'.$order_status.'</td>
                                    <td width="40%">'.$service_person_phone.'</td> 
                                    </th>
                                    </tr>
                                ';
                                    //date("Y-m-d H:i:s",$row['addtime'])." ".$order_process." ".$order_status." ".$service_person_phone."<br>";
                                }
                                echo '</table>
                                     </tbody>
                                  ';
                            }
                            ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <script type="text/javascript">
            $(function () {
                $('#history_back').click(function(){
                    var id = <?php echo $info['id']?>;
                    location.href="weixin_repair_history.php?id="+id
                });
            });
        </script>
    </div>
</div>
<div class="clear"></div>
</div>
<?php include('footer.inc.php');?>
</body>
</html>