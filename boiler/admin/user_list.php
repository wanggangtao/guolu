<?php
/**
 * 用户列表  user_list.php
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
$FLAG_LEFTMENU  = 'user_list';


$department = isset($_GET['department'])?safeCheck($_GET['department']):0;
$role = isset($_GET['role'])?safeCheck($_GET['role']):0;
$status = isset($_GET['status'])?safeCheck($_GET['status']):0;
$name = isset($_GET['name'])?safeCheck($_GET['name'], 0):"";

//初始化
$count     = User::getPageList(1, 10, 0, $name, $status, $department, $role);
$pageSize  = 15;
$pagecount = ceil($count / $pageSize);
$page      = getPage($pagecount);
$rows      = User::getPageList($page, $pageSize, 1, $name, $status, $department, $role);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="author" content="芝麻开发 http://www.zhimawork.com" />
    <title>用户列表 - 用户管理</title>
    <link rel="stylesheet" href="css/style.css" type="text/css" />
    <link rel="stylesheet" href="css/form.css" type="text/css" />
    <link rel="stylesheet" href="css/boxy.css" type="text/css" />
    <script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="js/layer/layer.js"></script>
    <script type="text/javascript" src="js/common.js"></script>
    <script type="text/javascript">
        $(function(){
            layer.config({
                extend: 'extend/layer.ext.js'
            });
            //查找
            $('#search').click(function(){
                var department = $('#department').val();
                var role = $('#role').val();
                var status = $('#status').val();
                var name = $('#name').val();

                location.href  = "user_list.php?department="+department+"&role="+role+"&status="+status+"&name="+name;
            });
            //添加
            $('#add').click(function(){
                layer.open({
                    type: 2,
                    title: '添加用户',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['600px', '500px'],
                    content: 'user_add.php'
                });
            });
            //修改
            $(".editinfo").click(function(){
                var thisid = $(this).parent('td').find('#aid').val();
                layer.open({
                    type: 2,
                    title: '修改用户',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['600px', '500px'],
                    content: 'user_edit.php?id='+thisid
                });
            });
            //详情
            $(".info").click(function(){
                var thisid = $(this).parent('td').find('#aid').val();
                layer.open({
                    type: 2,
                    title: '用户详情',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['600px', '500px'],
                    content: 'user_detail.php?id='+thisid
                });
            });
            //删除
            $(".delete").click(function(){
                var id = $(this).parent('td').find('#aid').val();
                layer.confirm('确认删除该用户吗？', {
                        btn: ['确认','取消']
                    }, function(){
                        var index = layer.load(0, {shade: false});
                        $.ajax({
                            type        : 'POST',
                            data        : {
                                id:id
                            },
                            dataType : 'json',
                            url : 'user_do.php?act=del',
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
            //删除
            $(".reset").click(function(){
                var id = $(this).parent('td').find('#aid').val();
                layer.confirm('确认重置密码吗？', {
                        btn: ['确认','取消']
                    }, function(){
                    layer.prompt({
                        title: '请输新密码',
                        value: '',
                        formType: 1
                    }, function (newpass, indexp) {
                        if(newpass.length < 6 || newpass.length > 16){
                            layer.msg('密码格式不正确！');
                            return false;
                        }
                        var reg = /(?=.*\d)(?=.*[a-zA-Z]).{6,16}$/;
                        if (!(reg.test(newpass))) {
                            layer.msg('密码格式不正确！');
                            return false;
                        }
                        layer.close(indexp);
                        var index = layer.load(0, {shade: false});
                        $.ajax({
                            type        : 'POST',
                            data        : {
                                id : id,
                                newpass:newpass
                            },
                            dataType : 'json',
                            url : 'user_do.php?act=reset',
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
    <?php include('user_menu.inc.php');?>
    <div id="maincontent">
        <div id="position">当前位置：<a href="user_list.php">用户管理</a> &gt; 用户</div>
        <div id="handlelist">
            部门
            <select id="department" class="select-handle">
                <option value="0">全部</option>
                <?php
                    $list = User_department::getAllList();
                    if($list)
                        foreach($list as $thisValue){
                            $selected = '';
                            if($thisValue['id'] == $department)
                                $selected = 'selected';
                            echo '<option value="'.$thisValue['id'].'" '.$selected.'>'.$thisValue['name'].'</option>';
                        }
                ?>
            </select>
            角色
            <select id="role" class="select-handle">
                <option value="0">全部</option>
                <?php
                $list = User_role::getAllList();
                if($list)
                    foreach($list as $thisValue){
                        $selected = '';
                        if($thisValue['id'] == $role)
                            $selected = 'selected';
                        echo '<option value="'.$thisValue['id'].'" '.$selected.'>'.$thisValue['name'].'</option>';
                    }
                ?>
            </select>
            状态
            <select id="status" class="select-handle">
                <option value="0">全部</option>
                <?php
                    foreach($ARRAY_uesr_status as $key => $val){
                        $selected = '';
                        if($key == $status)
                            $selected = 'selected';
                        echo '<option value="'.$key.'" '.$selected.'>'.$val.'</option>';
                    }
                ?>
            </select>
            姓名
            <input type="text" class="text-input input-length-10" id="name" value="<?php echo $name;?>">
            <input type="button" class="btn-handle" href="javascript:" id="search" value="查询">
            <input type="button" class="btn-handle fr" href="javascript:" id="add" value="添加">
        </div>
        <div class="tablelist">
            <table>
                <tr>
                    <th>账号</th>
                    <th>姓名</th>
                    <th>部门</th>
                    <th>角色</th>
                    <th>主管</th>
                    <th>状态</th>
                    <th>操作</th>
                </tr>
                <?php
                if(!empty($rows)){
                    foreach($rows as $row){
                        $departmentInfo = User_department::getInfoById($row['department']?$row['department']:0);
                        $roleInfo = User_role::getInfoById($row['role']);
                        $parentInfo = User::getInfoById($row['parent']?$row['parent']:0);
                        $pname = '-';
                        if($parentInfo)
                            $pname = $parentInfo['name'];
                        echo '<tr>
                                <td class="center">'.$row['account'].'</td>
                                <td class="center">'.$row['name'].'</td>
                                <td class="center">'.($departmentInfo?$departmentInfo['name']:'').'</td>
                                <td class="center">'.$roleInfo['name'].'</td>
                                <td class="center">'.$pname.'</td>
                                <td class="center">'.$ARRAY_uesr_status[$row['status']].'</td>
                                <td class="center">
                                    <a class="info" href="javascript:void(0)">详情</a> 
                                    <a class="editinfo" href="javascript:void(0)">修改</a>
                                    <a class="delete" href="javascript:void(0)">删除</a>
                                    <a class="reset" href="javascript:void(0)">重置密码</a>
                                    <input type="hidden" id="aid" value="'.$row['id'].'"/>
                                </td>
                            </tr>';
                    }
                }else{
                    echo '<tr><td class="center" colspan="7">没有数据</td></tr>';
                }
                ?>

            </table>
        </div>
        <div id="pagelist">
            <div class="pageinfo">
                <span class="table_info">共<?php echo $count;?>条数据，共<?php echo $pagecount;?>页</span>
            </div>
            <?php
            if($pagecount>1){
                echo dspPages(getPageUrl(), $page, $pageSize, $count, $pagecount);
            }
            ?>
        </div>
    </div>
    <div class="clear"></div>
</div>
<?php include('footer.inc.php');?>
</body>
</html>