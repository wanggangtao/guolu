<?php
/**
 * 锅炉列表  guolu_list.php
 *
 * @version       v0.01
 * @create time   2018/5/27
 * @update time
 * @author        wzp
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */
require_once('admin_init.php');
require_once('admincheck.php');

$FLAG_TOPNAV	= "products";
$FLAG_LEFTMENU  = 'guolu_list';

$position = isset($_GET['position'])?$_GET['position']:1;
$vender = isset($_GET['vender'])?safeCheck($_GET['vender']):0;
$type = isset($_GET['type'])?safeCheck($_GET['type']):0;
$is_condensate = isset($_GET['is_condensate'])?safeCheck($_GET['is_condensate']):0;
$is_lownigtrogen = isset($_GET['is_lownigtrogen'])?safeCheck($_GET['is_lownigtrogen']):0;

//初始化
$page = 1;
$pageSize  = 15;
$count     = Guolu_attr::getList($page, $pageSize, 1, $vender, $type, $is_condensate, $is_lownigtrogen);

$pagecount = ceil($count / $pageSize);
$page      = getPage($pagecount);
$rows      = Guolu_attr::getList($page, $pageSize, 0, $vender, $type, $is_condensate, $is_lownigtrogen);
$nums = $count-($page-1)*$pageSize;


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="author" content="芝麻开发 http://www.zhimawork.com" />
    <title>锅炉列表 - 产品管理 - 产品中心 </title>
    <link rel="stylesheet" href="css/style.css" type="text/css" />
    <link rel="stylesheet" href="css/form.css" type="text/css" />
    <link rel="stylesheet" href="css/boxy.css" type="text/css" />
    <script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="js/layer/layer.js"></script>
    <script type="text/javascript" src="js/common.js"></script>
    <style>

        .click{
            font-size: 19px;
            font-weight: bold;
            background-color: #17568B;
            /*color: rgb(102,160,226);*/
        }
    </style>

    <script type="text/javascript">
        $(function(){



            //显示切换
            $('#showone').click(function () {
                $(this).addClass('click');
                $('#showother').removeClass('click');
                $('.show_one').show();
                $('.show_other').hide();
            });

            $('#showother').click(function () {
                $(this).addClass('click');
                $('#showone').removeClass('click');
                $('.show_one').hide();
                $('.show_other').show();
            });

            //查找
            $('#search').click(function(){
                var vender = $('#vender').val();
                var type = $('#type').val();
                var is_condensate = $('#is_condensate').val();
                var is_lownigtrogen = $('#is_lownigtrogen').val();

                location.href  = "guolu_list.php?vender="+vender+"&type="+type+"&is_condensate="+is_condensate+"&is_lownigtrogen="+is_lownigtrogen;
            });


            //添加
            $('#add').click(function(){
                layer.open({
                    type: 2,
                    title: '添加锅炉',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['1200px', '620px'],
                    content: 'guolu_add.php'
                });
            });

            //修改
            $(".editinfo").click(function(){
                var thisid = $(this).parent('td').find('#aid').val();
                layer.open({
                    type: 2,
                    title: '修改锅炉',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['1200px', '630px'],
                    content: 'guolu_edit.php?id='+thisid
                });
            });
            //详情
            $(".info").click(function(){
                var thisid = $(this).parent('td').find('#aid').val();
                layer.open({
                    type: 2,
                    title: '锅炉详情',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['1200px', '600px'],
                    content: 'guolu_info.php?id='+thisid
                });
            });
            //删除
            $(".delete").click(function(){
                var id = $(this).parent('td').find('#aid').val();
                var pid = $(this).parent('td').find('#pid').val();
                layer.confirm('确认删除该产品吗？', {
                        btn: ['确认','取消']
                    }, function(){
                        var index = layer.load(0, {shade: false});
                        $.ajax({
                            type        : 'POST',
                            data        : {
                                id:id,
                                pid:pid
                            },
                            dataType : 'json',
                            url : 'guolu_do.php?act=del',
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


            //添加壁挂锅炉
            $('#smalladd').click(function(){
                layer.open({
                    type: 2,
                    title: '添加锅炉',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['1200px', '620px'],
                    content: 'smallguolu_add.php'
                });
            });

            //修改壁挂锅炉
            $(".smalleditinfo").click(function(){
                var id = $(this).parent('td').find('#smallid').val();
                layer.open({
                    type: 2,
                    title: '修改锅炉',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['1200px', '630px'],
                    content: 'smallguolu_edit.php?id='+id
                });
            });

            $(".smallinfo").click(function(){
                var id = $(this).parent('td').find('#smallid').val();
                layer.open({
                    type: 2,
                    title: '锅炉详情',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['1200px', '600px'],
                    content: 'smallguolu_info.php?id='+id
                });
            });


            //查找
            $('#smallsearch').click(function(){
                var samllvender = $('#smallvender').val();
                location.href  = "guolu_list.php?vender="+samllvender+"&position=2";
            });


            //微信video
            $('.weixin_video').click(function () {
                var id = $(this).parent('td').find('#smallid').val();
                var v = $(this).parents("tr").find(".version").html();
                var n = $(this).parents("tr").find(".versionName").html();
                location.href = "weixin_video.php?id="+id+"&v="+v+"&n="+n;
            });


            //删除壁挂锅炉
            $(".smalldelete").click(function(){
                var id = $(this).parent('td').find('#smallid').val();
                var pid = $(this).parent('td').find('#smallpid').val();
                layer.confirm('确认删除该产品吗？', {
                        btn: ['确认','取消']
                    }, function(){
                        var index = layer.load(0, {shade: false});
                        $.ajax({
                            type        : 'POST',
                            data        : {
                                id:id,
                                pid:pid
                            },
                            dataType : 'json',
                            url : 'guolu_do.php?act=delsmall',
                            success : function(data){
                                layer.close(index);

                                var code = data.code;
                                var msg  = data.msg;
                                switch(code){
                                    case 1:
                                        layer.alert(msg, {icon: 6}, function(index){
                                            location.href = 'guolu_list.php?position=2';
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


            var position = <?php echo $position;?>;
            if(position === 2){
                $('#showother').click();
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
    <?php include('products_menu.inc.php');?>
    <div id="maincontent">
        <div id="position">当前位置：<a href="guolu_list.php">产品中心</a> &gt; 产品管理 &gt; 锅炉</div>
        <div>
            <label id="showone" class="btn-handle click">工业锅炉</label>
            <label id="showother" class="btn-handle ">壁挂锅炉</label>
        </div>
        <br/>

        <div class="show_one">
        <div id="handlelist">
            厂家
            <select id="vender" class="select-handle">
                <option value="0">全部</option>
                <?php
                    $list = Dict::getListByCat(1,1);

//                    print_r($list);

                    if($list)
                        foreach($list as $thisValue){
                            $selected = '';
                            if($thisValue['id'] == $vender)
                                $selected = 'selected';
                            echo '<option value="'.$thisValue['id'].'" '.$selected.'>'.$thisValue['name'].'</option>';
                        }
                ?>
            </select>
            类型
            <select id="type" class="select-handle">
                <option value="0">全部</option>
                <?php
                $list = Dict::getListByParentid(2);
                if($list)
                    foreach($list as $thisValue){
                        $selected = '';
                        if($thisValue['id'] == $type)
                            $selected = 'selected';
                        echo '<option value="'.$thisValue['id'].'" '.$selected.'>'.$thisValue['name'].'</option>';
                    }
                ?>
            </select>
            是否冷凝
            <select id="is_condensate" class="select-handle">
                <option value="0">全部</option>
                <?php
                $list = Dict::getListByParentid(3);
                if($list)
                    foreach($list as $thisValue){
                        $selected = '';
                        if($thisValue['id'] == $is_condensate)
                            $selected = 'selected';
                        echo '<option value="'.$thisValue['id'].'" '.$selected.'>'.$thisValue['name'].'</option>';
                    }
                ?>
            </select>
            是否低氮
            <select id="is_lownigtrogen" class="select-handle">
                <option value="0">全部</option>
                <?php
                $list = Dict::getListByParentid(4);
                if($list)
                    foreach($list as $thisValue){
                        $selected = '';
                        if($thisValue['id'] == $is_lownigtrogen)
                            $selected = 'selected';
                        echo '<option value="'.$thisValue['id'].'" '.$selected.'>'.$thisValue['name'].'</option>';
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
                    <th>型号</th>
                    <th>厂家</th>
                    <th>类型</th>
                    <th>额定热功率（kw）</th>
                    <th>操作</th>
                </tr>
                <?php
                if(!empty($rows)){
                    foreach($rows as $row){
                        $venderInfo = Dict::getInfoById($row['guolu_vender']);
                        $typeInfo =  Dict::getInfoById($row['guolu_type']);
                        echo '<tr>
                                <td class="center">'.$nums.'</td>
                                <td class="center">'.$row['guolu_version'].'</td>
                                <td class="center">'.$venderInfo['name'].'</td>
                                <td class="center">'.$typeInfo['name'].'</td>
                                <td class="center">'.$row['guolu_ratedpower'].'</td>
                                <td class="center">
                                    <a class="info" href="javascript:void(0)">详情</a> 
                                    <a class="editinfo" href="javascript:void(0)">修改</a>
                                    <a class="delete" href="javascript:void(0)">删除</a>
                                    <a href="pricelog_list.php?type=1&objectid='.$row['guolu_proid'].'&leftmenu=guolu_list">价格记录</a>
                                    <input type="hidden" id="aid" value="'.$row['guolu_id'].'"/>
                                    <input type="hidden" id="pid" value="'.$row['guolu_proid'].'"/>
                                </td>
                            </tr>';
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
                <span class="table_info">共<?php echo $count;?>条数据，共<?php echo $pagecount;?>页</span>
            </div>
            <?php
            if($pagecount>1){
                echo dspPages(getPageUrl(), $page, $pageSize, $count, $pagecount);
            }
            ?>
        </div>
        </div>

        <?php

            $smallvender = isset($_GET['smallvender'])?safeCheck($_GET['smallvender']):0;
            //初始化
            $small_page = 1;
            $small_count     = Smallguolu::getCount();
            $small_pagecount = ceil($small_count / $pageSize);
            $small_page      = getPage($small_pagecount);
            $small_guolu_rows = Smallguolu::getList($small_page, $pageSize, $vender);
            $small_nums = $small_count-($small_page-1)*$pageSize;
        ?>
        <div class="show_other" style="display: none">
        <div id="handlelist">
            厂家
            <select id="smallvender" class="select-handle">
                <option value="0">全部</option>
                <?php
                $list = Dict::getListByCat(1,2);
                if($list)
                    foreach($list as $thisValue){
                        $selected = '';
                        if($thisValue['id'] == $vender)
                            $selected = 'selected';
                        echo '<option value="'.$thisValue['id'].'" '.$selected.'>'.$thisValue['name'].'</option>';
                    }
                ?>
            </select>
            <input type="button" class="btn-handle" href="javascript:" id="smallsearch" value="查询">
            <input type="button" class="btn-handle fr" href="javascript:" id="smalladd" value="添加">
        </div>
        <div class="tablelist">
            <table>
                <tr>
                    <th>序号</th>
                    <th>型号</th>
                    <th>厂家</th>
                    <th>类型</th>
                    <th>额定热功率（kw）</th>
                    <th>操作</th>
                </tr>
                <?php
                if(!empty($small_guolu_rows)){
                    foreach($small_guolu_rows as $row){
                        $venderInfo = Dict::getInfoById($row['smallguolu_vender']);
                        echo '<tr>
                                <td class="center">'.$small_nums.'</td>
                                <td class="center version">'.$row['smallguolu_version'].'</td>
                                <td class="center versionName">'.$venderInfo['name'].'</td>
                                <td class="center">壁挂锅炉</td>
                                <td class="center">'.$row['smallguolu_power'].'</td>
                                <td class="center">
                                    <a class="weixin_video" href="javascript:void(0)">视频管理</a>
                                    <a class="smallinfo" href="javascript:void(0)">详情</a> 
                                    <a class="smalleditinfo" href="javascript:void(0)">修改</a>
                                    <a class="smalldelete" href="javascript:void(0)">删除</a>
                                    <input type="hidden" id="smallid" value="'.$row['smallguolu_id'].'"/>
                                    <input type="hidden" id="smallpid" value="'.$row['smallguolu_proid'].'"/>
                                </td>
                            </tr>';
                        $small_nums--;
                    }
                }else{
                    echo '<tr><td class="center" colspan="6">没有数据</td></tr>';
                }
                ?>

            </table>
        </div>
        <div id="pagelist">
            <div class="pageinfo">
                <span class="table_info">共<?php echo $small_count;?>条数据，共<?php echo $small_pagecount;?>页</span>
            </div>
            <?php
            if($small_pagecount>1){
                echo dspPages(getPageUrl(), $small_page, $pageSize, $small_count, $small_pagecount);
            }
            ?>
        </div>
        </div>
    </div>
    <div class="clear"></div>
</div>
<?php include('footer.inc.php');?>
</body>
</html>