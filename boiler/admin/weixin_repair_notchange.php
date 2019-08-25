<?php
/**
 * Created by PhpStorm.
 * User: ww
 * Date: 2019/3/15
 * Time: 15:12
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
$child_status = $info['child_status'];
if($info['status'] == 2){
    $FLAG_LEFTMENU  = 'weixin_repair_treating';
}



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
    <script type="text/javascript">
        $(function() {
            //$('#order_over').click(function () {
            //    //parent.location.href = "javascript:history.go(-1)";
            //    var id=<?php //echo $info['id']?>
            //    var child_status=33;
            //    location.href = "weixin_repair_cancel.php?id="+id+"&&child_status="+child_status;
            //});

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
                location.href="weixin_repair_history.php?id="+id;
            });

            $('#order_over').click(function(){
                var id=<?php echo $info['id']?>;
                layer.open({
                    type: 2,
                    title: '审核',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['500px', '300px'],
                    content: 'weixin_repair_verify.php?id='+id
                });
            });
        });

        $(function() {
            $('#btn_back').click(function () {
                parent.location.href = "javascript:history.go(-1)";
            })
        });

        function setImg(path){
            var web_path = "<?php echo $HTTP_PATH?>";
            var all_path = web_path + path;
            $('#big_img').attr('src',all_path);
        }
    </script>

</head>
<body>
<div id="header">
    <?php include('top.inc.php');?>
    <?php include('nav.inc.php');?>
</div>
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
<div id="container">
    <?php include('weixin_menu_inc.php');?>
    <div id="maincontent">
        <div id="position">当前位置：<a href="weixin_employees_info.php">公众号管理</a> &gt; 预约管理</div>
        <?php
            if($child_status == 33){
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
                                <img  class="img status" src="images/已审核_u3141.png">
                            </div>
                        </div>
                    </div>
                ';
            }
            if($child_status == 32){
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
                                <img  class="img status" src="images/待审核_u3139.png">
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
                            <div id="u099" class="ax_default tupian">
                                <img  class="img status1" src="images/异常_u2791.png">
                            </div>
                        </div>
            
                        <div class="basechange">
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
                    if ($info['child_status'] == 23) {
                        echo '<a href="weixin_repair_treating.php">返回</a>';
                    }
                    else{
                        echo '<a href="weixin_repair_treated.php">返回</a>';
                    }
                    
                ?>
                
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

        <table  style="border: 1px;" class="table_all_list">
            <tbody>
                <tr>
                    <th width= "4%"></th>
                    <td width="10"></td>
                    <td width="30%"></td>
                    <td width="60"></td>
                </tr>

                <tr height="50px">
                    <?php
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
                    ?>
                    <th></th>
                    <td >
                        <label style="font-weight:bolder">维修人员 ：</label>
                    </td>
                    <td>服务人员：<?php echo $name;?></td>
                    <td>联系电话: <?php echo $phone;?></td>
                </tr>

                <tr height="50px">
                    <th></th>
                    <td>
                        <label style="font-weight:bolder">解决途径 ：</label>
                    </td>
                    <td>
                        <?php
                        if (isset($info['solutions'])) {
                            if ($info['solutions']==1){
                                echo "上门服务";
                            }
                            if ($info['solutions']==2){
                                echo "电话服务";
                            }
                        }
                        ?>
                    </td>
                </tr>

                <tr height="50px">
                    <th></th>
                    <td>
                        <label style="font-weight:bolder">维修详情 ：</label>
                    </td>
                    <td>
                        <?php
                        //                        if (isset($info['result'])){
                        //                            echo $info['result'];
                        //                        }
                        echo "维修情况： ".$info['result'];
                        ?>
                    </td>
                </tr>

                <tr  height="50px">
                    <th width="2%" ></th>
                    <td width="10%"></td>
                    <td colspan="2">
                        <?php
                            $repair_part = Boiler_repair_parts::getrepair_part($info['id']);
                            if (!empty($repair_part)){
                            echo '
                            <div>
                                <div>使用配件：
                            ';
                            foreach ($repair_part as $row){
                                echo $row['Info_part'].'&nbsp;&nbsp;&nbsp;&nbsp;数量：'.$row['part_num'].'&nbsp;&nbsp;&nbsp;&nbsp;金额：'.($row['part_money']*$row['part_num']).'</div><br>
                                <div>&#12288;&#12288;&#12288;&#12288;&#12288;';
                            }
                            echo '</div></div>';
                            }
                            else{
                                echo "使用配件： 暂无";
                            }
                        ?>

<!--                        <table>-->
<!--                            <tbody>-->
<!--                            --><?php
//                            $repair_part = Boiler_repair_parts::getrepair_part($info['id']);
//                            if (!empty($repair_part)){
//                                foreach ($repair_part as $row){
//                                    echo '<tr><td width="30%">'.$row['Info_part'].'</td>
//                                            <td width="30%">数量：'.$row['part_num'].'</td>
//                                            <td width="30%">金额：'.($row['part_money']*$row['part_num']).'</td>
//                                          </tr>
//                                    ';
//                                }
//                            }
//                            ?>
<!--                            </tbody>-->
<!--                        </table>-->
                    </td>
                </tr>

                <tr height="50px">
                    <th width= "2% " ></th>
                    <td width="10">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    <td colspan="2">上传照片：
                        <?php
                        $has_img = 0;
                        if(!empty($info['picture_url'])){

                            $has_img = 1;
                            $url_array = explode(",",$info['picture_url']);
                            $url_array = array_filter($url_array);
                            //                            print_r($url_array);
                        }
                        if($has_img) {
                            $pos = strripos($url_array[0], "/");
                            $now_path = $HTTP_PATH . substr($url_array[0], 0, $pos + 1) . "s_" . substr($url_array[0], $pos + 1);
                            echo '
                                <table class="table_all_list">
                                    <tr>
                                        <td rowspan="4" align="center">
                                            <img src="' . $now_path . '" id="big_img" alt="">
                                        </td>
                                        <td>
                                        </td>
                                    </tr>';
                            foreach ($url_array as $key => $item) {
                                if ($key > 2) break;
                                $pos_item = strripos($item, "/");
                                $now_item_path = substr($item, 0, $pos_item + 1) . "s_" . substr($item, $pos_item + 1);
                                echo '
                                    <tr>
                                        <td>
                                            <a onclick="setImg(' . $now_item_path . ')"> <img
                                                        src="' . $HTTP_PATH . $item . '" width="100px"
                                                        height="100px" alt=""></a>
                                        </td>
                                    </tr>';
                            }
                        }
                        else{
                            echo "暂无照片";
                        }
                           echo '
                        </table>';
                        ?>
                    </td>
                </tr>

                <tr height="50px">
                    <th></th>
                    <td></td>
                    <td colspan="2">
                        维修金额：上门费：￥<?php echo $info['repair_hand_money'];?>，配件金额：￥<?php  echo $info['repair_part_money'];?>。合计：￥<?php echo ($info['repair_hand_money']+$info['repair_part_money']);?>
                    </td>
                </tr>
                <tr height="50px">
                    <th></th>
                    <td><label style="font-weight:bolder">解决途径 ：</label></td>
                    <td colspan="2">
                        应收：上门费 ￥<?php  echo $info['repair_hand_money'];?>，配件费 ￥<?php  echo $info['repair_part_money'];?>。
                    </td>
                </tr>
                <tr height="50px">
                    <th></th>
                    <td></td>
                    <td colspan="2">
                        优惠券：<?php
                            if ($info['coupon_id']!=-1){
                                $coupon = Weixin_coupon::getInfoById($info['coupon_id']);
                                if (!empty($coupon)){
                                    echo $coupon['name'];
                                }
                            }
                            else{
                                echo "暂无";
                            }
                        ?>
                    </td>
                </tr>
                <tr height="50px">
                    <th></th>
                    <td></td>
                    <td colspan="2">
                        实收：￥
                        <?php
                        $product_info = Product_info::getInfoBycode($info['bar_code']);
                        if($product_info['period'] == "在保"){
                            echo "0.00元（保质期内不收维修费）";
                            }
                            else{
                                echo $info['sum_money'];
                            }?>
                    </td>
                </tr>
                <tr height="50px">
                    <th></th>
                    <td><label style="font-weight:bolder">评价信息 ：</label>
                    <td colspan="2">
                        <?php
                            if($info['user_evaluation']!=-1){
                                echo $info['user_evaluation'];
                            }
                            else{
                                echo "暂无";
                            }
                        ?>
                    </td>
                    </td>
                </tr>
                <?php
                    if ($info['child_status'] == 33) {
                            echo '<tr height="50px">
                        <th></th>
                        <td><label style="font-weight:bolder">审核信息 ：</label></td>
                        <td colspan="2">用户反馈：';

                            if ($info['client_satisfy'] == 1) {
                                echo "非常满意";
                            } else if ($info['client_satisfy'] == 2) {
                                echo "满意";
                            } else if ($info['client_satisfy'] == 3) {
                                echo "一般";
                            } else if ($info['client_satisfy'] == 4) {
                                echo "不满意";
                            } else if ($info['client_satisfy'] == 5) {
                                echo "非常不满意";
                            } else {
                                echo "暂无";
                            }

                            echo '
                        </td>
                    </tr>
                    <tr height="50px">
                        <th></th>
                        <td></td>
                        <td>反馈内容：';
                            if (!empty($info['client_evaluation'])) {
                                echo $info['client_evaluation'];
                            } else {
                                echo "暂无";
                            }
                            echo '
                        </td>
                    </tr>';
                    }
                ?>
            </tbody>
        </table>
        <div class="tablelist">
            <table  style="border: 1px;" class="table_all_list">
                <tbody>
                <tr>
                    <th width= "2% "  ></th>
                    <td width= "10% " ></td>
                    <td width= "30% " ></td>
                    <td width= "60% "></td>
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
                                echo '<tr>
                                    <th></th>
                                    <td width="20%">'.$format_time.'</td>
                                    <td width="20%">'.$order_process.'</td>
                                    <td width="20%">'.$order_status.'</td>
                                    <td width="40%">'.$service_person_phone.'</td>
                                    </tr>
                                ';
                            }
                            echo '</tbody>
                                     </table>
                                  ';
                        }
                        ?>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
            <div >
            <?php
                if ($child_status==32){
                    echo '<div style="text-align:center">
                            <input type="button" class="btn-handle"  id="order_over" name="order_over" value="审核">
                          </div>
                            ';
                }
                if ($child_status==23){
                    echo '<div style="text-align:center">
                            <input type="button" class="btn-handle btn-grey"  id="order_cancel" name="order_cancel" value="取消订单">
                          </div>
                            ';
                }
                if ($child_status==33){
                    echo "";
                }
            ?>
            </div>
        </div>
    </div>
    <div class="clear"></div>
</div>
<?php include('footer.inc.php');?>
</body>
</html>