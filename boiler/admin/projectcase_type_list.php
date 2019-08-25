<?php
/**
 * Created by PhpStorm.
 * User: tianyi
 * Date: 2018/10/21
 * Time: 15:37
 */
require_once('admin_init.php');
require_once('admincheck.php');

$FLAG_LEFTMENU  = 'projectcase_type_list';
$FLAG_TOPNAV    = 'common';

$page = 1;
$pageSize   = 15;
$totalcount= Projectcase_type::getListByOrder($page, $pageSize, $count = 1);

$pagecount = ceil($totalcount / $pageSize);
$page      = getPage($pagecount);

$rows      = Projectcase_type::getListByOrder($page, $pageSize, $count = 0);
$nums = $totalcount-($page-1)*$pageSize ;
//echo $nums;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="author" content="芝麻开发 http://www.zhimawork.com" />
    <title> 通用设置 - 用户类型 </title>
    <link rel="stylesheet" href="css/style.css" type="text/css" />
    <link rel="stylesheet" href="css/form.css" type="text/css" />
    <link rel="stylesheet" href="css/boxy.css" type="text/css" />
    <script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="js/layer/layer.js"></script>
    <script type="text/javascript" src="js/common.js"></script>
    <script type="text/javascript">
        $(function(){

            //添加
            $('#add').click(function(){
                layer.open({
                    type: 2,
                    title: '添加',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['450px', '320px'],
                    content: 'projectcase_type_add.php'
                });
            });
            //修改
            $(".editinfo").click(function(){
                var thisid = $(this).parent('td').find('#id').val();
                layer.open({
                    type: 2,
                    title: '修改',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['450px', '320px'],
                    content: 'projectcase_type_edit.php?id='+thisid
                });
            });
            //详情
            $(".info").click(function(){
                var thisid = $(this).parent('td').find('#id').val();
                layer.open({
                    type: 2,
                    title: '详情',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['450px', '320px'],
                    content: 'projectcase_type_info.php?id='+thisid
                });
            });
            //删除
            $(".delete").click(function(){
                var id = $(this).parent('td').find('#id').val();
                layer.confirm('确认删除该记录吗？', {
                        btn: ['确认','取消']
                    }, function(){
                        var index = layer.load(0, {shade: false});
                        $.ajax({
                            type        : 'POST',
                            data        : {
                                id:id
                            },
                            dataType : 'json',
                            url : 'projectcase_type_do.php?act=del',
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
            });
        });

    </script>
</head>
<body>
<div id="header">
    <?php include('top.inc.php');?>
    <?php include('nav.inc.php');?>
</div>
<div id="container">
    <?php include('common_menu.inc.php');?>
    <div id="maincontent">
        <div id="position">当前位置：<a href="projectcase_type_list.php">通用设置</a> &gt; 用户类型</div>
        <div id="handlelist">
            <input type="button" class="btn-handle fr" href="javascript:" id="add" value="添加">
        </div>
        <div class="tablelist">
            <table>
                <tr>
                    <th>序号</th>
                    <th>名称</th>
                    <th>英文名</th>
                    <th>排序</th>
                    <th>操作人</th>
                    <th>操作时间</th>
                    <th>操作</th>
                </tr>
                <?php

//                $i=1;
                if(!empty($rows)){
                    foreach($rows as $row){
                        //获取管理员账号
                        try {
                            $admin       = new Admin($row['operator']);
                            $account     = $admin->getAccount();
                        }catch(MyException $e){
                            $account = '-';
                        }
                        echo '<tr>
                                <td class="center">'.$nums.'</td>
                                <td class="center">'.$row['name'].'</td>
                                <td class="center">'.$row['english_name'].'</td>
                                <td class="center">'.$row['order'].'</td>
                                <td class="center">'.$account.'</td>
                                <td class="center">'.date('Y-m-d H:i:s',$row['addtime']).'</td>                              
                                <td class="center">
                                    <a class="info" href="javascript:void(0)">详情</a> 
                                    <a class="editinfo" href="javascript:void(0)">修改</a>
                                    <a class="delete" href="javascript:void(0)">删除</a>                                   
                                    <input type="hidden" id="id" value="'.$row['id'].'"/>                                  
                                </td>
                            </tr>';
//                        $i++;
                        $nums--;
                    }
                }else{
                    echo '<tr><td class="center" colspan="6">没有数据</td></tr>';
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
//                $url = Env::getPageUrl();
                echo dspPages(getPageUrl(), $page, $pageSize, $totalcount, $pagecount);
            }
            ?>
        </div>
    </div>
    <div class="clear"></div>
</div>
<?php include('footer.inc.php');?>
</body>
</html>