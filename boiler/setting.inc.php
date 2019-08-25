<?php

/**
 * setting.inc.php 一些可能扩展的运营参数
 *
 * @version       v0.01
 * @create time   2014/9/2
 * @update time   
 * @author        jt
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

//管理员分类
$ARRAY_admin_type = array(
	'0' => '普通管理员组',
	'9' => '超级管理员组'
);
//app版本状态
$ARRAY_version_status = array(
    '1' => '正常',
    '2' => '下线'
);
//app版本状态
$ARRAY_app_type = array(
    '1' => 'android',
    '2' => 'ios'
);
//app版本强制
$ARRAY_version_isforce = array(
    '0' => '不强制',
    '1' => '强制'
);
//价格添加方式
$ARRAY_price_addtype = array(
		'1' => '前台添加',
		'2' => '后台添加'
);
//用户状态
$ARRAY_uesr_status = array(
    '1' => '正常',
    '-1' => '离职'
);
//拜访方式
$ARRAY_visit_way = array(
    '1' => '电话拜访',
    '2' => '上门拜访'
);

//项目建筑类型
$ARRAY_project_build_type = array(
    1 => '住宅',
    2 => '办公楼或学校',
    3 => '医院或幼儿园',
    4 => '酒店',
    5 => '图书馆',
    6 => '商店',
    7 => '单层住宅',
    8 => '食堂或餐厅',
    9 => '影剧院',
    10 => '大礼堂或体育馆'
);
$ARRAY_project_level = array(
    '-1'=>'已删除',
    '0' => '未报备',
    '11' => '项目终止',
    '1' => '<img src="images/xingxing.png" alt="">',
    '2' => '<img src="images/xingxing.png" alt=""><img src="images/xingxing.png" alt="">',
    '3' => '<img src="images/xingxing.png" alt=""><img src="images/xingxing.png" alt=""><img src="images/xingxing.png" alt="">',
    '4' => '<img src="images/xingxing.png" alt=""><img src="images/xingxing.png" alt=""><img src="images/xingxing.png" alt=""><img src="images/xingxing.png" alt="">',
    '5' => '<img src="images/xingxing.png" alt=""><img src="images/xingxing.png" alt=""><img src="images/xingxing.png" alt=""><img src="images/xingxing.png" alt=""><img src="images/xingxing.png" alt="">'
);
$ARRAY_project_level_stop = array(
    '1' => '<img src="images/grayxingxing.png" alt="">',
    '2' => '<img src="images/grayxingxing.png" alt=""><img src="images/grayxingxing.png" alt="">',
    '3' => '<img src="images/grayxingxing.png" alt=""><img src="images/grayxingxing.png" alt=""><img src="images/grayxingxing.png" alt="">',
    '4' => '<img src="images/grayxingxing.png" alt=""><img src="images/grayxingxing.png" alt=""><img src="images/grayxingxing.png" alt=""><img src="images/grayxingxing.png" alt="">',
    '5' => '<img src="images/grayxingxing.png" alt=""><img src="images/grayxingxing.png" alt=""><img src="images/grayxingxing.png" alt=""><img src="images/grayxingxing.png" alt=""><img src="images/grayxingxing.png" alt="">'
);
$ARRAY_project_level_word = array(
    '0' => '未报备',
    '1' => '★',
    '2' => '★★',
    '3' => '★★★',
    '4' => '★★★★',
    '5' => '★★★★★'
);
$ARRAY_project_status = array(
    '1' => '未提交',
    '2' => '已提交',
    '3' => '已审核',
    '4' => '已驳回'
);
$ARRAY_project_status_review = array(
    '2' => '待审核',
    '3' => '已审核',
    '4' => '已驳回'
);

$constant_project_first_reward = 0.3;
$constant_project_second_reward = 0.5;
$constant_project_third_reward = 0.2;





$ARRAY_weixin_solve_type = array(
    '1' => '上门服务',
    '2' => '电话服务',

);


/********以下是选型相关配置*******************************************************/


$ARRAY_choose_type = array(
    1=>"智能选型",
    2=>"手动选型",
    3=>"更换锅炉"

);

//锅炉是否冷凝
$ARRAY_selection_is_condensate = array(
    '1' => '冷凝',
    '0' => '不冷凝'
);
//锅炉是否低氮
$ARRAY_selection_is_lownitrogen = array(
    '2' => '低氮30mg',
    '1' => '低氮80mg',
    '0' => '不低氮'
);
//锅炉房预留位置
$ARRAY_selection_guoluf_position = array(
    '0' => '地面独立锅炉房',
    '1' => '地下室',
    '2' => '楼顶',
    '3' => '裙楼顶部'
);
//采暖形式
$ARRAY_selection_heating_type = array(
    '0' => '暖气片',
    '1' => '地暖',
    '2' => '风机盘管'
);
//使用时间
$ARRAY_selection_usetime_type = array(
    '0' => '24小时运行',
    '1' => '间断运行'
);
//使用时间
$ARRAY_hotwater_usetime_type = array(
    '31' => '全日供水',
    '32' => '定时供水'
);
//锅炉用途
$ARRAY_selection_application = array(
    '0' => array(
        'name' => '采暖',
        'type' => array(
            '1' => '承压热水锅炉+承压系统',
            '2' => '常压锅炉+常压系统',
            '3' => '常压锅炉+板换+承压系统',
            '4' => '真空锅炉+承压系统'
            )),
    '1' => array(
        'name' => '热水',
        'type' => array(
            '1' => '常压热水锅炉+板换',
            '2' => '承压热水锅炉+板换',
            '3' => '常压热水锅炉+板换+保温水箱',
            '4' => '承压热水锅炉+板换+保温水箱'
        )),
    '2' => array(
        'name' => '采暖和热水',
        'type' => array(
            '1' => '承压热水锅炉+承压系统',
            '2' => '常压锅炉+常压系统',
            '3' => '常压锅炉+板换+承压系统',
            '4' => '真空锅炉+承压系统'
        ))
);


//采暖-建筑类别对应的采暖热指标（W），建筑编号 => [最低值, 最高值]
$ARRAY_project_qf = array(
    2  =>  [47, 70],    //'住宅'
    3  =>  [58, 81],    //'办公楼或学校'
    4  =>  [64, 81],    //'医院或幼儿园'
    5  =>  [58, 70],    //'旅馆'
    6  =>  [47, 76],    //'图书馆'
    7  =>  [64, 87],    //'商店'
    8  =>  [81, 105],   //'单层住宅'
    9  =>  [116, 140],  //'食堂或餐厅'
    10 =>  [93, 116],   //'影剧院'
    11 =>  [116, 163]    //'大礼堂或体育馆'
);

//采暖系统温度（°C），[最低值, 最高值]
$ARRAY_system_tem = array(
    0  =>  [60, 80],    //暖气
    1  =>  [50, 60],    //地暖
    2  =>  [50, 60]     //风机盘管
);

//热水-全日供水-建筑类别对应的热水用水定额(L)，建筑编号 => [最低值, 最高值, 供应时长(h)]
$ARRAY_project_qr = array(
    13  =>  array(               //住宅
            87 => [40, 80, 24],  //自备热水供应
            88 => [60, 100, 24]  //集中热水供应
        ),
    14  =>  [70, 110, 24],       //别墅
    15  =>  [80, 100, 24],       //酒店式公寓
    16  =>  array(               //宿舍
            93 => [70, 100, 24], //I,II类
            94 => [40, 80, 24]   //III,IV类
        ),
    17  =>  array(               //招待所、培训中心、普通旅馆
            97 => [25, 40, 24],  //公用盥洗室
            98 => [40, 60, 24],  //公用盥洗室、淋浴室
            99 => [50, 80, 24],  //公用盥洗室、淋浴室、洗衣室
            100 => [60, 100, 24]  //单独卫生间、公用洗衣室
        ),
    18  =>  array(               //宾馆 客房
            101 => [120, 160, 24],//旅客
            102 => [40, 50, 24]   //员工
        ),
    19  =>  array(               //医院住院部
            106 => [60, 100, 24], //公用盥洗室
            107 => [70, 130, 24], //公用盥洗室、淋浴室
            108 => [110, 200, 24],//单独卫生间
            3 => [70, 130, 8],  //医务人员
            4 => [7, 13, 8]     //门诊部床位
        ),
    20  =>  [100, 160, 24],      //疗养院、休养所住房部
    21  =>  [50,  70, 24],       //养老院
    22 =>  array(               //幼儿园、托儿所
            113 => [20, 40, 24],  //有住宿
            114 => [10, 15, 10]   //无住宿
        ),
    23 =>  array(               //公共浴室
            117 => [40, 60, 12],  //淋浴
            118 => [60, 80, 12],  //淋浴、浴盆
            119 => [70, 100, 12], //桑拿浴
        ),
    24 =>   [10, 15, 12],       //理发室，美容院
    25 =>   [15, 30, 8],        //洗衣房
    26 =>  array(               //餐饮厅
            124 => [15, 20, 10],  //营业餐厅
            125 => [7, 10, 12],   //快餐店、职工及学生食堂
            126 => [3, 8, 10]     //酒吧、咖啡厅、茶座
        ),
    27 =>   [5, 10, 8],         //办公楼
    28 =>   [15, 25, 12],       //健身中心
    29 =>   [17, 26, 4],        //体育场运动员淋浴
    30 =>   [2, 3, 4],          //会议厅
);

//热水-全日供水-热水小时变化系数Kh, 建筑编号 => [最低值, 最高值]
$ARRAY_water_kh = array(
    13  =>  [2.75, 4.8],         //住宅
    14  =>  [2.47, 4.21],        //别墅
    15  =>  [2.58, 4.00],        //酒店式公寓
    16  =>  [3.20, 4.80],        //宿舍
    17  =>  [3.00, 3.84],        //招待所、培训中心、普通旅馆
    18  =>  [2.60, 3.33],        //宾馆 客房
    19  =>  [2.56, 3.63],        //医院住院部
    20  =>  [2.56, 3.63],        //疗养院、休养所住房部
    21  =>  [2.74, 3.20],        //养老院
    22 =>  [3.20, 4.80],        //幼儿园、托儿所
    23 =>  [3.20, 3.20],        //公共浴室***以下没有数据，使用3.20
    24 =>  [3.20, 3.20],        //理发室，美容院
    25 =>  [3.20, 3.20],        //洗衣房
    26 =>  [3.20, 3.20],        //餐饮厅
    27 =>  [3.20, 3.20],        //办公楼
    28 =>  [3.20, 3.20],        //健身中心
    29 =>  [3.20, 3.20],        //体育场运动员淋浴
    30 =>  [3.20, 3.20],        //会议厅
);

//热水-定时供水-卫生器具对应的小时热水用定额(L/h)，建筑编号 => array( 器具编号 => 小时热水用定额(L/h) )
$ARRAY_appliance_qh = array(
    33  =>  array(               //住宅、别墅、宾馆、酒店式公寓
        46 => 300,               //带有淋浴器的浴盆
        47 => 250,               //无淋浴器浴盆
        48 => 140,               //淋浴器
        49 => 30,                //洗脸盆、盥洗槽水嘴
        50 => 180                //洗涤盆
    ),
    34  =>  array(               //宿舍、招待所、培训中心
        51 => 210,               //有淋浴小间
        52 => 450,               //无淋浴小间
        53 => 50                 //盥洗槽水嘴
    ),
    35  =>  array(               //餐饮业
        54 => 250,               //洗涤盆
        55 => 60,                //洗脸盆 工作人员专用
        57 => 120,               //顾客用
        56 => 400,               //淋浴器
    ),
    36  =>  array(               //幼儿园
        58 => 400,               //幼儿园浴盆
        59 => 180,               //幼儿园淋浴器
        60 => 25,                //盥洗槽水嘴
        61 => 180                //洗涤盆
    ),
    37  =>  array(               //托儿所
        62 => 120,               //托儿所浴盆
        63 => 90,                //托儿所淋浴器
        64 => 25,                //盥洗槽水嘴
        65 => 180                //洗涤盆
    ),
    38  =>  array(               //医院、疗养院、休养所
        66 => 15,                //洗手盆
        67 => 300,               //洗涤盆
        68 => 200,               //淋浴器
        69 => 250,               //浴盆
    ),
    39  =>  array(               //公共浴室
        70 => 250,               //浴盆
        71 => 200,               //淋浴器，有淋浴小间
        72 => 450,               //淋浴器，无淋浴小间
        73 => 50                 //洗脸盆
    ),
    40  =>  array(              //办公楼 洗脸盆
        74 => 50              //浴盆
    ),
    41  =>  array(             //理发室，美容院，洗脸盆
        75 => 35              //浴盆

    ),

    42  =>   array(              //实验室
        76 => 60,                //洗脸盆
        77 => 15                 //洗手盆
    ),
    43  =>   array(             //剧场
        78 => 200,               //淋浴器
        79 => 80                 //演员用洗脸盆
    ),
    44  =>  array(             //体育场馆 淋浴器
        80 => 300              //浴盆

    ),

    45  =>   array(             //工业企业生活间
        81 => 360,                //淋浴器，一般车间
        82 => 180,                //淋浴器，脏车间
        83 => 90,                 //洗脸盆或盥洗槽水龙头 一般车间
        84 => 100                  //脏车间
    ),
    13  =>  10,                 //净身器
);
//无缝钢管 公称直径对应的外径与壁厚  公称直径DN => [外径, 壁厚]
$ARRAY_pipe_DN = array(
    10 => [14, 2],
    15 => [18, 2],
    20 => [25, 2.5],
    25 => [32, 2.5],
    32 => [38, 2.5],
    40 => [45, 2.5],
    50 => [57, 3.0],
    65 => [76, 3.5],
    80 => [89, 3.5],
    100 => [108, 4],
    125 => [133, 4],
    150 => [159, 4.5],
    200 => [219, 6],
    250 => [273, 7],
    300 => [325, 8],
    350 => [377, 10],
    400 => [426, 11]
);

//项目案例类型 
$ARRAY_Projectcase_type = array(
        '0' => '全部',
		'1' => '酒店',
		'2' => '医院',
		'3' => '工厂',
        '4' => '住宅',
        '5' => '商场',
        '6' => '学校',
        '7' => '其他',
);
//公司动态类型
$ARRAY_Companysituation_type = array(
    '1' => '行业动态',
    '2' => '公司新闻',
);
//轮播图类型
$ARRAY_Picture_type = array(
    '0' => '',
    '1' => '首页',
    '2' => '新闻中心',
    '3' => '项目案例',
    '4' => '渠道分销',
    '5' => '售后服务',
    '6' => '公司介绍',
    '7' => '人才招聘',
);
//招聘岗位
$ARRAY_Postion_type = array(
    '1' => '销售',
    '2' => 'web前端工程师',
    '3' => 'php工程师',
    '4' => 'UI设计',
    '5' => '产品助理',
    '6' => '研发助理',
);
$ARRAY_Educition_type = array(
    '1' => '本科',
    '2' => '研究生',
    '3' => '博士',
    '4' => '其他',
);

$ARRAY_Salary_type = array(
    '1' => '面议',
    '2' => '4k以下',
    '3' => '4k-6k',
    '4' => '6k-8k',
    '5' => '8k-1w',
    '6' => '1w以上',
);


$ARRAY_Experience_type = array(
    '0' => '无',
    '1' => '一年',
    '2' => '两年',
    '3' => '三年',
    '4' => '四年',
    '5' => '五年及以上',
);

//用户状态类型
$ARRAY_userstate_type = array(
    '1' => '是',
    '2' => '否',
);

$ARRAY_Project_name = array(
    'project_addtime'=>'添加时间',
    'project_lastupdate'=>'修改时间',
    'project_name' => '项目名称',
    'project_detail' => '项目地址',
    'project_lat' => '纬度',
    'project_long' => '经度',
    'project_type' => '项目类型',
    'project_partya' => '甲方单位',
    'project_partya_address' => '甲方地址',
    'project_partya_desc' => '甲方简介',
    'project_partya_pic' => '甲方组织架构图',
    'project_linkman' => '联系人',
    'project_linktel' => '联系电话',
    'project_linkposition' => '联系人职位',
    'project_boiler_num' => '锅炉总数量',
    'project_boiler_tonnage' => '锅炉总吨位',
    'project_wallboiler_num' => '壁挂炉总数量',
    'project_brand' => '拟用品牌名称',
    'project_xinghao' => '型号',
    'project_build_type' => '建筑类型',
    'project_pre_buildtime' => '预计开工时间',
    'project_competitive_brand' => '竞争品牌',
    'project_competitive_desc' => '竞品情况',
    'project_desc' => '备注'
);

$ARRAY_Project_two = array(
    'name'=>'联系人姓名',
    'phone'=>'联系方式',
    'duty' => '职责',
    'department' => '部门',
    'position' => '联系人职位',
    'isimportant' => '是否为重要负责人',
);

$ARRAY_Project_three = array(
    'competitive_brand_situation'=>'竞争品牌动向',
    'progress_situation'=>'工作进展情况',
    'invitation_situation' => '招标情况',
    'other_situation' => '关于项目其它进展情况',
);

$ARRAY_Project_four = array(
    'project_cid_company'=>'招标公司',
    'project_cid_linkman'=>'招标负责人',
    'project_cid_linkphone' => '标联系电话',
    'project_cbid_situation' => '招投标情况',
    'name'=>'投标公司',
    'price'=>'投标价格',
    'brand' => '投标品牌',
    'isimportant' => '是否中标'
);

$ARRAY_isImport = array(
    '1' => '是',
    '0' => '否'
);

$ARRAY_Project_five = array(
    'after_solve'=>'善后处理',
    'money'=>'合同金额',
    'pay_condition' => '付款条件',
    'cost_plan' => '用款计划',
    'first_reward'=>'首次提成',
    'second_reward'=>'二次提成',
    'third_reward' => '三次提成',
    'pre_build_time' => '项目拟开工日期',
    'pre_check_time' => '项目验收日期'
);

?>