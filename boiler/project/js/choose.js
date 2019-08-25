
    $(document).on('click','.newBTn',function () {
        var htmladd="";
        htmladd +=' <div class="newdiv">';
        htmladd +='<img src="images/zzjia.png" class="imgJ">';
        htmladd +='<div class="aaa">';
        htmladd +='<input type="checkbox" class="one_2">';
        htmladd +='<div class="hengxian"><input type="text" value="离散" class="one_3"></div>';
        htmladd +='</div>';
        htmladd +='<div class="newageain"></div>';
        htmladd +='<button class="newBTn">添加</button>';
        htmladd +=' </div>';
        var num =  $(this).prev('.newageain').find('.newdiv').length;
        if(num!=0)
        {
            $(this).prev('.newageain').children('.newdiv:nth-last-child(1)').after(htmladd);
        }
        else
        {
            $(this).prev('.newageain').append(htmladd);
        }
    })
    $(document).on('click','.imgJ',function () {
        var load = $(this).attr('src');
        if(load =="images/zzjia.png")
        {
            $(this).attr('src','images/zzjian.png');
            $(this).next().next('.newageain').slideDown('fast');
            $(this).next().next().next('.newBTn').css('display','block');
        }
        else
        {
            $(this).attr('src','images/zzjia.png');
            $(this).next().next('.newageain').slideUp(100);
            $(this).next().next().next('.newBTn').css('display','none');
        }

    })
    $(document).on('click','#firstbtn',function () {
        var htmladd="";
        htmladd +=' <div class="newdiv firstdiv">';
        htmladd +='<img src="images/zzjia.png" class="imgJ">';
        htmladd +='<div class="aaa">';
        htmladd +='<input type="checkbox" class="one_2">';
        htmladd +='<div class="hengxian"><input type="text" value="离散" class="one_3"></div>';
        htmladd +='</div>';
        htmladd +='<div class="newageain"></div>';
        htmladd +='<button class="newBTn">添加</button>';
        htmladd +=' </div>';
        $(this).before(htmladd);

    })
    $(document).on('click','.detele',function () {
        $('.one_1').find('.one_2').each(function () {
            if ($(this).is(":checked"))
            {
                $(this).parent('.aaa').parent('.newdiv').remove();
            }
            else {}

        })
    })