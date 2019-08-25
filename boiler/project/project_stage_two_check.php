<?php
/**
 * 项目第二阶段 project_stage_two_check.php
 *
 * @version       v0.01
 * @create time   2018/06/28
 * @update time   2018/06/28
 * @author        dlk
 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
 */
require_once('web_init.php');
require_once('usercheck.php');

$TOP_TAB_NVA = "stage";
$LEFT_TAB_NVA = 'two';
$TOP_FLAG = "projectreview";

$id = isset($_GET['id'])?safeCheck($_GET['id']):'0';
$tag=isset($_GET['tag'])?safeCheck($_GET['tag']):0;

$projectinfo = Project::getInfoById($id);
$project_two = Project_two::getInfoByProjectId($id);
if (empty($projectinfo)) {
    echo '非法操作！';
    die();
} else {
    $userinfo = User::getInfoById($projectinfo['user']);
    if (!($projectinfo['user'] == $USERId || $userinfo['parent'] == $USERId)) {
        echo '没有权限操作！';
        die();
    }
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
    <title>项目管理-项目审批二级</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/main_two.css">
    <link rel="stylesheet" href="css/newlayui.css">
    <link rel="stylesheet" href="css/newtree.css">
    <link rel="stylesheet" href="layui/css/layui.css">
    <link rel="stylesheet" href="layui/css/layui.css">
    <script type="text/javascript" src="js/jquery-1.4.4.min.js"></script>
    <script type="text/javascript" src="js/myprogram.js"></script>
    <script type="text/javascript" src="layer/layer.js"></script>
    <link rel="stylesheet" href="ztree/css/zTreeStyle/zTreeStyle.css" type="text/css">
    <script type="text/javascript" src="ztree/js/jquery.ztree.core.js"></script>
    <script type="text/javascript" src="ztree/js/jquery.ztree.excheck.js"></script>
    <script type="text/javascript" src="ztree/js/jquery.ztree.exedit.js"></script>
    <script type="text/javascript">
        $(function(){
            $('#record_two').click(function(){
                location.href = 'project_two_record_check.php?id='+'<?php echo $id;?>'+'&tag='+'<?php echo $tag;?>';
            });
        });

    </script>
</head>
<body class="body_1">
    <?php include('top.inc.php');?>
        <?php include('project_check_top.inc.php');?>
    <div class="manageHRWJCont">
        <?php include('project_check_tab.inc.php');?>
        <div class="manageHRWJCont_middle">
            <?php include('project_check_left.inc.php');?>
            <div class="manageHRWJCont_middle_middle">

            <?php
                if($project_linkman){
                    $i = 0;
                    foreach ($project_linkman as $thislinkman){
                        $i ++;
                ?>
                <div class="middleDiv_nine">
                    <div class="middleDiv_nine_left">
                        <p><img class="must_reactImg" src="<?php echo $thislinkman['isimportant'] == 1?'images/radio_check.png':'images/must_react.png' ?>" alt="">联系人<?php echo $i;?></p></div>
                    <div class="middleDiv_nine_right">
                        <p><?php echo $thislinkman['name']; ?></p>
                        <?php if($i == 1) { ?><button id="record_two">修改记录</button><?php } ?></div>
                    <div class="clear"></div>
                </div>
                <div class="middleDiv_nine">
                    <div class="  middleDiv_nine_left2"> <p><img class="must_reactImg" src="images/must_react.png" alt="">部门</p></div>
                    <div class="middleDiv_nine_right">
                        <p><?php echo $thislinkman['department']; ?></p>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="middleDiv_nine">
                    <div class="  middleDiv_nine_left2"> <p><img class="must_reactImg" src="images/must_react.png" alt="">职位</p></div>
                    <div class="middleDiv_nine_right">
                        <p><?php echo $thislinkman['position']; ?></p>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="middleDiv_nine">
                    <div class="  middleDiv_nine_left2 "> <p><img class="must_reactImg" src="images/must_react.png" alt="">联系方式</p></div>
                    <div class="middleDiv_nine_right">
                        <p><?php echo $thislinkman['phone']; ?></p>
                    </div>
                    <div class="clear"></div>
                </div>

                <div class="middleDiv_nine">
                    <div class="  middleDiv_nine_left2"> <p><img class="must_reactImg" src="images/must_react.png" alt="">主要负责事项</p></div>
                    <div class="middleDiv_nine_right">
                        <div><?php echo $thislinkman['duty']; ?></div>
                    </div>
                    <div class="clear"></div>
                </div>
                <?php
                    }
                }else{
                    echo '
                <div class="middleDiv_nine">
                    <div class="middleDiv_nine_left"><p><img class="must_reactImg" src="images/must_react.png" alt="">联系人1</p></div>
                    <div class="middleDiv_nine_right">
                        <p></p>
                        <button id="record_two">修改记录</button></div>
                    <div class="clear"></div>
                </div>
                <div class="middleDiv_nine">
                    <div class="  middleDiv_nine_left2"> <p><img class="must_reactImg" src="images/must_react.png" alt="">部门</p></div>
                    <div class="middleDiv_nine_right">
                        <p></p>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="middleDiv_nine">
                    <div class="  middleDiv_nine_left2"> <p><img class="must_reactImg" src="images/must_react.png" alt="">职位</p></div>
                    <div class="middleDiv_nine_right">
                        <p></p>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="middleDiv_nine">
                    <div class="  middleDiv_nine_left2 "> <p><img class="must_reactImg" src="images/must_react.png" alt="">联系方式</p></div>
                    <div class="middleDiv_nine_right">
                        <p></p>
                    </div>
                    <div class="clear"></div>
                </div>

                <div class="middleDiv_nine">
                    <div class="  middleDiv_nine_left2"> <p><img class="must_reactImg" src="images/must_react.png" alt="">主要负责事项</p></div>
                    <div class="middleDiv_nine_right">
                        <div></div>
                    </div>
                    <div class="clear"></div>
                </div>';
                }
                ?>
                <p>注：<img class="must_reactImg" src="images/radio_check.png" alt="">为重要负责人</p>
                <div class="ProjectCheck_bottom">

                    <div>
                        <span>公司组织架构</span>
                        <ul id="treeDemo" class="ztree"></ul>
                    </div>
                </div>

                <div class="clear"></div>
            </div>
            <div class="clear"></div>
        </div>
        <SCRIPT type="text/javascript">
            var setting = {
                view: {
                },
                edit: {
                    editNameSelectAll: true
                },
                data: {
                    simpleData: {
                        enable: true
                    }
                },
                callback: {
                }
            };

            var zNodes = <?php echo $json;?>;

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