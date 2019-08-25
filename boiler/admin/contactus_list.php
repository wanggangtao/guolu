<?php
/**
 * Created by lyz.
 * Date: 2018/12/6
 * Time: 21:18
 */require_once('admin_init.php');
require_once('admincheck.php');

$FLAG_TOPNAV	= "webcontent";
$FLAG_LEFTMENU  = 'contactus_list';

$param=array();
$contact_info=Web_contactus::getList($param);

if (!empty($contact_info)){
    $contact_info=$contact_info[0];
}else{
    $contact_info=array();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="微普科技 http://www.wiipu.com" />
    <title>前端内容管理 - 联系我们 - 管理系统 </title>
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
        $(function() {

            $('#btn_submit').click(function () {

                var company = $('#company').val();
                var contacter = $('#contacter').val();

                var phone = $('#phone').val();
                var telephone = $('#telephone').val();

                var website = $('#website').val();
                var email = $('#email').val();
                var hotline = $('#hotline').val();
                var address = $('#address').val();


                // if(address.length!=0){
                //     layer.tips("请检索坐标","#address");
                //     return false;
                // }
                var address_position = $('#address_position').val();

                $.ajax({
                    type: 'POST',
                    data: {
                        company: company,
                        contacter: contacter,
                        phone: phone,
                        telephone: telephone,
                        website: website,
                        email: email,
                        address: address,
                        hotline: hotline,

                        address_position :address_position,
                    },
                    dataType: 'json',
                    url: 'contactus_do.php?act=add',
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

            $('#btn_edit').click(function () {
                layer.open({
                    type: 2,
                    title: '修改联系我们',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['650px', '600px'],
                    content: "contactus_edit.php"
                });
            });

        });

    </script>
</head>
<body>
<div id="header">
    <?php include('top.inc.php');?>
    <?php include('nav.inc.php');?>
</div>
<div id="container">
    <?php include('content_menu.inc.php');?>
    <div id="maincontent" >
        <div id="position">当前位置：<a href="recruit_list.php">前端内容管理</a> &gt; 联系我们</div>
        <div id="formlist">

            <p>
                <label>公司名称：</label>
                <input type="text" class="text-input input-length-30" readonly name="company" id="company"  value="<?php
                if(!empty($contact_info['company'])){
                    echo $contact_info['company'];
                }else{
                    echo "";
                }
                ?>"/>
            </p>


            <p>
                <label>24小时服务热线：</label>
                <input type="text" class="text-input input-length-30" readonly name="hotline" id="hotline" value="<?php
                if(!empty($contact_info['hotline'])){
                    echo $contact_info['hotline'];
                }else{
                    echo "";
                }
                ?>"/>
            </p>

            <p>
                <label>联系人：</label>
                <input type="text" class="text-input input-length-30" readonly name="contacter" id="contacter" value="<?php
                if (!empty($contact_info['contacter'])){
                    echo $contact_info['contacter'];
                }else{
                    echo "";
                }
                ?>"/>
            </p>

            <p>
                <label>手机：</label>
                <input type="text" class="text-input input-length-30" readonly name="phone" id="phone" value="<?php
                if(!empty($contact_info['phone'])){
                    echo $contact_info['phone'];
                }else{
                    echo "";
                }
                ?>" />
            </p>

            <p>
                <label>电话：</label>
                <input type="text" class="text-input input-length-30" readonly name="telephone" id="telephone" value="<?php
                if(!empty($contact_info['telephone'])){
                    echo $contact_info['telephone'];
                }else{
                    echo "";
                }
                ?>"/>
            </p>

            <p>
                <label>网址：</label>
                <input type="text" class="text-input input-length-30" readonly name="website" id="website" value="<?php
                if(!empty($contact_info['website'])){
                    echo  $contact_info['website'];
                }else{
                    echo "";
                }

                ?>" />
            </p>

            <p>
                <label>邮箱：</label>
                <input type="text" class="text-input input-length-30" readonly name="email" id="email" value="<?php
                if(!empty($contact_info['email'])){
                    echo $contact_info['email'];
                }else{
                    echo "";
                }
                ?>"/>
            </p>


            <p>
                <label>地址：</label>
                <input type="text" class="text-input input-length-30" readonly name="address" id="address" value="<?php
                if(!empty($contact_info['address'])){
                    echo $contact_info['address'];
                }else{
                    echo "";
                }
                ?>" />
<!--                <span style="padding-left:20px"><input type="button" class="btn-handle" href="javascript:" id="searchXY" value="检索坐标"/>-->
<!--                </span>-->
            </p>


            <p>
                <label>坐标：</label>
                <input type="text" class="text-input input-length-30" readonly name="address_position" id="address_position" value="<?php
                if(!empty($contact_info['lat'])&& !empty($contact_info['lng'])){
                    echo $contact_info['lat'].",".$contact_info['lng'];
                }else{
                    echo "";
                }
                ?>"/>
            </p>

            <p>
                <label>二维码一：</label>
                <img src="<?php echo $HTTP_PATH.$contact_info['picurl1'];?>" width="150px" height="150px" alt="">
            </p>
            <p>
                <label>二维码二：</label>
                <img src="<?php echo $HTTP_PATH.$contact_info['picurl2'];?>" width="150px" height="150px" alt="">
            </p>


            <p>
                <label>　　</label>
                <?php
                if(empty($contact_info)){
                ?>
                <input type="submit" id="btn_submit" class="btn_submit" value="保　存" /><?php }
                else{
                ?>
                <input type="submit" id="btn_edit" class="btn_submit" value="修  改" /><?php  }?>

            </p>


        </div>
    <div>
    <div class="clear"></div>
</div>
<?php include('footer.inc.php');?>
</body>
</html>