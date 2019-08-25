<?php
/**
 * 项目处理  project_do.php
 *
 * @version       v0.01
 * @create time   2018/6/29
 * @update time
 * @author        wzp
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */
require_once('web_init.php');
require_once('usercheck.php');

$act = safeCheck($_GET['act'], 0);
switch($act){
    case 'getTimingBuildInfo'://取得定时供水建筑类型下的详细内容
        $pid        = safeCheck($_POST['pid']);

        try {
            $detaillist = Selection_build::getListByParentid($pid);
            $htmlstr = '';
            if($detaillist){
                $i = 0;
                foreach ($detaillist as $thisinfo){
                    $i ++ ;
                    $stylestr = "";
                    if($i == 5){
                        $stylestr = 'style="width: 200px"';
                    }
                    $htmlstr .= '<div class="timing_2" '.$stylestr.'><span class="timing_3">'.$thisinfo['name'].'</span><input class="timing_4 hotwaterattr" data-value="'.$thisinfo['id'].'" type="number"><span class="timing_5">个</span></div>';
                }
            }
            echo json_encode_cn(array('code' => 1, 'htmlstr' => $htmlstr));
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;
    case 'getAlldayBuildInfo'://取得全日建筑类型下的详细内容
        $pid        = safeCheck($_POST['pid']);

        try {
            $detaillist = Selection_build::getListByParentid($pid);
            $htmlstr = '';
            if($detaillist){
                $i = 0;
                foreach ($detaillist as $thisinfo){
                    $childlist = Selection_build::getListByParentid($thisinfo['id']);
                    if(empty($childlist)){
                        $htmlstr .= '<div class="GLXXmain_1">'.$thisinfo['name'].'</div>
                                 <div class="GLXXmain_2">
                                     <input type="text" class="GLXXmain_3 hotwaterattr" data-value="'.$thisinfo['id'].'"><div class="GLXXmain_15"></div>
                                 </div>';
                    }else{
                        $htmlstr .= '<div class="GLXXmain_1">'.$thisinfo['name'].'</div>';
                        $htmlstr .= '<div class="GLXXmain_2">';
                        $htmlstr .= '<select type="text" class="GLXXmain_3 hotwaterattr" data-value="'.$thisinfo['id'].'" style="width: 344px;">';
                        foreach ($childlist as $thischild){
                            $htmlstr .= '<option value="'.$thischild['id'].'">'.$thischild['name'].'</option>';
                        }
                        $htmlstr .= '</select></div>';
                    }
                }
            }
            echo json_encode_cn(array('code' => 1, 'htmlstr' => $htmlstr));
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;
}
?>