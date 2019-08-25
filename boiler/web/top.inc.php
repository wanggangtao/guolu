<?php
/**
 * Created by PhpStorm.
 * User: forever
 * Date: 2018/12/10
 * Time: 12:37
 */
if($TOP_MENU!="distribution"){
unset($_SESSION['distribution_if_change']);}
if($TOP_MENU!="projectcase"){unset($_SESSION['if_change']);}
if($TOP_MENU!="company_situation"){unset($_SESSION['type_s']);}
?>
<div class="home_tab">
    <div class="home_tab_main">
        <img class="home_tab_logo" src="imgs/home_logo.png" alt="home_logo">
        <ul class="home_tab_list font_p">
            <li><a href="contactus.php" <?php if($TOP_MENU=="contactus")echo 'class="been_checked"'?>>联系我们</a></li>
            <li><a href="aftersale.php" <?php if($TOP_MENU=="aftersale")echo 'class="been_checked"'?>>售后服务</a></li>
            <li><a href="distribution.php" <?php if($TOP_MENU=="distribution")echo 'class="been_checked"'?>>渠道分销</a></li>
            <li><a href="projectcase.php" <?php if($TOP_MENU=="projectcase")echo 'class="been_checked"'?>>项目案例</a></li>
            <li><a href="company_situation.php" <?php if($TOP_MENU=="company_situation")echo 'class="been_checked"'?>>新闻中心</a></li>
            <li><a href="company_introduction.php" <?php if($TOP_MENU=="company_introduction")echo 'class="been_checked"'?>>公司介绍</a></li>
            <li><a href="index.php"  <?php if($TOP_MENU=="index")echo 'class="been_checked"'?>>首页</a></li>

            <!--   <div class="home_tab_server">24小时服务热线：400-966-5890</div> -->
        </ul>
        <?php
        $contactus_info=Web_contactus::getList();
        if(!empty($contactus_info)){
            $contactus_info=$contactus_info[0];
        }else{
            $contactus_info=array();
        }
        ?>
        <p class="nav-tell"><img src="imgs/icon-phone.png" />24小时服务热线：<?php if(!empty($contactus_info))echo $contactus_info['hotline'] ?></p>
        <div class="phone-menu"></div>
    </div>
</div>