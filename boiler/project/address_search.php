
<?php

$content = "";
if(isset($_GET["content"]))
{
    $content = $_GET["content"];
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <style type="text/css">
        body, html{width: 100%;height: 100%;margin:0;font-family:"微软雅黑";font-size:14px;}
        #l-map{height:100%;width:100%;}
        #r-result{
            width:100%;
            position: absolute;
            z-index: 1000;
            top: 23px;
            left: 10px;
            font-size: 14px;
            background: #fff;
            width: 300px;
            height: 24px;
            border: 1px solid #ddd;
            padding: 11px;
        }
    </style>
    <script type="text/javascript" src="js/jquery-1.4.4.min.js"></script>

    <script src="http://api.map.baidu.com/api?v=2.0&ak=G0uudN8VvLK1QOSTP4i79CmsGWbjOD7R"></script>
    <script src="http://api.map.baidu.com/library/SearchInfoWindow/1.5/src/SearchInfoWindow_min.js"></script>
    <title>关键字输入提示词条</title>
</head>
<body>

<div id="r-result"><input type="text" id="suggestId" size="20" value="百度" style="height;40px;width:200px;border-bottom:1px solid #eee;border-top:0px;border-left:0px;border-right:0px;"  placeholder="请输入检索地址"/></div>
<div id="searchResultPanel" style="border:1px solid #C0C0C0;width:150px;height:auto; display:none;"></div>
<div id="l-map"></div>

</body>
</html>
<script type="text/javascript">
    // 百度地图API功能
    function G(id) {
        return document.getElementById(id);
    }

    var map = new BMap.Map("l-map");
    map.centerAndZoom("西安",12);                   // 初始化地图,设置城市和地图级别。
    map.enableScrollWheelZoom(true);

    var ac = new BMap.Autocomplete(    //建立一个自动完成的对象
        {"input" : "suggestId"
            ,"location" : map
        });


    var geoc = new BMap.Geocoder();


    ac.addEventListener("onhighlight", function(e) {  //鼠标放在下拉列表上的事件
        var str = "";
        var _value = e.fromitem.value;
        var value = "";
        if (e.fromitem.index > -1) {
            value = _value.province +  _value.city +  _value.district +  _value.street +  _value.business;
        }
        str = "FromItem<br />index = " + e.fromitem.index + "<br />value = " + value;

        value = "";
        if (e.toitem.index > -1) {
            _value = e.toitem.value;
            value = _value.province +  _value.city +  _value.district +  _value.street +  _value.business;
        }
        str += "<br />ToItem<br />index = " + e.toitem.index + "<br />value = " + value;
        G("searchResultPanel").innerHTML = str;
    });

    var myValue = "<?php echo $content?>";

    if(myValue!="")
    {
        G("searchResultPanel").innerHTML ="onconfirm<br />index = 1<br />myValue = " + myValue;
        setPlace();
    }


    ac.addEventListener("onconfirm", function(e) {    //鼠标点击下拉列表后的事件
        var _value = e.item.value;
        myValue = _value.province +  _value.city +  _value.district +  _value.street +  _value.business;
        G("searchResultPanel").innerHTML ="onconfirm<br />index = " + e.item.index + "<br />myValue = " + myValue;

        setPlace();
    });

    function setPlace(){
        map.clearOverlays();    //清除地图上所有覆盖物
        function myFun(){
            var pp = local.getResults().getPoi(0).point;    //获取第一个智能搜索的结果

            var marker = new BMap.Marker(pp);
            map.centerAndZoom(pp, 18);
            map.addOverlay(marker);    //添加标注

            marker.addEventListener("click",attribute);
        }

        function attribute(e){

                var p = e.target;

                $(window.parent.document).find("#project_detail").val(myValue);
                $(window.parent.document).find("#project_long").val(p.getPosition().lng);
                $(window.parent.document).find("#project_lat").val(p.getPosition().lat);
                parent.layer.closeAll();

        }

        var local = new BMap.LocalSearch(map, { //智能搜索
            onSearchComplete: myFun
        });
        local.search(myValue);
    }


</script>
