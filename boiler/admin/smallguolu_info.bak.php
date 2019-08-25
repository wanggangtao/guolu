<?php
/**
 * 锅炉  smallguolu_info.php
 *
 * @version       v0.01
 * @create time   2018/5/28
 * @update time
 * @author        wzp
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

require_once('admin_init.php');
require_once('admincheck.php');

if(!isset($_GET['id']))
    die();

$id = safeCheck($_GET['id']);
$info = Smallguolu::getInfoById($id);
if(empty($info))
    die();

$pInfo = Products::getInfoById($info['proid']);

$deatail_video = $pInfo['detail_video'];
$videoarr  = explode(';',$deatail_video);
$videopath = $videoarr[0];
$placehold = $videoarr[1];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="author" content="芝麻开花 http://www.zhimawork.com" />
    <link rel="stylesheet" href="css/style.css" type="text/css" />
    <link rel="stylesheet" href="css/form.css" type="text/css" />
    <link rel="stylesheet" href="css/lunbo.css" type="text/css" />
    <script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="js/layer/layer.js"></script>
    <script type="text/javascript" src="js/common.js"></script>
    <script src="ckeditor/ckeditor.js"></script><script type="text/javascript">
        $(function() {


            var wxckeditor = CKEDITOR.replace('wxdesc',{
                toolbar : 'Common',
                forcePasteAsPlainText : 'true',//强制纯文本格式粘贴
                filebrowserImageUploadUrl : 'ckeditor_upload.php?type=img',
                filebrowserFlashUploadUrl : 'ckeditor_upload.php?type=flash',
                filebrowserUploadUrl : 'ckeditor_upload.php?type=file'
            });
        });
    </script>
</head>
<body>
<div id="formlist">


    <p>
        <label style="width: 140px;">型号</label>
        <input type="text" class="text-input input-length-50" name="version" id="version" value="<?php echo $info['version'];?>" readonly/>
    </p>
    <p>
        <label style="width: 140px;">厂家</label>
        <?php
        $infos = Dict::getInfoById($info['vender']);
        ?>
        <input type="text" class="text-input input-length-10" name="vender" id="vender" value="<?php echo $infos['name'];?>" readonly/>
    </p>
    <p>
        <label style="width: 200px;">额定输出功率(KW)</label>
        <input type="text" class="text-input input-length-10" name="power" id="power" value="<?php echo $info['power'];?>"/>
        <span class="warn-inline">&nbsp;</span>
        <label style="width: 200px;">供暖热水温度调节范围(℃)</label>
        <input type="text" class="text-input input-length-10" name="heat_temperature" id="heat_temperature" value="<?php echo $info['heat_temperature'];?>"/>
        <span class="warn-inline">&nbsp;</span>
    </p>
    <p>
        <label style="width: 200px;">生活热水温度调节范围(℃)</label>
        <input type="text" class="text-input input-length-10" name="live_temperature" id="live_temperature" value="<?php echo $info['live_temperature'];?>"/>
        <span class="warn-inline">&nbsp;</span>
        <label style="width: 200px;">最高热效率(%)</label>
        <input type="text" class="text-input input-length-10" name="thermal_efficiency" id="thermal_efficiency" value="<?php echo $info['thermal_efficiency'];?>"/>
        <span class="warn-inline">&nbsp;</span>
    </p>
    <p>
        <label style="width: 200px;">中国能效标识等级</label>
        <input type="text" class="text-input input-length-10" name="efficiency_level" id="efficiency_level" value="<?php echo $info['efficiency_level'];?>"/>
        <span class="warn-inline">&nbsp;</span>
        <label style="width: 200px;">外型尺寸(mm)</label>
        <input type="text" class="text-input input-length-10" name="size" id="size" value="<?php echo $info['size'];?>"/>
        <span class="warn-inline">&nbsp;</span>
    </p>
    <p>
        <label style="width: 200px;">净重(KG)</label>
        <input type="text" class="text-input input-length-10" name="weight" id="weight" value="<?php echo $info['weight'];?>"/>
        <span class="warn-inline">&nbsp;</span>
        <label style="width: 200px;">防护等级</label>
        <input type="text" class="text-input input-length-10" name="protection_level" id="protection_level" value="<?php echo $info['protection_level'];?>"/>
        <span class="warn-inline">&nbsp;</span>
    </p>
    <p>
        <label>商品图片</label>
        <img src="<?php echo !empty($pInfo)?$HTTP_PATH.$pInfo['img']:'';?>" height="100px">
    </p>


    <p>
        <label style="width: 200px;">产品说明</label>
    <div style="margin-top:40px;margin-left:10%">
        <textarea style="padding:5px;width:70%;height:70px;" name="wxdesc" cols=200 id="wxdesc" value="" readonly><?php echo HTMLDecode(!empty($pInfo)?$pInfo['wxdesc']:''); ?></textarea>
    </div>
    </p>


    <div class="luobo_div">
        <div class="luobo_lable_div">
            <label><b>轮播图管理(上传视频)</b></label>
        </div>

        <div class="luobo_add_div">
            <?php
            $displayVideoFlag = false;
            if(!empty($videopath)){
                $video ='<div id="detail_div"><video class="detailVideo" src="';
                $video .= $videopath;
                $video .= '" controls loop></video>';
                $video .= '<img  class="detail_del" id="delVideo" src="../admin/images/dot_del.png"/></div>';
                $displayVideoFlag = true;
                echo $video;
            }
            ?>
        </div>
    </div>
    <div style="height: 30px"></div>

    <div class="luobo_div">
        <div class="luobo_lable_div">
            <label><b>视频封面</b></label>
        </div>

        <div class="luobo_add_div">
            <?php
            if(!empty($placehold)){
                $imageDiv = '<div id="detail_div"><img class="detail_place" src="';
                $imageDiv .=$placehold;
                $imageDiv .= '"/>';
                $imageDiv .='</div>';
                echo $imageDiv;
            }
            ?>
        </div>
    </div>
    <br/>
    <div style="height: 30px"></div>
    <div class="luobo_div">
        <div class="luobo_lable_div">
            <label><b>轮播图管理(上传图片)</b></label>
        </div>

        <div class="drag_div luobo_add_div">
            <?php
            $displayImgFlag = true;
            //            $displayImgFlag = true;
            if(!empty($pInfo['detail_imgs'])){
                $imgarr  = explode(';',$pInfo['detail_imgs']);
                foreach ($imgarr as $src){
                    if(!empty($src)){
                        $imageDiv = '<div id="detail_div" DR_drag="1" DR_replace="1"><img class="detail_img" src="';
                        $imageDiv .=$src;
                        $imageDiv .= '"/>';
                        $imageDiv .='</div>';
                        echo $imageDiv;
                    }
                }
                if(count($imgarr)> 5) {
                    $displayImgFlag = false;
                }
//                if(count($imgarr)> 5) {
//                    $displayImgFlag = false;
//                }
            }
            ?>
        </div>
    </div>
    <div style="height: 30px"></div>



    </div>
    </body>
</html>