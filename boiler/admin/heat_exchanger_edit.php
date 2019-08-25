<?php
/**
 * 修改换热器  heat_exchanger_edit.php
 *
 * @version       v0.01
 * @create time   2018/5/28
 * @update time
 * @author        wzp
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

require_once('admin_init.php');
require_once('admincheck.php');

if(!isset($_GET['id']))
    die();

$id = safeCheck($_GET['id']);
$info = Heat_exchanger_attr::getInfoById($id);
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
                var vender = $('#vender').val();
                var heat_surface = $('#heat_surface').val();
                /*var first_r = $('#first_r').val();
                var second_r = $('#second_r').val();
                var length = $('#length').val();
                var width = $('#width').val();
                var height = $('#height').val();*/
                var weight = $('#weight').val();
                var price = 0;

                var reg = /^(-?\d+)(\.\d+)?$/;
                if(version == ''){
                    layer.tips('型号不能为空', '#version');
                    return false;
                }
                if(vender == ''){
                    layer.tips('厂家不能为空', '#vender');
                    return false;
                }
                if (!(reg.test(vender))) {
                    layer.tips('厂家应为数字', '#vender');
                    return false;
                }
                if(heat_surface == ''){
                    layer.tips('换热面积不能为空', '#heat_surface');
                    return false;
                }
                if (!(reg.test(heat_surface))) {
                    layer.tips('换热面积应为数字', '#heat_surface');
                    return false;
                }
                /*if(first_r == ''){
                    layer.tips('一次侧进出水管径不能为空', '#first_r');
                    return false;
                }
                if (!(reg.test(first_r))) {
                    layer.tips('一次侧进出水管径应为数字', '#first_r');
                    return false;
                }
                if(second_r == ''){
                    layer.tips('二次侧进出水管径不能为空', '#second_r');
                    return false;
                }
                if (!(reg.test(second_r))) {
                    layer.tips('二次侧进出水管径应为数字', '#second_r');
                    return false;
                }
                if(length == ''){
                    layer.tips('长不能为空', '#length');
                    return false;
                }
                if (!(reg.test(length))) {
                    layer.tips('长应为数字', '#length');
                    return false;
                }
                if(width == ''){
                    layer.tips('宽不能为空', '#width');
                    return false;
                }
                if (!(reg.test(width))) {
                    layer.tips('宽应为数字', '#width');
                    return false;
                }
                if(height == ''){
                    layer.tips('高不能为空', '#height');
                    return false;
                }
                if (!(reg.test(height))) {
                    layer.tips('高应为数字', '#height');
                    return false;
                }*/
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
                        vender  : vender,
                        heat_surface  : heat_surface,
                        weight  : weight,
                        price  : price
                    },
                    dataType :    'json',
                    url :         'heat_exchanger_do.php?act=edit',
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
        <input type="text" class="text-input input-length-30" name="version" id="version" value="<?php echo $info['version'];?>"/>
        <label style="width: 140px;">厂家</label>
        <select name="vender" class="select-option" id="vender">
            <?php
            $list = Dict::getListByParentid(34);
            if($list)
                foreach($list as $thisValue){
                    $isselect = '';
                    if($thisValue['id'] == $info['vender'])
                        $isselect = 'selected';
                    echo '<option value="'.$thisValue['id'].'" '.$isselect.'>'.$thisValue['name'].'</option>';
                }
            ?>
        </select>
    </p>
    <!--<p>
        <label style="width: 140px;">一次侧进出水管径(m)</label>
        <input type="text" class="text-input input-length-10" name="first_r" id="first_r" value="<?php /*echo $info['first_r'];*/?>"/>
        <label style="width: 140px;">二次侧进出水管径(m)</label>
        <input type="text" class="text-input input-length-10" name="second_r" id="second_r" value="<?php /*echo $info['second_r'];*/?>"/>
        <label style="width: 140px;">长(m)</label>
        <input type="text" class="text-input input-length-10" name="length" id="length" value="<?php /*echo $info['length'];*/?>"/>
    </p>-->
    <p>
        <!--<label style="width: 140px;">宽(m)</label>
        <input type="text" class="text-input input-length-10" name="width" id="width" value="<?php /*echo $info['width'];*/?>"/>
        <label style="width: 140px;">高(m)</label>
        <input type="text" class="text-input input-length-10" name="height" id="height" value="<?php /*echo $info['height'];*/?>"/>-->
        <label style="width: 140px;">换热面积(㎡)</label>
        <input type="text" class="text-input input-length-10" name="heat_surface" id="heat_surface" value="<?php echo $info['heat_surface'];?>"/>
        <label style="width: 140px;">重量(kg)</label>
        <input type="text" class="text-input input-length-10" name="weight" id="weight" value="<?php echo $info['weight'];?>"/>
        <!-- <label style="width: 140px;">价格(元)</label>
        <input type="text" class="text-input input-length-10" name="price" id="price" value="<?php echo $pInfo['price']?$pInfo['price']:'';?>"/> -->
    </p>
    <p>
        <label style="width: 350px;">　　</label>
        <input type="submit" id="btn_sumit" class="btn_submit" value="确　定" />
    </p>
</div>
</body>
</html>