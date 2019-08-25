<?php
/**
 * 优惠券管理  weixin_user_coupon_list.php
 *
 * @version       v0.01
 * @create time   2018/6/27
 * @update time
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */
require_once('admin_init.php');
require_once('admincheck.php');

$FLAG_TOPNAV    = "weixincontent";
$FLAG_LEFTMENU  = 'weixin_user_coupon_list';
$params=array();

$params['value'] = "";
$params['addtime'] = "";
$params['coupon_name'] = "";
if(isset($_GET['value'])){
    $params['value'] = safeCheck($_GET['value'],0);
}
if(isset($_GET['addtime'])){
    $params['addtime'] = safeCheck($_GET['addtime'],0);
}
if(isset($_GET['coupon_name']) && $_GET['coupon_name'] != -1){
    $params['coupon_name'] = safeCheck($_GET['coupon_name'],0);
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="author" content="芝麻开发 http://www.zhimawork.com" />
    <title>优惠券发放列表</title>
    <link rel="stylesheet" href="css/style.css" type="text/css" />
    <link rel="stylesheet" href="css/form.css" type="text/css" />
    <link rel="stylesheet" href="css/boxy.css" type="text/css" />
    <script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="js/layer/layer.js"></script>
    <script type="text/javascript" src="js/common.js"></script>
    <script type="text/javascript" src="laydate/laydate.js"></script>
    <script type="text/javascript">
        $(function(){
            // layer.config({
            //     extend: 'extend/layer.ext.js'
            // });
            //查询
            $('#search').click(function(){

                var searchValue = $('#searchValue').val();
                var addtime = $('#addtime').val();
                var coupon_name = $('#coupon_name').val();


                location.href  = "weixin_user_coupon_list.php?value="+searchValue+"&addtime="+addtime+"&coupon_name="+coupon_name;
            });

            //禁用
            $(".unallow").click(function(){
                var id = $(this).parent('td').find('#cid').val();
                layer.confirm('确认禁用该优惠券吗？', {
                        btn: ['确认','取消']
                    }, function(){
                        var index = layer.load(0, {shade: false});
                        $.ajax({
                            type        : 'POST',
                            data        : {
                                id:id
                            },
                            dataType : 'json',
                            url : 'weixin_user_coupon_do.php?act=unallow',
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
                layer.confirm('确认删除该优惠券吗？', {
                        btn: ['确认','取消']
                    }, function(){
                        var index = layer.load(0, {shade: false});
                        $.ajax({
                            type        : 'POST',
                            data        : {
                                id:id
                            },
                            dataType : 'json',
                            url : 'weixin_user_coupon_do.php?act=del',
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
        <div id="position">当前位置：<a href="weixin_user_coupon_list.php">优惠券管理</a>&gt; 优惠券发放列表 </div>
        <div id="handlelist">
            <td>用户名称</td>
            <input type="text"  style="width: 150px" class="text-input input-length-10" name = "searchValue" id="searchValue"  value="<?php echo $params['value'];?>" placeholder="请输入用户名称"/>

            <td>领券时间</td>
            <input type="text" autocomplete="off" style="width: 150px" class="form-control width120" name="addtime" id="addtime"
                   value="<?php
                   $addtime = "";
                   if (isset($params['addtime'])) {
                       $addtime = $params['addtime'];
                   }
                   echo $addtime;

                   ?>"placeholder="请输入领券时间"/>

            <script type="text/javascript">
                laydate.skin("molv");//设置皮肤
                var start = {
                    elem: '#addtime',
                    format: 'YYYY-MM-DD',
                    min: '2000-06-16 ', //设定最小日期为当前日期
                    max: '2099-06-16 ', //最大日期
                    istime: true,
                    istoday: false,
                };
                laydate(start);

            </script>
            <td>优惠券名称</td>
            <select style="width: 160px;height: 21px" id ="coupon_name">
                <option value="-1">请选择</option>
                <?php
                $coupon_name = Weixin_coupon::getList(array());
                foreach ($coupon_name as $item){
                    $select = "";
                    if($params['coupon_name'] ==$item['id'] ){
                        $select = "selected";

                    }
                    echo '<option value="'.$item['id'].'" '. $select.'>'.$item['name'].'</option>';
                }


                ?>
            </select>
            <input type="button" class="btn-handle"  id="search" name="search" value="查询">
        </div>
        <div class="tablelist">
            <table>
                <tr>
                    <th>用户名称</th>
                    <th>优惠券名称</th>
                    <th>领券时间</th>
                    <th>操作</th>
                </tr>
                <?php

                $totalcount= Weixin_user_coupon::getCount($params);

                $shownum   =10;
                $pagecount = ceil($totalcount / $shownum);
                $page      = getPage($pagecount);


                $params["page"] = $page;
                $params["pageSize"] = $shownum;

                $rows      = Weixin_user_coupon::getList($params);

                if(!empty($rows)){
                    $i = ($page-1)*$pageSize+1;
                    foreach($rows as $row){

                        $cname = Weixin_coupon::getNameById($row['cid']);
                        $uname = User_account::getNameById($row['uid']);
                        $addtime =date("Y-m-d H:i:s",$row['addtime']);
                        echo '<tr>
                                <td class="center">'.$uname.'</td>
                                <td class="center">'.$cname.'</td>
                                <td class="center">'.$addtime.'</td>
                                <td class="center">';
                                if($row['status']==1) {
                                    echo ' <a class="unallow" href="javascript:void(0)">禁用</a>';
                                }else{
                                    echo '禁用';
                                }
                                echo '
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
                <span class="table_info">共<?php echo $totalcount;?>条数据，共<?php echo $pagecount;?>页</span>
            </div>
            <?php
            if($pagecount>1){
                echo dspPages(getPageUrl(), $page, $shownum, $totalcount, $pagecount);
            }
            ?>
        </div>
    </div>
    <div class="clear"></div>
</div>
<?php include('footer.inc.php');?>
</body>
</html>