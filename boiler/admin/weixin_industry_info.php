<?php
/**
 * Created by PhpStorm.
 * User: hhx
 * Date: 2019/3/20
 * Time: 15:32
 */
require_once('admin_init.php');
require_once('admincheck.php');

$FLAG_TOPNAV	= "weixincontent";
$FLAG_LEFTMENU  = 'weixin_industry_info';

$page = 1;
$shownum = 10;
if(isset($_GET['page'])){
    $page = $_GET['page'];
}
$totalcount =weixin_situation::getPageListWeiXin($page, $shownum, 0);
$pagecount =ceil($totalcount/$shownum);
$rows = weixin_situation::getPageListWeiXin($page, $shownum, 1);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="author" content="芝麻开发 http://www.zhimawork.com" />
    <title>公众号管理 - 资讯管理</title>
    <link rel="stylesheet" href="css/style.css" type="text/css" />
    <link rel="stylesheet" href="css/form.css" type="text/css" />
    <link rel="stylesheet" href="css/boxy.css" type="text/css" />
    <link rel="stylesheet" href="css/newlayui.css">
    <link rel="stylesheet" href="layui/css/layui.css">

    <link href="css/semantic.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="js/layer/layer.js"></script>
    <script type="text/javascript" src="js/common.js"></script>
    <script type="text/javascript">
        $(function(){
            $('#add').click(function(){
                layer.open({
                    type: 2,
                    title: '添加资讯',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['1000px', '600px'],
                    content: 'weixin_industry_add.php'
                });
            });

            $('.editinfo').click(function(){
                var id = $(this).parent('td').find('#aid').val();
                layer.open({
                    type: 2,
                    title: '修改资讯',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['1000px', '600px'],
                    content: 'weixin_industry_edit.php?id=' + id
                });
            });

            $('.delete').click(function(){
                var id = $(this).parent('td').find('#aid').val();
                //alert(id);
                layer.confirm('确认删除该订单吗？', {
                    btn: ['确认','取消']
                }, function(){
                   $.ajax({
                       type:'POST',
                       data:{
                           id:id
                       },
                       url:"weixin_industry_do.php?act=del",
                       dataType:'json',
                       success : function(data){
                           var code =data.code;
                           var msg = data.msg;
                           switch(code){
                               case 1:
                                   layer.alert(msg, {icon: 6}, function(index){
                                       location.reload();
                                   });
                                   break;
                               default:
                                   layer.alert(msg,{icon: 5});
                           }
                       }
                   });
                });
            });
             ntop = function(id){

                var id = $("#ntop" +id).val();
                //var id = $(this).children('#ntop').val();
                //alert(id);
                $.ajax({
                    type: 'post',
                    url: 'weixin_industry_do.php?act=top',
                    data: {

                        id :id,
                    },
                    dataType: 'json',
                    success: function (data) {
                        var code=data.code;
                        var msg=data.msg;
                        switch (code){
                            case 1:
                                layer.alert(msg, {icon: 6}, function(index){
                                    parent.location.reload();
                                });
                                break;
                            default:
                                layer.alert(msg, {icon: 5});
                        }
                    },
                    error: function () {
                        alert("请求失败");
                    }
                });
            }
        });


    </script>
</head>
<body>
<div id="header">
    <?php include('top.inc.php');?>
    <?php include('nav.inc.php');?>
</div>
<div id="container">
    <?php include('weixin_menu_inc.php');?>
    <div id="maincontent">
        <div id="position">当前位置：<a href="weixin_employees_info.php">公众号管理</a> &gt; 资讯管理</div>
        <div id="handlelist">
            <input type="button" class="btn-handle fr" href="javascript:" id="add" value="新增">
        </div>
        <div class="tablelist">
            <table>
                <tr>
                    <th>ID</th>
                    <th>标题</th>
                    <th>内容概要</th>
                    <th>图片展示</th>
                    <th>发布时间</th>
                    <th>是否置顶</th>
                    <th>操作</th>
                </tr>
                <?php
                if($rows){
                    foreach($rows as $row){

                        $row['contents'] = HTMLDecode($row['content']?$row['content']:'');
                        $row['contentss'] = str_replace("\\","",$row['contents']);
                        $row['contentss'] = strip_tags($row['contentss']);
                        $row['contentsss'] = mb_substr($row['contentss'],0,15);
                        echo '<tr class="center">
                            <td>'.$row['id'].'</td>
                            <td>'.$row['title'].'</td>
                            <td>'.$row['contentsss'].'...</td>
                            <td>';
                            if($row['picurl']) {
                                echo '<img src="'.$HTTP_PATH . $row['picurl'].'" width="100" height="50">';
                            }
                        echo '</td>
                            <td>'.date('Y-m-d H:i',$row['addtime']).'</td>
                            <td class="center">
							    <div class="ui toggle checkbox" onchange="ntop('.$row['id'].');">
							    <input type="hidden"  id="ntop'.$row['id'].'" value="'.$row['id'].'"/>
							    <input type="checkbox" ';
                            if($row['if_top'] == 1){
                                echo 'checked';
                            }
                        echo '/>
                        
                        <label id="tips'.$row['id'].'" style="color:#999;">置顶</label>
												</div>
													
                            <td><a class="editinfo" href="javascript:void(0)">修改</a>
                                <a class="delete" href="javascript:void(0)">删除</a>
                                <input type="hidden" id="aid" value="'.$row['id'].'"/>
                            </td>
                            </tr>';
                       // $row['if_top']
                    }
                }else{
                    echo '<tr><td class="center" colspan="7">没有数据</td></tr>';
                }
                ?>
            </table>
        </div>
        <div id="pagelist">
            <div class="pageinfo">
                <span class="table_info">共<?php echo $totalcount;?>条数据，共<?php echo $pagecount;?>页</span>
            </div>
            <?php
            if($pagecount>1){
                echo dspPages(getPageUrl(), $page, $shownum, $totalcount, $pagecount);
            }
            ?>
        </div>
    </div>
</div>
<?php include('footer.inc.php');?>
</body>
</html>
