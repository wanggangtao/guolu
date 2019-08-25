<?php
/**
 * Created by PhpStorm.
 * User: forever
 * Date: 2018/12/10
 * Time: 15:16
 */
require_once ('web_init.php');
$TOP_MENU="distribution";
$params=array();
$districts=array();
$bannerlist = Picture::getPageList(1, 99, 1, 4, 1);

$this_change=isset($_SESSION['this_change'])? safeCheck($_SESSION['this_change']): 0;
$if_change=isset($_SESSION['distribution_if_change'])? safeCheck($_SESSION['distribution_if_change']): 0;
//if($if_change==1){
//    unset($_SESSION['if_change']);}
$page = isset($_GET['page'])? safeCheck($_GET['page']): 1;

if($if_change==0){
    $province_val=27;
     $city_val=0;}
else{
    $province_val = isset($_SESSION['province'])? safeCheck($_SESSION['province']): 0;
    $city_val = isset($_SESSION['city'])? safeCheck($_SESSION['city']): 0;
}
if($this_change==1) {$page = 1;}
unset($_SESSION['this_change']);
$params['province']=$province_val;
$params['city']=$city_val;

$totalcount= Web_distribution::getAllCount($params);
$shownum   = 6;
$pagecount = ceil($totalcount / $shownum);
$params["page"] = $page;
$params["pageSize"] = $shownum;
$rows=Web_distribution::getList($params);
$districts['province']=$province_val;
$districts['city']=$city_val;
$locations=Web_distribution::getDistrict($districts);
$str=json_encode($locations);

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>渠道分销</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <link rel="stylesheet" type="text/css" href="css/common.css"/>
    <link rel="stylesheet" href="css/swiper.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="css/query.css">
    <script type="text/javascript" src="js/jquery-2.1.4.js"></script>
    <script type="text/javascript" src="js/swiper.min.js"></script>
    <script type="text/javascript" src="js/layer/layer.js"></script>

    <!--    <script src="http://api.map.baidu.com/getscript?v=v=2.0:1 " type="text/javascript"></script>-->
    <script type="text/javascript" src="js/common.js"></script>
    <link rel="stylesheet" href="http://cache.amap.com/lbs/static/main1119.css"/>
    <style>
        .marker{
            width:22px;
            height:36px;
            background-image:url(http://webapi.amap.com/theme/v1.3/markers/b/mark_b.png);
            background-size:22px 36px;
            text-align: center;
            line-height: 24px;
            color: #fff
        }

        .center{
            width: 100%;
        }
        .btmtip {
            cursor: pointer;
            border-radius: 5px;
            background-color: #0D9BF2;
            padding: 3px;
            width: 80px;
            color: white;
            margin: 0 auto;
            text-align: center;
        }

        .luxian{
            position: fixed;
            width:60px;
            height:60px;
            left:10px;
            bottom: 24px;
        }
        .amap-toolbar{
            z-index:3;
        }
        #map2{
            border:4px solid rgba(0,0,0,0.06);
        }
       .marker1{
            width: 100%;
            height: 100%;
            text-align: center;
            color: #4A4A4A !important;
            font-size: 18px!important;
        }
        .amap-marker-label{
            background-color: #fff !important;
            box-shadow: 0 2px 9px 0 rgba(0,0,0,0.15);
            width:156px!important;
            height:42px!important;
            line-height: 42px;
            border:0px!important;
            display: none;
            top: -60px !important;
            left: -72px !important;
            box-shadow: 0 3px 14px rgba(0,0,100,.6);
        }

        .amap-marker-label:hover {
            box-shadow: 0 3px 14px rgba(0,0,0,.75);
        }
        .marker2{
            background:#fff!important;
            border:1px solid blue!important;
        }

        #none{
            position: relative;
            left: 550px;
            top: 150px;
            color: #818E96;
        }

        .amap-icon,.amap-icon img {
            width: 25px !important;
            height: 28px !important;
        }

.amap-icon {
    position: relative;
    -webkit-perspective: 500;
    -moz-perspective: 500;
    -ms-perspective: 500;
    perspective: 500;
    -ms-transform: perspective(500px);
    -moz-transform: perspective(500px); /*重要*/
    transform-style: preserve-3d; /*重要*/
    overflow: visible;
    z-index: 999;
}

.amap-icon::after {
    position: absolute;
    content: "";
    width: 30px;
    height: 30px;
    border: 8px solid #999;
    background-color: #555;
    border-radius: 100%; 
    transform-style: preserve-3d; /*重要*/
    transform: rotateX(80deg);
    bottom: -15px;
    left: -3px;
    opacity: 0;
    animation: animateCir 1.5s ease-out;
    animation-iteration-count: infinite;
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
}

@-webkit-keyframes animateCir {
    0% {
        transform: scale(0.4);
        opacity: 1;
    }
    10% {
        transform: scale(0.6);
        opacity: 0.8;
    }
    20% {
        transform: scale(0.7);
        opacity: 0.6;
    }
    30% {
        transform: scale(0.8);
        opacity: 0.4;
    }
    75% {
        transform: scale(0.9);
        opacity: 0.3;
    }
    100% {
        transform: scale(1);
        opacity: 0.2;
    }
}

.amap-marker-label::after {
    position: absolute;
    content: "";
    width:0;
    height:0;
    border-width:6px 6px 0;
    border-style:solid;
    border-color:#fff transparent transparent;
    left: 50%;
    transform: translate(-50%,0);
    bottom: -5px;
}

    </style>
    <script src="http://webapi.amap.com/maps?v=1.3&key=d7c5cdb73a595b9ee6556c08ff37abf9"></script>
    <script type="text/javascript" src="http://cache.amap.com/lbs/static/addToolbar.js"></script>
</head>
    <!--        /*-->
    <!--        获取子地址-->
    <!--        */-->
    <script>
        function selectOnchang(obj){
            var value = obj.options[obj.selectedIndex].value;

            $("#address_two").html("<option value='0'>全部</option>");
            if (value == 0) return false;
            $.ajax({
                type: 'POST',
                data: {

                    id: value,
                    type: 0
                },

                dataType: 'json',
                url: 'address_do.php?act=getChild',
                success: function (data) {

                    var code = data.code;

                    var resultData = data.msg;
                    switch (code) {

                        case 1:

                            var html = "<option value='0'>全部</option>";
                            for (var i = 0; i < resultData.length; i++) {
                                html += "<option value='" + resultData[i].id + "'>" + resultData[i].name + "</option>";
                            }
                            $("#address_two").html(html);


                            break;
                        default:
                            layer.alert(msg, {icon: 5});
                    }
                }
            });


        }
    </script>


<!--搜索-->

    <script type="text/javascript">
      function search(){
          var value2=document.getElementById("address_two");
          var index2=value2.selectedIndex;
          var city=value2.options[index2].value;

          var value1=document.getElementById("address_one");
          var index1=value1.selectedIndex;
          var province=value1.options[index1].value;
          var now_province_val=<?php echo $province_val; ?>;
          var now_city_val=<?php echo $city_val; ?>;
          var if_change;
          var this_change;
//          if(province==""||province==0){
//              layer.alert("请选择省份");
//              return false;
//          }

          if(now_province_val!=province||now_city_val!=city){
              if_change=1;
              this_change=1;
          }
          $.ajax({
              type        : 'POST',
              data        : {
                  province   : province,
                  city       : city,
                  if_change  : if_change,
                  this_change: this_change
              },
              dataType :    'json',
              url :         'distribution_do.php',
              success :     function(data){
                  location.reload();
              }
          });

      }
</script>
<body>
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
            $bannerlist=Picture::getPageList(1, 99, 1, 4, -1);
            echo '
                                <a href="'.$bannerlist[0]['http'].'"><img src="'.$HTTP_PATH.$bannerlist[0]['url'].'"></a>    
                        ';
        }
        ?>
    </div>

    <div class="dynamics_body">
        <div class="body_head">
            <span>渠道分销</span>
<!--            <img src="imgs/distribution_word.png" class="width306"/>-->
            <span style="display: inline-block;margin-left: 14px !important;font-size:18px;font-family:PingFangSC-Regular;font-weight:400;color:rgba(213,213,213,1);">DISTRIBUTION CHANNEL</span>
            <p>渠道分销</p>
            <p>></p>
            <p>首页</p>
        </div>
        <!--   地图 -->
        <div >
            <div id="map2" class="distribution_map"> </div>

            <script type="text/javascript">

                var map = new AMap.Map("map2", {
                    resizeEnable: true,
                    center: [116.397428, 39.90923],
                    zoom: 13
                });

                var locations=eval('<?php echo $str?>');

                for(var i=0;i<locations.length;i++) {
                    var location1 = locations[i];
                    var title =location1['title'];
                    var marker = new AMap.Marker({
                        position: new AMap.LngLat(location1['lat'], location1['lng']),   // 经纬度对象，也可以是经纬度构成的一维数组[116.39, 39.9]
                        title: location1['title']

                    });
                    var positionselect=[location1['lat'],location1['lng']];
                    var markerselect = new AMap.Marker({
                        position: positionselect,
                        title:location1['title'],
                        icon: new AMap.Icon({            
                            size: new AMap.Size(25, 28),  //图标大小
                            image: "imgs/dingwei.png",
                        })        
                    });

                    map.add(marker);
                    marker.setLabel({//label默认蓝框白底左上角显示，样式className为：amap-marker-label
                        offset: new AMap.Pixel(20, 0),//修改label相对于maker的位置
                        content: '<div class="marker1">'+title+'</div>'
                    });


                    // map.add(markerselect);
                    markerselect.setLabel({//label默认蓝框白底左上角显示，样式className为：amap-marker-label
                        offset: new AMap.Pixel(20, 20),//修改label相对于maker的位置
                        content: '<div class="marker2">'+title+'</div>'
                    });
                    $('.marker1').parent().addClass('marketchecks')
                    $("body").find(".amap-icon img").attr("src","imgs/dingwei.png");

                };


                map.setFitView();

            </script>

        </div>
        <!--   地图 -->
        <div>
            <div class="home_title">
                <div class="home_title_bottom" style="display: flex;justify-content: space-between;align-items: center">
                    <img src="imgs/home_title_left.png" alt="home_title_left"/>
                    <div class="english">
                        <span class="font_p ">分销点展示</span><br>
                        <span style="margin-left:0 !important;font-size:18px;font-family:PingFangSC-Regular;font-weight:400;color:rgba(133,133,133,1);">LOCAL PARTNERS</span>
                    </div>
                    <img src="imgs/home_title_right.png" alt="home_title_right"/>
                </div>
            </div>
        </div>

        <div class="distribution_select">
            <div class="select_component">
                <div>省份</div>
                <select   onchange="selectOnchang(this)" id="address_one">
<!--                    <option value="0">请选择省份</option>-->
                    <?php
                    if($province_val!=0){
                        if($province_val!=27){

                        }
                        else{
                            $province_name=District::getInfoById($province_val);
                            echo '<option value="0">全部</option>';
                            echo '<option value="'.$province_val.'"selected>'.$province_name['name'].'</option>';
                        }

//                    $provinces=District::getInfoById("27");
//                    $upId=$provinces['id'];
//                    $name=$provinces['name'];
//                    if($name!=$province_name['name']){
//                    echo '<option value="' . $upId . '">' . $name . '</option>';}
                    }
                    else
                        { echo '<option value="0">全部</option>';

                    $provinces=District::getAddressType(3,0);
                    if(!empty($provinces)){
                        foreach ($provinces as $province){

                            $upId=$province['id'];
                            $name=$province['name'];
                            if($name=="陕西省")
                            echo '<option value="' . $upId . '">' . $name . '</option>';
                        }
                    }}
                    ?>
                </select>
            </div>
            <div class="select_component">
                <div>市/区</div>
<!--                <div><input type="text" placeholder="--><?php //echo($city_name['name'])?><!--"></div>-->
                <select id="address_two">

                    <?php
                    if($province_val!=0&&$city_val!=0){
                        $city_name=district::getInfoById($city_val);
                        echo '<option value="'.$city_val.'">'.$city_name['name'].'</option>';
                        echo '<option value="0">全部</option>';
                        $city_lists=district::getInfoByUpid($province_val);
                        foreach ($city_lists as $city_list){
                            if($city_list['name']!=$city_name['name']){
                            echo '<option value="' . $city_list['id'] . '">' . $city_list['name']. '</option>';
                        }
                    }}
                    if($province_val!=0&&$city_val==0){
                        echo '<option value="0">全部</option>';
                        $city_lists=district::getInfoByUpid($province_val);
                        foreach ($city_lists as $city_list){
                            echo '<option value="' . $city_list['id'] . '">' . $city_list['name']. '</option>';
                        }
                    }
                    if($province_val==0&&$city_val==0){
                        echo '<option value="0">全部</option>';
                    }
                    ?>

                </select>
            </div>
            <button onclick="search()">搜索</button>
        </div>

        <div class="distribution_results">
            <?php
              if(!empty($rows)){
                  foreach ($rows as $row){
            ?>
          <a href="distribution_detail.php?id=<?php echo $row['id']?>">
            <div class="results_component">
                <img src="<?php if (!empty($row['picurl']))echo $HTTP_PATH.$row['picurl'];else echo $HTTP_PATH."userfiles/upload/webpic/201811221141526940.png"?>"/>
                <p><?php echo $row['title']?></p>
                <p>电话：<?php if(!empty($row['tel']))echo $row['tel'];else echo "暂无"?></p>
                <p>地址：<?php if(!empty($row['address']))echo $row['address'];else echo "暂无"?></p>
<!--                <a href="distribution_detail.php?id=--><?php //echo $row['id']?><!--">w详情</a>-->


                <a class="distri_more"  href="distribution_detail.php?id=<?php echo $row['id']?>">
                    <div >
                        <span>MORE</span>
                        <img style="width: 26px;height: 10px;display: inline-block;float: none;padding: 0;"
                             src="imgs/arr-distri.png" alt="">
                    </div>
                </a>
            </div>
           </a>
           <?php
                  }
              }
              else { ?>

              <p id="none">没有数据</p>


            <?php } ?>

        </div>



    </div>
    <?php
    echo dspPagesForMin(getPageUrl(),$page,$shownum,$totalcount, $pagecount)
    ?>
</div>
<script>
    $(function () {
        $("body").on("mouseenter",".amap-icon",function () {
            var _this = $(this);
            _this.siblings(".amap-marker-label").fadeIn();
        });
        $("body").on("mouseleave",".amap-icon",function () {
            var _this = $(this);
            _this.siblings(".amap-marker-label").fadeOut();
        });
        $("body").find(".amap-icon img").attr("src","imgs/dingwei.png");
        $("body").find(".amap-icon").attr("title","");
    })
</script>


</body>


<?php require_once ('foot.inc.php');?>