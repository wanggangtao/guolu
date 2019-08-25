<?php
require_once ('web_init.php');
//项目板块内容
$project_introduction=web_introduction::getInfoById(1);
//分销板块内容
$content_distribution = web_introduction::getInfoById(2);
//售后板块内容
$content_aftersale = web_introduction::getInfoById(3);
//项目板块优势
$project_advandages=web_intro_advantage::getListByType(1);
//分销板块优势
$advantage_distribution = Web_intro_advantage::getListByType(2);
//售后板块优势
$advantage_aftersale = Web_intro_advantage::getListByType(3);
//售后板块图片
$advantage_picture = Web_intro_aftersale_pic::getList();

$bannerlist = Picture::getPageList(1, 99, 1, 6, 1);

$params=array();
$params['display']=1;
$project_pictures=web_intro_projectpic::getListQinDuan($params);

$TOP_MENU="company_introduction";


?>
<!DOCTYPE html>
<head>
    <title>公司介绍</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <link rel="stylesheet" type="text/css" href="css/common.css"/>
    <link rel="stylesheet" href="css/swiper.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="css/query.css">
    <script type="text/javascript" src="js/jquery-2.1.4.js"></script>
    <script type="text/javascript" src="js/swiper.min.js"></script>
    <script type="text/javascript" src="js/common.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fuwenben@1.0.1/fuwenben.js"></script>
    <style type="text/css">
        a:link,a:visited{
            text-decoration:none;  /*超链接无下划线*/
        }
    </style>
</head>
<body>
<?php
if($TOP_MENU!="distribution"){
    unset($_SESSION['distribution_if_change']);}
if($TOP_MENU!="projectcase"){unset($_SESSION['if_change']);}
if($TOP_MENU!="company_situation"){unset($_SESSION['type_s']);}
?>
<div class="container">
    <?php require_once ('top.inc.php')?>
    <div class="dynamics_bannner">
        <?php
        if($bannerlist[0]){
            echo '
                                <a href="'.$bannerlist[0]['http'].'"><img src="'.$HTTP_PATH.$bannerlist[0]['url'].'"></a>    
                        ';
        }
        else{
            $bannerlist=Picture::getPageList(1, 99, 1, 6, -1);
            echo '
                                <a href="'.$bannerlist[0]['http'].'"><img src="'.$HTTP_PATH.$bannerlist[0]['url'].'"></a>    
                        ';
        }
        ?>
    </div>
    <!-- 公司介绍 -->
    <div class="company-wrap">
        <div class="company-tab">
            <div class="company-tab-item active">
                <a>项目板块</a>
            </div>
            <div class="company-tab-item">
                <a>分销板块</a>
            </div>
            <div class="company-tab-item">
                <a>售后板块</a>
            </div>
        </div>
        <div class="company-con">
            <div class="company-item block" id="company-item1">
                <div class="company-item-title">
                    <div class="h3-title">
                        <h3>项目板块</h3>
                        <span style="margin-left:0 !important;font-size:18px;font-family:PingFangSC-Regular;font-weight:400;color:rgba(133,133,133,1);">PROJECT SALES</span>
                    </div>
                    <div>
                        <img src="imgs/icon-phone.png" alt="电话">
                        <span>项目合作咨询电话：</span>
                        <p><?php echo $project_introduction['tel']?> </p>
                    </div>
                </div>
                <div class="company-p"><?php echo $project_introduction['content']?></div>
                <div class="company-project">
                    <div class="company-project-item">
                        <img src="<?php echo $HTTP_PATH.$project_advandages[0]['purl']?>" alt="">
                        <span><?php echo $project_advandages[0]['title']?></span>
                        <p><?php echo $project_advandages[0]['content']?></p>
                    </div>
                    <div class="company-project-item">
                        <img src="<?php echo $HTTP_PATH.$project_advandages[1]['purl']?>" alt="">
                        <span><?php echo $project_advandages[1]['title']?></span>
                        <p><?php echo $project_advandages[1]['content']?></p>
                    </div>
                    <div class="company-project-item">
                        <img src="<?php echo $HTTP_PATH.$project_advandages[2]['purl']?>" alt="">
                        <span><?php echo $project_advandages[2]['title']?></span>
                        <p><?php echo $project_advandages[2]['content']?></p>
                    </div>
                    <div class="company-project-item">
                        <img src="<?php echo $HTTP_PATH.$project_advandages[3]['purl']?>" alt="">
                        <span><?php echo $project_advandages[3]['title']?></span>
                        <p><?php echo $project_advandages[3]['content']?></p>
                    </div>
                </div>
                <div class="company-example">
                    <div class="company-example-title">典型工程案例<a class="company-link" href="projectcase.php">查看更多</a></div>
                    <div class="company-example-wrap swiper-container">
                        <div class="swiper-wrapper">
                            <?php
                                $caselist = Web_projectcase::getListQianDuan();
                          //  print_r($caselist);
                                if(count($caselist) >= 8){//选择的优秀案例多于8个，只显示前四个
                                    for($i = 0;$i<8;$i++){
                                        echo '
                                            <div class="company-example-item swiper-slide">
                                            <a href="projectcase_detail.php?id='.$caselist[$i]['id'].'&type='.$caselist[$i]['type'].'">
                                                 <img class="img-top" src="'.$HTTP_PATH.$caselist[$i]["picurl"].'" alt="典型工程案例1">
                                                <div class="company-example-text">'.$caselist[$i]["title"].'</div>
                                                <a class="link" href="projectcase_detail.php?id='.$caselist[$i]['id'].'&type='.$caselist[$i]['type'].'">MORE</a>
                                                </a>
                                            </div>
                                       
                                        ';
                                    }
                                }
                                else{ //选择的优秀案例少于8个，把选择的全部显示，还要选择排序靠前的
                                    $caselist1 = Web_projectcase::getListQianDuan();
                                    $caselist2 = Web_projectcase::getOtherList();
                                    for($i = 0;$i<count($caselist1);$i++){
                                        echo '
                                            <div class="company-example-item swiper-slide">
                                            <a href="projectcase_detail.php?id='.$caselist1[$i]['id'].'&type='.$caselist1[$i]['type'].'">
                                                 <img class="img-top" src="'.$HTTP_PATH.$caselist1[$i]["picurl"].'" alt="典型工程案例1">
                                                <div class="company-example-text">'.$caselist1[$i]["title"].'</div>
                                                <a class="link" href="projectcase_detail.php?id='.$caselist1[$i]['id'].'&type='.$caselist1[$i]['type'].'">MORE</a>
                                                </a>
                                            </div>
                                       
                                        ';
                                    }
                                    for($i = 0;$i<8 - count($caselist1);$i++){
                                        echo '
                                            <div class="company-example-item swiper-slide">
                                            <a href="projectcase_detail.php?id='.$caselist2[$i]['id'].'&type='.$caselist2[$i]['type'].'">
                                                 <img class="img-top" src="'.$HTTP_PATH.$caselist2[$i]["picurl"].'" alt="典型工程案例2">
                                                <div class="company-example-text">'.$caselist2[$i]["title"].'</div>
                                                <a class="link" href="projectcase_detail.php?id='.$caselist2[$i]['id'].'&type='.$caselist2[$i]['type'].'">MORE</a>
                                                </a>
                                            </div>
                                       
                                        ';
                                    }
                                }
                            ?>

                        </div>
                    </div>
                    <div class="btn-prev btn-prev-example"></div>
                    <div class="btn-next btn-next-example"></div>
                </div>
                <div class="company-partner">
                    <div class="company-example-title">合作伙伴</div>
                    <div class="company-partner-wrap swiper-container">
                        <div class="swiper-wrapper">
                            <?php foreach ($project_pictures as $project_picture){?>
                            <div class="company-partner-item swiper-slide">
                                <a href="<?php  echo $project_picture['http']?>">
                                    <img class="img-partner" src="<?php echo $HTTP_PATH.$project_picture['picurl'];?>" alt="">
                                </a>
                            </div>
                            <?php }?>
                        </div>
                    </div> 
                    <div class="btn-prev btn-prev-partner"></div>
                    <div class="btn-next btn-next-partner"></div>
                </div>
            </div>
            <div class="company-item"  id="company-item2">
                <div class="company-item-title">
                    <div class="h3-title">
                        <h3>分销板块</h3>
                        <span style="margin-left:0 !important;font-size:18px;font-family:PingFangSC-Regular;font-weight:400;color:rgba(133,133,133,1);">DISTRIBUTION CHANNEL</span>
                    </div>
                    <div>
                        <img src="imgs/icon-phone.png" alt="电话">
                        <span>分销合作咨询电话：</span>
                        <p><?php echo $content_distribution["tel"];?></p>
                    </div>
                </div> 
                <div class="company-p"><?php echo  $content_distribution["content"];?></div>

                <div class="distribution-wrap">
                    <div class="distribution-item">
                        <img src="<?php echo $HTTP_PATH.$advantage_distribution[0]['purl']?>">
                        <span><?php echo $advantage_distribution[0]["title"];?></span>
                        <p class="p_content"><?php echo $advantage_distribution[0]["content"];?></p>
                    </div>
                    <div class="distribution-item">
                        <img src="<?php echo $HTTP_PATH.$advantage_distribution[1]['purl']?>">
                        <span><?php echo $advantage_distribution[1]["title"];?></span>
                        <p class="p_content"><?php echo $advantage_distribution[1]["content"];?></p>
                    </div>
                    <div class="distribution-item">
                        <img src="<?php echo $HTTP_PATH.$advantage_distribution[2]['purl']?>" alt="分销板块3">
                        <span><?php echo $advantage_distribution[2]["title"];?></span>
                        <p class="p_content"><?php echo $advantage_distribution[2]["content"];?></p>
                    </div>
                    <div class="distribution-item">
                        <img src="<?php echo $HTTP_PATH.$advantage_distribution[3]['purl']?>" alt="分销板块4">
                        <span><?php echo $advantage_distribution[3]["title"];?></span>
                        <p class="p_content"><?php echo $advantage_distribution[3]["content"];?></p>
                    </div>
                </div>
                <div class="company-example">
                    <div class="company-example-title">优秀合作门店<a class="company-link" href="distribution.php">查看更多</a></div>
                    <div class="store swiper-container">
                        <div class="swiper-wrapper">

                            <?php
                            $caselist = Web_distribution::getListQianDuan();
                          //  print_r($caselist);
                            if(count($caselist) >= 8){//选择的优秀案例多于8个，只显示前8个
                                for($i = 0;$i<8;$i++){
                                    echo '
                                            <div class="company-example-item swiper-slide">
                                                <a href="distribution_detail.php?id='.$caselist[$i]["id"].'">
                                                <img class="img-top" src="'.$HTTP_PATH.$caselist[$i]["picurl"].'" alt="典型工程案例1">
                                                <div class="company-example-text">'.$caselist[$i]["title"].'</div>
                                         ';
                             ?>
                                                <a class="link" href="distribution_detail.php?id=<?php echo $caselist[$i]["id"];?>">MORE</a>
                                                </a>
                                            </div>
                               <?php

                                }
                            }
                            else{ //选择的优秀案例少于8个，把选择的全部显示，还要选择排序靠前的
                                $caselist1 = Web_distribution::getListQianDuan();
                                $caselist2 = Web_distribution::getOtherList();
                             //   print_r($caselist1);
                                for($i = 0;$i<count($caselist1);$i++){
                                    echo '
                                            <div class="company-example-item swiper-slide">
                                                <a href="distribution_detail.php?id='.$caselist[$i]["id"].'">
                                                 <img class="img-top" src="'.$HTTP_PATH.$caselist1[$i]["picurl"].'" alt="典型工程案例1">
                                                <div class="company-example-text">'.$caselist1[$i]["title"].'</div>
                                     ';
                             ?>
                                                <a class="link" href="distribution_detail.php?id=<?php echo $caselist[$i]["id"];?>">MORE</a>
                                                </a>
                                            </div>

                    <?php
                                }
                                for($i = 0;$i<8 - count($caselist1);$i++){
                                    echo '
                                            <div class="company-example-item swiper-slide">
                                            <a href="distribution_detail.php?id='.$caselist2[$i]["id"].'">
                                                 <img class="img-top" src="'.$HTTP_PATH.$caselist2[$i]["picurl"].'" alt="典型工程案例2">
                                                <div class="company-example-text">'.$caselist2[$i]["title"].'</div>
                                      ';
                             ?>
                                                <a class="link" href="distribution_detail.php?id=<?php echo $caselist2[$i]["id"];?>">MORE</a>
                                                </a>
                                            </div>
                <?php

                                }
                            }
                            ?>

                        </div>
                    </div>
                    <div class="btn-prev btn-prev-store"></div>
                    <div class="btn-next btn-next-store"></div>
                </div>
            </div>
            <div class="company-item"  name="company-item3">
                <div class="company-item-title">
                    <div class="h3-title">
                        <h3>售后板块</h3>
                        <span style="margin-left:0 !important;font-size:18px;font-family:PingFangSC-Regular;font-weight:400;color:rgba(133,133,133,1);">AFTER-SALE SERVICE</span>
                    </div>
                    <div>
                        <img src="imgs/icon-phone.png" alt="电话">
                        <span>售后服务咨询电话：</span>
                        <p><?php echo $content_aftersale["tel"];?></p>
                    </div>
                </div> 
                <div class="sell-wrap">
                    <div class="sell-left">
                        <img src="imgs/sell-wrap.png" alt="售后图片">
                    </div>
                    <div class="sell-right">
                        <div class="sell-right-con">
                            <div class="sell-line"></div>
                            <p><?php echo $content_aftersale["content"];?></p>
                        </div>
                    </div>
                </div>
                <div class="sell-tab">
                    <div class="sell-tab-con">
                        <div class="sell-tab-item sell-tab-item30">
                            <div class="sell-tilte"><?php echo $advantage_aftersale[0]["title"];?></div>
                            <div class="sell-con">
                                <span><?php echo $advantage_aftersale[0]["title"];?></span>
                                <p><?php echo $advantage_aftersale[0]["content"];?></p>
                            </div>
                        </div>
                        <div class="sell-tab-item">
                            <div class="sell-tilte"><?php echo$advantage_aftersale[1]["title"];?></div>
                            <div class="sell-con">
                                <span><?php echo$advantage_aftersale[1]["title"];?></span>
                                <p><?php echo $advantage_aftersale[1]["content"];?></p>
                            </div>
                        </div>
                        <div class="sell-tab-item">
                            <div class="sell-tilte"><?php echo$advantage_aftersale[2]["title"];?></div>
                            <div class="sell-con">
                                <span><?php echo$advantage_aftersale[2]["title"];?></span>
                                <p><?php echo $advantage_aftersale[2]["content"];?></p>
                            </div>
                        </div>
                        <div class="sell-tab-item sell-tab-item30">
                            <div class="sell-tilte"><?php echo$advantage_aftersale[3]["title"];?></div>
                            <div class="sell-con">
                                <span><?php echo$advantage_aftersale[3]["title"];?></span>
                                <p><?php echo $advantage_aftersale[3]["content"];?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

    </div>
    <div class="our-style">
        <div class="our-style-top">
            <div class="our-style-con">
                <div class="our-style-title">
                    <span>我们的风采</span>
                    <span>OUR TEAM</span>
                </div>
            </div>
        </div>
        <div class="our-wrap swiper-container">
            <div class="swiper-wrapper">

                <?php
                if($advantage_picture){
                    foreach($advantage_picture as $picture){
                        if($picture["display"] == 1){
                            echo '
                            <div class="our-con swiper-slide">
                                <div class="our-item1">
                                    <img src="'.$HTTP_PATH.$picture['picurl1'].'" alt="">
                                </div>
                                <div class="our-item2">
                                   <img src="'.$HTTP_PATH.$picture['picurl2'].'" alt="">
                               </div>
                               <div class="our-item3">
                                   <img src="'.$HTTP_PATH.$picture['picurl3'].'" alt="">
                               </div>
                               <div class="our-item3">

                               </div>
                               <div class="our-item3">

                              </div>
                              <div class="our-item3">
                                  <img src="'.$HTTP_PATH.$picture['picurl4'].'" alt="">
                              </div>
                           </div>
                        
                        ';
                        }
                        else{
                            echo '';
                        }

                    }
                }
                ?>


            <div class="our-contral">
                <span class="our-prev"></span>
                <span></span>
                <span class="our-next"></span>
            </div>
        </div>
    </div>




    </div>

<?php require_once('foot.inc.php')?>
<script language="javascript">
    var winH = $(window).width();
    var num = 4;
    var parNum = 5;
    if (winH <= 479) {
        num = 2;
        parNum = 3;
    }

    var mySwiper = new Swiper('.homePage_wrapper .swiper-container', {

        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        autoplay: {
            autoplay: true,
            disableOnInteraction: false,

        },

    });

    var exampleSwiper = new Swiper('.company-example-wrap', {
        slidesPerView: num,
        spaceBetween: 45,
        loop: false,
        navigation: {
            nextEl: '.btn-next-example',
            prevEl: '.btn-prev-example',
        },
    });

    var storeSwiper = new Swiper('.store', {
        slidesPerView: num,
        spaceBetween: 20,
        loop: false,
        navigation: {
            nextEl: '.btn-next-store',
            prevEl: '.btn-prev-store',
        },
    });

    var partnerSwiper = new Swiper('.company-partner-wrap', {
        slidesPerView: num,
        spaceBetween: 20,
        loop: false,
        navigation: {
            nextEl: '.btn-next-partner',
            prevEl: '.btn-prev-partner',
        },
    });

    var ourSwiper = new Swiper('.our-wrap', {
        navigation: {
            nextEl: '.our-prev',
            prevEl: '.our-next',
        },
        effect: "fade",
        loop: true
    });

    $(function () {
        $(".company-tab-item").click(function () {
            var _index = $(this).index();
            $(this).addClass("active").siblings().removeClass("active");
            var a = $(".company-item").eq(_index).offset().top - 110;
            $("html,body").animate({
                scrollTop: a
            }, 300);
        });

        var len = 26; //定义博客描述字数显示多少字符
        var contentArr = []; //用于存放大于len字符的数组
        $(".p_content").each(function (i) {
            var _this = $(this);
            var _index = _this.index();
            var content = $.trim(_this.text());
            contentArr.push(content);
            if (content.length > len) {
                var p_con =
                    content.substr(0, len) + "...";
                _this.html(p_con);
            }
        });

        $(".distribution-item").hover(function () {
            var _index = $(this).index();
            var parent = $(this).children(".p_content");
            var parentCon = $.trim(parent.text());
            if (parentCon.length - 3 === len) {
                parent.html(contentArr[_index]);
            }
        }, function () {
            var _index = $(this).index();
            var parent = $(this).children(".p_content");
            var parentCon = $.trim(parent.text());
            if (parentCon.length - 3 != len && parentCon.length > len) {
                parent.html(contentArr[_index].substr(0, len) + "...");
            } else {
                parent.html(contentArr[_index]);
            }
        });

        $(window).scroll(function () {
            var top = $("#company-item1").offset().top;
            var scrollTop = $(this).scrollTop();
            if (top - scrollTop < 220) {
                $(".company-tab-item").eq(0).addClass("active").siblings().removeClass("active");
            }
        })

    })
</script>
</body>
