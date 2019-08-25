<?php
/**
 * 项目导出处理  project_export_do.php
 *
 * @version       v0.01
 * @create time   2018/7/5
 * @update time
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */
require_once('web_init.php');
require_once('usercheck.php');
$topnva   = safeCheck($_POST['topnva'], 0);
$leftnva  = safeCheck($_POST['leftnva'], 0);
$id  = safeCheck($_POST['id']);
$projectinfo = Project::getInfoById($id);
if($topnva == "stage"){
    require_once($LIB_COMMON_PATH.'PHPWord/PHPWord.php');
    $file = 'userfiles/download/'.$projectinfo['id'].date('YmdHis').rand(1000, 9999).'_stage_all.docx';
    $excelpath = $FILE_PATH . $file;
    if (file_exists($excelpath))
        unlink($excelpath);

    $PHPWord = new PHPWord();
    $template = $PHPWord->loadTemplate($FILE_PATH.'userfiles/templet/stage_all.docx');
    $project_one = Project_one::getInfoByProjectId($id);
    $template->setValue('name', $projectinfo['name']);
    $typeinfo = Project_type::getInfoById($projectinfo['type']);
    $template->setValue('type', $typeinfo['name']);
    $template->setValue('time', getDateStrS($projectinfo['addtime']));
    //第一阶段
    $template->setValue('one_name', $projectinfo['name']);
    $template->setValue('detail', $projectinfo['detail']);
    $template->setValue('project_partya', $project_one['project_partya']);
    $template->setValue('project_partya_address', $project_one['project_partya_address']);
    $template->setValue('project_partya_desc', HTMLDecode($project_one['project_partya_desc']));
    $template->setValue('project_linkman', $project_one['project_linkman']);
    $template->setValue('project_linktel', $project_one['project_linktel']);
    $template->setValue('project_linkposition', $project_one['project_linkposition']);
    $template->setValue('project_brand', $project_one['project_brand']);
    $template->setValue('project_xinghao', $project_one['project_xinghao']);
    $template->setValue('project_build_type', $ARRAY_project_build_type[$project_one['project_build_type']]);
    $project_isnew = "";
    if($project_one['project_isnew'] == 1)
        $project_isnew = "是";
    else
        $project_isnew = "否";
    $template->setValue('project_isnew', $project_isnew);
    $template->setValue('project_pre_buildtime', getDateStrS($project_one['project_pre_buildtime']));
    $template->setValue('project_competitive_brand', $project_one['project_competitive_brand']);
    $template->setValue('project_competitive_desc', wordPrintReplace(wordCode($project_one['project_competitive_desc'])));
    $template->setValue('project_desc', wordPrintReplace(wordCode($project_one['project_desc'])));
    $template->setValue('level', $ARRAY_project_level_word[$projectinfo['level']]);
    $bunartype = "";
    if($projectinfo['type'] == 2){
        $bunartype = '壁挂炉总数量：'.$project_one['project_wallboiler_num'].' 台';
    }else{
        $burnerlist = Project_burner_type::getInfoByPoId($id);
        if($burnerlist) {
            $i = 0;
            foreach ($burnerlist as $thisburner) {
                $i++;
                $bunartype .= '<w:p><w:r><w:rPr><w:b w:val="on"/></w:rPr><w:t>类型'.$i.'</w:t></w:r></w:p>';
                $bunartype .= '<w:p><w:r><w:rPr></w:rPr><w:t>'.$thisburner['guolu_tonnage']." 吨".'</w:t></w:r></w:p>';
                $bunartype .= '<w:p><w:r><w:rPr></w:rPr><w:t>'.$thisburner['guolu_num']." 台".'</w:t></w:r></w:p>';
            }
        }
    }
    $template->setValue('bunartype', HTMLDecode($bunartype));

    //第二阶段
    $linkmans = "";
    $linkmanlist = Project_linkman::getInfoByPtId($id);
    $important_linkman = "";
    if($linkmanlist){
        $j = 0;
        foreach ($linkmanlist as $thisman) {
            $j++;
            if($thisman['isimportant'] == 1){
                $important_linkman = $thisman['name'];
            }
            $linkmans .= '<w:p><w:r><w:rPr><w:b w:val="on"/></w:rPr><w:t>联系人'.$j.':</w:t></w:r><w:r><w:t>'.$thisman['name'].'</w:t></w:r></w:p>';
            $linkmans .= '<w:p><w:r><w:rPr><w:b w:val="on"/></w:rPr><w:t>部门:</w:t></w:r><w:r><w:t>'.$thisman['department'].'</w:t></w:r></w:p>';
            $linkmans .= '<w:p><w:r><w:rPr><w:b w:val="on"/></w:rPr><w:t>职位:</w:t></w:r><w:r><w:t>'.$thisman['position'].'</w:t></w:r></w:p>';
            $linkmans .= '<w:p><w:r><w:rPr><w:b w:val="on"/></w:rPr><w:t>联系方式:</w:t></w:r><w:r><w:t>'.$thisman['phone'].'</w:t></w:r></w:p>';
            $linkmans .= '<w:p><w:r><w:rPr><w:b w:val="on"/></w:rPr><w:t>主要负责事项:</w:t></w:r><w:r><w:t>'.$thisman['duty'].'</w:t></w:r></w:p>';
        }
        $linkmans .= '<w:p><w:r><w:rPr><w:b w:val="on"/></w:rPr><w:t></w:t></w:r><w:r><w:t></w:t></w:r></w:p>';
        $linkmans .= '<w:p><w:r><w:rPr><w:b w:val="on"/></w:rPr><w:t>重要联系人:</w:t></w:r><w:r><w:t>'.$important_linkman.'</w:t></w:r></w:p>';
    }else{
        $linkmans .= '<w:p><w:r><w:rPr><w:b w:val="on"/></w:rPr><w:t>联系人1:</w:t></w:r></w:p>';
        $linkmans .= '<w:p><w:r><w:rPr><w:b w:val="on"/></w:rPr><w:t>部门:</w:t></w:r></w:p>';
        $linkmans .= '<w:p><w:r><w:rPr><w:b w:val="on"/></w:rPr><w:t>职位:</w:t></w:r></w:p>';
        $linkmans .= '<w:p><w:r><w:rPr><w:b w:val="on"/></w:rPr><w:t>联系方式:</w:t></w:r></w:p>';
        $linkmans .= '<w:p><w:r><w:rPr><w:b w:val="on"/></w:rPr><w:t>主要负责事项:</w:t></w:r></w:p>';
    }


    $framework = Project_client_framework::getInfoByPtId($id);

    $tree_data=array();
    foreach ($framework as $value) {
        $tree_data[$value['id']] = array(
            'id' => $value['id'],
            'parentid' => $value['pid'],
            'name' => $value['name']
        );
    }
        $str="<tr><td>\$spacer\$name</td></tr>";

    $tree=new Tree();
    $tree->init($tree_data);
    $treestr =  $tree->get_tree(0, $str);
    $treestr = str_replace('<tr><td>', '<w:p><w:r><w:t>', $treestr);
    $treestr = str_replace('</td></tr>', '</w:t></w:r></w:p>', $treestr);
    $treestr = '<w:p><w:r><w:rPr><w:b w:val="on"/></w:rPr><w:t></w:t></w:r><w:r><w:t></w:t></w:r></w:p><w:p><w:r><w:rPr><w:b w:val="on"/></w:rPr><w:t>公司组织架构:</w:t></w:r></w:p>'.$treestr;
    $template->setValue('project_two_linkman', HTMLDecode($linkmans.$treestr));

    //第三阶段
    $project_three = Project_three::getInfoByProjectId($id);
    if(empty($project_three)){
        $project_three = Project_three::Init();
    }
    $template->setValue('name', $projectinfo['name']);
    $typeinfo = Project_type::getInfoById($projectinfo['type']);
    $template->setValue('type', $typeinfo['name']);
    $template->setValue('time', getDateStrS($projectinfo['addtime']));
    $template->setValue('level', $ARRAY_project_level_word[$projectinfo['level']]);

    $template->setValue('competitive_brand_situation', wordPrintReplace(wordCode($project_three['competitive_brand_situation'])));
    $template->setValue('progress_situation', wordPrintReplace(wordCode($project_three['progress_situation'])));
    $template->setValue('invitation_situation', wordPrintReplace(wordCode($project_three['invitation_situation'])));
    $template->setValue('other_situation', wordPrintReplace(wordCode($project_three['other_situation'])));

    //第四阶段
    $project_four = Project_four::getInfoByProjectId($id);
    if(empty($project_four)){
        $project_four = Project_four::Init();
    }
    $template->setValue('name', $projectinfo['name']);
    $typeinfo = Project_type::getInfoById($projectinfo['type']);
    $template->setValue('type', $typeinfo['name']);
    $template->setValue('time', getDateStrS($projectinfo['addtime']));
    $template->setValue('level', $ARRAY_project_level_word[$projectinfo['level']]);

    $template->setValue('project_cid_company', $project_four['project_cid_company']);
    $template->setValue('project_cid_linkman', $project_four['project_cid_linkman']);
    $template->setValue('project_cid_linkphone', $project_four['project_cid_linkphone']);
    $template->setValue('cbid_situation', wordPrintReplace(wordCode($project_four['project_cbid_situation'])));
    //第五阶段
    $project_five = Project_five::getInfoByProjectId($id);
    if(empty($project_four)){
        $project_five = Project_five::Init();
    }
    $template->setValue('name', $projectinfo['name']);
    $typeinfo = Project_type::getInfoById($projectinfo['type']);
    $template->setValue('type', $typeinfo['name']);
    $template->setValue('time', getDateStrS($projectinfo['addtime']));
    $template->setValue('level', $ARRAY_project_level_word[$projectinfo['level']]);

    $template->setValue('money', number_format($project_five['money'],2));
    $project_reward = "";
    if($projectinfo['bonus_stage']){
        $project_reward .= '<w:p><w:r><w:rPr><w:b w:val="on"/></w:rPr><w:t>项目提成：</w:t></w:r></w:p>';
        $bonus_stage = explode('|', $projectinfo['bonus_stage']);
        if(in_array(1, $bonus_stage)){
            $project_reward .= '<w:p><w:pPr><w:ind w:firstLineChars="200" w:firstLine="480"/></w:pPr><w:r><w:t>预付款到账后所得：'.number_format($project_five['first_reward'],2).'元</w:t></w:r></w:p>';
        }
        if(in_array(2, $bonus_stage)){
            $project_reward .= '<w:p><w:ind w:firstLineChars="200" w:firstLine="480"/><w:r><w:t>项目竣工验收后得：'.number_format($project_five['second_reward'],2).'元</w:t></w:r></w:p>';
        }
        if(in_array(3, $bonus_stage)){
            $project_reward .= '<w:p><w:ind w:firstLineChars="200" w:firstLine="480"/><w:r><w:t>质保金到账后所得：'.number_format($project_five['third_reward'],2).'元</w:t></w:r></w:p>';
        }
    }
    $template->setValue('project_reward', HTMLDecode($project_reward));

    $template->setValue('after_solve', wordPrintReplace(wordCode($project_five['after_solve'])));
    $template->setValue('pay_condition', wordPrintReplace(wordCode($project_five['pay_condition'])));
    $template->setValue('cost_plan', wordPrintReplace(wordCode($project_five['cost_plan'])));

    $template->setValue('pre_build_time', getDateStrS($project_five['pre_build_time']));
    $template->setValue('pre_check_time', getDateStrS($project_five['pre_check_time']));

    $companys = "";
    $success_bid_company = "";
    $comppanylist = Project_bid_company::getInfoByPfId($id);
    if($comppanylist){
        $i = 0;
        foreach ($comppanylist as $thiscom) {
            $i++;
            if($thiscom['isimportant'] == 1){
                $success_bid_company = $thiscom['name'];
            }
            $companys .= '<w:p><w:r><w:rPr><w:b w:val="on"/></w:rPr><w:t>公司'.$i.'</w:t></w:r></w:p>';
            $companys .= '<w:p><w:r><w:rPr><w:b w:val="on"/></w:rPr><w:t>投标公司名称:</w:t></w:r><w:r><w:t>'.$thiscom['name'].'</w:t></w:r></w:p>';
            $companys .= '<w:p><w:r><w:rPr><w:b w:val="on"/></w:rPr><w:t>投标现场价格:</w:t></w:r><w:r><w:t>'.$thiscom['price'].'元'.'</w:t></w:r></w:p>';
            $companys .= '<w:p><w:r><w:rPr><w:b w:val="on"/></w:rPr><w:t>投标品牌:</w:t></w:r><w:r><w:t>'.$thiscom['brand'].'</w:t></w:r></w:p>';
        }
    }

    $template->setValue('project_company_str', HTMLDecode($companys));

    $template->setValue('success_bid_company', $success_bid_company);
    // 保存文件
    $template->save($excelpath);
    //返回值
    if (file_exists($excelpath))
        echo  action_msg($file, 1);
    else
        echo action_msg("导出到Word失败", 101);
}elseif($topnva == "visitlog"){
    set_time_limit(180);//给3分钟的执行时间
    header("content-type:text/html;charset=utf-8");
    require($LIB_COMMON_PATH.'phpexcel/PHPExcel.class.php');

    try{
        $PHPExcel = new PHPExcel();
        //拜访记录
        $activeSheet = $PHPExcel->setActiveSheetIndex(0);
        $activeSheet->setCellValue('A1', '时间');
        $activeSheet->setCellValue('B1', '拜访对象');
        $activeSheet->setCellValue('C1', '联系方式');
        $activeSheet->setCellValue('D1', '职位');
        $activeSheet->setCellValue('E1', '拜访方式');
        $activeSheet->setCellValue('F1', '拜访内容');
        $activeSheet->setCellValue('G1', '拜访效果');
        $activeSheet->setCellValue('H1', '下步计划');
        $activeSheet->setCellValue('I1', '评论');

        $activeSheet->getColumnDimension('A')->setWidth(15);
        $activeSheet->getColumnDimension('B')->setWidth(15);
        $activeSheet->getColumnDimension('C')->setWidth(15);
        $activeSheet->getColumnDimension('D')->setWidth(15);
        $activeSheet->getColumnDimension('E')->setWidth(15);
        $activeSheet->getColumnDimension('F')->setWidth(35);
        $activeSheet->getColumnDimension('G')->setWidth(35);
        $activeSheet->getColumnDimension('H')->setWidth(35);
        $activeSheet->getColumnDimension('I')->setWidth(35);
        $activeSheet->getStyle('C')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $activeSheet->getStyle('I')->getAlignment()->setWrapText(true);
        $vcount = Project_visitlog::getPageList(1, 10, 0, $id, '', 0, 0, 0,"");
        $vrows = Project_visitlog::getPageList(1, $vcount, 1, $id, '', 0, 0, 0,"");
        if($vrows){
            $i = 2;
            foreach($vrows as $row){
                $comuser = '';
                if($row['comuser']){
                    $comuserinfo = User::getInfoById($row['comuser']);
                    $comuser = $row['comment']."\r\n".'评论人：'.$row['name'];
                }else{
                    $comuser = $row['comment'];
                }
                $activeSheet->setCellValue('A' . $i, getDateStrS($row['visittime']));
                $activeSheet->setCellValue('B' . $i, $row['target']);
                $activeSheet->setCellValue('C' . $i, $row['tel']);
                $activeSheet->setCellValue('D' . $i, $row['position']);
                $activeSheet->setCellValue('E' . $i, $ARRAY_visit_way[$row['visitway']]);
                $activeSheet->setCellValue('F' . $i, HTMLDecode($row['content']));
                $activeSheet->setCellValue('G' . $i, HTMLDecode($row['effect']));
                $activeSheet->setCellValue('H' . $i, HTMLDecode($row['plan']));
                $activeSheet->setCellValue('I' . $i, $comuser);
                $i++;
            }
        }
        //命名sheet
        $activeSheet->setTitle('拜访记录');
        //考察记录
        $PHPExcel->createSheet();
        $activeSheet1 = $PHPExcel->setActiveSheetIndex(1);
        $activeSheet1->setCellValue('A1', '时间');
        $activeSheet1->setCellValue('B1', '考察人员');
        $activeSheet1->setCellValue('C1', '考察单位');
        $activeSheet1->setCellValue('D1', '考察品牌');
        $activeSheet1->setCellValue('E1', '考察地点');
        $activeSheet1->setCellValue('F1', '考察情况');

        $activeSheet1->getColumnDimension('A')->setWidth(15);
        $activeSheet1->getColumnDimension('B')->setWidth(25);
        $activeSheet1->getColumnDimension('C')->setWidth(25);
        $activeSheet1->getColumnDimension('D')->setWidth(25);
        $activeSheet1->getColumnDimension('E')->setWidth(35);
        $activeSheet1->getColumnDimension('F')->setWidth(40);

        $icount = Project_inspectlog::getPageList(1, 10, 0, $id, "", 0, 0,"");
        $irows = Project_inspectlog::getPageList(1, $icount, 1, $id, "", 0, 0,"");
        if($irows){
            $j = 2;
            foreach($irows as $row){
                $activeSheet1->setCellValue('A' . $j, getDateStrS($row['inspecttime']));
                $activeSheet1->setCellValue('B' . $j, $row['member']);
                $activeSheet1->setCellValue('C' . $j, $row['company']);
                $activeSheet1->setCellValue('D' . $j, $row['brand']);
                $activeSheet1->setCellValue('E' . $j, $row['address']);
                $activeSheet1->setCellValue('F' . $j, HTMLDecode($row['situation']));
                $j++;
            }
        }
        //命名sheet
        $activeSheet1->setTitle('考察记录');

        //定义输出的xlsx的文件
        $excelname_out = 'userfiles/download/'.'project'.date('YmdHis').rand(1000, 9999).'.xlsx';
        $excelfilePath_out = $FILE_PATH.$excelname_out;
        $objWriter = PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel2007');//生成xlsx
        $objWriter->save($excelfilePath_out);

        if (file_exists($excelfilePath_out)){
            echo action_msg($excelname_out, 1);
        }else {
            echo action_msg("导出失败", 101);
            die();
        }
    }catch(PHPExcel_Writer_Exception $we){
        echo action_msg($we->getMessage(), -1);
        exit();
    }catch(PHPExcel_Exception $pe){
        echo action_msg($pe->getMessage(), -1);
        exit();
    }
}

//生成无限极分类树
function make_tree($arr){
    $refer = array();
    $tree = array();
    foreach($arr as $k => $v){
        $refer[$v['id']] = & $arr[$k]; //创建主键的数组引用
    }
    foreach($arr as $k => $v){
        $pid = $v['pid'];  //获取当前分类的父级id
        if($pid == 0){
            $tree[] = & $arr[$k];  //顶级栏目
        }else{
            if(isset($refer[$pid])){
                $refer[$pid]['subcat'][] = & $arr[$k]; //如果存在父级栏目，则添加进父级栏目的子栏目数组中
            }
        }
    }
    return $tree;
}
?>