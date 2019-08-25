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
$TOP_MENU = "index";
$bannerlist = Picture::getPageList(1, 99, 1, 1, 1);
$aboutus = Webcontent::getPageList(1, 99, 1, 3);
$scalelist = Webcontent::getPageList(1, 99, 1, 1);
$hislist = Webcontent::getPageList(1, 99, 1, 2);


//优秀项目案例
$pcaselist = Web_projectcase::getListQianDuan();
//优秀门店
$dislist = Web_distribution::getListQianDuan();
//print_r($scalelist);
?>
<!DOCTYPE html>
<html>
<head>
    <title>首页</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <link rel="stylesheet" type="text/css" href="css/common.css"/>
    <link rel="stylesheet" href="css/swiper.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="css/query.css">
    <script type="text/javascript" src="js/jquery-2.1.4.js"></script>
    <script type="text/javascript" src="js/swiper.min.js"></script>
    <script type="text/javascript" src="js/common.js"></script>
    <script type="text/javascript" src="js/count.js"></script>
</head>
<body>
<?php
if($TOP_MENU!="distribution"){
    unset($_SESSION['distribution_if_change']);}
if($TOP_MENU!="projectcase"){unset($_SESSION['if_change']);}
if($TOP_MENU!="company_situation"){unset($_SESSION['type_s']);}
?>
<div class="container" style="background: #f8f9fb;">
    <?php require_once ('top.inc.php')?>
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
                else {
                    $bannerlist = Picture::getPageList(1, 99, 1, 1, -1);
                    foreach ($bannerlist as $thisb) {
                        echo '
                            <div class="swiper-slide">
                                <a href="' . $thisb['http'] . '"><img src="' . $HTTP_PATH . $thisb['url'] . '"></a>
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
<!--                    <img src="imgs/home_banner_bg.png" alt="home_banner_bg"/>-->
                    <img src="imgs/bg_banner_new.png" alt="home_banner_bg"/>
                    <p class="font_p" style="font-weight: normal !important;position: absolute;left: 0;top: 100px;font-size: 40px;">关于我们</p>
                    <span style="font-size:20px;font-family:PingFangSC-Regular;font-weight:400;color:rgba(255,255,255,1);top: 150px;">About Us</span>
                    <div class="line-tip" style="position: absolute;left: 0;top: 80px;width: 58px;height: 2px;background: #fff"></div>
                </div>
                <div class="wrapper_fixed_second" style="margin-top: 20px">
                    <span class="index-span"><?php echo $aboutus?$aboutus[0]['content']:''; ?></span>
                    <a class="see-more" href="company_introduction.php">MORE</a>
                </div>
            </div>
        </div>
    </div>

    <div class="home_title">
        <div class="home_title_bottom" style="display: flex;align-items: center;justify-content: space-between;">
            <img src="imgs/home_title_left.png" alt="home_title_left"/>
            <div class="english">
                <span class="font_p ">规模实力</span><br>
                <span style="margin-left:0 !important;font-size:18px;font-family:PingFangSC-Regular;font-weight:400;color:rgba(133,133,133,1);">OUR STRENGTH</span>
            </div>
            <img src="imgs/home_title_right.png" alt="home_title_right"/>
        </div>
    </div>

    <div class="home_img_icon">
        <?php
        if(count($scalelist)<=4){
            foreach ($scalelist as $thisscale){
                echo '
                    <a class="hover1" href="#">
                        <div class="home_img_sub1">
                            <p class="home_number">
                                <span><c class="count impact">'.$thisscale['title'].'</c><c class="impact">'.$thisscale['title_supplement'].'</c></span>
                                <span>'.$thisscale['subtitle'].'</span>
                            </p>
                        </div>
                        <div class="home_img_sub3">
                            <span><c class="impact">'.$thisscale['title'].'</c><c class="impact">'.$thisscale['title_supplement'].'</c></span>
                            <p class="home_numbering">'.$thisscale['content'].'</p>
                        </div>
                    </a>
                ';
            }
        }
        else{
            for($i = 0;$i<4;$i++){
                echo '
                    <a class="hover1" href="#">
                        <div class="home_img_sub1">
                            <p class="home_number">
                                <span><c class="count impact">'.$scalelist[$i]['title'].'</c><c class="impact">'.$scalelist[$i]['title_supplement'].'</c></span>
                                <span>'.$scalelist[$i]['subtitle'].'</span>
                            </p>
                        </div>
                        <div class="home_img_sub3">
                            <span><c class="impact">'.$scalelist[$i]['title'].'</c><c class="impact">'.$scalelist[$i]['title_supplement'].'</c></span>
                            <p class="home_numbering">'.$scalelist[$i]['content'].'</p>
                        </div>
                    </a>
                ';
            }
        }
        ?>
    </div>
    <div class="home_title our_values">
        <div class="home_title_bottom"style="display: flex;align-items: center;justify-content: space-between;">
            <img src="imgs/home_title_left.png" alt="home_title_left"/>
            <div class="english">
                <span class="font_p ">我们的价值观</span><br>
                <span style="margin-left:0 !important;font-size:18px;font-family:PingFangSC-Regular;font-weight:400;color:rgba(133,133,133,1);">OUR VALUE</span>
            </div>
            <img src="imgs/home_title_right.png" alt="home_title_right"/>
        </div>
    </div>

    <div class="middle_image"><img src="imgs/home_title_ad.png" alt="home_title_ad" ></div>

    <div class="development_history">

        <div class="home_title">
            <div class="home_title_bottom" style="display: flex;align-items: center;justify-content: space-between;">
                <img src="imgs/home_title_left.png" alt="home_title_left"/>
                <div class="english">
                    <span class="font_p ">发展历程</span><br>
                    <span style="margin-left:0 !important;font-size:18px;font-family:PingFangSC-Regular;font-weight:400;color:rgba(133,133,133,1);">MILESTONES</span>
                </div>
                <img src="imgs/home_title_right.png" alt="home_title_right"/>
            </div>
        </div>
        <!--    时间轴 -->
        <ul class="looping" style="margin-top:40px">

            <?php

            $contentList = webcontent::getPageList(1,10,1,2);



            ?>
            <li>
                <img class="try1" src="imgs/relation_a.png" alt="relation_a">
                <img class="try2" src="imgs/relation_node_a.png" alt="relation_node_a">
                <div class="try3"></div>
                <p class="try4"><?php echo $contentList[0]["title"]?></p>
                <span class="try5"><?php echo $contentList[0]["content"]?></span>
            </li>
            <li>
                <img  class="try6" src="imgs/relation_b.png" alt="relation_a">
                <img   class="try7" src="imgs/relation_node_b.png" alt="relation_node_a">
                <div  class="try8"></div>
                <p  class="try9"><?php echo $contentList[1]["title"]?></p>
                <span  class="try10"><?php echo $contentList[1]["content"]?></span>
            </li>
            <li>
                <img class="try11" src="imgs/relation_c.png" alt="relation_a">
                <img  class="try12" src="imgs/relation_node_c.png" alt="relation_node_a">
                <div class="try13"></div>
                <p class="try14"><?php echo $contentList[2]["title"]?></p>
                <span class="try15"><?php echo $contentList[2]["content"]?></span>
            </li>
            <li>
                <img class="try16" src="imgs/relation_asub.png" alt="relation_a">
                <img class="try17" src="imgs/relation_node_a.png" alt="relation_node_a">
                <div class="try18" ></div>
                <p class="try19"><?php echo $contentList[3]["title"]?></p>
                <span class="try20"><?php echo $contentList[3]["content"]?></span>
            </li>
            <li>
                <img  class="try21" src="imgs/relation_bsub.png" alt="relation_a">
                <img  class="try22" src="imgs/relation_node_b.png" alt="relation_node_a">
                <div class="try23"></div>
                <p class="try24"><?php echo $contentList[4]["title"]?></p>
                <span class="try25"><?php echo $contentList[4]["content"]?></span>
            </li>
            <li>
                <img class="try26" src="imgs/relation_csub.png" alt="relation_a">
                <img class="try27" src="imgs/relation_node_c.png" alt="relation_node_a">
                <div class="try28"></div>
                <p class="try29"><?php echo $contentList[5]["title"]?></p>
                <span class="try30"><?php echo $contentList[5]["content"]?></span>
            </li>
            <li>
                <img class="try31" src="imgs/relation_a.png" alt="relation_a">
                <img class="try32" src="imgs/relation_node_a.png" alt="relation_node_a">
                <div class="try33"></div>
                <p class="try34"><?php echo $contentList[6]["title"]?></p>
                <span class="try35"><?php echo $contentList[6]["content"]?></span>
            </li>
            <li>
                <img  style="display:none"/>
                <img class="try37" src="imgs/relation_node_b.png" alt="relation_node_a">
                <div class="try38"></div>
                <p class="try39"><?php echo $contentList[7]["title"]?></p>
                <span class="try40"><?php echo $contentList[7]["content"]?></span>
            </li>
        </ul>
        <!-- 时间轴 -->
    </div>



<!--    0103新版工程案例-->
    <div class="home_title">
        <div class="home_title_bottom" style="display: flex;align-items: center;justify-content: space-between;">
            <img src="imgs/home_title_left.png" alt="home_title_left" />
            <div class="english">
                <span class="font_p">项目案例</span><br>
                <span style="margin-left:0 !important;font-size:18px;font-family:PingFangSC-Regular;font-weight:400;color:rgba(133,133,133,1);">BUSINESS CASE</span>
            </div>
            <img src="imgs/home_title_right.png" alt="home_title_right" />
        </div>
        <div class="engineering_subtitle">
            <img src="imgs/case_title_ad.png" alt="case_title_ad"></div>
        <div class="engineering_product">
            <!-- 修改工程案例 -->
            <div class="company-example-wrap" style="margin-top: 0 !important;">
            <?php
                 if(count($pcaselist) >= 4){//选择的优秀案例多于4个，只显示前四个
                     for($i = 0;$i<4;$i++){
                         echo '
                              <div class="company-example-item">
                                    <a href="projectcase_detail.php?id='.$pcaselist[$i]['id'].'&type='.$pcaselist[$i]['type'].'">
                                        <img class="img-top" src="'.$HTTP_PATH.$pcaselist[$i]["picurl"].'" alt="典型项目案例">
                                        <div class="company-example-text">'.$pcaselist[$i]["title"].'</div>
                                        <a class="link" href="projectcase_detail.php?id='.$pcaselist[$i]['id'].'&type='.$pcaselist[$i]['type'].'">MORE</a>
                                   </a>
                              </div>
                       ';
                     }
                 }
                 else{ //选择的优秀案例少于4个，把选择的全部显示，还要选择排序靠前的
                     $pcaselist1 = Web_projectcase::getListQianDuan();
                     $pcaselist2 = Web_projectcase::getOtherList();
                            //   print_r($caselist1);
                     for($i = 0;$i<count($pcaselist1);$i++){
                         echo '
                               <div class="company-example-item">
                               <a href="projectcase_detail.php?id='.$pcaselist1[$i]['id'].'&type='.$pcaselist1[$i]['type'].'">
                                    <img class="img-top" src="'.$HTTP_PATH.$pcaselist1[$i]["picurl"].'" alt="典型项目案例1">
                                    <div class="company-example-text">'.$pcaselist1[$i]["title"].'</div>
                                    <a class="link" href="projectcase_detail.php?id='.$pcaselist1[$i]['id'].'&type='.$pcaselist1[$i]['type'].'">MORE</a>
                               </a>
                               </div>
                                 ';
                     }
                     for($i = 0;$i<4 - count($pcaselist1);$i++){
                         echo '
                               <div class="company-example-item">
                                    <a href="projectcase_detail.php?id='.$pcaselist2[$i]['id'].'&type='.$pcaselist2[$i]['type'].'">
                                        <img class="img-top" src="'.$HTTP_PATH.$pcaselist2[$i]["picurl"].'" alt="典型项目案例2">
                                        <div class="company-example-text">'.$pcaselist2[$i]["title"].'</div>
            
                                        <a class="link" href="projectcase_detail.php?id='.$pcaselist2[$i]['id'].'&type='.$pcaselist2[$i]['type'].'">MORE</a>
                                    </a>
                               </div>
                                 ';

                                 }
                            }
                    ?>


            </div>
        </div>
    </div>
    <a href="projectcase.php">
        <div class="view_more view_more1">
            <span>查看更多</span></div>
    </a>


    <div class="home_title" style="padding-top:0px">
        <div class="home_title_bottom" style="display: flex;justify-content: space-between;align-items: center">
            <img src="imgs/home_title_left.png" alt="home_title_left"/>
            <div class="english">
                <span class="font_p">合作门店</span><br>
                <span style="margin-left:0 !important;font-size:18px;font-family:PingFangSC-Regular;font-weight:400;color:rgba(133,133,133,1);">OUR PARTNERS</span>
            </div>
            <img src="imgs/home_title_right.png" alt="home_title_right"/>
        </div>
        <div class="engineering_subtitle"><img src="imgs/case_title_ad.png" alt="case_title_ad"></div>

        <div class="engineering_product">

            <div class="company-example-wrap" style="margin-top: 0 !important;">
                <?php
                if(count($dislist) >= 4){//选择的优秀案例多于4个，只显示前四个
                    for($i = 0;$i<4;$i++){
                        echo '
                              <div class="company-example-item">
                                    <a href="distribution_detail.php?id='.$dislist[$i]['id'].'">
                                        <img class="img-top" src="'.$HTTP_PATH.$dislist[$i]["picurl"].'" alt="典型工程案例1">
                                        <div class="company-example-text">'.$dislist[$i]["title"].'</div>
                                        <a class="link" href="distribution_detail.php?id='.$dislist[$i]['id'].'">MORE</a>
                                   </a>
                              </div>
                       ';
                    }
                }
                else{ //选择的优秀案例少于4个，把选择的全部显示，还要选择排序靠前的
                    $dislist1 = Web_distribution::getListQianDuan();
                    $dislist2 = Web_distribution::getOtherList();
                   //    print_r($dislist2);
                    for($i = 0;$i<count($dislist1);$i++){
                        echo '
                               <div class="company-example-item">
                               <a href="distribution_detail.php?id='.$dislist1[$i]['id'].'">
                               <img class="img-top" src="'.$HTTP_PATH.$dislist1[$i]["picurl"].'" alt="典型工程案例1">
                               <div class="company-example-text">'.$dislist1[$i]["title"].'</div>
                               <a class="link" href="distribution_detail.php?id='.$dislist1[$i]['id'].'">MORE</a>
                               </a>
                               </div>
                                 ';
                    }
                    for($i = 0;$i<4 - count($dislist1);$i++){
                        echo '
                               <div class="company-example-item">
                               <a href="distribution_detail.php?id='.$dislist2[$i]['id'].'">
                                    <img class="img-top" src="'.$HTTP_PATH.$dislist2[$i]["picurl"].'" alt="典型工程案例2">
                                    <div class="company-example-text">'.$dislist2[$i]["title"].'</div>
            
                                    <a class="link" href="distribution_detail.php?id='.$dislist2[$i]['id'].'">MORE</a>
                                    </a>
                               </div>
                                 ';

                    }
                }
                ?>



            </div>

        </div>
    </div>

	<?php if($dislist){?>
    <a href="distribution.php"> <div class="view_more">
        <span>查看更多</span>
    </div></a>
	<?php } ?>

<?php require_once('foot.inc.php')?>

<script language="javascript">
    $(function () {
        $(".count").each(function () {
            var _this = $(this);
            var _text = Number(_this.text());
            _this.attr("data-to",_text);
        });
        $(".count").each(count);
        var flag = true;
        $(window).scroll(function () {
            var win_top = $(window).scrollTop();
            if (win_top > 500) {
                if (flag) {
                    $(".count").each(count);
                    flag = false;
                }
            }
        })
    })
</script>