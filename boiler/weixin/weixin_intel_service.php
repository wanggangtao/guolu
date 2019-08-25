<?php
require_once("admin_init.php");
$userOpenId = "";
if($isWeixin)
{
    $userOpenId = common::getOpenId();
}
$user_info = user_account::getInfoByOpenid($userOpenId);

$weixin = new weixin();

$personal_info = $weixin->getUserInfo($userOpenId);
$params=array();
$params["type"]=-1;
$first_name=Community::geFCtListByFC($params);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>西安元聚在线客服</title>
    <link type="text/css" href="css/common.css" rel="stylesheet"/>
    <link rel="stylesheet" href="css/results.css">
    <link rel="stylesheet" href="static/css/swiper.min.css">
    <script type="text/javascript" src="js/jquery-1.4.4.min.js"></script>
    <script type="text/javascript" src="js/adaptive.js"></script>
    <script type="text/javascript" src="js/jweixin-1.3.2.js"></script>
    <script type="text/javascript">
        window['adaptive'].desinWidth = 750;
        window['adaptive'].baseFont = 18;
        window['adaptive'].maxWidth = 480;
        window['adaptive'].init();
    </script>
    <script>
        $(function () {
            var AllHeight = $(window).height()+10;
            var DIvheight = parseInt($('.chat_7').css('height'));
            AllHeight = AllHeight - DIvheight+20;
            $('.chat_2').css('height', AllHeight + 'px');

        })
        $.ajax({
            type: 'POST',
            url: 'chat_do.php?act=getBegin',
            dataType: "json",
            success: function (data) {
                var code=data.code;
                var msg=data.msg;
                var html = '';
                html += '<div class="chat_5"><img src="images/kefu.png" class="chat_5_1"/>';
                html += ' <div class="chat_6"><div class="chat_6_1">机器人小元</div>';
                html += '  <div class="chat_6_2">' + msg + '</div>';
                html += ' <div class="clear"></div> </div>  <div class="clear"></div> </div>';
                $('#liaotian').append(html);
                $('#heiddenId').val(0);
                var id=  "<?php echo $userOpenId?>";
                getInfo(id);

            },
            error: function () {
                console.log('连接失败');
            }
        })



    </script>
</head>
<body class="chat_1">

<div class="chat_2" id="chat" >
    <div id="liaotian" class="chat_2_1">
    </div>

</div>
<br><br>
<div class="chat_7" id="text" style="z-index: 9999">
    <input class="chat_7_1" type="text" style="z-index: 9999"/>
    <button class="chat_7_2" id="sendbtn" style="z-index: 9999"></button>
    <input type="hidden" id="heiddenId"/>
    <input type="hidden" id="is_other" value="0">
    <input type="hidden" id="c_name" value="0">
</div>


<div class="equity-fund" id="community_div" style="display: none">
    <?php foreach ($first_name as $value){?>
        <div class="shares"><?php echo $value['community_first_charter'];?>
            <ul class="ef-content" id="community_list_<?php echo $value['community_first_charter'];?>">
            </ul>
        </div>
    <?php }?>
    <div class="shares">
        <ul class="ef-content" id="community_list">
        </ul>
    </div>
    <div class="shares">如果没有找到您居住的小区，请点击“其他”输入您的小区名称。
        <ul class="ef-content" id="community_list_other">
        </ul>
    </div>


</div>

<script type="text/javascript" src="js/layer/layer.js"></script>
<script>

    function  chagehei() {  //上滑的动画，在每次发送内容后运行。
        var hei1 = parseInt($('.chat_2').css('height'));

        var hei2 = parseInt($('#liaotian').css('height'));
        console.log("gaodu");
        console.log(hei1);
        console.log(hei2);
        if (hei1>hei2)
        {}
        else
        {
            var scrollTop = document.documentElement.scrollTop || window.pageYOffset || document.body.scrollTop;
//             var heicha = (hei1 -hei2) +'px';
//             console.log(heicha);
//             $('#liaotian').animate({
//
//             },500)
            ($('#liaotian').children(".chat_5:last-child")[0]).scrollIntoView();

        }
    }
    function getInfo(id){
        $.ajax({
            type        : 'POST',
            data        : {
                id  : id
            },
            dataType :    'json',
            url :         'chat_do.php?act=getInfo',
            success: function (data) {
                code = data.code;
                msg  = data.msg;
                console.log(code=='2');
                switch(parseInt(code)){
                    case 1:
                        console.log(msg);
                        var html3 = '<div class="chat_5"><img src="images/kefu.png" class="chat_5_1"/>\n' +
                            '<div class="chat_6"><div class="chat_6_1">机器人小元</div>\n' +
                            '<div class="chat_6_2">\n' +
                            '<div class="chat_8">\n' +
                            '<div class="chat_8_1">\n' +
                            '<img src="' + msg.picUrl + '" class="chat_8_2"/>\n' +
                            '<div class="chat_8_3"><span>' + msg.title + '</span></div>\n' +
                            '</div>\n';
                        for(var i=0;i<msg.description.length;i++){
                            if(0==msg.type){
                                html3+='<div class="chat_9" onclick="goBrand(\''+msg.description[i][0]+'\',\''+msg.code+'\')" id="name_'+i+'">' + msg.description[i][1] + '</div>\n';
                            }else{
                                html3+='<div class="chat_9" onclick="goAfter(\''+msg.description[i][0]+'\')" id="name_'+i+'">' + msg.description[i][1] + '</div>\n';
                            }

                        }
                        if(0==msg.type){
                            html3+='<div class="chat_10" onclick="goMore()">查看更多<span>></span></div> \n';
                        }

                        html3+= '</div></div><div class="clear"></div> </div>  <div class="clear"></div>\n' +
                            '</div>';
                        $('#liaotian').append(html3);
                        // $('#heiddenId').val(lop);
                        $('.chat_7_1').val('');
                        chagehei();
                        break;
                    case 2:
                        console.log(2);
                        var html = '';
                        html += '<div class="chat_5"><img src="images/kefu.png" class="chat_5_1"/>';
                        html += ' <div class="chat_6"><div class="chat_6_1">机器人小元</div>';
                        html += '  <div class="chat_6_2">' + msg + '</div>';
                        html += ' <div class="clear"></div> </div>  <div class="clear"></div> </div>';
                        $('#liaotian').append(html);
                        break;
                    default:
                        console.log(222);
                        break;
                }

            },
            error: function () {
                console.log('连接失败');
            }
        });
    }
    //下个事件
    function goAfter(code) {
        $(function () {
            var AllHeight = $(window).height();
            var DIvheight = parseInt($('.chat_7').css('height'));
            AllHeight = AllHeight - DIvheight - 20;
            $('.chat_2').css('height', AllHeight + 'px');
        })
        var openid=  "<?php echo $userOpenId?>";
        var c_name=$('#c_name').val();
        $.ajax({
            type        : 'POST',
            data        : {
                c_name:c_name,
                openid:openid,
                code  : code
            },
            dataType :    'json',
            url :         'chat_do.php?act=getAfter',
            success: function (data) {
                code = data.code;
                msg  = data.msg;
                $('#c_name').val(0);
                console.log(code=='2');
                switch(parseInt(code)){
                    case 1:
                        console.log(msg);
                        var html3 = '<div class="chat_5"><img src="images/kefu.png" class="chat_5_1"/>\n' +
                            '<div class="chat_6"><div class="chat_6_1">机器人小元</div>\n' +
                            '<div class="chat_6_2">\n' +
                            '<div class="chat_8">\n' +
                            '<div class="chat_8_1">\n' +
                            '<img src="' + msg.picUrl + '" class="chat_8_2"/>\n' +
                            '<div class="chat_8_3"><span>' + msg.title + '</span></div>\n' +
                            '</div>\n';
                        if(msg.description.length==0){
                            html3+='<div class="chat_9"  id="name_'+i+'">' +msg.url + '</div>\n';
                        }else{
                            for(var i=0;i<msg.description.length;i++){
                                if(0==msg.type){
                                    html3+='<div class="chat_9" onclick="goBrand(\''+msg.description[i][0]+'\',\''+msg.code+'\')" id="name_'+i+'">' + msg.description[i][1] + '</div>\n';
                                }else{
                                    html3+='<div class="chat_9" onclick="goAfter(\''+msg.description[i][0]+'\')" id="name_'+i+'">' + msg.description[i][1] + '</div>\n';
                                }

                            }
                        }
                        if(0==msg.type){
                            html3+='<div class="chat_10" >查看更多<span>></span></div> \n';
                        }
                        html3+= '</div></div><div class="clear"></div> </div>  <div class="clear"></div>\n' +
                            '</div>';
                        $('#liaotian').append(html3);
                        // $('#heiddenId').val(lop);
                        $('.chat_7_1').val('');
                        chagehei();
                        break;
                    case 2://文字
                        console.log(2);
                        var html = '';
                        html += '<div class="chat_5"><img src="images/kefu.png" class="chat_5_1"/>';
                        html += ' <div class="chat_6"><div class="chat_6_1">机器人小元</div>';
                        html += '  <div class="chat_6_2">' + msg + '</div>';
                        html += ' <div class="clear"></div> </div>  <div class="clear"></div> </div>';
                        $('#liaotian').append(html);
                        // $('#heiddenId').val(lop);
                        $('.chat_7_1').val('');
                        chagehei();
                        break;
                    case 3://图片
                        console.log(msg);
                        var html3 = '<div class="chat_5"><img src="images/kefu.png" class="chat_5_1"/>\n' +
                            '<div class="chat_6"><div class="chat_6_1">机器人小元</div>\n' +
                            '<div class="chat_6_2">\n' +
                            '<a href="#">\n' +
                            '<img src="' + msg.url + '"   width="280" height="200" class="chat_8_2" />\n' +
                            '</a></div><div class="clear"></div> </div>  <div class="clear"></div></div>';
                        $('#liaotian').append(html3);
                        $('.chat_7_1').val('');
                        chagehei();
                        break;
                    case 4://视频
                        console.log(msg);
                        var html3 ='<div class="chat_5"><img src="images/kefu.png" class="chat_5_1"/>\n';
                        html3 += '<div class="chat_6"><div class="chat_6_1">机器人小元</div>\n' +
                            '<div class="chat_6_2">\n' +
                            '<a href="#">\n' +
                            '<video class="video" src="'+msg.url+'" poster="'+msg.videoCoverUrl+'" controls x5-playsinline="" playsinline="" webkit-playsinline="true" preload="auto" width="280" height="200" style="margin-top: 0;object-fit: fill"></video>\n'+
//                                '<div class="btn-play"><img src="static/images/play.png" alt="" id="images"></div>'+
                            '</a></div><div class="clear"></div> </div>  <div class="clear"></div></div>';
                        $('#liaotian').append(html3);
                        $('.chat_7_1').val('');
                        chagehei();
                        break;
                    default:
                        console.log(222);
                        break;
                }

            },
            error: function () {
                console.log('连接失败');
            }
        });
    }
    //弹出品牌
    function goBrand(id,code) {
        $("#community_div").hide();
        $("#chat").show();
        $("#text").show();

        $(function () {
            var AllHeight = $(window).height();
            var DIvheight = parseInt($('.chat_7').css('height'));
            AllHeight = AllHeight - DIvheight - 20;
            $('.chat_2').css('height', AllHeight + 'px');
        });

        $.ajax({
            type        : 'POST',
            data        : {
                id:id,
                code  : code
            },
            dataType :    'json',
            url :         'chat_do.php?act=getBrand',
            success: function (data) {
                code = data.code;
                msg  = data.msg;
                console.log(code=='2');
                console.log(msg.community_type);
                switch(parseInt(code)){
                    case 1:
                        if(msg.community_type!=1){
                            console.log(msg);
                            var html3 = '<div class="chat_5"><img src="images/kefu.png" class="chat_5_1"/>\n' +
                                '<div class="chat_6"><div class="chat_6_1">机器人小元</div>\n' +
                                '<div class="chat_6_2">\n' +
                                '<div class="chat_8">\n' +
                                '<div class="chat_8_1">\n' +
                                '<img src="' + msg.picUrl + '" class="chat_8_2"/>\n' +
                                '<div class="chat_8_3"><span>' + msg.title + '</span></div>\n' +
                                '</div>\n';
                            for(var i=0;i<msg.description.length;i++){
                                if(0==msg.type){
                                    html3+='<div class="chat_9" onclick="goBrand(\''+msg.description[i][0]+'\',\''+msg.code+'\')" id="name_'+i+'">' + msg.description[i][1] + '</div>\n';
                                }else{
                                    html3+='<div class="chat_9" onclick="goAfter(\''+msg.description[i][0]+'\')" id="name_'+i+'">' + msg.description[i][1] + '</div>\n';
                                }

                            }

                            html3+= '</div></div><div class="clear"></div> </div>  <div class="clear"></div>\n' +

                                '</div>';
                            $('#liaotian').append(html3);
                            // $('#heiddenId').val(lop);
//                        $('.chat_7_1').val('');
                            chagehei();
                        }else{
                            goAfter(msg.community_brand);
                        }

                        break;
                    case 2:
                        console.log(2);
                        var html = '';
                        html += '<div class="chat_5"><img src="images/kefu.png" class="chat_5_1"/>';
                        html += ' <div class="chat_6"><div class="chat_6_1">机器人小元</div>';
                        html += '  <div class="chat_6_2">' + msg + '</div>';
                        html += ' <div class="clear"></div> </div>  <div class="clear"></div> </div>';
                        $('#liaotian').append(html);
                        $('.chat_7_1').val('');
                        chagehei();
                        break;
                    default:
                        console.log(222);
                        break;
                }

            },
            error: function () {
                console.log('连接失败');
            }
        });

    }
    //小区更多
    function goMore() {
        $("ul li").remove();
        $("#chat").attr("style", "display:none;");
        $("#text").attr("style", "display:none");
        $("#community_div").attr("style", "display:block");

        $.ajax({
            type        : 'POST',
            dataType :    'json',
            url :         'chat_do.php?act=getMore',
            success: function (data) {
                code = data.code;
                msg  = data.msg;
                console.log(code=='2');
                console.log(msg);
                switch(parseInt(code)){
                    case 1:
                        for(var i=0;i<msg.description.length;i++){
                            console.log( );
                            if(msg.description[i][1].split("_")[0]=="" && 0!=msg.description[i][0]){
                                $("#community_list").append('<li onclick="goBrand(\'' + msg.description[i][0]+'\',\'' + msg.after_code+'\')">' +
                                    '<div class="li-left">' +
                                    '<span class="ef-num">' + msg.description[i][1].split("_")[1] + '</span> ' +
                                    '</div></li>');
                            }else{
                                if(0==msg.description[i][0]){
                                    $("#community_list_other").append('<li onclick="go_Other_Community(\'' + msg.description[i][0]+'\')">' +
                                        '<div class="li-left">' +
                                        '<span class="ef-num">' + msg.description[i][1].split("_")[1] + '</span> ' +
                                        '</div></li>');
                                }else{
                                    $("#community_list_"+msg.description[i][1].split("_")[0]).append('<li onclick="goBrand(\'' + msg.description[i][0]+'\',\'' + msg.after_code+'\')">' +
                                        '<div class="li-left">' +
                                        '<span class="ef-num">' + msg.description[i][1].split("_")[1] + '</span> ' +
                                        '</div></li>');
                                }
                            }


                        }
                        break;

                    default:
                        console.log(222);
                        break;
                }

            },
            error: function () {
                console.log('连接失败');
            }
        });

    }
    //其他小区
    function go_Other_Community(id){
        $(function () {
            var AllHeight = $(window).height();
            var DIvheight = parseInt($('.chat_7').css('height'));
            AllHeight = AllHeight - DIvheight - 20;
            $('.chat_2').css('height', AllHeight + 'px');
        })
        $("#chat").show();
        $("#text").show();
        $("#community_div").hide();
        $.ajax({
            type        : 'POST',
            dataType :    'json',
            url :         'chat_do.php?act=go_Other_Community',
            success: function (data) {
                code = data.code;
                msg  = data.msg;
                console.log(code=='2');
                switch(parseInt(code)){
                    case 2:
                        console.log(2);
                        var html = '';
                        html += '<div class="chat_5"><img src="images/kefu.png" class="chat_5_1"/>';
                        html += ' <div class="chat_6"><div class="chat_6_1">机器人小元</div>';
                        html += '  <div class="chat_6_2">' + msg + '</div>';
                        html += ' <div class="clear"></div> </div>  <div class="clear"></div> </div>';
                        $('#is_other').val(1);
                        $('#liaotian').append(html);
                        $('.chat_7_1').val('');
                        chagehei();
                        break;
                    default:
                        console.log(222);
                        break;
                }
            },
            error: function () {
                console.log('连接失败');
            }
        });

    }
    $(function () {
        //输入事件
        $('#sendbtn').click(function () {
            var is_other=$("#is_other").val();
//            var id='<?php //echo $userOpenId;?>//';
            var id=  "<?php echo $userOpenId?>";

//        var id="om86uuMSGmBuEuTlctcTPt4pGprI";
            var url="getBrand";
            var code=0;
            var Vlaue1 = $('.chat_7_1').val();//获取输入框内容
            var VlaueId = $('#heiddenId').val();//自己逻辑判断ID。
            if (Vlaue1 != '') {
                $('.chat_7_1').val('');
                var regRule = /\uD83C[\uDF00-\uDFFF]|\uD83D[\uDC00-\uDE4F]/g;
                if(Vlaue1.match(regRule)) {
                    Vlaue1 = Vlaue1.replace(/\uD83C[\uDF00-\uDFFF]|\uD83D[\uDC00-\uDE4F]/g, "");
                    layer.msg("不支持表情");
                    chagehei();
                    return false;
                }
                var html2 = '';
                <?php if($user_info and $personal_info){?>
                html2 += ' <div class="chat_3"><img src="<?php echo $personal_info['headimgurl']?>" class="chat_3_1"/>';
                html2 += '  <div class="chat_4"> <div class="chat_4_1"><?php echo $user_info['name']?></div>';
                <?php }else{?>
                html2 += ' <div class="chat_3"><img src="images/jinshenxing.png" class="chat_3_1"/>';
                html2 += '  <div class="chat_4"> <div class="chat_4_1">游客</div>';
                <?php } ?>

                html2 += ' <div class="chat_4_2">' + Vlaue1 + '</div>';
                html2 += '<div class="clear"></div></div> <div class="clear"></div>  </div>';
                $('#liaotian').append(html2);
                if(is_other==0){
                    url="replay";
                }


                console.log(Vlaue1);
                $.ajax({
                    type: 'POST',
                    url: 'chat_do.php?act='+url,
                    dataType: "json",
                    data: {
                        id:id,
                        code:code,
                        keyword: Vlaue1
                    },
                    success: function (data) {
                        codes = data.code;
                        msg  = data.msg;
                        $("#is_other").val(0);
                        if(is_other!=0){
                            $("#c_name").val(Vlaue1);
                        }
                        switch(parseInt(codes)){
                            case 1:
                                var html3 = '<div class="chat_5"><img src="images/kefu.png" class="chat_5_1"/>\n' +
                                    '<div class="chat_6"><div class="chat_6_1">机器人小元</div>\n' +
                                    '<div class="chat_6_2">\n' +
                                    '<div class="chat_8">\n' +
                                    '<div class="chat_8_1">\n' +
                                    '<img src="' + msg.picUrl + '" class="chat_8_2"/>\n' +
                                    '<div class="chat_8_3"><span>' + msg.title + '</span></div>\n' +
                                    '</div>\n';
                                if(msg.description.length==0){
                                    html3+='<div class="chat_9"  id="name_'+i+'">' +msg.url + '</div>\n';
                                }else{
                                    for(var i=0;i<msg.description.length;i++){
                                        if(0==msg.type){
                                            html3+='<div class="chat_9" onclick="goBrand(\''+msg.description[i][0]+'\',\''+msg.code+'\')" id="name_'+i+'">' + msg.description[i][1] + '</div>\n';
                                        }else{
                                            html3+='<div class="chat_9" onclick="goAfter(\''+msg.description[i][0]+'\')" id="name_'+i+'">' + msg.description[i][1] + '</div>\n';
                                        }

                                    }
                                }

                                if(0==msg.type){
                                    html3+='<div class="chat_10" >查看更多<span>></span></div> \n';
                                }
                                html3+= '</div></div><div class="clear"></div> </div>  <div class="clear"></div>\n' +
                                    '</div>';
                                $('#liaotian').append(html3);

                                chagehei();
                                break;
                            case 2://文字
                                console.log(2);
                                var html = '';
                                html += '<div class="chat_5"><img src="images/kefu.png" class="chat_5_1"/>';
                                html += ' <div class="chat_6"><div class="chat_6_1">机器人小元</div>';
                                html += '  <div class="chat_6_2">' + msg + '</div>';
                                html += ' <div class="clear"></div> </div>  <div class="clear"></div> </div>';
                                $('#liaotian').append(html);
                                $('.chat_7_1').val('');
                                chagehei();

                                break;
                            case 3://图片
                                console.log(msg);
                                var html3 = '<div class="chat_5"><img src="images/kefu.png" class="chat_5_1"/>\n' +
                                    '<div class="chat_6"><div class="chat_6_1">机器人小元</div>\n' +
                                    '<div class="chat_6_2">\n' +
                                    '<img src="' + msg.url + '" class="chat_8_2"  width="280" height="200"/>\n' +
                                    '</div><div class="clear"></div> </div>  <div class="clear"></div></div>';
                                $('#liaotian').append(html3);
                                $('.chat_7_1').val('');
                                chagehei();
                                break;
                            case 4://视频
                                console.log(msg);
                                var html3 ='<div class="chat_5"><img src="images/kefu.png" class="chat_5_1"/>\n';
                                html3 += '<div class="chat_6"><div class="chat_6_1">机器人小元</div>\n' +
                                    '<div class="chat_6_2">\n' +
                                    '<a href="#">\n' +
                                    '<video class="video" src="'+msg.url+'" controls x5-playsinline="" playsinline="" webkit-playsinline="true" preload="auto"  poster="static/images/123.png"  width="280" height="200" style="margin-top: 0;object-fit: fill"></video>\n'+
//                                '<div class="btn-play"><img src="static/images/play.png" alt="" id="images"></div>'+
                                    '</a></div><div class="clear"></div> </div>  <div class="clear"></div></div>';
                                $('#liaotian').append(html3);
                                $('.chat_7_1').val('');
                                chagehei();
                                break;
                            default:
                                console.log(data);
                                console.log(2);
                                var html = '';
                                html += '<div class="chat_5"><img src="images/kefu.png" class="chat_5_1"/>';
                                html += ' <div class="chat_6"><div class="chat_6_1">机器人小元</div>';
                                html += '  <div class="chat_6_2">' + msg + '</div>';
                                html += ' <div class="clear"></div> </div>  <div class="clear"></div> </div>';
                                $('#liaotian').append(html);
                                $('.chat_7_1').val('');
                                chagehei();
                                break;

                        }

                    },
                    error: function () {
                        console.log('连接失败');
//                        alert("233");
                    }
//                    error:function(data,type, err) {
//                        alert("ajax错误类型：" + type);
//                        alert(err);
//                    }
                })
            }

        })
        $('.chat_15_1').click(function () {
            window.history.back(-1);
        })


    })
</script>

</body>
</html>