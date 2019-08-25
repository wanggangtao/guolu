$(function () {
    $('.GLXXmain_7').click(function () {
        $('.GLXXmain_7').removeClass('GLXXmain_check');
        $(this).addClass('GLXXmain_check');
    });
    $('#addIner').click(function () {

        var newht =  $('#nqadd_new').find('.insertion').html();
        var NHtml ='<div  class="insertion">'+newht+'</div>';
        $("#WandN").find('.insertion:nth-last-child(3)').after(NHtml);
        $('#mouIner').css('display','block');
    });
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

    });
    $('#addnq').click(function () {
        var newht =  $('#nqadd_new').find('.insertion').html();
        var NHtml ='<div  class="insertion">'+newht+'</div>';
        $("#nuanqi").find('.insertion:nth-last-child(3)').after(NHtml);
        $('#mounq').css('display','block');
    });
    $('#mounq').click(function () {
        var len = $('#nuanqi').find('.insertion');
        if(len.length<=2)
        {
            $("#nuanqi").find('.insertion:nth-last-child(3)').remove();
            $('#mounq').css('display','none');
        }
        else
        {
            $("#nuanqi").find('.insertion:nth-last-child(3)').remove();
        }

    });
    // $('#wandnq').click(function () {
    //     $('.GLXXmain_8').css('display','none');
    //     $('#WandN').css('display','block');
    //     $('#guolutype3').show();
    //     $('#guolutype1').hide();
    //     $('#guolutype2').hide();
    // });
    // $('#NQ').click(function () {
    //     $('.GLXXmain_8').css('display','none');
    //     $('#nuanqi').css('display','block');
    //     $('#guolutype1').show();
    //     $('#guolutype2').hide();
    //     $('#guolutype3').hide();
    // });
    // $('#rs').click(function () {
    //     $('.GLXXmain_8').css('display','none');
    //     $('#water').css('display','block');
    //     $('#guolutype2').show();
    //     $('#guolutype1').hide();
    //     $('#guolutype3').hide();
    // });


/*//暖气加热水中热水切换

    // $(document).on('click',$('.GLXXmain_2').find('input'),
    $(document).on('click','.Nwater',function () {
        var naval = parseInt($(this).val());
        if (naval== 31)
        {
            $(this).parent().parent().find('.fulltimechose').css('display','block');
            $(this).parent().parent().find('.timingchose').css('display','none');
        }
        else if (naval== 32)
        {
            $(this).parent().parent().find('.fulltimechose').css('display','none');
            $(this).parent().parent().find('.timingchose').css('display','block');
        }
    });
    $('#nextbtn').click(function () {

        var newht =  $('#wateradd_new').find('.insertion_new').html();
        var NHtml ='<div  class="insertion">'+newht+'</div>';

        $("#guolutype4").after(NHtml);
        $('#WandN').css('display','none');
        $('#nextWandN').css('display','block');

    });*/
//热水中热水切换

    // $(document).on('click',$('.GLXXmain_2').find('input'),
    // $(document).on('click','.water',function () {
    //     var naval = parseInt($(this).val());
    //     if (naval== 31)
    //     {
    //         $(this).parent().parent().find('.fulltimechose').css('display','block');
    //         $(this).parent().parent().find('.timingchose').css('display','none');
    //     }
    //     else if (naval== 32)
    //     {
    //         $(this).parent().parent().find('.fulltimechose').css('display','none');
    //         $(this).parent().parent().find('.timingchose').css('display','block');
    //     }
    // });

//暖气加热水中热水增删分区

   /* $('#addIner_1').click(function () {
        var valnum = parseInt($(this).parent().prev().find('.GLXXmain_2').find('input').prop('name').replace(/[^0-9]/ig,""));
        valnum= valnum+1;
        var newht =  $('#wateradd').find('.insertion_new').html();
        var NHtml ='<div  class="insertion">'+newht+'</div>';
        NHtml = NHtml.replace('name="water_1"','name="water_' + valnum + '"').replace('name="water_1"','name="water_' + valnum + '"');
        $("#nextWandN").find('.insertion:nth-last-child(1)').after(NHtml);
        $('#mouIner_1').css('display','block');
    });
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

    });*/

//热水增删分区
    $('#addrs').click(function () {
        var valnum = parseInt($(this).parent().prev().find('.GLXXmain_2').find('input').prop('name').replace(/[^0-9]/ig,""));
        valnum= valnum+1;
        var newht =  $('#wateradd_new').find('.insertion_new').html();
        var NHtml ='<div  class="insertion">'+newht+'</div>';
        NHtml = NHtml.replace('name="water_1"','name="water_' + valnum + '"').replace('name="water_1"','name="water_' + valnum + '"');
        $("#water").find('.insertion:nth-last-child(3)').after(NHtml);
        $('#mours').css('display','block');
    });
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

    });

    $("body").on("change", ".timingbuildtype", function(){
        var thistype = $(this).val();
        var timing_1 = $(this).parent().parent().find('.timing_1');
        $.ajax({
            type        : 'POST',
            data        : {
                pid     : thistype
            },
            dataType :    'json',
            url :         'build_type_do.php?act=getTimingBuildInfo',
            success :     function(data){
                switch(data.code){
                    case 1:
                        timing_1.html('');
                        timing_1.html(data.htmlstr);
                        break;
                    default:
                        layer.alert(data.msg, {icon: 5});
                }
            }
        });
    });
    $("body").on("change", ".alldaybuildtype", function(){
        var thistype = $(this).val();
        var timing_1 = $(this).parent().parent().find('.AlldayBuildTypediv');
        $.ajax({
            type        : 'POST',
            data        : {
                pid     : thistype
            },
            dataType :    'json',
            url :         'build_type_do.php?act=getAlldayBuildInfo',
            success :     function(data){
                switch(data.code){
                    case 1:
                        timing_1.html('');
                        timing_1.html(data.htmlstr);
                        break;
                    default:
                        layer.alert(data.msg, {icon: 5});
                }
            }
        });
    });
})