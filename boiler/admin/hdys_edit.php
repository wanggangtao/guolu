<?php
/**
 * 修改全自动软水器  hdys_edit.php
 *
 * @version       v0.01
 * @update time
 * @author        wzp
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

require_once('admin_init.php');
require_once('admincheck.php');

if(!isset($_GET['id']))
    die();

$id = safeCheck($_GET['id']);
$info = Hdys_attr::getInfoById($id);
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
                var outwater = $('#outwater').val();
                //var weight = $('#weight').val();
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
                if(outwater == ''){
                    layer.tips('额定出水量不能为空', '#outwater');
                    return false;
                }
                if (!(reg.test(outwater))) {
                    layer.tips('额定出水量应为数字', '#outwater');
                    return false;
                }
                /*if(weight == ''){
                    layer.tips('重量不能为空', '#weight');
                    return false;
                }
                if (!(reg.test(weight))) {
                    layer.tips('重量应为数字', '#weight');
                    return false;
                }
                if(price != ''){
                    if (!(reg.test(price))) {
                        layer.tips('价格应为数字', '#price');
                        return false;
                    }
                }else
                    price = 0;*/
                $.ajax({
                    type        : 'POST',
                    data        : {
                        id : <?php echo $id;?>,
                        pid  : <?php echo $info['proid'];?>,
                        version : version,
                        vender  : vender,
                        outwater  : outwater,
                        price  : price
                    },
                    dataType :    'json',
                    url :         'hdys_do.php?act=edit',
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
        <label style="width: 130px;">型号</label>
        <input type="text" class="text-input input-length-50" name="version" id="version" value="<?php echo $info['version'];?>"/>
    </p>
    <p>
        <label style="width: 130px;">厂家</label>
        <select name="vender" class="select-option" id="vender">
            <?php
            $list = Dict::getListByParentid(31);
            if($list)
                foreach($list as $thisValue){
                    $isselect = '';
                    if($thisValue['id'] == $info['vender'])
                        $isselect = 'selected';
                    echo '<option value="'.$thisValue['id'].'" '.$isselect.'>'.$thisValue['name'].'</option>';
                }
            ?>
        </select>
        <label style="width: 130px;">额定出水量(m³/h)</label>
        <input type="text" class="text-input input-length-10" name="outwater" id="outwater" value="<?php echo $info['outwater'];?>"/>
    </p>
    <p style="display: none;">
        <!--<label style="width: 130px;">重量(kg)</label>
        <input type="text" class="text-input input-length-10" name="weight" id="weight" value="<?php /*//echo $info['weight'];*/?>"/>-->
        <label style="width: 100px;">价格(元)</label>
        <input type="text" class="text-input input-length-10" name="price" id="price" value="<?php echo $pInfo['price']?$pInfo['price']:'';?>"/>
    </p>
    <p>
        <label style="width: 200px;">　　</label>
        <input type="submit" id="btn_sumit" class="btn_submit" value="确　定" />
    </p>
</div>
</body>
</html>