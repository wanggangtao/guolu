<?php
/**
 * 修改水箱  water_box_edit.php
 *
 * @version       v0.01
 * @create time   2018/5/30
 * @update time
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

require_once('admin_init.php');
require_once('admincheck.php');
if(!isset($_GET['id']))
    die();

$id = safeCheck($_GET['id']);
$info = Water_box_attr::getInfoById($id);
if(empty($info))
    die();
$pInfo = Products::getInfoById($info['proid']);
if(empty($pInfo))
    die();
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
    <script type="text/javascript">
        $(function(){
            $('#btn_sumit').click(function(){

                var version = $('#version').val();
                var nominal_capacity = $('#nominal_capacity').val();
                var available_capacity = $('#available_capacity').val();
                var length = $('#length').val();
                var width = $('#width').val();
                var height = $('#height').val();
                var weight = $('#weight').val();
                var price = 0;
                var reg = /^(-?\d+)(\.\d+)?$/;
                if(version == ''){
                    layer.tips('型号不能为空', '#version');
                    return false;
                }
                if(nominal_capacity == ''){
                    layer.tips('公称容积不能为空', '#nominal_capacity');
                    return false;
                }
                if (!(reg.test(nominal_capacity))) {
                    layer.tips('公称容积应为数字', '#nominal_capacity');
                    return false;
                }
                if(available_capacity == ''){
                    layer.tips('有效容积不能为空', '#available_capacity');
                    return false;
                }
                if (!(reg.test(available_capacity))) {
                    layer.tips('有效容积应为数字', '#available_capacity');
                    return false;
                }
                if(length == ''){
                    layer.tips('箱体长不能为空', '#length');
                    return false;
                }
                if (!(reg.test(length))) {
                    layer.tips('箱体长应为数字', '#length');
                    return false;
                }
                if(width == ''){
                    layer.tips('箱体宽不能为空', '#width');
                    return false;
                }
                if (!(reg.test(width))) {
                    layer.tips('箱体宽应为数字', '#width');
                    return false;
                }
                if(height == ''){
                    layer.tips('箱体高不能为空', '#height');
                    return false;
                }
                if (!(reg.test(height))) {
                    layer.tips('箱体高应为数字', '#height');
                    return false;
                }
                if(weight == ''){
                    layer.tips('重量不能为空', '#weight');
                    return false;
                }
                if (!(reg.test(weight))) {
                    layer.tips('重量应为数字', '#weight');
                    return false;
                }
                /* if(price != ''){
                    if (!(reg.test(price))) {
                        layer.tips('价格应为数字', '#price');
                        return false;
                    }
                }else
                    price = 0; */
                $.ajax({
                    type        : 'POST',
                    data        : {
                        id : <?php echo $id;?>,
                        pid  : <?php echo $info['proid'];?>,
                        version : version,
                        nominal_capacity  : nominal_capacity,
                        available_capacity  : available_capacity,
                        length  : length,
                        width  : width,
                        height  : height,
                        weight : weight,
                        price  : price
                    },
                    dataType :    'json',
                    url :         'water_box_do.php?act=edit',
                    success :     function(data){
                        var code = data.code;
                        var msg  = data.msg;
                        switch(code){
                            case 1:
                                layer.alert(msg, {icon: 6,shade: false}, function(index){
                                    parent.location.reload();
                                });
                                break;
                            default:
                                layer.alert(msg, {icon: 5});
                        }
                    }
                });
            });
        });
    </script>
</head>
<body>
<div id="formlist">
    <p>
        <label style="width: 140px;">型号</label>
        <input type="text" class="text-input input-length-20" name="version" id="version" value="<?php echo $info['version'];?>"/>
        <label style="width: 120px;">公称容积(m³)</label>
        <input type="text" class="text-input input-length-10" name="nominal_capacity" id="nominal_capacity" value="<?php echo $info['nominal_capacity'];?>"/>
    </p>
    <p>
        <label style="width: 140px;">有效容积(m³)</label>
        <input type="text" class="text-input input-length-10" name="available_capacity" id="available_capacity" value="<?php echo $info['available_capacity'];?>"/>
        <label style="width: 230px;">箱体长(mm)</label>
        <input type="text" class="text-input input-length-10" name="length" id="length" value="<?php echo $info['length'];?>"/>
    </p>
    <p>
        <label style="width: 140px;">箱体宽(mm)</label>
        <input type="text" class="text-input input-length-10" name="width" id="width" value="<?php echo $info['width'];?>"/>
        <label style="width: 230px;">箱体高(mm)</label>
        <input type="text" class="text-input input-length-10" name="height" id="height" value="<?php echo $info['height'];?>"/>
    </p>
    <p>
        <label style="width: 140px;">重量(kg)</label>
        <input type="text" class="text-input input-length-10" name="weight" id="weight" value="<?php echo $info['weight'];?>"/>
        <!-- <label style="width: 230px;">价格(元)</label>
        <input type="text" class="text-input input-length-10" name="price" id="price" value="<?php echo $pInfo['price']?$pInfo['price']:'';?>"/> -->
    </p>

    <p>
        <label style="width: 250px;">　　</label>
        <input type="submit" id="btn_sumit" class="btn_submit" value="确　定" />
    </p>
</div>
</body>
</html>