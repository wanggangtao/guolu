<?php
/**
 * Created by PhpStorm.
 * User: wzr
 * Date: 2018/12/10
 * Time: 12:34
 */
$paprm=array();
$contactus_info=Web_contactus::getList($paprm);
if(!empty($contactus_info)){
    $contactus_info=$contactus_info[0];
}else{
    $contactus_info=array();
}



?>
<div class="home_beforefoot">
</div>

<div class="home_footer">
    <div class="home_footer_container">
        <div class="footer_left">
            <div class="footer_left_line"></div>
            <a href="contactus.php"><div class="footer_left_title">Contact Us</div></a>
            <a href="contactus.php"><p class="font_p3">联系我们</p></a>
            <p class="font_p2  company_name"><?php if (!empty($contactus_info)) echo $contactus_info['company']  ?></p>
            <div class="footer_left_content">
                <p class="font_p2 ">电话：<?php if (!empty($contactus_info)) echo $contactus_info['telephone'] ?></p>
            </div>
            <div class="footer_left_content footer_left_content1">
                <p class="font_p2 ">邮箱：<?php if (!empty($contactus_info)) echo $contactus_info['email'] ?></p>
            </div>
            <div class="footer_left_content footer_left_content2">
                <p class="font_p2 ">地址：<?php if(!empty($contactus_info))echo $contactus_info['address'] ?></p>
            </div>
        </div>



        <div class="footer_center">
            <div class="footer_left_line"></div>
            <div class="footer_left_title">Quick Links</div>
            <p class="font_p3">快速导航</p>


            <div class="footer_center_item">
                <a href="company_introduction.php"><p class="font_p2 ">公司介绍</p></a>
            </div>
<!--            <div class="footer_center_item">-->
<!--                <p class="font_p2 ">加盟合作</p>-->
<!--            </div>-->
            <a href="company_situation.php"><div class="footer_center_item">
               <p class="font_p2 ">新闻中心</p></a></div>
            <div class="footer_center_item">
                <a href="../login.php"> <p class="font_p2 ">员工通道</p></a>
            </div>
           <a href="projectcase.php"><div class="footer_center_item">
                <p class="font_p2 ">项目案例</p></a></div>
            <a href="distribution.php"><div class="footer_center_item">
                <p class="font_p2 ">渠道分销</p></a></div>
       <a href="recruit.php"><div class="footer_center_item">
               <p class="font_p2 ">人才招聘</p></div></a>
        <a href="contactus.php"><div class="footer_center_item">
        <p class="font_p2 ">联系我们</p></a>
            </div>
        </div>
        <div class="footer_right">
            <div><img src="<?php echo $HTTP_PATH.$contactus_info['picurl1'];?>" alt="erweima1"/></div>
            <div><img src="<?php echo $HTTP_PATH.$contactus_info['picurl2'];?>" alt="erweima2"/></div>
        </div>
    </div>

    <div class="footer_boottom">
        <p>24小时服务热线：<?php if(!empty($contactus_info))echo $contactus_info['hotline'] ?></p>
    </div>

</div>

</div>



<script language="javascript">
    var mySwiper = new Swiper('.homePage_wrapper .swiper-container',{

        pagination :{
            el: '.swiper-pagination',
            clickable :true,
        },
        autoplay: {
            autoplay: true,
            disableOnInteraction: false,

        },

    });


</script>
</body>

</html>