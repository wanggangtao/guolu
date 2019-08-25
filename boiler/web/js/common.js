$(function() {
    $(".home_tab_list li a").click(function() {
        $(this)
            .addClass("been_checked")
            .parent()
            .siblings()
            .children()
            .removeClass("been_checked");
    });
    $(".pagination li").click(function() {
        $(this)
            .addClass("active")
            .siblings()
            .removeClass("active");
    });
    $(".body_left_menu .menu_second").click(function() {
        $(this)
            .addClass("menu_first")
            .siblings()
            .removeClass("menu_first");
    });
    $(".distribution_select button").click(function() {
        $(this).css("opacity", "0.4");
    });

    // $(".home_img_icon .hover1").hover(function(){
    //     $(this).children(':first').css('display','none');
    //     $(this).children(':last').css('display','flex');
    //     $(this).siblings().children(':first').css('display','flex');
    //     $(this).siblings().children(':last').css('display','none');

    // },function(){
    //     $(this).children(':last').css('display','none');
    //     $(this).children(':first').eq(0).css('display','flex');
    //     $(this).siblings().children(':first').css('display','flex');
    //     $(this).siblings().children(':last').css('display','none');

    // });

    $(".engineering_box").hover(
        function() {
            $(this)
                .children()
                .eq(1)
                .css("display", "inline-block");
            $(this)
                .children()
                .eq(1)
                .css("display", "flex");
            $(this).css("background", "#04a6fe");
            $(this)
                .children()
                .eq(2)
                .css("color", "#fff");
        },
        function() {
            $(this)
                .children()
                .eq(1)
                .css("display", "none");
            $(this).css("background", "#eaeaea");
            $(this)
                .children()
                .eq(2)
                .css("color", "#333");
        }
    );

    $(".view_more").hover(
        function() {
            $(this).css("background", "#04a6fe");
            $(this)
                .children()
                .eq(0)
                .css("color", "#fff");
        },
        function() {
            $(this).css("background", "#fff");
            $(this)
                .children()
                .eq(0)
                .css("color", "#04a6fe");
        }
    );

    $(".view_more").click(function() {
        $(this).css("background", "rgba(4,166,254,0.4)");
    });
});

$(function() {
    var h3_height;
    if ($(".our_values").length > 0) {
        h3_height = $(".our_values").offset().top;
    }

    //首页动画，刷新加载
    function indexAni() {
        $(".try4").css({
            width: "200px",
            height: "45px",
            right: "126px",
            top: "15px"
        });
        $(".try5")
            .delay(250)
            .animate(
                {
                    width: "350px",
                    height: "50px",
                    right: "226px",
                    top: "73px"
                },
                100
            );
        $(".try3")
            .delay(350)
            .animate(
                {
                    width: "40px",
                    height: "40px",
                    right: "146px",
                    top: "20px"
                },
                100
            );
        $(".try2")
            .delay(450)
            .animate(
                {
                    width: "88px",
                    height: "88px",
                    right: "22px",
                    top: "-4px"
                },
                100
            );

        $(".try1")
            .delay(600)
            .animate(
                {
                    width: "212px",
                    height: "212px",
                    right: "-106px",
                    top: "0px",
                    opacity: 1
                },
                200
            );

        $(".try7")
            .delay(700)
            .animate(
                {
                    width: "88px",
                    height: "88px",
                    left: "22px",
                    top: "-4px"
                },
                250
            )
            .animate(
                {
                    width: "76.8px",
                    height: "76.8px",
                    left: "27.6px",
                    top: "1.6px"
                },
                250
            )
            .animate(
                {
                    width: "84.8px",
                    height: "84.8px",
                    left: "23.6px",
                    top: "-2.4px"
                },
                250
            )
            .animate(
                {
                    width: "78.4px",
                    height: "78.4px",
                    left: "26.8px",
                    top: "0.8px"
                },
                250
            )
            .animate(
                {
                    width: "80px",
                    height: "80px",
                    left: "26px",
                    top: "0px"
                },
                250
            );

        $(".try8")
            .delay(850)
            .animate(
                {
                    width: "40px",
                    height: "40px",
                    left: "146px",
                    top: "20px"
                },
                250
            );

        $(".try9")
            .delay(900)
            .animate(
                {
                    width: "200px",
                    height: "45px",
                    left: "226px",
                    top: "15px"
                },
                250
            );
        $(".try10")
            .delay(950)
            .animate(
                {
                    width: "350px",
                    height: "50px",
                    left: "226px",
                    top: "73px"
                },
                250
            );

        $(".try6")
            .delay(1000)
            .animate(
                {
                    width: "212px",
                    height: "212px",
                    left: "-106px",
                    top: "0px",
                    opacity: 1
                },
                250
            );

        // 3-4

        $(".try12")
            .delay(1100)
            .animate(
                {
                    width: "88px",
                    height: "88px",
                    right: "22px",
                    top: "-4px"
                },
                250
            )
            .animate(
                {
                    width: "76.8px",
                    height: "76.8px",
                    right: "27.6px",
                    top: "1.6px"
                },
                125
            )
            .animate(
                {
                    width: "84.8px",
                    height: "84.8px",
                    right: "23.6px",
                    top: "-2.4px"
                },
                125
            )
            .animate(
                {
                    width: "78.4px",
                    height: "78.4px",
                    right: "26.8px",
                    top: "0.8px"
                },
                125
            )
            .animate(
                {
                    width: "80px",
                    height: "80px",
                    right: "26px",
                    top: "0px"
                },
                125
            );
        $(".try13")
            .delay(1200)
            .animate(
                {
                    width: "40px",
                    height: "40px",
                    right: "146px",
                    top: "20px"
                },
                250
            );

        $(".try14")
            .delay(1250)
            .animate(
                {
                    width: "200px",
                    height: "45px",
                    right: "126px",
                    top: "15px"
                },
                250
            );
        $(".try15")
            .delay(1300)
            .animate(
                {
                    width: "350px",
                    height: "50px",
                    right: "226px",
                    top: "73px"
                },
                250
            );

        $(".try11")
            .delay(1350)
            .animate(
                {
                    width: "212px",
                    height: "212px",
                    right: "-106px",
                    top: "0px",
                    opacity: 1
                },
                250
            );

        $(".try17")
            .delay(1400)
            .animate(
                {
                    width: "88px",
                    height: "88px",
                    left: "22px",
                    top: "-4px"
                },
                250
            )
            .animate(
                {
                    width: "76.8px",
                    height: "76.8px",
                    left: "27.6px",
                    top: "1.6px"
                },
                250
            )
            .animate(
                {
                    width: "84.8px",
                    height: "84.8px",
                    left: "23.6px",
                    top: "-2.4px"
                },
                250
            )
            .animate(
                {
                    width: "78.4px",
                    height: "78.4px",
                    left: "26.8px",
                    top: "0.8px"
                },
                250
            )
            .animate(
                {
                    width: "80px",
                    height: "80px",
                    left: "26px",
                    top: "0px"
                },
                250
            );

        $(".try18")
            .delay(1550)
            .animate(
                {
                    width: "40px",
                    height: "40px",
                    left: "146px",
                    top: "20px"
                },
                250
            );

        $(".try19")
            .delay(1600)
            .animate(
                {
                    width: "200px",
                    height: "45px",
                    left: "226px",
                    top: "15px"
                },
                250
            );
        $(".try20")
            .delay(1650)
            .animate(
                {
                    width: "350px",
                    height: "50px",
                    left: "226px",
                    top: "73px"
                },
                250
            );

        $(".try16")
            .delay(1700)
            .animate(
                {
                    width: "212px",
                    height: "212px",
                    left: "-106px",
                    top: "0px",
                    opacity: 1
                },
                250
            );

        // 5-6个

        $(".try22")
            .delay(1750)
            .animate(
                {
                    width: "88px",
                    height: "88px",
                    right: "22px",
                    top: "-4px"
                },
                250
            )
            .animate(
                {
                    width: "76.8px",
                    height: "76.8px",
                    right: "27.6px",
                    top: "1.6px"
                },
                125
            )
            .animate(
                {
                    width: "84.8px",
                    height: "84.8px",
                    right: "23.6px",
                    top: "-2.4px"
                },
                125
            )
            .animate(
                {
                    width: "78.4px",
                    height: "78.4px",
                    right: "26.8px",
                    top: "0.8px"
                },
                125
            )
            .animate(
                {
                    width: "80px",
                    height: "80px",
                    right: "26px",
                    top: "0px"
                },
                125
            );

        $(".try23")
            .delay(1850)
            .animate(
                {
                    width: "40px",
                    height: "40px",
                    right: "146px",
                    top: "20px"
                },
                250
            );

        $(".try24")
            .delay(1900)
            .animate(
                {
                    width: "200px",
                    height: "45px",
                    right: "126px",
                    top: "15px"
                },
                250
            );
        $(".try25")
            .delay(1950)
            .animate(
                {
                    width: "350px",
                    height: "50px",
                    right: "226px",
                    top: "73px"
                },
                250
            );

        $(".try21")
            .delay(2000)
            .animate(
                {
                    width: "212px",
                    height: "212px",
                    right: "-106px",
                    top: "0px",
                    opacity: 1
                },
                250
            );

        $(".try27")
            .delay(2050)
            .animate(
                {
                    width: "88px",
                    height: "88px",
                    left: "22px",
                    top: "-4px"
                },
                250
            )
            .animate(
                {
                    width: "76.8px",
                    height: "76.8px",
                    left: "27.6px",
                    top: "1.6px"
                },
                250
            )
            .animate(
                {
                    width: "84.8px",
                    height: "84.8px",
                    left: "23.6px",
                    top: "-2.4px"
                },
                250
            )
            .animate(
                {
                    width: "78.4px",
                    height: "78.4px",
                    left: "26.8px",
                    top: "0.8px"
                },
                250
            )
            .animate(
                {
                    width: "80px",
                    height: "80px",
                    left: "26px",
                    top: "0px"
                },
                250
            );

        $(".try28")
            .delay(2150)
            .animate(
                {
                    width: "40px",
                    height: "40px",
                    left: "146px",
                    top: "20px"
                },
                250
            );

        $(".try29")
            .delay(2200)
            .animate(
                {
                    width: "200px",
                    height: "45px",
                    left: "226px",
                    top: "15px"
                },
                250
            );
        $(".try30")
            .delay(2250)
            .animate(
                {
                    width: "350px",
                    height: "50px",
                    left: "226px",
                    top: "73px"
                },
                250
            );

        $(".try26")
            .delay(2300)
            .animate(
                {
                    width: "212px",
                    height: "212px",
                    left: "-106px",
                    top: "0px",
                    opacity: 1
                },
                250
            );

        // 7-8个

        $(".try32")
            .delay(2400)
            .animate(
                {
                    width: "88px",
                    height: "88px",
                    right: "22px",
                    top: "-4px"
                },
                250
            )
            .animate(
                {
                    width: "76.8px",
                    height: "76.8px",
                    right: "27.6px",
                    top: "1.6px"
                },
                125
            )
            .animate(
                {
                    width: "84.8px",
                    height: "84.8px",
                    right: "23.6px",
                    top: "-2.4px"
                },
                125
            )
            .animate(
                {
                    width: "78.4px",
                    height: "78.4px",
                    right: "26.8px",
                    top: "0.8px"
                },
                125
            )
            .animate(
                {
                    width: "80px",
                    height: "80px",
                    right: "26px",
                    top: "0px"
                },
                125
            );
        $(".try33")
            .delay(2550)
            .animate(
                {
                    width: "40px",
                    height: "40px",
                    right: "146px",
                    top: "20px"
                },
                250
            );

        $(".try34")
            .delay(2600)
            .animate(
                {
                    width: "200px",
                    height: "45px",
                    right: "126px",
                    top: "15px"
                },
                250
            );
        $(".try35")
            .delay(2650)
            .animate(
                {
                    width: "350px",
                    height: "50px",
                    right: "226px",
                    top: "73px"
                },
                250
            );

        $(".try31")
            .delay(2700)
            .animate(
                {
                    width: "212px",
                    height: "212px",
                    right: "-106px",
                    top: "0px",
                    opacity: 1
                },
                250
            );

        $(".try37")
            .delay(2750)
            .animate(
                {
                    width: "88px",
                    height: "88px",
                    left: "22px",
                    top: "-4px"
                },
                250
            )
            .animate(
                {
                    width: "76.8px",
                    height: "76.8px",
                    left: "27.6px",
                    top: "1.6px"
                },
                250
            )
            .animate(
                {
                    width: "84.8px",
                    height: "84.8px",
                    left: "23.6px",
                    top: "-2.4px"
                },
                250
            )
            .animate(
                {
                    width: "78.4px",
                    height: "78.4px",
                    left: "26.8px",
                    top: "0.8px"
                },
                250
            )
            .animate(
                {
                    width: "80px",
                    height: "80px",
                    left: "26px",
                    top: "0px"
                },
                250
            );

        $(".try36")
            .delay(3050)
            .animate(
                {
                    width: "212px",
                    height: "212px",
                    left: "-106px",
                    top: "0px",
                    opacity: 1
                },
                250
            );

        $(".try38")
            .delay(2900)
            .animate(
                {
                    width: "40px",
                    height: "40px",
                    left: "146px",
                    top: "20px"
                },
                250
            );

        $(".try39")
            .delay(2950)
            .animate(
                {
                    width: "200px",
                    height: "45px",
                    left: "226px",
                    top: "15px"
                },
                250
            );
        $(".try40")
            .delay(3000)
            .animate(
                {
                    width: "350px",
                    height: "50px",
                    left: "226px",
                    top: "73px"
                },
                250
            );
    }

    indexAni();

    $(window).scroll(function() {
        var this_scrollTop = $(this).scrollTop();
        if (this_scrollTop > h3_height - 400) {
            // 执行动画
            // 重复每个写，代码没有复用
            // 1-2个
        }
        // 执行动画
        //    else{
        //   $('.looping li:nth-child(2n+1) img:nth-child(2n+1)').css({
        //   'position':'absolute',
        //    'bottom':'31.8px',
        //    'right':'31.8px',
        //    'height':'63.6px',
        //    'width':'63.6px',
        //    'opacity':'0'
        // });

        // $('.looping li div').css({
        //    'box-sizing':'border-box',
        //    'display':'inline-block',
        //    'width':'0px',
        //    'height':'0px',
        //    'border-radius':'50%'
        // });
        // $('.looping li p').css({
        //    'font-size':'40px',
        //    'font-weight': 'bold',
        //    'width':'0px',
        //    'height':'0px',
        //    'overflow':'hidden'
        // });
        // $('.looping li span').css({
        //    'font-size':'18px',
        //    'font-weight': 'bold',
        //    'width':'0px',
        //    'height':'0px',
        //    'overflow':'hidden'
        // });
        // $('.looping li:nth-child(2n+1) div').css({
        //    'background':'#04a6fe',
        //    'position':'absolute',
        //    'right':'146px',
        //    'top':'40px'
        // });
        // $('.looping li:nth-child(2n+1) p').css({
        //    'color':'#04a6fe',
        //   'position':'absolute',
        //    'right':'226px',
        //    'top':'15px'
        // });
        // $('.looping li:nth-child(2n+1) span').css({
        //    'color':'#04a6fe',
        //    'position':'absolute',
        //    'right':'226px',
        //    'top':'73px',
        //    'text-align': 'right'
        // });
        // $('.looping li:nth-child(2n+1) img:nth-child(2n)').css({
        //     'width':'0px',
        //   'height':'0px',
        //   'position':'absolute',
        //   'right':'66px',
        //   'top':'40px'
        // });

        // $('.looping li:nth-child(2n) img:nth-child(2n+1)').css({
        //   'position':'absolute',
        //    'top':'75px',
        //    'height':'63.6',
        //    'width':'63.6px',
        //    'opacity':'0'
        // });
        // $('.looping li:nth-child(2n) img:nth-child(2n)').css({
        //     'width':'0px',
        //   'height':'0px',
        //   'position':'absolute',
        //   'left':'26px',
        //  'top':'0px'
        // });

        // $('.looping li:nth-child(2n) div').css({
        //    'background':'#37a175',
        //    'position':'absolute',
        //    'left':'146px',
        //    'top':'40px'
        // });
        // $('.looping li:nth-child(2n) p').css({
        //    'color':'#37a175',
        //    'position':'absolute',
        //    'left':'226px',
        //   'top':'15px'
        // });
        // $('.looping li:nth-child(2n) span').css({
        //    'color':'#37a175',
        //    'position':'absolute',
        //    'left':'226px',
        //    'top':'15px',
        //    'top':'73px',
        //    'width':'355px',
        //    'text-align': 'left'
        // });

        // $('.looping li:nth-child(3n+1) div').css({
        //    'background':'#04a6fe'
        // });
        // $('.looping li:nth-child(3n+1) p,.looping li:nth-child(3n+1) span').css({
        //   'color':'#04a6fe'
        // });
        // $('.looping li:nth-child(3n+2) div').css({
        //    'background':'#37a175'
        // });
        // $('.looping li:nth-child(3n+2) p,.looping li:nth-child(3n+2) span').css({
        //    'color':'#37a175'
        // });
        // $('.looping li:nth-child(3n) div').css({
        //    'background':'#006cbc'
        // });
        // $('.looping li:nth-child(3n) p,.looping li:nth-child(3n+3) span').css({
        //    'color':'#006cbc'
        // });

        //    }
    });
});

(function($) {
    $.extend({
        reverseChild: function(obj, child) {
            var childObj = $(obj).find(child);
            var total = childObj.length;

            childObj.each(function(i) {
                $(obj).append(childObj.eq(total - 1 - i));
            });

            //console.log(childObj.html());
        }
    });
})(jQuery);

$(function() {
    var winW = $(window).width();
    if (winW <= 479) {
        // var spanText = $(".index-span p span").text();
        // if (spanText.length > 50) {
        //     spanText = spanText.substring(0, 50);
        // }
        // $(".index-span p span").text(spanText + "...");
        $.reverseChild(".home_tab_list", 'li');
        $(".phone-menu").on("click",function () {
            var _this = $(this);
            if (_this.hasClass("close")) {
                $(".home_tab_list").animate({
                    right: "-120px"
                });
                _this.removeClass("close");
            } else {
                $(".home_tab_list").animate({
                    right: "0px"
                });
                _this.addClass("close");
            }
        })
        $(window).scroll(function () {
            if ($("#company-item1").length > 0) {
                var top = $("#company-item1").offset().top;
                var scrollTop = $(this).scrollTop();
                if (scrollTop < 300) {
                    $(".company-tab-item").eq(0).addClass("active").siblings().removeClass("active");
                }
            }
        })
    }
});
