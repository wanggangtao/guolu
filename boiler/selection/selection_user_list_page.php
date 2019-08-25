<?php
/**
 * Created by PhpStorm.
 * User: sxm
 * Date: 2018/12/24
 * Time: 16:08
 */

require_once('web_init.php');
require_once('usercheck.php');

$userInfo = null;
$attr_id = null;   //查询用户名单时的tpl_attrid
if(isset($_GET['userInfo'])){
    $userInfo = $_GET['userInfo'];
    switch($userInfo){
        case "vender":
            $attr_id = Case_tpl::BOILER_USER_ATTRID;
            break;
        case "company":
            $attr_id = Case_tpl::KANGDA_USER_ATTRID;
            break;
        default:
    }
}else{
    $attr_id = isset($_GET['attrId'])?$_GET['attrId']:0;
}

$user_name = isset($_GET['user_name'])?$_GET['user_name']:"";
$selected_guolu_vender = isset($_GET['guolu_vender'])?$_GET['guolu_vender']:null;
$selected_user_type = isset($_GET['user_type'])?$_GET['user_type']:null;
$params = array();


if(isset($attr_id)){
    $params['tpl_attrid'] = $attr_id;
    $params['tpl_name'] = $user_name;
    $params['tpl_vender'] = $selected_guolu_vender;
    $params['tpl_usertype'] = $selected_user_type;
}

//获取用户名单
$rows   = Case_tpl::getListBySelect($params);
$rows_diff = array();


//历史勾选Id串
$typical_string = isset($_GET['t_str'])?$_GET['t_str']:"";   //勾选典型用户
$common_string = isset($_GET['c_str'])?$_GET['c_str']:"";    //勾选普通用户
//$both_tc_string = isset($_GET['tc_str'])?$_GET['tc_str']:"";   //两者均勾选

$typical_array = array();   //勾选典型用户数组
$common_array = array();    //勾选普通用户数组
//$both_tc_array = array();   //两者均勾选数组
$checked_array = array();   //所有勾选用户数组
$intersect_array = array();   //勾选用户与本次查询数组交集
$diff_array = array();     //只获取用户标记的，且未出现再本次查询中的用户Id
if(!empty($typical_string)){
    $typical_array = explode(",",$typical_string) ;
}
if(!empty($common_string)){
    $common_array = explode(",",$common_string) ;
}
//if(!empty($both_tc_string)){
//    $both_tc_array = $both_tc_string.explode(",");
//}

//$checked_array = array_merge($typical_array,$common_array,$both_tc_array);
$checked_array = array_unique(array_merge($typical_array,$common_array));
$intersect_array = array_intersect($checked_array,array_keys($rows));
$diff_array = array_diff($checked_array,$intersect_array);  //只获取用户标记的，且未出现再本次查询中的用户Id


foreach ($diff_array as $key => $value){

    $row = Case_tpl::getInfoById($value);
    $rows_diff[$key] = $row[0];
}
//print_r(count($typical_array)."------typical_array---");
//print_r(count($common_array)."----common_array---");
//print_r(count($checked_array)."----checked_array---");
//print_r(count($intersect_array)."----intersect_array---");
//print_r(count($diff_array)."----diff_array---");
//print_r(count($rows)."----------rows---");
//print_r(count($rows_diff)."--rows_diff--");

//print_r($common_array[0]."--common_array[0]--");
//foreach (array_keys($rows) as $key){
//    print_r($key."-------");
//}






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
    <link rel="stylesheet" href="css/form.css" />
    <link rel="stylesheet" href="css/newlayui.css">
    <link rel="stylesheet" href="layui/css/layui.css">
    <script type="text/javascript" src="js/jquery-1.4.4.min.js"></script>
    <script type="text/javascript" src="layer/layer.js"></script>
    <script type="text/javascript" src="ckeditor/ckeditor.js"></script>
    <script type="text/javascript">
        function addInfo(){
            var attrId = "<?php echo $attr_id ?>";
            var boiler_attr = "<?php echo Case_tpl::BOILER_USER_ATTRID ?>";
            var kangda_attr = "<?php echo Case_tpl::KANGDA_USER_ATTRID ?>";
            //按钮样式变动
            document.getElementById("confirm1").style.backgroundColor="#04A6FE";
            document.getElementById("confirm1").style.color="white";
            document.getElementById("cancel1").style.backgroundColor="white";
            document.getElementById("cancel1").style.color="#04A6FE";

            var typical_chk_value =[];
            $('input[name="typical_item"]:checked').each(function(){
                //typical_chk_value.push($(this).val());
                typical_chk_value.push($(this).attr("code"));
            });
            var common_chk_value =[];
            $('input[name="common_item"]:checked').each(function(){
                //common_chk_value.push($(this).val());
                common_chk_value.push($(this).attr("code"));
            });
            //alert(typical_chk_value);return false;//获取的值
            if(typical_chk_value.length < 1 && common_chk_value.length < 1){
                layer.alert("请勾选要添加的选项！");
                return false;
            }else{
                if(typical_chk_value.length != 0){
                    //添加典型用户
                    for (var i=0;i<typical_chk_value.length;i++)
                    {
                        //向富文本框中添加内容
                        var oEditor = window.parent.CKEDITOR.instances.content;
                        var oldData = oEditor.getData();
                        if(oldData != ""){
                            //oldData +=",";
                            //oEditor.insertHtml(",");
                        }
                        if(i!= 0){
                            oEditor.insertHtml(",");
                        }

                        //判断用户类型，添加
                        if(attrId == boiler_attr){
                            oEditor.insertHtml("##%boilerUserListTypical_"+typical_chk_value[i]+"%##");
                        }else if(attrId == kangda_attr){
                            oEditor.insertHtml("##%kangdaUserListTypical_"+typical_chk_value[i]+"%##");
                        }
                    }
                }
                //添加普通用户
                if(common_chk_value.length != 0){
                    //添加普通用户
                    for (var i=0;i<common_chk_value.length;i++)
                    {
                        //向富文本框中添加内容
                        var oEditor = window.parent.CKEDITOR.instances.content;
                        var oldData = oEditor.getData();
                        if(oldData != ""){
                            //oldData +=",";
                            //oEditor.insertHtml(",");
                        }
                        if(i!= 0){
                            oEditor.insertHtml(",");
                        }

                        //判断用户类型，添加
                        if(attrId == boiler_attr){
                            oEditor.insertHtml("##%boilerUserListCommon_"+common_chk_value[i]+"%##");
                        }else if(attrId == kangda_attr){
                            oEditor.insertHtml("##%kangdaUserListCommon_"+common_chk_value[i]+"%##");
                        }
                    }
                }

                //关闭当前页面
                var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
                parent.layer.close(index); //再执行关闭
            }
        }

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
    <script type="text/javascript" >
        //典型用户全选
        //$('#typical_user_checkall').click(function(){
        function allTypicalChecked() {
            var nodeList = document.getElementsByName("typical_item");
            for(var i = 0 ;i < nodeList.length;i++){
                if(document.getElementById("typical_user_checkall").checked == false){
                    nodeList[i].checked = false;
                }else{
                    nodeList[i].checked = true;
                }
            }
        }
        //普通用户全选
        //$('#common_user_checkall').click(function(){
        function allCommonChecked() {
            var nodeList = document.getElementsByName("common_item");
            for(var i = 0 ;i < nodeList.length;i++){
                if(document.getElementById("common_user_checkall").checked == false){
                    nodeList[i].checked = false;
                }else{
                    nodeList[i].checked = true;
                }
            }
        }
        //查询用户列表
        function searchUserList(){

            var attrId = "<?php echo $attr_id ?>";
            //获取用户姓名
            var userNameChecked = $('#userName').val();
            //获取锅炉厂家
            var guoluVender = $('#sel_vender').val();
            //获取用户类型
            var sel_usertype = $('#sel_usertype').val();

            var typical_array = new Array();
            var common_array = new Array();

            //获取已经勾选的典型用户
            $('input[name="typical_item"]:checked').each(function(){
                typical_array.push($(this).val());
            });
            //获取已经勾选的普通用户
            $('input[name="common_item"]:checked').each(function(){
                common_array.push($(this).val());
            });

            var typical_string = typical_array.join(",");
            var common_string = common_array.join(",");

            // //交集
            // var intersect_array= Array.intersect(typical_array,common_array);
            // var intersect_string = intersect_array.join(",");
            // //典型用户差集
            // var typical_diff_array = typical_array.minus(intersect_array);
            // var typical_diff_string = typical_diff_array.join(",");
            // //普通用户差集
            // var common_diff_array = common_array.minus(intersect_array);
            // var common_diff_string = common_diff_array.join(",");


            location.href="selection_user_list_page.php?attrId="+attrId+"&user_name="+userNameChecked+"&guolu_vender="+guoluVender+"&user_type="+sel_usertype+"&t_str="+typical_string+"&c_str="+common_string;


        }
        ///集合取交集
        Array.intersect = function () {
            var result = new Array();
            var obj = {};
            for (var i = 0; i < arguments.length; i++) {
                for (var j = 0; j < arguments[i].length; j++) {
                    var str = arguments[i][j];
                    if (!obj[str]) {
                        obj[str] = 1;
                    }
                    else {
                        obj[str]++;
                        if (obj[str] == arguments.length)
                        {
                            result.push(str);
                        }
                    }//end else
                }//end for j
            }//end for i
            return result;
        }

        //并集
        Array.union = function () {
            var arr = new Array();
            var obj = {};
            for (var i = 0; i < arguments.length; i++) {
                for (var j = 0; j < arguments[i].length; j++)
                {
                    var str=arguments[i][j];
                    if (!obj[str])
                    {
                        obj[str] = 1;
                        arr.push(str);
                    }
                }//end for j
            }//end for i
            return arr;
        }
        //集合去掉重复
        Array.prototype.uniquelize = function () {
            var tmp = {},
                ret = [];
            for (var i = 0, j = this.length; i < j; i++) {
                if (!tmp[this[i]]) {
                    tmp[this[i]] = 1;
                    ret.push(this[i]);
                }
            }

            return ret;
        }
        //2个集合的差集 在arr不存在
        Array.prototype.minus = function (arr) {
            var result = new Array();
            var obj = {};
            for (var i = 0; i < arr.length; i++) {
                obj[arr[i]] = 1;
            }
            for (var j = 0; j < this.length; j++) {
                if (!obj[this[j]])
                {
                    obj[this[j]] = 1;
                    result.push(this[j]);
                }
            }
            return result;
        };

    </script>

</head>
<body>
        <div  class="firstDivForm">
            <div id="line"></div>
            <div id="company" style="width: 900px; float:left;">
                <div style=" float:left; width: 200px;  padding-top: 23px; padding-left: 50px" >
                    用户名称
                    <input type="text" style="width:100px" placeholder="" id="userName" name="userName" value="<?php echo $user_name; ?>" id="user_name"/>
                </div>
                <div id="company1" >
                    锅炉厂家
                    <select id="sel_vender" class="select-handle">
                        <option value="0">全部</option>
                        <?php
                        //获取所有锅炉厂家  value= dict_id
                        $list = Dict::getListByParentid(1);
                        if($list)
                            foreach($list as $thisValue){
                                $selected = '';
                                if($thisValue['id'] == $selected_guolu_vender)
                                    $selected = 'selected';
                                echo '<option value="'.$thisValue['id'].'" '.$selected.'>'.$thisValue['name'].'</option>';
                            }
                        ?>
                    </select>
                </div>
                <div id="company1" >
                    用户类型
                    <select id="sel_usertype" class="select-handle">
                        <option value="0">全部</option>
                        <?php
                        //获取所有用户类型   value = projecjcase_type_id
                        $list = Projectcase_type::getList();
                        if($list)
                            foreach($list as $listValue){
                                $selected = '';
                                if($listValue['id'] == $selected_user_type)
                                    $selected = 'selected';
                                echo '<option value="'.$listValue['id'].'" '.$selected.'>'.$listValue['name'].'</option>';
                            }
                        ?>
                    </select>
                </div>
                <div style=" float:left; width: 100px;  padding-top: 23px; padding-left: 10px"  >
                    <input type="button" class="btn-handle" href="javascript:" id="search"   onclick="searchUserList()" value="确定">
                </div>

                <br/>
            </div>
            <div class="user_table_div">
                <table class="user_table_tb">
                    <tr class="user_table_tr">
                        <td>厂家用户名称</td>
                        <td>典型用户<br/><input id="typical_user_checkall" type="checkbox" onclick="allTypicalChecked()"/>全选</td>
                        <td>普通用户<br/><input id="common_user_checkall" type="checkbox" onclick="allCommonChecked()"/>全选</td>
                    </tr>
                    <?php
                    if(!empty($rows_diff)){
                        foreach ($rows_diff as $row_diff){
                            ?>
                            <tr>
                                <td class="center"><?php echo $row_diff['name'] ?></td>
                                <td class="center">
                                    <input type="checkbox" class="typical_checked"  name="typical_item" value="<?php echo $row_diff['id'] ?>"  code="<?php echo $row_diff['id'].'_'.$row_diff['name'] ?>"
                                        <?php
                                            if(in_array($row_diff['id'],$typical_array)){
                                                echo 'checked="checked"';
                                            }
                                        ?>
                                    >
                                </td>
                                <td class="center">
                                    <input type="checkbox" class="common_checked" name="common_item" value="<?php echo $row_diff['id'] ?>" code="<?php echo $row_diff['id'].'_'.$row_diff['name'] ?>"
                                        <?php
                                        if(in_array($row_diff['id'],$common_array)){
                                            echo 'checked="checked"';
                                        }
                                        ?>
                                    >
                                </td>
                            </tr>
                    <?php
                        }
                    }
                    if(!empty($rows)){
                        foreach ($rows as $row){?>
                                        <tr>
                                            <td class="center"><?php echo $row['name'] ?></td>
                                            <td class="center">
                                                <?php
                                                    if($row['userstate'] == array_keys($ARRAY_userstate_type)[0]){  ?>
                                                        <input type="checkbox" class="typical_checked" name="typical_item" value="<?php echo $row['id'] ?>"  code="<?php echo $row['id'].'_'.$row['name'] ?>"
                                                            <?php
                                                            if(in_array($row['id'],$typical_array)){
                                                                echo 'checked="checked"';
                                                            }
                                                            ?>
                                                        >
                                                <?php    }
                                                ?>
                                            </td>
                                            <td class="center">
                                                <input type="checkbox" class="common_checked" name="common_item" value="<?php echo $row['id']  ?>"   code="<?php echo $row['id'].'_'.$row['name'] ?>"
                                                    <?php
                                                    if(in_array($row['id'],$common_array)){
                                                        echo 'checked="checked"';
                                                    }
                                                    ?>
                                                >
                                            </td>
                                        </tr>
                        <?php }
                    }
                    ?>

                </table>
            </div>

            <div id="buttoning1">
                <button id="confirm1" onclick="addInfo()">确定</button>
                <button id="cancel1" onclick="cancel()">取消</button>
            </div>

        </div>



</body>
</html>