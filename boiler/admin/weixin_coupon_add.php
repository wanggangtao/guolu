<?php
/**
 * 添加优惠券  weixin_coupon_add.php
 *
 * @version       v0.01
 * @create time   2018/6/27
 * @update time
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

require_once('admin_init.php');
require_once('admincheck.php');
$params=array();
$servicetype= Service_type::getList($params);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="author" content="芝麻开花 http://www.zhimawork.com" />
    <link rel="stylesheet" href="css/style.css" type="text/css" />
    <link rel="stylesheet" href="css/form.css" type="text/css" />
    <script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="js/layer/layer.js"></script>
    <script type="text/javascript" src="js/common.js"></script>
    <script type="text/javascript" src="js/upload.js"></script>

    <script type="text/javascript">
        $(function(){
            $("#btn_sumit").click(function () {
                var name = $("#name").val();
                var checkID = [];//定义一个空数组存放使用类型
                var money = $("#money").val();
                var cfmmoney = $("#cfmmoney").val();
                var num = $("#num").val();
                var startTime = $('#starttime').val();
                var stopTime = $('#stoptime').val();
                var validity_period = $('#validity_period').val();
                var reg = /^(-?\d+)(\.\d+)?$/;
                $("input[name='usetype']:checked").each(function(i){//把所有被选中的复选框的值存入数组
                    checkID[i] =$(this).val();
                });
                if(name == ''){
                    layer.tips('优惠券名称不能为空', '#name');
                    return false;
                }
                if(name.length>10){
                    layer.tips('优惠券名称不能超过十个字', '#name');
                    return false;
                }
                if(money == ''){
                    layer.tips('优惠券金额不能为空', '#money');
                    return false;
                }
                if(cfmmoney == ''){
                    layer.tips('优惠券金额不能为空', '#cfmmoney');
                    return false;
                }
                if (!(reg.test(money))) {
                    layer.tips('优惠券金额应为数字', '#money');
                    return false;
                }
                if (!(reg.test(cfmmoney))) {
                    layer.tips('优惠券金额应为数字', '#cfmmoney');
                    return false;
                }
                if (cfmmoney != money) {
                    layer.tips("两次金额输入不一致！", '#cfmmoney');
                    return false;
                }
                if(num == ''){
                    num = -1;//表示不限制发放数量
                }
                if(validity_period == ''){
                    validity_period = 0;//表示不判断有效期
                    if(stopTime == ''&& startTime != ''){
                        layer.tips('请选择活动结束时间', '#stoptime');
                        return false;
                    }
                    if(stopTime != '' && startTime == ''){
                        layer.tips('请选择活动开始时间', '#starttime');
                        return false;
                    }
                    if(stopTime == '' && startTime == ''){
                        layer.tips('请选择活动限制时间', '#xianzhi');
                        return false;
                    }
                }
                if (!(reg.test(validity_period))) {
                    layer.tips('有效期应为数字', '#validity_period');
                    return false;
                }
                if(startTime == ''&& stopTime == ''){
                    startTime = 0;//表示不使用活动时间
                    stopTime = 0;//表示不使用活动时间
                }

                if(checkID.length==0){
                    layer.alert("使用类型不能为空!",{icon:5});
                    return false;
                }
                // console.log(startTime);
                checkID = checkID.join(",");
                $.ajax({
                    type: 'post',
                    url: 'weixin_coupon_do.php?act=add',
                    data: {
                        name:name,
                        checkID:checkID,
                        money:money,
                        num:num,
                        startTime:startTime,
                        stopTime:stopTime,
                        validity_period:validity_period,
                        // id:id,
                    },
                    dataType: 'json',
                    success: function (data) {
                        var code=data.code;
                        var msg=data.msg;
                        switch (code){
                            case 1:
                                layer.alert(msg, {icon: 6}, function(index){
                                    parent.location.reload();
                                });
                                break;
                            default:
                                layer.alert(msg, {icon: 5});
                        }
                    },
                    error: function () {
                        alert("请求失败");
                    }
                });
            });
            $("#btn_cacal").click(function () {
                parent.location.reload();
            });


        });

    </script>
</head>
<body>
<div id="formlist">

    <br>
    <p>

        <label><font color="#dc143c">*</font>优惠券名称：</label>
        <input type="text" autocomplete="off" class="text-input input-length-30" name="name" id="name" style="height: 10px;margin-top: 7px;">

    </p>
    <p>
        <label><font color="#dc143c">*</font>使用类型：</label>
        <?php
        if($servicetype)
            foreach($servicetype as $thisValue){
                echo '<input type="checkBox" name="usetype" id="usetype" style="margin-top:10px;" value="'.$thisValue['id'].'"/>'.$thisValue['name'];
            }
        ?>
    </p>
    <p>

        <label><font color="#dc143c">*</font>发放数量：</label>
        <input type="radio" class="numradio" name="numradio" id="unlimited" value="1" style="margin-top: 11px;"checked="checked">不限
        <input type="radio" class="numradio" name="numradio" id="customize"value="2"/>自定义
        <input type="text" class="" name="num" id="num" style="width:80px;height: 20px;display:none"/><span id="zhang"style="display:none">张</span>
        <script type="text/javascript">
             $(function(){
                 $('.numradio').change(function(){
                     var val=$(this).val();
                     if(val==1){
                         $("#num").val("");
                         $('#num').hide();
                         $('#zhang').hide();
                     }
                     else{
                         $('#num').show();
                         $('#zhang').show();
                     }
                 });
            });
        </script>
    </p>
    <p>

        <label><font color="#dc143c">*</font>优惠券金额：</label>
        <input type="text" class="text-input input-length-30" name="money" id="money" style="height: 10px;margin-top: 6px;">

    </p>
    <p>

        <label><font color="#dc143c">*</font>确认金额：</label>
        <input type="text" class="text-input input-length-30" name="cfmmoney" id="cfmmoney" style="height: 10px;margin-top: 6px;">

    </p>
    <p>
        <label id="xianzhi"><font color="#dc143c">*</font>使用限制：</label>
        <input type="radio" class="activitytime" name="activity" id="activity_time" value="1"  style="margin-top: 12px;">起止时间
        <input type="text" class="" autocomplete="off" name="starttime" id="starttime" value="<?php echo $starttime; ?>" style="width: 130px;height: 20px;display:none"/><span  style="display:none" id="zhi"> 至</span>
        <input type="text" class="" autocomplete="off" name="stoptime" id="stoptime" value="<?php echo $stoptime; ?>" style="width: 130px;height: 20px;display:none"/>
        <script type="text/javascript" src="laydate/laydate.js"></script>
        <script type="text/javascript">
            laydate.skin("molv");//设置皮肤
            var start = {
                elem: '#starttime',
                format: 'YYYY-MM-DD hh:mm:ss',
                istime: true,
                istoday: false,
                min: laydate.now(),
                choose: function(datas){
                    end.min = datas; //开始日选好后，重置结束日的最小日期
                }
            };
            var end = {
                elem: '#stoptime',
                format: 'YYYY-MM-DD hh:mm:ss',
                istime: true,
                min: laydate.now(),

                choose: function(datas){
                    start.max = datas; //结束日选好后，重置开始日的最大日
                }
            };
            laydate(start);
            laydate(end);
        </script>

        <br>
        <input type="radio" class="activitytime" name="activity" id="validity" value="2" style="margin-top: 16px;" checked="checked"/>固定时间
        <input type="text" class="" name="validity_period" id="validity_period" style="display: inline-block;margin-left: 15px;width: 117px;height: 20px;"><span  id="day">天</span>
        <script type="text/javascript">
            $(function(){
                $('.activitytime').change(function(){
                    var val=$(this).val();
                    if(val==1){
                        $("#validity_period").val("");
                        $('#validity_period').hide();
                        $('#day').hide();
                        $('#starttime').show();
                        $('#stoptime').show();
                        $('#zhi').show();
                    }
                    else{
                        $("#starttime").val("");
                        $("#stoptime").val("");
                        $('#starttime').hide();
                        $('#stoptime').hide();
                        $('#zhi').hide();
                        $('#validity_period').show();
                        $('#day').show();
                    }
                });
            });
        </script>
    </p>

    <p>

        <label style="width: 150px;">　　</label>
        <input type="submit" id="btn_sumit" class="btn_submit" value="提 交" />
        <input type="reset" id="btn_cacal" class="btn_submit" value="取 消" />
    </p>
</div>
</body>
</html>