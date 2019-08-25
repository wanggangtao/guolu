<?php
/**
 * 价格记录列表  pricelog_list.php
 *
 * @version       v0.01
 * @create time   2018/10/20
 * @update time
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */
require_once('admin_init.php');
require_once('admincheck.php');

$objectid = isset($_GET['objectid'])?safeCheck($_GET['objectid']):0;
$type = isset($_GET['type'])?safeCheck($_GET['type']):0;
$leftmenu = isset($_GET['leftmenu'])?safeCheck($_GET['leftmenu'], 0):'';

if($type == 1){
	$FLAG_TOPNAV    = "products";
	$FLAG_LEFTMENU  = $leftmenu;
	$productsinfo = Products::getInfoById($objectid);
	$modelinfo = Products_model::getInfoById($productsinfo['modelid']);
}else{
	$FLAG_TOPNAV	= "seletion";
	$FLAG_LEFTMENU  = 'quote_list';
}

$addtype = isset($_GET['addtype'])?safeCheck($_GET['addtype']):0;
$sttime = isset($_GET['sttime'])?safeCheck($_GET['sttime']):0;
$endtime = isset($_GET['endtime'])?safeCheck($_GET['endtime']):0;

//初始化
$countarr     = Case_pricelog::getPageList(1, 10, 0, $type, $objectid, $addtype, $sttime, $endtime);
$count     = $countarr['ct'];
$pageSize  = 15;
$pagecount = ceil($count / $pageSize);
$page      = getPage($pagecount);
$rows      = Case_pricelog::getPageList($page, $pageSize, 1, $type, $objectid, $addtype, $sttime, $endtime);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="author" content="芝麻开发 http://www.zhimawork.com" />
    <title>
    <?php 
    if($type == 1){
    	echo '价格记录列表 - 产品管理 - 产品中心 ';
    }else{
    	echo '价格记录列表 - 报价方案 - 选型方案 ';
    }
    ?>
    </title>
    <link rel="stylesheet" href="css/style.css" type="text/css" />
    <link rel="stylesheet" href="css/form.css" type="text/css" />
    <link rel="stylesheet" href="css/boxy.css" type="text/css" />
    <script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="js/layer/layer.js"></script>
    <script type="text/javascript" src="js/common.js"></script>
    <script type="text/javascript" src="laydate/laydate.js"></script>
    <script type="text/javascript">
        $(function(){
        	laydate({
                elem: '#sttime', //需显示日期的元素选择器
                event: 'click', //触发事件
                format: 'YYYY/MM/DD', //日期格式
                istime: false, //是否开启时间选择
                isclear: true, //是否显示清空
                istoday: true, //是否显示今天
                issure: true, //是否显示确认
                festival: true, //是否显示节日
                choose: function(dates){ //选择好日期的回调
                }
            });
        	laydate({
                elem: '#endtime', //需显示日期的元素选择器
                event: 'click', //触发事件
                format: 'YYYY/MM/DD', //日期格式
                istime: false, //是否开启时间选择
                isclear: true, //是否显示清空
                istoday: true, //是否显示今天
                issure: true, //是否显示确认
                festival: true, //是否显示节日
                choose: function(dates){ //选择好日期的回调
                }
            });
            //查找
            $('#search').click(function(){
                var addtype = $('#addtype').val();
                var sttime = 0;
				var sttimestr = $('#sttime').val() + " 00:00:00";
				if($('#sttime').val() != ''){
					sttime  = Date.parse(new Date(sttimestr)) / 1000;
				}else{
					sttime = 0;
				}
				var endtime = 0;
				var endtimestr = $('#endtime').val() + " 23:59:59";
				if($('#endtime').val() != ''){
					endtime  = Date.parse(new Date(endtimestr)) / 1000;
				}else{
					endtime = 0;
				}

                location.href  = "pricelog_list.php?type="+'<?php echo $type;?>'+"&objectid="+'<?php echo $objectid;?>'+"&leftmenu="+'<?php echo $leftmenu;?>'+"&sttime="+sttime+"&endtime="+endtime+"&addtype="+addtype;
            });
            //添加
            $('#add').click(function(){
                layer.open({
                    type: 1,
                    title: '添加价格',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['400px', '200px'],
                    content: $('#adddiv')
                });
            });

            $('#add_btn_sumit').click(function(){
                var price = $('#price_add').val();

                if(price == ''){
                    layer.tips('价格不能为空', '#a_name');
                    return false;
                }
                $.ajax({
                    type        : 'POST',
                    data        : {
                    	price  : price,
                    	type  : '<?php echo $type;?>',
                    	addtype  : 2,
                    	objectid  : '<?php echo $objectid;?>'
                    },
                    dataType :    'json',
                    url :         'pricelog_do.php?act=add',
                    success :     function(data){
                        // alert(data);
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
            
            //删除
            $(".delete").click(function(){
                var id = $(this).parent('td').find('#aid').val();
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
                            url : 'pricelog_do.php?act=del',
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
    <?php 
    if($type == 1){
    	include('products_menu.inc.php');
    }else{
    	include('selection_menu.inc.php');
    }
    ?>
    <div id="maincontent">
        <div id="position">当前位置：
        <?php 
        if($type == 1){
        	echo '<a href="guolu_list.php">产品中心</a> &gt; 产品管理 &gt; '.$modelinfo['name'].' &gt; 价格记录';
        }else{
        	echo '<a href="company_list.php">选型方案</a> &gt; 报价方案  &gt; 价格记录';
        }
        ?>
        </div>
        <div id="handlelist">
            添加方式
            <select id="addtype" class="select-handle">
                <option value="0">全部</option>
                <?php
                foreach($ARRAY_price_addtype as $key => $val){
                     $selected = '';
                     if($key == $addtype)
                         $selected = 'selected';
                      echo '<option value="'.$key.'" '.$selected.'>'.$val.'</option>';
                }
                ?>
            </select>
            <span style="padding: 0px 10px 0px 30px;">添加时间</span><input type="text" class="text-input input-length-10 laydate-icon" id="sttime" value="<?php echo $sttime?date('Y/m/d', $sttime):'';?>">
            ~<input type="text" class="text-input input-length-10 laydate-icon" id="endtime" value="<?php echo $endtime?date('Y/m/d', $endtime):'';?>">
            <input type="button" class="btn-handle" href="javascript:" id="search" value="查询">
            <input type="button" class="btn-handle fr" href="javascript:" id="add" value="添加">
        </div>
        <div style="padding: 20px 0px;">
		<span style="font-weight: bold;">统计数据：</span>根据筛选条件，价格最高为<?php echo $countarr['maxprice']?floatval($countarr['maxprice']):0;?>万元，价格最低为<?php echo $countarr['minprice']?floatval($countarr['minprice']):0;?>万元，最新报价<?php echo $rows?floatval($rows[0]['price']):0;?>万元，平均价格<?php echo $countarr['avgprice']?floatval($countarr['avgprice']):0;?>万元。
        </div>
        <div class="tablelist">
            <table>
                <tr>
                    <th>价格id</th>
                    <th>价格（万元）</th>
                    <th>添加方式</th>
                    <th>添加时间</th>
                    <th>操作</th>
                </tr>
                <?php
                if(!empty($rows)){
                	$i = $count - $pageSize * ($page - 1);
                    foreach($rows as $row){
                        echo '<tr>
                                <td class="center">'.$i.'</td>
                                <td class="center">'.$row['price'].'</td>
                                <td class="center">'.$ARRAY_price_addtype[$row['addtype']].'</td>
                                <td class="center">'.getDateStrS($row['addtime']).'</td>
                                <td class="center">
                                    <a class="delete" href="javascript:void(0)">删除</a>
                                    <input type="hidden" id="aid" value="'.$row['id'].'"/>
                                </td>
                            </tr>';
                        $i --;
                    }
                }else{
                    echo '<tr><td class="center" colspan="5">没有数据</td></tr>';
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
    <div id="adddiv" style="display: none">
        <div id="formlist">
            <p>
                <label>价格（万元）</label>
                <input type="text" class="text-input input-length-30" name="price_add" id="price_add" />
                <span class="warn-inline" id="e_name">* </span>
            </p>
            <p>
                <label>　　</label>
                <input type="submit" id="add_btn_sumit" class="btn_submit" value="确　定" />
            </p>
        </div>
    </div>
    <div class="clear"></div>
</div>
<?php include('footer.inc.php');?>
</body>
</html>