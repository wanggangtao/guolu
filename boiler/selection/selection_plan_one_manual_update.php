<?php
/**
 * 手动选型 选型方案一 更新 selection_plan_one_manual_update.php
 *
 * @version       v0.01
 * @create time   2018/12/24
 * @update time   2018/12/24
 * @author        ozqowen
 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
 */
require_once('web_init.php');
require_once('usercheck.php');


$TOP_FLAG = "selection";

$id = isset($_GET['id'])?safeCheck($_GET['id']):0;// 获取项目的id，即历史id
$isUpdate = isset($_GET['isUpdate'])?safeCheck($_GET['isUpdate']):0;// 是（1）否（0）是从方案生成页面点击“上一步”回到本页面
$addtype = isset($_GET['addtype']) ? safeCheck($_GET['addtype']) : 0;
$sttime = isset($_GET['sttime']) ? safeCheck($_GET['sttime']) : 0;
$endtime = isset($_GET['endtime']) ? safeCheck($_GET['endtime']) : 0;

$info = Selection_history::getInfoById($id);

$guolu_vender_list = Dict::getListByParentid(1);

// 如果是从方案池进来，需要向方案2页面传selection_plan_front_id
$plan_front_info = Selection_plan_front::getInfoByHistoryId($id);
$plan_front_id = null;
if (!empty($plan_front_info)) {
    $plan_front_id = $plan_front_info['id'];
}

if(empty($info) || $isUpdate == 0){
    echo '非法操作!';
    die();
}


// tab_type = 0 锅炉 1 采暖辅机 2 热水辅机 3 其他项 4 手动选型辅机
$guolu_plan_items = Selection_plan::getListByHidandTabtype($id,0);
$fuji_plan_items = Selection_plan::getListByHidandTabtype($id,4);
$other_plan_items = Selection_plan::getListByHidandTabtype($id,3);


/*根据历史id从Selection_history表中获取锅炉的相关数据*/
//根据获得的锅炉的id从guolu_attr获取该锅炉所对应的proid作为objectid参数
//从prielog表中获取最高，最低、平均价格和添加的最新价格
//type=1表示从产品中心获取锅炉和辅机的报价，type=2表示从选型方案获取其他项报价



$guolu_ids = explode(",",$info['guolu_id']);
$guolu_nums = explode(",",$info['guolu_num']);
$guolu_contexts = explode("||",$info['guolu_context']);

$origin_guolu_list_length = count($guolu_ids);

$page = 1;
$pageSize = 15;

$guolus = array();
foreach ($guolu_ids as $key=> $guolu_id) {

    $guoluItem = array();
    if (!empty($guolu_id)) {
        $guoluinfo = Guolu_attr::getInfoById($guolu_id);


        $countarr = Case_pricelog::getPageList($page, $pageSize, 0, 1, $guoluinfo['proid'], $addtype, $sttime, $endtime);
        $count = $countarr['ct'];
        $prices = Case_pricelog::getPageList($page, $pageSize, 1, 1, $guoluinfo['proid'], $addtype, $sttime, $endtime);

        $guoluItem["guoluinfo"] = $guoluinfo;
        $guoluItem["prices"] = $prices;
        $guoluItem["countarr"] = $countarr;
        $guoluItem["guolu_num"] = $guolu_nums[$key];
        $guoluItem["guolu_context"] = $guolu_contexts[$key];

        $guolus[] = $guoluItem;
    }

}

$fujilist = Selection_fuji::getListByHistoryId($id,1); // addtype = 1 该辅机已添加到方案中
$origin_fuji_list_length = count($fujilist);

/*获取其他项表单的相关数据*/
$rows = Case_tpl::getListByAttrid(13, 1, 15, $count = 0);
$origin_other_list_length = count($rows);
//从case_attr表中报价方案的attrid=13，利用attrid从case_tpl表中提取属性名称


?>
<!DOCTYPE html>
<html >
<head>
    <meta charset="UTF-8">
    <title>选型方案</title>
    <link rel="stylesheet" href="css/scheme.css" />
    <link rel="stylesheet" href="css/Tc.css" />

    <link rel="stylesheet" href="css/nav.css" />
    <link rel="stylesheet" href="css/GLXX1.css" />
    <script type="text/javascript" src="js/nav.js" ></script>
    <script type="text/javascript" src="js/2.0.0/jquery.min.js" ></script>
    <script type="text/javascript" src="js/laydate.js" ></script>

    <script type="text/javascript" src="layer/layer.js"></script>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/newlayui.css">
    <link rel="stylesheet" href="layui/css/layui.css">
    <script type="text/javascript" src="js/jquery-1.4.4.min.js"></script>


    <link rel="stylesheet" href="css/GLXX2.css" />
    <script type="text/javascript" src="js/layer.js" ></script>
    <script type="text/javascript" src="ckeditor/ckeditor.js"></script>
    <style>
			.inputing{
                background-image:url('images/未选中按钮.png');
                -webkit-appearance: none;
                background-size:16px 16px;
                display: inline-block;
                width: 16px;
                height: 16px;
                background-repeat:no-repeat;
                position: relative;
                top: 2px;
                left: -5px;
                outline: none;
                        }
            .inputing:checked{
                background:url('images/选中按钮.png');
                background-repeat:no-repeat;
            }
		</style>

</head>
<body class="body_2">
      <?php include('top.inc.php');?>

      <!-- 进度条 -->
      <?php
      if ($info['status'] == 5){
        echo '
        <div class="manageHRWJCont_middle_middle">
          <div id="step" style="margin-top: 30px">
              <div class="step-wrap">
                  <div class="step-list">
                      <div class="step-num">
                          <div class="num-bg"><a href="selection_manual_new.php?id='?><?php echo $id ?><?php echo'" class="num-bg">1</a></div>
                      </div>
                      <span class="step-name"><a href="selection_manual_new.php?id='?><?php echo $id ?><?php echo'" class="step-name">选型</a></span>
                  </div>
                  <div class="step-line"></div>
                  <div class="step-list">
                      <div class="step-num">
                          <div class="num-bg"><a href="selection_make_price.php?id='?><?php echo $id ?><?php echo'&isUpdate=1" class="num-bg">2</a></div>
                      </div>
                      <span class="step-name"><a href="selection_make_price.php?id='?><?php echo $id ?><?php echo'&isUpdate=1" class="step-name">报价</a></span>
                  </div>
                  <div class="step-line"></div>
                  <div class="step-list">
                      <div class="step-num">
                        <div class="num-bg"><a href="selection_plan_two.php?id='?><?php echo $id ?><?php echo'&front_plan_id='?><?php echo $plan_front_id ?><?php echo'" class="num-bg">3</a></div>
                      </div>
                      <span class="step-name"><a href="selection_plan_two.php?id='?><?php echo $id ?><?php echo'&front_plan_id='?><?php echo $plan_front_id ?><?php echo'" class="step-name">方案</a></span>
                  </div>
              </div>
          </div>
      </div>
        ';
      }else{
          echo '
          <div class="manageHRWJCont_middle_middle">
          <div id="step" style="margin-top: 30px">
              <div class="step-wrap">
                  <div class="step-list">
                      <div class="step-num">
                          <div class="num-bg"><a href="selection_manual_new.php?id='?><?php echo $id ?><?php echo'" class="num-bg">1</a></div>
                      </div>
                      <span class="step-name"><a href="selection_manual_new.php?id='?><?php echo $id ?><?php echo'" class="step-name">选型</a></span>
                  </div>
                  <div class="step-line"></div>
                  <div class="step-list">
                      <div class="step-num">
                          <div class="num-bg"><a href="selection_make_price.php?id='?><?php echo $id ?><?php echo'&isUpdate=1" class="num-bg">2</a></div>
                      </div>
                      <span class="step-name"><a href="selection_make_price.php?id='?><?php echo $id ?><?php echo'&isUpdate=1" class="step-name">报价</a></span>
                  </div>
                  <div class="step-line"></div>
                  <div class="step-list">
                        <div class="nums">3</div>
                        <span class="step-names">方案</span>
                  </div>
              </div>
          </div>
      </div>
          ';
      }
      ?>


      <!-- 方案一页面 -->
      <div style="margin-top: 20px;">

          <!-- 时间检索 -->
          <div class="GLXX1_main1">
                 <p class="p2">请选择推荐价格的有效时间</p>
                 <input  id="sttime"  value="<?php echo $sttime?date('Y-m-d',$sttime):'';?>" />
                 <p class="p3">—</p>
                 <input  id="endtime"  value="<?php echo $endtime?date('Y-m-d',$endtime):'';?>" />
                 <input  id="search" type="button"  value="查询"/>
             </div>

          <!-- 锅炉 -->
          <div class="GLXX1_main2" >
          <?php
            if(!empty($guolus)) {
           ?>
              <p class="GL" style="width:666px;margin-bottom: 10px">锅炉</p>

              <table>
                  <tr style="background-color: #E5F6FE;">
                        <td>设备名称</td>
                        <td>厂家名称</td>
                        <td style="width: 242px;">规格型号</td>
                        <td style="width: 50px;">数量</td>
                        <td style="width: 100px;">备注</td>
                        <td>最高价(万元)</td>
                        <td>最低价(万元)</td>
                        <td>均价(万元)</td>
                        <td>上次(万元)</td>
                        <td style="width: 144px;">自定义(万元)</td>
                    </tr>
                    <?php
                    if(!empty($guolus)) {

                        $current_guolu_length = 0;
                        foreach ($guolus as $guolu) {

                            $vendername = "";
                            $guoluinfo = $guolu["guoluinfo"];
                            $countarr = $guolu["countarr"];
                            $prices = $guolu["prices"];
                            $guolu_num = $guolu["guolu_num"];

                            $version = $guoluinfo ? $guoluinfo['version'] : '';

                            if ($guoluinfo) {//获取厂家的名称
                                $venderinfo = Dict::getInfoById($guoluinfo['vender']);
                                $vendername = $venderinfo['name'];
                            }

                            $guolu_maxprice = $countarr['maxprice'] ? floatval($countarr['maxprice']) : 0;
                            $guolu_minprice = $countarr['minprice'] ? floatval($countarr['minprice']) : 0;
                            $guolu_avgprice = $countarr['avgprice'] ? round($countarr['avgprice'],2) : 0;
                            $guolu_newprice = $prices?floatval($prices[0]['price']) : 0;
                            ?>
                            <tr class="guolu">
                                <td class="center">锅炉</td>
                                <td class="center" id="guolu_vender" data-value="<?php echo $guoluinfo['vender']; ?>">
                                    <?php echo $vendername ?>
                                </td>
                                <td class="center" id="guolu_version" data-value="<?php echo $version; ?>"><?php echo $version ?></td>
                                <td class="center" id="guolu_num" data-value="<?php echo $guolu_num; ?>"><?php echo $guolu_num; ?></td>
                                <td class="center" id="guolu_context" data-value="<?php echo $guolu["guolu_context"]; ?>"><?php echo $guolu["guolu_context"]; ?></td>
                                <td><input type="radio" name="price_guolu_<?php echo $guoluinfo["id"]?>"   value="<?php echo $guolu_maxprice; ?>"  class="inputing" /><?php echo $guolu_maxprice; ?></td>
                                <td><input type="radio" name="price_guolu_<?php echo $guoluinfo["id"]?>"   value="<?php echo $guolu_minprice; ?>" class="inputing" /><?php echo $guolu_minprice; ?></td>
                                <td><input type="radio" name="price_guolu_<?php echo $guoluinfo["id"]?>"   value="<?php echo $guolu_avgprice; ?>" class="inputing" /><?php echo $guolu_avgprice; ?></td>
                                <td><input type="radio" name="price_guolu_<?php echo $guoluinfo["id"]?>"   value="<?php echo $guolu_newprice; ?>" class="inputing"/><?php echo $guolu_newprice; ?></td>
                                <td class="defined" style="width:35px;">
                                    <input type="number" name="textprice" id="guolu_add_price" value="<?php echo floatval($guolu_plan_items[$current_guolu_length]['pro_price']); ?>" class="price_input" style="max-width:55%;"  />
                                    <input type="hidden" name="choose_guolu_price" id="choose_guolu_price" value=""/>
                                    <input type="hidden" name="attrid" id="attrid" value="<?php echo $guoluinfo["proid"]; ?>"/>
                                    <input type="hidden" name="gid" id="gid" value="<?php echo $guoluinfo["id"]; ?>"/>
                                </td>
                            </tr>

                        <?php
                            $current_guolu_length++;
                        }

                        while ($current_guolu_length < count($guolu_plan_items)){
                            $current_guolu_info = Guolu_attr::getInfoById($guolu_plan_items[$current_guolu_length]['attrid']);
                            $current_venderinfo = Dict::getInfoById($guoluinfo['vender']);
                            $current_vendername = $current_venderinfo['name'];
                            $addtype = isset($_GET['addtype']) ? safeCheck($_GET['addtype']) : 0;
                            $sttime = isset($_GET['sttime']) ? safeCheck($_GET['sttime']) : 0;
                            $endtime = isset($_GET['endtime']) ? safeCheck($_GET['endtime']) : 0;
                            $countarr = Case_pricelog::getPageList($page, $pageSize, 0, 1, $current_guolu_info['proid'], $addtype, $sttime, $endtime);
                            $prices = Case_pricelog::getPageList($page, $pageSize, 1, 1, $current_guolu_info['proid'], $addtype, $sttime, $endtime);
                            $guolu_maxprice = $countarr['maxprice'] ? floatval($countarr['maxprice']) : 0;
                            $guolu_minprice = $countarr['minprice'] ? floatval($countarr['minprice']) : 0;
                            $guolu_avgprice = $countarr['avgprice'] ? round($countarr['avgprice'],2) : 0;
                            $guolu_newprice = $prices?floatval($prices[0]['price']) : 0;
                            ?>
                            <tr class="guolu">
                                <td class="center">锅炉</td>
                                <td class="center">
                                    <select type="text" class="GLXXmain_3 guolu_vender" id="guolu_vender" >
                                        <?php
                                        if (!empty($guolu_vender_list)) {
                                            foreach ($guolu_vender_list as $guolu_vender) {
                                                $selected = '';
                                                if ($guolu_vender['id'] == $current_guolu_info['vender']) {
                                                    $selected = 'selected';
                                                }
                                                echo '<option value="' . $guolu_vender['id'] . '" ' . $selected . '>' . $guolu_vender['name'] . '</option>';

                                            }
                                        } else {
                                            echo "暂无可选厂家";
                                        }
                                        ?>
                                </td>
                                <td class="center">
                                    <select type="text" class="GLXXmain_3 guolu_version" id="guolu_version" >
                                        <?php
                                        $guolu_list = Guolu_attr::getList(0, '', 0, $current_guolu_info['vender'], '', '', '');
                                        if ($guolu_list) {
                                            foreach ($guolu_list as $guolu) {
                                                $selected = '';
                                                $is_exist = false;
                                                foreach ($guolu_plan_items as $guolu_plan_item){
                                                    if ($guolu['guolu_id'] == $guolu_plan_item['attrid']){
                                                        $is_exist = true;
                                                    }
                                                }
                                                if ($guolu['guolu_id'] == $guolu_plan_items[$current_guolu_length]['attrid']) {
                                                    $is_exist = false;
                                                    $selected = 'selected';
                                                }
                                                if (!$is_exist){
                                                    echo '<option value="' . $guolu['guolu_id'] . '" ' . $selected . '>' . $guolu['guolu_version'] . '</option>';
                                                }
                                            }
                                        } else {
                                            echo "没有找到合适的型号";
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td class="center">
                                    <input style="margin:2px" class="GLXXmain_3" type="number" value="<?php echo $guolu_plan_items[$current_guolu_length]['nums']; ?>" id="guolu_num"/>
                                </td>
                                <td class="center">
                                    <input style="margin:2px" class="GLXXmain_3" type="text" value="<?php echo $guolu_plan_items[$current_guolu_length]['remark']; ?>" id="guolu_context"/>
                                </td>
                                <td class="guolu_maxprice">
                                    <input type="radio" name="price_guolu_<?php echo $guolu_plan_items[$current_guolu_length]['attrid'] ?>" value="<?php echo $guolu_maxprice; ?>"  class="inputing" /><?php echo $guolu_maxprice; ?>
                                </td>
                                <td class="guolu_minprice">
                                    <input type="radio" name="price_guolu_<?php echo $guolu_plan_items[$current_guolu_length]['attrid'] ?>" value="<?php echo $guolu_minprice; ?>" class="inputing" /><?php echo $guolu_minprice; ?>
                                </td>
                                <td class="guolu_avgprice">
                                    <input type="radio" name="price_guolu_<?php echo $guolu_plan_items[$current_guolu_length]['attrid'] ?>" value="<?php echo $guolu_avgprice; ?>" class="inputing" /><?php echo $guolu_avgprice; ?>
                                </td>
                                <td class="guolu_newprice">
                                    <input type="radio" name="price_guolu_<?php echo $guolu_plan_items[$current_guolu_length]['attrid'] ?>" value="<?php echo $guolu_newprice; ?>" class="inputing" /><?php echo $guolu_newprice; ?>
                                </td>
                                <td class="defined guolu_addprice" style="width:35px;">
                                    <input type="number" name="textprice" id="guolu_add_price" value="<?php echo floatval($guolu_plan_items[$current_guolu_length]['pro_price']); ?>" class="price_input" style="max-width:55%;"  />
                                    <span id="delete_guolu" class="mougl" style="color: red"> 删除 </span>
                                    <input type="hidden" name="choose_guolu_price" id="choose_guolu_price" value=""/>
                                    <input type="hidden" name="attrid" id="attrid" value="<?php echo $current_guolu_info['proid']; ?>"/>
                                    <input type="hidden" name="gid" id="gid" value="<?php echo $guolu_plan_items[$current_guolu_length]['attrid']; ?>"/>
                                </td>
                            </tr>
                            <?php
                            $current_guolu_length++;
                        }
                    }
                    ?>
                </table>

              <div class="GLXXmain_16">
                  <span class="addgl" style="display: block;margin-left: 120px;margin-top: 10px" >添加锅炉报价项</span>
              </div>
          <?php
            }
          ?>
          </div>

          <!-- 辅机 -->
          <?php
                if(!empty($fuji_plan_items)){
            ?>
          <div class="GLXX1_main3" >
                <p class="GL" style="width:666px;position:relative;margin-top:20px;margin-bottom: 10px">辅机</p>
                <table style="position:relative;">
                    <tr style="background-color: #E5F6FE;">
                        <td style="width: 140px;">设备名称</td>
                        <td style="width: 214px;">规格型号</td>
                        <td style="width: 80px;">数量</td>
                        <td style="width: 100px;">备注</td>
                        <td>价格(万元)</td>
                    </tr>
                    <tr class="fuji_list_new" style="display: none">
                        <td >
                            <input style="margin:2px" class="GLXXmain_3" type="text" value="" id="fuji_name"/>
                        </td>
                        <td >
                            <input style="margin:2px" class="GLXXmain_3" type="text" value="" id="fuji_version"/>
                        </td>
                        <td >
                            <input style="margin:2px" class="GLXXmain_3" type="number" value="" id="fuji_num"/>
                        </td>
                        <td >
                            <input style="margin:2px" class="GLXXmain_3" type="text" value="" id="fuji_context"/>
                        </td>
                        <td class="defined">
                            <input type="number" name = "textprice" id="fuji_add_price" value="" class="price_input"  style="max-width:55%;"/>
                            <span id="delete_fuji" class="moufj" style="color: red"> 删除 </span>
                        </td>
                    </tr>
                    <?php
                    $current_fuji_length = 0;
                    foreach ($fujilist as $item){
                        echo '<tr class="fuji_list">';
                            echo '<td>' . $item['name'] . '</td>';
                            echo '<td>' . $item['version_show'] . '</td>';
                            echo '<td>' . $item['num'] . '</td>';
                            echo '<td>' . $item['context'] . '</td>';
                            echo '<td class="defined">
                                     <input type="number" name = "textprice" id="fuji_add_price" value="'?><?php echo floatval($fuji_plan_items[$current_fuji_length]['pro_price']); ?><?php echo '" class="price_input"  style="max-width:55%;"/>
                                     <input type="hidden" name = "fuji_id" id="fuji_id" value="'.$item['id'].'"/>
                                  </td>
    					      </tr>';
                        $current_fuji_length++;
                    }
                    while ($current_fuji_length < count($fuji_plan_items)){
                        echo '
                        <tr class="fuji_list" >
                        <td >
                            <input style="margin:2px" class="GLXXmain_3" type="text" value="'?><?php echo $fuji_plan_items[$current_fuji_length]['equ_name']; ?><?php echo '" id="fuji_name"/>
                        </td>
                        <td >
                            <input style="margin:2px" class="GLXXmain_3" type="text" value="'?><?php echo $fuji_plan_items[$current_fuji_length]['version']; ?><?php echo '" id="fuji_version"/>
                        </td>
                        <td >
                            <input style="margin:2px" class="GLXXmain_3" type="number" value="'?><?php echo $fuji_plan_items[$current_fuji_length]['nums']; ?><?php echo '" id="fuji_num"/>
                        </td>
                        <td >
                            <input style="margin:2px" class="GLXXmain_3" type="text" value="'?><?php echo $fuji_plan_items[$current_fuji_length]['remark']; ?><?php echo '" id="fuji_context"/>
                        </td>
                        <td class="defined">
                            <input type="number" name = "textprice" id="fuji_add_price" value="'?><?php echo floatval($fuji_plan_items[$current_fuji_length]['pro_price']); ?><?php echo '" class="price_input"  style="max-width:55%;"/>
                            <span id="delete_fuji" class="moufj" style="color: red"> 删除 </span>
                        </td>
                    </tr>
                    ';
                        $current_fuji_length++;
                    }
                    ?>
                </table>
                <div class="GLXXmain_16">
                      <span class="addfj" style="display: block;margin-left: 120px;margin-top: 10px">添加辅机报价项</span>
                  </div>
            </div>
          <?php
                }
            ?>

          <!-- 其他项 -->
           <div class="GLXX1_main4">
               <p class="GL" style="width: 666px;position:relative;margin-top:30px;margin-bottom: 10px">
                   请选择报价方案中其他项的价格，如果本次方案不需要某一项，可以不必选择或填写
               </p>
               <table style="position:relative;">
                   <tr style="background-color: #E5F6FE;">
                       <td style="width: 160px;">设备名称</td>
                       <td style="width: 324px;">规格型号</td>
                       <td style="width: 100px;">数量</td>
                       <td style="width: 100px;">备注</td>
                       <td>最高价(万元)</td>
                       <td>最低价(万元)</td>
                       <td>均价(万元)</td>
                       <td>上次(万元)</td>
                       <td style="width: 144px;">自定义(万元)</td>
                   </tr>
                   <tr class="other_new" style="display:none;">
                       <td>
                           <input style="margin:2px" class="GLXXmain_3" type="text" value="" id="other_name"/>
                       </td>
                       <td>
                           <input style="margin:2px" class="GLXXmain_3" type="text" value="" id="other_version"/>
                       </td>
                       <td>
                           <input style="margin:2px" class="GLXXmain_3" type="number" value="" id="other_num"/>
                       </td>
                       <td >
                           <input style="margin:2px" class="GLXXmain_3" type="text" value="" id="other_context"/>
                       </td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td class="defined">

                           <input type="number" name="other_add_price" id="other_add_price" value="" class="price_input"  style="max-width:55%;" />
                           <span id="delete_fuji" class="mou_other" style="color: red"> 删除 </span>
                           <input type="hidden" name = "choose_other_price" id="choose_other_price" value=""  />

                       </td>
                   </tr>
                   <?php

                    $other_new_items = array();
                    $other_origin_items = array();
                    foreach ($other_plan_items as $other_plan_item){
                        if ($other_plan_item['attrid'] == "" ){
                            array_push($other_new_items,$other_plan_item);
                        }else{
                            array_push($other_origin_items,$other_plan_item);
                        }
                    }

                    if(!empty($rows) ) {

                        foreach ($rows as $row) {
                            $tplcontentInfo =  Case_tplcontent::getInfoByAttridAndTplid(31,$row['id']);
                            $priceInfo = Case_pricelog::getPageList(1, 10, 0, 2, $row['id'], $addtype, $sttime, $endtime);
                            $newpriceInfo= Case_pricelog::getPageList($page, $pageSize, 1, 2, $row['id'], $addtype, $sttime, $endtime);

                            $other_maxprice=$priceInfo['maxprice']?floatval($priceInfo['maxprice']):0;
                            $other_minprice=$priceInfo['minprice']?floatval($priceInfo['minprice']):0;
                            $other_avgprice=$priceInfo['avgprice']?round($priceInfo['avgprice'],2):0;
                            $other_newprice=$newpriceInfo?floatval($newpriceInfo[0]['price']):0;

                            $current_other_item = array();
                            foreach ($other_origin_items as $other_origin_item){
                                if ($other_origin_item['attrid'] == $row['id'] ){
                                    $current_other_item = $other_origin_item;
                                }
                            }


                            echo '               
                           <tr class="other">
                                <td id="other_name" data-value="'?><?php echo $row['name'] ?><?php echo'">' . $row['name'] . '</td>
                                <td id="other_version" data-value="'?><?php echo !empty($tplcontentInfo)?$tplcontentInfo[0]["content"]:" " ?><?php echo '">' . HTMLDecode(!empty($tplcontentInfo)?$tplcontentInfo[0]["content"]:"") . '</td>
                                <td id="other_num" data-value="1">1</td>
                                <td >
                                    <input style="margin:2px" class="GLXXmain_3" type="text" value="'?><?php echo !empty($current_other_item)?$current_other_item['remark']:"" ?><?php echo'" id="other_context"/>
                                </td>
                                <td><input type="radio" name="price_other_'.$row["id"].'" value="'.$other_maxprice.'" class="inputing" />' . $other_maxprice. '</td>
                                <td><input type="radio" name="price_other_'.$row["id"].'" value="'.$other_minprice.'" class="inputing" />' . $other_minprice . '</td>
                                <td><input type="radio" name="price_other_'.$row["id"].'" value="'.$other_avgprice.'" class="inputing" />' . $other_avgprice . '</td>
                                <td><input type="radio" name="price_other_'.$row["id"].'" value="'.$other_newprice.'" class="inputing" />' . $other_newprice . '</td>
                                <td class="defined">
                                    
                                     <input type="number" name="other_add_price" id="other_add_price" value="'?><?php echo !empty($current_other_item)?floatval($current_other_item['pro_price']):"" ?><?php echo'" class="price_input"  style="max-width:55%;" />                               
                                     <a href="javascript:void(0)"  style="color: #04A6FE" class="clearAll"/>清除</a>
                                     <input type="hidden" name = "choose_other_price" id="choose_other_price" value=""  />
                                     <input type="hidden" name = "aid" id="aid" value="'.$row["id"].'"  />
        
                                </td>
                            </tr>';
                                    }
                           $current_other_new_length = count($other_new_items) - 1;
                           while ($current_other_new_length >= 0){
                               echo '
                               <tr class="other" >
                                   <td>
                                       <input style="margin:2px" class="GLXXmain_3" type="text" value="'?><?php echo $other_new_items[$current_other_new_length]['equ_name']; ?><?php echo '" id="other_name"/>
                                   </td>
                                   <td>
                                       <input style="margin:2px" class="GLXXmain_3" type="text" value="'?><?php echo $other_new_items[$current_other_new_length]['version']; ?><?php echo '" id="other_version"/>
                                   </td>
                                   <td>
                                       <input style="margin:2px" class="GLXXmain_3" type="number" value="'?><?php echo $other_new_items[$current_other_new_length]['nums']; ?><?php echo '" id="other_num"/>
                                   </td>
                                   <td >
                                       <input style="margin:2px" class="GLXXmain_3" type="text" value="'?><?php echo $other_new_items[$current_other_new_length]['remark']; ?><?php echo '" id="other_context"/>
                                   </td>
                                   <td></td>
                                   <td></td>
                                   <td></td>
                                   <td></td>
                                   <td class="defined">
                                       <input type="number" name="other_add_price" id="other_add_price" value="'?><?php echo floatval($other_new_items[$current_other_new_length]['pro_price']); ?><?php echo '" class="price_input"  style="max-width:55%;" />
                                       <span id="delete_fuji" class="mou_other" style="color: red"> 删除 </span>
                                   </td>
                               </tr>
                               ';
                               $current_other_new_length--;
                           }
                        }
                        ?>
               </table>
               <div class="GLXXmain_16">
                   <span class="add_other" style="display: block;margin-left: 120px;margin-top: 10px">添加其他报价项</span>
               </div>
           </div>

          <!-- 底部栏 -->
          <div class="footer" style="margin: 0 auto 99px auto;display: flex;justify-content: space-around;">
              <button class="GLXXmain_17" type="button"  id="btn_submit_ahead" value="上一步" style="width: 25%;height:auto;margin-left: 0 !important;margin-top: 3%;" >
                  <a href="selection_manual_new.php?id=<?php echo $id?>" style="color: white">上一步</a>
              </button>
              <button class="GLXXmain_17" type="button"  id="btn_submit_next" value="下一步" style="width: 25%;height:auto;margin-left: 0 !important;margin-top: 3%;">下一步</button>
          </div>

      </div>

      <style>
          #step .step-wrap {
              width: 710px;
              position: relative;
              margin: auto;
          }
          #step .step-wrap .step-list{
              display: inline-block;
              width: 64px;
              text-align: center;
          }
          #step .step-wrap .step-list .step-num{
              display: inline-block;
              position: relative;
              width: 48px;
              height: 48px;
              background: rgba(4,166,254,0.2);
              border-radius: 50%;
          }
          #step .step-wrap .step-list .nums{
              margin: auto;
              width: 32px;
              height: 32px;
              background: #FFC80A;
              border-radius: 50%;
              text-align: center;
              font-size: 16px;
              color: #fff;
              line-height: 32px;
          }
          #step .step-wrap .step-list .step-num .num-bg{
              margin: auto;
              position: absolute;
              top: 0;
              left: 0;
              right: 0;
              bottom: 0;
              width: 32px;
              height: 32px;
              background: #04A6FE;
              border-radius: 50%;
              text-align: center;
              font-size: 16px;
              color: #fff;
              line-height: 32px;
          }
          #step .step-wrap .step-list .step-name{
              font-size: 16px;
              color: #04A6FE;
          }
          #step .step-wrap .step-list .step-names{
              font-size: 16px;
              color: #293144;
              margin-top: 8px;
              display: block;
          }
          #step .step-wrap .step-line{
              display: inline-block;
              width: 290px;
              height: 2px;
              background: #04A6FE;
              margin: 0 -20px 42px -20px;
          }
          #step .step-wrap .step-lines{
              display: inline-block;
              width: 290px;
              height: 2px;
              background: #FFC80A;
              margin: 0 -20px 42px -20px;
          }

      </style>

      <script>

          laydate.render({
              elem: '#sttime' //需显示日期的元素选择器
              ,event: 'click' //触发事件
              ,format: 'yyyy-MM-dd' //日期格式
              ,istime: false //是否开启时间选择
              ,isclear: true //是否显示清空
              ,istoday: true //是否显示今天
              ,issure: true //是否显示确认
              ,festival: true //是否显示节日
              ,choose: function (dates) { //选择好日期的回调
              }
          });

          laydate.render({
              elem: '#endtime' //需显示日期的元素选择器
              ,event: 'click' //触发事件
              ,format: 'yyyy-MM-dd' //日期格式
              ,istime: false //是否开启时间选择
              ,isclear: true //是否显示清空
              ,istoday: true //是否显示今天
              ,issure: true //是否显示确认
              ,festival: true //是否显示节日
              ,choose: function (dates) { //选择好日期的回调
              }
          });

          //查找
          $('#search').click(function () {
              // var addtype = $('#addtype').val();
              var sttime = 0;
              var sttimestr = $('#sttime').val() + " 00:00:00";
              if ($('#sttime').val() != '') {
                  sttime = Date.parse(new Date(sttimestr)) / 1000;
              } else {
                  sttime = 0;
              }
              var endtime = 0;
              var endtimestr = $('#endtime').val() + " 23:59:59";
              if ($('#endtime').val() != '') {
                  endtime = Date.parse(new Date(endtimestr)) / 1000;
              } else {
                  endtime = 0;
              }

              location.href = "selection_plan_one_manual.php?id=" + '<?php echo $id;?>' + "&sttime=" + sttime + "&endtime=" + endtime;
          });

          // 手动输入价格时清除选中
          $(document).on('click','.price_input',function(){
              $(this).parent().parent().find(".inputing").attr("checked",false);

              // 锅炉表单
              $(this).parent("td").find("#guolu_add_price").val("");
              $(this).parent("td").parent("tr").find("#choose_guolu_price").val("");

              // 其他项表单
              $(this).parent("td").find("#other_add_price").val("");
              $(this).parent("td").parent("tr").find("#choose_other_price").val("");
          });

          /*如果选择价格框则自定义价格的文本框清空*/
          $(document).on('click','.inputing',function(){

              var val = $(this).val();

              // 锅炉表单
              $(this).parent("td").parent("tr").find(".price_input").val("");
              $(this).parent("td").parent("tr").find("#choose_guolu_price").val(val);

              // 其他项表单
              $(this).parent("td").parent("tr").find(".price_input").val("");
              $(this).parent("td").parent("tr").find("#choose_other_price").val(val);
          });

          // 清除
          $(".clearAll").click(function(){
              $(this).parent().parent().find(".inputing").attr("checked",false);
              $(this).parent().find(".price_input").val("");
          });

          //// 添加一行锅炉报价
          //$('.addgl').click(function () {
          //    var guoluArr = $(".guolu");
          //    var guolu_exist_ids_arr = [];
          //    for(var i = 0; i < guoluArr.length; i++){
          //        var guolu_currentDom = guoluArr[i];
          //        var guoluId = $(guolu_currentDom).find("#gid").val();// 获取每一行的gid
          //        guolu_exist_ids_arr.push(guoluId);
          //    }
          //    <?php
          //    $guolu_list = Guolu_attr::getList(0, '', 0, '', '', '', '');
          //    ?>
          //    var all_guolu_list = new Array();
          //    all_guolu_list = <?php //echo json_encode($guolu_list) ?>//;
          //    var show_guolu_list = [];
          //    for (var j = 0 ; j < all_guolu_list.length; j++){
          //        var isExist = false;
          //        for (var k = 0; k < guolu_exist_ids_arr.length; k++ ){
          //            if (all_guolu_list[j]['guolu_id'] == guolu_exist_ids_arr[k]) {
          //                isExist = true;
          //            }
          //        }
          //        if (!isExist){
          //            show_guolu_list.push(all_guolu_list[j]);
          //        }
          //    }
          //
          //    var guolu_version_html = "";
          //    for (var l = 0; l < show_guolu_list.length; l++){
          //        guolu_version_html += "<option value='" + show_guolu_list[l]['guolu_id'] + "'>" + show_guolu_list[l]['guolu_version'] + "</option>";
          //    }
          //    var NHtml = '<tr  class="guolu">' +
          //                    '<td class="center">锅炉</td>'+
          //                    '<td class="center"> '+
          //                        '<select type="text" class="GLXXmain_3 guolu_version" id="guolu_version" >'+
          //                              '<option value="0">请选择锅炉型号</option>' + guolu_version_html +
          //                        '</select>'+
          //                    '</td>'+
          //                    '<td class="center">'+
          //                          '<input style="margin:2px" class="GLXXmain_3" type="number" value="" id="guolu_num"/>'+
          //                    '</td>'+
          //                    '<td class="center">'+
          //                          '<input style="margin:2px" class="GLXXmain_3" type="text" value="" id="guolu_context"/>'+
          //                    '</td>'+
          //                    '<td class="guolu_maxprice">'+
          //                          '<input type="radio" name="" value="0"  class="inputing" />0'+
          //                    '</td>'+
          //                    '<td class="guolu_minprice">'+
          //                          '<input type="radio" name="" value="0" class="inputing" />0'+
          //                    '</td>'+
          //                    '<td class="guolu_avgprice">'+
          //                          '<input type="radio" name="" value="0" class="inputing" />0'+
          //                    '</td>'+
          //                    '<td class="guolu_newprice">'+
          //                          '<input type="radio" name="" value="0" class="inputing" />0'+
          //                    '</td>'+
          //                    '<td class="defined guolu_addprice" style="width:35px;">'+
          //                          '<input type="number" name="textprice" id="guolu_add_price" value="" class="price_input" style="max-width:55%;"  />' +
          //                    '<span id="delete_guolu" class="mougl" style="color: red"> 删除 </span>'+
          //                    '</td>'+
          //                '</tr>';
          //
          //    $(this).parent().parent().find('.guolu:nth-last-child(1)').after(NHtml);
          //
          //});

          // 添加一行锅炉报价(含厂家)
          $('.addgl').click(function () {

              var vender_list = new Array();
              vender_list = <?php echo json_encode($guolu_vender_list) ?>;
              var guolu_vender_html = "";
              for (var l = 0; l < vender_list.length; l++){
                  guolu_vender_html += "<option value='" + vender_list[l]['id'] + "'>" + vender_list[l]['name'] + "</option>";
              }

              var NHtml = '<tr  class="guolu">' +
                  '<td class="center">锅炉</td>'+
                  '<td class="center">'+
                  '<select type="text" class="GLXXmain_3 guolu_vender" id="guolu_vender" >'+
                  '<option value="0">请选择厂家</option>' + guolu_vender_html +
                  '</select>'+
                  '</td>'+
                  '<td class="center"> '+
                  '<select type="text" class="GLXXmain_3 guolu_version" id="guolu_version" >'+
                  '<option value="0">暂无可选型号</option>'+
                  '</select>'+
                  '</td>'+
                  '<td class="center">'+
                  '<input style="margin:2px" class="GLXXmain_3" type="number" value="" id="guolu_num"/>'+
                  '</td>'+
                  '<td class="center">'+
                  '<input style="margin:2px" class="GLXXmain_3" type="text" value="" id="guolu_context"/>'+
                  '</td>'+
                  '<td class="guolu_maxprice">'+
                  '<input type="radio" name="" value="0"  class="inputing" />0'+
                  '</td>'+
                  '<td class="guolu_minprice">'+
                  '<input type="radio" name="" value="0" class="inputing" />0'+
                  '</td>'+
                  '<td class="guolu_avgprice">'+
                  '<input type="radio" name="" value="0" class="inputing" />0'+
                  '</td>'+
                  '<td class="guolu_newprice">'+
                  '<input type="radio" name="" value="0" class="inputing" />0'+
                  '</td>'+
                  '<td class="defined guolu_addprice" style="width:35px;">'+
                  '<input type="number" name="textprice" id="guolu_add_price" value="" class="price_input" style="max-width:55%;"  />' +
                  '<span id="delete_guolu" class="mougl" style="color: red"> 删除 </span>'+
                  '</td>'+
                  '</tr>';

              $(this).parent().parent().find('.guolu:nth-last-child(1)').after(NHtml);

          });


          // 删除一行锅炉报价
          $(document).on('click','.mougl',function(){
              $(this).parent().parent().remove();

          });

          // 添加一行辅机报价
          $('.addfj').click(function () {
              var newht = $(this).parent().parent().find('.fuji_list_new').html();
              var NHtml = '<tr  class="fuji_list">' + newht + '</tr>';
              $(this).parent().parent().find('.fuji_list:nth-last-child(1)').after(NHtml);
          });

          // 删除一行辅机报价
          $(document).on('click','.moufj',function(){
              $(this).parent().parent().remove();
          });

          // 添加一行其他项报价
          $('.add_other').click(function () {
              var newht = $(this).parent().parent().find('.other_new').html();
              var NHtml = '<tr  class="other">' + newht + '</tr>';
              $(this).parent().parent().find('.other:nth-last-child(1)').after(NHtml);
          });

          // 删除一行其他项报价
          $(document).on('click','.mou_other',function(){
              var len = $(".mou_other").length;
              var len = parseFloat(len);
              if (len > 1) {
                  $(this).parent().parent().remove();
              }
          });

          // 下一步
          $('#btn_submit_next').on('click',function () {

              // 锅炉
              /*对锅炉表单中获取选择的价格和自定义价格作比较最终输出一个*/
              var guoluArr = $(".guolu");
              var guolu_dataArr = [];
              var guolu_origin_length = 0;
              guolu_origin_length = <?php echo count($guolu_ids) ?>;
              for (var i = 0; i < guoluArr.length; i++) {
                  var guolu_currentDom = guoluArr[i];
                  var guoluVal = $(guolu_currentDom).find("#guolu_add_price").val();//自定义价格

                  var guolu_chooseVal = $(guolu_currentDom).find("#choose_guolu_price").val();//选择的价格
                  var guolu_currentVal = guoluVal != "" ? guoluVal : guolu_chooseVal;//最后得到的数据

                  if (guolu_currentVal == ""){
                      layer.alert("请选择锅炉价格！",{icon:5});
                      return false;
                  }else if(guolu_currentVal < 0){
                      layer.alert("锅炉价格不能小于0！",{icon:5});
                      return false;
                  }

                  var guolu_currentId = $(guolu_currentDom).find("#gid").val();// 获取每一行的锅炉id
                  var guolu_attrId = $(guolu_currentDom).find("#attrid").val();// 获取每一行的attrid
                  var guolu_currentNum = 0;
                  var guolu_currentVersion = 0;
                  var guolu_currentContext = "";

                  if (i >= guolu_origin_length){
                      guolu_currentNum = $(guolu_currentDom).find("#guolu_num").val();// 获取每一行的锅炉数量
                      guolu_currentVersion = $(guolu_currentDom).find("#guolu_version option:selected").text();// 获取每一行的锅炉型号
                      if ($(guolu_currentDom).find("#guolu_version option:selected").val() == 0){
                          layer.alert("请选择锅炉型号！",{icon:5});
                          return false;
                      }
                      guolu_currentContext = $(guolu_currentDom).find("#guolu_context").val();// 获取每一行的锅炉备注
                  } else {
                      guolu_currentNum = $(guolu_currentDom).find("#guolu_num").data("value");// 获取每一行的锅炉数量
                      guolu_currentVersion = $(guolu_currentDom).find("#guolu_version").data("value");// 获取每一行的锅炉型号
                      guolu_currentContext = $(guolu_currentDom).find("#guolu_context").data("value");// 获取每一行的锅炉备注
                  }

                  if (guolu_currentNum == null || guolu_currentNum < 1){
                      layer.alert("请输入锅炉数量！",{icon:5});
                      return false;
                  }

                  var guolu_currentStr = guolu_currentId + "||" + guolu_currentNum + "||" + guolu_currentContext + "||"
                      + guolu_currentVersion  + "||" + guolu_currentVal + "||" + guolu_attrId;
                  // 将每一行的id和价格以及文本框中的价格拼接成字符串

                  guolu_dataArr.push(guolu_currentStr);
              }
              var guolu_dataStr = guolu_dataArr.join("#");


              // 辅机价格
              var fuji_sel_Arr = $(".fuji_list");
              var fuji_total_length = fuji_sel_Arr.length;
              var fuji_origin_length = 0;
              fuji_origin_length = <?php echo  count($fujilist) ?>;
              var fuji_sel_dataArr = [];
              var fuji_sel_dataStr = "";
              var fuji_new_dataArr = [];
              var fuji_new_dataStr = "";
              if (fuji_sel_Arr.length > 0) {
                  for (var i = 0; i < fuji_total_length; i++) {

                      var fuji_sel_currentDom = fuji_sel_Arr[i];
                      var fuji_current_value = $(fuji_sel_currentDom).find("#fuji_add_price").val();//自定义价格

                      if (fuji_current_value == "") {
                          layer.alert("请输入辅机价格！",{icon:5});
                          return false;
                      }else if(fuji_current_value < 0){
                          layer.alert("辅机价格不能小于0！",{icon:5});
                          return false;
                      }

                      // 后台新增辅机
                      if (i >= fuji_origin_length ){

                          var fuji_new_currentName = $(fuji_sel_currentDom).find("#fuji_name").val();
                          var fuji_new_currentVersion = $(fuji_sel_currentDom).find("#fuji_version").val();
                          var fuji_new_currentNum = $(fuji_sel_currentDom).find("#fuji_num").val();
                          var fuji_new_currentContext = $(fuji_sel_currentDom).find("#fuji_context").val();


                          var fuji_new_currentStr = "";

                          if (fuji_new_currentName != null && fuji_new_currentName != ""){
                              fuji_new_currentStr = fuji_new_currentStr + fuji_new_currentName + "||";
                          }else {
                              layer.alert("请输入辅机名称！",{icon:5});
                              return false;
                          }

                          if (fuji_new_currentVersion != null && fuji_new_currentVersion != ""){
                              fuji_new_currentStr = fuji_new_currentStr + fuji_new_currentVersion + "||";
                          }else {
                              layer.alert("请输入辅机型号！",{icon:5});
                              return false;
                          }

                          if (fuji_new_currentNum != null && fuji_new_currentNum != "" && fuji_new_currentNum > 0){
                              fuji_new_currentStr = fuji_new_currentStr + fuji_new_currentNum + "||";
                          }else {
                              layer.alert("请输入合适的辅机数量！",{icon:5});
                              return false;
                          }
                          fuji_new_currentStr = fuji_new_currentStr + fuji_new_currentContext + "||" + fuji_current_value;
                          fuji_new_dataArr.push(fuji_new_currentStr);

                      }else {

                          var fuji_sel_currentId = $(fuji_sel_currentDom).find("#fuji_id").val();// 获取每一行的辅机纪录id
                          var fuji_sel_currentStr = fuji_sel_currentId + "||" + fuji_current_value;
                          //将每一行的id和价格以及文本框中的价格拼接成字符串
                          fuji_sel_dataArr.push(fuji_sel_currentStr);
                      }
                  }
                  fuji_sel_dataStr = fuji_sel_dataArr.join("#");
                  fuji_new_dataStr = fuji_new_dataArr.join("#");
              }


              // 其他项价格
              var otherArr = $(".other");
              var dataArr = [];
              var other_dataStr = "";
              var other_origin_length = 0;
              other_origin_length = <?php echo  count($rows) ?>;
              var other_new_dataArr = [];
              var other_new_dataStr = "";
              if (otherArr.length > 0){
                  for (var i = 0; i < otherArr.length; i++) {
                      var other_currentDom = otherArr[i];
                      var otherVal = $(other_currentDom).find("#other_add_price").val();//自定义价格
                      var chooseVal = $(other_currentDom).find("#choose_other_price").val();//选择的价格
                      var currentVal = otherVal != "" ? otherVal : chooseVal;//最后得到的数据


                      if (i < other_origin_length) {

                          if (currentVal == "" || currentVal == undefined) {
                              continue;
                          }else if (currentVal < 0){
                              layer.alert("其他项价格不能小于0！",{icon:5});
                              return false;
                          }

                          var other_currentName = $(other_currentDom).find("#other_name").data("value");
                          var other_currentNum = $(other_currentDom).find("#other_num").data("value");
                          var other_currentVersion = $(other_currentDom).find("#other_version").data("value");
                          var other_currentContext = $(other_currentDom).find("#other_context").val();

                          if (other_currentVersion == undefined){
                              other_currentVersion = "";
                          }

                          var currentId = $(other_currentDom).find("#aid").val();//获取每一行的id

                          var currentStr = other_currentName + "||" + other_currentVersion + "||" + other_currentNum + "||" +
                              other_currentContext + "||" + currentVal + "||" + currentId;
                          //将每一行的id和价格以及文本框中的价格拼接成字符串

                          dataArr.push(currentStr);
                      }else {
                          var other_new_currentName = $(other_currentDom).find("#other_name").val();
                          var other_new_currentNum = $(other_currentDom).find("#other_num").val();
                          var other_new_currentVersion = $(other_currentDom).find("#other_version").val();
                          var other_new_currentContext = $(other_currentDom).find("#other_context").val();

                          if (other_new_currentName == null || other_new_currentName == ""){
                              layer.alert("请输入其他项名称！",{icon:5});
                              return false;
                          }

                          if (other_new_currentNum == null || other_new_currentNum == ""){
                              layer.alert("请输入其他项数量！",{icon:5});
                              return false;
                          }

                          if (other_new_currentVersion == null || other_new_currentVersion == ""){
                              layer.alert("请输入其他项型号！",{icon:5});
                              return false;
                          }

                          if (currentVal == null || currentVal == ""){
                              layer.alert("请输入其他项价格！",{icon:5});
                              return false;
                          }else if (currentVal < 0){
                              layer.alert("其他项价格不能小于0！",{icon:5});
                              return false;
                          }

                          var currentNewStr = other_new_currentName + "||" + other_new_currentVersion + "||" + other_new_currentNum + "||" +
                              other_new_currentContext + "||" + currentVal;

                          other_new_dataArr.push(currentNewStr);

                      }

                  }
                  other_dataStr = dataArr.join("#");
                  other_new_dataStr = other_new_dataArr.join("#");
              }

              // 添加选型方案
              $.ajax({
                  type        : 'POST',
                  data        : {
                      is_update : <?php echo $isUpdate;?>,
                      guolu_dataStr : guolu_dataStr,
                      fuji_sel_dataStr:fuji_sel_dataStr,
                      fuji_new_dataStr:fuji_new_dataStr,
                      other_dataStr  : other_dataStr,
                      other_new_dataStr : other_new_dataStr,
                      id  : <?php echo $id;?>
                  },
                  dataType :    'json',
                  url :         'selection_do.php?act=add_price_manual',
                  success :     function(data){

                      var code = data.code;
                      var msg  = data.msg;
                      switch(code){
                          case 1:
                              window.location.href = 'selection_plan_two.php?id=<?php echo $id;?>';
                              break;
                          default:
                              layer.alert(msg, {icon: 5});
                      }
                  }
              });
              //}
          });

          // 获取指定商家的锅炉列表
          $(document).on('change','.guolu_vender',function(){
              var vender = $(this).val();
              var versionDom = $(this).parent().parent().find(".guolu_version");
              versionDom.html("<option value='0'>暂无可选型号</option>");

              var maxPriceDom = $(this).parent().parent().find(".guolu_maxprice");
              var minPriceDom = $(this).parent().parent().find(".guolu_minprice");
              var avgPriceDom = $(this).parent().parent().find(".guolu_avgprice");
              var newPriceDom = $(this).parent().parent().find(".guolu_newprice");
              var addPriceDom = $(this).parent().parent().find(".guolu_addprice");

              if(vender != 0) {
                  var select_length = 0;
                  select_length = <?php echo $origin_guolu_list_length ?>;

                  var guoluArr = $(".guolu");
                  var guolu_exist_ids_arr = [];  // 存储已有锅炉型号
                  for(var i = 0; i < guoluArr.length; i++){
                      var guolu_currentDom = guoluArr[i];
                      var venderId = 0;

                      if(i >= select_length){
                          // 手动增加的锅炉型号
                          venderId = $(guolu_currentDom).find("#guolu_vender option:selected").val();// 获取每一行的厂家id
                      }else {
                          // 选型页面增加的锅炉型号
                          venderId = $(guolu_currentDom).find("#guolu_vender").data("value");// 获取每一行的厂家id
                      }
                      if(venderId == vender){
                          // 将同一个厂家的锅炉型号收集起来
                          var guoluId = $(guolu_currentDom).find("#gid").val();// 获取每一行的gid
                          guolu_exist_ids_arr.push(guoluId);
                      }
                  }
                  $.ajax({
                      type: 'POST',
                      data: {
                          vender: vender
                      },
                      dataType: 'json',
                      url: 'selection_do.php?act=get_guolu_list_vender',
                      success: function (data) {
                          var code = data.code;
                          var msg = data.msg;
                          var guolu_list = data.data;
                          switch (code) {
                              case 1:
                                  // 在这里先清空已选的锅炉类型，再更新锅炉类型单选框可选个数
                                  var html = "";

                                  var show_guolu_list = [];
                                  for (var j = 0; j < guolu_list.length; j++) {
                                      var isExist = false;
                                      for (var k = 0; k < guolu_exist_ids_arr.length; k++) {
                                          if (guolu_list[j]['guolu_id'] == guolu_exist_ids_arr[k]) {
                                              isExist = true;
                                          }
                                      }
                                      if (!isExist) {
                                          show_guolu_list.push(guolu_list[j]);
                                      }
                                  }

                                  for (var i = 0; i < show_guolu_list.length; i++) {
                                      var guolu_version = show_guolu_list[i].guolu_version;
                                      var value = show_guolu_list[i].guolu_id;
                                      html += "<option value='" + value + "'>" + guolu_version + "</option>";
                                  }
                                  versionDom.html(html);
                                  if (show_guolu_list != null && show_guolu_list.length >= 1) {
                                      getGuoluPrice(show_guolu_list[0].guolu_id, maxPriceDom, minPriceDom, avgPriceDom, newPriceDom, addPriceDom);
                                  }else {
                                      versionDom.html("<option value='0'>暂无可选型号</option>");
                                      getGuoluPrice(0, maxPriceDom, minPriceDom, avgPriceDom, newPriceDom, addPriceDom);
                                  }
                                  break;

                              default:
                                  break;
                          }
                      }
                  });
              }else {
                  getGuoluPrice(0,maxPriceDom,minPriceDom,avgPriceDom,newPriceDom,addPriceDom);
              }
          });

          // 锅炉id发生变化时获取其历史价格
          $(document).on('change','.guolu_version',function(){
              var guolu_id = $(this).val();
              var maxPriceDom = $(this).parent().parent().find(".guolu_maxprice");
              var minPriceDom = $(this).parent().parent().find(".guolu_minprice");
              var avgPriceDom = $(this).parent().parent().find(".guolu_avgprice");
              var newPriceDom = $(this).parent().parent().find(".guolu_newprice");
              var addPriceDom = $(this).parent().parent().find(".guolu_addprice");
              getGuoluPrice(guolu_id, maxPriceDom, minPriceDom, avgPriceDom, newPriceDom, addPriceDom);
          });

          // 获取指定型号锅炉的历史价格
          function getGuoluPrice(my_guolu_id,mMaxPriceDom,mMinPriceDom,mAvgPriceDom,mNewPriceDom,mAddPriceDom) {

              var guolu_id = my_guolu_id;
              var guoluArr = $(".guolu");
              var count = 0;
              for(var i = 0; i < guoluArr.length; i++){
                  var guolu_currentDom = guoluArr[i];
                  var guoluId = $(guolu_currentDom).find("#guolu_version").val();// 获取每一行的gid
                  if(guoluId == guolu_id){
                      count++;
                  }
              }
              if(count > 1){
                  alert("当前已经存在该型号的锅炉！");
                  guolu_id = 0;
              }

              var maxPriceDom = mMaxPriceDom;
              var minPriceDom = mMinPriceDom;
              var avgPriceDom = mAvgPriceDom;
              var newPriceDom = mNewPriceDom;
              var addPriceDom = mAddPriceDom;

              if (guolu_id != 0 && guolu_id != undefined) {
                  $.ajax({
                      type: 'POST',
                      data: {
                          guolu_id: guolu_id
                      },
                      dataType: 'json',
                      url: 'selection_do.php?act=get_guolu_price',
                      success: function (data) {
                          var code = data.code;
                          var msg = data.msg;
                          var guoluItem = data.data;
                          switch (code) {
                              case 1:
                                  // 更新价格
                                  var maxPrice = (guoluItem["countarr"]['maxprice'] == null) ? 0 : parseFloat(guoluItem["countarr"]['maxprice']);
                                  var maxPriceDomHtml = "<input type=\"radio\" name=\"price_guolu_" + guoluItem["guoluinfo"]["id"]
                                      + "\" value = \"" + maxPrice + "\" class=\"inputing\"/>" + maxPrice;
                                  maxPriceDom.html(maxPriceDomHtml);

                                  var minPrice = (guoluItem["countarr"]['minprice'] == null) ? 0 : parseFloat(guoluItem["countarr"]['minprice']);
                                  var minPriceDomHtml = "<input type=\"radio\" name=\"price_guolu_" + guoluItem["guoluinfo"]["id"]
                                      + "\" value = \"" + minPrice + "\" class=\"inputing\"/>" + minPrice;
                                  minPriceDom.html(minPriceDomHtml);

                                  var avgPrice = (guoluItem["countarr"]['avgprice'] == null) ? 0 : parseFloat(guoluItem["countarr"]['avgprice']);
                                  avgPrice = avgPrice.toFixed(2);
                                  var avgPriceDomHtml = "<input type=\"radio\" name=\"price_guolu_" + guoluItem["guoluinfo"]["id"]
                                      + "\" value = \"" + avgPrice + "\" class=\"inputing\"/>" + avgPrice;
                                  avgPriceDom.html(avgPriceDomHtml);

                                  var newPrice = (guoluItem["prices"][0] == null) ? 0 : parseFloat(guoluItem["prices"][0]['price']);
                                  var newPriceDomHtml = "<input type=\"radio\" name=\"price_guolu_" + guoluItem["guoluinfo"]["id"]
                                      + "\" value = \"" + newPrice + "\" class=\"inputing\"/>" + newPrice;
                                  newPriceDom.html(newPriceDomHtml);

                                  var guoluid = (guoluItem["guoluinfo"]['id'] == null) ? 0 : parseInt(guoluItem["guoluinfo"]['id']);
                                  var proid = (guoluItem["guoluinfo"]['proid'] == null) ? 0 : parseInt(guoluItem["guoluinfo"]['proid']);
                                  var addInputOneHtml = "<input type=\"number\" name=\"guolu_add_price\" value =\"\" " +
                                      "id=\"guolu_add_price\" style=\"max-width:55%;\" class=\"price_input\"/>";
                                  var addInputTwoHtml = "<input type=\"hidden\" name=\"choose_guolu_price\" id=\"choose_guolu_price\" value =\"\" />";
                                  var addInputThreeHtml = "<input type=\"hidden\" name=\"textprice\" value =\"" + proid + "\" id=\"attrid\" />";
                                  var addInputFourHtml = "<input type=\"hidden\" name=\"textprice\" value =\"" + guoluid + "\" id=\"gid\" />";
                                  var addInputFiveHtml = "<span id=\"delete_guolu\" class=\"mougl\" style=\"color: red\"> 删除 </span>";
                                  addPriceDom.html(addInputOneHtml + addInputTwoHtml + addInputThreeHtml + addInputFourHtml + addInputFiveHtml);

                                  break;

                              default:
                                  alert(msg);
                                  break;
                          }
                      }
                  });
              }
              else {
                  var maxPriceDomHtml = "<input type=\"radio\" name=\"\" value = \"" + 0 + "\" class=\"inputing\"/>" + 0;
                  maxPriceDom.html(maxPriceDomHtml);

                  var minPriceDomHtml = "<input type=\"radio\" name=\"\" value = \"" + 0 + "\" class=\"inputing\"/>" + 0;
                  minPriceDom.html(minPriceDomHtml);

                  var avgPriceDomHtml = "<input type=\"radio\" name=\"\" value = \"" + 0 + "\" class=\"inputing\"/>" + 0;
                  avgPriceDom.html(avgPriceDomHtml);

                  var newPriceDomHtml = "<input type=\"radio\" name=\"\" value = \"" + 0 + "\" class=\"inputing\"/>" + 0;
                  newPriceDom.html(newPriceDomHtml);
              }
          }


    </script>

</body>
</html>
