$(function(){
	$('.submit').click(function(){
		$('.submit_pop').css('display','block');
		setTimeout("$('.submit_pop').css('display','none')","4000");
	})
$('.submit_pop2_btn').click(function(){
        $('.submit_pop2').css('display','block');
        setTimeout("$('.submit_pop2').css('display','none')","4000");
    })

    
 $('.add_people').click(function () {
        // var valnum = parseInt($(this).parent().prev().find('.GLXXmain_2').find('input').prop('name').replace(/[^0-9]/ig,""));
        // valnum= valnum+1;
        var htmladd="";
        htmladd +='  <div class="middleDiv_three">';
        htmladd +=' <div>';
        htmladd +='<p>联系人</p><input type="text" placeholder="联系人">';
        htmladd +='</div>';
        htmladd +=' <div>';
        htmladd +='<p>部门</p><input type="text" placeholder="联系人部门">';
        htmladd +=' </div>';
        htmladd +=' <div>';
        htmladd +='<p>职位</p><input type="text" placeholder="联系人职位">';
        htmladd +='</div>';
        htmladd +='  <div>';
        htmladd +=' <p>联系方式</p><input type="text" placeholder="联系人联系方式">';
        htmladd +='</div>';
        htmladd +='  <div>';
        htmladd +=' <p>主要负责事项 </p>';
        htmladd +='<textarea name="" id="" cols="30" rows="10"placeholder="联系人主要负责事项"></textarea>';
        htmladd +=' </div>';
        htmladd +='</div>'; 
        $(".middleDiv_all").append(htmladd);
         
    })
$('.middleDivOne_popButton').click(function(){
    $('.middleDiv_one_pop').css('display','block');
})
$('.middleDivOne_closeButton').click(function(){
    $('.middleDiv_one_pop').css('display','none');
})
$('.manageHRWJCont_middle_left').find('li').click(function(){
    $(this).css({"background-color":"#C5EBFF","border-left":"4px solid #04A6FE"}).siblings().css({"background":"none","border":"none"})
})
$('.manageHRWJCont_top').find('li').click(function(){
    $(this).css({"background-color":"#04A6FE","color":"#fff"}).siblings().css({"background":"none","color":"#474747"})
})



})
function programRevise_function(e){
    $('.programRevise_pop').css('display','block');

}
function programRevise_close(e){
    $('.programRevise_pop').css('display','none');

}
function setting_one(e){
    $('.ProjectCheck_setting_cont1').css('display','block');
    $('.ProjectCheck_setting_cont2').css('display','none');
    $('.ProjectCheck_setting_cont3').css('display','none');
}
function setting_two(e){
    $('.ProjectCheck_setting_cont2').css('display','block');
    $('.ProjectCheck_setting_cont1').css('display','none');
    $('.ProjectCheck_setting_cont3').css('display','none');
}
function setting_three(e){
    $('.ProjectCheck_setting_cont3').css('display','block');
    $('.ProjectCheck_setting_cont2').css('display','none');
    $('.ProjectCheck_setting_cont1').css('display','none');
}