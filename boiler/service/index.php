<?php

require_once("web_init.php");



$beginInfo = fact::getBegin();


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>聊天框</title>
<link href="css/main.css?v=2" type="text/css" rel="stylesheet"/>
<script src="js/jquery-1.4.4.min.js" type="text/javascript"></script>
<script src="js/layui/layui.js" type="text/javascript"></script>
<script src="js/layer/layer.js" type="text/javascript"></script>
<script type="text/javascript">
    $(function(){

        $("#reset").click(function(){

            $(".newsList").html("");
            init();
        });


        $('.sendBtn').on('click',function(){
            var news=$('#dope').val();
            if(news==''){
                layer.alert('请输入发送内容！',{icon:5});
            }else{
                $('#dope').val('');

                sendContent(news);
            }

        });


    });

</script>


</head>

<body>
 <div class="litao">
    <div class="litao_1">
       <div class="litao_3 RightCont">
       <ul class="newsList">
				
		</ul>
       </div>
       <div class="litao_4">
         <textarea id="dope"></textarea>
         <button id="reset">重置</button>
         <button class="sendBtn" >发送</button>
         
       </div>
    </div>
    <div class="litao_2"><img src="img/bg3.gif"><span>技术支持：芝麻开花</span></div>
 </div>
</body>
</html>
<script type="text/javascript">

    var before = "";
    function sendBegin(thisCode)
    {

        $.ajax({
            type        : 'POST',
            data        : {
                code  : thisCode,
            },
            dataType :    'json',
            url :         'index_do.php?act=getInfo',
            success :     function(data){
                var code = data.code;
                var data  = data.msg;
                switch(code){
                    case 1:

                        before = thisCode;
                        var str='';
                        str+='<li>'+
                                '<div class="nesHead"><img src="img/service.png"/></div>'+
                                '<div class="news"><img class="jiao" src="img/jiao.jpg">'+data.content+'</div>'+
                                '<div class="clear"></div>'+
                            '</li>';
                        $('.newsList').append(str);
                        answerstwo(thisCode);
                        $('.conLeft').find('li.bg').children('.liRight').children('.infor').text(data.content);
                        $('.RightCont').scrollTop($('.RightCont')[0].scrollHeight );
                        break;
                    default:
                        layer.alert(msg, {icon: 5});
                }
            }
        });

    }


    function sendContent(content)
    {

        var answer='<li>'+
            '<div class="answerHead"><img src="img/tou.jpg"/></div>'+
            '<div class="answers"><img class="jiao" src="img/g1.jpg">' +
            '<p class="p2">'+content+'</p></li>';
        $('.newsList').append(answer);
        $('.RightCont').scrollTop($('.RightCont')[0].scrollHeight );

        $.ajax({
            type        : 'POST',
            data        : {
                content  : content,
                before:before,
            },
            dataType :    'json',
            url :         'index_do.php?act=getInfoByContent',
            success :     function(data){
                var code = data.code;
                var data  = data.msg;
                switch(code){
                    case 1:
                        var str='';
                        str+='<li>'+
                            '<div class="nesHead"><img src="img/service.png"/></div>'+
                            '<div class="news"><img class="jiao" src="img/jiao.jpg">'+data.content+'</div>'+
                            '<div class="clear"></div>'+
                            '</li>';
                        $('.newsList').append(str);
                        answerstwo(data.code);
                        before = data.code;
                        $('.conLeft').find('li.bg').children('.liRight').children('.infor').text(data.content);
                        $('.RightCont').scrollTop($('.RightCont')[0].scrollHeight );
                        break;
                    case 2:
                        var str='';
                        str+='<li>'+
                            '<div class="nesHead"><img src="img/service.png"/></div>'+
                            '<div class="news"><img class="jiao" src="img/jiao.jpg">'+data+'</div>'+
                            '<div class="clear"></div>'+
                            '</li>';
                        $('.newsList').append(str);

                        $('.conLeft').find('li.bg').children('.liRight').children('.infor').text(data);
                        $('.RightCont').scrollTop($('.RightCont')[0].scrollHeight );
                        break;
                    default:
                        layer.alert(msg, {icon: 5});
                }
            }
        });

    }


    function answerstwo(thisCode){
        /*	var arr=[
            "段子界今天天气很棒啊今天天气很棒啊今天天气很棒啊今天天气很棒啊今天天气很棒啊今天天气很棒啊混的最惨的两个狗：拉斯，普拉达。。。"];
            var aa=Math.floor((Math.random()*arr.length));*/

        $.ajax({
            type        : 'POST',
            data        : {
                code  : thisCode,
            },
            dataType :    'json',
            url :         'index_do.php?act=getAfter',
            success :     function(data){
                var code = data.code;
                var data  = data.msg;
                switch(code){
                    case 1:
                        var answer='';

                        if(data.length>0)
                        {
                            answer+='<li>'+
                                '<div class="answerHead"><img src="img/tou.jpg"/></div>'+
                                '<div class="answers"><img class="jiao" src="img/g1.jpg">' +
                                '<p class="p2">请问你的问题是：</p>';


                            for(var i=0;i<data.length;i++)
                            {
                                var currentSort = i+1;
                                answer+= '<p class="p1" onclick=sendBegin("'+data[i].after+'")>'+currentSort+'、<a>'+data[i].keyword+'</a></p>';
                            }

                            answer+=  '</div>'+
                                '<div class="clear"></div>'+
                                '</li>';
                        }
                        else
                        {
                            answer+='<li>'+
                                '<div class="answerHead"><img src="img/tou.jpg"/></div>'+
                                '<div class="answers"><img class="jiao" src="img/g1.jpg">' +
                                '<p class="p2">谢谢小服！</p></li>';
                        }
                        $('.newsList').append(answer);
                        $('.RightCont').scrollTop($('.RightCont')[0].scrollHeight );

                        break;
                    default:
                        layer.alert(msg, {icon: 5});
                }
            }
        });



    }


    function init()
    {
        sendBegin('<?php echo $beginInfo['code']?>');
    }


    init();
</script>