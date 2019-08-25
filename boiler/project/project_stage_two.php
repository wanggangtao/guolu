<?php
/**
 * 项目第二阶段 project_stage_two.php
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
$LEFT_TAB_NVA = "two";
$TOP_FLAG = 'myproject';

if(isset($_GET['id']))
    $id = safeCheck($_GET['id']);
else
    die();

$projectinfo = Project::getInfoById($id) ;
if(empty($projectinfo)){
    echo '非法操作！';
    die();
}
if($projectinfo['user'] != $USERId) {
    echo '没有权限操作！';
    die();
}
$tag=isset($_GET['tag'])?safeCheck($_GET['tag']):0;
$project_two = Project_two::getInfoByProjectId($id);
if(empty($project_two)){
    $project_two = Project_two::Init();
}
$project_linkman = Project_linkman::getInfoByPtId($id);
$project_client_framework = Project_client_framework::getInfoByPtId($id);
//树结构数据填充
$ALL_UNIT = array();
if($project_client_framework){
    foreach ($project_client_framework as $thisFrame){
        $thisRow = array();
        $thisRow['id'] = $thisFrame['id'];
        $thisRow['name'] = $thisFrame['name'];
        $thisRow['pId'] = $thisFrame['pid'];
        if($thisFrame['level'] == 1)
            $thisRow['open'] = true;

        $ALL_UNIT[] = $thisRow;
    }
}else{
    $attrs = array(
        "pt_id"=>$id,
        "name"=>'公司组织架构',
        "pid"=>0,
        "level"=>1,
        "addtime"=>time()
    );
    $cfid = Project_client_framework::add($attrs);
    $thisRow = array();
    $thisRow['id'] = $cfid;
    $thisRow['name'] = '公司组织架构';
    $thisRow['pId'] = 0;
    $thisRow['open'] = true;
    $ALL_UNIT[] = $thisRow;
}
$json = json_encode_cn($ALL_UNIT);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>项目管理-<?php echo $projectinfo['name'];?></title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/newtree.css">
    <link rel="stylesheet" href="css/main_two.css">
    <link rel="stylesheet" href="css/newlayui.css">
    <link rel="stylesheet" href="layui/css/layui.css">
    <script type="text/javascript" src="js/jquery-1.4.4.min.js"></script>
    <script type="text/javascript" src="layer/layer.js"></script>
    <script type="text/javascript" src="js/myprogram.js"></script>
    <link rel="stylesheet" href="ztree/css/zTreeStyle/zTreeStyle.css" type="text/css">
    <script type="text/javascript" src="ztree/js/jquery.ztree.core.js"></script>
    <script type="text/javascript" src="ztree/js/jquery.ztree.excheck.js"></script>
    <script type="text/javascript" src="ztree/js/jquery.ztree.exedit.js"></script>
    <script type="text/javascript">
        $(function(){
            $("body").on("click", ".middleDiv_three", function(){
                $(this).find('.newdivcheck').prop('checked',true);
                $(this).siblings().removeClass('middleDiv_three_check');
                $(this).addClass('middleDiv_three_check');
            });
            $("body").on("blur", ".linkphone", function(){
                var linkphone = $(this).val();
                var reg = /^[1][3,4,5,7,8][0-9]{9}$/;
                // if (!(reg.test(linkphone))) {
                //     layer.alert('请输入正确的11位手机号码！', {icon: 5});
                //     return false;
                // }
            });
            $('#project_sbumit').click(function(){
                var length = $(".middleDiv_three").length;
                var all_name = all_department = all_position = all_phone = all_duty = all_isim = '';
                var isIm = false;
                var regphone = /^[1][3,4,5,7,8][0-9]{9}$/;
                for(i=0;i<length;i++){
                    var thisE = $(".middleDiv_three").eq(i);
                    var name = thisE.find('.linkname').val();
                    var department = thisE.find('.linkdepartment').val();
                    var position = thisE.find('.linkposition').val();
                    var phone = thisE.find('.linkphone').val();
                    var duty = thisE.find('.linkduty').val();
                    if(name == '' || department == '' || position == '' || phone == '' || duty == ''){
                        layer.msg('联系人所有选项均不能为空');
                        return false;
                    }
//                    if (!(regphone.test(phone))) {
//                        layer.msg('联系人'+ (i+1)+'联系方式输入有误,请输入正确的11位手机号码！');
//                        return false;
//                    }
                    if(thisE.hasClass('middleDiv_three_check')){
                        isIm =  true;
                        all_isim = '1||'+all_isim;
                    }
                    else
                        all_isim = '0||'+all_isim;

                    all_name = name+'||'+all_name;
                    all_department = department+'||'+all_department;
                    all_position = position+'||'+all_position;
                    all_phone = phone+'||'+all_phone;
                    all_duty = duty+'||'+all_duty;
                }
                if(!isIm){
                    layer.msg('请选择某位联系人为重要负责人');
                    return false;
                }
                $(this).unbind('click');
                $.ajax({
                    type        : 'POST',
                    data        : {
                        id : <?php echo $project_two['id'];?>,
                        project_id : <?php echo $id;?>,
                        all_isim : all_isim,
                        all_phone : all_phone,
                        all_name  : all_name,
                        all_department  : all_department,
                        all_position  : all_position,
                        all_duty : all_duty
                    },
                    dataType :    'json',
                    url :         'project_do.php?act=project_two_save',
                    success :     function(data){
                        var code = data.code;
                        var msg  = data.msg;
                        switch(code){
                            case 1:
                                $.ajax({
                                    type        : 'POST',
                                    data        : {
                                        id : <?php echo $project_two['id'];?>,
                                        project_id : <?php echo $id;?>
                                    },
                                    dataType :    'json',
                                    url :         'project_do.php?act=project_two_submit',
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

            $('#project_save').click(function(){
                var length = $(".middleDiv_three").length;
                var all_name = all_department = all_position = all_phone = all_duty = all_isim = '';
                var isIm = false;
                for(i=0;i<length;i++){
                    var thisE = $(".middleDiv_three").eq(i);
                    var name = thisE.find('.linkname').val();
                    var department = thisE.find('.linkdepartment').val();
                    var position = thisE.find('.linkposition').val();
                    var phone = thisE.find('.linkphone').val();
                    var duty = thisE.find('.linkduty').val();
                    if(name == '' || department == '' || position == '' || phone == '' || duty == ''){
                        layer.msg('联系人所有选项均不能为空');
                        return false;
                    }

                    if(thisE.hasClass('middleDiv_three_check')){
                        isIm =  true;
                        all_isim = '1||'+all_isim;
                    }
                    else
                        all_isim = '0||'+all_isim;

                    all_name = name+'||'+all_name;
                    all_department = department+'||'+all_department;
                    all_position = position+'||'+all_position;
                    all_phone = phone+'||'+all_phone;
                    all_duty = duty+'||'+all_duty;
                }
                if(!isIm){
                    layer.msg('请选择某位联系人为重要负责人');
                    return false;
                }
                $(this).unbind('click');
                $.ajax({
                    type        : 'POST',
                    data        : {
                        id : <?php echo $project_two['id'];?>,
                        project_id : <?php echo $id;?>,
                        all_isim : all_isim,
                        all_phone : all_phone,
                        all_name  : all_name,
                        all_department  : all_department,
                        all_position  : all_position,
                        all_duty : all_duty
                    },
                    dataType :    'json',
                    url :         'project_do.php?act=project_two_save',
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
            $('#record').click(function(){
                location.href = 'project_two_record.php?id='+'<?php echo $id;?>'+'&tag='+'<?php echo $tag;?>';
            });
        });
    </script>
</head>
<body class="body_1">
<div >
    <?php include('top.inc.php');?>
            <?php include('project_top.inc.php');?>

    <div class="manageHRWJCont">
        <?php include('project_tab.inc.php');?>
        <div class="manageHRWJCont_middle">
            <?php include('project_left.inc.php');?>
            <div class="manageHRWJCont_middle_middle">
                <div class="middleDiv_all">
                    <div class="middleDiv_one">
                        <span></span>
                        <div class="manageHRWJCont_middle_right">
                            <?php if(($projectinfo['two_status'] != 2 && $projectinfo['two_status'] != 3 && ($projectinfo['one_status'] == 2 || $projectinfo['one_status'] == 3) && $projectinfo['stop_flag'] != 1 && $projectinfo['level'] >= 1)){ ?>
                                <button class="submit" id="project_sbumit">提交</button>
                            <?php } ?>
                            <button id="record">修改记录</button>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <?php
                    if($project_linkman){
                        foreach ($project_linkman as $thisLinkman){
                            $css = '';
                            $check = '';
                            if($thisLinkman['isimportant'] == 1){
                                $css = 'middleDiv_three_check';
                                $check = 'checked="checked"';
                            }
                            echo '
                                <div class="middleDiv_three '.$css.'">
                                   <div><p><img class="must_reactImg" src="images/must_react.png" alt="">联系人</p><input class="linkname" type="text" placeholder="联系人" value="'.$thisLinkman['name'].'"></div>
                                   <div><p><img  class="must_reactImg" src="images/must_react.png" alt="">部门</p><input class="linkdepartment" type="text" placeholder="联系人部门" value="'.$thisLinkman['department'].'"></div>
                                   <div><p><img class="must_reactImg" src="images/must_react.png" alt="">职位</p><input class="linkposition" type="text" placeholder="联系人职位" value="'.$thisLinkman['position'].'"></div>
                                   <div><p><img class="must_reactImg" src="images/must_react.png" alt="">联系方式</p><input class="linkphone" type="number" placeholder="联系人联系方式" value="'.$thisLinkman['phone'].'"></div>
                                   <div><p><img class="must_reactImg" src="images/must_react.png" alt="">主要负责事项</p><textarea class="linkduty" cols="30" rows="10"placeholder="联系人主要负责事项">'.$thisLinkman['duty'].'</textarea></div>
                                   <input class="newdivcheck" type="radio" name="1" '.$check.'>
                                 </div>
                            ';
                        }
                    }else{
                        echo '
                            <div class="middleDiv_three middleDiv_three_check">
                               <div><p><img class="must_reactImg" src="images/must_react.png" alt="">联系人</p><input class="linkname" type="text" placeholder="联系人" value=""></div>
                               <div> <p><img class="must_reactImg" src="images/must_react.png" alt="">部门</p><input class="linkdepartment" type="text" placeholder="联系人部门" value=""></div>
                               <div><p><img class="must_reactImg" src="images/must_react.png" alt="">职位</p><input class="linkposition" type="text" placeholder="联系人职位" value=""></div>
                               <div><p><img class="must_reactImg" src="images/must_react.png" alt="">联系方式</p><input class="linkphone" type="number" placeholder="联系人联系方式" value=""></div>
                               <div><p><img class="must_reactImg" src="images/must_react.png" alt="">主要负责事项</p><textarea class="linkduty" cols="30" rows="10" placeholder="联系人主要负责事项"></textarea></div>
                               <input class="newdivcheck" type="radio" name="1" checked="checked">
                             </div>
                        ';
                    }
                    ?>
                </div>
                <div class="middleDiv_four">
                    <p class="add_people">+添加联系人</p>
                    <span style="margin-top:20px;">注：在联系人中标注一位重要负责人，蓝色边框表示为重要负责人</span>
                </div>
                <div class="middleDiv_five" style="margin-top:20px;">
                    <p class="p1">公司组织架构</p>
                    <ul id="treeDemo" class="ztree"></ul>
                </div>
                <div class="middleDiv_two">
                    <?php if($projectinfo['two_status'] != 2 && $projectinfo['stop_flag'] != 1){ ?>
                        <button id="project_save">保存</button>
                    <?php } ?>
                </div>
                <div class="clear"></div>
            </div>
            <div class="clear"></div>
        </div>
        <div class="clear"></div>
    </div>
      <SCRIPT type="text/javascript">
          var setting = {
              view: {
                  addHoverDom: addHoverDom,
                  removeHoverDom: removeHoverDom,
                  selectedMulti: false
              },
              edit: {
                  enable: true,
                  editNameSelectAll: true
              },
              data: {
                  simpleData: {
                      enable: true
                  }
              },
              callback: {
                  beforeRemove: beforeRemove,
                  beforeRename: beforeRename,
                  onRemove: onRemove,
                  onRename: onRename
              }
          };

          var zNodes = <?php echo $json;?>;
          var className = "dark";

          function beforeRemove(treeId, treeNode) {
              className = (className === "dark" ? "":"dark");
              var zTree = $.fn.zTree.getZTreeObj("treeDemo");
              zTree.selectNode(treeNode);
              return confirm("确认删除[" + treeNode.name + "]吗？删除后本节点的子节点将都被删除！！！");
          }
          function onRemove(e, treeId, treeNode) {
              $.get('./framework_do.php?act=del&id='+treeNode.id);
          }
          function beforeRename(treeId, treeNode, newName, isCancel) {
              className = (className === "dark" ? "":"dark");
              if (newName.length == 0) {
                  setTimeout(function() {
                      var zTree = $.fn.zTree.getZTreeObj("treeDemo");
                      zTree.cancelEditName();
                      layer.msg("节点名称不能为空.");
                  }, 0);
                  return false;
              }
              return true;
          }
          function onRename(e, treeId, treeNode, isCancel) {
              $.get('./framework_do.php?act=edit&id='+treeNode.id+'&name='+treeNode.name,function (data) {
                  if(data >= 0){
                      layer.msg('修改成功');
                  }else{
                      layer.msg('修改失败');
                  }
              });
          }
          function addHoverDom(treeId, treeNode) {
              var sObj = $("#" + treeNode.tId + "_span");
              if (treeNode.editNameFlag || $("#addBtn_"+treeNode.tId).length>0) return;
              var addStr = "<span class='button add' id='addBtn_" + treeNode.tId
                  + "' title='add node' onfocus='this.blur();'></span>";
              sObj.after(addStr);
              var btn = $("#addBtn_"+treeNode.tId);
              if (btn) btn.bind("click", function(){
                  var zTree = $.fn.zTree.getZTreeObj("treeDemo");
                  var name='请命名';
                  $.get('./framework_do.php?act=add&ppid=<?php echo $id;?>&pid='+treeNode.id+'&name='+name,function (data) {
                      if(data > 0){
                          layer.msg('添加节点成功,请命名');
                          var newID = data; //获取新添加的节点Id
                          zTree.addNodes(treeNode, {id:newID, pId:treeNode.id, name:name}); //页面上添加节点
                          var node = zTree.getNodeByParam("id", newID, null); //根据新的id找到新添加的节点
                          zTree.selectNode(node); //让新添加的节点处于选中状态
                      }else{
                          layer.msg('添加失败');
                      }
                  });
              });
          };
          function removeHoverDom(treeId, treeNode) {
              $("#addBtn_"+treeNode.tId).unbind().remove();
          };
          function selectAll() {
              var zTree = $.fn.zTree.getZTreeObj("treeDemo");
              zTree.setting.edit.editNameSelectAll =  $("#selectAll").attr("checked");
          }

          $(document).ready(function(){
              $.fn.zTree.init($("#treeDemo"), setting, zNodes);
              $("#selectAll").bind("click", selectAll);
          });
      </SCRIPT>
      <style type="text/css">
          .ztree li span.button.add {margin-left:2px; margin-right: -1px; background-position:-144px 0; vertical-align:top; *vertical-align:middle}
      </style>

</body>
</html>