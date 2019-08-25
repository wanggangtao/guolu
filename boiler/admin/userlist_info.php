<?php
/**
 * Created by PhpStorm.
 * User: tianyi
 * Date: 2018/10/23
 * Time: 22:23
 */
require_once('admin_init.php');
require_once('admincheck.php');

$tplid = safeCheck($_GET['tplid']);
$tplinfo = Case_tpl::getInfoById($tplid);
//print_r($tplinfo);
if(empty($tplinfo)){
    die();
}

//在case_tpl表中找到锅炉厂家，用户类型，用户状态的id值
$vender=null;
$usertype=null;
$userstate=null;
$text=null;
//锅炉厂家
$vender = Table_dict::getInfoById($tplinfo[0]['vender']);
//    print_r($vender);
//用户类型
$usertype = Projectcase_type::getInfoById($tplinfo[0]['usertype']);
//print_r($usertype);
//用户状态
$userstate=$tplinfo[0]['userstate'];

//通过attrid对应的parentid，找出所有的属性项
$caseattr = Case_attr::getInfoById($tplinfo[0]['attrid']);
//$attrs = Case_attr::getListByPid($caseattr[0]['parentid'],0);
$attrs = Case_attr::getListByPidandLevel(3,$caseattr[0]['parentid'],0);
//echo $caseattr[0]['parentid'];
$ids = array();
foreach ($attrs as $l=>$attr){
    array_push($ids,$attr['id']);
}
//$tplcontents = Case_tplcontent::getListByTplid($tplid);
//print_r($tplcontents);
//$ids = array();
//foreach ($tplcontents as $l=>$tplcontent){
//    array_push($ids,$tplcontent['attrid']);
//}
//富文本框
$text_content = Table_case_tplcontent::getInfoByAttridAndTplid($caseattr[0]['parentid'],$tplid);
if($text_content) {
    $text = $text_content?$text_content[0]['content']:'';
}


//$fixedids = array();
//if(20 == $tplinfo[0]['attrid']){
//    $fixedids = array('20','21','22');
//}elseif(23 == $tplinfo[0]['attrid']){
//    $fixedids = array('23','24','25');
//}
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
        })
    </script>
</head>
<body>
<div id="formlist">
<!--    --><?php
//    foreach ($attrs as $k=>$attr){
////        print_r($attr);
//        $attrinfo = Case_attr::getInfoById($attr['name']);
//
//        echo '<p>
//                        <label>'.$attrinfo[0]["name"].'</label>
//                        <input type="text" class="text-input input-length-30" name="attr_'.$attrinfo[0]["id"].'" id="attr_'.$attrinfo[0]["id"].'" value="'.$tplcontent["content"].'"  readonly/>
//                        <span class="warn-inline" id="attr_'.$attrinfo[0]["id"].'">* </span>
//                    </p>';
//    }
//    ?>
    <?php
    $html = '';
    foreach ($attrs as $k=>$attr){
        $contentinfo = Case_tplcontent::getInfoByAttridAndTplid($attr['id'],$tplid);
        $tplcontent = !empty($contentinfo)?$contentinfo[0]['content']:"";
        $html .=  '<p>
                        <label>'.$attr["name"].'</label>
                        <input type="text" class="text-input input-length-30" name="attr_'.$attr["id"].'" id="attr_'.$attr["id"].'" value="'.$tplcontent.'"  readonly/>
                    ';
//        if(in_array($attr['id'],$fixedids)){
//            $html .= '<span class="warn-inline" id="attr_'.$attr["id"].'">* </span>
//                    </p>';
//        }else{
//            $html .= '</p>';
//        }

    }
    echo $html;
    ?>
    <p>
        <label>用户类型</label>
        <input type="text" class="text-input input-length-30" name="usertype" id="usertype" value="<?php echo $usertype[0]['name'];?>"  readonly/>
    </p>
    <p>
        <label>锅炉厂家</label>
        <input type="text" class="text-input input-length-30" name="vender" id="vender" value="<?php echo $vender['name'];?>"  readonly/>
    </p>
    <p>
        <label>用户状态</label>
        <input type="text" class="text-input input-length-30" name="userstate" id="userstate" value="<?php echo $ARRAY_userstate_type[$userstate];?>"  readonly/>
    </p>
    <p>
        <label style="width: 140px;"></label>
    <div style="margin-top:40px;margin-left:10%">
        <textarea style="padding:5px;width:70%;height:70px;" name="other" cols=200 id="other" value="" readonly><?php echo HTMLDecode($text);?></textarea>
    </div>
    </p>

</div>
</body>
</html>