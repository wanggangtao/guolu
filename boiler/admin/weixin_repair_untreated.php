<?php
/**
 * Created by PhpStorm.
 * User: sxx
 * Date: 2019/8/23
 
 */
require_once('admin_init.php');
require_once('admincheck.php');

$FLAG_TOPNAV	= "weixincontent";
$FLAG_LEFTMENU  = 'weixin_repair_untreated';

$params = array();

$params['value'] = "";
$params['starttime'] = "";
$params['server_type'] ="";

if(isset($_GET['value'])){
    $params['value'] = safeCheck($_GET['value'],0);
}
if(isset($_GET['starttime'])){
    $params['starttime'] = safeCheck($_GET['starttime'],0);
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
          $('#add').click(function(){
                layer.open({
                    type: 2,
                    title: '添加新订单',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['700px', '600px'],
                    content: 'weixin_repair_add.php'
                });
            });
        $(".delete").mouseover(function(){
            layer.tips('删除', $(this), {
                tips: [4, '#04A6FE'],
                time: 500
            })
        });
        $(".editinfo").mouseover(function(){
            layer.tips('处理', $(this), {
                tips: [4, '#04A6FE'],
                time: 500
            })
        });

        $('.editinfo').click(function(){
            var id = $(this).parent('td').find('#aid').val();
            //alert(id);
            var status = 1;
            location.href  = "weixin_repair_untreated_detail.php?id="+id+"&&status="+status;

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

            });

            });
        $('#search').click(function(){

            var searchValue = $('#searchValue').val();
            var starttime = $('#starttime').val();
            var server_type = $('#server_type').val();
//            alert(server_type);


            location.href  = "weixin_repair_untreated.php?value="+searchValue+"&starttime="+starttime+"&server_type="+server_type;
        });
    })

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
        <div id="position">当前位置：<a href="weixin_repair_untreated.php">预约管理</a> &gt; 未处理订单</div>
        <div id="handlelist">
                <input type="text" style="width: 100px" class="text-input input-length-10" name = "searchValue" id="searchValue"  value="<?php echo $params['value'];?>" placeholder="电话\地址"/>

            <td>提交时间</td>
                <input style="width: 100px" type="text" class="form-control width120" name="starttime" id="starttime"
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
        <input type="button" class="btn-handle"  id="search" name="search" value="查询">
        <input type="button" class="btn-handle"  id="add" name="add" value="添加">
        </div>
        <div class="tablelist">
            <table>
                <tr>
                    <th>序号</th>
                    <th>条码</th>
                    <th>服务类型</th>
                    <th>联系人</th>
                    <th>联系电话</th>
                    <th>联系地址</th>
                    <th>提交时间</th>
                    <th>操作</th>
                </tr>
                <?php
                $params['status'] = 1;
                $totalcount= repair_order::getcount ($params);

                $shownum   = 15;
                $pagecount = ceil($totalcount / $shownum);
                $page      = getPage($pagecount);


                $params["page"] = $page;
                $params["pageSize"] = $shownum;

                $rows = repair_order::getList($params);
                

                if(!empty($rows)){
                    $index = ($page-1)*$shownum+1;
                    foreach($rows as $row){

                        $info_usr = user_account::getInfoByBarCode($row['bar_code']);

                        $address = "";

                        if(isset($info_usr['contact_address'])){
                            $address = $info_usr['contact_address'];
                        }
                        $service_type = "";
                        if(isset($row['service_type'])){
                            $service_info = Service_type::getInfoById($row['service_type']);
                            if(isset($service_info['name'])){
                                $service_type = $service_info['name'];
                            }
                        }


                        echo '<tr>
                                <td class="center">'.$index.'</td>
                                <td class="center">'.$row['bar_code'].'</td>
                                <td class="center">'.$service_type.'</td>
                                  <td class="center">'.$row['register_person'].'</td>
                                <td class="center">'.$row['phone'].'</td>
                                <td class="center">'.$row['address_all'].'</td>
                                <td class="center">'.date("Y-m-d H:i:s", $row['addtime']).'</td>
                                                         
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