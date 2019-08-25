<?php
/**
 * 项目第四阶段 project_stage_four_check.php
 *
 * @version       v0.01
 * @create time   2018/07/01
 * @update time   2018/07/01
 * @author        dlk
 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
 */
require_once('web_init.php');
require_once('usercheck.php');

$TOP_TAB_NVA = "stage";
$LEFT_TAB_NVA = 'four';
$TOP_FLAG = "projectreview";

$id = isset($_GET['id'])?safeCheck($_GET['id']):'0';
$tag=isset($_GET['tag'])?safeCheck($_GET['tag']):0;
$projectinfo = Project::getInfoById($id);
$project_four = Project_four::getInfoByProjectId($id);
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
if(empty($project_four)){
    $project_four = Project_four::Init();
}
$project_bid_company = Project_bid_company::getInfoByPfId($id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>项目管理-项目审批四级</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/main_two.css">
    <link rel="stylesheet" href="css/newlayui.css">
    <link rel="stylesheet" href="layui/css/layui.css">
    <link rel="stylesheet" href="layui/css/layui.css">
    <script type="text/javascript" src="js/jquery-1.4.4.min.js"></script>
    <script type="text/javascript" src="js/myprogram.js"></script>
    <script type="text/javascript" src="layer/layer.js"></script>
    <script type="text/javascript">
        $(function(){
            $('#record_four').click(function(){
                location.href = 'project_four_record_check.php?id='+'<?php echo $id;?>'+'&tag='+'<?php echo $tag;?>';
            });

            $('.project_cid_ac_file').click(function(){
                var filepath = $(this).parent().find('input[name="cid_file_path"]').val();
                var wind = window.open(
                    '<?php echo $HTTP_PATH; ?>'+'project/file_preview.php?' +
                    'filepath='+ filepath +
                    '&username='+ '<?php echo $USERINFO['name'];?>',
                    '文件预览','height=500,width=611,scrollbars=yes,status=yes');
                //settimeout(function(){wind.document.title = "标题";},6000);
            });

            $('.project_bid_ac_file').click(function(){
                var filepath = $(this).parent().find('input[name="bid_file_path"]').val();
                window.open(
                    '<?php echo $HTTP_PATH; ?>'+'project/file_preview.php?' +
                    'filepath='+ filepath +
                    '&username='+ '<?php echo $USERINFO['name'];?>',
                    '文件预览','height=500,width=611,scrollbars=yes,status=yes');
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

            <div class="middleDiv_nine">

                <div class="middleDiv_nine_left2">

                    <span>招投标</span></div>
                <div class="middleDiv_nine_right">

                    <button id="record_four">修改记录</button></div>
                <div class="clear"></div>
            </div>

            <div class="middleDiv_nine">

                <div class="middleDiv_nine_left2">

                    <p><img class="must_reactImg_one" src="images/must_react.png" alt="">招标公司</p></div>
                <div class="middleDiv_nine_right">
                    <p><?php echo $project_four['project_cid_company'];?></p>
                </div>
                <div class="clear"></div>
            </div>

            <div class="middleDiv_nine">
                <div class="  middleDiv_nine_left2"> <p><img class="must_reactImg_one" src="images/must_react.png" alt="">负责人</p></div>
                <div class="middleDiv_nine_right">
                    <p><?php echo $project_four['project_cid_linkman'];?></p>
                </div>
                <div class="clear"></div>
            </div>
            <div class="middleDiv_nine">
                <div class="  middleDiv_nine_left2 "> <p><img class="must_reactImg_one" src="images/must_react.png" alt="">联系方式</p></div>
                <div class="middleDiv_nine_right">
                    <p><?php echo $project_four['project_cid_linkphone'];?></p>
                </div>
                <div class="clear"></div>
            </div>

            <div class="middleDiv_nine">
                <div class="  middleDiv_nine_left2"> <p>招标文件</p></div>
                <div class="clear"></div>
            </div>
            <?php
            if($project_four['project_cid_ac_file']){
                $cfileurlarr = explode('|', $project_four['project_cid_file']);
                $cfilenamearr = explode('|', $project_four['project_cid_ac_file']);
                for ($i= 0; $i < count($cfileurlarr); $i++){
                    echo '<div class="middleDiv_nine">
                            <div class="middleDiv_one_div"><a class="project_cid_ac_file">'.$cfilenamearr[$i].'</a><input name="cid_file_path" type="hidden" value="'.$cfileurlarr[$i].'"/></div>
                        </div>';
                }
            }
            ?>

            <div class="middleDiv_nine">
                <div class="  middleDiv_nine_left2"> <p>投标文件</p></div>
                <div class="clear"></div>
            </div>

            <?php
            if($project_four['project_bid_ac_file']){
                $bfileurlarr = explode('|', $project_four['project_bid_file']);
                $bfilenamearr = explode('|', $project_four['project_bid_ac_file']);
                for ($j= 0; $j < count($bfileurlarr); $j++){
                    echo '<div class="middleDiv_nine">
                            <div class="middleDiv_one_div"><a class="project_bid_ac_file">'.$bfilenamearr[$j].'</a><input name="bid_file_path" type="hidden" value="'.$bfileurlarr[$j].'"/></div>
                        </div>';
                }
            }
            ?>
            <div class="middleDiv_ten">
                <div class="top">
                    参与投标公司情况
                </div>
                <?php
                $companystr = "";
                if($project_bid_company){
                    $i = 0;
                    foreach ($project_bid_company as $thiscompany){
                        $i ++;
                        if($thiscompany['isimportant'] == 1){
                            $companystr =  $thiscompany['name'];
                        }
                        ?>
                        <div class="middleDiv_tenCont">
                            <p>公司<?php echo $i;?></p>
                            <p><img class="must_reactImg" src="images/must_react.png" alt="">投标公司名称<span><?php echo $thiscompany['name'];?></span></p>
                            <p><img class="must_reactImg" src="images/must_react.png" alt="">投标现场价格<span><?php echo $thiscompany['price'];?>  元</span></p>
                            <p><img class="must_reactImg" src="images/must_react.png" alt="">投标品牌<span><?php echo $thiscompany['brand'];?></span></p>

                        </div>
                        <?php
                    }
                }
                ?>
                <div class="clear"></div>
            </div>


            <div class="middleDiv_nine">
                <div class="  middleDiv_nine_left2 middleDiv_nine_left3"> <p><img class="must_reactImg_one" src="images/must_react.png" alt="">中标公司</p></div>
                <div class="middleDiv_nine_right middleDiv_nine_right3">
                    <p><?php echo $companystr;?></p>
                </div>
                <div class="clear"></div>
            </div>


            <div class="middleDiv_nine">
                <div class="  middleDiv_nine_left2"> <p>招投标情况</p></div>
                <div class="middleDiv_nine_right">
                    <div><?php echo $project_four['project_cbid_situation'];?></div>
                </div>
                <div class="clear"></div>
            </div>
        </div>




        <div class="clear"></div>
    </div>
</div>
</body>
</html>