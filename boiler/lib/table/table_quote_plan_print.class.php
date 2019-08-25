<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/13
 * Time: 21:27
 */

class Table_quote_plan_print extends Table {


    static protected function struct($data){
        $r = array();
        $r['history_id']                   = $data['device_history_id'];
        $r['pro_id']                   = $data['device_pro_id'];
        $r['name']                   = $data['device_name'];
        $r['size']                  = $data['device_size'];
        $r['remark']                  = $data['device_remark'];
        $r['number']                = $data['device_number'];
        $r['price']             =  $data['device_price'] ;
        $r['account']            =  $data['device_account'] ;

        return $r;
    }



    /**
     * 获取报价方案列表-锅炉
     * @return array|mixed
     * @throws Exception
     */
    static public function getQuoteListBoiler($historyId){
        global $mypdo;
        $sql = "SELECT  
                    ht.history_id as device_history_id,
                    pl.plan_attrid as device_pro_id,
                    concat(dict_lowc.dict_name,dict_cond.dict_name,'锅炉')  as device_name,
                      concat(gl.guolu_version,',效率',gl.guolu_heateffi_30,'%,供热量',gl.guolu_ratedpower,'KW' ) as device_size,
                      IFNULL( pl.plan_remark,'') as device_remark,
                      concat(IFNULL(pl.plan_nums,'0'), '台') as device_number,
                      IFNULL(round(pl.plan_pro_price,2),0.00) as device_price,
                      IFNULL(round(IFNULL(pl.plan_nums,0)*pl.plan_pro_price,2),0.00) as device_account
        
                FROM boiler_selection_plan as pl
                    LEFT JOIN boiler_guolu_attr as gl 
                            ON pl.plan_attrid = gl.guolu_id
                    LEFT JOIN boiler_dict as dict_cond
                            ON gl.guolu_is_condensate = dict_cond.dict_id
                    LEFT JOIN boiler_dict as dict_lowc
                         ON gl.guolu_is_lownitrogen = dict_lowc.dict_id
                    LEFT JOIN boiler_dict as dict_fact
                         ON gl.guolu_vender = dict_fact.dict_id
                     LEFT join boiler_selection_history as ht
                     ON pl.plan_history_id = ht.history_id
                         
                WHERE pl.plan_tab_type = ".Selection_plan::BOILER_TAB_TYPE." AND ht.history_id= $historyId";
        $rs = $mypdo->sqlQuery($sql);

        $r = array();
        if($rs){
            foreach($rs as $key => $val){
                //设置数量
//                $boiler_history_id = $val['device_history_id'];
//                $history = Selection_history::getInfoById($boiler_history_id);
//                $guolu_id = $val['device_pro_id'];
//                $guolu_ids = explode(",",$history['guolu_id']);
//                $guolu_nums = explode(",",$history['guolu_num']);
//                if(in_array($guolu_id,$guolu_ids)){
//                    $guolu_num = $guolu_nums[array_search($guolu_id,$guolu_ids)];
//                    $val['device_number'] = $guolu_num."台";     //锅炉数量
//                    $val['device_account'] = $guolu_num*$val['device_price'] == null ? 0.00 : round($guolu_num*$val['device_price'],2);
//                }
                $r[$key] = self::struct($val);
            }
        }
        return $r;
    }
    /**
     * 获取报价方案列表-辅机(弃用)
     * @return array|mixed
     * @throws Exception
     */
    static public function getQuoteListFuji_old($historyId){
        global $mypdo;
        $fujiList = Selection_plan::findFujiByHistory($historyId);
        $rs_array = array();
        foreach ($fujiList as $key => $value){
            $arr = array();
            $arr['history_id'] = self::struct($value['history_id']);
            //判断--该辅机是已存辅机
            if(isset($value['modelid'])){
                $attr_model = Products_model::getInfoById($value['modelid']);
                $attr_table_name = $mypdo->prefix.$attr_model['attrname'];
                $arr['name'] = isset($attr_model['name'])?$attr_model['name']:"";
                $table_name_array = explode("_",$attr_table_name);
                $table_primary_id = $table_name_array[count($table_name_array)-2].'_id';
                $table_vender = $table_name_array[count($table_name_array)-2].'_vender';
                $table_version= $table_name_array[count($table_name_array)-2].'_version';
                $select_table_sql = "select attr_tb.".$table_vender." as vender , attr_tb.".$table_version." as version from ".
                                        $mypdo->prefix."selection_plan as plan left join ".$attr_table_name."
                                         as attr_tb on plan.plan_attrid = attr_tb.".$table_primary_id;
                $vender_version = $mypdo->sqlQuery($select_table_sql);
                $arr['size'] = isset($vender_version[0]['vender'])?$vender_version[0]['vender']:"";
                $arr['factory'] = isset($vender_version[0]['version'])?$vender_version[0]['version']:"" ;
            }
            //判断--该辅机是新增辅机
            else{
                $arr['name'] = isset($value['equ_name'])?$value['equ_name']:"";
                $arr['size'] = isset($value['version'])?$value['version']:"";
                $arr['factory'] =  '(新增项无厂家)' ;
            }
            $arr['pro_id'] = (null == $value['attrid'])? 0 : $value['attrid'];
            $arr['number'] = $value['nums'];
            $arr['price'] = $value['pro_price'];
            $arr['account'] =(null == $arr['number']*$arr['price'])? 0 : $arr['number']*$arr['price'];
            $rs_array[$key] = $arr;
        }
        return $rs_array;
    }
    /**
     * 获取报价方案列表-辅机
     * @return array|mixed
     * @throws Exception
     */
    static public function getQuoteListFuji($historyId){
        global $mypdo;
        $sql = "SELECT 		plan.plan_history_id as device_history_id,
                      IFNULL(plan.plan_attrid,'') as device_pro_id,
                      IFNULL(plan.plan_equ_name,fj.fuji_name)   as device_name,
                     IFNULL(plan.plan_version,( CASE fj.fuji_data_type
                          WHEN 1  THEN fj.fuji_version_show
                          WHEN 2  THEN fj.fuji_value
                          end 
                         ))     as device_size,
		                IFNULL(plan.plan_remark,fj.fuji_context) as device_remark,
	                    concat(IFNULL(plan.plan_nums,fj.fuji_num),'台')	as device_number,
		                IFNULL(round(plan.plan_pro_price,2),0.00) as device_price,
		                IFNULL(round(plan.plan_pro_price*IFNULL(plan.plan_nums,fj.fuji_num),2),0.00) as device_account
                FROM 
	                  boiler_selection_plan as plan
	                  LEFT JOIN boiler_selection_fuji as fj
	                        ON plan.plan_attrid = fj.fuji_id 
                                AND plan.plan_history_id = fj.fuji_history_id

                WHERE plan.plan_history_id =  $historyId 
	                    AND plan.plan_tab_type IN 
	                    (".Selection_plan::HEATING_FUJI_TAB_TYPE.",".Selection_plan::WATER_FUJI_TAB_TYPE.",".Selection_plan::ADD_FUJI_TAB_TYPE.")
    
                 ";

        $rs = $mypdo->sqlQuery($sql);
        $r = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
        }
        return $r;
    }

    /**
     * 获取报价方案列表-其他
     * @param $historyId
     * @return array
     */
    static public function getQuoteListOther($historyId){
        global $mypdo;
        $sql = "SELECT  pl.plan_history_id as device_history_id,
                        IFNULL(pl.plan_attrid,'') as device_pro_id,
                        IFNULL(pl.plan_equ_name,'') as device_name,
                         IFNULL(pl.plan_version,'') as device_size,
                        IFNULL(pl.plan_remark,'') as device_remark,
                        concat(IFNULL(pl.plan_nums,0),'项')as device_number,
                        IFNULL(round(pl.plan_pro_price,2),0.00) as device_price,
                        IFNULL(round(pl.plan_pro_price*pl.plan_nums,2),0.00) as device_account
                FROM boiler_selection_plan as pl
                WHERE pl.plan_history_id = $historyId  AND pl.plan_tab_type = 3 
        ";
        $rs = $mypdo->sqlQuery($sql);
        $r = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
        }
        return $r;
    }

    /**
     * 获取报价方案列表-其他(弃用)
     * @param $historyId
     * @return array
     */
    static public function getQuoteListOther_old($historyId){
        global $mypdo;
        $sql = "SELECT  pl.plan_history_id as device_history_id,
                        IFNULL(pl.plan_attrid,'') as device_pro_id,
                        IFNULL(tpl.tpl_name,'') as device_name,
                         IFNULL('-','') as device_size,
                        IFNULL('-','') as device_factory,
                       '1项'	as device_number,
                        IFNULL(pl.plan_pro_price,'') as device_price,
                        IFNULL(pl.plan_pro_price,'') as device_account
                FROM boiler_selection_plan as pl
                    LEFT JOIN boiler_case_tpl as tpl
                        ON pl.plan_pro_id = tpl.tpl_id
                    LEFT JOIN boiler_case_tplcontent as cont
                        ON tpl.tpl_id = cont.tplcontent_tplid
                        
                WHERE pl.plan_history_id = $historyId AND tpl.tpl_attrid = 13  AND pl.plan_tab_type = 3
        ";
        $rs = $mypdo->sqlQuery($sql);
        $r = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
        }
        return $r;
    }

    /**
     * 人民币小写转大写
     *
     * @param string $number 数值
     * @param string $int_unit 币种单位，默认"元"，有的需求可能为"圆"
     * @param bool $is_round 是否对小数进行四舍五入
     * @param bool $is_extra_zero 是否对整数部分以0结尾，小数存在的数字附加0,比如1960.30
     * @return string
     */
    static  public function  rmb_format($money = 0, $int_unit = '元', $is_round = true, $is_extra_zero = false) {
        // 将数字切分成两段
        $parts = explode ( '.', $money, 2 );
        $int = isset ( $parts [0] ) ? strval ( $parts [0] ) : '0';
        $dec = isset ( $parts [1] ) ? strval ( $parts [1] ) : '';

        // 如果小数点后多于2位，不四舍五入就直接截，否则就处理
        $dec_len = strlen ( $dec );
        if (isset ( $parts [1] ) && $dec_len > 2) {
            $dec = $is_round ? substr ( strrchr ( strval ( round ( floatval ( "0." . $dec ), 2 ) ), '.' ), 1 ) : substr ( $parts [1], 0, 2 );
        }

        // 当number为0.001时，小数点后的金额为0元
        if (empty ( $int ) && empty ( $dec )) {
            return '零';
        }

        // 定义
        $chs = array ('0', '壹', '贰', '叁', '肆', '伍', '陆', '柒', '捌', '玖' );
        $uni = array ('', '拾', '佰', '仟' );
        $dec_uni = array ('角', '分' );
        $exp = array ('', '万' );
        $res = '';

        // 整数部分从右向左找
        for($i = strlen ( $int ) - 1, $k = 0; $i >= 0; $k ++) {
            $str = '';
            // 按照中文读写习惯，每4个字为一段进行转化，i一直在减
            for($j = 0; $j < 4 && $i >= 0; $j ++, $i --) {
                $u = $int {$i} > 0 ? $uni [$j] : ''; // 非0的数字后面添加单位
                $str = $chs [$int {$i}] . $u . $str;
            }
            $str = rtrim ( $str, '0' ); // 去掉末尾的0
            $str = preg_replace ( "/0+/", "零", $str ); // 替换多个连续的0
            if (! isset ( $exp [$k] )) {
                $exp [$k] = $exp [$k - 2] . '亿'; // 构建单位
            }
            $u2 = $str != '' ? $exp [$k] : '';
            $res = $str . $u2 . $res;
        }

        // 如果小数部分处理完之后是00，需要处理下
        $dec = rtrim ( $dec, '0' );
        // 小数部分从左向右找
        if (! empty ( $dec )) {
            $res .= $int_unit;

            // 是否要在整数部分以0结尾的数字后附加0，有的系统有这要求
            if ($is_extra_zero) {
                if (substr ( $int, - 1 ) === '0') {
                    $res .= '零';
                }
            }

            for($i = 0, $cnt = strlen ( $dec ); $i < $cnt; $i ++) {
                $u = $dec {$i} > 0 ? $dec_uni [$i] : ''; // 非0的数字后面添加单位
                $res .= $chs [$dec {$i}] . $u;
                if ($cnt == 1)
                    $res .= '整';
            }

            $res = rtrim ( $res, '0' ); // 去掉末尾的0
            $res = preg_replace ( "/0+/", "零", $res ); // 替换多个连续的0
        } else {
            $res .= $int_unit . '整';
        }
        return $res;
    }


}
?>