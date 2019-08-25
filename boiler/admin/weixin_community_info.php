<?php

require_once('admin_init.php');
require_once('admincheck.php');

$FLAG_TOPNAV    = "weixincontent";
$FLAG_LEFTMENU  = 'weixin_community_info';
$params=array();
$name="";
$brand="";
if(isset($_GET['name'])){
    $params['name']=$_GET['name'];
    $name=$_GET['name'];
}
if(isset($_GET['brand'])){
    if($_GET['brand']){
        $params['brand']=$_GET['brand'];
    }
    $brand=$_GET['brand'];
}
//$params["type"]=0;
//初始化
$count     = Community::getCount($params);
$pageSize  = 10;

$pagecount = ceil($count / $pageSize);

$page      = getPage($pagecount);

$params['page']=$page;
$params['pageSize']=$pageSize;
$rows      = Community::getList($params);

$brand_list=Dict::getListByPid(1,1);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="author" content="芝麻开发 http://www.zhimawork.com" />
    <title>信息管理 - 小区管理</title>
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
                var name = $('#name').val();
                var brand = $('#brand_value').val();
                //alert(brand);
                location.href  = "weixin_community_info.php?name="+name+"&brand="+brand;
            });
            //添加
            $('#add').click(function(){
                layer.open({
                    type: 2,
                    title: '添加小区',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['600px', '500px'],
                    content: 'weixin_community_add.php'
                });
            });
            //修改
            $(".editinfo").click(function(){
                var thisid = $(this).parent('td').find('#cid').val();
                layer.open({
                    type: 2,
                    title: '修改小区',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['600px', '500px'],
                    content: 'weixin_community_edit.php?id='+thisid
                });
            });
            //详情
            $(".edittype").click(function(){
                var id = $(this).parent('td').find('#cid').val();
                layer.confirm('确认修改该小区类型吗？', {
                        btn: ['确认','取消']
                    }, function(){
                        var index = layer.load(0, {shade: false});
                        $.ajax({
                            type        : 'POST',
                            data        : {
                                id:id
                            },
                            dataType : 'json',
                            url : 'weixin_community_do.php?act=edittype',
                            success : function(data){
                                layer.close(index);
                                var code = data.code;
                                var msg  = data.msg;
                                switch(code){
                                    case 1:
                                        layer.alert(msg, {icon: 6}, function(index){
                                            parent.location.reload();
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
            $(".delete").click(function(){
                var id = $(this).parent('td').find('#cid').val();
                layer.confirm('确认删除该小区吗？', {
                        btn: ['确认','取消']
                    }, function(){
                        var index = layer.load(0, {shade: false});
                        $.ajax({
                            type        : 'POST',
                            data        : {
                                id:id
                            },
                            dataType : 'json',
                            url : 'weixin_community_do.php?act=del',
                            success : function(data){
                                layer.close(index);

                                var code = data.code;
                                var msg  = data.msg;
                                switch(code){
                                    case 1:
                                        layer.alert(msg, {icon: 6}, function(index){
                                            parent.location.reload();
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
    <?php include('weixin_menu_inc.php');?>
    <div id="maincontent">
        <div id="position">当前位置：<a href="user_list.php">信息管理</a> &gt; 小区管理</div>
        <div id="handlelist">
            小区
            <input type="text" class="text-input input-length-10" id="name" value="<?php echo $name?>" style="
                   top: -3px;
                   line-height: 20px;
                   position: relative;

                 ">
            品牌
            <select  id="brand_value" class="select-handle">
                <option value="0">全部</option>
                <?php
                if($brand_list)
                    $select="selected";
                    foreach($brand_list as $thisValue){
                    if($thisValue['id']==$brand){
                        echo '<option value="' . $thisValue['id'] . '" '.$select.'>' . $thisValue['name'] . '</option>';
                    }else{
                        echo '<option value="' . $thisValue['id'] . '">' . $thisValue['name'] . '</option>';
                    }

                    }
                ?>
            </select>
            <input type="button" class="btn-handle" href="javascript:" id="search" value="查询">
            <input type="button" class="btn-handle fr" href="javascript:" id="add" value="添加">
        </div>
        <div class="tablelist">
            <table>
                <tr>
                    <th>序号</th>
                    <th>小区</th>
                    <th>锅炉品牌</th>
                    <th>地址</th>
                    <th>类型</th>
                    <th>操作</th>
                </tr>
                <?php
                if(!empty($rows)){
                    $i = ($page-1)*$pageSize+1;
                    foreach($rows as $row){
                        $attrs=array();
                        $brand_name="";
                        if($row['brand']){
                            if(strstr($row['brand'],",")){
                                $attrs=explode(",",$row['brand']);
                            }else{
                                $attrs[0]=$row['brand'];
                            }
                            foreach($attrs as $value){

                                $brand_name.=Dict::getInfoById($value)['name'];
                                $brand_name.=" ";

                            }
                        }else{
                            $brand_name="无";
                        }
                        $type="后台添加";
                        if($row['type']==1){
                            $type="客服添加";
                        }
                        echo '<tr>
                                <td class="center">'.$i.'</td>
                                <td class="center">'.$row['name'].'</td>
                                <td class="center">'.$brand_name.'</td>
                                <td class="center">'.$row['provice_name'].$row['city_name'].$row['area_name'].'</td>
                               <td class="center">'.$type.'</td>
                                <td class="center">';
                                    if($row['type']!=-1){
                                        echo '<a class="edittype" href="javascript:void(0)">类型修改</a>&nbsp;';
                                    }else{
                                        echo '<a class="edittype" href="javascript:void(0)">--------</a>&nbsp;';
                                    }
                                   echo  '<a class="editinfo" href="javascript:void(0)">修改</a>
                                    <a class="delete" href="javascript:void(0)">删除</a>
                                    <input type="hidden" id="cid" value="'.$row['id'].'"/>
                                </td>
                            </tr>';
                        $i++;
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