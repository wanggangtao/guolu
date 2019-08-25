<?php
/**
 * 项目第四阶段 project_stage_four.php
 *
 * @version       v0.01
 * @create time   2018/06/29
 * @update time   2018/06/29
 * @author        dlk
 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
 */
require_once('web_init.php');
require_once('usercheck.php');

$TOP_TAB_NVA = "stage";
$LEFT_TAB_NVA = "four";
$TOP_FLAG = 'myproject';

if(isset($_GET['id']))
    $id = safeCheck($_GET['id']);
else
    die();
$tag=isset($_GET['tag'])?safeCheck($_GET['tag']):0;
$projectinfo = Project::getInfoById($id) ;
if(empty($projectinfo)){
    echo '非法操作！';
    die();
}
if($projectinfo['user'] != $USERId) {
    echo '没有权限操作！';
    die();
}
$project_four = Project_four::getInfoByProjectId($id);
if(empty($project_four)){
    $project_four = Project_four::Init();
}
$project_bid_company = Project_bid_company::getInfoByPfId($id);
$companynum = 0;
if(count($project_bid_company) > 3){
    $companynum = count($project_bid_company);
}else{
    $companynum = 3;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>项目管理-<?php echo $projectinfo['name'];?></title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/main_two.css">
    <link rel="stylesheet" href="css/newlayui.css">
    <link rel="stylesheet" href="layui/css/layui.css">
    <script type="text/javascript" src="js/jquery-1.4.4.min.js"></script>
    <script type="text/javascript" src="layer/layer.js"></script>
    <script type="text/javascript" src="js/upload.js"></script>
    <script type="text/javascript">
        $(function(){
            $('#record_four').click(function(){
                location.href = 'project_four_record.php?id='+'<?php echo $id;?>'+'&tag='+'<?php echo $tag;?>';
            });

            $('.middleDivOne_popButton').click(function(){
                $('#selectoption').html('');
                var length = $(".middleDiv_five").length;
                for(i=0;i<length;i++){
                    var thisE = $(".middleDiv_five").eq(i);
                    var name = thisE.find('.name').val();
                    $('#selectoption').append('<li class="bidselected">'+name+'</li>');
                }
                $('.middleDiv_one_pop').css('display','block');
            });
            $("body").on("click", ".bidselected", function(){
                $('#success_bid_company').text($(this).text());
                $('.middleDiv_one_pop').css('display','none');
            });
            $('.middleDivOne_closeButton').click(function(){
                $('.middleDiv_one_pop').css('display','none');
            });
            var companynum = <?php echo $companynum;?>;
            $('.add_people').click(function () {
                companynum += 1;
                var htmladd="";
                htmladd +='   <div class="middleDiv_five">';
                htmladd +='  <p class="middleDiv_five_p">公司'+companynum+'</p>';
                htmladd +=' <div class="middleDiv_one">';
                htmladd +=' <div><p><img class="must_reactImg" src="images/must_react.png" alt="">投标公司名称</p></div>';
                htmladd +='<input type="text" class="name" placeholder="竞品公司名称">';
                htmladd +=' </div>';
                htmladd +=' <div class="middleDiv_one">';
                htmladd +='  <div><p><img class="must_reactImg" src="images/must_react.png" alt="">投标现场价格</p></div>';
                htmladd +=' <input type="number" class="price" placeholder="竞品公司报价"><span class="middleDiv_one_span">元</span>';
                htmladd +='</div>';
                htmladd +='<div class="middleDiv_one">';
                htmladd +='<div><p>投标品牌</p></div>';
                htmladd +=' <input type="text" class="brand" placeholder="竞品公司产品品牌">';
                htmladd +='<div class="remove" style="width: 324px;margin-top:10px;margin-bottom:0px;cursor: pointer;"><p style="padding-left: 150px;margin-bottom:0px;color: red;">-删除本公司</p></div>';
                htmladd +='</div>';
                htmladd +='</div>';

                $(".middleDiv_all").append(htmladd);
            });

            $('#project_cid_linkphone').blur(function(){
                var project_cid_linkphone = $('#project_cid_linkphone').val();
                var reg = /^[1][3,4,5,7,8][0-9]{9}$/;
                // if (!(reg.test(project_cid_linkphone))) {
                //     layer.alert('请输入正确的11位手机号码！', {icon: 5});
                //     return false;
                // }
            });
            $("body").on("blur", ".price", function(){
                var price = $(this).val();
                var reg = /^(-?\d+)(\.\d+)?$/;
                if (!(reg.test(price))) {
                    layer.alert('请输入正确数字！', {icon: 5});
                    return false;
                }
            });

            $("body").on("click", ".middleDiv_five", function(){
                $(this).siblings().removeClass('middleDiv_five_check');
                $(this).addClass('middleDiv_five_check');
            });
            $("body").on("click", ".remove", function(){
                $(this).parent().parent().remove();
            });
            $('#project_four_sbumit').click(function(){
                var success_bid_company = $('#success_bid_company').text();
                var length = $(".middleDiv_five").length;
                var all_name = all_price = all_brand = all_isim = '';
                var isIm = false;
                for(i=0;i<length;i++){
                    var thisE = $(".middleDiv_five").eq(i);
                    var name = thisE.find('.name').val();
                    var price = thisE.find('.price').val();
                    var brand = thisE.find('.brand').val();
                    if(name == '' || price == ''){
                        layer.msg('公司内容不能为空');
                        return false;
                    }

                    if(success_bid_company == name){
                        isIm =  true;
                        all_isim = '1||'+all_isim;
                    }
                    else
                        all_isim = '0||'+all_isim;

                    all_name = name+'||'+all_name;
                    all_price = price+'||'+all_price;
                    all_brand = brand+'||'+all_brand;
                }
                if(!isIm){
                    layer.msg('未选择中标公司或者选择中标公司已删除');
                    return false;
                }

                var project_cid_file = '';
                var project_bid_file = '';
                var project_cid_ac_file = '';
                var project_bid_ac_file = '';
                var lengthc = $(".cidfileinfo").length;
                for(i=0;i<lengthc;i++){
                    var thisE = $(".cidfileinfo").eq(i);
                    var pcf = thisE.find('input[name="project_cid_file"]').val();
                    var pcaf = thisE.find('input[name="project_cid_ac_file"]').val();
                    if(pcf != '' && pcaf != ''){
                        project_cid_file += pcf + '|';
                        project_cid_ac_file += pcaf + '|';
                    }
                }
                var lengthb = $(".bidfileinfo").length;
                for(i=0;i<lengthb;i++){
                    var thisE = $(".bidfileinfo").eq(i);
                    var pbf = thisE.find('input[name="project_bid_file"]').val();
                    var pbaf = thisE.find('input[name="project_bid_ac_file"]').val();
                    if(pcf != '' && pcaf != ''){
                        project_bid_file += pbf + '|';
                        project_bid_ac_file += pbaf + '|';
                    }
                }

                var project_cid_company = $('#project_cid_company').val();
                var project_cid_linkman = $('#project_cid_linkman').val();
                var project_cid_linkphone = $('#project_cid_linkphone').val();
                var project_cbid_situation = $('#project_cbid_situation').val();
                if(project_cid_company == ''){
                    layer.msg('招标公司不能为空');
                    return false;
                }
                if(project_cid_linkman == ''){
                    layer.msg('负责人不能为空');
                    return false;
                }
                if(project_cid_linkphone == ''){
                    layer.msg('联系方式不能为空');
                    return false;
                }
                var regphone = /^[1][3,4,5,7,8][0-9]{9}$/;
                // if (!(regphone.test(project_cid_linkphone))) {
                //     layer.msg('联系方式输入有误,请输入正确的11位手机号码！');
                //     return false;
                // }
//                if(project_cbid_situation == ''){
//                    layer.msg('招投标情况不能为空');
//                    return false;
//                }
                /*if(project_cid_file == ''){
                    layer.msg('招标文件不能为空');
                    return false;
                }
                if(project_bid_file == ''){
                    layer.msg('投标文件不能为空');
                    return false;
                }*/

                $(this).unbind('click');
                $.ajax({
                    type        : 'POST',
                    data        : {
                        id : <?php echo $project_four['id'];?>,
                        project_id : <?php echo $id;?>,
                        all_isim : all_isim,
                        all_name  : all_name,
                        all_price  : all_price,
                        all_brand  : all_brand,
                        project_cid_company  : project_cid_company,
                        project_cid_linkman  : project_cid_linkman,
                        project_cid_linkphone  : project_cid_linkphone,
                        project_cbid_situation  : project_cbid_situation,
                        project_cid_file  : project_cid_file,
                        project_bid_file  : project_bid_file,
                        project_cid_ac_file  : project_cid_ac_file,
                        project_bid_ac_file  : project_bid_ac_file
                    },
                    dataType :    'json',
                    url :         'project_do.php?act=project_four_save',
                    success :     function(data){
                        var code = data.code;
                        var msg  = data.msg;
                        switch(code){
                            case 1:
                                $.ajax({
                                    type        : 'POST',
                                    data        : {
                                        id : <?php echo $project_four['id'];?>,
                                        project_id : <?php echo $id;?>
                                    },
                                    dataType :    'json',
                                    url :         'project_do.php?act=project_four_submit',
                                    success :     function(data){
                                        var code = data.code;
                                        var msg  = data.msg;
                                        switch(code){
                                            case 1:
                                                layer.alert(msg, {icon: 6,shade: false}, function(index){
                                                    location.reload();
                                                });
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
            });

            $('#project_four_save').click(function(){
                var project_cid_file = '';
                var project_bid_file = '';
                var project_cid_ac_file = '';
                var project_bid_ac_file = '';
                var lengthc = $(".cidfileinfo").length;
                for(i=0;i<lengthc;i++){
                    var thisE = $(".cidfileinfo").eq(i);
                    var pcf = thisE.find('input[name="project_cid_file"]').val();
                    var pcaf = thisE.find('input[name="project_cid_ac_file"]').val();
                    if(pcf != '' && pcaf != ''){
                        project_cid_file += pcf + '|';
                        project_cid_ac_file += pcaf + '|';
                    }
                }
                var lengthb = $(".bidfileinfo").length;
                for(i=0;i<lengthb;i++){
                    var thisE = $(".bidfileinfo").eq(i);
                    var pbf = thisE.find('input[name="project_bid_file"]').val();
                    var pbaf = thisE.find('input[name="project_bid_ac_file"]').val();
                    if(pcf != '' && pcaf != ''){
                        project_bid_file += pbf + '|';
                        project_bid_ac_file += pbaf + '|';
                    }
                }

                var project_cid_company = $('#project_cid_company').val();
                var project_cid_linkman = $('#project_cid_linkman').val();
                var project_cid_linkphone = $('#project_cid_linkphone').val();
                var project_cbid_situation = $('#project_cbid_situation').val();
                /*if(project_cid_company == ''){
                    layer.msg('招标公司不能为空');
                    return false;
                }
                if(project_cid_linkman == ''){
                    layer.msg('负责人不能为空');
                    return false;
                }
                if(project_cid_linkphone == ''){
                    layer.msg('联系方式不能为空');
                    return false;
                }
                if(project_cbid_situation == ''){
                    layer.msg('招投标情况不能为空');
                    return false;
                }
                if(project_cid_file == ''){
                    layer.msg('招标文件不能为空');
                    return false;
                }
                if(project_bid_file == ''){
                    layer.msg('投标文件不能为空');
                    return false;
                }*/
                var success_bid_company = $('#success_bid_company').text();
                var length = $(".middleDiv_five").length;
                var all_name = all_price = all_brand = all_isim = '';
                var isIm = false;
                for(i=0;i<length;i++){
                    var thisE = $(".middleDiv_five").eq(i);
                    var name = thisE.find('.name').val();
                    var price = thisE.find('.price').val();
                    var brand = thisE.find('.brand').val();
                    if(name == '' || price == '' ){
                        layer.msg('公司内容不能为空');
                        return false;
                    }

                    if(success_bid_company == name){
                        isIm =  true;
                        all_isim = '1||'+all_isim;
                    }
                    else
                        all_isim = '0||'+all_isim;

                    all_name = name+'||'+all_name;
                    all_price = price+'||'+all_price;
                    all_brand = brand+'||'+all_brand;
                }
                /*if(!isIm){
                    layer.msg('请选择某公司为中标公司');
                    return false;
                }*/
                $(this).unbind('click');
                $.ajax({
                    type        : 'POST',
                    data        : {
                        id : <?php echo $project_four['id'];?>,
                        project_id : <?php echo $id;?>,
                        all_isim : all_isim,
                        all_name  : all_name,
                        all_price  : all_price,
                        all_brand  : all_brand,
                        project_cid_company  : project_cid_company,
                        project_cid_linkman  : project_cid_linkman,
                        project_cid_linkphone  : project_cid_linkphone,
                        project_cbid_situation  : project_cbid_situation,
                        project_cid_file  : project_cid_file,
                        project_bid_file  : project_bid_file,
                        project_cid_ac_file  : project_cid_ac_file,
                        project_bid_ac_file  : project_bid_ac_file
                    },
                    dataType :    'json',
                    url :         'project_do.php?act=project_four_save',
                    success :     function(data){
                        var code = data.code;
                        var msg  = data.msg;
                        switch(code){
                            case 1:
                                layer.alert(msg, {icon: 6,shade: false}, function(index){
                                    location.reload();
                                });
                                break;
                            default:
                                layer.alert(msg, {icon: 5});
                        }
                    }
                });
            });
            //上传
            $('#fileinput1').on('change', '#file1', function(){
                var file = $("#file1").val();
                var pos=file.lastIndexOf("\\");
                $(".cidfiles").find('input[name="project_cid_ac_file"]:last').val(file.substring(pos+1));
                ajaxUpload(1);
                return false;
            });
            //上传
            $('#fileinput2').on('change', '#file2', function(){
                var file = $("#file2").val();
                var pos=file.lastIndexOf("\\");
                $(".bidfiles").find('input[name="project_bid_ac_file"]:last').val(file.substring(pos+1));
                ajaxUpload(2);
                return false;
            });

            $('.add_bidfile').click(function () {
                var htmladd="";
                htmladd +='<div class="bidfileinfo">';
                htmladd +=' <input name="project_bid_ac_file" type="text" readonly="readonly" class="text-input" value="" style="height: 35px;width: 400px;">';
                htmladd +='<div class="fileremove" style="width: 324px;margin-top:10px;margin-bottom:0px;cursor: pointer;"><p style="float: left;margin-bottom:0px;color: red;">-删除该文件</p></div>';
                htmladd +='<input name="project_bid_file" type="hidden" readonly="readonly" value="" style="height: 35px;width: 400px;">';
                htmladd +='</div>';

                $(".bidfiles").append(htmladd);
            });
            $('.add_cidfile').click(function () {
                var htmladd="";
                htmladd +='<div class="cidfileinfo">';
                htmladd +=' <input name="project_cid_ac_file" type="text" readonly="readonly" class="text-input" value="" style="height: 35px;width: 400px;">';
                htmladd +='<div class="fileremove" style="width: 324px;margin-top:10px;margin-bottom:0px;cursor: pointer;"><p style="float: left;margin-bottom:0px;color: red;">-删除该文件</p></div>';
                htmladd +='<input name="project_cid_file" type="hidden" readonly="readonly" value="" style="height: 35px;width: 400px;">';
                htmladd +='</div>';

                $(".cidfiles").append(htmladd);
            });


            $("body").on("click", ".fileremove", function(){
                $(this).parent().remove();
            });


            $("body").on("click",".cidfileinfo .text-input",function(){

                var currentFile = $(this).parent().find("input[name='project_cid_file']").val();
                var win = window.open(
                    'file_preview.php?' +
                    'filepath='+ currentFile +
                    '&username='+ '<?php echo $USERINFO['name'];?>',
                    '文件预览','height=500,width=611,scrollbars=yes,status=yes');
                //alert(currentFile);

            });
            $("body").on("click",".bidfileinfo .text-input",function(){

                var currentFile = $(this).parent().find("input[name='project_bid_file']").val();
                var win = window.open(
                    'file_preview.php?' +
                    'filepath='+ currentFile +
                    '&username='+ '<?php echo $USERINFO['name'];?>',
                    '文件预览','height=500,width=611,scrollbars=yes,status=yes');
                //alert(currentFile);

            });
        });
        function ajaxUpload(value){
            var uploadUrl = 'file_upload.php';//处理文件
            var index = layer.load(0, {shade: false});
            $.ajaxFileUpload({
                url           : uploadUrl,
                fileElementId : 'file'+value,
                dataType      : 'json',
                success       : function(data){
                    layer.close(index);
                    var code = data.code;
                    var msg  = data.msg;
                    switch(code){
                        case 1:
                            layer.msg('上传成功');
                            switch(value){
                                case 1:
                                    $(".cidfiles").find('input[name="project_cid_file"]:last').val(msg);
                                    break;
                                case 2:
                                    $(".bidfiles").find('input[name="project_bid_file"]:last').val(msg);
                                    break;
                            }
                            break;
                        default:
                            layer.alert(msg, {icon: 5, title:'信息'});
                    }
                },
                error: function (data, status, e){
                    layer.alert(e);
                }
            })
            return false;
        }
    </script>
    <style>
        .middleDiv_five{
            height:520px;
            vertical-align: middle;
        }
        .middleDiv_five_check{
            margin-top: 0px;
            background: rgba(4,166,254,0.03);
            border: 1px solid #04A6FE;
        }
        .fileinput {
            float: left;
            position: relative;
            overflow: hidden;
            width: 140px;
            height: 40px;
            line-height: 40px;
            background: #04A6FE;
            box-shadow: 0 6px 24px 0 rgba(10,122,182,0.30);
            border-radius: 6px;
            font-family: PingFangSC-Regular;
            font-size: 20px;
            color: #FFFFFF;
            letter-spacing: 0.93px;
            cursor: pointer;
            border: none;
            margin-top: 10px;
            text-align: center;
        }

        .fileinput input {
            position: absolute;
            right: 0;
            top: 0;
            opacity: 0;
        }

        .text-input {
            height: 18px;
            width: 500px;
            border: 1px solid #cccccc;
            border-radius: 3px;
            float: left;
            background-color: #eee;
        }
    </style>
</head>
<body class="body_1">
<?php include('top.inc.php');?>
        <?php include('project_top.inc.php');?>
<div class="manageHRWJCont">
    <?php include('project_tab.inc.php');?>
    <div class="manageHRWJCont_middle">
        <?php include('project_left.inc.php');?>
        <div class="manageHRWJCont_middle_middle">
            <div class="middleDiv_all">
                <div class="middleDiv_one">
                    <span>招投标</span>
                    <div><p><img class="must_reactImg" src="images/must_react.png" alt="">招标公司</p></div>
                    <input type="text" placeholder="招标公司" id="project_cid_company" value="<?php echo $project_four['project_cid_company'];?>">
                    <div class="manageHRWJCont_middle_right">
                        <?php if(($projectinfo['four_status'] != 2 && $projectinfo['four_status'] != 3 && ($projectinfo['three_status'] == 2 || $projectinfo['three_status'] == 3) && $projectinfo['stop_flag'] != 1 && $projectinfo['level'] >= 1)){ ?>
                            <button class="submit" id="project_four_sbumit">提交</button>
                        <?php } ?>
                        <button id="record_four">修改记录</button>
                        <div class="clear"></div>
                    </div>
                </div>
                <div class="middleDiv_one">
                    <div><p><img class="must_reactImg" src="images/must_react.png" alt="">负责人</p></div>
                    <input type="text" placeholder="负责人" id="project_cid_linkman" value="<?php echo $project_four['project_cid_linkman'];?>">
                </div>
                <div class="middleDiv_one">
                    <div><p><img class="must_reactImg" src="images/must_react.png" alt="">联系方式</p></div>
                    <input type="number" placeholder="联系方式" id="project_cid_linkphone" value="<?php echo $project_four['project_cid_linkphone'];?>">
                </div>
                <div class="middleDiv_one" style="overflow: hidden;width: 500px;">
                    <div><p>上传招标文件</p></div>
                    <div class="fileinput" id="fileinput1">上传文件<input type="file" name="file" id="file1"></div>
                    <div class="cidfiles">
                        <?php
                        if($project_four['project_cid_file']){
                            $fileurlarr = explode('|', $project_four['project_cid_file']);
                            $filenamearr = explode('|', $project_four['project_cid_ac_file']);
                            for ($i= 0; $i < count($fileurlarr); $i++){
                                echo '<div class="cidfileinfo">';
                                echo '<input name="project_cid_ac_file" type="text" readonly="readonly" class="text-input" value="'.$filenamearr[$i].'" style="height: 35px;width: 400px;">';
                                if($i != 0){
                                    echo '<div class="fileremove" style="width: 324px;margin-top:10px;margin-bottom:0px;cursor: pointer;"><p style="float: left;margin-bottom:0px;color: red;">-删除该文件</p></div>';
                                }
                                echo '<input name="project_cid_file" type="hidden" readonly="readonly" value="'.$fileurlarr[$i].'" style="height: 35px;width: 400px;">';
                                echo '</div>';
                            }
                        }else{
                            echo '<div class="cidfileinfo">
                                        <input name="project_cid_ac_file" type="text" readonly="readonly" class="text-input" value="" style="height: 35px;width: 400px;">
                                        <input name="project_cid_file" type="hidden" readonly="readonly" value="" style="height: 35px;width: 400px;">
                                      </div>';
                        }
                        ?>
                    </div>
                </div>

                <div class="middleDiv_four">
                    <p class="add_cidfile" style="color: #04A6FE;">+添加文件</p>
                </div>
                <div class="middleDiv_one" style="overflow: hidden;width: 500px;">
                    <div><p>上传投标文件</p></div>
                    <div class="fileinput" id="fileinput2">上传文件<input type="file" name="file" id="file2"></div>
                    <div class="bidfiles">
                        <?php
                        if($project_four['project_bid_file']){
                            $bfileurlarr = explode('|', $project_four['project_bid_file']);
                            $bfilenamearr = explode('|', $project_four['project_bid_ac_file']);
                            for ($i= 0; $i < count($bfileurlarr); $i++){
                                echo '<div class="bidfileinfo">';
                                echo '<input name="project_bid_ac_file" type="text" readonly="readonly" class="text-input" value="'.$bfilenamearr[$i].'" style="height: 35px;width: 400px;">';
                                if($i != 0){
                                    echo '<div class="fileremove" style="width: 324px;margin-top:10px;margin-bottom:0px;cursor: pointer;"><p style="float: left;margin-bottom:0px;color: red;">-删除该文件</p></div>';
                                }
                                echo '<input name="project_bid_file" type="hidden" readonly="readonly" value="'.$bfileurlarr[$i].'" style="height: 35px;width: 400px;">';
                                echo '</div>';
                            }
                        }else{
                            echo '<div class="bidfileinfo">
                                        <input name="project_bid_ac_file" type="text" readonly="readonly" class="text-input" value="" style="height: 35px;width: 400px;">
                                        <input name="project_bid_file" type="hidden" readonly="readonly"  value="" style="height: 35px;width: 400px;">
                                      </div>';
                        }
                        ?>
                    </div>
                </div>

                <div class="middleDiv_four">
                    <p class="add_bidfile" style="color: #04A6FE;">+添加文件</p>
                </div>
                <div class="middleDiv_one" style="margin-bottom: 0px">
                    <span>参与投标公司情况</span>
                </div>
                <?php
                $i = 0;
                $count = empty($project_bid_company)?0:count($project_bid_company);
                $success_bid_company = "";
                if($project_bid_company){
                    foreach ($project_bid_company as $thiscompany){
                        $i ++;
                        $delstr = "";
                        if($i >= 4){
                            $delstr = '<div class="remove" style="width: 324px;margin-top:10px;margin-bottom:0px;cursor: pointer;"><p style="padding-left: 150px;margin-bottom:0px;color: red;">-删除本公司</p></div>';
                        }
                        $css = '';
                        if($thiscompany['isimportant'] == 1){
                            $success_bid_company = $thiscompany['name'];
                            $css = 'middleDiv_five_check';
                        }
                        echo '
                                    <div class="middleDiv_five '.$css.'">
                                        <p class="middleDiv_five_p">公司'.$i.'</p>
                                        <div class="middleDiv_one">
                                            <div><p><img class="must_reactImg" src="images/must_react.png" alt="">投标公司名称</p></div>
                                            <input type="text" class="name" placeholder="竞品公司名称" value="'.$thiscompany['name'].'">
                                        </div>
                                        <div class="middleDiv_one">
                                            <div><p><img class="must_reactImg" src="images/must_react.png" alt="">投标现场价格</p></div>
                                            <input class="price" type="number" placeholder="竞品公司报价" value="'.$thiscompany['price'].'"><span class="middleDiv_one_span">元</span>
                                        </div>
                                        <div class="middleDiv_one">
                                            <div><p>投标品牌</p></div>
                                            <input class="brand" type="text" placeholder="竞品公司产品品牌" value="'.$thiscompany['brand'].'">
                                            '.$delstr.'
                                        </div> 
                                    </div>     
                        ';
                    }
                }

                for ($j=1; $j < 4-$count; $j++){
                    $i ++;
                    echo '
                            <div class="middleDiv_five">
                                <p class="middleDiv_five_p">公司'.$i.'</p>
                                <div class="middleDiv_one">
                                    <div><p><img class="must_reactImg" src="images/must_react.png" alt="">投标公司名称</p></div>
                                    <input class="name" type="text" placeholder="竞品公司名称">
                                </div>
                                <div class="middleDiv_one">
                                    <div><p><img class="must_reactImg" src="images/must_react.png" alt="">投标现场价格</p></div>
                                    <input class="price" type="number" placeholder="竞品公司报价"><span class="middleDiv_one_span">元</span>
                                </div>
                                <div class="middleDiv_one">
                                    <div><p>投标品牌</p></div>
                                    <input  class="brand" type="text" placeholder="竞品公司产品品牌">
                                     <div class="remove" style="width: 324px;margin-top:10px;margin-bottom:0px;cursor: pointer;"></div>
                                </div>
                            </div>
                        ';
                }
                ?>

            </div>
            <div class="middleDiv_ten">
                <p class="add_people">+添加公司</p>
                <!--                <span style="margin-top:20px;">注：在公司中标注一个公司，蓝色边框表示为中标公司</span>-->
            </div>
            <div class="middleDiv_one">
                <div><p>中标公司</p></div>
                <button class="middleDivOne_popButton">选择</button><span class="check_company"><img class="must_reactImg" src="images/must_react.png" alt="">中标公司为：</span><span class="check_company" id="success_bid_company"><?php echo $success_bid_company; ?></span>
                <p class="middleDiv_one_p">招投标情况</p>
                <textarea name="project_cbid_situation" id="project_cbid_situation" cols="30" rows="10"><?php echo HTMLDecode($project_four['project_cbid_situation']);?></textarea>
                <div class="middleDiv_one_pop">
                    <div class="top">
                        <p>选择中标公司</p>
                        <img class="middleDivOne_closeButton" src="images/chahao.png" alt="" >
                    </div>
                    <div class="cont">
                        <ul id="selectoption">
                        </ul>
                    </div>
                </div>
            </div>
            <div class="middleDiv_two">
                <?php if($projectinfo['four_status'] != 2 && $projectinfo['stop_flag'] != 1){ ?>
                    <button id="project_four_save">保存</button>
                <?php } ?>
            </div>
        </div>
        <div class="clear"></div>
    </div>
    <div class="clear"></div>
</div>
</body>
</html>