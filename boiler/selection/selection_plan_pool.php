<?php
/**
 * Created by xinmei.
 * User: Administrator
 * Date: 2018/11/6
 * Time: 22:23
 */

require_once('web_init.php');
require_once('usercheck.php');

$TOP_FLAG = "pool";

$totalcount= Selection_plan_front::getAllCount(null);

$shownum   = 10;
$pagecount = ceil($totalcount / $shownum);
$page      = getPage($pagecount);


$params["page"] = $page;
$params["pageSize"] = $shownum;

$rows = Selection_plan_front::getAllPlanList($params);

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>方案池</title>

    <link rel="stylesheet" href="css/scheme.css" />

    <link rel="stylesheet" href="css/nav.css" />
    <link rel="stylesheet" href="css/GLXX2.css" />
    <script type="text/javascript" src="js/nav.js" ></script>
    <link rel="stylesheet" href="css/Tc.css" />
    <script type="text/javascript" src="js/2.0.0/jquery.min.js" ></script>
    <script type="text/javascript" src="js/layer.js" ></script>

    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/newlayui.css">
    <link rel="stylesheet" href="layui/css/layui.css">
    <script type="text/javascript" src="js/jquery-1.4.4.min.js"></script>
    <script type="text/javascript" src="layer/layer.js"></script>
    <script type="text/javascript" src="ckeditor/ckeditor.js"></script>
<!--    <link rel="stylesheet" href="css/site.css" />-->
    <link rel="stylesheet" href="css/page.css" />


    <style>
        .checkbox{
            background-image:url('images/未选中按钮.png');
            -webkit-appearance: none;
            background-size:16px 16px;
            display: inline-block;
            width: 16px;
            height: 16px;
            background-repeat:no-repeat;
            position: relative;
            outline: none;
        }
        .checkbox:checked{
            background:url('images/选中按钮.png');
            background-repeat:no-repeat;
        }


    </style>
    <script type="text/javascript">

        //删除
        //$("#delBtn").click(function(){
        function deletePlan(id){
            //var planId = $("#planId").val();
            //var planId = $(this).parent('td').find('#planId').val();
            layer.confirm('确认删除该方案吗？', {
                    btn: ['确认','取消']
                }, function(){
                    var index = layer.load(0, {shade: false});
                    $.ajax({
                        type        : 'POST',
                        data        : {
                            planId:id
                        },
                        dataType : 'json',
                        url : 'selection_do.php?act=delFrontPlan',
                        success : function(data){
                            layer.close(index);

                            var code = data.code;
                            var msg  = data.msg;
                            switch(code){
                                case 1:
                                    layer.alert(msg, {icon: 6}, function(index){
                                        location.reload();
                                    });
                                    break;
                                default:
                                    layer.alert(msg, {icon: 5});
                            }
                        }
                    });
                }, function(){}
            );
        }
        //打印方案
        function printPlan(plan_id){
            //var plan_id = $("#plan_id").val();
            window.location.href='selection_plan_do.php?planId='+plan_id;
        }


    </script>
</head>
<body class="body_2">
        <?php include('top.inc.php');?>
<!--        <div class="location">-->
<!--            <div class="location1">-->
<!--                <p>当前位置：锅炉选型<span>方案池</span></p>-->
<!--            </div>-->
<!--        </div>-->
        <div>
            <table class="XXhistory" >
                <tr class="GLDetils9_fir">
                    <td>序号</td>
                    <td style="width: 214px;">客户名称</td>
                    <td style="width: 580px;">方案名称</td>
                    <td>方案类型</td>
                    <td>生成时间</td>
                    <td>状态</td>
                    <td>操作</td>
                </tr>
                <?php
                    if(!empty($rows)){
                        $index = $shownum*($page-1) + 1;

                        $html = "";
                        foreach ($rows as $item){
                            $type_item = Selection_history::getInfoById($item['history_id']);
                            if(!empty($type_item)){
                                $type_value = $type_item['type'];
                                switch ($type_value){
                                    case 1:$type = "智能选型";break;
                                    case 2:$type = "手动选型";break;
                                    case 3:$type = "更换锅炉";break;
                                    default: $type="";break;
                                }
                            }else{
                                $type="";
                                $type_value="";
                            }
                            $html .= '
                                    <tr class="odd">
                                        <td>'.($index++).'</td>
                                       <td>'.$item["customer_name"].'</td>
                                      
                                  <td style="width: 300px;">
            
                                                            <a href="javascript:void(0)" class="detail" style="color: #0AA5FF">'.$item['name'].'</a>
                                                            <input type="hidden" id="aid" value="' . $item['history_id'] . '"/>
                                                            <input type="hidden" id="tid" value="' . $type_value . '"/>
                                                        </td>
                                     </td>
                                         <td>'.$type.'</td>
                                        
                                        <td>'.date('Y-m-d H:i:s',$item["addtime"]).'</td>
                                        
                                 
                                        ';




                            if($item["status"]==selection_plan_front::WAIT_SOLVE)
                            {
                                $html .= "<td style='color:green'>等待处理</td>";
                            }
                            else if($item["status"]==selection_plan_front::SOLVing)
                            {
                                $html .= "<td style='color:red'>正在处理,请稍后...</td>";
                            }
                            else
                            {
                                $html .= "<td align='center'><a href='".$HTTP_PATH.$item["url"]."' style='color: #04A6FE'>下载</a></td>";
                            }


                            $html .= '<td>                                        
                                                <a class="delete" id="delBtn" onclick="deletePlan('.$item["id"].')">删除</a>                                   
                                                <input type="hidden" id="plan_id" value="'.$item['id'].'"/> 
                                        </td>
                                    </tr>
                            ';
                        }

                        echo $html;
                    }else{
                        echo '
                            <tr class="title" >
                                <td style="  " colspan="7">暂无数据</td>
                            </tr>
                        ';
                    }
                ?>
            </table>
            <script>
                $(function() {
                    $(".detail").click(function(){
                        var thisid = $(this).parent('td').find('#aid').val();
                        var type = $(this).parent('td').find('#tid').val();
                        var index = layer.msg("正在跳转请稍后.....", {time: 1000000});
                        $.ajax({
                            type: 'POST',
                            data: {
                               id : thisid
                            },
                            dataType: 'json',
                            url: 'selection_do.php?act=copy_history',
                            success: function (data) {
                                layer.close(index);
                                var code = data.code;
                                var msg = data.msg;
                                var historyid = data.historyid;
                                if(code==1) {
                                    switch (type) {
                                        case "1":
                                            location.href = "selection.php?id="+historyid;
                                            break;
                                        case "2":
                                            location.href = "selection_manual_new.php?id="+historyid;
                                            break;
                                        case "3":
                                            location.href = "selection_change_old.php?id="+historyid;
                                            break;
                                        default:
                                            layer.alert(msg, {icon: 5});
                                    }
                                }else{
                                        layer.alert(msg, {icon: 5});
                                    }
                                }

                        });
                    })
                });
            </script>
            <div id="test1" class="GLthree"></div>
            <script src="layui/layui.js"></script>
            <script>
                layui.use('laypage', function(){
                    var laypage = layui.laypage;

                    //执行一个laypage实例
                    laypage.render({
                        elem: 'test1' //注意，这里的 test1 是 ID，不用加 # 号
                        ,count: <?php echo $totalcount; ?> //数据总数，从服务端得到
                        ,curr: <?php echo $page; ?>
                        ,limit:<?php echo $shownum; ?>
                        ,groups:3
                        ,layout:['count','prev','page','next']
                        ,jump: function(obj, first){
                            //obj包含了当前分页的所有参数，比如：
                            //console.log(obj.curr); //得到当前页，以便向服务端请求对应页的数据。
                            //console.log(obj.limit); //得到每页显示的条数
                            //首次不执行
                            if(!first){
                                //do something
                                location.href  = "selection_plan_pool.php?page="+obj.curr+"&pagesize="+obj.limit;
                            }
                        }
                    });
                });
            </script>
        </div>

</body>
</html>

