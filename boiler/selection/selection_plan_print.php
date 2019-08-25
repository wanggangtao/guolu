<?php
/**
 * Created by xinmei.
 * User: Administrator
 * Date: 2018/11/13
 * Time: 13:11
 */

require_once('web_init.php');


$planId = safeCheck(trim($_GET['planId']));
$planInfo = null;
if(!empty($planId)){
    $planInfo = Selection_plan_front::getInfoById($planId);
}

?>


<html xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office"
                 xmlns:w="urn:schemas-microsoft-com:office:word" xmlns:m="http://schemas.microsoft.com/office/2004/12/omml"
      xmlns="http://www.w3.org/TR/REC-html40">


  <head>
      <!--[if gte mso 9]><xml><w:WordDocument><w:View>Print</w:View><w:TrackMoves>false</w:TrackMoves><w:TrackFormatting/><w:ValidateAgainstSchemas/><w:SaveIfXMLInvalid>false</w:SaveIfXMLInvalid><w:IgnoreMixedContent>false</w:IgnoreMixedContent><w:AlwaysShowPlaceholderText>false</w:AlwaysShowPlaceholderText><w:DoNotPromoteQF/><w:LidThemeOther>EN-US</w:LidThemeOther><w:LidThemeAsian>ZH-CN</w:LidThemeAsian><w:LidThemeComplexScript>X-NONE</w:LidThemeComplexScript><w:Compatibility><w:BreakWrappedTables/><w:SnapToGridInCell/><w:WrapTextWithPunct/><w:UseAsianBreakRules/><w:DontGrowAutofit/><w:SplitPgBreakAndParaMark/><w:DontVertAlignCellWithSp/><w:DontBreakConstrainedForcedTables/><w:DontVertAlignInTxbx/><w:Word11KerningPairs/><w:CachedColBalance/><w:UseFELayout/></w:Compatibility><w:BrowserLevel>MicrosoftInternetExplorer4</w:BrowserLevel><m:mathPr><m:mathFont m:val="Cambria Math"/><m:brkBin m:val="before"/><m:brkBinSub m:val="--"/><m:smallFrac m:val="off"/><m:dispDef/><m:lMargin m:val="0"/> <m:rMargin m:val="0"/><m:defJc m:val="centerGroup"/><m:wrapIndent m:val="1440"/><m:intLim m:val="subSup"/><m:naryLim m:val="undOvr"/></m:mathPr></w:WordDocument></xml><![endif]-->
  </head>


<body lang=ZH-CN style='text-justify-trim:punctuation'>

<div class=WordSection1 style='layout-grid:15.6pt'>

    <h1 align=center style='text-align:center'><span lang=EN-US></span><span
                style='font-size: 35px'><?php echo $planInfo['name']; ?></span></h1>

    <?php
        $planContent = $planInfo['tplcontent'];
        $planContentArr = explode("##",$planContent);
        $planContentStr = '';

        $guoluUserTypicalArr = array();
        $guoluUserCommonArr = array();
        $kangdaUserTypicalArr = array();
        $kangdaUserCommonArr = array();
//        $guoluUserIdArr = array();
//        $guoluUserIndex = 0;
//        $kangdaUserIdArr = array();
//        $kangdaUserIndex = 0;
        foreach ($planContentArr as $content){
            if(preg_match('/^%/',$content) && preg_match('/%$/',$content)){
                $content = explode("%",$content)[1];
                //$content = substr($content,1,strpos($content, '%'));
                $arr = explode('_',$content);
                $name = $arr[0];
                $contentId = $arr[1];


                switch ($name){

                    case "companyIntroduction":
                        //---------------------判断锅炉典型用户列表--------------------
                        if(!empty($guoluUserTypicalArr)){
                            $planContentStr.='<h3>工厂典型用户</h3><br/>';
                            foreach ($guoluUserTypicalArr as $key => $value){
                                $text = null;
                                $tplinfo = null;
                                $caseattr = null;
                                $text_content = null;
                                $userName = null;

                                //获取该用户的详情富文本内容
                                $tplinfo = Case_tpl::getInfoById($value);
                                //$userName = Case_attr::getInfoById($tplinfo[0]['name']);
                                $userName = $tplinfo[0]['name'] ;
                                $caseattr = Case_attr::getInfoById($tplinfo[0]['attrid']);
                                $text_content = Table_case_tplcontent::getInfoByAttridAndTplid($caseattr[0]['parentid'],$value);
                                if($text_content) {
                                    $text = $text_content?$text_content[0]['content']:'';
                                }
                                $planContentStr.='<strong>['.$userName.']详情:</strong>'.$text;
                            }
                            //清空锅炉典型用户Id数组
                            $guoluUserTypicalArr = array();
                        }
                        //---------------------判断锅炉普通用户列表------------------------
                        if(!empty($guoluUserCommonArr)){
                            $planContentStr.='<h3>工厂普通用户</h3><br/>';

                            $planContentStr.='
                                <table class="cmTable" border=1>
                                    <thead>
                                        <tr>';
                            $text = null;
                            $tplinfo = null;
                            $caseattr = null;
                            $text_content = null;
                            $userName = null;

                            //获取parentId对应的属性
                            $tplinfo = Case_tpl::getInfoById($guoluUserCommonArr[0]);
                            $caseattr = Case_attr::getInfoById($tplinfo[0]['attrid']);
                            $guoluUserAttrs = Case_attr::getListByPid($caseattr[0]['parentid'],0);
                            $theadArray = array();
                            foreach ($guoluUserAttrs as $key=>$attr){
                                $planContentStr.='<th width="20%">'.$attr["name"].'</th>';
                                $theadArray[$key] = $attr["id"];
                            }
                            $planContentStr.='
                                        </tr>
                                    </thead>
                                <tbody >';
                            //-----------------表体-------------------
                            foreach ($guoluUserCommonArr as $guoluUserId){
                                $planContentStr.='<tr>';
                                $guoluContentInfos = Case_tplcontent::getListByTplid($guoluUserId);
                                foreach ($theadArray as $theadItem){
                                    $tdContent = "";
                                    foreach ($guoluContentInfos as $guoluContentInfo){
                                        if($guoluContentInfo["attrid"] == $theadItem){
                                            $tdContent = $guoluContentInfo["content"];
                                        }
                                    }
                                    $planContentStr.='<td align="center">'.$tdContent.'</td>';
                                }
                                $planContentStr.='</tr>';
                            }
                            //--------------表尾-------------------
                            $planContentStr.='
                                </tbody>
                            </table> ';

                            //清空锅炉典型用户Id数组
                            $guoluUserCommonArr = array();
                        }
                        //------------------------判断公司典型用户列表------------------------------
                        if(!empty($kangdaUserTypicalArr)){
                            $planContentStr.='<h3>公司典型用户</h3><br/>';
                            foreach ($kangdaUserTypicalArr as $key => $value){
                                $text = null;
                                $tplinfo = null;
                                $caseattr = null;
                                $text_content = null;
                                $userName = null;

                                //获取该用户的详情富文本内容
                                $tplinfo = Case_tpl::getInfoById($value);
                                //$userName = Case_attr::getInfoById($tplinfo[0]['name']);
                                $userName = $tplinfo[0]['name'] ;
                                $caseattr = Case_attr::getInfoById($tplinfo[0]['attrid']);
                                $text_content = Table_case_tplcontent::getInfoByAttridAndTplid($caseattr[0]['parentid'],$value);
                                if($text_content) {
                                    $text = $text_content?$text_content[0]['content']:'';
                                }
                                $planContentStr.='<strong>['.$userName.']详情:</strong>'.$text;
                            }
                            //清空锅炉典型用户Id数组
                            $kangdaUserTypicalArr = array();
                        }
                        //-----------------------------判断公司普通用户列表------------------------------
                        if(!empty($kangdaUserCommonArr)){
                            $planContentStr.='<h3>公司普通用户</h3><br/>';
                            $planContentStr.='
                                <table class="cmTable" border=1>
                                    <thead>
                                        <tr>';
                            $text = null;
                            $tplinfo = null;
                            $caseattr = null;
                            $text_content = null;
                            $userName = null;

                            //获取parentId对应的属性
                            $tplinfo = Case_tpl::getInfoById($kangdaUserCommonArr[0]);
                            $caseattr = Case_attr::getInfoById($tplinfo[0]['attrid']);
                            $guoluUserAttrs = Case_attr::getListByPid($caseattr[0]['parentid'],0);
                            $theadArray = array();
                            foreach ($guoluUserAttrs as $key=>$attr){
                                $planContentStr.='<th width="20%">'.$attr["name"].'</th>';
                                $theadArray[$key] = $attr["id"];
                            }
                            $planContentStr.='
                                        </tr>
                                    </thead>
                                <tbody >';
                            //-----------------表体-------------------
                            foreach ($kangdaUserCommonArr as $kangdaUserId){
                                $planContentStr.='<tr>';
                                $kangdaContentInfos = Case_tplcontent::getListByTplid($kangdaUserId);
                                foreach ($theadArray as $theadItem){
                                    $tdContent = "";
                                    foreach ($kangdaContentInfos as $kangdaContentInfo){
                                        if($kangdaContentInfo["attrid"] == $theadItem){
                                            $tdContent = $kangdaContentInfo["content"];
                                        }
                                    }
                                    $planContentStr.='<td align="center">'.$tdContent.'</td>';
                                }
                                $planContentStr.='</tr>';
                            }
                            //--------------表尾-------------------
                            $planContentStr.='
                                </tbody>
                            </table> ';
                            //清空锅炉典型用户Id数组
                            $kangdaUserCommonArr = array();
                        }

                        //--------------------------用户名单显示-------【完】----------------------


                        $info = Case_tplcontent::getContentByTplcontentId($contentId) ;
                        $planContentStr.=$info[0]["content"];
                        break;
                    case "companyCompetency":

                        //---------------------判断锅炉典型用户列表--------------------
                        if(!empty($guoluUserTypicalArr)){
                            $planContentStr.='<h3>工厂典型用户</h3><br/>';
                            foreach ($guoluUserTypicalArr as $key => $value){
                                $text = null;
                                $tplinfo = null;
                                $caseattr = null;
                                $text_content = null;
                                $userName = null;

                                //获取该用户的详情富文本内容
                                $tplinfo = Case_tpl::getInfoById($value);
                                //$userName = Case_attr::getInfoById($tplinfo[0]['name']);
                                $userName = $tplinfo[0]['name'] ;
                                $caseattr = Case_attr::getInfoById($tplinfo[0]['attrid']);
                                $text_content = Table_case_tplcontent::getInfoByAttridAndTplid($caseattr[0]['parentid'],$value);
                                if($text_content) {
                                    $text = $text_content?$text_content[0]['content']:'';
                                }
                                $planContentStr.='<strong>['.$userName.']详情:</strong>'.$text;
                            }
                            //清空锅炉典型用户Id数组
                            $guoluUserTypicalArr = array();
                        }
                        //---------------------判断锅炉普通用户列表------------------------
                        if(!empty($guoluUserCommonArr)){
                            $planContentStr.='<h3>工厂普通用户</h3><br/>';

                            $planContentStr.='
                                <table class="cmTable" border=1>
                                    <thead>
                                        <tr>';
                            $text = null;
                            $tplinfo = null;
                            $caseattr = null;
                            $text_content = null;
                            $userName = null;

                            //获取parentId对应的属性
                            $tplinfo = Case_tpl::getInfoById($guoluUserCommonArr[0]);
                            $caseattr = Case_attr::getInfoById($tplinfo[0]['attrid']);
                            $guoluUserAttrs = Case_attr::getListByPid($caseattr[0]['parentid'],0);
                            $theadArray = array();
                            foreach ($guoluUserAttrs as $key=>$attr){
                                $planContentStr.='<th width="20%">'.$attr["name"].'</th>';
                                $theadArray[$key] = $attr["id"];
                            }
                            $planContentStr.='
                                        </tr>
                                    </thead>
                                <tbody >';
                            //-----------------表体-------------------
                            foreach ($guoluUserCommonArr as $guoluUserId){
                                $planContentStr.='<tr>';
                                $guoluContentInfos = Case_tplcontent::getListByTplid($guoluUserId);
                                foreach ($theadArray as $theadItem){
                                    $tdContent = "";
                                    foreach ($guoluContentInfos as $guoluContentInfo){
                                        if($guoluContentInfo["attrid"] == $theadItem){
                                            $tdContent = $guoluContentInfo["content"];
                                        }
                                    }
                                    $planContentStr.='<td align="center">'.$tdContent.'</td>';
                                }
                                $planContentStr.='</tr>';
                            }
                            //--------------表尾-------------------
                            $planContentStr.='
                                </tbody>
                            </table> ';

                            //清空锅炉典型用户Id数组
                            $guoluUserCommonArr = array();
                        }
                        //------------------------判断公司典型用户列表------------------------------
                        if(!empty($kangdaUserTypicalArr)){
                            $planContentStr.='<h3>公司典型用户</h3><br/>';
                            foreach ($kangdaUserTypicalArr as $key => $value){
                                $text = null;
                                $tplinfo = null;
                                $caseattr = null;
                                $text_content = null;
                                $userName = null;

                                //获取该用户的详情富文本内容
                                $tplinfo = Case_tpl::getInfoById($value);
                                //$userName = Case_attr::getInfoById($tplinfo[0]['name']);
                                $userName = $tplinfo[0]['name'] ;
                                $caseattr = Case_attr::getInfoById($tplinfo[0]['attrid']);
                                $text_content = Table_case_tplcontent::getInfoByAttridAndTplid($caseattr[0]['parentid'],$value);
                                if($text_content) {
                                    $text = $text_content?$text_content[0]['content']:'';
                                }
                                $planContentStr.='<strong>['.$userName.']详情:</strong>'.$text;
                            }
                            //清空锅炉典型用户Id数组
                            $kangdaUserTypicalArr = array();
                        }
                        //-----------------------------判断公司普通用户列表------------------------------
                        if(!empty($kangdaUserCommonArr)){
                            $planContentStr.='<h3>公司普通用户</h3><br/>';
                            $planContentStr.='
                                <table class="cmTable" border=1>
                                    <thead>
                                        <tr>';
                            $text = null;
                            $tplinfo = null;
                            $caseattr = null;
                            $text_content = null;
                            $userName = null;

                            //获取parentId对应的属性
                            $tplinfo = Case_tpl::getInfoById($kangdaUserCommonArr[0]);
                            $caseattr = Case_attr::getInfoById($tplinfo[0]['attrid']);
                            $guoluUserAttrs = Case_attr::getListByPid($caseattr[0]['parentid'],0);
                            $theadArray = array();
                            foreach ($guoluUserAttrs as $key=>$attr){
                                $planContentStr.='<th width="20%">'.$attr["name"].'</th>';
                                $theadArray[$key] = $attr["id"];
                            }
                            $planContentStr.='
                                        </tr>
                                    </thead>
                                <tbody >';
                            //-----------------表体-------------------
                            foreach ($kangdaUserCommonArr as $kangdaUserId){
                                $planContentStr.='<tr>';
                                $kangdaContentInfos = Case_tplcontent::getListByTplid($kangdaUserId);
                                foreach ($theadArray as $theadItem){
                                    $tdContent = "";
                                    foreach ($kangdaContentInfos as $kangdaContentInfo){
                                        if($kangdaContentInfo["attrid"] == $theadItem){
                                            $tdContent = $kangdaContentInfo["content"];
                                        }
                                    }
                                    $planContentStr.='<td align="center">'.$tdContent.'</td>';
                                }
                                $planContentStr.='</tr>';
                            }
                            //--------------表尾-------------------
                            $planContentStr.='
                                </tbody>
                            </table> ';
                            //清空锅炉典型用户Id数组
                            $kangdaUserCommonArr = array();
                        }

                        //--------------------------用户名单显示-------【完】----------------------

                        $info = Case_tplcontent::getContentByTplcontentId($contentId) ;
                        $planContentStr.=$info[0]["content"];
                        break;
                    case "factoryIntroduction":

                        //---------------------判断锅炉典型用户列表--------------------
                        if(!empty($guoluUserTypicalArr)){
                            $planContentStr.='<h3>工厂典型用户</h3><br/>';
                            foreach ($guoluUserTypicalArr as $key => $value){
                                $text = null;
                                $tplinfo = null;
                                $caseattr = null;
                                $text_content = null;
                                $userName = null;

                                //获取该用户的详情富文本内容
                                $tplinfo = Case_tpl::getInfoById($value);
                                //$userName = Case_attr::getInfoById($tplinfo[0]['name']);
                                $userName = $tplinfo[0]['name'] ;
                                $caseattr = Case_attr::getInfoById($tplinfo[0]['attrid']);
                                $text_content = Table_case_tplcontent::getInfoByAttridAndTplid($caseattr[0]['parentid'],$value);
                                if($text_content) {
                                    $text = $text_content?$text_content[0]['content']:'';
                                }
                                $planContentStr.='<strong>['.$userName.']详情:</strong>'.$text;
                            }
                            //清空锅炉典型用户Id数组
                            $guoluUserTypicalArr = array();
                        }
                        //---------------------判断锅炉普通用户列表------------------------
                        if(!empty($guoluUserCommonArr)){
                            $planContentStr.='<h3>工厂普通用户</h3><br/>';

                            $planContentStr.='
                                <table class="cmTable" border=1>
                                    <thead>
                                        <tr>';
                            $text = null;
                            $tplinfo = null;
                            $caseattr = null;
                            $text_content = null;
                            $userName = null;

                            //获取parentId对应的属性
                            $tplinfo = Case_tpl::getInfoById($guoluUserCommonArr[0]);
                            $caseattr = Case_attr::getInfoById($tplinfo[0]['attrid']);
                            $guoluUserAttrs = Case_attr::getListByPid($caseattr[0]['parentid'],0);
                            $theadArray = array();
                            foreach ($guoluUserAttrs as $key=>$attr){
                                $planContentStr.='<th width="20%">'.$attr["name"].'</th>';
                                $theadArray[$key] = $attr["id"];
                            }
                            $planContentStr.='
                                        </tr>
                                    </thead>
                                <tbody >';
                            //-----------------表体-------------------
                            foreach ($guoluUserCommonArr as $guoluUserId){
                                $planContentStr.='<tr>';
                                $guoluContentInfos = Case_tplcontent::getListByTplid($guoluUserId);
                                foreach ($theadArray as $theadItem){
                                    $tdContent = "";
                                    foreach ($guoluContentInfos as $guoluContentInfo){
                                        if($guoluContentInfo["attrid"] == $theadItem){
                                            $tdContent = $guoluContentInfo["content"];
                                        }
                                    }
                                    $planContentStr.='<td align="center">'.$tdContent.'</td>';
                                }
                                $planContentStr.='</tr>';
                            }
                            //--------------表尾-------------------
                            $planContentStr.='
                                </tbody>
                            </table> ';

                            //清空锅炉典型用户Id数组
                            $guoluUserCommonArr = array();
                        }
                        //------------------------判断公司典型用户列表------------------------------
                        if(!empty($kangdaUserTypicalArr)){
                            $planContentStr.='<h3>公司典型用户</h3><br/>';
                            foreach ($kangdaUserTypicalArr as $key => $value){
                                $text = null;
                                $tplinfo = null;
                                $caseattr = null;
                                $text_content = null;
                                $userName = null;

                                //获取该用户的详情富文本内容
                                $tplinfo = Case_tpl::getInfoById($value);
                                //$userName = Case_attr::getInfoById($tplinfo[0]['name']);
                                $userName = $tplinfo[0]['name'] ;
                                $caseattr = Case_attr::getInfoById($tplinfo[0]['attrid']);
                                $text_content = Table_case_tplcontent::getInfoByAttridAndTplid($caseattr[0]['parentid'],$value);
                                if($text_content) {
                                    $text = $text_content?$text_content[0]['content']:'';
                                }
                                $planContentStr.='<strong>['.$userName.']详情:</strong>'.$text;
                            }
                            //清空锅炉典型用户Id数组
                            $kangdaUserTypicalArr = array();
                        }
                        //-----------------------------判断公司普通用户列表------------------------------
                        if(!empty($kangdaUserCommonArr)){
                            $planContentStr.='<h3>公司普通用户</h3><br/>';
                            $planContentStr.='
                                <table class="cmTable" border=1>
                                    <thead>
                                        <tr>';
                            $text = null;
                            $tplinfo = null;
                            $caseattr = null;
                            $text_content = null;
                            $userName = null;

                            //获取parentId对应的属性
                            $tplinfo = Case_tpl::getInfoById($kangdaUserCommonArr[0]);
                            $caseattr = Case_attr::getInfoById($tplinfo[0]['attrid']);
                            $guoluUserAttrs = Case_attr::getListByPid($caseattr[0]['parentid'],0);
                            $theadArray = array();
                            foreach ($guoluUserAttrs as $key=>$attr){
                                $planContentStr.='<th width="20%">'.$attr["name"].'</th>';
                                $theadArray[$key] = $attr["id"];
                            }
                            $planContentStr.='
                                        </tr>
                                    </thead>
                                <tbody >';
                            //-----------------表体-------------------
                            foreach ($kangdaUserCommonArr as $kangdaUserId){
                                $planContentStr.='<tr>';
                                $kangdaContentInfos = Case_tplcontent::getListByTplid($kangdaUserId);
                                foreach ($theadArray as $theadItem){
                                    $tdContent = "";
                                    foreach ($kangdaContentInfos as $kangdaContentInfo){
                                        if($kangdaContentInfo["attrid"] == $theadItem){
                                            $tdContent = $kangdaContentInfo["content"];
                                        }
                                    }
                                    $planContentStr.='<td align="center">'.$tdContent.'</td>';
                                }
                                $planContentStr.='</tr>';
                            }
                            //--------------表尾-------------------
                            $planContentStr.='
                                </tbody>
                            </table> ';
                            //清空锅炉典型用户Id数组
                            $kangdaUserCommonArr = array();
                        }

                        //--------------------------用户名单显示-------【完】----------------------

                        $info = Case_tplcontent::getContentByTplcontentId($contentId);
                        $tpl_info = Case_tpl::getInfoById($info[0]['tplid']);
                        $factory_name = $tpl_info[0]['name'];
                        //$planContentStr.='<h2>['.$factory_name.']厂家介绍：</h2><br/></h2>';
                        $planContentStr.=$info[0]["content"];
                        break;
                    case "factoryCompetency":

                        //---------------------判断锅炉典型用户列表--------------------
                        if(!empty($guoluUserTypicalArr)){
                            $planContentStr.='<h3>工厂典型用户</h3><br/>';
                            foreach ($guoluUserTypicalArr as $key => $value){
                                $text = null;
                                $tplinfo = null;
                                $caseattr = null;
                                $text_content = null;
                                $userName = null;

                                //获取该用户的详情富文本内容
                                $tplinfo = Case_tpl::getInfoById($value);
                                //$userName = Case_attr::getInfoById($tplinfo[0]['name']);
                                $userName = $tplinfo[0]['name'] ;
                                $caseattr = Case_attr::getInfoById($tplinfo[0]['attrid']);
                                $text_content = Table_case_tplcontent::getInfoByAttridAndTplid($caseattr[0]['parentid'],$value);
                                if($text_content) {
                                    $text = $text_content?$text_content[0]['content']:'';
                                }
                                $planContentStr.='<strong>['.$userName.']详情:</strong>'.$text;
                            }
                            //清空锅炉典型用户Id数组
                            $guoluUserTypicalArr = array();
                        }
                        //---------------------判断锅炉普通用户列表------------------------
                        if(!empty($guoluUserCommonArr)){
                            $planContentStr.='<h3>工厂普通用户</h3><br/>';

                            $planContentStr.='
                                <table class="cmTable" border=1>
                                    <thead>
                                        <tr>';
                            $text = null;
                            $tplinfo = null;
                            $caseattr = null;
                            $text_content = null;
                            $userName = null;

                            //获取parentId对应的属性
                            $tplinfo = Case_tpl::getInfoById($guoluUserCommonArr[0]);
                            $caseattr = Case_attr::getInfoById($tplinfo[0]['attrid']);
                            $guoluUserAttrs = Case_attr::getListByPid($caseattr[0]['parentid'],0);
                            $theadArray = array();
                            foreach ($guoluUserAttrs as $key=>$attr){
                                $planContentStr.='<th width="20%">'.$attr["name"].'</th>';
                                $theadArray[$key] = $attr["id"];
                            }
                            $planContentStr.='
                                        </tr>
                                    </thead>
                                <tbody >';
                            //-----------------表体-------------------
                            foreach ($guoluUserCommonArr as $guoluUserId){
                                $planContentStr.='<tr>';
                                $guoluContentInfos = Case_tplcontent::getListByTplid($guoluUserId);
                                foreach ($theadArray as $theadItem){
                                    $tdContent = "";
                                    foreach ($guoluContentInfos as $guoluContentInfo){
                                        if($guoluContentInfo["attrid"] == $theadItem){
                                            $tdContent = $guoluContentInfo["content"];
                                        }
                                    }
                                    $planContentStr.='<td align="center">'.$tdContent.'</td>';
                                }
                                $planContentStr.='</tr>';
                            }
                            //--------------表尾-------------------
                            $planContentStr.='
                                </tbody>
                            </table> ';

                            //清空锅炉典型用户Id数组
                            $guoluUserCommonArr = array();
                        }
                        //------------------------判断公司典型用户列表------------------------------
                        if(!empty($kangdaUserTypicalArr)){
                            $planContentStr.='<h3>公司典型用户</h3><br/>';
                            foreach ($kangdaUserTypicalArr as $key => $value){
                                $text = null;
                                $tplinfo = null;
                                $caseattr = null;
                                $text_content = null;
                                $userName = null;

                                //获取该用户的详情富文本内容
                                $tplinfo = Case_tpl::getInfoById($value);
                                //$userName = Case_attr::getInfoById($tplinfo[0]['name']);
                                $userName = $tplinfo[0]['name'] ;
                                $caseattr = Case_attr::getInfoById($tplinfo[0]['attrid']);
                                $text_content = Table_case_tplcontent::getInfoByAttridAndTplid($caseattr[0]['parentid'],$value);
                                if($text_content) {
                                    $text = $text_content?$text_content[0]['content']:'';
                                }
                                $planContentStr.='<strong>['.$userName.']详情:</strong>'.$text;
                            }
                            //清空锅炉典型用户Id数组
                            $kangdaUserTypicalArr = array();
                        }
                        //-----------------------------判断公司普通用户列表------------------------------
                        if(!empty($kangdaUserCommonArr)){
                            $planContentStr.='<h3>公司普通用户</h3><br/>';
                            $planContentStr.='
                                <table class="cmTable" border=1>
                                    <thead>
                                        <tr>';
                            $text = null;
                            $tplinfo = null;
                            $caseattr = null;
                            $text_content = null;
                            $userName = null;

                            //获取parentId对应的属性
                            $tplinfo = Case_tpl::getInfoById($kangdaUserCommonArr[0]);
                            $caseattr = Case_attr::getInfoById($tplinfo[0]['attrid']);
                            $guoluUserAttrs = Case_attr::getListByPid($caseattr[0]['parentid'],0);
                            $theadArray = array();
                            foreach ($guoluUserAttrs as $key=>$attr){
                                $planContentStr.='<th width="20%">'.$attr["name"].'</th>';
                                $theadArray[$key] = $attr["id"];
                            }
                            $planContentStr.='
                                        </tr>
                                    </thead>
                                <tbody >';
                            //-----------------表体-------------------
                            foreach ($kangdaUserCommonArr as $kangdaUserId){
                                $planContentStr.='<tr>';
                                $kangdaContentInfos = Case_tplcontent::getListByTplid($kangdaUserId);
                                foreach ($theadArray as $theadItem){
                                    $tdContent = "";
                                    foreach ($kangdaContentInfos as $kangdaContentInfo){
                                        if($kangdaContentInfo["attrid"] == $theadItem){
                                            $tdContent = $kangdaContentInfo["content"];
                                        }
                                    }
                                    $planContentStr.='<td align="center">'.$tdContent.'</td>';
                                }
                                $planContentStr.='</tr>';
                            }
                            //--------------表尾-------------------
                            $planContentStr.='
                                </tbody>
                            </table> ';
                            //清空锅炉典型用户Id数组
                            $kangdaUserCommonArr = array();
                        }

                        //--------------------------用户名单显示-------【完】----------------------

                        $info = Case_tplcontent::getContentByTplcontentId($contentId) ;
                        $tpl_info = Case_tpl::getInfoById($info[0]['tplid']);
                        $factory_name = $tpl_info[0]['name'];
                        //$planContentStr.='<h2>['.$factory_name.']厂家资质：</h2><br/></h2>';
                        $planContentStr.=$info[0]["content"];
                        break;
                    case "quotePlan":

                        //---------------------判断锅炉典型用户列表--------------------
                        if(!empty($guoluUserTypicalArr)){
                            $planContentStr.='<h3>工厂典型用户</h3><br/>';
                            foreach ($guoluUserTypicalArr as $key => $value){
                                $text = null;
                                $tplinfo = null;
                                $caseattr = null;
                                $text_content = null;
                                $userName = null;

                                //获取该用户的详情富文本内容
                                $tplinfo = Case_tpl::getInfoById($value);
                                //$userName = Case_attr::getInfoById($tplinfo[0]['name']);
                                $userName = $tplinfo[0]['name'] ;
                                $caseattr = Case_attr::getInfoById($tplinfo[0]['attrid']);
                                $text_content = Table_case_tplcontent::getInfoByAttridAndTplid($caseattr[0]['parentid'],$value);
                                if($text_content) {
                                    $text = $text_content?$text_content[0]['content']:'';
                                }
                                $planContentStr.='<strong>['.$userName.']详情:</strong>'.$text;
                            }
                            //清空锅炉典型用户Id数组
                            $guoluUserTypicalArr = array();
                        }
                        //---------------------判断锅炉普通用户列表------------------------
                        if(!empty($guoluUserCommonArr)){
                            $planContentStr.='<h3>工厂普通用户</h3><br/>';

                            $planContentStr.='
                                <table class="cmTable" border=1>
                                    <thead>
                                        <tr>';
                            $text = null;
                            $tplinfo = null;
                            $caseattr = null;
                            $text_content = null;
                            $userName = null;

                            //获取parentId对应的属性
                            $tplinfo = Case_tpl::getInfoById($guoluUserCommonArr[0]);
                            $caseattr = Case_attr::getInfoById($tplinfo[0]['attrid']);
                            $guoluUserAttrs = Case_attr::getListByPid($caseattr[0]['parentid'],0);
                            $theadArray = array();
                            foreach ($guoluUserAttrs as $key=>$attr){
                                $planContentStr.='<th width="20%">'.$attr["name"].'</th>';
                                $theadArray[$key] = $attr["id"];
                            }
                            $planContentStr.='
                                        </tr>
                                    </thead>
                                <tbody >';
                            //-----------------表体-------------------
                            foreach ($guoluUserCommonArr as $guoluUserId){
                                $planContentStr.='<tr>';
                                $guoluContentInfos = Case_tplcontent::getListByTplid($guoluUserId);
                                foreach ($theadArray as $theadItem){
                                    $tdContent = "";
                                    foreach ($guoluContentInfos as $guoluContentInfo){
                                        if($guoluContentInfo["attrid"] == $theadItem){
                                            $tdContent = $guoluContentInfo["content"];
                                        }
                                    }
                                    $planContentStr.='<td align="center">'.$tdContent.'</td>';
                                }
                                $planContentStr.='</tr>';
                            }
                            //--------------表尾-------------------
                            $planContentStr.='
                                </tbody>
                            </table> ';

                            //清空锅炉典型用户Id数组
                            $guoluUserCommonArr = array();
                        }
                        //------------------------判断公司典型用户列表------------------------------
                        if(!empty($kangdaUserTypicalArr)){
                            $planContentStr.='<h3>公司典型用户</h3><br/>';
                            foreach ($kangdaUserTypicalArr as $key => $value){
                                $text = null;
                                $tplinfo = null;
                                $caseattr = null;
                                $text_content = null;
                                $userName = null;

                                //获取该用户的详情富文本内容
                                $tplinfo = Case_tpl::getInfoById($value);
                                //$userName = Case_attr::getInfoById($tplinfo[0]['name']);
                                $userName = $tplinfo[0]['name'] ;
                                $caseattr = Case_attr::getInfoById($tplinfo[0]['attrid']);
                                $text_content = Table_case_tplcontent::getInfoByAttridAndTplid($caseattr[0]['parentid'],$value);
                                if($text_content) {
                                    $text = $text_content?$text_content[0]['content']:'';
                                }
                                $planContentStr.='<strong>['.$userName.']详情:</strong>'.$text;
                            }
                            //清空锅炉典型用户Id数组
                            $kangdaUserTypicalArr = array();
                        }
                        //-----------------------------判断公司普通用户列表------------------------------
                        if(!empty($kangdaUserCommonArr)){
                            $planContentStr.='<h3>公司普通用户</h3><br/>';
                            $planContentStr.='
                                <table class="cmTable" border=1>
                                    <thead>
                                        <tr>';
                            $text = null;
                            $tplinfo = null;
                            $caseattr = null;
                            $text_content = null;
                            $userName = null;

                            //获取parentId对应的属性
                            $tplinfo = Case_tpl::getInfoById($kangdaUserCommonArr[0]);
                            $caseattr = Case_attr::getInfoById($tplinfo[0]['attrid']);
                            $guoluUserAttrs = Case_attr::getListByPid($caseattr[0]['parentid'],0);
                            $theadArray = array();
                            foreach ($guoluUserAttrs as $key=>$attr){
                                $planContentStr.='<th width="20%">'.$attr["name"].'</th>';
                                $theadArray[$key] = $attr["id"];
                            }
                            $planContentStr.='
                                        </tr>
                                    </thead>
                                <tbody >';
                            //-----------------表体-------------------
                            foreach ($kangdaUserCommonArr as $kangdaUserId){
                                $planContentStr.='<tr>';
                                $kangdaContentInfos = Case_tplcontent::getListByTplid($kangdaUserId);
                                foreach ($theadArray as $theadItem){
                                    $tdContent = "";
                                    foreach ($kangdaContentInfos as $kangdaContentInfo){
                                        if($kangdaContentInfo["attrid"] == $theadItem){
                                            $tdContent = $kangdaContentInfo["content"];
                                        }
                                    }
                                    $planContentStr.='<td align="center">'.$tdContent.'</td>';
                                }
                                $planContentStr.='</tr>';
                            }
                            //--------------表尾-------------------
                            $planContentStr.='
                                </tbody>
                            </table> ';
                            //清空锅炉典型用户Id数组
                            $kangdaUserCommonArr = array();
                        }

                        //--------------------------用户名单显示-------【完】----------------------

                        $historyInfo =  Selection_history::getInfoById($contentId) ;
                        $history = $historyInfo["id"];
                        //锅炉
                        $quoteListBoiler = Table_quote_plan_print::getQuoteListBoiler($history);
                        //辅机
                        $quoteListFuji = Table_quote_plan_print::getQuoteListFuji($history);
                        //其他
                        $quoteListOther = Table_quote_plan_print::getQuoteListOther($history );
                        $planContentStr.='
                            <table class="cmTable" border=1>
                                <thead>
                                    <tr>
                                        <th width="5%">序号</th>
                                        <th width="20%">设备名称</th>
                                        <th width="20%">规格型号</th>
                                        <th width="10%">数量</th>
                                        <th width="15%">单价(万元)</th>
                                        <th width="15%">总价(万元)</th>
                                        <th width="20%">备注</th>
                                    </tr>
                                </thead>
                                <tbody  >';

                        $totalAccount = 0;
                        $i = 0;
                        //锅炉
                        if(!empty($quoteListBoiler)){
                            foreach ($quoteListBoiler as $key => $itemGuolu ){
                                $i++;
                                $planContentStr.='
                                    <tr>
                                        <td align="center">'.$i.'</td>
                                        <td align="center">'.$itemGuolu["name"].'</td>
                                        <td align="center">'.$itemGuolu["size"].'</td>
                                        <td align="center">'.$itemGuolu["number"].'</td>
                                        <td align="center">'.$itemGuolu["price"].'</td>
                                        <td align="center">'.$itemGuolu["account"].'</td>
                                        <td align="center">'.$itemGuolu["remark"].'</td>
                                    </tr>
                                ';
                                $totalAccount+=$itemGuolu["account"];
                            }
                        }
                        //辅机
                        if(!empty($quoteListFuji)){
                            foreach ($quoteListFuji as $itemFuji ){
                                $i++;
                                $planContentStr.='
                                    <tr>
                                        <td align="center">'.$i.'</td>
                                        <td align="center">'.$itemFuji["name"].'</td>
                                        <td align="center">'.$itemFuji["size"].'</td>
                                        <td align="center">'.$itemFuji["number"].'</td>
                                        <td align="center">'.$itemFuji["price"].'</td>
                                        <td align="center">'.$itemFuji["account"].'</td>
                                        <td align="center">'.$itemFuji["remark"].'</td>
                                    </tr>
                                ';
                                $totalAccount+=$itemFuji["account"];
                            }
                        }
                        //其他
                        if(!empty($quoteListOther)){
                            foreach ($quoteListOther as $itemOther ){
                                $i++;
                                $planContentStr.='
                                    <tr>
                                        <td align="center">'.$i.'</td>
                                        <td align="center">'.$itemOther["name"].'</td>
                                        <td align="center">'.$itemOther["size"].'</td>
                                        <td align="center">'.$itemOther["number"].'</td>
                                        <td align="center">'.$itemOther["price"].'</td>
                                        <td align="center">'.$itemOther["account"].'</td>
                                        <td align="center">'.$itemOther["remark"].'</td>
                                    </tr>
                                ';
                                $totalAccount+=$itemOther["account"];
                            }
                        }
                        //小写合计
                        $tatalAccountStr = '';
                        //if($totalAccount >10000){
                            //$tatalAccountStr = round($totalAccount/10000,4).'万元';
                            $tatalAccountStr =  $totalAccount .'万元';
                        //
                        $planContentStr.='
                                    <tr>
                                    <td  colspan=7 >
                                    合计：大写（人民币）:'.Table_quote_plan_print::rmb_format($totalAccount*10000).'; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;小写（￥）：'.$tatalAccountStr.'</td>
                                </tr>
                                ';
                        $planContentStr.='
                                </tbody>
                            </table> ';
                        break;
                    case "productIntroduction":

                        //---------------------判断锅炉典型用户列表--------------------
                        if(!empty($guoluUserTypicalArr)){
                            $planContentStr.='<h3>工厂典型用户</h3><br/>';
                            foreach ($guoluUserTypicalArr as $key => $value){
                                $text = null;
                                $tplinfo = null;
                                $caseattr = null;
                                $text_content = null;
                                $userName = null;

                                //获取该用户的详情富文本内容
                                $tplinfo = Case_tpl::getInfoById($value);
                                //$userName = Case_attr::getInfoById($tplinfo[0]['name']);
                                $userName = $tplinfo[0]['name'] ;
                                $caseattr = Case_attr::getInfoById($tplinfo[0]['attrid']);
                                $text_content = Table_case_tplcontent::getInfoByAttridAndTplid($caseattr[0]['parentid'],$value);
                                if($text_content) {
                                    $text = $text_content?$text_content[0]['content']:'';
                                }
                                $planContentStr.='<strong>['.$userName.']详情:</strong>'.$text;
                            }
                            //清空锅炉典型用户Id数组
                            $guoluUserTypicalArr = array();
                        }
                        //---------------------判断锅炉普通用户列表------------------------
                        if(!empty($guoluUserCommonArr)){
                            $planContentStr.='<h3>工厂普通用户</h3><br/>';

                            $planContentStr.='
                                <table class="cmTable" border=1>
                                    <thead>
                                        <tr>';
                            $text = null;
                            $tplinfo = null;
                            $caseattr = null;
                            $text_content = null;
                            $userName = null;

                            //获取parentId对应的属性
                            $tplinfo = Case_tpl::getInfoById($guoluUserCommonArr[0]);
                            $caseattr = Case_attr::getInfoById($tplinfo[0]['attrid']);
                            $guoluUserAttrs = Case_attr::getListByPid($caseattr[0]['parentid'],0);
                            $theadArray = array();
                            foreach ($guoluUserAttrs as $key=>$attr){
                                $planContentStr.='<th width="20%">'.$attr["name"].'</th>';
                                $theadArray[$key] = $attr["id"];
                            }
                            $planContentStr.='
                                        </tr>
                                    </thead>
                                <tbody >';
                            //-----------------表体-------------------
                            foreach ($guoluUserCommonArr as $guoluUserId){
                                $planContentStr.='<tr>';
                                $guoluContentInfos = Case_tplcontent::getListByTplid($guoluUserId);
                                foreach ($theadArray as $theadItem){
                                    $tdContent = "";
                                    foreach ($guoluContentInfos as $guoluContentInfo){
                                        if($guoluContentInfo["attrid"] == $theadItem){
                                            $tdContent = $guoluContentInfo["content"];
                                        }
                                    }
                                    $planContentStr.='<td align="center">'.$tdContent.'</td>';
                                }
                                $planContentStr.='</tr>';
                            }
                            //--------------表尾-------------------
                            $planContentStr.='
                                </tbody>
                            </table> ';

                            //清空锅炉典型用户Id数组
                            $guoluUserCommonArr = array();
                        }
                        //------------------------判断公司典型用户列表------------------------------
                        if(!empty($kangdaUserTypicalArr)){
                            $planContentStr.='<h3>公司典型用户</h3><br/>';
                            foreach ($kangdaUserTypicalArr as $key => $value){
                                $text = null;
                                $tplinfo = null;
                                $caseattr = null;
                                $text_content = null;
                                $userName = null;

                                //获取该用户的详情富文本内容
                                $tplinfo = Case_tpl::getInfoById($value);
                                //$userName = Case_attr::getInfoById($tplinfo[0]['name']);
                                $userName = $tplinfo[0]['name'] ;
                                $caseattr = Case_attr::getInfoById($tplinfo[0]['attrid']);
                                $text_content = Table_case_tplcontent::getInfoByAttridAndTplid($caseattr[0]['parentid'],$value);
                                if($text_content) {
                                    $text = $text_content?$text_content[0]['content']:'';
                                }
                                $planContentStr.='<strong>['.$userName.']详情:</strong>'.$text;
                            }
                            //清空锅炉典型用户Id数组
                            $kangdaUserTypicalArr = array();
                        }
                        //-----------------------------判断公司普通用户列表------------------------------
                        if(!empty($kangdaUserCommonArr)){
                            $planContentStr.='<h3>公司普通用户</h3><br/>';
                            $planContentStr.='
                                <table class="cmTable" border=1>
                                    <thead>
                                        <tr>';
                            $text = null;
                            $tplinfo = null;
                            $caseattr = null;
                            $text_content = null;
                            $userName = null;

                            //获取parentId对应的属性
                            $tplinfo = Case_tpl::getInfoById($kangdaUserCommonArr[0]);
                            $caseattr = Case_attr::getInfoById($tplinfo[0]['attrid']);
                            $guoluUserAttrs = Case_attr::getListByPid($caseattr[0]['parentid'],0);
                            $theadArray = array();
                            foreach ($guoluUserAttrs as $key=>$attr){
                                $planContentStr.='<th width="20%">'.$attr["name"].'</th>';
                                $theadArray[$key] = $attr["id"];
                            }
                            $planContentStr.='
                                        </tr>
                                    </thead>
                                <tbody >';
                            //-----------------表体-------------------
                            foreach ($kangdaUserCommonArr as $kangdaUserId){
                                $planContentStr.='<tr>';
                                $kangdaContentInfos = Case_tplcontent::getListByTplid($kangdaUserId);
                                foreach ($theadArray as $theadItem){
                                    $tdContent = "";
                                    foreach ($kangdaContentInfos as $kangdaContentInfo){
                                        if($kangdaContentInfo["attrid"] == $theadItem){
                                            $tdContent = $kangdaContentInfo["content"];
                                        }
                                    }
                                    $planContentStr.='<td align="center">'.$tdContent.'</td>';
                                }
                                $planContentStr.='</tr>';
                            }
                            //--------------表尾-------------------
                            $planContentStr.='
                                </tbody>
                            </table> ';
                            //清空锅炉典型用户Id数组
                            $kangdaUserCommonArr = array();
                        }

                        //--------------------------用户名单显示-------【完】----------------------

                        $pInfo = null;
                        if(!empty($contentId)){
                            //plan列表
                            $guoluPlans = Selection_plan::getListByHidandTabtype($contentId,Selection_plan::BOILER_TAB_TYPE);
                            foreach ($guoluPlans as $guoluPlan){
                                $guoluInfo = Guolu_attr::getInfoById($guoluPlan['attrid']);   //对应锅炉信息
                                $pInfo = Products::getInfoById($guoluInfo['proid']);

                                $planContentStr.= "<h3>[".$guoluInfo['version']."]产品介绍：</h3><br/>";

                                //输出到文档
                                if(!empty($pInfo)){
                                    $planContentStr.=$pInfo["desc"]."<br/>";
                                }else{
                                    $planContentStr.="暂无该项信息<br/>";
                                }

                            }
//                            $historyInfo =  Selection_history::getInfoById($contentId) ;
//                            if(!empty($historyInfo)){
//                                $guoluId = $historyInfo["guolu_id"];
//                                if(!empty($guoluId)){
//                                    $guoluInfo = Guolu_attr::getInfoById($guoluId);
//                                    $pInfo = Products::getInfoById($guoluInfo['proid']);
//                                }
//                            }
                        }

                        break;
                    case "productParameter":

                        //---------------------判断锅炉典型用户列表--------------------
                        if(!empty($guoluUserTypicalArr)){
                            $planContentStr.='<h3>工厂典型用户</h3><br/>';
                            foreach ($guoluUserTypicalArr as $key => $value){
                                $text = null;
                                $tplinfo = null;
                                $caseattr = null;
                                $text_content = null;
                                $userName = null;

                                //获取该用户的详情富文本内容
                                $tplinfo = Case_tpl::getInfoById($value);
                                //$userName = Case_attr::getInfoById($tplinfo[0]['name']);
                                $userName = $tplinfo[0]['name'] ;
                                $caseattr = Case_attr::getInfoById($tplinfo[0]['attrid']);
                                $text_content = Table_case_tplcontent::getInfoByAttridAndTplid($caseattr[0]['parentid'],$value);
                                if($text_content) {
                                    $text = $text_content?$text_content[0]['content']:'';
                                }
                                $planContentStr.='<strong>['.$userName.']详情:</strong>'.$text;
                            }
                            //清空锅炉典型用户Id数组
                            $guoluUserTypicalArr = array();
                        }
                        //---------------------判断锅炉普通用户列表------------------------
                        if(!empty($guoluUserCommonArr)){
                            $planContentStr.='<h3>工厂普通用户</h3><br/>';

                            $planContentStr.='
                                <table class="cmTable" border=1>
                                    <thead>
                                        <tr>';
                            $text = null;
                            $tplinfo = null;
                            $caseattr = null;
                            $text_content = null;
                            $userName = null;

                            //获取parentId对应的属性
                            $tplinfo = Case_tpl::getInfoById($guoluUserCommonArr[0]);
                            $caseattr = Case_attr::getInfoById($tplinfo[0]['attrid']);
                            $guoluUserAttrs = Case_attr::getListByPid($caseattr[0]['parentid'],0);
                            $theadArray = array();
                            foreach ($guoluUserAttrs as $key=>$attr){
                                $planContentStr.='<th width="20%">'.$attr["name"].'</th>';
                                $theadArray[$key] = $attr["id"];
                            }
                            $planContentStr.='
                                        </tr>
                                    </thead>
                                <tbody >';
                            //-----------------表体-------------------
                            foreach ($guoluUserCommonArr as $guoluUserId){
                                $planContentStr.='<tr>';
                                $guoluContentInfos = Case_tplcontent::getListByTplid($guoluUserId);
                                foreach ($theadArray as $theadItem){
                                    $tdContent = "";
                                    foreach ($guoluContentInfos as $guoluContentInfo){
                                        if($guoluContentInfo["attrid"] == $theadItem){
                                            $tdContent = $guoluContentInfo["content"];
                                        }
                                    }
                                    $planContentStr.='<td align="center">'.$tdContent.'</td>';
                                }
                                $planContentStr.='</tr>';
                            }
                            //--------------表尾-------------------
                            $planContentStr.='
                                </tbody>
                            </table> ';

                            //清空锅炉典型用户Id数组
                            $guoluUserCommonArr = array();
                        }
                        //------------------------判断公司典型用户列表------------------------------
                        if(!empty($kangdaUserTypicalArr)){
                            $planContentStr.='<h3>公司典型用户</h3><br/>';
                            foreach ($kangdaUserTypicalArr as $key => $value){
                                $text = null;
                                $tplinfo = null;
                                $caseattr = null;
                                $text_content = null;
                                $userName = null;

                                //获取该用户的详情富文本内容
                                $tplinfo = Case_tpl::getInfoById($value);
                                //$userName = Case_attr::getInfoById($tplinfo[0]['name']);
                                $userName = $tplinfo[0]['name'] ;
                                $caseattr = Case_attr::getInfoById($tplinfo[0]['attrid']);
                                $text_content = Table_case_tplcontent::getInfoByAttridAndTplid($caseattr[0]['parentid'],$value);
                                if($text_content) {
                                    $text = $text_content?$text_content[0]['content']:'';
                                }
                                $planContentStr.='<strong>['.$userName.']详情:</strong>'.$text;
                            }
                            //清空锅炉典型用户Id数组
                            $kangdaUserTypicalArr = array();
                        }
                        //-----------------------------判断公司普通用户列表------------------------------
                        if(!empty($kangdaUserCommonArr)){
                            $planContentStr.='<h3>公司普通用户</h3><br/>';
                            $planContentStr.='
                                <table class="cmTable" border=1>
                                    <thead>
                                        <tr>';
                            $text = null;
                            $tplinfo = null;
                            $caseattr = null;
                            $text_content = null;
                            $userName = null;

                            //获取parentId对应的属性
                            $tplinfo = Case_tpl::getInfoById($kangdaUserCommonArr[0]);
                            $caseattr = Case_attr::getInfoById($tplinfo[0]['attrid']);
                            $guoluUserAttrs = Case_attr::getListByPid($caseattr[0]['parentid'],0);
                            $theadArray = array();
                            foreach ($guoluUserAttrs as $key=>$attr){
                                $planContentStr.='<th width="20%">'.$attr["name"].'</th>';
                                $theadArray[$key] = $attr["id"];
                            }
                            $planContentStr.='
                                        </tr>
                                    </thead>
                                <tbody >';
                            //-----------------表体-------------------
                            foreach ($kangdaUserCommonArr as $kangdaUserId){
                                $planContentStr.='<tr>';
                                $kangdaContentInfos = Case_tplcontent::getListByTplid($kangdaUserId);
                                foreach ($theadArray as $theadItem){
                                    $tdContent = "";
                                    foreach ($kangdaContentInfos as $kangdaContentInfo){
                                        if($kangdaContentInfo["attrid"] == $theadItem){
                                            $tdContent = $kangdaContentInfo["content"];
                                        }
                                    }
                                    $planContentStr.='<td align="center">'.$tdContent.'</td>';
                                }
                                $planContentStr.='</tr>';
                            }
                            //--------------表尾-------------------
                            $planContentStr.='
                                </tbody>
                            </table> ';
                            //清空锅炉典型用户Id数组
                            $kangdaUserCommonArr = array();
                        }

                        //--------------------------用户名单显示-------【完】----------------------

                        if(!empty($contentId)){

                            //plan列表
                            $guoluPlans = Selection_plan::getListByHidandTabtype($contentId,Selection_plan::BOILER_TAB_TYPE);

                            //----------------------第一行：锅炉名称-------------------------------
//                            $planContentStr.='
//                                            <table class="cmTable" border=1>
//                                                <thead>
//                                                <tr>
//                                                    <th width="20%">参数\锅炉 </th>
//                                                 ';
//                            for($i = 1;$i <= count($guoluPlans);$i++){
//                                $planContentStr.=' <th width="40%" colspan="2">锅炉['.$i.']</th> ';
//                            }
//
//                            $planContentStr.='
//                                                </tr>
//                                                ';

                            //------------------------第一行：参数型号+单位+参数------------------------
                            $planContentStr.='
                                              <table class="cmTable" border=1>
                                                <thead>
                                                <tr>
                                                    <th width="20%" >技术参数/型号</th>
                                                    <th width="20%">单位</th>
                                              ';

                            for($i = 1;$i <= count($guoluPlans);$i++){
                                $planContentStr.=' 
                                                    <th width="20%">参数</th> ';
                            }

                            $planContentStr.='
                                                </tr>
                                                </thead>
                                                <tbody id="data">';


                            //---------------------------------其余行---------------------------------

                            //foreach ($guoluPlans as $guoluPlan){
                            //    $guoluInfo = Guolu_attr::getInfoById($guoluPlan['attrid']);   //对应锅炉信息

                            //锅炉型号
                                $planContentStr.='
                                    <tr>
                                        <td align="center">锅炉型号</td>
                                        <td align="center">-</td>
                                        ';
                                foreach ($guoluPlans as $key => $value){
                                    $guoluInfo = Guolu_attr::getInfoById($value['attrid']);   //对应锅炉信息
                                    $planContentStr.='
                                        <td align="center">'.$guoluInfo["version"].'</td>
                                     ';
                                }
                                $planContentStr.=' 
                                     </tr>';

                            //锅炉厂家
                            $planContentStr.='
                                    <tr>
                                        <td align="center">锅炉厂家</td>
                                        <td align="center">-</td>
                                        ';
                            foreach ($guoluPlans as $key => $value){
                                $guoluInfo = Guolu_attr::getInfoById($value['attrid']);   //对应锅炉信息
                                $venderDictInfo = Dict::getInfoById($guoluInfo['vender']);
                                $planContentStr.='
                                        <td align="center">'.$venderDictInfo["name"].'</td>
                                     ';
                            }
                            $planContentStr.=' 
                                     </tr>';



                            //额定热输入
                            $planContentStr.='
                                    <tr>
                                        <td align="center">额定热输入</td>
                                        <td align="center">KW</td>
                                        ';
                            foreach ($guoluPlans as $key => $value){
                                $guoluInfo = Guolu_attr::getInfoById($value['attrid']);   //对应锅炉信息
                                $planContentStr.='
                                        <td align="center">'.$guoluInfo["ratedpower"].'</td>
                                     ';
                            }
                            $planContentStr.=' 
                                     </tr>';


                            //额定功率下最小燃气流量
                            $planContentStr.='
                                    <tr>
                                        <td align="center">额定功率下最小燃气流量</td>
                                        <td align="center">m³/h</td>
                                        ';
                            foreach ($guoluPlans as $key => $value){
                                $guoluInfo = Guolu_attr::getInfoById($value['attrid']);   //对应锅炉信息
                                $planContentStr.='
                                        <td align="center">'.$guoluInfo["min_flow"].'</td>
                                     ';
                            }
                            $planContentStr.=' 
                                     </tr>';

                            //额定功率下最大燃气流量
                            $planContentStr.='
                                    <tr>
                                        <td align="center">额定功率下最大燃气流量</td>
                                        <td align="center">m³/h</td>
                                        ';
                            foreach ($guoluPlans as $key => $value){
                                $guoluInfo = Guolu_attr::getInfoById($value['attrid']);   //对应锅炉信息
                                $planContentStr.='
                                        <td align="center">'.$guoluInfo["max_flow"].'</td>
                                     ';
                            }
                            $planContentStr.=' 
                                     </tr>';

                            //最大负荷80℃~60℃热输出
                            $planContentStr.='
                                    <tr>
                                        <td align="center">最大负荷80℃~60℃热输出</td>
                                        <td align="center">kw</td>
                                        ';
                            foreach ($guoluPlans as $key => $value){
                                $guoluInfo = Guolu_attr::getInfoById($value['attrid']);   //对应锅炉信息
                                $planContentStr.='
                                        <td align="center">'.$guoluInfo["heatout_60"].'</td>
                                     ';
                            }
                            $planContentStr.=' 
                                     </tr>';

                            //最大负荷50℃~30℃热输出
                            $planContentStr.='
                                    <tr>
                                        <td align="center">最大负荷50℃~30℃热输出</td>
                                        <td align="center">kw</td>
                                        ';
                            foreach ($guoluPlans as $key => $value){
                                $guoluInfo = Guolu_attr::getInfoById($value['attrid']);   //对应锅炉信息
                                $planContentStr.='
                                        <td align="center">'.$guoluInfo["heatout_30"].'</td>
                                     ';
                            }
                            $planContentStr.=' 
                                     </tr>';
                            //热输入调节范围
                            $planContentStr.='
                                    <tr>
                                        <td align="center">热输入调节范围</td>
                                        <td align="center">kw</td>
                                        ';
                            foreach ($guoluPlans as $key => $value){
                                $guoluInfo = Guolu_attr::getInfoById($value['attrid']);   //对应锅炉信息
                                $planContentStr.='
                                        <td align="center">'.$guoluInfo["heatout_range"].'</td>
                                     ';
                            }
                            $planContentStr.=' 
                                     </tr>';

                            //最大负荷80℃~60℃热效率
                            $planContentStr.='
                                    <tr>
                                        <td align="center">最大负荷80℃~60℃热效率</td>
                                        <td align="center">%</td>
                                        ';
                            foreach ($guoluPlans as $key => $value){
                                $guoluInfo = Guolu_attr::getInfoById($value['attrid']);   //对应锅炉信息
                                $planContentStr.='
                                        <td align="center">'.$guoluInfo["heateffi_80"].'</td>
                                     ';
                            }
                            $planContentStr.=' 
                                     </tr>';

                            //最大负荷50℃~30℃热效率
                            $planContentStr.='
                                    <tr>
                                        <td align="center">最大负荷50℃~30℃热效率</td>
                                        <td align="center">%</td>
                                        ';
                            foreach ($guoluPlans as $key => $value){
                                $guoluInfo = Guolu_attr::getInfoById($value['attrid']);   //对应锅炉信息
                                $planContentStr.='
                                        <td align="center">'.$guoluInfo["heateffi_50"].'</td>
                                     ';
                            }
                            $planContentStr.=' 
                                     </tr>';

                            //30%负荷50℃~30℃热效率
                            $planContentStr.='
                                    <tr>
                                        <td align="center">30%负荷50℃~30℃热效率</td>
                                        <td align="center">%</td>
                                        ';
                            foreach ($guoluPlans as $key => $value){
                                $guoluInfo = Guolu_attr::getInfoById($value['attrid']);   //对应锅炉信息
                                $planContentStr.='
                                        <td align="center">'.$guoluInfo["heateffi_30"].'</td>
                                     ';
                            }
                            $planContentStr.=' 
                                     </tr>';

                            //进水温度
                            $planContentStr.='
                                    <tr>
                                        <td align="center">进水温度</td>
                                        <td align="center">℃</td>
                                        ';
                            foreach ($guoluPlans as $key => $value){
                                $guoluInfo = Guolu_attr::getInfoById($value['attrid']);   //对应锅炉信息
                                $planContentStr.='
                                        <td align="center">'.$guoluInfo["inwater_t"].'</td>
                                     ';
                            }
                            $planContentStr.=' 
                                     </tr>';

                            //出水温度
                            $planContentStr.='
                                    <tr>
                                        <td align="center">出水温度</td>
                                        <td align="center">%</td>
                                        ';
                            foreach ($guoluPlans as $key => $value){
                                $guoluInfo = Guolu_attr::getInfoById($value['attrid']);   //对应锅炉信息
                                $planContentStr.='
                                        <td align="center">'.$guoluInfo["outwater_t"].'</td>
                                     ';
                            }
                            $planContentStr.=' 
                                     </tr>';
                            //最低/最高系统水压
                            $planContentStr.='
                                    <tr>
                                        <td align="center">最低/最高系统水压</td>
                                        <td align="center">bar</td>
                                        ';
                            foreach ($guoluPlans as $key => $value){
                                $guoluInfo = Guolu_attr::getInfoById($value['attrid']);   //对应锅炉信息
                                $planContentStr.='
                                        <td align="center">'.$guoluInfo["syswater_pre"].'</td>
                                     ';
                            }
                            $planContentStr.=' 
                                     </tr>';
                            //供热水能力
                            $planContentStr.='
                                    <tr>
                                        <td align="center">供热水能力</td>
                                        <td align="center">m³/h</td>
                                        ';
                            foreach ($guoluPlans as $key => $value){
                                $guoluInfo = Guolu_attr::getInfoById($value['attrid']);   //对应锅炉信息
                                $planContentStr.='
                                        <td align="center">'.$guoluInfo["heat_capacity"].'</td>
                                     ';
                            }
                            $planContentStr.=' 
                                     </tr>';
                            //最大水流量
                            $planContentStr.='
                                    <tr>
                                        <td align="center">最大水流量</td>
                                        <td align="center">m³/h</td>
                                        ';
                            foreach ($guoluPlans as $key => $value){
                                $guoluInfo = Guolu_attr::getInfoById($value['attrid']);   //对应锅炉信息
                                $planContentStr.='
                                        <td align="center">'.$guoluInfo["hot_flow"].'</td>
                                     ';
                            }
                            $planContentStr.=' 
                                     </tr>';

                            //烟气温度
                            $planContentStr.='
                                    <tr>
                                        <td align="center">烟气温度（最大负荷80℃~60℃）</td>
                                        <td align="center">℃</td>
                                        ';
                            foreach ($guoluPlans as $key => $value){
                                $guoluInfo = Guolu_attr::getInfoById($value['attrid']);   //对应锅炉信息
                                $planContentStr.='
                                        <td align="center">'.$guoluInfo["fluegas_80"].'</td>
                                     ';
                            }
                            $planContentStr.=' 
                                     </tr>';

                            //烟气温度
                            $planContentStr.='
                                    <tr>
                                        <td align="center">烟气温度（最大负荷50℃~30℃）</td>
                                        <td align="center">℃</td>
                                        ';
                            foreach ($guoluPlans as $key => $value){
                                $guoluInfo = Guolu_attr::getInfoById($value['attrid']);   //对应锅炉信息
                                $planContentStr.='
                                        <td align="center">'.$guoluInfo["fluegas_50"].'</td>
                                     ';
                            }
                            $planContentStr.=' 
                                     </tr>';
                            //CO排放
                            $planContentStr.='
                                    <tr>
                                        <td align="center">CO排放</td>
                                        <td align="center">mg/m³</td>
                                        ';
                            foreach ($guoluPlans as $key => $value){
                                $guoluInfo = Guolu_attr::getInfoById($value['attrid']);   //对应锅炉信息
                                $planContentStr.='
                                        <td align="center">'.$guoluInfo["emission_co"].'</td>
                                     ';
                            }
                            $planContentStr.=' 
                                     </tr>';
                            //NOx排放
                            $planContentStr.='
                                    <tr>
                                        <td align="center">NOx排放</td>
                                        <td align="center">mg/m³</td>
                                        ';
                            foreach ($guoluPlans as $key => $value){
                                $guoluInfo = Guolu_attr::getInfoById($value['attrid']);   //对应锅炉信息
                                $planContentStr.='
                                        <td align="center">'.$guoluInfo["emission_nox"].'</td>
                                     ';
                            }
                            $planContentStr.=' 
                                     </tr>';
                            //最大冷凝水排量
                            $planContentStr.='
                                    <tr>
                                        <td align="center">最大冷凝水排量</td>
                                        <td align="center">L/h</td>
                                        ';
                            foreach ($guoluPlans as $key => $value){
                                $guoluInfo = Guolu_attr::getInfoById($value['attrid']);   //对应锅炉信息
                                $planContentStr.='
                                        <td align="center">'.$guoluInfo["condensed_max"].'</td>
                                     ';
                            }
                            $planContentStr.=' 
                                     </tr>';
                            //冷凝水PH值
                            $planContentStr.='
                                    <tr>
                                        <td align="center">冷凝水PH值</td>
                                        <td align="center">-</td>
                                        ';
                            foreach ($guoluPlans as $key => $value){
                                $guoluInfo = Guolu_attr::getInfoById($value['attrid']);   //对应锅炉信息
                                $planContentStr.='
                                        <td align="center">'.$guoluInfo["condensed_ph"].'</td>
                                     ';
                            }
                            $planContentStr.=' 
                                     </tr>';
                            //烟道接口φ
                            $planContentStr.='
                                    <tr>
                                        <td align="center">烟道接口φ</td>
                                        <td align="center">mm</td>
                                        ';
                            foreach ($guoluPlans as $key => $value){
                                $guoluInfo = Guolu_attr::getInfoById($value['attrid']);   //对应锅炉信息
                                $planContentStr.='
                                        <td align="center">'.$guoluInfo["flue_interface"].'</td>
                                     ';
                            }
                            $planContentStr.=' 
                                     </tr>';
                            //燃气接口
                            $planContentStr.='
                                    <tr>
                                        <td align="center">燃气接口</td>
                                        <td align="center">DN</td>
                                        ';
                            foreach ($guoluPlans as $key => $value){
                                $guoluInfo = Guolu_attr::getInfoById($value['attrid']);   //对应锅炉信息
                                $planContentStr.='
                                        <td align="center">'.$guoluInfo["gas_interface"].'</td>
                                     ';
                            }
                            $planContentStr.=' 
                                     </tr>';
                            //进出水接口
                            $planContentStr.='
                                    <tr>
                                        <td align="center">进出水接口</td>
                                        <td align="center">DN</td>
                                        ';
                            foreach ($guoluPlans as $key => $value){
                                $guoluInfo = Guolu_attr::getInfoById($value['attrid']);   //对应锅炉信息
                                $planContentStr.='
                                        <td align="center">'.$guoluInfo["iowater_interface"].'</td>
                                     ';
                            }
                            $planContentStr.=' 
                                     </tr>';
                            //锅炉水容量
                            $planContentStr.='
                                    <tr>
                                        <td align="center">锅炉水容量</td>
                                        <td align="center">L</td>
                                        ';
                            foreach ($guoluPlans as $key => $value){
                                $guoluInfo = Guolu_attr::getInfoById($value['attrid']);   //对应锅炉信息
                                $planContentStr.='
                                        <td align="center">'.$guoluInfo["water"].'</td>
                                     ';
                            }
                            $planContentStr.=' 
                                     </tr>';
                            //燃气类型
                            $planContentStr.='
                                    <tr>
                                        <td align="center">燃气类型</td>
                                        <td align="center">-</td>
                                        ';
                            foreach ($guoluPlans as $key => $value){
                                $guoluInfo = Guolu_attr::getInfoById($value['attrid']);   //对应锅炉信息
                                $planContentStr.='
                                        <td align="center">'.$guoluInfo["gas_type"].'</td>
                                     ';
                            }
                            $planContentStr.=' 
                                     </tr>';
                            //额定燃气压力
                            $planContentStr.='
                                    <tr>
                                        <td align="center">额定燃气压力(动压)</td>
                                        <td align="center">Pa</td>
                                        ';
                            foreach ($guoluPlans as $key => $value){
                                $guoluInfo = Guolu_attr::getInfoById($value['attrid']);   //对应锅炉信息
                                $planContentStr.='
                                        <td align="center">'.$guoluInfo["gas_press"].'</td>
                                     ';
                            }
                            $planContentStr.=' 
                                     </tr>';
                            //燃气工作压力范围
                            $planContentStr.='
                                    <tr>
                                        <td align="center">燃气工作压力范围(动压)</td>
                                        <td align="center">Pa</td>
                                        ';
                            foreach ($guoluPlans as $key => $value){
                                $guoluInfo = Guolu_attr::getInfoById($value['attrid']);   //对应锅炉信息
                                $planContentStr.='
                                        <td align="center">'.$guoluInfo["gaspre_range"].'</td>
                                     ';
                            }
                            $planContentStr.=' 
                                     </tr>';

                            //能效等级
                            $planContentStr.='
                                    <tr>
                                        <td align="center">能效等级</td>
                                        <td align="center">-</td>
                                        ';
                            foreach ($guoluPlans as $key => $value){
                                $guoluInfo = Guolu_attr::getInfoById($value['attrid']);   //对应锅炉信息
                                $planContentStr.='
                                        <td align="center">'.$guoluInfo["energy_level"].'</td>
                                     ';
                            }
                            $planContentStr.=' 
                                     </tr>';

                            //锅炉长度
                            $planContentStr.='
                                    <tr>
                                        <td align="center">锅炉长度</td>
                                        <td align="center">mm</td>
                                        ';
                            foreach ($guoluPlans as $key => $value){
                                $guoluInfo = Guolu_attr::getInfoById($value['attrid']);   //对应锅炉信息
                                $planContentStr.='
                                        <td align="center">'.$guoluInfo["length"].'</td>
                                     ';
                            }
                            $planContentStr.=' 
                                     </tr>';
                            //锅炉宽度
                            $planContentStr.='
                                    <tr>
                                        <td align="center">锅炉宽度</td>
                                        <td align="center">mm</td>
                                        ';
                            foreach ($guoluPlans as $key => $value){
                                $guoluInfo = Guolu_attr::getInfoById($value['attrid']);   //对应锅炉信息
                                $planContentStr.='
                                        <td align="center">'.$guoluInfo["width"].'</td>
                                     ';
                            }
                            $planContentStr.=' 
                                     </tr>';
                            //高度
                            $planContentStr.='
                                    <tr>
                                        <td align="center">高度（含脚和烟囱连接）</td>
                                        <td align="center">mm</td>
                                        ';
                            foreach ($guoluPlans as $key => $value){
                                $guoluInfo = Guolu_attr::getInfoById($value['attrid']);   //对应锅炉信息
                                $planContentStr.='
                                        <td align="center">'.$guoluInfo["height"].'</td>
                                     ';
                            }
                            $planContentStr.=' 
                                     </tr>';

                            //重量
                            $planContentStr.='
                                    <tr>
                                        <td align="center">重量（净）</td>
                                        <td align="center">kg</td>
                                        ';
                            foreach ($guoluPlans as $key => $value){
                                $guoluInfo = Guolu_attr::getInfoById($value['attrid']);   //对应锅炉信息
                                $planContentStr.='
                                        <td align="center">'.$guoluInfo["net_weight"].'</td>
                                     ';
                            }
                            $planContentStr.=' 
                                     </tr>';
                            //参考供热面积
                            $planContentStr.='
                                    <tr>
                                        <td align="center">参考供热面积</td>
                                        <td align="center">㎡</td>
                                        ';
                            foreach ($guoluPlans as $key => $value){
                                $guoluInfo = Guolu_attr::getInfoById($value['attrid']);   //对应锅炉信息
                                $planContentStr.='
                                        <td align="center">'.$guoluInfo["refer_heatarea"].'</td>
                                     ';
                            }
                            $planContentStr.=' 
                                     </tr>';
                            //电源
                            $planContentStr.='
                                    <tr>
                                        <td align="center">电源</td>
                                        <td align="center">V/Hz</td>
                                        ';
                            foreach ($guoluPlans as $key => $value){
                                $guoluInfo = Guolu_attr::getInfoById($value['attrid']);   //对应锅炉信息
                                $planContentStr.='
                                        <td align="center">'.$guoluInfo["power_supply"].'</td>
                                     ';
                            }
                            $planContentStr.=' 
                                     </tr>';
                            //噪音
                            $planContentStr.='
                                    <tr>
                                        <td align="center">噪音</td>
                                        <td align="center">dB</td>
                                        ';
                            foreach ($guoluPlans as $key => $value){
                                $guoluInfo = Guolu_attr::getInfoById($value['attrid']);   //对应锅炉信息
                                $planContentStr.='
                                        <td align="center">'.$guoluInfo["noise"].'</td>
                                     ';
                            }
                            $planContentStr.=' 
                                     </tr>';
                            //最大耗电量
                            $planContentStr.='
                                    <tr>
                                        <td align="center">最大耗电量</td>
                                        <td align="center">W</td>
                                        ';
                            foreach ($guoluPlans as $key => $value){
                                $guoluInfo = Guolu_attr::getInfoById($value['attrid']);   //对应锅炉信息
                                $planContentStr.='
                                        <td align="center">'.$guoluInfo["electric_power"].'</td>
                                     ';
                            }
                            $planContentStr.=' 
                                     </tr>';

                            //-----------------表尾-------------------
                            $planContentStr.=' 
                                                </tbody>
                                            </table> ';
                        }

                        break;
                    //锅炉用户-典型
                    case "boilerUserListTypical":
                        array_push($guoluUserTypicalArr,$contentId);
                        break;
                    //锅炉用户-普通
                    case "boilerUserListCommon":
                        array_push($guoluUserCommonArr,$contentId);
                        break;
                    //康达用户-典型
                    case "kangdaUserListTypical":
                        array_push($kangdaUserTypicalArr,$contentId);
                        break;
                    //康达用户-普通
                    case "kangdaUserListCommon":
                        array_push($kangdaUserCommonArr,$contentId);
                        break;
//                    case "boilerUserList":
//                        array_push($guoluUserIdArr,$contentId);
//                        //$guoluUserIdArr[$guoluUserIndex] = $contentId;
////                        $info = Case_tplcontent::getContentByTplcontentId($contentId) ;
////                        $planContentStr.=$info[0]["content"];
//                        break;
//                    case "kangdaUserList":
//                        array_push($kangdaUserIdArr,$contentId);
//                        //$kangdaUserIdArr[$kangdaUserIndex] = $contentId;
////                        $info = Case_tplcontent::getContentByTplcontentId($contentId) ;
////                        $planContentStr.=$info[0]["content"];
//                        break;
                    case "afterSaleIntroduction":

                        //---------------------判断锅炉典型用户列表--------------------
                        if(!empty($guoluUserTypicalArr)){
                            $planContentStr.='<h3>工厂典型用户</h3><br/>';
                            foreach ($guoluUserTypicalArr as $key => $value){
                                $text = null;
                                $tplinfo = null;
                                $caseattr = null;
                                $text_content = null;
                                $userName = null;

                                //获取该用户的详情富文本内容
                                $tplinfo = Case_tpl::getInfoById($value);
                                //$userName = Case_attr::getInfoById($tplinfo[0]['name']);
                                $userName = $tplinfo[0]['name'] ;
                                $caseattr = Case_attr::getInfoById($tplinfo[0]['attrid']);
                                $text_content = Table_case_tplcontent::getInfoByAttridAndTplid($caseattr[0]['parentid'],$value);
                                if($text_content) {
                                    $text = $text_content?$text_content[0]['content']:'';
                                }
                                $planContentStr.='<strong>['.$userName.']详情:</strong>'.$text;
                            }
                            //清空锅炉典型用户Id数组
                            $guoluUserTypicalArr = array();
                        }
                        //---------------------判断锅炉普通用户列表------------------------
                        if(!empty($guoluUserCommonArr)){
                            $planContentStr.='<h3>工厂普通用户</h3><br/>';

                            $planContentStr.='
                                <table class="cmTable" border=1>
                                    <thead>
                                        <tr>';
                            $text = null;
                            $tplinfo = null;
                            $caseattr = null;
                            $text_content = null;
                            $userName = null;

                            //获取parentId对应的属性
                            $tplinfo = Case_tpl::getInfoById($guoluUserCommonArr[0]);
                            $caseattr = Case_attr::getInfoById($tplinfo[0]['attrid']);
                            $guoluUserAttrs = Case_attr::getListByPid($caseattr[0]['parentid'],0);
                            $theadArray = array();
                            foreach ($guoluUserAttrs as $key=>$attr){
                                $planContentStr.='<th width="20%">'.$attr["name"].'</th>';
                                $theadArray[$key] = $attr["id"];
                            }
                            $planContentStr.='
                                        </tr>
                                    </thead>
                                <tbody >';
                            //-----------------表体-------------------
                            foreach ($guoluUserCommonArr as $guoluUserId){
                                $planContentStr.='<tr>';
                                $guoluContentInfos = Case_tplcontent::getListByTplid($guoluUserId);
                                foreach ($theadArray as $theadItem){
                                    $tdContent = "";
                                    foreach ($guoluContentInfos as $guoluContentInfo){
                                        if($guoluContentInfo["attrid"] == $theadItem){
                                            $tdContent = $guoluContentInfo["content"];
                                        }
                                    }
                                    $planContentStr.='<td align="center">'.$tdContent.'</td>';
                                }
                                $planContentStr.='</tr>';
                            }
                            //--------------表尾-------------------
                            $planContentStr.='
                                </tbody>
                            </table> ';

                            //清空锅炉典型用户Id数组
                            $guoluUserCommonArr = array();
                        }
                        //------------------------判断公司典型用户列表------------------------------
                        if(!empty($kangdaUserTypicalArr)){
                            $planContentStr.='<h3>公司典型用户</h3><br/>';
                            foreach ($kangdaUserTypicalArr as $key => $value){
                                $text = null;
                                $tplinfo = null;
                                $caseattr = null;
                                $text_content = null;
                                $userName = null;

                                //获取该用户的详情富文本内容
                                $tplinfo = Case_tpl::getInfoById($value);
                                //$userName = Case_attr::getInfoById($tplinfo[0]['name']);
                                $userName = $tplinfo[0]['name'] ;
                                $caseattr = Case_attr::getInfoById($tplinfo[0]['attrid']);
                                $text_content = Table_case_tplcontent::getInfoByAttridAndTplid($caseattr[0]['parentid'],$value);
                                if($text_content) {
                                    $text = $text_content?$text_content[0]['content']:'';
                                }
                                $planContentStr.='<strong>['.$userName.']详情:</strong>'.$text;
                            }
                            //清空锅炉典型用户Id数组
                            $kangdaUserTypicalArr = array();
                        }
                        //-----------------------------判断公司普通用户列表------------------------------
                        if(!empty($kangdaUserCommonArr)){
                            $planContentStr.='<h3>公司普通用户</h3><br/>';
                            $planContentStr.='
                                <table class="cmTable" border=1>
                                    <thead>
                                        <tr>';
                            $text = null;
                            $tplinfo = null;
                            $caseattr = null;
                            $text_content = null;
                            $userName = null;

                            //获取parentId对应的属性
                            $tplinfo = Case_tpl::getInfoById($kangdaUserCommonArr[0]);
                            $caseattr = Case_attr::getInfoById($tplinfo[0]['attrid']);
                            $guoluUserAttrs = Case_attr::getListByPid($caseattr[0]['parentid'],0);
                            $theadArray = array();
                            foreach ($guoluUserAttrs as $key=>$attr){
                                $planContentStr.='<th width="20%">'.$attr["name"].'</th>';
                                $theadArray[$key] = $attr["id"];
                            }
                            $planContentStr.='
                                        </tr>
                                    </thead>
                                <tbody >';
                            //-----------------表体-------------------
                            foreach ($kangdaUserCommonArr as $kangdaUserId){
                                $planContentStr.='<tr>';
                                $kangdaContentInfos = Case_tplcontent::getListByTplid($kangdaUserId);
                                foreach ($theadArray as $theadItem){
                                    $tdContent = "";
                                    foreach ($kangdaContentInfos as $kangdaContentInfo){
                                        if($kangdaContentInfo["attrid"] == $theadItem){
                                            $tdContent = $kangdaContentInfo["content"];
                                        }
                                    }
                                    $planContentStr.='<td align="center">'.$tdContent.'</td>';
                                }
                                $planContentStr.='</tr>';
                            }
                            //--------------表尾-------------------
                            $planContentStr.='
                                </tbody>
                            </table> ';
                            //清空锅炉典型用户Id数组
                            $kangdaUserCommonArr = array();
                        }

                        //--------------------------用户名单显示-------【完】----------------------

                        $info = Case_tplcontent::getContentByTplcontentId($contentId) ;
                        $planContentStr.=$info[0]["content"];
                        break;
                    case "afterSaleCompetency":

                        //---------------------判断锅炉典型用户列表--------------------
                        if(!empty($guoluUserTypicalArr)){
                            $planContentStr.='<h3>工厂典型用户</h3><br/>';
                            foreach ($guoluUserTypicalArr as $key => $value){
                                $text = null;
                                $tplinfo = null;
                                $caseattr = null;
                                $text_content = null;
                                $userName = null;

                                //获取该用户的详情富文本内容
                                $tplinfo = Case_tpl::getInfoById($value);
                                //$userName = Case_attr::getInfoById($tplinfo[0]['name']);
                                $userName = $tplinfo[0]['name'] ;
                                $caseattr = Case_attr::getInfoById($tplinfo[0]['attrid']);
                                $text_content = Table_case_tplcontent::getInfoByAttridAndTplid($caseattr[0]['parentid'],$value);
                                if($text_content) {
                                    $text = $text_content?$text_content[0]['content']:'';
                                }
                                $planContentStr.='<strong>['.$userName.']详情:</strong>'.$text;
                            }
                            //清空锅炉典型用户Id数组
                            $guoluUserTypicalArr = array();
                        }
                        //---------------------判断锅炉普通用户列表------------------------
                        if(!empty($guoluUserCommonArr)){
                            $planContentStr.='<h3>工厂普通用户</h3><br/>';

                            $planContentStr.='
                                <table class="cmTable" border=1>
                                    <thead>
                                        <tr>';
                            $text = null;
                            $tplinfo = null;
                            $caseattr = null;
                            $text_content = null;
                            $userName = null;

                            //获取parentId对应的属性
                            $tplinfo = Case_tpl::getInfoById($guoluUserCommonArr[0]);
                            $caseattr = Case_attr::getInfoById($tplinfo[0]['attrid']);
                            $guoluUserAttrs = Case_attr::getListByPid($caseattr[0]['parentid'],0);
                            $theadArray = array();
                            foreach ($guoluUserAttrs as $key=>$attr){
                                $planContentStr.='<th width="20%">'.$attr["name"].'</th>';
                                $theadArray[$key] = $attr["id"];
                            }
                            $planContentStr.='
                                        </tr>
                                    </thead>
                                <tbody >';
                            //-----------------表体-------------------
                            foreach ($guoluUserCommonArr as $guoluUserId){
                                $planContentStr.='<tr>';
                                $guoluContentInfos = Case_tplcontent::getListByTplid($guoluUserId);
                                foreach ($theadArray as $theadItem){
                                    $tdContent = "";
                                    foreach ($guoluContentInfos as $guoluContentInfo){
                                        if($guoluContentInfo["attrid"] == $theadItem){
                                            $tdContent = $guoluContentInfo["content"];
                                        }
                                    }
                                    $planContentStr.='<td align="center">'.$tdContent.'</td>';
                                }
                                $planContentStr.='</tr>';
                            }
                            //--------------表尾-------------------
                            $planContentStr.='
                                </tbody>
                            </table> ';

                            //清空锅炉典型用户Id数组
                            $guoluUserCommonArr = array();
                        }
                        //------------------------判断公司典型用户列表------------------------------
                        if(!empty($kangdaUserTypicalArr)){
                            $planContentStr.='<h3>公司典型用户</h3><br/>';
                            foreach ($kangdaUserTypicalArr as $key => $value){
                                $text = null;
                                $tplinfo = null;
                                $caseattr = null;
                                $text_content = null;
                                $userName = null;

                                //获取该用户的详情富文本内容
                                $tplinfo = Case_tpl::getInfoById($value);
                                //$userName = Case_attr::getInfoById($tplinfo[0]['name']);
                                $userName = $tplinfo[0]['name'] ;
                                $caseattr = Case_attr::getInfoById($tplinfo[0]['attrid']);
                                $text_content = Table_case_tplcontent::getInfoByAttridAndTplid($caseattr[0]['parentid'],$value);
                                if($text_content) {
                                    $text = $text_content?$text_content[0]['content']:'';
                                }
                                $planContentStr.='<strong>['.$userName.']详情:</strong>'.$text;
                            }
                            //清空锅炉典型用户Id数组
                            $kangdaUserTypicalArr = array();
                        }
                        //-----------------------------判断公司普通用户列表------------------------------
                        if(!empty($kangdaUserCommonArr)){
                            $planContentStr.='<h3>公司普通用户</h3><br/>';
                            $planContentStr.='
                                <table class="cmTable" border=1>
                                    <thead>
                                        <tr>';
                            $text = null;
                            $tplinfo = null;
                            $caseattr = null;
                            $text_content = null;
                            $userName = null;

                            //获取parentId对应的属性
                            $tplinfo = Case_tpl::getInfoById($kangdaUserCommonArr[0]);
                            $caseattr = Case_attr::getInfoById($tplinfo[0]['attrid']);
                            $guoluUserAttrs = Case_attr::getListByPid($caseattr[0]['parentid'],0);
                            $theadArray = array();
                            foreach ($guoluUserAttrs as $key=>$attr){
                                $planContentStr.='<th width="20%">'.$attr["name"].'</th>';
                                $theadArray[$key] = $attr["id"];
                            }
                            $planContentStr.='
                                        </tr>
                                    </thead>
                                <tbody >';
                            //-----------------表体-------------------
                            foreach ($kangdaUserCommonArr as $kangdaUserId){
                                $planContentStr.='<tr>';
                                $kangdaContentInfos = Case_tplcontent::getListByTplid($kangdaUserId);
                                foreach ($theadArray as $theadItem){
                                    $tdContent = "";
                                    foreach ($kangdaContentInfos as $kangdaContentInfo){
                                        if($kangdaContentInfo["attrid"] == $theadItem){
                                            $tdContent = $kangdaContentInfo["content"];
                                        }
                                    }
                                    $planContentStr.='<td align="center">'.$tdContent.'</td>';
                                }
                                $planContentStr.='</tr>';
                            }
                            //--------------表尾-------------------
                            $planContentStr.='
                                </tbody>
                            </table> ';
                            //清空锅炉典型用户Id数组
                            $kangdaUserCommonArr = array();
                        }

                        //--------------------------用户名单显示-------【完】----------------------

                        $info = Case_tplcontent::getContentByTplcontentId($contentId) ;
                        $planContentStr.=$info[0]["content"];
                        break;
                    case "otherParameter":

                        //---------------------判断锅炉典型用户列表--------------------
                        if(!empty($guoluUserTypicalArr)){
                            $planContentStr.='<h3>工厂典型用户</h3><br/>';
                            foreach ($guoluUserTypicalArr as $key => $value){
                                $text = null;
                                $tplinfo = null;
                                $caseattr = null;
                                $text_content = null;
                                $userName = null;

                                //获取该用户的详情富文本内容
                                $tplinfo = Case_tpl::getInfoById($value);
                                //$userName = Case_attr::getInfoById($tplinfo[0]['name']);
                                $userName = $tplinfo[0]['name'] ;
                                $caseattr = Case_attr::getInfoById($tplinfo[0]['attrid']);
                                $text_content = Table_case_tplcontent::getInfoByAttridAndTplid($caseattr[0]['parentid'],$value);
                                if($text_content) {
                                    $text = $text_content?$text_content[0]['content']:'';
                                }
                                $planContentStr.='<strong>['.$userName.']详情:</strong>'.$text;
                            }
                            //清空锅炉典型用户Id数组
                            $guoluUserTypicalArr = array();
                        }
                        //---------------------判断锅炉普通用户列表------------------------
                        if(!empty($guoluUserCommonArr)){
                            $planContentStr.='<h3>工厂普通用户</h3><br/>';

                            $planContentStr.='
                                <table class="cmTable" border=1>
                                    <thead>
                                        <tr>';
                            $text = null;
                            $tplinfo = null;
                            $caseattr = null;
                            $text_content = null;
                            $userName = null;

                            //获取parentId对应的属性
                            $tplinfo = Case_tpl::getInfoById($guoluUserCommonArr[0]);
                            $caseattr = Case_attr::getInfoById($tplinfo[0]['attrid']);
                            $guoluUserAttrs = Case_attr::getListByPid($caseattr[0]['parentid'],0);
                            $theadArray = array();
                            foreach ($guoluUserAttrs as $key=>$attr){
                                $planContentStr.='<th width="20%">'.$attr["name"].'</th>';
                                $theadArray[$key] = $attr["id"];
                            }
                            $planContentStr.='
                                        </tr>
                                    </thead>
                                <tbody >';
                            //-----------------表体-------------------
                            foreach ($guoluUserCommonArr as $guoluUserId){
                                $planContentStr.='<tr>';
                                $guoluContentInfos = Case_tplcontent::getListByTplid($guoluUserId);
                                foreach ($theadArray as $theadItem){
                                    $tdContent = "";
                                    foreach ($guoluContentInfos as $guoluContentInfo){
                                        if($guoluContentInfo["attrid"] == $theadItem){
                                            $tdContent = $guoluContentInfo["content"];
                                        }
                                    }
                                    $planContentStr.='<td align="center">'.$tdContent.'</td>';
                                }
                                $planContentStr.='</tr>';
                            }
                            //--------------表尾-------------------
                            $planContentStr.='
                                </tbody>
                            </table> ';

                            //清空锅炉典型用户Id数组
                            $guoluUserCommonArr = array();
                        }
                        //------------------------判断公司典型用户列表------------------------------
                        if(!empty($kangdaUserTypicalArr)){
                            $planContentStr.='<h3>公司典型用户</h3><br/>';
                            foreach ($kangdaUserTypicalArr as $key => $value){
                                $text = null;
                                $tplinfo = null;
                                $caseattr = null;
                                $text_content = null;
                                $userName = null;

                                //获取该用户的详情富文本内容
                                $tplinfo = Case_tpl::getInfoById($value);
                                //$userName = Case_attr::getInfoById($tplinfo[0]['name']);
                                $userName = $tplinfo[0]['name'] ;
                                $caseattr = Case_attr::getInfoById($tplinfo[0]['attrid']);
                                $text_content = Table_case_tplcontent::getInfoByAttridAndTplid($caseattr[0]['parentid'],$value);
                                if($text_content) {
                                    $text = $text_content?$text_content[0]['content']:'';
                                }
                                $planContentStr.='<strong>['.$userName.']详情:</strong>'.$text;
                            }
                            //清空锅炉典型用户Id数组
                            $kangdaUserTypicalArr = array();
                        }
                        //-----------------------------判断公司普通用户列表------------------------------
                        if(!empty($kangdaUserCommonArr)){
                            $planContentStr.='<h3>公司普通用户</h3><br/>';
                            $planContentStr.='
                                <table class="cmTable" border=1>
                                    <thead>
                                        <tr>';
                            $text = null;
                            $tplinfo = null;
                            $caseattr = null;
                            $text_content = null;
                            $userName = null;

                            //获取parentId对应的属性
                            $tplinfo = Case_tpl::getInfoById($kangdaUserCommonArr[0]);
                            $caseattr = Case_attr::getInfoById($tplinfo[0]['attrid']);
                            $guoluUserAttrs = Case_attr::getListByPid($caseattr[0]['parentid'],0);
                            $theadArray = array();
                            foreach ($guoluUserAttrs as $key=>$attr){
                                $planContentStr.='<th width="20%">'.$attr["name"].'</th>';
                                $theadArray[$key] = $attr["id"];
                            }
                            $planContentStr.='
                                        </tr>
                                    </thead>
                                <tbody >';
                            //-----------------表体-------------------
                            foreach ($kangdaUserCommonArr as $kangdaUserId){
                                $planContentStr.='<tr>';
                                $kangdaContentInfos = Case_tplcontent::getListByTplid($kangdaUserId);
                                foreach ($theadArray as $theadItem){
                                    $tdContent = "";
                                    foreach ($kangdaContentInfos as $kangdaContentInfo){
                                        if($kangdaContentInfo["attrid"] == $theadItem){
                                            $tdContent = $kangdaContentInfo["content"];
                                        }
                                    }
                                    $planContentStr.='<td align="center">'.$tdContent.'</td>';
                                }
                                $planContentStr.='</tr>';
                            }
                            //--------------表尾-------------------
                            $planContentStr.='
                                </tbody>
                            </table> ';
                            //清空锅炉典型用户Id数组
                            $kangdaUserCommonArr = array();
                        }

                        //--------------------------用户名单显示-------【完】----------------------

                        $info = Case_tplcontent::getContentByTplcontentId($contentId) ;
                        $planContentStr.=$info[0]["content"];
                        break;
                }
            }else{
                if($content == ","){
                    $planContentStr.="";
                }else{
                    //---------------------判断锅炉典型用户列表--------------------
                    if(!empty($guoluUserTypicalArr)){
                        $planContentStr.='<h3>工厂典型用户</h3><br/>';
                        foreach ($guoluUserTypicalArr as $key => $value){
                            $text = null;
                            $tplinfo = null;
                            $caseattr = null;
                            $text_content = null;
                            $userName = null;

                            //获取该用户的详情富文本内容
                            $tplinfo = Case_tpl::getInfoById($value);
                            //$userName = Case_attr::getInfoById($tplinfo[0]['name']);
                            $userName = $tplinfo[0]['name'] ;
                            $caseattr = Case_attr::getInfoById($tplinfo[0]['attrid']);
                            $text_content = Table_case_tplcontent::getInfoByAttridAndTplid($caseattr[0]['parentid'],$value);
                            if($text_content) {
                                $text = $text_content?$text_content[0]['content']:'';
                            }
                            $planContentStr.='<strong>['.$userName.']详情:</strong>'.$text;
                        }
                        //清空锅炉典型用户Id数组
                        $guoluUserTypicalArr = array();
                    }
                    //---------------------判断锅炉普通用户列表------------------------
                    if(!empty($guoluUserCommonArr)){
                        $planContentStr.='<h3>工厂普通用户</h3><br/>';

                        $planContentStr.='
                                <table class="cmTable" border=1>
                                    <thead>
                                        <tr>';
                        $text = null;
                        $tplinfo = null;
                        $caseattr = null;
                        $text_content = null;
                        $userName = null;

                        //获取parentId对应的属性
                        $tplinfo = Case_tpl::getInfoById($guoluUserCommonArr[0]);
                        $caseattr = Case_attr::getInfoById($tplinfo[0]['attrid']);
                        $guoluUserAttrs = Case_attr::getListByPid($caseattr[0]['parentid'],0);
                        $theadArray = array();
                        foreach ($guoluUserAttrs as $key=>$attr){
                            $planContentStr.='<th width="20%">'.$attr["name"].'</th>';
                            $theadArray[$key] = $attr["id"];
                        }
                        $planContentStr.='
                                        </tr>
                                    </thead>
                                <tbody >';
                        //-----------------表体-------------------
                        foreach ($guoluUserCommonArr as $guoluUserId){
                            $planContentStr.='<tr>';
                            $guoluContentInfos = Case_tplcontent::getListByTplid($guoluUserId);
                            foreach ($theadArray as $theadItem){
                                $tdContent = "";
                                foreach ($guoluContentInfos as $guoluContentInfo){
                                    if($guoluContentInfo["attrid"] == $theadItem){
                                        $tdContent = $guoluContentInfo["content"];
                                    }
                                }
                                $planContentStr.='<td align="center">'.$tdContent.'</td>';
                            }
                            $planContentStr.='</tr>';
                        }
                        //--------------表尾-------------------
                        $planContentStr.='
                                </tbody>
                            </table> ';

                        //清空锅炉典型用户Id数组
                        $guoluUserCommonArr = array();
                    }
                    //------------------------判断公司典型用户列表------------------------------
                    if(!empty($kangdaUserTypicalArr)){
                        $planContentStr.='<h3>公司典型用户</h3><br/>';
                        foreach ($kangdaUserTypicalArr as $key => $value){
                            $text = null;
                            $tplinfo = null;
                            $caseattr = null;
                            $text_content = null;
                            $userName = null;

                            //获取该用户的详情富文本内容
                            $tplinfo = Case_tpl::getInfoById($value);
                            //$userName = Case_attr::getInfoById($tplinfo[0]['name']);
                            $userName = $tplinfo[0]['name'] ;
                            $caseattr = Case_attr::getInfoById($tplinfo[0]['attrid']);
                            $text_content = Table_case_tplcontent::getInfoByAttridAndTplid($caseattr[0]['parentid'],$value);
                            if($text_content) {
                                $text = $text_content?$text_content[0]['content']:'';
                            }
                            $planContentStr.='<strong>['.$userName.']详情:</strong>'.$text;
                        }
                        //清空锅炉典型用户Id数组
                        $kangdaUserTypicalArr = array();
                    }
                    //-----------------------------判断公司普通用户列表------------------------------
                    if(!empty($kangdaUserCommonArr)){
                        $planContentStr.='<h3>公司普通用户</h3><br/>';
                        $planContentStr.='
                                <table class="cmTable" border=1>
                                    <thead>
                                        <tr>';
                        $text = null;
                        $tplinfo = null;
                        $caseattr = null;
                        $text_content = null;
                        $userName = null;

                        //获取parentId对应的属性
                        $tplinfo = Case_tpl::getInfoById($kangdaUserCommonArr[0]);
                        $caseattr = Case_attr::getInfoById($tplinfo[0]['attrid']);
                        $guoluUserAttrs = Case_attr::getListByPid($caseattr[0]['parentid'],0);
                        $theadArray = array();
                        foreach ($guoluUserAttrs as $key=>$attr){
                            $planContentStr.='<th width="20%">'.$attr["name"].'</th>';
                            $theadArray[$key] = $attr["id"];
                        }
                        $planContentStr.='
                                        </tr>
                                    </thead>
                                <tbody >';
                        //-----------------表体-------------------
                        foreach ($kangdaUserCommonArr as $kangdaUserId){
                            $planContentStr.='<tr>';
                            $kangdaContentInfos = Case_tplcontent::getListByTplid($kangdaUserId);
                            foreach ($theadArray as $theadItem){
                                $tdContent = "";
                                foreach ($kangdaContentInfos as $kangdaContentInfo){
                                    if($kangdaContentInfo["attrid"] == $theadItem){
                                        $tdContent = $kangdaContentInfo["content"];
                                    }
                                }
                                $planContentStr.='<td align="center">'.$tdContent.'</td>';
                            }
                            $planContentStr.='</tr>';
                        }
                        //--------------表尾-------------------
                        $planContentStr.='
                                </tbody>
                            </table> ';
                        //清空锅炉典型用户Id数组
                        $kangdaUserCommonArr = array();
                    }

                    //--------------------------用户名单显示-------【完】----------------------

                    $planContentStr.=$content;
                }
            }

        }
    //---------------------判断锅炉典型用户列表--------------------
    if(!empty($guoluUserTypicalArr)){
        $planContentStr.='<h3>工厂典型用户</h3><br/>';
        foreach ($guoluUserTypicalArr as $key => $value){
            $text = null;
            $tplinfo = null;
            $caseattr = null;
            $text_content = null;
            $userName = null;

            //获取该用户的详情富文本内容
            $tplinfo = Case_tpl::getInfoById($value);
            //$userName = Case_attr::getInfoById($tplinfo[0]['name']);
            $userName = $tplinfo[0]['name'] ;
            $caseattr = Case_attr::getInfoById($tplinfo[0]['attrid']);
            $text_content = Table_case_tplcontent::getInfoByAttridAndTplid($caseattr[0]['parentid'],$value);
            if($text_content) {
                $text = $text_content?$text_content[0]['content']:'';
            }
            $planContentStr.='<strong>['.$userName.']详情:</strong>'.$text;
        }
        //清空锅炉典型用户Id数组
        $guoluUserTypicalArr = array();
    }
    //---------------------判断锅炉普通用户列表------------------------
    if(!empty($guoluUserCommonArr)){
        $planContentStr.='<h3>工厂普通用户</h3><br/>';

        $planContentStr.='
                                <table class="cmTable" border=1>
                                    <thead>
                                        <tr>';
        $text = null;
        $tplinfo = null;
        $caseattr = null;
        $text_content = null;
        $userName = null;

        //获取parentId对应的属性
        $tplinfo = Case_tpl::getInfoById($guoluUserCommonArr[0]);
        $caseattr = Case_attr::getInfoById($tplinfo[0]['attrid']);
        $guoluUserAttrs = Case_attr::getListByPid($caseattr[0]['parentid'],0);
        $theadArray = array();
        foreach ($guoluUserAttrs as $key=>$attr){
            $planContentStr.='<th width="20%">'.$attr["name"].'</th>';
            $theadArray[$key] = $attr["id"];
        }
        $planContentStr.='
                                        </tr>
                                    </thead>
                                <tbody >';
        //-----------------表体-------------------
        foreach ($guoluUserCommonArr as $guoluUserId){
            $planContentStr.='<tr>';
            $guoluContentInfos = Case_tplcontent::getListByTplid($guoluUserId);
            foreach ($theadArray as $theadItem){
                $tdContent = "";
                foreach ($guoluContentInfos as $guoluContentInfo){
                    if($guoluContentInfo["attrid"] == $theadItem){
                        $tdContent = $guoluContentInfo["content"];
                    }
                }
                $planContentStr.='<td align="center">'.$tdContent.'</td>';
            }
            $planContentStr.='</tr>';
        }
        //--------------表尾-------------------
        $planContentStr.='
                                </tbody>
                            </table> ';

        //清空锅炉典型用户Id数组
        $guoluUserCommonArr = array();
    }
    //------------------------判断公司典型用户列表------------------------------
    if(!empty($kangdaUserTypicalArr)){
        $planContentStr.='<h3>公司典型用户</h3><br/>';
        foreach ($kangdaUserTypicalArr as $key => $value){
            $text = null;
            $tplinfo = null;
            $caseattr = null;
            $text_content = null;
            $userName = null;

            //获取该用户的详情富文本内容
            $tplinfo = Case_tpl::getInfoById($value);
            //$userName = Case_attr::getInfoById($tplinfo[0]['name']);
            $userName = $tplinfo[0]['name'] ;
            $caseattr = Case_attr::getInfoById($tplinfo[0]['attrid']);
            $text_content = Table_case_tplcontent::getInfoByAttridAndTplid($caseattr[0]['parentid'],$value);
            if($text_content) {
                $text = $text_content?$text_content[0]['content']:'';
            }
            $planContentStr.='<strong>['.$userName.']详情:</strong>'.$text;
        }
        //清空锅炉典型用户Id数组
        $kangdaUserTypicalArr = array();
    }
    //-----------------------------判断公司普通用户列表------------------------------
    if(!empty($kangdaUserCommonArr)){
        $planContentStr.='<h3>公司普通用户</h3><br/>';
        $planContentStr.='
                                <table class="cmTable" border=1>
                                    <thead>
                                        <tr>';
        $text = null;
        $tplinfo = null;
        $caseattr = null;
        $text_content = null;
        $userName = null;

        //获取parentId对应的属性
        $tplinfo = Case_tpl::getInfoById($kangdaUserCommonArr[0]);
        $caseattr = Case_attr::getInfoById($tplinfo[0]['attrid']);
        $guoluUserAttrs = Case_attr::getListByPid($caseattr[0]['parentid'],0);
        $theadArray = array();
        foreach ($guoluUserAttrs as $key=>$attr){
            $planContentStr.='<th width="20%">'.$attr["name"].'</th>';
            $theadArray[$key] = $attr["id"];
        }
        $planContentStr.='
                                        </tr>
                                    </thead>
                                <tbody >';
        //-----------------表体-------------------
        foreach ($kangdaUserCommonArr as $kangdaUserId){
            $planContentStr.='<tr>';
            $kangdaContentInfos = Case_tplcontent::getListByTplid($kangdaUserId);
            foreach ($theadArray as $theadItem){
                $tdContent = "";
                foreach ($kangdaContentInfos as $kangdaContentInfo){
                    if($kangdaContentInfo["attrid"] == $theadItem){
                        $tdContent = $kangdaContentInfo["content"];
                    }
                }
                $planContentStr.='<td align="center">'.$tdContent.'</td>';
            }
            $planContentStr.='</tr>';
        }
        //--------------表尾-------------------
        $planContentStr.='
                                </tbody>
                            </table> ';
        //清空锅炉典型用户Id数组
        $kangdaUserCommonArr = array();
    }

    //--------------------------用户名单显示-------【完】----------------------

    echo htmlspecialchars_decode($planContentStr);

    ?>

</body>

</html>





















