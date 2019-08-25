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
$id=$_GET['id'];
$community_info=Community::getInfoById($id);
$brand=Dict::getListByPid(1,1);
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
            if($("#outtime").prop("checked")){
                $('#starttime').attr("disabled",true);
            }else{
                $('#starttime').attr("disabled",false);
            }
            $("#address_one").change(function () {
                var value = $(this).val();
                var type=0;
                $("#address_two").empty();
                $("#address_three").empty();
                $("#address_three").fadeOut();
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
                        } else {
                            //alert('dasd');
                            $("#address_two").fadeIn("slow");
                            $("#address_two").append('<option value="0">请选择市</option>');
                            for (var i = 0; arr.length; i++) {
                                var option = '<option value="' + arr[i].id + '">' + arr[i].name + '</option>';
                                $("#address_two").append(option);
                            }
                            $("#address_three").fadeOut();
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
                        } else {
                            $("#address_three").fadeIn("slow");
                            $("#address_three").append('<option value="0">请选择区</option>');
                            for (var i = 0; arr.length; i++) {
                                var option = '<option value="' + arr[i].id + '">' + arr[i].name + '</option>';
                                $("#address_three").append(option);
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
                var item_array=new Array();
                $('input[name="brand"]:checked').each(function(){
                    item_array.push($(this).val());//向数组中添加元素
                });
                var itemstr=item_array.join(',');//将数组元素连接起来以构建一个字符串

                var brand = itemstr;

                var name = $("#name").val();
                var province=$("#address_one").val();
                var city=$("#address_two").val();
                var area=$("#address_three").val();

                var period="";
                var ischoose=$("#outtime").prop("checked");
                if(ischoose){
                    period="过保";
                }else{
                    period=$("#starttime").val();
                }

                var province_name=$("#address_one").find("option:selected").text();
                var city_name=$("#address_two").find("option:selected").text();
                var area_name=$("#address_three").find("option:selected").text();
                if(name == ''){
                    layer.tips('小区名不能为空', '#name');
                    return false;
                }
                if(brand == '' || brand == 'undefined' || brand == null){
                    layer.tips('品牌不能为空', '#brand');
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

                $.ajax({
                    type: 'post',
                    url: 'weixin_community_do.php?act=edit',
                    data: {
                        id:id,
                        brand: brand,
                        name:name,
                        province_id: province,
                        city_id: city,
                        area_id:area,
                        period:period,
                        province_name: province_name,
                        city_name: city_name,
                        area_name:area_name
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
            $("#outtime").change(function() {
                var ischoose=$("#outtime").prop("checked");
                if(ischoose){
                    $('#starttime').attr("disabled",true);
                }else{
                    $('#starttime').attr("disabled",false);
                }
            });
        });

    </script>
</head>
<body>
<div id="formlist">

    <br><br><br>
    <p>

        <label><font color="#dc143c">*</font>小区</label>
        <input type="text" class="text-input input-length-30" name="name" id="name" value="<?php echo $community_info['name'];?>"/>

    </p>
    <p>
        <label><font color="#dc143c">*</font>品牌</label>
        <?php
        $attrs=array();
        if(strstr($community_info['brand'],",")){
            $attrs=explode(",",$community_info['brand']);
        }else{
            $attrs[0]=$community_info['brand'];
        }
        $checked="checked";
        if($brand)
            foreach($brand as $thisValue){
                if(in_array($thisValue['id'],$attrs))
                    echo '<input type="checkBox" name="brand" id="brand" value="'.$thisValue['id'].'" '.$checked.'/>'.$thisValue['name'];
                else
                    echo '<input type="checkBox" name="brand" id="brand" value="'.$thisValue['id'].'" />'.$thisValue['name'];
            }
        ?>
    </p>
    <p>
        <label><font color="#dc143c">*</font>地址</label>
        <span>

			<select  id="address_one" class="select-handle">
                <?php
                $select="selected";
                $rows = District::getAddressType(3,0);
                if(!empty($rows)) {
                    foreach (array_reverse($rows) as $row) {
                        $oneId = $row['id'];
                        $name = $row['name'];
                        if($row['id']==$community_info['provice_id'])
                            echo '<option value="' . $oneId . '" '.$select.' >' . $name . '</option>';
                        else
                            echo '<option value="' . $oneId . '" >' . $name . '</option>';
                    }
                }
                ?>
            </select>
            <select   id="address_two" class="select-handle">
                 <?php
                 $select_two="selected";
                 $rows = District::getAddressType(0,$community_info['provice_id']);
                 if(!empty($rows)) {
                     foreach (array_reverse($rows) as $row) {
                         $oneId = $row['id'];
                         $name = $row['name'];
                         if($row['id']==$community_info['city_id'])
                             echo '<option value="' . $oneId . '" '.$select_two.' >' . $name . '</option>';
                         else
                             echo '<option value="' . $oneId . '" >' . $name . '</option>';
                     }
                 }
                 ?>
            </select>
            <select    id="address_three" class="select-handle">
               <?php
               $select_three="selected";
               $rows = District::getAddressType(0,$community_info['city_id']);
               if(!empty($rows)) {
                   foreach (array_reverse($rows) as $row) {
                       $oneId = $row['id'];
                       $name = $row['name'];
                       if($row['id']==$community_info['area_id'])
                           echo '<option value="' . $oneId . '" '.$select_three.' >' . $name . '</option>';
                       else
                           echo '<option value="' . $oneId . '" >' . $name . '</option>';
                   }
               }
               ?>
            </select>
		</span>

    </p>
    <p>
        <label style="padding-top:5px"><font color="#dc143c"></font>质保期</label>
        <br>
        <input type="checkBox" name="outtime" id="outtime" value="过保" <?php if($community_info['period']=="过保") echo "checked"?>/>过保
        <br>
        <input type="text" class="laydate-icon input-length-30"  name="starttime" style="margin-top: 14px" id="starttime" value="<?php if($community_info['period']!="过保") echo date("Y-m-d",$community_info['period'])?>"/>
        <script type="text/javascript" src="laydate/laydate.js"></script>
        <script type="text/javascript">
            laydate.skin("molv");//设置皮肤
            var start = {
                elem: '#starttime',
                format: 'YYYY-MM-DD',
                istime: true,
                istoday: false,
                choose: function(datas){
                    end.min = datas; //开始日选好后，重置结束日的最小日期
                    end.start = datas; //将结束日的初始值设定为开始日
                }
            };
            laydate(start);
        </script>


    </p>
    <p>
        <label style="width: 200px;">　　</label>
        <input type="submit" id="btn_sumit" class="btn_submit" value="提　交" />
    </p>
</div>
</body>
</html>