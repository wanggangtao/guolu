<?php
/**
 *  手动选型 selection_manual_new.php
 *
 * @version       v0.01
 * @create time   2018/12/05
 * @update time   2018/12/05
 * @author        ozqowen
 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
 */
require_once('web_init.php');
require_once('usercheck.php');

$TOP_FLAG = "selection";

// 获取history_id，如果有history_id表示从 第二步报价页面 点击上一步返回，需要数据回显，否则即为新建选型
$id = isset($_GET['id'])?safeCheck($_GET['id']):0;

$history_id = $id;

$guolu_vender_list = Dict::getListByParentid(1);
$project_id=0;
$pro_name = "";

if ($history_id != 0) {

    // 获取选项历史记录
    $history_info = Selection_history::getInfoById($history_id);

    // 获取锅炉列表参数
    $guolu_id_list = explode(",", $history_info['guolu_id']);

    // 获取锅炉数量
    $guolu_num_list = explode(",", $history_info['guolu_num']);

    // 获取锅炉备注
    $guolu_context_list = explode("||", $history_info['guolu_context']);


    // 获取辅机列表参数
    $fujilist = Selection_fuji::getListByHistoryId($id,1); // addtype = 1 辅机添加到方案中

    // 获取客户名称
    $customer = $history_info['customer'];

    // 如果是从方案池进来，需要向方案2页面传selection_plan_front_id
    $plan_front_info = Selection_plan_front::getInfoByHistoryId($history_id);
    $plan_front_id = null;
    if (!empty($plan_front_info)) {
        $plan_front_id = $plan_front_info['id'];
    }

    $project_id=$history_info['project_id'];
    $pro_name=$history_info['customer'];

}else {
    $project_id = isset($_GET['project_id']) ? safeCheck($_GET['project_id']) : 0;


    if ($project_id) {
        $proinfo = Project::getInfoById($project_id);
//print_r($proinfo);
        $pro_name = !empty($proinfo) ? $proinfo['name'] : '';
    }
}
//选型入口
    //$fromProject = isset($_GET['fromProject'])?safeCheck($_GET['fromProject']):0;




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>锅炉手动选型</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/main_two.css">
    <link rel="stylesheet" href="css/newlayui.css">
    <link rel="stylesheet" href="layui/css/layui.css">
    <script type="text/javascript" src="js/jquery-1.4.4.min.js"></script>
    <script type="text/javascript" src="layer/layer.js"></script>
    <style>
        .GLXXmain_3{
            float:left
        }

    </style>
</head>
<body class="body_2">
    <?php include('top.inc.php');?>

    <div class="manageHRWJCont_middle_left" style="margin-top: 30px">
        <ul>
            <a href="selection.php?project_id=<?php echo $project_id;?>"><li>智能选型</li></a>
            <a href="selection_manual_new.php?project_id=<?php echo $project_id;?>"><li class="manage_liCheck">手动选型</li></a>
            <a href="selection_change.php?project_id=<?php echo $project_id;?>"><li>更换锅炉</li></a>

        </ul>
    </div>
    <?php
      if ($history_id > 0 && $history_info['status'] == 5){
        echo '
        <div class="manageHRWJCont_middle_middle">
          <div id="step" style="margin-top: 30px">
              <div class="step-wrap">
                  <div class="step-list">
                      <div class="step-num">
                          <div class="num-bg"><a href="selection_manual_new.php?id='?><?php echo $id ?><?php echo'" class="num-bg">1</a></div>
                      </div>
                      <span class="step-name"><a href="selection_manual_new.php?id='?><?php echo $id ?><?php echo'" class="step-name">选型</a></span>
                  </div>
                  <div class="step-line"></div>
                  <div class="step-list">
                      <div class="step-num">
                          <div class="num-bg"><a href="selection_make_price.php?id='?><?php echo $id ?><?php echo'&isUpdate=1" class="num-bg">2</a></div>
                      </div>
                      <span class="step-name"><a href="selection_make_price.php?id='?><?php echo $id ?><?php echo'&isUpdate=1" class="step-name">报价</a></span>
                  </div>
                  <div class="step-line"></div>
                  <div class="step-list">
                      <div class="step-num">
                        <div class="num-bg"><a href="selection_plan_two.php?id='?><?php echo $id ?><?php echo'&front_plan_id='?><?php echo $plan_front_id ?><?php echo'" class="num-bg">3</a></div>
                      </div>
                      <span class="step-name"><a href="selection_plan_two.php?id='?><?php echo $id ?><?php echo'&front_plan_id='?><?php echo $plan_front_id ?><?php echo'" class="step-name">方案</a></span>
                  </div>
              </div>
          </div>
      </div>
        ';
      }else{
          echo '
            <div class="manageHRWJCont_middle_middle">
                <div id="step" style="margin-top: 30px">
                    <div class="step-wrap">
                        <div class="step-list">
                            <div class="step-num">
                                <div class="num-bg">1</div>
                            </div>
                            <span class="step-name">选型</span>
                        </div>
                        <div class="step-line"></div>
                        <div class="step-list">
                            <div class="nums">2</div>
                            <span class="step-names">报价</span>
                        </div>
                        <div class="step-lines"></div>
                        <div class="step-list">
                            <div class="nums">3</div>
                            <span class="step-names">方案</span>
                        </div>
                    </div>
                </div>
            </div>
            ';
          }
          ?>

    <div class="manageHRWJCont_middle_middle">

        <div class="GLXXmain_1">客户名称</div>
        <div class="GLXXmain_2">
             <input class="GLXXmain_3" type="text" value="<?php if($project_id ) echo $pro_name;else echo !empty($customer)?$customer:''; ?>" id="customer"  style="width: 200px"/>
        </div>


        <div class="XXRmain_1" style="margin-top: 30px">锅炉</div>
        <table class="XXRmain_7" border="1" id="my_guolu_list" style="margin-top: 0px;width: 1030px !important;">
            <tr class="GLDetils9_fir">
                    <td width="10%">序号</td>
                    <td width="10%">设备名称</td>
                    <td width="15%">厂家名称</td>
                    <td width="30%">规格型号</td>
                    <td width="5%">数量</td>
                    <td width="20%">备注</td>
                    <td width="10%">操作</td>
            </tr>
            <tr class="guolu_item_temp" style="display: none" ></tr>
            <?php
            if ($history_id != 0) {
                if (!empty($guolu_id_list)) {
                    $i = 1;
                    while (($i - 1) < count($guolu_id_list) && !empty($guolu_id_list[0])) {

                        $guoluinfo = Guolu_attr::getInfoById($guolu_id_list[$i - 1]);

                        echo '
                        <tr class="guolu_item" >
                            <td><span id="guolu_index" class="center guolu_index" data-value="'?><?php echo $i ?><?php echo'">'?><?php echo $i ?><?php echo'</span></td>
                            <td id="guolu_name">锅炉</td>
                            <td id="guolu_vender_td">
                                <select id="guolu_vender" class="GLXXmain_3 guolu_vender" style="margin:2px;width: 99%" type="text">' ?>
                                <?php
                                if (!empty($guolu_vender_list)) {
                                    foreach ($guolu_vender_list as $guolu_vender) {
                                        $selected = '';
                                        if ($guolu_vender['id'] == $guoluinfo['vender']) {
                                            $selected = 'selected';
                                        }
                                        echo '<option value="' . $guolu_vender['id'] . '" ' . $selected . '>' . $guolu_vender['name'] . '</option>';
                                    }
                                } else {
                                    echo "没有找到合适的型号";
                                }
                                echo '
                                </select>
                            </td>
                            <td id="guolu_type">
                                <select id="guolu_version" class="GLXXmain_3 guolu_version" style="margin:2px;width: 99%" type="text">' ?>
                        <?php
                        $guolu_list = Guolu_attr::getList(0, '', 0, $guoluinfo['vender'], '', '', '');
                        if ($guolu_list) {
                            foreach ($guolu_list as $guolu) {
                                $selected = '';
                                if ($guolu['guolu_id'] == $guolu_id_list[$i - 1]) {
                                    $selected = 'selected';
                                }
                                echo '<option value="' . $guolu['guolu_id'] . '" ' . $selected . '>' . $guolu['guolu_version'] . '</option>';
                            }
                        } else {
                            echo "没有找到合适的型号";
                        }
                        echo '
                                </select>
                            </td>
                            <td >
                                <input style="margin:2px" class="GLXXmain_3" type="number" value="'?><?php echo $guolu_num_list[$i - 1] ?><?php echo '" id="guolu_num"/>
                            </td>
                            <td >
                                <input style="margin:2px" class="GLXXmain_3" type="text" value="' ?><?php echo $guolu_context_list[$i - 1] ?><?php echo '" id="guolu_context"/>
                            </td>
                            <td>
                                <span id="delete_guolu" class="mougl" style="color: red"> 删除 </span>
                            </td>
                        </tr>
                        '
                        ?>
                        <?php
                        $i++;
                    }
                }
            }
            ?>

        </table>
        <div class="GLXXmain_16">
            <span class="addgl" style="display: block" >添加锅炉</span>
        </div>

        <div class="XXRmain_1">辅机</div>
        <table class="XXRmain_7" border="1" id="my_guolu_list" style="margin-top: 0px;width: 1030px !important;">
            <tr class="GLDetils9_fir">
                    <td width="10%">序号</td>
                    <td width="10%">设备名称</td>
                    <td width="30%">规格型号</td>
                    <td width="20%">数量</td>
                    <td width="20%">备注</td>
                    <td width="10%">操作</td>
            </tr>
            <tr class="fuji_item_temp" style="display: none">
            <?php
            if ($history_id != 0) {
                if ($fujilist) {
                    $j = 1;
                    foreach ($fujilist as $fuji) {
                        echo '
                        <tr class="fuji_item" >
                            <td><span id="fuji_index" class="center fuji_index" data-value="'?><?php echo $j ?><?php echo '">'?><?php echo $j ?><?php echo '</span></td>
                            <td >
                                <input style="margin:2px" class="GLXXmain_3" type="text" value="' ?><?php echo $fuji['name'] ?><?php echo '" id="fuji_name"/>
                            </td>
                            <td >
                                <input style="margin:2px;width: 89.3%" class="GLXXmain_3" type="text" value="' ?><?php echo $fuji['version_show'] ?><?php echo '" id="fuji_version"/>
                            </td>
                            <td >
                                <input style="margin:2px" class="GLXXmain_3" type="number" value="' ?><?php echo $fuji['num'] ?><?php echo '" id="fuji_num"/>
                            </td>
                            <td >
                                <input style="margin:2px" class="GLXXmain_3" type="text" value="' ?><?php echo $fuji['context'] ?><?php echo '" id="fuji_context"/>
                            </td>
                            <td>
                                <span id="delete_fuji" class="moufj" style="color: red"> 删除 </span>
                            </td>
                    </tr>
                    ';
                                $j++;
                    }
                }
            }
            ?>

        </table>
        <div class="GLXXmain_16">
            <span class="addfj" style="display: block" >添加辅机</span>
        </div>

        <button class="GLXXmain_17" id="next_btn" >开始报价</button>
    </div>


    <style>
        #step .step-wrap {
            width: 100%;
            position: relative;
        }
        #step .step-wrap .step-list{
            display: inline-block;
            width: 64px;
            text-align: center;
        }
        #step .step-wrap .step-list .step-num{
            display: inline-block;
            position: relative;
            width: 48px;
            height: 48px;
            background: rgba(4,166,254,0.2);
            border-radius: 50%;
        }
        #step .step-wrap .step-list .nums{
            margin: auto;
            width: 32px;
            height: 32px;
            background: #FFC80A;
            border-radius: 50%;
            text-align: center;
            font-size: 16px;
            color: #fff;
            line-height: 32px;
        }
        #step .step-wrap .step-list .step-num .num-bg{
            margin: auto;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            width: 32px;
            height: 32px;
            background: #04A6FE;
            border-radius: 50%;
            text-align: center;
            font-size: 16px;
            color: #fff;
            line-height: 32px;
        }
        #step .step-wrap .step-list .step-name{
            font-size: 16px;
            color: #04A6FE;
        }
        #step .step-wrap .step-list .step-names{
            font-size: 16px;
            color: #293144;
            margin-top: 8px;
            display: block;
        }
        #step .step-wrap .step-line{
            display: inline-block;
            width: 290px;
            height: 2px;
            background: #04A6FE;
            margin: 0 -20px 42px -20px;
        }
        #step .step-wrap .step-lines{
            display: inline-block;
            width: 290px;
            height: 2px;
            background: #FFC80A;
            margin: 0 -20px 42px -20px;
        }

    </style>

    <script>
        $(function () {


            $('.indexMtwo_1').hover(function () {
                $(this).find('.mouseset').slideDown('fast');
                var name = $(this).find('.indexMtwo_1_2').text();
                $(this).find('.mouseset').find('span').text(name);
            }, function () {
                $(this).find('.mouseset').slideUp(100);
            });



            // 添加一行锅炉参数
            //$('.addgl').click(function () {
            //    var guoluArr = $(".guolu_item");
            //    var guolu_exist_ids_arr = [];
            //    for(var i = 0; i < guoluArr.length; i++){
            //        var guolu_currentDom = guoluArr[i];
            //        var guoluId = $(guolu_currentDom).find("#guolu_version option:selected").val();
            //        guolu_exist_ids_arr.push(guoluId);
            //    }
            //    <?php
            //    $guolu_list = Guolu_attr::getList(0, '', 0, '', '', '', '');
            //    ?>
            //    var all_guolu_list = new Array();
            //    all_guolu_list = <?php //echo json_encode($guolu_list) ?>//;
            //    var show_guolu_list = [];
            //    for (var j = 0 ; j < all_guolu_list.length; j++){
            //        var isExist = false;
            //        for (var k = 0; k < guolu_exist_ids_arr.length; k++ ){
            //            if (all_guolu_list[j]['guolu_id'] == guolu_exist_ids_arr[k]) {
            //                isExist = true;
            //            }
            //        }
            //        if (!isExist){
            //            show_guolu_list.push(all_guolu_list[j]);
            //        }
            //    }
            //
            //    var guolu_version_html = "";
            //    for (var l = 0; l < show_guolu_list.length; l++){
            //        guolu_version_html += "<option value='" + show_guolu_list[l]['guolu_id'] + "'>" + show_guolu_list[l]['guolu_version'] + "</option>";
            //    }
            //
            //    var currentIndex = 1;
            //    var guoluIndexArr = $(".guolu_index");
            //    if (guoluIndexArr.length > 0) {
            //        var guoluLastIndexDom = guoluIndexArr[guoluIndexArr.length - 1];
            //        currentIndex = $(guoluLastIndexDom).data("value") + 1;
            //    }
            //
            //    var NHtml = '<tr  class="guolu_item">' +
            //        '<td class="center" >'+
            //        '<span id="guolu_index" class="center guolu_index" data-value="' + currentIndex + '">' + currentIndex + '</span>'+
            //        '</td>'+
            //        '<td class="center">锅炉</td>'+
            //        '<td class="center"> '+
            //        '<select id="guolu_version" class="GLXXmain_3" style="margin:2px;width: 99%" type="text">'+
            //        '<option value="0">请选择锅炉型号</option>' + guolu_version_html +
            //        '</select>'+
            //        '</td>'+
            //        '<td class="center">'+
            //        '<input style="margin:2px" class="GLXXmain_3" type="number" value="" id="guolu_num"/>'+
            //        '</td>'+
            //        '<td class="center">'+
            //        '<input style="margin:2px" class="GLXXmain_3" type="text" value="" id="guolu_context"/>'+
            //        '</td>'+
            //        '<td class="center" >'+
            //        '<span id="delete_guolu" class="mougl" style="color: red"> 删除 </span>'+
            //        '</td>'+
            //        '</tr>';
            //
            //    if(guoluArr.length < 1){
            //        $(this).parent().parent().find('.guolu_item_temp:nth-last-child(1)').after(NHtml);
            //    }else {
            //        $(this).parent().parent().find('.guolu_item:nth-last-child(1)').after(NHtml);
            //    }
            //});

            // 添加一行锅炉参数(包含厂家)
            $('.addgl').click(function () {

                var guoluArr = $(".guolu_item");
                var vender_list = new Array();
                vender_list = <?php echo json_encode($guolu_vender_list) ?>;
                var guolu_vender_html = "";
                for (var l = 0; l < vender_list.length; l++){
                    guolu_vender_html += "<option value='" + vender_list[l]['id'] + "'>" + vender_list[l]['name'] + "</option>";
                }

                var currentIndex = 1;
                var guoluIndexArr = $(".guolu_index");
                if (guoluIndexArr.length > 0) {
                    var guoluLastIndexDom = guoluIndexArr[guoluIndexArr.length - 1];
                    currentIndex = $(guoluLastIndexDom).data("value") + 1;
                }

                var NHtml = '<tr  class="guolu_item">' +
                    '<td class="center" >'+
                    '<span id="guolu_index" class="center guolu_index" data-value="' + currentIndex + '">' + currentIndex + '</span>'+
                    '</td>'+
                    '<td class="center">锅炉</td>'+
                    '<td class="center"> '+
                    '<select id="guolu_vender" class="GLXXmain_3 guolu_vender" style="margin:2px;width: 99%" type="text">'+
                    '<option value="0">请选择厂家</option>' + guolu_vender_html +
                    '</select>'+
                    '</td>'+
                    '<td class="center"> '+
                    '<select id="guolu_version" class="GLXXmain_3 guolu_version" style="margin:2px;width: 99%" type="text">'+
                    '<option value="0">请选择锅炉型号</option>' +
                    '</select>'+
                    '</td>'+
                    '<td class="center">'+
                    '<input style="margin:2px" class="GLXXmain_3" type="number" value="" id="guolu_num"/>'+
                    '</td>'+
                    '<td class="center">'+
                    '<input style="margin:2px" class="GLXXmain_3" type="text" value="" id="guolu_context"/>'+
                    '</td>'+
                    '<td class="center" >'+
                    '<span id="delete_guolu" class="mougl" style="color: red"> 删除 </span>'+
                    '</td>'+
                    '</tr>';

                if(guoluArr.length < 1){
                    $(this).parent().parent().find('.guolu_item_temp:nth-last-child(1)').after(NHtml);
                }else {
                    $(this).parent().parent().find('.guolu_item:nth-last-child(1)').after(NHtml);
                }
            });

            // 删除一行锅炉参数
            $(document).on('click','.mougl',function(){
                var len = $(".guolu_item").length;
                var len = parseFloat(len);
                $(this).parent().parent().remove();
                // 更新序号
                var guoluIndexArr = $(".guolu_index");
                for (var temp = 0; temp < guoluIndexArr.length; temp++){
                    var guoluLastIndexTempDom = guoluIndexArr[temp];
                    $(guoluLastIndexTempDom).data("value",temp+1);
                    $(guoluLastIndexTempDom).html(temp+1);
                }
            });

            // 添加一行辅机参数
            $('.addfj').click(function () {

                var currentFujiIndex = 1;
                var fujiIndexArr = $(".fuji_index");

                if (fujiIndexArr.length > 0){
                    var fujiLastIndexDom = fujiIndexArr[fujiIndexArr.length - 1];
                    currentFujiIndex = $(fujiLastIndexDom).data("value") + 1;
                }

                var NHtml = '<tr class="fuji_item">'+
                    '<td>'+
                        '<span id="fuji_index" class="center fuji_index" data-value="'+ currentFujiIndex + '">'+ currentFujiIndex + '</span>'+
                    '</td>'+
                    '<td >'+
                        '<input style="margin:2px" class="GLXXmain_3" type="text" value="" id="fuji_name"/>'+
                    '</td>'+
                    '<td >'+
                        '<input style="margin:2px;width: 89.3%" class="GLXXmain_3" type="text" value="" id="fuji_version"/>'+
                    '</td>'+
                    '<td >'+
                        '<input style="margin:2px" class="GLXXmain_3" type="number" value="" id="fuji_num"/>'+
                    '</td>'+
                    '<td >'+
                        '<input style="margin:2px" class="GLXXmain_3" type="text" value="" id="fuji_context"/>'+
                    '</td>'+
                    '<td>'+
                        '<span id="delete_fuji" class="moufj" style="color: red"> 删除 </span>'+
                    '</td>'+
                    '</tr>';

                if (fujiIndexArr.length > 0){
                    $(this).parent().parent().find('.fuji_item:nth-last-child(1)').after(NHtml);
                }else {
                    $(this).parent().parent().find('.fuji_item_temp:nth-last-child(1)').after(NHtml);
                }

            });

            // 删除一行辅机参数
            $(document).on('click','.moufj',function(){
                var len = $(".fuji_item").length;
                var len = parseFloat(len);
                $(this).parent().parent().remove();
                // 更新序号
                var fujiIndexArr = $(".fuji_index");
                for (var temp = 0; temp < fujiIndexArr.length; temp++){
                    var fujiLastIndexTempDom = fujiIndexArr[temp];
                    $(fujiLastIndexTempDom).data("value",temp+1);
                    $(fujiLastIndexTempDom).html(temp+1);
                }
            });

            // 开始报价
            $('#next_btn').click(function () {

                var isPrepared = true;

                var customer = $('#customer').val();

                //输入项检查
                if (customer == '') {
                    layer.alert("客户名称不能为空",{icon:5});
                    isPrepared = false;
                }

                var guoluArr = new Array(); // 锅炉类型
                var guoluNumArr = new Array(); // 锅炉数量
                var guoluContextArr = new Array(); // 锅炉备注

                $(".guolu_item").each(function(){
                    var currentNum = $(this).find("#guolu_num").val();
                    var currentContext = $(this).find("#guolu_context").val();

                    if(currentNum != "" && parseFloat(currentNum) > 0)
                    {
                        var currentVersion = $(this).find("#guolu_version").val();
                        if (currentVersion == null || currentVersion == 0){
                            layer.alert("请选择锅炉型号",{icon:5});
                            isPrepared = false;
                        }
                        guoluArr.push(currentVersion);
                        guoluNumArr.push(currentNum);
                        guoluContextArr.push(currentContext);
                    }else {
                        layer.alert("请填写锅炉数量",{icon:5});
                        isPrepared = false;
                    }
                });

                var guoluStr = guoluArr.join(",");
                var guoluNumStr = guoluNumArr.join(",");
                var guoluContextStr = guoluContextArr.join('||');

                var fujiNameArr = new Array(); // 辅机名称
                var fujiVersionArr = new Array(); // 辅机型号
                var fujiNumArr = new Array(); // 辅机数量
                var fujiContextArr = new Array(); // 辅机备注

                $(".fuji_item").each(function(){

                    var currentFujiName = $(this).find("#fuji_name").val();
                    var currentFujiVersion = $(this).find("#fuji_version").val();
                    var currentFujiNum = $(this).find("#fuji_num").val();
                    var currentFujiContext = $(this).find("#fuji_context").val();

                    if(currentFujiNum != "" && parseFloat(currentFujiNum) > 0)
                    {
                        fujiNameArr.push(currentFujiName);
                        fujiVersionArr.push(currentFujiVersion);
                        fujiNumArr.push(currentFujiNum);
                        fujiContextArr.push(currentFujiContext);
                    }else {
                        layer.alert("请填写辅机数量",{icon:5});
                        isPrepared = false;
                    }
                    if (currentFujiName == ""){
                        layer.alert("请填写辅机名称",{icon:5});
                        isPrepared = false;
                    }
                    if (currentFujiVersion == ""){
                        layer.alert("请填写辅机规格型号",{icon:5});
                        isPrepared = false;
                    }
                });

                var fujiNameStr = fujiNameArr.join("||");
                var fujiVersionStr = fujiVersionArr.join("||");
                var fujiNumStr = fujiNumArr.join("||");
                var fujiContextStr = fujiContextArr.join("||");

                var index = layer.load(0, {shade: false});

                var history_id = 0;
                history_id = <?php echo $history_id ?>;
                var project_id=0;
                project_id=<?php echo $project_id ?>;

                if (isPrepared) {
                    if (history_id != 0) {
                        $.ajax({
                            type: 'POST',
                            data: {
                                history_id: history_id,
                                customer: customer,
                                guoluStr: guoluStr,
                                guoluNumStr: guoluNumStr,
                                guoluContextStr: guoluContextStr,
                                fujiNameStr: fujiNameStr,
                                fujiVersionStr: fujiVersionStr,
                                fujiNumStr: fujiNumStr,
                                fujiContextStr: fujiContextStr
                            },
                            dataType: 'json',
                            url: 'selection_do.php?act=update_guolu_manual_new',
                            success: function (data) {
                                layer.close(index);
                                var code = data.code;
                                var msg = data.msg;
                                switch (code) {
                                    case 1:
                                        location.href = 'selection_make_price.php?id=' + history_id;
                                        break;
                                    default:
                                        layer.alert(msg, {icon: 5});
                                }
                            }
                        });
                    } else {
                        $.ajax({
                            type: 'POST',
                            data: {
                                history_id: history_id,
                                project_id,project_id,
                                customer: customer,
                                guoluStr: guoluStr,
                                guoluNumStr: guoluNumStr,
                                guoluContextStr: guoluContextStr,
                                fujiNameStr: fujiNameStr,
                                fujiVersionStr: fujiVersionStr,
                                fujiNumStr: fujiNumStr,
                                fujiContextStr: fujiContextStr
                            },
                            dataType: 'json',
                            url: 'selection_do.php?act=select_guolu_manual_new',
                            success: function (data) {
                                layer.close(index);
                                var code = data.code;
                                var msg = data.msg;
                                var history_id = data.data;
                                switch (code) {
                                    case 1:
                                        location.href = 'selection_make_price.php?id=' + history_id;
                                        break;
                                    default:
                                        layer.alert(msg, {icon: 5});
                                }
                            }
                        });
                    }
                } else {
                    layer.close(index);
                    return false;
                }
            });

            // 获取指定商家的锅炉列表
            $(document).on('change','.guolu_vender',function(){
                var vender = $(this).val();
                var versionDom = $(this).parent().parent().find(".guolu_version");
                versionDom.html("<option value='0'>暂无可选型号</option>");


                if(vender != 0) {

                    var guoluArr = $(".guolu_item");
                    var guolu_exist_ids_arr = [];  // 存储已有锅炉型号
                    for(var i = 0; i < guoluArr.length; i++){
                        var guolu_currentDom = guoluArr[i];
                        var venderId = $(guolu_currentDom).find("#guolu_vender option:selected").val();// 获取每一行的厂家id

                        if(venderId == vender){
                            // 将同一个厂家的锅炉型号收集起来
                            var guoluId = $(guolu_currentDom).find("#guolu_version option:selected").val();// 获取每一行的锅炉id
                            guolu_exist_ids_arr.push(guoluId);
                        }
                    }
                    $.ajax({
                        type: 'POST',
                        data: {
                            vender: vender
                        },
                        dataType: 'json',
                        url: 'selection_do.php?act=get_guolu_list_vender',
                        success: function (data) {
                            var code = data.code;
                            var msg = data.msg;
                            var guolu_list = data.data;
                            switch (code) {
                                case 1:
                                    // 在这里先清空已选的锅炉类型，再更新锅炉类型单选框可选个数
                                    var html = "";

                                    var show_guolu_list = [];
                                    for (var j = 0; j < guolu_list.length; j++) {
                                        var isExist = false;
                                        for (var k = 0; k < guolu_exist_ids_arr.length; k++) {
                                            if (guolu_list[j]['guolu_id'] == guolu_exist_ids_arr[k]) {
                                                isExist = true;
                                            }
                                        }
                                        if (!isExist) {
                                            show_guolu_list.push(guolu_list[j]);
                                        }
                                    }

                                    for (var i = 0; i < show_guolu_list.length; i++) {
                                        var guolu_version = show_guolu_list[i].guolu_version;
                                        var value = show_guolu_list[i].guolu_id;
                                        html += "<option value='" + value + "'>" + guolu_version + "</option>";
                                    }
                                    versionDom.html(html);
                                    if (!(show_guolu_list != null && show_guolu_list.length >= 1)) {
                                        versionDom.html("<option value='0'>暂无可选型号</option>");
                                    }
                                    break;

                                default:
                                    break;
                            }
                        }
                    });
                }
            });

            // 锅炉id发生变化时获取其历史价格
            $(document).on('change','.guolu_version',function(){
                var guolu_id = $(this).val();
                var guoluArr = $(".guolu_item");
                var count = 0;
                for(var i = 0; i < guoluArr.length; i++){
                    var guolu_currentDom = guoluArr[i];
                    var guoluId = $(guolu_currentDom).find("#guolu_version option:selected").val();// 获取每一行的gid
                    if(guoluId == guolu_id){
                        count++;
                    }
                }
                if(count > 1){
                    alert("当前已经存在该型号的锅炉！");
                }
            });

        });

    </script>

</body>
</html>