<?php
/**
 * Created by lyz.
 * Date: 2018/12/6
 * Time: 21:18
 */require_once('admin_init.php');
require_once('admincheck.php');

$FLAG_TOPNAV	= "webcontent";
$FLAG_LEFTMENU  = 'recruit_list';

$page = isset($_GET['page'])? safeCheck($_GET['page']): 1;
$pagesize = 10;

$listcount = Web_recruit::getPageList(1, 10, 0);
$pagecount = ceil($listcount/$pagesize);

$caselist = Web_recruit::getPageList($page, $pagesize, 1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="微普科技 http://www.wiipu.com" />
    <title>人员招聘 - 人才招聘 - 管理系统 </title>
    <link rel="stylesheet" href="css/style.css" type="text/css" />
    <link rel="stylesheet" href="css/form.css" type="text/css" />
    <link href="css/semantic.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="js/layer/layer.js"></script>
    <script type="text/javascript" src="js/common.js"></script>
    <script type="text/javascript" src="js/upload.js"></script>
    <script type="text/javascript">
        // $(function(){
        // });

        function delete_picture(id){
            layer.confirm('确认删除该条信息吗？', {
                    btn: ['确认','取消']
                }, function(){
                    var index = layer.load(0, {shade: false});
                    $.ajax({
                        type        : 'POST',
                        data        : {
                            id : id
                        },
                        dataType : 'json',
                        url : 'recruit_do.php?act=del',
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

        function add_picture(){
            layer.open({
                type: 2,
                title: '添加人员管理',
                shadeClose: true,
                shade: 0.3,
                area: ['1000px', '650px'],
                content: 'recruit_add.php'
            });
        }

        function edit_picture(id){
            layer.open({
                type: 2,
                title: '修改人员招聘',
                shadeClose: true,
                shade: 0.3,
                area: ['1000px', '650px'],
                content: 'recruit_edit.php?id='+id
            });
        }


        function detail_picture(id){
            layer.open({
                type: 2,
                title: '人员招聘详情',
                shadeClose: true,
                shade: 0.3,
                area: ['1000px', '650px'],
                content: 'recruit_detail.php?id='+id
            });
        }


    </script>
</head>
<body>
<div id="header">
    <?php include('top.inc.php');?>
    <?php include('nav.inc.php');?>
</div>
<div id="container">
    <?php include('content_menu.inc.php');?>
    <div id="maincontent" >
        <div id="position">当前位置：<a href="recruit_list.php">前端内容管理</a> &gt; 人员招聘 &gt; 招聘岗位</div>
        <div style="margin:10px auto; float:right;">
            <div class="ui mini blue button" style="float:right;margin-left:20px;" onclick="add_picture()" >新增</div>
        </div>

        <div class="tablelist" >
            <table>
                <tr style="border-top:6px solid #9bccee;">
                    <th width="10%">ID</th>
                    <th width="15%">招聘岗位</th>
                    <th width="15%">学历要求</th>
                    <th width="15%">招聘人数</th>
<!--                    <th width="15%">职位描述</th>-->
<!--                    <th width="15%">任职要求</th>-->
                    <th width="15%">工作经验</th>
                    <th width="10%">操作</th>
                </tr>
                <?php
                if(!empty($caselist)){
                    $i = ($page-1)*$pagesize+1;
                    foreach($caselist as $list){
                        echo '
										<tr class="pictures">
											<td class="center" >'.$i.'</td>
											<td class="center" id="t_station'.$list['id'].'">'.$list['station'].'</td>
											<td class="center">'.$ARRAY_Educition_type[$list['education']].'</td>
											<td class="center" id="t_number'.$list['id'].'">'.$list['number'].'</td>
										
										    <td class="center" id="t_need'.$list['id'].'">'.$ARRAY_Experience_type[$list['experience']].'</td>
											<td class="center">
												<a href="javascript:edit_picture('.$list['id'].')" ><img title="修改" src="images/edit.png" width="20px;" /></a>
												<a href="javascript:delete_picture('.$list['id'].')" ><img title="删除" src="images/del.png" width="20px;" /></a>
												<a href="javascript:detail_picture('.$list['id'].')" ><img title="详情" src="images/detail.png" width="20px;" /></a>
                                                        
											</td>
										</tr>';
                        $i++;
                    }
                }else{
                    echo '<tr><td class="center" colspan="6">没有数据</td></tr>';
                }
                ?>
            </table>
        </div>
        <!-- 分页 -->
        <div id="pagelist">
            <!-- 分页信息 -->
            <div class="pageinfo">
                <span>共<?php echo $listcount;?>条，共<?php echo $pagecount;?>页</span>
            </div>
            <?php
            if($pagecount>1){
                $count=1;
                echo dspPages(getPageUrl(), $page, $pagesize, $count, $pagecount);
            }
            ?>
        </div>
    </div>
    <div class="clear"></div>
</div>
<?php include('footer.inc.php');?>
</body>
</html>