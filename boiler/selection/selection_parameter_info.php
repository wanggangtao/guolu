<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/5
 * Time: 14:32
 */

require_once('web_init.php');
require_once('usercheck.php');

$infoValue = safeCheck($_GET["infoValue"],0);
$rows = null;
switch ($infoValue){
    case "companyIntroduction":
        $rows  = Case_tpl::getListByAttrid($attrid = Case_tpl::COMPANY_ATTRID, null, null, $count = 0);
        break;
//    case "companyCompetency":
//        $rows = Case_tpl::getListByAttrid($attrid = Case_tpl::COMPANY_ATTRID, null, null, $count = 0);
//        break;
//    case "factoryIntroduction":
//        $rows = Case_tpl::getListByAttrid($attrid = Case_tpl::VENDER_ATTRID, null, null, $count = 0);
//        break;
//    case "factoryCompetency":
//        $rows = Case_tpl::getListByAttrid($attrid = Case_tpl::VENDER_ATTRID, null, null, $count = 0);
//        break;
//    case "boilerUserList":
//        $rows  = Case_tpl::getListByAttrid($attrid = Case_tpl::BOILER_USER_ATTRID, null, null, $count = 0);
//        break;
//    case "kangdaUserList":
//        $rows = Case_tpl::getListByAttrid($attrid = Case_tpl::KANGDA_USER_ATTRID, null, null, $count = 0);
//        break;
    case "afterSaleIntroduction":
        $rows = Case_tpl::getListByAttrid($attrid = Case_tpl::AFTERSALE_ATTRID, null, null, $count = 0);
        break;
//    case "afterSaleCompetency":
//        $rows = Case_tpl::getListByAttrid($attrid = Case_tpl::AFTERSALE_ATTRID, null, null, $count = 0);
//        break;
    case "otherParameter":
        $rows = Case_tpl::getListByAttrid($attrid = Case_tpl::OTHER_ATTRID, null, null, $count = 0);
        break;

}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
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
    <script type="text/javascript">
        function addInfo(){
            //按钮样式变动
            document.getElementById("confirm1").style.backgroundColor="#04A6FE";
            document.getElementById("confirm1").style.color="white";
            document.getElementById("cancel1").style.backgroundColor="white";
            document.getElementById("cancel1").style.color="#04A6FE";

                //------------允许多选的情况----------------------
                // var chk_value =[];
                // $('input[name="itemInfo"]:checked').each(function(){
                //     chk_value.push($(this).val());
                // });
                // //alert(chk_value);return false;//获取的值
                // if(chk_value.length == 0){
                //     layer.alert("请选择要添加的选项！");
                //     return false;
                // }else{
                //     for (var i=0;i<chk_value.length;i++)
                //     {
                //         //向富文本框中添加内容
                //         var oEditor = window.parent.CKEDITOR.instances.content;
                //         var oldData = oEditor.getData();
                //         if(oldData != ""){
                //             oldData +=",";
                //         }
                //         if(i!= 0){
                //             oEditor.insertHtml(",");
                //         }
                //         oEditor.insertHtml(chk_value[i]);
                //         //oEditor.setData(oldData+chk_value.shift() );
                //     }
                //     //关闭当前页面
                //     var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
                //     parent.layer.close(index); //再执行关闭
                // }

            //---------------2018-12-23 修改：只允许单选---------------
            var checkedValue = $('input[name="itemInfo"]:checked').val();    //所选项value值
            if(null == checkedValue || checkedValue == ""){
                layer.alert("请选择要添加的项！");
            }else{
                //向富文本框中添加内容
                var oEditor = window.parent.CKEDITOR.instances.content;
                oEditor.insertHtml(checkedValue);
            }

            //关闭当前页面
            var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
            parent.layer.close(index); //再执行关闭
        }
        //全选
        // function allParamChecked(){
        //     var nodeList = document.getElementsByName("itemInfo");
        //     for(var i = 0 ;i < nodeList.length;i++){
        //         if(document.getElementById("allCheckBtn").checked == false){
        //             nodeList[i].checked = false;
        //         }else{
        //             nodeList[i].checked = true;
        //         }
        //     }
        // }
        //取消操作
        function cancel(){
            //按钮样式变动
            document.getElementById("confirm1").style.backgroundColor="white";
            document.getElementById("confirm1").style.color="#04A6FE";
            document.getElementById("cancel1").style.backgroundColor="#04A6FE";
            document.getElementById("cancel1").style.color="white";

            //关闭当前页面
            var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
            parent.layer.close(index); //再执行关闭
        }
    </script>
    <style>
        .checkbox{
            background-image:url('images/未选中按钮.png');
            -webkit-appearance: none;
            background-size:16px 16px;
            display: inline-block;
            width: 16px;
            height: 16px;
            background-repeat:no-repeat;
            position: relative;
            outline: none;
        }
        .checkbox:checked{
            background:url('images/选中按钮.png');
            background-repeat:no-repeat;
        }
    </style>
</head>
<body>
        <div  style="height: 500px">
<!--            <div id="TC1_main1">-->
<!--                <div id="all">-->
<!--                    <input type="checkbox" value="全选" id="allCheckBtn" class="checkbox" onclick="allParamChecked()" /><span>全选</span>-->
<!--                </div>-->
<!--            </div>-->
            <div id="line"></div>
            <div id="company">
                <?php
                if(!empty($rows)) {
                    //拼接标记码  ##%【列项名称】_【列项内容ID】%##
                    foreach ($rows as $item) {
                        $code = '##%';
                        switch ($infoValue) {
                            //企业简介--标记码中的值=企业在tplContent中的tplcontent_id
                            case "companyIntroduction":
                                $con_content = Table_case_tplcontent::getInfoByAttridAndTplid(Case_tplcontent::COMPANY_INTRO_ATTRID, $item['id']);
                                $code .= 'companyIntroduction_' . $con_content[0]['id'] .'_'.$item['name']. '%##';
                                break;
//                          case "companyCompetency":
//                                $inl_content = Table_case_tplcontent::getInfoByAttridandtplid(Case_tplcontent::COMPANY_COMPETENCY_ATTRID,$item['id']);
//                                $code.="companyCompetency_".$inl_content[0]['id'].'%##';
//                                break;
//                            case "factoryIntroduction":
//                                $con_content = Table_case_tplcontent::getInfoByAttridandtplid(Case_tplcontent::VENDER_INTRO_ATTRID,$item['id']);
//                                $code.="factoryIntroduction_".$con_content[0]['id'].'%##';
//                                break;
//                            case "factoryCompetency":
//                                $inl_content = Table_case_tplcontent::getInfoByAttridandtplid(Case_tplcontent::VENDER_COMPETENCY_ATTRID,$item['id']);
//                                $code.="factoryCompetency_".$inl_content[0]['id'].'%##';
//                                break;
                            //厂家用户名单（原-锅炉用户名单）
//                            case "boilerUserList":
//                                //$con_content = Table_case_tplcontent::getInfoByAttridandtplid(20,$item['id']);
//                                //$code.="boilerUserList_".$con_content[0]['id'].'%##';
//                                $code.="boilerUserList_".$item['id'].'%##';
//                                break;
                            //公司用户名单（原-康达用户名单）
//                            case "kangdaUserList":
////                                $inl_content = Table_case_tplcontent::getInfoByAttridandtplid(23,$item['id']);
////                                $code.="kangdaUserList_".$inl_content[0]['id'].'%##';
//                                $code.="kangdaUserList_".$item['id'].'%##';
//                                break;
                            //插入对应售后介绍项的tplContent_id
                            case "afterSaleIntroduction":
                                $con_content = Table_case_tplcontent::getInfoByAttridAndTplid(Case_tplcontent::AFTERSALE_INTRO_ATTRID, $item['id']);
                                $code .= "afterSaleIntroduction_" . $con_content[0]['id'].'_'.$item['name'] . '%##';
                                break;
//                            case "afterSaleCompetency":
//                                $inl_content = Table_case_tplcontent::getInfoByAttridandtplid(Case_tplcontent::AFTERSALE_COMPETENCY_ATTTRID,$item['id']);
//                                $code.="afterSaleCompetency_".$inl_content[0]['id'].'%##';
//                                break;
                            //插入对应其他项的tplContent_id
                            case "otherParameter":
                                $inl_content = Table_case_tplcontent::getInfoByTplid($item['id']);
                                $code .= "otherParameter_" . $inl_content[0]['id'] .'_'.$item['name']. '%##';
                                break;
                        }

                        echo '
                           <div id="company1" >
                                <input type="radio" name="itemInfo"  id="checkbox1" value="' . $code . '"  class="checkbox"/><span>' . $item['name'] . '</span>
                           </div> 
                       ';
                    }
                }else{
                    echo '<h2>抱歉！您在选型过程中没有选择该项！</h2>';

                }
                ?>

            </div>
            <div id="buttoning1">
                <button id="confirm1" onclick="addInfo()">确定</button>
                <button id="cancel1" onclick="cancel()">取消</button>
            </div>
        </div>


</body>
</html>