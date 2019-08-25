<?php
/**
 * 选型 selection.php
 *
 * @version       v0.01
 * @create time   2018/07/18
 * @update time   2018/07/18
 * @author        dlk
 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
 */
require_once('web_init.php');
require_once('usercheck.php');

$TOP_FLAG = "history";
$pageSize  = 10;
$page = isset($_GET['page'])?safeCheck($_GET['page']):1;

$count = Selection_history::getPageList(1, 10, 0, '', 0, 1);
$rows = Selection_history::getPageList($page, $pageSize, 1, '', 0, 1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>选型历史</title>
    <link rel="stylesheet" href="css/main.css?v=1">
    <link rel="stylesheet" href="css/newlayui.css">
    <link rel="stylesheet" href="layui/css/layui.css">
    <script type="text/javascript" src="js/jquery-1.4.4.min.js"></script>
    <script type="text/javascript" src="layer/layer.js"></script>
    <script>
        $(function () {
            $('.indexTop_2').click(function(){
                $('.indexTop_2').removeClass('indexTop_checked');
                $(this).addClass('indexTop_checked');
            })
            $('.indexMtwo_1').hover(function () {
                $(this).find('.mouseset').slideDown('fast');
                var name = $(this).find('.indexMtwo_1_2').text();
                $(this).find('.mouseset').find('span').text(name);
            },function () {
                $(this).find('.mouseset').slideUp(100);
            })
        })
    </script>
</head>
<body class="body_2">
    <?php include('top.inc.php');?>
    <div>
        <table class="XXhistory">
            <tr class="GLDetils9_fir">
                <td>序号</td>

                <td>时间</td>
                <td>厂家-型号</td>
                <td>客户名称</td>
                <td>操作</td>
            </tr>
            <?php
            if($rows){
                $index = $pageSize*($page-1) + 1;

                foreach ($rows as $row){


                    $guoluIds = explode(",",$row['guolu_id']);

                    $guoluStr = "";
                    foreach ($guoluIds as $guoluId)
                    {
                        $guoluinfo = Guolu_attr::getInfoById($guoluId);
                        $version = "";
                        $vendername = "";
                        if($guoluinfo){
                            $venderinfo = Dict::getInfoById($guoluinfo['vender']);
                            $vendername = $venderinfo['name'];
                            $version = $guoluinfo['version'];

                            $guoluStr .= $vendername."-".$version."<br/>";
                        }
                    }


                    echo '
                    <tr>
                        <td>'.($index++).'</td>
                        <td>'.date('Y-m-d H:i:s',$row['addtime']).'</td>
                        <td>'.$guoluStr.'</td>
                        <td>'.$row['customer'].'</td>
                        <td><a  href="selection_history_details.php?id='.$row['id'].'" style="color:#04A6FE">查看详情</a><c class="update" style="padding-left:10px;color:#04A6FE">修改备注<input type="hidden" id="history_id" value="'.$row['id'].'" /></c></td>
                        <input type="hidden" id="history_time'.$row['id'].'" value="'.date('Y年m月d',$row['addtime']).'" />
                        <input type="hidden" id="history_vender'.$row['id'].'" value="'.$vendername.'" />
                        <input type="hidden" id="history_customer'.$row['id'].'" value="'.$row['customer'].'" />
                        <input type="hidden" id="history_version'.$row['id'].'" value="'.$version.'" />
                        <input type="hidden" id="history_remark'.$row['id'].'" value="'.HTMLDecode($row['remark']).'" />
                    </tr>
                    ';
                }
            }
            ?>
        </table>
        <div id="test1" class="GLthree"></div>
        <script src="layui/layui.js"></script>
        <script>
            layui.use('laypage', function(){
                var laypage = layui.laypage;

                //执行一个laypage实例
                laypage.render({
                    elem: 'test1' //注意，这里的 test1 是 ID，不用加 # 号
                    ,count: <?php echo $count; ?> //数据总数，从服务端得到
                    ,curr: <?php echo $page; ?>
                    ,limit:<?php echo $pageSize; ?>
                    ,groups:3
                    ,layout:['count','prev','page','next']
                    ,jump: function(obj, first){
                        //obj包含了当前分页的所有参数，比如：
                        //console.log(obj.curr); //得到当前页，以便向服务端请求对应页的数据。
                        //console.log(obj.limit); //得到每页显示的条数
                        //首次不执行
                        if(!first){
                            //do something
                            location.href  = "selection_history.php?page="+obj.curr+"&pagesize="+obj.limit;
                        }
                    }
                });
            });
        </script>
    </div>

<script>
    $(function () {
        $('.update').click(function () {
            var id = $(this).find('#history_id').val();
            $("#history_remark_id").val(id);
            $("#poupwin_time").html("时间：" + $("#history_time"+id).val());
            $("#poupwin_vender").html("厂家：" + $("#history_vender"+id).val());
            $("#poupwin_version").html("型号：" + $("#history_version"+id).val());
            $("#poupwin_customer").html("用户：" + $("#history_customer"+id).val());
            $("#remark").val($("#history_remark"+id).val());

            layer.open({
                type: 1,
//                shade: false,
//                title: false, //不显示标题
                content: $('#poupwin'), //捕获的元素，注意：最好该指定的元素要存放在body最外层，否则可能被其它的相对元素所影响
//                cancel: function(){
//                    layer.msg('捕获就是从页面已经存在的元素上，包裹layer的结构', {time: 5000, icon:6});
//                }
            });
//            $('.poupwin').slideDown('fast');
        });

        $('.poupwin_3').click(function () {
            var remarkid = $("#history_remark_id").val();
            var remark = $("#remark").val();
            var index = layer.load(0, {shade: false});
            $.ajax({
                type        : 'POST',
                data        : {
                    id : remarkid,
                    remark : remark
                },
                dataType :    'json',
                url :         'selection_do.php?act=remark_update',
                success :     function(data){
                    layer.close(index);
                    var code = data.code;
                    var msg  = data.msg;
                    switch(code){
                        case 1:
                            $('.poupwin').slideUp(100);
                            location.reload();
                            break;
                        default:
                            layer.alert(msg, {icon: 5});
                    }
                }
            });
        })
    })
</script>
</body>
</html>

<div id="poupwin" style="display: none">
    <input type="hidden" id="history_remark_id" value="" />
    <div class="poupwin_1" id="poupwin_time">时间：2018年5月20日</div>
    <div class="poupwin_1" id="poupwin_vender">厂家：普锐斯</div>
    <div class="poupwin_1" id="poupwin_version">型号：PREX 3G LowNOx-30mg</div>
    <div class="poupwin_1" id="poupwin_customer">用户：小林</div>
    <div class="poupwin_1">备注：</div>
    <textarea class="poupwin_2" id="remark"></textarea>
    <button class="poupwin_3">提交</button>
</div>