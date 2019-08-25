$(function(){
	var as;



	function sendBegin(code,content)
	{
        var str='';
        str+='<li>'+
            '<div class="nesHead"><img src="img/6.jpg"/></div>'+
            '<div class="news"><img class="jiao" src="img/jiao.jpg">'+content+'</div>'+
            '<div class="clear"></div>'+
            '</li>';
        $('.newsList').append(str);
        //setTimeout(send,0);
        answerstwo(code);

        $('.conLeft').find('li.bg').children('.liRight').children('.infor').text(content);
        $('.RightCont').scrollTop($('.RightCont')[0].scrollHeight );
	}






	function send(){

			setTimeout(answerstwo,1000);

	}


	function answerstwo(code){
        $.ajax({
            type        : 'POST',
            data        : {
                code  : code,
            },
            dataType :    'json',
            url :         'knowledge_do.php?act=add',
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


		var answer='';
		answer+='<li>'+
					'<div class="answerHead"><img src="img/tou.jpg"/></div>'+
					'<div class="answers"><img class="jiao" src="img/g1.jpg">'+
					'<p class="p2">您好请问你想输入的是：</p>'+
					'<p class="p1">1、<a>查询我的订单</a></p>'+
					'<p class="p1">2、<a>获取新的信息</a></p>'+
					'<p class="p1">3、<a>啦啦啦啦啦啦</a></p>'+
					'</div>'+
					'<div class="clear"></div>'+
				'</li>';
		$('.newsList').append(answer);	
		$('.RightCont').scrollTop($('.RightCont')[0].scrollHeight );
	}
})