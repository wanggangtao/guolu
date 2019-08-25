$(function(){
	$('.submit').click(function(){
		$('.submit_pop').css('display','block');
		setTimeout("$('.submit_pop').css('display','none')","4000");
	});
$('.submit_pop2_btn').click(function(){
        $('.submit_pop2').css('display','block');
        setTimeout("$('.submit_pop2').css('display','none')","4000");
    });

    
 $('.add_people').click(function () {
        // var valnum = parseInt($(this).parent().prev().find('.GLXXmain_2').find('input').prop('name').replace(/[^0-9]/ig,""));
        // valnum= valnum+1;
        var htmladd="";
        htmladd +='  <div class="middleDiv_three">';
        htmladd +=' <div>';
        htmladd +='<p><img class="must_reactImg" src="images/must_react.png" alt="">联系人</p><input type="text" class="linkname" placeholder="联系人">';
        htmladd +='</div>';
        htmladd +=' <div>';
        htmladd +='<p><img class="must_reactImg" src="images/must_react.png" alt="">部门</p><input type="text" class="linkdepartment" placeholder="联系人部门">';
        htmladd +=' </div>';
        htmladd +=' <div>';
        htmladd +='<p><img class="must_reactImg" src="images/must_react.png" alt="">职位</p><input type="text" class="linkposition" placeholder="联系人职位">';
        htmladd +='</div>';
        htmladd +='  <div>';
        htmladd +=' <p><img class="must_reactImg" src="images/must_react.png" alt="">联系方式</p><input type="number" class="linkphone" placeholder="联系人联系方式">';
        htmladd +='</div>';
        htmladd +='  <div>';
        htmladd +=' <p><img class="must_reactImg" src="images/must_react.png" alt="">主要负责事项 </p>';
        htmladd +='<textarea  class="linkduty"  cols="30" rows="10" placeholder="联系人主要负责事项"></textarea>';

        htmladd +=' </div>';
        htmladd +='<Input class="newdivcheck" type="radio" name="1">';
        htmladd +='</div>'; 
        $(".middleDiv_all").append(htmladd);
         
    });
$('.middleDivOne_popButton').click(function(){
    $('.middleDiv_one_pop').css('display','block');
});
$('.middleDivOne_closeButton').click(function(){
    $('.middleDiv_one_pop').css('display','none');
});
 $('.manageHRWJCont_top').find('li').click(function(){
    $(this).addClass('HRWJCont_li').siblings().removeClass('HRWJCont_li');
});
 // $('.manageHRWJCont_middle_left').find('li').click(function(){
 //        $(this).addClass('manage_liCheck').siblings().removeClass('manage_liCheck');
 //    });

    // $('.picture_detail').hover(function () {
    //     $(this).find('p').css('display','block');
    // },function () {
    //     $(this).find('p').css('display','none');
    // })
    $('.managePicture_cont .picture_deletBtn').click(function(){
        $(this).parent().parent().remove();
    })

});
function programRevise_function(e){
    $('.programRevise_pop').css('display','block');

}
function programRevise_close(e){
    $('.programRevise_pop').css('display','none');

}
function setting_one(e){
    $('.ProjectCheck_setting_contAll').css('display','none');
    $('.ProjectCheck_setting_cont1').css('display','block');
}
function setting_two(e){
    $('.ProjectCheck_setting_contAll').css('display','none');
    $('.ProjectCheck_setting_cont2').css('display','block');
}
function setting_three(e){
    $('.ProjectCheck_setting_contAll').css('display','none');
    $('.ProjectCheck_setting_cont3').css('display','block');

}
function setting_four(e){
    $('.ProjectCheck_setting_contAll').css('display','none');
    $('.ProjectCheck_setting_cont4').css('display','block');

}
function setting_five(e){
    $('.ProjectCheck_setting_contAll').css('display','none');
    $('.ProjectCheck_setting_cont5').css('display','block');

}