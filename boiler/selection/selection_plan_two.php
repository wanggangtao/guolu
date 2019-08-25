<?php
/**
 * 选型方案2
 * Created by Xinmei.
 * User: Administrator
 * Date: 2018/11/4
 * Time: 15:36
 */
require_once('web_init.php');
require_once('usercheck.php');

$TOP_FLAG = "selection";

//获取historyId
$id = isset($_GET['id'])?safeCheck($_GET['id']):0;

//选型入口
//$fromProject = isset($_GET['fromProject'])?safeCheck($_GET['fromProject']):0;

$project_id=0;
if($id){
    $project_id=Selection_history::getInfoById($id)['project_id'];
}

$vender_dict_id_arr = array();
$vender_intro_tplContent_ids = array();
$vender_intro_ids_str = "0";
$vender_competency_tplContent_ids = array();
$vender_competency_ids_str = "0";

$guoluIdArray = array();
$guoluVersionArray = array();
$guoluVersionString= "";



//厂家信息
if($id != 0){
    //获取锅炉字符串（用','隔开的数字字符串）
    $history  = Selection_history::getInfoById($id);
//    $history_guolu_id = $history['guolu_id'];
//    $guoluIdArray = explode(",",$history_guolu_id);
    $plans = Selection_plan::getInfoByHistoryidandTabtype($id,Selection_plan::BOILER_TAB_TYPE);
    //print_r(count($plans)."-----------countPlan-------------");
    if(!empty($plans)){
        foreach ($plans as $key => $plan){
            if(!empty($plan['attrid'])){
                $guoluIdArray[$key] = $plan['attrid'];
            }
        }
    }

    //print_r(count($guoluIdArray)."----------------countArr-------------");
    if(!empty($guoluIdArray)){
        foreach ($guoluIdArray as $key => $guoluIdArr){
            //print_r($guoluIdArr."-----------guoluIdaArr-------------");
            $guoluInfo = Guolu_attr::getInfoById($guoluIdArr);
            if(!empty($guoluInfo['vender'])){
                $vender_id = $guoluInfo['vender'];
                $vender_dict_id_arr[$key] = $vender_id;
            }
            if(!empty($guoluInfo['version'])){
                $guoluVersionArray[$key] = $guoluInfo['version'];
            }
        }
        $vender_dict_id_arr = array_unique($vender_dict_id_arr);
    }
    $guoluVersionString = implode(",",$guoluVersionArray);
    if(!empty($vender_dict_id_arr)){
        foreach ($vender_dict_id_arr  as $vender_dict_id_value){
            //print_r($vender_dict_id_value."-----------vender_dict_id_value-------------");
            $vender_info = Dict::getInfoById($vender_dict_id_value);   //工厂的dict
            $vender_tplContents = Case_tplcontent::getListByDictId($vender_dict_id_value);  //attrid=1,content=工厂dict_id的tplContent
            if (!empty($vender_tplContents)) {
                foreach ($vender_tplContents as $vender_tpl_value) {

                    //print_r($vender_tpl_value['id']."---------vender_tpl_value--------------");
                    $intro_info = Case_tplcontent::getInfoByAttridAndTplid(Case_tplcontent::VENDER_INTRO_ATTRID, $vender_tpl_value['tplid']);
                    if (!empty($intro_info)) {
                        $intro_id = $intro_info[0]["id"];
                        $vender_intro_tplContent_ids[count($vender_intro_tplContent_ids)] = $intro_id."_".$vender_info['name'];
                    }

                    $competency_info = Case_tplcontent::getInfoByAttridAndTplid(Case_tplcontent::VENDER_COMPETENCY_ATTRID, $vender_tpl_value['tplid']);
                    if (!empty($competency_info)) {
                        $competency_id = $competency_info[0]['id'];
                        $vender_competency_tplContent_ids[count($vender_competency_tplContent_ids)] = $competency_id."_".$vender_info['name'];
                    }
                }
            }
        }
        $vender_intro_ids_str = implode(",", $vender_intro_tplContent_ids);
        $vender_competency_ids_str = implode(",", $vender_competency_tplContent_ids);
    }
//    if(!empty($guoluIdArray[0]) && $guoluIdArray[0] != "") {
//        //获取工厂信息
//        $vender_dict_id = Guolu_attr::getInfoById($guoluIdArray[0])['vender'];    //工厂dict_Id
//        $vender_info = Dict::getInfoById($vender_dict_id);   //工厂的dict
//        $vender_tplContents = Case_tplcontent::getListByDictId($vender_dict_id);  //attrid=1,content=工厂dict_id的tplContent
//
//        if (!empty($vender_tplContents)) {
//            foreach ($vender_tplContents as $key => $value) {
//                //print_r($value['id']."----".$value['tplid']."=");
//                $intro_info = Case_tplcontent::getInfoByAttridAndTplid(Case_tplcontent::VENDER_INTRO_ATTRID, $value['tplid']);
//                if (!empty($intro_info)) {
//                    $intro_id = $intro_info[0]["id"];
//                    $vender_intro_tplContent_ids[$key] = $intro_id;
//                }
//
//                $competency_info = Case_tplcontent::getInfoByAttridAndTplid(Case_tplcontent::VENDER_COMPETENCY_ATTRID, $value['tplid']);
//                if (!empty($competency_info)) {
//                    $competency_id = $competency_info[0]['id'];
//                    $vender_competency_tplContent_ids[$key] = $competency_id;
//                }
//            }
//            $vender_intro_ids_str = implode(",", $vender_intro_tplContent_ids);
//            $vender_competency_ids_str = implode(",", $vender_competency_tplContent_ids);
//        }
//    }

//    $vender_intro = Table_case_tplcontent::getInfoByAttridAndTplid(Case_tplcontent::VENDER_INTRO_ATTRID,$vender_dict_id);
//
//    $vender_intro_id = 0;
//    if(null != $vender_intro){
//        $vender_intro_id = $vender_intro['id'];
//    }
//    $vender_competency = Table_case_tplcontent::getInfoByAttridAndTplid(Case_tplcontent::VENDER_COMPETENCY_ATTRID,$vender_dict_id);
//    $vender_competency_id = 0;
//    if(null != $vender_competency){
//        $vender_competency_id = $vender_competency['id'];
//    }

}

//修改方案池方案--ID
$front_plan_id = null;
if(isset($_GET['front_plan_id'])){
    $front_plan_id = safeCheck($_GET['front_plan_id']);
}
if(!empty($front_plan_id)){
    $front_plan = Selection_plan_front::getInfoById($front_plan_id);
    $plan_content = $front_plan['tplcontent'];
    //$id = $front_plan['history_id'];

    //获取锅炉字符串（用','隔开的数字字符串）
    //$history  = Selection_history::getInfoById($id);
    //$history_guolu_id = $history['guolu_id'];
    //$guoluIdArray = explode(",",$history_guolu_id);
//    if(!empty($guoluIdArray[0]) && $guoluIdArray[0] != "") {
//        //获取工厂信息
//        $vender_dict_id = Guolu_attr::getInfoById($guoluIdArray[0])['vender'];    //工厂dict_Id
//        $vender_info = Dict::getInfoById($vender_dict_id);   //工厂的dict
//        $vender_tplContents = Case_tplcontent::getListByDictId($vender_dict_id);  //attrid=1,content=工厂dict_id的tplContent

//--------------------------old----------------------------
//        $vender_intro_tplContent_ids = array();
//        $vender_intro_ids_str = "0";
//        $vender_competency_tplContent_ids = array();
//        $vender_competency_ids_str = "0";
//---------------------------------------------------------

//        if (!empty($vender_tplContents)) {
//            foreach ($vender_tplContents as $key => $value) {
//                //print_r($value['id']."----".$value['tplid']."=");
//                $intro_info = Case_tplcontent::getInfoByAttridAndTplid(Case_tplcontent::VENDER_INTRO_ATTRID, $value['tplid']);
//                if (!empty($intro_info)) {
//                    $intro_id = $intro_info[0]["id"];
//                    $vender_intro_tplContent_ids[$key] = $intro_id;
//                }
//
//                $competency_info = Case_tplcontent::getInfoByAttridAndTplid(Case_tplcontent::VENDER_COMPETENCY_ATTRID, $value['tplid']);
//                if (!empty($competency_info)) {
//                    $competency_id = $competency_info[0]['id'];
//                    $vender_competency_tplContent_ids[$key] = $competency_id;
//                }
//            }
//            $vender_intro_ids_str = implode(",", $vender_intro_tplContent_ids);
//            $vender_competency_ids_str = implode(",", $vender_competency_tplContent_ids);
//        }
//    }

}




//初始化
$page = 1;
$pageSize  = 15;
$attrid = 7;

//查询模板列表
$rows = Case_tpl::getListByAttrid($attrid, null, null, $count = 0);

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>选型方案</title>
    <link rel="stylesheet" href="css/nav.css" />
    <link rel="stylesheet" href="css/GLXX2.css" />
    <script type="text/javascript" src="js/nav.js" ></script>
    <link rel="stylesheet" href="css/Tc.css" />
    <script type="text/javascript" src="js/2.0.0/jquery.min.js" ></script>
    <script type="text/javascript" src="js/layer.js" ></script>

    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/newlayui.css">
    <link rel="stylesheet" href="layui/css/layui.css">
    <script type="text/javascript" src="js/jquery-1.4.4.min.js"></script>
    <script type="text/javascript" src="layer/layer.js"></script>
    <script type="text/javascript" src="ckeditor/ckeditor.js"></script>
    <style>
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
        #step .step-bg {
            width: 100%;
            height: 10px;
            border-radius: 5px;
            position: absolute;
            top: 10px;
            left: 0;
            background-color: lightgrey;
        }
        #step .step-progress {
            width: 66.66%;
            height: 10px;
            border-radius: 5px;
            position: absolute;
            top: 10px;
            left: 0;
            background-color: #04A6FE;
        }
        #step .step {
            display: inline-block;
        }
    </style>

    <script type="text/javascript">
        $(function() {
            //编辑器初始化
            var ckeditor = CKEDITOR.replace('content', {
                toolbar: 'Common',
                forcePasteAsPlainText: 'true',//强制纯文本格式粘贴
                filebrowserImageUploadUrl: 'ckeditor_upload.php?type=img',
                filebrowserUploadUrl: 'ckeditor_upload.php?type=file'
            });
            $('.GLXXmain_7').click(function(){
                $('.GLXXmain_7').removeClass('GLXXmain_check');
                $(this).addClass('GLXXmain_check');
            });
        });

        //自定义模板
        function userDefined() {
            var oEditor = CKEDITOR.instances.content;
            oEditor.setData("");
        }
        //跳转至  选型页面
        function toSelection() {
            document.getElementById("confirm2").style.backgroundColor="#04A6FE";
            document.getElementById("confirm2").style.color="white";
            document.getElementById("cancel2").style.backgroundColor="white";
            document.getElementById("cancel2").style.color="#04A6FE";
            window.location.href='selection.php';
        }
        //跳转至  方案池页面
        function toPlanPool() {
            document.getElementById("confirm2").style.backgroundColor="white";
            document.getElementById("confirm2").style.color="#04A6FE";
            document.getElementById("cancel2").style.backgroundColor="#04A6FE";
            document.getElementById("cancel2").style.color="white";
            window.location.href='selection_plan_pool.php';
        }
        //跳转至  项目管理-选型方案页面
        function toProjectSelection() {
            document.getElementById("confirm2").style.backgroundColor="#04A6FE";
            document.getElementById("confirm2").style.color="white";
            document.getElementById("cancel2").style.backgroundColor="white";
            document.getElementById("cancel2").style.color="#04A6FE";
            window.location.href='../project/project_select_plan.php?tag=1&id=<?php echo $project_id;?>';
        }
        //取消
        function toCancel() {
            document.getElementById("confirm2").style.backgroundColor="white";
            document.getElementById("confirm2").style.color="#04A6FE";
            document.getElementById("cancel2").style.backgroundColor="#04A6FE";
            document.getElementById("cancel2").style.color="white";

            window.parent.location.reload();//刷新父页面
            //parent.layer.close(index);//关闭弹出层
            layer.close(layer.index);
            //window.location.href='selection_plan_pool.php';
        }

    </script>

</head>
<body class="body_2">

<?php include('top.inc.php');?>
<div class="manageHRWJCont_middle_middle">

    <div id="step" style="margin-top: 30px">
        <div class="step-wrap" align="center">
            <div class="step-list">
                <div class="step-num" id="selection_one">
                    <?php
                    if($history['type'] == 1) {//智能选型
                        ?>
                        <a href="selection.php?id=<?php echo $id ?>" </a>
                        <div class="num-bg">1</div>
                        <?php
                    }  elseif($history['type'] == 2){//手动选型
                        ?>
                        <a href="selection_manual_new.php?id=<?php echo $id?>" </a>
                        <div class="num-bg">1</div>
                        <?php
                    }elseif($history['type'] == 3){//更换锅炉
                        ?>
                        <a href="selection_change_old.php?id=<?php echo $id?>" </a>
                        <div class="num-bg">1</div>
                        <?php
                    }
                    ?>
                </div>
                <span class="step-name">选型</span>
            </div>
            <div class="step-line"></div>
            <div class="step-list">
                <div class="step-num " id="selection_two">
                    <?php
                    if($history['type'] == 1) {//智能选型
                        ?>
                        <div class="num-bg" ><a href="selection_make_price.php?id=<?php echo $id ?>&&isUpdate=1" style="color: white">2</a>
                        </div>
                        <?php
                    }elseif($history['type'] == 2){//手动选型
                        ?>
                        <div class="num-bg"><a href="selection_make_price.php?id=<?php echo $id?>&&isUpdate=1" style="color: white">2</a>
                        </div>
                        <?php
                    } elseif($history['type'] == 3){//更换锅炉
                        ?>
                        <div class="num-bg"><a href="selection_make_price.php?id=<?php echo $id?>&&isUpdate=1" style="color: white">2</a>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <span class="step-name">报价</span>
            </div>
            <div class="step-line"></div>
            <div class="step-list">
                <div class="step-num ">
                    <div class="num-bg">3</div>
                </div>
                <span class="step-name">方案</span>
            </div>
        </div>
    </div>

</div>

<div class="GLXX2_main1" style="margin-left:100px;margin-top: 30px">
    <p class="first">请选择选型方案模板</p>
    <ul >
        <?php
        foreach ($rows as $item){
            echo '<li id="demo" class="GLXXmain_7"  value="'.$item["id"].'"><a>'.$item["name"].'</a></li>';
        }
        ?>
        <li onclick="userDefined()"  class="GLXXmain_7 GLXXmain_check" ><a id="userDefined">自定义模板</a></li>
    </ul>
    <!--<script>
        document.getElementById("userDefined").style.backgroundColor=" #04A6FE";
        document.getElementById("userDefined").style.borderRadius="10px";
        document.getElementById("userDefined").style.color="white"
    </script>-->
</div><!--方案模板1-->
<div class="GLXX2_main2" style="margin-left:100px">
    <p class="first">请选择选型方案参数</p>
    <ul>

        <li class="add1"   value="companyIntroduction"><a>企业简介</a></li>
        <li class="companyCompetency"   value="companyCompetency"><a>企业资质</a></li>
        <li class="factoryIntroduction"   value="factoryIntroduction"><a>工厂介绍</a></li>
        <li class="factoryCompetency"   value="factoryCompetency"><a>工厂资质</a></li>
        <li class="quotePlan"   value="quotePlan"><a>报价方案</a></li>
        <li class="productIntroduction"   value="productIntroduction"><a>产品介绍</a></li>
        <li class="productParameter"   value="productParameter"><a>产品参数</a></li>
        <li class="userVenderList"   value="boilerUserList"><a>厂家用户名单</a></li>
        <li class="userCompanyList"   value="kangdaUserList"><a>公司用户名单</a></li>
        <li class="add1"   value="afterSaleIntroduction"><a>售后介绍</a></li>
        <li class="afterSaleCompetency"   value="afterSaleCompetency"><a>售后资质</a></li>
        <li class="add1"   value="otherParameter"><a>其他</a></li>
    </ul>
    <script>
        $('.add1').click(function(){
            var infoValue = $(this).attr("value");
            layer.open({
                type: 2,
                title: false,
                shadeClose: true,
                shade: 0.3,
                area: ['800px', '600px'],
                content: 'selection_parameter_info.php?infoValue='+infoValue
                //content: $('#TC1')
            });
        });
        $('.userVenderList').click(function(){
            layer.open({
                type: 2,
                title: false,
                shadeClose: true,
                shade: 0.3,
                area: ['900px', '600px'],
                content: 'selection_user_list_page.php?userInfo=vender'
                //content: $('#TC1')
            });
        });
        $('.userCompanyList').click(function(){
            layer.open({
                type: 2,
                title: false,
                shadeClose: true,
                shade: 0.3,
                area: ['900px', '600px'],
                content: 'selection_user_list_page.php?userInfo=company'
                //content: $('#TC1')
            });
        });

        //企业资质--直接插入企业资质tpl_content_id
        $('.companyCompetency').click(function(){
            var companyCount = 0;
            var companyIntroContentId = null;
            var companyComprtencyId = null;
            var companyName = "";

            //获取富文本框中的内容
            var oEditor = window.CKEDITOR.instances.content;
            var text = oEditor.getData();
            //分割-获取标记码数组
            var textArray = text.split("##");
            for(var i=0;i<textArray.length;i++){
                //识别 标记码（数组中可能存在非“%xxxx%”格式）
                if(textArray[i].match(/^%/) && textArray[i].match(/%$/)){
                    var content = (textArray[i].split("%"))[1];
                    //分割-获取标记码中的键-值
                    var key = (content.split("_"))[0];
                    var value = (content.split("_"))[1];
                    var name = (content.split("_"))[2];
                    //识别 企业简介
                    if(key == "companyIntroduction" ){
                        companyCount++;
                        if(companyCount >= 2){
                            if(companyId != value){
                                layer.alert("企业简介数量不可超过1个！",{icon:5});
                                return false;
                            }
                        }
                        companyIntroContentId = value;
                        companyName = name;
                    }
                }
            }
            if(companyCount < 1){
                layer.alert("请先选择一个企业简介！",{icon:5});
                return false;
            }
            $.ajax({
                type        : 'POST',
                data        : {
                    tplContentId:companyIntroContentId,
                    attrId:"<?php echo Case_tplcontent::COMPANY_COMPETENCY_ATTRID?>"
                },
                dataType : 'json',
                url : 'selection_do.php?act=getOtherTplcontentIdByTplId',
                success : function(data){
                    var code = data.code;
                    var msg  = data.msg;
                    var rs = data.data;
                    switch(code){
                        case 1:
                            companyComprtencyId = rs;
                            var code = "##%companyCompetency_"+companyComprtencyId+"_"+companyName+"%##";
                            //向富文本框中添加内容
                            var oEditor = window.CKEDITOR.instances.content;
                            oEditor.insertHtml(code);
                            break;
                        default:
                            layer.alert(msg, {icon: 5});
                    }
                }
            });
        });

        //工厂介绍--直接插入对应的tpl_content_id
        $('.factoryIntroduction').click(function(){
            var code = "";
            var vender_intro_ids = "<?php echo $vender_intro_ids_str; ?>";
            var vender_intro_arr = vender_intro_ids.split(",");
            if(vender_intro_ids != "0" && vender_intro_ids != "" && vender_intro_arr.length > 0){
                for (var i=0;i< vender_intro_arr.length;i++){
                    var vender_intro_split_arr = vender_intro_arr[i].split("_");
                    var vender_intro_id = vender_intro_split_arr[0];
                    var vender_name = vender_intro_split_arr[1];
                    if(i > 0){
                        code += ",";
                    }
                    code += "##%factoryIntroduction_"+vender_intro_id+"_"+vender_name+"%##";
                }
            }else{
                layer.alert("您选的锅炉工厂信息在后台找不到，请检查！",{icon:5});
                return false;
            }

            //向富文本框中添加内容
            var oEditor = window.CKEDITOR.instances.content;
            oEditor.insertHtml(code);
        });

        //工厂资质--直接插入对应的tplcontentId
        $('.factoryCompetency').click(function(){
            var venderIntroCount = 0;
            var vender_competency_ids = "<?php echo $vender_competency_ids_str; ?>";
            var vender_competency_arr = vender_competency_ids.split(",");
            //获取富文本框中的内容
            var oEditor = window.CKEDITOR.instances.content;
            var text = oEditor.getData();
            //分割-获取标记码数组
            var textArray = text.split("##");
            for(var i=0;i<textArray.length;i++){
                //识别 标记码（数组中可能存在非“%xxxx%”格式）
                if(textArray[i].match(/^%/) && textArray[i].match(/%$/)){
                    var content = (textArray[i].split("%"))[1];
                    //分割-获取标记码中的键-值
                    var key = (content.split("_"))[0];
                    //识别 企业简介
                    if(key == "factoryIntroduction" ){
                        venderIntroCount++;
                    }
                }
            }
            if(venderIntroCount < 1){
                layer.alert("请先填写工厂介绍！",{icon:5});
                return false;
            }

            var code = "";
            if(vender_competency_ids != "0" && vender_competency_ids != "" && vender_competency_arr.length > 0){
                for (var i=0;i< vender_competency_arr.length;i++){
                    var vender_competency_split_arr = vender_competency_arr[i].split("_");
                    var vender_competency_id = vender_competency_split_arr[0];
                    var vender_name = vender_competency_split_arr[1];
                    if(i > 0){
                        code += ",";
                    }
                    code += "##%factoryCompetency_"+vender_competency_id+"_"+vender_name+"%##";
                }
            }else{
                layer.alert("您选的锅炉工厂信息在后台找不到，请检查！",{icon:5});
                return false;
            }

            //向富文本框中添加内容
            var oEditor = window.CKEDITOR.instances.content;
            oEditor.insertHtml(code);

            // if(venderCompetency_id > 0 ){
            //     code = "##%factoryIntroduction_"+venderCompetency_id+"%##";
            // }else{
            //     layer.alert("您选的锅炉工厂信息在后台找不到，请检查！",{icon:5});
            //     return false;
            // }
            //
            // //向富文本框中添加内容
            // var oEditor = window.CKEDITOR.instances.content;
            // oEditor.insertHtml(code);
        });

        //报价方案--直接插入history_id
        $('.quotePlan').click(function(){
            var code = "##%quotePlan_<?php echo $id;?>%##";
            //向富文本框中添加内容
            var oEditor = window.CKEDITOR.instances.content;
            oEditor.insertHtml(code);
        });

        //产品简介--直接插入history_id
        $('.productIntroduction').click(function(){
            //var guoluVersionString = "<?php echo $guoluVersionString; ?>";
            var code = "##%productIntroduction_<?php echo $id;?>_<?php echo $guoluVersionString; ?>%##";
            //向富文本框中添加内容
            var oEditor = window.CKEDITOR.instances.content;
            oEditor.insertHtml(code);
        });

        //产品参数--直接插入history_id
        $('.productParameter').click(function(){
            var code = "##%productParameter_<?php echo $id;?>_<?php echo $guoluVersionString; ?>%##";
            //向富文本框中添加内容
            var oEditor = window.CKEDITOR.instances.content;
            oEditor.insertHtml(code);
        });

        //售后资质--直接插入对应售后资质的tplContent_id
        $('.afterSaleCompetency').click(function(){
            var afterSaleCount = 0;
            var afterSaleIntroId = null;
            var afterSaleCompetencyId = null;
            var afterSaleName = "";


            //获取富文本框中的内容
            var oEditor = window.CKEDITOR.instances.content;
            var text = oEditor.getData();
            //分割-获取标记码数组
            var textArray = text.split("##");
            for(var i=0;i<textArray.length;i++){
                //识别 标记码（数组中可能存在非“%xxxx%”格式）
                if(textArray[i].match(/^%/) && textArray[i].match(/%$/)){
                    var content = (textArray[i].split("%"))[1];
                    //分割-获取标记码中的键-值
                    var key = (content.split("_"))[0];
                    var value = (content.split("_"))[1];
                    var name = (content.split("_"))[2];
                    //识别 企业简介
                    if(key == "afterSaleIntroduction" ){
                        afterSaleCount++;
                        if(afterSaleCount >= 2){
                            if(afterSaleId != value){
                                layer.alert("售后介绍数量不可超过1个！",{icon:5});
                                return false;
                            }

                        }
                        afterSaleIntroId = value;
                        afterSaleName = name;
                    }
                }
            }
            if(afterSaleCount < 1){
                layer.alert("请先选择一个售后介绍！",{icon:5});
                return false;
            }
            $.ajax({
                type        : 'POST',
                data        : {
                    tplContentId:afterSaleIntroId,
                    attrId:"<?php echo Case_tplcontent::AFTERSALE_COMPETENCY_ATTTRID?>"
                },
                dataType : 'json',
                url : 'selection_do.php?act=getOtherTplcontentIdByTplId',
                success : function(data){
                    var code = data.code;
                    var msg  = data.msg;
                    var rs = data.data;
                    switch(code){
                        case 1:
                            afterSaleCompetencyId = rs;
                            var code = "##%afterSaleCompetency_"+afterSaleCompetencyId+"_"+afterSaleName+"%##";
                            //向富文本框中添加内容
                            var oEditor = window.CKEDITOR.instances.content;
                            oEditor.insertHtml(code);
                            break;
                        default:
                            layer.alert(msg, {icon: 5});
                    }
                }
            });
        });


        $('#demo').click(function(){
            var demoId = $(this).val();
            //清空读文本编辑器
            var oEditor = CKEDITOR.instances.content;
            oEditor.setData('');

            $.ajax({
                type        : 'POST',
                data        : {
                    id:demoId
                },
                dataType : 'json',
                url : 'selection_do.php?act=getTplContent',
                success : function(data){
                    var code = data.code;
                    var msg  = data.msg;
                    switch(code){
                        case 1:
                            //在富文本框中添加内容
                            var oEditor = CKEDITOR.instances.content;
                            oEditor.insertHtml(msg);
                            break;
                        default:
                            layer.alert(msg, {icon: 5});
                    }
                }
            });
        });

    </script>

</div><!--方案模板2-->
<div style="margin-top:40px;margin-left:30%;width:600px">
    <textarea id="content" style="padding:5px;width:100%;height:70px; " name="content" cols=200  class="ckeditor" >
        <?php
        if(!empty($plan_content)){
            echo HTMLDecode($plan_content);
        }
        ?>
    </textarea>
    <!--    <span class="warn-inline" id = "s_content">*</span>-->
</div>
<div class="footer" style="display: flex !important;justify-content: space-around !important;padding-top: 90px !important;margin-bottom: 90px !important;">
    <button class="GLXXmain_17" type="button"  id="btn_submit_ahead" value="上一步" style="margin-left: 0px !important;">
        <a href="selection_make_price.php?id=<?php echo $id ?>&isUpdate=1" style="color: white">上一步</a>
    </button>
    <button class="GLXXmain_17" type="button"  id="btn_submit_next" value="生成方案">生成方案</button>
</div>
<!--下一步-->
<!--<div class="GLXX2_main4">-->
<!--    <input type="button" value="生成方案" class="last"   />-->
<!--</div>-->
<script>

    //生成方案--输入方案名称
    $('#btn_submit_next').click(function(){
        var oEditor = CKEDITOR.instances.content;
        var editinfo = oEditor.getData();
        if(null == editinfo || editinfo == ""){
            layer.alert("模板信息为空！");
            return false;
        }
        layer.open({
            type: 1,
            title: "生成方案",
            shadeClose: true,
            shade: 0.3,
            area: ['500px', '340px'],
            content: $('#TC3')
        });
    });
</script>
<div id="TC2">
    <p>
        生成方案成功！</br>
        如果需要打印，请去方案池。
    </p>
    <button id="confirm2" onclick="toSelection()">继续选型</button>
    <button id="cancel2" onclick="toPlanPool()">去方案池</button>
</div>
<div id="TC2_2">
    <p>
        生成方案成功！</br>
        请到我的项目-选型方案查看。
    </p>
    <button id="confirm2" onclick="toProjectSelection()">确定</button>
    <button id="cancel2" onclick="toCancel()">取消</button>
</div>

<!--弹窗2-->
<div id="TC3">
    <br/>
    <p id="text1">
        请输入选型方案名称：
    </p>
    <p id="text2">
        注意：该名称即最终打印的选型方案文件名。
    </p>
    <div class="name1">
        <input id="planName" type="text" placeholder="请输入选型方案名称" style="width: 346px;height: 54px;position: relative;left:77px;font-family: PingFangSC-Regular;
            font-size: 16px;color: #92969C;"
            <?php
            if(!empty($front_plan_id)  && !empty($front_plan)){
                echo 'value="'.$front_plan["name"].'"';
            }
            ?>
        />
    </div>
    <div class="buttoning2">
        <button id="confirm3">确定</button>
    </div>
</div>
<script>
    $('#confirm3').click(function(){

        //获取方案模板内容
        var oEditor = CKEDITOR.instances.content;
        var tplcontent = oEditor.getData();

        //方案名称
        var planName = $("#planName").val();
        if(null == planName || planName == "" ){
            layer.alert("请输入方案名称！");
            return false;
        }

        var old_plan_id = "<?php echo $front_plan_id;?>";
        if(null != old_plan_id && old_plan_id != ""){
            //修改选型方案
            $.ajax({
                type: 'POST',
                data: {
                    id: old_plan_id,  //方案ID
                    name: planName,
                    tplcontent:tplcontent
                },
                dataType: 'json',
                url: 'selection_do.php?act=update_plan_front',
                success: function (data) {

                    var code = data.code;
                    var msg = data.msg;
                    var project_id = "<?php echo $project_id;?>";
                    switch (code) {
                        case 1:
                            if(project_id > 0){
                                layer.open({
                                    type: 1,
                                    title:false,
                                    shadeClose: true,
                                    shade: 0.3,
                                    area: ['500px', '320px'],
                                    content: $('#TC2_2')
                                });
                            }else{
                                layer.open({
                                    type: 1,
                                    title:false,
                                    shadeClose: true,
                                    shade: 0.3,
                                    area: ['500px', '320px'],
                                    content: $('#TC2')
                                });
                            }
                            break;
                        default:
                            layer.alert(msg, {icon: 5});
                    }
                }
            });
        }else{
            var historyId = "<?php echo $id; ?>";
            //添加选型方案
            $.ajax({
                type: 'POST',
                data: {
                    history_id: historyId,  //选型历史ID
                    name: planName,
                    tplcontent:tplcontent
                },
                dataType: 'json',
                url: 'selection_do.php?act=add_plan_front',
                success: function (data) {

                    var code = data.code;
                    var msg = data.msg;
                    var project_id = "<?php echo $project_id;?>";
                    switch (code) {
                        case 1:
                            $.ajax({
                                type: 'POST',
                                data: {
                                    id: historyId,  //选型历史ID
                                },
                                dataType: 'json',
                                url: 'selection_plan_one_do.php?act=addpricelog',
                                success: function (data) {

                                    var code = data.code;
                                    var msg = data.msg;
                                    switch (code) {
                                        case 1:
                                            if(project_id > 0){
                                                layer.open({
                                                    type: 1,
                                                    title:false,
                                                    shadeClose: true,
                                                    shade: 0.3,
                                                    area: ['500px', '320px'],
                                                    content: $('#TC2_2')
                                                });
                                            }else{
                                                layer.open({
                                                    type: 1,
                                                    title:false,
                                                    shadeClose: true,
                                                    shade: 0.3,
                                                    area: ['500px', '320px'],
                                                    content: $('#TC2')
                                                });
                                            }
                                            break;
                                        default:
                                            layer.alert(msg, {icon: 5});
                                    }
                                }
                            });
                            break;
                        default:
                            layer.alert(msg, {icon: 5});
                    }
                }
            });
        }
    });
</script>
<!--弹窗3-->


</body>
</html>

