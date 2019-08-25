<?php
/**
 * 添加订单
 *
 * @version       v0.01
 * @create time   2018/6/27
 * @update time
 * @author        sxx
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

require_once('admin_init.php');
require_once('admincheck.php');
$brand=Dict::getListByParentid(1);
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
    <link rel="stylesheet" href="css/results.css">
    <script type="text/javascript">

        $(function(){
            $("#address_one").change(function () {
                var value = $(this).val();
                var type=0;
                $("#address_two").empty();
                $("#address_three").empty();
                $("#address_four").empty();
                $("#address_three").fadeOut();
                $("#address_four").fadeOut();
                if (value == 0) {
                    return false;
                }
                $.ajax({
                    type: 'post',
                    url: 'address_do.php?act=getChild',
                    data: {
                        id: value,
                        type:type
                    },
                    dataType: 'json',
                    success: function (data) {

                        var code=data.msg;
                        var msg=data.msg;
                        console.log(msg);
                        var arr = msg;
                        if (arr.length == 0) {
                            $("#address_two").fadeOut("slow");
                            $("#address_three").fadeOut("slow");
                            $("#address_four").fadeOut("slow");
                        } else {
                            $("#address_two").fadeIn("slow");
                            $("#address_two").append('<option value="0">请选择市</option>');
                            for (var i = 0; arr.length; i++) {
                                var option = '<option value="' + arr[i].id + '">' + arr[i].name + '</option>';
                                $("#address_two").append(option);
                            }
                            $("#address_three").fadeIn("slow");
                            $("#address_three").append('<option value="0">请选择区</option>');
                            $("#address_four").fadeIn("slow");
                            $("#address_four").append('<option value="0">请选择小区</option>');
                        }
                    },
                    error: function () {
                        alert("请求失败");
                    }
                });
            });
            $("#address_two").change(function () {
                var value = $(this).val();
                var type=0;
                $("#address_three").empty();
                $("#address_four").empty();
                if (value == 0) {
                    return false;
                }
                $.ajax({
                    type: 'post',
                    url: 'address_do.php?act=getChild',
                    data: {
                        id: value,
                        type:type
                    },
                    dataType: 'json',
                    success: function (data) {

                        var code=data.msg;
                        var msg=data.msg;
                        console.log(msg);
                        var arr = msg;
                        if (arr.length == 0) {
                        $("#address_three").fadeOut("slow");
                        $("#address_four").fadeOut("slow");
                        } else {
                            $("#address_three").fadeIn("slow");
                            $("#address_three").append('<option value="0">请选择区</option>');
                            for (var i = 0; arr.length; i++) {
                                var option = '<option value="' + arr[i].id + '">' + arr[i].name + '</option>';
                                $("#address_three").append(option);
                            }
                            $("#address_four").fadeIn("slow");
                            $("#address_four").append('<option value="0">请选择小区</option>');
                        }
                    },
                    error: function () {
                        alert("请求失败");
                    }
                });
            });
            $("#address_three").change(function () {
                var value = $(this).val();
                var type=0;
                $("#address_four").empty();
                if (value == 0) {
                    return false;
                }
                $.ajax({
                    type: 'post',
                    url: 'weixin_community_do.php?act=getCommunity',
                    data: {
                        id: value,
                        type:type
                    },
                    dataType: 'json',
                    success: function (data) {
                        var code=data.msg;
                        var msg=data.msg;
                        console.log(msg);
                        var arr = msg;
                        if (arr.length == 0) {
                            $("#address_four").fadeOut("slow");
                        } else {
                            $("#address_four").fadeIn("slow");
                            $("#address_four").append('<option value="-1">其他</option>');
                            for (var i = 0; arr.length; i++) {
                                var option = '<option value="' + arr[i].id + '">' + arr[i].name + '</option>';
                                $("#address_four").append(option);
                            }

                        }
                        $("#address_four").fadeIn("slow");
                        var option = '<option value="-1">其他</option>';
                        var option = '<option value="-1">其他</option>';
                        $("#address_four").append(option);

                    },
                    error: function () {
                        alert("请求失败");
                    }
                });
            });
            $("#btn_sumit").click(function () {

                var name = $("#name").val();
                var phone = $("#phone").val();
                var WXphone = $("#WXphone").val();
                var code = $("#code").val();
                var province=$("#address_one").val();
                var city=$("#address_two").val();
                var area=$("#address_three").val();
                var community=$("#address_four").val();
                var address=$("#address").val();
                var province_name=$("#address_one").find("option:selected").text();
                var city_name=$("#address_two").find("option:selected").text();
                var area_name=$("#address_three").find("option:selected").text();
                var community_name=$("#address_four").find("option:selected").text();
                var model = $("#model option:selected").val();
                var brand = $("#brand option:selected").val();
                
                var service_type=$('#u1 input[name="u1"]:checked').val();
                var failure_cause = $("#fail_cause").val();
                
                //alert(service_type);//alert(brand);//alert(community_name);
                if(name == ''){
                    layer.tips('联系人不能为空', '#name');
                    return false;
                }
                if(model == ''){
                    layer.tips('型号不能为空', '#model');
                    return false;
                }
                if(brand == ''){
                    layer.tips('品牌不能为空', '#brand');
                    return false;
                }
                if(phone == ''){
                    layer.tips('注册电话不能为空', '#phone');
                    return false;
                }
                if(WXphone == ''){
                    layer.tips('维修电话不能为空', '#WXphone');
                    return false;
                }
                if(failure_cause == ''){
                    layer.tips('故障描述不能为空', '#fail_cause');
                    return false;
                }
                if(code == ''){
                    layer.tips('条码不能为空', '#code');
                    return false;
                }
                if(province == 0){
                    layer.tips('省不能为空', '#address_one');
                    return false;
                }
                if(city == 0){
                    layer.tips('市不能为空', '#address_two');
                    return false;
                }
                if(area == 0){
                    layer.tips('区不能为空', '#address_three');
                    return false;
                }
                if(community == 0){
                    layer.tips('小区不能为空', '#address_four');
                    return false;
                }

                if(address == ""){
                    layer.tips('详细地址不能为空', '#address');
                    return false;
                }

                if(community_name=="请选择小区")
                {
                    community=0;
                    community_name="";
                }
                var contact_address=province_name+" "+city_name+" "+area_name+" "+community_name+" "+address;
                var status = 1;
                var child_status = 11;
                $.ajax({
                    type: 'post',
                    url: 'weixin_repair_do.php?act=add',
                    data: {
                        name:name,
                        phone:phone,
                        WXphone:WXphone,
                        model  :model,
                        brand  :brand,
                        code : code,
                        service_type:service_type,
                        failure_cause :failure_cause,
                        status  : status,
                        child_status :child_status,
                        contact_address:contact_address
                       

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
//            $("#code").keyup(function (e) {
//                var code = $("#code").val();
//                if(code!=""){
//                    $.ajax({
//                        type:'post',
//                        url: 'weixin_user_do.php?act=code',
//                        data:{
//                            code :　code
//                        },
//                        dataType:'json',
//                        success:function (data) {
//                            var code = data.code;
//                            var msg  = data.msg;
//                            $("ul li").remove();
//                            switch (code){
//                                case 1:
//                                    console.log(msg);
//                                    $("#code_div").show();
//                                    for(var i =0;i<3;i++){
//                                        console.log(msg[i][0]);
//                                        $("#code_list").append('<li onclick="copy(\''+msg[i][0] +'\')" href="#">' +
//                                            '<div class="li-left">' +
//                                            '<span class="ef-num">' + msg[i][0] + '</span> ' +
//                                            '</div></li>');
//                                    }
//                                    break;
//                                default :
//                                    $("#code").val("");
//                                    $("#code_div").hide();
//                            }
//                        },
//                        error:function () {
//                            layer.open({
//                                content: "请求失败"
//                                ,btn: '我知道了'
//                            });
//                        }
//                    })
//                }else{
//                    $("#code").val("");
//                    $("#code_div").hide();
//                }
//
//
//            });
            $("#phone").blur(function () {
                var phone = $("#phone").val();
                if(phone!=""){

                    $.ajax({
                        type:'post',
                        url: 'weixin_repair_do.php?act=phone_info',
                        data:{
                            phone :　phone
                        },
                        dataType:'json',
                        success:function (data) {
                            var code = data.code;
                            var msg  = data.msg;
                            console.log(msg);
                            switch (code){
                                case 1:
                                    if(msg.all_address!=""){
                                        $("#address_one").append('<option value="'+msg.province_id+'" selected>'+msg.province_name+'</option>');;
                                        $("#address_two").append('<option value="'+msg.city_id+'" selected>'+msg.city_name+'</option>');;
                                        $("#address_three").append('<option value="'+msg.area_id+'" selected>'+msg.area_name+'</option>');;
                                        $("#address_four").append('<option value="'+msg.community_id+'" selected>'+msg.community_name+'</option>');;
                                        $("#address").val(msg.detail_addres);
                                    }
                                    if (msg.brands!="") {
                                    $("#brand").append('<option value="'+msg.brands+'" selected>'+msg.brands+'</option>');;	

                                    }
                                    if (msg.versions!="") {
                                    $("#model").append('<option value="'+msg.versions+'" selected>'+msg.versions+'</option>');;	

                                    }
                                    if (msg.code!="") {
                                     $("#code").val(msg.code);	

                                    }
                                    if (msg.name!="") {
                                     $("#name").val(msg.name);	

                                    }


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
            $("#phone").blur(function () {
                var phone = $("#phone").val();
                var isNumber =/^[1][3,4,5,7,8,9][0-9]{9}$/;
                if(!isNumber.test(phone)){
                    layer.tips('请输入正确的联系方式', '#phone');
                    return false;
                }

            });
            $("#WXphone").blur(function () {
                var phone = $("#WXphone").val();
                var isNumber =/^[1][3,4,5,7,8,9][0-9]{9}$/;
                if(!isNumber.test(phone)){
                    layer.tips('请输入正确的联系方式', '#WXphone');
                    return false;
                }

            });

        });


    function  copy($name) {
        $("#code").val($name);
        $("#code_div").hide();
    }
    </script>
    <style type="text/css">
        #div_main {
            margin: 0 auto;
            width: 300px;
            height: 400px;
            border: 1px solid black;
            margin-top: 50px;
        }

        #div_txt {
            position: relative;
            width: 200px;
            margin: 0 auto;
            margin-top: 40px;
        }

        #txt1 {
            width: 99%;
        }

        #div_items {
            position: relative;
            width: 40%;
            height: 80px;
            border: 1px solid #66afe9;
            border-top: 0px;
            overflow: auto;
            display: none;
        }

        .div_item {
            width: 100%;
            height: 20px;
            margin-top: 1px;
            font-size: 13px;
            line-height: 20px;
        }
        </style>
</head>
<body>
<div id="formlist">

    <br><br><br>
        <p>

        <label><font color="#dc143c">*</font>注册电话</label>
        <input type="text" class="text-input input-length-30" name="phone" id="phone"/>

    </p>

    <p>
        <label><font color="#dc143c">*</font>地址</label>
        <span>

            <select  id="address_one" class="select-handle">
                <option value="0">请选择省份</option>
                <?php
                $rows = District::getAddressType(3,0);
                if(!empty($rows)) {
                    foreach (array_reverse($rows) as $row) {
                        $oneId = $row['id'];
                        $name = $row['name'];
                        echo '<option value="' . $oneId . '">' . $name . '</option>';
                    }
                }
                ?>
            </select>
            <select    id="address_two" class="select-handle">
                <option value="0">请选择市</option>
            </select>
            <select    id="address_three" class="select-handle">
                <option value="0">请选择区</option>
            </select>
             <select    id="address_four" class="select-handle">
                <option value="0">请选择小区</option>
            </select>
        </span>

    </p>
    <p>

        <label><font color="#dc143c">*</font>详细地址</label>

        <textarea rows="10" cols="50" id="address"></textarea>
        <!--        <input type="text" class="text-input input-length-30" name="code" id="code"/>-->

     <p>
        <label><font color="#dc143c">*</font>型号</label>
        <span>

			<select  id="model" class="select-handle">
                <option value="0">请选择型号</option>
                <option value="1">LN1GBQ2800(MG-2800)</option>
                <option value="2">LN1GBQ2800(MG-2800)</option>
                <option value="3">LN1GBQ2800(MG-2800)</option>
                <option value="4">LN1GBQ2800(MG-2800)</option>
                <option value="5">LN1GBQ2800(MG-2800)</option>

            </select>
        </span>
     </p>
          <p>
        <label><font color="#dc143c">*</font>品牌</label>
        <span>

			<select  id="brand" class="select-handle">
                <option value="0">请选择品牌</option>
                <option value="1">法罗力</option>
                <option value="2">康佳</option>
                <option value="3">晟恺</option>
                <option value="4">音诺伟森</option>

            </select>
        </span>
     </p> 

    <p>

        <label><font color="#dc143c">*</font>联系人</label>
        <input type="text" class="text-input input-length-30" name="name" id="name"/>

    </p>
        <p>

        <label><font color="#dc143c">*</font>条码</label>
        <input type="text" class="text-input input-length-30" name="code" id="code"/>
        <div id="code_div" class="equity-fund"  style="display: none" style="position: absolute; z-index: 9999">
            <br/><br/>
            <ul   class="ef-content" id="code_list" >
            </ul>
        </div>

    </p>

    <p>

        <label><font color="#dc143c">*</font>维修联系电话</label>
        <input type="text" class="text-input input-length-30" name="WXphone" id="WXphone"/>

    </p>
    <p id="u1">

        <label><font color="#dc143c">*</font>服务类型</label>
        <input id="u1_input" type="radio" value="1" name="u1" ><span>报修故障</span>
        <input id="u2_input" type="radio" value="2" name="u1" ><span>锅炉保养</span>
        <input id="u3_input" type="radio" value="46" name="u1" ><span>地暖清洗</span>
        <input id="u4_input" type="radio" value="47" name="u1" ><span>安全检查</span>
        <input id="u5_input" type="radio" value="48" name="u1" ><span>以旧换新</span>

        
    </p>

     <p>


        <label><font color="#dc143c">*</font>故障描述</label>

        <textarea rows="10" cols="50" id="fail_cause"></textarea>

    </p>
    <p>
        <label style="width: 200px;">　　</label>
        <input type="submit" id="btn_sumit" class="btn_submit" value="提　交" />
    </p>
</div>
<script type="text/javascript">

    //弹出列表框
    $("#code").click(function () {
        $("#div_items").css('display', 'block');
        return false;
    });

    //隐藏列表框
    $("body").click(function () {
        $("#div_items").css('display', 'none');
    });

    //移入移出效果
    $(".div_item").hover(function () {
        $(this).css('background-color', '#1C86EE').css('color', 'white');
    }, function () {
        $(this).css('background-color', 'white').css('color', 'black');
    });

    //文本框输入
    $("#code").keyup(function () {
        $("#div_items").css('display', 'block');//只要输入就显示列表框

        if ($("#code").val().length <= 0) {
            $(".div_item").css('display', 'block');//如果什么都没填，跳出，保持全部显示状态
            return;
        }

        $(".div_item").css('display', 'none');//如果填了，先将所有的选项隐藏

        for (var i = 0; i < $(".div_item").length; i++) {
            //模糊匹配，将所有匹配项显示
            if ($(".div_item").eq(i).text().substr(0, $("#txt1").val().length) == $("#txt1").val()) {
                $(".div_item").eq(i).css('display', 'block');
            }
        }
    });

    //项点击
    $(".div_item").click(function () {
        $("#code").val($(this).text());
    });

</script>
</body>
</html>