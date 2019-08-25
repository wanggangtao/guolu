<?php
/**
 * 添加用户  user_add.php
 *
 * @version       v0.01
 * @create time   2018/6/27
 * @update time
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

require_once('admin_init.php');
require_once('admincheck.php');
$brand=Dict::getListByPid(1,1);
$type=Dict::getListByParentid(2);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="author" content="芝麻开花 http://www.zhimawork.com" />
    <link rel="stylesheet" href="css/style.css" type="text/css" />
    <link rel="stylesheet" href="css/form.css" type="text/css" />
    <link rel="stylesheet" href="css/boxy.css" type="text/css" />
    <script type="text/javascript" src="js/layer/layer.js"></script>
    <script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="js/layer/layer.js"></script>
    <script type="text/javascript" src="js/common.js"></script>
    <script type="text/javascript" src="js/upload.js"></script>

    <script type="text/javascript">
//         $(function(){
//             $("#brand").change(function () {
//                 var value = $(this).val();
//                 var type = $("#type").val();
//                 console.log(type);
//                 $("#version").empty();
//                 if (value == 0) {
//                     return false;
//                 }
//                 $.ajax({
//                     type: 'post',
//                     url: 'weixin_product_do.php?act=get_version',
//                     data: {
//                         id: value,
//                         type:type
//                     },
//                     dataType: 'json',
//                     success: function (data) {
//
//                         var code=data.msg;
//                         var msg=data.msg;
//                         console.log(msg);
//                         var arr = msg;
//                         if (arr.length == 0) {
//                             $("#version").fadeOut("slow");
//                         } else {
//                             //alert('dasd');
//                             $("#version").fadeIn("slow");
//                             $("#version").append('<option value="0">请选择型号</option>');
//                             for (var i = 0; arr.length; i++) {
//                                 var option = '<option value="' + arr[i].guolu_id + '">' + arr[i].guolu_version + '</option>';
//                                 $("#version").append(option);
//                             }
//                         }
//                     },
//                     error: function () {
//                         alert("请求失败");
//                     }
//                 });
//             });
//             $("#type").change(function () {
//                 var type = $(this).val();
//                 var value = $("#brand").val();
//                 console.log(type);
//                 $("#version").empty();
//                 if (value == 0) {
//                     return false;
//                 }
//                 $.ajax({
//                     type: 'post',
//                     url: 'weixin_product_do.php?act=get_version',
//                     data: {
//                         id: value,
//                         type:type
//                     },
//                     dataType: 'json',
//                     success: function (data) {
//
//                         var code=data.msg;
//                         var msg=data.msg;
//                         console.log(msg);
//                         var arr = msg;
//                         if (arr.length == 0) {
//                             $("#version").fadeOut("slow");
//                         } else {
//                             //alert('dasd');
//                             $("#version").fadeIn("slow");
//                             $("#version").append('<option value="0">请选择型号</option>');
//                             for (var i = 0; arr.length; i++) {
//                                 var option = '<option value="' + arr[i].guolu_id + '">' + arr[i].guolu_version + '</option>';
//                                 $("#version").append(option);
//                             }
//                         }
//                     },
//                     error: function () {
//                         alert("请求失败");
//                     }
//                 });
//             });
//             $("#btn_sumit").click(function () {
//                 var brand =$("#brand").val();
//                 var code = $("#code").val();
//                 var type =$("#type").val();
//                 var version = $("#version").val();
//                 var starttime = $("#starttime").val();
//                 $.ajax({
//                     type: 'post',
//                     url: 'weixin_product_do.php?act=add',
//                     data: {
//                         brand: brand,
//                         code:code,
//                         type:type,
//                         period:starttime,
//                         version:version
//                     },
//                     dataType: 'json',
//                     success: function (data) {
// //                        alert(data);
//                         var code=data.code;
//                         var msg=data.msg;
//                         switch (code){
//                             case 1:
//                                 layer.alert(msg, {icon: 6}, function(index){
//                                     parent.location.reload();
//                                 });
//                                 break;
//                             default:
//                                 layer.alert(msg, {icon: 5});
//                         }
//                     },
//                     error: function () {
//                         alert("请求失败");
//                     }
//                 });
//             });
//         });
//         $(document).ready(function () {
//                 var value = $("#brand").val();
//                 var type = $("#type").val();
//                 console.log(type);
//                 $("#version").empty();
//                 if (value == 0) {
//                     return false;
//                 }
//                 $.ajax({
//                     type: 'post',
//                     url: 'weixin_product_do.php?act=get_version',
//                     data: {
//                         id: value,
//                         type:type
//                     },
//                     dataType: 'json',
//                     success: function (data) {
//
//                         var code=data.msg;
//                         var msg=data.msg;
//                         console.log(msg);
//                         var arr = msg;
//                         if (arr.length == 0) {
//                             $("#version").fadeOut("slow");
//                         } else {
//                             //alert('dasd');
//                             $("#version").fadeIn("slow");
//                             $("#version").append('<option value="0">请选择型号</option>');
//                             for (var i = 0; arr.length; i++) {
//                                 var option = '<option value="' + arr[i].guolu_id + '">' + arr[i].guolu_version + '</option>';
//                                 $("#version").append(option);
//                             }
//                         }
//                     },
//                     error: function () {
//                         alert("请求失败");
//                     }
//                 });
//
//
//         });


        $(function(){
            $("#brand").change(function () {
                var type = $(this).val();
                var value = $("#brand").val();
                // console.log(type);
                $("#version").empty();
                if (value == 0) {
                    return false;
                }
                $.ajax({
                    type: 'post',
                    url: 'weixin_product_do.php?act=get_version',
                    data: {
                        id: value//,
                        //type:type
                    },
                    dataType: 'json',
                    success: function (data) {
                        var code=data.msg;
                        var msg=data.msg;
//                        console.log(msg);
                        var arr = msg;
                        if (arr.length == 0) {
                            $("#version").fadeIn("slow");
                            $("#version").append('<option value="0">请选择型号</option>');
                        } else {
                            //alert('dasd');
                            $("#version").fadeIn("slow");
                            $("#version").append('<option value="0">请选择型号</option>');
                            for (var i = 0; arr.length; i++) {
                                var option = '<option value="' + arr[i].smallguolu_id + '">' + arr[i].smallguolu_version + '</option>';
                                $("#version").append(option);
                            }
                        }
                    },
                    error: function () {
                        alert("请求失败");
                    }
                });
            });
            $("#btn_sumit").click(function () {
                var brand =$("#brand").val();
                var code = $("#code").val();
                // var type =$("#type").val();
                var version = $("#version").val();
//                var starttime = $("#starttime").val();
//                alert(version);
                if(code == ''){
                    layer.tips('条码不能为空', '#code');
                    return false;
                }
                if(brand == ''){
                    layer.tips('品牌不能为空', '#brand');
                    return false;
                }
                if(version == 0){
                    layer.tips('型号不能为空', '#version');
                    return false;
                }



                $.ajax({
                    type: 'post',
                    url: 'weixin_product_do.php?act=add',
                    data: {
                        brand: brand,
                        code:code,
                        // type:type,
                        version:version
                    },
                    dataType: 'json',
                    success: function (data) {
//                        alert(data);
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
            $("#code").blur(function () {
                var code = $("#code").val();
                if(code!=""){
                    $.ajax({
                        type:'post',
                        url: 'weixin_product_do.php?act=code_info',
                        data:{
                            code :　code
                        },
                        dataType:'json',
                        success:function (data) {
                            var code = data.code;
                            var msg  = data.msg;
                            console.log(msg);
                            switch (code){
                                case 1:
                                    break;
                                default:
                                    layer.alert(msg, {icon: 5});
                            }
                        },
                        error:function () {
                            layer.open({
                                content: "请求失败"
                                ,btn: '我知道了'
                            });
                        }
                    })
                }else{
                    layer.tips('条码不能为空', '#code');
                    return false;
                }


            });
        });
        $(document).ready(function () {
            var value = $("#brand").val();
            // var type = $("#type").val();
            // console.log(type);
            // $("#version").empty();
            if (value == 0) {
                return false;
            }
            $.ajax({
                type: 'post',
                url: 'weixin_product_do.php?act=get_version',
                data: {
                    id: value
                    // type:type
                },
                dataType: 'json',
                success: function (data) {

                    var code=data.msg;
                    var msg=data.msg;
                    console.log(msg);
                    var arr = msg;
                    if (arr.length == 0) {
                        $("#version").fadeOut("slow");
                    } else {
                        //alert('dasd');
                        $("#version").fadeIn("slow");
                        $("#version").append('<option value="0">请选择型号</option>');
                        for (var i = 0; arr.length; i++) {
                            var option = '<option value="' + arr[i].smallguolu_id + '">' + arr[i].smallguolu_version + '</option>';
                            $("#version").append(option);
                        }
                    }
                },
                error: function () {
                    alert("请求失败");
                    //echo e.message;
                }
            });


        });


    </script>
</head>
<body>
<div id="formlist">

    <br><br><br>
    <p>

        <label><font color="#dc143c">*</font>条码</label>
        <input type="text" class="text-input input-length-30" name="code" id="code"/>

    </p>
    <p>
        <label><font color="#dc143c">*</font>品牌</label>
        <select  id="brand" class="select-handle">
            <?php
            if($brand)
                foreach($brand as $thisValue){
                    echo '<option value="' . $thisValue['id'] . '">' . $thisValue['name'] . '</option>';
                }
            ?>
        </select>

    </p>
<!--    <p>-->
<!--        <label><font color="#dc143c">*</font>类型</label>-->
<!--        <select  id="type" class="select-handle">-->
<!--            --><?php
//            if($type)
//                foreach($type as $thisValue){
//                    echo '<option value="' . $thisValue['id'] . '">' . $thisValue['name'] . '</option>';
//                }
//            ?>
<!--        </select>-->
<!---->
<!--    </p>-->
    <p>
        <label><font color="#dc143c">*</font>型号</label>
        <select  id="version" class="select-handle">

<!--            --><?php
//            if($version)
//                foreach($version as $thisValue){
//                    echo '<option value="' . $thisValue['id'] . '">' . $thisValue['name'] . '</option>';
//                }
//            ?>
        </select>

    </p>
<!--    <p>-->
<!--        <label><font color="#dc143c"></font>质保期</label>-->
<!--        <input type="text" class="laydate-icon input-length-30"  name="starttime" id="starttime" />-->
<!--        <script type="text/javascript" src="laydate/laydate.js"></script>-->
<!--        <script type="text/javascript">-->
<!--            laydate.skin("molv");//设置皮肤-->
<!--            var start = {-->
<!--                elem: '#starttime',-->
<!--                format: 'YYYY-MM-DD',-->
<!--                istime: true,-->
<!--                istoday: false,-->
<!--                choose: function(datas){-->
<!--                    end.min = datas; //开始日选好后，重置结束日的最小日期-->
<!--                    end.start = datas; //将结束日的初始值设定为开始日-->
<!--                }-->
<!--            };-->
<!--            laydate(start);-->
<!--        </script>-->
<!---->
<!---->
<!--    </p>-->
    <p>
        <label style="width: 200px;">　　</label>
        <input type="submit" id="btn_sumit" class="btn_submit" value="提　交" />
    </p>
</div>
</body>
</html>