<?php
/**
 * Created by PhpStorm.
 * User: sxx
 * Date: 2019/8/23
 * Time: 15:12
 */
require_once('admin_init.php');
require_once('admincheck.php');

$FLAG_TOPNAV	= "weixincontent";
$FLAG_LEFTMENU  = 'weixin_repair_treated';

$params['value'] = "";
$params['starttime'] = "";
$params['server_type'] = "";
$params['child_status'] = "";
$params['PJ_status'] = "";

if(isset($_GET['value'])){
    $params['value'] = safeCheck($_GET['value'],0);
}
if(isset($_GET['starttime'])){
    $params['starttime'] = safeCheck($_GET['starttime'],0);
}
if(isset($_GET['server_type']) && $_GET['server_type'] != -1){
    $params['server_type'] = safeCheck($_GET['server_type'],0);
}
if(isset($_GET['child_status']) && $_GET['child_status'] != 0){

    $params['child_status'] = safeCheck($_GET['child_status'],0);
}
if(isset($_GET['PJ_status']) && $_GET['PJ_status'] != 0){

    $params['PJ_status'] = safeCheck($_GET['PJ_status'],0);
}

if(isset($_GET['solve_type']) && $_GET['solve_type'] != -1){
    $params['solve_type'] = safeCheck($_GET['solve_type'],0);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="author" content="芝麻开发 http://www.zhimawork.com" />
    <title>公众号管理 - 预约管理</title>
    <link rel="stylesheet" href="css/style.css" type="text/css" />
    <link rel="stylesheet" href="css/form.css" type="text/css" />
    <link rel="stylesheet" href="css/boxy.css" type="text/css" />
    <link rel="stylesheet" href="css/newlayui.css">
    <link rel="stylesheet" href="layui/css/layui.css">
    <link rel="stylesheet" href="layui/css/layui.css">
    <script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="js/layer/layer.js"></script>
    <script type="text/javascript" src="js/common.js"></script>
    <script type="text/javascript">
        $(function(){
            $('#search').click(function(){
                var searchValue = $('#searchValue').val();
                var starttime = $('#starttime').val();
                var server_type = $('#server_type').val();
                var solve_type = $("#solve_type").val();
                var child_status = $("#child_status option:selected").val();
                var PJ_status = $("#PJ_status option:selected").val();


                location.href  = "weixin_repair_treated.php?value="+searchValue+"&starttime="+starttime+"&server_type="+server_type+"&solve_type="+solve_type+"&child_status="+child_status+"&PJ_status="+PJ_status;
            });

            $(".delete").mouseover(function(){
                layer.tips('删除', $(this), {
                    tips: [4, '#04A6FE'],
                    time: 500
                })
            });

            $('#import').click(function(){
                layer.open({
                    type: 2,
                    title: '保修单批量导入',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['600px', '500px'],
                    content: 'weixin_import_repair.php'
                });
            });

            $(".editinfo").mouseover(function(){
                layer.tips('处理', $(this), {
                    tips: [4, '#04A6FE'],
                    time: 500
                })
            });

            $('.editinfo').click(function(){
                var id = $(this).parent('td').find('#aid').val();

                 var status = 3;
                // <?php
                //  $row = repair_order::getInfoById('id');
                //  $child_status = $row['child_status'];
                
                //  if ($child_status=='31') {
                //  location.href  = "weixin_repair_cancel.php?id="+id;
                
                //  }
                //  ?>
                location.href = "weixin_repair_do.php?id="+id+"&&act=herf";

                //location.href  = "weixin_repair_notchange.php?id="+id;
              location.href = "weixin_repair_do.php?id="+id+"&&act=herf";
            });

            $('.delete').click(function(){
                var id = $(this).parent('td').find('#aid').val();

                layer.confirm('确认删除该订单吗？', {
                    btn: ['确认','取消']
                }, function(){
                    $.ajax({
                        type        : 'POST',
                        data        : {
                            id: id
                        },
                        dataType : 'json',
                        url : 'weixin_repair_do.php?act=del',
                        success : function(data){

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

                })

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
        <div id="position">当前位置：<a href="weixin_repair_untreated.php">预约管理</a> &gt; 已处理订单</div>
        <div id="handlelist">
            <input type="text"  style="width: 200px" class="text-input input-length-10" name = "searchValue" id="searchValue"  value="<?php echo $params['value'];?>" placeholder="电话\地址\维修人员\联系人"/>

            <td>提交时间</td>
            <input type="text" style="width: 100px" class="form-control width120" name="starttime" id="starttime"
                   value="<?php
                   $startTime = "";
                   if (isset($params['starttime'])) {
                       $startTime = $params['starttime'];
                   }
                   echo $startTime;

                   ?>"/>
            <script type="text/javascript" src="laydate/laydate.js"></script>
            <script type="text/javascript">
                laydate.skin("molv");//设置皮肤
                var start = {
                    elem: '#starttime',
                    format: 'YYYY-MM-DD ',
                    min: '2000-06-16 ', //设定最小日期为当前日期
                    max: '2099-06-16 ', //最大日期
                    istime: true,
                    istoday: false,
                };
                laydate(start);

            </script>
            <td>服务类型</td>
            <select style="width: 100px;height: 21px" id ="server_type">
                <option value="-1">--</option>
                <?php
                $server_type = Service_type::getList(array());
                foreach ($server_type as $item){
                    $select = "";
                    if($params['server_type'] ==$item['id'] ){
                        $select = "selected";

                    }
                    echo '<option value="'.$item['id'].'" '. $select.'>'.$item['name'].'</option>';
                }


                ?>
            </select>
            <td>解决途径</td>
            <select style="width: 100px;height: 21px" id ="solve_type">
                <option value="-1">--</option>
                <?php

                foreach ($ARRAY_weixin_solve_type as $key => $item){
                    $select = "";
                    if($params['solve_type'] == $key ){
                        $select = "selected";

                    }
                    echo '<option value="'.$key.'" '. $select.'>'.$item.'</option>';
                }


                ?>
            </select>
            <td>订单状态</td>
            <select style="width: 100px;height: 21px" id ="child_status">
            <option value="0">全部</option>
            <option value="31">已取消</option>
            <option value="32">待审核</option>
            <option value="33">已审核</option>
            </select> 

            <td>评价</td>
            <select style="width: 100px;height: 21px" id ="PJ_status">
            <option value="0">全部</option>
            <option value="1">未评价</option>
            <option value="2">已评价</option>
            
            </select> 

            <input type="button" class="btn-handle"  id="search" name="search" value="查询">

        </div>
        <div class="tablelist">
            <table>
                <tr>
                    <th>序号</th>
                    <th>条码</th>
                    <th style="width:5%">服务类型</th>
                    <th>联系人</th>
                    <th>联系电话</th>
                    <th style="width:20%">联系地址</th>
                    <th style="width:10%">提交时间</th>

                    <th>维修人员</th>
                    <th>解决途径</th>
                    <th>评价</th>
                    <th>订单状态</th>
                    <th style="width:8%">操作</th>
                </tr>
                <?php
                $params['status'] = 3;
                $totalcount= repair_order::getCount($params);

                $shownum   =15;
                $pagecount = ceil($totalcount / $shownum);
                $page      = getPage($pagecount);


                $params["page"] = $page;
                $params["pageSize"] = $shownum;

                $rows = repair_order::getList($params);

                if(!empty($rows)){
                    $index = ($page-1)*$shownum+1;
                    foreach($rows as $row){

                        $rpname = "";
                        $repair_info =   repair_person::getInfoById($row['person']);

                        $name = "";
                        $phone = "";

                        if(!empty($repair_info)){
                            $name=$repair_info['name'];
                            $phone=$repair_info['phone'];
                        }

                        $service_type = "";
                        if(isset($row['service_type'])){
                            $service_info = Service_type::getInfoById($row['service_type']);
                            if(isset($service_info['name'])){
                                $service_type = $service_info['name'];
                            }
                        }
                        $solve_type = "";
                        if(isset($row['solutions'])){
                            $solve_type = $ARRAY_weixin_solve_type[$row['solutions']];
                        }
                        if ($row['child_status']==31) {
                            $status="已取消";
                        }elseif ($row['child_status']==32) {
                            $status="待审核";
                        }else{
                            $status="已审核";
                        }
                        if ($row['user_evaluation']==-1) {
                            $user_evaluation="未评价";
                        }else{
                             $user_evaluation="已评价";
                        }


                        echo '<tr>
                                <td class="center">'.$index.'</td>
                                <td class="center">'.$row['bar_code'].'</td>
                                <td class="center">'.$service_type.'</td>
                                <td class="center">'.$row['register_person'].'</td>
                                <td class="center">'.$row['phone'].'</td>
                                <td class="center">'.$row['address_all'].'</td>
                                <td class="center">'.date("Y-m-d H:i:s", $row['addtime']).'</td>
                                <td class="center">'.$name."<br>".$phone.'</td> 
                                 <td class="center">'.$solve_type.'</td>
                                  <td class="center">'.$user_evaluation.'</td> 
                                 <td class="center">'.$status.'</td>


                                <td class="center">
                                    <a class="editinfo" href="javascript:void(0)">详情</a>
                                    <a class="delete" href="javascript:void(0)">删除</a>
                                    <input type="hidden" id="aid" value="'.$row['id'].'"/>
                                </td>
                            </tr>';
                        $index++ ;
                    }
                }else{
                    echo '<tr><td class="center" colspan="10">没有数据</td></tr>';
                }
                ?>

            </table>
        </div>
        <br>
        <input type="button" class="btn-handle" href="javascript:" id="import" value="批量导入">

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
