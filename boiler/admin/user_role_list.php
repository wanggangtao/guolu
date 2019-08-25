<?php
/**
 * 角色列表  role_list.php
 *
 * @version       v0.01
 * @create time   2018/6/27
 * @update time
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */
require_once('admin_init.php');
require_once('admincheck.php');

$FLAG_TOPNAV    = "user";
$FLAG_LEFTMENU  = 'role_list';

//初始化
$rows      = User_role::getAllList();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="author" content="芝麻开发 http://www.zhimawork.com" />
    <title>角色列表 - 用户管理</title>
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
                    type: 1,
                    title: '添加角色',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['400px', '200px'],
                    content: $('#adddiv')
                });
            });
            //修改
            $(".editinfo").click(function(){
                var thisid = $(this).parent('td').find('#aid').val();
                var thisname = $(this).parent('td').parent('tr').children('td').eq(1).text();
                $('#departmentid').val(thisid);
                $('#name_edit').val(thisname);
                layer.open({
                    type: 1,
                    title: '修改角色',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['400px', '200px'],
                    content: $('#editdiv')
                });
            });
            //删除
            $(".delete").click(function(){
                var id = $(this).parent('td').find('#aid').val();
                layer.confirm('确认删除该角色吗？', {
                        btn: ['确认','取消']
                    }, function(){
                        var index = layer.load(0, {shade: false});
                        $.ajax({
                            type        : 'POST',
                            data        : {
                                id:id
                            },
                            dataType : 'json',
                            url : 'user_role_do.php?act=del',
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

            $('#add_btn_sumit').click(function(){
                var name = $('#name').val();

                if(name == ''){
                    layer.tips('角色名称不能为空', '#a_name');
                    return false;
                }
                $.ajax({
                    type        : 'POST',
                    data        : {
                        name  : name
                    },
                    dataType :    'json',
                    url :         'user_role_do.php?act=add',
                    success :     function(data){
                        var code = data.code;
                        var msg  = data.msg;
                        switch(code){
                            case 1:
                                layer.alert(msg, {icon: 6,shade: false}, function(index){
                                    parent.location.reload();
                                });
                                break;
                            default:
                                layer.alert(msg, {icon: 5});
                        }
                    }
                });
            });

            $('#edit_btn_sumit').click(function(){
                var name_edit = $('#name_edit').val();
                var id = $('#departmentid').val();

                if(name_edit == ''){
                    layer.tips('角色名称不能为空', '#e_name');
                    return false;
                }
                $.ajax({
                    type        : 'POST',
                    data        : {
                        id  : id,
                        name  : name_edit
                    },
                    dataType :    'json',
                    url :         'user_role_do.php?act=edit',
                    success :     function(data){
                        var code = data.code;
                        var msg  = data.msg;
                        switch(code){
                            case 1:
                                layer.alert(msg, {icon: 6,shade: false}, function(index){
                                    parent.location.reload();
                                });
                                break;
                            default:
                                layer.alert(msg, {icon: 5});
                        }
                    }
                });
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
    <?php include('user_menu.inc.php');?>
    <div id="maincontent">
        <div id="position">当前位置：<a href="user_list.php">用户管理</a> &gt; 角色</div>
        <div id="handlelist">
            <input type="button" class="btn-handle fr" href="javascript:" id="add" value="添加">
        </div>
        <div class="tablelist">
            <table>
                <tr>
                    <th>序号</th>
                    <th>角色名称</th>
                    <th>操作</th>
                </tr>
                <?php
                if(!empty($rows)){
                    $i = 0;
                    foreach($rows as $row){
                        $i ++;
                        echo '<tr>
                                <td class="center">'.$i.'</td>
                                <td class="center">'.$row['name'].'</td>
                                <td class="center">
                                    <a class="editinfo" href="javascript:void(0)">修改</a>
                                    <a class="delete" href="javascript:void(0)">删除</a>
                                    <input type="hidden" id="aid" value="'.$row['id'].'"/>
                                </td>
                            </tr>';
                    }
                }else{
                    echo '<tr><td class="center" colspan="3">没有数据</td></tr>';
                }
                ?>

            </table>
        </div>
    </div>
    <div id="adddiv" style="display: none">
        <div id="formlist">
            <p>
                <label>角色名称</label>
                <input type="text" class="text-input input-length-30" name="name" id="name" />
                <span class="warn-inline" id="a_name">* </span>
            </p>
            <p>
                <label>　　</label>
                <input type="submit" id="add_btn_sumit" class="btn_submit" value="提　交" />
            </p>
        </div>
    </div>
    <div id="editdiv" style="display: none">
        <input type="hidden" id="departmentid" value=""/>
        <div id="formlist">
            <p>
                <label>角色名称</label>
                <input type="text" class="text-input input-length-30" name="name_edit" id="name_edit" />
                <span class="warn-inline" id="e_name">* </span>
            </p>
            <p>
                <label>　　</label>
                <input type="submit" id="edit_btn_sumit" class="btn_submit" value="提　交" />
            </p>
        </div>
    </div>
    <div class="clear"></div>
</div>
<?php include('footer.inc.php');?>
</body>
</html>