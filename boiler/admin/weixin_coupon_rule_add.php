<?php
/**
 * 添加优惠券规则  weixin_coupon_rule_add.php
 *
 * @version       v0.01
 * @create time   2018/6/27
 * @update time
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

require_once('admin_init.php');
require_once('admincheck.php');
$params["onTime"] = time();
$servicetype= Weixin_coupon::getList($params);
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
        var select_community_array = [];
        var select_coupon_array = [];

        $(function(){

            $("#btn_sumit").click(function () {
                var type_array =  new Array();
                var community_array = new Array();
                community_array.push(-1);

                $(".close_img").each(function () {
                    var real_id=$(this).attr("real_id");
                    type_array.push(real_id);
                })

                $(".close_img2").each(function () {
                    var real_id=$(this).attr("real_id");
                    community_array.push(real_id);

                })

                if(type_array <= 0){
                    layer.tips('优惠券不能为空', '#select_coupon');
                    return false;
                }
                if($('#select_community').val() == -2 && community_array < 1){
                    layer.tips('小区不能为空', '#select_community');
                    return false;
                }


                var name = $("#name").val();
                var startTime = $('#starttime').val();
                var stopTime = $('#stoptime').val();
                if(name == ''){
                    layer.tips('规则名称不能为空', '#name');
                    return false;
                }
                if(name.length>10){
                    layer.tips('规则名称不能超过十个字', '#name');
                    return false;
                }
                if(startTime == ''){
                    layer.tips('活动开始时间不能为空', '#starttime');
                    return false;
                }
                if(stopTime == ''){
                    layer.tips('活动结束时间不能为空', '#stoptime');
                    return false;
                }
                $.ajax({
                    type: 'post',
                    url: 'weixin_coupon_rule_do.php?act=add',
                    data: {
                        name:name,
                        startTime:startTime,
                        stopTime:stopTime,
                        type_array : type_array,
                        community_array:community_array
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

            var index = 0;
            var content = "";
            $("#select_coupon").change(function(){
                $(this).blur();
                var id=$('#select_coupon option:selected') .val();//选中的值
                var name=$('#select_coupon option:selected').text();//选中的文本
                if(id == -1){
                    return false;
                }
                if(name != ''){
                    select_coupon_array.push(id);
                    content =  '<span class = "close_img" style="font-weight:bold; margin-left: 157px;" real_id = "'+id+'" > <font>'+name+'</font><img src="images/cancle.png"   style="width: 13px;height: 13px;"  index_id = "'+index+'"/><br><br></span>';
                    $(".is_show").before(content);

                    index ++;
                }


            }).focus(function () {
                $(this).val("-1");
            });

            $("#select_community").change(function(){
                var id=$('#select_community option:selected') .val();//选中的值
                var name=$('#select_community option:selected').text();//选中的文本
                if(id == -1){
                    select_community_array = [];
                    community_array =[];
                    community_array.push(-1);
                    $(".close_img2").each(function () {
                        $(this).remove();
                    })
                    return false;
                }

                if(id > 0){

                    if($.inArray(id ,select_community_array) < 0 ){
                        select_community_array .push(id);
                        content =  '<span class = "close_img2" style="font-weight:bold; margin-left: 157px;" real_id = "'+id+'"><font>'+name+'</font><img src="images/cancle.png"   style="width: 13px;height: 13px;"  index_id = "'+index+'"/><br><br></span>';
                        $(".show_community").before(content);
                        index ++;
                    }
                }


            }).focus(function () {
                $(this).val("-2");
            });



        });

        $(document).on("click",'.close_img',function(){
//            var index = $(this).find("img").attr("index_id");
//            alert(index);
            select_coupon_array.splice(select_coupon_array.indexOf($(this).attr("real_id")),1)

            $(this).remove();


            if(select_coupon_array != ""){
                $("#select_coupon").val(select_coupon_array[0]);
            }else{
                $("#select_coupon").val("-1");
            }

        })

        $(document).on("click",'.close_img2',function(){
            select_community_array.splice(select_community_array.indexOf($(this).attr("real_id")),1)

            $(this).remove();

            if(select_community_array != ""){
                $("#select_community").val(select_community_array[0]);
            }else{
                $("#select_community").val("-1");
            }
        })
    </script>
</head>
<body>
<div id="formlist">

    <br>
    <p>

        <label><font color="#dc143c">*</font>规则名称：</label>
        <input type="text" autocomplete="off" class="text-input input-length-30" name="name" id="name" style="height: 10px;margin-top: 6px;"/>

    </p>

    <p>
        <label><font color="#dc143c">*</font>选择优惠券：</label>
        <select  id="select_coupon" class="select-handle" style="width: 157.62px; height: 26px;margin-top: 5px;">
            <option value="-1">请选择优惠券</option>
            <?php
            if($servicetype)
                foreach($servicetype as $thisValue){
                    echo '<option  value="' . $thisValue['id'] . '" >' . $thisValue['name'] . '</option>';
                }
            ?>
        </select>
    </p>
        <div class = "is_show">
        </div>
    <p>
        <label><font color="#dc143c">*</font>选择小区：</label>
        <select  id="select_community" class="select-handle" style="width: 157.62px; height: 26px;margin-top: 5px;">
            <option value="-1">全部小区</option>
            <?php
            $community_info =  Community::getList();
            if($community_info)
                foreach($community_info as $thisValue){
                    echo '<option  value="' . $thisValue['id'] . '" >' . $thisValue['name'] . '</option>';
                }
            ?>
            <option value="-2">请选择小区</option>

        </select>
    </p>
    <div class = "show_community">
    </div>

    <p>
        <label><font color="#dc143c">*</font>发放开始时间：</label>
        <input type="text" autocomplete="off" class="text-input input-length-30"  name="starttime" id="starttime" value="<?php echo $starttime; ?>" style="height: 10px; margin-top: 5px;"/>
    </p>
    <p>
        <label><font color="#dc143c">*</font>发放结束时间：</label>
        <input type="text" autocomplete="off" class="text-input input-length-30"  name="stoptime" id="stoptime" value="<?php echo $stoptime; ?>" style="height: 10px; margin-top: 5px;"/>
    </p>
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
    </p>

    <p>

        <label style="width: 150px;">　　</label>
        <input type="submit" id="btn_sumit" class="btn_submit" value="提 交" />
        <input type="reset" id="btn_cacal" class="btn_submit" value="取 消" />
    </p>
</div>
</body>
</html>