<?php
/**
 * 首页 index.php
 *
 * @version       v0.01
 * @create time   2018/11/21
 * @update time   2018/11/21
 * @author        dlk
 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
 */
require_once('web_init.php');

$bannerlist = Picture::getPageList(1, 99, 1, 1, -1);
$aboutus = Webcontent::getPageList(1, 99, 1, 3);
$scalelist = Webcontent::getPageList(1, 99, 1, 1);
$hislist = Webcontent::getPageList(1, 99, 1, 2);
//项目案例
$pcaselist = Web_projectcase::getPageList(1, 5, 1, 0);
//门店
$dislist = Web_distribution::getPageList(1, 5, 1);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>首页</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <link rel="stylesheet" type="text/css" href="css/common.css"/>
    <link rel="stylesheet" href="css/swiper.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="css/query.css">
    <script type="text/javascript" src="js/jquery-2.1.4.js"></script>
    <script type="text/javascript" src="js/swiper.min.js"></script>
    <script type="text/javascript" src="js/common.js"></script>
</head>
<body>
<div class="container">
    <div class="home_tab">
        <div class="home_tab_main">
            <img class="home_tab_logo" src="imgs/home_logo.png" alt="home_logo">
            <ul class="home_tab_list font_p">
                <li><a href="#">售后服务</a></li>
                <li><a href="#">渠道分销</a></li>
                <li><a href="#">项目案例</a></li>
                <li><a href="#">公司动态</a></li>
                <li><a href="#">公司介绍</a></li>
                <li><a href="#"  class="been_checked">首页</a></li>

                <!--   <div class="home_tab_server">24小时服务热线：400-966-5890</div> -->
            </ul>
            <div class="phone-menu"></div>
        </div>
    </div>
    <div class="homePage_wrapper">
        <div class="swiper-container ">
            <div class="swiper-wrapper">
                <?php
                if($bannerlist){
                    foreach ($bannerlist as $thisb){
                        echo '
                            <div class="swiper-slide">
                                <a href="'.$thisb['http'].'"><img src="'.$HTTP_PATH.$thisb['url'].'"></a>
                            </div>
                        ';
                    }
                }
                ?>
            </div>
            <div class="swiper-pagination"></div>
        </div>


        <div class="wrapper_profixed">
            <div class="wrapper_fixed">
                <div class="wrapper_fixed_first">
                    <img src="imgs/home_banner_bg.png" alt="home_banner_bg"/>
                    <span class="font_p">关于我们</span>
                </div>
                <div class="wrapper_fixed_second">
                    <span><?php echo $aboutus?$aboutus[0]['content']:''; ?></span>
                </div>
            </div>
        </div>
    </div>

    <div class="home_title">
        <div class="home_title_bottom">
            <img src="imgs/home_title_left.png" alt="home_title_left"/>
            <span class="font_p ">规模实力</span>
            <img src="imgs/home_title_right.png" alt="home_title_right"/>
        </div>
    </div>

    <div class="home_img_icon">
        <?php
        if($scalelist){
            foreach ($scalelist as $thisscale){
                echo '
                    <a class="hover1" href="#">
                        <div class="home_img_sub1">
                            <p class="home_number">
                                <span>'.$thisscale['title'].'</span>
                                <span>'.$thisscale['subtitle'].'</span>
                            </p>
                        </div>
                        <div class="home_img_sub3">
                            <p class="home_numbering">'.$thisscale['content'].'</p>
                        </div>
                    </a>
                ';
            }
        }
        ?>
    </div>
    <div class="home_title">
        <div class="home_title_bottom">
            <img src="imgs/home_title_left.png" alt="home_title_left"/>
            <span class="font_p ">我们的价值观</span>
            <img src="imgs/home_title_right.png" alt="home_title_right"/>
        </div>
    </div>

    <div class="middle_image"><img src="imgs/home_title_ad.png" alt="home_title_ad" ></div>

    <div class="development_history">

        <div class="home_title">
            <div class="home_title_bottom">
                <img src="imgs/home_title_left.png" alt="home_title_left"/>
                <span class="font_p ">发展历程</span>
                <img src="imgs/home_title_right.png" alt="home_title_right"/>
            </div>
        </div>
        <!--    时间轴 -->
        <ul class="looping" style="margin-top:40px">
            <li>
                <img class="try1" src="imgs/relation_a.png" alt="relation_a">
                <img class="try2" src="imgs/relation_node_a.png" alt="relation_node_a">
                <div class="try3"></div>
                <p class="try4">1995</p>
                <span class="try5">成立 COMPTOIR DE SHAANXI SARL公司（卢森堡康达公司）</span>
            </li>
            <li>
                <img  class="try6" src="imgs/relation_b.png" alt="relation_a">
                <img   class="try7" src="imgs/relation_node_b.png" alt="relation_node_a">
                <div  class="try8"></div>
                <p  class="try9">1997</p>
                <span  class="try10">成功交付西北第一台燃气锅炉房</span>
            </li>
            <li>
                <img class="try11" src="imgs/relation_c.png" alt="relation_a">
                <img  class="try12" src="imgs/relation_node_c.png" alt="relation_node_a">
                <div class="try13"></div>
                <p class="try14">2000</p>
                <span class="try15">陕西康达暖通设备有限公司成立</span>
            </li>
            <li>
                <img class="try16" src="imgs/relation_asub.png" alt="relation_a">
                <img class="try17" src="imgs/relation_node_a.png" alt="relation_node_a">
                <div class="try18" ></div>
                <p class="try19">2006</p>
                <span class="try20">西安元聚环保设备有限公司成立</span>
            </li>
            <li>
                <img  class="try21" src="imgs/relation_bsub.png" alt="relation_a">
                <img  class="try22" src="imgs/relation_node_b.png" alt="relation_node_a">
                <div class="try23"></div>
                <p class="try24">2007</p>
                <span class="try25">陕北榆林办事处成立</span>
            </li>
            <li>
                <img class="try26" src="imgs/relation_csub.png" alt="relation_a">
                <img class="try27" src="imgs/relation_node_c.png" alt="relation_node_a">
                <div class="try28"></div>
                <p class="try29">2010</p>
                <span class="try30">渠道分销部成立，拓展分销市场</span>
            </li>
            <li>
                <img class="try31" src="imgs/relation_a.png" alt="relation_a">
                <img class="try32" src="imgs/relation_node_a.png" alt="relation_node_a">
                <div class="try33"></div>
                <p class="try34">2014</p>
                <span class="try35">陕南汉中办事处成立</span>
            </li>
            <li>
                <img  style="display:none"/>
                <img class="try37" src="imgs/relation_node_b.png" alt="relation_node_a">
                <div class="try38"></div>
                <p class="try39">2017</p>
                <span class="try40">成为西安市锅炉房低氮改造推荐单位</span>
            </li>
        </ul>
        <!-- 时间轴 -->
    </div>

    <div class="home_title">
        <div class="home_title_bottom">
            <img src="imgs/home_title_left.png" alt="home_title_left"/>
            <span class="font_p">工程案例</span>
            <img src="imgs/home_title_right.png" alt="home_title_right"/>
        </div>
        <div class="engineering_subtitle"><img src="imgs/case_title_ad.png" alt="case_title_ad"></div>

        <div class="engineering_product">
            <div class="engineering_line">
                <?php
                if($pcaselist){
                    foreach ($pcaselist as $thiscase){
                        echo '
							<div class="engineering_box">
								<div class="engineering_product_img">
									<img src="'.$HTTP_PATH.$thiscase['picurl'].'" alt="engineering_product_img"/>
								</div>
					
								<div class="engineering_product_hover" >
									<img src="imgs/case_btn_add.png" alt="case_btn_add_img"/>
								</div>
								<p class="engineering_product_des">'.$thiscase['title'].'</p>
							</div>
						';
                    }
                }else{
                    echo '<span class="font_p" style="margin: auto;">暂无工程案例</span>';
                }
                ?>
            </div>

        </div>
    </div>
    <?php if($pcaselist){?>
        <div class="view_more view_more1">
            <span>查看更多</span>
        </div>
    <?php } ?>


    <div class="home_title" style="padding-top:0px">
        <div class="home_title_bottom">
            <img src="imgs/home_title_left.png" alt="home_title_left"/>
            <span class="font_p">合作门店</span>
            <img src="imgs/home_title_right.png" alt="home_title_right"/>
        </div>
        <div class="engineering_subtitle"><img src="imgs/case_title_ad.png" alt="case_title_ad"></div>

        <div class="engineering_product">
            <div class="engineering_line">
                <?php
                if($dislist){
                    foreach ($dislist as $thisdis){
                        echo '
							<div class="engineering_box">
								<div class="engineering_product_img">
									<img src="'.$HTTP_PATH.$thisdis['picurl'].'" alt="engineering_product_img"/>
								</div>
					
								<div class="engineering_product_hover" >
									<img src="imgs/case_btn_add.png" alt="case_btn_add_img"/>
								</div>
								<p class="engineering_product_des">'.$thisdis['address'].'</p>
							</div>
						';
                    }
                }else{
                    echo '<span class="font_p" style="margin: auto;">暂无合作门店</span>';
                }
                ?>
            </div>

        </div>
    </div>

    <?php if($dislist){?>
        <div class="view_more">
            <span>查看更多</span>
        </div>
    <?php } ?>
    <div class="home_beforefoot">
    </div>

    <div class="home_footer">
        <div class="home_footer_container">
            <div class="footer_left">
                <div class="footer_left_line"></div>
                <div class="footer_left_title"></div>
                <p class="font_p">联系我们</p>
                <p class="font_p2">西安元聚环保设备有限公司</p>
                <div class="footer_left_content">
                    <p class="font_p2 ">电话：188xxxxxxxx</p>
                </div>
                <div class="footer_left_content footer_left_content1">
                    <p class="font_p2 ">邮箱：yuanju@xianyuanju.com</p>
                </div>
                <div class="footer_left_content footer_left_content2">
                    <p class="font_p2 ">地址：西安市高新区唐延路旺座现代城B座27层022701室</p>
                </div>
            </div>



            <div class="footer_center">
                <div class="footer_left_line"></div>
                <div class="footer_left_title"></div>
                <p class="font_p">快速导航</p>
                <div class="footer_center_item">
                    <p class="font_p2 ">公司介绍</p>
                </div>
                <div class="footer_center_item">
                    <p class="font_p2 ">加盟合作</p>
                </div>
                <div class="footer_center_item">
                    <p class="font_p2 ">公司动态</p>
                </div>
                <div class="footer_center_item">
                    <a class="home_tab_list_a" href="../login.php"><p class="font_p2 ">员工通道</p></a>
                </div>
                <div class="footer_center_item">
                    <p class="font_p2 ">项目案例</p>
                </div>
                <div class="footer_center_item">
                    <p class="font_p2 ">人员招聘</p>
                </div>
                <div class="footer_center_item">
                    <p class="font_p2 ">渠道分销</p>
                </div>
                <div class="footer_center_item">
                    <p class="font_p2 ">联系我们</p>
                </div>
            </div>
            <div class="footer_right">
                <div><img src="imgs/erweima3.png" alt="erweima1"/></div>
                <div><img src="imgs/erweima4.png" alt="erweima2"/></div>
            </div>
        </div>
        <div class="footer_boottom">
            <p>24小时服务热线：400-966-5890</p>
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