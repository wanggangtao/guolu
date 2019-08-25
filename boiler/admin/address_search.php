<?php

if(isset($_GET["address_detail"]))
{
    $address_detail = $_GET["address_detail"];
}else{
    $address_detail=$_POST['address_detail'];
}


?>

<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no, width=device-width">
    <title></title>
    <style>
        body,#mapContainer{
            margin:0;
            height:100%;
            width:100%;
            font-size:12px;
        }
        .marker{
            width:42px;
            height:56px;
            background-image:url(http://webapi.amap.com/theme/v1.3/markers/b/mark_b.png);
            background-size: 42px 56px;
            text-align: center;
            line-height: 24px;
            color: #fff
        }
    </style>
    <link rel="stylesheet" href="https://a.amap.com/jsapi_demos/static/demo-center/css/demo-center.css"/>
    <script src="http://cache.amap.com/lbs/static/es5.min.js"></script>
    <script type="text/javascript" src="http://webapi.amap.com/maps?v=1.3&key=d7c5cdb73a595b9ee6556c08ff37abf9&plugin=AMap.ToolBar"></script>
    <script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="js/layer/layer.js"></script>
    <style>
        html, body, #mapContainer {
            height: 100%;
            width: 100%;
        }

        .input-card{
            opacity: 1;
        }

        .input-card .btn{
            margin-right: 1.2rem;
            width: 9rem;
        }

        .input-card .btn:last-child{
            margin-right: 0;
        }
    </style>
    <script>

        function init() {
            var address_detail = "<?php echo $address_detail?>";
            map = new AMap.Map("mapContainer", {
                zoom: 18,
                center:[116.473188,39.993253]
            });
            AMap.plugin(['AMap.Autocomplete','AMap.PlaceSearch'],function(){
                var placeSearch = new AMap.PlaceSearch({
                    city:'010',
                    pageSize:5
                })
                placeSearch.search(address_detail,function(status,data){
                    if(status!=='complete')return;
                    var pois = data.poiList.pois;
                    map.setZoom(15);
                    map.setCenter(pois[0].location.location);
                    var marker = new AMap.Marker({
                        content:'<div class="marker" ></div>',
                        position:pois[0].location,
                        map:map,
                        draggable: true,
                        cursor: 'move',
                        raiseOnDrag: true
                    })
                    marker.setLabel({
                        offset: new AMap.Pixel(20, 20),//修改label相对于maker的位置
                        content: pois[0].name
                    });
                    marker.id= pois[0].id;
                    marker.name = pois[0].name;
                    marker.on('click',function(e){

                        $(window.parent.document).find("#address_position").val(e.lnglat.getLng() + ',' + e.lnglat.getLat());
                        parent.layer.closeAll();
                    });


                    marker.on('dragging',function(e){

                        $(window.parent.document).find("#address_position").val(e.lnglat.getLng() + ',' + e.lnglat.getLat());
                    });

                    map.setFitView();
                })
            })
            map.addControl(new AMap.ToolBar());
            if(AMap.UA.mobile){
                document.getElementById('button_group').style.display='none';
            }
        }
        //PlaceSearh插件同样支持poiOnMap与detailOnAMAP方法
    </script>
</head>
<body onload="init()">
<div id="mapContainer" ></div>

</body>
</html>