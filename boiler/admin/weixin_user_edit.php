<?php
/**
 * 用户修改  user_edit.php
 *
 * @version       v0.01
 * @create time   2019/3/17
 * @update time
 * @author        GuanXin
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

require_once('admin_init.php');
require_once('admincheck.php');

$id=$_GET['id'];
$user_info=User_account::getInfoById($id);
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
                            //alert('dasd');
                            $("#address_two").fadeIn("slow");
                            $("#address_two").append('<option value="0">请选择市</option>');
                            for (var i = 0; arr.length; i++) {
                                var option = '<option value="' + arr[i].id + '">' + arr[i].name + '</option>';
                                $("#address_two").append(option);
                            }
                            $("#address_three").fadeOut();
                            $("#address_four").fadeOut();
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
//                            $("#address_three").fadeOut("slow");
                            $("#address_three").fadeIn("slow");
                            $("#address_three").append('<option value="0">请选择区</option>');
                            $("#address_four").fadeIn("slow");
                            $("#address_four").append('<option value="0">请选择小区</option>');

                        } else {
                            $("#address_three").fadeIn("slow");
                            $("#address_three").append('<option value="0">请选择区</option>');
                            for (var i = 0; arr.length; i++) {
                                var option = '<option value="' + arr[i].id + '">' + arr[i].name + '</option>';
                                $("#address_three").append(option);
                            }
                            $("#address_four").fadeOut();
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
                            $("#address_four").fadeIn("slow");
                            $("#address_four").append('<option value="-1">其他</option>');
                        } else {
                            $("#address_four").fadeIn("slow");
                            $("#address_four").append('<option value="-1">其他</option>');
                            for (var i = 0; arr.length; i++) {
                                var option = '<option value="' + arr[i].id + '">' + arr[i].name + '</option>';
                                $("#address_four").append(option);
                            }
                        }
                    },
                    error: function () {
                        alert("请求失败");
                    }
                });
            });
            $("#btn_sumit").click(function () {

                var id=<?php echo $id;?>;
                var name = $("#name").val();
                var phone = $("#phone").val();
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
                if(name == ''){
                    layer.tips('联系人不能为空', '#name');
                    return false;
                }
                if(phone == ''){
                    layer.tips('注册电话不能为空', '#phone');
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
                $.ajax({
                    type: 'post',
                    url: 'weixin_user_do.php?act=edit',
                    data: {
                        id:id,
                        name:name,
                        phone:phone,
                        code:code,
                        province_id: province,
                        city_id: city,
                        area_id:area,
                        address:address,
                        contact_address:contact_address,
                        community_id: community

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
                        url: 'weixin_user_do.php?act=code_info',
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
                                    $("#address_four").empty();
                                    $("#address_one").append('<option value="'+msg.province_id+'" selected>'+msg.province_name+'</option>');;
                                    $("#address_two").append('<option value="'+msg.city_id+'" selected>'+msg.city_name+'</option>');;
                                    $("#address_three").append('<option value="'+msg.area_id+'" selected>'+msg.area_name+'</option>');;
                                    $("#address_four").append('<option value="'+msg.community_id+'" selected>'+msg.community_name+'</option>');;
                                    $("#address").val(msg.detail_addres);

                                    break;
                                default :

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
        });

    </script>
</head>
<body>
<div id="formlist">

    <br><br><br>
    <p>

        <label><font color="#dc143c">*</font>条码</label>
        <input type="text" class="text-input input-length-30" name="code" value="<?php echo $user_info['product_code'];?>" id="code"/>

    </p>
    <p>
        <label><font color="#dc143c">*</font>地址</label>
        <span>

			<select  id="address_one" class="select-handle">
                <option value="0">请选择省份</option>
                <?php
                $rows = District::getAddressType(3,0);
                if(!empty($rows)) {
                    $select_brand="selected";
                    foreach (array_reverse($rows) as $row) {
                        $oneId = $row['id'];
                        $name = $row['name'];
                        if($row['id'] == $user_info['province_id']) {
                            echo '<option value="' . $row['id'] . '" ' . $select_brand . '>' . $row['name'] . '</option>';
                        }
                        else
                            echo '<option value="' . $oneId . '">' . $name . '</option>';
                    }
                }
                ?>
            </select>
            <select    id="address_two" class="select-handle">
                <option value="0">请选择市</option>
                <?php
                $rows = District::getAddressType(0, $user_info['province_id']);
                if(!empty($rows)) {
                    $select_brand="selected";
                    foreach (array_reverse($rows) as $row) {
                        $oneId = $row['id'];
                        $name = $row['name'];
                        if($row['id'] == $user_info['city_id']) {
                            echo '<option value="' . $row['id'] . '" ' . $select_brand . '>' . $row['name'] . '</option>';
                        }
                        else
                            echo '<option value="' . $oneId . '">' . $name . '</option>';
                    }
                }
                ?>
            </select>
            <select    id="address_three" class="select-handle">
                <option value="0">请选择区</option>
                <?php
                $rows = District::getAddressType(0, $user_info['city_id']);
                if(!empty($rows)) {
                    $select_brand="selected";
                    foreach (array_reverse($rows) as $row) {
                        $oneId = $row['id'];
                        $name = $row['name'];
                        if($row['id'] == $user_info['area_id']) {
                            echo '<option value="' . $row['id'] . '" ' . $select_brand . '>' . $row['name'] . '</option>';
                        }
                        else
                            echo '<option value="' . $oneId . '">' . $name . '</option>';
                    }
                }
                ?>
            </select>
             <select    id="address_four" class="select-handle">
                <option value="0">请选择小区</option>
                 <?php
                 $params['area_id'] = $user_info['area_id'];
                 if($user_info['community_id'] == -1)
                 {
                     echo '<option value="-1" selected="selected">其他</option>';
                 }
                 $rows = Community::getList($params);
                 if(!empty($rows)) {
                     $select_brand="selected";
                     foreach (array_reverse($rows) as $row) {
                         $oneId = $row['id'];
                         $name = $row['name'];
                         if($row['id'] == $user_info['community_id']) {
                             echo '<option value="' . $row['id'] . '" ' . $select_brand . '>' . $row['name'] . '</option>';
                         }
                         else
                             echo '<option value="' . $oneId . '">' . $name . '</option>';
                     }
                 }
                 ?>
            </select>
		</span>

    </p>
    <p >
        <label><font color="#dc143c">*</font>详细地址</label>

        <textarea rows="10" cols="50" id="address"><?php echo $user_info['detail_addres'];?></textarea>
        <!--        <input type="text" class="text-input input-length-30" name="code" id="code"/>-->

    </p>
    <p>

        <label><font color="#dc143c">*</font>联系人</label>
        <input type="text" class="text-input input-length-30" name="name" value="<?php echo $user_info['name'];?>" id="name"/>

    </p>
    <p>

        <label><font color="#dc143c">*</font>注册电话</label>
        <input type="text" class="text-input input-length-30" name="phone" value="<?php echo $user_info['phone'];?>" id="phone"/>

    </p>

    <p>
        <label style="width: 200px;">　　</label>
        <input type="submit" id="btn_sumit" class="btn_submit" value="提　交" />
    </p>
</div>
</body>
</html>