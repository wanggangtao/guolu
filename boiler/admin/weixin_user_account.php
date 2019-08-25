<?php

require_once('admin_init.php');
require_once('admincheck.php');

$FLAG_TOPNAV    = "weixincontent";
$FLAG_LEFTMENU  = 'weixincontent';
$params=array();
$choice=1;
$queryvalue=null;

if(isset($_GET['queryvalue']))
{
    $queryvalue=$_GET['queryvalue'];
}
if(isset($_GET['choice'])){
    $choice = $_GET['choice'];
    if($choice != 0 && $queryvalue != null){
        switch ($choice){
            case 1:
               $params['name'] = $queryvalue;
               break;
            case 2:
                $params['phone'] = $queryvalue;
                break;
            case 3:
                $params['contact_address'] = $queryvalue;
                break;
            case 4:
                $params['product_code'] = $queryvalue;
                break;
            case 5:
                $params['nickname'] = $queryvalue;
                break;
            default:
                break;
        }
    }
}

//初始化
$count = User_account::getCount($params);
$pageSize  = 10;

$pagecount = ceil($count / $pageSize);

$page      = getPage($pagecount);

$params['page']=$page;
$params['pageSize']=$pageSize;
$rows      = User_account::getList($params);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="author" content="芝麻开发 http://www.zhimawork.com" />
    <title>公众号管理- 用户管理</title>
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
                var index = $('#address_one').val();
                var queryvalue = $('#query_value').val();
                location.href  = "weixin_user_account.php?choice="+index+"&queryvalue="+queryvalue;
            });
            //添加
            $('#add').click(function(){
                layer.open({
                    type: 2,
                    title: '添加用户',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['600px', '600px'],
                    content: 'weixin_user_add.php'
                });
            });

            //导入用户
            $('#import').click(function(){
                layer.open({
                    type: 2,
                    title: '用户批量导入',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['600px', '500px'],
                    content: 'weixin_import_user.php'
                });
            });
            //导出用户
            $('#export').click(function(){
                layer.confirm('确认导出用户信息？', {
                        btn: ['确认','取消']
                    }, function(){
                    var index = layer.load(0, {shade: false});
                    $.ajax({
                        type        : 'POST',
                        data        : {
                        },
                        dataType : 'json',
                        url : 'weixin_user_do.php?act=export',
                        success : function(data){
                            layer.close(index);

                            var code = data.code;
                            var msg  = data.msg;
                            str=msg.split(",");
                            msg=str[0];
                            switch(code){
                                case 1:
                                    layer.alert(msg, {icon: 6,shade: false}, function(index){
                                        location.href = '<?php echo $HTTP_PATH;?>userfiles/upload/<?php echo date("Ymd");?>/'+str[1];
                                        layer.close(index);
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
            //修改
            $(".editinfo").click(function(){
                var thisid = $(this).parent('td').find('#cid').val();
                console.log(thisid);
                layer.open({
                    type: 2,
                    title: '修改用户',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['600px', '600px'],
                    content: 'weixin_user_edit.php?id='+thisid
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
                    content: 'weixin_user_record.php?id='+thisid
                });
            });

            //删除
            $(".delete").click(function(){
                var id = $(this).parent('td').find('#cid').val();
                console.log(id);
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
                            url : 'weixin_user_do.php?act=del',
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
        <div id="position">当前位置：<a href="user_list.php">公众号管理</a> &gt; 用户管理</div>
        <div id="handlelist">
            <select  id="address_one" class="select-handle">
<!--                <option value="0" --><?php //if($choice == 0) echo "selected=\"selected\""; ?><!-->全部</option>-->
                <option value="5" <?php if($choice == 5) echo "selected=\"selected\""; ?>>昵称</option>
                <option value="1" <?php if($choice == 1) echo "selected=\"selected\""; ?>>联系人</option>
                <option value="2" <?php if($choice == 2) echo "selected=\"selected\""; ?>>电话</option>
                <option value="3" <?php if($choice == 3) echo "selected=\"selected\""; ?>>地址</option>
                <option value="4" <?php if($choice == 4) echo "selected=\"selected\""; ?>>条码</option>

            </select>
            <input type="text"   id="query_value" value="<?php echo $queryvalue?>" style="
                   top: -3px;
                   line-height: 20px;
                   position: relative;

                 ">
            <input type="button" class="btn-handle" href="javascript:" id="search" value="查询">
            <input type="button" class="btn-handle fr" href="javascript:" id="add" value="添加">
        </div>
        <div class="tablelist">
            <table>
                <tr>
                    <th>序号</th>
                    <th>联系人</th>
                    <th>昵称</th>
                    <th>条码</th>
                    <th>联系地址</th>

                    <th>注册电话</th>
                    <th>品牌</th>
                    <th>型号</th>
                    <th>操作</th>
                </tr>
                <?php
                if(!empty($rows)){
                    $i = ($page-1)*$pageSize+1;
                    foreach($rows as $row){
//                        var_dump($row);
                        $code="暂无";
                        $brand="暂无";
                        $version="暂无";
                        $address="暂无";
//                        if($row['contact_address']){
//                            $address= $row['contact_address'];
//                        }
                        if($row['product_code']){
                            $code=$row['product_code'];
                            $pro_info=Product_info::getInfoByBarCode($row['product_code']);

                            if($pro_info){
                                $address=$pro_info['all_address'];
                                $brand=Dict::getInfoById($pro_info['brand'])['name'];
                                $temp = Smallguolu::getInfoById($pro_info['version']);
                                if($temp != null)
                                {
                                    $version=$temp['version'];
                                }
//                                print_r($temp);
//                                echo $temp;
//                                $version=Guolu_attr::getInfoById($pro_info['version'])['version'];
//                                $version=$pro_info['version'];
                            }
                        }

                        echo '<tr>
                               
                                <td class="center">'.$i.'</td>
                                <td class="center">'.$row['name'].'</td>
                                <td class="center">'.$row['nickname'].'</td>
                                <td class="center">'.$code.'</td>
                              
                                <td class="center">'.$address.'</td>
                                 <td class="center">'.$row['phone'].'</td>
                                <td class="center">'.$brand.'</td>
                                <td class="center">'.$version.'</td>
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
                    echo '<tr><td class="center" colspan="8">没有数据</td></tr>';
                }
                ?>

            </table>
        </div>
        <br>
        <input type="button" class="btn-handle" href="javascript:" id="import" value="批量导入">
        <input type="button" class="btn-handle" href="javascript:" id="export" value="批量导出">
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