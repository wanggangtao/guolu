<?php

/**
 * case_pricelog.class.php 选型价格类
 *
 * @version       v0.01
 * @create time   2018/10/20
 * @update time   2018/10/20
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Selection_plan
{

    //tab_type
    const BOILER_TAB_TYPE = 0;  //锅炉
    const HEATING_FUJI_TAB_TYPE = 1;   //采暖辅机
    const WATER_FUJI_TAB_TYPE = 2 ;  //热水辅机
    const OTHER_TAB_TYPE =  3;  //其他项
    const ADD_FUJI_TAB_TYPE = 4;   //手动添加辅机

    public function __construct()
    {

    }

    /**
     * Selection_plan::add() 添加
     * @param $attrs
     * @return mixed
     */
    static public function add($attrs)
    {
        if (empty($attrs)) throw new Exception('参数不能为空', 102);
        $id = Table_selection_plan::add($attrs);

        return $id;
    }
    static public function delByHistoryId($hid){
        return Table_selection_plan::delByHistoryId($hid);
    }

    static public function getInfoByHistoryidandTabtype($hisid,$tab_type){
        return Table_selection_plan::getInfoByHistoryidandTabtype($hisid,$tab_type);
    }

    static public function getInfoByHistoryId($hisid){
        return Table_selection_plan::getInfoByHistoryId($hisid);
    }

    static public function getInfoById($id){
        return Table_selection_plan::getInfoById($id);
    }

    static public function getInfoByHidandTabtypeandAttrid($hisid,$tab_type,$attrid){
        return Table_selection_plan::getInfoByHidandTabtypeandAttrid($hisid,$tab_type,$attrid);
    }

    static public function getListByHidandTabtype($hisid,$tab_type){
        return Table_selection_plan::getListByHidandTabtype($hisid,$tab_type);
    }
    //通过history_id获取所有plan
    static public function findFujiByHistory($hisid){
        return Table_selection_plan::findFujiByHistory($hisid);
    }

    static public function update($id, $attrs){
        return Table_selection_plan::update($id, $attrs);
    }
}
