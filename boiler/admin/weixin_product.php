<?php

require_once('admin_init.php');
require_once('admincheck.php');

$FLAG_TOPNAV    = "weixincontent";
$FLAG_LEFTMENU  = 'weixincontent';
$params=array();
$name=null;
$starttime=null;
$stoptime=null;
if(isset($_GET['code'])){
    $name=$_GET['code'];
    if(!empty($name)){
        $params['code']=$name;
    }
}
if(isset($_GET['starttime'])){
    $starttime=$_GET['starttime'];
    if(!empty($starttime)){
        $params['starttime'] = strtotime($starttime);
    }
}

if(isset($_GET['stoptime'])){
    $stoptime=$_GET['stoptime'];
    if(!empty($stoptime)){
        $params['stoptime'] = strtotime($stoptime);
    }
}
//初始化
$count     = Product_info::getCount($params);
$pageSize  = 10;

$pagecount = ceil($count / $pageSize);

$page      = getPage($pagecount);

$params['page']=$page;
$params['pageSize']=$pageSize;
$rows      = Product_info::getList($params);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="author" content="芝麻开发 http://www.zhimawork.com" />
    <title>公众号管理- 产品管理</title>
    <link rel="stylesheet" href="css/style.css" type="text/css" />
    <link rel="stylesheet" href="css/form.css" type="text/css" />
    <link rel="stylesheet" href="css/boxy.css" type="text/css" />
    <script type="text/javascript" src="../static/js/layer/layer.js"></script>
    <script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="js/layer/layer.js"></script>
    <script type="text/javascript" src="js/common.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            layer.config({
                extend: 'extend/layer.ext.js'
            });
            //查找
            $('#search').click(function(){
                var name = $('#name').val();
                var startTime = $('#starttime').val();
                var stopTime = $('#stoptime').val();
                location.href  = "weixin_product.php?code="+name+"&starttime="+startTime+"&stoptime="+stopTime;
            });
            //添加
            $('#add').click(function(){
                layer.open({
                    type: 2,
                    title: '添加产品',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['600px', '500px'],
                    content: 'weixin_product_add.php'
                });
            });
            $('#import').click(function(){
                layer.open({
                    type: 2,
                    title: '产品批量导入',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['600px', '500px'],
                    content: 'weixin_import_product.php'
                });
            });
            //修改
            $(".editinfo").click(function(){
                var thisid = $(this).parent('td').find('#cid').val();
                console.log(thisid);
                layer.open({
                    type: 2,
                    title: '修改产品',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['600px', '500px'],
                    content: 'weixin_product_edit.php?id='+thisid
                });
            });
            //详情
            $(".view_record").click(function(){
                var thisid = $(this).parent('td').find('#cid').val();
                console.log(thisid);
                layer.open({
                    type: 2,
                    title: '服务记录查看',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['1000px', '500px'],
                    content: 'weixin_product_record.php?id='+thisid
                });
            });
            //删除
            $(".delete").click(function(){
                var id = $(this).parent('td').find('#cid').val();
                layer.confirm('如果删除，将会删除该产品所有的数据信息，是否继续操作？', {
                        btn: ['确认','取消']
                    }, function(){
                        var index = layer.load(0, {shade: false});
                        $.ajax({
                            type        : 'POST',
                            data        : {
                                id:id
                            },
                            dataType : 'json',
                            url : 'weixin_product_do.php?act=del',
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
        <div id="position">当前位置：<a href="user_list.php">公众号管理</a> &gt; 产品管理</div>
        <div id="handlelist">
            条码
            <input type="text" class="text-input input-length-10" id="name" value="<?php echo $name; ?>" >

            <label for="exampleInputEmail2">质保期</label>

            <input type="text" class="laydate-icon input-length-30"  name="starttime" id="starttime" value="<?php echo $starttime; ?>" />
            <label> ~ </label>
            <input type="text" class="laydate-icon input-length-30"  name="stoptime" id="stoptime" value="<?php echo $stoptime; ?>" />
            <script type="text/javascript" src="laydate/laydate.js"></script>
            <script type="text/javascript">
                laydate.skin("molv");//设置皮肤
                var start = {
                    elem: '#starttime',
                    format: 'YYYY-MM-DD hh:mm:ss',
                    istime: true,
                    istoday: false,
                    choose: function(datas){
                        end.min = datas; //开始日选好后，重置结束日的最小日期
                        end.start = datas; //将结束日的初始值设定为开始日
                    }
                };
                var end = {
                    elem: '#stoptime',
                    format: 'YYYY-MM-DD hh:mm:ss',
                    istime: true,
                    choose: function(datas){
                        end.min = datas; //开始日选好后，重置结束日的最小日期
                        end.start = datas; //将结束日的初始值设定为开始日
                    }
                };
                laydate(start);
                laydate(end);
            </script>

            <input type="button" class="btn-handle" href="javascript:" id="search" value="查询">
            <input type="button" class="btn-handle fr" href="javascript:" id="add" value="添加">
        </div>
        <div class="tablelist">
            <table>
                <tr>
                    <th>序号</th>
                    <th>条码</th>
                    <th>品牌</th>
                    <th>型号</th>
<!--                    <th>类型</th>-->
                    <th>质保期</th>
                    <th>地址</th>
                    <th>操作</th>
                </tr>
                <?php
                if(!empty($rows)){
                    $i = ($page-1)*$pageSize+1;
                    foreach($rows as $row){
                        $address = "";
                        $brand=Dict::getInfoById($row['brand'])['name'];
                        //<td class="center">'.$type.'</td>在brand下面一行，表示类型
//                        $type=Dict::getInfoById($row['type'])['name'];
                        $address="--";
                        $user_info=User_account::getInfoByBarCode($row['code']);
//                        print_r($row['code']);
                        if($user_info){
                            if($user_info['contact_address']){
                                $address=$user_info['contact_address'];
                            }
                        }
                        $guolu_version=Smallguolu::getInfoById($row['version']);
                        $version="无";
                        if($guolu_version){
                            $version=$guolu_version['version'];
                        }
                        $date="";
                        if($row['period']){
                            if(is_numeric($row['period'])){
                                $date=date("Y-m-d",$row['period']);
                            }else{
                                $date="过保";
                            }

                        }

                        echo '<tr>
                                <td class="center">'.$i.'</td>
                                <td class="center">'.$row['code'].'</td>
                                <td class="center">'.$brand.'</td>
                                <td class="center">'.$version.'</td>
                                <td class="center">'.$date.'</td>
                                <td class="center">'.$row['all_address'].'</td>
                                <td class="center">
                                    <a class="view_record" href="javascript:void(0)">服务记录</a>
                                    <a class="editinfo" href="javascript:void(0)">修改</a>
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
        <br>
        <input type="button" class="btn-handle" href="javascript:" id="import" value="批量导入">
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