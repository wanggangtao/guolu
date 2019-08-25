<?php

/**
 * table_selection_history.class.php 数据库表:选型历史表
 *
 * @version       v0.01
 * @createtime    2018/7/18
 * @updatetime    2018/7/18
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Table_selection_history extends Table {


    private static  $pre = "history_";

    static protected function struct($data){
        $r = array();
        $r['id']                   = $data['history_id'];
        $r['project_id']                   = $data['history_project_id'];
        $r['customer']             = $data['history_customer'];
        $r['guolu_position']       = $data['history_guolu_position'];
        $r['underground_unm']      = $data['history_underground_unm'];
        $r['guolu_height']         = $data['history_guolu_height'];
        $r['is_condensate']        = $data['history_is_condensate'];
        $r['is_lownitrogen']       = $data['history_is_lownitrogen'];
        $r['application']          = $data['history_application'];
        $r['heating_type']         = $data['history_heating_type'];
        $r['water_type']           = $data['history_water_type'];
        $r['guolu_id']             = $data['history_guolu_id'];
        $r['guolu_num']            = $data['history_guolu_num'];
        $r['guolu_context']        = $data['history_guolu_context'];
        $r['total_exchange_q']     = $data['history_total_exchange_q'];
        $r['remark']               = $data['history_remark'];
        $r['user']                 = $data['history_user'];
        $r['status']                 = $data['history_status'];
        $r['type']                 = $data['history_type'];
        $r['area_num_nuan_qi']     = $data['history_area_num_nuan_qi'];
        $r['area_num_water']       = $data['history_area_num_water'];
        $r['board_power']          = $data['history_board_power'];
        $r['hm_heating_type']      = $data['history_hm_heating_type'];

        $r['guolu_context']             = $data['history_guolu_context'];
        //锅炉的热负荷值
        $r['guolu_attr']             = $data['history_guolu_attr'];
        $r['water_box_attr']             = $data['history_water_box_attr'];


        $r['addtime']              = $data['history_addtime'];
        $r['lastupdate']           = $data['history_lastupdate'];

        return $r;
    }



    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"=>"number",
            "project_id"=>"number",
            "customer"=>"string",
            "guolu_position"=>"number",
            "underground_unm"=>"number",
            "guolu_height"=>"number",
            "is_condensate"=>"number",
            "is_lownitrogen"=>"number",
            "application"=>"number",
            "heating_type"=>"number",
            "water_type"=>"number",
            "guolu_id"=>"string",
            "guolu_num"=>"string",
            "guolu_context"=>"string",
            "total_exchange_q"=>"string",
            "remark"=>"string",
            "status"=>"number",
            "user"=>"number",
            "type"=>'number',
            "area_num_nuanqi"=>'number',
            "area_num_water"=>'number',
            "board_power"=>'number',
            "hm_heating_type"=>"number",
            "guolu_attr"=>"number",
            "water_box_attr"=>"string",
            "addtime"=>"number",
            "lastupdate"=>"number"
        );

        return isset($typeAttr[$attr])?$typeAttr[$attr]:$typeAttr;
    }



    static public function add($attrs){

        global $mypdo;

        $params = array();
        foreach ($attrs as $key=>$value)
        {
            $type = self::getTypeByAttr($key);
            $params[self::$pre.$key] =  array($type,$value);

        }

        $r = $mypdo->sqlinsert($mypdo->prefix.'selection_history', $params);
        return $r;
    }


    static public function update($id,$attrs){

        global $mypdo;

        $params = array();
        foreach ($attrs as $key=>$value)
        {
            $type = self::getTypeByAttr($key);
            $params[self::$pre.$key] =  array($type,$value);

        }
        //where条件
        $where = array(
            "history_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'selection_history', $params, $where);
        return $r;
    }

    /**
     * Table_selection_history::getInfoById() 根据ID获取详情
     *
     * @param Integer $id  ID
     *
     * @return
     */
    static public function getInfoById($id){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));

        $sql = "select * from ".$mypdo->prefix."selection_history where history_id = $id limit 1";

        $rs = $mypdo->sqlQuery($sql);
        $r  = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
            return $r[0];
        }else{
            return $r;
        }
    }

    /**
     * Table_selection_history::del() 根据ID删除数据
     *
     * @param Integer $id  项目ID
     *
     * @return
     */
    static public function del($id){

        global $mypdo;

        $where = array(
            "history_id" => array('number', $id)
        );

        return $mypdo->sqldelete($mypdo->prefix.'selection_history', $where);
    }

    /**
     * Table_project::getPageList() 根据条件列出所有信息
     * @param number $page             起始页，$page为0时取出全部
     * @param number $pagesize         页面大小
     * @param number $count            0数量1列表
     * @param string $customer         客户名称
     * @param number $user             所属人
     * @param number $status           状态
     * @return
     */
    static public function getPageList($page, $pageSize, $count, $customer, $user, $status){
        global $mypdo;

        $where = " where 1=1 ";
        if($customer){
            $customer = $mypdo->sql_check_input(array('string', "%".$customer."%"));
            $where .= " and history_customer like $customer ";
        }
        if($user){
            $user = $mypdo->sql_check_input(array('number', $user));
            $where .= " and history_user = $user ";
        }
        if($status > -1){
            $status = $mypdo->sql_check_input(array('number', $status));
            $where .= " and history_status = $status ";
        }

        if($count == 0){
            $sql = "select count(1) as ct from ".$mypdo->prefix."selection_history".$where;
            $rs = $mypdo->sqlQuery($sql);
            return $rs[0]['ct'];
        }else{
            $sql = "select * from ".$mypdo->prefix."selection_history".$where;
            $sql .=" order by history_lastupdate desc";

            $limit = "";
            if(!empty($page)){
                $start = ($page - 1)*$pageSize;
                $limit = " limit {$start},{$pageSize}";
            }

            $sql .= $limit;
            $rs = $mypdo->sqlQuery($sql);
            $r = array();
            if($rs){
                foreach($rs as $key => $val){
                    $r[$key] = self::struct($val);
                }
            }

            return $r;
        }
    }

    static public function getListByStatus(){
        global $mypdo;


        $where = " where history_status != 10 ";


        $where .= " order by history_id desc";


        $sql = "select * from ".$mypdo->prefix."selection_history".$where;

        $rs = $mypdo->sqlQuery($sql);

        $r = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
        }
        return $r;
    }


}
?>