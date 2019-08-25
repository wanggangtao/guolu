<?php
/**
 * 添加锅炉  guolu_add.php
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
$info = Smallguolu::getInfoById($id);
if(empty($info))
    die();

$pInfo = Products::getInfoById($info['proid']);
$deatail_video = $pInfo['detail_video'];
$videoarr  = explode(';',$deatail_video);
$videopath = $videoarr[0];
$placehold = $videoarr[1];

//if(empty($pInfo))
//    die();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="author" content="芝麻开花 http://www.zhimawork.com" />
    <link rel="stylesheet" href="css/style.css" type="text/css" />
    <link rel="stylesheet" href="css/form.css" type="text/css" />
    <link rel="stylesheet" href="css/lunbo.css" type="text/css" />
    <script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="js/layer/layer.js"></script>
    <script type="text/javascript" src="js/common.js"></script>
    <script type="text/javascript" src="js/upload.js"></script>
    <script src="ckeditor/ckeditor.js"></script>
    <script type="text/javascript">
        $(function(){


            var wxckeditor = CKEDITOR.replace('wxdesc',{
                toolbar : 'Common',
                forcePasteAsPlainText : 'true',//强制纯文本格式粘贴
                filebrowserImageUploadUrl : 'ckeditor_upload.php?type=img',
                filebrowserFlashUploadUrl : 'ckeditor_upload.php?type=flash',
                filebrowserUploadUrl : 'ckeditor_upload.php?type=file'
            });
            var uploadIndex;
            $(document).off('change','#placehold_input').on('change','#placehold_input',function (e) {
                uploadIndex = layer.load(1);
                ajaxUploadVideoPalcehold($(this));
            });
            $('#placehold_input_click').click(function () {

                $('#placehold_input').val('');
                $('#placehold_input').trigger('click');
            });
            function ajaxUploadVideoPalcehold($fileVideo){
                if($fileVideo.val() == ''){
                    layer.tips('请选择文件', $fileVideo, {tips: 3});
                    return false;
                }
                var uploadUrl = 'all_upload.php?type=logoimg';//处理文件
                $.ajaxFileUpload({
                    url           : uploadUrl,
                    fileElementId : 'placehold_input',
                    dataType      : 'json',
                    success       : function(data){
                        var code = data.code;
                        var msg  = data.msg;
                        switch(code){
                            case 1:
                                layer.close(uploadIndex);
                                layer.msg('上传成功');
                                $('#img1').val(msg);
                                var img = placeCreate(msg);
                                $('#placehold_input_click').before(img);
                                // if($parent.find('div').length == 5){
                                $('#placehold_input_click').hide();
                                // }
                                break;
                            default:
                                layer.close(uploadIndex);
                                layer.alert(msg, {icon: 5});
                        }
                    },
                    error : function(data, status, e){
                        layer.alert(e);
                    }
                });
            }

            function placeCreate($src){
                var imageDiv = '<div id="detail_div"><img id="detail_placehold" class="detail_place" src="../';
                imageDiv +=$src;
                imageDiv += '"/><img class="detail_del" id="del_place" src="../admin/images/dot_del.png"/>';
                imageDiv +='<i class="drag"></i></div>';
                return imageDiv;
            }

            $(document).on('click','#del_place',function () {
                $parent = $($(this).parents()[0]);
                if($($parent.parents()[0]).find('div').length > 1){
                    $('#placehold_input_click').show();
                }
                $('#placehold_input').val('');
                $('#img1').val('');

                $parent.remove();
            });

            $('#btn_sumit').click(function(){

                var version = $('#version').val();
                var vender = $('#vender').val();

                var heat_temperature = $('#heat_temperature').val();
                var live_temperature = $('#live_temperature').val();

                var thermal_efficiency = $('#thermal_efficiency').val();
                var power = $('#power').val();

                var efficiency_level = $('#efficiency_level').val();
                var size = $('#size').val();

                var weight = $('#weight').val();
                var protection_level = $('#protection_level').val();

                var img = $('#img1').val();
                var wxdesc = wxckeditor.getData();

                var detail_video = '';



                var reg = /(^[0-9]\d*$)/;

                var detail_imgs ='';


                if(version == ''){
                    layer.tips('型号不能为空', '#version');
                    $("#version").focus();
                    return false;
                }
                if(vender == ''){
                    layer.tips('厂家不能为空', '#vender');
                    $("#vender").focus();
                    return false;
                }



                if(power != ''){
                    if (!(reg.test(power))) {
                        layer.tips('额定功率应为正整数或0', '#power');
                        $("#power").focus();
                        return false;
                    }
                }

                if(weight != ''){
                    if (!(reg.test(weight))) {
                        layer.tips('净重应为正整数或0', '#weight');
                        $("#weight").focus();
                        return false;
                    }
                }


                $.ajax({
                    type        : 'POST',
                    data        : {
                        id : <?php echo $id;?>,
                        pid  : <?php echo $info['proid'];?>,
                        version : version,
                        vender  : vender,
                        heat_temperature  : heat_temperature,
                        live_temperature  : live_temperature,
                        power : power,
                        thermal_efficiency  : thermal_efficiency,
                        efficiency_level : efficiency_level,
                        size  : size,
                        weight : weight,
                        protection_level  : protection_level,
                        img  : img,
                        wxdesc:wxdesc,
                        detail_video  :  detail_video,
                        detail_imgs  :  detail_imgs,

                    },
                    dataType :    'json',
                    url :         'guolu_do.php?act=editsmall',
                    success :     function(data){
                        var code = data.code;
                        var msg  = data.msg;
                        switch(code){
                            case 1:
                                layer.alert(msg, {icon: 6,shade: false}, function(index){
                                    parent.location.href = "guolu_list.php?position=2";
                                });
                                break;
                            default:
                                layer.alert(msg, {icon: 5});
                        }
                    }
                });
            });
        });

        function ajaxUpload(value){
            if($('#file'+value).val() == ''){
                layer.tips('请选择文件', '#file'+value, {tips: 3});
                return false;
            }
            var uploadUrl = 'all_upload.php?type=guluimg';//处理文件
            $.ajaxFileUpload({
                url           : uploadUrl,
                fileElementId : 'file'+value,
                dataType      : 'json',
                success       : function(data){
                    var code = data.code;
                    var msg  = data.msg;
                    switch(code){
                        case 1:
                            $('#img'+ value).val(msg);
                            $('#val'+ value).html(msg);
                            layer.msg('上传成功');
                            break;
                        default:
                            layer.alert(msg, {icon: 5});
                    }
                },
                error: function (data, status, e){
                    layer.alert(e);
                }
            })
            return false;
        }
    </script>
</head>
<body>
<div id="formlist">

    <p>
        <label style="width: 200px;">型号</label>
        <input type="text" class="text-input input-length-50" name="version" id="version" value="<?php echo $info['version'];?>"/>
        <span class="warn-inline">* </span>
    </p>
    <p>
        <label style="width: 200px;">厂家</label>
        <select name="vender" class="select-option" id="vender">
            <?php
            $list = Dict::getListByCat(1,2);
            if($list)
                foreach($list as $thisValue){
                    $isselect = '';
                    if($thisValue['id'] == $info['vender'])
                        $isselect = 'selected';
                    echo '<option value="'.$thisValue['id'].'" '.$isselect.'>'.$thisValue['name'].'</option>';
                }
            ?>
        </select>
        <span class="warn-inline">* </span>
    </p>

    <p>
        <label style="width: 200px;">额定输出功率(KW)</label>
        <input type="text" class="text-input input-length-10" name="power" id="power" value="<?php echo $info['power'];?>"/>
        <span class="warn-inline">&nbsp;</span>
        <label style="width: 200px;">供暖热水温度调节范围(℃)</label>
        <input type="text" class="text-input input-length-10" name="heat_temperature" id="heat_temperature" value="<?php echo $info['heat_temperature'];?>"/>
        <span class="warn-inline">&nbsp;</span>
    </p>
    <p>
        <label style="width: 200px;">生活热水温度调节范围(℃)</label>
        <input type="text" class="text-input input-length-10" name="live_temperature" id="live_temperature" value="<?php echo $info['live_temperature'];?>"/>
        <span class="warn-inline">&nbsp;</span>
        <label style="width: 200px;">最高热效率(%)</label>
        <input type="text" class="text-input input-length-10" name="thermal_efficiency" id="thermal_efficiency" value="<?php echo $info['thermal_efficiency'];?>"/>
        <span class="warn-inline">&nbsp;</span>
    </p>
    <p>
        <label style="width: 200px;">中国能效标识等级</label>
        <input type="text" class="text-input input-length-10" name="efficiency_level" id="efficiency_level" value="<?php echo $info['efficiency_level'];?>"/>
        <span class="warn-inline">&nbsp;</span>
        <label style="width: 200px;">外型尺寸(mm)</label>
        <input type="text" class="text-input input-length-10" name="size" id="size" value="<?php echo $info['size'];?>"/>
        <span class="warn-inline">&nbsp;</span>
    </p>
    <p>
        <label style="width: 200px;">净重(KG)</label>
        <input type="text" class="text-input input-length-10" name="weight" id="weight" value="<?php echo $info['weight'];?>"/>
        <span class="warn-inline">&nbsp;</span>
        <label style="width: 200px;">防护等级</label>
        <input type="text" class="text-input input-length-10" name="protection_level" id="protection_level" value="<?php echo $info['protection_level'];?>"/>
        <span class="warn-inline">&nbsp;</span>
    </p>


    <input id="img1"  name="img1" type="hidden" value="<?php echo !empty($pInfo)?$pInfo['img']:'';?>"/>
    <div class="luobo_div" >
        <div class="luobo_lable_div">
            <label style="margin-left: 70%"><b>商品图片</b></label>
        </div>

        <div class="luobo_add_div">
            <?php
            $displayPlaceFlag = false;
            $placehold = $pInfo['img'];
            if(!empty($placehold)){ ?>
                <div id="detail_div"><img id="detail_placehold" class="detail_place" src="<?php echo $HTTP_PATH.$placehold; ?>" /><img class="detail_del" id="del_place" src="../admin/images/dot_del.png"/></div>
                <?php $displayPlaceFlag = true; }?>
            <input id="placehold_input" type="file" accept="image/*" style="display: none" name="placehold_input"/>
            <div id="placehold_input_click" class="input_click" <?php if($displayPlaceFlag) {echo 'style="display: none"';}?> ><img class="add_img" src="images/add_icon.png" ></div>
        </div>

    </div>

    <br/>
    <div class="tipDiv">
        <span style="color: #D5D5D5;font-size: 12px">建议图片大小375*300,最大不超过1M</span><br/>
    </div>
    <br/>


    <p>
        <label style="width: 200px;">产品说明</label>
    <div style="margin-top:40px;margin-left:10%">
        <textarea style="padding:5px;width:70%;height:70px;" name="wxdesc" cols=200 id="wxdesc" value=""><?php echo HTMLDecode(!empty($pInfo)?$pInfo['wxdesc']:''); ?></textarea>
    </div>
    </p>


    <p>
        <label style="width: 350px;">　　</label>
        <input type="submit" id="btn_sumit" class="btn_submit" value="确　定" />
    </p>
</div>
</body>
</html>