<?php
/**
 * Created by lyz.
 * Date: 2018/12/6
 * Time: 22:36
 */

require_once('admin_init.php');
require_once('admincheck.php');
$param=array();
$contact_info = Web_contactus::getList($param);

if (!empty($contact_info)){
    $contact_info=$contact_info[0];
}else{
    $contact_info=array();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="author" content="芝麻开花 http://www.zhimawork.com" />
    <link rel="stylesheet" href="css/style.css" type="text/css" />
    <link rel="stylesheet" href="css/form.css" type="text/css" />
    <link href="css/semantic.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="js/layer/layer.js"></script>
    <script type="text/javascript" src="js/common.js"></script>
    <script type="text/javascript" src="js/upload.js"></script>

    <script type="text/javascript"
            src="http://webapi.amap.com/maps?v=1.3&key=d7c5cdb73a595b9ee6556c08ff37abf9"></script>
    <script type="text/javascript">
        $(function () {
            $(document).on("click", "#searchXY", function () {

                var address = $('input[name="address"]').val();
                if(address.length==0){
                    layer.tips("请输入地址后重试","#address");
                    return false;
                }

                layer.open({
                    type: 2,
                    title: '检索地址信息',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['600px', '540px'],
                    content: 'address_search.php?address_detail='+address
                });

            });

            $('#btn_edit').click(function () {
                var id="<?php  if (!empty($contact_info)) echo $contact_info['id']; ?>";
                var address_posttion_currt="<?php echo $contact_info['lat'].','.$contact_info['lng'] ?>";
                var address_currt="<?php echo $contact_info['address'] ?>";

                var company = $('#company').val();
                var contacter = $('#contacter').val();

                var phone = $('#phone').val();
                var telephone = $('#telephone').val();

                var website = $('#website').val();
                var email = $('#email').val();
                var hotline = $('#hotline').val();
                var address = $('#address').val();
                var picurl1 = $('#picurl1').val();
                var picurl2 = $('#picurl2').val();


                var address_position = $('#address_position').val();
                if(picurl1==''){
                    layer.alert('请选择图片',{icon: 5,shade: false});
                    return false;
                }
                if(picurl2==''){
                    layer.alert('请选择图片',{icon: 5,shade: false});
                    return false;
                }
                if(address!=address_currt && address_position==address_posttion_currt){
                    layer.alert('请重新检索地址',{icon:5});
                    return false;
                }


                $.ajax({
                    type: 'POST',
                    data: {
                        id:id,
                        company: company,
                        contacter: contacter,
                        phone: phone,
                        telephone: telephone,
                        website: website,
                        email: email,
                        address: address,
                        hotline: hotline,

                        address_position :address_position,
                        picurl1   : picurl1,
                        picurl2   : picurl2
                    },
                    dataType: 'json',
                    url: 'contactus_do.php?act=edit',
                    success: function (data) {
                        var code = data.code;
                        var msg = data.msg;
                        switch (code) {
                            case 1:
                                layer.alert(msg, {icon: 6, shade: false}, function (index) {
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
        function ajaxUpload(value){
            if($('#upload_file'+value).val() == ''){
                layer.tips('请选择文件', '#file'+value, {tips: 3});
                return false;
            }
            var uploadUrl = 'all_upload.php?type=erweima&id='+ value;//处理文件
            $.ajaxFileUpload({
                url           : uploadUrl,
                fileElementId : 'upload_file'+value,
                dataType      : 'json',
                success       : function(data){
                    var code = data.code;
                    var msg  = data.msg;
                    switch(code){
                        case 1:
                            $('#picurl'+ value).val(msg);
                            $('#val'+ value).attr("src",'<?php echo $HTTP_PATH;?>' + msg);
                            layer.msg('上传成功');
                            break;
                        default:
                            layer.alert(msg, {icon: 5});
                    }
                },
                error: function (data, status, e){
                    layer.alert(e);
                }
            });
            return false;
        }

    </script>
</head>
<body>
<div id="formlist">
    <p>
        <label>公司名称：</label>
        <input type="text" class="text-input input-length-50"  name="company" id="company"  value="<?php
        if(!empty($contact_info['company'])){
            echo $contact_info['company'];
        }else{
            echo "";
        }
        ?>"/>
    </p>


    <p>
        <label>24小时服务热线：</label>
        <input type="text" class="text-input input-length-50"  name="hotline" id="hotline" value="<?php
        if(!empty($contact_info['hotline'])){
            echo $contact_info['hotline'];
        }else{
            echo "";
        }
        ?>"/>
    </p>

    <p>
        <label>联系人：</label>
        <input type="text" class="text-input input-length-50"  name="contacter" id="contacter" value="<?php
        if (!empty($contact_info['contacter'])){
            echo $contact_info['contacter'];
        }else{
            echo "";
        }
        ?>"/>
    </p>

    <p>
        <label>手机：</label>
        <input type="text" class="text-input input-length-50"  name="phone" id="phone" value="<?php
        if(!empty($contact_info['phone'])){
            echo $contact_info['phone'];
        }else{
            echo "";
        }
        ?>" />
    </p>

    <p>
        <label>电话：</label>
        <input type="text" class="text-input input-length-50"  name="telephone" id="telephone" value="<?php
        if(!empty($contact_info['telephone'])){
            echo $contact_info['telephone'];
        }else{
            echo "";
        }
        ?>"/>
    </p>

    <p>
        <label>网址：</label>
        <input type="text" class="text-input input-length-50"  name="website" id="website" value="<?php
        if(!empty($contact_info['website'])){
            echo  $contact_info['website'];
        }else{
            echo "";
        }

        ?>" />
    </p>

    <p>
        <label>邮箱：</label>
        <input type="text" class="text-input input-length-50"  name="email" id="email" value="<?php
        if(!empty($contact_info['email'])){
            echo $contact_info['email'];
        }else{
            echo "";
        }
        ?>"/>
    </p>


    <p>
        <label>地址：</label>
        <input type="text" class="text-input input-length-50"  name="address" id="address" value="<?php
        if(!empty($contact_info['address'])){
            echo $contact_info['address'];
        }else{
            echo "";
        }
        ?>" />
          <span style="padding-left:20px"><input type="button" class="btn-handle" href="javascript:" id="searchXY" value="检索坐标"/>
          </span>
    </p>


    <p>
        <label>坐标：</label>
        <input type="text" class="text-input input-length-50"  name="address_position" id="address_position" value="<?php
        if(!empty($contact_info['lat'])&& !empty($contact_info['lng'])){
            echo $contact_info['lat'].",".$contact_info['lng'];
        }else{
            echo "";
        }
        ?>"/>
    </p>

    <p>
        <label>图片上传</label>
        <input id="picurl1"  name="picurl1" type="hidden"  value="<?php echo $contact_info['picurl1'];?>" />
        <input id="upload_file1" class="upfile_btn" type="file" name="upload_file1" style="height:24px;"/>
        <input type="button" class="upfile_btn btn-handle" onclick="return ajaxUpload(1);" value="上传"/>
    </p>

    <p style="padding-left:150px;"><img id="val1" src="<?php echo $HTTP_PATH.$contact_info['picurl1'];?>" width="125px" height="125px" alt="" /></p>


    <p>
        <label>图片上传</label>
        <input id="picurl2"  name="picurl2" type="hidden"  value="<?php echo $contact_info['picurl2'];?>" />
        <input id="upload_file2" class="upfile_btn" type="file" name="upload_file2" style="height:24px;"/>
        <input type="button" class="upfile_btn btn-handle" onclick="return ajaxUpload(2);" value="上传"/>
    </p>

    <p style="padding-left:150px;"><img id="val2" src="<?php echo $HTTP_PATH.$contact_info['picurl2'];?>" width="125px" height="125px" alt="" /></p>
    <p>
        <label></label>
        <input type="submit" id="btn_edit" class="btn_submit" value="保 存" style="display:block;margin:0 auto"/>
    </p>
</div>
</body>
</html>