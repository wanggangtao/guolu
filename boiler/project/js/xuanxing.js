$(function () {
    $('.GLXXmain_7').click(function () {
        $('.GLXXmain_7').removeClass('GLXXmain_check');
        $(this).addClass('GLXXmain_check');
    })
    $('#addIner').click(function () {

        var newht =  $('#WandN').find('.insertion').html();
        var NHtml ='<div  class="insertion">'+newht+'</div>';
        $("#WandN").find('.insertion:nth-last-child(3)').after(NHtml);
        $('#mouIner').css('display','block');
    })
    $('#mouIner').click(function () {
        var len = $('#WandN').find('.insertion');
        if(len.length<=2)
        {
            $("#WandN").find('.insertion:nth-last-child(3)').remove();
            $('#mouIner').css('display','none');
        }
        else
        {
            $("#WandN").find('.insertion:nth-last-child(3)').remove();
        }

    })
    $('#addnq').click(function () {

        var newht =  $('#nuanqi').find('.insertion').html();
        var NHtml ='<div  class="insertion">'+newht+'</div>';
        $("#nuanqi").find('.insertion:nth-last-child(3)').after(NHtml);
        $('#mounq').css('display','block');
    })
    $('#mounq').click(function () {
        var len = $('#').find('.insertion');
        if(len.length<=2)
        {
            $("#nuanqi").find('.insertion:nth-last-child(3)').remove();
            $('#mounq').css('display','none');
        }
        else
        {
            $("#nuanqi").find('.insertion:nth-last-child(3)').remove();
        }

    })
    $('#wandnq').click(function () {
        $('.GLXXmain_8').css('display','none');
        $('#WandN').css('display','block');
    })
    $('#NQ').click(function () {
        $('.GLXXmain_8').css('display','none');
        $('#nuanqi').css('display','block');
    })
    $('#rs').click(function () {
        $('.GLXXmain_8').css('display','none');
        $('#water').css('display','block');
    })

})
//暖气加热水中热水切换
$(function () {
    // $(document).on('click',$('.GLXXmain_2').find('input'),
    $(document).on('click','.Nwater',function () {
        var naval = parseInt($(this).val());
        if (naval== 1)
        {
            $(this).parent().parent().find('.fulltimechose').css('display','block');
            $(this).parent().parent().find('.timingchose').css('display','none');
        }
        else if (naval== 2)
        {
            $(this).parent().parent().find('.fulltimechose').css('display','none');
            $(this).parent().parent().find('.timingchose').css('display','block');
        }
    })
    $('#nextbtn').click(function () {
        $('#WandN').css('display','none');
        $('#nextWandN').css('display','block');
    })
})
//热水中热水切换
$(function () {
    // $(document).on('click',$('.GLXXmain_2').find('input'),
    $(document).on('click','.water',function () {
        var naval = parseInt($(this).val());
        if (naval== 1)
        {
            $(this).parent().parent().find('.fulltimechose').css('display','block');
            $(this).parent().parent().find('.timingchose').css('display','none');
        }
        else if (naval== 2)
        {
            $(this).parent().parent().find('.fulltimechose').css('display','none');
            $(this).parent().parent().find('.timingchose').css('display','block');
        }
    })
    $('#nextbtn').click(function () {
        $('#WandN').css('display','none');
        $('#nextWandN').css('display','block');
    })
})
//暖气加热水中热水增删分区
$(function () {
    $('#addIner_1').click(function () {
        var valnum = parseInt($(this).parent().prev().find('.GLXXmain_2').find('input').prop('name').replace(/[^0-9]/ig,""));
        valnum= valnum+1;
        var htmladd="";
        htmladd +=' <div class="insertion">';
        htmladd +='<img src="images/fgx_ls.png" class="GLXXmain_9">';
        htmladd +='<div class="GLXXmain_2">';
        htmladd +='<input  type="radio" class="GLXXmain_5 Nwater" name="n'+valnum+'" checked value="1"><span class="GLXXmain_6">全日供水</span>';
        htmladd +='<input  style="margin-left: 60px" type="radio" class="GLXXmain_5 Nwater"  name="n'+valnum+'" value="2"><span class="GLXXmain_6">定时供水</span>';
        htmladd +='</div>';
        htmladd +=' <div class="fulltimechose" >';
        htmladd +='<div class="GLXXmain_1">建筑类别</div>';
        htmladd +='<div class="GLXXmain_2">';
        htmladd +='<select type="text" class="GLXXmain_3" style="width: 344px">';
        htmladd +=' <option >住宅</option>';
        htmladd +=' <option>aaa</option>';
        htmladd +='<option>aa</option>';
        htmladd +='<option>sss</option>';
        htmladd +=' <option>www</option>';
        htmladd +='</select>';
        htmladd +=' </div>';
        htmladd +='<div class="GLXXmain_1">使用人数</div><div class="GLXXmain_2"><input type="text" class="GLXXmain_3"><div class="GLXXmain_15"></div> </div>';
        htmladd +='<div class="GLXXmain_1">热水供应方式</div><div class="GLXXmain_2">';
        htmladd +='<select type="text" class="GLXXmain_3" style="width: 344px;">';
        htmladd +=' <option >住宅</option> <option>aaa</option><option>aa</option><option>sss</option><option>www</option>';
        htmladd +='</select>';
        htmladd +=' </div>';
        htmladd +=' </div>';
        htmladd +='<div class="timingchose" style="display: none"><div class="GLXXmain_1">建筑类别</div><div class="GLXXmain_2">';
        htmladd +='<select type="text" class="GLXXmain_3" style="width: 344px">';
        htmladd +='<option >住宅</option>\n' +
            '            <option>aaa</option>\n' +
            '            <option>aa</option>\n' +
            '            <option>sss</option>\n' +
            '            <option>www</option>';
        htmladd +='</select>';
        htmladd +='</div>';
        htmladd +='<div class="timing_1">\n' +
            '            <div class="timing_2"><span class="timing_3">带有淋浴器的浴盆</span><input class="timing_4" type="number"><span class="timing_5">个</span></div>\n' +
            '        <div class="timing_2"><span class="timing_3">无淋浴器的浴盆</span><input class="timing_4" type="number"><span class="timing_5">个</span></div>\n' +
            '        <div class="timing_2"><span class="timing_3">淋浴器</span><input class="timing_4" type="number"><span class="timing_5">个</span></div>\n' +
            '        <div class="timing_2"><span class="timing_3">洗脸盆（池）</span><input class="timing_4" type="number"><span class="timing_5">个</span></div>\n' +
            '        <div class="timing_2" style="width: 200px"><span class="timing_3">盥洗槽水嘴）</span><input class="timing_4" type="number"><span class="timing_5">个</span></div>\n' +
            '        </div>\n' +
            '        </div>\n' +
            '        </div>';
        $("#nextWandN").find('.insertion:nth-last-child(3)').after(htmladd);
        $('#mouIner_1').css('display','block');
    })
    $('#mouIner_1').click(function () {
        var len = $('#nextWandN').find('.insertion');
        if(len.length<=2)
        {
            $("#nextWandN").find('.insertion:nth-last-child(3)').remove();
            $('#mouIner_1').css('display','none');
        }
        else
        {
            $("#nextWandN").find('.insertion:nth-last-child(3)').remove();
        }

    })
})
//热水增删分区
$(function () {
    $('#addrs').click(function () {
        var valnum = parseInt($(this).parent().prev().find('.GLXXmain_2').find('input').prop('name').replace(/[^0-9]/ig,""));
        valnum= valnum+1;
        var htmladd="";
        htmladd +=' <div class="insertion">';
        htmladd +='<img src="images/fgx_ls.png" class="GLXXmain_9">';
        htmladd +='<div class="GLXXmain_2">';
        htmladd +='<input  type="radio" class="GLXXmain_5 water" name="water_'+valnum+'" checked value="1"><span class="GLXXmain_6">全日供水</span>';
        htmladd +='<input  style="margin-left: 60px" type="radio" class="GLXXmain_5 water"  name="water_'+valnum+'" value="2"><span class="GLXXmain_6">定时供水</span>';
        htmladd +='</div>';
        htmladd +=' <div class="fulltimechose" >';
        htmladd +='<div class="GLXXmain_1">建筑类别</div>';
        htmladd +='<div class="GLXXmain_2">';
        htmladd +='<select type="text" class="GLXXmain_3" style="width: 344px">';
        htmladd +=' <option >住宅</option>';
        htmladd +=' <option>aaa</option>';
        htmladd +='<option>aa</option>';
        htmladd +='<option>sss</option>';
        htmladd +=' <option>www</option>';
        htmladd +='</select>';
        htmladd +=' </div>';
        htmladd +='<div class="GLXXmain_1">使用人数</div><div class="GLXXmain_2"><input type="text" class="GLXXmain_3"><div class="GLXXmain_15"></div> </div>';
        htmladd +='<div class="GLXXmain_1">热水供应方式</div><div class="GLXXmain_2">';
        htmladd +='<select type="text" class="GLXXmain_3" style="width: 344px;">';
        htmladd +=' <option >住宅</option> <option>aaa</option><option>aa</option><option>sss</option><option>www</option>';
        htmladd +='</select>';
        htmladd +=' </div>';
        htmladd +=' </div>';
        htmladd +='<div class="timingchose" style="display: none"><div class="GLXXmain_1">建筑类别</div><div class="GLXXmain_2">';
        htmladd +='<select type="text" class="GLXXmain_3" style="width: 344px">';
        htmladd +='<option >住宅</option>\n' +
            '            <option>aaa</option>\n' +
            '            <option>aa</option>\n' +
            '            <option>sss</option>\n' +
            '            <option>www</option>';
        htmladd +='</select>';
        htmladd +='</div>';
        htmladd +='<div class="timing_1">\n' +
            '            <div class="timing_2"><span class="timing_3">带有淋浴器的浴盆</span><input class="timing_4" type="number"><span class="timing_5">个</span></div>\n' +
            '        <div class="timing_2"><span class="timing_3">无淋浴器的浴盆</span><input class="timing_4" type="number"><span class="timing_5">个</span></div>\n' +
            '        <div class="timing_2"><span class="timing_3">淋浴器</span><input class="timing_4" type="number"><span class="timing_5">个</span></div>\n' +
            '        <div class="timing_2"><span class="timing_3">洗脸盆（池）</span><input class="timing_4" type="number"><span class="timing_5">个</span></div>\n' +
            '        <div class="timing_2" style="width: 200px"><span class="timing_3">盥洗槽水嘴）</span><input class="timing_4" type="number"><span class="timing_5">个</span></div>\n' +
            '        </div>\n' +
            '        </div>\n' +
            '        </div>';
        $("#water").find('.insertion:nth-last-child(3)').after(htmladd);
        $('#mours').css('display','block');
    })
    $('#mours').click(function () {
        var len = $('#water').find('.insertion');
        if(len.length<=2)
        {
            $("#water").find('.insertion:nth-last-child(3)').remove();
            $('#mours').css('display','none');
        }
        else
        {
            $("#water").find('.insertion:nth-last-child(3)').remove();
        }

    })

})
     $(function () {
        $('.select_1').click(function () {
            $(this).next('.guolumain_4_2').slideDown('fast');
            $(this).find('img').addClass('rotate');
        })
        $('.guolumain_4_3').click(function () {
            var Newtext = $(this).text();
            $(this).parent().prev('.select_1').find('span').text(Newtext);
            $(this).parent().slideUp(100);
            $(this).parent().prev('.select_1').find('img').removeClass('rotate');
        })
    })
 