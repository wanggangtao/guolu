<?php
/**
 * 产品属性列表  dict_list.php
 *
 * @version       v0.01
 * @create time   2018/5/27
 * @update time
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */
require_once('admin_init.php');
require_once('admincheck.php');

$cat = isset($_GET["cat"])?safeCheck($_GET["cat"]):0;
$catcid =  isset($_GET["catcid"])?safeCheck($_GET["catcid"]):0;
$FLAG_TOPNAV	= "products";
$FLAG_LEFTMENU  = 'cat_dict'.$cat;
$catinfo = Category::getInfoById($cat);
if(empty($catinfo)){
    echo '非法操作';
    die();
}

$catlist = Category::getInfoByParentid($cat);
if(empty($catlist)){
    echo $catinfo['name'].'还未添加子类别，请前往添加'.'<a href="category.php">点击这里跳转</a>';
    die();
}
if($catcid == 0 && !empty($catlist)){
    $catcid = $catlist[0]['id'];
}
$catchidinfo = Category::getInfoById($catcid);
//初始化
//$page=1;
//$pageSize  = 15;
$totalcount= Dict::getPageList(1, 10, 0, 0, 1, $catcid);
//$pagecount = ceil($totalcount / $pageSize);
//$page      = getPage($pagecount);
$rows      = Dict::getPageList(1, 10, 1, 0, 1, $catcid);
//$nums = $totalcount-($page-1)*$pageSize;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="author" content="芝麻开发 http://www.zhimawork.com" />
    <title><?php if(count($catlist) > 1){ echo $catchidinfo['name'].' - ';} echo $catinfo['name'];?> - 属性管理 - 产品中心 </title>
    <link rel="stylesheet" href="css/style.css" type="text/css" />
    <link rel="stylesheet" href="css/form.css" type="text/css" />
    <link rel="stylesheet" href="css/boxy.css" type="text/css" />
    <script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="js/layer/layer.js"></script>
    <script type="text/javascript" src="js/common.js"></script>
    <script type="text/javascript">
        $(function(){
            var modelid = '<?php echo $catcid;?>';
            //添加类别
            $('#addcat').click(function(){
                layer.open({
                    type: 2,
                    title: '添加产品属性',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['500px', '200px'],
                    content: 'dict_add.php?id=0&modelid='+ modelid
                });
            });
            //添加属性值
            $('.addvalue').click(function(){
            	var thisid = $(this).parent('td').find('#aid').val();
                layer.open({
                    type: 2,
                    title: '属性值',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['500px', '400px'],
                    content: 'dict_values.php?id='+ thisid
                });
            });
            //修改类别
            $(".editinfo").click(function(){
                var thisid = $(this).parent('td').find('#aid').val();
                layer.open({
                    type: 2,
                    title: '修改产品属性',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['500px', '200px'],
                    content: 'dict_add.php?id='+thisid+'&modelid='+ modelid
                });
            });

            //删除管理员
            /*$(".delete").click(function(){
                var thisid = $(this).parent('td').find('#aid').val();
                layer.confirm('确认删除该属性吗？', {
                        btn: ['确认','取消']
                    }, function(){
                        var index = layer.load(0, {shade: false});
                        $.ajax({
                            type        : 'POST',
                            data        : {
                                id:thisid
                            },
                            dataType : 'json',
                            url : 'dict_do.php?act=del',
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
            });*/
        });

    </script>
</head>
<body>
<div id="header">
    <?php include('top.inc.php');?>
    <?php include('nav.inc.php');?>
</div>
<div id="container">
    <?php include('products_menu.inc.php');?>
    <div id="maincontent">
        <div id="position">当前位置：<a href="guolu_list.php">产品中心</a> &gt; 属性管理 &gt; <?php echo $catinfo['name']; if(count($catlist) > 1){ echo ' &gt; '.$catchidinfo['name']; }?></div>
        <?php if(count($catlist) > 1){ ?>
            <div id="tablist">
                <ul>
                    <?php
                    foreach ($catlist as $child){
                        $active = '';
                        if($catcid == $child['id'])
                            $active = 'class="active"';
                        echo '<li '.$active.'><a href="dict_list.php?cat='.$cat.'&catcid='.$child['id'].'">'.$child['name'].'</a></li>';
                    }
                    ?>
                </ul>
            </div>
        <?php } ?>
        <div id="handlelist">
<!--            <input type="button" class="btn-handle" href="javascript:" id="addcat" value="添 加">-->
            <span class="table_info">共<?php echo $totalcount;?>条数据</span>
            <div>
            </div>
        </div>
        <div class="tablelist">
            <table>
                <tr>
                    <th>序号</th>
                    <th>属性名称</th>
                    <th>属性编码</th>
                    <th>操作人</th>
                    <th>操作时间</th>
                    <th>操作</th>
                </tr>
                <?php

                $i=1;
                //如果列表不为空
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
                                    <td class="center">'.$i.'</td>
                                    <td class="center">'.$row['name'].'</td>
                                    <td class="center">'.$row['code'].'</td>
                                    <td class="center">'.$account.'</td>
                                    <td class="center">'.date("Y-m-d H:i:s",$row['addtime']).'</td>
                                    <td class="center">
                                        <a class="addvalue" href="javascript:void(0)">属性值</a> 
                                        <a class="editinfo" href="javascript:void(0)">修改</a>
                                        <!--<a class="delete" href="javascript:void(0)">删除</a>-->
                                        <input type="hidden" id="aid" value="'.$row['id'].'"/>
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
    </div>
    <div class="clear"></div>
</div>
<?php include('footer.inc.php');?>
</body>
</html>