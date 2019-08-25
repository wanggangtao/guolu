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
$FLAG_LEFTMENU  = 'weixin_repair_treating';

$params['value'] = "";
$params['starttime'] = "";
$params['server_type'] = "";
$params['child_status'] = "";
if(isset($_GET['value'])){
    $params['value'] = safeCheck($_GET['value'],0);
}
if(isset($_GET['starttime'])){
    $params['starttime'] = safeCheck($_GET['starttime'],0);
}
if(isset($_GET['child_status']) && $_GET['child_status'] != 0){

    $params['child_status'] = safeCheck($_GET['child_status'],0);
}
if(isset($_GET['server_type']) && $_GET['server_type'] != -1){
    $params['server_type'] = safeCheck($_GET['server_type'],0);
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
                var child_status = $("#child_status option:selected").val();


                location.href  = "weixin_repair_treating.php?value="+searchValue+"&starttime="+starttime+"&server_type="+server_type+"&child_status="+child_status;
            });

            $(".editinfo").mouseover(function(){
                    layer.tips('处理',$(this),{
                        tips:[4,'#04A6FE'],
                        time: 500
                    })
            });
            $(".editinfo").click(function(){
                var id = $(this).parent("td").find("#aid").val();
                var status = 2;
                location.href = "weixin_repair_do.php?id="+id+"&&act=herf1";
            });

            $(".delete").mouseover(function(){
               layer.tips('删除',$(this),{
                   tips:[4,'#04A6FE'],
                   time:500
                })
            });
            $(".delete").click(function(){
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
        <div id="position">当前位置：<a href="weixin_repair_untreated.php">预约管理</a> &gt; 处理中订单</div>
        <div id="handlelist">
            <input type="text" style="width: 150px" class="text-input input-length-10" name = "searchValue" id="searchValue"  value="<?php echo $params['value'];?>" placeholder="电话\地址\维修人员"/>

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
            <td>订单状态</td>
            <select style="width: 100px;height: 21px" id ="child_status">
            <option value="0">全部</option>
            <option value="21">待接单</option>
            <option value="22">已接单</option>
            <option value="23">待支付</option>
            </select> 


            <input type="button" class="btn-handle"  id="search" name="search" value="查询">

        </div>
        <div class="tablelist">
            <table>
                <tr>
                    <th></th>
                    <th>序号</th>
                    <th>条码</th>
                    <th>服务类型</th>
                    <th>联系人</th>
                    <th>联系电话</th>
                    <th style="width:20%">联系地址</th>
                    <th>提交时间</th>
                    <th>维修人员</th>
                    <th>订单状态</th>
                    <th>操作</th>
                </tr>
                <?php
                $params['status'] = 2;
                $totalcount= repair_order::getCount($params);

                $shownum   = 15;
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
                        if ($row['child_status']==21) {
                            $status="待接单";
                        }elseif ($row['child_status']==22) {
                            $status="已接单";
                        }else{
                            $status="待支付";
                        }

                        echo '<tr>
                                <td class="center"><input name="check" type="checkbox" value='.$row['id'].' /></td>
                                <td class="center">'.$index.'</td>
                                <td class="center">'.$row['bar_code'].'</td>
                                <td class="center">'.$service_type.'</td>
                                  <td class="center">'.$row['register_person'].'</td>
                                <td class="center">'.$row['phone'].'</td>
                                <td class="center">'.$row['address_all'].'</td>
                                <td class="center">'.date("Y-m-d H:i:s", $row['addtime']).'</td>
                                <td class="center">'.$name."<br>".$phone.'</td>
                               <td class="center">'.$status.'</td>                        
                                <td class="center">
                                    <a class="editinfo" href="javascript:void(0)">处理</a>
                                    <a class="delete" href="javascript:void(0)">删除</a>
                                    <input type="hidden" id="aid" value="'.$row['id'].'"/>
                                </td>
                            </tr>';
                        $index++ ;
                    }
                }else{
                    echo '<tr><td class="center" colspan="9">没有数据</td></tr>';
                }
                ?>

            </table>
        </div>
        <br>
        <input type="button" class="btn-handle" href="javascript:" id="import" value="批量导出">
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
<script>
    $(function () {
        $("#import").click(function () {
            layer.confirm('确认导出该订单吗？', {
                    btn: ['确认','取消']
                },function(){
            var ids= "";
            $("input:checkbox[name='check']:checked").each(function () {
                ids+= $(this).val() + " ";//点击导出按钮后遍历所有单选按钮，将被选中的按钮的值（这里存的是对应订单id）存到一个字符串中并以空格区分。
            });
                var data = <?php echo json_encode($params)?>;
                var index = layer.msg("正在导出请稍后.....", {time: 10});
                $.ajax({
                    type: 'post',
                    url: 'repair_treating_excel.php',
                    data: {
                        ids: ids,
                        data: data,
                    },
                    dataType: 'json',
                    success: function (data) {
                        layer.close(index);
                        var code = data.code;
                        var msg = data.msg;
                        switch (code) {
                            case 1:
                                var myurl = window.location.href;
                                var suburl = myurl.substr(0, myurl.indexOf('admin'));
                                window.location.href = suburl + data.msg;
                                break;
                            default:
                                layer.alert(msg, {icon: 5}, function (index) {
                                    location.reload();
                                });
                        }

                    },
                    // error: function () {
                    //     layer.alert("导出失败", {icon: 5}, function (index) {
                    //         location.reload();
                    //     });
                    // }
                });
            })
        });
    })

</script>
</body>
</html>



