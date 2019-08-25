<?php
/**
 * 选型方案页面 project_select_plan.php
 *
 * @version       v0.01
 * @create time   2018/06/29
 * @update time   2018/06/29
 * @author        dlk
 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
 */
require_once('web_init.php');
require_once('usercheck.php');

$TOP_TAB_NVA = "select_plan";
$LEFT_TAB_NVA = "";
//通过tag值识别选型方案页面
$tag=isset($_GET['tag'])?safeCheck($_GET['tag']):0;
  if($tag == 1){//我的项目
      $TOP_FLAG = 'myproject';
}elseif ($tag == 2) {//项目审批
      $TOP_FLAG = 'projectreview';
  }elseif ($tag == 3){//项目查询
      $TOP_FLAG = 'projectselect';
  }else{
      echo '非法操作！';
      die();
  }


$id = isset($_GET['id'])?safeCheck($_GET['id']):'0';
$projectinfo = Project::Init();


$totalcount= Selection_plan_front::getByProidCount($id);
$shownum   = 10;
$pagecount = ceil($totalcount / $shownum);
$page      = getPage($pagecount);


$params["page"] = $page;
$params["pageSize"] = $shownum;

$rows = Selection_plan_front::getListByProid($params,$id);
//print_r($rows[0]['pdf_url']);


if(!empty($id)){
    $projectinfo = Project::getInfoById($id) ;
    if(empty($projectinfo)){
        echo '非法操作！';
        die();
    }
}else{
    echo '非法操作！';
    die();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>项目管理-选型方案</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/main_two.css">
    <link rel="stylesheet" href="css/newlayui.css">
    <link rel="stylesheet" href="layui/css/layui.css">
    <link rel="stylesheet" href="layui/css/layui.css">
    <script type="text/javascript" src="js/jquery-1.4.4.min.js"></script>
    <script type="text/javascript" src="js/myprogram.js"></script>
    <script type="text/javascript" src="layer/layer.js"></script>
    <script type="text/javascript">
        //打印方案
        //function printPlan(plan_id){
        //    //var plan_id = $("#plan_id").val();
        //    window.location.href='selection_plan_do.php?planId='+plan_id;
        //}
        ////查看
        //function planlook(plan_front_id){
        //    // alert(plan_front_id);
        //    //var project_id=<?php ////echo $id;?>////;
        //    //var tag=<?php ////echo $tag;?>////;
        //    window.open('project_select_plan_preview.php?&plan_front_id='+plan_front_id);
        //}
     //如果后台文档转换还没处理完成，3分钟后自动刷新一次
        function refresh(){
            url = location.href;
            console.log(url);
            var once = url.split("#");
            if (once[1] != 1) {
                url += "#1";
                self.location.replace(url);
                window.location.reload();
            }
        }
        $(document).ready(function(){
            var my_element = $(".statu");
            if(my_element.length > 0){
                setTimeout('refresh()', 18000);
            }
        });


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
                        url : '../selection/selection_do.php?act=delFrontPlan',
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

    </script>
</head>
<body class="body_1">
<?php include('top.inc.php');?>
<?php include('project_top.inc.php');?>
<div class="manageHRWJCont">

    <?php
    if($tag == 1){//我的项目模块
    include('project_tab.inc.php');
    ?>
    <div style="width: 200px;margin-right:60px;margin-left:120px;">
    <a href="#" onclick="window.open('../selection/selection.php?project_id=<?php echo $id?>')">
        <span class="GLXXmain_16" id="addrs">+添加方案</span>
    </a>
</div>
    <?php
    }elseif ($tag == 2){//项目审批模块
        include('project_check_tab.inc.php');
    }elseif ($tag == 3){//项目查询模块
        include('project_show_tab.inc.php');
    }
    ?>
    <div style="margin-right:60px;margin-left:120px;">
        <div>
        <table class="XXhistory" >
            <tr class="GLDetils9_fir">
                <td>序号</td>
                <td style="width: 580px;">方案名称</td>
                <td>方案类型</td>
                <td style="width: 580px;">生成时间</td>
                <td style="width: 580px;">操作</td>
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
                                 <td style="width: 300px;">
                                 ';
                    if($tag == 1){//只有在我的项目模块可以点击方案名称查看并修改方案详情
                           $html.='       
                                     <a href="javascript:void(0)" class="detail" style="color: #0AA5FF">'.$item['name'].'</a>
                                            <input type="hidden" id="aid" value="' . $item['history_id'] . '"/>
                                             <input type="hidden" id="tid" value="' . $type_value . '"/>';
                    }else{
                    $html.=' <a>'.$item['name'].'</a>';
                    }

                    $html.='</td>
                                        <td style="color: #0C0C0C">'.$type.'</td>
                                        <td>'.date('Y-m-d H:i:s',$item["addtime"]).'</td>
                                        <td >';

                    //----------------------当方案内容较多时文档存入数据库中的数据较慢---------------------------
                    if($item["status"]==selection_plan_front::WAIT_SOLVE)
                    {
                        $html .= "<a style='color:green' class='statu'>等待处理</a>";
                    }
                    else if($item["status"]==selection_plan_front::SOLVing)
                    {
                        $html .= "<a style='color:red' class='statu'>正在处理,请稍后...</a>";
                    }else{
                        $html.='<a href="'.$HTTP_PATH.$item["pdf_url"].'" style="color: #04A6FE">查看</a>
                                            <a href="'.$HTTP_PATH.$item["url"].'" style="color: #04A6FE">下载</a>';
                    }



                    //在项目查询模块中只有总经理（即role=3）有删除权限
                    if($tag == 1 || $USERINFO['role'] == 3){
                        $html .= '&nbsp;<a class="delete" id="delBtn" style="color: #04A6FE" onclick="deletePlan('.$item["id"].')">删除</a>
                        ';
                    }
                    $html .=' <input type="hidden" id="plan_id" value="'.$item['id'].'"/>
                                       </td>
                                    </tr>';

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
                    //查看详情和修改方案详情
                    $(".detail").click(function(){
                        var thisid = $(this).parent('td').find('#aid').val();
                        var type = $(this).parent('td').find('#tid').val();
                        var project_id = <?php echo $id;?>;
                        // alert(type);
                        // return false;
                        var index = layer.msg("正在跳转请稍后.....", {time: 1000000});
                        $.ajax({
                            type: 'POST',
                            data: {
                                id : thisid
                            },
                            dataType: 'json',
                            url: '../selection/selection_do.php?act=copy_history',
                            success: function (data) {
                                layer.close(index);
                                var code = data.code;
                                var msg = data.msg;
                                var historyid = data.historyid;
                                if(code==1) {
                                    switch (type) {
                                        case "1":
                                            // window.location.href = "../selection/selection.php?id="+historyid;
                                            window.open('../selection/selection.php?&project_id='+project_id+'&id='+historyid);
                                            break;
                                        case "2":
                                            // window.location.href = "../selection/selection_manual_new.php?id="+historyid;
                                            window.open('../selection/selection_manual_new.php?&project_id='+project_id+'&id='+historyid);
                                            break;
                                        case "3":
                                            // window.location.href = "../selection/selection_change_old.php?id="+historyid;
                                            window.open('../selection/selection_change_old.php?&project_id='+project_id+'&id='+historyid);
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
            <div id="test2" class="GLthree GLthree_two"></div>
            <script src="layui/layui.js"></script>
            <script>
                layui.use('laypage', function(){
                    var laypage = layui.laypage;

                    //执行一个laypage实例
                    laypage.render({
                        elem: 'test2' //注意，这里的
                        // test1 是 ID，不用加 # 号
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
                                location.href  = "project_select_plan.php?tag=<?php echo $tag?>&id=<?php echo $id?>&page="+obj.curr+"&pagesize="+obj.limit;
                            }
                        }
                    });
                });
            </script>

    </div>
    </div>
</div>
<div class="clear"></div>
</body>
</html>