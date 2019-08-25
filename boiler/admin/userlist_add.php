<?php
/**
 * Created by PhpStorm.
 * User: tianyi
 * Date: 2018/10/21
 * Time: 20:05
 */
require_once('admin_init.php');
require_once('admincheck.php');

$attrid = safeCheck($_GET['attrid']);
//通过attrid对应的parentid，找出所有的属性项
$caseattr = Case_attr::getInfoById($attrid);
//print_r($caseattr[0]['parentid']);
//$attrs = Case_attr::getListByPid($caseattr[0]['parentid'],0);
//属性值对应的id值
 $attrs = Case_attr::getListByPidandLevel(3,$caseattr[0]['parentid'],0);
$ids = array();
foreach ($attrs as $l => $attr) {
    array_push($ids, $attr['id']);
}
//print_r($ids);

//这里是列表菜单中固定展示的属性对应的id值，因为属性名称可变，每次拿id去取
$fixedids = array();
if(20 == $attrid){
    $fixedids = array('20');
}elseif(23 == $attrid){
    $fixedids = array('23');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="author" content="芝麻开花 http://www.zhimawork.com" />
    <link rel="stylesheet" href="css/style.css" type="text/css" />
    <link rel="stylesheet" href="css/form.css" type="text/css" />
    <script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="js/layer/layer.js"></script>
    <script type="text/javascript" src="js/common.js"></script>
    <script type="text/javascript" src="ckeditor/ckeditor.js"></script>
    <script type="text/javascript">
        $(function() {
            //编辑器初始化
            var ckeditor = CKEDITOR.replace('other', {
                toolbar: 'Common',
                forcePasteAsPlainText: 'true',//强制纯文本格式粘贴
                filebrowserImageUploadUrl: 'ckeditor_upload.php?type=img',
                filebrowserUploadUrl: 'ckeditor_upload.php?type=file'
            });
            $('#btn_submit_add').click(function () {

                var ids = <?php echo json_encode($ids)?>;
                var fixedids = <?php echo json_encode($fixedids)?>;
                var content = '';
                var temp = '';

                var vender = $('#vender').val();
                var usertype = $('#usertype').val();
                var userstate = $('#userstate').val();
                // alert(userstate);
                // return false;
                var other = ckeditor.getData();
                if(vender == '' || vender == 0){
                    layer.tips('锅炉厂家不能为空', '#vender');
                    return false;
                }
                if(usertype == '' || usertype == 0){
                    layer.tips('用户类型不能为空', '#usertype');
                    return false;
                }
                if(userstate == 0 ){
                    layer.tips('用户状态不能为空', '#userstate');
                    return false;
                }
                if(userstate == 1 && other == '' ){
                        layer.tips('请在富文本框中填写内容，或将“是否是典型用户”选择为否', '#other');
                        return false;
                    }

                var v_con = vender + '_'+ <?php echo 1;?>;
                var u_con = usertype + '_'+ <?php echo 4;?>;
                var s_con = userstate + '_'+ <?php echo 5;?>;


                for(i=0;i<ids.length;i++){
                        temp = $('#attr_'+ids[i]).val();
                        if(temp == ''&&(fixedids.indexOf(ids[i]) != -1)){
                            layer.tips('内容不能为空', '#attr_'+ids[i]);
                            return false;
                        }else {
                            content = content+temp+'_'+ids[i]+',';
                        }
                    }
                    if(content !="") {
                        content=content+v_con+','+u_con+','+s_con;
                    }
                    // alert(vender);
                    // return false;


                $.ajax({
                    type: 'POST',
                    data: {
                        vender:vender,
                        usertype:usertype,
                        userstate:userstate,
                        other:other,
                        content: content
                    },
                    dataType: 'json',
                    url: 'userlist_do.php?act=add',
                    success: function (data) {
                        // alert(data);
                        var code = data.code;
                        var msg = data.msg;
                        switch (code) {
                            case 1:
                                layer.alert(msg, {icon: 6, shade: false}, function (index) {
                                    parent.location.reload();
                                });
                                break;
                            default:
                                layer.alert(msg, {icon: 5});
                        }
                    }
                });
            });
        })
    </script>
</head>
<body>
<div id="formlist">
    <?php
        $html = '';
        foreach ($attrs as $k=>$attr){
                $html .= '<p>
                        <label>' . $attr["name"] . '</label>
                        <input type="text" class="text-input input-length-30" name="attr_' . $attr["id"] . '" id="attr_' . $attr["id"] . '" value=""  />
                        ';
            if(in_array($attr['id'],$fixedids)){
                $html .= '<span class="warn-inline" id="attr_'.$attr["id"].'">* </span>
                    </p>';
            }else{
                $html .= '</p>';
            }
        }
        echo $html;
   ?>
    <p>
        <label>用户类型</label>
        <select name="usertype" class="select-option" id="usertype">
            <option value="0">----请选择----</option>
            <?php
            $list =  Projectcase_type::getList();
            if($list)
                foreach($list as $thisValue){
                    echo '<option value="'.$thisValue['id'].'">'.$thisValue['name'].'</option>';
                }
            ?>
        </select>
        <span class="warn-inline">* </span>
    <p>
        <label>锅炉厂家</label>
        <select name="vender" class="select-option" id="vender">
            <option value="0">----请选择----</option>
            <?php
            $list = Dict::getListByParentid(1);
            if($list)
                foreach($list as $thisValue){
                    echo '<option value="'.$thisValue['id'].'">'.$thisValue['name'].'</option>';
                }
            ?>
        </select>
        <span class="warn-inline">* </span>
    <p>
        <label>是否是典型用户</label>
        <select name="userstate" class="select-option" id="userstate">
            <option value="0">----请选择----</option>
            <?php
           foreach ($ARRAY_userstate_type as $key => $val)
                {
                    echo "<option value='{$key}'>{$val}</option>";
                }
            ?>
        </select>
        <span class="warn-inline">* </span>
    <p>
        <label ></label>
    <div style="margin-top:40px;margin-left:10%">
        <textarea style="padding:5px;width:70%;height:70px;" name="other" cols=200 id="other" value=""></textarea>
    </div>
    </p>
    <p>
        <label>&nbsp;</label>
        <input type="button" id="btn_submit_add" class="btn_submit" style="margin-left:30px;" value="提　交" />
    </p>

</div>
</body>
</html>