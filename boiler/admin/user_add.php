<?php
/**
 * 添加用户  user_add.php
 *
 * @version       v0.01
 * @create time   2018/6/27
 * @update time
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

require_once('admin_init.php');
require_once('admincheck.php');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="author" content="芝麻开花 http://www.zhimawork.com" />
    <link rel="stylesheet" href="css/style.css" type="text/css" />
    <link rel="stylesheet" href="css/form.css" type="text/css" />
    <script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="js/layer/layer.js"></script>
    <script type="text/javascript" src="js/common.js"></script>
    <script type="text/javascript" src="js/upload.js"></script>
    <script type="text/javascript" src="laydate/laydate.js"></script>
    <script type="text/javascript">
        $(function(){
            laydate({
                elem: '#birthday', //需显示日期的元素选择器
                event: 'click', //触发事件
                format: 'YYYY-MM-DD', //日期格式
                istime: false, //是否开启时间选择
                isclear: true, //是否显示清空
                istoday: true, //是否显示今天
                issure: true, //是否显示确认
                festival: true, //是否显示节日
                choose: function(dates){ //选择好日期的回调
                }
            });

            $('#account').blur(function(){
                var account = $('#account').val();
                var reg = /^[1][3,4,5,7,8][0-9]{9}$/;
                if (!(reg.test(account))) {
                    layer.tips('手机号格式不正确！', '#account');
                    return false;
                }
                $.ajax({
                    type        : 'POST',
                    data        : {
                        account  : account,
                        id     : 0
                    },
                    dataType :    'json',
                    url :         'user_do.php?act=checkAccount',
                    success :     function(data){
                        switch(data.code){
                            case 1:
                                break;
                            default:
                                layer.alert(data.msg, {icon: 5});
                        }
                    }
                });
            });
            $('#password').blur(function(){
                var password = $('#password').val();
                if(password.length < 6 || password.length > 16){
                    layer.tips('密码格式不正确！', '#password');
                    return false;
                }
                var reg = /(?=.*\d)(?=.*[a-zA-Z]).{6,16}$/;
                if (!(reg.test(password))) {
                    layer.tips('密码格式不正确！', '#password');
                    return false;
                }
            });
            $('#btn_sumit').click(function(){
                var account = $('#account').val();
                var password = $('#password').val();
                var name = $('#name').val();
                var birthday = $('#birthday').val();
                var headimg = $('#headimg1').val();
                var parentid = $('#parent').val();
                var department = $('#department').val();
                var role = $('#role').val();
                if(account == ''){
                    layer.tips('账号不能为空', '#account');
                    return false;
                }
                if(password == ''){
                    layer.tips('密码不能为空', '#password');
                    return false;
                }
                if(name == ''){
                    layer.tips('姓名不能为空', '#name');
                    return false;
                }
                /*if(department == 0){
                    layer.tips('部门不能为空', '#department');
                    return false;
                }*/
                if(role == 0){
                    layer.tips('角色不能为空', '#role');
                    return false;
                }

                $.ajax({
                    type        : 'POST',
                    data        : {
                        account   : account,
                        password  : password,
                        name      : name,
                        birthday  : birthday,
                        headimg   : headimg,
                        parentid  : parentid,
                        department : department,
                        role      : role
                    },
                    dataType :    'json',
                    url :         'user_do.php?act=add',
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


        function getParentUser(){
           var department = $("#department").val();
           var role = $('#role').val();

           if(role==0) return false;
           if(role == 3){
               $("#department_parent").hide();
           }else{
               $("#department_parent").show();
           }
           var factDom = $("#parent");
            factDom.html("<option value=\"0\">----请选择----</option>");
            $.ajax({
                type        : 'POST',
                data        : {
                    department  : department,
                    role        : role
                },
                dataType :    'json',
                url :         'user_do.php?act=getParentuser',
                success :     function(data){
                    var code = data.code;
                    var data  = data.msg;
                    switch(code){
                        case 1:

                            var html = "";
                            for(var i=0;i<data.length;i++)
                            {
                                factDom.append("<option value='"+data[i].id+"'>"+data[i].name+"</option>")
                            }

                            break;
                        default:
                            layer.alert(data, {icon: 5});
                    }
                }
            });
        }

        function ajaxUpload(value){
            if($('#file'+value).val() == ''){
                layer.tips('请选择文件', '#file'+value, {tips: 3});
                return false;
            }
            var uploadUrl = 'all_upload.php?type=headimg';//处理文件
            $.ajaxFileUpload({
                url           : uploadUrl,
                fileElementId : 'file'+value,
                dataType      : 'json',
                success       : function(data){
                    var code = data.code;
                    var msg  = data.msg;
                    switch(code){
                        case 1:
                            $('#headimg'+ value).val(msg);
                            $('#val'+ value).attr("src",'<?php echo $HTTP_PATH;?>' + msg);
                            layer.msg('上传成功');
                            break;
                        default:
                            layer.alert(msg, {icon: 5});
                    }
                },
                error: function (data, status, e){
                    layer.alert(e);
                }
            })
            return false;
        }
    </script>
</head>
<body>
<div id="formlist">

    <p>
        <label>账号（手机号）</label>
        <input type="text" class="text-input input-length-30" name="account" id="account"/>
        <span class="warn-inline">* </span>
    </p>
    <p>
        <label>密码</label>
        <input type="password" class="text-input input-length-30" name="password" id="password"/>
        <span class="warn-inline">* (采用6-16个字符长度；不能用中文、空格；至少含数字/字母/符号2中组合；不能含有非法字符)</span>
    </p>
    <p>
        <label>姓名</label>
        <input type="text" class="text-input input-length-30" name="name" id="name"/>
        <span class="warn-inline">* </span>
    </p>
    <p>
        <label>角色</label>
        <select class="select-option" id="role" onchange="getParentUser()">
            <option value="0">----请选择----</option>
            <?php
            $roleList = User_role::getAllList();
            if(!empty($roleList))
            {
                foreach ($roleList as $item)
                {
                    echo "<option value='{$item["id"]}'>{$item["name"]}</option>";
                }
            }
            ?>

        </select>
        <span class="warn-inline">* </span>
    </p>
    <div id="department_parent">
    <p>
        <label>部门</label>
        <select class="select-option" id="department" onchange="getParentUser()">
            <option value="0">----请选择----</option>
            <?php
                $departmentList = User_department::getAllList();
                if(!empty($departmentList))
                {
                    foreach ($departmentList as $item)
                    {
                        echo "<option value='{$item["id"]}'>{$item["name"]}</option>";
                    }
                }
            ?>

        </select>
        <span class="warn-inline">* </span>
    </p>

    <p>
        <label>用户主管人</label>
        <select class="select-option" id="parent">
           <option value="0">----请选择----</option>
        </select>
    </p>
    </div>
    <p>
        <label>生日</label>
        <input type="text" class="text-input input-length-30" name="birthday" id="birthday"/>
    </p>
    <p>
        <label>用户头像</label>
        <input id="headimg1"  name="headimg1" type="hidden"/>
        <input id="file1" class="upfile_btn" type="file" name="file" style="height:24px;"/>
        <input type="button" class="upfile_btn btn-handle" onclick="return ajaxUpload(1);" value="上传"/>
    </p>
    <p style="padding-left:150px;"><img id="val1" src=""  /></p>
    <p>
        <label>　　</label>
        <input type="submit" id="btn_sumit" class="btn_submit" value="提　交" />
    </p>
</div>
</body>
</html>