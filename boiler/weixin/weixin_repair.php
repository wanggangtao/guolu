<!DOCTYPE html>
<html lang="en">

<?php
/**
 * Created by PhpStorm.
 * User: ww
 * Date: 2019/3/17
 * Time: 22:04
 *
 * 个人信息
 */

require_once "admin_init.php";
$userOpenId = "";
if($isWeixin)
{
    $userOpenId = common::getOpenId();
}
//$userOpenId ="om86uuCRcTOSedWjSpdytUWmI-34";
$user_info = user_account::getInfoByOpenid($userOpenId);
$type = 1;
if(isset($_GET['type'])){
    $type = $_GET['type'];
}
//print_r($user_info['id']);

if(empty($user_info)){
    header("Location: weixin_login.php?type=".$type);
    exit();
}

//$user_info['id'] = 451;

$my_all_coupon = Weixin_user_coupon::getMyCouponInfo(array("uid" => $user_info['id']));

$have_coupon = 1;
if(empty($my_all_coupon)){
    $have_coupon = 0;

}else{
    foreach ($my_all_coupon as $key => $value){
        $my_all_coupon[$key]['starttime'] = date("Y.m.d",$value['starttime']);
        $my_all_coupon[$key]['endtime'] = date("Y.m.d",$value['endtime']);
    }
//    print_r($my_all_coupon);
}
?>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0, user-scalable=no"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta content="black" name="apple-mobile-web-app-status-bar-style">
    <meta content="telephone=no" name="format-detection">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title>一键预约</title>
    <link rel="stylesheet" href="static/weui/css/weui.min.css" />
    <link rel="stylesheet" href="static/weui/css/jquery-weui.min.css" />
    <link rel="stylesheet" href="static/css/basic.css" />
    <link rel="stylesheet" href="static/css/style.css" />
    <link rel="stylesheet" href="static/css/query.css" />
    <link rel="stylesheet" href="static/css/swiper.min.css">

    <link rel="stylesheet" type="text/css" href="static/css/mobileSelect.css">
    <script src="static/js/mobileSelect.js" type="text/javascript"></script>
    <script type="text/javascript" src="static/layer_mobile/layer.js"></script>
    <script src="static/js/jquery.min.js"></script>
    <script src="static/weui/js/jquery-weui.min.js"></script>
    <script src="static/js/swiper.min.js"></script>
    <script src="static/js/common.js"></script>

    <script src="static/js/query.js"></script>

    <script type="text/javascript">
        window.onload = function () {
                $("#type_id").attr("checked","checked");
                $("#type_id").change();
        }

    </script>
</head>

<body>
<div id="app">
    <div class="person-wrap">
        <div class="person-top">
            <div class="person-tip flex" style="margin-top: 0;">
                <img src="images/person-tip.png" alt="">
                <span>温馨提示：* 为必填项。</span>
            </div>
        </div>
        <div class="person-form">
            <form>
                <div class="form-item">
                    <div class="form-item-title flex">
                        <img src="images/person-star.png" alt="">
                        <span>联系电话</span>
                        <span class="repair-item-tip">（上门维修时您的联系电话）</span>
                    </div>
                    <div class="form-input flex">
                        <input  id = "linkPhone" name = "linkPhone" value="<?php echo $user_info['phone'];?>" type="text">
                    </div>
                </div>
                <div class="form-item">

                    <div class="form-item-title flex">
                        <img src="images/person-star.png" alt="">
                        <span>   <?php
                            $address = "地址：";
                            if(!empty($user_info['contact_address'])){
                                $address .= $user_info['contact_address'];
                            }else{
                                $address .= "暂无";
                            }
                            echo $address?> </span>
                    </div>
                </div>
                <div class="form-item">
                    <div class="form-item-tip flex">
                        <img src="images/person-tip.png" alt="">
                        <span>注：如果地址有误，请联系客服 400-966-5890</span>
                    </div>
                </div>
                <div class="form-item">
                    <div class="form-item-title flex">
                        <label style="white-space: nowrap;">
                            <img src="images/person-star.png" alt="">
                            <span>服务类型： </span>
                        </label>
                        <div style="display: flex;flex-wrap: wrap;justify-content:flex-start;line-height: 25px;width: 70%">
                            <?php
                        $service_type =  Service_type::getList();
                        foreach ($service_type as $item){
                            $thisid ="";
                            if($item['id'] == 1){
                                $thisid= "id = 'type_id'";
                            }
                            echo '<label style="display: flex;align-items: center;margin-right: 5px;"><input name="service_type" type="radio" value="'.$item['id'].'" '.$thisid.' />'.$item['name'].' </label>';

                        }
                            ?>
                        </div>    
                    </div>

                </div>

<!--                <div class="form-item" style="margin-top: 10px;">-->
<!--                    <div class="form-item-title flex" style="justify-content: space-between;">-->
<!--                        <span style="color: red">抵用券：</span>-->
<!--                        <div id = "is_use" style="color: red;text-align: right">-->
<!--                            <span id ="is_use_item">--><?php //if($have_coupon == 0){
//                                echo "暂无优惠券";
//                                }else{
//                                echo "不使用优惠券";
//                                }?><!--</span>-->
<!--                            <input type="hidden" id = "use_item_id" value="-1">-->
<!--                            <img src="static/images/arr-item.png" alt="">-->
<!--                        </div>-->
<!--                    </div>-->
<!---->
<!--                </div>-->
<!---->


                <div class="is_show"></div>

<input type="hidden" id = "str_id">
                <div class="from-submit" id="submit-order">
                    <input style="text-align: center" id="btn_submit" type="button" value="一键下单" >
                </div>
            </form>
        </div>
    </div>

    <div class="coupon" style="display: none"></div>

</div>
</body>

<script>
//
//    var mobileSelect1 = new MobileSelect({
//        trigger: '#is_use_item',
//        title: '优惠券',
//        wheels: [
//            {data: ["暂无"]}
//        ],
//        position: [0] //初始化定位
//    });
$(function () {
    $("#is_use").click(function () {

        var have_coupon = <?php echo $have_coupon?>;
        var uid = <?php echo $user_info['id']  ?>;
        var val = $('input:radio[name="service_type"]:checked').val();
        var my_all_coupon = <?php echo json_encode($my_all_coupon)?>;
        var now_coupon = $("#use_item_id").val();
//        alert(now_coupon);

            if(have_coupon == 1){

                $.ajax({
                    type: 'post',
                    url: 'weixin_my_coupon.php?act=all',
                    data: {
                        service_type:val,
                        uid: uid,
                    },
                    dataType: 'json',
                    success: function (data) {
                        var msg = data.msg;
                        //       alert(msg);

                        var content = '<div class="coupon_con">';
                        var no_use1 = '  <a class="coupon_item"> ' +
                            '<div class="coupon_item_top"> ' +
                            '<div class="coupon_item_center"> ' +
                            '<span style="align-self: center;right: -50px;position: relative;">不使用优惠券</span> ' +
                            '</div> ' +
                            '<div class="coupon_item_right"> ' +
                            '<label class="coupon_label" for="coupon_radio"> ';
                        var style_no_use = "";
                        var use = "";
                        var flag ;
                        var is_checked = "";
                        var no_use_checked = "";
                        var content_item = "";
                        var content_item_content;
                        if(now_coupon == -1){
                            no_use_checked = "checked";
                        }
                        var index_radio;
//                    console.log(my_all_coupon.length);

                        for(var i = 0 ; i < my_all_coupon.length ; i++){
//                        console.log(my_all_coupon[i]);
                            style_no_use = "";
                            use = "";
                            flag = 0;
                            is_checked ="";
                            content_item_content ="";
                            for(var j =0 ;j< msg.length ;j++){
                                if(my_all_coupon[i]['myid']== msg[j]){
                                    flag = 1;
                                    break;
                                }
                            }

                            if(my_all_coupon[i]['myid'] == now_coupon){
                                is_checked = "checked";
                            }

                            if(flag) {
                                index_radio = i + 100;

                                use =   '<div class="coupon_item_right">  <label class="coupon_label" for="coupon_radio' +
                                    index_radio +
                                    '"> ' +
                                    '<input value="' +
                                    my_all_coupon[i]['myid'] +
                                    '" myname = "' +
                                    my_all_coupon[i]['money'] +
                                    '元 ' +
                                    my_all_coupon[i]['name']+
                                    '" class="coupon_radio" type="radio" id="" name="coupon_radio"' +
                                    is_checked +
                                    '> <span></span> ' +
                                    '</label></div>';
                            }else{
                                style_no_use = 'coupon_item_used';

                            }

                            content_item_content =
                                ' <a class="coupon_item"> ' +
                                '<div class="coupon_item_top"> ' +
                                '<div class="coupon_item_left"> ' +
                                '<div class="coupon_item_name ' +
                                style_no_use +
                                '">小元服务</div> ' +
                                '</div> ' +
                                '<div class="coupon_item_center"> ' +
                                '<p class="coupon_item_title ' +
                                style_no_use +
                                '">小元壁挂炉服务</p> ' +
                                '<p class="coupon_item_text"> <span class="coupon_text_red ' +
                                style_no_use +
                                '">' +
                                my_all_coupon[i]['money'] +
                                '</span> <span  class="' +
                                style_no_use+
                                '">' +
                                my_all_coupon[i]['name'] +
                                '</span> </p> ' +
                                '</div> ' +
                                use +
                                '</div><div class="coupon_item_bottom">有效期：' +
                                my_all_coupon[i]['starttime'] +
                                '-' +
                                my_all_coupon[i]['endtime'] +
                                '</div> ' +
                                '</a>';

                            if(flag){
                                content_item = content_item_content + content_item ;
                            }else{
                                content_item = content_item +content_item_content;
                            }

                        }

                        content = content + content_item + no_use1;
                        content +=  '<input class="coupon_radio" value="-1" myname = "不使用优惠券" type="radio" id="coupon_radio4" name="coupon_radio" ' +
                            no_use_checked +
                            '> ' +
                            '<span></span> ' +
                            '</label> </div> </div> ' +
                            '</a>';
                        content += '</div>';

                        $(".coupon").html(content);

                        $(".person-wrap").css('display','none');
                        $(".coupon").css('display','block');
                    },
                    error : function (XMLHttpRequest, textStatus, errorThrown) {

                        layer.open({
                            content: "优惠券使用失败"
                            ,btn: '我知道了'
                        });
                    }

                });
            }


    })



})

$(document).on("click",'.coupon_item',function(){
//    console.log($(this).find("input").first());
    $(this).find("input").first().attr('checked','true');
    var val = $('input:radio[name="coupon_radio"]:checked').val();  //选中的优惠券的值
    var name = $('input:radio[name="coupon_radio"]:checked').attr("myname");

    $(".person-wrap").css('display','block');
    $(".coupon").css('display','none');

    $("#is_use_item").html(name);
    $("#use_item_id").val(val);


})


    $(document).ready(function() {

        $('input[type=radio][name=service_type]').change(function() {

            let $submitButton = document.getElementById('submit-order');
            var type = $(this).val();
            var id = <?php echo $user_info['id']?>

            var have_coupon = <?php echo $have_coupon?>;

            var use_item_name = "";
            if($(this).val() == 1){
                  $submitButton.style.margin = '40px auto 30px';
                  var code = '<div class="form-item"> ' +
                      '<div class="form-input flex"> ' +
                      '<textarea id = "result"  placeholder="请描述产品故障" ></textarea> ' +
                      '</div> ' +
                      '</div> ' +
                      '<div class="form-item"> ' +
                      '<div class="form-item-title flex"> ' +
                      '<img style="opacity: 0" src="images/person-star.png" alt=""> ' +
                      '<span>上传图片</span> ' +
                      '<span class="repair-item-tip">（最多上传3张图片）</span> ' +
                      '</div> ' +
                      '<div class="form-input-upload flex"> ' +
                      '<div class="upload flex" > ' +
                      '<div class="upload-item upload-first" > ' +
                      '<label id="label" for="file"> ' +
                      '<img src="static/images/btn-upload.png" alt=""> ' +
                      '</label> ' +
                      '<input id="file" multiple type="file"accept="image/*"style="display: none;"> ' +
                      '</div> ' +
                      '</div> ' +
                      '</div> ' +
                      '</div>';


              }else{
                  $submitButton.style.margin = '95px auto 30px';
                  code = "";
              }
            $(".is_show").html(code);

            $.ajax({
                type: 'post',
                url: 'weixin_my_coupon.php?act=one',
                data: {
                    service_type:type,
                    uid: id,
                },
                dataType: 'json',
                success: function (data) {
                    var msg = data.msg;
//                           alert(msg);
                    if(msg != ""){
                        $("#is_use_item").html(msg['money'] +"元 " +msg['name']);
                        $("#use_item_id").val(msg['myid']);
                    }else {

                        $("#is_use_item").html("不使用优惠券");
                    }

                },
                error : function (XMLHttpRequest, textStatus, errorThrown) {

                    layer.open({
                        content: "优惠券使用失败"
                        ,btn: '我知道了'
                    });
                }

            });




        });
       });

    $(document).on("change",'#file',function(e){
            var upload_W = $(".upload-item").width() - 13;
            console.log(upload_W);
            $(".upload-item").height(upload_W);
            var imgArr = [];
            var tmpl = '<div class="upload-item" style="height: '+upload_W+'px"><img src="#url#" alt=""><div class="image_close"></img><img src="static/images/input-close.png"><div></div>'
            var $uploaderFiles = $(".upload-first");    //图片列表

            var num =$(".upload").find('.upload-item').length;

            if(num >3){
                layer.open({
                    content: "最多上传3张图片"
                    ,btn: '我知道了'
                });
                return false;
            }
            var uploadIndex =   layer.open({type: 2});

            var src,url = window.URL || window.webkitURL || window.mozURL,files = e.target.files;
            //var file = files[0];
            var file = files[0];
            imgArr.push(file);
            var path = "<?php echo $HTTP_PATH?>";

            var fileObj = document.getElementById('file').files[0];


            var formFile = new FormData();
            formFile.append("file", fileObj); //加入文件对象

            var data = formFile;
            $.ajax({
                url: "upload_file.php",
                data: data,
                type: "Post",
                dataType: "json",
                cache: false,
                processData: false,
                contentType: false,
                success: function (result) {
                    layer.close(uploadIndex);

                    var code  = result.code;
                    var msg = result.msg;
                    var all_path = path + msg;
                    switch (code){
                        case 1:
                            $uploaderFiles.before($(tmpl.replace('#url#', all_path)));
                            layer.open({
                                content: "上传成功"
                                ,btn: '我知道了'
                            })
                            break;
                        default:
                            layer.open({
                                content: msg
                                ,btn: '我知道了'
                            });
                    }
                },
            })



    $("body").on("click",".image_close", function () {
        var index = $(this).parent().index();
        $(this).parent().remove();
        imgArr.splice(index,1);
        console.log(index,imgArr);
    })

})
    $(function () {

        $("#btn_submit").click(function () {
            var id = <?php echo $user_info['id']?>;
            var coupon_id =  $("#use_item_id").val();

            var server_type = $("input[name='service_type']:checked").val();

            var openId = "<?php echo $userOpenId?>";
            var linkPhone = $("#linkPhone").val();
            var isNumber = /^[0-9]+.?[0-9]*$/;

            if(linkPhone == ''){
                layer.open({
                    content: "维修电话不能为空"
                    ,btn: '我知道了'
                });
                return false;
            }else if(!isNumber.test(linkPhone) || linkPhone.length != 11){
                layer.open({
                    content: "维修电话格式不正确"
                    ,btn: '我知道了'
                });
                return false;
            }

            var result = $.trim($("#result").val());
//
//            if(result == ""){
//                layer.open({
//                    content: "故障原因不能为空"
//                    ,btn: '我知道了'
//                });
//                return false;
//            }
            var imgs =$(".upload").find('.upload-item').find('img');

            var urlStr = "";
            for(var i=0 ; i < imgs.length ; i++){
                if(imgs[i].src.indexOf('input-close.png') == -1 && imgs[i].src.indexOf('btn-upload.png') == -1){
                    urlStr += imgs[i].src.substring(imgs[i].src.indexOf('/userfiles'))+",";
                }
            }

            urlStr = urlStr.substring(0, urlStr.lastIndexOf(','));


            $.ajax({
                type: 'post',
                url: 'repair_do.php',
                data: {
                    openId:openId,
                    linkPhone: linkPhone,
                    result : result,
                    urlStr : urlStr,
//                    is_use_name :is_use_name,
                    server_type:server_type,
                    id:id,
                    coupon_id:coupon_id

                },
                dataType: 'json',
                success: function (data) {
                    var code = data.code;
                    var msg = data.msg;
                    switch (code){
                        case 1:
                            location.href = 'weixin_order_success.php';
                            break;
                        default:
                            layer.open({
                                content: msg
                                ,btn: '我知道了'
                            });
                    }
                },
                error : function (XMLHttpRequest, textStatus, errorThrown) {
//                    alert("dsad");
//                    // 状态码
//                    alert(XMLHttpRequest.status);
//                    // 状态
//                    alert(XMLHttpRequest.readyState);
                    // 错误信息
//                    alert(textStatus);

                    layer.open({
                        content: "提交失败，请重新提交"
                        ,btn: '我知道了'
                    });
                }

                });


        })

    })

$("input,textarea").on("blur",function(){
    setTimeout(function(){
        window.scrollTo(0,0);
    },100)
}).on('focus',function(){
    var clientHeight = document.documentElement.clientHeight || document.body.clientHeight;
    var offsetTop = $(this).offset().top - (clientHeight / 4);
    setTimeout(function(){
        window.scrollTo(0,offsetTop);
    },100)
})
</script>

</html>