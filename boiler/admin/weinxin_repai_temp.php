<?php
/**
 * Created by PhpStorm.
 * User: ww
 * Date: 2019/3/15
 * Time: 15:12
 */
require_once('admin_init.php');
require_once('admincheck.php');

$status = 1;
$disable = "";
$is_tel = "disabled";
$FLAG_TOPNAV	= "weixincontent";
$FLAG_LEFTMENU  = 'weixin_repair_untreated';
if(isset($_GET['status'])){
    $status = $_GET['status'];
}
if($status == 2){
    $FLAG_LEFTMENU  = 'weixin_repair_treating';
    $disable = "disabled";
    $is_tel = "";


}
/*else if($status == 3){
    $FLAG_LEFTMENU = "weixin_repair_treated";
}*/

if(isset($_GET['id'])){
    $id = safeCheck($_GET['id'],1);
}else{
    exit();
}

$info = repair_order::getInfoById($id);


$all_usr = repair_person::getList();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="author" content="芝麻开发 http://www.zhimawork.com" />
    <title>公众号管理 - 报修管理</title>
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
        window.onload = function () {
            var status = <?php echo $status?>;
            if(status == 2){
//            $("#status1").attr("checked"," ");
                $("#status2").attr("checked","checked");
                $("#status2").change();
                $("#repair_check").change();
            }
        }
    </script>
</head>
<style>
    .table_all_list {
        table-layout: fixed;
    }

    .table_all_list tbody td {
        text-align: left !important;
        border: 0px !important;
    }
    .table_all_list tbody th {
        text-align: left !important;
        border: 0px !important;
    }

    .table_all_list tbody td input {
        margin: 0px !important;
    }
</style>
<body>
<div id="header">
    <?php include('top.inc.php');?>
    <?php include('nav.inc.php');?>
</div>
<div id="container">
    <?php include('weixin_menu_inc.php');?>
    <div id="maincontent">
        <div id="position">当前位置：<a href="weixin_employees_info.php">公众号管理</a> &gt; 信息管理</div>

        <div class="tablelist">
            <table class="table_all_list">
                <tbody>
                <tr>
                    <th width= "8% " >报修详情：</th>
                    <td width= "20% " align="right"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <th ></th>
                    <td>联系人：<?php if(isset($info['register_person'])){
                            echo $info['register_person'];
                        } ?></td>
                    <td>维修联系电话：<?php if(isset($info['phone'])){
                            echo $info['phone'];
                        } ?></td>
                    <td>注册电话：<?php if(isset($info['register_phone'])){
                            echo $info['register_phone'];
                        } ?></td>
                    <td></td>
                </tr>

                <tr>
                    <th ></th>
                    <td>优惠券：<?php if(isset($info['coupon_id'])){
                            echo $info['coupon_id'];
                        } ?></td>
                    <td>提交时间：<?php if(isset($info['addtime'])){
                            echo date("Y-m-d H:i:s", $info['addtime']);
                        } ?></td>
                    <td>品牌型号：<?php if(isset($info['brand'])){
                            echo $info['brand'];
                        }
                        if(isset($info['model']) && !empty($info['model'])){
                              echo "——".$info['model'];
                        }
                        ?></td>
                    <td></td>
                </tr>
                <tr>
                    <th ></th>
                    <td   colspan="4" >联系地址：<?php if(isset($info['address_all'])) echo $info['address_all'];?></td>
                </tr>
                <tr>
                    <th ></th>
                    <td   colspan="4" >服务类型：<?php if(isset($info['service_type'])) echo $info['service_type'];?></td>
                </tr>
                <?php
                if($info['service_type'] == 1){
                ?>
                    <tr>
                        <th ></th>
                        <td  colspan="4" >故障描述：<?php if(isset($info['failure_cause'])) echo $info['failure_cause'];?></td>
                    </tr>
                    <tr>
                        <th ></th>
                        <td  >图片详情：</td>
                        <td colspan="3">
                            <?php
                            $has_img = 0;
                            if(!empty($info['picture_url'])){

                                $has_img = 1;
                                $url_array = explode(",",$info['picture_url']);
                                $url_array = array_filter($url_array);
//                            print_r($url_array);
                            }
                            if($has_img) {
                            $pos = strripos($url_array[0],"/");
                            $now_path =  $HTTP_PATH .substr($url_array[0],0,$pos + 1)."s_".substr($url_array[0],$pos + 1);
                            ?>
                            <table border="0">

                                <tr>
                                    <td rowspan="3" align="center">
                                        <img src="<?php echo $now_path; ?>" id="big_img" alt="">
                                    </td>

                                    <td>
                                    </td>
                                </tr>
                                <?php
                                foreach ($url_array as $key => $item) {

                                    if($key > 2) break;

                                    $pos_item = strripos($item,"/");
                                    $now_item_path =  substr($item,0,$pos_item + 1)."s_".substr($item,$pos_item + 1);
                                    ?>

                                    <tr>
                                        <td>
                                            <a onclick="setImg('<?php echo $now_item_path; ?>')"> <img
                                                    src="<?php echo $HTTP_PATH . $item ?>" width="100px"
                                                    height="100px" alt=""></a>
                                        </td>
                                    </tr>

                                    <?php
                                }?>

                                <?php
                                echo "</table>";
                                }
                                else{
                                    echo "暂无照片";
                                }
                                ?>
                                </td>
                    </tr>

                <?php
                }
                ?>
                                <tr>
                                    <th ></th>
                                    <td  >服务记录：</td>
                                    <td   colspan="3" >
                                        <table>
                                            <?php
                                            $params['code'] = $info['bar_code'];
                                            $params['status'] = 3;
                                            $rows = repair_order::getListByCode($params);

                                            if(!empty($rows)){
                                                echo'<tr>
                                        <td>服务次数</td>
                                        <td>客户联系电话</td>
                                        <td>服务时间</td>
                                        <td>服务类型</td>
                                        <td>服务结果</td>
                                        <td>服务人员</td>
                                        <td>备注</td>

                                            </tr>';
                                                $i = 1;
                                                foreach($rows as $row){

                                                    $name="";
                                                    $phone="";
                                                    $person=Repair_person::getInfoById($row['person']);
                                                    if($person){
                                                        $name=$person['name'];
                                                        $phone=$person['phone'];
                                                    }
                                                    echo '<tr>
                                <td >'.$i.'</td>
                                <td >'.$row['phone'].'</td>
                                <td >'.date("Y-m-d",$row['finish_time']).'</td>
                                <td >'.$row['service_type'].'</td>
                                <td >'.$row['result'].'</td>
                                <td >'.$name."<br>".$phone.'</td>
                               <td >'.$row['remarks'].'</td>
                            </tr>';
                                                    $i++;
                                                }
                                            }else{
                                                echo "暂无";
                                            }


                                            ?>


                                        </table>
                                            </td>

                                </tr>

                                <tr>
                                    <td></td>
                                    <td>备注信息：</td>
                                    <td  colspan="3"><textarea id = "remarks" rows="5" cols="100"><?php echo $info['remarks'];?></textarea>
                                        <br>(注：最多输入200个字。)
                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>处理状态：</td>
                                    <td>


                                        <input name="status" id ="status1" type="radio" checked='checked' value="1" <?php echo $disable?>/> 未处理
                                        <input name="status" id ="status2" type="radio"  value="2"/>处理中
                                        <input name="status" id ="status3" type="radio" value="3"/>已处理

                                    </td>
                                </tr>
                                <tbody class="is_show"></tbody>
                </tbody>
            </table>
                            <table>
                                <tr>
                                    <td colspan="3" align="center">
                                        <?php
                                        /*                                    if ($status == 1 || $status == 2){
                                                                            */?>
                                        <input type="button" class="btn-handle"  id="btn_sumit" name="btn_sumit" value="确认">
                                        &emsp;&emsp;&emsp;&emsp;
                                        <input type="button" class="btn-handle"  id="btn_back" name="btn_back" value="取消">
                                        <?php
                                        /*}else if($status == 3){
                                         */?><!--
                                     <input type="button" class="btn-handle"  id="btn_back" name="btn_back" value="返回">
                                     --><?php
                                        /*                                    }*/
                                        ?>


                                    </td>
                                </tr>

                            </table>


                        <script type="text/javascript">

                            $(function(){

                                $('#btn_sumit').click(function() {

                                    var status =  $("input[name='status']:checked").val();
                                    var remarks =   $.trim($("#remarks").val());
                                    var repair_check = -1 ;
                                    var linkPone ="";
                                    var content = "";
                                    var result = "";
                                    var finish_time = 0;
                                    var phone = $("#phone").val();

                                    var id = <?php echo $id?>;
                                    if(status == 2){
                                        repair_check =   $("#repair_check option:selected").val() ;
                                        linkPone =   $("#linkPone").val();
                                    }
                                    if(status == 3){
                                        repair_check =   $("#repair_check option:selected").val() ;
                                        linkPone =   $("#linkPone").val();
                                        content = $.trim($("#content").val());
                                        result = $.trim($("#result").val());
                                        finish_time = $("#finish_time").val();
                                    }
                                    var type ="<?php echo $status;?>";
                                    $.ajax({
                                        type: 'post',
                                        url: 'weixin_repair_do.php?act=edit',
                                        data: {
                                            id:id,
                                            linkPone: linkPone,
                                            repair_check:repair_check,
                                            remarks:remarks,
                                            status:status,
                                            content:content,
                                            result:result,
                                            finish_time :finish_time,
                                            phone:phone
                                        },
                                        dataType: 'json',
                                        success: function (data) {
                                            var code = data.code;
                                            var msg = data.msg;
                                            switch (code){
                                                case 1:
                                                    layer.alert(msg, {icon: 6}, function (index) {

                                                        if(type == 1){
                                                            parent.location.href = "weixin_repair_untreated.php";

                                                        }else if(type == 2){
                                                            parent.location.href = "weixin_repair_treating.php";

                                                        }else{
                                                            parent.location.href="javascript:history.go(-1)";
                                                        }

                                                    });

                                                    break;
                                                default:
                                                    layer.alert("请求失败");
                                            }
                                        },
                                        error: function () {
                                            layer.alert("请求失败");
                                        }
                                    });


                                });

                                $('#btn_back').click(function(){

                                    parent.location.href="javascript:history.go(-1)";


                                });
                                $('#status1').change(function () {
                                    var content ="";
                                    $(".is_show").html(content);

                                });
                                $('#status2').change(function () {
                                    var all_usr = <?php echo json_encode($all_usr)?>;

                                    var name_id = "<?php if(isset($info['person'])) echo $info['person'];?>";
                                    var linkPone = "<?php if(isset($info['linkphone'])) echo $info['linkphone'];?>";
                                    var option = "<option value='-1'>---</option>";

                                    var checked = "";
                                    for(var index_usr = 0 ;index_usr < all_usr.length ; index_usr ++){
                                        if(name_id == all_usr[index_usr].id) checked = "selected";
                                        option += "<option value='"+all_usr[index_usr].id+"' " +
                                            checked +
                                            ">"+all_usr[index_usr].name+"</option>";
                                    }
                                    var content = "<tr>" +
                                        "<td></td><td>维修人员：</td>" +
                                        "<td><select name= 'repair_check' id = 'repair_check'>" +
                                        option +
                                        "</select></td>" +
                                        "</tr><tr>" +
                                        "<td></td><td>联系电话：</td>" +
                                        "<td><input id = 'linkPone' name = 'linkPone' value='" +
                                        linkPone +
                                        "'/></td>" +
                                        "</tr>";

                                    $(".is_show").html(content);

                                });
                                $('#status3').change(function () {
                                    var is_tel = "<?php echo $is_tel?>";

                                    var nowTime = "<?php echo date("Y-m-d H:i:s") ?>";
                                    var all_usr = <?php echo json_encode($all_usr)?>;
                                    var name_id = "<?php if(isset($info['person'])) echo $info['person'];?>";
                                    var linkPone = "<?php if(isset($info['linkphone'])) echo $info['linkphone'];?>";
                                    var option = "<option value='-1'>---</option>";

                                    var checked = "";
                                    for(var index_usr = 0 ;index_usr < all_usr.length ; index_usr ++){
                                        if(name_id == all_usr[index_usr].id) checked = "selected";
                                        option += "<option value='"+all_usr[index_usr].id+"' " +
                                            checked +
                                            ">"+all_usr[index_usr].name+"</option>";
                                    }
                                    var content = "<tr>" +
                                        "<td></td><td>维修人员：</td>" +
                                        "<td><select name= 'repair_check' id = 'repair_check'>" +
                                        option +
                                        "</select></td>" +
                                        "</tr><tr>" +
                                        "<td></td><td>联系电话：</td>" +
                                        "<td><input id = 'linkPone' name = 'linkPone' value='" +
                                        linkPone +
                                        "'/></td>" +
                                        "</tr>" +
                                        "<tr><th>维修完成情况：</th></tr>" +
                                        "" +
                                        "<tr> <td></td><td>解决途径：</td>" +
                                        "<td><input name='solutions' id ='solutions1' type='radio' value='1'" +
                                        is_tel +
                                        "/> 上门服务" +
                                        " <input name='solutions' id ='solutions2' type='radio' checked='checked' value='2'/>电话服务" +
                                        "</td></tr>" +
                                        "<tr> <td></td><td>服务结果：</td>" +
                                        "<td><textarea rows='5' cols='100' id = 'result' name = 'result'></textarea><br>" +
                                        "（注：最多输入200个字）" +
                                        "</td></tr>"+
                                        "<tr> <td></td><td>维修时间：</td>" +
                                        "<td><input id = 'finish_time' name = 'finish_time'" +
                                        "value='"+nowTime+"'/>" +
                                        "</td></tr>";

                                    $(".is_show").html(content);

                                })
                            });


                            $(document).on("change","#repair_check",function(){
                                var repair_id =   $("#repair_check option:selected").val() ;

                                $.ajax({
                                    type: 'post',
                                    url: 'weixin_repair_do.php?act=select_repair',
                                    data: {
                                        repair_id: repair_id,
                                    },
                                    dataType: 'json',
                                    success: function (data) {
                                        var code = data.code;
                                        var msg = data.msg;
                                        switch (code){
                                            case 1:
                                                $('#linkPone').val(msg);
                                                break;
                                            default:
                                                layer.alert("请求失败");
                                        }
                                    },
                                    error: function () {
                                        layer.alert("请求失败");
                                    }
                                });
                            });
                            function setImg(path){
//                    alert(path);
                                var web_path = "<?php echo $HTTP_PATH?>";
                                var all_path = web_path + path;
                                $('#big_img').attr('src',all_path);
                            }
                        </script>
                        <script type="text/javascript" src="laydate/laydate.js"></script>
                        <script type="text/javascript">
                            $(document).on("click","#finish_time",function(){
                                laydate.skin("molv");//设置皮肤
                                var start = {
                                    elem: '#finish_time',
                                    format: 'YYYY-MM-DD hh:mm:ss',
                                    min: '2000-06-16 23:59:59', //设定最小日期为当前日期
                                    max: '2099-06-16 23:59:59', //最大日期
                                    istime: true,
                                    istoday: false,
                                };
                                laydate(start);
                            })
                        </script>
        </div>
    </div>
    <div class="clear"></div>
</div>
<?php include('footer.inc.php');?>
</body>
</html>